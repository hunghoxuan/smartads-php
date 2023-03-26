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
 * This is the customized model class for table "app_user_logs".
 */
class AppUserLogs extends AppUserLogsBase //\yii\db\ActiveRecord
{
    const LOOKUP = [        'action' => [['id' => AppUserLogs::ACTION_REGISTER, 'name' => 'register'], ['id' => AppUserLogs::ACTION_LOGIN, 'name' => 'login'], ['id' => AppUserLogs::ACTION_PURCHASE, 'name' => 'purchase'], ['id' => AppUserLogs::ACTION_FEEDBACK, 'name' => 'feedback'], ],
];

    const COLUMNS_UPLOAD = [];

    public $order_by = 'created_date desc,';

    const OBJECTS_RELATED = [];
    const OBJECTS_META = [];
    const COLUMNS = [
        'api' => ['id', 'user_id', 'action', 'duration', 'created_date', 'modified_date', 'application_id', ],
        'all' => ['id', 'user_id', 'action', 'duration', 'created_date', 'modified_date', 'application_id',  'objectAttributes', 'objectFile', 'objectCategories'],
        '+' => ['user',   'objectAttributes', 'objectFile', 'objectCategories']
    ];

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
        
            [['id', 'user_id', 'action', 'duration', 'created_date', 'modified_date', 'application_id'], 'filter', 'filter' => 'trim'],
                
            [['user_id'], 'required'],
            [['duration'], 'integer'],
            [['created_date', 'modified_date'], 'safe'],
            [['user_id', 'action', 'application_id'], 'string', 'max' => 100],
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
                    'id' => FHtml::t('AppUserLogs', 'ID'),
                    'user_id' => FHtml::t('AppUserLogs', 'User ID'),
                    'action' => FHtml::t('AppUserLogs', 'Action'),
                    'duration' => FHtml::t('AppUserLogs', 'Duration'),
                    'created_date' => FHtml::t('AppUserLogs', 'Created Date'),
                    'modified_date' => FHtml::t('AppUserLogs', 'Modified Date'),
                    'application_id' => FHtml::t('AppUserLogs', 'Application ID'),
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
        $i18n->translations['AppUserLogs*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/messages',
            'fileMap' => [
                'AppUserLogs' => 'AppUserLogs.php',
            ],
        ];
    }

    public function toViewModel() {
    $model = new ViewModel();
            FHtml::setFieldValue($model, ['id'], $this->id);
            FHtml::setFieldValue($model, ['user_id'], $this->user_id);
            FHtml::setFieldValue($model, ['action'], $this->action);
            FHtml::setFieldValue($model, ['duration'], $this->duration);
            FHtml::setFieldValue($model, ['created_date'], $this->created_date);
            FHtml::setFieldValue($model, ['modified_date'], $this->modified_date);
            FHtml::setFieldValue($model, ['application_id'], $this->application_id);
        return $model;
    }
}
