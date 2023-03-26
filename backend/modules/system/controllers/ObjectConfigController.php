<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "ObjectActions".
*/
namespace backend\modules\system\controllers;

use Yii;
use backend\models\ObjectActions;
use backend\models\ObjectActionsSearch;
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
 * ObjectActionsController implements the CRUD actions for ObjectActions model.
 */
class ObjectConfigController extends AdminController
{
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
                ],
            ],
        ]);
    }

    /**
     * Lists all ObjectActions models.
     * @return mixed
     */
    public function actionIndex()
    {    

    }


    /**
     * Displays a single ObjectActions model.
     * @param string $id
     * @return mixed
     */
    public function actionView()
    {
        return $this->render('view', ['model' => null]);
    }

    /**
     * Delete an existing ObjectActions model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $object_id = isset($object_id) ? $object_id : FHtml::getRequestParam('object_id');
        $object_type = isset($object_type) ? $object_type : FHtml::getRequestParam('object_type');
        $model = ObjectActions::findOne($id);
        if (isset($model))
            $model->delete();

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#' . $this->getPjaxContainerId()];
        }else{
            return $this->redirect(['view', 'object_id' => $object_id, 'object_type' => $object_type]);
        }
    }

    public function actionDeleteAll()
    {
        $request = Yii::$app->request;
        $object_id = isset($object_id) ? $object_id : FHtml::getRequestParam('object_id');
        $object_type = isset($object_type) ? $object_type : FHtml::getRequestParam('object_type');

        ObjectActions::deleteAll(['object_id' => $object_id, 'object_type' => $object_type]);

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#' . $this->getPjaxContainerId()];
        }else{
            return $this->redirect(['view', 'object_id' => $object_id, 'object_type' => $object_type]);
        }
    }

}
