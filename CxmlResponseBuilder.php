<?php

/**
 * CxmlResponseBuilder is the abstract base class for the Response family of documents.
 *
 * @author Brian Newsham
 *
 */
abstract class CxmlResponseBuilder extends CxmlBuilder
{
    /**
     * code attribute of the Status element.
     *
     * @var integer
     */
    protected $Status_code;

    /**
     * text attribute of the Status element.
     *
     * @var string
     */
    protected $Status_text;

    /**
     * Content of the Status element.
     *
     * @var string
     */
    protected $Status;

    /**
     * Set the Status element attributes, and optional content.
     *
     * @param integer $code
     * @param string $text
     * @param string $value
     */
    public function setStatus($code, $text, $value = null)
    {
        $this->Status_code = $code;
        $this->Status_text = $text;
        $this->Status = $value;

        return $this;
    }
}
