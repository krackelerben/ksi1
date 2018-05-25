<?php

/**
 * CxmlPunchOutOrderMessageBuilder is a builder for CxmlPunchOutOrderMessage objects.
 *
 * @author Brian Newsham
 *
 */
class CxmlPunchOutOrderMessageBuilder extends CxmlRequestBuilder
{

    /**
     * deploymentMode attribute for the Message element.
     *
     * @var string
     */
    protected $Message_deploymentMode;

    /**
     * Buyer cookie.
     *
     * @var string
     */
    protected $BuyerCookie;

    /**
     * operationAllowed attribute on the PunchOutOrderMessageHeader element.
     * (create | inspect | edit)
     *
     * @var string
     */
    protected $operationAllowed;

    /**
     * Total amount of the cart.
     *
     * @var string|float
     */
    protected $Total_Money;

    /**
     * ShipTo data.
     *
     * @var stdClass
     */
    protected $ShipTo;

    /**
     * Shipping charges.
     *
     * @var float|string
     */
    protected $Shipping_Money;

    /**
     * Description of shipping charges.
     *
     * @var string
     */
    protected $Shipping_Description;

    /**
     * Tax amount.
     *
     * @var float|string
     */
    protected $Tax_Money;

    /**
     * Description of tax amount.
     *
     * @var string
     */
    protected $Tax_Description;

    /**
     * Array of CxmlItemIn objects.
     *
     * @var CxmlItemIn[]
     */
    protected $ItemIns;

    /**
     * A helper object for customizing encodings.
     *
     * @var CxmlHelper
     */
    protected $helper;

    /**
     * Constructor.
     *
     * @param string $version
     */
    public function __construct($version = null)
    {
        parent::__construct($version);
        $this->Message_deploymentMode = null;
        $this->BuyerCookie = null;
        $this->operationAllowed = null;
        $this->Total_Money = null;
        $this->ShipTo = null;
        $this->Shipping_Money = null;
        $this->Shipping_Description = null;
        $this->Tax_Money = null;
        $this->Tax_Description = null;
        $this->ItemIns = array();
        $this->helper = null;
    }

    /**
     * Set the helper object.
     *
     * @param CxmlHelper $helper A concrete implementation of CxmlHelper.
     * @return CxmlPunchOutOrderMessageBuilder
     */
    public function setHelper(CxmlHelper $helper)
    {
        $this->helper = $helper;
        return $this;
    }

    /**
     * Set the deployment mode attribute.
     *
     * @param string $deployment_mode
     *            ( test | production )
     * @return CxmlPunchOutOrderMessageBuilder
     */
    public function setDeploymentMode($deployment_mode)
    {
        $this->Message_deploymentMode = $deployment_mode;
        return $this;
    }

    /**
     * Set the value for the BuyerCookie element.
     *
     * @param string $buyer_cookie
     * @return CxmlPunchOutOrderMessageBuilder
     */
    public function setBuyerCookie($buyer_cookie)
    {
        $this->BuyerCookie = $buyer_cookie;
        return $this;
    }

    /**
     * Set the operation attribute for the PunchOutOrderMessageHeader element.
     *
     * @param string $operation (create | inspect | edit)
     * @return CxmlPunchOutOrderMessageBuilder
     */
    public function setOperationAllowed($operation)
    {
        $this->operationAllowed = $operation;
        return $this;
    }

    /**
     * Set the total amount for the cart.
     *
     * @param float|string $money Total dollar about for the cart.
     * @return CxmlPunchOutOrderMessageBuilder
     */
    public function setTotal($money)
    {
        $this->Total_Money = $money;
        return $this;
    }

    /**
     * Set the ShipTo data.
     *
     * @param stdClass $ship_to
     * @return CxmlPunchOutOrderMessageBuilder
     */
    public function setShipTo($ship_to)
    {
        $this->ShipTo = $ship_to;
        return $this;
    }

    /**
     * Set the amount and optional description of the shipping charges.
     *
     * @param float|string $money Shipping amount for the cart.
     * @param string $description Description of the shipping charge.
     * @return CxmlPunchOutOrderMessageBuilder
     */
    public function setShipping($money, $description = null)
    {
        $this->Shipping_Money = $money;
        $this->Shipping_Description = $description;
        return $this;
    }

    /**
     * Set the amount and optional description of the tax.
     *
     * @param float|string $money Tax amount for the cart.
     * @param string $description Description of the taxes.
     * @return CxmlPunchOutOrderMessageBuilder
     */
    public function setTax($money, $description = null)
    {
        $this->Tax_Money = $money;
        $this->Tax_Description = $description;
        return $this;
    }

    /**
     * Add data for ItemIn elements.
     *
     * @param CxmlItemIn $item_in
     * @return CxmlPunchOutOrderMessageBuilder
     */
    public function addItem($item_in)
    {
        $this->ItemIns[] = $item_in;
        return $this;
    }

    /**
     * Get the number of items that have been added to the builder.
     *
     * @return int
     */
    public function countItems()
    {
        return count($this->ItemIns);
    }

    /**
     * Get the total price for all ItemIns.
     *
     * @return string The total dollar value of the cart formatted as a currency string.
     */
    public function getTotal()
    {
        $total = 0;
        foreach ($this->ItemIns as $ItemIn) {
            $total += floatval($ItemIn['quantity']) * floatval($ItemIn['UnitPrice']);
        }
        return Cxml::currency($total);
    }

    /**
     * (non-PHPdoc)
     * @see CxmlBuilder::getResult()
     */
    public function getResult()
    {
        $this->buildHeader();
        $this->buildRequest();

        return new CxmlPunchOutOrderMessage($this->simple_xml_element);
    }

