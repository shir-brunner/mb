<?php

class SmallClaimSpam
{
    public function toHtml(Request $request)
    {
        return Yii::app()->controller->renderPartial('//products/smallClaimSpam/document', array('request' => $request), true);
    }
}