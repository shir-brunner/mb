<?php

/**
 * This is the model base class for the table "file".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "File".
 *
 * Columns in table "file" available as properties of the model,
 * and there are no model relations.
 *
 * @property integer $file_id
 * @property string $file_name
 * @property string $file_path
 * @property string $file_content_type
 * @property string $file_create_time
 *
 */
abstract class BaseFile extends GxActiveRecord {

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'file';
	}

	public static function label($n = 1)
	{
		return Yii::t('app', 'File|Files', $n);
	}

	public static function representingColumn() {
		return 'file_name';
	}

	public function rules()
	{
		return array(
			array('file_name, file_path, file_content_type, file_create_time', 'required'),
			array('file_name', 'length', 'max'=>300),
			array('file_path', 'length', 'max'=>700),
			array('file_content_type', 'length', 'max'=>500),
			array('file_id, file_name, file_path, file_content_type, file_create_time', 'safe', 'on' => 'search'),
		);
	}

	public function relations()
	{
		return array(
		);
	}

	public function pivotModels() {
		return array(
		);
	}

	public function attributeLabels()
	{
		return array(
			'file_id' => Yii::t('app', ''),
			'file_name' => Yii::t('app', 'Name'),
			'file_path' => Yii::t('app', 'Path'),
			'file_content_type' => Yii::t('app', 'Content Type'),
			'file_create_time' => Yii::t('app', 'Create Time'),
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('file_id', $this->file_id);
		$criteria->compare('file_name', $this->file_name, true);
		$criteria->compare('file_path', $this->file_path, true);
		$criteria->compare('file_content_type', $this->file_content_type, true);
		$criteria->compare('file_create_time', $this->file_create_time, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	protected function beforeValidate()
	{
		if($this->isNewRecord && empty($this->file_create_time))
		{
			$this->setAttribute('file_create_time', date('Y-m-d H:i:s'));
		}

		return parent::beforeValidate();
	}
}