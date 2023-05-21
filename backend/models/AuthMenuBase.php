<?php

namespace backend\models;

use backend\modules\system\models\SettingsMenu;
use common\models\BaseDataObject;
use Yii;
use yii\db\ActiveRecord;

/**



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
class AuthMenuBase extends \common\models\BaseModel
{
    const GROUP_FRONTEND = FRONTEND;
    const GROUP_BACKEND = BACKEND;

    /**
     * @inheritdoc
     */
    public $tableName = 'settings_menu';

    public static function tableName()
    {
        return 'settings_menu';
    }

    public function getRoute()
    {
        return $this->url;
    }

    public function setRoute($value)
    {
        $this->url = $value;
    }
}
