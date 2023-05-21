<?php

/*This is the customized model class for table "<?= $generator->generateTableName($tableName) ?>".
 */

namespace common\base;

use backend\models\AppUser;
use backend\models\AuthGroup;
use backend\models\AuthMenu;
use backend\models\AuthPermission;
use backend\models\AuthRole;
use backend\models\User;
use backend\modules\users\models\UserLogs;
use common\components\AccessRule;
use common\components\FError;
use common\components\FFile;
use common\components\FHtml;
use common\components\FSecurity;
use common\models\LoginForm;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use yii\helpers\Json;
use yii\helpers\StringHelper;

class BaseSecurity extends FFile
{
    const USER_NAME_SUPERADMIN = ['root', 'superadmin', 'sysadmin'];
    const USER_NAME_ADMIN = ['admin'];
    const TABLE_USER = 'user';
    const TABLE_APP_USER = 'user';

    public static function currentUserModel($zone = '')
    {
        if (empty($zone))
            $zone = FHtml::currentZone();

        if ($zone === FRONTEND) {
            $appuser = \Yii::$app->appuser;
            $user = \Yii::$app->user;

            if (isset($user->identity))
                return self::currentBackendUser();

            return $appuser;
        }

        return self::currentBackendUser();
    }

    public static function currentBackendUser()
    {
        $user = self::currentUser(BACKEND);

        return $user;
    }

    public static function currentUser($zone = '')
    {
        if (empty($zone))
            $zone = FHtml::currentZone();

        if ($zone === FRONTEND) {
            $appuser = \Yii::$app->appuser;
            $user = \Yii::$app->user;

            if (isset($user->identity))
                return $user;

            return $appuser;
        }

        $user = \Yii::$app->user;

        return $user;
    }


    public static function getAuthorizedMenu($menu = [])
    {
        return $menu;
    }

    public static function addBackendUser($username, $email = '', $password = '123456', $role = User::ROLE_USER)
    {
        return self::addUser($username, $email, $password, $role);
    }

    public static function addUser($username, $email = '', $password = DEFAULT_PASSWORD, $role = User::ROLE_USER, $isBackend = BACKEND)
    {
        if (is_object($username)) {
            $model = $username;
            $username = FHtml::getFieldValue($model, ['username', 'email', 'name']);
            $username = strtolower(str_replace(' ', '_', $username));
            $email = FHtml::getFieldValue($model, ['email']);
            $password = FHtml::getFieldValue($model, ['password'], DEFAULT_PASSWORD);
            $role = FHtml::getFieldValue($model, ['role'], User::ROLE_USER);
        } else {
            $table_name = '';
            if ($isBackend == true || $isBackend == BACKEND) {
                $table_name = self::TABLE_USER;
                $model = FHtml::getModel($table_name, '', ['username' => $username]);
            } else {
                $table_name = self::TABLE_APP_USER;
                $model = FHtml::getModel($table_name, '', ['username' => $username]);
            }
        }

        if (self::isRootUser($username) && !FHtml::isRoleAdmin()) {
            FHtml::addError(FHtml::t('common', 'Can not use this username. Please select another username.'));
            return false;
        }

        if ($model->isNewRecord) {
            $model->username = $username;

            if (empty($model->name))
                $model->name = $username;

            $model->email = !empty($email) ? $email : (strpos('@', $username) ? $username : '');
            $model->status = FHtml::USER_STATUS_ACTIVE;
            $model->created_at = time();
            $model->updated_at = time();

            $model->setPassword($password);
            $model->generateAuthKey();
            $model->generatePasswordResetToken();
            $model->role = $role;

            if (!$model->save()) {
                FHtml::addError($model->errors);
                return false;
            }
        }

        return $model;
    }

    public static function isRootUser($username = '')
    {
        if (empty($username)) {
            return self::isRoleAdmin() && in_array(FHtml::currentUsername(), self::USER_NAME_SUPERADMIN);
        } else {
            return in_array($username, self::USER_NAME_SUPERADMIN);
        }
    }

