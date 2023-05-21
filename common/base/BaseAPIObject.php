<?php

namespace common\base;

use backend\models\ObjectAttributes;
use backend\models\ObjectCategory;
use backend\models\ObjectTranslation;
use backend\models\ObjectProperties;
use backend\modules\cms\models\CmsBlogs;
use backend\modules\wp\models\WpPosts;
use common\components\FActiveDataProvider;
use common\components\FActiveQuery;
use common\components\FActiveQueryPHPFile;
use common\components\FActiveQueryWordpress;
use common\components\FConstant;
use common\components\FFrontend;
use common\components\FHtml;
use common\components\FModel;
use common\components\FSecurity;
use common\models\BaseModel;
use frontend\models\ViewModel;
use kcfinder\path;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\base\UnknownMethodException;
use yii\base\UnknownPropertyException;
use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use backend\models\AuthPermission;

class BaseAPIObject extends BaseViewObject
{
    protected $category_id_array;
    protected $columnsMode = '';
    protected $uniqueColumns;
    protected $uniqueBooleanColumns;

    /**
     * @var
     */
    public $model_object_type;
    /**
     * @var array
     */
    private $columns = [];

    /**
     * @var
     */
    private $objectAttributes;
    private $objectAttributesArray = null;
    public $order_by;

    public function getDefaultNotTranslatedFields()
    {
        return ['code', 'sku', 'id', 'application_id', 'created_date', 'created_user', 'modified_user', 'modified_date', 'type', 'status', 'is_default', "category_id", "is_active", 'is_feature', 'is_hot', 'is_new', 'is_top', 'image', 'view_count', 'rate_count', 'attachment'];
    }

    public function getUploadFields()
    {
        return static::COLUMNS_UPLOAD;
    }

    public function getJsonFields()
    {
        return [];
    }

    public function getPropertyField()
    {
        if (FHtml::field_exists($this, FModel::FIELD_PROPERTIES)) {
            return FModel::FIELD_PROPERTIES;
        }
        return '';
    }

    public function getTranslationsField()
    {
        if (FHtml::field_exists($this, FModel::FIELD_TRANSLATIONS)) {
            return FModel::FIELD_TRANSLATIONS;
        }
        return '';
    }

    /** @return array .method return field not translate when update data*/
    public function getNotTranslatedFields()
    {
        return ['id', 'application_id', 'code', 'created_user', 'created_date', 'modified_user', 'modified_date'];
    }

    public function getUniqueFields()
    {
        if (!isset($this->uniqueColumns)) {
            $arr = ['sku'];
            $this->uniqueColumns = $this->field_exists($arr);
        }
        return $this->uniqueColumns;
    }

    public function getUniqueFieldsExcludedValues()
    {
        return FHtml::EXCLUDED_UNIQUE_CODES;
    }

    public function getUniqueBooleanFields()
    {
        if (!isset($this->uniqueBooleanColumns)) {
            $arr = ['is_default', 'is_unique'];
            $this->uniqueBooleanColumns = $this->field_exists($arr);
        }
        return $this->uniqueBooleanColumns;
    }

    public function getUniqueBooleanFieldsCondition()
    {
        if (FHtml::field_exists($this, 'object_type')) {
            return ['object_type' => $this->object_type];
        }
        return [];
    }

    public function getTranslatedContent($lang = '')
    {
        $model = $this->getTranslatedModel($lang);
        return isset($model) ? $model->content : '';
    }

    public function getTranslatedContentArray($lang = '')
    {
        return FHtml::decode($this->getTranslatedContent($lang));
    }

    public function isDBLanguagesEnabled()
    {
        $table1 = $this->getTableName();
        if (!empty($table1) && FHtml::isInArray($table1, FHtml::EXCLUDED_TABLES_AS_MULTILANGS))
            return false;

        return FHtml::settingDBLanguaguesEnabled();
    }

    public static function getRelatedObjects()
    {
        $arr = static::OBJECTS_RELATED;
        return $arr;
    }

    public function getModelRelatedObjects()
    {
        return self::getRelatedObjects();
    }

    public function getIsLocked()
    {
        return false;
    }

    public function getIsReadOnly()
    {
        return false;
    }

    public function isWorkflowEnabled()
    {
        return false;
    }


    /**
     * @param $column
     * @return array
     */
    public static function getLookupArray($column = '')
    {
        $table = static::tableName();
        if (empty($column)) {
            $key = "$table";
        } else {
            $key = "$table.$column";
        }

        if (StringHelper::startsWith($column, '#')) {
            $key = '#';
            $column = substr($column, 1);
        }

        if (key_exists($column, static::LOOKUP)) {
            return FHtml::getComboArray(static::LOOKUP[$column]);
        }

        return [];
    }

    public static function getLookupCategoryArray()
    {
        return static::getLookupArray('category_id');
    }

    /**
     * @return array
     */
    public static function getMetaObjects()
    {
        return [];
    }


    public function getModelLookupArray($column)
    {
        return static::getLookupArray($column);
    }

    /**
     * @return array
     */
    public function getModelMetaObjects()
    {
        return static::getMetaObjects();
    }

    private static $schema_checked = false;

    public static function getTableSchema($db = null)
    {
        if (!isset($db))
            $db = static::getDb();

        if (!is_object($db) || !isset($db))
            return null;

        $table = static::tableName();
        $tableSchema = $db
            ->getSchema()
            ->getTableSchema($table);

        if ($tableSchema === null) {
            if (!self::$schema_checked) {
                FHtml::addError("Table does not existed: " . $table);
                self::$schema_checked = true;
            }
        }

        if (!is_object($tableSchema))
            $tableSchema = null;
        return $tableSchema;
    }


    public static function tableSchema()
    {
        return FHtml::getTableSchema(self::tableName());
    }

