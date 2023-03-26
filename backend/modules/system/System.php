<?php

namespace backend\modules\system;

use backend\models\AuthMenu;
use backend\modules\system\models\ObjectActivityAPI;
use common\components\FBackup;
use common\components\FHtml;
use yii\base\Module;

/**
 * api module definition class
 */
class System extends Module
{
    const FIELDS_GROUP = [ //table.column

    ];

    const LOOKUP = [    // 'table.column' => array(), 'table.column' => 'table1.column1'
        'settings_api.type' => ['object', 'array', 'link', 'html']
    ];
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\system\controllers';

    public static function getLookupArray($column = '')
    {
        if (key_exists($column, self::LOOKUP)) {
            $data = self::LOOKUP[$column];

            $data = FHtml::getComboArray($data);

            return $data;
        }

        return [];
    }

    public static function createModuleMenu($menus = ['object*', 'apps', 'setting*', 'user', 'auth*', 'application', 'tools', 'tools-import', 'settings-menu', 'object-collection', 'push-notification'])
    {
        $controller = FHtml::currentController();
        $action = FHtml::currentAction();
        $module = FHtml::currentModule();

        $menu[] = AuthMenu::menuItem(
            '#',
            'Administrations',
            'glyphicon glyphicon-th',
            (in_array($module, ['', 'system']) && FHtml::isInArray($controller, $menus)) || ($controller == 'site' && $action == 'apps' && in_array('apps', $menus)),
            [],
            [
                !FHtml::isInArray('user', $menus) ?  null : AuthMenu::menuItem(
                    '/user/index',
                    'Users',
                    'glyphicon glyphicon-cog',
                    $controller == 'user',
                    [FHtml::ROLE_ADMIN]
                ),
                FHtml::frameworkVersion() == 'framework' || !FHtml::isDBSecurityEnabled() || !FHtml::isInArray('auth-group', $menus) ?  null : AuthMenu::menuItem(
                    '/system/auth-group/index',
                    'User Groups',
                    'glyphicon glyphicon-cog',
                    $controller == 'auth-group',
                    [FHtml::ROLE_ADMIN]
                ),
                FHtml::frameworkVersion() == 'framework' || !FHtml::isDBSecurityEnabled() || !FHtml::isInArray('auth-role', $menus) ?  null : AuthMenu::menuItem(
                    '/system/auth-role/index',
                    'User Rights',
                    'glyphicon glyphicon-cog',
                    $controller == 'auth-role',
                    [FHtml::ROLE_ADMIN]
                ),
                !FHtml::isInArray('object-category', $menus) ?  null : AuthMenu::menuItem(
                    '/object-category/index',
                    'Categories',
                    'glyphicon glyphicon-book',
                    $controller == 'object-category',
                    [FHtml::ROLE_ADMIN]
                ),
                (!FHtml::isInArray('settings', $menus)) ? null : AuthMenu::menuItem(
                    '/settings/index',
                    'Settings',
                    'glyphicon glyphicon-cog',
                    $controller == 'settings',
                    [FHtml::ROLE_ADMIN]
                ),
                (!FHtml::isInArray('settings-api', $menus)) ? null : AuthMenu::menuItem(
                    '/settings-api/index',
                    'APIs',
                    'glyphicon glyphicon-cog',
                    $controller == 'settings-api',
                    [FHtml::ROLE_ADMIN]
                ),
//                !FHtml::isInArray('object-collection', $menus) ?  null : AuthMenu::menuItem(
//                    '/object-collection/index',
//                    'Collection',
//                    'glyphicon glyphicon-cog',
//                    $controller == 'object-collection'
//                ),
//                !FHtml::isInArray('object-calendar', $menus) ?  null : AuthMenu::menuItem(
//                    'system/object-calendar/index',
//                    'Calendar',
//                    'fa fa-calendar',
//                    $controller == 'object-calendar',
//                    []
//                ),


//                (!FHtml::isInArray('settings-api', $menus)) ? null : AuthMenu::menuItem(
//                    '/system/settings-api/index',
//                    'API',
//                    'glyphicon glyphicon-cog',
//                    $controller == 'settings-api',
//                    [FHtml::ROLE_ADMIN]
//                ),
//                !FHtml::isInArray('object-setting', $menus) || !FHtml::isDBSettingsEnabled() ? null : AuthMenu::menuItem(
//                    '/object-setting/index',
//                    'Settings',
//                    'glyphicon glyphicon-book',
//                    $controller == 'object-setting',
//                    [FHtml::ROLE_ADMIN]
//                ),

                !FHtml::isInArray('tools-import', $menus) ?  null : AuthMenu::menuItem(
                    '/tools-import/index',
                    'Import',
                    'glyphicon glyphicon-cog',
                    $controller == 'tools-import',
                    [FHtml::ROLE_ADMIN]
                ),
                !FHtml::isInArray('apps', $menus) ?  null : AuthMenu::menuItem(
                    '/site/apps',
                    'Apps',
                    'glyphicon glyphicon-cog',
                    $controller == 'site' && $action == 'apps',
                    [FHtml::ROLE_ADMIN]
                ),

                !FHtml::isInArray('tools', $menus) ?  null : AuthMenu::menuItem(
                    '/tools',
                    'System',
                    'glyphicon glyphicon-cog',
                    $controller == 'tools',
                    [FHtml::ROLE_ADMIN]
                ),

//                FHtml::frameworkVersion() == 'framework' || !FHtml::isInArray('settings-menu', $menus) ?  null : AuthMenu::menuItem(
//                    '/system/settings-menu/index',
//                    'Menus',
//                    'glyphicon glyphicon-cog',
//                    $controller == 'settings-menu',
//                    [FHtml::ROLE_ADMIN]
//                ),

//                (!FHtml::isInArray('setting', $menus)) ? null : AuthMenu::menuItem(
//                    '/system/setting/index',
//                    'Setting',
//                    'glyphicon glyphicon-cog',
//                    $controller == 'setting' && $action != 'push-notification',
//                    [FHtml::ROLE_ADMIN]
//                ),

                !FHtml::isInArray('push-notification', $menus) || !FHtml::isDBSettingsEnabled() ? null : AuthMenu::menuItem(
	                '/system/setting/push-notification',
	                'Notification',
	                'fa fa-cloud-upload',
	                $controller == 'setting' && $action == 'push-notification',
	                [FHtml::ROLE_ADMIN]
                ),
                !FHtml::isInArray('settings-text', $menus) || (!FHtml::isLanguagesEnabled() && empty(FHtml::currentLang())) ? null : AuthMenu::menuItem(
                    '/settings-text/index',
                    'Translations',
                    'glyphicon glyphicon-cog',
                    $controller == 'settings-text',
                    [FHtml::ROLE_ADMIN]
                ),
//                FHtml::frameworkVersion() == 'framework' || !FHtml::isDynamicObjectEnabled() || !FHtml::isInArray('object-type', $menus) ?  null : AuthMenu::menuItem(
//                    '/system/object-type/index',
//                    'Objects',
//                    'glyphicon glyphicon-book',
//                    $controller == 'object-type',
//                    [FHtml::ROLE_ADMIN]
//                ),
                FHtml::frameworkVersion() == 'framework' || !FHtml::isDynamicObjectEnabled() || !FHtml::isInArray('object-actions', $menus) ?  null : AuthMenu::menuItem(
                    '/system//object-actions/index',
                    FHtml::t('common', 'Object Changes Log'),
                    'glyphicon glyphicon-book',
                    $controller == 'object-actions',
                    [FHtml::ROLE_ADMIN]
                ),
//                FHtml::frameworkVersion() == 'framework' || !FHtml::isRootUser() || !FHtml::isInArray('application', $menus) ?  null : AuthMenu::menuItem(
//                    '/system/application/index',
//                    FHtml::t('common', 'Applications'),
//                    'glyphicon glyphicon-book',
//                    $controller == 'applications',
//                    [FHtml::ROLE_ADMIN]
//                ),
            ]
        );

        return $menu;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    public static function updateRating($object_id, $object_type)
    {
        if (FHtml::isTableExisted($object_type)) {
            FHtml::executeSql("UPDATE $object_type SET rate_count = (SELECT COUNT(*) FROM object_review WHERE object_id = $object_id AND object_type = '$object_type'), rate = (SELECT AVG(rate) FROM object_review WHERE object_id = $object_id AND object_type = '$object_type') WHERE id = $object_id");
        }
    }

    public static function updateComment($object_id, $object_type)
    {
        if (FHtml::isTableExisted($object_type)) {
            FHtml::executeSql("UPDATE $object_type SET comment_count = (SELECT COUNT(*) FROM object_comment WHERE object_id = $object_id AND object_type = '$object_type') WHERE id = $object_id");
        }
    }

    public static function updateReply($parent_id, $parent_type)
    {
        if (FHtml::isTableExisted($parent_type)) {
            FHtml::executeSql("UPDATE $parent_type oc1 INNER JOIN (SELECT COUNT(*) as count FROM object_comment WHERE parent_id = $parent_id) oc2 SET oc1.reply_count = oc2.count WHERE oc1.id = $parent_id");
        }
    }

    public static function updateActivity($object_id, $object_type, $type)
    {
        $update_field = $type . "_count";
        if (FHtml::isTableExisted($object_type)) {
            if ($type == ObjectActivityAPI::TYPE_FAVOURITE) {
                if (FHtml::field_exists(FHtml::getModel($object_type), 'like_count')) {
                    $update_field = 'like_count';
                }
            }
            if (FHtml::field_exists(FHtml::getModel($object_type), $update_field)) {
                FHtml::executeSql("UPDATE $object_type SET $update_field = (SELECT COUNT(*) FROM object_activity WHERE object_id = $object_id AND object_type = '$object_type' AND type = '$type') WHERE id = $object_id");
            }
        }
    }

    public static function getSortString($sort_field = 'id', $sort_order = 'DESC')
    {
        //new/mine/like/reply/dislike
        switch ($sort_field) {
            case 'new':
                $sort_string = "created_date DESC";
                break;
            //case 'mine':
                //$sort_string = '';
                //break;
            case 'like':
                $sort_string = "like_count DESC";
                break;
            case 'reply':
                $sort_string = "reply_count DESC";
                break;
            case 'dislike':
                $sort_string = "dislike_count DESC";
                break;
            default:
                $sort_string = $sort_field . " " . $sort_order;;
        }
        return $sort_string;
    }

    public static function import($model) {
        return FBackup::import($model);
    }
}
