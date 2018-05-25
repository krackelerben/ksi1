<?php

/**
 * This is the model class for table "cxml_endpoint".
 *
 * The followings are the available columns in table 'cxml_endpoint':
 * @property integer $id
 * @property string $name
 * @property integer $customer_id
 * @property string $setup_url
 * @property string $order_url
 * @property string $from_domain
 * @property string $from_identity
 * @property string $to_domain
 * @property string $to_identity
 * @property string $sender_domain
 * @property string $sender_identity
 * @property string $sender_shared_secret
 * @property string $deployment_mode
 * @property string $extrinsics
 * @property string $contacts
 * @property string $ship_to
 * @property string $create_time Date/time the message was created.
 * @property integer $create_user_id ID of the User that created this message.
 * @property string $update_time Date/time the message was last updated.
 * @property integer $update_user_id ID of the User that last updated this message.
 */
class CxmlEndpoint extends KrackelerActiveRecord
{

    /**
     *
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'cxml_endpoint';
    }

    /**
     *
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(
                'customer_id, name, setup_url, order_url, from_domain, from_identity, to_domain, to_identity, '
                . 'sender_domain, sender_identity, sender_shared_secret, deployment_mode',
                'required'
            ),
            array(
                'customer_id, create_user_id, update_user_id',
                'numerical',
                'integerOnly' => true
            ),
            array(
                'name, from_domain, from_identity, to_domain, to_identity, sender_domain, sender_identity',
                'length',
                'max' => 64
            ),
            array(
                'setup_url, order_url, sender_shared_secret',
                'length',
                'max' => 255
            ),
            array(
                'deployment_mode',
                'length',
                'max' => 16
            ),
            array(
                'extrinsics, contacts, ship_to, create_time, update_time',
                'safe'
            ),

            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'id, customer_id, name, url, from_domain, from_identity, to_domain, to_identity, sender_domain, '
                . 'sender_identity, sender_shared_secret, deployment_mode, extrinsics, contacts, ship_to, '
                . 'create_time, create_user_id, update_time, update_user_id',
                'safe',
                'on' => 'search'
            )
        );
    }

    /**
     *
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'creater' => array(
                self::BELONGS_TO,
                'User',
                'create_user_id'
            ),
            'updater' => array(
                self::BELONGS_TO,
                'User',
                'update_user_id'
            )
        );
    }

    /**
     *
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'customer_id' => 'Customer ID',
            'name' => 'Name',
            'setup_url' => 'Setup URL',
            'order_url' => 'Order URL',
            'from_domain' => 'From Domain',
            'from_identity' => 'From Identity',
            'to_domain' => 'To Domain',
            'to_identity' => 'To Identity',
            'sender_domain' => 'Sender Domain',
            'sender_identity' => 'Sender Identity',
            'sender_shared_secret' => 'Sender Shared Secret',
            'deployment_mode' => 'Deployment Mode',
            'extrinsics' => 'Extrinsics',
            'contacts' => 'Contacts',
            'ship_to' => 'Ship To',
            'create_time' => 'Create Time',
            'create_user_id' => 'Create User',
            'update_time' => 'Update Time',
            'update_user_id' => 'Update User'
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     *         based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.
        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('customer_id', $this->customer_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('setup_url', $this->setup_url, true);
        $criteria->compare('order_url', $this->order_url, true);
        $criteria->compare('from_domain', $this->from_domain, true);
        $criteria->compare('from_identity', $this->from_identity, true);
        $criteria->compare('to_domain', $this->to_domain, true);
        $criteria->compare('to_identity', $this->to_identity, true);
        $criteria->compare('sender_domain', $this->sender_domain, true);
        $criteria->compare('sender_identity', $this->sender_identity, true);
        $criteria->compare('sender_shared_secret', $this->sender_shared_secret, true);
        $criteria->compare('deployment_mode', $this->deployment_mode, true);
        $criteria->compare('extrinsics', $this->extrinsics, true);
        $criteria->compare('contacts', $this->contacts, true);
        $criteria->compare('ship_to', $this->ship_to, true);
        $criteria->compare('create_time', $this->create_time, true);
        $criteria->compare('create_user_id', $this->create_user_id);
        $criteria->compare('update_time', $this->update_time, true);
        $criteria->compare('update_user_id', $this->update_user_id);

        return new CActiveDataProvider(
            $this,
            array(
                'criteria' => $criteria
            )
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     *
     * @param string $className
     *            active record class name.
     * @return CxmlEndpoint the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Define the list of options for the domain attributes.
     *
     * @return array
     */
    public function domainOptions()
    {
        return array(
            'DUNS' => 'DUNS',
            'NetworkID' => 'NetworkID',
            'AribaNetworkUserId' => 'AribaNetworkUserId'
        );
    }

    /**
     * Define the list of options for the deploymentMode attribute.
     *
     * @return array
     */
    public function deploymentModeOptions()
    {
        return array(
            'production' => 'production',
            'test' => 'test'
        );
    }
}