    public static function isRoleAdmin($userid = '')
    {
        if (empty($userid))
            $role = FHtml::getCurrentRole();
        else {
            $role = FHtml::getFieldValue($userid, 'role');
        }

        return $role == \common\models\User::ROLE_ADMIN;
    }

    public static function getCurrentRole()
    {
        $identity = self::currentUserIdentity();

        if (isset($identity))
            return $identity->role;
        else
            return FHtml::ROLE_NONE;
    }

    public static function currentUserIdentity()
    {
        $user = self::currentUser();
        return isset($user->identity) ? $user->identity : null;
    }

    public static function addFrontendUser($username, $email = '', $password = DEFAULT_PASSWORD, $role = User::ROLE_USER)
    {
        return self::addAppUser($username, $email, $password, $role);
    }

    public static function addAppUser($username, $email = '', $password = DEFAULT_PASSWORD, $role = User::ROLE_USER)
    {
        return self::addUser($username, $email, $password, $role, FRONTEND);
    }

    public static function getUser($username, $isBackend = BACKEND)
    {
        $zone = FHtml::currentZone();

        if ($isBackend === true || $isBackend === BACKEND) {
            $user = User::findByUsername($username);
        } else {
            $user = AppUser::findByUsername($username);
        }
        return $user;
    }

    public static function getUserName($username)
    {
        if (in_array($username, self::USER_NAME_ADMIN) && APPLICATIONS_ENABLED) {
            $username = $username . '_' . FHtml::currentApplicationId();
        }

        return $username;
    }

    public static function setUserPassword($model, $password_new = '')
    {
        if (empty($password_new))
            $password_new = DEFAULT_PASSWORD;

        $model->setPassword($password_new);
        $model->generateAuthKey();
        $model->generatePasswordResetToken();
        return $model;
    }

    //HungHX: 20160801

    public static function isRoleUser($userid = '')
    {
        if (empty($userid))
            $role = FHtml::getCurrentRole();
        else {
            $role = FHtml::getFieldValue($userid, 'role');
        }
        return $role == \common\models\User::ROLE_USER;
    }

    public static function isRoleModerator($userid = '')
    {
        if (empty($userid))
            $role = FHtml::getCurrentRole();
        else {
            $role = FHtml::getFieldValue($userid, 'role');
        }
        return $role == \common\models\User::ROLE_ADMIN || $role == \common\models\User::ROLE_MODERATOR;
    }

    public static function isAuthorized($action, $object_type, $field, $form_name = '', $form_type = '', $role = '', $userid = '', $manualValue = false)
    {
        if (is_object($object_type))
            $object_type = FHtml::getTableName($object_type);

        $object_type = str_replace('-', '_', BaseInflector::camel2id($object_type));

        if (FHtml::isInArray($field, ['id', 'application_id']) && $action != self::ACTION_VIEW)
            return false;

        $user = self::currentUser();
        if (!isset($user)) {
            return false;
        }

        if (empty($role))
            $role = FHtml::getCurrentRole();

        if ($role == FHTML::ROLE_NONE)
            return false;

        if ($role == self::ROLE_ADMIN)
            return true;

        if (empty($userid))
            $userid = FHtml::currentUserId();

        return self::isInRole($object_type, $action) || $manualValue;
    }

    public static function currentUserId()
    {
        $identity = self::currentUserIdentity();
        if (isset($identity))
            return $identity->getId();
        else
            return '';
    }

