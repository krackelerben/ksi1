<?php

/**
 * CxmlProfileResponseBuilder builds a ProfileResponse document.
 *
 * @author Brian Newsham
 *
 */
class CxmlProfileResponseBuilder extends CxmlResponseBuilder
{

    /**
     * effectiveDate date.
     *
     * @see CxmlProfileResponse::$effectiveDate
     * @var string
     */
    protected $effectiveDate;

    /**
     * lastRefresh date.
     *
     * @see CxmlProfileResponse::$lastRefresh
     * @var string
     */
    protected $lastRefresh;

    /**
     * Array of supported transactions.
     *
     * @var array
     */
    protected $transactions;

    /**
     * Constructor.
     *
     * @param string $version
     *            Version of cXML that will be referenced in the header.
     */
    public function __construct($version = null)
    {
        parent::__construct($version);
        $this->effectiveDate = null;
        $this->lastRefresh = null;
        $this->transactions = array();
    }

    /**
     * Set the effectiveDate attribute.
     *
     * @param string $date
     */
    public function setEffectiveDate($date)
    {
        $this->effectiveDate = $date;

        return $this;
    }

    /**
     * Set the lastRefresh attribute.
     *
     * @param string $date
     */
    public function setLastRefresh($date)
    {
        $this->lastRefresh = $date;

        return $this;
    }

    /**
     * Add supported transaction details.
     *
     * @param string $requestName
     * @param string $URL
     */
    public function addTransaction($requestName, $URL, $options = null)
    {
        $transaction = array(
            'requestName' => $requestName,
            'URL' => $URL
        );
        if (is_array($options)) {
            $transaction['options'] = $options;
        }
        $this->transactions[] = $transaction;

        return $this;
    }

    /**
     * (non-PHPdoc)
     *
     * @see CxmlBuilder::getResult()
     */
    public function getResult()
    {
        $Response = $this->simple_xml_element->addChild('Response');
        $Status = $Response->addChild('Status', $this->Status);
        $Status['code'] = $this->Status_code;
        $Status['text'] = $this->Status_text;
        $ProfileResponse = $Response->addChild('ProfileResponse');
        if (! is_null($this->effectiveDate)) {
            $ProfileResponse['effectiveDate'] = $this->effectiveDate;
        }
        if (! is_null($this->lastRefresh)) {
            $ProfileResponse['lastRefresh'] = $this->lastRefresh;
        }
        foreach ($this->transactions as $transaction) {
            $this->buildTransaction($ProfileResponse, $transaction);
        }

        return new CxmlProfileResponse($this->simple_xml_element);
    }

    /**
     * Build the Transaction element and add it to the ProfileResponse.
     *
     * @param SimpleXMLElement $profile_response
     * @param array $transaction
     */
    protected function buildTransaction($profile_response, $transaction)
    {
        $el = $profile_response->addChild('Transaction');
        $el['requestName'] = $transaction['requestName'];
        $el->addChild('URL', $transaction['URL']);
        if (array_key_exists('options', $transaction)) {
            foreach ($transaction['options'] as $name => $value) {
                $Option = $el->addChild('Option', $value);
                $Option['name'] = $name;
            }
        }
    }
}
