<?php

/**
 * ListofDocsForm class.
 * ListofDocsForm is the data structure for keeping
 * ListofDocs form data. It is used by the 'ListofDocs' action of 'DiaryreportController'.
 */
class ListofDocsForm extends CFormModel {

    public $officer_id;

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            // both dates are required
            array('officer_id', 'required'),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels() {
        return array(
            'officer_id' => 'Officer',
        );
    }

}