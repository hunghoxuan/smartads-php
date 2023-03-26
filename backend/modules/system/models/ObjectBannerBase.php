<?php

namespace backend\modules\system\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "object_banner".
 *
 * @property integer $id
 * @property integer $object_id
 * @property string $object_type
 * @property string $image
 * @property string $title
 * @property string $link_url
 * @property string $platform
 * @property string $position
 * @property string $type
 * @property integer $sort_order
 * @property integer $is_active
 * @property string $application_id
 */
class ObjectBannerBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{
    const PLATFORM_ANDROID = 'android';
    const PLATFORM_IOS = 'ios';
    const PLATFORM_WEB = 'web';
    const PLATFORM_ALL = 'all';
    const POSITION_DASHBOARD = 'dashboard';
    const POSITION_TOP = 'top';
    const POSITION_BOTTOM = 'bottom';
    const POSITION_LEFT = 'left';
    const POSITION_RIGHT = 'right';
    const POSITION_CENTER = 'center';
    const POSITION_MIDDLE = 'middle';
    const TYPE_BANNER = 'banner';
    const TYPE_BLOCK = 'block';
    const TYPE_VERTICAL = 'vertical';
    const TYPE_HORIZONTAL = 'horizontal';
    const TYPE_FULL_SCREEN = 'full-screen';

    /**
     * @inheritdoc
     */
    public $tableName = 'object_banner';

    public static function tableName()
    {
        return 'object_banner';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'object_id', 'object_type', 'image', 'title', 'link_url', 'platform', 'position', 'type', 'sort_order', 'is_active', 'application_id'], 'filter', 'filter' => 'trim'],
            [['object_id', 'sort_order', 'is_active'], 'integer'],
            [['title', 'is_active'], 'required'],
            [['object_type', 'platform', 'position', 'type', 'application_id'], 'string', 'max' => 100],
            [['image'], 'string', 'max' => 300],
            [['title'], 'string', 'max' => 255],
            [['link_url'], 'string', 'max' => 1000],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('ObjectBanner', 'ID'),
            'object_id' => FHtml::t('ObjectBanner', 'Object ID'),
            'object_type' => FHtml::t('ObjectBanner', 'Object Type'),
            'image' => FHtml::t('ObjectBanner', 'Image'),
            'title' => FHtml::t('ObjectBanner', 'Title'),
            'link_url' => FHtml::t('ObjectBanner', 'Link Url'),
            'platform' => FHtml::t('ObjectBanner', 'Platform'),
            'position' => FHtml::t('ObjectBanner', 'Position'),
            'type' => FHtml::t('ObjectBanner', 'Type'),
            'sort_order' => FHtml::t('ObjectBanner', 'Sort Order'),
            'is_active' => FHtml::t('ObjectBanner', 'Is Active'),
            'application_id' => FHtml::t('ObjectBanner', 'Application ID'),
        ];
    }


}