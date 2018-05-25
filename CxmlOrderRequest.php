<?php

/**
 * CxmlOrderRequest class represents a cXML OrderRequest document.
 *
 * @author Brian Newsham
 *
 * @property string $deploymentMode (production | test) defaults to production.
 * @property string $orderDate
 * @property string $orderID
 * @property string $orderType
 * @property float $Shipping
 * @property string $Shipping_Description
 * @property float $Tax
 * @property string $Tax_Description
 * @property string $type
 * @property float $Total
 * @property string $UserAgent
 */
class CxmlOrderRequest extends CxmlDocument
{

    const DEPLOY_TEST = 'test';

    const DEPLOY_PRODUCTION = 'production';

    const ORDER_TYPE_RELEASE = 'release';

    const ORDER_TYPE_REGULAR = 'regular';

    const TYPE_NEW = 'new';

    const TYPE_UPDATE = 'update';

    const TYPE_DELETE = 'delete';

    /**
     * lineNumber counter.
     *
     * @var int
     */
    private $lineNumber = 0;

    /**
     * Magic getter.
     *
     * @param string $key
     * @throws Exception
     */
    public function __get($key)
    {
        switch ($key) {
            case 'deploymentMode':
                $deploymentMode = $this->simple_xml_element->Request['deploymentMode'];
                return is_null($deploymentMode) ? self::DEPLOY_PRODUCTION : $deploymentMode->__toString();
            case 'orderDate':
                return $this->simple_xml_element->Request->OrderRequest->OrderRequestHeader['orderDate'];
            case 'orderID':
                return $this->simple_xml_element->Request->OrderRequest->OrderRequestHeader['orderID'];
            case 'orderType':
                return $this->simple_xml_element->Request->OrderRequest->OrderRequestHeader['orderType'];
            case 'Shipping':
                return $this->simple_xml_element->Request->OrderRequest->OrderRequestHeader->Shipping->Money;
            case 'Shipping_Description':
                return $this->simple_xml_element->Request->OrderRequest->OrderRequestHeader->Shipping->Description;
            case 'Tax':
                return $this->simple_xml_element->Request->OrderRequest->OrderRequestHeader->Tax->Money;
            case 'Tax_Description':
                return $this->simple_xml_element->Request->OrderRequest->OrderRequestHeader->Tax->Description;
            case 'type':
                return $this->simple_xml_element->Request->OrderRequest->OrderRequestHeader['type'];
            case 'Total':
                return $this->simple_xml_element->Request->OrderRequest->OrderRequestHeader->Total->Money;
            case 'UserAgent':
                return $this->simple_xml_element->Header->Sender->UserAgent;
            default:
                throw new Exception('Unknown property in ' . __CLASS__);
                break;
        }
    }

    /**
     * Magic setter.
     *
     * @param string $key
     * @param midex $value
     * @throws Exception
     */
    public function __set($key, $value)
    {
        switch ($key) {
            case 'deploymentMode':
                $this->simple_xml_element->Request['deploymentMode'] = $value;
                break;
            case 'orderDate':
                $this->simple_xml_element->Request->OrderRequest->OrderRequestHeader['orderDate'] = $value;
                break;
            case 'orderID':
                $this->simple_xml_element->Request->OrderRequest->OrderRequestHeader['orderID'] = $value;
                break;
            case 'orderType':
                $this->simple_xml_element->Request->OrderRequest->OrderRequestHeader['orderType'] = $value;
                break;
            case 'Shipping':
                $this->simple_xml_element->Request->OrderRequest->OrderRequestHeader->Shipping->Money = $value;
                break;
            case 'Shipping_Description':
                $this->simple_xml_element->Request->OrderRequest->OrderRequestHeader->Shipping->Description = $value;
                $this->simple_xml_element->Request->OrderRequest->OrderRequestHeader->Shipping
                    ->Description['xml:lang'] = 'en';
                break;
            case 'Tax':
                $this->simple_xml_element->Request->OrderRequest->OrderRequestHeader->Tax->Money = $value;
                break;
            case 'Tax_Description':
                $this->simple_xml_element->Request->OrderRequest->OrderRequestHeader->Tax->Description = $value;
                $this->simple_xml_element->Request->OrderRequest->OrderRequestHeader->Tax
                    ->Description['xml:lang'] = 'en';
                break;
            case 'type':
                $this->simple_xml_element->Request->OrderRequest->OrderRequestHeader['type'] = $value;
                break;
            case 'Total':
                $this->simple_xml_element->Request->OrderRequest->OrderRequestHeader->Total->Money = $value;
                break;
            case 'UserAgent':
                $this->simple_xml_element->Header->Sender->UserAgent = $value;
                break;
            default:
                throw new Exception('Unknown property in ' . __CLASS__);
                break;
        }
    }

