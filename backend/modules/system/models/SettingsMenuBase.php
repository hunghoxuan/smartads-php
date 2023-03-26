<?php

namespace backend\modules\system\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseDataObject;
use frontend\models\ViewModel;

/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the model class for table "settings_menu".
 *
 * @property integer $id
 * @property string $icon
 * @property string $name
 * @property string $url
 * @property string $object_type
 * @property string $module
 * @property string $group
 * @property string $role
 * @property string $menu_type
 * @property string $display_type
 * @property integer $sort_order
 * @property integer $is_active
 * @property string $created_date
 * @property string $created_user
 * @property string $modified_date
 * @property string $modified_user
 * @property string $application_id
 */
class SettingsMenuBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{
    const GROUP_FRONTEND = FRONTEND;
    const GROUP_BACKEND = BACKEND;
    const MENU_TYPE_CATEGORY = 'CATEGORY';
    const MENU_TYPE_TYPE = 'TYPE';
    const MENU_TYPE_STATUS = 'STATUS';
    const MENU_TYPE_MIXED = 'MIXED';
    const DISPLAY_TYPE_DEFAULT = 'DEFAULT';
    const DISPLAY_TYPE_TREE = 'TREE';
    const DISPLAY_TYPE_MEGA = 'MEGA';

// id, icon, name, url, object_type, module, group, role, menu_type, display_type, sort_order, is_active, created_date, created_user, modified_date, modified_user, application_id
    const COLUMN_ID = 'id';
    const COLUMN_ICON = 'icon';
    const COLUMN_NAME = 'name';
    const COLUMN_URL = 'url';
    const COLUMN_OBJECT_TYPE = 'object_type';
    const COLUMN_MODULE = 'module';
    const COLUMN_GROUP = 'group';
    const COLUMN_ROLE = 'role';
    const COLUMN_MENU_TYPE = 'menu_type';
    const COLUMN_DISPLAY_TYPE = 'display_type';
    const COLUMN_SORT_ORDER = 'sort_order';
    const COLUMN_IS_ACTIVE = 'is_active';
    const COLUMN_CREATED_DATE = 'created_date';
    const COLUMN_CREATED_USER = 'created_user';
    const COLUMN_MODIFIED_DATE = 'modified_date';
    const COLUMN_MODIFIED_USER = 'modified_user';
    const COLUMN_APPLICATION_ID = 'application_id';

    /**
     * @inheritdoc
     */
    public $tableName = 'settings_menu';

    public static function tableName()
    {
        return 'settings_menu';
    }



}
