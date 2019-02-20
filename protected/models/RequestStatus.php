<?php

Yii::import('application.models._base.BaseRequestStatus');

class RequestStatus extends BaseRequestStatus
{
	const AWAITING_FULFILLMENT = 1;
	const AWAITING_PAYMENT = 2;
	const IN_PROGRESS = 3;
	const READY = 4;
	const AWAITING_SIGNATURE  = 5;
	const SIGNED = 6;

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}