    public static function isInRole($object_type, $action, $role = '', $userid = '', $field = '')
    {
        if ($action == 'edit')
            $action = 'update';

        if ($action == 'add')
            $action = 'create';

        if ($action == 'view-detail') {
            $action = 'view';
        }

        if (empty($object_type))
            $object_type = str_replace('-', '_', FHtml::currentController());

        if (is_object($object_type))
            $object_type = FHtml::getTableName($object_type);

        $object_type = str_replace('-', '_', BaseInflector::camel2id($object_type));

        if (empty($role))
            $role = FHtml::getCurrentRole();


        $user = FSecurity::currentUser();
        if (!isset($user)) {
            return false;
        }

        if (in_array($action, ['update', 'edit']) && $user->id == $userid)
            return true;

        if ($user->isGuest && $role != FHtml::ROLE_NONE) {
            return false;
        }

        if ($role == \common\models\User::ROLE_ADMIN) {
            return true; // can do any thing
        }

        $module = FHtml::getModelModule($object_type);
        $controller = str_replace('_', '-', $object_type);

        $rules = FHtml::getControllerRules($object_type);

        if (!empty($rules)) {
            foreach ($rules as $i => $rule) {
                $actions = FHtml::getFieldValue($rule, 'actions');
                if (is_array($actions) && in_array($action, $actions)) {
                    $rights = FHtml::getFieldValue($rule, 'roles');
                    return FHtml::getFieldValue($rule, 'allow', false) && FSecurity::isInRoles($rights, $module, $controller, $action);
                }
            }

            return false;
        }

        if ($role == \common\models\User::ROLE_MODERATOR) {
            return in_array($action, ['view', 'index', 'create', 'add', 'edit', 'update']);
        }

        if ($role == \common\models\User::ROLE_USER) {
            return in_array($action, ['view', 'index']);
        }

        if (strlen($role) > 0) {
            return $user->can($role);
        }

        if (strlen($action) > 0)
            return $user->can($action);

        return false;
    }

    public static function getControllerRules($controller = null)
    {
        if (is_string($controller))
            $controller = FHtml::getControllerObject($controller);

        if (is_object($controller) && isset($controller)) {
            $arr = $controller->behaviors();
            if (key_exists('access', $arr))
                $arr = $arr['access'];
            else
                return null;
            if (key_exists('rules', $arr))
                $rules = $arr['rules'];
            else
                return null;
            return $rules;
        }
        return null;
    }

    public static function isUserActionAuthorized($user = null, $action = '', $table = '')
    {
        if (empty($action))
            $action = FHtml::currentAction();

        if (!isset($user))
            $user = FHtml::currentUser();

        $roles = FHtml::getUserRoles($user);

        $rules = FSecurity::getControllerRules($table);

        foreach ($rules as $i => $rule) {

            if (key_exists('actions', $rule) && in_array($action, $rule['actions'])) {
                if (key_exists('roles', $rule) && (is_array($roles) && array_intersect($roles, $rule['roles'])) || (is_string($roles) && in_array($roles, $rule['roles'])))
                    return $rule['allow'];
            }
        }

        return false;
    }

    //HungHX: 20160801
    public static function isInRoles($roles, $module = '', $controller = '', $action = '', $user = null)
    {
        if (FHtml::isRoleAdmin())
            return true;

        if (\Yii::$app->user->isGuest) {
            return false;
        }

        if (empty($roles))
            $roles = [User::ROLE_ALL];

        if (is_string($roles))
            $roles = FHtml::decode($roles);

        $roles = self::getRoles($roles, $module, $controller, $action);
        foreach ($roles as $key => $value) {
            $roles[$key] = str_replace('-', '_', $value);
        }

        $user_roles = self::getUserRoles($user);
        foreach ($user_roles as $key => $value) {
            $user_roles[$key] = str_replace('-', '_', $value);
        }

        return (in_array(User::ROLE_ALL, $roles) || !empty(array_intersect($user_roles, $roles)));
    }

