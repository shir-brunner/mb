<?php

class FeedCommand extends CConsoleCommand
{
    private $_lastCronRun = null;

    public function actionCreatePosts()
    {
        Yii::app()->setComponent('user', new ConsoleWebUser());

        $criteria = new CDbCriteria();
        $criteria->order = 'feed_cron_run_time DESC';
        $this->_lastCronRun = FeedCronRun::model()->find($criteria);

        $startTime = date('Y-m-d H:i:s');

        $this->fromFoodLogs();
        $this->fromWorkoutLogs();
        $this->fromClientIndices();

        $feedCronRun = new FeedCronRun();
        $feedCronRun->setAttribute('feed_cron_run_time', $startTime);
        $feedCronRun->save();
    }

    private function fromFoodLogs()
    {
        $criteria = new CDbCriteria();

        if($this->_lastCronRun)
        {
            $criteria->addCondition("t.client_food_log_create_time > '" . $this->_lastCronRun->feed_cron_run_time . "'");
        }

        $criteria->with = array(
            'client' => array(
                'with' => array(
                    'user' => array(
                        'parents' => array(),
                    ),
                ),
            ),
            'food',
        );

        $criteria->compare('client.client_is_demo', 0);

        $clientFoodLogs = ClientFoodLog::model()->findAll($criteria);
        $grouped = array();
        foreach($clientFoodLogs as $clientFoodLog)
        {
            $grouped[$clientFoodLog->client_id][] = $clientFoodLog;
        }

        foreach($grouped as $clientId => $foodLogs)
        {
            $feedTrigger = new FeedTrigger();
            $feedTrigger->setAttributes(array(
                'feed_trigger_type' => FeedTrigger::TYPE_FOOD_LOG_ADDED,
                'user_id' => $foodLogs[0]->client->user_id,
            ));

            $feedTrigger->setExtraInfo('clientFoodLogs', array_map(function($clientFoodLog) {
                return array(
                    'id' => $clientFoodLog->getPrimaryKey(),
                    'food' => $clientFoodLog->food ? array(
                        'id' => $clientFoodLog->getPrimaryKey(),
                        'name' => $clientFoodLog->food->food_name,
                    ) : null,
                    'comment' => $clientFoodLog->client_food_log_comment,
                );
            }, $foodLogs));

            $foodLogsWithImages = array_values(array_filter($foodLogs, function($clientFoodLog) {
                return $clientFoodLog->client_food_log_image_cdn_key;
            }));

            count($foodLogsWithImages) && $feedTrigger->setExtraInfo('images', array_map(function($clientFoodLog) {
                return array(
                    'cdnKey' => $clientFoodLog->client_food_log_image_cdn_key,
                    'fullImage' => array(
                        'cdnKey' => $clientFoodLog->client_food_log_full_image_cdn_key,
                    ),
                );
            }, $foodLogsWithImages));

            if(!$feedTrigger->save())
            {
                continue;
            }

            $targetUsers = $foodLogs[0]->client->user->getParents();
            $targetUserIds = array_map(function($user) { return $user->getPrimaryKey(); }, $targetUsers);
            $targetUserIds[] = $feedTrigger->user_id;

            foreach($targetUserIds as $targetUserId)
            {
                $feedTarget = new FeedTarget();
                $feedTarget->setAttributes(array(
                    'user_id' => $targetUserId,
                    'feed_trigger_id' => $feedTrigger->getPrimaryKey(),
                ));

                $feedTarget->save();
            }

            $foodTitles = array();

            foreach($foodLogs as $clientFoodLog)
            {
                if($clientFoodLog->food)
                {
                    $foodTitles[] = $clientFoodLog->food->food_name;
                }
                else if(!empty($clientFoodLog->client_food_log_comment))
                {
                    $foodTitles[] = $clientFoodLog->client_food_log_comment;
                }
            }

            $heShe = $foodLogs[0]->client->user->user_gender == User::MALE ? 'he' : ($foodLogs[0]->client->user->user_gender == User::FEMALE ? 'she' : 'he / she');
            $foodsString = implode(', ', $foodTitles);
            $title = empty($foodTitles) ? ('logged what ' . $heShe . ' ate') : ('logged ' . (strlen($foodsString) > 100 ? (substr($foodsString, 0, 100) . '..') : $foodsString));

            Yii::app()->notifications->push($targetUsers, array(
                'title' => 'Food Log Alert',
                'message' => ucwords($foodLogs[0]->client->user->getFullName()) . ' just ' . $title . '.',
                'notificationTriggerId' => NotificationTrigger::CLIENT_ADDED_FOOD_TO_ACTIVITY_LOG,
                'triggerUserId' => $feedTrigger->user_id,
            ));
        }
    }

