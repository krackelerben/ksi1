<?php

/**
 * CxmlPunchOutSetupResponseBuilder builds the PunchOutSetupResponse document.
 *
 * @author Brian Newsham
 *
 */
class CxmlPunchOutSetupResponseBuilder extends CxmlResponseBuilder
{

    /**
     * The StartPage URL.
     *
     * @var string
     */
    protected $StartPage_URL;

    /**
     * Set the StartPage URL.
     *
     * @param string $url
     */
    public function setStartPageURL($url)
    {
        $this->StartPage_URL = $url;

        return $this;
    }

    /**
     * (non-PHPdoc)
     *
     * @see CxmlBuilder::getResult()
     * @return CxmlPunchOutSetupResponse
     */
    public function getResult()
    {
        $Response = $this->simple_xml_element->addChild('Response');
        $Status = $Response->addChild('Status', $this->Status);
        $Status['code'] = $this->Status_code;
        $Status['text'] = $this->Status_text;
        $PunchOutSetupResponse = $Response->addChild('PunchOutSetupResponse');
        $StartPage = $PunchOutSetupResponse->addChild('StartPage');
        $StartPage->addChild('URL', $this->StartPage_URL);

        return new CxmlPunchOutSetupResponse($this->simple_xml_element);
    }
}