    public static function populateRecord($record, $row)
    {
        $db = static::getDb();

        if (!is_object($db) || !isset($db))
            return null;

        $schema = static::getTableSchema();
        if (!isset($schema)) {
            $columns = array_flip($record->attributes());
            foreach ($row as $name => $value) {
                if (isset($columns[$name])) {
                    FHtml::setFieldValue($record, $name, $value);
                } elseif ($record->canSetProperty($name)) {
                    $record->$name = $value;
                }
            }
            return;
        }

        return parent::populateRecord($record, $row);
    }


    /**
     * @return array|mixed|null
     * @throws InvalidConfigException
     */
    public function attributes()
    {
        if (FHtml::constant_defined($this, 'COLUMNS_ARRAY') && !empty($this::COLUMNS_ARRAY))
            return $this::COLUMNS_ARRAY;

        $schema = static::getTableSchema();

        if (isset($schema))
            return array_keys($schema->columns);
        else {
            return $this->fields();
        }
    }

    public static function getTableAttributes($db = null)
    {
        $schema = static::getTableSchema($db);

        if (isset($schema))
            return array_keys($schema->columns);

        return [];
    }

    private static $_primaryKey;
    public static function primaryKey()
    {
        if (isset(self::$_primaryKey))
            return self::$_primaryKey;

        $schema = static::getTableSchema();
        if (isset($schema)) {
            $keys = $schema->primaryKey;
            self::$_primaryKey = !empty($keys) ? $keys : 'id';
        } else {
            self::$_primaryKey = 'id';
        }

        return self::$_primaryKey;
    }


    public static function createModels($array, $columnsMapping = null)
    {
        $result = [];
        foreach ($array as $i => $array_item) {
            $result[] = static::createModel($array_item, $columnsMapping);
        }
        return $result;
    }

    public static function createMany($array, $columnsMapping = null)
    {
        return static::createModels($array, $columnsMapping);
    }

    public static function isExistedTable()
    {
        $result = FModel::isTableExisted(static::tableName(), static::getDb());
        return $result;
    }

    public static function settingDynamicFieldEnabled()
    {
        return true && FHtml::settingDynamicFieldEnabled();
    }

    public static function findOneAndGetFieldValue($condition, $field_name, $default_value = null, $applications_enabled = true)
    {
        return static::getModelFieldValue($condition, $field_name, $default_value, $applications_enabled);
    }

    public static function getModelFieldValue($condition, $field_name, $default_value = null, $applications_enabled = true)
    {
        $model = static::getOne($condition, $applications_enabled);
        return isset($model) ? FModel::getFieldValue($model, $field_name, $default_value) : $default_value;
    }

    public static function getOne($condition, $applications_enabled = true)
    {
        return static::findOne($condition, $applications_enabled);
    }

    //find one and delete others that have same condition
    public static function findUnique($condition)
    {
        if (is_numeric($condition)) {
            $model = static::findOne($condition);
            if (isset($model))
                return $model;
        } else if (is_string($condition) && strpos($condition, ' ') == 0) { // $condition is a value, not a normal sql condition
            return null;
        }

        $items = static::findAll($condition);

        if (count($items) > 0) {
            $item1 = $items[0];
            foreach ($items as $item) {
                if ($item->primaryKey != $item1->primaryKey)
                    $item->delete();
            }
            return $item1;
        } else
            return null;
    }

    public static function findOneAsArray($condition, $selected_fields = [])
    {
        return static::findOne($condition, $selected_fields, true, true);
    }

    public static function findOne($condition, $selected_fields = [], $asArray = false, $applications_enabled = true)
    {
        if (!static::isExistedTable()) {
            FHtml::addError("Table " . static::tableName() . " does not exist.");
            return null;
        }

        if (is_bool($selected_fields)) {
            $applications_enabled = $selected_fields;
            $asArray = false;
            $selected_fields = [];
        }
        /** @var BaseModel $model */
        /** @var BaseModel $result */
        $model = static::createModel();
        $result = null;
        if (isset($model)) {
            if (is_numeric($condition)) {
                $result = $model::find()->where([$model->getPrimaryKey() => $condition])->one();
            } else {
                //FHtml::var_dump($model);
                $result = $model::find()->where($condition)->one();
            }

            if (isset($result) && is_object($result) && !empty($selected_fields)) {
                $result->setFields($selected_fields);
            }

            if ($asArray && isset($result) && is_object($result)) {
                $result = $result->asArray($selected_fields);
            }
            return $result;
        }

        return $result;
    }


    /**
     * Finds ActiveRecord instance(s) by the given condition.
     * This method is internally called by [[findOne()]] and [[findAll()]].
     * @param mixed $condition please refer to [[findOne()]] for the explanation of this parameter
     * @return ActiveQueryInterface the newly created [[ActiveQueryInterface|ActiveQuery]] instance.
     * @throws InvalidConfigException if there is no primary key defined
     * @internal
     */
    protected static function findByCondition($condition, $asArray = false)
    {
        $query = static::find();

        if (!ArrayHelper::isAssociative($condition)) {
            // query by primary key
            $primaryKey = static::primaryKey();
            if (isset($primaryKey[0])) {
                $condition = [$primaryKey[0] => $condition];
            } else {
                throw new InvalidConfigException('"' . get_called_class() . '" must have a primary key.');
            }
        }

        return $query->andWhere($condition)->asArray($asArray);
    }

    public static function isAPIModel()
    {
        return StringHelper::endsWith(static::className(), 'API');
    }

    public function getDefaultFindParams()
    {
        return [];
    }

    public static function findAllForEdit($condition = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $display_fields = [], $asArray = false, $load_activeonly = false)
    {
        return static::findAll($condition, $order_by, $page_size, $page, $isCached, $display_fields, $asArray, $load_activeonly);
    }

    public static function findAll($condition = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $display_fields = [], $asArray = false, $load_activeonly = true)
    {
        if (is_numeric($condition) && !empty($condition)) { //findOne
            return static::findOne($condition, $display_fields, $asArray);
        }

        return static::find()->select($display_fields)->where($condition)->orderBy($order_by)->limit($page_size)->offset($page * $page_size - $page_size)->asArray($asArray)->all();
    }

