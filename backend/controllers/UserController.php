<?php
/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "User".
 */
namespace backend\controllers;

use backend\models\User;
use backend\models\UserSearch;
use common\components\AccessRule;
use common\components\FHtml;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\Response;

//use yii\web\Controller;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends AdminController
{
    protected $moduleName = 'User';
    protected $moduleTitle = 'User';
    protected $moduleKey = 'user';
    protected $object_type = 'user';

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
                            FHtml::ROLE_MODERATOR,
                            FHtml::ROLE_ADMIN
                        ],
                    ],
                    [
                        'actions' => ['create', 'delete'],
                        'allow' => true,
                        'roles' => [
                            FHtml::ROLE_MODERATOR,
                            FHtml::ROLE_ADMIN
                        ],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => [
                            FHtml::ROLE_MODERATOR,
                            FHtml::ROLE_ADMIN,
                            FHtml::ROLE_USER,

                        ],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => [
                            User::ROLE_ADMIN,

                        ],
                    ],
                    [
                        'actions' => ['profile'],
                        'allow' => true,
                        'roles' => [
                            User::ROLE_ADMIN,FHtml::ROLE_ADMIN,
                            FHtml::ROLE_USER,

                        ],
                    ],
                ],
            ],
        ]);
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //Save model if has Create new form in Index view
        FHtml::saveModel($this->object_type);

        if (Yii::$app->request->post('hasEditable')) {
            $Id = Yii::$app->request->post('editableKey');

            $model = User::findOne($Id);

            $out = Json::encode(['output' => '', 'message' => '']);

            $post = [];
            $posted = current($_POST['User']);
            $post['User'] = $posted;

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
     * Displays a single User model.
     * @param integer $id
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
                'title' => FHtml::t($this->moduleName) . " #" . $id,
                'content' => $this->renderPartial('view', [
                    'model' => $model
                ]),
                'footer' => Html::a(FHtml::t('Update'), ['update', 'id' => $id], ['class' => 'btn btn-primary pull-left', 'role' => $this->view->params['displayType']]) .
                    Html::button(FHtml::t('Close'), ['class' => 'btn btn-default', 'data-dismiss' => "modal"])
            ];
        }else{
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = parent::findModel($id);
        $model->groups_array = $model->getGroupsArray();
        $model->rights_array = $model->getRightsArray();
        return $model;
    }

    /**
     * Creates a new User model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($type = false)
    {
        $request = Yii::$app->request;
        $returnParams = FHtml::RequestParams(['id']);
        if (!empty($type))
            $returnParams = ['type' => $type];

        $model = $this->createModel($this->object_type, '', ['type' => $type]);

        if($request->isAjax){
            return FHtml::saveModelAjax($this, $model, null);
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post())) {
                $model->id = null;

                if ($model->save()) {
                    $id = $model->id;

                    if ($this->saveType() == 'clone')
                    {
                        return $this->redirect(['create', 'id' => $id]);
                    } else if ($this->saveType() == 'add')
                    {
                        return $this->redirect(['create']);
                    } else if ($this->saveType() == 'save')
                    {
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

    protected function createModel($className = '', $id = '', $params = null)
    {
        $model = parent::createModel($className, $id, $params);
        return $model;
    }

    public function actionProfile() {
        $id = FHtml::currentUserId();
        return self::actionUpdate($id);
    }

    /**
     * Updates an existing User model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $type = FHtml::getRequestParam('type');

        if ($id != FHtml::currentUserId() && FHtml::isRoleUser() && !FHtml::isRoleAdmin()) {
            return $this->redirect(['index']);
        }

        $model = $this->findModel($id);

        if ($request->isAjax) {
            return FHtml::saveModelAjax($this, $model, null);
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post())) {
                if ($model->save()) {
                    if ($this->saveType() == 'clone')
                    {
                        return $this->redirect(['create', 'id' => $model->id]);
                    }  else if ($this->saveType() == 'add')
                    {
                        return $this->redirect(['create']);
                    } else if ($this->saveType() == 'save')
                    {
                        return $this->redirect(['update', 'id' => $model->id]);
                    }
                    return $this->redirect(['index']);
                } else {
                    FHtml::addError($model->errors);
                }

                return $this->render('update', ['model' => $model]);
            } else {
                return $this->render('update', ['model' => $model]);
            }
        }
    }

    /**
     * Delete an existing User model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $returnParams = FHtml::RequestParams(['id']);

        $this->findModel($id)->delete();

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=> '#' . $this->getPjaxContainerId()];
        }else{
            return $this->redirect(ArrayHelper::merge(['index'], $returnParams ));
        }
    }

    /**
     * Delete multiple existing User model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkDelete()
    {
        $request = Yii::$app->request;
        $returnParams = FHtml::RequestParams(['id']);

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
            return $this->redirect(ArrayHelper::merge(['index'], $returnParams ));
        }
    }

    public function actionBulkAction($action = '', $field = '', $value = '')
    {
        $request = Yii::$app->request;
        $returnParams = FHtml::RequestParams(['id']);

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
            return $this->redirect(ArrayHelper::merge(['index'], $returnParams ));
        }
    }
}
