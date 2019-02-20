<?php

Yii::import('application.models._base.BaseRequest');

class Request extends BaseRequest
{
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function beforeValidate()
	{
		if($this->isNewRecord)
		{
			$this->request_hash = uniqid();
		}

		return parent::beforeValidate();
	}

	public function toArray()
	{
		return array(
			'id' => $this->getPrimaryKey(),
			'hash' => $this->request_hash,
			'product' => $this->product ? $this->product->toArray() : null,
			'user' => $this->user ? $this->user->toArray() : null,
			'status' => $this->requestStatus ? $this->requestStatus->toArray() : null,
			'fields' => empty($this->request_fields) ? array() : json_decode($this->request_fields, true),
			'documentHtml' => $this->request_document_html,
			'extraInfo' => $this->extraInfo(),
			'createTime' => $this->request_create_time,
		);
	}

/*	public function getFieldValue($fieldId)
	{
		$requestFields = array_filter($this->requestFields, function($requestField) use ($fieldId) {
			return $requestField->field_id == $fieldId;
		});

		$requestField = reset($requestFields);

		return $requestField ? $requestField->request_field_value : null;
	}*/
}