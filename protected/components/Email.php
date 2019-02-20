<?php

require dirname(__FILE__).'/../vendor/phpmailer/PHPMailerAutoload.php';

class Email
{
    public static function send($fromAddress, $to, $subject, $body, $fromName = null, $altBody = null, $replyTo = null, array $attachments = array())
    {
        $phpMailer = new PHPMailer;
        $phpMailer->isSMTP();
        $phpMailer->SMTPDebug = 0;
        $phpMailer->Host = 'smtp-relay.gmail.com';
        $phpMailer->Port = 587;
        $phpMailer->SMTPSecure = 'tls';
        $phpMailer->SMTPAuth = true;
        $phpMailer->Username = "assaf.winterstein@leaf.healthcare";
        $phpMailer->Password = "ykmpnzugfqkricwp";

        $phpMailer->setFrom($fromAddress, isset($fromName) ? $fromName : 'Leaf');

        if($replyTo)
        {
            $phpMailer->addReplyTo($replyTo);
        }

        if(!is_array($to))
        {
            $to = explode(',', $to);
        }

        foreach($to as $address)
        {
            $phpMailer->addAddress($address);
        }

        $phpMailer->Subject = $subject;
        $phpMailer->msgHTML($body);

        $phpMailer->AltBody = $altBody;

        foreach ($attachments as $attachmentName => $attachmentContent)
        {
            $phpMailer->addStringAttachment($attachmentContent, $attachmentName);
        }

        $phpMailer->createBody();

        try
        {
            if (!$phpMailer->send())
            {
                throw new Exception($phpMailer->ErrorInfo);
            }
        }
        catch(Exception $e)
        {
            //save for recovery, a cron will pick it up in a minute
            $emailScheduler = new EmailScheduler();
            $emailScheduler->email_scheduler_from_address = $fromAddress;
            $emailScheduler->email_scheduler_to_address = is_array($to) ? implode(',', $to) : $to;
            $emailScheduler->email_scheduler_subject = $subject;
            $emailScheduler->email_scheduler_body = $body;
            $emailScheduler->email_scheduler_from_name = $fromName;
            $emailScheduler->email_scheduler_alt_body = $altBody;
            $emailScheduler->email_scheduler_reply_to = $replyTo;
            $emailScheduler->save();
        }

        return $phpMailer;
    }

    public static function schedule($fromAddress, $to, $subject, $body, $fromName = null, $altBody = null, $replyTo = null)
    {
        $emailScheduler = new EmailScheduler();
        $emailScheduler->email_scheduler_from_address = $fromAddress;
        $emailScheduler->email_scheduler_to_address = $to;
        $emailScheduler->email_scheduler_subject = $subject;
        $emailScheduler->email_scheduler_body = $body;
        $emailScheduler->email_scheduler_from_name = $fromName;
        $emailScheduler->email_scheduler_alt_body = $altBody;
        $emailScheduler->email_scheduler_reply_to = $replyTo;
        $emailScheduler->save();
    }
}