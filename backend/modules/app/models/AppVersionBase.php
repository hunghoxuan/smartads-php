<?php

namespace backend\modules\app\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "app_version".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $package_version
 * @property string $package_name
 * @property string $platform
 * @property string $platform_info
 * @property string $file
 * @property integer $count_views
 * @property integer $count_downloads
 * @property integer $is_active
 * @property integer $is_default
 * @property string $history
 * @property string $created_date
 * @property string $created_user
 * @property string $modified_date
 * @property string $modified_user
 * @property string $application_id
 */
class AppVersionBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public $tableName = 'app_version';

    public static function tableName()
    {
        return 'app_version';
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
            [['id', 'name', 'description', 'package_version', 'package_name', 'platform', 'platform_info', 'file', 'count_views', 'count_downloads', 'is_active', 'is_default', 'history', 'created_date', 'created_user', 'modified_date', 'modified_user', 'application_id'], 'filter', 'filter' => 'trim'],
            [['name', 'file', 'is_active', 'is_default'], 'required'],
            [['package_version', 'count_views', 'count_downloads', 'is_active', 'is_default'], 'integer'],
            [['history'], 'string'],
            [['created_date', 'modified_date'], 'safe'],
            [['name', 'package_name', 'platform'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 2000],
            [['platform_info'], 'string', 'max' => 1000],
            [['file'], 'string', 'max' => 300],
            [['created_user', 'modified_user', 'application_id'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('AppVersion', 'ID'),
            'name' => FHtml::t('AppVersion', 'Name'),
            'description' => FHtml::t('AppVersion', 'Description'),
            'package_version' => FHtml::t('AppVersion', 'Package Version'),
            'package_name' => FHtml::t('AppVersion', 'Package Name'),
            'platform' => FHtml::t('AppVersion', 'Platform'),
            'platform_info' => FHtml::t('AppVersion', 'Platform Info'),
            'file' => FHtml::t('AppVersion', 'File'),
            'count_views' => FHtml::t('AppVersion', 'Count Views'),
            'count_downloads' => FHtml::t('AppVersion', 'Count Downloads'),
            'is_active' => FHtml::t('AppVersion', 'Is Active'),
            'is_default' => FHtml::t('AppVersion', 'Is Default'),
            'history' => FHtml::t('AppVersion', 'History'),
            'created_date' => FHtml::t('AppVersion', 'Created Date'),
            'created_user' => FHtml::t('AppVersion', 'Created User'),
            'modified_date' => FHtml::t('AppVersion', 'Modified Date'),
            'modified_user' => FHtml::t('AppVersion', 'Modified User'),
            'application_id' => FHtml::t('AppVersion', 'Application ID'),
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
        $i18n->translations['AppVersion*'] = [
            'class' => 'common\components\FMessageSource',
            'basePath' => '@backend/modules/app/messages',
            'fileMap' => [
                'AppVersion' => 'AppVersion.php',
            ],
        ];
    }
}