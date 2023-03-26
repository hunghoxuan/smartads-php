<?php
/**
 * Created by PhpStorm.
 * User: Darkness
 * Date: 11/30/2016
 * Time: 2:00 PM
 */

namespace common\components;

use yii\base\Exception;
use yii\helpers\Json;
use Yii;
use Globals;


class FNotification extends FModel
{
    public static function push($recipient, $event, $params = [], $channel = 'notification')
    {
        $result = [];
        if (isset(\Yii::$app->redis)) {
            $result[] = array_merge($result, ['redis' => \Yii::$app->redis->executeCommand('PUBLISH', [
                'channel' => $channel,
                'message' => Json::encode(array_merge(['recipient' => $recipient, 'event' => $event], $params))
            ])]);
        }
        return $result;
    }

    /**
     * @param $notificationModel
     */
    public static function executeNotification($notificationModel)
    {

        if (!$notificationModel->is_sent || $notificationModel->sent_date > FHtml::Now()) {
            return;
        }
        $sent_type = FHtml::getFieldValue($notificationModel, 'sent_type');
        if (is_string($sent_type))
            $sent_type = FHtml::decode($sent_type, true);

        if (empty($sent_type))
            return;

        if (empty($notificationModel->sent_date))
            $notificationModel->sent_date = FHtml::Now();

        if (is_array($sent_type) && (in_array('all', $sent_type) || in_array('app', $sent_type))) {

            $url = ['api/push-notification',
                "message" => FHtml::getFieldValue($notificationModel, 'message'),
                'action' => FHtml::getFieldValue($notificationModel, 'action'), //api/news/detail
                'params' => FHtml::getFieldValue($notificationModel, 'params') //id=10
            ];

            FApi::async($url);
        }
    }

    public static function pushIosFcm($registrationIDs, $msg, $data)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $api_key = Setting::getSettingValueByKey(FConstant::GOOGLE_API_KEY);

        $loop = ceil(count($registrationIDs) / 1000);


        for ($i = 1; $i <= $loop; $i++) {
            if (0 < count($registrationIDs) && count($registrationIDs) < 1000)
                $registrationID = $registrationIDs;
            else {
                $registrationID = array_slice($registrationIDs, 0, 1000);
                $registrationIDs = array_slice($registrationIDs, 1000, count($registrationIDs));
            }

            $notification = array(
                'text' => $msg
            );

            if (isset($data['title'])) {
                $title = strlen($data['title']) != 0 ? $data['title'] : '';
                $notification['title'] = $title;
                unset($data['title']);
            }

            //$arrayToSend = array('to' => $token, 'notification' => $notification, 'priority'=>'high');
            $arrayToSend = array('registration_ids' => $registrationID, 'notification' => $notification, 'data' => $data, 'priority' => 'high');

            $json = json_encode($arrayToSend);

            $headers = array(
                'Authorization: key=' . $api_key,
                'Content-Type: application/json'
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_exec($ch);
            curl_close($ch);
        }
    }

    public static function pushAndroid($a_devices, $message, $params = [])
    {
        $api_key = \backend\models\SettingAPI::getSettingValueByKey(\Globals::GOOGLE_API_KEY);

        //$url = 'https://android.googleapis.com/gcm/send';
        $url = 'https://fcm.googleapis.com/fcm/send';

        $loop = ceil(count($a_devices) / 1000);

        $msg = array('message' => $message);

        if (!empty($params)) {
            $msg = array_merge($msg, $params);
        }

        for ($i = 1; $i <= $loop; $i++) {
            if (0 < count($a_devices) && count($a_devices) < 1000)
                $registrationID = $a_devices;
            else {
                $registrationID = array_slice($a_devices, 0, 1000);
                $a_devices = array_slice($a_devices, 1000, count($a_devices));
            }

            $fields = array
            (
                'registration_ids' => $registrationID,
                'data' => $msg
            );

            $headers = array(
                'Authorization: key=' . $api_key,
                'Content-Type: application/json'
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            curl_close($ch);
            //echo $result;
        }
    }
}