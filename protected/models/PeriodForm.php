<?php

/**
 * PeriodForm class.
 * PeriodForm is the data structure for keeping
 * period form data. It is used by the 'period' action of 'DiaryreportController'.
 */
class PeriodForm extends CFormModel {

    public $datefrom;
    public $dateto;

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            // both dates are required
            array('datefrom, dateto', 'required'),
            // must a valid dates
            array('datefrom, dateto', 'type', 'type' => 'date', 'dateFormat' => 'yyyy-MM-dd'),
            // dateto must be greater or equal to datefrom
            array('dateto', 'compare', 'compareAttribute' => 'datefrom', 'operator' => '>=', 'allowEmpty' => false, 'message' => '{attribute} must be greater than "{compareValue}".'),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels() {
        return array(
            'datefrom' => 'Date From',
            'dateto' => 'Date To',
        );
    }

    public function beforeValidate() {

        list($d, $m, $y) = explode('-', $this->datefrom);
        $mk = mktime(0, 0, 0, $m, $d, $y);
        $this->datefrom = date('Y-m-d', $mk);

        list($d, $m, $y) = explode('-', $this->dateto);
        $mk = mktime(0, 0, 0, $m, $d, $y);
        $this->dateto = date('Y-m-d', $mk);

        return parent::beforeValidate();
    }

}