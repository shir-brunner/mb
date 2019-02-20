<?php

class PublicController extends GxController
{
    public $layout = '//layouts/public';
    private $_categories = array();

    public function actionIndex()
    {
        $this->pageTitle = Yii::app()->name;
        $this->render('index');
    }

    public function actionProduct($id)
    {
        $product = null;
        $request = null;

        if(isset($_REQUEST['rh']))
        {
            $request = Request::model()->findByAttributes(array(
                'request_hash' => $_REQUEST['rh'],
            ));

            if($request)
            {
                $product = $request->product;
            }
        }

        if(!$product)
        {
            if(!$product = Product::model()->findByPk($id))
            {
                $this->redirect(Yii::app()->createUrl(''));
            }
        }

        if(!$product->product_published)
        {
            $this->redirect(Yii::app()->createUrl(''));
        }

        $this->pageTitle = $product->product_name;
        $this->render('product', array(
            'product' => $product,
            'request' => $request,
        ));
    }

    public function actionError()
    {
        if($error = Yii::app()->errorHandler->error)
        {
            print_r($error['message']);
        }
    }

    public function actionLogin()
    {
        if(isset($_REQUEST['email']))
        {
            $loginForm = new LoginForm();
            $loginForm->setAttributes(array(
                'email' => isset($_REQUEST['email']) ? $_REQUEST['email'] : null,
                'password' => isset($_REQUEST['password']) ? $_REQUEST['password'] : null,
                'rememberMe' => isset($_REQUEST['remember_me']) ? $_REQUEST['remember_me'] : true,
            ));

            if($loginForm->validate() && $loginForm->login())
            {
                $redirectUrl = Yii::app()->createUrl('request/index');

                echo json_encode(array(
                    'status' => 'OK',
                    'body' => array(
                        'redirectUrl' => $redirectUrl,
                    ),
                ));

                Yii::app()->end();
            }

            echo json_encode(array(
                'status' => 'ERROR',
                'body' => array(
                    'errors' => $loginForm->getErrors(),
                ),
            ));

            Yii::app()->end();
        }
    }

    public function actionCategory($id)
    {
        if(!$category = Category::model()->findByPk($id))
        {
            $this->redirect(Yii::app()->createUrl(''));
        }

        $this->pageTitle = $category->category_name;
        $this->render('category', array('category' => $category));
    }

    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->createUrl(''));
    }

    public function actionPay($requestHash)
    {
        if(!$request = Request::model()->findByAttributes(array('request_hash' => $requestHash)))
        {
            $this->redirect(Yii::app()->createUrl(''));
        }

        $allowedStatusIds = array(RequestStatus::AWAITING_PAYMENT, RequestStatus::AWAITING_FULFILLMENT);
        if(!in_array($request->request_status_id, $allowedStatusIds))
        {
            $this->redirect(Yii::app()->createUrl(''));
        }

        if($request->request_status_id != RequestStatus::AWAITING_PAYMENT)
        {
            $productClassName = $request->product->product_class_name;
            $productClass = new $productClassName();

            $request->setAttributes(array(
                'request_status_id' => RequestStatus::AWAITING_PAYMENT,
                'request_document_html' => $productClass->toHtml($request),
            ));

            $request->save();
        }

        $this->layout = '//layouts/clean'; //only for presentation
        $this->pageTitle = GxHtml::encode($request->product->product_name) . ' - תשלום';
        $this->render('pay', array('request' => $request));
    }

    public function actionThankYou()
    {
        $this->layout = '//layouts/clean'; //only for presentation
        $this->render('thankYou');
    }

    public function getCategories()
    {
        if(empty($this->_categories))
        {
            $this->_categories = Category::model()->findAll();
        }

        return $this->_categories;
    }

    public function actionTerms()
    {
        $this->pageTitle = 'תנאי שימוש';
        $this->render('terms');
    }
}