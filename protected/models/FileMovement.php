<?php

/**
 * This is the model class for table "{{file_movement}}".
 *
 * The followings are the available columns in table '{{file_movement}}':
 * @property integer $id
 * @property integer $document_id
 * @property integer $received_by
 * @property string $marked_date
 * @property integer $marked_to
 * @property integer $marked_by
 * @property integer $file_id
 * @property string $received_time_stamp
 * @property string $create_time
 * @property string $create_user
 * @property string $update_time
 * @property string $update_user
 * @property integer $movement_count
 */
class FileMovement extends DiaryActiveRecord
{
	public $file_page_no = null; 

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{file_movement}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('marked_date, marked_to, marked_by, file_id, create_time, create_user, update_time, update_user', 'required'),
			array('document_id, received_by, marked_to, marked_by, file_id, movement_count', 'numerical', 'integerOnly' => true),
			array('create_time, update_time', 'length', 'max' => 100),
			array('create_user, update_user', 'length', 'max' => 25),
			array('file_page_no', 'length', 'max' => 20),
			array('recieved_time_stamp', 'safe'),
			array('document_id', 'exist', 'allowEmpty' => true, 'attributeName' => 'id', 'className' => 'Document'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, document_id, received_by, marked_date, marked_to, marked_by, file_id, received_date, create_time, create_user, update_time, update_user, movement_count, received_time_stamp', 'safe', 'on' => 'search'),
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
			'document' => array(self::BELONGS_TO, 'Documents', 'document_id'),
			'assignment' => array(self::HAS_ONE, 'Assignments', 'marked_to'),
			'designation' => array(self::HAS_ONE, 'Designation', array('designation_id' => 'id'), 'through' => 'assignment'),
			'marked_to_duty' => array(self::BELONGS_TO, 'Duty', 'marked_to'),
			'marked_by_duty' => array(self::BELONGS_TO, 'Duty', 'marked_by'),
			'marked_to_assignment' => array(self::HAS_ONE, 'Assignments', 'marked_to'),
			'marked_by_assignment' => array(self::HAS_ONE, 'Assignments', 'marked_by'),
			'received_by_user' => array(self::BELONGS_TO, 'User', 'received_by'),
			'file' => array(self::BELONGS_TO, 'File', 'file_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'document_id' => 'Document ID',
			'received_by' => 'Received By',
			'marked_date' => 'Marked Date',
			'marked_to' => 'Marked To',
			'marked_by' => 'Marked By',
			'file_id' => 'File',
			'received_time_stamp' => 'Received Date',
			'movement_count' => 'Movement Count',
			'create_time' => 'Create Time',
			'create_user' => 'Create User',
			'update_time' => 'Update Time',
			'update_user' => 'Update User',
			'file_page_no' => 'File Page No.',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('document_id', $this->document_id);
		$criteria->compare('received_by', $this->received_by);
		$criteria->compare('marked_date', $this->marked_date, true);
		$criteria->compare('marked_to', $this->marked_to);
		$criteria->compare('marked_by', $this->marked_by);
		$criteria->compare('file_id', $this->file_id);
		$criteria->compare('received_time_stamp', $this->received_time_stamp, true);
		$criteria->compare('create_time', $this->create_time, true);
		$criteria->compare('create_user', $this->create_user, true);
		$criteria->compare('update_time', $this->update_time, true);
		$criteria->compare('update_user', $this->update_user, true);
		$criteria->compare('movement_count', $this->movement_count, true);

		return new CActiveDataProvider(
			$this,
			array(
				'criteria' => $criteria,
			)
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FileMovement the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	// After finding the record
	protected function afterFind()
	{
		if (!empty($this->marked_date))
			$this->fixDate($this, 'marked_date', false);

		// call the parent class's function
		return parent::afterFind();
	}

	// Before saving the record to the DB
	protected function beforeSave()
	{
		// Formats the date as required by DB
		if (!empty($this->marked_date))
			if (stripos($this->marked_date, Yii::app()->user->getDateSeparator()) == 2)
				$this->fixDate($this, 'marked_date');


		//call the function from parent class
		return parent::beforeSave();
	}

	/**
	 * This method is invoked before an AR finder executes a find call.
	 */
	protected function beforeFind()
	{
		// Formats the date as required by DB
		if (stripos($this->marked_date, Yii::app()->user->getDateSeparator()) == 2)
			$this->fixDate($this, 'marked_date');

		parent::beforeFind();
	}

	/**
	 * @return string Designation and Duty to which document is marked
	 */
	public function getMarkedTo($short = true)
	{
		if ($short)
			return $this->marked_to_duty->assigned_to->designation->short_form . ((!empty($this->marked_to_duty->short_form) && $this->marked_to_duty->assigned_to->designation->short_form !== 'AG') ? ' (' . $this->marked_to_duty->short_form . ')' : '');

		return $this->marked_to_duty->assigned_to->designation->title . ' (' . $this->marked_to_duty->duty . ')';
	}

	/**
	 * @return string Designation and Duty who marked the document
	 */
	public function getMarkedBy($short = true)
	{
		if ($short)
			return $this->marked_by_duty->assigned_to->designation->short_form . ' (' . $this->marked_by_duty->short_form . ')';

		return $this->marked_by_duty->assigned_to->designation->title . ' (' . $this->marked_by_duty->duty . ')';
	}

	public function isReceived()
	{
		return !empty($this->received_by);
	}
}