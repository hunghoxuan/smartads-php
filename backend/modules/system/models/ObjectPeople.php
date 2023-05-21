<?php

namespace backend\modules\system\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseDataObject;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**



 * This is the customized model class for table "object_people".
 */
class ObjectPeople extends ObjectPeopleBase //\yii\db\ActiveRecord
{
    const LOOKUP = [];

    const COLUMNS_UPLOAD = [];

    public $order_by = 'sort_order asc,is_active desc,';

    const OBJECTS_RELATED = [];
    const OBJECTS_META = [];
    const COLUMNS = [
        'api' => ['id', 'object_id', 'object_type', 'name', 'title', 'phone', 'email', 'address', 'facebook', 'google', 'linkedin', 'skype', 'instagram', 'twitter', 'yahoo', 'sort_order', 'is_active', 'application_id',],
        'all' => ['id', 'object_id', 'object_type', 'name', 'title', 'phone', 'email', 'address', 'facebook', 'google', 'linkedin', 'skype', 'instagram', 'twitter', 'yahoo', 'sort_order', 'is_active', 'application_id',  'objectAttributes', 'objectFile', 'objectCategories'],
        '+' => ['objectAttributes', 'objectFile', 'objectCategories']
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['id', 'object_id', 'object_type', 'name', 'title', 'phone', 'email', 'address', 'facebook', 'google', 'linkedin', 'skype', 'instagram', 'twitter', 'yahoo', 'sort_order', 'is_active', 'application_id'], 'filter', 'filter' => 'trim'],

            [['sort_order', 'is_active'], 'integer'],
            [['object_id', 'object_type', 'application_id'], 'string', 'max' => 100],
            [['name', 'title', 'phone', 'email', 'address', 'facebook', 'google', 'linkedin', 'skype', 'instagram', 'twitter', 'yahoo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('ObjectPeople', 'ID'),
            'object_id' => FHtml::t('ObjectPeople', 'Object ID'),
            'object_type' => FHtml::t('ObjectPeople', 'Object Type'),
            'name' => FHtml::t('ObjectPeople', 'Name'),
            'title' => FHtml::t('ObjectPeople', 'Title'),
            'phone' => FHtml::t('ObjectPeople', 'Phone'),
            'email' => FHtml::t('ObjectPeople', 'Email'),
            'address' => FHtml::t('ObjectPeople', 'Address'),
            'facebook' => FHtml::t('ObjectPeople', 'Facebook'),
            'google' => FHtml::t('ObjectPeople', 'Google'),
            'linkedin' => FHtml::t('ObjectPeople', 'Linkedin'),
            'skype' => FHtml::t('ObjectPeople', 'Skype'),
            'instagram' => FHtml::t('ObjectPeople', 'Instagram'),
            'twitter' => FHtml::t('ObjectPeople', 'Twitter'),
            'yahoo' => FHtml::t('ObjectPeople', 'Yahoo'),
            'sort_order' => FHtml::t('ObjectPeople', 'Sort Order'),
            'is_active' => FHtml::t('ObjectPeople', 'Is Active'),
            'application_id' => FHtml::t('ObjectPeople', 'Application ID'),
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
        $i18n->translations['ObjectPeople*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/messages',
            'fileMap' => [
                'ObjectPeople' => 'ObjectPeople.php',
            ],
        ];
    }

    public function toViewModel()
    {
        $model = new ViewModel();
        FHtml::setFieldValue($model, ['id'], $this->id);
        FHtml::setFieldValue($model, ['object_id'], $this->object_id);
        FHtml::setFieldValue($model, ['object_type'], $this->object_type);
        FHtml::setFieldValue($model, ['name'], $this->name);
        FHtml::setFieldValue($model, ['title'], $this->title);
        FHtml::setFieldValue($model, ['phone'], $this->phone);
        FHtml::setFieldValue($model, ['email'], $this->email);
        FHtml::setFieldValue($model, ['address'], $this->address);
        FHtml::setFieldValue($model, ['facebook'], $this->facebook);
        FHtml::setFieldValue($model, ['google'], $this->google);
        FHtml::setFieldValue($model, ['linkedin'], $this->linkedin);
        FHtml::setFieldValue($model, ['skype'], $this->skype);
        FHtml::setFieldValue($model, ['instagram'], $this->instagram);
        FHtml::setFieldValue($model, ['twitter'], $this->twitter);
        FHtml::setFieldValue($model, ['yahoo'], $this->yahoo);
        FHtml::setFieldValue($model, ['sort_order'], $this->sort_order);
        FHtml::setFieldValue($model, ['is_active'], $this->is_active);
        FHtml::setFieldValue($model, ['application_id'], $this->application_id);
        return $model;
    }
}
