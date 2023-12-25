<?php

/**
 * This is the model class for table "{{dispatch_docs}}".
 *
 * The followings are the available columns in table '{{dispatch_docs}}':
 * @property integer $id
 * @property string $reference_number
 * @property string $reference_dated
 * @property string $description
 * @property integer $signed_by
 * @property integer $document_id
 * @property string $addressed_to
 * @property string $copy_to
 * @property integer $disposal_id
 * @property string $create_time
 * @property string $create_user
 * @property string $update_time
 * @property string $update_user
 * @property integer $received_by
 * @property integer $received_timestamp
 */
class DispatchDocs extends DiaryActiveRecord
{
	public $image;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{dispatch_docs}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('reference_number, reference_dated, description', 'required'),
			array('signed_by, document_id, disposal_id', 'numerical', 'integerOnly'=>true),
			array('reference_number', 'length', 'max'=>255),
			array('addressed_to, copy_to, create_time, create_user, update_time, update_user, received_by, received_timestamp', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, reference_number, reference_dated, description, signed_by, document_id, addressed_to, copy_to', 'safe', 'on'=>'search'),
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
				'document' => array(self::BELONGS_TO, 'Document', 'document_id'),
				'disposal' => array(self::BELONGS_TO, 'Disposal', 'disposal_id'),
				'duty' => array(self::BELONGS_TO, 'Duty', 'signed_by'),
				'dispatcher' => array(self::BELONGS_TO, 'User', 'received_by'),
				'dispatch_details' => array(self::HAS_MANY, 'DispatchDetails', 'disp_doc_id'),
				'images' => array(self::HAS_MANY, 'DispDocsImages', 'disp_doc_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'reference_number' => 'Reference Number',
			'reference_dated' => 'Reference Dated',
			'description' => 'Description',
			'signed_by' => 'Signed By',
			'document_id' => 'Document',
			'addressed_to' => 'Addressed To',
			'copy_to' => 'Copy To',
			'disposal_id' => 'Disposal ID',
			'received_by' => 'Received by',
			'received_timestamp' => 'Received on',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('reference_number',$this->reference_number,true);
		$criteria->compare('reference_dated',$this->reference_dated,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('signed_by',$this->signed_by);
		$criteria->compare('document_id',$this->document_id);
		$criteria->compare('addressed_to',$this->addressed_to,true);
		$criteria->compare('copy_to',$this->copy_to,true);
		$criteria->compare('disposal_id', $this->disposal_id, true);
		$criteria->compare('received_by', $this->received_by, true);
		$criteria->compare('received_timestamp', $this->received_timestamp, true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DispatchDocs the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * This method is invoked before saving a record (after validation, if any).
	 * @return boolean whether the saving should be executed. Defaults to true.
	 */
	protected function beforeSave()
	{
		// Formats the date as required by DB
		if (stripos($this->reference_dated, Yii::app()->user->getDateSeparator()) == 2)
			$this->fixDate($this, 'reference_dated');

		return parent::beforeSave();
	}

	/**
	 * This method is invoked before an AR finder executes a find call.
	 */
	protected function beforeFind()
	{
		// Formats the date as required by DB
		if (stripos($this->reference_dated, Yii::app()->user->getDateSeparator()) == 2)
			$this->fixDate($this, 'reference_dated');

		parent::beforeFind();
	}

	/**
	 * This method is invoked after each record is instantiated by a find method.
	 */
	protected function afterFind()
	{
		$this->fixDate($this, 'reference_dated', false);
		$this->fixDate($this, 'disposal_date', false);

		parent::afterFind();
	}

	public function isReceived()
	{
		return !empty($this->received_by);
	}

	public function getDispatchStatus()
	{
		return empty($this->dispatch_details) ? "Pending" : "Dispatched";
	}

	public function isOwner($userID)
	{
		return $this->create_user == $userID;
	}
}
