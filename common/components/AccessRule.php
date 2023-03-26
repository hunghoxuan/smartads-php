<?php
namespace common\components;
use backend\models\AuthPermission;
use common\models\User;
use Yii;
use yii\helpers\Json;

class AccessRule extends \yii\filters\AccessRule {

    protected function matchRole($user)
    {
        if (FHtml::isRoleAdmin())
            return true;

        if (empty($this->roles)) {
            return false;
        }

        $rights = array();
        if (!$user->getIsGuest()) {
            $rights = FSecurity::getUserRoles($user);
        }

        if (empty($this->roles) || in_array(User::ROLE_ADMIN, $rights) || in_array('admin', $rights)) {
            return true;
        }

        foreach ($this->roles as $role) {
            if ($role === User::ROLE_NONE) {
                if ($user->getIsGuest()) {
                    return true;
                }
            } elseif ($role === User::ROLE_ALL) {
                if (!$user->getIsGuest()) {
                    return true;
                }
            } elseif (!$user->getIsGuest() && in_array($role, $rights)) {
                return true;
            }
        }

        $module = FHtml::currentModule();
        $controller = FHtml::currentController();
        $action = FHtml::currentAction();

        return self::checkAccess(FSecurity::getRoles($this->roles), $module, $controller, $action);
    }

    public static function checkAccess($rights, $module = '', $controller = '', $action = '') {
        //1. check user access
        $check_user_access = FSecurity::checkUserAccess();
        if ($check_user_access)
            return true;

        //1. check hash paramter in url
        $check_hash = FSecurity::checkToken();
        if (isset($check_hash))
            return $check_hash;

        $identity = FHtml::currentUserIdentity();

        if (!isset($identity))
            return false;

        //3. check roles
        $result = FSecurity::isInRoles($rights, $module, $controller, $action) ;
        return $result;
    }
}