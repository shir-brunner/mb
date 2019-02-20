<?php

Yii::import('application.models._base.BaseUser');

class User extends BaseUser
{
	public $password;

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function getFullName()
	{
		return ucfirst($this->user_first_name) . ' ' . ucfirst($this->user_last_name);
	}

	public function rules()
	{
		$rules = parent::rules();
		$rules[] = array('user_email', 'email');
		$rules[] = array('password', 'safe');

		return $rules;
	}

	protected function beforeValidate()
	{
		if($this->isNewRecord)
		{
			if(!empty($this->password))
			{
				if(!Yii::app()->security->isPasswordStrong($this->password))
				{
					$this->addError('password', 'Password must contain at least one letter and a number. minimum 8 characters.');
				}
				else
				{
					$this->setAttribute('user_password_hash', Yii::app()->security->generatePasswordHash($this->password));
				}
			}
		}

		if($this->isNewRecord || $this->attributeChanged('user_email'))
		{
			if(User::model()->resetScope()->findByAttributes(array('user_email' => $this->user_email)))
			{
				$this->addError('user_email', 'Email already in use.');
			}
		}

		return parent::beforeValidate();
	}

	public function hasAuthItem($itemName)
	{
		foreach($this->authItems as $authItem)
		{
			if($authItem->name == $itemName)
			{
				return true;
			}
		}

		return false;
	}

	public function toArray()
	{
		return array(
			'id' => $this->getPrimaryKey(),
			'firstName' => $this->user_first_name,
			'lastName' => $this->user_last_name,
			'email' => $this->user_email,
		);
	}
}