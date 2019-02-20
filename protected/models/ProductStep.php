<?php

Yii::import('application.models._base.BaseProductStep');

class ProductStep extends BaseProductStep
{
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function toArray()
	{
		return array(
			'id' => $this->getPrimaryKey(),
			'name' => $this->product_step_name,
			'view' => $this->product_step_view,
			'helpText' => $this->product_step_help_text,
			'order' => $this->product_step_order,
		);
	}
}