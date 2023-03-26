<?php

namespace backend\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "object_collection".
 *
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $object_type
 * @property integer $is_active
 * @property integer $sort_order
 * @property string $application_id
 */
class ObjectCollectionBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public $tableName = 'object_collection';

    public static function tableName()
    {
        return 'object_collection';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return FHtml::currentDb();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name', 'description', 'object_type', 'is_active', 'sort_order', 'application_id'], 'filter', 'filter' => 'trim'],
            [['name', 'object_type', 'is_active'], 'required'],
            [['is_active', 'sort_order'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 1000],
            [['object_type', 'application_id'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('ObjectCollection', 'ID'),
            'name' => FHtml::t('ObjectCollection', 'Name'),
            'description' => FHtml::t('ObjectCollection', 'Description'),
            'object_type' => FHtml::t('ObjectCollection', 'Object Type'),
            'is_active' => FHtml::t('ObjectCollection', 'Is Active'),
            'sort_order' => FHtml::t('ObjectCollection', 'Sort Order'),
            'application_id' => FHtml::t('ObjectCollection', 'Application ID'),
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
        $i18n->translations['ObjectCollection*'] = [
            'class' => 'common\components\FMessageSource',
            'basePath' => '@backend/messages',
            'fileMap' => [
                'ObjectCollection' => 'ObjectCollection.php',
            ],
        ];
    }
}