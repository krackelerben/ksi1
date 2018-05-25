<?php

/**
 * CxmlOrderResponseBuilder builds a general Response document.
 *
 * @author Brian Newsham
 *
 */
class CxmlOrderResponseBuilder extends CxmlResponseBuilder
{

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

        return new CxmlOrderResponse($this->simple_xml_element);
    }
}
