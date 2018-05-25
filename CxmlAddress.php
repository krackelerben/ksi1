<?php

/**
 * CxmlAddress is a value object for a cXML Address element.
 *
 * @author Brian Newsham
 *
 * @property string $isoCountryCode
 * @property string $addressID
 * @property string $addressIDDomain
 * @property string $Name
 * @property array $DeliverTo
 * @property array $Street
 * @property string $City
 * @property string $State
 * @property string $PostalCode
 * @property string $Country
 * @property string $Country_isoCountryCode
 * @property string $Email (optional)
 * @property string $Phone (optional)
 * @property string $Fax (optional)
 * @property string $URL (optional)
 */
class CxmlAddress implements ArrayAccess
{

    /**
     * Internal storage for address fields.
     *
     * @var array
     */
    protected $contents = array();

    /**
     * Build an instance of this class from the SimpleXMLElement representation.
     *
     * @param SimpleXMLElement $sxe
     * @return CxmlAddress
     */
    public static function factory($sxe)
    {
        $ca = new CxmlAddress();

        if (isset($sxe['addressID'])) {
            $ca->addressID = (string)$sxe['addressID'];
        }
        if (isset($sxe['isoCountryCode'])) {
            $ca->isoCountryCode = (string)$sxe['isoCountryCode'];
        }
        $ca->Name = Cxml::firstNode($sxe, './Name');

        foreach ($sxe->xpath('./PostalAddress/DeliverTo') as $node) {
            $ca->DeliverTo = $node->__toString();
        }
        foreach ($sxe->xpath('./PostalAddress/Street') as $node) {
            $ca->Street = $node->__toString();
        }

        $ca->City = Cxml::firstNode($sxe, './PostalAddress/City');
        $ca->State = Cxml::firstNode($sxe, './PostalAddress/State');
        $ca->PostalCode = Cxml::firstNode($sxe, './PostalAddress/PostalCode');
        $ca->Country = Cxml::firstNode($sxe, './PostalAddress/Country');
        $ca->Country_isoCountryCode = Cxml::firstNode($sxe, './PostalAddress/Country/@isoCountryCode');

        $email = Cxml::firstNode($sxe, './Email');
        if (!is_null($email)) {
            $ca->Email = $email;
        }

        /**
         * @todo populate phone and fax
         */
        //$ca->Phone = Cxml::firstNode($sxe, './Phone');
        //$ca->Fax = Cxml::firstNode($sxe, './Fax');

        $url = Cxml::firstNode($sxe, './URL');
        if (!is_null($url)) {
            $ca->URL = $url;
        }

        return $ca;
    }

    /**
     * Magic getter provides access to content fields with object->property syntax.
     *
     * @param mixed $index
     *            index of the content array to get.
     * @return mixed
     */
    public function __get($index)
    {
        return $this->offsetGet($index);
    }

    /**
     * Magic setter provides access to content fields with object->property syntax.
     *
     * @param string $key
     *            ItemIn field name.
     * @param string $value
     *            Value
     */
    public function __set($key, $value)
    {
        $this->offsetSet($key, $value);
    }

    /**
     * __isset() is triggered by calling isset() or empty() on inaccessible properties.
     *
     * @link http://php.net/manual/en/language.oop5.overloading.php#object.isset
     * @param mixed $index Name of a magic property.
     * @return boolean
     */
    public function __isset($index)
    {
        return array_key_exists($index, $this->contents);
    }

    /**
     * __unset() is invoked when unset() is used on inaccessible properties.
     *
     * @link http://php.net/manual/en/language.oop5.overloading.php#object.isset
     * @param mixed $index Name of a magic property.
     */
    public function __unset($index)
    {
        unset($this->contents[$index]);
    }

    /**
     * (non-PHPdoc)
     *
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists($index)
    {
        return isset($this->contents[$index]);
    }

    /**
     * (non-PHPdoc)
     *
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet($index)
    {
        if ($this->offsetExists($index)) {
            return $this->contents[$index];
        }
        return false;
    }

    /**
     * (non-PHPdoc)
     *
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet($index, $value)
    {
        switch ($index) {
            case 'DeliverTo':
                $this->contents[$index][] = $value;
                break;
            case 'Street':
                $this->contents[$index][] = $value;
                break;
            default:
                $this->contents[$index] = $value;
                break;
        }
        return true;
    }

    /**
     * (non-PHPdoc)
     *
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset($index)
    {
        unset($this->contents[$index]);
        return true;
    }

    /**
     * Returns the internal storage array.
     *
     * @return array
     */
    public function getContents()
    {
        return $this->contents;
    }
}
