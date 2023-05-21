<?php

namespace backend\modules\smartscreen\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "smartscreen_channels".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $image
 * @property string $content_id
 * @property string $layout_id
 * @property integer $is_active
 * @property integer $is_default
 * @property string $devices
 * @property string $created_date
 * @property string $created_user
 * @property string $application_id
 */
class SmartscreenChannelsBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public $tableName = 'smartscreen_channels';

    public static function tableName()
    {
        return 'smartscreen_channels';
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
            [['id', 'name', 'description', 'image', 'content_id', 'is_active', 'is_default', 'devices', 'layout_id', 'created_date', 'created_user', 'application_id'], 'filter', 'filter' => 'trim'],
            [['name'], 'required'],
            [['content_id', 'devices', 'layout_id'], 'string'],
            [['is_active', 'is_default'], 'integer'],
            [['created_date'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 2000],
            [['image'], 'string', 'max' => 300],
            [['created_user', 'application_id'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('SmartscreenChannels', 'ID'),
            'name' => FHtml::t('SmartscreenChannels', 'Name'),
            'description' => FHtml::t('SmartscreenChannels', 'Description'),
            'image' => FHtml::t('SmartscreenChannels', 'Image'),
            'content_id' => FHtml::t('SmartscreenChannels', 'Content'),
            'is_active' => FHtml::t('SmartscreenChannels', 'Is Active'),
            'is_default' => FHtml::t('SmartscreenChannels', 'Is Default'),
            'devices' => FHtml::t('SmartscreenChannels', 'Devices'),
            'created_date' => FHtml::t('SmartscreenChannels', 'Created Date'),
            'created_user' => FHtml::t('SmartscreenChannels', 'Created User'),
            'application_id' => FHtml::t('SmartscreenChannels', 'Application ID'),
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
        $i18n->translations['SmartscreenChannels*'] = [
            'class' => 'common\components\FMessageSource',
            'basePath' => '@backend/modules/smartscreen/messages',
            'fileMap' => [
                'SmartscreenChannels' => 'SmartscreenChannels.php',
            ],
        ];
    }

    public function getDefaultValue($field, $default_value = null)
    {
        if ($field == 'created_date') {
            FHtml::var_dump($field);
            return date('Y-m-d H:i:s');
        }
        return parent::getDefaultValue($field, $default_value);
    }
}
