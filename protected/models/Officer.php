<?php

/**
 * This is the model class for table "{{officers}}".
 *
 * The followings are the available columns in table '{{officers}}':
 * @property string $id
 * @property string $name
 * @property string $create_time
 * @property string $create_user
 * @property string $update_time
 * @property string $update_user
 */
class Officer extends DiaryActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Officer the static model class
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
		return '{{officers}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'length', 'max'=>100),
			array('create_user, update_user', 'length', 'max'=>10),
			array('create_time, update_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, create_time, create_user, update_time, update_user', 'safe', 'on'=>'search'),
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
			'assignment'=>array(self::HAS_ONE, 'OfficerDesigDutyAssignment', 'officer_id', 'condition'=>'state=1'),
			'designation'=>array(self::HAS_ONE, 'Designation', array('designation_id'=>'id'), 'through'=>'assignment'),
			'duties'=>array(self::MANY_MANY, 'Duties', 'diaryv2_officer_desig_duty_assignment(officer_id, duty_id, state)', 'condition'=>'state=1'),
			'user'=>array(self::HAS_ONE, 'Users', 'diaryv2_user_officer_assignment(user_id, officer_id)', 'condition'=>'state=1'),
			'assignments'=>array(self::HAS_MANY, 'OfficerDesigDutyAssignment', 'officer_id', 'condition'=>'state=1'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_user',$this->create_user,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_user',$this->update_user,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return array of Officers, indexed by IDs
	 */ 
	public static function getListofOfficers()
	{ 
		//lists all officers 
		$_Officers = Officer::model()->findAll();
	  	$officersArray = CHtml::listData($_Officers, 'id', 'name');
	  	return $officersArray;
	}
	
}