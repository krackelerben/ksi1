<?php

/**
 * CxmlOrderRequestBuilder is a builder for CxmlOrderRequest objects.
 *
 * @author Brian Newsham
 *
 */
class CxmlOrderRequestBuilder extends CxmlRequestBuilder
{

    protected $Request_deploymentMode;

    protected $orderDate;

    protected $orderID;

    protected $orderType;

    protected $type;

    protected $Total;

    protected $Shipping;

    protected $Shipping_Description;

    protected $Tax;

    protected $Tax_Description;

    protected $ShipTo;

    protected $BillTo;

    protected $Comments;

    protected $ItemOuts;

    /**
     * Constructor.
     *
     * @param string $version
     *            Version of cXML that will be referenced in the header.
     */
    public function __construct($version = null)
    {
        parent::__construct($version);
        $this->Request_deploymentMode = null;
        $this->orderDate = null;
        $this->orderID = null;
        $this->orderType = null;
        $this->type = null;
        $this->Total = null;
        $this->Shipping = null;
        $this->Shipping_Description = null;
        $this->Tax = null;
        $this->Tax_Description = null;
        $this->ShipTo = null;
        $this->BillTo = null;
        $this->ItemOuts = array();
    }

    /**
     * Set the deployment mode attribute.
     *
     * @param string $deployment_mode
     *            ( test | production )
     * @return CxmlOrderRequestBuilder
     */
    public function setDeploymentMode($deployment_mode)
    {
        $this->Request_deploymentMode = $deployment_mode;
        return $this;
    }

    /**
     * Set the orderDate attribute.
     *
     * @param string $orderDate
     *            cXML datetime string.
     * @return CxmlOrderRequestBuilder
     */
    public function setOrderDate($orderDate)
    {
        $this->orderDate = $orderDate;
        return $this;
    }

    /**
     * Set the order ID.
     *
     * @param string $orderID
     *            order ID.
     * @return CxmlOrderRequestBuilder
     */
    public function setOrderID($orderID = null)
    {
        $this->orderID = $orderID;
        return $this;
    }

    /**
     * Set the orderType attribute for the OrderRequestHeader element.
     *
     * @param string $orderType
     *            order type.
     * @return CxmlOrderRequestBuilder
     */
    public function setOrderType($orderType)
    {
        $this->orderType = $orderType;
        return $this;
    }

    /**
     * Set the type attribute for the OrderRequestHeader element.
     *
     * @param string $type
     * @return CxmlOrderRequestBuilder
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Set the total for the OrderRequest.
     *
     * @param float|string $total
     * @return CxmlOrderRequestBuilder
     */
    public function setTotal($total)
    {
        $this->Total = $total;
        return $this;
    }

    /**
     * Set the shipping charge and description.
     *
     * @param float|string $shipping
     * @param string $description
     * @return CxmlOrderRequestBuilder
     */
    public function setShipping($shipping, $description)
    {
        $this->Shipping = $shipping;
        $this->Shipping_Description = $description;
        return $this;
    }

    /**
     * Set the values required for a Tax element.
     *
     * @param float|string $tax
     *            The tax value.
     * @param string $description
     *            Description of the tax value.
     * @return CxmlOrderRequestBuilder
     */
    public function setTax($tax, $description)
    {
        $this->Tax = $tax;
        $this->Tax_Description = $description;
        return $this;
    }

    /**
     * Set ShipTo information.
     *
     * @param CxmlAddress $ship_to
     * @return CxmlOrderRequestBuilder
     */
    public function setShipTo($ship_to)
    {
        $this->ShipTo = $ship_to;
        return $this;
    }

    /**
     * Set BillTo information.
     *
     * @param CxmlAddress $bill_to
     * @return CxmlOrderRequestBuilder
     */
    public function setBillTo($bill_to)
    {
        $this->BillTo = $bill_to;
        return $this;
    }

    /**
     * Set Comments at the order level.
     *
     * @param string $comments Comments.
     * @return CxmlOrderRequestBuilder
     */
    public function setComments($comments)
    {
        $this->Comments = $comments;
        return $this;
    }

    /**
     * Add data for ItemOut elements.
     *
     * @param CxmlItemOut $item_out
     * @return CxmlOrderRequestBuilder
     */
    public function addItem($item_out)
    {
        $this->ItemOuts[] = $item_out;
        return $this;
    }

    /**
     * (non-PHPdoc)
     *
     * @see CxmlBuilder::getResult()
     */
    public function getResult()
    {
        $this->buildHeader();
        $this->buildRequest();

        return new CxmlOrderRequest($this->simple_xml_element);
    }

    /**
     * Build the OrderRequest SimpleXMLElement.
     */
    protected function buildRequest()
    {
        $Request = $this->simple_xml_element->addChild('Request');
        $Request['deploymentMode'] = $this->Request_deploymentMode;
        $OrderRequest = $Request->addChild('OrderRequest');
        $OrderRequestHeader = $OrderRequest->addChild('OrderRequestHeader');
        $OrderRequestHeader['orderDate'] = $this->orderDate;
        $OrderRequestHeader['orderID'] = $this->orderID;
        $OrderRequestHeader['orderType'] = $this->orderType;
        $OrderRequestHeader['type'] = $this->type;

        $this->buildMoney($OrderRequestHeader, 'Total', $this->Total);
        $this->buildShipTo($OrderRequestHeader, $this->ShipTo);
        $this->buildBillTo($OrderRequestHeader, $this->BillTo);

        $elShipping = $this->buildMoney($OrderRequestHeader, 'Shipping', $this->Shipping);
        if ($this->Shipping_Description) {
            $Description = $elShipping->addChild('Description', $this->Shipping_Description);
            $Description['xml:lang'] = 'en';
        }

        $elTax = $this->buildMoney($OrderRequestHeader, 'Tax', $this->Tax);
        if ($this->Tax_Description) {
            $Description = $elTax->addChild('Description', $this->Tax_Description);
            $Description['xml:lang'] = 'en';
        }

        $OrderRequestHeader->addChild(
            'Comments',
            XmlHelper::encodeNumericEntity(XmlHelper::escape($this->Comments), 'UTF-8')
        );

        foreach ($this->ItemOuts as $ItemOut) {
            $this->buildItemOut($OrderRequest, $ItemOut);
        }
    }

    /**
     * Build a ShipTo address.
     *
     * @param SimpleXMLElement $el
     * @param stdClass $ship_to
     */
    protected function buildShipTo($el, $ship_to)
    {
        $this->buildAddress($el, 'ShipTo', $ship_to);
    }

    /**
     * Build a BillTo address.
     *
     * @param SimpleXMLElement $el
     * @param stdClass $bill_to
     */
    protected function buildBillTo($el, $bill_to)
    {
        $this->buildAddress($el, 'BillTo', $bill_to);
    }
}
