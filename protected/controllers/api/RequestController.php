<?php

class RequestController extends ApiController
{
    public function requireLogin()
    {
        return !in_array($this->action->id, array('create', 'updateFields'));
    }

    public function actionCreate()
    {
        $request = new Request();
        $request->setAttributes(array(
            'product_id' => isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : null,
            'request_status_id' => RequestStatus::AWAITING_FULFILLMENT,
        ));

        $saved = $request->save();
        $this->sendResponse($saved ? 'OK' : 'ERROR', array(
            'request' => $saved ? $request->toArray() : null,
            'errors' => $request->getErrors(),
        ));
    }

    public function actionUpdateFields()
    {
        if(!isset($_REQUEST['fields']) || !is_array($_REQUEST['fields']))
        {
            throw new CHttpException(500, 'Fields must be an array');
        }

        $requestHash = isset($_REQUEST['request_hash']) ? $_REQUEST['request_hash'] : null;
        if(!$request = Request::model()->findByAttributes(array('request_hash' => $requestHash)))
        {
            $this->sendResponse('REQUEST_NOT_FOUND');
        }

        $transaction = Yii::app()->db->beginTransaction();
        $fields = $_REQUEST['fields'];

        $discountCode = isset($fields['discountCode']) ? $fields['discountCode'] : null;
        if($discountCode)
        {
            $discountValid = false;

            $productExtraInfo = $request->product->extraInfo();
            if(isset($productExtraInfo['discounts']) && is_array($productExtraInfo['discounts']))
            {
                foreach($productExtraInfo['discounts'] as $discount)
                {
                    if(isset($discount['code']) && $discount['code'] == $discountCode)
                    {
                        $requestExtraInfo = $request->extraInfo();
                        $requestExtraInfo['discount'] = $discount;
                        $request->saveAttributes(array('request_extra_info' => json_encode($requestExtraInfo)));
                        $discountValid = true;
                    }
                }
            }

            if(!$discountValid)
            {
                $transaction->rollback();
                $this->sendResponse('INVALID_DISCOUNT_CODE');
            }
        }

        if(empty($request->user_id) && isset($fields['userEmail']))
        {
            $user = User::model()->findByAttributes(array('user_email' => $fields['userEmail']));

            if($user)
            {
                $request->saveAttributes(array('user_id' => $user->getPrimaryKey()));
            }
            else if($fields['userName'])
            {
                $user = new User();
                $password = uniqid();

                $pieces = explode(' ', trim($fields['userName']));

                $user->setAttributes(array(
                    'user_first_name' => isset($pieces[0]) ? $pieces[0] : null,
                    'user_last_name' => isset($pieces[1]) ? $pieces[1] : null,
                    'user_email' => $fields['userEmail'],
                    'password' => $password,
                ));

                if($user->save())
                {
                    $authAssignment = new AuthAssignment();
                    $authAssignment->setAttribute('itemname', 'Client');
                    $authAssignment->setAttribute('userid', $user->getPrimaryKey());
                    $authAssignment->save();

                    $request->saveAttributes(array('user_id' => $user->getPrimaryKey()));
                }
                else
                {
                    print_r($fields['userName']); exit;
                }
            }
        }

        $requestFields = empty($request->request_fields) ? array() : json_decode($request->request_fields, true);
        foreach($fields as $key => $value)
        {
            if($value == '')
            {
                unset($requestFields[$key]);
            }
            else
            {
                $requestFields[$key] = $value;
            }
        }
        $request->setAttribute('request_fields', json_encode($requestFields));

        if(!$request->save())
        {
            $transaction->rollback();
            $this->sendResponse('ERROR', array('errors' => $request->getErrors()));
        }

        $transaction->commit();
        $this->sendResponse('OK');
    }

