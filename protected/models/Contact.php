<?php

Yii::import('application.models._base.BaseContact');

class Contact extends BaseContact
{
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function rules()
	{
		$rules = parent::rules();
		$rules[] = array('contact_email', 'email');
		return $rules;
	}

	public function toArray()
	{
		return array(
			'id' => $this->getPrimaryKey(),
			'name' => $this->contact_name,
			'email' => $this->contact_email,
			'phone' => $this->contact_phone,
			'message' => $this->contact_message,
			'createTime' => $this->contact_create_time,
		);
	}
}