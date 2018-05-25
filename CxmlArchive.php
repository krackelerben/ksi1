<?php

/**
 * This is the model class for table "cxml_archive".
 *
 * The followings are the available columns in table 'cxml_archive':
 * @property integer $id
 * @property integer $cxml_endpoint_id
 * @property integer $user_id
 * @property string $buyer_cookie
 * @property string $cxml_class
 * @property string $cxml_doc
 * @property string $create_time Date/time the message was created.
 * @property integer $create_user_id ID of the User that created this message.
 * @property string $update_time Date/time the message was last updated.
 * @property integer $update_user_id ID of the User that last updated this message.
 */
class CxmlArchive extends KrackelerActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'cxml_archive';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cxml_endpoint_id, buyer_cookie, cxml_class', 'required'),
            array('cxml_endpoint_id, user_id, create_user_id, update_user_id', 'numerical', 'integerOnly'=>true),
            array('buyer_cookie, cxml_class', 'length', 'max'=>255),
            array('cxml_doc, create_time, update_time', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, cxml_endpoint_id, user_id, buyer_cookie, cxml_class, cxml_doc, create_time, create_user_id, '
                . 'update_time, update_user_id', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'endpoint' => array(self::BELONGS_TO, 'CxmlEndpoint', 'cxml_endpoint_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'cxml_endpoint_id' => 'Cxml Endpoint',
            'user_id' => 'User',
            'buyer_cookie' => 'Buyer Cookie',
            'cxml_class' => 'Cxml Class',
            'cxml_doc' => 'Cxml Doc',
            'create_time' => 'Create Time',
            'create_user_id' => 'Create User',
            'update_time' => 'Update Time',
            'update_user_id' => 'Update User',
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
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('cxml_endpoint_id', $this->cxml_endpoint_id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('buyer_cookie', $this->buyer_cookie, true);
        $criteria->compare('cxml_class', $this->cxml_class, true);
        $criteria->compare('cxml_doc', $this->cxml_doc, true);
        $criteria->compare('create_time', $this->create_time, true);
        $criteria->compare('create_user_id', $this->create_user_id);
        $criteria->compare('update_time', $this->update_time, true);
        $criteria->compare('update_user_id', $this->update_user_id);

        return new CActiveDataProvider(
            $this,
            array(
                'criteria'=>$criteria,
            )
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return CxmlArchive the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Instantiate cxml_doc as its intended type ($cxml_class).
     *
     * @return CxmlDocument
     */
    public function makeDocument()
    {
        $cxml_class = $this->cxml_class;
        return new $cxml_class($this->cxml_doc);
    }
}
