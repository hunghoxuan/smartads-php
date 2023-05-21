<?php

/**



 * This is the customized model class for table "SmartscreenLayouts".
 */

namespace backend\modules\smartscreen\controllers;

use backend\modules\smartscreen\models\SmartscreenFrame;
use backend\modules\smartscreen\models\SmartscreenFrameAPI;
use backend\modules\smartscreen\models\SmartscreenLayoutsFrame;
use backend\modules\smartscreen\models\SmartscreenSchedules;
use Yii;
use backend\modules\smartscreen\models\SmartscreenLayouts;
use backend\modules\smartscreen\models\SmartscreenLayoutsSearch;
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
 * SmartscreenLayoutsController implements the CRUD actions for SmartscreenLayouts model.
 */
class SmartscreenLayoutsController extends AdminController
{
    protected $moduleName = 'SmartscreenLayouts';
    protected $moduleTitle = 'Smartscreen Layouts';
    protected $moduleKey = 'smartscreen_layouts';
    protected $object_type = 'smartscreen_layouts';

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
     * Lists all SmartscreenLayouts models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = SmartscreenLayoutsSearch::createNew();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //Save model if has Create new form in Index view
        FHtml::saveModel($this->object_type);

        if (Yii::$app->request->post('hasEditable')) {
            $Id = Yii::$app->request->post('editableKey');

            $model = SmartscreenLayouts::findOne($Id);

            $out = Json::encode(['output' => '', 'message' => '']);

            $post = [];
            $posted = current($_POST['SmartscreenLayouts']);
            $post['SmartscreenLayouts'] = $posted;

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
     * Displays a single SmartscreenLayouts model.
     * @param integer $id
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
     * Creates a new SmartscreenLayouts model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($type = false)
    {
        $request = Yii::$app->request;

        $model = $this->createModel($this->object_type);

        $model->is_active = 1;

        if ($request->isAjax) {
            return FHtml::saveModelAjax($this, $model, null);
        } else {
            if ($model->load($request->post())) {
                $model->id = null;

                $frames = $model->list_frame;


                if ($model->save()) {
                    $id = $model->id;

                    if (!empty($frames)) {
                        self::excuteFrame($model->id, $frames);
                    }

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
     * Updates an existing SmartscreenLayouts model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;

        $model = $this->findModel($id);

        $full_frame = $model->frameQuery;
        $frames_params = array();
        foreach ($full_frame as $frame) {

            $frames_params[] = array(
                'frame' => $frame->id,
                'marginLeft' => $frame->marginLeft,
                'marginTop' => $frame->marginTop,
                'percentWidth' => $frame->percentWidth,
                'percentHeight' => $frame->percentHeight,
                'backgroundColor' => $frame->backgroundColor,
            );
        }

        $model->list_frame = $frames_params;

        if ($request->isAjax) {
            return FHtml::saveModelAjax($this, $model, null);
        } else {
            if ($model->load($request->post())) {

                $frames = $model->list_frame;

                if ($model->save()) {

                    if (!empty($frames)) {
                        self::excuteFrame($model->id, $frames);
                    }

                    if ($this->saveType() == 'clone') {
                        return $this->redirect(['create', 'id' => $model->id]);
                    } else if ($this->saveType() == 'add') {
                        return $this->redirect(['create']);
                    } else if ($this->saveType() == 'save') {
                        return $this->redirect(['update', 'id' => $model->id]);
                    }
                    return $this->redirect(['index']);
                } else {
                    print_r($model->errors);
                    die;
                }

                return $this->render('update', ['model' => $model, 'full_frame' => $full_frame]);
            } else {
                return $this->render('update', ['model' => $model, 'full_frame' => $full_frame]);
            }
        }
    }

    public function saveLayoutsFrame($id, $frames)
    {
        $my_array = [];

        SmartscreenLayoutsFrame::deleteAll("layout_id = $id");

        foreach ($frames as $key => $value) {
            if ((int)$value['frame']) {
                $frame_id = $value['frame'];
            } else {
                $frame = SmartscreenFrame::find()
                    ->select(['id'])
                    ->where(['name' => $value['frame']])
                    ->one();

                $frame_id = isset($frame) ? $frame->id : null;
            }

            $my_array[] = array(
                $id, $frame_id, $key
            );
        }

        \Yii::$app->db->createCommand()->batchInsert('smartscreen_layouts_frame', ['layout_id', 'frame_id', 'sort_order'], $my_array)->execute();
    }

    /**
     * Delete an existing SmartscreenLayouts model.
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
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#' . $this->getPjaxContainerId()];
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing SmartscreenLayouts model.
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
     * Finds the SmartscreenLayouts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SmartscreenLayouts the loaded model
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

    public function actionGetFrame()
    {
        $frame_id = $_REQUEST['frame_id'];
        $classSelect = $_REQUEST['classSelect'];
        $this->layout = false;

        if ($frame_id) {
            $frame = SmartscreenFrameAPI::find()
                ->where(['id' => $frame_id])
                ->one();

            if ($frame) {
                if (!empty($frame->backgroundColor)) {
                    $backgroundColor = $frame->backgroundColor;
                } else {
                    $backgroundColor = self::random_color();
                }

                //                json
                $backgroundColor = '#ffffff';
                $frame_params = array($frame->percentWidth, $frame->percentHeight, $frame->marginTop, $frame->marginLeft);
                echo json_encode($frame_params);

                return $this->render('get-frame', [
                    'frame' => $frame,
                    'backgroundColor' => $backgroundColor,
                    'classSelect' => $classSelect,
                ]);
            }
        }
    }

    public static function random_color_part()
    {
        return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
    }

    public static function random_color()
    {
        return '#' . self::random_color_part() . self::random_color_part() . self::random_color_part();
    }

    public function excuteFrame($model_id, $frames)
    {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            foreach ($frames as $item) {
                $frame = SmartscreenFrame::find()
                    ->select(['id', 'percentWidth', 'percentHeight', 'marginTop', 'marginLeft'])
                    ->where(['id' => $item['frame']])
                    ->one();

                if ($frame) {
                    if (($frame->percentWidth != $item['percentWidth']) || ($frame->percentHeight != $item['percentHeight']) || ($frame->marginTop != $item['marginTop']) || ($frame->marginLeft != $item['marginLeft'])) {
                        $frame->percentWidth = $item['percentWidth'];
                        $frame->percentHeight = $item['percentHeight'];
                        $frame->marginTop = $item['marginTop'];
                        $frame->marginLeft = $item['marginLeft'];
                        $frame->save();
                    }
                } else {
                    $frame = new SmartscreenFrame();
                    $frame->name = $item['frame'] . '_' . $model_id;
                    $frame->percentWidth = $item['percentWidth'];
                    $frame->percentHeight = $item['percentHeight'];
                    $frame->marginTop = $item['marginTop'];
                    $frame->marginLeft = $item['marginLeft'];
                    $frame->created_date = time();
                    $frame->save();
                }
            }

            self::saveLayoutsFrame($model_id, $frames);

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public
    function actionGetContent()
    {

        $this->layout = false;
        $layout_id = $_REQUEST['layout_id'];
        $selectId = $_REQUEST['selectId'];
        $scheduleId = isset($_REQUEST['scheduleId']) ? $_REQUEST['scheduleId'] : '';

        if ($layout_id) {

            $layout = SmartscreenLayouts::findOne($layout_id);
            $content_json = '';

            if (!empty($scheduleId)) {
                $content_json = SmartscreenSchedules::find()
                    ->select(['content_id'])
                    ->where(['id' => $scheduleId])
                    ->one();
            }

            if ($layout) {
                return $this->render('get-content', ['layout' => $layout, 'selectId' => $selectId, 'content_json' => $content_json]);
            }

            //                $orderBy = new \yii\db\Expression('FIELD (id, ' . implode(',', array_values($layout->frame)) . ')');
        }

        return false;
    }
}
