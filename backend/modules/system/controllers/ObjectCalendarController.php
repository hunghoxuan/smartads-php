<?php

namespace backend\modules\system\controllers;

use Yii;
use backend\modules\system\models\ObjectCalendar;
use backend\modules\system\models\ObjectCalendarSearch;
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
 * ObjectCalendarController implements the CRUD actions for ObjectCalendar model.
 */
class ObjectCalendarController extends AdminController
{
    protected $moduleName = 'ObjectCalendar';
    protected $moduleTitle = 'Object Calendar';
    protected $moduleKey = 'object_calendar';
    protected $object_type = 'object_calendar';

    const BASE_URL  = 'system/object-calendar/';
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
                            User::ROLE_MODERATOR, User::ROLE_ADMIN
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
     * Lists all ObjectCalendar models.
     * @return mixed
     */
    public function actionIndex()
    {
        /* @var $searchModel ObjectCalendarSearch */
        $searchModel = ObjectCalendarSearch::createNew();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //Save model if has Create new form in Index view
        FHtml::saveModel($this->object_type);

        if (Yii::$app->request->post('hasEditable')) {
            $Id = Yii::$app->request->post('editableKey');

            $model = ObjectCalendar::findOne($Id);

            $out = Json::encode(['output' => '', 'message' => '']);

            $post = [];
            $posted = current($_POST['ObjectCalendar']);
            $post['ObjectCalendar'] = $posted;

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
     * Displays a single ObjectCalendar model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;

        $model = $this->findModel($id);

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => FHtml::t($this->moduleName) . " #" . $id,
                'content' => $this->renderPartial('view', [
                    'model' => $model
                ]),
                'footer' => Html::a(FHtml::t('Update'), ['update', 'id' => $id], ['class' => 'btn btn-primary pull-left', 'role' => $this->view->params['displayType']]) .
                    Html::button(FHtml::t('Close'), ['class' => 'btn btn-default', 'data-dismiss' => "modal"])
            ];
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Creates a new ObjectCalendar model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;

        $model = $this->createModel($this->object_type);

        if ($request->isAjax) {
            return FHtml::saveModelAjax($this, $model, null);
        } else {
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
                } else {
                    FHtml::addError($model->getErrors());
                }
                return $this->render('create', ['model' => $model]);
            } else {
                return $this->render('create', ['model' => $model]);
            }
        }
    }

    /**
     * Updates an existing ObjectCalendar model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;

        $model = $this->findModel($id);

        if ($request->isAjax) {
            return $this->renderPartial('update', ['model' => $model]);
            return FHtml::saveModelAjax($this, $model, null);
        } else {
            if ($model->load($request->post())) {
                if ($model->save()) {
                    if ($this->saveType() == 'clone') {
                        return $this->redirect(['create', 'id' => $model->id]);
                    } else if ($this->saveType() == 'add') {
                        return $this->redirect(['create']);
                    } else if ($this->saveType() == 'save') {
                        return $this->redirect(['update', 'id' => $model->id]);
                    }
                    return $this->redirect(['index']);
                } else {
                    FHtml::addError($model->getErrors());
                }

                return $this->render('update', ['model' => $model]);
            } else {
                return $this->render('update', ['model' => $model]);
            }
        }
    }

    /**
     * Delete an existing ObjectCalendar model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;

        $this->findModel($id)->delete();

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#' . $this->getPjaxContainerId()];
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing ObjectCalendar model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionBulkDelete()
    {
        $request = Yii::$app->request;

        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
        foreach ($pks as $pk) {
            /* @var $model ObjectCalendar */
            $model = FHtml::findOne($this->object_type, $pk);
            if (isset($model)) {
                $model->delete();
            }
        }

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#' . $this->getPjaxContainerId()];
        } else {
            return $this->redirect(['index']);
        }
    }

    public function actionBulkAction($action = '', $field = '', $value = '')
    {
        $request = Yii::$app->request;

        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
        foreach ($pks as $pk) {
            /* @var $model ObjectCalendar */
            $model = FHtml::findOne($this->object_type, $pk);
            if (isset($model)) {
                if ($action == 'change') {
                    FHtml::setFieldValue($model, $field, $value);
                    $model->save();
                }
            }
        }

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#' . $this->getPjaxContainerId()];
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the ObjectCalendar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ObjectCalendar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = parent::findModel($id);
        return $model;
    }

    /**
     * Create the ObjectCalendar model.
     * @return ObjectCalendar the loaded model
     */
    protected function createModel($className = '', $id = '', $params = null)
    {
        $model = parent::createModel($className, $id, $params);
        return $model;
    }
}