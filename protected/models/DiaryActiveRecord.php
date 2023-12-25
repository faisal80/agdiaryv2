<?php
abstract class DiaryActiveRecord extends CActiveRecord
{
	
	/**
	 * Prepares create_time, create_user, update_time and update_user attributes before performing validation.
	 */
  	protected function beforeValidate()
  	{	
      	if($this->isNewRecord)
    	{
	     	// set the create date, last updated date and the user doing the creating  
	      	$this->create_time=$this->update_time=new CDbExpression('NOW()');
	        $this->create_user=$this->update_user=Yii::app()->user->id;
        }
	    else
	    {
		 	//not a new record, so just set the last updated time and last updated user id     
      		$this->update_time=new CDbExpression('NOW()');
      		$this->update_user=Yii::app()->user->id;
    	}
    
    	return parent::beforeValidate();
    }

	/**
	 * Validates Date
	 * @param type $attribute
	 * @param type $params
	 */
    public function validate_date($attribute,$params)
    {
		$ds = $params[0];
		$sDate = str_replace($ds, '-', $this->getAttribute($attribute));
		if (preg_match('/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}$/', $sDate, $xadBits))
			if (checkdate($xadBits[1], $xadBits[2], $xadBits[3]))
	            $this->addError($attribute,'Incorrect date.');
    }

	/**
	 * This method sets the date as per DB requirement or the App requirement
	 */
	protected function fixDate(&$model, $attribute, $forDB=true)
	{
		$ds = Yii::app()->user->getDateSeparator();
		if ($forDB) // Set for DB
		{
			if ($model->getAttribute($attribute)<>'')
			{
				list($d, $m, $y) = explode($ds, $model->getAttribute($attribute));
				$mk=mktime(0, 0, 0, $m, $d, $y);
				$model->setAttribute($attribute, date('Y-m-d', $mk));
				return true;
			}
		} else {	// Set for App
			if ($model->getAttribute($attribute)<>'')
			{
				list($y, $m, $d) = explode('-', $model->getAttribute($attribute));
				$mk=mktime(0, 0, 0, (int)$m, (int)$d, (int)$y);
				$model->setAttribute($attribute, date(Yii::app()->user->getState('date_format'), $mk));
				return true;
			}
		}
		return false;		
	}
		
}