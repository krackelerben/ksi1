<?php

/**
 * CxmlPunchOutOrderMessage represents a cXML shopping cart.
 *
 * @author Brian Newsham
 *
 * @property string $deploymentMode (production | test) defaults to production.
 * @property string $operationAllowed
 * @property string $BuyerCookie
 * @property string $Total
 * @property string $Shipping
 * @property string $Shipping_Description
 * @property string $Tax
 * @property string $Tax_Description
 * @property string $ShipTo
 */
class CxmlPunchOutOrderMessage extends CxmlDocument
{

    /**
     * Magic getter to access 'interesting' elements and attributes within the document.
     *
     * @param string $key
     * @throws Exception
     */
    public function __get($key)
    {
        switch ($key) {
            case 'deploymentMode':
                $deploymentMode = $this->simple_xml_element->Message['deploymentMode'];
                return is_null($deploymentMode) ? 'production' : $deploymentMode->__toString();
                break;
            case 'operationAllowed':
                return $this->simple_xml_element->Message->PunchOutOrderMessage
                    ->PunchOutOrderMessageHeader['operationAllowed'];
                break;
            case 'BuyerCookie':
                return $this->simple_xml_element->Message->PunchOutOrderMessage->BuyerCookie;
                break;
            case 'Shipping':
                return $this->simple_xml_element->Message->PunchOutOrderMessage->PunchOutOrderMessageHeader
                    ->Shipping->Money;
                break;
            case 'Shipping_Description':
                return $this->simple_xml_element->Message->PunchOutOrderMessage->PunchOutOrderMessageHeader
                    ->Shipping->Description;
                break;
            case 'Tax':
                return $this->simple_xml_element->Message->PunchOutOrderMessage->PunchOutOrderMessageHeader
                    ->Tax->Money;
                break;
            case 'Tax_Description':
                return $this->simple_xml_element->Message->PunchOutOrderMessage->PunchOutOrderMessageHeader
                    ->Tax->Description;
                break;
            case 'Total':
                return $this->simple_xml_element->Message->PunchOutOrderMessage->PunchOutOrderMessageHeader
                    ->Total->Money;
                break;
            default:
                throw new Exception('Unknown property in ' . __CLASS__);
                break;
        }
    }

    /**
     * Magic setter to set 'interesting' elements and attributes within the document.
     *
     * @param string $key
     * @param mixed $value
     * @throws Exception
     */
    public function __set($key, $value)
    {
        switch ($key) {
            case 'deploymentMode':
                $this->simple_xml_element->Message['deploymentMode'] = $value;
                break;
            case 'operationAllowed':
                $this->simple_xml_element->Message->PunchOutOrderMessage
                    ->PunchOutOrderMessageHeader['operationAllowed'] = $value;
                break;
            case 'BuyerCookie':
                $this->simple_xml_element->Message->PunchOutOrderMessage->BuyerCookie = $value;
                break;
            case 'Shipping':
                $this->simple_xml_element->Message->PunchOutOrderMessage->PunchOutOrderMessageHeader
                    ->Shipping->Money = $value;
                break;
            case 'Shipping_Description':
                $this->simple_xml_element->Message->PunchOutOrderMessage->PunchOutOrderMessageHeader
                    ->Shipping->Description = $value;
                break;
            case 'Tax':
                $this->simple_xml_element->Message->PunchOutOrderMessage->PunchOutOrderMessageHeader
                    ->Tax->Money = $value;
                break;
            case 'Tax_Description':
                $this->simple_xml_element->Message->PunchOutOrderMessage->PunchOutOrderMessageHeader
                    ->Tax->Description = $value;
                break;
            case 'Total':
                $this->simple_xml_element->Message->PunchOutOrderMessage->PunchOutOrderMessageHeader
                    ->Total->Money = $value;
                break;
            default:
                throw new Exception('Unknown property in ' . __CLASS__);
                break;
        }
    }

    /**
     * Get an array of the ItemIn elements in the message.
     *
     * @return CxmlItemIn[]
     */
    public function getItemIterator()
    {
        $item_ins = $this->simple_xml_element->xpath('//ItemIn');
        $result = array();
        foreach ($item_ins as $item_in) {
            $result[] = CxmlItemIn::factory($item_in);
        }
        return $result;
    }
}