    public static function getRoles($roles, $module = '', $controller = '', $action = '')
    {
        $arr = [];

        if (empty($roles) || !isset($roles)) {
            $roles = [];
        }

        if (is_string($roles)) {
            $roles = [$roles];
        }

        foreach ($roles as $item) {
            if (key_exists($item, FHtml::ROLE_CODE_GROUPS))
                $arr[] = FHtml::ROLE_CODE_GROUPS[$item];
            else
                $arr[] = $item;
        }

        $roles1 = [User::ROLE_ADMIN];

        $object_type = strtolower(str_replace('-', '_', $controller));

        if (in_array($action, ['view', 'index'])) {
            if (!empty($module)) {
                $roles1 = array_merge($roles1, [strtolower($module)]);
                $roles1 = array_merge($roles1, [strtolower($module) . '/view']);
                $roles1 = array_merge($roles1, [strtolower($module) . '/index']);
                $roles1 = array_merge($roles1, [strtolower($module) . '/edit']);
                $roles1 = array_merge($roles1, [strtolower($module) . '/manage']);
                $roles1 = array_merge($roles1, [strtolower($module) . '/moderator']);
                $roles1 = array_merge($roles1, [strtolower($module) . '/admin']);
            }
            if (!empty($controller)) {
                $roles1 = array_merge($roles1, [$object_type]);
                $roles1 = array_merge($roles1, [$object_type . '/index']);
                $roles1 = array_merge($roles1, [$object_type . '/view']);
                $roles1 = array_merge($roles1, [$object_type . '/manage']);
                $roles1 = array_merge($roles1, [$object_type . '/edit']);
                $roles1 = array_merge($roles1, [$object_type . '/moderator']);
                $roles1 = array_merge($roles1, [$object_type . '/admin']);
            }
        } else if (in_array($action, ['add', 'update', 'create', 'edit', 'delete', 'bulk-action'])) {
            if (!empty($module)) {
                $roles1 = array_merge($roles1, [strtolower($module) . '/edit']);
                $roles1 = array_merge($roles1, [strtolower($module) . '/moderator']);
                $roles1 = array_merge($roles1, [strtolower($module) . '/manage']);
                $roles1 = array_merge($roles1, [strtolower($module) . '/admin']);
            }
            if (!empty($controller)) {
                $roles1 = array_merge($roles1, [$object_type . '/manage']);
                $roles1 = array_merge($roles1, [$object_type . '/admin']);
                $roles1 = array_merge($roles1, [$object_type . '/edit']);
                $roles1 = array_merge($roles1, [$object_type . '/moderator']);
                $roles1 = array_merge($roles1, [$object_type . '/' . strtolower($action)]);
            }
        } else if (in_array($action, ['populate', 'bulk-delete', 'reset'])) {
            if (!empty($module)) {
                $roles1 = array_merge($roles1, [strtolower($module) . '/manage']);
                $roles1 = array_merge($roles1, [strtolower($module) . '/admin']);
            }
            if (!empty($controller)) {
                $roles1 = array_merge($roles1, [$object_type . '/manage']);
                $roles1 = array_merge($roles1, [strtolower($module) . '/admin']);
                $roles1 = array_merge($roles1, [$object_type . '/' . strtolower($action)]);
            }
        } else {
            $roles1 = array_merge($roles1, [$object_type . '/' . strtolower($action)]);
        }

        $arr = array_merge($arr, $roles1);
        $arr = array_unique($arr);


        return $arr;
    }

    public static function getUserRoles($user = null)
    {
        if (!isset($user))
            $user = \Yii::$app->user;

        $roles = array();

        if (count($roles) != 0) {
            $rights = array_merge(array_unique($roles), [$user->identity->role]);
        } else {
            $rights = [$user->identity->role];
        }

        return $rights;
    }

    public static function getPermissions($object_type, $field, $form_name = '', $form_type = '', $role = '', $userid = '')
    {
        return null;
    }

    public static function logInBackend($model = null, $username = '', $password = '')
    {
        if (!isset($model))
            $model = new LoginForm();
        if (!empty($username) && !empty($password)) {
            $model->username = $username;
            $model->password = $password;
        } else {
            $model->load(\Yii::$app->request->post());
        }

        if ($model->login()) {

            $user = $model->getUser();

            $application_id = FHtml::getFieldValue($model->getUser(), 'application_id');
            if (!empty($application_id) && APPLICATIONS_ENABLED) {
                $application = FHtml::getApplication($application_id);
                if (isset($application)) {
                    FHtml::setApplicationId($application_id);
                    FHtml::setFieldValue($user, 'is_online', 1);
                    FHtml::setFieldValue($user, 'last_login', FHtml::Now());
                    $user->save();
                    return true;
                }
                FSecurity::logOut();
                return false;
            }

            return true;
        }

        return false;
    }

    public static function logOut()
    {
        FHtml::setApplicationId('');
        FHtml::refreshCache();
        FHtml::Session()->close();

        $user = FHtml::currentUserIdentity();
        if (isset($user)) {
            FHtml::setFieldValue($user, 'is_online', 0);
            FHtml::setFieldValue($user, 'last_logout', FHtml::Now());
            $user->save();
        }

        \Yii::$app->user->logout();
    }

