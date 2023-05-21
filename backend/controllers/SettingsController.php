<?php

/**
 *
 ***
 * This is the customized model class for table "Settings".
 */

namespace backend\controllers;

use backend\models\Setting;
use Yii;
use backend\models\Settings;
use backend\models\SettingsSearch;
//use yii\web\Controller;
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

use yii\helpers\ArrayHelper;

/**
 * SettingsController implements the CRUD actions for Settings model.
 */
class SettingsController extends AdminController
{
    protected $moduleName = 'Settings';
    protected $moduleTitle = 'Settings';
    protected $moduleKey = 'settings';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
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
                            User::ROLE_MODERATOR,
                            User::ROLE_ADMIN
                        ],
                    ]
                ],
            ],
        ];
    }

    /**
     * Lists all Settings models.
     * @return mixed
     */
    public function actionIndex()
    {
        $file = FHtml::getParamFile();
        $canEdit = true;
        if (empty($file) || !is_file($file) || !is_writable($file)) {
            FHtml::addError(FHtml::t('common', "Permission denied") . ': ' . $file);
            $canEdit = false;
        }

        $model = \backend\models\Settings::getInstance();
        if (!empty($_POST)) {
            $model->load($_POST['Settings']);
            $model->save();
            FHtml::Cache()->flush();
            FHtml::RefreshCache();
            $this->redirect(['index']);
        }
        return $this->render('index', [
            'searchModel' => null,
            'dataProvider' => null,
            'model' => $model,
            'canEdit' => $canEdit
        ]);
    }
}
