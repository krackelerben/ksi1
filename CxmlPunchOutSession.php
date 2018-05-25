<?php

/**
 * CxmlPunchOutSession initializes a PunchOut session with a remote end-point.
 *
 * @author Brian Newsham
 *
 */
class CxmlPunchOutSession
{
    /**
     * cXML Archiver
     *
     * @var CxmlArchiver
     */
    protected $archiver;

    /**
     * Buyer Cookie.
     *
     * @var string
     */
    protected $buyer_cookie;

    /**
     * cXML PunchOut Dispatcher.
     *
     * @var CxmlCurlDispatcher
     */
    protected $dispatcher;

    /**
     * cXML End-point configuration.
     *
     * @var CxmlEndpoint
     */
    protected $endpoint;

    /**
     * cXML PunchOutSetupRequest builder.
     *
     * @var CxmlPunchOutSetupRequestBuilder
     */
    protected $builder;

    /**
     * cXML PunchOutSetupRequest document.
     *
     * @var CxmlPunchOutSetupRequest
     */
    protected $request;

    /**
     * cXML PunchOutSetupResponse document.
     *
     * @var CxmlPunchOutSetupResponse
     */
    protected $response;

    /**
     * The current logged in user.
     *
     * @var KWebUser
     */
    protected $user;

    /**
     * Generates a BuyerCookie string.
     *
     * @return string A unique string
     */
    public static function generateBuyerCookie()
    {
        return uniqid('KRACKELER-');
    }

    /**
     * Constructor.
     *
     * @param CxmlArchiver $archiver
     * @param string $buyer_cookie
     * @param CxmlCurlDispatcher $dispatcher
     * @param CxmlEndpoint $endpoint
     * @param CxmlPunchOutSetupRequestBuilder $builder
     * @param KWebUser $user
     */
    public function __construct($archiver, $buyer_cookie, $dispatcher, $endpoint, $builder, $user)
    {
        $this->archiver = $archiver;
        $this->buyer_cookie = $buyer_cookie;
        $this->dispatcher = $dispatcher;
        $this->endpoint = $endpoint;
        $this->builder = $builder;
        $this->user = $user;
        $this->request = null;
        $this->response = null;
    }

    /**
     * Build the request with input from the end point.
     *
     * @param CxmlEndpoint $endpoint
     * @param CxmlPunchOutSetupRequestBuilder $builder
     */
    public function buildPunchOutSetupRequest($endpoint, $builder)
    {
        $builder->setRootAttributes();
        $builder->setFromCredentials($endpoint->from_identity, $endpoint->from_domain);
        $builder->setToCredentials($endpoint->to_identity, $endpoint->to_domain);
        $builder->setSenderCredentials(
            $endpoint->sender_identity,
            $endpoint->sender_shared_secret,
            $endpoint->sender_domain
        );
        $builder->setDeploymentMode($endpoint->deployment_mode);
        $builder->setSenderUserAgent('Krackeler.com');
        $builder->setBuyerCookie($this->buyer_cookie);

        $extrinsics = json_decode($endpoint->extrinsics);

        if (!is_null($extrinsics)) {
            foreach ($extrinsics as $name => $value) {
                $builder->addExtrinsic($name, $this->expand($value));
            }
        }
        /*
        $contacts = json_decode($endpoint->contacts);
        if ( ! is_null($contacts)) {
            $builder->addContact($contacts);
        }
        */
        $ship_to = json_decode($endpoint->ship_to);
        if (!is_null($ship_to)) {
            $builder->setShipTo($ship_to);
        }

        return new CxmlPunchOutSetupRequest($builder->getResult());
    }

    /**
     * Replace markup tags with content.
     *
     * @param string $str
     * @return string
     */
    public function expand($str)
    {
        return preg_replace_callback(
            '|\[\[(.+)\.(.+)\]\]|',
            array($this, 'replaceMarkup'),
            $str
        );
    }

    /**
     * Helper function for expand($str) to replace markup
     * tags with content.
     *
     * @param array $matches
     * @return string
     */
    protected function replaceMarkup($matches)
    {
        $verb = $matches[1];
        $param = $matches[2];

        switch ($verb)
        {
            case 'user':
                return Yii::app()->user->getUserField($param);
                break;
            default:
                return '[[' . $verb . ':' . $param . ']]';
                break;
        }
    }

