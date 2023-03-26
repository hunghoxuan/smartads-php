<?php
/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "<?= $generator->generateTableName($tableName) ?>".
 */

namespace common\components;

use backend\models\AppUser;
use backend\models\AuthGroup;
use backend\models\AuthMenu;
use backend\models\AuthPermission;
use backend\models\AuthRole;
use backend\modules\app\models\AppUserAPI;
use backend\modules\system\models\SettingsMenu;
use backend\modules\system\System;
use backend\modules\users\models\UserAlias;
use backend\modules\users\models\UserLogs;
use common\base\BaseSecurity;
use common\components\FConstant;
use common\models\LoginForm;
use backend\models\User;
use common\widgets\fmedia\FMedia;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use Yii;
use yii\helpers\Json;
use yii\helpers\StringHelper;

class FSecurity extends FFile
{
    const USER_NAME_SUPERADMIN = ['root', 'superadmin', 'sysadmin'];
    const USER_NAME_ADMIN = ['admin'];
    const REQUEST_PARAM_TOKEN = 'token';
    const REQUEST_PARAM_AUTH_KEY = 'auth_key';
    const USER_TABLE = 'qms_user';
    const USER_FIELD_ID = 'user_id';
    const USER_FIELD_USERNAME = 'username';
    const USER_FIELD_PASSWORD = 'password';
    const USER_FIELD_EMAIL = 'email';
    const USER_FIELD_ROLE = 'role';
    const USER_FIELD_IS_ONLINE = 'is_online';
    const USER_FIELD_LAST_LOGIN = 'last_login';
    const USER_FIELD_LAST_LOGOUT = 'last_logout';
    const TABLE_ALIAS_USER = 'qms_user';

    public static function currentUser($zone = '')
    {
        return \Yii::$app->user;
    }

    public static function currentUserIdentity()
    {
        $user = self::currentUser();
        static::setCurrentUser($user->getIdentity());
        return isset($user->identity) ? $user->identity : null;
    }

    public static function currentBackendUser()
    {
        $user = self::currentUser(BACKEND);
        return $user;
    }

    public static function currentUserId()
    {
        $identity = self::currentUserIdentity();
        if (isset($identity))
            return $identity->getId();
        else
            return '';
    }

