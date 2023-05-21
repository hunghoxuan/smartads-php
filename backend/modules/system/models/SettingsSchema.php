<?php

namespace backend\modules\system\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseDataObject;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**



 * This is the customized model class for table "settings_schema".
 */
class SettingsSchema extends SettingsSchemaBase //\yii\db\ActiveRecord
{
    const LOOKUP = [
        'dbType' => [['id' => SettingsSchema::DBTYPE_NUMERIC, 'name' => 'numeric'], ['id' => SettingsSchema::DBTYPE_BOOL, 'name' => 'bool'], ['id' => SettingsSchema::DBTYPE_FLOAT, 'name' => 'float'], ['id' => SettingsSchema::DBTYPE_VARCHAR, 'name' => 'varchar'], ['id' => SettingsSchema::DBTYPE_TEXT, 'name' => 'text'], ['id' => SettingsSchema::DBTYPE_DATE, 'name' => 'date'], ['id' => SettingsSchema::DBTYPE_TIME, 'name' => 'time'], ['id' => SettingsSchema::DBTYPE_DATETIME, 'name' => 'datetime'],],
        'editor' => [['id' => SettingsSchema::EDITOR_TEXT, 'name' => 'text'], ['id' => SettingsSchema::EDITOR_TEXTAREA, 'name' => 'textarea'], ['id' => SettingsSchema::EDITOR_SELECT, 'name' => 'select'], ['id' => SettingsSchema::EDITOR_NUMERIC, 'name' => 'numeric'], ['id' => SettingsSchema::EDITOR_CURRENCY, 'name' => 'currency'], ['id' => SettingsSchema::EDITOR_BOOLEAN, 'name' => 'boolean'], ['id' => SettingsSchema::EDITOR_DATE, 'name' => 'date'], ['id' => SettingsSchema::EDITOR_TIME, 'name' => 'time'], ['id' => SettingsSchema::EDITOR_DATETIME, 'name' => 'datetime'], ['id' => SettingsSchema::EDITOR_RANGE, 'name' => 'range'], ['id' => SettingsSchema::EDITOR_FILE, 'name' => 'file'], ['id' => SettingsSchema::EDITOR_IMAGE, 'name' => 'image'],],
    ];

    const COLUMNS_UPLOAD = [];

    public $order_by = 'sort_order asc,is_active desc,';

    const OBJECTS_RELATED = [];
    const OBJECTS_META = [];
    const COLUMNS = [
        'api' => ['id', 'object_type', 'name', 'description', 'dbType', 'editor', 'lookup', 'format', 'algorithm', 'group', 'roles', 'sort_order', 'is_group', 'is_column', 'is_readonly', 'is_active', 'is_system',],
        'all' => ['id', 'object_type', 'name', 'description', 'dbType', 'editor', 'lookup', 'format', 'algorithm', 'group', 'roles', 'sort_order', 'is_group', 'is_column', 'is_readonly', 'is_active', 'is_system',  'objectAttributes', 'objectFile', 'objectCategories'],
        '+' => ['objectAttributes', 'objectFile', 'objectCategories']
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['id', 'object_type', 'name', 'description', 'dbType', 'editor', 'lookup', 'format', 'algorithm', 'group', 'roles', 'sort_order', 'is_group', 'is_column', 'is_readonly', 'is_active', 'is_system'], 'filter', 'filter' => 'trim'],

