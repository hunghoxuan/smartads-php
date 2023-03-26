<?php
use backend\assets\CustomAsset;
use common\components\FHtml;

//$asset = CustomAsset::register($this);
//$baseUrl = $asset->baseUrl;
$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$baseUrl .= '/frontend/themes';
$user = Yii::$app->user->identity;

/* @var $item backend\modules\ecommerce\models\Product */
/* @var $items array */
/* @var $alignment string */
/* @var $itemsCount integer */
/* @var $color string */

$item_side = 12 / $columns_count;

?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/css/custom.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/css/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/css/shop.style.css">


<div class="row high-rated margin-bottom-20">
    <!-- Easy Block -->
    <?php
    if (count($items) != 0) {
    $count = 0;
    foreach ($items as $item) {
    $linkurl = empty($item->linkurl) ? FHtml::createUrl($link_url, ['id' => $item->id, 'category_id' => $item->category_id]) : $item->linkurl;
    $count++;
    $count_gallery = count($item->galleries);
    $i = 0;
    if ($count_gallery != 0) {
        /*Gallery*/
        ?>
        <div class="col-md-<?= $item_side ?> col-sm-6 md-margin-bottom-40">
            <div class="easy-block-v1">
                <div class="easy-block-v1-status rgba-<?= $color ?>"><?= $item->status ?></div>
                <!--div class="easy-block-v1-badge rgba-<!--?= $color ?>"><!?= $item->status ?></div-->
                <div class="easy-block-v1-price rgba-<?= $color ?>"> $ <?= number_format($item->price) ?></div>
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
                <div class="overflow-h">
                    <h3><?= FHtml::showLinkUrl(FHtml::getFieldValue($item, $field_title), $linkurl, $show_viewmore) ?></h3>
                    <?= FHtml::showModelPrice($item, $color, false, $show_price) ?>
                    <?= FHtml::showModelRates($item, 5, '', $color, true, $show_rate) ?>
                </div>
                <ul class="list-unstyled">
                    <?= FHtml::showModelField($item, $field_overview, $label_viewmore, $item_layout) ?>
                </ul>
                <?= FHtml::showViewMoreUrl($linkurl, $show_viewmore) ?>
            </div>
        </div>
        <?php
    } else {
        /*No Gallery*/
        ?>
        <!-- Begin Easy Block -->
        <div class="col-md-<?= $item_side ?> col-sm-6 md-margin-bottom-40">
            <div class="thumbnails thumbnail-style thumbnail-kenburn">
                <div class="thumbnail-img">
                    <div class="overflow-hidden">
                        <?= FHtml::showImage($item->image, $image_folder, $image_width, $image_height, 'img-responsive') ?>
                    </div>

                    <?= FHtml::showViewMoreUrl($linkurl, $show_viewmore, [], $label_viewmore, $color, 'btn-more hover-effect') ?>
                </div>
                <div class="caption">
                    <h3><?= FHtml::showLinkUrl(FHtml::getFieldValue($item, $field_title), $linkurl, $show_viewmore) ?></h3>
                    <span class="product-price" style="color:<?= $color ?>"> <?= FHtml::showModelPrice($item, $color, false, $show_price) ?></span>

                    <p><?= FHtml::showModelField($item, $field_overview, '', $item_layout) ?></p>
                    <?= FHtml::showModelRates($item, 5, '', $color, true, $show_rate) ?>

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



