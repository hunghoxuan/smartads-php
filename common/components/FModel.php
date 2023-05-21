<?php

/*This is the customized model class for table "<?= $generator->generateTableName($tableName) ?>".
 */

namespace common\components;

use applications\mozaweb\models\EcommerceProduct;
use backend\models;
use backend\models\ObjectSetting;
use backend\models\ObjectTranslation;
use common\base\BaseAPIObject;
use common\base\BaseDataObject;
use common\config\FSettings;
use common\models\BaseDataList;
use common\models\BaseModel;
use common\widgets\FCheckbox;
use common\widgets\FDateInput;
use common\widgets\FDatePicker;
use common\widgets\FFileInput;
use common\widgets\fheadline\FHeadline;
use common\widgets\FNumericInput;
use common\widgets\FRangeInput;
use common\widgets\FTimeInput;
use common\widgets\FUploadedFile;
use common\models\ViewModel;
use kartik\switchinput\SwitchInput;
use Symfony\Component\HttpKernel\Fragment\FragmentHandler;
use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\db\Connection;
use yii\db\cubrid\Schema;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use yii\helpers\Json;
use yii\helpers\StringHelper;

class FModel extends FConfig
{
    const FIELD_PROPERTIES = 'properties';
    const FIELD_TRANSLATIONS = 'translations';
    const TABLE_TRANSLATIONS = 'object_translation';
    const TABLE_ATTRIBUTES = 'object_attributes';
    const TABLE_PROPERTIES = 'object_properties';
    const TABLE_RELATIONS = 'object_relation';


    public static function getControllerObject($tableName, $zone = 'backend')
    {
        $tableName = self::getTableName($tableName);
        $namespace = self::getControllerNamespace($tableName);
        $module = self::getModelModule($tableName);
        $className = $namespace . BaseInflector::camelize($tableName) . 'Controller';
        if (class_exists($className)) {
            return Yii::createObject(['class' => $className], [$tableName, $module]);
        }
        return null;
    }

    public static function getTableName($model)
    {
        $result = '';
        if (is_array($model) && is_object($model))
            $model = $model[0];

        if (is_object($model) && method_exists($model, 'getTableName')) {
            return $model->getTableName();
        } else if (is_string($model))
            $result = $model;
        else if (is_object($model) && method_exists($model, 'tableName')) {
            return $model::tableName();
        } else {
            return '';
        }

        if (StringHelper::endsWith($result, '_search}}'))
            $result = str_replace('_search}}', '}}', $result);

        $result = str_replace('-', '_', BaseInflector::camel2id($result));
        $result = str_replace('{{%', '', $result);
        $result = str_replace('}}', '', $result);

        return $result;
    }

    public static function getControllerNamespace($table, $namespace = 'backend\\controllers\\')
    {
        $table = FHtml::getTableName($table);

        $module = self::getModelModule(strtolower($table));
        if (!empty($module))
            return 'backend\\modules\\' . $module . '\\controllers\\';

        return $namespace;
    }

    public static function getModuleTables()
    {
        $modules = self::MODULES;
        $modules1 = FHtml::getModulesArray();
        foreach ($modules1 as $module1 => $module1Object) {
            $moduleObject = FHtml::getModuleObject($module1);
            if (!isset($modules[$module1]))
                $modules[$module1] = [];

            if (FHtml::field_exists($moduleObject, 'TABLES'))
                $modules[$module1] = array_merge($modules[$module1], $moduleObject::TABLES);
        }

        return $modules;
    }

    public static function getModelModule($table)
    {
        if (is_object($table))
            $table = FHtml::getTableName($table);

        if (is_array($table)) {
            if (isset($table['object_type'])) {
                $table = $table['object_type'];
            } else {
                return '';
            }
        }

        $modules = static::getModuleTables();

        foreach ($modules as $module => $tables) {
            if ((FHtml::isInArray($table, $tables) || FHtml::isInArray(BaseInflector::camelize($table), $tables) || FHtml::isInArray(strtolower($table), $tables)) && !empty($tables)) {
                return $module;
            }
        }

        try {
            $result = explode('_', $table);
            if (count($result) > 1)
                return $result[0];

            return $table;
        } catch (Exception $e) {
            return '';
        }
    }

    public static function createMetaModel($table, $type = '', $id = null, $params = null)
    {
        if (empty($type)) {
            return null;
        } else {
            return self::getModel($table, $type, $id, $params);
        }
    }

    // 2017/3/7
    public static function getModel($table, $type = '', $id = null, $default_fields = null, $autoCreateNew = true)
    {
        if (empty($table))
            return null;

        $model = null;

        if (is_array($table) && !empty($table)) {
            $table = $table[0];
        }

        if (StringHelper::startsWith($table, '@'))
            $table = substr($table, 1);

        if (strpos($table, '\\') !== false) {
            if (class_exists($table)) {
                $model = Yii::createObject(['class' => $table]);
            }
        } else {
            if (is_array($type)) {
                $default_fields = $type;
                $autoCreateNew = isset($id) ? $id : true;
                $id = $type;
                $type = '';
            }

            $classNames = [];
            $currentModule = FHtml::currentModule();
            $baseClassName = str_replace('-', '_', BaseInflector::camelize($table)) . BaseInflector::camelize($type);
            $className1 = self::getApplicationNamespace() . '\\models\\' . $baseClassName;
            $className5 = empty($currentModule) ? '' : FHtml::getApplicationNamespace() . '\\backend\\modules\\' . FHtml::currentModule() . '\\models\\' . $baseClassName;
            $className2 = empty($currentModule) ? '' : FHtml::getApplicationNamespace() . '\\backend\\modules\\' . FHtml::getModelModule($table) . '\\models\\' . $baseClassName;
            $className3 = self::getModelNamespace($table) . $baseClassName;
            $className4 = "backend\\models\\" . $baseClassName;

            $classNames = [$className1, $className2, $className5, $className3, $className4];

            foreach ($classNames as $className) {
                if (!empty($className) && class_exists($className)) {
                    $model = Yii::createObject(['class' => $className]);

                    break;
                }
                if (isset($model))
                    break;
            }
        }

        if (isset($model)) {
            if (isset($id) && !empty($id))
                $model = $model::findOne($id);

            if (!isset($model)) {
                if ($autoCreateNew) {
                    $model = Yii::createObject(['class' => $className::className()]);
                } else
                    return null;
            }
            if (is_array($id) && empty($default_fields))
                $default_fields = $id;

            return $model = self::setFieldValues($model, $default_fields);
        }

        return null;
    }

    public static function getApplicationNamespace($application_id = '', $namespace = '')
    {
        if (empty($application_id))
            $application_id = strtolower(FHtml::currentApplicationId());

        if (!empty($application_id))
            return 'applications\\' . $application_id;

        return $namespace;
    }

    public static function getModelNamespace($table, $namespace = 'backend\\models\\')
    {
        $table = FHtml::getTableName($table);

        $module = self::getModelModule(strtolower($table));
        if (!empty($module))
            return 'backend\\modules\\' . $module . '\\models\\';

        return $namespace;
    }

    //2017.05.03

    public static function setFieldValues($model, $arrays)
    {
        if (!isset($arrays) || empty($arrays))
            return $model;

        foreach ($arrays as $field => $value) {
            if (self::field_exists($model, $field))
                FHtml::setFieldValue($model, $field, $value);
        }

        return $model;
    }

    public static function getProperties($model)
    {
        if (!is_object($model))
            return [];

        $class = new \ReflectionClass($model);
        $names = [];
        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if (!$property->isStatic()) {
                $names[] = $property->getName();
            }
        }

