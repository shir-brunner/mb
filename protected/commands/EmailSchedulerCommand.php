<?php

class EmailSchedulerCommand extends CConsoleCommand
{
    public function actionProcess()
    {
        $emails = EmailScheduler::model()->findAllByAttributes(array('email_scheduler_processed' => 0));
        foreach ($emails as $email)
        {
            $email->email_scheduler_processed = 1;
            $email->save();
        }

        foreach ($emails as $email)
        {
            Email::send($email->email_scheduler_from_address, $email->email_scheduler_to_address, $email->email_scheduler_subject, $email->email_scheduler_body, $email->email_scheduler_from_name, $email->email_scheduler_alt_body, $email->email_scheduler_reply_to);
        }
    }
}