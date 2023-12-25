<?php

/**
 * This is the model class for table "{{disposal}}".
 *
 * The followings are the available columns in table '{{disposal}}':
 * @property string $id
 * @property string $document_id
 * @property string $officer_id
 * @property string $disposal_date
 * @property string $disposal_type
 * @property string $disposal_ref_number
 * @property string $reference_dated
 * @property string $dispatcher_user_id
 * @property string $out_date
 * @property string $ref_file_page
 * @property integer $file_id
 * @property string $is_reminder
 * @property string $owner_id
 * @property string $disposal
 * @property string $create_time
 * @property string $create_user
 * @property string $update_time
 * @property string $update_user
 * @property string $noting_paras
 */
class Disposal extends DiaryActiveRecord
{

	public $image;
	public $isDisposed;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Disposal the static model class
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
		return '{{disposal}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('document_id, officer_id, disposal_type', 'required'),
			array('document_id, officer_id, dispatcher_user_id, ref_file_page, owner_id, create_user, update_user', 'length', 'max'=>10),
			array('disposal_type', 'length', 'max'=>25),
			array('noting_paras', 'length', 'max'=>20),
			array('disposal_ref_number, disposal', 'length', 'max'=>255),
			array('is_reminder', 'length', 'max'=>5),
			array('disposal_date, reference_dated, out_date, create_time, update_time', 'safe'),
			array('image', 'file', 'types' => 'jpg, gif, png', 'allowEmpty' => true),
			array('isDisposed', 'default', 'value'=>false),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, document_id, officer_id, disposal_date, disposal_type, disposal_ref_number, reference_dated, dispatcher_user_id, out_date, ref_file_page, file_id, is_reminder, owner_id, disposal, create_time, create_user, update_time, update_user, noting_paras', 'safe', 'on'=>'search'),
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
			'officer'=>array(self::BELONGS_TO, 'Duty', 'officer_id'),
			'images' => array(self::HAS_MANY, 'DisposalImage', 'disposal_id'),
			'document' => array(self::BELONGS_TO, 'Document', 'document_id'),
			'dispatch_docs' => array(self::HAS_MANY, 'DispatchDocs', 'disposal_id'),
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
			'officer_id' => 'Officer',
			'disposal_date' => 'Disposal Date',
			'disposal_type' => 'Disposal Type',
			'disposal_ref_number' => 'Disposal Ref Number',
			'reference_dated' => 'Reference Dated',
			'dispatcher_user_id' => 'Dispatcher User',
			'out_date' => 'Out Date',
			'ref_file_page' => 'Ref File Page',
			'file_id' => 'File',
			'is_reminder' => 'Is Reminder',
			'owner_id' => 'Owner',
			'disposal' => 'Disposal',
			'create_time' => 'Create Time',
			'create_user' => 'Create User',
			'update_time' => 'Update Time',
			'update_user' => 'Update User',
			'isDisposed' => 'Case Disposed',
			'noting_paras' => 'Noting Paras',
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
		$criteria->compare('officer_id',$this->officer_id,true);
		$criteria->compare('disposal_date',$this->disposal_date,true);
		$criteria->compare('disposal_type',$this->disposal_type,true);
		$criteria->compare('disposal_ref_number',$this->disposal_ref_number,true);
		$criteria->compare('reference_dated',$this->reference_dated,true);
		$criteria->compare('dispatcher_user_id',$this->dispatcher_user_id,true);
		$criteria->compare('out_date',$this->out_date,true);
		$criteria->compare('ref_file_page',$this->ref_file_page,true);
		$criteria->compare('file_id',$this->file_id,true);
		$criteria->compare('is_reminder',$this->is_reminder,true);
		$criteria->compare('owner_id',$this->owner_id,true);
		$criteria->compare('disposal',$this->disposal,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_user',$this->create_user,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_user',$this->update_user,true);
		$criteria->compare('noting_paras',$this->noting_paras,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function beforeValidate()
	{
		$val = $this->disposal_type;
		if ($val == 'reply' || $val == 'circular' || $val == 'notification' || $val == 'intimation' || $val == 'letter')
		{
			$this->getValidatorList()->add(CValidator::createValidator('required', $this, 'disposal_ref_number, reference_dated'));
		} elseif ($val == 'filed') {
			$this->getValidatorList()->add(CValidator::createValidator('required', $this, 'file_id'));
		} elseif ($val == 'other') {
			$this->getValidatorList()->add(CValidator::createValidator('required', $this, 'disposal'));
		}
		return parent::beforeValidate();
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

		if (stripos($this->disposal_date, Yii::app()->user->getDateSeparator()) == 2)
			$this->fixDate($this, 'disposal_date');

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

}