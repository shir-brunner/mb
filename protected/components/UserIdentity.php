<?php

class UserIdentity extends CUserIdentity
{
	const ERROR_USER_LOCKED = 10;
	const ERROR_NOT_VERIFIED = 11;
	const ERROR_NO_PERMISSIONS = 12;

	private $_user;
	private $_errorMessage;
	public $email;


	public function __construct($email, $password)
	{
		$this->email = $email;
		$this->password = $password;
	}

	public function authenticate()
	{
		$user = User::model()->with(array('authItems'))->find('LOWER(user_email)=?', array(strtolower(trim($this->email))));

		if($user === null)
		{
			$this->_errorMessage = Yii::t('app', 'הסיסמא שהזנת שגויה');
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		}
		else if(!Yii::app()->security->validatePassword($this->password, $user->user_password_hash))
		{
			$this->_errorMessage = Yii::t('app', 'הסיסמא שהזנת שגויה');
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		}
		else
		{
			$this->username = $user->getFullName();

			$loginToken = new LoginToken();
			$loginToken->setAttribute('user_id', $user->getPrimaryKey());
			$loginToken->save();

			$this->setState('user', $user);
			$this->setState('loginToken', $loginToken);

			$this->_user = $user;

			$this->errorCode = self::ERROR_NONE;
		}

		return $this->errorCode == self::ERROR_NONE;
	}

	public function getId()
	{
		return $this->_user->user_id;
	}

	public function getErrorMessage()
	{
		return $this->_errorMessage;
	}
}