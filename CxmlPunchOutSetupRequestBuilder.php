<?php

/**
 * CxmlPunchOutSetupRequestBuilder builds a PunchOutSetupRequest document.
 *
 * @author Brian Newsham
 *
 */
class CxmlPunchOutSetupRequestBuilder extends CxmlRequestBuilder
{
    /**
     * Value of the deploymentMode on the Request element.
     *
     * @var string
     */
    protected $Request_deploymentMode;

    /**
     * Value of the operation attribute on the PunchOutSetupRequest element.
     *
     * @var string
     */
    protected $PunchOutSetupRequest_operation;

    /**
     * Array of Extrinsic key/value pairs.
     *
     * @var array
     */
    protected $extrinsics;

    /**
     * Value of the BuyerCookie element.
     *
     * @var string
     */
    protected $BuyerCookie;

    /**
     * Value of the BrowserFormPost/URL element.
     *
     * @var string
     */
    protected $BrowserFormPost_URL;

    /**
     * Value of the SupplierSetup/URL element.
     *
     * @var string
     */
    protected $SupplierSetup_URL;

    /**
     * ShipTo data.
     *
     * @var CxmlAddress
     */
    protected $ShipTo;

    /**
     * An array with SupplierPartID, and SupplierPartAuxiliaryID keys for the selected item.
     *
     * @var array
     */
    protected $SelectedItem;

    /**
     * Array of data to generate ItemOut elements.
     *
     * @var array
     */
    protected $ItemOuts = array();

    /**
     * Constructor.
     *
     * @param string $version Version of cXML that will be referenced in the header.
     */
    public function __construct($version = null)
    {
        parent::__construct($version);
        $this->Request_deploymentMode = null;
        $this->PunchOutSetupRequest_operation = null;
        $this->extrinsics = array();
        $this->BuyerCookie = null;
        $this->BrowserFormPost_URL = null;
        $this->SupplierSetup_URL = null;
        $this->ShipTo = null;
        $this->SelectedItem = null;
        $this->ItemOuts = array();
    }

    /**
     * Set the 'deploymentMode' attribute on the Request element.
     * Can be 'production' or 'test'.
     *
     * @param string $deployment_mode
     * @return CxmlPunchOutSetupRequestBuilder
     */
    public function setDeploymentMode($deployment_mode)
    {
        $this->Request_deploymentMode = $deployment_mode;
        return $this;
    }

    /**
     * Set the 'operation' attribute on the PunchOutSetupRequest element.
     * Can be one of 'create', 'edit', 'inspect', or 'source'.
     *
     * @param string $operation
     * @return CxmlPunchOutSetupRequestBuilder
     */
    public function setOperation($operation)
    {
        $this->PunchOutSetupRequest_operation = $operation;
        return $this;
    }

    /**
     * Set the BuyerCookie element.
     *
     * @param string $buyer_cookie
     * @return CxmlPunchOutSetupRequestBuilder
     */
    public function setBuyerCookie($buyer_cookie)
    {
        $this->BuyerCookie = $buyer_cookie;
        return $this;
    }

    /**
     * Set the BrowserFormPost/URL element.
     *
     * @param string $url
     * @return CxmlPunchOutSetupRequestBuilder
     */
    public function setBrowserFormPostURL($url)
    {
        $this->BrowserFormPost_URL = $url;
        return $this;
    }

    /**
     * Set the SupplierSetup/URL element.
     *
     * @param string $url
     * @return CxmlPunchOutSetupRequestBuilder
     */
    public function setSupplierSetupURL($url)
    {
        $this->SupplierSetup_URL = $url;
        return $this;
    }

    /**
     * Set the ShipTo data.
     *
     * @param CxmlAddress $ship_to
     * @return CxmlPunchOutSetupRequestBuilder
     */
    public function setShipTo($ship_to)
    {
        $this->ShipTo = $ship_to;
        return $this;
    }

    /**
     * Set the selected item data.
     *
     * @param string $supplier_part_id
     * @param string $supplier_part_auxiliary_id
     * @return CxmlPunchOutSetupRequestBuilder
     */
    public function setSelectedItem($supplier_part_id, $supplier_part_auxiliary_id = null)
    {
        $this->SelectedItem = array(
            'SupplierPartID' => $supplier_part_id,
            'SupplierPartAuxiliaryID' => $supplier_part_auxiliary_id,
        );
        return $this;
    }

    /**
     * Add an ItemOut to the PunchOutSetupRequest.
     *
     * @param CxmlItemOut $item_out
     * @return CxmlPunchOutSetupRequestBuilder
     */
    public function addItem($item_out)
    {
        $this->ItemOuts[] = $item_out;
        return $this;
    }

    /**
     * Add an Extrinsic element.
     *
     * @param string $name
     * @param string $value
     * @return CxmlPunchOutSetupRequestBuilder
     */
    public function addExtrinsic($name, $value)
    {
        $this->extrinsics[$name] = $value;
        return $this;
    }

