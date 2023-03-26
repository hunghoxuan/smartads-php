<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "ToolsCopy".
*/
namespace backend\modules\system\controllers;

use Yii;
use backend\modules\tools\models\ToolsCopy;
use backend\modules\tools\models\ToolsCopySearch;
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
use common\components\FFile;

/**
 * ToolsCopyController implements the CRUD actions for ToolsCopy model.
 */
class ToolsCopyController extends AdminController
{
    protected $moduleName = 'ToolsCopy';
    protected $moduleTitle = 'Tools Copy';
    protected $moduleKey = 'tools_copy';
    protected $object_type = 'tools_copy';

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
                            User::ROLE_USER, User::ROLE_MODERATOR, User::ROLE_ADMIN
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

    /**
     * Lists all ToolsCopy models.
     * @return mixed
     */
    public function actionIndex()
    {    
              $searchModel = ToolsCopySearch::createNew();
       $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

       //Save model if has Create new form in Index view
       FHtml::saveModel($this->object_type);

       if (Yii::$app->request->post('hasEditable')) {
           $Id = Yii::$app->request->post('editableKey');

           $model = ToolsCopy::findOne($Id);

           $out = Json::encode(['output' => '', 'message' => '']);

           $post = [];
           $posted = current($_POST['ToolsCopy']);
           $post['ToolsCopy'] = $posted;

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
     * Displays a single ToolsCopy model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;

        $model = $this->findModel($id);
        $type = FHtml::getFieldValue($model, 'type');

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> FHtml::t($this->moduleName)." #".$id,
                    'content'=>$this->renderPartial('view', [
                        'model' => $model
                    ]),
                    'footer'=>Html::a(FHtml::t('Update'),['update','id'=>$id],['class'=>'btn btn-primary pull-left','role'=>$this->view->params['displayType']]).
                              Html::button(FHtml::t('Close'),['class'=>'btn btn-default','data-dismiss'=>"modal"])
                ];
        }else{
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Creates a new ToolsCopy model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($type = false)
    {
        set_time_limit(0);
        $request = Yii::$app->request;
        $model = $this->createModel($this->object_type);
        if($request->isAjax){
            return FHtml::saveModelAjax($this, $model, null);
        }else{
            if ($model->load($request->post())) {
                $model->id    = null;
                $name_folders = $model->name;
                $folders      = $model->folders;
                $files        = $model->files;
                $id           = 0;

                foreach ($name_folders as $key => $name) {
                    $model1 = $this->createModel($this->object_type);
                    if (strpos($name, FFile::getPathRoot()) === false) {
                        $name = FFile::getPathRoot() . $name;
                        FFile::createDir($name);
                    }
                    $model1->name        = $name;
                    $model1->folders     = $model->folders;
                    $model1->files       = $model->files;
                    $model1->description = $model->description;
                    $model1->checkFormResubmission(false);
                    if ($model1->save()) {
                        if (!empty($folders)) {
                            foreach ($folders as $key => $folder) {
                                FHtml::copy(FFile::getFullPath() . $folder, $name ."\\". $folder);
                            }
                        }

                        if (!empty($files)) {
                            foreach ($files as $key => $file) {
                                FHtml::copy(FFile::getFullPath() . $file, $name ."\\". $file);
                            }
                        }
                    }
                    $id = $model1->id;
                }

                if ($this->saveType() == 'clone') {
                    return $this->redirect(['create', 'id' => $id]);
                } else if ($this->saveType() == 'add') {
                    return $this->redirect(['create']);
                } else if ($this->saveType() == 'save') {
                    return $this->redirect(['update', 'id' => $id]);
                }
                return $this->render('create', ['model' => $model]);
            } else {
                return $this->render('create', ['model' => $model]);
            }
        }
    }

    /**
     * Updates an existing ToolsCopy model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
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
     * Delete an existing ToolsCopy model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;

        $this->findModel($id)->delete();

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#' . $this->getPjaxContainerId()];
        }else{
            return $this->redirect(['index']);
        }
    }

     /**
     * Delete multiple existing ToolsCopy model.
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
            $model = FHtml::findOne($this->object_type, $pk);
            if (isset($model)) {
                $model->delete();
            }
        }

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#' . $this->getPjaxContainerId()];
        }else{
            return $this->redirect(['index']);
        }
    }

    public function actionBulkAction($action = '', $field = '', $value = '')
    {
        $request = Yii::$app->request;

        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
        foreach ($pks as $pk) {
            $model = FHtml::findOne($this->object_type, $pk);
            if (isset($model)) {
                if ($action == 'change') {
                    FHtml::setFieldValue($model, $field, $value);
                    $model->save();
                }
            }
        }

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#' . $this->getPjaxContainerId()];
        } else {
            return $this->redirect(['index']);
        }
    }



    /**
     * Finds the ToolsCopy model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ToolsCopy the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = parent::findModel($id);
        return $model;
    }

    protected function createModel($className = '', $id = '', $params = null) {
        $model = parent::createModel($className, $id, $params);
        return $model;
    }
}