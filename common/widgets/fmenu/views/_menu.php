<?php
/**
 * Created by PhpStorm.
 * User: Darkness
 * Date: 9/28/2016
 * Time: 2:58 PM
 *
 * @var $data array
 */
use frontend\components\Helper;
use yii\helpers\Html;
use backend\assets\CustomAsset;

//$asset = \frontend\assets\CustomAsset::register($this);
//$baseUrl = $asset->baseUrl;
//$baseUrl .= '/frontend/themes';
$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$baseUrl .= '/frontend/themes';
$user = Yii::$app->user->identity;
$this->registerCssFile($baseUrl . "/unify/assets/css/headers/header-default.css", []);

?>

<?php
if (is_array($data)) {
    foreach ($data as $item) {
        if ($item) { ?>
            <?php ?>

            <?php if ($item['type'] == 'mega-default') { ?>

                <!--use header-default.css-->

                <li class="dropdown mega-menu-fullwidth <?php if (isset($item['active']) AND $item['active']) echo "active" ?>">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                        <?php echo Html::decode($item['name']) ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <div class="mega-menu-content disable-icons">
                                <div class="" style="padding-left:20px">
                                    <div class="row equal-height">
                                        <?php Helper::displayMegaMenuDefault($item['children']) ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>

            <?php } ?>

            <?php if ($item['type'] == 'mega-v5') { ?>
                <!--use header-v5.css-->
                <li class="dropdown mega-menu-fullwidth <?php if (isset($item['active']) AND $item['active']) echo "active" ?>">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                        <?php echo Html::decode($item['name']) ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <div class="mega-menu-content">
                                <div class="" style="padding-left:20px">
                                    <div class="row">
                                        <?php Helper::displayMegaMenuV5($item['children']) ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
            <?php } elseif ($item['type'] == 'mega-v8') { ?>
                <!--use header-v8.css-->
                <li class="dropdown mega-menu-fullwidth">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                        <?php echo Html::decode($item['name']) ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <div class="mega-menu-content">
                                <div class="" style="padding-left:20px">
                                    <div class="row">
                                        <?php Helper::displayMegaMenuV8($item['children']) ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
            <?php } elseif ($item['type'] == 'mega-v8-mix') { ?>
                <!--use header-v8.css-->
                <li class="dropdown mega-menu-fullwidth">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                        <?php echo Html::decode($item['name']) ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <div class="mega-menu-content">
                                <div class="" style="padding-left:20px">
                                    <div class="row">
                                        <?php Helper::displayMegaMenuV8Mix($item['children'], $item['layout']) ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
            <?php } else { ?>
                <?php if ($item['type'] == 'tree' || $item['type'] == 'single') { ?>
                    <li class="<?php echo isset($item['children']) ? 'dropdown ' : '' ?>
                                    <?php if (isset($item['active']) AND $item['active']) echo "active" ?>">
                        <a <?php if (isset($item['children'])) : ?> class="dropdown-toggle" data-toggle="dropdown" <?php endif; ?>
                                href="<?php echo isset($item['url']) ? $item['url'] : 'javascript:void(0);' ?>">
                            <?php echo Html::decode($item['name']) ?>
                        </a>
                        <?php if ($item['type'] == 'tree') { ?>
                            <?php if (isset($item['children']) AND is_array($item['children']) AND count($item['children']) != 0): ?>
                                <?php Helper::displayTreeMenu($item['children']) ?>
                            <?php endif; ?>
                        <?php } ?>
                    </li>
                <?php } ?>
                <?php
            }
        }
    }
}
?>


