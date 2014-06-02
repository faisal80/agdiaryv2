<?php

/**
 * This is the model class for table "{{users}}".
 *
 * The followings are the available columns in table '{{users}}':
 * @property string $id
 * @property string $username
 * @property string $passwd
 * @property string $user_type
 * @property string $date_format
 * @property string $can_create_doc
 * @property string $create_time
 * @property string $create_user
 * @property string $update_time
 * @property string $update_user
 */
class User extends DiaryActiveRecord
{
	public $passwd_repeat;
	public $newpasswd;
	public $newpasswd_repeat;
	
	private $_identity; 
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
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
		return '{{users}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, passwd', 'required'),
			array('newpasswd', 'required', 'on'=>'changepwd'),
			array('username', 'length', 'max'=>25),
			array('username', 'unique'),
			array('fullname', 'length', 'max'=>100),
			array('passwd', 'length', 'max'=>255),
			array('passwd', 'compare', 'on'=>'insert'),
			array('newpasswd_repeat', 'compare', 'compareAttribute'=>'newpasswd', 'on'=>'changepwd'),
			array('user_type, date_format', 'length', 'max'=>20),
			array('can_create_doc', 'length', 'max'=>5),
			array('create_user, update_user', 'length', 'max'=>10),
			array('fullname, create_time, update_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, fullname, username, passwd, user_type, date_format, can_create_doc, create_time, create_user, update_time, update_user', 'safe', 'on'=>'search'),
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
			'officers'=>array(self::MANY_MANY, 'Officer', 'diaryv2_user_officer_assignment(user_id, officer_id)', 'group'=>'name'),
			'officers_attached'=>array(self::HAS_MANY, 'UserOfficerAssignment', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'fullname' => 'Full Name',
			'username' => 'Username',
			'passwd' => 'Password',
			'user_type' => 'User Type',
			'date_format' => 'Date Format',
			'can_create_doc' => 'Can Create Document',
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
		$criteria->compare('fullname', $this->fullname, true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('passwd',$this->passwd,true);
		$criteria->compare('user_type',$this->user_type,true);
		$criteria->compare('date_format',$this->date_format,true);
		$criteria->compare('can_create_doc',$this->can_create_doc,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_user',$this->create_user,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_user',$this->update_user,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($_username, $_passwd)
	{
		$this->_identity=new UserIdentity($_username,$_passwd);
		if($this->_identity->authenticate())
		{
			return true;
		} else {
			$this->addError('passwd', 'Invalid password');
			return false;
		}
	}
	
	/**
	 * perform one-way encryption on the password before we store it in	the database
	 */
	protected function afterValidate()
	{
		parent::afterValidate();
		$this->passwd = $this->encrypt($this->passwd);
	}
	
	public function encrypt($value)
	{
		return md5($value);
	}
	
	/**
	 * @return array of Users, indexed by user IDs
	 */ 
	public static function getListofUsers()
	{ 
		//lists all users 
		$_Users = User::model()->findAll();
	  	$usersArray = CHtml::listData($_Users, 'id', 'username');
	  	return $usersArray;
	}

	/**
	 * @return array of active Designations & Duties attached with current user indexed with Duty id
	 */
	public static function getListOfDesigByDutyID()
	{
		$_result = array();
		//get current user model
		$_user = User::model()->findByPk(Yii::app()->user->id);
		//get list of officers attached to current user
		$_officers = $_user->officers;
		foreach ($_officers as $_officer)
		{
			//get list of duties assigned to the officer
			$_assignments = $_officer->assignments;
			foreach ($_assignments as $_assignment)
			{
				$_result = $_result + array($_assignment->duty->id => $_assignment->designation->title . ' ('. $_assignment->duty->duty . ')');
			}
		}
		return $_result;
	}
}