        return $names;
    }

    public static function constant_defined($model, $field)
    {
        if (is_object($model) && defined($model::className() . '::' . $field)) {
            return true;
        }
        return false;
    }

    public static function attribute_exists($model, $field)
    {
        $a = self::field_exists($model, $field, true);
        return $a;
    }

    public static function field_exists($model, $field, $check_attribute_only = false)
    {
        if (is_object($model) && property_exists($model, 'modelClass')) { // if parse Query, not Active Record
            $model = self::createModel($model->modelClass);
        }

        if (is_string($model))
            $model = FHtml::createModel($model);

        if (!isset($model) || empty($model))
            return false;

        try {
            $pos = strpos(strtolower($field), ' as ');
            if ($pos > 0) {
                $field = substr($field, 0, $pos);
            }

            if (self::constant_defined($model, $field)) {
                return true;
            }

            if (is_array($model)) {
                if ((key_exists($field, $model)
                    || (self::is_numeric($field) && count($model) > $field)))
                    return true;
            }


            if (is_object($model) && isset($model)) {
                if ($check_attribute_only) {
                    return method_exists($model, 'hasAttribute') && $model->hasAttribute($field);
                }

                if (
                    property_exists($model, $field)
                    || (method_exists($model, 'hasAttribute') && $model->hasAttribute($field)) // in attributes array
                    // || (method_exists($model, 'fields') && in_array($field, $model->fields())) // in fields array
                    || (method_exists($model, $field)) //public property
                    //|| (strpos($field, '.') !== false && !empty(self::getFieldValue($model, $field))) //Hung: getFieldValue with column which has . inside the name cause performance issue
                    //|| (method_exists($model, 'getCustomFields') && in_array($field, $model->getCustomFields()))
                    //|| (method_exists($model, 'getObjectAttributesArray') && key_exists($field, $model->getObjectAttributesArray()))
                    || (property_exists($model, 'objectAttributesArray') && key_exists($field, $model->objectAttributesArray))
                )
                    return true;
            }

            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function createModel($table)
    {
        return self::getModel($table);
    }

    public static function getInstance($table)
    {
        return self::getModel($table);
    }

    public static function is_numeric($value)
    {
        return is_numeric($value);
    }

    public static function setFieldValue(&$model, $field, $value, $overrideIfEmpty = true)
    {
        if (!$overrideIfEmpty && empty($value)) // does not set value if $value is empty
            return $model;

        $array = [];
        if (is_array($field))
            $array = $field;
        else
            $array[] = $field;

        if (is_callable($value) && !is_string($value))
            $value = call_user_func($value, $model);

        foreach ($array as $field1) {
            if (self::field_exists($model, $field1)) {
                if (is_object($model))
                    $model->$field1 = $value;
                else if (is_array($model)) {
                    $model[$field1] = $value;
                }
            } else {
                if (is_array($model)) {
                    $model[$field1] = $value;
                } else if (is_object($model)) { // auto add column to table if not existed
                    if (FConfig::settingDynamicObjectEnabled()) {
                        self::addColumn($model, $field1);
                        $model->refresh();
                        $model->$field1 = $value;
                    } else if (method_exists($model, 'settingDynamicFieldEnabled') && $model::settingDynamicFieldEnabled()) {
                        $model->$field1 = $value;
                    } else if (method_exists($model, 'setCustomAttribute')) {
                        $model->setCustomAttribute($field, $value);
                    }
                }
            }
        }

        return $model;
    }

    public static function addColumn($table, $column, $type = Schema::TYPE_STRING . '(1000)')
    {
        $db = new FDatabase();
        if (is_object($table))
            $table = FHtml::getTableName($table);
        $db->addColumn($table, $column, $type);
    }

    public static function getDataProvider($table, $params = [])
    {
        if (is_object($table))
            $table = FHtml::getTableName($table);

        $searchModel = self::createModel($table . '_search');
        if (isset($searchModel) && is_array($params)) // dont use it if param is string
            return $searchModel->search($params);

        $searchModel = self::createModel($table);

        if (isset($searchModel)) {
            $query = self::getModelQuery($searchModel, $params);
            $provider = new \yii\data\ActiveDataProvider([
                'query' => $query,
            ]);
            return $provider;
        }

        return null;
    }

    public static function buildQueryFromModel($model, $search_params, $order_by = [], $load_active_only = true)
    {
        return self::getModelQuery($model, $search_params, $order_by, $load_active_only);
    }

    public static function getModelQuery($model, $search_params, $order_by = [], $load_active_only = true)
    {
        if (is_array($model))
            return null;

        if (!is_object($model) && is_string($model)) {
            $model = FHtml::createModel($model);
        }

        if (!isset($model))
            return null;

        $query = $model::find();
        //OR
        $query = $query->andWhere($search_params)->orderBy($order_by);
        return $query;

        //        if ($load_active_only || !FHtml::isRoleAdmin()) {
        //            if (self::field_exists($query, 'is_active')) {
        //                if (empty($search_params)) {
        //                    $search_params = ['is_active' => 1];
        //                } else {
        //                    if (is_array($search_params))
        //                        $search_params =  ['AND', [$search_params], ['is_active' => 1]];
        //                    else if (is_string($search_params))
        //                        $search_params = $search_params . ' AND is_active = 1';
        //                }
        //            }
        //        }
        //
        //        if (is_string($search_params)) {
        //            $query = $query->andWhere($search_params);
        //        } else if (is_array($search_params)) {
        //            foreach ($search_params as $field => $value) {
        //                if ($value == self::NULL_VALUE || !isset($value) || (empty($value) && is_string($value)) || is_object($value))
        //                    continue;
        //
        //                if (self::field_exists($query, $field, true)) { // && key_exists($field, ArrayHelper::map($model->getAttributes(), 0, 0)) --> this code is error
        //                    $query = $query->andWhere(self::buildQueryParams($field, $value));
        //                }
        //            }
        //        }
        //
        //        $query = $query->orderBy(!empty($order_by) ? $order_by : FHtml::getOrderBy($model));
        //
        //        return $query;
    }

    public static function buildQueryParams($fields, $value = '', $operator = '=', $connector = 'or')
    {
        $item = [];
        if (is_string($fields) && strpos($fields, ',') !== false) {
            $fields = explode(',', $fields);
        }

        //2017/3/19: [$operator, $field, $value]
        if (is_numeric($fields) && is_array($value) && count($value) == 3) {
            $operator = $value[0];
            $fields = $value[1];
            $value = $value[2];
        }

        // 2016-10-19: if keyword contains '%' then it is LIKE
        if (is_string($value) && strpos($value, '%') !== false) {
            $value = str_replace('%', '', $value);
            $operator = 'like';
        }

        if ((is_string($value) && strpos($value, ',') !== false) || is_array($value)) {
            if (is_string($value))
                $value = explode(',', $value);
            $operator = 'in';
            $item = [$operator, $fields, $value];
            return $item;
        }

        // 2017-3-17:
        if (StringHelper::startsWith($value, '-') || StringHelper::startsWith($value, '!')) {
            $value = substr($value, 1, strlen($value) - 1);
            $operator = '!=';
        }

        if (is_array($fields)) {
            // If there are more than 2 fields than auto merge conditions of the fields

            $item[] = $connector;
            foreach ($fields as $field1) {
                if (FHtml::isInArray($field1, ['category_id', 'categoryid'])) {
                    $operator = $operator == '!=' ? 'not like' : 'like';
                    $value1 = ',' . $value . ',';
                    if ($operator == 'like')
                        $item[] = ['OR', [$field1 => $value], [$operator, $field1, $value1]];
                    else
                        $item[] = ['AND', ['<>', $field1, $value], [$operator, $field1, $value1]];
                } else if (!is_numeric($value) && is_string($value) && !FHtml::isInArray($field1, FHtml::getFIELDS_GROUP())) {
                    $operator = $operator == '!=' ? 'not like' : 'like';
                    $item[] = [$operator, $field1, $value];
                }
            }
        } else if (is_string($fields)) {
            $field1 = $fields;
            if (FHtml::isInArray($field1, ['category_id', 'categoryid'])) {
                $operator = $operator == '!=' ? 'not like' : 'like';
                $value1 = ',' . $value . ',';
                if ($operator == 'like')
                    $item = ['OR', [$field1 => $value], [$operator, $field1, $value1]];
                else
                    $item = ['AND', ['<>', $field1, $value], [$operator, $field1, $value1]];
                return $item;
            } else if (!is_numeric($value) && is_string($value) && !FHtml::isInArray($field1, FHtml::getFIELDS_GROUP())) {
                $operator = $operator == '!=' ? 'not like' : 'like';
            }

            if ($operator != '=' && !empty($operator))
                $item = [$operator, $field1, $value];
            else if (in_array($field1, ['application_id']))
                $item = "`{$field1}` is null or `$field1` = '$value'";
            else if (empty($value))
                $item = "`{$field1}` is null or `$field1` = '$value'";
            else if (in_array($operator, ['<>', 'not like']))
                $item = "`{$field1}` is null or `$field1` {$operator} '$value'";
            else
                $item = [$field1 => $value];
        }


        return $item;
    }

    public static function getFIELDS_GROUP()
    {
        $group = FSettings::FIELDS_GROUP;
        $modules = self::getModules();
        foreach ($modules as $moduleName) {
            $module = self::getModuleObject($moduleName);
            if (isset($module)) {
                if (defined($module::className() . '::FIELDS_GROUP')) {
                    $group = array_merge($group, $module::FIELDS_GROUP);
                }
            }
        }

        return $group;
    }

    public static function getModules()
    {
        return array_keys(\Yii::$app->getModules());
    }

    public static function getModuleObject($module, $zone = 'backend')
    {
        $module = strtolower($module);
        $application_id = FHtml::currentApplicationId();

        $className = $zone . '\\modules\\' . $module . '\\' . BaseInflector::camelize($module);
        $className1 = 'applications\\' . $application_id . '\\' . $zone . '\\modules\\' . $module . '\\' . BaseInflector::camelize($module);

        if (class_exists($className1))
            return $module = Yii::createObject(['class' => $className1], [$module, $module]);

        if (class_exists($className)) {
            return Yii::$app->getModule($module);
        }

        return null;
    }

    public static function getOrderBy($model)
    {
        $sort = FHtml::getRequestParam(['sort', 'order_by', 'sort_by']);

        if (!empty($sort) && FHtml::field_exists($model, $sort))
            return $sort;

        if (FHtml::field_exists($model, 'order_by') && !empty($model->order_by))
            return $model->order_by;

        $s = '';
        if (FHtml::field_exists($model, 'sort_order'))
            $s .= 'sort_order asc, ';

        if (FHtml::field_exists($model, 'is_active'))
            $s .= 'is_active desc, ';

        if (FHtml::field_exists($model, 'is_hot'))
            $s .= 'is_hot desc, ';

        if (FHtml::field_exists($model, 'id'))
            $s .= $model->tableName . '.id asc, ';

        if (FHtml::field_exists($model, 'modified_date'))
            $s .= 'modified_date asc, ';

        if (FHtml::field_exists($model, 'created_date'))
            $s .= 'created_date asc, ';

        if (FHtml::field_exists($model, 'name'))
            $s .= 'name asc, ';

        return $s;
    }

    public static function delete($table, $condition = [])
    {
        $model = self::findOne($table, $condition);
        if (isset($model))
            return $model->delete();
        return false;
    }

    public static function findOne($table, $condition)
    {
        if (is_object($table))
            $table = FHtml::getTableName($table);

        return self::getModel($table, '', $condition, null, false);
    }

    public static function deleteAll($table, $condition = [])
    {
        $model = self::createModel($table);
        if (isset($model))
            return $model::deleteAll($condition);
        return false;
    }

    public static function findAllForCombo($table, $condition = [], $id_field = 'id', $display_name = 'name', $order_by = '')
    {
        $models = static::findAll($table, $condition, $order_by);
        return FModel::arrayMap($models, $id_field, $display_name);
    }

    public static function findAll($table, $condition = [], $order_by = [], $page_size = -1, $page = -1, $isCached = false, $selected_fields = [], $asArray = false, $load_active_only = true)
    {
        if (is_object($table))
            $table = FHtml::getTableName($table);

        return self::getModels($table, $condition, $order_by, $page_size, $page, $isCached, $load_active_only, $selected_fields, $asArray);
    }

    public static function getModels($object_type, $search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $load_active_only = true, $selectedFields = [], $asArray = false)
    {
        $list = self::getModelsList($object_type, $search_params, $order_by, $page_size, $page, $isCached, $load_active_only, $selectedFields, $asArray);

        $models =  (isset($list) && isset($list->query)) ? $list->getModels() : [];

        return $models;
    }

    public static function getModelsList($object_type = '', $search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $load_active_only = true, $selected_fields = [], $asArray = false)
    {
        if (is_object($object_type)) {
            $model = $object_type;
            $object_type = FHtml::getTableName($model);
        }

        if (FHtml::isDynamicQueryEnabled()) {
            $lookupModel = self::getSettingLookup($object_type); // Get defined Params in setting_query table
            if (isset($lookupModel)) {
                if ($lookupModel->is_active === 1) {
                    $object_type = $lookupModel->object_type;
                    $search_params = $lookupModel->params;
                    $order_by = $lookupModel->orderby;
                    $page_size = $lookupModel->limit;
                    $isCached = $lookupModel->is_cached;
                    $selected_fields = $lookupModel->fields;
                }
            }
        }

        if ($isCached) {
            $data = self::getCachedData('', $object_type, 'LIST');
            if (isset($data))
                return $data;
        }

        $model = self::createModel($object_type);

        $order_by = empty($order_by) ? (method_exists($model, 'getOrderBy') ? $model->getOrderBy() : []) : $order_by;

        if (!is_object($model) && !isset($model))
            return null;

        $query = self::getModelQuery($model, $search_params, $order_by, $load_active_only);

        //FHtml::var_dump($query->where); die;

        if (!isset($query) || is_string($query))
            return $query;

        $total = 0;

        if ($asArray)
            $query = $query->asArray();

        try {
            if ($page_size < 1) {
            } else {

                $total = $query->count();
                if ($page * $page_size - $page_size >= $total) {

                    return $provider = new BaseDataList([
                        'query' => $query,
                        'models' => [],
                        'asArray' => $asArray,
                        'display_fields' => $selected_fields,
                        'pagination' => [
                            'pageSize' => $page_size,
                            'totalCount' => $total,
                            'page' => $page
                        ],
                    ]);
                    //$page = ceil($total/$page_size);
                } else if ($page < 1)
                    $page = 1;

                $start_index = $page * $page_size - $page_size;
                $query = $query->limit($page_size)->offset($start_index);
            }

            $models = $query->all();
        } catch (\yii\db\Exception $ex) {
            FHtml::addError($ex);
            $query = null;
            $models = [];
        }

        $provider = new BaseDataList([
            'query' => $query,
            'models' => $models,
            'asArray' => $asArray,
            'display_fields' => $selected_fields,
            'pagination' => [
                'pageSize' => $page_size,
                'totalCount' => $total,
                'page' => $page
            ],
        ]);


        return $provider;
    }

    public static function getSettingLookup($name)
    {
        $model = FHtml::getModel('settings_lookup', '', ['name' => $name]); // models\SettingsLookup::findOne(['name' => $name]);
        return $model;
    }

    //2017.5.4

    public static function getCachedModels($table, $condition = [], $order_by = [], $page_size = -1, $page = -1)
    {
        if (is_object($table))
            $table = FHtml::getTableName($table);

        return self::getModels($table, $condition, $order_by, $page_size, $page, true);
    }

    public static function findCachedModels($table, $condition = [], $order_by = [], $page_size = -1, $page = -1)
    {
        if (is_object($table))
            $table = FHtml::getTableName($table);

        return self::getModels($table, $condition, $order_by, $page_size, $page, true);
    }

    public static function getModelObject($table, $type)
    {
        if (empty($table))
            return null;

        $className = self::getModelNamespace($table) . str_replace('-', '_', BaseInflector::camelize($table)) . BaseInflector::camelize($type);

        if (class_exists($className)) {
            $model = Yii::createObject(['class' => $className]);
            return $model;
        }
        return null;
    }

    public static function getPageViewModel($object_type = '', $id = '')
    {
        if (empty($id))
            $id = \common\components\FHtml::getRequestParam(['id']);

        $model = null;
        if (!empty($id))
            $model = FHtml::getViewModel($object_type, '', $id);

        return $model;
    }

    public static function getViewModel($table, $type = '', $id = null, $params = null, $autoCreateNew = true)
    {
        $model = self::getModel($table, $type, $id, $params, $autoCreateNew);
        if (isset($model))
            return $model->toViewModel();
        return null;
    }

    public static function copyFieldValues($model, $arrays, $rootModel = null, $overrideIfEmpty = false)
    {
        if (!isset($rootModel)) {
            foreach ($arrays as $field => $value) {
                if ($overrideIfEmpty || !empty($value))
                    FHtml::setFieldValue($model, $field, $value);
            }
            return $model;
        }

        if (!isset($arrays) || empty($arrays))
            $arrays = array_keys($rootModel->getAttributes());

        foreach ($arrays as $field) {
            if (self::field_exists($model, $field) && self::field_exists($rootModel, $field)) {
                $value = FHtml::getFieldValue($rootModel, $field);
                if ($overrideIfEmpty || !empty($value))
                    FHtml::setFieldValue($model, $field, $value);
            }
        }

        return $model;
    }

    public static function getFieldExpression($model, $field, $empty_value = null)
    {
        if (is_string($model) && ArrayHelper::isAssociative($field) && !empty($empty_value)) {
            $model = FHtml::getModel($model, '', $field, [], false);
            $field = $empty_value;
            $empty_value = null;
        }

        $arr = (is_object($model) && method_exists($model, 'getAttributes')) ? $model->getAttributes() : (is_array($model) ? $model : []);

        $result = $field;
        $params = [];

        foreach ($arr as $key => $value) {
            $key = trim($key);
            $value = is_array($value) ? FHtml::showArrayAsTable($value) : trim($value);
            $params = array_merge($params, ['$' . $key . ' ' => $value . ' ']);
            $params = array_merge($params, ['${' . $key . '}' => $value]);
            $params = array_merge($params, ["[$key]" => $value]);
            $params = array_merge($params, ["{{{$key}}}" => $value]);
            $params = array_merge($params, ["{{$key}}" => $value]);
            $params = array_merge($params, [" $key " => " " . $value . " "]);
            $params = array_merge($params, ["$key " => $value . " "]);
            $params = array_merge($params, [" $key" => " " . $value]);
            $params = array_merge($params, ["$key" => $value]);
        }

        $result = FHtml::strReplace($result, $params);
        return trim($result);
    }

    public static function getFieldValue($model, $field, $empty_value = null, $field_type = '')
    {
        if (is_object($model) && method_exists($model, 'getFieldValue')) {
            return $model->getFieldValue($field, $empty_value, true);
        }

        try {
            if (is_string($model) && ArrayHelper::isAssociative($field) && !empty($empty_value)) {
                $model = FHtml::getModel($model, '', $field, [], false);
                $field = $empty_value;
                $empty_value = null;
            }

            $arr = [];
            if (isset($model)) {
                if (is_string($field) && !empty($field)) {
                    if (strpos($field, ',') !== false || strpos($field, ' ') !== false || strpos($field, '(') !== false || strpos($field, '{') !== false || strpos($field, '[') !== false || strpos($field, '}') !== false) {
                        return self::getFieldExpression($model, $field, $empty_value);
                    } else {
                        $arr[] = $field;
                    }
                } else if (is_array($field)) {
                    $arr = $field;
                } else if (is_numeric($field)) {
                    $arr[] = $field;
                }
                $result = null;

                foreach ($arr as $field1) {
                    $field1 = trim($field1);
                    //field1 as field of Child Object. Example: ::getFieldValue('product.name');
                    $arr = explode('.', $field1);
                    if (count($arr) > 1) {
                        $model = FModel::getFieldValue($model, $arr[0]);
                        if (isset($model))
                            return FModel::getFieldValue($model, $arr[1], $empty_value);
                        return $empty_value;
                    }

                    //field1 as property
                    if (isset($model) && !empty($field1)) {
                        if (is_array($model)  && isset($model[$field1])) {
                            return $model[$field1];
                        }
                    }

                    if (self::field_exists($model, $field1)) {
                        if (is_object($model))
                            $result = $model->{$field1};
                        else if (is_array($model))
                            $result = $model[$field1];
                        return $result;
                    } else if (is_object($model) && FHtml::settingDynamicFieldEnabled()) {
                        //try to get custom attribute if setting dynamic field enabled is True

                        $result = $model->{$field1};
                        if (isset($result))
                            return $result;
                    }
                }

                return !isset($result) ? self::getValue($empty_value, $field_type) : $result;
            } else
                return self::getValue($empty_value, $field_type);
        } catch (Exception $e) {
            return self::getValue($empty_value, $field_type);
        }
    }

    public static function getValue($value, $field_type = '', $empty_value = null)
    {
        if (in_array($field_type, ['numeric', 'int', FHTML::SHOW_NUMBER]))
            $empty_value = 0;
        else if (!isset($empty_value) && in_array($field_type, ['bool', FHtml::SHOW_BOOLEAN]))
            $empty_value = false;

        if (is_array($value))
            return $value[rand(0, count($value) - 1)]['id'];
        else if (isset($value))
            return $value;

        if (is_array($empty_value))
            return $empty_value[rand(0, count($empty_value) - 1)]['id'];
        else
            return $empty_value;
    }

    public static function getCloneModel($table, $id = '', $params = null)
    {
        $model = FHtml::createModel($table);

        if (!empty($id)) {
            $model = $model::findOne($id);
            if (isset($model)) {
                $model->id = null;
                $model->setIsNewRecord(true);
            } else {
                $model = FHtml::createModel($table);
            }
        }

        $model = self::setFieldValues($model, $params);

        return $model;
    }

    public static function getModelForAPI($table, $type = '', $id = null, $params = null, $autoCreateNew = true)
    {
        $model = self::getModel($table, $type, $id, $params, $autoCreateNew);
        return self::prepareDataForAPI($model);
    }

    public static function prepareDataForAPI($models, $folder = '', $displayFields = [], $file_fields = ['image', 'icon', 'thumbnail', 'avatar', 'banner', 'file'])
    {
        if (!is_array($models) && is_object($models))
            $arr[] = $models;
        else if (is_array($models))
            $arr = $models;
        if (!empty($arr)) {
            foreach ($arr as $model) {
                if (!is_object($model)) {
                    continue;
                }

                //get display fields from ModelAPI
                $modelAPI = FModel::getModelAPI($model);
                if (isset($modelAPI) && self::field_exists($modelAPI, 'fields') && empty($displayFields))
                    $displayFields = $modelAPI->fields();

                if (!empty($displayFields) && method_exists($model, 'setFields')) {
                    $model->setFields($displayFields);
                } else if (!empty($displayFields) && self::field_exists($model, 'columnsMode')) {
                    $model->columnsMode = $displayFields;
                } else if (empty($displayFields) && self::field_exists($model, 'columnsMode')) {
                    $model->columnsMode = empty($model->columnsMode) ? 'api' : $model->columnsMode;
                }
                if (method_exists($model, 'prepareCustomFields'))
                    $model->prepareCustomFields();

                if (empty($folder) && method_exists($model, 'getTableName'))
                    $folder = str_replace('_', '-', $model->getTableName());

                foreach ($file_fields as $field) {
                    if (self::field_exists($model, $field))
                        FHtml::setFieldValue($model, $field, FHtml::getFileURLForAPI(FHtml::getFieldValue($model, $field), $folder));
                }
            }
        }

        return $models;
    }

    /**
     * @param $model
     * @return object|null
     * @throws \yii\base\InvalidConfigException
     */
    public static function getModelAPI($model)
    {
        $classNames = [];
        /* @var $model string | ActiveRecord */
        if (is_string($model)) {
            $module = FHtml::getModelModule($model);
            $model = BaseInflector::camelize($model);
            $classNames[] = FHtml::getApplicationNamespace() . "backend\\modules\\$module\\models\\{$model}API";
            $classNames[] = "backend\\modules\\$module\\models\\{$model}API";
        } else if (is_object($model)) {
            if (strpos($model::className(), "API") == false)
                $classNames[] = $model::className() . 'API';
        }

        foreach ($classNames as $className) {
            if (class_exists($className)) {
                return Yii::createObject(['class' => $className]);
                break;
            }
        }
        return null;
    }

    public static function createTable($table, $columns, $tableOptions = null)
    {
        $db = new FDatabase();
        if (is_object($table))
            $table = FHtml::getTableName($table);
        $db->createTable($table, $columns, $tableOptions);
    }

    // auto assign full path to Image, File

    public static function dropTable($table)
    {
        $db = new FDatabase();
        if (is_object($table))
            $table = FHtml::getTableName($table);
        $db->dropTable($table);
    }

    public static function truncateTable($table)
    {
        $db = new FDatabase();
        if (is_object($table))
            $table = FHtml::getTableName($table);
        $db->truncateTable($table);
    }

    // build active query with complex Search Params

    public static function alterColumn($table, $column, $type = Schema::TYPE_STRING . '(1000)')
    {
        $db = new FDatabase();
        if (is_object($table))
            $table = FHtml::getTableName($table);
        $db->alterColumn($table, $column, $type);
    }

    public static function renameColumn($table, $column, $newName)
    {
        $db = new FDatabase();
        if (is_object($table))
            $table = FHtml::getTableName($table);
        $db->renameColumn($table, $column, $newName);
    }

    public static function getFieldLabel($model, $field, $isDisplaySetting = false)
    {
        if (empty($field))
            return '';

        $table = FHtml::getTableName($model);

        if (empty($table) || empty($model))
            return FHtml::t('common', BaseInflector::camel2words($field));

        if (is_string($model))
            $model = FHtml::getModel($model);

        $result = [];

        $fields = [];
        if (is_string($field))
            $fields = [$field];
        else if (is_array($field))
            $fields = $field;

        if (is_array($fields)) {
            foreach ($fields as $field1) {
                $result1 = '';
                if (isset($model) && FHtml::field_exists($model, 'attributeLabels()') && key_exists($field, $model->attributeLabels()))
                    $result1 = $model->attributeLabels()[$field1];

                if (empty($result1))
                    $result1 = FHtml::t(empty($table) ? 'common' : $table, BaseInflector::camel2words($field1));

                if ($isDisplaySetting) {
                    if (FModel::getRequiredFields($model, $field1))
                        $result1 .= "<span class='text-danger'>*</span>";
                    //$result1 .= FHtml::createLink('system/object-setting', ['object_type' => $table, 'meta_key' => $field1], BACKEND, ' <span class="glyphicon glyphicon-cog text-default small"></span>', '_blank', '');
                }

                $result[] = $result1;
            }
        }

        if (is_string($field) && !empty($result))
            return $result[0];

        return $result;
    }


    //2017/3/15
    public static function getComboArray($key, $table = null, $column = null, $isCache = true, $id_field = 'id', $name_field = 'name', $hasNull = true, $search_params = [], $limit = 0)
    {
        $data = self::getArray($key, $table, $column, $isCache, '', $search_params, $limit);

        if ($data != null && is_array($data) && !is_array($key)) {
            $data = FModel::arrayMap($data, $id_field, $name_field, $hasNull);
        }

        if (!is_array($data))
            $data = [];

        if (key_exists('', $data) && empty($data['']))
            $data[''] = FHtml::getNullValueText(); // FHtml::NULL_VALUE;

        $result = $data;
        return $result;
    }

    public static function getLookupArray($key, $table = '', $column = '', $isCache = true, $id_field = 'id', $name_field = 'name', $hasNull = true, $search_params = [], $limit = 0)
    {
        return static::getComboArray($key, $table, $column, $isCache, $id_field, $name_field, $hasNull, $search_params, $limit);
    }

    public static function getArrayForCombo($key, $table = '', $column = '', $isCache = true, $id_field = 'id', $name_field = 'name', $hasNull = true, $search_params = [], $limit = 0)
    {
        return self::getComboArray($key, $table, $column, $isCache, $id_field, $name_field, $hasNull, $search_params, $limit);
    }

    public static function getArrayKeyValues($data, $autoTranslated = true, $lang_category = 'common', $keys = ['id', 'name'], $id_fields = ['id', 'key', 'code'], $name_fields = ['name', 'text', 'title', 'value'])
    {
        return self::getKeyValueArray($data, $autoTranslated, $lang_category, $keys, $id_fields, $name_fields);
    }

    public static function getConstantArray($key, $table = null, $column = null)
    {
        $data = null;

        //special cases
        if ($column == 'lang' || $key == 'lang') {
            return $data = FHtml::applicationLangsArray(); // self::ARRAY_LANG;
        } else if ($column == 'modules' || $key == 'modules') {

            return $data = FHtml::getApplicationModulesComboArray();
        }
        //        else if ($column == 'object_type' || $key == 'object_type') {
        //            $data = FHtml::getApplicationObjectTypes();
        //        }
        else if ($column == 'timezone' || $key == 'timezone') {
            return $data = FHtml::getTimeZoneArray();
        } else if ($column == 'role' || $key == 'role' || $key == 'user.role' || "$table.$column" == 'user.role') {
            $currentRole = FHtml::getCurrentRole();
            $currentAction = FHtml::currentAction();
            if ($currentRole == \common\models\User::ROLE_ADMIN || !in_array($currentAction, ['create', 'update', 'profile'])) {
                $data = [
                    ['id' => \common\models\User::ROLE_ADMIN, 'name' => 'Admin'],
                    ['id' => \common\models\User::ROLE_MODERATOR, 'name' => 'Manager'],
                    ['id' => \common\models\User::ROLE_USER, 'name' => 'User']
                ];
            } else if ($currentRole == \common\models\User::ROLE_MODERATOR) {
                $data = [
                    ['id' => \common\models\User::ROLE_MODERATOR, 'name' => 'Manager'],
                    ['id' => \common\models\User::ROLE_USER, 'name' => 'User']
                ];
            } else {
                $data = [
                    ['id' => \common\models\User::ROLE_USER, 'name' => 'User']
                ];
            }
            return $data;
        } else if ($key == 'user.status' || $key == 'user_status' || ($table == 'user' && $column == 'status')) {

            $data = [
                ['id' => \common\models\User::STATUS_DELETED, 'name' => FHtml::STATUS_REJECTED],
                ['id' => \common\models\User::STATUS_ACTIVE, 'name' => FHtml::STATUS_APPROVED]
            ];

            return $data;
        } else if ($column == 'editor' || $key == 'editor') {

            return $data = self::ARRAY_EDITOR;
        } else if ($column == 'is_active' || (StringHelper::startsWith($column, 'is_'))) {
            return $data = [
                ['id' => '1', 'name' => 'Yes'],
                ['id' => '0', 'name' => 'No'],
                ['id' => '', 'name' => FHtml::getNullValueText()]
            ];
        } else if ($key == 'color' || $column == 'color') {

            return $data = self::ARRAY_COLOR;
        } else if ($key == 'gender' || $column == 'gender') {
            return $data = self::ARRAY_GENDER;
        } else if ($key == 'field_layout' || $column == 'field_layout') {
            return $data = self::ARRAY_FIELD_LAYOUT;
        } else if ($key == 'alignment' || $column == 'alignment') {

            return $data = self::ARRAY_ALIGNMENT;
        } else if ($key == 'grid_buttons' || $column == 'grid_buttons') {

            return $data = self::ARRAY_GRID_BUTTONS;
        } else if ($key == 'transition_speed' || $column == 'transition_speed') {

            return $data =
                self::ARRAY_TRANSITION_SPEED;
        } else if ($key == 'transition_type' || $column == 'transition_type') {

            return $data =
                self::ARRAY_TRANSITION_TYPE;
        } else if ($key == 'theme_color' || $key == '@theme_color' || $column == 'theme_color') {

            return $data = self::ARRAY_ADMIN_THEME;
        } else if ($key == 'portlet_style' || $key == '@portlet_style' || $column == 'portlet_style') {

            return $data = self::ARRAY_PORTLET_STYLE;
        } else if ($key == 'theme_style' || $key == '@theme_style' || $column == 'theme_style') {
            return $data = self::ARRAY_THEME_STYLE;
        } else if ($key == 'controls_alignment' || $key == '@controls_alignment' || $column == 'controls_alignment') {

            return $data = self::ARRAY_CONTROLS_ALIGNMENT;
        } else if ($key == 'buttons_style' || $key == '@buttons_style' || $column == 'buttons_style') {

            return $data = self::ARRAY_BUTTONS_STYLE;
        } else if ($column == 'dataType') {
            return $data = self::ARRAY_DBTYPE;
        } else if (($key == 'app_user' || $key == '@app_user' || $key == '@user' || $key == 'user') & $column == 'user_id') {
            $table = 'user';
            $key = '@user';
            $data = FHtml::getKeyValueArray($key, $table, $column);
        }
        return $data;
    }

    public static function getArray($key, $table = null, $column = null, $isCache = false, $select = '', $search_params = [], $limit = 0)
    {
        $isCache = false;
        $translated = true;
        $data = [];

        if ($isCache) {
            $dataCached = self::getCachedData($key, $table, $column);
            if (!empty($dataCached))
                return $dataCached;
        }

        if (is_array($key)) {
            $data = FHtml::getKeyValueArray($key, false, '', $table, $column);
            return $data;
        } else if (is_object($key) && is_string($table) && !empty($table)) {
            $column = $table;
            $model = $key;
            if (method_exists($model, 'getModelLookupArray'))
                return $model->getModelLookupArray($column);
            else if (method_exists($model, 'getLookupArray'))
                return $model::getLookupArray($column);
            $table = FHtml::getTableName($model);
        }

        if (StringHelper::startsWith($key, '.'))
            return [];

        //1. Check if $key is encoded json or keys
        $arr = FHtml::decode($key, false); //try to decode key to array
        if (!empty($arr) && is_array($arr)) {
            $data = FHtml::getKeyValueArray($arr, true, $table);
            return $data;
        }

        //2. Get special $key first
        $data = static::getConstantArray($key, $table, $column);
        if (isset($data) && is_array($data))
            return $data;

        $data = [];

        if (FHtml::isTableExisted($key) && !StringHelper::startsWith($key, '@')) {
            if (empty($table)) {
                $table = $key;
                $key = "@$table";
            } else if ($key == $table && !empty($column)) {
                $key = "$table.$column";
            } else if (empty($column)) {
                $column = $table;
                $table = $key;
                $key = "$table.$column";
            }
        }

        // Select Distinct
        $key = self::strReplace($key, ['\\' => '.']);

        if (strpos($key, '#') !== false) {
            if (strpos($key, '#') !== 0) {
                $arr = explode('#', $key);
                if (count($arr) > 0 && !empty($arr[0]))
                    $table = $arr[0];

                if (count($arr) > 1 && !empty($arr[1]))
                    $column = $arr[1];
            } else {
                $key = str_replace('#', '', $key);
                $arr = explode('.', $key);
                if (count($arr) > 0 && !empty($arr[0]))
                    $table = $arr[0];

                if (count($arr) > 1 && !empty($arr[1]))
                    $column = $arr[1];
            }

            if (FHtml::field_exists($table, $column)) {
                $sql = "SELECT DISTINCT `$column` As id, `$column` as name FROM $table ORDER BY id desc";
                $list = FHtml::findBySql($sql);

                $data = array_merge($data, FHtml::getKeyValueArray($list, false, $table));
            }

            //return $data;
        } else if (!empty($key) && strpos(strtolower($key), ':') != false) {
            $arr = explode(':', $key);
            if (!empty($arr[0]))
                $table = $arr[0];

            if (!empty($arr[1]) && count($arr) > 0)
                $column = $arr[1];
        } else if (!empty($key) && strpos(strtolower($key), '.') != false && empty($table) & empty($column)) {
            $arr = explode('.', $key);
            if (!empty($arr[0]))
                $table = $arr[0];

            if (!empty($arr[1]) && count($arr) > 0)
                $column = $arr[1];
        } else if (!empty($key) && StringHelper::startsWith(strtolower($key), '@') != false) {
            $table = str_replace('@', '', $key);
            $column = '';
        } else if (!empty($key) && StringHelper::startsWith(strtolower($key), 'select') != false) {
            $list = FHtml::findBySql($key);
            $data = FHtml::getKeyValueArray($list, false, $table);
        }

        if (empty($key))
            $key = !empty($column) ? "$table.$column" : $table;

        // 1. get from applications/application/config/params.php
        $moduleName = FHtml::getModelModule($table);
        $lookup = array_merge(static::LOOKUP, FConfig::getApplicationParams(true, true, false));

        if (!empty($lookup)) {
            $keys = [$key, "$table.$column", $table . '_' . $column, $moduleName . '_' . $column, "common.$column", "$moduleName.$column"];
            foreach ($keys as $id) {
                if (key_exists($id, $lookup)) {
                    $value = $lookup[$id];
                    $arr = self::getKeyValueArray($value, true, $table);
                    if (is_array($arr) && !empty($arr)) {
                        $data = ArrayHelper::merge($data, $arr);
                        return $data;
                    }
                }
            }
        }

        //2. Get From Application Helper, Module, Model
        $model1 = self::getModel($table);
        $module = FHtml::getModuleObject($moduleName);
        $application = FHtml::getApplicationHelper();
        $object_array = [$application, $module, $model1];
        $lookup = [];

        $hasData = false;
        foreach ($object_array as $model) {
            if ($hasData)
                break;
            if (isset($model)) {
                if (!empty($column) && method_exists($model, $column . 'Array')) {
                    $arr = $model::{$column . 'Array'}();
                    if (!empty($arr)) {
                        $arr = self::getKeyValueArray($arr, true, $table);
                        if (!empty($arr)) {
                            $data = ArrayHelper::merge($data, $arr);
                            $hasData = true;
                        }
                    }
                } else if (method_exists($model, 'getLookupArray')) { //Hung: recursive ?
                    $key1 = self::strReplace($key, ["$table." => '']);
                    $arr1 = array_merge([$key1], [$key]);
                    foreach ($arr1 as $arr1_key) {
                        if (empty($arr1_key))
                            continue;

                        $arr = $model::getLookupArray($arr1_key);

                        if (!empty($arr)) {
                            $auto_translated = is_array($arr);
                            $arr = self::getKeyValueArray($arr, $auto_translated, $table);
                            $data = array_merge($data, $arr);
                            $hasData = true;
                            break;
                        }
                    }
                } else if (self::field_exists($model, 'LOOKUP')) { //Hung: recursive ?
                    $lookup = array_merge($lookup, $model::LOOKUP);
                }
            }
        }

        if (!empty($lookup)) {
            foreach ($lookup as $id => $value) {
                if ($id == $key || $id == ($table . '.' . $column) || $id == ($table . '_' . $column) || $id == ($moduleName . '_' . $column) || $id == ('common.' . $column) || $id == ($moduleName . '.' . $column)) {
                    $arr = self::getKeyValueArray($value, true, $table);
                    if (is_array($arr) && !empty($arr)) {
                        $data = ArrayHelper::merge($data, $arr);
                        break;
                    }
                }
            }
        }

        // 3. get data from object_category, object_setting db table
        if ((!empty($key) && FHtml::isDBSettingsEnabled())
            || empty($column)   // get data from direct table
        ) {

            $tables = [$table];
            $arr = [];

            foreach ($tables as $table) {
                if (empty($table) || strpos($table, '.') !== false)
                    continue;

                $query = self::getQuery($key, $table, $column, 'object_category', $select, $search_params);
                if (isset($query) && is_object($query)) {

                    if ($limit > 0)
                        $query->limit($limit);

                    $arr = array_merge($arr, $query->all());

                    $arr_sample_item = FContent::getArrayItemValue($arr, 0);

                    $i = 0;
                    if (count($arr) > 0 && (is_object($arr_sample_item) || is_array($arr_sample_item))) { // if return ActiveQuery::find(), not Query::find()
                        $arr1 = [];

                        foreach ($arr as $arr_item) {
                            //load only is_active item
                            $is_active = FModel::getModelIsActive($arr_item, ['is_active']);
                            if (!$is_active)
                                continue;

                            $id = FModel::getModelId($arr_item, ['key', 'id', 'meta_key']);
                            $name = FModel::getModelName($arr_item, ['value', 'name', 'text', 'meta_value', 'title', 'username']);
                            $code = FModel::getModelCode($arr_item, ['code', 'id', 'meta_key']);
                            $index = FModel::getModelCode($arr_item, ['tree_index', 'index']);

                            if (!empty($code))
                                $id = $code;
                            if (empty($id))
                                $id = $name;

                            $arr1[] = ['id' => $id, 'name' => $name, 'text' => empty($code) ? "$name" : "$name ($code)", 'code' => $code];
                        }
                        $arr = $arr1;
                    }

                    $data = ArrayHelper::merge($data, $arr);
                }
            }
        }


        if ($isCache)
            self::saveCachedData($data, $key, $table, $column);

        return $data;
    }

    public static function getModelId($model, $fields = ['id', 'key', 'meta_key'])
    {
        return static::getModelField($model, $fields, null, 'getModelId');
    }

    public static function getModelName($model, $fields = ['value', 'name', 'text', 'meta_value', 'title', 'username'])
    {
        return static::getModelField($model, $fields, null, 'getModelName');
    }

    public static function getModelCode($model, $fields = ['code', 'id', 'meta_key'])
    {
        return static::getModelField($model, $fields, null, 'getModelCode');
    }

    public static function getModelIsActive($model, $fields = ['is_active'])
    {
        return static::getModelField($model, $fields, true, 'getModelIsActive');
    }

    public static function getModelField($model, $fields = [], $default_value = null, $method = '')
    {
        if (is_object($model) &&  !empty($method) && method_exists($model, $method))
            return $model->$method();

        return FHtml::getFieldValue($model, $fields, $default_value);
    }

    public static function findbySql($sql, $params = [])
    {
        try {
            return self::currentDb()->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $ex) {
            FHtml::addError($ex);
        }
        return false;
    }

    /**
     * @param string $db_config
     * @param string $table_name
     * @return Connection|object
     * @throws \yii\base\InvalidConfigException
     */
    public static function currentDb($db_config = '', $table_name = '')
    {
        if (empty($db_config))
            $arr = [FHtml::currentApplicationDatabase()];
        else
            $arr = [$db_config, FHtml::currentApplicationDatabase()];

        if (isset($table_name) && is_object($table_name)) {
            $table_name = FHtml::getTableName($table_name);
        }

        foreach ($arr as $arr_item) {
            if (empty($arr_item))
                continue;

            $db = Yii::$app->get($arr_item, false);
            if (isset($db)) {
                if (empty($table_name) || FHtml::isTableExisted($table_name, $db)) {
                    $db->enableSchemaCache = true;
                    return $db;
                }
            }
        }

        $db = Yii::$app->get(FHtml::CONFIG_DB, true);
        $db->enableSchemaCache = true;
        return $db;
    }

    public static function refreshSchema($table = [])
    {

        if (empty($table)) {

            self::currentDb()->schema->refresh();
            return;
        }
        if (is_string($table))
            $table = [$table];
        foreach ($table as $table1) {
            self::currentDb()->schema->refreshTableSchema($table1);
        }
    }

    public static function getKeyValueArray($data, $autoTranslated = true, $lang_category = 'common', $keys = ['id', 'name'], $id_fields = ['id', 'key', 'code'], $name_fields = ['name', 'text', 'title', 'value'])
    {
        if (empty($lang_category))
            $lang_category = 'common';

        $result = [];

        $id_field = '';
        $name_field = '';
        if (is_array($keys)) {
            $id_field = $keys[0];
            $name_field = $keys[1];
        } else if (!empty($keys)) {
            $id_field = 'id';
            $name_field = 'name';
        } else {
            $id_field = '';
            $name_field = '';
        }

        if (is_string($data)) {
            $data = self::getArray($data);
        }

        if (empty($id_fields))
            $id_fields = ['id', 'key', 'code'];
        if (empty($name_fields))
            $name_fields = ['name', 'text', 'title', 'value'];

        if (is_array($data)) {
            $result = [];
            $associate = true;
            $array_index = 0;

            foreach ($data as $id => $item) {
                // Hung: WHY ??
                if (($id !== $array_index && is_string($item))) {
                    $associate = false;
                    //return $data; // if not assciatve array then return immediately ???
                }

                if (is_numeric($item)) {
                    if (empty($item))
                        $item = FHtml::STATUS_NEW;
                    else if ($item == 1)
                        $item = FHtml::STATUS_APPROVED;
                    $associate = true;
                }

                if (is_array($item) || is_object($item)) {
                    $id_value = FHtml::getFieldValue($item, $id_fields, null);
                    $name_value = FHtml::getFieldValue($item, $name_fields, null);

                    if (empty($id_value) && empty($name_value) && is_array($item) && !key_exists('id', $item)) {
                        if (!ArrayHelper::isAssociative($item)) {
                            $id_value = array_keys($item)[0];
                            $name_value = array_values($item)[0];
                        } else {
                            $id_value = $item[0];
                            $name_value = $item[1];
                        }
                    }
                } else {
                    $text = ($autoTranslated) ? FHtml::t($lang_category, $item) : $item;

                    if (!$associate) {
                        $id_value = strtolower($id);
                        $name_value = $text;
                    } else {
                        $id_value = strtolower($item);
                        $name_value = $text;
                    }
                }

                if (!empty($id_field))
                    $result[] = [$id_field => $id_value, $name_field => $name_value, 'translated' => $autoTranslated];
                else {
                    $result = FHtml::arrayMerge($result, ["$id_value" => $name_value]); //Hung: can not use array_merge because we want to keep array index as original number (dont want rerrange index)
                }

                $array_index += 1;
            }

            $data = $result;
        }

        return $data;
    }

    public static function getQuery($key, $table = '', $column = '', $lookup_table = 'object_setting', $select = '', $search_params = [], $order_by = [], $limit = 0)
    {
        if ($table == 'object_type' || $key == 'object_type' || $column == 'object_type')
            return null;

        $sql_select = '';
        $sql_table = '';
        //$table = str_replace('@', '', $table);
        if (StringHelper::startsWith($key, '@')) {

            $key = substr($key, 1);
            $arr = explode(',', $key);
            $sql_table = $arr[0];

            if (!FHtml::isTableExisted($sql_table))
                return null;

            $model = FHtml::createModel($sql_table);

            if ($key == 'user') {
                $id_column = 'id';
                $name_column = 'name';
            } else if ($key == 'app_user') {
                $id_column = 'id';
                $name_column = 'name';
                $table = 'user';
            } else {
                $id_column = isset($arr[1]) ? $arr[1] : 'id';
                $name_column = isset($arr[2]) ? $arr[2] : '';
                if (empty($name_column)) {
                    $arr = ['name', 'title', 'username'];
                    foreach ($arr as $name1) {
                        if (FHtml::field_exists($model, $name1)) {
                            $name_column = $name1;
                            break;
                        }
                    }
                }
                if (empty($name_column))
                    return [];
            }
            $sql_select = !empty($select) ? $select : '*, ' . $id_column . ' as id, ' . $name_column . ' as name' . ', ' . $name_column . ' as text';
            $query = new FQuery();

            $query->select($sql_select)
                ->from($sql_table);

            if (!empty($search_params))
                $query->where($search_params);

            if (isset($model) && FHtml::field_exists($model, 'is_active'))
                $query->andWhere(['is_active' => 1]);

            $query->orderBy(!empty($order_by) ? $order_by : [
                $id_column => SORT_ASC,
            ]);

            return $data = $query;
        } else if (StringHelper::endsWith($column, '_userid')) {
            $sql_select = !empty($select) ? $select : 'id, name AS name';
            $sql_table = self::TABLE_USER;

            if (!FHtml::isTableExisted($sql_table))
                return null;

            $query = new FQuery;
            $query->select($sql_select)
                ->from($sql_table);

            $query->where(!empty($search_params) ? $search_params : ['status' => FHtml::USER_STATUS_ACTIVE]);

            $query->orderBy(!empty($order_by) ? $order_by : [
                'name' => SORT_ASC,
            ]);
            return $data = $query;
        } else if (StringHelper::endsWith($column, '_user')) {
            $sql_select = !empty($select) ? $select : 'id as id, name AS name';
            $sql_table = self::TABLE_USER;

            if (!FHtml::isTableExisted($sql_table))
                return null;

            $query = new FQuery;
            $query->select($sql_select)
                ->from($sql_table);

            $query->where(!empty($search_params) ? $search_params : ['status' => FHtml::USER_STATUS_ACTIVE]);

            $query->orderBy(!empty($order_by) ? $order_by : [
                'name' => SORT_ASC,
            ]);
            return $data = $query;
        } else if (($table == 'product' && $column == '') || ($key == '@product')) {
            $sql_select = !empty($select) ? $select : 'id as id, name AS text';
            $sql_table = 'product';

            if (!FHtml::isTableExisted($sql_table))
                return null;

            $query = new FQuery;
            $query->select($sql_select)
                ->from($sql_table);

            $query->where(!empty($search_params) ? $search_params : ['is_active' => true]);

            if ($limit > 0)
                $query->limit($limit);

            $query->orderBy(!empty($order_by) ? $order_by : [
                'id' => SORT_ASC,
            ]);
            return $data = $query;
        } else if (in_array($table, ['object-category', 'category', 'object_category']) || in_array($key, ['object-category', 'category', 'object_category', 'category_id', 'categoryid']) || in_array($column, ['categoryid', 'category_id']) || strpos($key, 'category_id') !== false) {
            $sql_table = 'object_category';

            if (!FHtml::isTableExisted($sql_table))
                return null;

            $query = models\ObjectCategory::find();
            $moduleName = FHtml::getModelModule($table);

            $search_params = ['object_type' => $table];

            if (empty($table) || $table == self::TABLE_CATEGORIES)
                $query = $query->where(!empty($search_params) ? $search_params : ['OR', ['object_type' => $moduleName], ['object_type' => $table], ['object_type' => ''], ['object_type' => FHtml::OBJECT_TYPE_DEFAULT]]);
            else
                $query = $query->where(!empty($search_params) ? $search_params : ['OR', ['object_type' => $moduleName], ['object_type' => $table], ['object_type' => ''], ['object_type' => FHtml::OBJECT_TYPE_DEFAULT]]);

            $query->orderBy(!empty($order_by) ? $order_by : [
                'sort_order' => SORT_ASC, 'name' => SORT_ASC
            ]);
            return $query;
        } else if ($column == 'parent_id') {
            if (!FHtml::isTableExisted($table))
                return null;

            $model = FHtml::createModel($table);
            if (isset($model)) {
                $query = $model::find();
                $query->where(!empty($search_params) ? $search_params : ['is_active' => 1]);

                $query->orderBy(!empty($order_by) ? $order_by : [
                    'name' => SORT_ASC
                ]);
                return $query;
            }
        } else { // Get from Meta Setting table

            if ($lookup_table == 'object_category') {
                $sql_table = 'object_category';

                if (!FHtml::isTableExisted($sql_table)) {
                    return null;
                }

                $query = models\ObjectCategory::find();

                if (empty($search_params)) {
                    if (!empty($column))
                        $search_params = ['object_type' => "$table.$column", 'is_active' => 1];
                    else
                        $search_params = ['object_type' => "$key", 'is_active' => 1];

                    if ($key != $table && $key != "$table.$column") {
                        $search_params = ['OR', ['object_type' => "$key", 'is_active' => 1], $search_params];
                    }
                }

                $query->where($search_params);

                $query->orderBy(!empty($order_by) ? $order_by : [
                    'sort_order' => SORT_ASC,
                    'name' => SORT_ASC,
                ]);

                return $data = $query;
            } else {

                $sql_table = 'object_setting';

                if (!FHtml::isTableExisted($sql_table))
                    return null;

                $query = ObjectSetting::find();
                $query->where(!empty($search_params) ? $search_params : ['OR', ['object_type' => $table, 'meta_key' => $column, 'is_active' => true], ['meta_key' => "$table.$column", 'is_active' => true]]);

                $query->orderBy(!empty($order_by) ? $order_by : [
                    'meta_key' => SORT_ASC,
                    'sort_order' => SORT_ASC,
                ]);

                return $data = $query;
            }
        }

        return null;
    }

    public static function buildQuery($query, $search_params)
    {
        if (is_string($search_params)) {
            $query = $query->where($search_params);
        } else if (is_array($search_params)) {
            foreach ($search_params as $field => $value) {
                $query = $query->andWhere(self::buildQueryParams($field, $value));
            }
        }

        return $query;
    }

    public static function getRelatedViewModels($object_type, $object_id, $object2_type, $relation_type = FHtml::RELATION_MANY_MANY)
    {
        $models = self::getRelatedModels($object_type, $object_id, $object2_type, $relation_type);
        return self::toViewModel($models);
    }

    public static function getRelatedModels($object_type, $object_id, $object2_type, $relation_type = FHtml::RELATION_MANY_MANY, $display_fields = [])
    {
        $arr = [];
        if (is_string($object_id))
            $arr = explode(',', $object_id);
        else if (is_array($object_id))
            $arr = $object_id;

        $destination = self::createModel($object2_type);
        $result = [];
        if ($relation_type == FHtml::RELATION_FOREIGN_KEY) {
            if (!empty($arr))
                $result = $destination::find()->where(['in', $destination->primaryKeyField(), $arr])->all();
        } else if ($relation_type == FHtml::RELATION_ONE_MANY) {
            if (!empty($arr))
                $result = $destination::find()->where(['in', 'object_id', $arr])->where(['object_type' => $object_type])->all();
        } else if (!empty($relation_type)) {
            $arr = models\ObjectRelation::find()->where(['object_type' => $object_type, 'object_id' => $object_id, 'object2_type' => $object2_type, 'relation_type' => $relation_type])->select('object2_id')->orderBy('sort_order asc, created_date desc')->asArray()->all();
            $arr = ArrayHelper::getColumn($arr, 'object2_id');
            if (!empty($arr))
                $result = $destination::find()->where(['in', $destination->primaryKeyField(), $arr])->all();
        } else {
            $ids_array = self::getRelatedModelsIDArray($object_type, $object_id, $object2_type, $relation_type);
            if (!empty($ids_array)) {
                $result = $destination::find()->where(['in', $destination->primaryKeyField(), $ids_array])->all();
            }
        }

        if (isset($result) && !empty($display_fields)) {
            foreach ($result as $item) {
                if (FHtml::field_exists($item, 'setFields')) {
                    $item->setFields($display_fields);
                }
            }
        }

        return $result;
    }

    public static function getRelatedModelsIDArray($object_type, $object_id, $object2_type, $relation_type = FHtml::RELATION_MANY_MANY, $display_fields = [])
    {
        $arr = [];
        if (is_string($object_id) && !self::is_numeric($object_id))
            $arr = explode(',', $object_id);
        else if (is_array($object_id)) {
            $arr = $object_id;
            $object_id = implode(',', $arr);
        }

        if ($relation_type == FHtml::RELATION_ONE_MANY) {
        } else {

            $relation_condition = empty($relation_type) ? "relation_type = '' or relation_type is null" : "relation_type = '$relation_type'";
            $list = models\ObjectRelation::find()->where($relation_condition);
            if (empty($object_id))
                return [];

            $list = $list->andWhere("object_type = '$object_type' and object_id in ($object_id) and object2_type = '$object2_type'")->andWhere($relation_condition)->select('object2_id')->orderBy('sort_order asc, created_date desc')->asArray()->all();
            $arr = ArrayHelper::getColumn($list, 'object2_id');
            if (empty($relation_type)) {
                $list = models\ObjectRelation::find()->where($relation_condition)->andWhere("object2_type = '$object_type' and object2_id in ($object_id) and object_type = '$object2_type'")->select('object_id')->orderBy('sort_order asc, created_date desc')->asArray()->all();
                $arr2 = ArrayHelper::getColumn($list, 'object_id');
                $arr = ArrayHelper::merge($arr, $arr2);
            }
        }
        return $arr;
    }

    public static function getRelatedDataProvider($object_type, $object_id, $object2_type, $relation_type = FHtml::RELATION_MANY_MANY, $display_fields = [])
    {
        $arr = [];
        if (is_string($object_id))
            $arr = explode(',', $object_id);
        else if (is_array($object_id))
            $arr = $object_id;

        $source = self::createModel($object_type);
        $destination = self::createModel($object2_type);
        $result = [];
        if ($relation_type == FHtml::RELATION_ONE_MANY) {
        } else {
            $ids_array = self::getRelatedModelsIDArray($object_type, $object_id, $object2_type, $relation_type);
            $ids = !empty($ids_array) ? implode(',', $ids_array) : '-1';
            return self::getDataProvider($object2_type, "id in ($ids)");
        }
    }

    public static function toViewModel($models)
    {
        if (is_array($models)) {
            $viewModels = [];
            foreach ($models as $dataitem) {
                $viewModels[] = $dataitem->toViewModel();
            }

            return $viewModels;
        }
        if (is_object($models)) {

            return $models->toViewModel();
        }
    }

    public static function queryOne($sql, $params = [])
    {
        return self::createCommand($sql, $params)->queryOne();
    }

    public static function createCommand($sql = '', $params = [], $db = null)
    {
        try {
            if (!is_string($sql)) {
                $db = $sql;
                $sql = '';
            }

            if (!is_object($db))
                $db = FModel::currentDb($db);

            $command = $db->createCommand($sql);
            if (!empty($params) && !isset($command))
                $command = $command->bindValues($params);

            return $command;
        } catch (\yii\db\Exception $ex) {
            FHtml::addError($ex);
        }
        return null;
    }

    public static function executeSql($sql, $params = [], $db = null)
    {
        try {
            return self::createCommand($sql, $params, $db)->execute();
        } catch (\yii\db\Exception $ex) {
            FHtml::addError($ex);
        }

        return false;
    }

    public static function executeSqlQueryAll($sql, $params = [], $db = null)
    {
        try {
            return self::createCommand($sql, $params, $db)->queryAll();
        } catch (\yii\db\Exception $ex) {
            FHtml::addError($ex);
        }

        return [];
    }

    public static function executeSqlQueryOne($sql, $params = [], $db = null)
    {
        try {
            return self::createCommand($sql, $params, $db)->queryOne();
        } catch (\yii\db\Exception $ex) {
            FHtml::addError($ex);
        }

        return null;
    }

    public static function executeSqlUpdate($object_type, $condition, $fields, $db = null)
    {
        if (is_object($object_type)) {
            $fields = $condition;
            $model = $object_type;
            $condition = ["id" => FHtml::getFieldValue($model, 'id')];
            $object_type = FHtml::getTableName($model);
        } else if (is_array($object_type)) {
            $ids_array = [];
            $model_array = $object_type;
            foreach ($model_array as $model) {
                $object_type = FHtml::getTableName($model);
                $ids_array[] = FHtml::getFieldValue($model, 'id');
            }
            $condition = "id in (" . implode(', ', $ids_array) . ")";
        }

        if (empty($fields))
            return false;

        if (!FHtml::isTableExisted($object_type, $db))
            return false;

        if (is_array($condition)) {
            $condition1 = [];
            foreach ($condition as $key => $value) {
                $condition1[] = "$key = '$value'";
            }
            $condition1 = implode(' AND ', $condition1);
        } else {
            $condition1 = $condition;
        }

        if (is_array($fields)) {
            $fields1 = [];
            foreach ($fields as $key => $value) {
                if (empty($value) && !is_numeric($value))
                    $fields1[] = "$key = null";
                else
                    $fields1[] = "$key = '$value'";
            }

            $fields1 = implode(' , ', $fields1);
        } else {
            $fields1 = $fields;
        }

        $sql = "UPDATE $object_type SET $fields1 WHERE $condition1";
        return FModel::executeSql($sql, [], $db);
    }

    public static function executeFileSql($file_sql, $params = [], $db = null)
    {
        try {
            if (!is_file($file_sql)) {
                FHtml::addError("Execute File SQL: File $file_sql does not exist !");
                return false;
            }

            $sql = FFile::readFile($file_sql);
            if (empty($sql)) {
                return false;
            }

            $value = self::createCommand($sql, $params, $db)->execute();
            return $value;
        } catch (\yii\db\Exception $ex) {
            FHtml::addError($ex);
            return false;
        }
    }

    public static function setupTable($table, $checkExisted = true)
    {
        if ($checkExisted && FHtml::isTableExisted($table))
            return true;

        $files = [];
        $rootFolder = FHtml::getRootFolder();
        $module = FHtml::getModelModule($table);
        $application_id = FHtml::currentApplicationId();

        $files[] = "$rootFolder\\applications\\$application_id\\setup\\$table.sql";
        $files[] = "$rootFolder\\setup\\tables\\$table.sql";
        $files[] = "$rootFolder\\setup\\$table.sql";

        $files[] = "$rootFolder\\modules\\$module\\setup\\$table.sql";
        //var_dump($files); die;
        foreach ($files as $file) {
            $file = FFile::getFullFileName($file);
            if (is_file($file)) {
                return FHtml::executeFileSql($file);
            }
        }

        return false;
    }

    public static function queryScalar($sql, $params = [], $db = null)
    {
        return self::createCommand($sql, $params, $db)->queryScalar();
    }

    // INSERT (table name, column values)
    public static function insertTable($table, $params = [], $db = null)
    {
        return self::createCommand('', $params, $db)->insert($table, $params)->execute();
    }

    // UPDATE (table name, column values, condition)
    public static function updateTable($table, $params = [], $condition = '', $db = null)
    {
        return self::createCommand($db)->update($table, $params, $condition)->execute();
    }

    // DELETE (table name, condition)
    public static function deleteTable($table, $condition = '', $db = null)
    {
        return self::createCommand($db)->delete($table, $condition)->execute();
    }

    public static function getSqlValue($content, $quote = '')
    {
        if (!is_string($content))
            return $content;

        return $quote . addcslashes(str_replace("'", "''", $content), "\000\n\r\\\032") . $quote;
    }

    public static function getSqlParam($content, $quote = '')
    {
        return self::getSqlValue($content, $quote);
    }


    // table name, column names, column values
    public static function batchInsert($table, $columns, $values = '', $db = null)
    {
        return self::createCommand($db)->batchInsert($table, $columns, $values)->execute();
    }

    public static function executeSqlBatch($sqls = [], $is_batch = true, $db = null)
    {
        if (!is_object($db))
            $db = self::currentDb($db);

        if (!isset($db))
            return false;

        $result = [];

        if ($is_batch) {
            $transaction = $db->beginTransaction();
            try {
                foreach ($sqls as $sql) {
                    $db->createCommand($sql)->execute();
                    $result[] = $sql;
                }

                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                FHtml::addError($e);
                return false;
            } catch (\Throwable $e) {
                $transaction->rollBack();
                FHtml::addError($e);
                return false;
            }
        } else {
            $sql_string = implode(';', $sqls);
            $result = $db->createCommand($sql_string)->execute();
        }

        return $result;
    }

    public static function getModelPrimaryKeyValue($model)
    {
        if (!is_object($model))
            return null;

        return static::getFieldValue($model, $model::primaryKey());
    }

    /**
     * @param BaseDataObject $models
     * @return array|object
     */
    public static function getTranslatedModels($models, $fields = null)
    {
        $lang = FConfig::currentLang();

        $tableName = "";
        $model = null;
        if (empty($models) || !is_array($models))
            return $models;

        if (isset($models) && !empty($models)) {
            $model = $models[0];
            $tableName = FHtml::getTableName($model);
        }

        if (!isset($models) || empty($tableName) || !FHtml::isDBLanguagesEnabled($model) || ($lang == FHtml::defaultLang())) {
            if (!empty($fields)) {
                $result = [];
                foreach ($models as $model) {
                    if (is_object($model))
                        $model->setFields($fields);
                    $result[] = $model;
                }
                $models = $result;
            }
            return $models;
        }

        $field_translations = $model->getTranslationsField();

        if (!empty($field_translations)) {
            //medhod 1
            $result = [];
            foreach ($models as $model) {
                $result[] = FModel::getTranslatedModel($model, '', $fields);
            }
            return $result;
        }

        //method 2
        if (FHtml::isTableExisted(FHtml::TABLE_TRANSLATIONS)) {
            $ids_models = [];
            foreach ($models as $model) {
                $id = is_array($model) ? $model['id'] : $model->id;
                $ids_models[] = $id;
            }

            $translates = ObjectTranslation::findAll(['lang' => $lang, 'object_id' => $ids_models, 'object_type' => $tableName]);

            if (empty($translates))
                return $models;

            $ids_translate = array_column((array) $translates, "object_id");
            $translates = array_combine($ids_translate, $translates);

            //get Translated content if any
            $result = [];
            foreach ($models as $model) {
                $id = is_array($model) ? $model['id'] : $model->id;
                if (in_array($id, $ids_translate)) {
                    $content = is_array($model) ? $translates[$id]['content'] : $translates[$id]->content;
                    $model = self::getTranslatedModel($model, $content);
                }
                if (!empty($fields) && is_object($model))
                    $model->setFields($fields);

                $result[] = $model;
            }

            return $result;
        }

        return $models;
    }

    public static function getTranslatedContent($model)
    {
    }
    /**
     * @param  BaseDataObject $model
     * Method update data to table translation
     */
    public static function saveTranslatedModel($model, $lang = '', $isSave = true)
    {
        if (!isset($model))
            return false;

        if (empty($lang))
            $lang = FHtml::currentLang();

        //get value from original attributes && custom attributes
        $attributes = array_merge($model->getAttributes(), $model->getObjectAttributesArray());
        $not_languagued_attributes = array_unique(array_merge($model->getNotTranslatedFields(), $model->getDefaultNotTranslatedFields()));
        $table_name = $model->getTableName();

        /* Update translation */
        $set_sql = "";
        $id_field = 'id';
        $id_value = $model->getPrimaryKeyValue();
        if (empty($id_value) || empty($table_name) || empty($id_field))
            return null;

        $content_arr = [];
        foreach ($attributes as $attribute => $value) {
            if (!in_array($attribute, $not_languagued_attributes))
                $content_arr = array_merge($content_arr, [$attribute => $value]);
        }

        //$translated_content = htmlentities(FHtml::encode($content_arr), ENT_COMPAT | ENT_QUOTES | ENT_HTML5 | ENT_IGNORE);
        $translated_content = FHtml::encode($content_arr);

        //update orginal model by SQL Update
        foreach ($not_languagued_attributes as $field) {
            if ($field == $id_field) {
                continue;
            }

            if (FHtml::field_exists($model, $field)) {
                $value = is_array($model->{$field}) ? FHtml::encode($model->{$field}) : $model->{$field};

                if (StringHelper::startsWith($field, 'is_') || StringHelper::startsWith($field, 'modified_') || StringHelper::startsWith($field, 'created_') || StringHelper::endsWith($field, '_count')) {
                    if ($value != '') {
                        $set_sql .= $field . " = '" . FHtml::getSqlValue($value) . "', ";
                        $model->{$field} = FHtml::getSqlValue($value);
                    }
                } else {
                    $set_sql .= $field . " = '" . FHtml::getSqlValue($value) . "', ";
                    //$model->{$field} = FHtml::getSqlValue($value);
                }
            }
        }

        //if model has Translations field, then save translated content to original model directly
        $field_translations = $model->getTranslationsField();
        if (!empty($field_translations) && $model->field_exists($field_translations)) {
            $translated_content_array = $model->{$field_translations};
            if (!is_array($translated_content_array))
                $translated_content_array = [];

            $translated_content_array[$lang] = $translated_content;
            $set_sql .= $field_translations . " = '" . FHtml::getSqlValue(FHtml::encode($translated_content_array)) . "', ";
            $model->{$field_translations} = FHtml::getSqlValue(FHtml::encode($translated_content_array));
        }

        $set_sql =  trim($set_sql, ", ");
        $save = true;

        //save not translated fields
        if (!empty($set_sql) && $isSave) {
            $sql = "UPDATE " . $table_name . " SET $set_sql WHERE $id_field = " . $id_value;
            $save = $model->updateCommand($model, $sql);
            //$save = $model->save(); //recursive
        }

        //if not save translated content before
        if (empty($field_translations) && FHtml::isTableExisted(FModel::TABLE_TRANSLATIONS)) {

            $translated = ObjectTranslation::findUnique(['lang' => $lang, 'object_id' => $id_value, 'object_type' => $table_name, 'application_id' => FHtml::currentApplicationCode()]);

            if (!isset($translated)) {
                $translated = new ObjectTranslation();
                $translated->object_type = $table_name;
                $translated->object_id = $id_value;
                $translated->application_id = FHtml::currentApplicationCode();
                $translated->lang = $lang;
            }

            $translated->content = $translated_content;
            $save = $translated->save();

            if (!$save) {
                FHtml::addError($translated->errors);
            }
        }

        return $save;

        /** @var ObjectTranslation $translate */
    }

    public static function getTranslatedModel($model, $content = '', $fields = null, $lang = '')
    {
        if (is_string($fields)) {
            $lang = $fields;
            $fields = [];
        }

        if (empty($lang))
            $lang = FHtml::currentLang();

        if (isset($model) && FHtml::isDBLanguagesEnabled($model) && method_exists($model, 'getTranslatedModel') && ($lang !== FHtml::defaultLang())) {
            //do
            $not_languagued_attributes = array_unique(array_merge($model->getNotTranslatedFields(), $model->getDefaultNotTranslatedFields()));

            if (empty($content)) {
                $content = $model->getTranslatedContent($lang);
            }

            if (!empty($content)) {

                if (!is_array($content)) {
                    $content = html_entity_decode($content, ENT_COMPAT | ENT_QUOTES | ENT_HTML5 | ENT_IGNORE);
                    //$content = self::strReplace($content, ['&period;' => '.', '&lbrack;' => '[', '&rsqb;' => ']', '"""' => '"', '&bsol;' => '\\', '&quot;' => '"', '&lbrace;' => '{', '&comma;' => ',', '&rcub;' => '}', '&colon;' => ':', '&lowbar;' => '_']);

                    $content = trim($content, "\"");
                    $arr = FHtml::decode($content, true);
                } else {
                    $arr = $content;
                }

                if (is_array($arr) && !empty($arr)) {
                    foreach ($arr as $field => $value) {
                        if (in_array($field, $not_languagued_attributes))
                            continue;

                        if (!empty($value) && FHtml::is_json($value) && $field != 'category_id') {
                            $value = FHtml::decode($value);
                        }
                        if (!empty($value)) {
                            $model = FHtml::setFieldValue($model, $field, $value);
                        }
                    }
                }
            }
        }

        if (!empty($fields) && isset($model) && is_object($model))
            $model->setFields($fields);

        return $model;
    }

    public static function getPageModelsList($object_type = '', $search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $display_fields = [])
    {
        $page_size1 = FHtml::getRequestParam(['per-page', 'page_size']);
        $page1 = FHtml::getRequestParam('page');

        if (!empty($page_size1))
            $page_size = $page_size1;
        if (!empty($page1))
            $page = $page1;

        $model = static::createModel($object_type);

        $search_params = FModel::buildSearchParams($model, $search_params);

        $order_by = FModel::buildOrderBy($model, $order_by);

        return self::getModelsList($model, $search_params, $order_by, $page_size, $page, $isCached, true, $display_fields);
    }

    public static function buildOrderBy($model, $order_by = [])
    {
        if (!empty($order_by))
            return $order_by;

        $order_by = FModel::getOrderBy($model);

        return $order_by;
    }

    public static function buildSearchParams($model, $search_params = '', $load_active_only = false)
    {
        $category_id = FHtml::getRequestParam(['category_id', 'category']);
        $keyword = FHtml::getRequestParam(['keyword', 'k']);
        $search_params = empty($search_params) ? [] : $search_params;
        if (!empty($search_params))
            return $search_params;

        if (!empty($category_id)) {
            if (self::field_exists($model, 'category_id')) {
                if (empty($search_params)) {
                    $search_params = ['OR', ['category_id' => $category_id], ['LIKE', 'category_id', ",$category_id,"]];
                } else {
                    if (is_array($search_params))
                        $search_params = ArrayHelper::merge($search_params, ['OR', ['category_id' => $category_id], ['LIKE', 'category_id', ",$category_id,"]]);
                    else if (is_string($search_params)) {
                        if (empty($search_params))
                            $search_params = "(1 = 1)";
                        $search_params .= " AND (category_id LIKE '%,$category_id,%' OR category_id='$category_id')";
                    }
                }
            }
        }

        if (!empty($keyword)) {
            $arr = ['name', 'overview', 'content', 'description'];

            if (is_string($search_params)) {
                $search_params1 = "";
                foreach ($arr as $field) {
                    if (self::field_exists($model, $field)) {
                        $search_params1 .= " $field LIKE '%$keyword%' OR ";
                    }
                }
                if (!empty($search_params1))
                    $search_params .= " AND (" . $search_params1 . " 1=0)";
            } else if (is_array($search_params)) {
                $search_params1 = ['OR'];
                foreach ($arr as $field) {
                    if (self::field_exists($model, $field)) {
                        $search_params1[] = ['LIKE', $field, $keyword];
                    }
                }
                if (count($search_params1) > 1)
                    $search_params = ['AND', $search_params, $search_params1];
            }
        }

        if ($load_active_only) {
            if (self::field_exists($model, 'is_active')) {
                if (empty($search_params)) {
                    $search_params = ['is_active' => 1];
                } else {
                    if (is_array($search_params))
                        $search_params = ArrayHelper::merge($search_params, ['is_active' => 1]);
                    else if (is_string($search_params))
                        $search_params = $search_params . ' AND is_active = 1';
                }
            }
        }

        //FHtml::var_dump($search_params);  die;
        return $search_params;
    }

    // Get About

    public static function getModelsForAPI($object_type, $search_params = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $folder = '', $displayFields = [])
    {
        if (empty($folder))
            $folder = str_replace('_', '-', $object_type);

        $list = self::getModelsList($object_type, $search_params, $order_by, $page_size, $page, $isCached);

        return isset($list) ? FHtml::prepareDataForAPI($list->models, $folder, $displayFields) : null;
    }

    public static function getObjectSettings($object_type, $key, $isCached = false, $displayFields = [])
    {
        $list = self::getModelsList(self::TABLE_OBJECT_SETTING, ['is_active' => 1, 'object_type' => $object_type, 'meta_key' => $key], 'sort_order asc');
        return isset($list) ? $list->models : null;
    }

    public static function getFieldArray($model, $field)
    {
        try {
            if (isset($model)) {
                if (is_string($field)) {
                    if (strpos(',', $field) === false)
                        $arr[] = $field;
                    else
                        $arr = explode(',', $field);
                } else if (is_array($field)) {
                    $arr = $field;
                }
                $result = [];
                foreach ($arr as $field1) {
                    if (self::field_exists($model, $field1))
                        $result = ArrayHelper::merge($result, [$field1 => $model[$field1]]);
                }
                return $result;
            } else
                return [];
        } catch (Exception $e) {
            return [];
        }
    }

    public static function increaseFieldValues($model, $arrays, $value = 1)
    {
        if (!isset($arrays) || empty($arrays))
            return $model;

        if (is_string($arrays))
            $arrays = explode(',', $arrays);

        if (ArrayHelper::isIndexed($arrays)) {
            foreach ($arrays as $field) {
                if (self::field_exists($model, $field))
                    $model[$field] = $model[$field] + $value;
            }
        } else if (ArrayHelper::isAssociative($arrays)) {
            foreach ($arrays as $field => $value1) {
                if (self::field_exists($model, $field))
                    $model[$field] = $model[$field] + $value1;
            }
        }

        return $model;
    }

    //HungHX: 20160814
    public static function refreshCache($key = '')
    {
        $cache = FHtml::Cache();

        if (!empty($cache)) {
            $cache->flush();
        }

        $application_id = FHtml::currentApplicationId();
        FHtml::DestroySession("$application_id@backendMenu");

        FConfig::clearCacheLanguageFileContent();
    }

    public static function getTableNames()
    {
        return self::currentDb()->getSchema()->getTableNames();
    }

    public static function Db()
    {
        return \Yii::$app->db;
    }

    public static function getObjectColumn($object_type, $column)
    {
        $items = self::getObjectColumns($object_type);
        if (isset($items)) {
            foreach ($items as $item) {
                if (strtolower($item->name) == strtolower($column))
                    return $item;
            }
        }
        return null;
    }

    public static function getObjectColumns($object_type)
    {
        if (!FHtml::isTableExisted('settings_schema'))
            return null;

        $key = $object_type . '::Columns';
        $items = self::getCachedData($key);
        if (isset($items))
            return $items;

        $items = FHtml::getModels('settings_schema', ['object_type' => $object_type], 'sort_order asc', -1, 1, true, false);
        self::saveCachedData($items, $key);

        return $items;
    }

    public static function saveModel($model, $post = null, $arrays = [])
    {
        $post = isset($post) ? $post : Yii::$app->request->post();
        $model = is_object($model) ? $model : self::createModel($model);

        $saveType = isset($_POST['saveType']) ? $_POST['saveType'] : '';

        if ($saveType == 'search')
            return false;

        if (!empty($post) && isset($model) && $model->load($post)) {
            self::prepareModel($model, null, $arrays);
            if ($model->validate()) {
                if (!$model->save()) {
                    FHtml::addError($model->errors);
                    return false;
                }
                FHtml::saveUploadedFiles($model);
            } else {
                FHtml::addError($model->errors);
                $model = null;
                return false;
            }
        }

        return $model;
    }

    public static function saveModels($object_type, $condition = [], $field_values = [])
    {
        if (is_array($object_type)) {
            $models = $object_type;
            $field_values = $condition;
        } else if (is_object($object_type)) {
            $models = [$object_type];
            $field_values = $condition;
        } else
            $models = FHtml::getModels($object_type, $condition);

        $errors = [];
        foreach ($models as $model) {
            FHtml::setFieldValues($model, $field_values);
            if (!$model->save() || !empty($models->errors))
                $errors[] = FHtml::addError($model->errors);
        }
        return $models;
    }

    //Check all models if every field has same value
    //Check all models if every field has same value
    public static function checkAllModelsFieldValue($object_type, $condition = [], $field = 'status', $value = FHtml::STATUS_DONE)
    {
        if (is_array($object_type))
            $models = $object_type;
        else if (is_object($object_type))
            $models = [$object_type];
        else
            $models = FHtml::getModels($object_type, $condition);

        if (empty($models) || count($models) == 0)
            return false;

        if (!is_array($value))
            $value = [$value];

        foreach ($models as $model) {
            if (!in_array(FHtml::getFieldValue($model, $field), $value))
                return false;
        }

        return true;
    }


    public static function prepareModel($model, $post = null, $arrays = [])
    {
        $post = isset($post) ? $post : Yii::$app->request->post();

        if (isset($model)) {
            $result = $model->load($post);

            foreach ($arrays as $key => $value) {
                if (self::field_exists($model, $key))
                    $model[$key] = $value;
            }
        }

        return $model;
    }

    public static function saveObjectItems($model, $object_type, $object_id, $object_related = [])
    {
        if (!isset($model))
            return false;

        if (method_exists($model, 'saveObjectItems')) {
            return $model->saveObjectItems();
        }

        FHtml::saveObjectAttributes($model, $object_type, $object_id);
        FHtml::saveObjectFile($model, $object_type, $object_id);
        FHtml::saveCategory($model, $object_type, $object_id);
        FHtml::saveObjectRelations($model, $object_type, $object_id);

        return true;
    }

    // Get Value of a key in table setting
    /**
     * @param BaseModel $model
     * @param       $object_type
     * @param       $object_id
     * @param array $arrays
     * @return mixed
     */
    public static function prepareFieldValues(
        $model,
        $object_type = '',
        $fields = [],
        $created_fields = ['category_id_array', 'is_active', 'category_id', 'created_user', 'created_date', 'application_id'],
        $updated_fields = ['category_id_array', 'category_id', 'modified_user', 'modified_date']
    ) {
        if (!isset($model) && !is_object($model))
            return $model;

        if (empty($object_type))
            $object_type = $model->getTableName();

        $object_module = BaseInflector::camelize($object_type);
        $insert = $model->isNewRecord;

        //1. prepare for some default values first
        if ($insert)
            FHtml::prepareDefaultValues($model, $created_fields, FHtml::ACTION_ADD);
        else
            FHtml::prepareDefaultValues($model, $updated_fields, FHtml::ACTION_SAVE);

        if (empty($fields)) {
            $property_field = (isset($model) && method_exists($model, 'getPropertyField')) ? $model->getPropertyField() : FModel::FIELD_PROPERTIES;
            $properties_model = $model->getPropertiesModel();
            if (key_exists($object_module, $_POST) && key_exists($property_field, $_POST[$object_module])) {
                $fields = $_POST[$object_module][$property_field];
                if (!empty($property_field) && self::field_exists($model, $property_field)) {
                    $model->{$property_field} = FHtml::encode($fields);
                } else if (isset($properties_model) && FHtml::field_exists($properties_model, $property_field)) {
                    $properties_model->{$property_field} = FHtml::encode($fields);
                    $properties_model->save();
                }
            }
        } else {
            foreach ($fields as $field => $field_value) {
                if (is_numeric($field)) {
                    $field = $field_value;
                    $field_value = $model->getFieldValue($field);
                }

                // if field is array type
                if (!empty($field_value) && is_array($field_value) && $field != 'category_id') {
                    $model[$field] = FHtml::encode($field_value);
                }
            }
        }

        if (key_exists($object_module, $_POST)) {
            $post = $_POST[$object_module];

            foreach ($post as $field => $value) {
                if (empty($value)) {
                    continue;
                }
                if (is_array($value)) {
                    $value = FHtml::encode($value);
                }

                //Hung: if get all data in POST value and set to dynamic field of object
                if (!isset($model->{$field}) && !StringHelper::startsWith($field, '_') && !in_array($field, $model->getUploadFields())) {
                    $model->{$field} = $value;
                }
            }
        }

        return $model;
    }

    public static function saveObjectAttributes($model, $object_type, $object_id, $arrays = [], $attributes = [])
    {
        $object_module = BaseInflector::camelize($object_type);
        if (!FHtml::isTableExisted(FModel::TABLE_ATTRIBUTES))
            return false;

        if (key_exists($object_module, $_POST) && key_exists('ObjectAttributes', $_POST[$object_module])) {
            $arrays = $_POST[$object_module]['ObjectAttributes'];
        } else if (key_exists('ObjectAttributes', $_POST)) {
            $arrays = $_POST['ObjectAttributes'];
        }

        if (empty($arrays)) // delete all
        {
            models\ObjectAttributes::deleteAll(['object_type' => $object_type, 'object_id' => $object_id]);
            return $model;
        }

        if (is_array($arrays) && !empty($arrays)) {
            $i = 0;
            $ids = [];
            foreach ($arrays as $array) {
                if (!empty($array['id']))
                    $ids[] = $array['id'];
            }

            if (!empty($ids)) {
                models\ObjectAttributes::deleteAll('object_type = "' . $object_type . '" AND object_id = "' . $object_id . '" AND id NOT IN (' . implode(',', $ids) . ')');
            }

            foreach ($arrays as $array) {
                if (empty($array['meta_key']) || FHtml::isNotModelField($array['meta_key']))
                    continue;

                $id = $array['id'];
                if (empty($id)) {
                    $item = new models\ObjectAttributes();
                } else {
                    $item = models\ObjectAttributes::findOne($id);
                }

                if (isset($item)) {
                    $item->object_id = $object_id;
                    $item->object_type = $object_type;
                    $item->is_active = 1;
                    $item->created_date = date('Y-m-d');
                    $item->created_by = FHtml::currentUserId();
                    $item->application_id = FHtml::currentApplicationCode();
                    $item->meta_key = $array['meta_key'];
                    $item->meta_value = $array['meta_value'];

                    $item->save();
                }
            }
        }

        if (is_array($attributes) && !empty($attributes)) {
            foreach ($attributes as $meta_key => $meta_value) {
                FModel::setModelCustomAttribute($object_type, $object_id, $meta_key, $meta_value);
            }
        }
    }

    //delete all files in model or all object files of model
    public static function deleteObjectFile($model, $file_name = '')
    {
        $files = [];
        if (is_object($model)) {
            $object_type = FHtml::getTableName($model);
            $fields = FModel::getModelUploadFields($model);
            foreach ($fields as $field) {
                $files[] = FModel::getFieldValue($model, $field);
            }
            $files = array_unique($files);
        } else if (is_string($model))
            $object_type = $model;
        else
            return [];

        $folder = FHtml::getFullUploadFolder(str_replace('_', '-', $object_type));

        if (empty($files)) {
            if (is_string($file_name) && !empty($file_name))
                $files = [$file_name];
            else if (is_array($file_name))
                $files = $file_name;
        }

        $result = [];
        foreach ($files as $file_name) {
            $file_name_full = self::getFullFileName(self::getUploadFileName($folder, $file_name));
            FFile::delete($file_name_full);
            $result[] = $file_name_full;
        }

        return $result;
    }

    //save object files belong to model
    public static function saveObjectFile($model, $object_type, $object_id, $arrays = [], $folder = [])
    {
        $object_file_table_existed = FHtml::isTableExisted('object_file');

        if (empty($folder))
            $folder = FHtml::getFullUploadFolder('object-file');

        $object_module = BaseInflector::camelize($object_type);

        //1. Check ObjectFile multi Widget Input
        if (key_exists($object_module, $_POST) && key_exists('ObjectFile', $_POST[$object_module]) && $object_file_table_existed) {
            $arrays = $_POST[$object_module]['ObjectFile'];
            if (empty($arrays)) // delete all
            {
                models\ObjectFile::deleteEach("object_type = '$object_type' AND object_id = '$object_id'");
            } else {
                $i = 0;
                foreach ($arrays as $array) {
                    if (!empty($array['id']))
                        $ids[] = $array['id'];
                }

                if (!empty($ids))
                    models\ObjectFile::deleteEach('object_type = "' . $object_type . '" AND object_id = "' . $object_id . '" AND id NOT IN (' . implode(',', $ids) . ')');

                foreach ($arrays as $array) {
                    $id = $array['id'];
                    if (empty($id)) {
                        $item = new models\ObjectFile();
                    } else {
                        $item = models\ObjectFile::findOne($id);
                    }

                    $item->object_id = $object_id;
                    $item->object_type = $object_type;
                    $item->is_active = 1;
                    $item->sort_order = $i;

                    $item->created_date = FHtml::Now();
                    $item->created_user = FHtml::currentUserId();
                    $item->application_id = FHtml::currentApplicationCode();

                    if (self::field_exists($item, 'title') && key_exists('title', $array))
                        $item->title = $array['title'];

                    if (self::field_exists($item, 'file') && key_exists('file', $array))
                        $item->title = $array['file'];

                    if (self::field_exists($item, 'description') && key_exists('description', $array))
                        $item->description = $array['description'];

                    if (self::field_exists($item, 'file_type') && key_exists('file_type', $array) && !empty($array['file_type']))
                        $item->file_type = $array['file_type'];

                    if (self::field_exists($item, 'file_size') && key_exists('file_size', $array))
                        $item->file_size = $array['file_size'];

                    if (self::field_exists($item, 'file_duration') && key_exists('file_duration', $array))
                        $item->file_duration = $array['file_duration'];

                    if (self::field_exists($item, 'status') && key_exists('status', $array))
                        $item->status = $array['status'];

                    if (self::field_exists($item, 'type') && key_exists('type', $array))
                        $item->type = $array['type'];

                    if (self::field_exists($item, 'is_active') && key_exists('is_active', $array))
                        $item->is_active = $array['is_active'];

                    $file = FUploadedFile::getInstance($model, 'ObjectFile[' . $i . '][file_upload]');

                    if (isset($file)) {
                        $file->fieldName = 'file';
                        $file_name = strtolower(str_replace('.' . $file->extension, '', $file->name));

                        FFile::deleteFile($item->file, 'object-file');

                        $file->name = $object_type . '_' . $object_id . '_' .  FHtml::getFriendlyFileName($file_name) . '.' . $file->extension;
                        if (empty($item->file_type)) {
                            $item->file_type = self::getFileType($file);
                        }
                        $item->file_size = $file->size;
                        $item->file = strtolower($file->name);
                    } else {
                        $file_name = $item->file;
                    }

                    if (empty($item->title)) {
                        if (isset($file))
                            $item->title = BaseInflector::camel2words($file_name);
                        else if (!empty($item->file))
                            $item->title = $item->file;
                        else
                            $item->title = $object_type . '_' . $object_id . '_file_' . $i;
                    }

                    if ($item->save()) {
                        FHtml::saveFiles($file, $folder, $item);
                    } else {
                        if ($item->errors)
                            FHtml::addError($item->errors);
                    }

                    $i += 1;
                }
            }
        }

        //2. check multiple upload
        if (key_exists($object_module, $_FILES)) {
            $file_fields = $_FILES[$object_module]['name'];
            foreach ($file_fields as $file_field => $arrays1) {
                $arrays1 = $_FILES[$object_module]['name'][$file_field];
                if (!is_array($arrays1))
                    continue;
                //echo $file_field; echo $object_file_table_existed; var_dump($arrays1); die;
                foreach ($arrays1 as $i => $name) {

                    if ($file_field == '_ObjectFile' && $object_file_table_existed) {
                        //echo "{$file_field}[{$i}]"; echo 'fs'; die;
                        $file = FUploadedFile::getInstance($model, "{$file_field}[{$i}]");
                        if (!isset($file))
                            continue;
                        //var_dump($file); die;
                        $item = new models\ObjectFile();
                        if (self::field_exists($item, 'title'))
                            $item->title = $name;

                        if (self::field_exists($item, 'file'))
                            $item->file = strtolower($name);

                        $item->is_active = 1;
                        $item->object_type = $object_type;
                        $item->object_id = $object_id;
                        $item->created_date = FHtml::Now();
                        $item->created_user = FHtml::currentUserId();
                        $item->application_id = FHtml::currentApplicationCode();
                        $file->fieldName = 'file';
                        $file_name = str_replace('.' . $file->extension, '', strtolower($file->name));

                        $file->name = $object_type . '_' . $object_id . '_' . FHtml::getFriendlyFileName($file_name) . '.' . $file->extension;
                        if (empty($item->file_type)) {
                            $item->file_type = self::getFileType($file);
                        }
                        $item->file_size = $file->size;
                        $item->file = strtolower($file->name);

                        if ($item->save()) {
                            FHtml::saveFiles($file, $folder, $model, false);
                            //echo "Success ! "; FHtml::var_dump($item);
                        } else {
                            //echo "Error ! "; var_dump($item->errors); die;
                            if ($item->errors)
                                echo FHtml::addError($item->errors);
                        }
                    } else {
                        $file_input_name = "$object_module" . "[$file_field]" . "[$i]" . "[file]";
                        $file = FUploadedFile::getInstanceByName($file_input_name);
                        if (!isset($file))
                            continue;

                        //var_dump($file); die;
                        $folder = FHtml::getUploadFolder($model);
                        $file_name = FHtml::saveUploadedFile($file, $folder);
                        $file_values = FHtml::getFieldValue($model, $file_field);
                        $file_values = FHtml::decode($file_values);
                        if (is_array($file_values)) {
                            $arr = $file_values[$i];
                            if (isset($arr)) {
                                $arr['file'] = $file_name;
                                $file_values[$i] = $arr;
                            }
                        }
                        FHtml::setFieldValue($model, $file_field, $file_values);
                    }
                }
            }
        }
    }

    public static function getFileType($file)
    {
        if (is_object($file))
            $file_extension = $file->extension;
        else
            $file_extension = '';

        if (in_array($file_extension, ['jpg', 'png', 'gif', 'jpeg', 'bmp']))
            return 'Image';
        else if (in_array($file_extension, ['mp3']))
            return 'Audio';
        else if (in_array($file_extension, ['mp4', 'mov', 'avi', 'mog', 'mpeg', 'mpg']))
            return 'Video';
        else
            return 'File';
    }

    public static function saveCategory($model, $object_type = '', $object_id = '', $arrays = [])
    {
        if (!FHtml::isTableExisted('object_relation'))
            return false;

        $object_module = BaseInflector::camelize($object_type);
        $object2_type = 'object-category';
        $object2_module = BaseInflector::camelize(str_replace('\\', '_', $object2_type . '_array'));
        $relation_type = '';

        if (empty($arrays)) {
            if (FHtml::field_exists($model, 'category_id_array'))
                $arrays = FHtml::getFieldValue($model, 'category_id_array');
            else if (FHtml::field_exists($model, 'category_id'))
                $arrays = explode(',', FHtml::getFieldValue($model, 'category_id'));
            else if (key_exists($object_module, $_POST) && key_exists($object2_module, $_POST[$object_module]))
                $arrays = $_POST[$object_module][$object2_module];
        }

        if (is_string($arrays)) {
            $_arrays = json_decode($arrays, true);

            if (json_last_error() == 0) {
                $arrays = $_arrays;
            }
        }

        if (!empty($arrays) && is_array($arrays)) {
            //remove empty values
            foreach ($arrays as $id => $name) {
                if (empty($name))
                    unset($arrays[$id]);
            }
        }

        models\ObjectRelation::deleteAll(['object_type' => $object_type, 'object_id' => $object_id, 'object2_type' => $object2_type]);

        if (is_array($arrays) && !empty($arrays)) {

            $i = 0;
            foreach ($arrays as $array) {
                $i += 1;
                $item = new models\ObjectRelation();
                $item->object_id = $object_id;
                $item->object_type = $object_type;
                $item->created_date = FHtml::Now();

                $item->object2_type = $object2_type;
                $item->object2_id = (!is_array($array)) ? $array : $array['id'];
                $item->relation_type = $relation_type;
                if (empty($item->object2_id))
                    continue;
                FHtml::setFieldValue($item, 'sort_order', $i);
                FHtml::setFieldValue($item, 'created_user', FHtml::currentUserId());
                FHtml::setFieldValue($item, 'application_id', FHtml::currentApplicationId());
                $item->save();
            }
        }
    }

    public static function saveObjectRelations($model, $object_type, $object_id)
    {
        if (!isset($model))
            return;

        $object_module = BaseInflector::camelize($object_type);
        $arr_related = [];

        if (key_exists($object_module, $_POST)) {

            foreach ($_POST[$object_module] as $key => $value) {
                //Auto save to object_relation table ? Is that good idea ?
                $key_table =  $model->getModelLookupArray($key);
                if (!empty($key_table) && !in_array($object_type, ['object_relation'])) {
                    $object_related = $key_table;
                    $object2_module = str_replace('_', '', $key);
                    if (!is_array($value))
                        $value = [$value];
                    FHtml::saveObjectRelation($model, $object_type, $object_id, $object_related,  $key, $object2_module, $value);
                }

                if (StringHelper::endsWith($key, 'RelationType')) {

                    $key = str_replace("_RelationType", '', $key);
                    $object2_module = str_replace('_', '', $key);
                    if (key_exists("{$key}_ObjectType", $_POST[$object_module]))
                        $object_related = $_POST[$object_module]["{$key}_ObjectType"];
                    else
                        $object_related = str_replace('-', '_', BaseInflector::camel2id($object2_module));

                    FHtml::saveObjectRelation($model, $object_type, $object_id, $object_related, FHtml::RELATION_MANY_MANY, $object2_module);
                }
            }
        }
    }

    public static function saveObjectRelation($model, $object_type, $object_id, $object2_type, $relation_type, $object2_module = '', $arrays = null)
    {
        if (is_array($object2_type)) {
            //FHtml::var_dump($object2_type); die;
            return;
        }
        $arr = explode('\\', $object2_type);

        if (count($arr) > 1) {
            $object2_type = $arr[0];
            $relation_type = $arr[1];
        }

        $object_module = BaseInflector::camelize($object_type);

        $table_relation_existed = FHtml::isTableExisted('object_relation');

        if (empty($object2_module))
            $object2_module = BaseInflector::camelize(str_replace('\\', '_', $object2_type));

        if ($table_relation_existed && is_array($arrays) && !empty($arrays)) {

            $i = 0;
            $errors = [];
            $array_relations = models\ObjectRelation::findAll(['object_type' => $object_type, 'object_id' => $object_id, 'object2_type' => $object2_type, 'relation_type' => $relation_type]);
            $array_ids = [];
            foreach ($arrays as $array) {
                $object2_id = (!is_array($array)) ? $array : FHtml::getFieldValue($array, ['id', 'name']);
                $array_ids[] = $object2_id;

                $i += 1;
                $item = FHtml::findOne('object_relation', ['object_type' => $object_type, 'object_id' => $object_id, 'object2_id' => $object2_id, 'object2_type' => $object2_type, 'relation_type' => $relation_type]);
                if (!isset($item)) {
                    $item = models\ObjectRelation::createNew();
                    $item->object_id = $object_id;
                    $item->object_type = $object_type;
                    $item->created_date = FHtml::Now();
                    $item->sort_order = $i;
                    $item->created_user = FHtml::currentUserId();
                    $item->object2_type = $object2_type;
                    $item->object2_id = $object2_id;
                    $item->relation_type = $relation_type;
                    if (!empty($item->object2_id) && !empty($item->object_id) && !empty($item->object_type) && !empty($item->object2_type)) {
                        if (!$item->save()) {
                            FHtml::addError($item->errors);
                        }
                    }
                }
            }
            if (!empty($array_ids) && !empty($array_relations)) {
                foreach ($array_relations as $model_relation) {
                    if (!in_array($model_relation->object2_id, $array_ids))
                        $model_relation->delete();
                }
            }

            return;
        }


        if (empty($arrays)) {
            if (!key_exists($object_module, $_POST))
                return false;

            //die;
            //Case 1: include New and Existing items
            if ($table_relation_existed && key_exists($object2_module, $_POST[$object_module])) {
                $arrays = $_POST[$object_module][$object2_module];
                static::saveObjectRelation($model, $object_type, $object_id, $object2_type, $relation_type, $object2_module, $arrays);
            }

            //Case 2: only New Existing items
            $object2_module = '_' . $object2_module;
            $arrays = [];
            $arrays_keys = [];

            //            echo $object2_module;
            //            var_dump($_POST[$object_module]); die;
            foreach ($_POST[$object_module] as $key => $value) {
                if (StringHelper::startsWith($key, $object2_module . '_') && !StringHelper::endsWith($key, 'RelationType') && !StringHelper::endsWith($key, 'ObjectType')) {
                    $arrays_keys[] = $key;
                }
            }

            if (key_exists($object2_module, $_POST[$object_module])) {
                $arrays_keys[] = $object2_module;
            } else if (key_exists($object2_module . '_' . $relation_type, $_POST[$object_module])) {
                $arrays_keys[] = $object2_module . '_' . $relation_type;
            }

            $arrays_keys = array_unique($arrays_keys);

            foreach ($arrays_keys as $arrays_key) {
                $relation_type = str_replace($object2_module . '_', '', $arrays_key);
                $arrays = $_POST[$object_module][$arrays_key];
                if ($table_relation_existed) {
                    static::saveObjectRelation($model, $object_type, $object_id, $object2_type, $relation_type, $object2_module, $arrays);
                } else if (is_array($arrays) && !empty($arrays)) {

                    $i = 0;
                    foreach ($arrays as $array) {
                        $object2_id = is_numeric($array) ? $array : FHtml::getFieldValue($array, ['id', 'name']);

                        if (FHtml::field_exists($object2_type, $relation_type)) { // if $relation_type is a field of foreign table case, save directly to foreign table

                            $item = FHtml::getModel($object2_type, '', ['id' => $object2_id]);
                            FHtml::setFieldValue($item, $relation_type, FHtml::getFieldValue($model, 'id'));
                            if (!$item->save()) {
                                FHtml::addError($item->errors);
                            }
                        }
                    }
                }
            }
        }
    }

    public static function setModelCustomAttribute($object_type, $object_id = '', $field_name = '', $field_value = null)
    {
        if (is_object($object_type)) {
            $field_name = $object_id;
            $model = $object_type;
            $object_id = FHtml::getFieldValue($model, ['id']);
            $field_value = FHtml::getFieldValue($model, $field_name);
            $object_type = FHtml::getTableName($model);
        }
        if (FHtml::isNotModelField($field_name))
            return;
        $model = FHtml::getModel(FModel::TABLE_ATTRIBUTES, ['object_type' => $object_type, 'object_id' => $object_id, 'meta_key' => $field_name]);
        $result = false;
        if (isset($model)) {
            $model->meta_value = $field_value;
            $model->created_by = FHtml::currentUserId();
            $model->created_date = FHtml::Now();
            $model->application_id = FHtml::currentApplicationCode();
            $model->is_active = 1;

            $result = $model->save();
            if (!empty($model->errors)) {
                FHtml::addError($model->errors);
            }
        }
        return $result;
    }

    public static function saveUploadedFiles($model, $files = [], $folder = '', $baseFolder = '')
    {
        $upload_fields = self::getModelUploadFields($model);
        if (!empty($upload_fields)) {
            $folder = empty($folder) ? FHtml::getImageFolder($model) : $folder;
            //$baseFolder = empty($baseFolder) ? FHtml::getFullUploadFolder() : $baseFolder;

            $files = empty($files) ? FHtml::saveModelFiles($model, $upload_fields, $folder . '_' . FHtml::getAttribute($model, 'id')) : $files;
            //FHtml::var_dump($model);
            //var_dump($files); die;
        }
        return $files;
    }

    public static function getModelUploadFields($model)
    {
        $upload_fields = [];
        if (method_exists($model, 'getUploadFields'))
            $upload_fields = $model->getUploadFields();
        else if (FHtml::field_exists($model, 'COLUMNS_UPLOAD'))
            $upload_fields = $model::COLUMNS_UPLOAD;
        else {
            foreach (['thumbnail', 'logo', 'banner', 'avatar', 'image', 'file'] as $field) {
                if (FHtml::field_exists($model, $field))
                    $upload_fields[] = $field;
            }
        }

        return $upload_fields;
    }

    //HungHX: 20160801
    public static function prepareDefaultValues($model, $fields = ['category_id_array', 'created_date', 'created_user', 'modified_user', 'modified_date', 'is_active', 'application_id'], $whenAction = FHtml::ACTION_ADD)
    {
        if ((FHtml::currentAction() == 'create' && $whenAction == FHtml::ACTION_ADD) && self::field_exists($model, 'id')) {
            unset($model->id);
        }

        $modelName = StringHelper::basename($model::className());

        foreach ($fields as $field) {
            if (!self::field_exists($model, $field) && !StringHelper::endsWith($field, '_array')) //non existed field
                continue;

            if (in_array($field, ['created_date']) && self::field_exists($model, $field) && ($model->isNewRecord || empty($model[$field]))) {
                $model[$field] = FHtml::Now();
            } else if (in_array($field, ['created_at']) && self::field_exists($model, $field) && ($model->isNewRecord || empty($model[$field]))) {
                $model[$field] = time();
            } else if (in_array($field, ['created_user']) && self::field_exists($model, $field) && ($model->isNewRecord || empty($model[$field]))) {
                $model[$field] = FHtml::currentUserId();
            } else if (in_array($field, ['modified_date', 'updated_date']) && self::field_exists($model, $field)) {
                $model[$field] = FHtml::Now();
            } else if (in_array($field, ['modified_at']) && self::field_exists($model, $field)) {
                $model[$field] = time();
            } else if (self::field_exists($model, $field) && empty($model[$field]) && strpos($field, 'application_id') !== false || strpos($field, 'application_id') !== false) {
                $model[$field] = FHtml::currentApplicationCode();
            } else if (self::field_exists($model, $field) && empty($model[$field]) && StringHelper::endsWith($field, 'user')) {
                $model[$field] = FHtml::currentUserId();
            } else if (StringHelper::endsWith($field, '_array') || is_array($model[$field]) || FHtml::is_json($model[$field])) {
                $field1 = str_replace('_array', '', $field);

                if (!self::field_exists($model, $field1)) //non existed field
                    continue;

                if (in_array($field1, ['category_id'])) { // if field is category_id
                    if ($whenAction == self::ACTION_LOAD) {
                        $result = self::getFieldValue($model, $field1);
                        $result = trim($result, ',[];|\t\n');

                        $model[$field] = explode(',', $result);
                        $model[$field1] = explode(',', $result);
                    } else {
                        if (isset($_POST[$modelName][$field])) {
                            $arr = $_POST[$modelName][$field];
                            $model[$field] = $arr;
                            if (is_array($arr) && FHtml::field_exists($model, $field1) && !empty($arr)) {
                                $model[$field1] = ',' . implode(',', $arr) . ',';
                            } else {
                                $model[$field1] = null;
                            }
                        }
                    }
                } else {
                    if ($whenAction == self::ACTION_LOAD) {
                        $result = self::getFieldValue($model, $field1);
                        $model[$field1] = FHtml::decode($result);
                    } else {
                        if (isset($_POST[$modelName][$field])) {
                            $arr = $_POST[$modelName][$field];
                            $model[$field] = $arr;
                        }

                        if (isset($_POST[$modelName][$field1])) {
                            $arr = $_POST[$modelName][$field1];
                            $model[$field1] = FHtml::encode($arr);
                        }
                    }
                }
            } else {
            }
        }
    }

    //HungHX: 20160814
    public static function selectDistinctArray($table, $column, $isCache = true)
    {
        $sql_select = $column . ' AS Name';
        $sql_table = $table;
        $query = new FQuery;
        $query->select($sql_select)
            ->from($sql_table)->distinct();

        $query->orderBy([
            $column => SORT_ASC,
        ]);
        $data = ArrayHelper::getColumn($query->all(), 'Name');
        return $data;
    }

    public static function findDistinctArray($table, $column, $isCache = true)
    {
        return static::selectDistinctArray($table, $column, $isCache);
    }

    public static function Request()
    {
        return \Yii::$app->request;
    }

    public static function RequestParams($excluded_keys = [])
    {
        if (is_string($excluded_keys))
            $excluded_keys = explode(',', $excluded_keys);

        $params = \Yii::$app->request->getQueryParams();
        if (!empty($excluded_keys)) {
            foreach ($excluded_keys as $key) {
                ArrayHelper::remove($params, $key);
            }
        }
        return $params;
    }

    public static function merge($param, $extra = [])
    {
        return self::mergeRequestParams($param, $extra);
    }

    public static function mergeRequestParams($param, $extra = [])
    {
        if (empty($param))
            return $extra;

        if (is_array($param)) {
            $result = [];
            foreach ($param as $key => $value) {
                if (isset($value) && !empty($value)) {
                    $result = array_merge($result, [$key => $value]);
                }
            }
            if (is_array($extra)) {
                foreach ($extra as $key => $value) {
                    if (isset($value) && !empty($value)) {
                        $result = array_merge($result, [$key => $value]);
                    }
                }
            }
            return $result;
        } else if (is_string($param)) {
            $result = $param;
            if (is_string($extra)) {
                $result = $param . $extra;
            } else if (is_array($extra)) {
                foreach ($extra as $key => $value) {
                    $result .= ((strpos($result, '?') > 0) ? '&' : '?') . $key . '=' . $value;
                }
            }
        }
        return $result;
    }

    public static function getArrayParam($array, $param, $defaultvalue = '')
    {
        if (isset($array[$param]))
            return $array[$param];
        else
            return $defaultvalue;
    }

    public static function isNotModelField($key, $model = null)
    {
        if (FHtml::isInArray($key, ['_csrf', '_*', 'ObjectFiles', 'saveType', 'ObjectAttributes', 'properties']))
            return true;

        if (isset($model) && is_object($model))
            return !self::field_exists($model, $key);

        return false;
    }

    //2017/3/21
    //2017/3/21
    public static function loadParams(&$model, $params, $columnsMapping = null)
    {
        if (is_string($params))
            return $model;

        $params1 = [];

        if (!isset($model))
            return false;

        if (empty($params))
            return $model;

        //$params from ajax search of kartik grid 'ModelSearch' => [
        $object_type = BaseInflector::camelize(FHtml::getClassName($model::className(), true));

        if (is_object($params) && method_exists($params, 'asArray')) {
            $params1 = $params->asArray();
            foreach ($columnsMapping as $col => $col_value) {
                if (is_string($col_value) && !key_exists($col_value, $params1)) {
                    $params1 = array_merge($params1, [$col => FHtml::getFieldValue($params, $col_value)]);
                }
            }
        } else if (is_array($params)) {
            if (key_exists($object_type . 'Search', $params)) {
                $params1 = $params[$object_type . 'Search'];
                unset($params[$object_type . 'Search']);
            } else if (key_exists($object_type, $params)) {
                $params1 = $params[$object_type];
                unset($params[$object_type]);
            }

            if (!empty($params1))
                $params1 = array_merge($params1, $params);
            else
                $params1 = $params;
        }

        //        if (!empty($params1) && is_array($params1)) { //may be $params is $_POST or $_GET
        //            foreach ($params1 as $key => $value) {
        //                if (is_string($key) && !empty($columnsMapping) && key_exists($key, $columnsMapping))
        //                    $key = $columnsMapping[$key];
        //                $field_value = isset($params1[$key]) ? $params1[$key] : null;
        //                $model[$key] = $field_value;
        //            }
        //        };

        //        if (!empty($params1))
        //            $params = $params1;

        if (!empty($params1) && is_array($params1)) {
            if (!empty($columnsMapping)) {
                foreach ($columnsMapping as $col => $col_value) {
                    if (is_callable($col_value))
                        $col_value = call_user_func($col_value, $params);
                    else if (is_string($col_value) && key_exists($col_value, $params1)) {
                        $col_value = $params1[$col_value];
                    }

                    $params1 = array_merge($params1, [$col => $col_value]);
                }
            }

            foreach ($params1 as $key => $value) {
                if (self::isNotModelField($key))
                    continue;

                //2017/3/17 if $params like ['<>', $field, $value]
                if (is_numeric($key) && is_array($value)) {
                    if (count($value) == 3)
                        FHtml::setFieldValue($model, $value[1], in_array($value[0], ['!=', '<>']) ? ('-' . $value[2]) : $value[2]);
                } else {
                    FHtml::setFieldValue($model, $key, $value);
                }
            }
        }

        if (FModel::isApplicationsEnabled($model)) {
            $applicationid = FHtml::currentApplicationCode(); // Auto filter by ApplicationId
            $model->application_id = $applicationid;
        }

        return $model;
    }


    public static function getDummyViewModels($count = 1, $object_type = '')
    {
        $arr = [];
        $i = 0;
        for ($i = 0; $i < $count; $i++) {
            $arr[] = self::getDummyViewModel($object_type);
        }

        return $arr;
    }

    public static function getDummyViewModel($object_type = '')
    {
        return ViewModel::dummy();
    }

    public static function saveEditableData($object_type = '')
    {
        // validate if there is a editable input saved via AJAX
        if (Yii::$app->request->post('hasEditable')) {
            // instantiate your book model for saving
            $Id = Yii::$app->request->post('editableKey');

            if (empty($object_type))
                $object_type = FHtml::currentController();

            $model = FHtml::getModel($object_type, '', $Id);

            // store a default json response as desired by editable
            $out = Json::encode(['output' => '', 'message' => '']);

            // fetch the first entry in posted data (there should
            // only be one entry anyway in this array for an
            // editable submission)
            // - $posted is the posted data for Book without any indexes
            // - $post is the converted array for single model validation
            $post = [];
            $controllerPost = BaseInflector::camelize($object_type);
            $posted = current($_POST[$controllerPost]);
            $post[$controllerPost] = $posted;

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
            //echo $out;
            return $out;
        }
    }

    public static function parseAttribute($attribute)
    {
        //        if (is_string($attribute))
        //            $arr = explode(':', $attribute);
        //
        //        if (is_array($arr))
        //            $attribute = $arr;

        if (empty($attribute))
            return [
                'name' => '',
                'attribute' => '',
                'format' => '',
                'label' => '',
                'editor' => '',
                'items' => ''
            ];

        if (is_string($attribute)) {
            if (!preg_match('/^([\w\.]+)(:(\w*))?(:(.*))?$/', $attribute, $matches)) {
                FHtml::var_dump($matches);
                return $attribute = [
                    'attribute' => $attribute,
                    'format' => 'raw',
                    'label' => $attribute,
                    'editor' => ''
                ];
                // throw new \yii\console\Exception('Invalid attribute: [' . $attribute . ']. The attribute must be specified in the format of "attribute", "attribute:format" or "attribute:format:label": ');
            }
            $attribute = [
                'attribute' => $matches[1],
                'format' => isset($matches[3]) ? $matches[3] : 'raw',
                'label' => isset($matches[5]) ? $matches[5] : '',
            ];
        } else if (is_array($attribute) && !key_exists('attribute', $attribute)) {

            $matches = $attribute;
            FHtml::var_dump($matches);
            $attribute = [
                'attribute' => $matches[0],
                'format' => isset($matches[1]) ? $matches[1] : 'raw',
                'label' => isset($matches[2]) ? $matches[2] : '',
            ];
        } else {
            return [
                'attribute' => '',
                'format' => '',
                'label' => '',
                'editor' => '',
                'items' => ''
            ];
        }

        $format = $attribute['format'];
        $data = [];
        if (in_array($format, ['dropdown', 'array']))
            $type = 'dropDownList';
        else if (StringHelper::startsWith($format, '[')) {
            $attribute['format'] = 'array';
            $type = 'dropDownList';
            $format = trim($format, "[]");
            $data = FHtml::decode($format, ',');
        } else if (is_array($format)) {
            $attribute['format'] = 'array';
            $type = 'dropDownList';
            $data = FHtml::decode($format, ',');
            //$data = FHtml::getKeyValueArray($data);
        } else if (in_array($format, ['text', 'textarea']))
            $type = 'textArea';
        else if (in_array($format, ['range']))
            $type = FRangeInput::className();
        else if (in_array($format, ['numeric', 'int']))
            $type = FNumericInput::className();
        else if (in_array($format, ['date', 'datetime', 'dateInput']))
            $type = FDateInput::className();
        else if (in_array($format, ['time', 'timeInput']))
            $type = FTimeInput::className();
        else if (in_array($format, ['file', 'image', 'fileInput']))
            $type = FFileInput::className();
        else if (in_array($format, ['checkbox', 'boolean']))
            $type = 'checkbox';
        else
            $type = '';

        $attribute['editor'] = $type;
        $attribute['items'] = $data;
        return $attribute;
    }

    //The attribute must be specified in the format of "attribute", "attribute:format" or "attribute:format:label"
    public static function getCounters($table, $column)
    {

        $arr = FHtml::getComboArray('', $table, $column);
        $result = [];
        foreach ($arr as $key => $value) {
            $result = array_merge($result, [$key => FHtml::countModels($table, [$column => $key])]);
        }

        return $result;
    }


    public static function countModels($model, $params)
    {
        $query = self::getModelQuery($model, $params);
        if (isset($query) && is_object($query))
            return $query->count();
        else
            return 0;
    }

    //2017/3/21: Return Counting for all values
    public static function getModelFields($model, $excluded_fields = ['id'], $type = '')
    {
        if (is_string($model)) {
            $model = FHtml::createModel($model);
        }

        if (!empty($type) && is_string($type)) {
            if (FHtml::field_exists($model, $type))
                return $model->$type();
            $func = 'get' . BaseInflector::camelize($type) . 'Fields';
            if (FHtml::field_exists($model, $func))
                return $model->$func();
        }

        $fields = FHtml::getTableColumns($model);

        $result = [];
        foreach ($fields as $field) {
            $is_excluded = !empty($excluded_fields) && !FHtml::isInArray($field->name, $excluded_fields);
            if ($type == 'preview') {
                if (!$is_excluded && FHtml::isInArray($field->name, ['id', 'code', 'name', 'title', 'username' . 'description', 'overview', 'is_active', 'is_hot', 'is_top', 'type', 'status', 'category_id']))
                    $result[] = $field->name;
            } else if (is_array($type)) {
                if (!$is_excluded  && FHtml::isInArray($field->name, $type))
                    $result[] = $field->name;
            } else if (!$is_excluded) {
                $result[] = $field->name;
            }
        }
        return $result;
    }

    public static function getModelPreviewFields($model, $excluded_fields = ['id'])
    {
        return self::getModelFields($model, $excluded_fields, 'preview');
    }

    //2017/3/21: Count $model base on specific params
    public static function getModelFieldValue($object_type, $condition = [], $value_field = 'id')
    {
        $result = null;

        if (is_object($object_type) && is_string($condition)) {
            return FHtml::getFieldValue($object_type, $condition);
        }

        if (!empty($object_type)) {
            if (is_object($object_type))
                $model = $object_type;
            else
                $model = FHtml::createModel($object_type);

            if (isset($model)) {
                $model = $model::findOne($condition);
                if (isset($model))
                    $result = $model->getFieldValue($value_field);
            }
        }

        return $result;
    }

    public static function is_timestamp($timestamp)
    {
        $check = (is_int($timestamp) or is_float($timestamp))
            ? $timestamp
            : (string)(int)$timestamp;
        return ($check === $timestamp)
            and ((int)$timestamp <= PHP_INT_MAX)
            and ((int)$timestamp >= ~PHP_INT_MAX);
    }

    public static function getClassName($object, $getBaseNameOnly = false)
    {
        if (is_object($object)) {
            $result = $object::className();
        } else if (is_string($object)) {
            if (class_exists($object)) {

                $result = $object;
            } else if (FHtml::isTableExisted($object)) {
                $model = FHtml::createModel($object);
                if (isset($model))
                    $result = $model::className();
            } else
                return StringHelper::basename($object);
        } else {
            return '';
        }


        if (!$getBaseNameOnly)
            return $result;
        else
            return StringHelper::basename($result);
    }

    public static function isTableExisted($table, $db = null)
    {
        if (strpos($table, '.') !== false || strpos($table, ',') !== false || strpos($table, '\\') !== false || strpos($table, ' ') !== false) {
            return false;
        }
        $schema = self::getTableSchema($table, $db, false); //Hung: if set to TRUE then performance is very slow

        return isset($schema);
    }

    public static function isModuleExisted($module)
    {
        $module = self::getModuleObject($module);

        return isset($module);
    }

    public static function getTableSchema($tableName, $db = null, $refresh = false)
    {
        $tableSchema = null;
        if (empty($tableName) || strpos($tableName, '.') !== false || strpos($tableName, ' ') !== false || strpos($tableName, '/') !== false)
            return null;
        try {

            if (is_bool($db)) {
                $db = null;
                $refresh = $db;
            }

            if (empty($db))
                $db = FHtml::currentDb();
            else if (is_string($db))
                $db = FHtml::currentDb($db);

            if (!isset($db))
                return null;

            $tableSchema = $db
                ->getSchema()
                ->getTableSchema($tableName, $refresh);
        } catch (\yii\db\Exception $ex) {
            FHtml::addError($ex);
            return null;
        }

        return $tableSchema;
    }

    public static function getTableColumns($tableName)
    {
        if (is_object($tableName))
            $tableName = FHtml::getTableName($tableName);

        $schema = self::getTableSchema($tableName);
        return isset($schema) ? $schema->columns : [];
    }

    public static function currentDbName()
    {
        $arr = [FHtml::currentApplicationDatabase(), FHtml::CONFIG_DB];
        foreach ($arr as $arr_item) {
            $db = Yii::$app->get($arr_item, false);
            if (isset($db))
                return $arr_item;
        }
        return FHtml::CONFIG_DB;
    }

    public static function currentDatabaseName()
    {
        $dsn = FConfig::getConfigDsn();
        if (empty($dsn))
            return '';
        $arr = explode('=', $dsn);
        if (empty($arr))
            return '';
        return $arr[count($arr) - 1];
    }

    public static function checkFormResubmission($model = null, $token_param = '_csrf')
    {
        if (is_object($model))
            $model = FHtml::getTableName($model);

        $session_key = "$model.last_token";

        $prevToken = self::Session()->get($session_key);
        $currentToken = '';

        if (is_array($token_param)) {
            foreach ($token_param as $key => $value) {
                if (is_string($key) && is_string($value))
                    $currentToken .= "$key=$value;";
            }
        } else {
            if ($model !== FHtml::currentObjectType()) {
                return false;
            }
            $currentToken = (isset($_POST) && key_exists($token_param, $_POST)) ? $_POST[$token_param] : $token_param;
        }

        if (empty($currentToken)) {
            self::Session()->remove($session_key);
            return false;
        }
        if ($currentToken == $prevToken) {
            return true;
        } else {
            self::Session()[$session_key] = $currentToken;
            return false;
        }
    }

    public static function getPreviewFields($object_type)
    {
        if (is_string($object_type))
            $model = FModel::createModel($object_type);
        else if (is_object($object_type))
            $model = $object_type;
        else
            $model = null;
        if (isset($model) && is_object($model) && FHtml::field_exists($model, 'getPreviewFields')) {
            $fields = $model->getPreviewFields();
            if (!empty($fields))
                return $fields;
        }
        return [];
    }

    /**
     * @param $model
     * @param int $parent
     * @param array $newData
     * @return array
     */
    public static function getCategoryRecursive($model, $parent = 0, &$newData = [])
    {
        $child_array = array();
        foreach ($model as $key1 => $item1) {
            if ($item1->parent_id == $parent) {
                $child_array[$item1->name] = $item1->id;
                unset($model[$key1]);
            }
        }
        foreach ($child_array as $key_child => $value_child) {
            foreach ($model as $key2 => $item2) {
                if ($item2->parent_id == $value_child) {
                    $newData[$key_child][$item2->id] = $item2->name;
                }
            }
            self::getCategoryRecursive($model, $value_child, $newData);
        }
        return $newData;
    }

    /**
     * @param ObjectCategory $model
     * @param int $parent
     * @param array $newData
     * @param string $character
     * @return array
     */
    public static function getCategoryRecursiveWithCharacter($model, $parent = 0, &$newData = [], $character = "")
    {
        $child_array = array();
        foreach ($model as $key1 => $item1) {
            if ($item1->parent_id == $parent) {
                $child_array[] = $item1;
                unset($model[$key1]);
            }
        }
        foreach ($child_array as $key_child => $value_child) {
            $newData[] = [
                'id' => $value_child->id,
                "name" => $character . $value_child->name
            ];
            self::getCategoryRecursiveWithCharacter($model, $value_child->id, $newData, '- ' . $character);
        }
        return $newData;
    }

    public static function findArray($data, $q = '', $isFindAll = true)
    {
        $result = [];

        if (is_string($data))
            $data = FHtml::getArray($data);

        if (!is_array($data) || empty($data))
            return $result;

        if (empty($q))
            $result = $data;
        else {
            foreach ($data as $item) {
                foreach ($item as $key => $value) {
                    if ($isFindAll) {
                        if (strpos(strtolower($value), strtolower($q)) !== false) {
                            $result[] = $item;
                        }
                    } else {
                        if ($value == $q) {
                            return $item;
                        }
                    }
                }
            }
        }
        return $result;
    }

    public static function findOneArray($data, $q = '')
    {
        return self::findArray($data, $q, false);
    }

    public static function findAllArray($data, $q = '')
    {
        return self::findArray($data, $q, true);
    }

    //only allow one model with $extra_condition to have $value_unique (1), others must set to null
    public static function setModelUniqueBooleanColumns($object_type, $columns, $extra_condition = [], $value_unique = 1, $value_null = 0)
    {
        if (is_object($object_type)) {
            $model = $object_type;
            $object_type = FHtml::getTableName($model);
        } else {
            $model = FModel::createModel($object_type);
        }

        if (empty($columns) && method_exists($model, 'getUniqueBooleanFields'))
            $columns = $model->getUniqueBooleanFields();

        if (empty($extra_condition) && method_exists($model, 'getUniqueBooleanFieldsCondition'))
            $extra_condition = $model->getUniqueBooleanFieldsCondition();

        if (empty($columns))
            return [];

        if (is_string($columns))
            $columns = [$columns];

        $result = [];
        foreach ($columns as $column => $value) {
            if (is_numeric($column)) {
                $column = $value;
                $value = $model->getFieldValue($column);
            }

            if (!FHtml::field_exists($model, $column))
                continue;

            //$result[] = "$model->id : $value";

            if ($value == $value_unique) {
                // FModel::executeSqlUpdate($object_type, array_merge([$column => $value_unique], $extra_condition), [$column => $value_null]); //==> Error need to check.
                $list = $model::findAllForEdit(array_merge([$column => $value_unique], $extra_condition));
                if (!empty($list)) {
                    $ids = [];
                    foreach ($list as $item) {
                        //$result[] = 'Found: ' . $item->getPrimaryKeyValue();
                        if ($item->getPrimaryKeyValue() != $model->getPrimaryKeyValue() && ($item->$column != $value_null)) {
                            $ids[] = $item->getPrimaryKeyValue();
                        }
                    }
                    if (!empty($ids)) {
                        $ids = implode(',', $ids);
                        FHtml::executeSql("UPDATE $object_type SET $column = '$value_null' WHERE ID IN ($ids)");
                    }
                }
            }
        }

        //FHtml::var_dump($result);
    }

    //find all models that have same value in secific $columns
    public static function findDuplicatedModels($model, $columns = [], $auto_delete = false, $excluded_values = FConstant::EXCLUDED_UNIQUE_CODES)
    {
        if (!isset($model))
            return [];

        $result = [];
        if (empty($columns) && method_exists($model, 'getUniqueFields'))
            $columns = $model->getUniqueFields();
        if (empty($columns))
            return [];

        if (method_exists($model, 'getUniqueFieldsExcludedValues'))
            $excluded_values = array_merge($excluded_values, $model->getUniqueFieldsExcludedValues());

        foreach ($columns as $column => $value) {
            if (is_numeric($column)) {
                $column = $value;
                $value = $model->getFieldValue($column);
            }

            if (!FHtml::field_exists($model, $column) || (!empty($excluded_values) && FHtml::isInArray($value, $excluded_values)))
                continue;

            $condition = "($column = '$value')";
            if (!empty($model->id))
                $condition .= " and (" . $model->primaryKeyField() . " <> '$model->id')";

            $model1 = $model::findAll($condition, 'id desc');

            if (!empty($model1)) {
                $result = array_merge($result, [$column => $model1]);
                if ($auto_delete) {
                    foreach ($model1 as $model1_item) {
                        $model1_item->delete();
                    }
                    return [];
                }
            }
        }

        return $result;
    }

    public static function getNextId($model, $byYear = true, $update = false)
    {
        $tablename = FHtml::getTableName($model);

        if ($byYear) {
            $year = date('Y');
            $applcation_id = FHtml::currentApplicationCode();

            $model = ObjectYearIndex::findOne(['current_year' => $year, 'object_type' => $tablename]);
            if (!isset($model)) {
                $model = new ObjectYearIndex();
                $model->current_year = $year;
                $model->application_id = $applcation_id;
                $model->object_type = $tablename;
                $model->last_index = 0;
                $model->created_date = date('Y-m-d');
                $model->created_user = FHtml::currentUserId();
                $update = true;
            }

            if ($update) {
                $model->last_index = $model->last_index + 1;

                if (!$model->save()) {
                    var_dump($model->errors);
                    //die;
                }
            }

            return $model->last_index;
        } else {
            $sql = "select max(id) from $tablename";
            $id = FHtml::queryScalar($sql);
            $id = (isset($id) ? $id : 0) + 1;
            return $id;
        }
    }

    //2018-02-8
    public static function getUsersComboArray($condition = [], $displayname = 'name')
    {
        return models\User::findComboArray($condition, 'id', $displayname, 'name asc');
    }

    public static function getDisplayField($model, $fields = ['name', 'username', 'title'])
    {
        $display_name = '';
        foreach ($fields as $name) {
            if (FHtml::field_exists($model, $name)) {
                $display_name = $name;
                break;
            }
        }
        return $display_name;
    }

    public static function saveModelFiles($model, $fields, $fileName = '', $oldModel = null)
    {
        return self::getUploadedFiles($model, $fields, $fileName, $oldModel);
    }

    public static function getUploadedFiles($model, $fields, $fileName = '', $oldModel = null)
    {
        $files = [];
        if (!isset($fields) || count($fields) == 0)
            return $files;

        $folder = FHtml::getFullUploadFolder($model);

        $fileTitle = FHtml::getFieldValue($model, ['name', 'title', 'username']);

        if (is_array($fileTitle))
            $fileTitle = '';
        if (is_array($fileName))
            $fileName = '';
        $fileTitle = FHtml::toSEOFriendlyString($fileTitle);
        $post_fix = '_date_' . date('Ymd');

        $modelBaseName = BaseInflector::camelize(FHtml::getTableName($model));

        //if Ajax upload
        if (isset($_FILES)) {
            if (key_exists($modelBaseName, $_FILES)) {
                $filesArray = [];
                foreach ($fields as $field) {
                    if (isset($_FILES[$modelBaseName]['name'][$field]) && is_array($_FILES[$modelBaseName]['name'][$field])) {
                        $filesArray[$field] = [
                            'name' => $_FILES[$modelBaseName]['name'][$field][0],
                            'tmp_name' => $_FILES[$modelBaseName]['tmp_name'][$field][0],
                            'type' => $_FILES[$modelBaseName]['type'][$field][0],
                            'error' => $_FILES[$modelBaseName]['error'][$field][0],
                        ];
                    }
                }
            } else {
                $filesArray = $_FILES;
            }

            //if Ajax upload
            foreach ($filesArray as $field => $file) {
                if (!FHtml::field_exists($model, $field) || !isset($file) || empty($file['name']))
                    continue;

                //??
                if (is_array($file['name']))
                    continue;

                if ($fileName == '') {
                    $file_name = $fileTitle . $post_fix . $file['name'];
                } else {
                    $file_name = $fileTitle . '_' . str_replace("-", "_", $fileName) . '_' . $field . $post_fix . $file['name'];
                }

                $file_name = strtolower($file_name);
                $uploaded_file = FHtml::getFullUploadFolder($model) . '//' . $file_name;
                if (move_uploaded_file($file['tmp_name'], $uploaded_file)) {
                    FHtml::setFieldValue($model, $field, $file_name);
                }
            }

            foreach ($fields as $field) {
                if (empty($field) || !self::field_exists($model, $field))
                    continue;

                $file = FUploadedFile::getInstance($model, $field);
                if (!isset($file)) {
                    $file = FUploadedFile::getInstance($model, $field . '_upload');
                }

                if (!isset($file)) {
                    $file = FUploadedFile::getInstance($model, $field . '_file');
                }

                if ($file) {
                    $extension = $file->extension;

                    if ($fileName == '') {
                        $file->name = FModel::normalizeFileName($fileTitle . $post_fix . '.' . $extension);
                    } else {
                        $file->name = FModel::normalizeFileName($field . '-' . $fileTitle . '-' . str_replace("-", "_", $fileName) . $post_fix . '.' . $extension);
                    }

                    $file->oldName = FHtml::getFieldValue($model, $field);
                    $file->fieldName = $field;
                    $old_file = $file->oldName;

                    $new_file = strtolower($file->name);

                    $result = self::saveFile($file, $old_file, $folder, $new_file);

                    FHtml::setFieldValue($model, $field, $new_file);

                    $files[] = $file;
                }
            }
        }

        return $files;
    }

    //
    public static function normalizeFileName($filename, $except = [],  $replaced = '-')
    {
        if (empty($except))
            $except = array(' ', '\\', '/', ':', '*', '?', '"', '<', '>', '|');
        return str_replace($except, $replaced, $filename);
    }

    public static function normalizeSqlCondition($params, $model = null, $applications_enabled = true)
    {
        return $params;
        //        if (is_array($params))
        //        {
        //            $keywords = ['_pjax', '_show_error', 'show_error', 'lang', 'application_id'];
        //            foreach ($keywords as $keyword)
        //                unset($params[$keyword]);
        //
        //            if (isset($model)) {
        //                foreach ($params as $key => $value) {
        //                    if (is_string($key) && is_string($value) && !FHtml::field_exists($model, $key))
        //                        unset($params[$key]);
        //                }
        //            }
        //        }
        //
        //        //return $params;
        //
        //        $condition = $params;
        //        if (empty($condition))
        //            $condition = [];
        //
        //        $table = '';
        //        if (isset($model) && is_object($model)) {
        //            $table = FHtml::getTableName($model);
        //        } else if (!empty($model) && is_string($model)) {
        //            $table = $model;
        //            $model = FHtml::createModel($table);
        //        }
        //
        //        $is_sql_condition = (is_string($condition) && !is_numeric($condition) && FHtml::is_sql_condition($condition));
        //
        //        if (isset($model) && is_object($model) && $applications_enabled && FHtml::isApplicationsEnabled() && FHtml::field_exists($model, 'application_id')) {
        //
        //            $check_by_application_id = isset($check_by_application_id) ? $check_by_application_id : FHtml::isInArray($table, FHtml::EXCLUDED_TABLES_AS_APPLICATIONS);
        //            $application_id = $check_by_application_id ? FHtml::currentApplicationId() : FHtml::currentApplicationCode();
        //            $application_id_none = FHtml::APPLICATION_NONE;
        //
        //            if (is_array($condition)) {
        //                $condition = ['AND', $condition];
        //                if ($check_by_application_id)
        //                    $condition = array_merge($condition, [["application_id" => [$application_id, $application_id_none]]]);
        //                else
        //                    $condition = array_merge($condition, [["application_id" => $application_id]]);
        //
        //                if (!FHtml::isRoleAdmin() && FHtml::field_exists($model, 'is_active')) {
        //                    $condition = array_merge($condition, [['is_active' => 1]]);
        //                }
        //
        //            } else if ($is_sql_condition) {
        //                if ($check_by_application_id)
        //                    $condition = $condition . " and (application_id = '$application_id' or application_id = '$application_id_none')";
        //                else
        //                    $condition = $condition . " and (application_id = '$application_id')";
        //
        //                if (!FHtml::isRoleAdmin() && FHtml::field_exists($model, 'is_active')) {
        //                    $condition = $condition . " and (is_active = 1)";
        //                }
        //            }
        //        }
        //
        //        return $condition;
    }

    public static function getCategories($object_type = '', $object_id = -1, $isCached = false)
    {
        if (!FHtml::isTableExisted(self::TABLE_CATEGORIES))
            return [];
        $model = self::createModel(self::TABLE_CATEGORIES);

        if (is_array($object_type)) {
            $object_type = $object_type['object_type'];
        }

        $data = [];
        if ($object_id === -1) { // pass id or id array as first param
            $arr = [];
            if (is_string($object_type)) {
                if (empty($object_type)) {
                    return FHtml::getArray("category_id");
                } else if (strpos($object_type, ',') === false) {
                    return FHtml::getArray(StringHelper::endsWith($object_type, 'category_id') ? $object_type : "$object_type.category_id");
                } else {
                    $arr = explode(',', $object_type);
                }
            } else if (is_array($object_type))
                $arr = $object_type;
            else
                $arr[] = $object_type;
            $data = $model::find()->andWhere(['is_active' => 1])->andWhere(['in', 'id', $arr])->all();

            return $data;
        } else {
            if ($isCached) {
                $data = self::getCachedData(self::TABLE_CATEGORIES, $object_type, $object_id);
                if (isset($data))
                    return $data;
            }

            if (is_string($object_id))
                $arr = explode(',', $object_id);
            else if (is_array($object_id))
                $arr = $object_id;
            else
                $arr[] = $object_id;
            $default_object_type = FHtml::getModelModule($object_type);
            if (!empty($arr)) {
                if (ArrayHelper::isIndexed($arr)) { // if $arr is [id1, id2, id3..]
                    $data = $model::find()->andWhere(['is_active' => 1])->andWhere("object_type = '$object_type' OR object_type = '$default_object_type' OR object_type = '' ")->andWhere(['in', 'id', $arr])->orderby('sort_order asc, name asc')->all();
                } else { // if $arr is [field1 => value1, field2 => value2, field3 => value3..]

                    $data = $model::find()->andWhere(['is_active' => 1])->andWhere("object_type = '$object_type' OR object_type = '$default_object_type' OR object_type = '' ")->andWhere($arr)->orderby('sort_order asc, name asc')->all();
                }
            } else {
                $data = $model::find()->andWhere(['is_active' => 1])->andWhere("object_type = '$object_type' OR object_type = '$default_object_type' OR object_type = '' ")->orderby('sort_order asc, name asc')->all();
            }

            if ($isCached)
                self::saveCachedData($data, 'object-category\\' . $object_type, $object_type);

            return $data;
        }
    }

    public static function getQuerySelectString($query)
    {
        if (!isset($query))
            return '';
        if ($query instanceof Query)
            $select = $query->select;
        else
            $select = $query;

        if ($select instanceof Expression) {
            $select_str = $select[0]->expression;
        } else if (is_array($select)) {
            $select_str = implode(',', $select);
        } else if (is_string($select)) {
            $select_str = $select;
        } else {
            $select_str = '';
        }
        return strtolower($select_str);
    }

    public static function field_existsInQuery($column, $query, $model = null)
    {
        if (!isset($model))
            return true;

        if (is_object($query))
            $select_str = static::getQuerySelectString($query);
        else if (is_string($query))
            $select_str = $query;

        if (!is_string($column))
            return false;

        if (strpos($column, '.') !== false) {
            $column = substr($column, strpos($column, '.') + 1);
        }

        if (isset($model) && FHtml::field_exists($model, $column, true))
            return true;

        if (!empty($select_str) && strpos($select_str, " as $column") !== false)
            return true;

        return false;
    }

    public static function getCategoriesObjectType($module = '')
    {
        if (!empty($module)) {
            $moduleObject = FHtml::getModuleObject($module);
            if (isset($moduleObject) && method_exists($moduleObject, 'getCategoriesObjectType'))
                return $moduleObject->getCategoriesObjectType();
            return [];
        }
        $modules = FHtml::getApplicationModulesComboArray();
        $result = [];
        foreach ($modules as $module) {
            $result = array_merge($result, self::getCategoriesObjectType($module));
        }

        $result = array_unique($result);

        $arr = FModel::getLookupArray('#object_category.object_type');
        foreach ($arr as $item_key => $item_value) {
            $result[] = $item_key;
        }
        return array_unique($result);
    }

    public static function getSettingsFormTableColumns($form, $model, $module = '')
    {
        if (is_string($module)) {
            if (empty($module))
                $module = FHtml::currentModule();
            $moduleObject = FHtml::getModuleObject($module);
        } else if (is_object($module)) {
            $moduleObject = $module;
        }
        //FHtml::var_dump($moduleObject);
        $keys = (isset($moduleObject) && method_exists($moduleObject, 'getSettingsTypes')) ? $moduleObject->getSettingsTypes() : [];
        $result = [];
        foreach ($keys as $key => $value) {
            $key1 = str_replace('.', '_', $key);
            $result = array_merge($result, [$key1 => $form->fieldNoLabel($model, $key1)->arrayKeyValuesInput()]);
        }

        return $result;
    }

    public static function getDateRangeSQLCondition($column, $params = ['date_range', 'daterange'])
    {
        $ranges = static::getDateRange($column, $params);

        $date_start = empty($ranges) ? '' : $ranges['start'];
        $date_end = empty($ranges) ? '' : $ranges['end'];

        $andWhere = [];
        if (!empty($date_start)) {
            $andWhere[] = ['>=', $column, $date_start];
        }
        if (!empty($date_end)) {
            $andWhere[] = ['<=', $column, $date_end];
        }
        if (!empty($date_start) || !empty($date_end)) {
            $andWhere = array_merge(['AND'], $andWhere);
        }
        return $andWhere;
    }

    public static function getDateRange($column = '', $params = ['date_range', 'daterange'])
    {
        $date_start = FHtml::getRequestParam(['startDate', $column . '_start']);
        $date_end = FHtml::getRequestParam(['endDate', $column . '_end']);
        $params = array_merge([$column], $params);
        $date_range = FHtml::getRequestParam($params);
        if (!empty($date_range)) {
            $date_range = FHtml::strReplace($date_range, [' ' => '', '+' => '']);
            $arr = explode('-', $date_range);
            if (count($arr) == 2) {
                $date_start = $arr[0];
                $date_end = $arr[1];
            } else if (count($arr) == 1) {
                $date_start = '';
                $date_end = $arr[0];
            }
        }
        return (empty($date_start) && empty($date_end)) ? [] : ['start' => $date_start, 'end' => $date_end];
    }

    public static function getTreeViewArray($array, $currentParent = null, $currLevel = 0, $prevLevel = -1, $currIndex = 1, &$html = '', $buildChildren = false, $root_call = true)
    {
        foreach ($array as $categoryId => &$category) {
            $parent_id = FHtml::getFieldValue($category, 'parent_id');
            $name = FHtml::getFieldValue($category, ['name', 'title']);

            $currentParent_id = is_object($currentParent) ? FHtml::getFieldValue($currentParent, ['id']) : $currentParent;
            $currentParent_index = FHtml::getFieldValue($currentParent, 'tree_index');

            if ((empty($currentParent_id) && empty($parent_id)) || ($currentParent_id == $parent_id)) {
                if ($currLevel > $prevLevel) {
                    $prevLevel = $currLevel;
                    $html .= " <ol class='tree'> ";
                    $currIndex = 1;
                } else if ($currLevel == $prevLevel) {
                    $html .= " </li> ";
                    $currIndex += 1;
                }

                $html .= '<li> <label for="subfolder2">' . $name . '</label> <input type="checkbox" name="subfolder2"/>';

                $category['tree_level'] = $currLevel;
                $category['tree_index'] = empty($currentParent_index) ?  $currIndex : $currentParent_index . "." . $currIndex;

                $currLevel++;

                $array[$categoryId] = $category;

                self::getTreeViewArray($array, $category, $currLevel, $prevLevel, $currIndex, $html, false, false);

                $currLevel--;
            }
        }

        if ($currLevel == $prevLevel)
            $html .= " </li>  </ol> ";

        if ($root_call) { //only sort at root of recursive
            usort($array, function ($a, $b) {
                if ($a['tree_index'] == $b['tree_index']) {
                    return 0;
                };
                return ($a['tree_index'] < $b['tree_index']) ? -1 : 1;
            });
            if ($buildChildren) {
                $array = static::getNestArray($array);
            }
        }

        return $array;
    }

    public static function getNestArray($source)
    {
        $nested = array();

        foreach ($source as &$s) {
            if (is_null($s['parent_id'])) {
                // no parent_id so we put it in the root of the array
                $nested[] = &$s;
            } else {
                $pid = $s['parent_id'];
                if (isset($source[$pid])) {
                    // If the parent ID exists in the source array
                    // we add it to the 'children' array of the parent after initializing it.

                    if (!isset($source[$pid]['children'])) {
                        $source[$pid]['children'] = array();
                    }

                    $source[$pid]['children'][] = &$s;
                }
            }
        }
        return $nested;
    }

    public static function getRequiredFields($model, $field = '')
    {
        if (!is_object($model))
            return false;
        $rules = $model->rules();
        $fields = [];
        foreach ($rules as $rule) {
            if (isset($rule[1]) && $rule[1] == 'required')
                $fields = $rule[0];
        }
        if (empty($field))
            return $fields;
        else
            return in_array($field, $fields);
    }
}
