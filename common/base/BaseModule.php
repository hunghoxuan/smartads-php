<?php

namespace common\base;

use backend\models\AuthPermission;
use backend\models\User;
use common\components\FConstant;
use common\components\FHtml;
use common\components\FSecurity;
use common\models\BaseModel;
use Yii;
use yii\base\NotSupportedException;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $first_name
 * @property string $last_name
 * @property string $image
 * @property string $overview
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property string $role
 * @property string $application_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */

class BaseModule extends \yii\base\Module
{
    public static function getCategoriesObjectType() {
        $currentModule = FHtml::currentModule();
        return [$currentModule];
    }

    public static function getDefaultObjectType() {
        $arr = static::getCategoriesObjectType();
        if (!empty($arr))
            return $arr[0];
        return '';
    }

    public static function getLookupArray($column = '')
    {
        return [];
    }

    public static function getLookupList() {
        return [];
    }

    public static function createModuleMenu($menu = []) {
        return [];
    }

    public static function getSettingsTypes() {
        if (null !== static::LOOKUP)
            return static::LOOKUP;
        return [];
    }
}
