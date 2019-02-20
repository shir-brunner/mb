<?php

Yii::import('application.models._base.BaseProduct');

class Product extends BaseProduct
{
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function toArray()
	{
		return array(
			'id' => $this->getPrimaryKey(),
			'name' => $this->product_name,
			'category' => $this->category ? $this->category->toArray() : null,
			'description' => $this->product_description,
			'published' => intval($this->product_published),
			'imageUrl' => $this->product_image_url,
			'iconUrl' => $this->product_icon_url,
			'hoverIconUrl' => $this->product_hover_icon_url,
		);
	}
}