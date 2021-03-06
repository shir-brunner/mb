<?php

/**
 * This is the model base class for the table "request_status".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "RequestStatus".
 *
 * Columns in table "request_status" available as properties of the model,
 * followed by relations of table "request_status" available as properties of the model.
 *
 * @property integer $request_status_id
 * @property string $request_status_name
 *
 * @property Request[] $requests
 */
abstract class BaseRequestStatus extends GxActiveRecord {

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'request_status';
	}

	public static function label($n = 1)
	{
		return Yii::t('app', 'RequestStatus|RequestStatuses', $n);
	}

	public static function representingColumn() {
		return 'request_status_name';
	}

	public function rules()
	{
		return array(
			array('request_status_name', 'required'),
			array('request_status_name', 'length', 'max'=>200),
			array('request_status_id, request_status_name', 'safe', 'on' => 'search'),
		);
	}

	public function relations()
	{
		return array(
			'requests' => array(self::HAS_MANY, 'Request', 'request_status_id'),
		);
	}

	public function pivotModels() {
		return array(
		);
	}

	public function attributeLabels()
	{
		return array(
			'request_status_id' => Yii::t('app', ''),
			'request_status_name' => Yii::t('app', 'Name'),
			'requests' => null,
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('request_status_id', $this->request_status_id);
		$criteria->compare('request_status_name', $this->request_status_name, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

}