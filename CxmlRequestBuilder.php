<?php

/**
 * CxmlRequestBuilder is the abstract base class for the Request family of documents.
 *
 * @author Brian Newsham
 *
 */
abstract class CxmlRequestBuilder extends CxmlBuilder
{

    protected $From_Identity;

    protected $From_domain;

    protected $To_Identity;

    protected $To_domain;

    protected $Sender_Identity;

    protected $Sender_domain;

    protected $Sender_SharedSecret;

    protected $Sender_UserAgent;

    protected $lineNumber;

    /**
     * Constructor.
     *
     * @param string $version
     *            Version of cXML that will be referenced in the header.
     */
    public function __construct($version = null)
    {
        parent::__construct($version);
        $this->lineNumber = 0;
    }

    /**
     * Set the From Credentials.
     *
     * @param string $identity
     *            Value of the Identity element.
     * @param string $domain
     *            Value of the Credential[domain] attribute.
     */
    public function setFromCredentials($identity, $domain = 'NetworkID')
    {
        $this->From_Identity = $identity;
        $this->From_domain = $domain;

        return $this;
    }

    /**
     * Set the To Credentials.
     *
     * @param string $identity
     *            Value of the Identity element.
     * @param string $domain
     *            Value of the Credential[domain] attribute.
     */
    public function setToCredentials($identity, $domain = 'NetworkID')
    {
        $this->To_Identity = $identity;
        $this->To_domain = $domain;

        return $this;
    }

    /**
     * Set the Sender Credentials.
     *
     * @param string $identity
     *            Value of the Identity element.
     * @param string $shared_secret
     *            Value of the SharedSecret element.
     * @param string $domain
     *            Value of the Credential[domain] attribute.
     */
    public function setSenderCredentials($identity, $shared_secret = null, $domain = 'NetworkID')
    {
        $this->Sender_Identity = $identity;
        $this->Sender_SharedSecret = $shared_secret;
        $this->Sender_domain = $domain;

        return $this;
    }

    /**
     * Set the Sender UserAgent string.
     *
     * @param string $user_agent
     */
    public function setSenderUserAgent($user_agent)
    {
        $this->Sender_UserAgent = $user_agent;

        return $this;
    }

    /**
     * Construct the common Header elements.
     */
    protected function buildHeader()
    {
        $Header = $this->simple_xml_element->addChild('Header');
        $From = $Header->addChild('From');
        $To = $Header->addChild('To');
        $Sender = $Header->addChild('Sender');

        $From_Credential = $From->addChild('Credential');
        $From_Credential['domain'] = $this->From_domain;
        $From_Credential->addChild('Identity', $this->From_Identity);

        $To_Credential = $To->addChild('Credential');
        $To_Credential['domain'] = $this->To_domain;
        $To_Credential->addChild('Identity', $this->To_Identity);

        $Sender_Credential = $Sender->addChild('Credential');
        $Sender_Credential['domain'] = $this->Sender_domain;
        $Sender_Credential->addChild('Identity', $this->Sender_Identity);
        if (! is_null($this->Sender_SharedSecret)) {
            $Sender_Credential->addChild('SharedSecret', $this->Sender_SharedSecret);
        }
        $Sender->addChild('UserAgent', $this->Sender_UserAgent);
    }