    public function actionUpdate()
    {
        $requestId = isset($_REQUEST['request_id']) ? $_REQUEST['request_id'] : null;
        if(!$request = Request::model()->findByPk($requestId))
        {
            $this->sendResponse('REQUEST_NOT_FOUND');
        }

        isset($_REQUEST['request_status_id']) && $request->setAttribute('request_status_id', $_REQUEST['request_status_id']);
        isset($_REQUEST['document_html']) && $request->setAttribute('request_document_html', $_REQUEST['document_html']);

        $saved = $request->save();
        $this->sendResponse($saved ? 'OK' : 'ERROR', array(
            'errors' => $request->getErrors(),
        ));
    }

    public function actionAll()
    {
        $this->sendResponse('OK', array(
            'requests' => array_map(function($request) {
                return $request->toArray();
            }, Request::model()->findAll()),
        ));
    }

    public function actionDelete()
    {
        $requestId = isset($_REQUEST['request_id']) ? $_REQUEST['request_id'] : null;
        if($request = Request::model()->findByPk($requestId))
        {
            $request->delete();
        }

        $this->sendResponse('OK');
    }

    public function actionRestoreDocument()
    {
        $requestId = isset($_REQUEST['request_id']) ? $_REQUEST['request_id'] : null;
        if(!$request = Request::model()->findByPk($requestId))
        {
            $this->sendResponse('REQUEST_NOT_FOUND');
        }

        $productClassName = $request->product->product_class_name;
        $productClass = new $productClassName();

        $request->setAttribute('request_document_html', $productClass->toHtml($request));

        $saved = $request->save();
        $this->sendResponse($saved ? 'OK' : 'ERROR', array(
            'errors' => $request->getErrors(),
            'request' => $saved ? $request->toArray() : null,
        ));
    }

    public function actionExport()
    {
        $requestId = isset($_REQUEST['request_id']) ? $_REQUEST['request_id'] : null;
        if(!$request = Request::model()->findByPk($requestId))
        {
            $this->sendResponse('REQUEST_NOT_FOUND');
        }

        $format = isset($_REQUEST['format']) ? $_REQUEST['format'] : 'pdf';
        switch($format)
        {
            default:
                $this->outputPdf($request);
        }
    }

    private function outputPdf(Request $request)
    {
        require_once Yii::app()->basePath . '/vendors/mpdf/vendor/mpdf/mpdf/mpdf.php';


        $html = '';

        $html .= '<html>';
        $html .= '    <body>';
        /*$className = $request->product->product_class_name;
        $d = new $className();
        $html .= $d->toHtml($request);*/
        $html .= $request->request_document_html;
        $html .= '    </body>';
        $html .= '</html>';

        $html = str_replace('<div style="clear: both;">&nbsp;</div>', '<div style="clear: both;"></div>', $html);
        $html = str_replace('padding: 94px;', '', $html);

        $mpdf = new mPDF('UTF-8', 'A4', '', '', 25, 25, 25, 25);
        $mpdf->WriteHTML($html);

        $extraInfo = $request->extraInfo();
        if(isset($extraInfo['fileInfos']) && is_array($extraInfo['fileInfos']))
        {
            $fileInfos = $extraInfo['fileInfos'];
            usort($fileInfos, function($a, $b) {
                if(!isset($a['order']) || !isset($b['order']) || $a['order'] == $b['order'])
                {
                    return 0;
                }

                return (intval($a['order']) < intval($b['order'])) ? -1 : 1;
            });

            foreach($fileInfos as $fileInfo)
            {
                if(empty($fileInfo['attached']) || !intval($fileInfo['attached']) || empty($fileInfo['file']))
                {
                    continue;
                }

                if($file = File::model()->findByPk($fileInfo['file']['id']))
                {
                    if($file->file_content_type == 'application/pdf')
                    {
                        $mpdf->AddPage();
                        $mpdf->SetImportUse();
                        $pageCount = $mpdf->SetSourceFile($file->file_path);
                        for($i = 1; $i <= $pageCount; $i++)
                        {
                            $mpdf->UseTemplate($mpdf->ImportPage());

                            $i < $pageCount && $mpdf->AddPage();
                        }
                    }
                    else if(@is_array(getimagesize($file->file_path))) //check if it's an image
                    {
                        $mpdf->AddPage();
                        $mpdf->WriteHtml('<div style="text-align: center;"><img src="' . $file->file_path . '" /></div>');
                    }
                }
            }
        }

        $mpdf->Output();
    }

