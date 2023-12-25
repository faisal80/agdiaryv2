<?php

/**
 * This is the model class for table "{{marked}}".
 *
 * The followings are the available columns in table '{{marked}}':
 * @property string $id
 * @property string $document_id
 * @property string $marked_to
 * @property string $marked_by
 * @property string $marked_date
 * @property string $time_limit
 * @property string $received_by
 * @property string $received_time_stamp
 * @property string $create_time
 * @property string $create_user
 * @property string $update_time
 * @property string $update_user
 */
class Marked extends DiaryActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Marked the static model class
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
		return '{{marked}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('document_id, marked_to, marked_by, time_limit, received_by, create_user, update_user', 'length', 'max'=>10),
			array('marked_date, received_time_stamp, create_time, update_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, document_id, marked_to, marked_by, marked_date, time_limit, received_by, received_time_stamp, create_time, create_user, update_time, update_user', 'safe', 'on'=>'search'),
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
				'document'=>array(self::BELONGS_TO, 'Documents', 'document_id'),
				'assignment'=>array(self::HAS_ONE, 'Assignments', 'marked_to'),
				'designation'=>array(self::HAS_ONE, 'Designation', array('designation_id'=>'id'), 'through'=>'assignment'),
				'marked_to_duty'=>array(self::BELONGS_TO, 'Duty', 'marked_to'),
				'marked_by_duty'=>array(self::BELONGS_TO, 'Duty', 'marked_by'),
				'marked_to_assignment'=>array(self::HAS_ONE, 'Assignments', 'marked_to'),
				'marked_by_assignment'=>array(self::HAS_ONE, 'Assignments', 'marked_by'),
				'received_by_user'=>array(self::BELONGS_TO, 'User', 'received_by'),
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
			'marked_to' => 'Marked To',
			'marked_by' => 'Marked By',
			'marked_date' => 'Marked Date',
			'time_limit' => 'Time Limit',
			'received_by' => 'Received By',
			'received_time_stamp' => 'Receiving Date & Time',
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
		$criteria->compare('document_id',$this->document_id,true);
		$criteria->compare('marked_to',$this->marked_to,true);
		$criteria->compare('marked_by',$this->marked_by,true);
		$criteria->compare('marked_date',$this->marked_date,true);
		$criteria->compare('time_limit',$this->time_limit,true);
		$criteria->compare('received_by',$this->received_by,true);
		$criteria->compare('received_time_stamp',$this->received_time_stamp,true);
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
		$this->fixDate($this, 'marked_date', false);
        
    // call the parent class's function
    return parent::afterFind ();
  }

	// Before saving the record to the DB
  protected function beforeSave ()
  {
		// Formats the date as required by DB
		if (stripos($this->marked_date, Yii::app()->user->getDateSeparator())==2)
			$this->fixDate($this, 'marked_date');

    //call the function from parent class
    return parent::beforeSave ();
	}

	/**
	 * This method is invoked before an AR finder executes a find call.
	 */
	protected function beforeFind() 
	{
		// Formats the date as required by DB
		if (stripos($this->marked_date,  Yii::app()->user->getDateSeparator())==2)
			$this->fixDate($this, 'marked_date');

		parent::beforeFind();
	}
	
	/**
	 * @return string Designation and Duty to which document is marked
	 */
	public function getMarkedTo($short=true)
	{
		if ($short)
			return $this->marked_to_duty->assigned_to->designation->short_form . (!empty($this->marked_to_duty->short_form) ? ' (' . $this->marked_to_duty->short_form . ')' : '' );
		
		return $this->marked_to_duty->assigned_to->designation->title . ' (' . $this->marked_to_duty->duty . ')';
	}
	
	/**
	 * @return string Designation and Duty who marked the document
	 */
	public function getMarkedBy($short=true)
	{
		if ($short)
			return $this->marked_by_duty->assigned_to->designation->short_form . ' ('. $this->marked_by_duty->short_form . ')';	

		return $this->marked_by_duty->assigned_to->designation->title . ' ('. $this->marked_by_duty->duty . ')';
	}

	public function isReceived()
	{
		return !empty($this->received_by);
	}
}