    public static function getControllerBehaviours($rules = [], $controller = '')
    {
        return $rules;
    }

    public static function getApplicationUsersComboArray($displayName = 'username')
    {
        return ArrayHelper::map(FSecurity::getApplicationUsers(), 'id', $displayName);
    }

    public static function getApplicationUsers()
    {
        return FHtml::findAll('user');
    }

    public static function getApplicationRolesComboArray()
    {
        $arr = ArrayHelper::map(FSecurity::getApplicationRoles(), 'id', 'name');
        return $arr;
    }

    public static function getApplicationRoles()
    {
        $result = FHtml::findAll('auth_role');

        return $result;
    }

    public static function getRolesComboArray()
    {
        $arr = ['admin' => 'Admin', 'moderator' => 'Moderator', 'user' => 'User'];

        return $arr;
    }

    public static function getApplicationModulesComboArray()
    {
        return [];
    }

    public static function populateAuthItems()
    {
    }

    public static function populateAuthGroups()
    {
        return FSecurity::populateAuthItems();
    }

    public static function getUserGroupArray($user)
    {
        return [];
    }

    public static function getUserGroupModels($user)
    {
        return [];
    }

    public static function getUserRoleArray($user)
    {
        return [];
    }

    public static function getUserRoleModels($user)
    {
        return [];
    }

    public static function getGroupRoleModels($group)
    {

        $arr = [];
        return $arr;
    }

    public static function getApplicationGroupsComboArray()
    {
        return ArrayHelper::map(FSecurity::getApplicationGroups(), 'id', 'name');
    }

    public static function getApplicationGroups()
    {
        return [];
    }

    public static function updateUserGroups($userModel, $groups = [])
    {
    }

    public static function updateUserRoles($userModel, $roles = [])
    {
    }

    public static function createAuthGroup($controller, $name, $actions = [])
    {
    }

    public static function createAuthRole($controller, $action = '', $description = '')
    {
    }

    public static function saveAuthPermission($object_type, $id, $relation_type, $related_object_type, $related_objects = [])
    {
    }

    public static function checkFootPrint($hash, $time, $arr, $check_footprint = true, $check_time = true, $max_duration = FOOTPRINT_TIME_LIMIT, $algorithm = SECRET_HASH_ALGORITHM, $secret_key = SECRET_KEY)
    {
        if ($check_footprint && !FSecurity::checkHash($hash, $arr, $algorithm, $secret_key))
            return FError::INVALID_FOOTPRINT;

        if ($check_time && !FSecurity::checkExpired($time, $max_duration))
            return FError::EXPIRED_FOOTPRINT;

        return '';
    }

    public static function checkHash($hash, $arr, $algorithm = SECRET_HASH_ALGORITHM, $secret_key = SECRET_KEY, $secret_key_position = null)
    {
        if (isset($secret_key_position)) {
            $hash1 = FSecurity::generateHash($arr, $algorithm, $secret_key, $secret_key_position);
            $hash2 = '';
        } else {
            $hash1 = FSecurity::generateHash($arr, $algorithm, $secret_key, true);
            $hash2 = FSecurity::generateHash($arr, $algorithm, $secret_key, false);
        }

        if ($hash1 == $hash || $hash2 == $hash)
            return true;
        else
            return false;
    }

    public static function generateHash($arr, $algorithm = SECRET_HASH_ALGORITHM, $secret_key = SECRET_KEY, $secret_key_position = true)
    {
        if (!is_array($arr))
            $arr = [$arr];

        if ($secret_key_position)
            $arr = array_merge([$secret_key], $arr);
        else
            $arr = array_merge($arr, [$secret_key]);

        $arr_str = implode(',', $arr);
        $sha1 = hash($algorithm, $arr_str, true);
        return bin2hex($sha1);
    }

    public static function checkExpired($time, $max = FOOTPRINT_TIME_LIMIT)
    {
        $time_value = is_numeric($time) ? $time : strtotime($time);

        $duration = FHtml::time() - $time_value;

        if (abs($duration) > $max)
            return false;

        return true;
    }

