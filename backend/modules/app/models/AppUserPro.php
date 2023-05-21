<?php

namespace backend\modules\app\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**



 * This is the customized model class for table "app_user_pro".
 */
class AppUserPro extends AppUserProBase //\yii\db\ActiveRecord
{
    const LOOKUP = [];

    const COLUMNS_UPLOAD = [];

    public $order_by = 'is_active desc,created_date desc,';

    const OBJECTS_RELATED = [];
    const OBJECTS_META = [];
    const COLUMNS = [
        'api' => ['user_id', 'rate', 'rate_count', 'description', 'business_name', 'business_email', 'business_address', 'business_website', 'business_phone', 'is_active', 'created_date', 'modified_date',],
        'all' => ['user_id', 'rate', 'rate_count', 'description', 'business_name', 'business_email', 'business_address', 'business_website', 'business_phone', 'is_active', 'created_date', 'modified_date',  'objectAttributes', 'objectFile', 'objectCategories'],
        '+' => ['objectAttributes', 'objectFile', 'objectCategories']
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['user_id', 'rate', 'rate_count', 'description', 'business_name', 'business_email', 'business_address', 'business_website', 'business_phone', 'is_active', 'created_date', 'modified_date'], 'filter', 'filter' => 'trim'],

            [['user_id', 'is_active'], 'required'],
            [['user_id', 'rate_count', 'is_active'], 'integer'],
            [['rate'], 'number'],
            [['created_date', 'modified_date'], 'safe'],
            [['description'], 'string', 'max' => 500],
            [['business_name', 'business_email', 'business_address', 'business_website'], 'string', 'max' => 255],
            [['business_phone'], 'string', 'max' => 20],
            [['user_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => FHtml::t('AppUserPro', 'User ID'),
            'rate' => FHtml::t('AppUserPro', 'Rate'),
            'rate_count' => FHtml::t('AppUserPro', 'Rate Count'),
            'description' => FHtml::t('AppUserPro', 'Description'),
            'business_name' => FHtml::t('AppUserPro', 'Business Name'),
            'business_email' => FHtml::t('AppUserPro', 'Business Email'),
            'business_address' => FHtml::t('AppUserPro', 'Business Address'),
            'business_website' => FHtml::t('AppUserPro', 'Business Website'),
            'business_phone' => FHtml::t('AppUserPro', 'Business Phone'),
            'is_active' => FHtml::t('AppUserPro', 'Is Active'),
            'created_date' => FHtml::t('AppUserPro', 'Created Date'),
            'modified_date' => FHtml::t('AppUserPro', 'Modified Date'),
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
        $i18n->translations['AppUserPro*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/messages',
            'fileMap' => [
                'AppUserPro' => 'AppUserPro.php',
            ],
        ];
    }

    public function toViewModel()
    {
        $model = new ViewModel();
        FHtml::setFieldValue($model, ['user_id'], $this->user_id);
        FHtml::setFieldValue($model, ['rate'], $this->rate);
        FHtml::setFieldValue($model, ['rate_count'], $this->rate_count);
        FHtml::setFieldValue($model, ['description'], $this->description);
        FHtml::setFieldValue($model, ['business_name'], $this->business_name);
        FHtml::setFieldValue($model, ['business_email'], $this->business_email);
        FHtml::setFieldValue($model, ['business_address'], $this->business_address);
        FHtml::setFieldValue($model, ['business_website'], $this->business_website);
        FHtml::setFieldValue($model, ['business_phone'], $this->business_phone);
        FHtml::setFieldValue($model, ['is_active'], $this->is_active);
        FHtml::setFieldValue($model, ['created_date'], $this->created_date);
        FHtml::setFieldValue($model, ['modified_date'], $this->modified_date);
        return $model;
    }
}
