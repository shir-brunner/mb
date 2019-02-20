<?php

class SmallClaimCar
{
    public function toHtml(Request $request)
    {
        return Yii::app()->controller->renderPartial('//products/smallClaimCar/document', array('request' => $request), true);
    }
}