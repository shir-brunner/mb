<?php

class FinancialAgreement
{
    public function toHtml(Request $request)
    {
        return Yii::app()->controller->renderPartial('//products/financialAgreement/document', array('request' => $request), true);
    }
}