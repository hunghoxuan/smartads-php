<?php

/**
 *
 ***
 * This is the customized model class for table "ToolsImport".
 */

namespace backend\controllers;

use backend\modules\system\System;
use Yii;
use backend\models\ToolsImport;
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
 * ToolsImportController implements the CRUD actions for ToolsImport model.
 */
class ToolsImportController extends AdminController
{
    protected $moduleName = 'ToolsImport';
    protected $moduleTitle = 'Tools Import';
    protected $moduleKey = 'tools_import';
    protected $object_type = 'tools_import';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return FHtml::getControllerBehaviours([
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'update' => ['get', 'post'],
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
                        'roles' => [],
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
     * Lists all ToolsImport models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ToolsImport();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //Save model if has Create new form in Index view
        FHtml::saveModel($this->object_type);

        if (Yii::$app->request->post('hasEditable')) {
            $Id = Yii::$app->request->post('editableKey');

            $model = ToolsImport::findOne($Id);

            $out = Json::encode(['output' => '', 'message' => '']);

            $post = [];
            $posted = current($_POST['ToolsImport']);
            $post['ToolsImport'] = $posted;

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
     * Displays a single ToolsImport model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;

        $model = $this->findModel($id);
        $type = FHtml::getFieldValue($model, 'type');

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
     * Creates a new ToolsImport model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($type = false)
    {
        $request = Yii::$app->request;

        $model = $this->createModel($this->object_type);

        if ($request->isAjax) {
            return FHtml::saveModelAjax($this, $model, null);
        } else {
            if (!empty($_POST) && $model->load($request->post())) {
                $model->id = null;

                if ($model->save()) {

                    $id = $model->id;

                    System::import($model);

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

    /**
     * Updates an existing ToolsImport model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;

        $model = $this->findModel($id);

        $result = [];
        if ($request->isAjax) {
            return FHtml::saveModelAjax($this, $model, null);
        } else {
            if (!empty($_POST) && $model->load($request->post())) {
                if ($model->save()) {
                    if ($this->saveType() == 'clone') {
                        return $this->redirect(['create', 'id' => $model->id]);
                    } else if ($this->saveType() == 'add') {
                        return $this->redirect(['create']);
                    } else if ($this->saveType() == 'save') {
                        $result = System::import($model);
                        return $this->render('update', ['model' => $model, 'result' => $result]);
                    }
                    return $this->redirect(['index']);
                }

                return $this->render('update', ['model' => $model, 'result' => $result]);
            } else {
                //var_dump($model->getErrors());
                return $this->render('update', ['model' => $model, 'result' => []]);
            }
        }
    }

    //    /**
    //     * Delete an existing ToolsImport model.
    //     * For ajax request will return json object
    //     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
    //     * @param string $id
    //     * @return mixed
    //     */
    //    public function actionDelete($id)
    //    {
    //        $request = Yii::$app->request;
    //
    //        $this->findModel($id)->delete();
    //
    //        if($request->isAjax){
    //            Yii::$app->response->format = Response::FORMAT_JSON;
    //            return ['forceClose'=>true,'forceReload'=>'#crud-datatableToolsImport-pjax'];
    //        }else{
    //            return $this->redirect(['index']);
    //        }
    //    }

    //     /**
    //     * Delete multiple existing ToolsImport model.
    //     * For ajax request will return json object
    //     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
    //     * @param string $id
    //     * @return mixed
    //     */
    //    public function actionBulkDelete()
    //    {
    //        $request = Yii::$app->request;
    //
    //        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
    //        foreach ($pks as $pk) {
    //            $model = FHtml::findOne($this->object_type, $pk);
    //            if (isset($model)) {
    //                $model->delete();
    //            }
    //        }
    //
    //        if($request->isAjax){
    //            Yii::$app->response->format = Response::FORMAT_JSON;
    //            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
    //        }else{
    //            return $this->redirect(['index']);
    //        }
    //    }

    //    public function actionBulkAction($action = '', $field = '', $value = '')
    //    {
    //        $request = Yii::$app->request;
    //
    //        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
    //        foreach ($pks as $pk) {
    //            $model = FHtml::findOne($this->object_type, $pk);
    //            if (isset($model)) {
    //                if ($action == 'change') {
    //                    FHtml::setFieldValue($model, $field, $value);
    //                    $model->save();
    //                }
    //            }
    //        }
    //
    //        if($request->isAjax){
    //            Yii::$app->response->format = Response::FORMAT_JSON;
    //            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
    //        } else {
    //            return $this->redirect(['index']);
    //        }
    //    }

    /**
     * Finds the ToolsImport model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ToolsImport the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = parent::findModel($id);
        return $model;
    }

    protected function createModel($className = '', $id = '', $params = null)
    {
        $model = parent::createModel($className, $id, $params);
        return $model;
    }
}
