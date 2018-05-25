<?php

/**
 * CxmlOrderResponse represents a generic cXML response document.
 *
 * @author Brian Newsham
 *
 * @property string $Status_code
 * @property string $Status_text
 * @property string $Status
 */
class CxmlOrderResponse extends CxmlDocument
{

    /**
     * Magic getter.
     *
     * @param string $key
     * @throws Exception
     * @return mixed
     */
    public function __get($key)
    {
        switch ($key) {
            case 'Status_code':
                return $this->simple_xml_element->Response->Status['code'];
                break;
            case 'Status_text':
                return $this->simple_xml_element->Response->Status['text'];
                break;
            case 'Status':
                return $this->simple_xml_element->Response->Status;
                break;
            default:
                throw new Exception('Unknown property in ' . __CLASS__);
                break;
        }
    }

    /**
     * Magic setter.
     *
     * @param string $key
     * @param mixed $value
     * @throws Exception
     */
    public function __set($key, $value)
    {
        switch ($key) {
            case 'Status_code':
                $this->simple_xml_element->Response->Status['code'] = $value;
                break;
            case 'Status_text':
                $this->simple_xml_element->Response->Status['text'] = $value;
                break;
            case 'Status':
                $this->simple_xml_element->Response->Status = $value;
                break;
            default:
                throw new Exception('Unknown property in ' . __CLASS__);
                break;
        }
    }
}
