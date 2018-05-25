<?php

/**
 * SendOrderForm holds the information needed to convert a cart to an order.
 *
 * @author Brian Newsham
 *
 */
class SendOrderForm extends CFormModel
{
    public $line_item_id;
    public $order_id;
    public $poom_id;
    public $endpoint_id;
    public $po_num;
    public $ship_DeliverTo0;
    public $ship_DeliverTo1;
    public $ship_DeliverTo2;
    public $ship_Street;
    public $ship_City;
    public $ship_State;
    public $ship_PostalCode;
    public $ship_isoCountryCode;
    public $bill_DeliverTo0;
    public $bill_DeliverTo1;
    public $bill_DeliverTo2;
    public $bill_Street;
    public $bill_City;
    public $bill_State;
    public $bill_PostalCode;
    public $bill_isoCountryCode;

    /**
     * Load the order_id and poom_id from the line_item_id.
     *
     */
    public function preLoad()
    {
        $line_item = StoreOrderLine::model()->findByPk($this->line_item_id);
        $this->order_id = $line_item->order_id;
        $CxmlCart = json_decode($line_item->getEavAttribute('CxmlCart'));
        $this->poom_id = $CxmlCart->cxml_archive_id;
    }

    /**
     * (non-PHPdoc)
     * @see CModel::rules()
     */
    public function rules()
    {
        return array(
            array(
                'line_item_id, order_id, poom_id, po_num, ship_DeliverTo0, ship_Street, ship_City, ship_State, '
                . 'ship_PostalCode, bill_DeliverTo0, bill_Street, bill_City, bill_State, bill_PostalCode, endpoint_id',
                'required'
            ),
            array(
                'ship_DeliverTo0, ship_DeliverTo1, ship_DeliverTo2, ship_Street, ship_City, bill_DeliverTo0, '
                . 'bill_DeliverTo1, bill_DeliverTo2, bill_Street, bill_City',
                'length',
                'max' => 35
            ),
            array(
                'ship_State, ship_isoCountryCode, bill_State, bill_isoCountryCode',
                'length',
                'max' => 2
            ),
            array(
                'ship_PostalCode, bill_PostalCode',
                'length',
                'max' => 10
            ),
            array(
                'endpoint_id',
                'numerical',
                'integerOnly' => true
            ),
        );
    }

    /**
     * (non-PHPdoc)
     * @see CModel::attributeLabels()
     */
    public function attributeLabels()
    {
        return array(
            'po_num' => 'Purchase Order #',
            'ship_DeliverTo0' => 'Ship Deliver To (1)',
            'ship_DeliverTo1' => 'Ship Deliver To (2)',
            'ship_DeliverTo2' => 'Ship Deliver To (3)',
            'bill_DeliverTo0' => 'Bill Deliver To (1)',
            'bill_DeliverTo1' => 'Bill Deliver To (2)',
            'bill_DeliverTo2' => 'Bill Deliver To (3)',
        );
    }

    /**
     * Get or create a CxmlAddressStore from the fields provided, and use it to populate a CxmlAddress.
     * @param array $fields
     * @return CxmlAddress
     */
    protected function getCxmlAddress($fields)
    {
        $cas = CxmlAddressStore::model()->findByAttributes(
            [
                'street' => $fields['street'],
                'city' => $fields['city'],
                'state' => $fields['state'],
                'postalcode' => $fields['postalcode'],
                'isocountrycode' => $fields['isocountrycode'],
            ]
        );
        if (!$cas) {
            $cas = new CxmlAddressStore();
            $cas->street = $fields['street'];
            $cas->city = $fields['city'];
            $cas->state = $fields['state'];
            $cas->postalcode = $fields['postalcode'];
            $cas->isocountrycode = $fields['isocountrycode'];
            $cas->is_deleted = 'N';
            $cas->save();
        }

        $ca = new CxmlAddress();
        $ca['addressID'] = $cas->addressid;
        $ca['Name'] = $fields['name'];
        for ($i = 0; $i <= 2; $i++) {
            $attribute = 'deliverto' . $i;
            if (strlen($fields[$attribute])) {
                $ca['DeliverTo'] = $fields[$attribute];
            }
        }
        $ca['Street'] = $cas->street;
        $ca['City'] = $cas->city;
        $ca['State'] = $cas->state;
        $ca['PostalCode'] = $cas->postalcode;
        $ca['Country'] = $this->getCountryName($cas->isocountrycode);
        $ca['Country_isoCountryCode'] = $cas->isocountrycode;
        return $ca;
    }

    /**
     * Construct a CxmlAddress from the "Bill To" fields.
     *
     * @return CxmlAddress
     */
    public function getBillToAddress()
    {
        return $this->getCxmlAddress(
            array(
                'name' => $this->bill_DeliverTo0 . ' ' . $this->bill_Street,
                'deliverto0' => $this->bill_DeliverTo0,
                'deliverto1' => $this->bill_DeliverTo1,
                'deliverto2' => $this->bill_DeliverTo2,
                'street' => $this->bill_Street,
                'city' => $this->bill_City,
                'state' => $this->bill_State,
                'postalcode' => $this->bill_PostalCode,
                'isocountrycode' => $this->bill_isoCountryCode,
            )
        );
    }

    /**
     * Construct a CxmlAddress from the "Ship To" fields.
     *
     * @return CxmlAddress
     */
    public function getShipToAddress()
    {
        return $this->getCxmlAddress(
            array(
                'name' => $this->ship_DeliverTo0 . ' ' . $this->ship_Street,
                'deliverto0' => $this->ship_DeliverTo0,
                'deliverto1' => $this->ship_DeliverTo1,
                'deliverto2' => $this->ship_DeliverTo2,
                'street' => $this->ship_Street,
                'city' => $this->ship_City,
                'state' => $this->ship_State,
                'postalcode' => $this->ship_PostalCode,
                'isocountrycode' => $this->ship_isoCountryCode,
            )
        );
    }

    /**
     * Get the full name of a country from its ISO code.
     *
     * @param string $isoCountryCode
     * @throws Exception
     * @return string Full name of the country.
     */
    public function getCountryName($isoCountryCode)
    {
        switch ($isoCountryCode) {
            case 'US':
                return 'United States';
            case 'CA':
                return 'Canada';
            default:
                throw new Exception("Unknown country code '$isoCountryCode'");
                break;
        }
    }
}
