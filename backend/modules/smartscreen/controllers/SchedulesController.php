<?php
/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "SmartscreenContent".
 */
namespace backend\modules\smartscreen\controllers;

use backend\modules\smartscreen\models\SmartscreenFile;
use backend\modules\smartscreen\models\SmartscreenFileAPI;
use backend\modules\smartscreen\models\SmartscreenStation;
use backend\modules\smartscreen\Smartscreen;
use common\components\FApi;
use common\components\FConstant;
use common\widgets\FUploadedFile;
use Yii;
use backend\modules\smartscreen\models\SmartscreenContent;
use backend\modules\smartscreen\models\SmartscreenContentSearch;
//use yii\web\Controller;
use backend\controllers\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\UploadedFile;
use yii\imagine\Image;
use common\components\AccessRule;
use common\models\User;
use yii\filters\AccessControl;
use common\components\FHtml;
use common\components\Helper;
use yii\helpers\ArrayHelper;

/**
 * SmartscreenContentController implements the CRUD actions for SmartscreenContent model.
 */
class SchedulesController extends AdminController
{
   /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return FHtml::getControllerBehaviours([
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'only' => ['create', 'update', 'delete', 'view', 'index'],
                'rules' => [
                    [
                        'actions' => ['view', 'index'],
                        'allow' => true,
                        'roles' => [
                            User::ROLE_NONE //No access
                        ],
                    ],
                    [
                        'actions' => ['update', 'delete'],
                        'allow' => true,
                        'roles' => [
                            User::ROLE_MODERATOR, User::ROLE_ADMIN
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function isAuthorized() {
        return true;
    }

    /**
     * Lists all SmartscreenContent models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (($re = $this->isAuthorized()) !== true)
            return $re;

        $ime = isset($_REQUEST['ime']) ? $_REQUEST['ime'] : '';
        $start_time = isset($_REQUEST['start_time']) ? $_REQUEST['start_time'] : null;

        $date = isset($_REQUEST['date']) ? $_REQUEST['date'] : null;
        $finished_schedule_id = isset($_REQUEST['finished_schedule_id']) ? $_REQUEST['finished_schedule_id'] : '';
        $schedule_id = isset($_REQUEST['schedule_id']) ? $_REQUEST['schedule_id'] : '';
        $channel_id = isset($_REQUEST['channel_id']) ? $_REQUEST['channel_id'] : '';
        $limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : -1;
        $device_id = isset($_REQUEST['device_id']) ? $_REQUEST['device_id'] : '';

        Smartscreen::setDefaultTimezone();

        if (empty($start_time)) {
            $start_time = date('H:i');
        }
        if (empty($ime) && !empty($device_id))
        {
            $device = SmartscreenStation::findOne($device_id);
            if (empty($ime) && isset($device))
                $ime = $device->ime;
        }

        $result = Smartscreen::getDeviceSchedulesForAPI($ime, $date, $start_time, $finished_schedule_id, $channel_id, $schedule_id, $limit);

        if (is_string($result)) {
            return FApi::getOutputForAPI('', FConstant::ERROR, $result, ['code' => 205]);
        }

        return $this->render('index', $result);
    }

}
