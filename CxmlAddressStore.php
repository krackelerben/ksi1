<?php

/**
 * This is the model class for table "cxml_address_store".
 *
 * The followings are the available columns in table 'cxml_address_store':
 * @property integer $id
 * @property string $hash
 * @property string $addressid
 * @property string $name
 * @property string $deliverto0
 * @property string $deliverto1
 * @property string $deliverto2
 * @property string $street
 * @property string $city
 * @property string $state
 * @property string $postalcode
 * @property string $isocountrycode
 * @property string $is_deleted
 * @property string $create_time Date/time the message was created.
 * @property integer $create_user_id ID of the User that created this message.
 * @property string $update_time Date/time the message was last updated.
 * @property integer $update_user_id ID of the User that last updated this message.
 *
 * The followings are the available model relations:
 * @property User $creater
 * @property User $updater
 */
class CxmlAddressStore extends KrackelerActiveRecord
{

    /**
     *
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'cxml_address_store';
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
                'hash, addressid, name, street, city, state, postalcode, isocountrycode, is_deleted',
                'required'
            ),
            array(
                'create_user_id, update_user_id',
                'numerical',
                'integerOnly' => true
            ),
            array(
                'hash',
                'length',
                'max' => 255
            ),
            array(
                'addressid',
                'length',
                'max' => 16
            ),
            array(
                'name',
                'length',
                'max' => 128
            ),
            array(
                'deliverto0, deliverto1, deliverto2, street',
                'length',
                'max' => 50
            ),
            array(
                'city',
                'length',
                'max' => 25
            ),
            array(
                'state, isocountrycode',
                'length',
                'max' => 2
            ),
            array(
                'postalcode',
                'length',
                'max' => 10
            ),
            array(
                'is_deleted',
                'length',
                'max' => 1
            ),
            array(
                'create_time, update_time',
                'safe'
            ),

            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'id, hash, addressid, name, deliverto0, deliverto1, deliverto2, street, city, state, postalcode, '
                . 'isocountrycode, is_deleted, create_time, create_user_id, update_time, update_user_id',
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
            'creater' => array(self::BELONGS_TO, 'User', 'create_user_id'),
            'updater' => array(self::BELONGS_TO, 'User', 'update_user_id'),
        );
    }

    /**
     * Returns a list of behaviors that this model should behave as.
     *
     * @return array
     */
    public function behaviors()
    {
        return array(
            'SoftDeleteBehavior' => array(
                'class' => 'application.components.SoftDeleteBehavior'
            ),
            'DisableDefaultScopeBehavior' => array(
                'class' => 'application.components.DisableDefaultScopeBehavior'
            )
        );
    }

    /**
     * Returns the declaration of named scopes.
     *
     * @return array
     */
    public function scopes()
    {
        $alias = $this->getTableAlias(false, false);
        return array(
            'trashed' => array(
                'condition' => "`$alias`.`is_deleted`='Y'"
            )
        );
    }

    /**
     * (non-PHPdoc)
     *
     * @see CActiveRecord::defaultScope()
     */
    public function defaultScope()
    {
        $alias = $this->getTableAlias(false, false);
        return $this->getDefaultScopeDisabled() ? array() : array(
            'condition' => "`$alias`.`is_deleted`='N'"
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
            'hash' => 'Hash',
            'addressid' => 'Address ID',
            'name' => 'Name',
            'deliverto0' => 'Deliver To 1',
            'deliverto1' => 'Deliver To 2',
            'deliverto2' => 'Deliver To 3',
            'street' => 'Street',
            'city' => 'City',
            'state' => 'State',
            'postalcode' => 'Postal Code',
            'isocountrycode' => 'ISO country code',
            'is_deleted' => 'Is Deleted',
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
        $criteria->compare('hash', $this->hash, true);
        $criteria->compare('addressid', $this->addressid, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('deliverto0', $this->deliverto0, true);
        $criteria->compare('deliverto1', $this->deliverto1, true);
        $criteria->compare('deliverto2', $this->deliverto2, true);
        $criteria->compare('street', $this->street, true);
        $criteria->compare('city', $this->city, true);
        $criteria->compare('state', $this->state, true);
        $criteria->compare('postalcode', $this->postalcode, true);
        $criteria->compare('isocountrycode', $this->isocountrycode, true);
        $criteria->compare('is_deleted', $this->is_deleted, true);
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
     * @return CxmlAddressStore the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * (non-PHPdoc)
     * @see KrackelerActiveRecord::beforeValidate()
     */
    protected function beforeValidate()
    {
        if (!strlen($this->name)) {
            $this->name = $this->street . ', ' . $this->city . ', ' . $this->state . ' ' . $this->postalcode;
        }
        $hash = $this->makeHash();
        $this->hash = $hash;
        $this->addressid = substr($hash, 0, 6);
        return parent::beforeValidate();
    }

    /**
     * Calculate the address hash.
     *
     * The hash and addressid can be updated directly in the database with this query
     * UPDATE cxml_address_store
     * SET hash=SHA1(CONCAT(
     *       street, "\n===\n",
     *       city, "\n===\n",
     *       state, "\n===\n",
     *       postalcode, "\n===\n",
     *       isocountrycode)),
     *     addressid=LEFT(hash, 6)
     * WHERE id=N;
     *
     * @return string
     */
    public function makeHash()
    {
        return sha1(
            implode(
                "\n===\n",
                [
                    $this->street,
                    $this->city,
                    $this->state,
                    $this->postalcode,
                    $this->isocountrycode
                ]
            )
        );
    }
}