            [['object_type', 'name'], 'required'],
            [['sort_order', 'is_group', 'is_column', 'is_readonly', 'is_active', 'is_system'], 'integer'],
            [['object_type', 'dbType', 'editor'], 'string', 'max' => 100],
            [['name', 'lookup', 'format', 'algorithm', 'group'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 2000],
            [['roles'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('SettingsSchema', 'ID'),
            'object_type' => FHtml::t('SettingsSchema', 'Object Type'),
            'name' => FHtml::t('SettingsSchema', 'Name'),
            'description' => FHtml::t('SettingsSchema', 'Description'),
            'dbType' => FHtml::t('SettingsSchema', 'Db Type'),
            'editor' => FHtml::t('SettingsSchema', 'Editor'),
            'lookup' => FHtml::t('SettingsSchema', 'Lookup'),
            'format' => FHtml::t('SettingsSchema', 'Format'),
            'algorithm' => FHtml::t('SettingsSchema', 'Algorithm'),
            'group' => FHtml::t('SettingsSchema', 'Group'),
            'roles' => FHtml::t('SettingsSchema', 'Roles'),
            'sort_order' => FHtml::t('SettingsSchema', 'Sort Order'),
            'is_group' => FHtml::t('SettingsSchema', 'Is Group'),
            'is_column' => FHtml::t('SettingsSchema', 'Is Column'),
            'is_readonly' => FHtml::t('SettingsSchema', 'Is Readonly'),
            'is_active' => FHtml::t('SettingsSchema', 'Is Active'),
            'is_system' => FHtml::t('SettingsSchema', 'Is System'),
        ];
    }

    public function prepareCustomFields()
    {
        parent::prepareCustomFields();
    }

    public function fields()
    {
        $fields = parent::fields();

        $columns = self::COLUMNS;
        if (is_string($this->columnsMode) && !empty($this->columnsMode) && key_exists($this->columnsMode, $columns)) {
            $fields1 = $columns[$this->columnsMode];
            if (!empty($fields1))
                $fields = $fields1;
        } else if (is_array($this->columnsMode))
            return $this->columnsMode;

        if (key_exists('+', $columns) && !empty($columns['+'])) {
            $fields = array_merge($fields, $columns['+']);
        }
        //unset($fields['xxx'], $fields['yyy'], $fields['zzz']);

        return $fields;
    }

    public static function getLookupArray($column = '')
    {
        if (key_exists($column, self::LOOKUP))
            return self::LOOKUP[$column];
        return [];
    }

    public static function getRelatedObjects()
    {
        return self::OBJECTS_RELATED;
    }

    public static function getMetaObjects()
    {
        return self::OBJECTS_META;
    }

    public static function tableSchema()
    {
        return FHtml::getTableSchema(self::tableName());
    }

    public static function Columns()
    {
        return self::tableSchema()->columns;
    }

    public static function ColumnsArray()
    {
        return ArrayHelper::getColumn(self::tableSchema()->columns, 'name');
    }


    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        $i18n = Yii::$app->i18n;
        $i18n->translations['SettingsSchema*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/messages',
            'fileMap' => [
                'SettingsSchema' => 'SettingsSchema.php',
            ],
        ];
    }

    public function toViewModel()
    {
        $model = new ViewModel();
        FHtml::setFieldValue($model, ['id'], $this->id);
        FHtml::setFieldValue($model, ['object_type'], $this->object_type);
        FHtml::setFieldValue($model, ['name'], $this->name);
        FHtml::setFieldValue($model, ['description'], $this->description);
        FHtml::setFieldValue($model, ['dbType'], $this->dbType);
        FHtml::setFieldValue($model, ['editor'], $this->editor);
        FHtml::setFieldValue($model, ['lookup'], $this->lookup);
        FHtml::setFieldValue($model, ['format'], $this->format);
        FHtml::setFieldValue($model, ['algorithm'], $this->algorithm);
        FHtml::setFieldValue($model, ['group'], $this->group);
        FHtml::setFieldValue($model, ['roles'], $this->roles);
        FHtml::setFieldValue($model, ['sort_order'], $this->sort_order);
        FHtml::setFieldValue($model, ['is_group'], $this->is_group);
        FHtml::setFieldValue($model, ['is_column'], $this->is_column);
        FHtml::setFieldValue($model, ['is_readonly'], $this->is_readonly);
        FHtml::setFieldValue($model, ['is_active'], $this->is_active);
        FHtml::setFieldValue($model, ['is_system'], $this->is_system);
        return $model;
    }
}
