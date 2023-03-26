<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\controllers;


use common\components\FHtml;
use common\components\FModel;
use common\controllers\BaseAdminController;
use yii\web\NotFoundHttpException;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use Yii;
use yii\web\Response;


/**
 * Controller is the base class for RESTful API controller classes.
 *
 * Controller implements the following steps in a RESTful API request handling cycle:
 *
 * 1. Resolving response format (see [[ContentNegotiator]]);
 * 2. Validating request method (see [[verbs()]]).
 * 3. Authenticating user (see [[\yii\filters\auth\AuthInterface]]);
 * 4. Rate limiting (see [[RateLimiter]]);
 * 5. Formatting response data (see [[serializeData()]]).
 *
 * @author Hung Ho (Steve) | www.apptemplate.co, wwww.moza-tech.com, www.codeyii.com | skype: hung.hoxuan  <hung.hoxuan@gmail.com>
 * @since 2.0
 */
class AdminController extends BaseAdminController
{
    //public $enableCsrfValidation = true;

    // Return json data to fill in Combo box for Lookup api in other controls
    public function actionListLookup($search_fields = '')
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

        if (!isset($search_model)) {

            $result = FModel::findAllArray($search_object, $q); //filter array $data by keyword $q

            $out['results'] =  array_values($result);

            return $out;
        }

