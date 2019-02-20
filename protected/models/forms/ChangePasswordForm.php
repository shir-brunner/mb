<?php

class ChangePasswordForm extends CFormModel
{
    public $currentPassword;
    public $newPassword;
    public $newPasswordConfirm;

    public function rules()
    {
        return array(
            array(
                'currentPassword, newPassword, newPasswordConfirm', 'required',
                'message' => '{attribute} is required.',
            ),
            array('currentPassword', 'compareCurrentPassword'),
            array('newPassword', 'isPasswordNew'),
            array(
                'newPasswordConfirm', 'compare',
                'compareAttribute' => 'newPassword',
                'message' => 'The new passwords do not match.',
            ),
            array(
                'newPassword', 'passwordStrength',
            ),
        );
    }

    public function compareCurrentPassword($attribute, $params)
    {
        if(!Yii::app()->security->validatePassword($this->currentPassword, Yii::app()->user->getUser()->user_password_hash) && !empty($this->currentPassword))
        {
            $this->addError($attribute, 'Password is incorrect.');
        }
    }

    public function isPasswordNew($attribute, $params)
    {
        if($this->currentPassword == $this->newPassword && !empty($this->newPassword))
        {
            $this->addError($attribute, 'The new password must be different from the old one.');
        }
    }

    public function passwordStrength($attribute)
    {
        if(!empty($this->$attribute) && strlen($this->$attribute) < 8)
        {
            $this->addError($attribute, 'Password must be at least 8 characters long.');
            return;
        }

        if(!Yii::app()->security->isPasswordStrong($this->$attribute) && !empty($this->$attribute))
        {
            $this->addError($attribute, 'Password must contain at least one letter and a number.');
        }
    }

    public function attributeLabels()
    {
        return array(
            'currentPassword' => 'Current Password',
            'newPassword' => 'New Password',
            'newPasswordConfirm' => 'Password Confirmation',
        );
    }

    public function changePassword()
    {
        Yii::app()->user->getUser()->setAttribute('user_password_hash', Yii::app()->security->generatePasswordHash($this->newPassword));
        Yii::app()->user->getUser()->setAttribute('user_password_changed', 1);

        return Yii::app()->user->getUser()->save();
    }

    public function getErrors($attribute = NULL)
    {
        return array_merge(parent::getErrors(), Yii::app()->user->getUser()->getErrors());
    }
}