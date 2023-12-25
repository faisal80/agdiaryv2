<?php

/**
 * This is the model class for table "{{officers}}".
 *
 * The followings are the available columns in table '{{officers}}':
 * @property string $id
 * @property string $officer_name
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
			array('officer_name', 'length', 'max'=>100),
			array('create_user, update_user', 'length', 'max'=>10),
			array('create_time, update_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, officer_name, create_time, create_user, update_time, update_user', 'safe', 'on'=>'search'),
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
			'assignment'=>array(self::HAS_ONE, 'Assignments', 'officer_id', 'condition'=>'state=1'),
			'designation'=>array(self::HAS_ONE, 'Designation', array('designation_id'=>'id'), 'through'=>'assignment'),
			'duties'=>array(self::HAS_MANY, 'Duties', array('officer_id', 'duty_id'),'through'=>'assignments', 'condition'=>'state=1'),
			//'user'=>array(self::HAS_ONE, 'Users', array=>('user_id', 'officer_id'), 'through' =>'' , 'condition'=>'state=1'),
			'assignments'=>array(self::HAS_MANY, 'Assignments', 'officer_id', 'condition'=>'state=1'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'officer_name' => 'Officer Name',
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
		$criteria->compare('officer_name',$this->officer_name,true);
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
	  	$officersArray = CHtml::listData($_Officers, 'id', 'officer_name');
	  	return $officersArray;
	}
	
	public function getFunctions()
	{
		$assignments = $this->assignments;
		$result = $assignments[0]->designation->short_form;
		$duties = ' (';
		foreach ($assignments as $assignment) {
			$duties = $duties . $assignment->duty->short_form . ', ';
		}
		$duties = strstr($duties, ', ', true) . ')';
		return $result . (strlen($duties)==3 ? '' : $duties); 
	}
}