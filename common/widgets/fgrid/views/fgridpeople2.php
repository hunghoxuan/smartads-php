<?php
use backend\assets\CustomAsset;
use common\components\FHtml;

/* @var $this \yii\web\View */

//Get base url
//$asset = CustomAsset::register($this);
//$baseUrl = $asset->baseUrl;
$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$baseUrl .= '/frontend/themes';
$user = Yii::$app->user->identity;
$item_side = 12 / $columns_count;

//var_dump($items);die;
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/css/custom.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/css/style.css">

<div class="row margin-bottom-20">
    <!-- Easy Block -->
    <?php
    if (count($items) != 0) {
    $count = 0;
    foreach ($items as $item) {
        $linkurl = empty($item->linkurl) ? FHtml::createUrl($link_url, ['id' => $item->id, 'category_id' => FHtml::getFieldValue($item, 'category_id')]) : $item->linkurl;
        $count++;
        $count_gallery = count($item->galleries);
        $i = 0;
    if ($count_gallery != 0) {
        /*Gallery*/
        ?>
        <div class="col-md-<?= $item_side ?> col-sm-6 ">
            <div class="team-v2">
                <div id="carousel-example-generic<?= $count ?>" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <?php

                        foreach ($item->galleries as $gallery) { ?>
                            <li class="rounded-x<?= ($i == 0) ? ' active' : '' ?>"
                                data-target="#carousel-example-generic<?= $count ?>"
                                data-slide-to="<?= $i ?>"></li>
                            <?php
                            $i++;
                        }
                        ?>
                    </ol>
                    <div class="carousel-inner">
                        <?php
                        $j = 0;
                        foreach ($item->galleries as $gallery) {
                            $gallery = $gallery->toViewModel();
                            $css = 'item' . ($j == 0) ? ' active' : '';
                            ?>
                            <div class="item<?= ($j == 0) ? ' active' : '' ?>">
                                <?= FHtml::showImage($gallery->name, $image_folder, $image_width, $image_height, 'img-responsive') ?>
                            </div>
                            <?php
                            $j++;
                        }
                        ?>
                    </div>
                </div>
                <div class="inner-team">
                    <h3><?= FHtml::showLinkUrl(FHtml::getFieldValue($item, $field_title), $linkurl, $show_viewmore) ?></h3>
                    <small class="color-green"><?= FHtml::getFieldValue($item, ['position', 'status', 'type'], '') ?></small>
                    <p><?= FHtml::showModelField($item, $field_overview , '', $item_layout) ?></p>
                    <hr>
                    <?= FHtml::showSocialLinks($item, ['fb','tw','gp','facebook','twitter','google','linkedin'])?>
                    <?= FHtml::showViewMoreUrl($linkurl, $show_viewmore) ?>
                </div>
            </div>
        </div>
        <?php
    }else{
        /*No Gallery*/
        ?>
        <!-- Begin Easy Block -->
        <div class="col-md-<?= $item_side ?> col-sm-6">
            <div class="team-v2">
                <?= FHtml::showImage($item->image, $image_folder, $image_width, $image_height,  'img-responsive') ?>
                <div class="inner-team">
                    <h3><?= FHtml::showLinkUrl(FHtml::getFieldValue($item, $field_title), $linkurl, $show_viewmore) ?></h3>
                    <small class="color-<?= FHtml::getFieldValue($item, 'color', 'green')?>"><?= FHtml::getFieldValue($item, ['positon', 'status', 'type'], '') ?></small>
                    <p><?= FHtml::showModelField($item, $field_overview , '', $item_layout) ?></p>
                    <hr>
                    <?= FHtml::showSocialLinks($item, ['fb','tw','gp','facebook','twitter','google','linkedin'])?>
                    <?= FHtml::showViewMoreUrl($linkurl, $show_viewmore) ?>
                </div>
            </div>
        </div>
        <!-- End Begin Easy Block -->
        <?php
    }
    if ($count % $columns_count == 0) {
    // $itemsCount number of items per row
    // space between rows
    ?>
</div>
<div class="row clear-fix margin-bottom-20"></div>
<div class="row high-rated margin-bottom-20">
    <?php
    }
    if ($count == $items_count && $items_count > 0)
        break;
    }
    }
    ?>
    <!-- End Easy Block -->
</div>