    private function fromWorkoutLogs()
    {
        $criteria = new CDbCriteria();

        if($this->_lastCronRun)
        {
            $criteria->addCondition("t.client_workout_log_create_time > '" . $this->_lastCronRun->feed_cron_run_time . "'");
        }

        $criteria->with = array(
            'client' => array(
                'with' => array(
                    'user' => array(
                        'parents' => array(),
                    ),
                ),
            ),
            'workout',
        );

        $criteria->compare('client.client_is_demo', 0);

        $clientWorkoutLogs = ClientWorkoutLog::model()->findAll($criteria);
        $grouped = array();
        foreach($clientWorkoutLogs as $clientWorkoutLog)
        {
            $grouped[$clientWorkoutLog->client_id][] = $clientWorkoutLog;
        }

        foreach($grouped as $clientId => $workoutLogs)
        {
            $feedTrigger = new FeedTrigger();
            $feedTrigger->setAttributes(array(
                'feed_trigger_type' => FeedTrigger::TYPE_WORKOUT_LOG_ADDED,
                'user_id' => $workoutLogs[0]->client->user_id,
            ));

            $feedTrigger->setExtraInfo('clientWorkoutLogs', array_map(function($clientWorkoutLog) {
                return array(
                    'id' => $clientWorkoutLog->getPrimaryKey(),
                    'workout' => $clientWorkoutLog->workout ? array(
                        'id' => $clientWorkoutLog->getPrimaryKey(),
                        'name' => $clientWorkoutLog->workout->workout_name,
                    ) : null,
                    'comment' => $clientWorkoutLog->client_workout_log_comment,
                );
            }, $workoutLogs));

            if(!$feedTrigger->save())
            {
                continue;
            }

            $targetUsers = $workoutLogs[0]->client->user->getParents();
            $targetUserIds = array_map(function($user) { return $user->getPrimaryKey(); }, $targetUsers);
            $targetUserIds[] = $feedTrigger->user_id;

            foreach($targetUserIds as $targetUserId)
            {
                $feedTarget = new FeedTarget();
                $feedTarget->setAttributes(array(
                    'user_id' => $targetUserId,
                    'feed_trigger_id' => $feedTrigger->getPrimaryKey(),
                ));

                $feedTarget->save();
            }
        }
    }

    private function fromClientIndices()
    {
        $criteria = new CDbCriteria();

        if($this->_lastCronRun)
        {
            $criteria->addCondition("t.client_index_history_time > '" . $this->_lastCronRun->feed_cron_run_time . "'");
        }

        $criteria->with = array(
            'clientIndex' => array(
                'with' => array(
                    'unit' => array(),
                    'client' => array(
                        'with' => array(
                            'user' => array(
                                'parents' => array(),
                            ),
                        ),
                    ),
                ),
            ),
        );

        $criteria->compare('client.client_is_demo', 0);

        foreach(ClientIndexHistory::model()->findAll($criteria) as $clientIndexHistory)
        {
            //only if the client updated his own index and index was not updated by someone else
            if($clientIndexHistory->updator_user_id == $clientIndexHistory->clientIndex->client->user_id)
            {
                $feedTrigger = new FeedTrigger();
                $feedTrigger->setAttributes(array(
                    'feed_trigger_type' => FeedTrigger::TYPE_CLIENT_INDEX_UPDATED,
                    'user_id' => $clientIndexHistory->clientIndex->client->user_id,
                ));

                $feedTrigger->setExtraInfo('clientIndex', array(
                    'name' => $clientIndexHistory->clientIndex->client_index_name,
                    'value' => $clientIndexHistory->client_index_history_value,
                    'metricValue' => $clientIndexHistory->clientIndex->metricValue(),
                    'unit' => array(
                        'name' => $clientIndexHistory->clientIndex->unit->unit_name,
                        'metricName' => $clientIndexHistory->clientIndex->unit->unit_metric_name,
                    ),
                ));

                if(!$feedTrigger->save())
                {
                    continue;
                }

                $targetUsers = $clientIndexHistory->clientIndex->client->user->getParents();
                $targetUserIds = array_map(function($user) { return $user->getPrimaryKey(); }, $targetUsers);
                $targetUserIds[] = $feedTrigger->user_id;

                foreach($targetUserIds as $targetUserId)
                {
                    $feedTarget = new FeedTarget();
                    $feedTarget->setAttributes(array(
                        'user_id' => $targetUserId,
                        'feed_trigger_id' => $feedTrigger->getPrimaryKey(),
                    ));

                    $feedTarget->save();
                }

                $user = $clientIndexHistory->clientIndex->client->user;
                $hisHer = $user->user_gender == User::MALE ? 'his' : ($user->user_gender == User::FEMALE ? 'her' : 'his / her');

                Yii::app()->notifications->push($targetUsers, array(
                    'title' => 'Index Update',
                    'message' => ucwords($clientIndexHistory->clientIndex->client->user->getFullName()) . ' just updated ' . $hisHer . ' ' . strtolower($clientIndexHistory->clientIndex->client_index_name) . '.',
                    'notificationTriggerId' => NotificationTrigger::CLIENT_UPDATED_INDEX,
                    'triggerUserId' => $feedTrigger->user_id,
                ));
            }
        }
    }
}