<?php

namespace backend\modules\system\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseDataObject;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**



 * This is the customized model class for table "object_comment".
 */
class ObjectComment extends ObjectCommentBase //\yii\db\ActiveRecord
{
    const LOOKUP = [
        'user_type' => [
            ['id' => ObjectComment::USER_TYPE_APP_USER, 'name' => 'app_user'],
            ['id' => ObjectComment::USER_TYPE_USER, 'name' => 'user'],
        ],
    ];

    const COLUMNS_UPLOAD = [];

    public $order_by = 'created_date desc,';

    const OBJECTS_RELATED = [];
    const OBJECTS_META = [];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['id', 'object_id', 'object_type', 'parent_id', 'comment', 'app_user_id', 'user_id', 'user_type', 'created_date', 'created_user', 'application_id'], 'filter', 'filter' => 'trim'],

            [['object_id'], 'required'],
            [['parent_id'], 'integer'],
            [['created_date'], 'safe'],
            [['object_id'], 'string', 'max' => 255],
            [['object_type', 'app_user_id', 'user_id', 'user_type', 'created_user', 'application_id'], 'string', 'max' => 100],
            [['comment'], 'string', 'max' => 4000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('ObjectComment', 'ID'),
            'object_id' => FHtml::t('ObjectComment', 'Object ID'),
            'object_type' => FHtml::t('ObjectComment', 'Object Type'),
            'parent_id' => FHtml::t('ObjectComment', 'Parent ID'),
            'comment' => FHtml::t('ObjectComment', 'Comment'),
            'app_user_id' => FHtml::t('ObjectComment', 'App User ID'),
            'user_id' => FHtml::t('ObjectComment', 'User ID'),
            'user_type' => FHtml::t('ObjectComment', 'User Type'),
            'created_date' => FHtml::t('ObjectComment', 'Created Date'),
            'created_user' => FHtml::t('ObjectComment', 'Created User'),
            'application_id' => FHtml::t('ObjectComment', 'Application ID'),
        ];
    }



    // Lookup Object: app_user\n
    public $app_user;
    public function getAppUser()
    {
        if (!isset($this->app_user))
            $this->app_user = FHtml::getModel('app_user', '', $this->app_user_id, '', false);

        return $this->app_user;
    }
    public function setAppUser($value)
    {
        $this->app_user = $value;
    }

    // Lookup Object: user\n
    public $user;
    public function getUser()
    {
        if (!isset($this->user))
            $this->user = FHtml::getModel('user', '', $this->user_id, '', false);

        return $this->user;
    }
    public function setUser($value)
    {
        $this->user = $value;
    }


    public function prepareCustomFields()
    {
        parent::prepareCustomFields();

        $this->app_user = self::getAppUser();
        $this->user = self::getUser();
    }

    public function fields()
    {
        $fields = parent::fields();

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
        $i18n->translations['ObjectComment*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/messages',
            'fileMap' => [
                'ObjectComment' => 'ObjectComment.php',
            ],
        ];
    }

    public function toViewModel()
    {
        $model = new ViewModel();
        FHtml::setFieldValue($model, ['id'], $this->id);
        FHtml::setFieldValue($model, ['object_id'], $this->object_id);
        FHtml::setFieldValue($model, ['object_type'], $this->object_type);
        FHtml::setFieldValue($model, ['parent_id'], $this->parent_id);
        FHtml::setFieldValue($model, ['comment'], $this->comment);
        FHtml::setFieldValue($model, ['app_user_id'], $this->app_user_id);
        FHtml::setFieldValue($model, ['user_id'], $this->user_id);
        FHtml::setFieldValue($model, ['user_type'], $this->user_type);
        FHtml::setFieldValue($model, ['created_date'], $this->created_date);
        FHtml::setFieldValue($model, ['created_user'], $this->created_user);
        FHtml::setFieldValue($model, ['application_id'], $this->application_id);
        return $model;
    }
}
