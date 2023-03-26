<?php

namespace backend\modules\system\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseDataObject;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "object_booking".
 */
class ObjectBooking extends ObjectBookingBase //\yii\db\ActiveRecord
{
    const LOOKUP = [        'status' => [['id' => ObjectBooking::STATUS_LOST, 'name' => 'Lost'], ],
];

    const COLUMNS_UPLOAD = [];

    public $order_by = 'is_active desc,created_date desc,';

    const OBJECTS_RELATED = [];
    const OBJECTS_META = [];
    const COLUMNS = [
        'api' => ['id', 'object_id', 'object_type', 'user_id', 'start_date', 'end_date', 'is_active', 'type', 'status', 'created_date', 'created_user', 'modified_date', 'modified_user', 'application_id', ],
        'all' => ['id', 'object_id', 'object_type', 'user_id', 'start_date', 'end_date', 'is_active', 'type', 'status', 'created_date', 'created_user', 'modified_date', 'modified_user', 'application_id',  'objectAttributes', 'objectFile', 'objectCategories'],
        '+' => [  'objectAttributes', 'objectFile', 'objectCategories']
    ];

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
        
            [['id', 'object_id', 'object_type', 'user_id', 'start_date', 'end_date', 'is_active', 'type', 'status', 'created_date', 'created_user', 'modified_date', 'modified_user', 'application_id'], 'filter', 'filter' => 'trim'],
                
            [['object_id'], 'required'],
            [['start_date', 'end_date', 'created_date', 'modified_date'], 'safe'],
            [['is_active'], 'integer'],
            [['object_id'], 'string', 'max' => 255],
            [['object_type', 'user_id', 'type', 'status', 'created_user', 'modified_user', 'application_id'], 'string', 'max' => 100],
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
                    'id' => FHtml::t('ObjectBooking', 'ID'),
                    'object_id' => FHtml::t('ObjectBooking', 'Object ID'),
                    'object_type' => FHtml::t('ObjectBooking', 'Object Type'),
                    'user_id' => FHtml::t('ObjectBooking', 'User ID'),
                    'start_date' => FHtml::t('ObjectBooking', 'Start Date'),
                    'end_date' => FHtml::t('ObjectBooking', 'End Date'),
                    'is_active' => FHtml::t('ObjectBooking', 'Is Active'),
                    'type' => FHtml::t('ObjectBooking', 'Type'),
                    'status' => FHtml::t('ObjectBooking', 'Status'),
                    'created_date' => FHtml::t('ObjectBooking', 'Created Date'),
                    'created_user' => FHtml::t('ObjectBooking', 'Created User'),
                    'modified_date' => FHtml::t('ObjectBooking', 'Modified Date'),
                    'modified_user' => FHtml::t('ObjectBooking', 'Modified User'),
                    'application_id' => FHtml::t('ObjectBooking', 'Application ID'),
                ];
    }




    public function prepareCustomFields() {
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

    public static function getLookupArray($column) {
        if (key_exists($column, self::LOOKUP))
            return self::LOOKUP[$column];
        return [];
    }

    public static function getRelatedObjects() {
        return self::OBJECTS_RELATED;
    }

    public static function getMetaObjects() {
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
        $i18n->translations['ObjectBooking*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/messages',
            'fileMap' => [
                'ObjectBooking' => 'ObjectBooking.php',
            ],
        ];
    }

    public function toViewModel() {
    $model = new ViewModel();
            FHtml::setFieldValue($model, ['id'], $this->id);
            FHtml::setFieldValue($model, ['object_id'], $this->object_id);
            FHtml::setFieldValue($model, ['object_type'], $this->object_type);
            FHtml::setFieldValue($model, ['user_id'], $this->user_id);
            FHtml::setFieldValue($model, ['start_date'], $this->start_date);
            FHtml::setFieldValue($model, ['end_date'], $this->end_date);
            FHtml::setFieldValue($model, ['is_active'], $this->is_active);
            FHtml::setFieldValue($model, ['type'], $this->type);
            FHtml::setFieldValue($model, ['status'], $this->status);
            FHtml::setFieldValue($model, ['created_date'], $this->created_date);
            FHtml::setFieldValue($model, ['created_user'], $this->created_user);
            FHtml::setFieldValue($model, ['modified_date'], $this->modified_date);
            FHtml::setFieldValue($model, ['modified_user'], $this->modified_user);
            FHtml::setFieldValue($model, ['application_id'], $this->application_id);
        return $model;
    }
}
