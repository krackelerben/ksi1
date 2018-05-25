<?php

/**
 * CxmlBuilder is the base class for building cXML documents.
 *
 * @author Brian Newsham
 *
 */
abstract class CxmlBuilder
{

    /**
     * Simple XML Element
     *
     * @var SimpleXMLElement
     */
    protected $simple_xml_element;

    /**
     * Build the document and return it.
     *
     * @return SimpleXMLElement
     */
    abstract public function getResult();

    /**
     * Constructor.
     *
     * @param string $version Version of cXML that will be referenced in the header.
     */
    public function __construct($version = null)
    {
        if (is_null($version)) {
            $version = '1.2.008';
        }
        $xml = <<<EOQ
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE cXML SYSTEM "http://xml.cXML.org/schemas/cXML/$version/cXML.dtd">
<cXML version="$version" payloadID="" timestamp="" xml:lang="en-US"/>
EOQ;
        $this->simple_xml_element = new SimpleXMLElement($xml);
    }

    /**
     * Set the root attributes of the document
     *
     * @param array $attributes
     */
    public function setRootAttributes($attributes = array())
    {
        $defaults = array(
            'payloadID' => date('Ymdhis') . '.' . mt_rand(0, 10000) . '@' . $_SERVER['SERVER_NAME'],
            'timestamp' => date('Y-m-d') . 'T' . date('H:i:sP')
        );
        $pairs = array_merge($defaults, $attributes);
        foreach ($pairs as $name => $value) {
            $this->simple_xml_element[$name] = $value;
        }

        return $this;
    }
}
