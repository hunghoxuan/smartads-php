<?php

namespace backend\modules\smartscreen\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "smartscreen_content".
 *
 * @property integer $id
 * @property string $title
 * @property string $url
 * @property string $description
 * @property string $type
 * @property string $kind
 * @property integer $duration
 * @property string $expire_date
 * @property integer $sort_order
 * @property string $owner_id
 * @property integer $is_active
 * @property integer $is_default
 * @property integer $created_date
 * @property integer $modified_date
 * @property string $application_id
 *
 * @property SmartscreenFile[] $smartscreenFiles
 */
class SmartscreenContentBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public $tableName = 'smartscreen_content';

    public static function tableName()
    {
        return 'smartscreen_content';
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
            [['id', 'title', 'url', 'description', 'type', 'kind', 'duration', 'expire_date', 'sort_order', 'owner_id', 'is_active', 'is_default', 'created_date', 'modified_date', 'application_id'], 'filter', 'filter' => 'trim'],
            [['title', 'created_date'], 'required'],
            [['description'], 'string'],
            [['duration', 'sort_order', 'is_active', 'is_default', 'created_date', 'modified_date'], 'integer'],
            [['title', 'url', 'type'], 'string', 'max' => 255],
            [['kind', 'owner_id', 'application_id'], 'string', 'max' => 100],
            [['expire_date'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('SmartscreenContent', 'ID'),
            'title' => FHtml::t('SmartscreenContent', 'Title'),
            'url' => FHtml::t('SmartscreenContent', 'Url'),
            'description' => FHtml::t('SmartscreenContent', 'Description'),
            'type' => FHtml::t('SmartscreenContent', 'Type'),
            'kind' => FHtml::t('SmartscreenContent', 'Kind'),
            'duration' => FHtml::t('SmartscreenContent', 'Duration'),
            'expire_date' => FHtml::t('SmartscreenContent', 'Expire Date'),
            'sort_order' => FHtml::t('SmartscreenContent', 'Sort Order'),
            'owner_id' => FHtml::t('SmartscreenContent', 'Owner ID'),
            'is_active' => FHtml::t('SmartscreenContent', 'Is Active'),
            'is_default' => FHtml::t('SmartscreenContent', 'Is Default'),
            'created_date' => FHtml::t('SmartscreenContent', 'Created Date'),
            'modified_date' => FHtml::t('SmartscreenContent', 'Modified Date'),
            'application_id' => FHtml::t('SmartscreenContent', 'Application ID'),
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
        $i18n->translations['SmartscreenContent*'] = [
            'class' => 'common\components\FMessageSource',
            'basePath' => '@backend/modules/smartscreen/messages',
            'fileMap' => [
                'SmartscreenContent' => 'SmartscreenContent.php',
            ],
        ];
    }

}