    public static function isUserInApplication($user = null, $application_id = '')
    {
        return true;
    }



    public static function executeApplicationFunction($func_name, $params = null)
    {
        return;
    }

    public static function createBackendMenuItem($route, $name, $icon, $active, $roles = array(), $children = false)
    {
        /* @var $check AuthMenu */
        if (empty($route))
            return null;

        if (is_array($route)) {
            $url = $route[0];
            unset($route[0]);
            if (!empty($route)) {
                $url .= '?';
                foreach ($route as $key => $value) {
                    $url .= '&' . $key . '=' . $value;
                }
            }
            $route = $url;
        }

        $object_type = '';
        $module = '';
        $controller = '';
        $action = '';
        $arr = explode('/', str_replace('/index', '', trim($route, '/')));
        if (count($arr) > 2) {
            $controller = $arr[1];
            $object_type = str_replace('-', '_', $controller);
            $module = BaseInflector::camel2words($arr[0]);
            $action = $arr[2];
        } else if (count($arr) > 1) {
            $controller = $arr[1];
            $object_type = str_replace('-', '_', $controller);
            $module = BaseInflector::camel2words($arr[0]);
            $action = 'index';
        } else if (count($arr) == 1) {
            $controller = $arr[0];
            $object_type = str_replace('-', '_', $controller);
            $module = BaseInflector::camel2words($name);
            $action = 'index';
        }
        $menu = array(
            'active' => $active,
            'name' => FHtml::t('common', $name),
            'visible' => AccessRule::checkAccess($roles, $module, $controller, $action),
            'icon' => $icon,
            'url' => Yii::$app->urlManager->createUrl([$route]),
        );


        if (!($children === false)) { // if all child menu is not visible and also set parent menu is invisible
            $menu['children'] = $children;
            $visible = false;
            foreach ($children as $child) {
                $visible = $visible || $child['visible'];
            }
            $menu['visible'] = $visible;
        }

        return $menu;
    }

    public static function logAction($condition = [], $action = '', $status = '', $info = '', $object_type = '', $object_id = '', $user_id = '', $ip_address = '')
    {
    }

    public static function getModuleControllersFromUrls($urls = null)
    {
        if (!isset($urls))
            $urls = FSecurity::getMenuUrls();

        $arr = [];
        foreach ($urls as $url) {
            $arr1 = explode('/', $url);

            if (count($arr1) > 7) {
                $module = $arr1[5];
                $object_type = $arr1[6];
            } else {
                $module = '';
                $object_type = $arr1[5];
            }

            if (key_exists($module, $arr)) {
                $arr[$module] = array_merge($arr[$module], [$object_type]);
            } else {
                $arr = array_merge($arr, [$module => [$object_type]]);
            }
        }

        return $arr;
    }

    /* ham xoa dau tieng viet*/
    public static function getMenuUrls($menu = null)
    {
        if (!isset($menu))
            $menu = FHtml::backendMenu();

        $arr = [];
        foreach ($menu as $item) {
            if (key_exists('url', $item))
                $arr[] = $item['url'];
            if (key_exists('children', $item))
                $arr = array_merge($arr, FSecurity::getMenuUrls($item['children']));
        }

        return $arr;
    }

