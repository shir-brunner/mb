<?php

class FileController extends ApiController
{
    public function requireLogin()
    {
        return $this->action->id != 'create';
    }

    public function actionCreate()
    {
        if(empty($_FILES['file']) || empty($_FILES['file']['tmp_name']))
        {
            $this->sendResponse('ERROR', array(
                'errors' => array('Parameter "file" is required.'),
            ));
        }

        if(filesize($_FILES['file']['tmp_name']) > 15 * 1000 * 1000)
        {
            $this->sendResponse('ERROR', array(
                'errors' => array('File cannot be larger than 15MB'),
            ));
        }

        $file = new File();
        $fileName = uniqid();
        $fullPath = Yii::app()->params['uploadsFolder'] . DIRECTORY_SEPARATOR . $fileName;

        if(!move_uploaded_file($_FILES['file']['tmp_name'], $fullPath))
        {
            $this->sendResponse('ERROR', array(
                'errors' => array('Cannot move file to uploads folder'),
            ));
        }

        $file->setAttributes(array(
            'file_name' => empty($_REQUEST['name']) ? $_FILES['file']['name'] : $_REQUEST['name'],
            'file_path' => $fullPath,
            'file_content_type' => mime_content_type($fullPath),
        ));

        $saved = $file->save();
        $this->sendResponse($saved ? 'OK' : 'ERROR', array(
            'errors' => $file->getErrors(),
            'file' => $saved ? $file->toArray() : null,
        ));
    }

    public function actionDelete()
    {
        $fileId = isset($_REQUEST['file_id']) ? $_REQUEST['file_id'] : null;
        if($file = File::model()->findByPk($fileId))
        {
            unlink($file->file_path);
            $file->delete();
        }

        $this->sendResponse('OK');
    }

    public function actionDownload()
    {
        $fileId = isset($_REQUEST['file_id']) ? $_REQUEST['file_id'] : null;
        if(!$file = File::model()->findByPk($fileId))
        {
            $this->sendResponse('FILE_NOT_FOUND');
        }

        if(!file_exists($file->file_path))
        {
            $this->sendResponse('FILE_NOT_EXIST');
        }

        if(false !== ($handler = fopen($file->file_path, 'r')))
        {
            header('Content-Description: File Transfer');
            header('Content-Type: ' . $file->file_content_type);
            header('Content-Disposition: attachment; filename=' . $file->file_name);
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file->file_path));

            while(false !== ($chunk = fread($handler, 4096)))
            {
                echo $chunk;
            }
        }
        else
        {
            $this->sendResponse('CANNOT_OPEN_FILE');
        }
    }

    public function actionUploadImage()
    {
        if(empty($_FILES['file']) || empty($_FILES['file']['tmp_name']))
        {
            $this->sendResponse('ERROR', array(
                'errors' => array('Parameter "file" is required.'),
            ));
        }

        if(filesize($_FILES['file']['tmp_name']) > 15 * 1000 * 1000)
        {
            $this->sendResponse('ERROR', array(
                'errors' => array('File cannot be larger than 15MB'),
            ));
        }

        $imageName = uniqid();
        $fullPath = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $imageName;

        if(!move_uploaded_file($_FILES['file']['tmp_name'], $fullPath))
        {
            $this->sendResponse('ERROR', array(
                'errors' => array('Cannot move image file to images/uploads folder'),
            ));
        }

        $this->sendResponse('OK', array(
            'image' => array(
                'url' => Yii::app()->baseUrl . '/images/uploads/' . $imageName
            ),
        ));
    }
}