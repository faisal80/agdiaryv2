<?php

/**
 * This is the model class for table "{{document_images}}".
 *
 * The followings are the available columns in table '{{document_images}}':
 * @property string $id
 * @property string $document_id
 * @property string $image_path
 * @property string $create_time
 * @property string $create_user
 * @property string $update_time
 * @property string $update_user
 */
class DocumentImage extends DiaryActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return DocumentImage the static model class
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
		return '{{document_images}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('document_id, create_user, update_user', 'length', 'max'=>10),
			array('image_path', 'length', 'max'=>255),
			array('create_time, update_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, document_id, image_path, create_time, create_user, update_time, update_user', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'document_id' => 'Document',
			'image_path' => 'Image Path',
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
		$criteria->compare('image_path',$this->image_path,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_user',$this->create_user,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_user',$this->update_user,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Deletes corresponding file from hdd
	 * @return boolean
	 */
	public function delete() {
		$folder=Yii::getPathOfAlias('webroot.document_images').DIRECTORY_SEPARATOR;

		if(!unlink($folder.$this->image_path))
			throw new CHttpException(404, Yii::t('yii', $folder.$this->image_path . ' cannot be deleted.'));
		
		return parent::delete();
	}

}