    public static function getModuleControllers($controllerDir = '../controllers', $getActions = false)
    {
        if (empty($controllerDir)) {
            $controllerDirs = [];

            foreach (\Yii::$app->modules as $moduleId => $module) {
                /*
                 * get module base path
                 */
                if (method_exists($module, 'getBasePath')) {
                    $basePath = $module->getBasePath();
                } else {
                    $class = $module['class'];

                    if (!file_exists(FHtml::getRootFolder() . "/$class.php"))
                        continue;

                    if (!class_exists($module['class']))
                        continue;

                    $reflector = new \ReflectionClass($module['class']);
                    $basePath = StringHelper::dirname($reflector->getFileName());
                }
                $basePath .= '/controllers';
                $controllerDirs[$moduleId] = $basePath;
            }

            $actions = [];
            foreach ($controllerDirs as $moduleId => $cDir) {
                $actions[$moduleId] = FSecurity::getModuleControllers($cDir, false);
            }

            return $actions;
        }

        if (!StringHelper::endsWith($controllerDir, 'controllers')) {
            if (key_exists($controllerDir, \Yii::$app->modules)) {
                $module = \Yii::$app->modules[$controllerDir];
                if (method_exists($module, 'getBasePath')) {
                    $basePath = $module->getBasePath();
                } else {
                    $class = $module['class'];

                    $reflector = new \ReflectionClass($module['class']);
                    $basePath = StringHelper::dirname($reflector->getFileName());
                }
                $controllerDir = $basePath;
            }
            $controllerDir = $controllerDir . '/controllers';
        }

        $controllerlist = [];
        if ($handle = opendir($controllerDir)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && substr($file, strrpos($file, '.') - 10) == 'Controller.php') {
                    $controllerlist[] = $file;
                }
            }
            closedir($handle);
        }
        asort($controllerlist);
        $fulllist = [];
        if ($getActions) {
            foreach ($controllerlist as $controller) :
                $fulllist = array_merge($fulllist, FSecurity::getControllerActions($controllerDir, $controller));
            endforeach;
        } else {
            foreach ($controllerlist as $controller) :
                $fulllist[BaseInflector::camel2id(substr($controller, 0, -14))] = substr($controller, 0, -14);
            endforeach;
        }
        return $fulllist;
    }


    public static function getControllerActions($controllerDir = '', $controller = '')
    {
        $fulllist = [];
        if (!StringHelper::endsWith($controllerDir, 'controllers') && !empty($controller)) {
            if (key_exists($controllerDir, \Yii::$app->modules)) {
                $module = \Yii::$app->modules[$controllerDir];
                if (method_exists($module, 'getBasePath')) {
                    $basePath = $module->getBasePath();
                } else {
                    $class = $module['class'];

                    $reflector = new \ReflectionClass($module['class']);
                    $basePath = StringHelper::dirname($reflector->getFileName());
                }
                $controllerDir = $basePath;
            }
            $controllerDir = $controllerDir . '/controllers';
        }

        if (!empty($controller)) {
            if (strpos('.', $controller) !== false)
                $controller = BaseInflector::camelize($controller);

            if (!StringHelper::endsWith($controller, 'Controller.php')) {
                $controller = ucfirst($controller) . 'Controller.php';
            }

            $handle = fopen($controllerDir . '/' . $controller, "r");
        } else {
            $handle = fopen($controllerDir, "r");
        }

        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if (preg_match('/public function action(.*?)\(/', $line, $display)) :
                    if (strlen($display[1]) > 2) :
                        $fulllist[BaseInflector::camel2id(substr($controller, 0, -14))][] = strtolower($display[1]);
                    endif;
                endif;
            }
        }
        fclose($handle);
        return $fulllist;
    }

    public static function getUserAccessToken($userid = null)
    {

        if (isset($userid) && is_object($userid) && method_exists($userid, 'getAuthKey'))
            return $userid->getAuthKey();

        if (!empty($userid)) {
            if (is_numeric($userid))
                $user = User::findOne($userid);
            else
                $user = self::getUser($userid);

            if (isset($user))
                return $user->getAuthKey();
        }

        $token = FHtml::getRequestParam([FSecurity::REQUEST_PARAM_AUTH_KEY], '');
        return $token;
    }

    public static function getUserByAccessToken($token = '', $isBackend = BACKEND)
    {
        if (empty($token))
            $token = self::getUserAccessToken();

        if (empty($token))
            return null;

        if ($isBackend === true || $isBackend === BACKEND) {
            $user = User::findIdentityByAccessToken($token);
        } else {
            $user = AppUser::findIdentityByAccessToken($token);
        }
        return $user;
    }

    public static function getUserStatusArray()
    {
        return [\common\models\User::STATUS_ACTIVE => FHtml::t('common', 'Enabled'), \common\models\User::STATUS_DISABLED => FHtml::t('common', 'Disabled')];
    }
}
