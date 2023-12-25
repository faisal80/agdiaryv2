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
 * @property boolean $can_create_doc
 * @property boolean $can_create_blank_doc
 * @property string $create_time
 * @property string $create_user
 * @property string $update_time
 * @property string $update_user
 * @property string $designation
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
	public static function model($className = __CLASS__)
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
			array('newpasswd', 'required', 'on' => 'changepwd'),
			array('username', 'length', 'max' => 25),
			array('username', 'unique'),
			array('fullname, designation', 'length', 'max' => 100),
			array('passwd', 'length', 'max' => 255),
			array('passwd', 'compare', 'strict' => true, 'on'=>'insert'),
			array('newpasswd_repeat', 'compare', 'strict' =>true,  'compareAttribute' => 'newpasswd', 'on' => 'changepwd'),
			array('user_type, date_format', 'length', 'max' => 20),
			array('can_create_doc, can_create_blank_doc', 'default', 'value' => false),
			array('create_user, update_user', 'length', 'max' => 10),
			array('fullname, create_time, update_time, passwd_repeat', 'safe'),
			// The following rule is used by search().
			// array('fullname, create_time, update_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			// Please remove those attributes that should not be searched.
			array('id, fullname, designation, username, user_type, date_format, can_create_doc, create_time, create_user, update_time, update_user, can_create_doc, can_create_blank_doc', 'safe', 'on' => 'search'),
			// array('fullname, create_time, update_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			// array('fullname, create_time, update_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched. 
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
			'officers' => array(self::MANY_MANY, 'Officer', 'diaryv2_user_officer_assignment(user_id, officer_id)', 'group' => 'officer_name'),
			'officers_attached' => array(self::HAS_MANY, 'UserOfficerAssignment', 'user_id', 'condition'=>'end_date>="9999-12-31"'),
			'assignments' => array(self::HAS_MANY, 'Assignments', array('officer_id' => 'officer_id'), 'through' => 'officers_attached'),
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
			'designation' => 'Designation',
			'username' => 'Username',
			'passwd' => 'Password',
			'passwd_repeat' => 'Repeat Password',
			'user_type' => 'User Type',
			'date_format' => 'Date Format',
			'can_create_doc' => 'Can Create Document',
			'can_create_blank_doc' => 'Can Create Blank Document',
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

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id, true);
		$criteria->compare('fullname', $this->fullname, true);
		$criteria->compare('designation', $this->designation, true);
		$criteria->compare('username', $this->username, true);
		// $criteria->compare('passwd', $this->passwd, true);
		$criteria->compare('user_type', $this->user_type, true);
		$criteria->compare('date_format', $this->date_format, true);
		$criteria->compare('can_create_doc', $this->can_create_doc, true);
		$criteria->compare('can_create_blank_doc', $this->can_create_blank_doc, true);
		$criteria->compare('create_time', $this->create_time, true);
		$criteria->compare('create_user', $this->create_user, true);
		$criteria->compare('update_time', $this->update_time, true);
		$criteria->compare('update_user', $this->update_user, true);

		return new CActiveDataProvider(
			$this,
			array(
				'criteria' => $criteria,
			)
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($_username, $_passwd)
	{
		$this->_identity = new UserIdentity($_username, $_passwd);
		if ($this->_identity->authenticate()) {
			return true;
		} else {
			$this->addError('passwd', 'Invalid password');
			return false;
		}
	}

	/**
	 * perform one-way encryption on the password before we store it in	the database
	 */
	protected function beforeSave()
	{
		$this->passwd = $this->encrypt($this->passwd);
		return parent::beforeSave();
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
	public static function getListOfDesigByDutyID($short=false)
	{
		$_result = array();
		//get current user model
		$_user = User::model()->findByPk(Yii::app()->user->id);
		//get list of officers attached to current user
		//$_officers = $_user->officers_attached;
		//foreach ($_officers as $_officer) {
			//get list of duties assigned to the officer
			$_assignments = $_user->assignments;
			foreach ($_assignments as $_assignment) {
				if ($short) {
					$_result = $_result + array($_assignment->duty->id => $_assignment->designation->short_form . ' (' . $_assignment->duty->short_form . ')');
				} else {
					$_result = $_result + array($_assignment->duty->id => $_assignment->designation->title . ' (' . $_assignment->duty->duty . ')');
				}
			}
		//}
		return $_result;
	}

	// /**
	//  * setter function for passwd_repeat
	//  */
	// public function setPasswd_repeat($passwd_repeat)
	// {
	// 	$this->passwd_repeat = $passwd_repeat;
	// }

	public static function getAllUserTypes()
	{
		$tp = Yii::app()->db->tablePrefix;

		$userTypes = Yii::app()->db->createCommand()
			->selectDistinct('user_type', 'user_type')
			->from($tp . 'users')
			->queryAll();

		return CHtml::listData($userTypes, 'user_type', 'user_type');
	}

	public function getAttachedDutyIDs()
	{
		$_result = array();
		$officers = $this->officers;
		foreach ($officers as $officer) {
			$assignments = $officer->assignments;
			foreach ($assignments as $assignment) {
				$_result = $_result + array($assignment->duty_id);
			}
		}
		return $_result;
	}

	/**
	 * @return array It returns an intersect of list officers to whom document is marked and 
	 * the officers attached with current user
	 */
	public function getListofOfficers()
	{
		$officers = Assignments::getDesignationsListIndexedByDutyID();
		// $duty_ids = $this->getAttachedDutyIDs();
		$duty_ids = array_keys($officers);
		$result = array();
		foreach ($duty_ids as $duty_id) {
			$result = $this->hierarchical_duties($duty_id) + $result;
		}
		return $result;
	}

	protected function hierarchical_duties($duty_id)
	{
		$duty = Duty::model()->findByPk($duty_id);
		$result = array($duty->id => $duty->assigned_to->shortAssignment);
		if (!empty($duty->duty_id))
			return $this->hierarchical_duties($duty->duty_id) + $result;
		return $result;
	}

}
