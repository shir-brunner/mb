<?php

class BaseWebUser extends CWebUser
{
    public function checkAccess($operation, $params = array(), $allowCaching = true)
    {
        if($this->getIsGuest())
        {
            return false;
        }

        if($allowCaching && !$this->getIsGuest() && isset(Yii::app()->session['access'][$operation]))
        {
            return Yii::app()->session['access'][$operation];
        }

        $checkAccess = Yii::app()->getAuthManager()->checkAccess($operation, $this->getId(), $params);

        if($allowCaching && !$this->getIsGuest())
        {
            $access = isset(Yii::app()->session['access']) ? Yii::app()->session['access'] : array();
            $access[$operation] = $checkAccess;
            Yii::app()->session['access'] = $access;
        }

        return $checkAccess;
    }
}