    /**
     * Build the ItemOut element, and add it to $el.
     *
     * @param SimpleXMLElement $el
     * @param CxmlItemOut $ItemOut
     */
    protected function buildItemOut($el, $ItemOut)
    {
        $child = $el->addChild('ItemOut');
        $child['lineNumber'] = ++ $this->lineNumber;
        $child['quantity'] = $ItemOut['quantity'];

        /*
         * Build ItemID element.
         */
        $el_ItemID = $child->addChild('ItemID');
        $el_ItemID->addChild('SupplierPartID', $ItemOut['SupplierPartID']);
        if (isset($ItemOut['SupplierPartAuxiliaryID'])) {
            $el_ItemID->addChild('SupplierPartAuxiliaryID', $ItemOut['SupplierPartAuxiliaryID']);
        }

        /*
         * Build ItemDetail element.
         */
        $el_ItemDetail = $child->addChild('ItemDetail');
        $this->buildMoney($el_ItemDetail, 'UnitPrice', $ItemOut['UnitPrice']);

        $Description = $el_ItemDetail->addChild(
            'Description',
            XmlHelper::encodeNumericEntity(XmlHelper::escape($ItemOut['Description']), 'UTF-8')
        );
        $Description['xml:lang'] = 'en';
        if (isset($ItemOut['Description_ShortName'])) {
            $Description->addChild('ShortName', $ItemOut['Description_ShortName']);
        }

        $el_ItemDetail->addChild('UnitOfMeasure', $ItemOut['UnitOfMeasure']);

        if (isset($ItemOut['UNSPSC'])) {
            $Classification = $el_ItemDetail->addChild('Classification', $ItemOut['UNSPSC']);
            $Classification['domain'] = 'UNSPSC';
        }
        if (isset($ItemOut['ManufacturerPartID'])) {
            $el_ItemDetail->addChild('ManufacturerPartID', $ItemOut['ManufacturerPartID']);
        }
        if (isset($ItemOut['ManufacturerName'])) {
            $el_ItemDetail->addChild('ManufacturerName', $ItemOut['ManufacturerName']);
        }
        if (isset($ItemOut['URL'])) {
            $el_ItemDetail->addChild('URL', $ItemOut['URL']);
        }
        if (isset($ItemOut['Extrinsics'])) {
            foreach ($ItemOut['Extrinsics'] as $name => $value) {
                $Extrinsic = $el_ItemDetail->addChild('Extrinsic', $value);
                $Extrinsic['name'] = $name;
            }
        }

        /*
         * Build Shipping element.
         */
        if (isset($ItemOut['Shipping']) && ! empty($ItemOut['Shipping'])) {
            $Shipping = $this->buildMoney($child, 'Shipping', $ItemOut['Shipping']);
            $Description = $Shipping->addChild('Description', $ItemOut['Shipping_Description']);
            $Description['xml:lang'] = 'en';
        }

        /*
         * Build Tax element.
         */
        if (isset($ItemOut['Tax']) && ! empty($ItemOut['Tax'])) {
            $Tax = $this->buildMoney($child, 'Tax', $ItemOut['Tax']);
            $Description = $Tax->addChild('Description', $ItemOut['Tax_Description']);
            $Description['xml:lang'] = 'en';
        }
    }

    /**
     * Build a money element structure.
     *
     * @param SimpleXMLElement $el
     * @param string $el_name
     * @param float $Money
     * @param string $currency
     */
    protected function buildMoney($el, $el_name, $Money, $currency = 'USD')
    {
        $child = $el->addChild($el_name);
        $el_money = $child->addChild('Money', Cxml::currency($Money));
        $el_money['currency'] = $currency;
        return $child;
    }

    /**
     * Build an address element.
     *
     * @param SimpleXMLElement $el
     * @param string $el_name
     * @param CxmlAddress $data
     */
    protected function buildAddress($el, $el_name, $data)
    {
        $Address_Parent = $el->addChild($el_name);
        $Address = $Address_Parent->addChild('Address');
        if (isset($data->addressID)) {
            $Address['addressID'] = $data->addressID;
        }
        if (isset($data->Name)) {
            $Name = $Address->addChild('Name', $data->Name);
            $Name['xml:lang'] = 'en';
        }
        $PostalAddress = $Address->addChild('PostalAddress');
        if (isset($data->name)) {
            $PostalAddress['name'] = $data->name;
        }
        foreach ($data->DeliverTo as $deliverTo) {
            $PostalAddress->addChild('DeliverTo', $deliverTo);
        }
        foreach ($data->Street as $street) {
            $PostalAddress->addChild('Street', $street);
        }
        $PostalAddress->addChild('City', $data->City);
        $PostalAddress->addChild('State', $data->State);
        $PostalAddress->addChild('PostalCode', $data->PostalCode);
        $Country = $PostalAddress->addChild('Country', $data->Country);
        $Country['isoCountryCode'] = $data->Country_isoCountryCode;
    }
}
