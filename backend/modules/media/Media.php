<?php

namespace backend\modules\media;
use backend\models\AuthMenu;
use backend\models\User;
use common\components\FHtml;
use common\components\FSecurity;
use yii\base\Module;

/**
 * api module definition class
 */
class Media extends Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\media\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    public static function createModuleMenu($menu = ['media-file'])
    {
        $controller = FHtml::currentController();

        $menu[] = AuthMenu::menuItem(
            '#',
            'Media',
            'glyphicon glyphicon-cog',
            FHtml::isInArray($controller, $menu),
            [User::ROLE_ADMIN],
            [
                !FHtml::isInArray('media-file', $menu) ? null : AuthMenu::menuItem(
                    '/media/media-file/index',
                    'Media',
                    'glyphicon glyphicon-wrench',
                    $controller == 'media-file',
                    [User::ROLE_ADMIN]
                )
            ]
        );

        return $menu;
    }


}
