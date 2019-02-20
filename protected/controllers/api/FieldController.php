<?php

class FieldController extends ApiController
{
    public function actionCreate()
    {
        $field = new Field();

        isset($_REQUEST['name']) && $field->setAttribute('field_name', $_REQUEST['name']);
        isset($_REQUEST['field_type_id']) && $field->setAttribute('field_type_id', $_REQUEST['field_type_id']);
        isset($_REQUEST['template_id']) && $field->setAttribute('template_id', $_REQUEST['template_id']);
        isset($_REQUEST['help_text']) && $field->setAttribute('field_help_text', $_REQUEST['help_text']);
        isset($_REQUEST['order']) && $field->setAttribute('field_order', $_REQUEST['order']);
        isset($_REQUEST['required']) && $field->setAttribute('field_required', $_REQUEST['required']);
        isset($_REQUEST['step']) && $field->setAttribute('field_step', $_REQUEST['step']);

        $saved = $field->save();
        $this->sendResponse($saved ? 'OK' : 'ERROR', array(
            'field' => $saved ? $field->toArray() : null,
            'errors' => $field->getErrors(),
        ));
    }

    public function actionUpdate()
    {
        $fieldId = isset($_REQUEST['field_id']) ? $_REQUEST['field_id'] : null;

        if(!$field = Field::model()->findByPk($fieldId))
        {
            $this->sendResponse('FIELD_NOT_FOUND');
        }

        isset($_REQUEST['name']) && $field->setAttribute('field_name', $_REQUEST['name']);
        isset($_REQUEST['field_type_id']) && $field->setAttribute('field_type_id', $_REQUEST['field_type_id']);
        isset($_REQUEST['template_id']) && $field->setAttribute('template_id', $_REQUEST['template_id']);
        isset($_REQUEST['help_text']) && $field->setAttribute('field_help_text', $_REQUEST['help_text']);
        isset($_REQUEST['order']) && $field->setAttribute('field_order', $_REQUEST['order']);
        isset($_REQUEST['required']) && $field->setAttribute('field_required', $_REQUEST['required']);
        isset($_REQUEST['step']) && $field->setAttribute('field_step', $_REQUEST['step']);

        $saved = $field->save();
        $this->sendResponse($saved ? 'OK' : 'ERROR', array(
            'field' => $saved ? $field->toArray() : null,
            'errors' => $field->getErrors(),
        ));
    }

    public function actionDelete()
    {
        $fieldId = isset($_REQUEST['field_id']) ? $_REQUEST['field_id'] : null;

        if(!$field = Field::model()->findByPk($fieldId))
        {
            $this->sendResponse('FIELD_NOT_FOUND');
        }

        $field->delete();
        $this->sendResponse('OK');
    }

    public function actionChangeOrder()
    {
        $orders = isset($_REQUEST['orders']) ? $_REQUEST['orders'] : array();

        foreach($orders as $fieldId => $order)
        {
            Field::model()->updateByPk($fieldId, array('field_order' => $order));
        }

        $this->sendResponse('OK');
    }

    public function actionUpdateExtraInfo()
    {
        $fieldId = isset($_REQUEST['field_id']) ? $_REQUEST['field_id'] : null;

        if(!$field = Field::model()->findByPk($fieldId))
        {
            $this->sendResponse('FIELD_NOT_FOUND');
        }

        $extraInfoArray = empty($field->field_extra_info) ? array() : json_decode($field->field_extra_info, true);
        $extraInfoArray[$_REQUEST['key']] = empty($_REQUEST['value']) ? null : $_REQUEST['value'];
        $field->setAttribute('field_extra_info', json_encode($extraInfoArray));

        $saved = $field->save();
        $this->sendResponse($saved ? 'OK' : 'ERROR', array(
            'field' => $saved ? $field->toArray() : null,
            'errors' => $field->getErrors(),
        ));
    }

    public function actionUpdateExtraInfoBatch()
    {
        $fieldId = isset($_REQUEST['field_id']) ? $_REQUEST['field_id'] : null;

        if(!$field = Field::model()->findByPk($fieldId))
        {
            $this->sendResponse('FIELD_NOT_FOUND');
        }

        if(!isset($_REQUEST['batch']) || !is_array($_REQUEST['batch']))
        {
            $this->sendResponse('BATCH_PARAM_REQUIRED');
        }

        $booleanKeys = array('addFee', 'isFee');

        foreach($_REQUEST['batch'] as $item)
        {
            foreach($booleanKeys as $booleanKey)
            {
                if($item['key'] == $booleanKey)
                {
                    $item['value'] = intval($item['value']);
                }
            }

            $extraInfoArray = empty($field->field_extra_info) ? array() : json_decode($field->field_extra_info, true);

            if(isset($item['key']) && isset($item['value']))
            {
                $extraInfoArray[$item['key']] = $item['value'];
            }
            else if(isset($item['key']))
            {
                unset($extraInfoArray[$item['key']]);
            }

            $field->setAttribute('field_extra_info', json_encode($extraInfoArray));
        }

        $saved = $field->save();
        $this->sendResponse($saved ? 'OK' : 'ERROR', array(
            'field' => $saved ? $field->toArray() : null,
            'errors' => $field->getErrors(),
        ));
    }
}