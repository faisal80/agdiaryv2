<?php

/**
 * This is the model class for table "{{disposal_images}}".
 *
 * The followings are the available columns in table '{{disposal_images}}':
 * @property integer $id
 * @property string $image_path
 * @property integer $disposal_id
 * @property integer $create_time
 * @property integer $create_user
 * @property integer $update_time
 * @property integer $update_user
 */
class DisposalImage extends DiaryActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{disposal_images}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('image_path, disposal_id, create_time, create_user, update_time, update_user', 'required'),
			array('disposal_id, create_user, update_user', 'numerical', 'integerOnly'=>true),
			array('image_path', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, image_path, disposal_id, create_time, create_user, update_time, update_user', 'safe', 'on'=>'search'),
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
			'disposal' => array(self::BELONGS_TO, 'Disposal', 'disposal_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'image_path' => 'Image Path',
			'disposal_id' => 'Disposal',
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
		$criteria->compare('image_path',$this->image_path,true);
		$criteria->compare('disposal_id',$this->disposal_id);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('create_user',$this->create_user);
		$criteria->compare('update_time',$this->update_time);
		$criteria->compare('update_user',$this->update_user);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DisposalImage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Deletes corresponding file from hdd
	 * @return boolean
	 */
	public function delete() {
		$folder=Yii::getPathOfAlias('webroot.disposal_images').DIRECTORY_SEPARATOR;

		if(!unlink($folder.$this->image_path))
			throw new CHttpException(404, Yii::t('yii', $folder.$this->image_path . ' cannot be deleted.'));
		
		return parent::delete();
	}
}
