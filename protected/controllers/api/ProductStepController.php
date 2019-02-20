<?php

class ProductStepController extends ApiController
{
    public function actionCreate()
    {
        $productStep = new ProductStep();

        isset($_REQUEST['product_id']) && $productStep->setAttribute('product_id', $_REQUEST['product_id']);
        isset($_REQUEST['name']) && $productStep->setAttribute('product_step_name', $_REQUEST['name']);
        isset($_REQUEST['view']) && $productStep->setAttribute('product_step_view', $_REQUEST['view']);
        isset($_REQUEST['help_text']) && $productStep->setAttribute('product_step_help_text', $_REQUEST['help_text']);

        $saved = $productStep->save();
        $this->sendResponse($saved ? 'OK' : 'ERROR', array(
            'errors' => $productStep->getErrors(),
            'step' => $saved ? $productStep->toArray() : null,
        ));
    }

    public function actionUpdate()
    {
        $productStepId = isset($_REQUEST['product_step_id']) ? $_REQUEST['product_step_id'] : null;
        if(!$productStep = ProductStep::model()->findByPk($productStepId))
        {
            $this->sendResponse('PRODUCT_STEP_NOT_FOUND');
        }

        isset($_REQUEST['name']) && $productStep->setAttribute('product_step_name', $_REQUEST['name']);
        isset($_REQUEST['view']) && $productStep->setAttribute('product_step_view', $_REQUEST['view']);
        isset($_REQUEST['help_text']) && $productStep->setAttribute('product_step_help_text', $_REQUEST['help_text']);

        $saved = $productStep->save();
        $this->sendResponse($saved ? 'OK' : 'ERROR', array(
            'errors' => $productStep->getErrors(),
            'step' => $saved ? $productStep->toArray() : null,
        ));
    }

    public function actionDelete()
    {
        $productStepId = isset($_REQUEST['product_step_id']) ? $_REQUEST['product_step_id'] : null;
        if($productStep = ProductStep::model()->findByPk($productStepId))
        {
            $productStep->delete();
        }

        $this->sendResponse('OK');
    }

    public function actionChangeOrder()
    {
        $orders = isset($_REQUEST['orders']) ? $_REQUEST['orders'] : array();

        foreach($orders as $productStepId => $order)
        {
            ProductStep::model()->updateByPk($productStepId, array('product_step_order' => $order));
        }

        $this->sendResponse('OK');
    }
}