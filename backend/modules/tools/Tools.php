<?php

namespace backend\modules\tools;
use backend\models\AuthMenu;
use backend\modules\purchase\models\PurchaseItem;
use backend\modules\purchase\models\PurchaseItemSearch;
use backend\modules\purchase\models\PurchaseRequest;
use backend\modules\purchase\Purchase;
use backend\modules\store\models\StoreExport;
use backend\modules\store\models\StoreImport;
use backend\modules\store\models\StoreProduct;
use backend\modules\system\models\ObjectChange;
use common\components\AccessRule;
use common\components\FBackup;
use common\components\FExcel;
use common\components\FHtml;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * purchase module definition class
 */
class Tools extends \yii\base\Module
{
    public static function createModuleMenu($menu = ['tools-import', 'tools-copy', 'tools'])
    {
        $controller = FHtml::currentController();
        $action = FHtml::currentAction();

        $menu[] = AuthMenu::menuItem(
            '#',
            'Tools',
            'glyphicon glyphicon-th',
            FHtml::isInArray($controller, $menu) && !empty($menu),
            [],
            [
                !FHtml::isInArray('tools-import', $menu) ? null : AuthMenu::menuItem(
                    '/tools/tools-import/index',
                    'Import',
                    'glyphicon glyphicon-cog',
                    $controller == 'tools-import',
                    [FHtml::ROLE_ADMIN]
                ),
                !FHtml::isInArray('tools', $menu) ? null : AuthMenu::menuItem(
                    '/tools/tools/setup',
                    'Make Setup',
                    'glyphicon glyphicon-cog',
                    $controller == 'tools' && $action == 'setup',
                    [FHtml::ROLE_ADMIN]
                ),
                !FHtml::isInArray('tools', $menu) ? null : AuthMenu::menuItem(
                    '/tools/tools/backup',
                    'Backup',
                    'glyphicon glyphicon-cog',
                    $controller == 'tools' && $action == 'backup',
                    [FHtml::ROLE_ADMIN]
                ),
                !FHtml::isInArray('tools', $menu) ? null : AuthMenu::menuItem(
                    '/tools/tools/cache',
                    'Cache',
                    'glyphicon glyphicon-cog',
                    $controller == 'tools' && $action == 'cache',
                    [FHtml::ROLE_ADMIN]
                ),
                !FHtml::isInArray('tools-copy', $menu) ? null : AuthMenu::menuItem(
                    '/tools/tools-copy/index',
                    'Copy',
                    'glyphicon glyphicon-cog',
                    $controller == 'tools-copy',
                    [FHtml::ROLE_ADMIN]
                ),
            ]
        );

        return $menu;
    }

    public static function import($model) {
        return FBackup::import($model);
    }
}
