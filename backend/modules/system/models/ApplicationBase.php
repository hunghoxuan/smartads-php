<?php

namespace backend\modules\system\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseDataObject;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 *
 ***
 * This is the model class for table "application".
 *

 * @property integer $id
 * @property string $logo
 * @property string $code
 * @property string $name
 * @property string $description
 * @property string $keywords
 * @property string $note
 * @property string $lang
 * @property string $modules
 * @property string $storage_max
 * @property string $storage_current
 * @property string $address
 * @property string $map
 * @property string $website
 * @property string $email
 * @property string $phone
 * @property string $fax
 * @property string $chat
 * @property string $facebook
 * @property string $twitter
 * @property string $google
 * @property string $youtube
 * @property string $copyright
 * @property string $terms_of_service
 * @property string $profile
 * @property string $privacy_policy
 * @property integer $is_active
 * @property string $type
 * @property string $status
 * @property string $owner_id
 * @property string $created_date
 * @property string $created_user
 * @property string $modified_date
 * @property string $modified_user
 */
class ApplicationBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{
    const TYPE_ONEPAGE = 'ONEPAGE';
    const TYPE_COMPANY = 'COMPANY';
    const TYPE_ECOMMERCE = 'ECOMMERCE';
    const TYPE_SOCIAL = 'SOCIAL';
    const TYPE_MUSIC = 'MUSIC';
    const TYPE_EDUCATION = 'EDUCATION';
    const STATUS_DEMO = 'DEMO';
    const STATUS_LIVE = 'LIVE';
    const STATUS_CLOSED = 'CLOSED';
    const STATUS_SUSPEND = 'SUSPEND';
    const ADMIN_FORM_ALIGNMENT_VERTICAL = 'vertical';
    const ADMIN_FORM_ALIGNMENT_HORIZONTAL = 'horizontal';
    const ADMIN_FORM_ALIGNMENT_INLINE = 'inline';
    /**
     * @inheritdoc
     */
    public $tableName = 'application';

    public static function tableName()
    {
        return 'application';
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
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('Application', 'ID'),
            'logo' => FHtml::t('Application', 'Logo'),
            'code' => FHtml::t('Application', 'Code'),
            'name' => FHtml::t('Application', 'Name'),
            'description' => FHtml::t('Application', 'Description'),
            'keywords' => FHtml::t('Application', 'Keywords'),
            'note' => FHtml::t('Application', 'Note'),
            'lang' => FHtml::t('Application', 'Lang'),
            'modules' => FHtml::t('Application', 'Modules'),
            'storage_max' => FHtml::t('Application', 'Storage Max'),
            'storage_current' => FHtml::t('Application', 'Storage Current'),
            'address' => FHtml::t('Application', 'Address'),
            'map' => FHtml::t('Application', 'Map'),
            'website' => FHtml::t('Application', 'Website'),
            'email' => FHtml::t('Application', 'Email'),
            'phone' => FHtml::t('Application', 'Phone'),
            'fax' => FHtml::t('Application', 'Fax'),
            'chat' => FHtml::t('Application', 'Chat'),
            'facebook' => FHtml::t('Application', 'Facebook'),
            'twitter' => FHtml::t('Application', 'Twitter'),
            'google' => FHtml::t('Application', 'Google'),
            'youtube' => FHtml::t('Application', 'Youtube'),
            'copyright' => FHtml::t('Application', 'Copyright'),
            'terms_of_service' => FHtml::t('Application', 'Terms Of Service'),
            'profile' => FHtml::t('Application', 'Profile'),
            'privacy_policy' => FHtml::t('Application', 'Privacy Policy'),
            'is_active' => FHtml::t('Application', 'Is Active'),
            'type' => FHtml::t('Application', 'Type'),
            'status' => FHtml::t('Application', 'Status'),
            'owner_id' => FHtml::t('Application', 'Owner ID'),
            'created_date' => FHtml::t('Application', 'Created Date'),
            'created_user' => FHtml::t('Application', 'Created User'),
            'modified_date' => FHtml::t('Application', 'Modified Date'),
            'modified_user' => FHtml::t('Application', 'Modified User'),
        ];
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
        $i18n->translations['Application*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/modules/system/messages',
            'fileMap' => [
                'Application' => 'Application.php',
            ],
        ];
    }
}
