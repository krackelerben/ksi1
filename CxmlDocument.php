<?php

/**
 * CxmlDocument is the base class for all cXML documents.
 *
 * @author Brian Newsham
 *
 */
class CxmlDocument
{

    /**
     * SimpleXMLElement for the document.
     *
     * @var SimpleXMLElement
     */
    protected $simple_xml_element;

    /**
     * Constructor.
     *
     * @param string|SimpleXMLElements $xml
     */
    public function __construct($xml)
    {
        if ($xml instanceof SimpleXMLElement) {
            $this->simple_xml_element = $xml;
        } else {
            $this->simple_xml_element = $this->openXml($xml);
        }
    }

    /**
     * Construct a document object from a file containing XML.
     *
     * @param string $filename file containing a XML document of the type the class this method represents.
     * @return CxmlDocument
     */
    public static function fromFile($filename)
    {
        $calledClass = get_called_class();
        return new $calledClass(file_get_contents($filename));
    }

    /**
     * Initialize the object with a XML document string.
     *
     * @param string $xml
     * @throws Exception
     */
    public function openXml($xml)
    {
        return new SimpleXMLElement($xml, LIBXML_NOWARNING | LIBXML_NOERROR);
    }

    /**
     * Convert the SimpleXMLElement to an XML string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->simple_xml_element->asXml();
    }
}
