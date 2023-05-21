<?php

namespace backend\modules\system\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseDataObject;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**



 * This is the customized model class for table "object_tag".
 */
class ObjectTag extends ObjectTagBase //\yii\db\ActiveRecord
{
    const LOOKUP = [];

    const COLUMNS_UPLOAD = [];

    public $order_by = 'sort_order asc,';

    const OBJECTS_RELATED = [];
    const OBJECTS_META = [];
    const COLUMNS = [
        'api' => ['id', 'object_id', 'object_type', 'tag', 'sort_order', 'application_id',],
        'all' => ['id', 'object_id', 'object_type', 'tag', 'sort_order', 'application_id',  'objectAttributes', 'objectFile', 'objectCategories'],
        '+' => ['tag',   'objectAttributes', 'objectFile', 'objectCategories']
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['id', 'object_id', 'object_type', 'tag', 'sort_order', 'application_id'], 'filter', 'filter' => 'trim'],

            [['object_id', 'object_type'], 'required'],
            [['sort_order'], 'integer'],
            [['object_id'], 'string', 'max' => 255],
            [['object_type', 'tag', 'application_id'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('ObjectTag', 'ID'),
            'object_id' => FHtml::t('ObjectTag', 'Object ID'),
            'object_type' => FHtml::t('ObjectTag', 'Object Type'),
            'tag' => FHtml::t('ObjectTag', 'Tag'),
            'sort_order' => FHtml::t('ObjectTag', 'Sort Order'),
            'application_id' => FHtml::t('ObjectTag', 'Application ID'),
        ];
    }



    // Lookup Object: tag\n
    public $tag;
    public function getTag()
    {
        if (!isset($this->tag))
            $this->tag = FHtml::getModel('settings_tag', '', $this->tag_id, '', false);

        return $this->tag;
    }
    public function setTag($value)
    {
        $this->tag = $value;
    }


    public function prepareCustomFields()
    {
        parent::prepareCustomFields();

        $this->tag = self::getTag();
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
        $i18n->translations['ObjectTag*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/messages',
            'fileMap' => [
                'ObjectTag' => 'ObjectTag.php',
            ],
        ];
    }

    public function toViewModel()
    {
        $model = new ViewModel();
        FHtml::setFieldValue($model, ['id'], $this->id);
        FHtml::setFieldValue($model, ['object_id'], $this->object_id);
        FHtml::setFieldValue($model, ['object_type'], $this->object_type);
        FHtml::setFieldValue($model, ['tag'], $this->tag);
        FHtml::setFieldValue($model, ['sort_order'], $this->sort_order);
        FHtml::setFieldValue($model, ['application_id'], $this->application_id);
        return $model;
    }
}