        if (empty($search_fields))
            $search_fields = ['name', 'code'];
        else
            $search_fields = explode(',', $search_fields);

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
        } else if (is_null($q) || empty($q)) {
            if (!empty($search_params))
                $params = $search_params;
            else
                $params = [];

            $data = FHtml::getArray('@' . $search_object, $search_object, '', true, '', $params);

        } elseif ($id > 0) {
            $data = FHtml::getModel($search_object, '', $id, null, false);
        } else {
            $data = [];
        }

        $arr1 = [];
        foreach ($data as $arr_item) {
            $names = [];
            $id = FHtml::getFieldValue($arr_item, 'id');
            foreach ($search_fields as  $field) {
                $field_value = FHtml::getFieldValue($arr_item, $field);
                if (!empty($field_value))
                    $names[] = (!in_array($field, ['name', 'title', 'code']) ? (FHtml::t('common', $field) . ':') : '') . $field_value . '';
            }
            $name = implode(' | ', $names);
            $name .= " (#" . FHtml::getFieldValue($arr_item, ['id']) . ')';
            $arr1[] = ['id' => $id, 'text' => $name];
        }

        $out['results'] = $arr1; //array_values($data);

        return $out;
    }

    // Return json data of detail object for Lookup api in other controls
    public function actionDetailLookup()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $search_object = FHtml::getRequestParam(['object', 'search_object', 'name'], 'product');
        $search_field = FHtml::getRequestParam(['field', 'search_field'], 'id');
        $search_params = FHtml::getRequestParam(['params', 'search_params'], '');

        $post = FHtml::getRequestParam(['keys', 'id'], FHtml::getFieldValue($_POST, 'keys'));

        if (empty($post))
            return null;

        $search_model = FHtml::createModel($search_object);

        if (!isset($search_model)) {
            return FModel::findOneArray($search_object, $post);
        }

        $data = $search_model::findOne([$search_field => $post]);
        //$data = FHtml::getModel($search_object, '', ['=', $search_field, $post], null, false);

        if (isset($data) && is_object($data) && method_exists($data, 'getAttributes'))
            return $data->getAttributes();

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
                $model->setSortOrder($i + 1);
                $model->save();
                $sort_orders .= $arr[$i] . ', ';

            } else if (isset($model) && FHtml::field_exists($model, 'sort_order')) {
                FHtml::setFieldValue($model, 'sort_order', $i + 1);
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
        $file = FHtml::getRequestParam('file');

        //return FHtml::encode($file) . FHtml::encode($_FILES);

        //return "object: $object_type / id: $object_id / field: $field / value: $value";
        $model = self::getModel($object_type, $object_id, $field);

        if (is_string($model))
            return $model;

        if (isset($model) && FHtml::field_exists($model, $field)) {

            FHtml::setFieldValue($model, $field, $value);

            $result = $model->save();

            if (is_string($result) && !empty($result))
                return $result;
            else if (!$result) {
                if (!empty($model->errors))
                    return FHtml::addError($model->errors);
                else if (!empty($model->getInnerMessage()))
                    return $model->getInnerMessage();
            }

            return '';
        } else {
            if (!isset($model))
                return FHtml::addError(FHtml::t('message', "Invalid Object") . ' : ' . $object_type . ' #' . $object_id);
            else
                return FHtml::addError(FHtml::t('message', "Invalid Attribute") . ' : ' . $field);
        }
    }

    //2017/3/18: Reset some fields to null
    public function actionReset()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $object_type = FHtml::getRequestParam('object');
        $object_id = FHtml::getRequestParam('id');
        $params = FHtml::getRequestParam('params');

        $model = self::getModel($object_type, $object_id);
        if (is_string($model))
            return $model;

        if (!empty($params)) {
            $arr = FHtml::decode($params, true);
        } else {
            $arr = array_merge($_REQUEST, $_POST);
        }

        foreach ($arr as $field => $value) {
            if (is_numeric($field))
            {
                $field = $value;
                $value = null;
            }
            if (isset($model) && FHtml::field_exists($model, $field) && !in_array($field, ['object', 'id', 'params'])) {
                FHtml::setFieldValue($model, $field, $value);
            }
        }
        if ($model->save() || empty($model->errors))
            return '';
        else
            return FHtml::addError($model->errors);
    }

    public function actionUnlink()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $object_type = FHtml::getRequestParam('object');
        $object_id = FHtml::getRequestParam('id');

        if (empty($object_type) || empty($object_id)) {
            return FHtml::t('message', 'Missing param') . ": Object: $object_type - Id: $object_id";
        }

        if ($object_type == 'object_relation')
        {
            FHtml::executeSql("delete from object_relation where id = '$object_id'");
            return '';
        }

        $object2_type = FHtml::getRequestParam('object2_type');
        $object2_id = FHtml::getRequestParam( 'object2_id');

        $relation_type = FHtml::getRequestParam('relation_type');

        if (empty($relation_type)) {
            $relation_condition = "relation_type is null or relation_type = ''";
            $model = FHtml::executeSql("delete from object_relation where (((object_type = '$object_type' and object_id = '$object_id' and object2_type = '$object2_type' and object2_id = '$object2_id') or (object2_type = '$object_type' and object2_id = '$object_id'  and object_type = '$object2_type' and object_id = '$object2_id')) and ($relation_condition))");
        }
        else {
            if (FHtml::field_exists($object_type, $relation_type)) {
                $model = FHtml::getModel($object_type, '', ['id' => $object_id]);
                if (isset($model))
                {
                    $model->setFieldValue($relation_type, null);
                    if ($relation_type == 'object_id') {
                        $model->setFieldValue('object_type', null);
                    }
                    $model->save();
                } else {
                    return FHtml::t('message', 'Data not found') . ": Object: $object_type - Id: $object_id";
                }
            } else {
                $relation_condition = "relation_type = '$relation_type'";
                FHtml::executeSql("delete from object_relation where (((object_type = '$object_type' and object_id = '$object_id' and object2_type = '$object2_type' and object2_id = '$object2_id') or (object2_type = '$object_type' and object2_id = '$object_id'  and object_type = '$object2_type' and object_id = '$object2_id')) and ($relation_condition))");
            }
        }

        return '';
    }

    // Change Value  of Object_Type
    public function actionActive()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $object_type = FHtml::getRequestParam('object');
        $object_id = FHtml::getRequestParam('id');
        $field = FHtml::getRequestParam('field');
        $value = FHtml::getRequestParam('data');

        $model = self::getModel($object_type, $object_id, $field);

        if (is_string($model))
            return $model;

        if (isset($model))
            $value = FHtml::getFieldValue($model, $field);

        if (empty($value))
            $value = 1;
        else
            $value = 0;

        FHtml::setFieldValue($model, $field, $value);

        if ($model->save() || empty($model->errors))
            return '';

        if (!empty($model->getErrors()))
            return FHtml::addError($model->errors);

        return '';
    }

    public function actionPost() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        FHtml::saveRequestPost();
        return '';
    }

    // Add new Object_Type
    public function actionPlus()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $object_type = FHtml::getRequestParam('object');
        $id = FHtml::getRequestParam('id');

        $model = self::getModel($object_type, $id);

        if (!isset($model))
            return FHtml::addError("Could not create or find object [$object_type #$id]");

        if (is_string($model))
            return $model;

        try {
            $model = FHtml::loadParams($model, $_REQUEST);
            $model = FHtml::loadParams($model, $_POST);

            if (is_string($model))
                return $model;

            FHtml::prepareDefaultValues($model, ['is_active', 'created_date', 'created_user', 'application_id']);

            $result = $model->save();
            if (is_string($result) && !empty($result))
                return $result;
            else if (!$result) {
                if (!empty($model->errors))
                    return FHtml::addError($model->errors);
                else if (!empty($model->getInnerMessage()))
                    return $model->getInnerMessage();
            }

            $arr = [];
            if (key_exists('objects', $_POST))
            {
                $arr = FHtml::decode($_POST['objects']);
            } else if (key_exists('objects', $_REQUEST))
            {
                $arr = FHtml::decode($_REQUEST['objects']);
            }

            $result = '';
            if (is_array($arr) && !empty($arr)) {
                foreach ($arr as $object_type1 => $object_params) {
                    //return $object_type . FHtml::encode($object_params);
                    $object_model = FHtml::createModel($object_type1);
                    if (isset($object_model)) {
                        $object_model = FHtml::loadParams($object_model, $object_params);
                        $t = '';
                        foreach ($object_params as $param_key => $param_value) {
                            if ($param_value == '{model.id}' || $param_value == '{object_id}') {
                                $param_value = $model->id;
                            }
                            else if ($param_value == '{model.table}' || $param_value == '{object_type}') {
                                $param_value = FHtml::getTableName($model);
                            }
                            else
                                continue;

                            FHtml::setFieldValue($object_model, $param_key, $param_value);
                        }

                        $object_model->save();

                        if (!empty($object_model->getErrors()))
                            return 'Error: ' . $object_type1 . ': '. FHtml::encode($object_model->getErrors()) . '\n';
                    }
                }
            }

            if (!empty($model->getErrors()))
                return FHtml::addError($model->errors);
            return $result;
        } catch (Exception $e) {
            return FHtml::addError($e);
        }
    }

    // Add new Object_Type
    public function actionRemove()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $object_type = FHtml::getRequestParam('object');
        $object_id = FHtml::getRequestParam('id');

        $model = self::getModel($object_type, $object_id);
        if (!isset($model))
            return FHtml::t('message', 'Data not found') . ": Object: $object_type - Id: $object_id";

        if (is_string($model))
            return $model;

        if ($object_type == 'object_relation') {
            $object_type = $model->object2_type;
            $object_id = $model->object2_id;
            $model->delete();
            $model = self::getModel($object_type, $object_id);
        }

        if (!isset($model))
            return FHtml::t('message', 'Data not found') . ": Object: $object_type - Id: $object_id";

        try {
            $model->delete();

            if (!empty($model->getErrors()))
                return FHtml::addError($model->errors);
            return '';
        } catch (Exception $e) {
            return FHtml::addError($e);
        }
    }

    public function actionViewDetail($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', ['model' => $model]);
    }

    public function actionDeleteAll()
    {
        if (FHtml::isRoleAdmin()) {
            $object_type = FHtml::getRequestParam('object_type');
            $object_id = FHtml::getRequestParam('object_id');
            $object = FHtml::getRequestParam('object');

            if (!empty($object) && !empty($object_type)) {
                $table = $object;
                $condition = empty($object_id) ? ['object_type' => $object_type] : ['object_type' => $object_type, 'object_id' => $object_id];
            } else {
                $table = FHtml::getRequestParam('object', FModel::getTableName(FModel::currentController()));
                $condition = [];
            }

            if (FModel::deleteAll($table, $condition))
                FHtml::refreshCache();
        }
        $returnParams = FHtml::RequestParams(['id']);

        return self::exitAction($returnParams);
    }

    /**
     * Displays a single CmsAbout model.
     * @param integer $id
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
            ];
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Creates a new CmsAbout model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;

        $model = $this->createModel($this->object_type);

        if ($request->isAjax) {
            return $this->saveModelAjax($model);
        } else {
            if ($model->load($request->post())) {
                $model->id = null;
                if ($model->save()) {
                    return $this->returnView('update', $model->id);
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
     * Updates an existing CmsAbout model.
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
            return $this->saveModelAjax($model);
        } else {
            if ($model->load($request->post())) {
                if ($model->save()) {
                    return $this->returnView('update', $model->id);
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
     * Delete an existing SmartscreenStation model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $returnParams = FHtml::RequestParams(['id']);

        $this->findModel($id)->delete();

        return self::exitAction($returnParams);
    }

    /**
     * Delete multiple existing SmartscreenStation model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkDelete()
    {
        $returnParams = FHtml::RequestParams(['id']);

        $models = self::getSelectedModels();
        foreach ($models as $model) {
            if (isset($model)) {
                $model->delete();
            }
        }

        return self::exitAction($returnParams);
    }

    public function actionBulkAction($action = '', $field = '', $value = '')
    {
        $returnParams = FHtml::RequestParams(['id']);
        $models = self::getSelectedModels();

        foreach ($models as $model) {
            if (isset($model)) {
                if ($action == 'change') {
                    $model[$field] = $value;
                    $model->save();
                }
            }
        }

        return self::exitAction($returnParams);
    }

    public function actionBulkApprove($field = '', $value = '')
    {
        $returnParams = FHtml::RequestParams(['id']);
        $models = self::getSelectedModels();

        foreach ($models as $model) {
            if (isset($model)) {
                $model->approve();
            }
        }

        return self::exitAction($returnParams);
    }

    public function actionBulkReject($field = '', $value = '')
    {
        $returnParams = FHtml::RequestParams(['id']);

        $models = self::getSelectedModels();

        foreach ($models as $model) {
            if (isset($model)) {
                $model->reject();
            }
        }

        return self::exitAction($returnParams);
    }

}
