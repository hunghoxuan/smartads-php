<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "SettingsText".
*/
namespace backend\controllers;

use Yii;
use backend\models\SettingsText;
use backend\models\SettingsTextSearch;
//use yii\web\Controller;
use backend\controllers\AdminController;
use yii\helpers\StringHelper;
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
                            User::ROLE_ADMIN
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
        $currentLang = FHtml::currentLang();

        if (empty($currentLang))
            $this->goHome();

        $lang = FHtml::getRequestParam('key', $currentLang);
        $application_id = FHtml::currentApplicationId();
        $lang_file = FHtml::getApplicationTranslationsFile($application_id, $lang);
        $canEdit = true;
        if (!is_file($lang_file) || !is_writable($lang_file)) {
            FHtml::addError(FHtml::t('common', "Permission denied. Please set permission for this file") . ': ' . $lang_file);
            $canEdit = false;
        }


        $model = new SettingsText();

        if($request->isAjax) {
            if (!empty($_POST))
                return FHtml::saveModelAjax($this, $model, null);
        } else {
            if ($model->load($request->post())) {
                $model->save();
            }
        }

        $searchModel = new SettingsTextSearch();

        $params = Yii::$app->request->queryParams;

        $dataProvider = $searchModel->search($params);
        return $this->render('index', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel, 'canEdit' => $canEdit]);

    }

    /**
     * Updates an existing ObjectCategory model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;

        $model = $this->findModel($id);

        if($request->isAjax){
            return FHtml::saveModelAjax($this, $model, null);
        } else {
            if ($model->load($request->post())) {

                if ($model->save()) {

                    if ($this->saveType() == 'clone') {
                        return $this->redirect(['create', 'id' => $model->id]);
                    }  else if ($this->saveType() == 'add') {
                        return $this->redirect(['create']);
                    } else if ($this->saveType() == 'save') {
                        return $this->redirect(['update', 'id' => $model->id]);
                    }

                    return $this->redirect(['index']);
                }
                return $this->render('update', ['model' => $model]);
            } else {
                return $this->render('update', ['model' => $model]);
            }
        }
    }

    /**
     * Creates a new CmsBlogs model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($type = false)
    {
        $request = Yii::$app->request;

        $model = $this->createModel($this->object_type);

        if($request->isAjax){
            return FHtml::saveModelAjax($this, $model, null);
        }else{
            if ($model->load($request->post())) {
                $model->id = null;

                if ($model->save()) {
                    $id = $model->id;

                    if ($this->saveType() == 'clone') {
                        return $this->redirect(['create', 'id' => $id]);
                    } else if ($this->saveType() == 'add') {
                        return $this->redirect(['create']);
                    } else if ($this->saveType() == 'save') {
                        return $this->redirect(['update', 'id' => $id]);
                    }
                    return $this->redirect(['index']);
                }
                return $this->render('create', ['model' => $model]);
            } else {
                return $this->render('create', ['model' => $model]);
            }
        }
    }

    protected function findModel($id)
    {
        $model = SettingsText::findOne($id);
        if (isset($model)) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionBulkDelete()
    {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys\
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#' . $this->getPjaxContainerId()];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }

}
