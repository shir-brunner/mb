<?php

class RequestController extends GxController
{
    public function actionExport()
    {
        $requestId = isset($_REQUEST['request_id']) ? $_REQUEST['request_id'] : null;

        if(!$request = Request::model()->findByPk($requestId))
        {
            throw new CHttpException(404, 'הדף לא נמצא');
        }

        require Yii::app()->basePath . '/vendors/tcpdf/tcpdf.php';

        $html = $this->renderPartial('export', array('request' => $request), true);
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetFont('freeserif', '', 12);
        $pdf->AddPage();
        $pdf->setRTL(true);
        $pdf->writeHTML($html);
        $pdf->Output();
    }

    public function actionIndex()
    {
        $this->pageTitle = 'Requests';
        $this->render('index');
    }

    public function actionEdit($id)
    {
        if(!$request = Request::model()->findByPk($id))
        {
            throw new CHttpException(404, 'Request not found');
        }

        $this->pageTitle = 'Edit Request';
        $this->render('edit', array('request' => $request));
    }
}