    /**
     * Builds the request portion of the document.
     *
     */
    protected function buildRequest()
    {
        $Message = $this->simple_xml_element->addChild('Message');
        if (strlen($this->Message_deploymentMode)) {
            $Message['deploymentMode'] = $this->Message_deploymentMode;
        }
        $poom = $Message->addChild('PunchOutOrderMessage');
        $poom->addChild('BuyerCookie', $this->BuyerCookie);
        $poomh = $poom->addChild('PunchOutOrderMessageHeader');
        if (!is_null($this->operationAllowed)) {
            $poomh['operationAllowed'] = $this->operationAllowed;
        }

        $this->buildMoney($poomh, 'Total', $this->Total_Money);

        if (!is_null($this->ShipTo)) {
            $this->buildAddress($poomh, 'ShipTo', $this->ShipTo);
        }

        if (!is_null($this->Shipping_Money)) {
            $Shipping = $this->buildMoney($poomh, 'Shipping', $this->Shipping_Money);
            $Description = $Shipping->addChild('Description', $this->Shipping_Description);
            $Description['xml:lang'] = 'en';
        }
        if (!is_null($this->Tax_Money)) {
            $Tax = $this->buildMoney($poomh, 'Tax', $this->Tax_Money);
            $Description = $Tax->addChild('Description', $this->Tax_Description);
            $Description['xml:lang'] = 'en';
        }

        foreach ($this->ItemIns as $ItemIn) {
            $this->buildItemIn($poom, $ItemIn);
        }
    }

    /**
     * Build the ItemIn element, and add it to $poom.
     *
     * @param SimpleXMLElement $poom
     * @param CxmlItemIn $ItemIn
     */
    protected function buildItemIn($poom, CxmlItemIn $ItemIn)
    {
        $el = $poom->addChild('ItemIn');
        $el['quantity'] = $ItemIn['quantity'];

        /*
         * Build ItemID element.
         */
        $el_ItemID = $el->addChild('ItemID');
        $el_ItemID->addChild('SupplierPartID', $ItemIn['SupplierPartID']);
        if (isset($ItemIn['SupplierPartAuxiliaryID'])) {
            $el_ItemID->addChild('SupplierPartAuxiliaryID', $ItemIn['SupplierPartAuxiliaryID']);
        }

        /*
         * Build ItemDetail element.
         */
        $el_ItemDetail = $el->addChild('ItemDetail');
        $UnitPrice = $ItemIn['UnitPrice'];
        $UnitPrice = is_null($this->helper) ? $UnitPrice : $this->helper->formatPrice($UnitPrice);
        $this->buildMoney($el_ItemDetail, 'UnitPrice', $UnitPrice);

        $escDescription = $ItemIn['Description'];
        $escDescription = XmlHelper::encodeNumericEntity(XmlHelper::escape($escDescription), 'UTF-8');
        $escDescription = is_null($this->helper) ? $escDescription : $this->helper->encode($escDescription);
        $Description = $el_ItemDetail->addChild('Description', $escDescription);
        $Description['xml:lang'] = 'en';
        if (isset($ItemIn['Description_ShortName'])) {
            $Description->addChild('ShortName', $ItemIn['Description_ShortName']);
        }

        $UnitOfMeasure = $ItemIn['UnitOfMeasure'];
        $UnitOfMeasure = is_null($this->helper) ? $UnitOfMeasure : $this->helper->formatUom($UnitOfMeasure);
        $el_ItemDetail->addChild('UnitOfMeasure', $UnitOfMeasure);

        if (isset($ItemIn['UNSPSC'])) {
            $UNSPSC = $ItemIn['UNSPSC'];
            $UNSPSC = is_null($this->helper) ? $UNSPSC : $this->helper->formatUnspsc($UNSPSC);
            $Classification = $el_ItemDetail->addChild('Classification', $UNSPSC);
            $Classification['domain'] = 'UNSPSC';
        }
        if (isset($ItemIn['ManufacturerPartID'])) {
            $el_ItemDetail->addChild('ManufacturerPartID', $ItemIn['ManufacturerPartID']);
        }
        if (isset($ItemIn['ManufacturerName'])) {
            $el_ItemDetail->addChild('ManufacturerName', $ItemIn['ManufacturerName']);
        }
        if (isset($ItemIn['URL'])) {
            $el_ItemDetail->addChild('URL', $ItemIn['URL']);
        }
        if (isset($ItemIn['Extrinsics'])) {
            foreach ($ItemIn['Extrinsics'] as $name => $value) {
                $Extrinsic = $el_ItemDetail->addChild('Extrinsic', $value);
                $Extrinsic['name'] = $name;
            }
        }

        /*
         * Build Supplier ID element.
         */
        if (isset($ItemIn['SupplierID'])) {
            $SupplierID = $el->addChild('SupplierID', $ItemIn['SupplierID']);
            $SupplierID['domain'] = $ItemIn['SupplierID_domain'];
        }

        /*
         * Build Shipping element.
         */
        if (isset($ItemIn['Shipping'])) {
            $Shipping = $this->buildMoney($el, 'Shipping', $ItemIn['Shipping']);
            $Description = $Shipping->addChild('Description', trim($ItemIn['Shipping_Description']));
            $Description['xml:lang'] = 'en';
        }

        /*
         * Build Tax element.
         */
        if (isset($ItemIn['Tax'])) {
            $Tax = $this->buildMoney($el, 'Tax', $ItemIn['Tax']);
            $Description = $Tax->addChild('Description', $ItemIn['Tax_Description']);
            $Description['xml:lang'] = 'en';
        }
    }
}