    public static function setCurrentUser($user) {
        if (!isset($user)) {
            $_SESSION['moza.user_role'] = null;
            $_SESSION['moza.user_id'] = null;
            $_SESSION['moza.user_name'] = null;
            $_SESSION['moza.user_email'] = null;
            $_SESSION['moza.user_token'] = null;
            $_SESSION['moza.user_displayname'] = null;
        } else {
            if (FHtml::field_exists($user, 'role') && !isset($_SESSION['moza.user_role'])) {
                $_SESSION['moza.user_role'] = $user->role;
                $_SESSION['moza.user_id'] = $user->id;
                $_SESSION['moza.user_name'] = $user->username;
                $_SESSION['moza.user_email'] = $user->email;
                //$_SESSION['moza.user_token'] = FSecurity::generateUserAccessAuthKey($user->id);
                $str = $user->first_name . ' ' . $user->last_name;
                $_SESSION['moza.user_displayname'] = !empty($str) ? $str : $user->username;
            }
        }
    }

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
                $table_name = 'user';
                $model = FHtml::getModel($table_name, '', ['username' => $username]);
            } else {
                $table_name = 'app_user';
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
        if ($isBackend === true || $isBackend === BACKEND) {
            $user = User::findByUsername($username);
        } else {
            $user = AppUserAPI::findByUsername($username);
        }
        return $user;
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

    public static function isReadOnly($model) {
        if (isset($model) && method_exists($model, 'getIsReadOnly'))
            return $model->getIsReadOnly();

        if (isset($model) && method_exists($model, 'getIsLocked'))
            return $model->getIsLocked();
        return false;
    }

    public static function isLocked($model) {
        if (isset($model) && method_exists($model, 'getIsLocked'))
            return $model->getIsLocked();

        if (isset($model) && method_exists($model, 'getIsReadOnly'))
            return $model->getIsReadOnly();

        return false;
    }

    //HungHX: 20160801
    public static function isInRole($object_type, $action, $role = '', $userid = '', $field = '', $checkCurrentController = true)
    {
        $currentAction = FHtml::currentAction();

        if (is_bool($role)) {
            $checkCurrentController = $role;
            $role = '';
        }

        if (empty($role))
            $role = FHtml::getCurrentRole();

        if ($role == \common\models\User::ROLE_ADMIN) {
            return true; // can do any thing
        }

        $user = self::currentUser();
        if (!isset($user)) {
            return false;
        }

        if (in_array($action, ['update', 'edit', 'delete']) && $user->id == $userid) {
            return true;
        }

        if ($user->isGuest && $role != 'guest') {
            return false;
        }

        if (FHtml::frameworkVersion() == 'framework' && $role == \common\models\User::ROLE_MODERATOR) { // Default Role for ROLE MODERATOR
            if (in_array($action, ['view', 'index', 'create', 'update']))
                return true;
        }

        if ($action == 'edit')
            $action = 'update';

        if ($action == 'add')
            $action = 'create';

        if ($action == 'view-detail') {
            $action = 'view';
        }

        if ($action == 'update' && $currentAction == 'create')
            $action = $currentAction;

        if (empty($object_type))
            $object_type = str_replace('-', '_', FHtml::currentController());

        if (is_object($object_type)) {
            $readonly = FHtml::isLocked($object_type);

            if ($readonly && in_array($action, ['update', 'edit', 'delete']))
                return false;

            $readonly = FHtml::isReadOnly($object_type);

            if ($readonly && in_array($action, ['update', 'edit']))
                return false;

            $object_type = FHtml::getTableName($object_type);
        }

        $object_type = str_replace('-', '_', BaseInflector::camel2id($object_type));

        $module = FHtml::getModelModule($object_type);
        $controller = str_replace('_', '-', $object_type);
        $currentController = str_replace('_', '-', FHtml::currentController());

        $rules = FHtml::getControllerRules($object_type);
        $user_roles = self::getUserRoles($user);

        if (!empty($rules)) {
            foreach ($rules as $i => $rule) {
                $result = false;

                $actions = FHtml::getFieldValue($rule, 'actions');

                if (is_array($actions) && in_array($action, $actions)) {
                    $rights = FHtml::getFieldValue($rule, 'roles');
                    $rights = self::getRoles($rights, $module, $controller, $action);
                    $result = FHtml::getFieldValue($rule, 'allow', false) && self::isInRoles($rights, $module, $controller, $action, $user_roles);

                    //$checkCurrentController = true, means if user is authorized with current Object/ Controller then it will have authorized with its sub objects. If check for menu then must set to fasle
                    if ($checkCurrentController && $controller != $currentController && !$result) {
                        $result = self::isInRoles($rights, $module, $currentController, $action, $user_roles);
                    }

                    return $result;
                }
            }
        }


        return false;
    }

    public static function getUserRoles($user = null)
    {
        if (!isset($user))
            $user = \Yii::$app->user;

        $roles = array();
        $identity = FHtml::currentUserIdentity();
        if (!isset($identity))
            return [];

        if (isset($identity->groups)) {
            $groups = $identity->groups;

            /* @var $group AuthPermission */
            foreach ($groups as $group) {
                if (isset($group->group)) {
                    $group_roles = $group->group->roles;

                    foreach ($group_roles as $role) {
                        if (isset($role->role))
                            $roles[] = $role->role->code;
                        else if ($role->object2_id == 0) {
                            $roles[] = $role->object2_type;
                        }
                    }
                } else if ($group->object_id == 0) {
                    $roles[] = $group->object_type;
                }
            }
        }

        if (isset($identity->rights)) {
            $rights = $identity->rights;

            foreach ($rights as $right) {
                if (isset($right->role))
                    $roles[] = $right->role->code;
                else if ($right->object2_id == 0) {
                    $roles[] = $right->object2_type;
                }
            }
        }

        if (count($roles) != 0) {
            $rights = array_merge(array_unique($roles), [$identity->role]);
        } else {
            $rights = [$identity->role];
        }

        return $rights;
    }


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

        if (is_array($user)) // if pass $user_roles
            $user_roles = $user;
        else
            $user_roles = self::getUserRoles($user);

        if (!is_array($user_roles) || empty($user_roles))
            return false;

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

        //Hung: 15.11 - authorized columns, defined in model
        if (!FHtml::isTableExisted($object_type))
            return [];

        $object_model = FHtml::createModel($object_type);
        $authorized_columns = isset($object_model) ? FSecurity::getAuthorizedColumns($object_model) : [];
        if (!empty($authorized_columns)) {
            foreach ($authorized_columns as $column => $role) {
                if (is_numeric($column)) {
                    $column = $role;
                    $column_value = FHtml::getRequestParam($column);
                    if (!empty($column_value))
                        $role = [$controller . '?' . $column . '=' . $column_value, $column_value];
                    else
                        $role = [];
                }
                $role = is_string($role) ? [strtolower($role)] : $role;

                $roles1 = array_merge($roles1, $role);
            }

        }

        if (empty($action))
            $action = 'index';

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
        } else if (in_array($action, ['add', 'update', 'create', 'edit', 'bulk-action'])) {
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
        } else if (in_array($action, ['populate', 'delete', 'bulk-delete', 'reset'])) {
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

    public static function isAuthorized($action, $object_type, $field = '', $form_name = '', $form_type = '', $role = '', $userid = '', $manualValue = false)
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

        if ($role == self::ROLE_ADMIN)
            return true;

        if (empty($role) || $role == 'guest')
            return false;

        if (empty($userid))
            $userid = FHtml::currentUserId();

        if (empty($userid))
            return false;

        return self::isInRole($object_type, $action) || $manualValue;
    }

    public static function getPermissions($object_type, $field, $form_name = '', $form_type = '', $role = '', $userid = '')
    {
        return null;
    }


    //HungHX: 20160801
    public static function getCurrentRole()
    {
        $identity = self::currentUserIdentity();

        if (isset($identity))
            return $identity->role;
        else
            return 'guest';
    }

    public static function logOut()
    {
        $user = FHtml::currentUserIdentity();

        if (isset($user)) {
            FHtml::setFieldValue($user, 'is_online', 0);
            FHtml::setFieldValue($user, 'last_logout', FHtml::Now());
            $user->save();
        }
        \Yii::$app->user->logout();
        FHtml::DestroySession();
        static::setCurrentUser(null);
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
            if (!isset($user))
                return false;

            $application_id = FHtml::getFieldValue($user, 'application_id');

            if (!empty($application_id) && APPLICATIONS_ENABLED) {
                $application = FHtml::getApplication($application_id);
                if (!isset($application)) {
                    FError::addError("Application [$application_id] does not exist.");
                    FSecurity::logOut();
                    return false;
                } else {
                    FHtml::setApplicationId($application_id);
                }
            }

            if (isset($user)) {
                FHtml::setFieldValue($user, 'is_online', 1);
                FHtml::setFieldValue($user, 'last_login', FHtml::Now());
                $user->save(false);
            }

            return true;
        }

        return false;
    }

    public static function getControllerBehaviours($rules = [], $controller = '')
    {
        return $rules;
    }

    public static function getApplicationUsers($user_role = FHtml::ROLE_USER)
    {
        return FHtml::findAll('user', ['status' => FHtml::USER_STATUS_ACTIVE, 'role' => $user_role]);
    }

    public static function getApplicationRoles($from_folder = true, $from_db = true)
    {
        $result = AuthRole::findAll(); // FHtml::findAll('auth_role');

        return $result;
    }

    public static function getApplicationGroups()
    {
        $result =  AuthGroup::findAll(); // FHtml::findAll('auth_group');
        return $result;
    }

    public static function getApplicationObjectTypes() {
        $modules = self::getApplicationModulesComboArray();
        $arr = [];
        foreach ($modules as $module => $module_name) {
            if (in_array($module, ['system', 'user', 'tools'])) //only admin can access
                continue;
            //$arr = array_merge($arr, [$module => $module_name]);
            $arr1 = FSecurity::getModuleControllers($module, false);
            foreach ($arr1 as $controller => $name) {
                $controller = str_replace('-', '_', $controller);
                if (FHtml::isTableExisted($controller))
                    $arr = array_merge($arr, [$controller => $controller]);
            }
        }

        return $arr;
    }

    public static function getApplicationUsersComboArray($displayName = 'name')
    {
        return ArrayHelper::map(self::getApplicationUsers(), 'id', $displayName);
    }

    public static function getApplicationRolesComboArray($auto_get_from_code = false, $extraParams = [])
    {
        $arr = [];

        $menu_modules = FSecurity::getAuthorizedModulesControllersFromMenu();

        $modules = FSecurity::getApplicationModulesComboArray();

        foreach ($modules as $module => $module_name) {
            if (in_array($module, ['system', 'user', 'tools'])) //only admin can access
                continue;

            if (!key_exists($module, $menu_modules))
                continue;

            $menu_controllers = $menu_modules[$module];

            $arr1 = FSecurity::getModuleControllers($module, true);

            $arr = array_merge($arr, ["$module\\" . FHtml::NULL_VALUE  => FHtml::t('common', 'Module') . ' : ' . FHtml::t('common', BaseInflector::camel2words($module))]);

//            $arr = array_merge($arr, ["$module/manage" => "Full Rights"]);
//            $arr = array_merge($arr, ["$module/edit" => ""]);
//            $arr = array_merge($arr, ["$module/create" => "Create"]);
//            $arr = array_merge($arr, ["$module/update" => "Update"]);
//            $arr = array_merge($arr, ["$module/delete" => "Delete"]);
//            $arr = array_merge($arr, ["$module" => "View"]);

            foreach ($menu_controllers as $controller => $controller_name) {
                if (!key_exists($controller, $arr1))
                    continue;
                //$controller_name = $menu_controllers[$controller];
                $actions = $arr1[$controller];

                $arr = array_merge($arr, ["$controller\\" . FHtml::NULL_VALUE  => ' ' . FHtml::t('common', $controller_name) . "    <span style='font-weight:normal;color:lightgrey'> /admin/$module/$controller/</span>"]);

                if ($auto_get_from_code) {
                    foreach ($actions as $action) {
                        $arr = array_merge($arr, ["$controller/$action" => BaseInflector::camel2words($action)]);
                    }
                } else {
                    $arr = array_merge($arr, ["$controller/manage" => "Full Rights"]);
                    $arr = array_merge($arr, ["$controller/edit" => "Editor"]);
                    $arr = array_merge($arr, ["$controller/create" => "Create"]);
                    $arr = array_merge($arr, ["$controller/update" => "Update"]);
                    $arr = array_merge($arr, ["$controller/delete" => "Delete"]);
                    $arr = array_merge($arr, ["$controller" => "View"]);
                }
                //Hung: add extra Params
                if (in_array($controller, $extraParams)) {
                    foreach ($extraParams as $controller1 => $actions1) {
                        if (StringHelper::startsWith($controller1, $controller)) {
                            $arr = array_merge($arr, ["$controller1" => $actions1]);
                        }
                    }
                }

                //Hung: add Authorized Columns
                $object_type = FHtml::getTableName($controller);
                if (FHtml::isTableExisted($object_type)) {
                    $object_model = FHtml::createModel($object_type);
                    if (isset($object_model)) {
                        $authorized_columns = FSecurity::getAuthorizedColumns($object_model);

                        foreach ($authorized_columns as $column) {
                            $column_arr = FHtml::getComboArray("$object_type.$column");

                            if (!empty($column_arr)) {
                                foreach ($column_arr as $key => $value) {
                                    if (!empty($key))
                                        $arr = array_merge($arr, ["$controller" . FHtml::encode([$column => $key]) => $value]);
                                }
                            }
                        }
                    }
                }
            }
        }

        $arr1 = ArrayHelper::map(self::getApplicationRoles(true, true), 'code', 'name');

        foreach ($arr1 as $key => $value) {
            $arr = array_merge($arr, [str_replace('_', '-', "$key") => $value]);
        }

        foreach ($arr as $key => $value) {
            if (empty($value))
                unset($arr[$key]);
        }
        return $arr;
    }

    public static function getRolesComboArray()
    {
        $arr = ['admin' => 'Admin', 'moderator' => 'Moderator', 'user' => 'User'];
        $arr = array_merge($arr, ArrayHelper::map(self::getApplicationRoles(), 'code', 'name'));

        return $arr;
    }

    public static function getApplicationActions($get_from_modules = true) {
        $application_id = FHtml::currentApplicationId();

        $actions = [];
        $list_files1 = [];
        $list_files1 = array_merge($list_files1, FHtml::listContents("applications\\$application_id\actions"));
        $list_files1 = array_merge($list_files1, FHtml::listContents("backend\actions"));
        $modules = FHtml::getApplicationModulesComboArray();
        foreach ($modules as $module_id => $module_name) {
            $list_files1 = array_merge($list_files1, FHtml::listContents("backend\modules\\$module_id\actions"));
        }

        foreach ($list_files1 as $arr) {
            $action_name = str_replace('.php', '', $arr['name']);
            $name = BaseInflector::camel2id(str_replace('Action.php', '', $arr['name']));
            $actions = array_merge($actions, [$name => $action_name]);
        }

        return $actions;
    }

    public static function getModulesArray() {
        return static::getApplicationModulesComboArray(true);
    }


    /**
     * @param bool $from_folders
     * @param bool $from_db
     * @return array
     */
    public static function getApplicationModulesComboArray($from_folders = true, $from_db = false)
    {
        $result = [];

        if ($from_folders) {
            $class = "";
            $current_modules = FHtml::currentModule();
            foreach (\Yii::$app->modules as $moduleId => $module) {
                if (method_exists($module, 'getBasePath')) {
                    $basePath = $module->getBasePath();
                    $class = ucfirst($moduleId); //str_replace("controllers", ucfirst($moduleId), $module->controllerNamespace);
                    $file = FFile::getFullFileName($basePath . "/$class.php");
                } else {
                    if (!class_exists($module['class']))
                        continue;
                    $class = $module['class'];
                    $file = FFile::getFullFileName(FHtml::getRootFolder() . "/$class.php");
                }

                if (!file_exists($file))
                    continue;

                $result = array_merge($result, [$moduleId => FHtml::t('common', BaseInflector::camelize($moduleId))]);
            }
        }

        if ($from_db) {
            $application_id = FHtml::currentApplicationCode();
            $result1 = [];
            if (FHtml::isTableExisted('settings_menu')) {
                $result1 = \yii\helpers\ArrayHelper::map(FHtml::findbySql("select distinct module, module from settings_menu where application_id = '$application_id'"), 'module', 'module');
            }
            foreach ($result1 as $key => $value) {
                if (!key_exists(strtolower($key), $result)) {
                    $result = array_merge($result, [$key => $value]);
                }
            }
        }

        foreach ($result as $key => $value) {
            if (empty($value))
                unset($result[$key]);
        }

        ksort($result);
        return $result;
    }

    public static function populateAuthItems()
    {
        $items = FHtml::getModuleControllersFromUrls();
        foreach ($items as $module => $controllers) {
            $module = strtolower($module);
            $group_model1 = AuthGroup::createOrUpdate(['name' => BaseInflector::camel2words($module) . ' User'], [], false);
            $group_model2 = AuthGroup::createOrUpdate(['name' => BaseInflector::camel2words($module) . ' Admin'], [], false);
            $group_model3 = AuthGroup::createOrUpdate(['name' => BaseInflector::camel2words($module) . ' Moderator'], [], false);

            $role_model1 = AuthRole::createOrUpdate(['code' => $module], ['name' => BaseInflector::camel2words($module) . ':View Only'], false);
            $role_model2 = AuthRole::createOrUpdate(['code' => "$module/manage"], ['name' => BaseInflector::camel2words($module) . ':All Actions'], false);
            $role_model3 = AuthRole::createOrUpdate(['code' => "$module/edit"], ['name' => BaseInflector::camel2words($module) . ':View,Add,Edit'], false);

            if (is_object($group_model1) && is_object($role_model1))
                $permission_model1 = AuthPermission::createOrUpdate(['object_type' => 'auth_group', 'object_id' => $group_model1->id, 'relation_type' => 'group-role', 'object2_type' => 'auth_role', 'object2_id' => $role_model1->id]);

            if (is_object($group_model2) && is_object($role_model2))
                $permission_model2 = AuthPermission::createOrUpdate(['object_type' => 'auth_group', 'object_id' => $group_model2->id, 'relation_type' => 'group-role', 'object2_type' => 'auth_role', 'object2_id' => $role_model2->id]);

            if (is_object($group_model3) && is_object($role_model3))
                $permission_model3 = AuthPermission::createOrUpdate(['object_type' => 'auth_group', 'object_id' => $group_model3->id, 'relation_type' => 'group-role', 'object2_type' => 'auth_role', 'object2_id' => $role_model3->id]);

            foreach ($controllers as $controller) {
                $controller = str_replace('-', '_', $controller);
                $role_model1 = AuthRole::createOrUpdate(['code' => $controller], ['name' => BaseInflector::camel2words($controller) . ':View Only'], false);
                $role_model2 = AuthRole::createOrUpdate(['code' => "$controller/manage"], ['name' => BaseInflector::camel2words($controller) . ':All Actions'], false);
                $role_model3 = AuthRole::createOrUpdate(['code' => "$controller/edit"], ['name' => BaseInflector::camel2words($controller) . ':View,Add,Edit'], false);
            }
        }
    }

    public static function getUserGroupModels($user)
    {
        $arr = [];
        $groups = $user->groups;
        foreach ($groups as $group) {
            $arr[] = $group->group;
        }

        return $arr;
    }

    public static function getUserGroupArray($user)
    {
        $groups = self::getUserGroupModels($user);
        $result = ArrayHelper::getColumn($groups, 'object_id');
        return $result;
    }

    public static function getUserRoleModels($user)
    {
        $models = $user->hasMany(AuthPermission::className(), ['object_id' => 'id'])
            ->andOnCondition(['AND',
                ['relation_type' => 'user-role'],
                ['object2_id' => 'auth_group'],
                ['object_type' => 'user']]);
        return $models;
    }

    public static function getUserRoleArray($user)
    {
        $groups = self::getUserRoleModels($user);
        $result = ArrayHelper::getColumn($groups, 'object_id2');
        return $result;
    }

    public static function getGroupRoleModels($group) {

        $arr = [];
        $roles = $group->roles;

        foreach ($roles as $role) {
            $arr[] = $role->role;
        }

        return $arr;
    }

    public static function getApplicationGroupsComboArray()
    {
        $arr = ArrayHelper::map(self::getApplicationGroups(), 'id', 'name');
        return $arr;
    }

    public static function saveAuthPermission($object_type, $id, $relation_type, $related_object_type, $related_objects = [])
    {
        if (!isset($id) || empty($id))
            return;

        $time_string = time();
        $today = date('Y-m-d H:i:s', $time_string);

        if (!is_array($related_objects))
            $related_objects = [$related_objects];

        if (count($related_objects) != 0) {
            AuthPermission::deleteAll("relation_type = '$relation_type' AND object_id = $id");
            foreach ($related_objects as $related_object) {
                $new_user = new AuthPermission();
                if (is_numeric($related_object)) {
                    $new_user->object2_id = $related_object;
                    $new_user->object2_type = $related_object_type;
                } else if (is_string($related_object)) {
                    if (empty($related_object))
                        continue;
                    $new_user->object2_id = 0;
                    $new_user->object2_type = $related_object;
                }
                $new_user->object_id = $id;
                $new_user->object_type = $object_type;
                $new_user->relation_type = $relation_type;
                $new_user->sort_order = 0;
                $new_user->created_date = $today;
                if (!$new_user->save()) {
                    FError::addError($new_user->errors);
                }
            }
        }
    }

    public static function updateUserGroups($userModel, $groups = [])
    {
        $time_string = time();
        $today = date('Y-m-d H:i:s', $time_string);

        if (!is_array($groups))
            $groups = [$groups];
        AuthPermission::deleteAll("relation_type = 'group-user' AND object2_id = $userModel->id AND object2_type='user'");
        foreach ($groups as $group) {
            if (StringHelper::startsWith($group, 'id:'))
                $group = str_replace('id:', '', $group);

            $new_user = new AuthPermission();
            if (is_numeric($group)) {
                $new_user->object_id = $group;
                $new_user->object_type = 'auth_group';
            } else if (is_string($group)) {
                if (empty($group))
                    continue;
                $new_user->object_id = 0;
                $new_user->object_type = $group;
            }
            $new_user->object2_id = $userModel->id;
            $new_user->object2_type = 'user';
            $new_user->relation_type = 'group-user';
            $new_user->sort_order = 0;
            $new_user->created_date = $today;
            if (!$new_user->save()) {
                FError::addError($new_user->errors);
            }
        }
    }

    public static function updateUserRoles($userModel, $roles = [])
    {
        $time_string = time();
        $today = date('Y-m-d H:i:s', $time_string);

        if (!is_array($roles))
            $roles = [$roles];

        AuthPermission::deleteAll("relation_type = 'user-role' AND object_id = $userModel->id AND object_type='user'");

        foreach ($roles as $role) {
            if (StringHelper::startsWith($role, 'id:'))
                $role = str_replace('id:', '', $role);

            $new_user = new AuthPermission();
            if (is_numeric($role)) {
                $new_user->object2_id = $role;
                $new_user->object2_type = 'auth_role';
                $new_user->relation_type = 'user-role';

            } else if (is_string($role)) {
                if (empty($role))
                    continue;
                $new_user->object2_id = 0;
                $new_user->object2_type = $role;
                $new_user->relation_type = 'user-role';
            }

            $new_user->object_id = $userModel->id;
            $new_user->object_type = 'user';
            $new_user->sort_order = 0;
            $new_user->created_date = $today;
            if (!$new_user->save()) {
                FError::addError($new_user->errors);
            }
        }
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

    public static function createAuthRole($controller, $action = '', $description = '')
    {
        if (empty($action)) {
            $name = $controller;
            $action = 'View';
            $description = empty($description) ? 'View, List' : $description;
        } else {
            $name = "$controller/$action";
            $description = empty($description) ? ($action == 'manage' ? 'Create, Update, Delete' : $action) : $description;

        }

        $role = AuthRole::findOne(['code' => $name]);
        if (!isset($role)) {
            $role = new AuthRole();
            $role->code = strtolower($name);
            $role->name = BaseInflector::camel2words($controller) . ' - ' . BaseInflector::camel2words($action);
            $role->description = $description;
            $role->is_active = 1;
            $role->application_id = FHtml::currentApplicationCode();
            $role->save();
        }

        return $role;
    }

    public static function createAuthGroup($controller, $name, $actions = [])
    {
        $name = BaseInflector::camel2words($controller) . ' ' . $name;
        $object_type = str_replace('-', '_', $controller);
        $group = AuthGroup::findOne(['name' => $name]);
        if (!isset($group)) {
            $group = new AuthGroup();
            $group->name = $name;
            $group->is_active = 1;
            $group->application_id = FHtml::currentApplicationCode();
            $group->save();
        }

        if (isset($group) && !empty($actions))
        {
            foreach ($actions as $action) {
                if ($action == 'view')
                    $action = '';

                $role_model = self::createAuthRole($controller, $action);
                if (isset($role_model))
                {
                    self::saveAuthPermission('auth_group', $group->id, 'group-role', 'auth_role', [$role_model->id]);
                }
            }
        }

        return $group;
    }

    public static function generateHash($arr, $algorithm = SECRET_HASH_ALGORITHM, $secret_key = SECRET_KEY, $secret_key_position = true) {
        if (!is_array($arr))
            $arr = [$arr];

        if ($secret_key_position)
            $arr = array_merge([$secret_key], $arr);
        else
            $arr = array_merge($arr, [$secret_key]);

        $arr_str = implode($arr, ',');
        $sha1 = hash($algorithm, $arr_str, true);
        return bin2hex($sha1);
    }

    public static function checkHash($hash, $arr, $algorithm = SECRET_HASH_ALGORITHM, $secret_key = SECRET_KEY, $secret_key_position = null) {
        if (isset($secret_key_position)) {
            $hash1 = self::generateHash($arr, $algorithm, $secret_key, $secret_key_position);
            $hash2 = '';
        } else {
            $hash1 = self::generateHash($arr, $algorithm, $secret_key, true);
            $hash2 = self::generateHash($arr, $algorithm, $secret_key, false);
        }

        if ($hash1 == $hash || $hash2 == $hash)
            return true;
        else
            return false;
    }

    public static function checkExpired($time, $max = FOOTPRINT_TIME_LIMIT) {
        $time_value = is_numeric($time) ? $time : strtotime($time);

        $duration = FHtml::time() - $time_value;

        if (abs($duration) > $max)
            return false;

        return true;
    }

    public static function checkFootPrint($hash, $time, $arr, $check_footprint = true, $check_time = true, $max_duration = FOOTPRINT_TIME_LIMIT, $algorithm = SECRET_HASH_ALGORITHM, $secret_key = SECRET_KEY) {
        if ($check_footprint && !self::checkHash($hash, $arr, $algorithm, $secret_key))
            return FError::INVALID_FOOTPRINT;

        if ($check_time && !self::checkExpired($time, $max_duration))
            return FError::EXPIRED_FOOTPRINT;

        return '';
    }

    public static function getTokenParams($user_name = '', $url = '', $object_type = '', $object_id) {
        if (empty($user_name))
            $user_name = FHtml::currentUserId();

        if (empty($url))
            $url = FHtml::currentDomain();

        if (empty($object_type))
            $object_type = FHtml::getTableName(FHtml::currentController());

        if (empty($object_id))
            $object_id = FHtml::getRequestParam('id');

        return [FHtml::currentApplicationId(), $user_name, $url, $object_type, $object_id];
    }

    public static function generateToken($user_name = '', $url = '', $object_type = '', $object_id = '', $algorithm = SECRET_HASH_ALGORITHM, $secret_key = SECRET_KEY, $secret_key_position = true) {
        $arr = self::getTokenParams($user_name, $url, $object_type, $object_id);
        $a = self::generateHash($arr, $algorithm, $secret_key, $secret_key_position);

        return $a;
    }

    public static function checkToken($token = '', $user_name = '', $url = '', $object_type = '', $object_id = '', $algorithm = SECRET_HASH_ALGORITHM, $secret_key = SECRET_KEY, $secret_key_position = true) {
        if (empty($token))
            $token = FHtml::getRequestParam(FSecurity::REQUEST_PARAM_TOKEN);

        if (empty($token))
            return null;

        $arr = self::getTokenParams($user_name, $url, $object_type, $object_id);
        return self::checkHash($token, $arr, $algorithm, $secret_key, $secret_key_position);
    }

    public static function getUserAccessAuthKeyParams($user_id) {
        return FHtml::getUserAccessToken($user_id);
    }

    public static function generateUserAccessAuthKey($user_id, $algorithm = SECRET_HASH_ALGORITHM, $secret_key = SECRET_KEY, $secret_key_position = true) {
        $arr = self::getUserAccessAuthKeyParams($user_id);
        return self::generateHash($arr, $algorithm, $secret_key, $secret_key_position);
    }

    public static function checkUserAccess($user_id = '', $auth_key = '', $user_auth_key = '') {
        if (empty($auth_key)) //
            $auth_key = FSecurity::getUserAccessToken();

        if (empty($user_id))
            $user_id = FHtml::getRequestParam(['user', 'user_name']);

        if (empty($user_auth_key))
            $user_auth_key = FHtml::getRequestParam(FSecurity::REQUEST_PARAM_AUTH_KEY);

        if (!empty($user_auth_key) && !empty($user_id)) //  && empty(FSecurity::currentUserId()
        {
            if (!empty($user_id)) {
                $user = FSecurity::getUser($user_id);
            } else if (!empty($auth_key)) {
                $user = FSecurity::getUserByAccessToken($auth_key);
            }

            if (!isset($user))
                return false;

            $arr = self::getUserAccessAuthKeyParams($user);

            if (isset($user) && FSecurity::checkHash($user_auth_key, $arr)) {
                Yii::$app->user->switchIdentity($user, 0);

                //refresh current page and remove proccesed params --> temporarily removed
//                $url = FHtml::createUrl(FHtml::currentUrlPath(), FHtml::RequestParams([FSecurity::REQUEST_PARAM_AUTH_KEY, 'user']));
//                FHtml::currentControllerObject()->redirect($url);
                return FHtml::currentUserIdentity();
            }
        }

        return false;
    }

    public static function isUserInApplication($user = null, $application_id = '')
    {
        if (!isset($user))
            $user = FHtml::currentBackendUser();

        if (empty($application_id))
            $application_id = FHtml::currentApplicationCode();

        if (self::isRoleAdmin($user))
            return true;
        else {
            $user_application_id = FHtml::getFieldValue($user, 'application_id');
            return $application_id == $user_application_id;
        }

        return false;
    }

    public static function isRootUser($username = '')
    {
        if (empty($username)) {
            return self::isRoleAdmin() && in_array(FHtml::currentUsername(), FSecurity::USER_NAME_SUPERADMIN);
        } else {
            return in_array($username, FSecurity::USER_NAME_SUPERADMIN);
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

    /**
     * Calls a method, function or closure. Parameters are supplied by their names instead of their position.
     * @param $call_arg like $callback in call_user_func_array()
     * Case1: {object, method}
     * Case2: {class, function}
     * Case3: "class::function"
     * Case4: "function"
     * Case5: closure
     * @param array $param_array A key-value array with the parameters
     * @return result of the method, function or closure
     * @throws \Exception when wrong arguments are given or required parameters are not given.
     */
    public static function callFunc($call_arg, array $param_array)
    {
        $Func = null;
        $Method = null;
        $Object = null;
        $Class = null;

        // The cases. f means function name
        // Case1: f({object, method}, params)
        // Case2: f({class, function}, params)
        if(is_array($call_arg) && count($call_arg) == 2)
        {
            if(is_object($call_arg[0]))
            {
                $Object = $call_arg[0];
                $Class = get_class($Object);
            }
            else if(is_string($call_arg[0]))
            {
                $Class = $call_arg[0];
            }

            if(is_string($call_arg[1]))
            {
                $Method = $call_arg[1];
            } else if (is_array($call_arg[1])) {
                list($Class, $Method) = explode("->", $call_arg[0]);
                if (class_exists($Class)) {
                    $Object = \Yii::createObject(['class' => $Class::className()], $call_arg[1]);
                    return $Object->$Method($param_array);
                }
            }
        }
        // Case3: f("class::function", params)
        else if(is_string($call_arg) && strpos($call_arg, "::") !== FALSE)
        {

            list($Class, $Method) = explode("::", $call_arg);
        }
        // Case4: f("function", params)
        else if(is_string($call_arg) && strpos($call_arg, "->") !== FALSE)
        {

            list($Class, $Method) = explode("->", $call_arg);
            if (class_exists($Class)) {
                $properties = FHtml::getFieldValue($param_array, ['properties', 'objectProperties', 1]);
                if (!empty($properties))
                    $objectClass = array_merge($properties, ['class' => $Class::className()]);
                else
                    $objectClass = ['class' => $Class::className()];
                $Object = \Yii::createObject( $objectClass, FHtml::getFieldValue($param_array, ['constructors', 'objectParams', 0]));
                return $Object->$Method(FHtml::getFieldValue($param_array, ['methodParams', 'params', 2]));
            }
        }
        else if(is_string($call_arg) && strpos($call_arg, "::") === FALSE)
        {

            $Method = $call_arg;
        }
        // Case5: f(closure, params)
        else if(is_object($call_arg) && $call_arg instanceof \Closure)
        {
            $Method = $call_arg;
            return $Method($param_array);
        }
        else if(is_object($call_arg))
        {
            $Class = $call_arg;
            $Method = $param_array[0];
            unset($param_array[0]);
            return $Class->$Method($param_array);
        }
        else throw new \Exception("Case not allowed! Invalid Data supplied!");
        if($Class) $Func = new \ReflectionMethod($Class, $Method);
        else $Func = new \ReflectionFunction($Method);
        $params = array();
        foreach($Func->getParameters() as $Param)
        {
            if($Param->isDefaultValueAvailable()) $params[$Param->getPosition()] = $Param->getDefaultValue();
            if(array_key_exists($Param->name, $param_array)) $params[$Param->getPosition()] = $param_array[$Param->name];
            if(!$Param->isOptional() && !isset($params[$Param->getPosition()])) die("No Defaultvalue available and no Value supplied!\r\n");
        }
        if($Func instanceof \ReflectionFunction) return $Func->invokeArgs($params);
        if($Func->isStatic()) return $Func->invokeArgs(null, $params);
        else return $Func->invokeArgs($Object, $params);
    }

    public static function executeApplicationFunction($func_name, $params = null) {
        $helper = FHtml::getApplicationHelper();
        if (isset($helper) && method_exists($helper, $func_name))
        {
            return $helper->$func_name($params);
        }
        if (isset($helper) && method_exists($helper, 'execute')) {
            return $helper->execute($func_name, $params);
        }
    }

    public static function createBackendMenu($application_id = '') {
        if (empty($application_id))
            $application_id = FHtml::currentApplicationId();

        $arr = FFile::includeFile("applications/$application_id/config/backend_menu.php");
        $menu[] = AuthMenu::buildDashBoardMenu();

        if (!empty($arr)) {
            foreach ($arr as $module => $controllers) {
                $moduleObject = FHtml::getModuleObject($module);
                if (isset($moduleObject) && method_exists($moduleObject, 'createModuleMenu')) {
                    $menu = array_merge($menu, $moduleObject::createModuleMenu($controllers));
                }
            }
        }

        return $menu;
    }

    public static function getPublicUrls() {
        return ['site/index'];
    }

    public static function isDBSecurityEnabled()
    {
        return FConfig::settingDBSecurityEnabled();
    }

    public static function createBackendMenuItem($route, $name, $icon, $active, $roles = array(), $children = false, $badge = -1)
    {
        /* @var $check AuthMenu */
        if (empty($route))
            return null;

        if (is_array($route))
        {
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
        $module = ''; $controller = ''; $action = '';
        $arr = FHtml::parseUrl($route, $name);
        $controller = $arr['controller'];
        $module = $arr['module'];
        $action = $arr['action'];

        if (StringHelper::startsWith($route, '@')) {
            $menu = array(
                'active' => $active,
                'name' => $name,
                'visible' => true,
                'roles' => $roles,
                'icon' => $icon,
                'badge' => $badge,
                'url' => \common\components\FHtml::getRootUrl()  . str_replace('@',  '/', $route),
                'module' => $module, 'controller' => $controller, 'action' => $action
            );
            return $menu;
        }


        if ($route == '#')
            $condition = ['url' => $route, 'name' => $name];
        else
            $condition = ['url' => $route];

        $public_urls = self::getPublicUrls();

        if (self::isDBSecurityEnabled() && FHtml::isTableExisted('auth_menu')) {
            $check = AuthMenu::findOne($condition);
            if (isset($check)) {
                $roles = FHtml::decode($check->role);

                $menu = array(
                    'active' => $active,
                    'name' => $check->name,
                    'visible' => in_array($route, $public_urls) ||  $check->is_active && AccessRule::checkAccess($roles, $module, $controller, $action),
                    'roles' => $roles,
                    'icon' => $check->icon,
                    'badge' => $badge,
                    'url' => FHtml::createUrl([$check->route]),
                    'module' => $module, 'controller' => $controller, 'action' => $action
                );

            } else {
                $menu = array(
                    'active' => $active,
                    'name' => $name,
                    'visible' => in_array($route, $public_urls) || AccessRule::checkAccess($roles, $module, $controller, $action),
                    'icon' => $icon,
                    'roles' => $roles,
                    'url' => FHtml::createUrl([$route]),
                    'badge' => $badge,
                    'module' => $module, 'controller' => $controller, 'action' => $action

                );

                $now = time();
                //$imageName = '';
                $today = date('Y-m-d H:i:s', $now);
                $new_menu = new AuthMenu();
                $new_menu->icon = $icon;
                $new_menu->name = $name;
                $new_menu->route = $route;
                $new_menu->group = BACKEND;
                $new_menu->is_active = $active;
                $new_menu->role = Json::encode($roles);
                $new_menu->application_id = FHtml::currentApplicationCode();
                $new_menu->created_user = (string)FHtml::currentUserId();

                if ($route == '#')
                    $new_menu->sort_order = 0;
                else
                    $new_menu->sort_order = 1;

                $new_menu->created_date = $today;

                if (count($arr) > 1) {
                    $new_menu->object_type = $object_type;
                    $new_menu->module = $module;
                    if ($new_menu->module == 'System')
                        $new_menu->module = 'Administration';
                } else if (count($arr) == 1) {
                    if ($name == 'Home') {
                        $new_menu->module = 'Home';
                        $new_menu->object_type = '';
                    } else if ($route !== '#')
                        $new_menu->module = 'Administration';
                    else
                        $new_menu->module = $name == 'Administration' ? 'Administration' : $module;
                }

                $new_menu->save();
            }
        } else {

            $menu = array(
                'active' => $active,
                'name' => $name,
                'visible' => in_array($route, $public_urls) || AccessRule::checkAccess($roles, $module, $controller, $action),
                'icon' => $icon,
                'roles' => $roles,
                'badge' => $badge,
                'url' => FHtml::createUrl([$route]),
                'module' => $module, 'controller' => $controller, 'action' => $action

            );
        }

        if (!($children === false) && is_array($children)) { // if all child menu is not visible and also set parent menu is invisible
            $menu['children'] = $children;
            $visible = false; $active = false;
            foreach ($children as $child) {
                $visible = $visible || $child['visible'];
                $active = $active || $child['active'];
            }
            $menu['visible'] = $visible;
            $menu['active'] = $active;

        }

        return $menu;
    }

    public static function addAdminMenu($menu, $appendedMenu = ['Users', 'System', 'Tools'], $controller = '') {
        if (empty($controller))
            $controller = FHtml::currentController();

        if (!empty($appendedMenu)) {
            foreach ($appendedMenu as $menu_name => $menu_array) {

                if (is_numeric($menu_name)) {
                    $menu_name = $menu_array;
                    $menu_array = null;
                }

                $menu_existed = false;
                foreach ($menu as $i => $menu_item) {

                    if (is_array($menu_item) && key_exists('name', $menu_item) && $menu_item['name'] == $menu_name)
                    {
                        $menu_existed = true;
                        break;
                    }
                }
                if ($menu_existed)
                    continue;

                $menu_module = FHtml::getModuleObject($menu_name);

                if (isset($menu_module) && FHtml::isRoleAdmin() && method_exists($menu_module, 'createModuleMenu')) {
                    $menu = array_merge($menu, $menu_module->createModuleMenu());
                }
            }
        } else {

            $menu[] = AuthMenu::menuItem(
                '#',
                'System',
                'glyphicon glyphicon-user',
                in_array($controller, ['user', 'settings', 'object-setting', 'object-category']),
                [],
                [
                    !FHtml::isDBSettingsEnabled() ? null : AuthMenu::menuItem(
                        '/settings/index',
                        'Configuration',
                        'glyphicon glyphicon-cog',
                        $controller == 'settings',
                        [FHtml::ROLE_ADMIN]
                    ),
                    !FHtml::isDBSettingsEnabled() ? null : AuthMenu::menuItem(
                        '/object-setting/index',
                        'Settings',
                        'glyphicon glyphicon-book',
                        $controller == 'object-setting',
                        [FHtml::ROLE_ADMIN]
                    ),
                    AuthMenu::menuItem(
                        '/object-category/index',
                        'Categories',
                        'glyphicon glyphicon-book',
                        $controller == 'object-category',
                        [FHtml::ROLE_ADMIN]
                    ),
                    AuthMenu::menuItem(
                        '/user/index',
                        'Users',
                        'glyphicon glyphicon-user',
                        $controller == 'user',
                        [FHtml::ROLE_ADMIN]
                    ),
                ]
            );
        }

        return $menu;
    }

    //Set custom menu for backend here
    public static function getBackendMenu($controller = null, $action = null)
    {
        if (empty($controller))
            $controller = FHtml::currentController();

        if (empty($action))
            $action = FHtml::currentAction();

        $application_id = FHtml::currentApplicationId();
        $db_security = !FHtml::isRoleAdmin() && FHtml::isDBSecurityEnabled();

        $session_key = "$application_id@backendMenu";

        if ($db_security) {
            $menu = FHtml::Session($session_key);
            if (isset($menu)) {
                FHtml::currentView()->params['cachedMenu'] = true;
                return $menu;
            }
        }

        // 1. get from Application Helper first
        $helper = FHtml::getApplicationHelper($application_id);

        if (isset($helper) && method_exists($helper, 'getBackendMenu')) {
            $menu =  $helper::getBackendMenu($controller, $action);

        } else {
            // 2. Otherwise, return default menu
            $menu[] = AuthMenu::buildDashBoardMenu();

            $modules = FHtml::getApplicationModulesComboArray();
            $menu = self::addAdminMenu($menu, $modules);
        }

        $menu[] = AuthMenu::menuItem (
            '#',
            'My Profile',
            'glyphicon glyphicon-user',
            in_array($controller, ['user']) && $action == 'profile',
            [],
            [
                AuthMenu::menuItem (
                    '/user/profile',
                    'Edit Profile',
                    '',
                    $controller == 'user' && $action == 'profile',
                    []
                ),

                AuthMenu::menuItem (
                    '/site/logout',
                    'Logout',
                    '',
                    false,
                    []
                )
            ]
        );

        //If Enabled DB Security then check auth_menu, auth_permission
        if ($db_security) {
            $menu = FSecurity::getAuthorizedMenu($menu);
            FHtml::Session($session_key, $menu);
        }

        return $menu;
    }

    public static function getDynamicMenuFromDB($controller = null, $action = null) {
        if (empty($controller))
            $controller = FHtml::currentController();

        if (empty($action))
            $action = FHtml::currentAction();

        // Otherwise, build dynamic Menu
        if (empty($modules))
            $modules = FHtml::currentAdminModules();

        $result[] = AuthMenu::buildDashBoardMenu();

        if (isset($mainMenu) && !empty($mainMenu)) {
            $result = array_merge($result, $mainMenu);
        }

        if (is_string($modules)) {
            $modules = explode(',', $modules);
        }

        foreach ($modules as $module) {
            $moduleObject = FHtml::getModuleObject($module);
            if (isset($moduleObject) && method_exists($moduleObject, 'createModuleMenu')) {
                $menu[] = $moduleObject::createModuleMenu();
            }
        }

        if (isset($menu) && !empty($menu))
            $result = array_merge($result, $menu);
        $menu = [];

        //Get from database
        $menuList = FHtml::getModels('settings_menu', ['is_active' => 1, 'group' => $group], 'sort_order', -1, 1, true);

        if (isset($menuList) && !empty($menuList) && !is_string($menuList)) {
            $result1 = [];
            $result2 = [];
            $moduleList = [];
            foreach ($menuList as $menuItem) {
                $module = $menuItem->module;
                if (key_exists($module, $result1)) {
                    $moduleMenu = $result1[$module];
                    $moduleController = $result2[$module];
                } else {
                    $moduleList[] = $module;
                    $moduleMenu = [];
                    $moduleController = [];
                }
                $moduleMenu[] = [
                    'label' => FHtml::t('common', $menuItem->name),
                    'name' => FHtml::t('common', $menuItem->name),
                    'route' => strpos($menuItem->url, 'http') === false ? FHtml::createUrl($menuItem->url) : $menuItem->url,
                    'active' => $controller == $menuItem->url,
                    'visible' => FHtml::isInRoles($menuItem->role),
                    'icon' => $menuItem->icon
                ];
                $moduleController[] = $menuItem->url;
                $result1[$module] = $moduleMenu;
                $result2[$module] = $moduleController;
            }

            foreach ($moduleList as $moduleItem) {
                $existed = false;
                for ($i = 0; $i < count($result); $i = $i + 1) {
                    $resultItem = $result[$i];
                    $moduleName = $resultItem['name'];
                    if (key_exists($moduleName, $result1)) {
                        $resultItem['children'] = array_merge($resultItem['children'], $result1[$moduleName]); // add to existing children
                        $result[$i] = $resultItem;
                        $existed = true;
                        break;
                    }
                }

                if (!$existed) {
                    $menuItem = [
                        'active' => in_array($controller, $result2[$moduleItem]),
                        'open' => true,
                        'visible' => true || FHtml::isInRole($moduleItem, 'active'),
                        'name' => FHtml::t('common', $moduleItem),
                        'label' => FHtml::t('common', $moduleItem),
                        'icon' => 'glyphicon glyphicon-list-alt',
                        'children' => $result1[$moduleItem]
                    ];

                    $menu[] = $menuItem;
                }
            }
        }

        if (isset($menu) && !empty($menu))
            $result = array_merge($result, $menu);

        $result[] = AuthMenu::buildAdministrationMenu();
        $result[] = AuthMenu::buildToolsMenu();

        return $result;
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

                    if (!file_exists(FFile::getFullFileName("$class.php")));
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
                $actions[$moduleId] = self::getModuleControllers($cDir, false);
            }

            return $actions;
        }

        if (!StringHelper::endsWith($controllerDir, 'controllers'))
        {
            if (key_exists($controllerDir, \Yii::$app->modules )) {
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
            foreach ($controllerlist as $controller):
                $fulllist = array_merge($fulllist, self::getControllerActions($controllerDir, $controller));
            endforeach;
        } else {
            foreach ($controllerlist as $controller):
                $fulllist[BaseInflector::camel2id(substr($controller, 0, -14))] = substr($controller, 0, -14);
            endforeach;
        }
        return $fulllist;
    }

    public static function getApiControllerActions() {
        $application_id = FHtml::currentApplicationId();
        $modules = array_merge(['' => ''], FHtml::getApplicationModulesComboArray());
        $actions = [];

        foreach ($modules as $module => $moduleName) {
            $arrays = empty($module) ? ["applications\\$application_id\backend\controllers\ApiController", "backend\controllers\ApiController"]
                :  ["applications\\$application_id\backend\modules\\$module\controllers\ApiController", "backend\modules\\$module\controllers\ApiController"];
            foreach ($arrays as $api_class) {
                if (class_exists($api_class)) {
                    $Object = \Yii::createObject(['class' => $api_class], ['api', FHtml::currentModule()]);
                    $actions1 = isset($Object) ? $Object->actions() : [];
                    foreach ($actions1 as $action_name => $action_params) {
                        $action_url = empty($module) ? "api/$action_name" : "$module/api/$action_name";

                        if (is_array($action_url))
                            $action_url = $action_url[0];

                        $actions = array_merge($actions, [$action_url => $action_params['class']]);
                    }
                }
            }
        }
        return $actions;
    }

    public static function getControllerActions($controllerDir = '', $controller = '') {
        $fulllist = [];
        if (!StringHelper::endsWith($controllerDir, 'controllers') && !empty($controller))
        {
            if (key_exists($controllerDir, \Yii::$app->modules )) {
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
            if (!StringHelper::endsWith($controllerDir, 'controllers'))
                $controllerDir = $controllerDir . '/controllers';
        }

        if (!empty($controller)) {
            if (strpos('.', $controller) !== false)
                $controller = BaseInflector::camelize($controller);

            if (StringHelper::endsWith($controller, 'Controller')) {
                $controller = ucfirst($controller) . '.php';
            }
            if (!StringHelper::endsWith($controller, 'Controller.php')) {
                $controller = ucfirst($controller) . 'Controller.php';
            }

            $handle = is_file($controllerDir . '/' . $controller) ? fopen($controllerDir . '/' . $controller, "r") : null;

        } else {
            $handle = is_file($controllerDir) ? fopen($controllerDir, "r") : null;
        }

        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if (preg_match('/public function action(.*?)\(/', $line, $display)):
                    if (strlen($display[1]) > 2):
                        $fulllist[BaseInflector::camel2id(substr($controller, 0, -14))][] = strtolower($display[1]);
                    endif;
                endif;
            }
            fclose($handle);
        }
        return $fulllist;
    }

    public static function getAuthorizedMenu($menu = []) {
        if (empty($menu))
            $menu = FSecurity::getBackendMenu();

        //hide all menus that user does not have access to
        foreach ($menu as $idx => $menu_item) {
            if (!is_array($menu_item) || (key_exists('visible', $menu_item) && $menu_item['visible'] == false))
                continue;
            $child_menu = [];
            if (key_exists('children', $menu_item))
                $child_menu = $menu_item['children'];

            $child_visible = false;
            foreach ($child_menu as $idx2 => $child_menu_item) {
                if (!isset($child_menu[$idx2]['name'])) {
                    unset($child_menu[$idx2]);
                    continue;
                }

                $url = $child_menu_item['url'];

                $url_arr = FHtml::parseUrl($url);
                $controller = $url_arr['controller'];
                $module = $url_arr['module'];
                $action = $url_arr['action'];

                $child_menu[$idx2]['module'] = $module;
                $child_menu[$idx2]['controller'] = $controller;
                $child_menu[$idx2]['action'] = $action;

                if ($url == 'site/index')
                    $access = true;
                else
                    $access = FHtml::isInRole($controller, $action, '', '', '', false) || FHtml::isInRole($module, $action, '', '', '', false);

                $child_menu[$idx2]['visible'] = $access;

                if ($child_menu[$idx2]['visible']) {
                    $child_visible = true;
                }
            }
            if (!empty($child_menu) && $child_visible) {
                $menu_item['children'] = $child_menu;
                $menu[$idx] = $menu_item;
            } else if (!empty($child_menu) && !$child_visible) {
                unset($menu[$idx]);
            }
        }

        return $menu;
    }

    public static function getAuthorizedModulesControllersFromMenu($menu = []) {
        if (empty($menu))
            $menu = FSecurity::getBackendMenu();

        //hide all menus that user does not have access to
        $result = [];
        foreach ($menu as $idx => $menu_item) {
            if (!is_array($menu_item) || (key_exists('visible', $menu_item) && $menu_item['visible'] == false))
                continue;
            $child_menu = [];
            if (key_exists('children', $menu_item))
                $child_menu = $menu_item['children'];
            $child_result = [];
            foreach ($child_menu as $idx2 => $child_menu_item) {
                $url = $child_menu_item['url'];
                $url_arr = explode('/', $url);
                $action = count($url_arr) > 1 ? $url_arr[count($url_arr) - 1] : '';
                $l = strpos( $action, '?');
                if ($l > 0)
                    $action = substr($action, 0, $l);

                $controller = count($url_arr) > 2 ? $url_arr[count($url_arr) - 2] : '';
                $module = count($url_arr) > 3 ? $url_arr[count($url_arr) - 3] : '';

                if ($url == 'site/index')
                    $access = true;
                else
                    $access = FHtml::isInRole($controller, $action) || FHtml::isInRole($module, $action);

                if ($access && !empty($module)) {
                    if (key_exists($module, $result)) {
                        $item = $result[$module];

                        $result[$module] = array_merge($item, [$controller => $child_menu_item['name']]);
                    } else {
                        $result[$module] = [$controller => $child_menu_item['name']];
                    }
                }
            }
        }

        return $result;
    }

    //Hung: return Authorized Columuns for one model, used in Search model, Index
    public static function getAuthorizedColumns($model) {
        if (is_string($model))
            $model = FHtml::createModel($model);
        if (isset($model) && is_object($model)) {
            if (FHtml::field_exists($model, 'getAuthorizedColumns')) {
                return $model::getAuthorizedColumns();
            }
        }
        return [];
    }

    //Hung: return Authorized Condition for one model, used in Search model, Index
    public static function getAuthorizedSearchParamsForUser($model = null, $user = null) {
        if (isset($model))
            $controller = str_replace('_', '-', FHtml::getTableName($model));
        else
            $controller = FHtml::currentController();

        $roles = FHtml::getUserRoles($user);

        $extraQuery = [];

        if (is_array($roles)) {
            foreach ($roles as $i => $role) {
                if (StringHelper::startsWith($role, "$controller{")) {
                    $extraQuery = str_replace("$controller", '', $role);
                    $extraQuery = FHtml::decode($extraQuery);
                }
            }
        }
        return $extraQuery;
    }

    public static function isEditInGrid($module, $field, $form_type = '', $manualValue = null)
    {
        if (isset($manualValue))
            return $manualValue;

        if (FHtml::isInArray($field, ['id', 'application_id']) || FHtml::isInArray($field, ['image', 'thumbnail', 'avatar', 'banner'])) {
            return false;
        }

        return self::isAuthorized(self::ACTION_EDIT, $module, $field, 'index', $form_type, '', '', $manualValue);
    }

    public static function isVisibleInGrid($module, $field, $form_type = '', $manualValue = null)
    {
        if (isset($manualValue)) {
            return $manualValue;
        }

        $object_type = BaseInflector::camel2id($module, '_');
        $result = self::isAuthorized(self::ACTION_VIEW, $module, $field, 'index', $form_type, '', '', $manualValue);

        $controller = str_replace('-', '_', FHtml::currentController());
        if ($controller != $object_type) {
            $result = $result || self::isAuthorized(self::ACTION_VIEW, $controller, $field, 'index', $form_type, '', '', $manualValue);
        }

        if (!$result)
            return false;

        if (FHtml::isDynamicFormEnabled()) {
            $column = FHtml::getObjectColumn($object_type, $field);
            if (isset($column) && (!FHtml::getFieldValue($column, 'is_column')))
                return false;
        }

        return true;
    }

    public static function isVisibleInCreate($module, $field, $form_type = '', $manualValue = null)
    {
        return self::isAuthorized(self::ACTION_VIEW, $module, $field, 'create', $form_type, '', '', $manualValue);
    }

    public static function isEditInCreate($module, $field, $form_type = '', $manualValue = null)
    {
        return self::isAuthorized(self::ACTION_EDIT, $module, $field, 'create', $form_type, '', '', $manualValue);
    }

    public static function isVisibleInUpdate($module, $field, $form_type = '', $manualValue = null)
    {

        return self::isAuthorized(self::ACTION_EDIT, $module, $field, 'update', $form_type, '', '', $manualValue);
    }

    public static function isEditInUpdate($module, $field, $form_type = '', $manualValue = null)
    {

        return self::isAuthorized(self::ACTION_EDIT, $module, $field, 'update', $form_type, '', '', $manualValue);
    }

    public static function isVisibleInView($module, $field, $form_type = '', $manualValue = null)
    {

        return self::isAuthorized(self::ACTION_EDIT, $module, $field, 'view', $form_type, '', '', $manualValue);
    }

    public static function isLocalhost() {
        return FHtml::currentHost() == 'http://localhost';
    }

    public static function isAdminUser() {
        return self::isRoleAdmin() && strtolower(FHtml::currentUsername()) == 'admin';
    }

    public static function logAction($condition = [], $action = '', $status = '', $info = '', $object_type = '', $object_id = '', $user_id = '', $ip_address = '') {
        if (!self::isUserLogsEnabled())
            return;

        if (!empty($condition))
            $log = UserLogs::findOne($condition);

        $completed = false;
        if (!isset($log)) {
	        $log = new UserLogs();
        }
        else
            $completed = true;


//	    $log->ip_address = !empty($ip_address) ? $ip_address : FHtml::currentIPAddress();
//        $log->action = !empty($action) ? $action : FHtml::currentAction();
//        $log->link_url = FHtml::currentUrl();
//        $log->log_date = FHtml::Today();
//        $log->object_type = !empty($object_type) ? $object_type : FHtml::currentController();
//        $log->object_id = !empty($object_id) ? $object_id : FHtml::getRequestParam(['id', 'object_id']);
//        $log->user_id = !empty($user_id) ? $user_id : FHtml::currentUserId();
//        $log->application_id = FHtml::currentApplicationId();
//        $log->status = !empty($status) ? $status : ($completed ? FHtml::STATUS_FINISH : '');
//        $log->note = $info;
//        if ($completed && $status != FHtml::NULL_VALUE)
//            $log->modified_date = FHtml::Now();
//        else
//            $log->created_date = FHtml::Now();
//
//        $log->save();

	    // long test with postgres sql
	    $columns = $log->getAttributes();
	    ArrayHelper::remove($columns, 'id');
	    $columns = array_combine(array_keys($columns), [
		    FHtml::Today(),
		    !empty($user_id) ? $user_id : FHtml::currentUserId(),
		    !empty($action) ? $action : FHtml::currentAction(),
		    !empty($object_type) ? $object_type : FHtml::currentController(),
		    !empty($object_id) ? $object_id : FHtml::getRequestParam(['id', 'object_id']),
		    FHtml::currentUrl(),
		    !empty($ip_address) ? $ip_address : FHtml::currentIPAddress(),
		    '',
		    $info,
		    !empty($status) ? $status : ($completed ? FHtml::STATUS_FINISH : ''),
		    FHtml::Now(),
		    FHtml::Now(),
		    FHtml::currentApplicationId()
	    ]);

	    $command = FHtml::currentDb()->createCommand();

	    if ($log->isNewRecord) {
		    ArrayHelper::remove($columns, 'modified_date');
		    $command = $command->insert($log::tableName(), $columns);
	    }
	    else {
		    ArrayHelper::remove($columns, 'created_date');
			$command = $command->update($log::tableName(), $columns, ['id' => $log->id]);
	    }
		$command->execute();
	    FHtml::clearMessages();
    }

    public static function isUserLogsEnabled() {
        if (!FHtml::isTableExisted('user_logs') || !FHtml::isModuleExisted('users') || !FConfig::settingUserActionsLogEnabled())
            return false;

        return true;
    }

    public static function getUserName($userid, $keyField = '', $displayField = 'name')
    {
        if (!isset($userid))
            return '';

        if (empty($keyField)) {
            if (is_numeric($userid))
                $keyField = 'id';
            else if (is_string($userid))
                $keyField = 'username';
            else
                $keyField = 'id';
        }

        if (empty($displayField))
            $displayField = 'name';

        if (is_object($userid)) { //if pass $model as $userid
            $result = FHtml::getFieldValue($userid, $displayField);
        } else {


            $sql_select = '*';
            $sql_table = 'user';
            $query = new FQuery;
            $query->select($sql_select)
                ->from($sql_table);
            $query->andWhere([$keyField => $userid]);
            $data = $query->one();

            if (isset($data))
                $result = $data[$displayField];
            else
                $result = $userid;
        }

        return $result;
    }

    public static function generateCode($userId)
    {
        $s = strtoupper(md5(uniqid(rand(), true)));
        //return substr($s . $userId,18,strlen($s));
        return substr($s . $userId, 18, 6);
    }

    public static function generateRandomCode($length)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }
        return strtoupper($result);
    }

    public static function getToken($length)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet) - 1;
        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[FHtml::crypto_rand_secure(0, $max)];
        }
        return $token;
    }

    public static function crypto_rand_secure($min, $max)
    {
        $range = $max - $min;
        if ($range < 1) return $min; // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int)($log / 8) + 1; // length in bytes
        $bits = (int)$log + 1; // length in bits
        $filter = (int)(1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
    }

    /**
     * @param string $user_alias_name
     * @param int $user_id
     * @return UserAlias|null
     */
    public static function getUserAlias($user_alias_name = "", $user_id = 0, $user_name = '') {
        $current_user_id = self::currentUserId();
        if ($user_id == 0) {
            $user_id = $current_user_id;
        }
        if (empty($user_name))
            $user_name = FHtml::currentUsername();

        if (empty($user_alias_name))
            $user_alias_name = FSecurity::getUserAliasTable();

        $user_alias = UserAlias::findOne(['user_id' => $user_id, 'alias_table' => $user_alias_name]);
        if (isset($user_alias) && !empty($user_alias)) {
            $user_alias_model = FHtml::findOne($user_alias_name, ['id' => $user_alias->alias_id]);
        } else {
            if (!empty($user_name))
                $user_alias_model = FHtml::findOne($user_alias_name, ['username' => $user_name]);
            else
                $user_alias_model = null;
        }

        if (isset($user_alias_model) && !empty($user_alias_model)) {
            return $user_alias_model;
        }

        return null;
    }

    public static function getUserAliasTable() {
        return self::TABLE_ALIAS_USER;
    }

    /**
     * @param $user_alias_id
     * @return mixed|null|\yii\web\Session
     */
    public static function setCurrentAliasId($user_alias_id) {
        return FHtml::Session('user_alias_id', $user_alias_id);
    }

    public static function currentUserAliasId() {
        $user_id = self::currentUserId();

        if (empty($user_id))
            return null;

        $table = self::getUserAliasTable();
        if ($table == 'user' || empty($table))
            return $user_id;

        $user_model = FHtml::currentUserIdentity();

        if (isset($user_model) && FHtml::field_exists($user_model, 'getAliasUserId')) {
            return is_object($user_model->aliasUserId) ? $user_model->aliasUserId->id : $user_model->aliasUserId;
        }

        $user_alias_id = FHtml::Session('user_alias_id');

        if (!isset($user_alias_id) && empty($user_alias_id)) {
            $user_alias = self::getUserAlias($table);
            if (isset($user_alias)) {
                $user_alias_id = FHtml::getFieldValue($user_alias, ['id', 'user_id']);
                self::setCurrentAliasId($user_alias_id);
            }
            return $user_alias_id;
        }

        return $user_alias_id;
    }

    /**
     * @param User $model
     * @return bool
     */
    public static function loginUserAlias($model = null) {

        if (!$model) {
            return false;
        }

        $table = self::getUserAliasTable();

        $model_user = FHtml::createModel($table);

        if (isset($model_user)) {
            /** @var QmsUser $user */
            $user = $model_user::find()->where([self::USER_FIELD_USERNAME => $model->username])->one();

            if (isset($user) && !empty($user) && method_exists($user, 'validatePassword') && $user->validatePassword($model->password)) {
                $new_user = self::addUser($model->username, $user->email, $model->password);
                self::updateUserOnlineStatus($new_user, true);
                return true;
            }
        }

        return false;
    }

    public static function updateUserOnlineStatus($user, $is_login = true) {
        if (isset($user) && is_object($user)) {
            $save = false;
            if (FHtml::field_exists($user, self::USER_FIELD_IS_ONLINE)) {
                FHtml::setFieldValue($user, self::USER_FIELD_IS_ONLINE, $is_login);
                $save = true;
            }

            $field = $is_login ? self::USER_FIELD_LAST_LOGIN : self::USER_FIELD_LAST_LOGOUT;

            if (FHtml::field_exists($user, $field)) {
                FHtml::setFieldValue($user, $field, FHtml::Now());
                $save = true;
            }

            if ($save)
                return $user->save();
        }

        return false;
    }

    public static function getUserAccessToken($userid = null) {

        if (isset($userid) && is_object($userid) && method_exists($userid, 'getAuthKey'))
            return $userid->getAuthKey();

        if (!empty($userid))
        {
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

    public static function getUserStatusArray() {
        return [\common\models\User::STATUS_ACTIVE => FHtml::t('common', 'Enabled'), \common\models\User::STATUS_DISABLED => FHtml::t('common', 'Disabled')];
    }

    public function generateLoginToken($user_id) {
        return md5($user_id . time());
    }


}