<?php

namespace backend\modules\app\controllers;

use Yii;
use backend\modules\app\models\AppLog;
use backend\modules\app\models\AppLogSearch;
use backend\controllers\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Html;
use yii\helpers\Json;
use common\components\AccessRule;
use common\models\User;
use yii\filters\AccessControl;
use common\components\FHtml;

/**
 * AppLogController implements the CRUD actions for AppLog model.
 */
class AppLogController extends AdminController
{
    protected $moduleName = 'AppLog';
    protected $moduleTitle = 'App Log';
    protected $moduleKey = 'app_log';
    protected $object_type = 'app_log';

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
                        'actions' => ['view', 'index', 'create'],
                        'allow' => true,
                        'roles' => [
                            User::ROLE_MODERATOR, User::ROLE_ADMIN, User::ROLE_USER
                        ],
                    ],
                    [
                        'actions' => ['update', 'delete'],
                        'allow' => true,
                        'roles' => [
                            User::ROLE_ADMIN
                        ],
                    ],
                ],
            ],
        ]);
    }

    /**
     * Lists all AppLog models.
     * @return mixed
     */
    public function actionIndex()
    {
        /* @var $searchModel AppLogSearch */
        $searchModel = AppLog::createNew();
        $dataProvider = $searchModel->search(array_merge(Yii::$app->request->queryParams, FHtml::getRequestPost()));

        //Save model if has Create new form in Index view
        FHtml::saveModel($this->object_type);

        if (Yii::$app->request->post('hasEditable')) {
            $Id = Yii::$app->request->post('editableKey');

            $model = AppLog::findOne($Id);

            $out = Json::encode(['output' => '', 'message' => '']);

            $post = [];
            $posted = current($_POST['AppLog']);
            $post['AppLog'] = $posted;

            if ($model->load($post)) {
                $model->save();
                $output = '';
                $out = Json::encode(['output' => $output, 'message' => '']);
            }
            echo $out;
            return;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single AppLog model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return parent::actionView($id);
    }

    /**
     * Creates a new AppLog model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        return parent::actionCreate();
    }

    /**
     * Updates an existing AppLog model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        return parent::actionUpdate($id);
    }

    /**
     * Delete an existing AppLog model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        return parent::actionDelete($id);
    }

    /**
     * Delete multiple existing AppLog model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionBulkDelete()
    {
        return parent::actionBulkDelete();
    }

    public function actionBulkAction($action = '', $field = '', $value = '')
    {
        return parent::actionBulkAction($action, $field, $value);
    }

    /**
     * Finds the AppLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return AppLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = AppLog::findOne($id);
        if (isset($model)) {
            return $model;
        } else {
            throw new NotFoundHttpException('Data does not exist');
        }
    }

    /**
     * Create the AppLog model.
     * @return AppLog the loaded model
     */
    protected function createModel($className = '', $id = '', $params = null)
    {
        $model = parent::createModel($className, $id, $params);
        return $model;
    }

    public function saveModelAjax($model, $params = null, $modelMeta = null)
    {
        return parent::saveModelAjax($model, $params, $modelMeta);
    }

    public function loadModel($model, $params = []) {
        return parent::loadModel($model, $params);
    }

    public function returnView($action, $id) {
        return parent::returnView($action, $id);
    }

    protected function getPjaxContainerId($object_type = '', $type = 'crud-datatable') {
        return parent::getPjaxContainerId($object_type, $type);
    }
}