    public static function findArray($condition = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $display_fields = [], $asArray = true, $load_activeonly = true)
    {
        $arr = static::findAll($condition, $order_by, $page_size, $page, $isCached, $display_fields, $asArray, $load_activeonly);
        return $arr;
    }

    public static function findAllForCombo($condition = [], $id_field = 'id', $display_name = 'name', $order_by = '')
    {
        if (empty($id_field))
            $id_field = static::primaryKey();

        $model = static::createNew();

        if (empty($display_name))
            $display_name = FHtml::getDisplayField($model);

        if (empty($order_by))
            $order_by = FHtml::getOrderBy($model);


        $models = static::findAll($condition, $order_by);

        if (static::tableName() == 'object_setting') {
            $id_field = 'key';
            $display_name = 'value';
        }

        $arr = FModel::arrayMap($models, $id_field, $display_name);

        return $arr;
    }

    public static function findAllForCSV($condition = [], $order_by = [], $page_size = -1, $page = 1, $hasHeader = true, $display_fields = [], $asArray = true, $load_activeonly = true)
    {
        $models = static::findArray($condition, $order_by, $page_size, $page, false, $display_fields, $asArray, $load_activeonly);
        if (empty($display_fields))
            $display_fields = static::getTableAttributes();
        $result = [];
        if ($hasHeader)
            $result[] = $display_fields;
        foreach ($models as $model) {
            $result[] = is_array($model) ? array_values($model) : (is_object($model) ? array_values($model->asArray()) : $model);
        }

        return $result;
    }

    public static function findAllForCSVWithFields($display_fields = [], $condition = [], $order_by = [], $page_size = -1, $page = 1, $hasHeader = true, $asArray = true, $load_activeonly = true)
    {
        return static::findAllForCSV($condition, $order_by, $page_size, $page, $hasHeader, $display_fields, $asArray, $load_activeonly);
    }

    public static function findComboArray($condition = [], $id_field = 'id', $display_name = 'name', $order_by = '')
    {
        return static::findAllForCombo($condition, $id_field, $display_name, $order_by);
    }

    public static function findLimit($limit = -1, $condition = [], $order_by = [], $page = 1, $isCached = false, $display_fields = [], $load_activeonly = true)
    {
        return FHtml::getModels(static::modelName(), $condition, $order_by, $limit, $page, $isCached, $load_activeonly, static::getFields($display_fields));
    }

    public static function findHot($limit = -1, $condition = [], $order_by = [], $page = 1, $isCached = false, $display_fields = [], $load_activeonly = true)
    {
        if (is_array($condition) && !key_exists('is_hot', $condition) && FHtml::field_exists(static::tableName(), 'is_hot'))
            $condition = array_merge($condition, ['is_hot' => 1]);

        return FHtml::getModels(static::modelName(), $condition, $order_by, $limit, $page, $isCached, $load_activeonly, static::getFields($display_fields));
    }

    public static function findTop($limit = -1, $condition = [], $order_by = [], $page = 1, $isCached = false, $display_fields = [], $load_activeonly = true)
    {
        if (is_array($condition) && !key_exists('is_top', $condition) && FHtml::field_exists(static::tableName(), 'is_top'))
            $condition = array_merge($condition, ['is_top' => 1]);
        return FHtml::getModels(static::modelName(), $condition, $order_by, $limit, $page, $isCached, $load_activeonly, static::getFields($display_fields));
    }

    public static function getDataProvider($condition = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $display_fields = [], $load_activeonly = true)
    {
        $display_fields = static::getFields($display_fields);
        $list = FHtml::getPageModelsList(static::modelName(), $condition, $order_by, $page_size, $page, $isCached, $display_fields);

        return $list;
    }

    //Some basic Fields (will be overrided in actual model
    public static function findAllForAPI($condition = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $folder = '', $display_fields = [], $load_activeonly = true)
    {
        return FHtml::getModelsForAPI(static::modelName(), $condition, $order_by, $page_size, $page, $isCached, $folder, $display_fields);
    }


    public static function findByCategory($categories = 0, $order_by = [], $page_size = -1, $page = 1, $field = 'category_id')
    {
        $list = [];
        if (is_string($categories)) {
            if (strpos($categories, ',') !== false)
                $list = explode(',', $categories);
            else
                $list[] = $categories;
        } else if (is_array($categories))
            $list = $categories;
        $result = [];
        if (!empty($list)) {
            foreach ($list as $listItem) {
                $result = array_merge($result, static::findAll(['OR', [$field => $listItem], ['LIKE', $field, ",$listItem,"]], $order_by, $page_size, $page));
            }
        }
        return $result;
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        $name = static::getDbName();

        return FHtml::currentDb($name, static::tableName());
    }

    public static function getDbType()
    {
        return FConstant::DB_TYPE_SQL;
    }

    public static function getDbName()
    {
        return '';
    }


    public static function cacheOne($condition, $applications_enabled = true)
    {
        if (!static::isExistedTable()) {
            return null;
        }
        $cached_key = FHtml::encode(static::tableName() . '::find[' . FHtml::encode($condition) . ']');
        $cache = FHtml::Cache();
        $result = null;
        if (isset($cache) && $cache->exists($cached_key)) {
            $result = $cache->get($cached_key);
            if (isset($result) || !empty($result))
                return $result;
        }
        $result = static::findOne($condition, $applications_enabled);
        $cache->set($cached_key, $result);
        return $result;
    }

    public static function cacheAll($condition = [], $order_by = [], $page_size = -1, $page = 1, $display_fields = [])
    {
        if (!static::isExistedTable()) {
            return null;
        }

        $cached_key = FHtml::encode(static::tableName() . '::find[' . FHtml::encode($condition) . ']');
        $cache = FHtml::Cache();
        $result = null;
        if (isset($cache) && $cache->exists($cached_key)) {
            $result = $cache->get($cached_key);
            if (isset($result) || !empty($result))
                return $result;
        }
        $result = static::findAll($condition, $order_by, $page_size, $page, false, $display_fields, true);
        $cache->set($cached_key, $result);
        return $result;
    }

