<?php

namespace backend\modules\system\controllers;

use Yii;
use backend\modules\system\models\ObjectRequest;
use backend\modules\system\models\ObjectRequestSearch;
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
 * ObjectRequestController implements the CRUD actions for ObjectRequest model.
 */
class ObjectRequestController extends AdminController
{
    protected $moduleName = 'ObjectRequest';
    protected $moduleTitle = 'Object Request';
    protected $moduleKey = 'object_request';
    protected $object_type = 'object_request';

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
     * Lists all ObjectRequest models.
     * @return mixed
     */
    public function actionIndex()
    {
        /* @var $searchModel ObjectRequestSearch */
        $searchModel = ObjectRequest::createNew();
        $dataProvider = $searchModel->search(array_merge(Yii::$app->request->queryParams, FHtml::getRequestPost()));

        //Save model if has Create new form in Index view
        FHtml::saveModel($this->object_type);

        if (Yii::$app->request->post('hasEditable')) {
            $Id = Yii::$app->request->post('editableKey');

            $model = ObjectRequest::findOne($Id);

            $out = Json::encode(['output' => '', 'message' => '']);

            $post = [];
            $posted = current($_POST['ObjectRequest']);
            $post['ObjectRequest'] = $posted;

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
     * Displays a single ObjectRequest model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return parent::actionView($id);
    }

    /**
     * Creates a new ObjectRequest model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        return parent::actionCreate();
    }

    /**
     * Updates an existing ObjectRequest model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        return parent::actionUpdate($id);
    }

    /**
     * Delete an existing ObjectRequest model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        return parent::actionDelete($id);
    }

    /**
     * Delete multiple existing ObjectRequest model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
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
     * Finds the ObjectRequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ObjectRequest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = ObjectRequest::findOne($id);
        if (isset($model)) {
            return $model;
        } else {
            throw new NotFoundHttpException('Data does not exist');
        }
    }

    /**
     * Create the ObjectRequest model.
     * @return ObjectRequest the loaded model
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