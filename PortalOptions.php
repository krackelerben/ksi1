<?php

/**
 * PortalOptions class represents the inputs needed to create or edit a PunchOut cart.
 *
 * @author Brian Newsham
 *
 */
class PortalOptions extends CFormModel
{
    /**
     * PunchOut mode (create|edit)
     *
     * @var string
     */
    public $mode;

    /**
     * Declares the validation rules.
     *
     * @return array
     */
    public function rules()
    {
        return array(
            // mode is required
            array(
                'mode',
                'required'
            ),
            array(
                'mode',
                'in',
                'range' => array('create', 'edit'),
                'allowEmpty' => false
            ),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     *
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'mode' => 'Mode',
        );
    }
}
