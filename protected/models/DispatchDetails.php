<?php

/**
 * This is the model class for table "{{dispatch_details}}".
 *
 * The followings are the available columns in table '{{dispatch_details}}':
 * @property integer $id
 * @property integer $disp_doc_id
 * @property string $dispatch_date
 * @property string $dispatch_through
 * @property string $receipt_no
 * @property string $receipt_date
 * @property string $create_time
 * @property integer $create_user
 * @property string $update_time
 * @property integer $update_user
 */
class DispatchDetails extends DiaryActiveRecord
{
	public $image;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{dispatch_details}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('disp_doc_id, dispatch_date, dispatch_through, receipt_no, receipt_date, create_time, create_user, update_time, update_user', 'required'),
			array('disp_doc_id, create_user, update_user', 'numerical', 'integerOnly'=>true),
			array('dispatch_through, create_time, update_time', 'length', 'max'=>100),
			array('receipt_no', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, disp_doc_id, dispatch_date, dispatch_through, receipt_no, receipt_date, create_time, create_user, update_time, update_user', 'safe', 'on'=>'search'),
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
			'images' => array(self::HAS_MANY, 'DispDetImages', 'disp_det_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'disp_doc_id' => 'Disp Doc',
			'dispatch_date' => 'Dispatch Date',
			'dispatch_through' => 'Dispatch Through',
			'receipt_no' => 'Receipt No',
			'receipt_date' => 'Receipt Date',
			'create_time' => 'Create Time',
			'create_user' => 'Create User',
			'update_time' => 'Update Time',
			'update_user' => 'Update User',
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
		$criteria->compare('disp_doc_id',$this->disp_doc_id);
		$criteria->compare('dispatch_date',$this->dispatch_date,true);
		$criteria->compare('dispatch_through',$this->dispatch_through,true);
		$criteria->compare('receipt_no',$this->receipt_no,true);
		$criteria->compare('receipt_date',$this->receipt_date,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_user',$this->create_user);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_user',$this->update_user);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DispatchDetails the static model class
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
		if (stripos($this->dispatch_date, Yii::app()->user->getDateSeparator()) == 2)
			$this->fixDate($this, 'dispatch_date');

		if (stripos($this->receipt_date, Yii::app()->user->getDateSeparator()) == 2)
			$this->fixDate($this, 'receipt_date');

		return parent::beforeSave();
	}

	/**
	 * This method is invoked before an AR finder executes a find call.
	 */
	protected function beforeFind()
	{
		// Formats the date as required by DB
		if (stripos($this->dispatch_date, Yii::app()->user->getDateSeparator()) == 2)
			$this->fixDate($this, 'dispatch_date');

		parent::beforeFind();
	}

	/**
	 * This method is invoked after each record is instantiated by a find method.
	 */
	protected function afterFind()
	{
		$this->fixDate($this, 'dispatch_date', false);
		$this->fixDate($this, 'receipt_date', false);

		parent::afterFind();
	}
}
