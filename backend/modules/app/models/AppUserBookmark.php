<?php

namespace backend\modules\app\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "app_user_bookmark".
 */
class AppUserBookmark extends AppUserBookmarkBase //\yii\db\ActiveRecord
{
    const LOOKUP = [];

    const COLUMNS_UPLOAD = [];

    public $order_by = 'created_date desc,';

    const OBJECTS_RELATED = [];
    const OBJECTS_META = [];
    const COLUMNS = [
        'api' => ['id', 'user_id', 'object_id', 'object_type', 'name', 'content', 'created_date', 'application_id', ],
        'all' => ['id', 'user_id', 'object_id', 'object_type', 'name', 'content', 'created_date', 'application_id',  'objectAttributes', 'objectFile', 'objectCategories'],
        '+' => ['user',   'objectAttributes', 'objectFile', 'objectCategories']
    ];

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
        
            [['id', 'user_id', 'object_id', 'object_type', 'name', 'content', 'created_date', 'application_id'], 'filter', 'filter' => 'trim'],
                
            [['user_id', 'object_id', 'object_type', 'name'], 'required'],
            [['content'], 'string'],
            [['created_date'], 'safe'],
            [['user_id', 'object_id', 'object_type', 'application_id'], 'string', 'max' => 100],
            [['name'], 'string', 'max' => 2000],
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
                    'id' => FHtml::t('AppUserBookmark', 'ID'),
                    'user_id' => FHtml::t('AppUserBookmark', 'User ID'),
                    'object_id' => FHtml::t('AppUserBookmark', 'Object ID'),
                    'object_type' => FHtml::t('AppUserBookmark', 'Object Type'),
                    'name' => FHtml::t('AppUserBookmark', 'Name'),
                    'content' => FHtml::t('AppUserBookmark', 'Content'),
                    'created_date' => FHtml::t('AppUserBookmark', 'Created Date'),
                    'application_id' => FHtml::t('AppUserBookmark', 'Application ID'),
                ];
    }



    // Lookup Object: user\n
    public $user;
    public function getUser() {
        if (!isset($this->user))
        $this->user = FHtml::getModel('user', '', $this->user_id, '', false);

        return $this->user;
    }
    public function setUser($value) {
        $this->user = $value;
    }


    public function prepareCustomFields() {
        parent::prepareCustomFields();

        $this->user = self::getUser();
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

    public static function getLookupArray($column = '') {
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
        $i18n->translations['AppUserBookmark*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/messages',
            'fileMap' => [
                'AppUserBookmark' => 'AppUserBookmark.php',
            ],
        ];
    }

    public function toViewModel() {
    $model = new ViewModel();
            FHtml::setFieldValue($model, ['id'], $this->id);
            FHtml::setFieldValue($model, ['user_id'], $this->user_id);
            FHtml::setFieldValue($model, ['object_id'], $this->object_id);
            FHtml::setFieldValue($model, ['object_type'], $this->object_type);
            FHtml::setFieldValue($model, ['name'], $this->name);
            FHtml::setFieldValue($model, ['content'], $this->content);
            FHtml::setFieldValue($model, ['created_date'], $this->created_date);
            FHtml::setFieldValue($model, ['application_id'], $this->application_id);
        return $model;
    }
}
