<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\controllers;

use backend\controllers\Controller;
use common\components\FHtml;
use yii\base\Exception;
use yii\helpers\BaseInflector;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

/**
 * ActiveController implements a common set of actions for supporting RESTful access to ActiveRecord.
 *
 * The class of the ActiveRecord should be specified via [[modelClass]], which must implement [[\yii\db\ActiveRecordInterface]].
 * By default, the following actions are supported:
 *
 * - `index`: list of models
 * - `view`: return the details of a model
 * - `create`: create a new model
 * - `update`: update an existing model
 * - `delete`: delete an existing model
 * - `options`: return the allowed HTTP methods
 *
 * You may disable some of these actions by overriding [[actions()]] and unsetting the corresponding actions.
 *
 * To add a new action, either override [[actions()]] by appending a new action class or write a new action method.
 * Make sure you also override [[verbs()]] to properly declare what HTTP methods are allowed by the new action.
 *
 * You should usually override [[checkAccess()]] to check whether the current user has the privilege to perform
 * the specified action against the specified model.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class BaseApiController extends BaseController
{
    public $defaultAction = 'index';

    public $enableCsrfValidation = false;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        //$behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'cache' => ['class' => 'backend\controllers\api\CacheAction', 'checkAccess' => [$this, 'checkAccess']],
            'browse' => ['class' => 'backend\actions\ListObjectAction', 'checkAccess' => [$this, 'checkAccess']],
            'list' => ['class' => 'backend\actions\ListObjectAction', 'checkAccess' => [$this, 'checkAccess']],
            'detail' => ['class' => 'backend\actions\ViewObjectAction', 'checkAccess' => [$this, 'checkAccess']],
            'get' => ['class' => 'backend\actions\ViewObjectAction', 'checkAccess' => [$this, 'checkAccess']],
            'create' => ['class' => 'backend\actions\CreateObjectAction', 'checkAccess' => [$this, 'checkAccess']],
            'add' => ['class' => 'backend\actions\CreateObjectAction', 'checkAccess' => [$this, 'checkAccess']],
            'update' => ['class' => 'backend\actions\UpdateObjectAction', 'checkAccess' => [$this, 'checkAccess']],
            'save' => ['class' => 'backend\actions\UpdateObjectAction', 'checkAccess' => [$this, 'checkAccess']],
            'delete' => ['class' => 'backend\actions\DeleteObjectAction', 'checkAccess' => [$this, 'checkAccess']],
            'count' => ['class' => 'backend\actions\CountAction', 'checkAccess' => [$this, 'checkAccess']],
            'feedback' => ['class' => 'backend\actions\FeedbackAction', 'checkAccess' => [$this, 'checkAccess']],

        ];
    }

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'index' => ['GET', 'HEAD'],
            'view' => ['GET', 'HEAD'],
            'create' => ['POST'],
            'update' => ['PUT', 'PATCH', 'POST'],
            'delete' => ['DELETE'],
        ];
    }


    /**
     * Checks the privilege of the current user.
     *
     * This method should be overridden to check whether the current user has the privilege
     * to run the specified action against the specified data model.
     * If the user does not have access, a [[ForbiddenHttpException]] should be thrown.
     *
     * @param string $action the ID of the action to be executed
     * @param object $model the model to be accessed. If null, it means no specific model is being accessed.
     * @param array $params additional parameters
     * @throws ForbiddenHttpException if the user does not have access
     */
    public function checkAccess()
    {
        return true;
        // need to be modified for security
    }

    public function actionIndex()
    {
        $model = '';
        return $this->render('index.php', ['model' => $model]);
    }


    public function actionExecute($action = '')
    {
        $action = empty($action) ? FHtml::getRequestParam('action') : $action;

        $className = 'backend\\actions\\' . BaseInflector::camelize($action) . 'Action';

        if (class_exists($className)) {
            $action1 = \Yii::createObject(['class' => $className::className()]);
            return $action1::run();
        }
    }

    public function actionListLookup()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $q = FHtml::getRequestParam(['q', 'data']);
        $id = FHtml::getRequestParam(['id']);
        $search_object = FHtml::getRequestParam(['object', 'search_object', 'name'], 'product');
        $search_field = FHtml::getRequestParam(['field', 'search_field'], 'name');
        $search_params = FHtml::getRequestParam(['params', 'search_params'], '');

        if (empty($search_params))
            $search_params = "";
        else
            $search_params = FHtml::decode($search_params);

        $out = ['results' => ['id' => '', 'text' => '']];
        $search_model = FHtml::createModel($search_object);
        if (!isset($search_model))
            return $out;

        $search_fields = ['code', 'name'];
        $search_params_required = is_array($search_params) ? [['like', $search_field, $q]] : "$search_field like '%$q%'";
        foreach ($search_fields as $search_field1) {
            if (FHtml::field_exists($search_model, $search_field1) && $search_field1 != $search_field)
            {
                if (is_array($search_params_required))
                    $search_params_required[] = ['like', $search_field1, $q];
                else
                    $search_params_required .= " OR $search_field1 like '%$q%'";
            }
        }

        if (is_array($search_params_required) && count($search_params_required) > 1)
            $search_params_required = array_merge(['OR'], $search_params_required);
        else if (is_array($search_params_required) && count($search_params_required) == 1)
            $search_params_required = $search_params_required[0];

        if (!is_null($q) || !empty($q)) {
            if (!empty($search_params))
                $params = is_array($search_params) ? ['AND', $search_params_required, $search_params] : ($search_params . " AND ($search_params_required)");
            else
                $params = $search_params_required;

            $data = FHtml::getArray('@' . $search_object, $search_object, '', true, '',  $params);

            $out['results'] = array_values($data);
        } else if (is_null($q) || empty($q)) {
            if (!empty($search_params))
                $params = $search_params;
            else
                $params = [];
            $data = FHtml::getArray('@' . $search_object, $search_object, '', true, '', $params);
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => FHtml::getFieldValue(FHtml::getModel($search_object, '', $id, null, false), ['name', 'title', 'username'])];
        }
        return $out;
    }


    // Return json data of detail object for Lookup api in other controls
    public function actionDetailLookup()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $search_object = FHtml::getRequestParam(['object', 'search_object', 'name'], 'product');
        $search_field = FHtml::getRequestParam(['field', 'search_field'], 'id');

        $post = $_POST['keys'];
        $arr = explode(',', $post); // mulitple ids
        if (empty($arr))
            $data = FHtml::getModel($search_object, '', ['=', $search_field, $post], null, false);
        else
            $data = FHtml::getModels($search_object, ['in', $search_field, $arr]);

        return $data;
    }

    // Update Sort Orders of Object_Type
    public function actionSortOrder()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $object_type = FHtml::getRequestParam('object_type');
        $sort_orders = FHtml::getRequestParam('sort_orders');
        $sort_order_field = FHtml::getRequestParam('sort_order_field', 'sort_order');

        if (empty($sort_orders) || empty($object_type))
            return 'Empty data';

        if (is_array($sort_orders))
            $arr = $sort_orders;
        else if (is_string($sort_orders))
            $arr = explode(',', $sort_orders);

        $sort_orders = $object_type . ': ';
        for ($i = 0; $i < count($arr); $i++) {
            $model = FHtml::getModel($object_type, '', $arr[$i], null, false);
            if (isset($model) && method_exists($model, 'setSortOrder')) {
                $model->setSortOrder($i);
                $model->save();
                $sort_orders .= $arr[$i] . ', ';

            } else if (isset($model) && FHtml::field_exists($model, 'sort_order')) {
                FHtml::setFieldValue($model, 'sort_order', $i);
                $model->save();
                $sort_orders .= $arr[$i] . ', ';
            }
        }
        return $sort_orders;
    }

    // Change Value  of Object_Type
    public function actionChange()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $object_type = FHtml::getRequestParam('object');
        $object_id = FHtml::getRequestParam('id');
        $field = FHtml::getRequestParam('field');
        $value = FHtml::getRequestParam('data');

        $model = FHtml::getModel($object_type, '', $object_id, null, false);

        if (!isset($model))
            return 'Error ! Object [' . $object_type . '] does not exist.  ';

        if (!FHtml::field_exists($model, $field))
            return 'Error ! Field name [' . $field . '] does not exist.  ';

        if (isset($model) && FHtml::field_exists($model, $field)) {
            FHtml::setFieldValue($model, $field, $value);
            $model->save();
            return '';
        } else {
            return 'Error saving ' . $object_type . ', field: ' . $field . ', value: ' . $value;
        }
    }

    // Change Value  of Object_Type
    public function actionActive()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $object_type = FHtml::getRequestParam('object');
        $object_id = FHtml::getRequestParam('id');
        $field = FHtml::getRequestParam('field');
        $value = FHtml::getRequestParam('data');

        $model = FHtml::getModel($object_type, '', $object_id, null, false);
        if (!isset($model))
            return 'Error ! Object [' . $object_type . '] does not exist.  ';

        if (!FHtml::field_exists($model, $field))
            return 'Error ! Field name [' . $field . '] does not exist.  ';

        if (isset($model) && FHtml::field_exists($model, $field)) {
            if ($value == 1)
                $value = 0;
            else
                $value = 1;

            FHtml::setFieldValue($model, $field, $value);
            $model->save();
            return '';
        } else {
            return 'Error saving ' . $object_type . ', field: ' . $field . ', value: ' . $value;
        }
    }

    // Add new Object_Type
    public function actionPlus()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $object_type = FHtml::getRequestParam('object');
        $object_id = FHtml::getRequestParam('id');

        $model = FHtml::createModel($object_type);

        $object_type = BaseInflector::camel2words($object_type);

        if (!isset($model))
            return 'Error ! Object [' . $object_type . '] does not exist.  ';

        try {
            $model = FHtml::loadParams($model, $_REQUEST);
            FHtml::setFieldValue($model, 'is_active', 1);
            FHtml::setFieldValue($model, 'key', 'fdsfs');
            FHtml::setFieldValue($model, 'value', 'fsdfsd');

            $model->save();
            if (!empty($model->getErrors()))
                return 'Error: ' . FHtml::encode($model->getErrors());
            return '';
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    // Add new Object_Type
    public function actionRemove()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $object_type = FHtml::getRequestParam('object');
        $object_id = FHtml::getRequestParam('id');
        $model = FHtml::getModel($object_type, '', $object_id, [], false);

        $object_type = BaseInflector::camel2words($object_type);

        if (!isset($model))
            return 'Error ! Object [' . $object_type . '] does not exist.  ';

        try {
            $model->delete();

            if (!empty($model->getErrors()))
                return 'Error: ' . FHtml::encode($model->getErrors());
            return '';
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    //get Array Settings
    public function actionSettings()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $key = FHtml::getRequestParam('key');
        $object_type = FHtml::getRequestParam('object_type');
        $column = FHtml::getRequestParam('column');
        $result = FHtml::getArray($key, $object_type, $column, false);
        $out = FHtml::getOutputForAPI($result, $key,  '', 'data', 1);
        $out['code'] = $key;
        return $out;
    }
}