    public function actionUpdateExtraInfo()
    {
        $requestId = isset($_REQUEST['request_id']) ? $_REQUEST['request_id'] : null;
        if(!$request = Request::model()->findByPk($requestId))
        {
            $this->sendResponse('REQUEST_NOT_FOUND');
        }

        $extraInfoArray = empty($request->request_extra_info) ? array() : json_decode($request->request_extra_info, true);
        $extraInfoArray[$_REQUEST['key']] = empty($_REQUEST['value']) ? null : $_REQUEST['value'];
        $request->setAttribute('request_extra_info', json_encode($extraInfoArray));

        $saved = $request->save();
        $this->sendResponse($saved ? 'OK' : 'ERROR', array(
            'errors' => $request->getErrors(),
        ));
    }

    /*private function outputPdf(Request $request)
    {
        require_once Yii::app()->basePath . '/vendors/tcpdf/tcpdf.php';

        $html = '';

        $html .= '<html>';
        $html .= '    <head>';
        //$html .= '      <style>' . file_get_contents(Yii::getPathOfAlias('application') . '\..\css\mb\pdf.css') . '</style>';
        $html .= '    </head>';
        $html .= '    <body style="color: red;">';
        $html .=        'hello world';
        $html .= '    </body>';
        $html .= '</html>';

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->output();
    }*/

    /*private function outputPdf(Request $request)
    {
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        require_once Yii::app()->basePath . '/vendors/dompdf/autoload.inc.php';
        spl_autoload_register(array('YiiBase', 'autoload'));

        $dompdf = new Dompdf\Dompdf();

        $html = '';

        $html .= '<html>';
        $html .= '    <head>';
        //$html .= '      <style>' . file_get_contents(Yii::getPathOfAlias('application') . '\..\css\mb\pdf.css') . '</style>';
        $html .= '    </head>';
        $html .= '    <body>';
        $html .=        $request->request_document_html;
        $html .= '    </body>';
        $html .= '</html>';




        $html = '';

        $html .= '<html>';
        $html .= '    <head>';
        $html .= '    </head>';
        $html .= '    <body>';
        $className = $request->product->product_class_name;
        $d = new $className();
        $html .= $d->toHtml($request);
        $html .= '    </body>';
        $html .= '</html>';


        $dompdf->loadHtml($html);
        $dompdf->render();
        $dompdf->stream($request->product->product_name . '.pdf', array('Attachment' => false));
    }*/

    /*public function actionPreview()
    {
        $requestId = isset($_REQUEST['request_id']) ? $_REQUEST['request_id'] : null;
        if(!$request = Request::model()->findByPk($requestId))
        {
            $this->sendResponse('REQUEST_NOT_FOUND');
        }

        require Yii::app()->basePath . '/vendors/phpword/bootstrap.php';

        if(!$request->file)
        {
            $this->sendResponse('FILE_NOT_FOUND');
        }

        $find = array();
        $replace = array();

        foreach($request->requestFields as $requestField)
        {
            $find[] = $requestField->field->field_name;
            $replace[] = $requestField->request_field_value;
        }

        $tempFullPath = Yii::app()->params['uploadsFolder'] . DIRECTORY_SEPARATOR . uniqid();

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($request->file->file_path);
        $templateProcessor->setValue($find, $replace);
        $templateProcessor->saveAs($tempFullPath);

        $format = isset($_REQUEST['format']) ? $_REQUEST['format'] : 'pdf';
        switch($format)
        {
            case 'docx':
                $this->outputDocx($tempFullPath);
                break;
            default:
                $this->outputPdf($tempFullPath);
        }

        unlink($tempFullPath);
    }*/
}