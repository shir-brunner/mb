<?php

class SupporterCommand extends CConsoleCommand
{
    public function actionNewGuestNotification()
    {
        $guests = Yii::app()->db->createCommand('select count(*) from leaf.visitor where visitor_entry_time > now() - 63')->queryRow();
        if(reset($guests) > 0)
        {
            $supporters = User::model()->resetScope()->findAllByAttributes(array('user_is_supporter' => 1));

            Yii::app()->notifications->push($supporters, array(
                'title' => 'New Guest',
                'message' => 'New guest has just entered Leaf\'s homepage.',
                'notificationTriggerId' => NotificationTrigger::NEW_GUEST,
                'link' => Yii::app()->createUrl('supporter/guests'),
            ));
        }
    }
}