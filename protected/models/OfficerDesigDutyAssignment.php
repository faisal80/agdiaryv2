<?php

/**
 * This is the model class for table "{{officer_desig_duty_assignment}}".
 *
 * The followings are the available columns in table '{{officer_desig_duty_assignment}}':
 * @property string $id
 * @property string $duty_id
 * @property string $officer_id
 * @property string $designation_id
 * @property integer $state
 * @property string $wef
 * @property string $create_time
 * @property string $create_user
 * @property string $update_time
 * @property string $update_user
 */
class OfficerDesigDutyAssignment extends DiaryActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return OfficerDesigDutyAssignment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{officer_desig_duty_assignment}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('duty_id, officer_id, designation_id', 'required'),
			array('state', 'numerical', 'integerOnly'=>true),
			array('duty_id, officer_id, designation_id, create_user, update_user', 'length', 'max'=>10),
			array('wef, create_time, update_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, duty_id, officer_id, designation_id, state, wef, create_time, create_user, update_time, update_user', 'safe', 'on'=>'search'),
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
			'officer'=>array(self::BELONGS_TO, 'Officer', 'officer_id'),
			'designation'=>array(self::BELONGS_TO, 'Designation', 'designation_id'),
			'duty'=>array(self::BELONGS_TO, 'Duty', 'duty_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'duty_id' => 'Duty',
			'officer_id' => 'Officer',
			'designation_id' => 'Designation',
			'state' => 'State',
			'wef' => 'W.E.F',
			'create_time' => 'Create Time',
			'create_user' => 'Create User',
			'update_time' => 'Update Time',
			'update_user' => 'Update User',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		// Formats the date as required by DB
		if ($this->wef<>'')
		{
			list($d, $m, $y) = explode('-', $this->wef);
        	$mk=mktime(0, 0, 0, $m, $d, $y);
        	$this->wef = date (Yii::app()->user->getDateFormat(), $mk);
		}
				
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('duty_id',$this->duty_id,true);
		$criteria->compare('officer_id',$this->officer_id,true);
		$criteria->compare('designation_id',$this->designation_id,true);
		$criteria->compare('state',$this->state);
		$criteria->compare('wef',$this->wef,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_user',$this->create_user,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_user',$this->update_user,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	// After finding the record
	protected function afterFind ()
  {
		$this->fixDate($this, 'wef', false);
        
    // call the parent class's function
    return parent::afterFind ();
  }

	// Before saving the record to the DB
  protected function beforeSave ()
  {
		// Formats the date as required by DB
		if (stripos($this->wef, Yii::app()->user->getDateSeparator())==2)
			$this->fixDate($this, 'wef');

    //call the function from parent class
    return parent::beforeSave ();
	}

	/**
	 * This method is invoked before an AR finder executes a find call.
	 */
	protected function beforeFind() 
	{
		// Formats the date as required by DB
		if (stripos($this->wef,  Yii::app()->user->getDateSeparator())==2)
			$this->fixDate($this, 'wef');

		parent::beforeFind();
	}
		
		
	/**
	 * @return array of active Designations & Duties attached with current user indexed with duty id
	 */
	public static function getDesignationsListIndexedByDutyID1()
	{
		$result = array();
		// Find all officers attached to current user
		$_officers = UserOfficerAssignment::model()->findAll('user_id=:user_id', array(':user_id'=>Yii::app()->user->id));
		//$_officer_ids = CHtml::listData($_officer_ids, 'id');
		foreach ($_officers as $_officer)
		{
			//Find all duties assigned to each officer attached with this user
			$_assignments = OfficerDesigDutyAssignment::model()->findAll('officer_id=:officer_id AND state=1', array(':officer_id'=>$_officer->officer_id));
			foreach ($_assignments as $_assignment)
			{
				$result = $result + array($_assignment->duty->id=>$_assignment->designation->title . ' (' . $_assignment->duty->duty . ')');
			}
		}
		return $result;
	}

	/**
	 * @return array of active Designations & Duties attached with current user indexed with id
	 */
	public static function getDesignationsListIndexedByDutyID()
	{
		$result = array();
		//Find all active duty assignments
		$_assignments = OfficerDesigDutyAssignment::model()->findAll('state=1');
		foreach ($_assignments as $_assignment)
		{
			$result = $result + array($_assignment->duty->id=>$_assignment->designation->title . ' (' . $_assignment->duty->duty . ')');
		}
		return $result;
	}
	
}