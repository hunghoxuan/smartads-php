<?php

namespace backend\modules\system\controllers;

use backend\controllers\AdminController;
use backend\modules\system\models\ObjectSetting;
use kartik\widgets\FileInput;
use common\components\FHtml;
use Yii;
use backend\modules\system\models\ObjectSettingSearch;
//use yii\web\Controller;
use yii\base\Object;
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


/**
 * MetaSettingController implements the CRUD actions for MetaSetting model.
 */
class ObjectSettingController extends AdminController
{
    protected $moduleName = 'SETTING';

    private function buildButtonsCreate()
    {
        return Html::button('', ['id' => 'buttonSave', 'onClick' => '$("#saveType").val("save")', 'type' => "submit", 'style' => 'display:none']) .
        Html::button('Save', ['onClick' => '$("#saveType").val("save");$("#buttonSave").click()', 'class' => 'btn btn-primary pull-left', 'data-dismiss' => "modal"]) .
        Html::button('Save & More', ['onClick' => '$("#saveType").val("saveNew");$("#buttonSave").click()', 'class' => 'btn btn-success pull-left', 'data-dismiss' => "modal"]) .
        Html::button('Close', ['class' => 'btn btn-default', 'data-dismiss' => "modal"]);
    }

    private function buildButtonsEdit()
    {
        return Html::button('', ['id' => 'buttonSave', 'onClick' => '$("#saveType").val("save")', 'type' => "submit", 'style' => 'display:none']) .
        Html::button('Save', ['onClick' => '$("#saveType").val("save");$("#buttonSave").click()', 'class' => 'btn btn-primary pull-left', 'data-dismiss' => "modal"]) .
        Html::button('Close', ['class' => 'btn btn-default', 'data-dismiss' => "modal"]);
    }

