<?php

namespace backend\modules\system\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "object_content".
 *
 * @property string $id
 * @property string $object_id
 * @property string $object_type
 * @property string $image
 * @property string $name
 * @property string $description
 * @property string $content
 * @property integer $sort_order
 * @property integer $is_active
 * @property string $created_date
 * @property string $created_user
 * @property string $application_id
 */
class ObjectContentBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public $tableName = 'object_content';

    public static function tableName()
    {
        return 'object_content';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'object_id', 'object_type', 'image', 'name', 'description', 'content', 'sort_order', 'is_active', 'created_date', 'created_user', 'application_id'], 'filter', 'filter' => 'trim'],
            [['object_id'], 'required'],
            [['content'], 'string'],
            [['sort_order', 'is_active'], 'integer'],
            [['created_date'], 'safe'],
            [['object_id', 'object_type', 'created_user', 'application_id'], 'string', 'max' => 100],
            [['image'], 'string', 'max' => 300],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 2000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('ObjectContent', 'ID'),
            'object_id' => FHtml::t('ObjectContent', 'Object ID'),
            'object_type' => FHtml::t('ObjectContent', 'Object Type'),
            'image' => FHtml::t('ObjectContent', 'Image'),
            'name' => FHtml::t('ObjectContent', 'Name'),
            'description' => FHtml::t('ObjectContent', 'Description'),
            'content' => FHtml::t('ObjectContent', 'Content'),
            'sort_order' => FHtml::t('ObjectContent', 'Sort Order'),
            'is_active' => FHtml::t('ObjectContent', 'Is Active'),
            'created_date' => FHtml::t('ObjectContent', 'Created Date'),
            'created_user' => FHtml::t('ObjectContent', 'Created User'),
            'application_id' => FHtml::t('ObjectContent', 'Application ID'),
        ];
    }

    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        $i18n = Yii::$app->i18n;
        $i18n->translations['ObjectContent*'] = [
            'class' => 'common\components\FMessageSource',
            'basePath' => '@backend/modules/system/messages',
            'fileMap' => [
                'ObjectContent' => 'ObjectContent.php',
            ],
        ];
    }
}