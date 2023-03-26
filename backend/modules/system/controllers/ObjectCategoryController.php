<?php

namespace backend\modules\system\controllers;

use backend\controllers\AdminController;
use Yii;
use backend\models\ObjectCategory;
use backend\models\ObjectCategorySearch;
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

/**
 * ObjectCategoryController implements the CRUD actions for ObjectCategory model.
 */
class ObjectCategoryController extends AdminController
{
    protected $moduleName = 'ObjectCategory';
    protected $moduleTitle = 'Object Category';
    protected $moduleKey = 'object-category';

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
                        'actions' => ['view', 'index', 'create', 'update'],
                        'allow' => true,
                        'roles' => [
                            User::ROLE_USER,
                            User::ROLE_MODERATOR,
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
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => [
                            User::ROLE_ADMIN
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all ObjectCategory models.
     * @return mixed
     */
    public function actionIndex($object_type = false)
    {    
        $searchModel = new ObjectCategorySearch();

        $params = Yii::$app->request->queryParams;
        if ($object_type) {
            $params['ObjectCategorySearch']['object_type'] = $object_type;
        }
        $dataProvider = $searchModel->search($params);


           // validate if there is a editable input saved via AJAX
           if (Yii::$app->request->post('hasEditable')) {
               // instantiate your book model for saving
               $Id = Yii::$app->request->post('editableKey');

               $model = ObjectCategory::findOne($Id);

               // store a default json response as desired by editable
               $out = Json::encode(['output' => '', 'message' => '']);

               // fetch the first entry in posted data (there should
               // only be one entry anyway in this array for an
               // editable submission)
               // - $posted is the posted data for Book without any indexes
               // - $post is the converted array for single model validation
               $post = [];
               $posted = current($_POST['ObjectCategory']);
               $post['ObjectCategory'] = $posted;

               // load model like any single model validation
               if ($model->load($post)) {
                   // can save model or do something before saving model
                   $model->save();

                   // custom output to return to be displayed as the editable grid cell
                   // data. Normally this is empty - whereby whatever value is edited by
                   // in the input by user is updated automatically.
                   $output = '';
                   // similarly you can check if the name attribute was posted as well
                   // if (isset($posted['name'])) {
                   //   $output =  ''; // process as you need
                   // }
                   $out = Json::encode(['output' => $output, 'message' => '']);
               }
               // return ajax json encoded response and exit
               echo $out;
               return;
           }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single ObjectCategory model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        $model = self::findModel($id);
        $modelMeta = null;

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> FHtml::t($this->moduleName)." #".$id,
                    'content'=>$this->renderPartial('_view_preview', [
                        'model' => $model, 'modelMeta' => $modelMeta,
                    ]),
                    'footer'=>Html::a(FHtml::t('Update'),['update','id'=>$id],['class'=>'btn btn-primary pull-left','role'=>$this->view->params['displayType']]).
                              Html::button(FHtml::t('Close'),['class'=>'btn btn-default','data-dismiss'=>"modal"])
                ];
        }else{
            return $this->render('view', [
                'model' => $model, 'modelMeta' => $modelMeta,
            ]);
        }
    }

    /**
     * Creates a new ObjectCategory model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($object_type = false)
    {
        $request = Yii::$app->request;
        $model = new ObjectCategory();

        $model->object_type = $object_type ? $object_type : FHtml::getRequestParam('object_type');

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> FHtml::t($this->moduleName),
                    'content'=>$this->renderPartial('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button(FHtml::t('Close'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button(FHtml::t('Create'),['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                'forceReload'=>'#' . $this->getPjaxContainerId(),
                'title'=> FHtml::t($this->moduleName),
                'content'=>'<span class="text-success">Create ObjectCategory success</span>',
                'footer'=> Html::button(FHtml::t('Close'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                Html::a(FHtml::t('Create more'),['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])

                ];
            }else{
            return [
            'title'=> FHtml::t($this->moduleName),
            'content'=>$this->renderAjax('create', [
            'model' => $model,
            ]),
            'footer'=> Html::button(FHtml::t('Close'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
            Html::button(FHtml::t('Create'),['class'=>'btn btn-primary','type'=>"submit"])

            ];
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post())) {

                if ($model->save()) {
                    return $this->redirect(['index', 'object_type' => $model->object_type]);
                }
                return $this->redirect(['index', 'object_type' => $model->object_type]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
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
        $modelMeta = null;

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> FHtml::t($this->moduleName)." #".$id,
                    'content'=>$this->renderPartial('update', [
                        'model' => $model, 'modelMeta' => $modelMeta,
                    ]),
                    'footer'=> Html::button(FHtml::t('Close'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button(FHtml::t('Save'),['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                    return [
                        'forceReload'=>'#' . $this->getPjaxContainerId(),
                        'title'=> FHtml::t($this->moduleName)." #".$id,
                        'content'=>$this->renderAjax('view', [
                            'model' => $model,
                        ]),
                        'footer'=> Html::button(FHtml::t('Close'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                    Html::a(FHtml::t('update'),['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                    ];
            }else{
                    return [
                        'title'=> FHtml::t($this->moduleName)." #".$id,
                        'content'=>$this->renderAjax('update', [
                            'model' => $model,
                        ]),
                        'footer'=> Html::button(FHtml::t('Close'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                    Html::button(FHtml::t('Save'),['class'=>'btn btn-primary','type'=>"submit"])
                    ];
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post())) {

               if ($model->save()) {

                   return $this->redirect(['index', 'object_type' => $model->object_type]);
                }
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing ObjectCategory model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#' . $this->getPjaxContainerId()];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }

     /**
     * Delete multiple existing ObjectCategory model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkDelete()
    {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
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

    public function actionBulkAction($action = '', $field = '', $value = '')
    {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            if (isset($model)) {
                if ($action == 'change') {
                    $model[$field] = $value;
                    $model->save();
                }
            }
        }

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#' . $this->getPjaxContainerId()];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }


    /**
     * Finds the ObjectCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ObjectCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ObjectCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
