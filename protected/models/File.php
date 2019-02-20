<?php

Yii::import('application.models._base.BaseFile');

class File extends BaseFile
{
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function toArray()
	{
		return array(
			'id' => $this->getPrimaryKey(),
			'name' => $this->file_name,
			'contentType' => $this->file_content_type,
			'createTime' => $this->file_create_time,
		);
	}
}