    public function init()
    {
        parent::init();
        //$this->view->params['displayType'] = "modal-remote";
    }

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
                        'actions' => ['view', 'index', 'create', 'update', 'delete'],
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
     * Lists all MetaSetting models.
     * @return mixed
     */
    public function actionIndex($object_type = false, $meta_key = false)
    {

        $dataProvider = array();
        $searchModel = new ObjectSettingSearch();
        $params = Yii::$app->request->queryParams;

        if ($object_type) {
            $params['ObjectSettingSearch']['object_type'] = $object_type;
        }
        if ($meta_key) {
            $params['ObjectSettingSearch']['meta_key'] = $meta_key;
        }


        $model = new ObjectSetting();

        $model['object_type'] = $object_type;
        $model['meta_key'] = $meta_key;

        if ($model->load(Yii::$app->request->post())) {
            $model->is_active = 1;
            $model->save();
        }

        $dataProvider = $searchModel->search($params);

        // validate if there is a editable input saved via AJAX
        if (Yii::$app->request->post('hasEditable')) {
            // instantiate your book model for saving
            $Id = Yii::$app->request->post('editableKey');

            $model = ObjectSetting::findOne($Id);

            // store a default json response as desired by editable
            $out = Json::encode(['output' => '', 'message' => '']);

            // fetch the first entry in posted data (there should
            // only be one entry anyway in this array for an
            // editable submission)
            // - $posted is the posted data for Book without any indexes
            // - $post is the converted array for single model validation
            $post = [];
            $posted = current($_POST['ObjectSetting']);
            $post['ObjectSetting'] = $posted;

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
     * Displays a single MetaSetting model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Setting #" . $id,
                'content' => $this->renderPartial('_view_preview', [
                    'model' => $model, 'modelMeta' => $modelMeta,
                ]),
                'footer' => Html::a('update', ['update', 'id' => $id], ['class' => 'btn btn-primary pull-left', 'role' => 'modal-remote']) .
                    Html::button('Close', ['class' => 'btn btn-default', 'data-dismiss' => "modal"])
            ];
        } else {
            return $this->render('view', [
                'model' => $model, 'modelMeta' => $modelMeta,
            ]);
        }
    }

    /**
     * Creates a new MetaSetting model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($object_type = false, $meta_key = false)
    {
        $request = Yii::$app->request;
        $model = new ObjectSetting();

        $model['object_type'] = $object_type;
        $model['meta_key'] = $meta_key;

        $moduleName = $this->moduleName;

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Create new " . $object_type . " setting: " . $meta_key,
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'object_type' => $object_type,
                        'meta_key' => $meta_key
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];

            } else if ($model->load($request->post())) {
                $model->attributes = $_POST['ObjectSetting'];

                $types = array();

                if (isset($_POST['ObjectSetting']['Items'])) {
                    $types = $_POST['ObjectSetting']['Items'];
                }
                if (count($types) > 0) {
                    foreach ($types as $type) {
                        $metaSetting = new ObjectSetting();
                        $metaSetting->object_type = FHtml::getRequestParam('object_type', 'common');
                        $metaSetting->meta_key = $meta_key;
                        $metaSetting->key = $type['key'];
                        $metaSetting->value = $type['value'];
                        $metaSetting->icon = $type['icon'];
                        $metaSetting->is_active = isset($type['is_active']) ? $type['is_active'] : 1;
                        $metaSetting->sort_order = 1;
                        $metaSetting->application_id = FHtml::currentApplicationCode();
                        $metaSetting->save();
                    }

                }

                return [
                    'forceReload' => '#' . $this->getPjaxContainerId(),
                    'title' => "Create new " . $object_type . " Setting: " . $meta_key,
                    'content' => '<span class="text-success">Create Object Setting success</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];

            } else {
                return [
                    'title' => "Create new " . $object_type . " Setting: " . $meta_key,
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {


            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post())) {
                $model->attributes = $_POST['ObjectSetting'];

                if (isset($_POST['ObjectSetting'])) {
                    $object_type = $_POST['ObjectSetting']['object_type'];
                    $meta_key = $_POST['ObjectSetting']['meta_key'];
                }

                $metaSettings = array();

                if (isset($_POST['ObjectSetting']['Items'])) {
                    $metaSettings = $_POST['ObjectSetting']['Items'];
                }

                if (count($metaSettings) > 0) {
                    foreach ($metaSettings as $index => $item) {
                        $key = $item['key'];
                        $value = $item['value'];
                        $file = UploadedFile::getInstanceByName("ObjectSetting[Items][$index][icon]");
                        if (isset($file)) {
                            $time_string = time();
                            $icon = $time_string . rand(0, 1000) . 'icon.' . $file->extension;
                            $file->saveAs($this->uploadFolder . '/' . ICON_DIR . '/' . $icon);
                        } else {
                            $icon = null;
                        }
                        $is_active = isset($item['is_active']) ? $item['is_active'] : 'on';
                        if ($is_active == 'on') {
                            $is_active = 1;
                        } else $is_active = 0;
                        $color = isset($item['color']) ? $item['color'] : '';
                        $sort_order = isset($item['sort_order']) ? $item['sort_order'] : 1;

                        $metaSetting = new ObjectSetting();
                        $metaSetting->object_type = FHtml::getRequestParam('object_type', 'common');
                        $metaSetting->meta_key = $meta_key;
                        $metaSetting->key = $key;
                        $metaSetting->value = $value;
                        $metaSetting->icon = $icon;
                        $metaSetting->is_active = $is_active;
                        $metaSetting->sort_order = $sort_order;
                        $metaSetting->color = $color;
                        $metaSetting->application_id = FHtml::currentApplicationCode();
                        $metaSetting->save();
                    }
                }

                return $this->redirect(['index', 'object_type' => $object_type]);

            } else {
                return $this->render('create', [
                    'model' => $model,
                    'object_type' => $object_type,
                    'meta_key' => $meta_key
                ]);
            }
        }
    }

    /**
     * Updates an existing MetaSetting model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Update Setting #" . $id,
                    'content' => $this->renderPartial('update', [
                        'model' => $model, 'modelMeta' => null,
                    ]),
                    'footer' => $this->buildButtonsEdit(),
                ];
            } else if ($model->load($request->post())) {

                if ($model->save()) {
                    return ['forceClose' => true, 'forceReload' => true];
                } else {
                    return [
                        'title' => "Update Setting #" . $id,
                        'content' => $this->renderPartial('update', [
                            'model' => $model, 'modelMeta' => null,
                        ]),
                        'footer' => $this->buildButtonsEdit(),
                    ];
                }

            } else {
                return [
                    'title' => "Update Setting #" . $id,
                    'content' => $this->renderPartial('update', [
                        'model' => $model, 'modelMeta' => null,
                    ]),
                    'footer' => $this->buildButtonsEdit(),
                ];
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post())) {
                if ($model->save()) {
                    return $this->redirect(['index', 'object_type' => $model->object_type]);
                }
                return $this->redirect(['index', 'object_type' => $model->object_type]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing MetaSetting model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

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

    /**
     * Delete multiple existing MetaSetting model.
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
            if (isset($model)) {
                $model->delete();
            }
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

    /**
     * Finds the MetaSetting model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MetaSetting the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ObjectSetting::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findNextModel($id)
    {
        $model = ObjectSetting::find()->where(['>', 'id', $id])->one();

        if ($model !== null) {
            return $model;
        } else {
            return $this->findModel($id);
        }
    }

    protected function findPrevModel($id)
    {
        $model = ObjectSetting::find()->where(['<', 'id', $id])->orderBy('id desc')->one();
        if ($model !== null) {
            return $model;
        } else {
            return $this->findModel($id);
        }
    }
}
