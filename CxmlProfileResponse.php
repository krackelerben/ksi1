<?php

/**
 * CxmlProfileResponse class provides access to interesting values in a ProfileResponse document.
 *
 * @author Brian Newsham
 *
 * @property integer $Status_code
 * @property string $Status_text
 * @property string $effectiveDate The date and time when these services became available. Dates should not be in the
 *     future.
 * @property string $lastRefresh Indicates when the profile cache was last refreshed. When an application receives a
 *     ProfileResponse from a profile cache server, it will know the age of the data in the cache.
 */
class CxmlProfileResponse extends CxmlDocument
{

    /**
     * Magic getter provides read access to 'interesting' elements and attributes.
     *
     * @param string $name Name of the attribute or element.
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
            case 'effectiveDate':
                return $this->simple_xml_element->Response->ProfileResponse['effectiveDate'];
                break;
            case 'lastRefresh':
                return $this->simple_xml_element->Response->ProfileResponse['lastRefresh'];
                break;
            default:
                throw new Exception('Unknown property in ' . __CLASS__);
                break;
        }
    }

    /**
     * Get an array of Transaction elements in the message. Each element has a 'requestName' and 'URL' key.
     *
     * @return array
     */
    public function getTransactionIterator()
    {
        $nodes = $this->simple_xml_element->xpath('//Transaction');
        $result = array();
        foreach ($nodes as $node) {
            $result[] = [
                'requestName' => (string)$node['requestName'],
                'URL' => (string)$node->URL,
            ];
        }
        return $result;
    }
}
