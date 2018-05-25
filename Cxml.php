<?php

/**
 * Cxml is a helper class for all cXML related classes.
 *
 * @author Brian Newsham
 *
 */
class Cxml
{

    const STATUS_OK = 200;

    /**
     * Gets the current date and time in the required format for cXML.
     * The format is a restricted subset of ISO 8601.
     *
     * @see http://www.w3.org/TR/NOTE-datetime-970915.html
     *
     * @return string
     */
    public static function date()
    {
        return date('Y-m-d') . 'T' . date('H:i:sP');
    }

    /**
     * Format a number as currency.
     *
     * @param number $number
     * @return string
     */
    public static function currency($number)
    {
        return number_format(floatval($number), 2, '.', '');
    }

    /**
     * Return the first matching node as a string, or NULL if there are no matching nodes.
     *
     * @param SimpleXMLElement $sxe
     *            A SimpleXMLElement node.
     * @param string $xpath
     *            XPath to search against $sxe.
     * @return string|NULL
     */
    public static function firstNode($sxe, $xpath)
    {
        $nodes = $sxe->xpath($xpath);
        if (empty($nodes)) {
            return null;
        }
        return (string) $nodes[0];
    }
}
