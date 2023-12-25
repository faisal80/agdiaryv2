<?php

/**
 * This is the model class for table "{{files}}".
 *
 * The followings are the available columns in table '{{files}}':
 * @property integer $id
 * @property integer $section_id
 * @property string $number
 * @property string $description
 * @property string $open_date
 * @property string $close_date
 * @property string $number_of_pages
 * @property string $number_of_paras_on_note_part
 * @property string $create_time
 * @property string $create_user
 * @property string $update_time
 * @property string $update_user
 * @property boolean $enroute
 */
class File extends DiaryActiveRecord
{
	// for report only
	public $marked_id = null;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{files}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('section_id, number, description, create_time, create_user, update_time, update_user', 'required'),
			array('section_id', 'numerical', 'integerOnly' => true),
			array('number', 'length', 'max' => 255),
			array('number_of_pages, number_of_paras_on_note_part', 'length', 'max' => 50),
			array('create_time, update_time', 'length', 'max' => 100),
			array('create_user, update_user', 'length', 'max' => 25),
			array('open_date, close_date, enroute', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, section_id, number, description, open_date, close_date, number_of_pages, number_of_paras_on_note_part, create_time, create_user, update_time, update_user, enroute', 'safe', 'on' => 'search'),
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
			'section' => array(self::BELONGS_TO, 'Section', 'section_id'),
			'created_by' => array(self::BELONGS_TO, 'User', 'create_user'), 
			'updated_by' => array(self::BELONGS_TO, 'User', 'update_user'), 
			'markings' => array(self::HAS_MANY, 'FileMovement', 'file_id'),		
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'section_id' => 'Section',
			'number' => 'Number',
			'description' => 'Description',
			'open_date' => 'Open Date',
			'close_date' => 'Close Date',
			'number_of_pages' => 'Number of Pages',
			'number_of_paras_on_note_part' => 'No. of Paras on Note Part',
			'create_time' => 'Create Time',
			'create_user' => 'Create User',
			'update_time' => 'Update Time',
			'update_user' => 'Update User',
			'enroute' => 'En-route',
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
		$criteria->compare('section_id', $this->section_id);
		$criteria->compare('number', $this->number, true);
		$criteria->compare('description', $this->description, true);
		$criteria->compare('open_date', $this->open_date, true);
		$criteria->compare('close_date', $this->close_date, true);
		$criteria->compare('number_of_pages', $this->number_of_pages, true);
		$criteria->compare('number_of_paras_on_note_part', $this->number_of_paras_on_note_part, true);
		$criteria->compare('create_time', $this->create_time, true);
		$criteria->compare('create_user', $this->create_user, true);
		$criteria->compare('update_time', $this->update_time, true);
		$criteria->compare('update_user', $this->update_user, true);
		$criteria->compare('enroute', $this->enroute, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		)
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return File the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	// After finding the record
	protected function afterFind()
	{
		if (!empty($this->open_date)) $this->fixDate($this, 'open_date', false);
		if (!empty($this->close_date)) $this->fixDate($this, 'close_date', false);

		// call the parent class's function
		return parent::afterFind();
	}

	// Before saving the record to the DB
	protected function beforeSave()
	{
		// Formats the date as required by DB
		if (!empty($this->open_date))
			if (stripos($this->open_date, Yii::app()->user->getDateSeparator()) == 2)
				$this->fixDate($this, 'open_date');

		if (!empty($this->close_date))
			if (stripos($this->close_date, Yii::app()->user->getDateSeparator()) == 2)
				$this->fixDate($this, 'close_date');
			
		//call the function from parent class
		return parent::beforeSave();
	}

	/**
	 * This method is invoked before an AR finder executes a find call.
	 */
	protected function beforeFind()
	{
		// Formats the date as required by DB
		if (stripos($this->open_date, Yii::app()->user->getDateSeparator()) == 2)
			$this->fixDate($this, 'open_date');

		if (stripos($this->close_date, Yii::app()->user->getDateSeparator()) == 2)
			$this->fixDate($this, 'close_date');

		parent::beforeFind();
	}

	public static function getFilesList()
	{
		return CHtml::listData(File::model()->findAll(), 'id', 'number', 'section.section_name');
	}

	/**
	 * This function returns marking of the document most recently.
	 * @return Marked|null instance of last marking or null
	 */

	 public function LastMarking()
	 {
		 $lastMarking = $this->markings(
			 array(
				 'order' => 'create_time DESC',
				 'limit' => 1
			 )
		 );
 
		 return $lastMarking ? $lastMarking[0] : null;
	 }
	 
	 /**
	 * This function returns second last marking of the file.
	 * @return FileMovement|null instance of second last marking or null
	 */

	 public function SecondLastMarking()
	 {
		 $secondLastMarking = $this->markings(
			 array(
				 'order' => 'create_time DESC',
				 'limit' => 2
			 )
		 );
 
		 return !empty($secondLastMarking[1]) ? $secondLastMarking[1] : null;
	 }

	 /**
	  * This function changes the value of enroute field to false
	  */
	 public function Rackit()
	 {
		$this->enroute = 0;
		$this->save(false);
	 }

	 public function Initiator()
	 {
		if ($this->enroute) {
			$lastMovementCount = !empty($this->lastMarking()->movement_count) ? $this->lastMarking()->movement_count : 0 ;
			$initMarking = $this->markings(
				array(
					'condition' => 'movement_count=' .  $lastMovementCount,
					'order' => 'create_time',
					'limit' => 1
				)
				);
			return !empty($initMarking[0]) ? $initMarking[0] : null;
		}
		return null;
	 }
}