    /**
     * Create a new PunchOut session with a remote website.
     *
     * @return CxmlPunchOutSetupResponse
     */
    public function createPunchOut()
    {
        $this->builder->setOperation(CxmlPunchOutSetupRequest::OPERATION_CREATE);
        $this->request = $this->buildPunchOutSetupRequest($this->endpoint, $this->builder);
        $this->response = $this->dispatcher->dispatch($this->endpoint, $this->request);
        $this->archiver->addDocument($this->request);
        $this->archiver->addDocument($this->response);

        return $this->response;
    }

    /**
     * Edit an existing PunchOut session with a remote website.
     *
     * @param $poom CxmlPunchOutOrderMessage
     * @return CxmlPunchOutSetupResponse
     */
    public function editPunchOut($poom)
    {
        $this->builder->setOperation(CxmlPunchOutSetupRequest::OPERATION_EDIT);
        $item_ins = $poom->getItemIterator();
        foreach ($item_ins as $item_in) {
            $this->builder->addItem(
                CxmlItemOut::fromArray(
                    [
                        'quantity' => $item_in['quantity'],
                        'SupplierPartID' => $item_in['SupplierPartID'],
                        'SupplierPartAuxiliaryID' => $item_in['SupplierPartAuxiliaryID']
                    ]
                )
            );
        }
        $this->request = $this->buildPunchOutSetupRequest($this->endpoint, $this->builder);
        $this->response = $this->dispatcher->dispatch($this->endpoint, $this->request);
        $this->archiver->addDocument($this->request);
        $this->archiver->addDocument($this->response);

        return $this->response;
    }

    /**
     * Receive the shopping cart from a remote website.
     *
     * @param CxmlPunchOutOrderMessage $poom
     */
    public function endPunchOut($poom)
    {
        $this->archiver->addDocument($poom);
    }

    /**
     * Cancel a PunchOut session.
     *
     */
    public function cancelPunchOut()
    {

    }

    /**
     * Builds a CxmlOrderRequest model.
     *
     * @param CxmlEndpoint $endpoint
     * @param CxmlOrderRequestBuilder $builder
     */
    protected function buildOrderRequest($endpoint, $builder)
    {
        $builder->setRootAttributes();
        $builder->setFromCredentials($endpoint->from_identity, $endpoint->from_domain);
        $builder->setToCredentials($endpoint->to_identity, $endpoint->to_domain);
        $builder->setSenderCredentials(
            $endpoint->sender_identity,
            $endpoint->sender_shared_secret,
            $endpoint->from_domain
        );
        $builder->setSenderUserAgent('Krackeler.com');
        $builder->setDeploymentMode($endpoint->deployment_mode);
        $builder->setOrderDate(Cxml::date());
        $builder->setOrderType('regular');
        $builder->setType('new');

        return new CxmlOrderRequest($builder->getResult());
    }

    /**
     * Send an OrderRequest document.
     *
     * @return CxmlOrderResponse
     */
    public function sendOrderRequest()
    {
        $this->request = $this->buildOrderRequest($this->endpoint, $this->builder);
        $this->response = $this->dispatcher->dispatch($this->endpoint, $this->request);
        $this->archiver->addDocument($this->request);
        $this->archiver->addDocument($this->response);

        return $this->response;
    }

    /**
     * Send the request, and return the start page URL.
     *
     * @return string|null
     */
    public function run()
    {
        $this->request = $this->builder->build($this->endpoint);
        $this->response = $this->dispatcher->dispatch($this->endpoint, $this->request);
        /**
         * TODO: Log the request and response.
         */
        $archiver = new CxmlArchiver(
            $this->endpoint,
            Yii::app()->user,
            $this->request->buyer_cookie
        );
        $archiver->addDocument($this->request);
        $archiver->addDocument($this->response);

        if (Cxml::STATUS_OK == $this->response->status_code) {
            return $this->response->start_page_url;
        } else {
            // log the error
        }
        return null;
    }

    /**
     * Get the request document.
     *
     * @return CxmlPunchOutSetupRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Get the response document.
     *
     * @return CxmlPunchOutSetupResponse
     */
    public function getResponse()
    {
        return $this->response;
    }
}
