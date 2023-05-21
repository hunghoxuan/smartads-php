<?php

/**
 *
 ***
 * This is the customized model class for table "SettingsText".
 */

namespace backend\modules\system\controllers;

use Yii;
use backend\modules\system\models\SettingsText;
use backend\models\SettingsTextSearch;
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
 * SettingsTextController implements the CRUD actions for SettingsText model.
 */
class SettingsTextController extends AdminController
{
    protected $moduleName = 'SettingsText';
    protected $moduleTitle = 'Settings Text';
    protected $moduleKey = 'settings_text';
    protected $object_type = 'settings_text';

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
                            User::ROLE_ALL
                        ],
                    ],
                    [
                        'actions' => ['update', 'create', 'delete'],
                        'allow' => true,
                        'roles' => [
                            User::ROLE_MODERATOR,
                            User::ROLE_ADMIN
                        ],
                    ],
                ],
            ],
        ]);
    }

    /**
     * Lists all SettingsText models.
     * @return mixed
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $lang = FHtml::currentLang();
        $model = SettingsText::findOne(['lang' => $lang]);

        if ($request->isAjax) {
            return FHtml::saveModelAjax($this, $model, null);
        } else {
            if ($model->load($request->post())) {
                $model->save();
                return $this->redirect(['index']);
            } else {
                return $this->render('update', ['model' => $model]);
            }
        }
    }
}
