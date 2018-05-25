<?php

/**
 * CxmlItemIn models the ItemIn elements in the PunchOutSetupRequest and PunchOutOrderMessage documents.
 *
 *<!ELEMENT ItemIn (ItemID, Path?, ItemDetail, SupplierID?, ShipTo?, Shipping?, Tax?)>
 *<!ATTLIST ItemIn
 *    quantity   %r8;      #REQUIRED
 *    lineNumber %uint;    #IMPLIED
 *>
 * @author Brian Newsham
 *
 * @property float $quantity
 * @property int $lineNumber
 * @property string $Description
 * @property string $Description_ShortName
 * @property array $Extrinsics
 * @property string $ManufacturerName
 * @property string $ManufacturerPartID
 * @property float $Shipping
 * @property string $Shipping_Description
 * @property string $SupplierPartAuxiliaryID
 * @property string $SupplierPartID
 * @property float $Tax
 * @property string $Tax_Description
 * @property string $UnitOfMeasure
 * @property float $UnitPrice
 * @property string $UNSPSC
 */
class CxmlItemIn implements ArrayAccess
{

    /**
     * Internal storage.
     *
     * @var array
     */
    protected $contents = array();

    /**
     * Build an instance of this class from the SimpleXMLElement representation.
     *
     * @param SimpleXMLElement $sxe
     * @return CxmlItemIn
     */
    public static function factory($sxe)
    {
        $item = new CxmlItemIn();

        $item->quantity = (string) $sxe['quantity'];
        $item->lineNumber = (string) $sxe['lineNumber'];
        $item->Description = Cxml::firstNode($sxe, 'ItemDetail/Description');
        $item->Description_ShortName = Cxml::firstNode($sxe, 'ItemDetail/Description/ShortName');
        $item->ManufacturerName = Cxml::firstNode($sxe, 'ItemDetail/ManufacturerName');
        $item->ManufacturerPartID = Cxml::firstNode($sxe, 'ItemDetail/ManufacturerPartID');
        $item->Shipping = Cxml::firstNode($sxe, 'Shipping/Money');
        $item->Shipping_Description = Cxml::firstNode($sxe, 'Shipping/Description');
        $item->SupplierPartAuxiliaryID = Cxml::firstNode($sxe, 'ItemID/SupplierPartAuxiliaryID');
        $item->SupplierPartID = Cxml::firstNode($sxe, 'ItemID/SupplierPartID');
        $item->Tax = Cxml::firstNode($sxe, 'Tax/Money');
        $item->Tax_Description = Cxml::firstNode($sxe, 'Tax/Description');
        $item->UnitOfMeasure = Cxml::firstNode($sxe, 'ItemDetail/UnitOfMeasure');
        $item->UnitPrice = Cxml::firstNode($sxe, 'ItemDetail/UnitPrice/Money');
        $item->UNSPSC = Cxml::firstNode($sxe, "ItemDetail/Classification[@domain='UNSPSC']");
        return $item;
    }

    /**
     * Create an ItemIn from array of data.
     *
     * @param array $arr
     * @return CxmlItemIn
     */
    public static function fromArray($arr)
    {
        $item = new CxmlItemIn();
        $item->contents = $arr;
        return $item;
    }

    /**
     * Magic getter provides access to content fields with object->property syntax.
     *
     * @param mixed $key
     *            key of the content array to get.
     * @return mixed
     */
    public function __get($key)
    {
        return $this->offsetGet($key);
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
        if ($index) {
            $this->contents[$index] = $value;
        } else {
            $this->contents[] = $value;
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