    /**
     * Add multiple Extrinsic elements.
     *
     * @param array $extrinsics Array formatted as array(name => value)
     * @return CxmlPunchOutSetupRequestBuilder
     */
    public function addExtrinsics($extrinsics)
    {
        $this->extrinsics = array_merge($this->extrinsics, $extrinsics);
        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see CxmlBuilder::getResult()
     * @return CxmlPunchOutSetupRequest
     */
    public function getResult()
    {
        $this->buildHeader();
        $this->buildRequest();

        return new CxmlPunchOutSetupRequest($this->simple_xml_element);
    }

    /**
     * Builds the request portion of the document.
     *
     */
    protected function buildRequest()
    {
        $Request = $this->simple_xml_element->addChild('Request');
        $Request['deploymentMode'] = $this->Request_deploymentMode;
        $posr = $Request->addChild('PunchOutSetupRequest');
        $posr['operation'] = $this->PunchOutSetupRequest_operation;
        $posr->addChild('BuyerCookie', $this->BuyerCookie);
        foreach ($this->extrinsics as $name => $value) {
            $extrinsic = $posr->addChild('Extrinsic', $value);
            $extrinsic['name'] = $name;
        }
        $BrowserFormPost = $posr->addChild('BrowserFormPost');
        $BrowserFormPost->addChild('URL', $this->BrowserFormPost_URL);
        $SupplierSetup = $posr->addChild('SupplierSetup');
        $SupplierSetup->addChild('URL', $this->SupplierSetup_URL);
        if (! is_null($this->ShipTo)) {
            $this->buildAddress($posr, 'ShipTo', $this->ShipTo);
        }
        if (! is_null($this->SelectedItem)) {
            $SelectedItem = $posr->addChild('SelectedItem');
            $ItemID = $SelectedItem->addChild('ItemID');
            $ItemID->addChild('SupplierPartID', $this->SelectedItem['SupplierPartID']);
            if (strlen($this->SelectedItem['SupplierPartAuxiliaryID'])) {
                $ItemID->addChild('SupplierPartAuxiliaryID', $this->SelectedItem['SupplierPartAuxiliaryID']);
            }
        }
        if (count($this->ItemOuts)) {
            foreach ($this->ItemOuts as $item_out) {
                $this->buildItemOut($posr, $item_out);
            }
        }
    }

    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Build the PunchOutSetupRequest document.
     *
     * @param CxmlPunchOutSetupRequest $request
     * @param CxmlEndpoint $endpoint
     * @return CxmlPunchOutSetupRequest
     */
    public function build(CxmlPunchOutSetupRequest $request, CxmlEndpoint $endpoint)
    {
        $request->setFromCredentials($endpoint->from_identity, $endpoint->from_domain);
        $request->setToCredentials($endpoint->to_identity, $endpoint->to_domain);
        $request->setSenderCredentials(
            $endpoint->sender_identity,
            $endpoint->sender_shared_secret,
            $endpoint->sender_domain
        );
        $request->deploymentMode = $endpoint->deployment_mode;
        $request->operation = $endpoint->operation;
        $request->UserAgent = 'Krackeler.com';
        $request->BuyerCookie = 'KSI.' . mt_rand(1, 999999);

        $extrinsics = json_decode($endpoint->extrinsics);
        if (!is_null($extrinsics)) {
            foreach ($extrinsics as $name => $value) {
                $request->addExtrinsic($name, $value);
            }
        }
        /*
        $contacts = json_decode($endpoint->contacts);
        if ( ! is_null($contacts)) {
            $doc->addContact($contacts);
        }
        */
        $ship_to = json_decode($endpoint->ship_to);
        if (!is_null($ship_to)) {
            $request->setShipTo($ship_to);
        }

        $request->setBrowserFormPostUrl($this->browser_form_post);

        /*
        foreach ($options->extrinsics as $name => $value) {
            $doc->addExtrinsic($name, $value);
        }
        $doc->addExtrinsic('FirstName', 'Brian');
        $doc->addExtrinsic('LastName', 'Newsham');
        $doc->addExtrinsic('UniqueName', 'catalog_tester');
        $doc->addExtrinsic('CostCenter', '670');
        $doc->addExtrinsic('UserEmail', 'sigma.cxml@krackeler.com');
        $doc->setBuyerCookie('ABC123');

        $ship_to = new stdClass();
        //$ship_to->addressID = '26';
        $ship_to->Name = 'Test Address';
        //$ship_to->name = '_5uicbb';
        $ship_to->DeliverTo = 'Brian Newsham';
        $ship_to->Street = '57 Broadway';
        $ship_to->City = 'Albany';
        $ship_to->State = 'NY';
        $ship_to->PostalCode = '12202';
        $ship_to->Country = 'United States';
        $doc->setShipTo($ship_to);

        //$doc->setSelectedItem('VC00021');
        //$doc->setSelectedItem('VC00021', 'CartId`68581692~ConfigurationID`2996891');
        //echo $doc;
        */
        return $request;
    }
}