    /**
     * Set the From Credentials.
     *
     * @param string $identity
     *            Value of the Identity element (username).
     */
    public function setFromCredentials($identity, $domain = 'NetworkID')
    {
        $this->simple_xml_element->Header[0]->From[0]->Credential['domain'] = $domain;
        $this->simple_xml_element->Header[0]->From[0]->Credential[0]->Identity[0] = $identity;
    }

    /**
     * Set the To Credentials.
     *
     * @param string $identity
     *            Value of the Identity element (username).
     */
    public function setToCredentials($identity, $domain = 'NetworkID')
    {
        $this->simple_xml_element->Header[0]->To[0]->Credential['domain'] = $domain;
        $this->simple_xml_element->Header[0]->To[0]->Credential[0]->Identity[0] = $identity;
    }

    /**
     * Set the Sender Credentials.
     *
     * @param string $identity
     *            Value of the Identity element (username).
     * @param string $shared_secret
     *            Value of the SharedSecret elememnt (password).
     */
    public function setSenderCredentials($identity, $shared_secret, $domain = 'NetworkID')
    {
        $this->simple_xml_element->Header[0]->Sender[0]->Credential['domain'] = $domain;
        $this->simple_xml_element->Header[0]->Sender[0]->Credential[0]->Identity[0] = $identity;
        $this->simple_xml_element->Header[0]->Sender[0]->Credential[0]->SharedSecret[0] = $shared_secret;
    }

    /**
     * Get the ShipTo element if it exists in the OrderRequestHeader.
     *
     * @return CxmlAddress|null
     */
    public function getShipTo()
    {
        $nodes = $this->simple_xml_element->xpath('//OrderRequestHeader/ShipTo');
        return count($nodes) ? CxmlAddress::factory($nodes[0]) : null;
    }

    /**
     * Create and populate the ShipTo element.
     *
     * @param stdClass $ship_to
     */
    public function setShipTo($ship_to)
    {
        $el = $this->simple_xml_element->Request->OrderRequest->OrderRequestHeader;
        $ShipTo = $el->addChild('ShipTo');
        $Address = $ShipTo->addChild('Address');
        if (isset($ship_to->addressID)) {
            $Address['addressID'] = $ship_to->addressID;
        }
        if (isset($ship_to->Name)) {
            $Name = $Address->addChild('Name', $ship_to->Name);
            $Name['xml:lang'] = 'en';
        }
        $PostalAddress = $Address->addChild('PostalAddress');
        if (isset($ship_to->name)) {
            $PostalAddress['name'] = $ship_to->name;
        }
        $PostalAddress->addChild('DeliverTo', $ship_to->DeliverTo);
        $PostalAddress->addChild('Street', $ship_to->Street);
        $PostalAddress->addChild('City', $ship_to->City);
        $PostalAddress->addChild('State', $ship_to->State);
        $PostalAddress->addChild('PostalCode', $ship_to->PostalCode);
        $country = $PostalAddress->addChild('Country', $ship_to->Country);
        $country['isoCountryCode'] = $ship_to->isoCountryCode;
    }

