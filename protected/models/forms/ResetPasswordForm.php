<?php

class ResetPasswordForm extends CFormModel
{
    public $newPassword;
    public $newPasswordConfirm;

    private $_user;

    public function rules()
    {
        return array(
            array(
                'newPassword, newPasswordConfirm', 'required',
                'message' => '{attribute} is required.',
            ),
            array(
                'newPasswordConfirm', 'compare',
                'compareAttribute' => 'newPassword',
                'message' => 'The new passwords do not match.',
            ),
            array(
                '_user', 'validateTokenExpiration',
            ),
            array(
                'newPassword', 'passwordStrength',
            ),
        );
    }

    public function attributeLabels()
    {
        return array(
            'newPassword' => 'New Password',
            'newPasswordConfirm' => 'Confirm Password',
        );
    }

    public function validateTokenExpiration($attribute)
    {
        if(empty($this->$attribute->user_password_reset_token_create_time) || (time() - $this->$attribute->user_password_reset_token_create_time > 7200))
        {
            $this->addError('newPassword', 'Your reset password request has expired, reset your password and try again.');
        }
    }

    public function passwordStrength($attribute)
    {
        if(!Yii::app()->security->isPasswordStrong($this->$attribute))
        {
            $this->addError($attribute, 'Password must contain at least one letter and a number. minimum 8 characters.');
        }
    }

    public function resetPassword()
    {
        $this->_user->setAttribute('user_password_hash', Yii::app()->security->generatePasswordHash($this->newPassword));
        $this->_user->user_password_reset_token_create_time = null;
        $this->_user->user_password_reset_token = null;
        return $this->_user->save();
    }

    public function setUser($user)
    {
        $this->_user = $user;
    }
}