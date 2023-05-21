<?php

namespace backend\modules\system\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseDataObject;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**



 * This is the customized model class for table "object_reviews".
 */
class ObjectReviews extends ObjectReviewsBase //\yii\db\ActiveRecord
{
    const LOOKUP = [];

    const COLUMNS_UPLOAD = [];

    public $order_by = 'is_active desc,created_date desc,';

    const OBJECTS_RELATED = [];
    const OBJECTS_META = [];
    const COLUMNS = [
        'api' => ['id', 'object_id', 'object_type', 'rate', 'comment', 'user_id', 'name', 'email', 'is_active', 'created_date', 'application_id',],
        'all' => ['id', 'object_id', 'object_type', 'rate', 'comment', 'user_id', 'name', 'email', 'is_active', 'created_date', 'application_id',  'objectAttributes', 'objectFile', 'objectCategories'],
        '+' => ['user',   'objectAttributes', 'objectFile', 'objectCategories']
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['id', 'object_id', 'object_type', 'rate', 'comment', 'user_id', 'name', 'email', 'is_active', 'created_date', 'application_id'], 'filter', 'filter' => 'trim'],

            [['object_id', 'object_type'], 'required'],
            [['object_id', 'user_id', 'is_active'], 'integer'],
            [['rate'], 'number'],
            [['created_date'], 'safe'],
            [['object_type', 'application_id'], 'string', 'max' => 100],
            [['comment'], 'string', 'max' => 2000],
            [['name', 'email'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('ObjectReviews', 'ID'),
            'object_id' => FHtml::t('ObjectReviews', 'Object ID'),
            'object_type' => FHtml::t('ObjectReviews', 'Object Type'),
            'rate' => FHtml::t('ObjectReviews', 'Rate'),
            'comment' => FHtml::t('ObjectReviews', 'Comment'),
            'user_id' => FHtml::t('ObjectReviews', 'User ID'),
            'name' => FHtml::t('ObjectReviews', 'Name'),
            'email' => FHtml::t('ObjectReviews', 'Email'),
            'is_active' => FHtml::t('ObjectReviews', 'Is Active'),
            'created_date' => FHtml::t('ObjectReviews', 'Created Date'),
            'application_id' => FHtml::t('ObjectReviews', 'Application ID'),
        ];
    }



    // Lookup Object: user\n
    public $user;
    public function getUser()
    {
        if (!isset($this->user))
            $this->user = FHtml::getModel('app_user', '', $this->user_id, '', false);

        return $this->user;
    }
    public function setUser($value)
    {
        $this->user = $value;
    }


    public function prepareCustomFields()
    {
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

    public static function getLookupArray($column)
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
        $i18n->translations['ObjectReviews*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/messages',
            'fileMap' => [
                'ObjectReviews' => 'ObjectReviews.php',
            ],
        ];
    }

    public function toViewModel()
    {
        $model = new ViewModel();
        FHtml::setFieldValue($model, ['id'], $this->id);
        FHtml::setFieldValue($model, ['object_id'], $this->object_id);
        FHtml::setFieldValue($model, ['object_type'], $this->object_type);
        FHtml::setFieldValue($model, ['rate'], $this->rate);
        FHtml::setFieldValue($model, ['comment'], $this->comment);
        FHtml::setFieldValue($model, ['user_id'], $this->user_id);
        FHtml::setFieldValue($model, ['name'], $this->name);
        FHtml::setFieldValue($model, ['email'], $this->email);
        FHtml::setFieldValue($model, ['is_active'], $this->is_active);
        FHtml::setFieldValue($model, ['created_date'], $this->created_date);
        FHtml::setFieldValue($model, ['application_id'], $this->application_id);
        return $model;
    }
}