    /**
     * Create and populate the BillTo element.
     *
     * @param stdClass $bill_to
     */
    public function setBillTo($bill_to)
    {
        $el = $this->simple_xml_element->Request->OrderRequest->OrderRequestHeader;
        $BillTo = $el->addChild('BillTo');
        $Address = $BillTo->addChild('Address');
        if (isset($bill_to->addressID)) {
            $Address['addressID'] = $bill_to->addressID;
        }
        if (isset($bill_to->Name)) {
            $Name = $Address->addChild('Name', $bill_to->Name);
            $Name['xml:lang'] = 'en';
        }
        $PostalAddress = $Address->addChild('PostalAddress');
        if (isset($bill_to->name)) {
            $PostalAddress['name'] = $bill_to->name;
        }
        $PostalAddress->addChild('DeliverTo', $bill_to->DeliverTo);
        $PostalAddress->addChild('Street', $bill_to->Street);
        $PostalAddress->addChild('City', $bill_to->City);
        $PostalAddress->addChild('State', $bill_to->State);
        $PostalAddress->addChild('PostalCode', $bill_to->PostalCode);
        $country = $PostalAddress->addChild('Country', $bill_to->Country);
        $country['isoCountryCode'] = $bill_to->isoCountryCode;
    }

    /**
     * Create and populate an ItemOut element.
     *
     * @param CxmlItemOut $item_out
     */
    public function addItem($item_out)
    {
        $el = $this->simple_xml_element->Request->OrderRequest;
        $ItemOut = $el->addChild('ItemOut');
        $ItemID = $ItemOut->addChild('ItemID');
        $ItemID->addChild('SupplierPartID');
        $ItemID->addChild('SupplierPartAuxiliaryID');

        $ItemDetail = $ItemOut->addChild('ItemDetail');
        $UnitPrice = $ItemDetail->addChild('UnitPrice');
        $UnitPrice->addChild('Money');

        // http://php.net/manual/en/simplexmlelement.addchild.php#104458
        $Description = $ItemDetail->addChild('Description');
        $Description_ShortName = $Description->addChild('ShortName');

        $ItemDetail->addChild('UnitOfMeasure');
        $ItemDetail->addChild('Classification');
        $ItemDetail->addChild('ManufacturerPartID');
        $ItemDetail->addChild('ManufacturerName');

        $ItemOut['lineNumber'] = ++ $this->lineNumber;
        $ItemOut['quantity'] = $item_out->quantity;
        $ItemOut->ItemID->SupplierPartID = $item_out->SupplierPartID;
        $ItemOut->ItemID->SupplierPartAuxiliaryID = $item_out->SupplierPartAuxiliaryID;
        $ItemOut->ItemDetail->UnitPrice->Money['currency'] = 'USD';
        $ItemOut->ItemDetail->UnitPrice->Money = $item_out->UnitPrice;
        $ItemOut->ItemDetail->Description['xml:lang'] = 'en';
        $ItemOut->ItemDetail->Description = $item_out->Description;
        $ItemOut->ItemDetail->Description->ShortName = $item_out->Description_ShortName;
        $ItemOut->ItemDetail->UnitOfMeasure = $item_out->UnitOfMeasure;
        $ItemOut->ItemDetail->Classification['domain'] = 'UNSPSC';
        $ItemOut->ItemDetail->Classification = $item_out->UNSPSC;
        $ItemOut->ItemDetail->ManufacturerPartID = $item_out->ManufacturerPartID;
        $ItemOut->ItemDetail->ManufacturerName = $item_out->ManufacturerName;
    }

    /**
     * Get an array of CxmlItemOut objects from ItemOut element data.
     *
     * @return CxmlItemOut[]
     */
    public function getItemIterator()
    {
        $item_outs = $this->simple_xml_element->xpath('//ItemOut');
        $result = array();
        foreach ($item_outs as $item_out) {
            $result[] = CxmlItemOut::factory($item_out);
        }
        return $result;
    }
}