    public function prepareUploadFields()
    {
        $upload_fields = FModel::getModelUploadFields($this);
        if (!empty($upload_fields)) {
            foreach ($upload_fields as $field) {
                FHtml::setFieldValue($this, $field, FHtml::getFileURL($this->{$field}, FHtml::getImageFolder($this)));
            }
        }
        return $upload_fields;
    }

    public function setFields($fields)
    {
        if (empty($fields))
            return;

        if (is_string($fields))
            $fields = FHtml::decode($fields);
        $result = [];
        if (is_string($fields))
            $fields = [$fields];

        foreach ($fields as $field => $field_value) {
            if (is_numeric($field)) {
                $field = $field_value;
                $field_value = null;
            }
            if (isset($field_value)) {
                FHtml::setFieldValue($this, $field, $field_value);
            }
            $result[] = $field;
        }

        $this->api_fields = $result;
    }

    private $api_fields = [];
    public function addExtraField($field)
    {
        $this->api_fields[] = $field;
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function getApiFields()
    {
        $schema = static::getTableSchema();
        if (isset($schema))
            return array_keys($schema->columns);
        else {
            return array_keys($this->attributeLabels());
        }
    }

    public function setApiFields($fields)
    {
        $this->setFields($fields);
    }

    public function fields()
    {
        if (!empty($this->api_fields))
            return $this->api_fields;

        $fields = $this->getRequestFields();
        if (!empty($fields))
            return $fields;

        $fields = $this->getApiFields();
        return $fields;
    }

    public function prepareCustomFields()
    {
        $this->objectAttributes = $this->getObjectAttributes();
        $this->objectFile = $this->getObjectFiles();
        $this->objectCategories = $this->getObjectCategories();
    }


    public function setTableName($value)
    {
        $this->tableName = $value;
    }

    public function getRelatedModels($object_type = '', $relation_type = FHtml::RELATION_MANY_MANY)
    {
        if (empty($object_type))
            return $this->getRelatedRecords();

        return FHtml::getRelatedModels($this->getTableName(), $this->getPrimaryKeyValue(), $object_type, $relation_type);
    }

    //Categories - start
    private $objectCategories;
    public static function findAllCategories($object_type = '')
    {
        $module = FHtml::getModelModule(static::tableName());

        if (empty($object_type))
            $params = [static::tableName(), $module];
        else
            $params = $object_type;

        return ObjectCategory::findAll(['is_active' => 1, 'object_type' => $params]);
    }

    public function getObjectCategories()
    {
        if (!isset($this->objectCategories)) {
            $category_id = FHtml::getFieldValue($this, ['category_id', 'categoryid']);
            if (!empty($category_id))
                $this->objectCategories = FFrontend::getCategories($category_id);
            else
                $this->objectCategories = FHtml::getRelatedModels(static::getTableName(), $category_id, FHtml::TABLE_CATEGORIES);
        }
        return $this->objectCategories;
    }

    public static function getModelCategories($object_type = '')
    {
        return static::findAllCategories($object_type);
    }

    public function getCategoriesModels()
    {
        return $this->getObjectCategories();
    }

    public function getCategories()
    {
        $categories = $this->getObjectCategories();
        $result = [];
        if (is_array($categories)) {
            foreach ($categories as $category) {
                $result = array_merge($result, ['object_category#' . $category['id'] => $category['name']]);
            }
        }
        return $result;
    }

    //    public function getCategory()
    //    {
    //        $arr = static::getCategoriesModels();
    //        if (count($arr) > 0)
    //            return $arr[0];
    //        return new ObjectCategory();
    //    }

    public function getCategory_id_array()
    {
        if (isset($this['category_id']) && empty($this->category_id_array))
            $this->category_id_array = explode(',', trim($this['category_id'], ','));

        return $this->category_id_array;
    }

    public function getCategoryIdArray()
    {
        return $this->getCategory_id_array();
    }

    public function setCategory_id_array($value)
    {
        $this->category_id_array = $value;
    }
    //Categories - end

    //Content - Start
    private $objectContent;
    public function getObjectContent()
    {
        if (isset($this->objectContent))
            return $this->objectContent;

        $this->objectContent = FHtml::getModels('object_content', ['object_type' => $this->getTableName(), 'object_id' => $this->getPrimaryKeyValue()]);
        return $this->objectContent;
    }

    public function getContents()
    {
        $contents = $this->getObjectContent();
        $result = [];
        if (is_array($contents)) {
            foreach ($contents as $content) {
                $table = is_object($content) ? $content->getTableName() : 'object_content';
                $result = array_merge($result, ["$table#" . $content['id'] => ['name' => $content['name'], 'image' => FHtml::getFileUrl($content['image'], 'object-content'), 'description' => $content['description'], 'content' => $content['content']]]);
            }
        }
        return $result;
    }
    //Content - End

    //Files - Start
    private $objectFile;
    public function getObjectFiles()
    {
        if (empty($this->objectFile) && !$this->isNewRecord) {
            $this->objectFile = FHtml::getModels('object_file', ['object_type' => $this->getTableName(), 'object_id' => $this->getPrimaryKeyValue()]);
        }
        return $this->objectFile;
    }

    public function getFilesArray()
    {
        return $this->getFiles();
    }

    public function getObjectFile()
    {
        return $this->getObjectFiles();
    }

    public function setObjectFiles($value)
    {
        $this->objectFile = $value;
    }

    public function getFiles()
    {
        $files = $this->getObjectFiles();
        $result = [];
        if (is_array($files)) {
            foreach ($files as $file) {
                $table = is_object($file) ? $file->getTableName() : 'object_file';
                $result = array_merge($result, ["$table#" . $file['id'] => FHtml::getFileUrl($file['file'], $table)]);
            }
        }
        return $result;
    }

    //Files - End
    public function getOrderBy()
    {
        $order_by = FHtml::getRequestParam(['sort', 'order_by']);
        if (empty($order_by)) {
            $order_by = !empty($this->order_by) ? $this->order_by : FHtml::getOrderBy($this);
        }

        return $order_by;
    }

    public function loadFieldValue($fieldname, $params = [])
    {
        $a = BaseInflector::camelize($this->getTableName());
        if (empty($params)) {
            $params = $_POST;
        }

        if (key_exists($a, $params)) {
            $params = $params[$a];
        }

        return key_exists($fieldname, $params) ? $params[$fieldname] : null;
    }

    public function getTranslatedModel($lang = '')
    {
        if (empty($lang))
            $lang = FHtml::currentLang();

        if ($lang == FHtml::defaultLang()) //does not need to
            return null;

        $field_translations = $this->getTranslationsField();
        if (!empty($field_translations) && $this->field_exists($field_translations)) {
            $translation_array = FHtml::decode($this->{$field_translations});
            if (!is_array($translation_array))
                $translation_array = [];

            if (key_exists($lang, $translation_array)) {
                $translated_model = new ObjectTranslation();
                $translated_model->content = $translation_array[$lang];
                return $translated_model;
            }
            return null;
        }

        if (!FHtml::isTableExisted(FModel::TABLE_TRANSLATIONS))
            return null;

        $query = $this->hasOne(ObjectTranslation::className(), ['object_id' => 'id'])
            ->andOnCondition([
                'AND',
                ['object_type' => $this->tableName],
                ['lang' => $lang]
            ]);
        return $query->one();
    }


    public function validate($attributeNames = null, $clearErrors = true)
    {
        try {
            return parent::validate($attributeNames, $clearErrors);
        } catch (UnknownPropertyException $ex) {
            foreach ($attributeNames as $attributeName) {
                if (!$this->isCustomField($attributeName))
                    FHtml::addError($ex);
            }
            return false;
        } catch (Exception $ex) {
            FHtml::addError($ex);
            return null;
        }
    }

    public function isCustomField($name)
    {
        if (FHtml::field_exists($this, $name))
            return true;

        if (StringHelper::startsWith($name, '_') || StringHelper::startsWith($name, '@'))
            return true;
        $arr = $this->getCustomFields();
        return in_array($name, $arr);
    }

    public function getCustomFields()
    {
        $result = [];
        if (FHtml::constant_defined($this, 'COLUMNS_API'))
            $result = array_merge($result, $this::COLUMNS_API);

        if (FHtml::constant_defined($this, 'COLUMNS_CUSTOM'))
            $result = array_merge($result, $this::COLUMNS_CUSTOM);

        if (FHtml::constant_defined($this, 'OBJECTS_RELATED'))
            $result = array_merge($result, $this::OBJECTS_RELATED);

        if (FHtml::constant_defined($this, 'OBJECTS_META'))
            $result = array_merge($result, $this::OBJECTS_META);

        return $result;
    }

    public function getAttribute($name)
    {
        try {
            return parent::getAttribute($name);
        } catch (UnknownPropertyException $ex) {

            if (!$this->isCustomField($name)) { // $field start with '_' is virtual field, no need to show error
                if (static::settingDynamicFieldEnabled())
                    return $this->getCustomAttribute($name);
                FHtml::addError($ex);
            }
            return null;
        } catch (Exception $ex) {

            FHtml::addError($ex);
            return null;
        }
    }

    public function hasAttribute($name)
    {
        return parent::hasAttribute($name);
    }

    public function setFieldValue($fieldname, $value)
    {
        return $this->setAttribute($fieldname, $value);
    }

    /**
     * Sets the named attribute value.
     * @param string $name the attribute name
     * @param mixed $value the attribute value.
     * @throws InvalidParamException if the named attribute does not exist.
     * @see hasAttribute()
     */
    public function setAttribute($name, $value)
    {
        try {
            return parent::setAttribute($name, $value);
        } catch (UnknownPropertyException $ex) {

            if (!$this->isCustomField($name)) { // $field start with '_' is virtual field, no need to show error
                if (static::settingDynamicFieldEnabled())
                    return $this->setCustomAttribute($name, $value);
                FHtml::addError($ex);
            }
            return null;
        } catch (InvalidParamException $ex) {
            if (!$this->isCustomField($name)) { // $field start with '_' is virtual field, no need to show error
                if (static::settingDynamicFieldEnabled())
                    return $this->setCustomAttribute($name, $value);
                FHtml::addError($ex);
            }
            return null;
        } catch (Exception $ex) {
            FHtml::addError($ex);
            return null;
        }
    }

    public function setCustomFieldValue($meta_key, $meta_value)
    {
        return $this->setCustomAttribute($meta_key, $meta_value);
    }

    public function __get($name)
    {
        try {
            return parent::__get($name);
        } catch (UnknownPropertyException $ex) {
            if (!$this->isCustomField($name)) { // $field start with '_' is virtual field, no need to show error
                if (static::settingDynamicFieldEnabled())
                    return $this->getCustomAttribute($name);
                FHtml::addError($ex);
            }
            return null;
        } catch (Exception $ex) {

            FHtml::addError($ex);
            return null;
        }
    }

    public function __set($name, $value)
    {
        try {
            return parent::__set($name, $value);
        } catch (UnknownPropertyException $ex) {
            if (!$this->isCustomField($name)) { // $field start with '_' is virtual field, no need to show error
                if (static::settingDynamicFieldEnabled())
                    return $this->setCustomAttribute($name, $value);
                FHtml::addError($ex);
            }

            return null;
        } catch (InvalidCallException $ex) {

            if (!$this->isCustomField($name)) { // $field start with '_' is virtual field, no need to show error
                if (static::settingDynamicFieldEnabled())
                    return $this->setCustomAttribute($name, $value);
            }
            FHtml::addError($ex);
        } catch (Exception $ex) {
            FHtml::addError($ex);
            return null;
        }
    }



    public function afterFind()
    {
        $result = parent::afterFind(); // TODO: Change the autogenerated stub

        $fields = $this->attributes();
        $json_columns = $this->getJsonFields();

        foreach ($fields as $field) {
            if (in_array($field, $json_columns)) {
                $field_value = $this->getFieldValue($field);
                if (!empty($field_value) && $field != 'category_id') {
                    $this->setFieldValue($field, FHtml::decode($field_value));
                }
            }
        }

        return $result;
    }

    public function getCustomAttribute($meta_key, $default_value = null)
    {
        // find by custom get Attribute first
        $arr = [];
        $arr[] = preg_replace('#[\s]+#', '', ucwords(str_replace('_', ' ', $meta_key)));
        $arr[] = preg_replace('#[\s]+#', '', ucwords(str_replace('_', '', $meta_key)));
        foreach ($arr as $str) {
            if (method_exists($this, 'get' . $str)) {
                $value = $this->{'get' . $str}();
                return $value;
            }
        }

        //2
        $attributes = $this->getObjectAttributesArray();
        if (is_array($attributes) && key_exists($meta_key, $attributes)) {
            return $attributes[$meta_key];
        }

        //1.
        $propertiesModel = $this->getPropertiesModel();
        if (isset($propertiesModel)) {
            if (FHtml::field_exists($propertiesModel, $meta_key))
                return $propertiesModel->$meta_key;
            else
                return $propertiesModel->getCustomAttribute($meta_key, $default_value);
        }

        //3. START - Auto get Model or Array. Ex: $model->modelMusic
        $prefixs = ['array', 'list', 'model'];
        $meta_key1 = '';
        $meta_key1_prefix = '';
        $meta_key1_value = null;
        foreach ($prefixs as $prefix) {
            if (StringHelper::startsWith($meta_key, $prefix)) {
                $meta_key1 = substr($meta_key, strlen($prefix));
                $meta_key1 = strtolower($meta_key1);
                $meta_key1_prefix = $prefix;
                break;
            }
        }

        if (!empty($meta_key1) && FHtml::field_exists($this, strtolower($meta_key1))) {
            $meta_key1_value = $this->getFieldValue(strtolower($meta_key1));
            if (is_string($meta_key1_value)) {
                $meta_key1 = $meta_key1_value;
            }
        }

        if (!empty($meta_key1)) {

            $tables = [$this->getTableName() . "_$meta_key1", $meta_key1];
            foreach ($tables as $table) {
                if (FHtml::isTableExisted($table))
                    return in_array($meta_key1_prefix, ['model']) ? FHtml::findOne($table, ['object_id' => $this->getPrimaryKeyValue()]) : FHtml::findAll($table, ['object_id' => $this->getPrimaryKeyValue()]);
            }

            if (!empty($meta_key1_value)) {
                $table = $this->getModelLookupArray($meta_key1);
                if (is_string($table)) {
                    if (StringHelper::startsWith($table, '@'))
                        $table = substr($table, 1);

                    return in_array($meta_key1_prefix, ['model']) ? FHtml::findOne($table, ['id' => $meta_key1_value]) : FHtml::findAll($table, ['id' => $meta_key1_value]);
                }
            }
        }
        //END - Auto get Model or Array. Ex: $model->modelMusic

        return $default_value;
    }

    public function setCustomAttribute($meta_key, $meta_value)
    {
        $attributes = static::getObjectAttributesArray();
        $attributes[$meta_key] = $meta_value;
        $this->objectAttributesArray = $attributes;

        //1. If model has properties field
        $propertyField = $this->getPropertyField();
        if (!empty($propertyField)) {
            $result = [];
            foreach ($attributes as $key => $value) {
                if (!empty($key) || FHtml::isNotModelField($key))
                    continue;
                $result[] = ['meta_key' => $key, 'meta_value' => $value];
                //$result[$key] = $value;
            }
            $this->{$propertyField} = !empty($result) ? FHtml::encode($result) : null;
            return true;
        }

        //2. Else, if model as extended Object_Properties Model
        $propertiesModel = $this->getPropertiesModel();
        if (!isset($propertiesModel) && FHtml::isTableExisted(FModel::TABLE_PROPERTIES)) {
            $this->propertiesModel = new ObjectProperties();
            $this->propertiesModel->object_type = $this->getTableName();
            $this->propertiesModel->object_id = $this->getPrimaryKeyValue();
            $this->propertiesModel->application_id = FHtml::currentApplicationId();
            $propertiesModel = $this->propertiesModel;
        }

        if (isset($propertiesModel)) {
            foreach ($attributes as $meta_key => $meta_value) {
                if (FHtml::field_exists($propertiesModel, $meta_key)) {
                    $propertiesModel->$meta_key = $meta_value;
                } else {
                    if (empty($meta_key) || FHtml::isNotModelField($meta_key))
                        continue;
                    $propertiesModel->setCustomAttribute($meta_key, $meta_value);
                }
            }
            return true;
        }

        return;
    }

    public function loadObjectAttributesArray()
    {
        $this->objectAttributesArray = [];

        //1. if Properties Attribute is not null
        //1. check if table already has Properties field
        $property_field = $this->getPropertyField();

        if (FModel::field_exists($this, $property_field)) {
            $arr = FHtml::decode($this->{$property_field});
            if (!empty($arr)) {
                foreach ($arr as $i => $arr_item) {
                    $column_name = FHtml::getFieldValue($arr_item, 'meta_key');
                    $column_value = FHtml::getFieldValue($arr_item, 'meta_value');
                    $this->objectAttributesArray = array_merge($this->objectAttributesArray, [$column_name => $column_value]);
                }
            }

            return $this->objectAttributesArray;
        }

        //2. if ObjectProperties Model is not null
        $propertiesModel = $this->getPropertiesModel();
        if (isset($propertiesModel)) {
            foreach ($propertiesModel->attributes as $attribute1 => $attribute1_value) {
                if (in_array($attribute1, ['id']))
                    continue;
                $this->objectAttributesArray = array_merge($this->objectAttributesArray, [$attribute1 => $attribute1_value]);
            }
            foreach ($propertiesModel->getObjectAttributesArray() as $attribute1 => $attribute1_value) {
                $this->objectAttributesArray = array_merge($this->objectAttributesArray, [$attribute1 => $attribute1_value]);
            }
            return $this->objectAttributesArray;
        }

        //3. From objet_attributes table
        $attributes = $this->getObjectAttributes();
        if (is_array($attributes)) {
            foreach ($attributes as $attribute) {
                $this->objectAttributesArray = array_merge($this->objectAttributesArray, [$attribute->meta_key => $attribute->meta_value]);
            }
            return $this->objectAttributesArray;
        }

        return $this->objectAttributesArray;
    }

    public function getObjectAttributesArray()
    {
        if (!isset($this->objectAttributesArray))
            $this->loadObjectAttributesArray();

        //Do not save 'keywords'
        if (is_array($this->objectAttributesArray)) {
            foreach ($this->objectAttributesArray as $key => $value) {
                if (FModel::isNotModelField($key))
                    unset($this->objectAttributesArray[$key]);
            }
        }
        return $this->objectAttributesArray;
    }


    private $propertiesModel;
    public function getPropertiesModel()
    {
        if (FHtml::isInArray(static::tableName(), ['object_*'])) {
            return null;
        }
        if (!isset($this->propertiesModel)) {
            if (ObjectProperties::isExistedTable())
                $this->propertiesModel = ObjectProperties::findUnique(['object_type' => $this->getTableName(), 'object_id' => $this->getPrimaryKeyValue(), 'application_id' => FHtml::currentApplicationCode()]);
        }

        return $this->propertiesModel;
    }

    public function getObjectAttributes()
    {
        $this->objectAttributes = [];

        //3.
        $this->objectAttributes = null;
        if (FHtml::isTableExisted(FModel::TABLE_ATTRIBUTES)) {

            if (!isset($this->objectAttributes)) {
                $this->objectAttributes = FHtml::getModels(FModel::TABLE_ATTRIBUTES, ['object_type' => $this->getObjectType(), 'object_id' => $this->getPrimaryKeyValue()]);
            }

            $arr = static::getObjectSchemaAttributes();

            if (!empty($arr)) {
                foreach ($arr as $column => $desc) {
                    $column_name = (is_string($column) && !is_numeric($column)) ? $column : ((is_string($desc) && !is_numeric($desc)) ? $desc : '');
                    if (FHtml::field_exists($this, $column_name))
                        continue;

                    $existed = false;
                    if (!is_array($this->objectAttributes))
                        $this->objectAttributes = [];

                    foreach ($this->objectAttributes as $attribute1) {
                        if ($attribute1->meta_key == $column_name)
                            $existed = true;
                    }
                    if ($existed)
                        continue;

                    $attribute = new ObjectAttributes();
                    $attribute->tableName = $this->getTableName();
                    $attribute->meta_key = $column_name;
                    $attribute->object_type = $this->getTableName();
                    $attribute->object_id = $this->getPrimaryKeyValue();
                    $this->objectAttributes[] = $attribute;
                }
            }

            return $this->objectAttributes;
        }

        return [];
    }

    public function setObjectAttributes($value)
    {
        $this->objectAttributes = $value;
    }
    //Attributes - END

    public function getObjectType()
    {
        if (!empty($this->model_object_type))
            return $this->model_object_type;
        else
            return static::getTableName();
    }

    public function getObjectSchemaAttributes()
    {
        $columns = $this->getObjectColumns();
        $arr = [];
        if (!empty($columns) && is_array($columns)) {
            foreach ($columns as $column) {
                if (FHtml::getFieldValue($column, 'is_custom') == true)
                    $arr[] = [$column->name => ['dbType' => $column->dbType, 'editor' => $column->editor]];
            }
        }
        if (!empty($this::COLUMNS_ATTRIBUTES))
            $arr = array_merge($arr, $this::COLUMNS_ATTRIBUTES);

        return $arr;
    }

    public function getObjectColumns()
    {
        if (empty($this->columns)) {
            $this->columns = FHtml::getObjectColumns($this->getTableName());
        }
        return $this->columns;
    }

    public static function getTableColumns()
    {
        $schema = static::getTableSchema();
        return isset($schema) ? $schema->columns : [];
    }

    public static function getTableColumn($columnName)
    {
        $columns = static::getTableColumns();
        if (key_exists($columnName, $columns))
            return $columns[$columnName];
        return null;
    }

    public static function getTableColumnDbType($columnName)
    {
        $column = static::getTableColumn($columnName);
        if (isset($column))
            return $column->type;

        return null;
    }

    public function getObjectColumn($columnName)
    {
        $columns = $this->getObjectColumns();
        if (!isset($columns) || empty($columns))
            return null;

        foreach ($columns as $column) {
            if (strtolower($column->name) == strtolower($columnName))
                return $column;
        }
        return null;
    }

    public function toBaseModel()
    {
        return static::toViewModel();
    }

    public function search($params, $andWhere = '')
    {
        if (!static::isSqlDb()) {
            $models = $this::findAll($params);
            return $dataProvider = new FActiveDataProvider([
                'models' => $models,
            ]);
        }

        $query = $this::find();
        $model = (isset($query) && method_exists($query, 'getModel')) ? $query->getModel() : $this;
        $columnsMapping = (isset($query) && property_exists($query, 'columnMapping')) ? $query->columnMapping : [];
        if (!isset($model))
            $model = $this;

        $dataProvider = new FActiveDataProvider([
            'query' => $query,
        ]);

        FHtml::loadParams($this, $params);

        $params = $this->asArray();

        foreach ($params as $key => $value) {
            if (!empty($columnsMapping)) {
                if (is_object($model) && key_exists($key, $columnsMapping) && is_string($columnsMapping[$key]))
                    $key = $columnsMapping[$key];
            }
            if (is_object($model) && !$model->field_exists($key)) //remove search field if does not existed in searched object
                continue;

            if (!empty($value) && !is_array($value) && in_array($key, $this->getArrayFields())) {
                $query = $query->andFilterWhere(['OR', [$key => $value], ['like', $key, ",$value,"]]);
            } else if (is_numeric($value))
                $query = $query->andFilterWhere([$key => $value]);
            else
                $query = $query->andFilterWhere(['like', $key, $value]);
        }

        if (!empty($andWhere))
            $query = $query->andWhere($andWhere);

        $order_by = $this->getOrderBy();

        $query->orderby($order_by);

        return $dataProvider;
    }

    public function count($params, $db = null)
    {
        return static::find($db)->where($params)->select($this->primaryKeyField())->asArray()->count();
    }


    /**
     * Returns the relation object with the specified name.
     * A relation is defined by a getter method which returns an [[ActiveQueryInterface]] object.
     * It can be declared in either the Active Record class itself or one of its behaviors.
     * @param string $name the relation name
     * @param boolean $throwException whether to throw exception if the relation does not exist.
     * @return ActiveQueryInterface|ActiveQuery the relational query object. If the relation does not exist
     * and `$throwException` is false, null will be returned.
     * @throws InvalidParamException if the named relation does not exist.
     */
    public function getRelation($name, $throwException = false)
    {
        $condition = $throwException;
        $throwException = false;
        $field = '';

        $relation = parent::getRelation($name, $throwException);
        if (isset($relation))
            return $relation;

        if (FHtml::isTableExisted($name)) {
            $model = FHtml::createModel($name);
            if (isset($model)) {
                if (!is_array($condition)) {
                    if (is_string($condition))
                        $field = $condition;
                    else
                        $field = "$this->tableName" . "_id";

                    if (FHtml::field_exists($this, $field)) {
                        $condition = [$model->primaryKeyField() => $field];
                        return !empty($condition) ? $this->hasOne($model::className(), $condition) : null;
                    } else
                        $condition = [];
                }

                return !empty($condition) ? $this->hasOne($model::className(), $condition) : null;
            }
        }

        return null;
    }

    public function hasOne($class, $link)
    {
        $class = FHtml::getClassName($class);
        if (!is_array($link) && !is_object($link))
            $link = ['id' => $link];
        return parent::hasOne($class, $link);
    }

    //    public function hasMany($class, $link)
    //    {
    //        $class = FHtml::getClassName($class);
    //        if (is_string($link))
    //            $link = [$link => $this->primaryKeyField()];
    //        return parent::hasMany($class, $link);
    //    }

    public function getPermissions()
    {
        return [];
    }

    public function checkPermission($user = null, $action = '')
    {
        if (empty($action))
            $action = FHtml::currentAction();
        if (empty($user))
            $user = FHtml::currentUserIdentity();
        if (FHtml::isRoleAdmin($user))
            return true;

        return true;
    }

    public function field_exists($fields = [])
    {
        $result = [];
        if (is_string($fields))
            $fields = [$fields];

        if (is_array($fields)) {
            foreach ($fields as $field)
                if (FHtml::field_exists($this, $field))
                    $result[] = $field;
        }
        return $result;
    }


    public function loadParams($params)
    {
        return FHtml::loadParams($this, $params);
    }


    public function getRequestFields()
    {
        $fields = FHtml::getRequestParam([static::tableName() . '_fields', 'fields']);
        if (!empty($fields)) {
            $fields = explode(',', $fields);
            if (!is_array($fields))
                $fields = [];
            return $fields;
        }
        return [];
    }

    public function getManyIdArrays($object_type)
    {
        $arr = AuthPermission::findAll([
            'object_type' => $this->getTableName(),
            'object_id' => $this->getId(),
            'object2_type' => $object_type,
            'relation_type' => $object_type,
            'is_active' => FHtml::STATUS_ACTIVE,
        ]);

        return ArrayHelper::getColumn($arr, 'object2_id');
    }

    public function saveMany($object_type, $id_arrays)
    {
        $id = $this->getId();
        if (empty($object_type) || empty($id))
            return false;
        if (!is_array($id_arrays))
            $id_arrays = [$id_arrays];

        AuthPermission::deleteAll("relation_type = '$object_type' AND object_id = $id AND object_type = '" . $this->getTableName() . "'");
        $i = 0;
        foreach ($id_arrays as $id2) {
            $i++;
            $tmp = new AuthPermission();
            $tmp->object_id = $id;
            $tmp->object_type = $this->getTableName();
            $tmp->relation_type = $object_type;
            $tmp->object2_type = $object_type;
            $tmp->object2_id = $id2;
            $tmp->is_active = FHtml::STATUS_ACTIVE;
            $tmp->sort_order = $i;
            $tmp->created_date = FHtml::Today();
            $tmp->save();
            // if (!$tmp->save()) {
            //     FHTml::addError($tmp->errors);
            // }
        }
    }


    public function beforeSave($insert)
    {
        return parent::beforeSave($insert);
    }

    public static function getFields($display_fields = [])
    {
        return $display_fields;
    }

    public static function getTotalRecord($params = [])
    {
        $a = static::find()->where($params)->count();
        return $a;
    }

    public static function makeTreeViewModels($models)
    {
        $models_array = [];
        if (!is_array($models))
            return $models;

        foreach ($models as $model) {
            $models_array[$model->id] = $model;
        }
        $arr = FModel::getTreeViewArray($models_array);
        return $arr;
    }
}
