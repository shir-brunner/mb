<?php

abstract class ApiController extends GxController
{
    protected $_user;
    protected $_client;

    protected function beforeAction($action)
    {
        if($this->requireLogin())
        {
            $loginTokenHash = isset($_REQUEST['login_token']) ? $_REQUEST['login_token'] : null;

            $loginToken = LoginToken::model()->with(array(
                'user' => array(),
            ))->findByAttributes(array('login_token_hash' => $loginTokenHash));

            if(!$loginToken || !$loginToken->user)
            {
                $this->sendResponse('LOGIN_REQUIRED');
            }

            $this->_user = $loginToken->user;

            //allow to use Yii::app()->user->id anywhere, as if the user was logged in via session
            Yii::app()->setComponent('user', new BaseWebUser($this->_user, $loginToken));
        }

        return parent::beforeAction($action);
    }

    protected function requireLogin()
    {
        return true;
    }
}