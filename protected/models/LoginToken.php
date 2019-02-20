<?php

Yii::import('application.models._base.BaseLoginToken');

class LoginToken extends BaseLoginToken
{
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	protected function beforeValidate()
	{
		if($this->isNewRecord)
		{
			$this->login_token_hash = bin2hex(openssl_random_pseudo_bytes(32));
		}

		return parent::beforeValidate();
	}
}