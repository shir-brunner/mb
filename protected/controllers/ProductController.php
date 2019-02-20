<?php

class ProductController extends GxController
{
    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules()
    {
        return array(
            array('allow',
                'roles' => array('Super User'),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex()
    {
        $this->pageTitle = 'Products';
        $this->render('index');
    }

    public function actionEdit($id)
    {
        if(!$product = Product::model()->findByPk($id))
        {
            throw new CHttpException(404, 'Product not found');
        }

        $this->pageTitle = 'Edit Product';
        $this->render('edit', array('product' => $product));
    }
}