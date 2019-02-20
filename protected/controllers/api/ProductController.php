<?php

class ProductController extends ApiController
{
    public function actionCreate()
    {
        $product = new Product();

        isset($_REQUEST['name']) && $product->setAttribute('product_name', $_REQUEST['name']);
        isset($_REQUEST['description']) && $product->setAttribute('product_description', $_REQUEST['description']);
        isset($_REQUEST['price']) && $product->setAttribute('product_price', $_REQUEST['price']);
        isset($_REQUEST['published']) && $product->setAttribute('product_published', $_REQUEST['published']);
        isset($_REQUEST['category_id']) && $product->setAttribute('category_id', $_REQUEST['category_id']);
        isset($_REQUEST['image_url']) && $product->setAttribute('product_image_url', $_REQUEST['image_url']);
        isset($_REQUEST['icon_url']) && $product->setAttribute('product_icon_url', $_REQUEST['icon_url']);
        isset($_REQUEST['hover_icon_url']) && $product->setAttribute('product_hover_icon_url', $_REQUEST['hover_icon_url']);
        isset($_REQUEST['class_name']) && $product->setAttribute('product_class_name', $_REQUEST['class_name']);

        $saved = $product->save();
        $this->sendResponse($saved ? 'OK' : 'ERROR', array(
            'product' => $saved ? $product->toArray() : null,
            'errors' => $product->getErrors(),
        ));
    }

    public function actionUpdate()
    {
        $productId = isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : null;
        if(!$product = Product::model()->findByPk($productId))
        {
            $this->sendResponse('PRODUCT_NOT_FOUND');
        }

        isset($_REQUEST['name']) && $product->setAttribute('product_name', $_REQUEST['name']);
        isset($_REQUEST['description']) && $product->setAttribute('product_description', $_REQUEST['description']);
        isset($_REQUEST['price']) && $product->setAttribute('product_price', $_REQUEST['price']);
        isset($_REQUEST['published']) && $product->setAttribute('product_published', $_REQUEST['published']);
        isset($_REQUEST['category_id']) && $product->setAttribute('category_id', $_REQUEST['category_id']);
        isset($_REQUEST['image_url']) && $product->setAttribute('product_image_url', $_REQUEST['image_url']);
        isset($_REQUEST['icon_url']) && $product->setAttribute('product_icon_url', $_REQUEST['icon_url']);
        isset($_REQUEST['hover_icon_url']) && $product->setAttribute('product_hover_icon_url', $_REQUEST['hover_icon_url']);
        isset($_REQUEST['class_name']) && $product->setAttribute('product_class_name', $_REQUEST['class_name']);

        $this->sendResponse($product->save() ? 'OK' : 'ERROR', array(
            'errors' => $product->getErrors(),
        ));
    }

    public function actionAll()
    {
        $this->sendResponse('OK', array(
            'products' => array_map(function($product) {
                return $product->toArray();
            }, Product::model()->findAll()),
        ));
    }

    public function actionDelete()
    {
        $productId = isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : null;
        if($product = Product::model()->findByPk($productId))
        {
            $product->delete();
        }

        $this->sendResponse('OK');
    }

    public function actionUpdateExtraInfo()
    {
        $productId = isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : null;
        if(!$product = Product::model()->findByPk($productId))
        {
            $this->sendResponse('PRODUCT_NOT_FOUND');
        }

        $extraInfoArray = empty($product->product_extra_info) ? array() : json_decode($product->product_extra_info, true);
        $extraInfoArray[$_REQUEST['key']] = empty($_REQUEST['value']) ? null : $_REQUEST['value'];
        $product->setAttribute('product_extra_info', json_encode($extraInfoArray));

        $saved = $product->save();
        $this->sendResponse($saved ? 'OK' : 'ERROR', array(
            'errors' => $product->getErrors(),
        ));
    }
}