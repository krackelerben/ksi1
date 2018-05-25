<?php

/**
 * CxmlPunchOutSetupResponse class provides access to interesting values in a PunchOutSetupResponse document.
 *
 * @author Brian Newsham
 *
 * @property integer $Status_code Status code
 * @property string $Status_text Status text
 * @property string $StartPage_URL StartPage URL.
 */
class CxmlPunchOutSetupResponse extends CxmlDocument
{

    /**
     * Magic getter.
     *
     * @param string $name
     */
    public function __get($name)
    {
        switch ($name) {
            case 'Status_code':
                return $this->simple_xml_element->Response->Status['code'];
                break;
            case 'Status_text':
                return $this->simple_xml_element->Response->Status['text'];
                break;
            case 'StartPage_URL':
                return $this->simple_xml_element->Response->PunchOutSetupResponse->StartPage->URL;
                break;
            default:
                throw new Exception('Unknown property in ' . __CLASS__);
                break;
        }
    }

    /**
     * Magic setter.
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        switch ($name) {
            case 'Status_code':
                $this->simple_xml_element->Response->Status['code'] = $value;
                break;
            case 'Status_text':
                $this->simple_xml_element->Response->Status['text'] = $value;
                break;
            case 'StartPage_URL':
                $this->simple_xml_element->Response->PunchOutSetupResponse->StartPage->URL = $value;
                break;
            default:
                throw new Exception('Unknown property in ' . __CLASS__);
                break;
        }
    }
}
