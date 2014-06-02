<?php

/**
 * This is the model class for table "{{file_movement}}".
 *
 * The followings are the available columns in table '{{file_movement}}':
 * @property string $id
 * @property string $document_id
 * @property string $received_by
 * @property string $marked_to
 * @property string $marked_by
 * @property string $file_id
 * @property string $marked_date
 * @property string $received_date
 * @property string $create_time
 * @property string $create_user
 * @property string $update_time
 * @property string $update_user
 */
class Filemovement extends DiaryActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Filemovement the static model class
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
			array('marked_to, marked_by, file_id', 'required'),
			array('document_id, received_by, marked_to, marked_by, file_id, create_user, update_user', 'length', 'max'=>10),
			array('marked_date, received_date, create_time, update_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, document_id, received_by, marked_to, marked_by, file_id, marked_date, received_date, create_time, create_user, update_time, update_user', 'safe', 'on'=>'search'),
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
			'document'=>array(self::BELONGS_TO, 'Document', 'document_id'),
			'received_by'=>array(self::BELONGS_TO, 'User', 'received_by'),
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
			'marked_to' => 'Marked To',
			'marked_by' => 'Marked By',
			'file_id' => 'File ID',
			'marked_date' => 'Marked Date',
			'received_date' => 'Received Date',
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
		$criteria->compare('received_by',$this->received_by,true);
		$criteria->compare('marked_to',$this->marked_to,true);
		$criteria->compare('marked_by',$this->marked_by,true);
		$criteria->compare('file_id',$this->file_id,true);
		$criteria->compare('marked_date',$this->marked_date,true);
		$criteria->compare('received_date',$this->received_date,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_user',$this->create_user,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_user',$this->update_user,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	
}