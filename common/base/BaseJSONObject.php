<?php

namespace common\base;


use common\components\FActiveDataProvider;
use common\components\FActiveQueryArray;
use common\components\FActiveQueryPHPFile;
use common\components\FConfig;
use common\components\FConstant;
use common\components\FFile;
use common\components\FFrontend;
use common\components\FHtml;
use common\components\FModel;
use yii\db\ActiveRecord;
use yii\helpers\BaseInflector;
use yii\helpers\StringHelper;

/**
 * Class BaseDataObject
 * @package common\base
 */
class BaseJSONObject extends BaseArrayObject
{
    public static function getDbType()
    {
        return FConstant::DB_TYPE_JSON;
    }

    public static function getDb()
    {
        $application_id = FHtml::currentApplicationId();
        $table = static::tableName();
        $folder = "applications\\$application_id\\data\\$table";
        FFile::createDir(FHtml::getRootFolder() . '\\' . $folder);
        return "$folder\all.json";
    }

    public static function loadData($file)
    {
        $result = FModel::findArrayFromJSONFile($file);
        if (is_string($result))
            return json_decode($result);
        return $result;
    }

    public static function saveData($file, $data)
    {
        return FFile::saveToJSon($data, $file);
    }

//    public function delete() {
//
//        $arr = static::findArray();
//
//        $id = $this->getPrimaryKeyValue();
//
//        if (key_exists($id, $arr)) {
//            unset($arr[$id]);
//            static::saveAll($arr);
//        }
//
//        return true;
//    }
//
//    public function search($params, $andWhere = '')
//    {
//        $model = static::createModel();
//        $models = $model::findAll($params);
//
//        return $dataProvider = new FActiveDataProvider([
//            'models' => $models,
//        ]);
//    }
//
//    public static function findArray($condition = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $display_fields = [], $asArray = true, $load_activeonly = true)
//    {
//        $db = static::getDb();
//
//        $arr = [];
//        if (is_string($db)) {
//            $db = FFile::getFullFileName($db);
//            if (!is_file($db))
//                return [];
//
//            $arr = static::loadData($db);
//            $result = [];
//            foreach ($arr as $key => $arr_item) {
//                if (key_exists('id', $arr_item))
//                    $key = $arr_item['id'];
//                $result[$key] = $arr_item;
//            }
//            $arr = $result;
//
//        }
//
//        if (is_array($condition)) {
//            $condition = FModel::normalizeSqlCondition($condition);
//
//            if (!empty($condition)) {
//                foreach ($arr as $key => $arr_item) {
//                    if (!FHtml::array_existed($condition, $arr_item))
//                        unset($arr[$key]);
//                }
//            }
//        }
//
//        return $arr;
//    }
//
//    public static function findAll($condition = [], $order_by = '', $page_size = -1, $page = 1, $is_cached = false, $display_fields = [], $asArray = true, $loadActiveOnly = true) {
//        $models = parent::findAll();
//
//        $result = [];
//
//        foreach ($models as $item) {
//            $has_key = false;
//            if (is_array($condition)) {
//                foreach ($condition as $key => $value) {
//                    if (FHtml::isInArray($key, ['_*', 'PHPSESSID', 'advanced-backend']))
//                        continue;
//
//                    if (isset($item[$key])) {
//                        if (strtolower($item[$key]) == strtolower($value))
//                            $result[] = $item;
//                        $has_key = true;
//                    }
//                }
//            }
//            if (!$has_key)
//                $result[] = $item;
//        }
//
//        return $result;
//    }
//
//    public static function findOne($condition, $selected_fields = [], $asArray = false, $applications_enabled = true)
//    {
//        $model = parent::findOne($condition, $selected_fields, $asArray, $applications_enabled); // TODO: Change the autogenerated stub
//        return $model;
//    }
//
//    public static function getQueryObject($class_name = '') {
//        if (empty($class_name))
//            $class_name = get_called_class();
//
//        $query = \Yii::createObject(FActiveQueryArray::className(), [$class_name]); //using FActiveQuery
//        return $query;
//    }
//
//    public function saveUploadFiles() {
//        $this->uploadedFiles = FHtml::saveUploadedFiles($this);
//    }
//
//    public function save($runValidation = true, $attributeNames = null)
//    {
//        $tableName = $this::tableName();
//        $this->saveUploadFiles();
//
//        $arr_item = $this->asArray($this->fields());
//        $arr = static::findArray();
//        $id_field = $this->primaryKeyField();
//        if (!key_exists($id_field, $arr_item))
//            return false;
//
//        $id = $arr_item[$id_field];
//
//        if (empty($id))
//            $id = $tableName . '_' . date('YmdHis') . rand(1, 100);
//
//        $arr_item[$id_field] = $id;
//
//        foreach ($this->uploadedFiles as $file) {
//            $arr_item[$file->fieldName] = $file->name;
//        }
//
//        $this->id = $id;
//        $arr = array_merge($arr, [$id => $arr_item]);
//        static::saveAll($arr);
//
//        return true;
//    }
//
//    public static function saveAll($condition = [], $params = [])
//    {
//        if (empty($params) && is_array($condition)) {
//            $models = $condition;
//        } else {
//            $models = static::findArray($condition);
//        }
//
//        if (is_array($models)) {
//            $db = static::getDb();
//            if (is_string($db)) {
//                static::saveData($db, $models);
//            }
//        }
//
//        return false;
//    }
//
//    public function hasAttribute($name)
//    {
//        $fields = $this->fields();
//        if (is_array($fields) && in_array($name, $fields))
//            return true;
//
//        return parent::hasAttribute($name);
//    }
}
