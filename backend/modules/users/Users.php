<?php

namespace backend\modules\users;

use backend\models\AuthMenu;
use common\components\FHtml;
use yii\base\Module;

/**
 * api module definition class
 */
class Users extends Module
{
    const FIELDS_GROUP = [ //table.column
    ];

    const LOOKUP = [    // 'table.column' => array(), 'table.column' => 'table1.column1'
    ];

    public $controllerNamespace = 'backend\modules\users\controllers';

    public static function getLookupArray($column = '') {
        if (key_exists($column, self::LOOKUP)) {
            $data = self::LOOKUP[$column];

            $data = FHtml::getComboArray($data);

            return $data;
        }

        return [];
    }

    public static function createModuleMenu($menu = ['user-feedback', 'user-logs'])
    {
        $controller = FHtml::currentController();

        $menu[] = AuthMenu::menuItem(
            '#',
            'Users',
            'glyphicon glyphicon-th',
            FHtml::isInArray($controller, $menu) && !empty($menu),
            [],
            [

                !FHtml::isInArray('user-feedback', $menu) ? null : AuthMenu::menuItem(
                    '/users/user-feedback/index',
                    'User Feedbacks',
                    'glyphicon glyphicon-cog',
                    $controller == 'user-feedback',
                    [FHtml::ROLE_ADMIN, FHtml::ROLE_MODERATOR]
                ),
                !FHtml::isInArray('user-logs', $menu) ? null : AuthMenu::menuItem(
                    '/users/user-logs/index',
                    'User Logs',
                    'glyphicon glyphicon-cog',
                    $controller == 'user-logs',
                    [FHtml::ROLE_ADMIN]
                ),
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
}
