<?php
namespace backend\actions;

use backend\models\ObjectCategoryAPI;
use backend\modules\app\models\AppUserAPI;
use yii\helpers\Inflector;
use yii\helpers\Url;
use Yii;

class TestAction extends BaseAction
{

    public function run()
    {
        $result = array();
        $content = '{"xx":"Xxxx","yy":"Yyyy"}';
        $type = "complex";
        $data = json_decode($content, true);
        $result["type"] = $type;
        $result["data"] = $data;
        echo json_encode($result);die;


        $rows_per_page = 20;
        $start_index = 0;
        $conditionFoods = "id > 0";

        //$foods = ObjectCategoryAPI::find()->where($conditionFoods)->orderBy(['created_date'=> SORT_DESC,'name'=>SORT_ASC])->offset($start_index)->limit($rows_per_page)->all();

        $data = ObjectCategoryAPI::getDb()->cache(function ($db) use($conditionFoods, $start_index, $rows_per_page) {
            return ObjectCategoryAPI::find()->where($conditionFoods)->orderBy(['created_date'=> SORT_DESC,'name'=>SORT_ASC])->offset($start_index)->limit($rows_per_page)->all();
        },60);



/*
        $key = "foods";
        $cache = Yii::$app->cache;
        $data = $cache->get($key);

        if ($data === false) {
            $cache->set($key, $foods, 60);
        }
*/
        return $data;

        $a = 0.17;
        $b = 1 - 0.83; //0.17

        if($a == $b ){
            echo 'a = b';
        }
        else {
            echo 'a != b';
        }


        if (abs(($a-$b)/$b) < 0.00001) {
            echo "a = b";
        }


		die;
		Yii::$app->mailer->compose()
		->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
		->setTo('immrhy@gmail.com')
		->setSubject('['. Yii::$app->name .'] Welcome new member')
		->setTextBody('Plain text content')
		->setHtmlBody('<b>HTML content</b>')
		->send();


//        $check = AppUserAPI::find()->innerJoinWith('pro')->orderBy('app_user_pro.rate DESC')->all();
//
//        return $check;
/*
        $url = \Yii::$app->getUrlManager()->getHostInfo() . Url::to(['utility/pushNotification',
                'msg' => 'Hello',
            ]);


        ignore_user_abort(true);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1); //trick: don't wait for response
        curl_exec($ch);
        curl_close($ch);
*/
//        $pdf = new Pdf([
//            //'filename' => $original_path,
//            // set to use core fonts only
//            'mode' => Pdf::MODE_UTF8,
//            // A4 paper format
//            'format' => Pdf::FORMAT_A4,
//            // portrait orientation
//            'orientation' => Pdf::ORIENT_PORTRAIT,
//            // stream to browser inline
//            'destination' => Pdf::DEST_BROWSER,
//            // your html content input
//            'content' => 'खजुराहो के मंदिरों में क्यों बनाई गई इरोटिक मूर्तियां, यह है 4 मान्यताएं',
//            // format content from your own css file if needed or use the
//            // enhanced bootstrap css built by Krajee for mPDF formatting
//            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
//            // any css to be embedded if required
//            'options' => ['title' => 'खजुराहो के मंदिरों में क्यों बनाई गई इरोटिक मूर्तियां, यह है 4 मान्यताएं'],
//            // call mPDF methods on the fly
//            'methods' => [
//                'SetHeader'=>['खजुराहो के मंदिरों में क्यों बनाई गई इरोटिक मूर्तियां, यह है 4 मान्यताएं'],
//                'SetFooter'=>['{PAGENO}'],
//            ]
//        ]);
//        $pdf->render();
        /*
        $devices = AppUserDevice::find()->all();
        $a_devices = array();
        $i_devices = array();
        foreach ($devices as $row) {
            if ($row->type == 1)
                $a_devices[] = $row->gcm_id;
            if ($row->type == 2)
                $i_devices[] = $row->gcm_id;
        }

        $message = 'Just a push';

        if (count($a_devices) > 0) {
            try {
                \Globals::pushAndroid($a_devices, $message);
            } catch (\Exception $e) {
                 var_dump($e);
            }
        }
        if (count($i_devices) > 0) {
            try {
                \Globals::pushIos($i_devices, $message);
            } catch (\Exception $e) {
                var_dump($e);
            }
        }

/*
// buffer all upcoming output
        ob_start();
        echo "Here's my awesome web page";

// get the size of the output
        $size = ob_get_length();

// send headers to tell the browser to close the connection
        header("Content-Length: $size");
        header('Connection: close');

// flush all output
        ob_end_flush();
        ob_flush();
        flush();

// close current session
        if (session_id()) session_write_close();

        /******** background process starts here ********/
//        sleep(20);
//        $check = AppUser::findOne(1);
//        $check->is_available = 9;
//        $check->save();
//


//        $latitude = isset($_REQUEST['latitude']) ? $_REQUEST['latitude'] : '21.0425204';
//        $longitude = isset($_REQUEST['longitude']) ? $_REQUEST['longitude'] : '105.8002436';
//        $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'handy work'; //delivery / handy work / house work / other
//        $distance = isset($_REQUEST['distance']) ? $_REQUEST['distance'] : 1000; //  5  : 0 -> not search by distance
//
//
//        $rows = (new Query())
//            ->select(['ad.*'])
//            ->from("(SELECT *, (((acos(sin((" . $latitude . "*pi()/180)) *
//                    sin((`lat`*pi()/180))+cos((" . $latitude . "*pi()/180)) *
//                    cos((`lat`*pi()/180)) * cos(((" . $longitude . " - `long`)*pi()/180))))*180/pi())*60*1.1515*1.609344)
//            as distance
//            FROM `app_user`) place INNER JOIN app_user_skill ak ON place.id = ak.user_id INNER JOIN app_user_device ad ON place.id = ad.user_id")
//            ->where("place.distance <= $distance AND place.is_available = 1 AND ak.work LIKE '%$type%'")
//            ->all();

//        $rows = AppUserDevice::find()->all();
//        $devices = ArrayHelper::getColumn($rows, 'gcm_id');

/*        $a_device_records = ArrayHelper::getColumn($rows, function ($element) {
                if($element['type'] == '1' )
                return  $element['gcm_id'];
            else
                return '';
        });

        $i_device_records = ArrayHelper::getColumn($rows, function ($element) {
            if ($element['type'] == 2)
                return  $element['gcm_id'];
            else
                return '';
        });*/


//        $devices = array();
//        $a_devices = array();
//        $i_devices = array();
//
//        foreach ($rows as $row){
//            if($row['type'] == 1)
//                $a_devices[] = $row['gcm_id'];
//            if($row['type'] == 2)
//                $i_devices[] = $row['gcm_id'];
//            $devices[] = $row['gcm_id'];
//        }
//
//
//        $response = array(
//            'status' => \Globals::SUCCESS,
//            'data' => '',
//            'message' => 'OK'
//        );
//        return $response;
     /*   return \Yii::$app->mailer->compose(['html' => 'welcome-html', 'text' => 'welcome-text', 'htmlLayout'=>'@layouts/welcome-html.php'], ['user' => AppUser::findOne(3)])
            ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
            ->setTo('immrhy@gmail.com')
            ->setSubject('Welcome to Instant Helper')
            ->send();*/
    }
}
