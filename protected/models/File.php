<?php

/**
 * This is the model class for table "{{files}}".
 *
 * The followings are the available columns in table '{{files}}':
 * @property string $id
 * @property string $section_id
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
 */
class File extends DiaryActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return File the static model class
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
			array('section_id', 'required'),
			array('section_id, number_of_pages, number_of_paras_on_note_part, create_user, update_user', 'length', 'max'=>10),
			array('number', 'length', 'max'=>100),
			array('description', 'length', 'max'=>255),
			array('open_date, close_date, create_time, update_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, section_id, number, description, open_date, close_date, number_of_pages, number_of_paras_on_note_part, create_time, create_user, update_time, update_user', 'safe', 'on'=>'search'),
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
			'section_id' => 'Section',
			'number' => 'Number',
			'description' => 'Description',
			'open_date' => 'Open Date',
			'close_date' => 'Close Date',
			'number_of_pages' => 'Number Of Pages',
			'number_of_paras_on_note_part' => 'Number Of Paras On Note Part',
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

		if (stripos($this->open_date, Yii::app()->user->getDateSeparator())==2)
			$this->fixDate($this, 'open_date');
		if (stripos($this->close_date, Yii::app()->user->getDateSeparator())==2)
			$this->fixDate($this, 'close_date');

		
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('section_id',$this->section_id,true);
		$criteria->compare('number',$this->number,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('open_date',$this->open_date,true);
		$criteria->compare('close_date',$this->close_date,true);
		$criteria->compare('number_of_pages',$this->number_of_pages,true);
		$criteria->compare('number_of_paras_on_note_part',$this->number_of_paras_on_note_part,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_user',$this->create_user,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_user',$this->update_user,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * This method is invoked before saving a record (after validation, if any).
	 * @return boolean whether the saving should be executed. Defaults to true.
	 */
	protected function beforeSave() 
	{
		// Formats the date as required by DB
		if (($this->open_date <> '') && stripos($this->open_date, Yii::app()->user->getDateSeparator()) === 2)
			$this->fixDate($this, 'open_date');
		if (($this->close_date <> '') && stripos($this->close_date, Yii::app()->user->getDateSeparator())===2)
			$this->fixDate($this, 'close_date');
		
        if ($this->open_date == '')
            $this->open_date = new CDbExpression('NULL');
        if ($this->close_date == '')
            $this->close_date = new CDbExpression ('NULL');
        
		return parent::beforeSave();
	}		

	/**
	 * This method is invoked before an AR finder executes a find call.
	 */
	protected function beforeFind() 
	{
		// Formats the date as required by DB
		if (stripos($this->open_date,  Yii::app()->user->getDateSeparator())==2)
			$this->fixDate($this, 'open_date');
		if (stripos($this->close_date,  Yii::app()->user->getDateSeparator())==2)
			$this->fixDate($this, 'close_date');

		parent::beforeFind();
	}
	
	/**
	 * This method is invoked after each record is instantiated by a find method.
	 */
	protected function afterFind() 
	{
		$this->fixDate($this, 'open_date', false);
		$this->fixDate($this, 'close_date', false);

		parent::afterFind();
	}
	
}