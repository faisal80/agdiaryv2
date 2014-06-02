<?php

/**
 * This is the model class for table "{{documents}}".
 *
 * The followings are the available columns in table '{{documents}}':
 * @property string $id
 * @property string $diary_number
 * @property string $date_received
 * @property string $reference_number
 * @property string $date_of_document
 * @property string $received_from
 * @property string $description
 * @property string $owner_id
 * @property string $doc_status
 * @property string $is_case_disposed
 * @property string $type_of_document
 * @property string $detail_of_enclosure
 * @property string $tags
 * @property string $create_time
 * @property string $create_user
 * @property string $update_time
 * @property string $update_user
 */
class Document extends DiaryActiveRecord
{
	
	public $image;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Document the static model class
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
		return '{{documents}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('reference_number, date_of_document, received_from, description', 'required'),
			array('diary_number, owner_id', 'length', 'max'=>10),
			array('reference_number, received_from, detail_of_enclosure, tags', 'length', 'max'=>255),
			array('doc_status, type_of_document', 'length', 'max'=>100),
			array('is_case_disposed', 'length', 'max'=>5),
			//array('date_of_document', 'validate_date', Yii::app()->user->getDateSeparator()),
      array('image', 'file', 'types'=>'jpg, gif, png', 'allowEmpty'=>true),
			//array('date_received, date_of_document, description, update_time, image', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, diary_number, date_received, reference_number, date_of_document, received_from, description, owner_id, doc_status, is_case_disposed, type_of_document, detail_of_enclosure, tags, create_time, create_user, update_time, update_user', 'safe', 'on'=>'search'),
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
			'images'=>array(self::HAS_MANY, 'DocumentImage', 'document_id'),
			'markings'=>array(self::HAS_MANY, 'Marked', 'document_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'diary_number' => 'Diary Number',
			'date_received' => 'Date Received',
			'reference_number' => 'Reference Number',
			'date_of_document' => 'Date of Document',
			'received_from' => 'Received From',
			'description' => 'Description',
			'owner_id' => 'Owner',
			'doc_status' => 'Doc Status',
			'is_case_disposed' => 'Is Case Disposed',
			'type_of_document' => 'Type Of Document',
			'detail_of_enclosure' => 'Detail Of Enclosure',
			'tags' => 'Category tags',
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
		
		if (stripos($this->date_received, Yii::app()->user->getDateSeparator())==2)
			$this->fixDate($this, 'date_received');
		if (stripos($this->date_of_document, Yii::app()->user->getDateSeparator())==2)
			$this->fixDate($this, 'date_of_document');

		
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('diary_number',$this->diary_number,true);
		$criteria->compare('date_received',$this->date_received,true);
		$criteria->compare('reference_number',$this->reference_number,true);
		$criteria->compare('date_of_document',$this->date_of_document,true);
		$criteria->compare('received_from',$this->received_from,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('owner_id',$this->owner_id,true);
		$criteria->compare('doc_status',$this->doc_status,true);
		$criteria->compare('is_case_disposed',$this->is_case_disposed,true);
		$criteria->compare('type_of_document',$this->type_of_document,true);
		$criteria->compare('detail_of_enclosure',$this->detail_of_enclosure,true);
		$criteria->compare('tags', $this->tags, true);
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
		if (stripos($this->date_received, Yii::app()->user->getDateSeparator())==2)
			$this->fixDate($this, 'date_received');
		if (stripos($this->date_of_document, Yii::app()->user->getDateSeparator())==2)
			$this->fixDate($this, 'date_of_document');
		
		return parent::beforeSave();
	}		

	/**
	 * This method is invoked before an AR finder executes a find call.
	 */
	protected function beforeFind() 
	{
		// Formats the date as required by DB
		if (stripos($this->date_received,  Yii::app()->user->getDateSeparator())==2)
			$this->fixDate($this, 'date_received');
		if (stripos($this->date_of_document,  Yii::app()->user->getDateSeparator())==2)
			$this->fixDate($this, 'date_of_document');

		parent::beforeFind();
	}
	
	/**
	 * This method is invoked after each record is instantiated by a find method.
	 */
	protected function afterFind() 
	{
		$this->fixDate($this, 'date_received', false);
		$this->fixDate($this, 'date_of_document', false);

		parent::afterFind();
	}
	
	/**
	 * @return	boolean	Whether this document is confidential/secret or not
	 */
	public function isConfidential()
	{
		if (!stristr($this->doc_status, 'confidential') === false) return true;
		if (!stristr($this->doc_status, 'secret') === false) return true;
		return false;
	}
	
}