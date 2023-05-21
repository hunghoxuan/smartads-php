<?php

namespace backend\modules\system\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseDataObject;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**



 * This is the customized model class for table "settings_lookup".
 */
class SettingsLookup extends SettingsLookupBase //\yii\db\ActiveRecord
{
    const LOOKUP = [];

    const COLUMNS_UPLOAD = [];

    public $order_by = 'sort_order asc,is_active desc,created_date desc,';

    const OBJECTS_RELATED = [];
    const OBJECTS_META = [];
    const COLUMNS = [
        'api' => ['id', 'name', 'object_type', 'params', 'fields', 'orderby', 'limit', 'sql', 'is_cached', 'is_active', 'sort_order', 'created_user', 'created_date', 'application_id',],
        'all' => ['id', 'name', 'object_type', 'params', 'fields', 'orderby', 'limit', 'sql', 'is_cached', 'is_active', 'sort_order', 'created_user', 'created_date', 'application_id',  'objectAttributes', 'objectFile', 'objectCategories'],
        '+' => ['objectAttributes', 'objectFile', 'objectCategories']
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['id', 'name', 'object_type', 'params', 'fields', 'orderby', 'limit', 'sql', 'is_cached', 'is_active', 'sort_order', 'created_user', 'created_date', 'application_id'], 'filter', 'filter' => 'trim'],

            [['name'], 'required'],
            [['is_cached', 'is_active', 'sort_order'], 'integer'],
            [['created_date'], 'safe'],
            [['name', 'orderby', 'limit'], 'string', 'max' => 255],
            [['object_type', 'created_user', 'application_id'], 'string', 'max' => 100],
            [['params', 'fields', 'sql'], 'string', 'max' => 2000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('SettingsLookup', 'ID'),
            'name' => FHtml::t('SettingsLookup', 'Name'),
            'object_type' => FHtml::t('SettingsLookup', 'Object Type'),
            'params' => FHtml::t('SettingsLookup', 'Params'),
            'fields' => FHtml::t('SettingsLookup', 'Fields'),
            'orderby' => FHtml::t('SettingsLookup', 'Orderby'),
            'limit' => FHtml::t('SettingsLookup', 'Limit'),
            'sql' => FHtml::t('SettingsLookup', 'Sql'),
            'is_cached' => FHtml::t('SettingsLookup', 'Is Cached'),
            'is_active' => FHtml::t('SettingsLookup', 'Is Active'),
            'sort_order' => FHtml::t('SettingsLookup', 'Sort Order'),
            'created_user' => FHtml::t('SettingsLookup', 'Created User'),
            'created_date' => FHtml::t('SettingsLookup', 'Created Date'),
            'application_id' => FHtml::t('SettingsLookup', 'Application ID'),
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
        $i18n->translations['SettingsLookup*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/messages',
            'fileMap' => [
                'SettingsLookup' => 'SettingsLookup.php',
            ],
        ];
    }

    public function toViewModel()
    {
        $model = new ViewModel();
        FHtml::setFieldValue($model, ['id'], $this->id);
        FHtml::setFieldValue($model, ['name'], $this->name);
        FHtml::setFieldValue($model, ['object_type'], $this->object_type);
        FHtml::setFieldValue($model, ['params'], $this->params);
        FHtml::setFieldValue($model, ['fields'], $this->fields);
        FHtml::setFieldValue($model, ['orderby'], $this->orderby);
        FHtml::setFieldValue($model, ['limit'], $this->limit);
        FHtml::setFieldValue($model, ['sql'], $this->sql);
        FHtml::setFieldValue($model, ['is_cached'], $this->is_cached);
        FHtml::setFieldValue($model, ['is_active'], $this->is_active);
        FHtml::setFieldValue($model, ['sort_order'], $this->sort_order);
        FHtml::setFieldValue($model, ['created_user'], $this->created_user);
        FHtml::setFieldValue($model, ['created_date'], $this->created_date);
        FHtml::setFieldValue($model, ['application_id'], $this->application_id);
        return $model;
    }
}
