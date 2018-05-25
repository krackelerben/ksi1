<?php

/**
 * CxmlItemOutBuilder constructs CxmlItemOut objects.
 *
 * @author Brian Newsham
 *
 */
class CxmlItemOutBuilder
{
    /**
     * Line number counter.
     *
     * @var int
     */
    protected $lineNumber = 0;

    /**
     * cXML ItemOut value object.
     *
     * @var CxmlItemOut
     */
    protected $io;

    /**
     * Constructor.
     *
     */
    public function __construct()
    {
        $this->lineNumber = 0;
    }

    /**
     * Build a new CxmlItemOut.
     *
     * @return CxmlItemOutBuilder
     */
    public function newItem()
    {
        $this->io = new CxmlItemOut();
        return $this;
    }

    /**
     * Set the quantity desired.
     *
     * @param float $quantity
     * @return CxmlItemOutBuilder
     */
    public function setQuantity($quantity)
    {
        $this->io['quantity'] = $quantity;
        return $this;
    }

    /**
     * Set the line number using the internal counter.
     *
     * @return CxmlItemOutBuilder
     */
    public function setLineNumber()
    {
        $this->io['lineNumber'] = ++$this->lineNumber;
        return $this;
    }

    /**
     * Set the fields for the ItemID element.
     *
     * @param string $supplier_part_id
     * @param string $supplier_part_auxiliary_id
     * @param string $buyer_part_id
     * @return CxmlItemOutBuilder
     */
    public function setItemID($supplier_part_id, $supplier_part_auxiliary_id = null, $buyer_part_id = null)
    {
        $this->io['SupplierPartID'] = $supplier_part_id;
        $this->io['SupplierPartAuxiliaryID'] = $supplier_part_auxiliary_id;
        $this->io['BuyerPartID'] = $buyer_part_id;
        return $this;
    }

    /**
     * Set the unit price.
     *
     * @param float $price
     * @param string $currency
     * @return CxmlItemOutBuilder
     */
    public function setUnitPrice($price, $currency = 'USD')
    {
        $this->io['UnitPrice'] = $price;
        $this->io['UnitPrice_currency'] = $currency;
        return $this;
    }

    /**
     * Set the unit of measure.
     *
     * @param string $unit_of_measure
     * @return CxmlItemOutBuilder
     */
    public function setUnitOfMeasure($unit_of_measure)
    {
        $this->io['UnitOfMeasure'] = $unit_of_measure;
        return $this;
    }

    /**
     * Add a classification element.
     *
     * @todo ItemOut only has the UNSPSC classification.
     * @param string $classification
     * @param string $domain
     * @return CxmlItemOutBuilder
     */
    public function addClassification($classification, $domain)
    {
        $this->io['UNSPSC'] = $classification;
        return $this;
    }

    /**
     * Set the item description.
     *
     * @param string $description
     * @param string $short_name
     * @return CxmlItemOutBuilder
     */
    public function addDescription($description, $short_name = null)
    {
        $this->io['Description'] = $description;
        $this->io['Description_ShortName'] = $short_name;
        return $this;
    }

    /**
     * Set the item URL.
     *
     * @param string $url
     * @return CxmlItemOutBuilder
     */
    public function setURL($url = null)
    {
        $this->io['URL'] = $url;
        return $this;
    }

    /**
     * Set the manufacturer part ID.
     *
     * @param string $manufacturer_part_id
     * @return CxmlItemOutBuilder
     */
    public function setManufacturerPartID($manufacturer_part_id = null)
    {
        $this->io['ManufacturerPartID'] = $manufacturer_part_id;
        return $this;
    }

    /**
     * Set the manufacturer name.
     *
     * @param string $manufacturer_name
     * @return CxmlItemOutBuilder
     */
    public function setManufacturerName($manufacturer_name = null)
    {
        $this->io['ManufacturerName'] = $manufacturer_name;
        return $this;
    }

    /**
     * Get the CxmlItemOut being built.
     *
     * @return CxmlItemOut
     */
    public function getResult()
    {
        return $this->io;
    }
}
