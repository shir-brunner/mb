<?php

class ForgotPasswordForm extends CFormModel
{
    public $email;

    private $_user;

    public function rules()
    {
        return array(
            array('email', 'required'),
            array('_user', 'exists'),
        );
    }

    public function exists($attribute)
    {
        $this->_user = User::model()->findByAttributes(array('user_email' => trim($this->email)));
        if(!$this->_user)
        {
            $this->addError('mobile', 'Email does not exist.');
        }
    }

    public function sendResetPasswordMessage()
    {
        $timestamp = time();
        $token = Yii::app()->security->generateToken();

        $this->_user->setAttributes(array(
            'user_password_reset_token' => $token,
            'user_password_reset_token_create_time' => $timestamp,
        ));

        $this->_user->save();

        Email::send(Yii::app()->params['resetPasswordFromEmail'], $this->_user->user_email, Yii::app()->name . ' - Reset Your Password', Yii::app()->controller->renderPartial('//email/_resetPassword', array('user' => $this->_user), true));
    }

}