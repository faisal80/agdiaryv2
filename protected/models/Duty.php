<?php

/**
 * This is the model class for table "{{duties}}".
 *
 * The followings are the available columns in table '{{duties}}':
 * @property string $id
 * @property string $duty_id
 * @property string $duty
 * @property string $short_form Short form of duty for chart
 * @property string $info
 * @property string $section_id
 * @property string $create_time
 * @property string $create_user
 * @property string $update_time
 * @property string $update_user
 */
class Duty extends DiaryActiveRecord
{
	public $null_value_for_duty_id;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Duty the static model class
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
		return '{{duties}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('duty', 'required'),
			array('duty_id, create_user, update_user, section_id', 'length', 'max'=>10),
			array('short_form', 'length', 'max'=>50),
			array('duty, info', 'length', 'max'=>100),
			array('create_time, update_time, section_id', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, duty_id, duty, short_form, info, section_id, create_time, create_user, update_time, update_user', 'safe', 'on'=>'search'),
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
			'parent_duty'=>array(self::BELONGS_TO, 'Duty', 'duty_id'),
			'child_duties'=>array(self::HAS_MANY, 'Duty', 'duty_id'),
			'assigned_to'=>array(self::HAS_ONE, 'Assignments', 'duty_id', 'condition'=>'state=1'),
			'designation'=>array(self::HAS_ONE, 'Designation', array('designation_id'=>'id'), 'through'=>'assigned_to'),
			'section'=>array(self::BELONGS_TO, 'Section', 'section_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'duty_id' => 'Parent Duty',
			'duty' => 'Duty',
			'short_form' => 'Short Form',
			'info' => 'Info',
			// 'section_id'=>'Section Name',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('duty_id',$this->duty_id,true);
		$criteria->compare('duty',$this->duty,true);
		$criteria->compare('short_form', $this->short_form, true);
		$criteria->compare('info',$this->info,true);
		$criteria->compare('section_id', $this->section_id, true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_user',$this->create_user,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_user',$this->update_user,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getFullDuty() {
		return $this->duty . ' (' . $this->info . ')';
	}

	/**
	 * Returns a list of all duties for drop down list
	 * @param boolean $excludedAssigned Exclude assigned duties from the list.
	 * @return array A list of duties
	 */
	public static function getDutiesList($excludedAssigned=false)
	{
		/*
		$duties= Duty::model()->findAll();
		$result = array(null=>'None');
		foreach ($duties as $duty) {
			if (!($excludedAssigned && !empty($duty->assigned_to)))
				$result =  $result + array($duty->id => $duty->getFullDuty());
		}
		return $result;
		*/

		if ($excludedAssigned)
			return CHtml::listData(Duty::model()->findAllBySQL('SELECT a.id, a.duty, a.info 
																FROM {{duties}} a
																LEFT JOIN {{officer_desig_duty_assignment}} b
																	ON a.id = b.duty_id	
																	WHERE b.duty_id IS NULL
																	OR b.state=0
																ORDER BY a.info, a.duty'), 
																'id', 'duty', 'info');	

		return CHtml::listData(Duty::model()->findAllBySQL('SELECT id, duty, info FROM {{duties}} ORDER BY info, duty'), 'id', 'duty', 'info');
	}
		
	public function isAttachedToUser($userID)
	{
		//get current user model
		$_user = User::model()->findByPk(Yii::app()->user->id);
		//get list of officers attached to current user
		$_officers = $_user->officers;
		foreach ($_officers as $_officer) {
			//get list of duties assigned to the officer
			$_assignments = $_officer->assignments;
			foreach ($_assignments as $_assignment) {
				if ($this->id == $_assignment->duty_id) return true;
			}
		}
		return false;
	}
}
