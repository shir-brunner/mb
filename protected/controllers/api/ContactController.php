<?php

class ContactController extends ApiController
{
    public function requireLogin()
    {
        return $this->action->id != 'create';
    }

    public function actionCreate()
    {
        $contact = new Contact();

        isset($_REQUEST['name']) && $contact->setAttribute('contact_name', $_REQUEST['name']);
        isset($_REQUEST['email']) && $contact->setAttribute('contact_email', $_REQUEST['email']);
        isset($_REQUEST['phone']) && $contact->setAttribute('contact_phone', $_REQUEST['phone']);
        isset($_REQUEST['message']) && $contact->setAttribute('contact_message', $_REQUEST['message']);

        $saved = $contact->save();
        $this->sendResponse($saved ? 'OK' : 'ERROR', array(
            'errors' => $contact->getErrors(),
        ));
    }

    public function actionAll()
    {
        $this->sendResponse('OK', array(
            'contacts' => array_map(function($contact) {
                return $contact->toArray();
            }, Contact::model()->findAll())
        ));
    }

    public function actionDelete()
    {
        $contactId = isset($_REQUEST['contact_id']) ? $_REQUEST['contact_id'] : null;

        if($contact = Contact::model()->findByPk($contactId))
        {
            $contact->delete();
        }

        $this->sendResponse('OK');
    }
}