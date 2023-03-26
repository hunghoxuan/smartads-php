<?php

namespace backend\models;

use backend\modules\system\System;
use common\components\AccessRule;
use common\components\FConfig;
use common\components\FConstant;
use common\components\FHtml;
use common\components\FSecurity;
use Yii;
use yii\helpers\BaseInflector;
use yii\helpers\Json;

/**
 * @property AuthPermission[] $roles
 */
class AuthMenu extends AuthMenuBase
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'route', 'group', 'role', 'is_active'], 'required'],
            [['created_date', 'modified_date'], 'safe'],
            [['icon', 'name', 'route'], 'string', 'max' => 255],
            [['group', 'created_user', 'modified_user', 'application_id'], 'string', 'max' => 100],
            [['role'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return FHtml::currentDb();
    }

    /**
     * Connections
     */
    public function getRoles()
    {
        return $this->hasMany(AuthPermission::className(), ['object_id' => 'id'])
            ->andOnCondition(['AND',
                ['relation_type' => 'menu-role'],
                ['object2_type' => 'auth_menu'],
                ['object_type' => 'auth_role']
            ]);
    }

	/**
	 * @param       $route
	 * @param       $name
	 * @param       $icon
	 * @param       $active
	 * @param array $roles
	 * @param bool  $children
	 * @param int   $badge
	 * @return array
	 */
	public static function menuItem($route, $name, $icon, $active, $roles = array(), $children = false, $badge = -1)
    {
        return FSecurity::createBackendMenuItem($route, $name, $icon, $active, $roles, $children, $badge);
    }

    public static function menuItem2($menus, $name, $controller2, $icon = 'glyphicon glyphicon-cog', $active = true, $roles = array(FHtml::ROLE_ADMIN), $controller = '', $action = '', $children = false, $badge = -1)
    {
        if (empty($controller))
            $controller = FHtml::currentController();


        if (strpos($controller2, "/") !== false) {
            $arr = explode('/', $controller2);
            if (empty($arr[0]))
                array_shift($arr);
            $module = $arr[0];
            $controller2 = $arr[1];
        } else {
            $module = '';
        }

        $route = "$module/$controller2";
        return !FHtml::isInArray($controller2, $menus) ?  null : AuthMenu::menuItem(
            $route,
            $name,
            $icon,
            $active && ($controller2 == $controller),
            $roles, $children, $badge
        );
    }

    public static function buildDashBoardMenu()
    {
        $controller = FHtml::currentController();

        return AuthMenu::menuItem (
            'site/index',
            'Home',
            'fa fa-list',
            $controller == 'site',
            []
        );
    }

    public static function buildAdministrationMenu()
    {
        $currentRole = FHtml::getCurrentRole();
        if ($currentRole != User::ROLE_ADMIN || FHtml::frameworkVersion() == 'framework')
            return null;

        $menu = System::createModuleMenu();

        return $menu;
    }

    public static function buildToolsMenu()
    {
        $controller = FHtml::currentController();
        $action = FHtml::currentAction();
        $currentRole = FHtml::getCurrentRole();
        if ($currentRole != User::ROLE_ADMIN)
            return null;

        $menu = array(
            'active' => FHtml::isInArray($controller, ['tools*']),
            'name' => Yii::t('common', 'Tools'),
            'icon' => 'glyphicon glyphicon-wrench',
            'url' => FHtml::createUrl('/tools/index'),
            'children' => array (
                array(
                    'label' => Yii::t('common', 'Api'),
                    'active' => in_array($controller, ['tools']) AND ($action == 'api'),
                    'url' => FHtml::createUrl('tools/api'),
                    'icon' => '',
                ),
                array(
                    'label' => Yii::t('common', 'Cache'),
                    'active' => $controller == 'tools' AND ($action == 'cache'),
                    'url' => FHtml::createUrl('tools/cache'),
                    'icon' => '',
                ),
                array(
                    'label' => Yii::t('common', 'Phpmyadmin'),
                    'active' => in_array($controller, ['tools']) AND ($action == 'api'),
                    'url' => FHtml::currentDomain() . '/phpmyadmin',
                    'icon' => '',
                ),
                array(
                    'label' => Yii::t('common', 'Swagger (API test)'),
                    'active' => in_array($controller, ['tools']) AND ($action == 'api'),
                    'url' => FHtml::currentDomain() . '/swagger',
                    'icon' => '',
                ),
            )
        );

        return $menu;
    }
}
