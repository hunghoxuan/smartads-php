<?php
use backend\assets\CustomAsset;
use common\components\FHtml;

//$asset = CustomAsset::register($this);
//$baseUrl = $asset->baseUrl;
$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$baseUrl .= '/frontend/themes';
$user = Yii::$app->user->identity;

/* @var $item backend\models\Product */
/* @var $items array */
/* @var $alignment string */
/* @var $itemsCount integer */
/* @var $color string */

$item_side = 12 / $columns_count;

?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/css/custom.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/css/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/css/shop.style.css">

<div class="row high-rated">
    <!-- Easy Block -->
    <?php
    if (count($items) != 0) {
    $count = 0;
    foreach ($items as $item) {
    $arr = [];
    $linkurl = FHtml::makeModelLinkUrl($item, $link_url, ['id', 'name', 'category_id']);
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
                    <?= FHtml::showModelField($item, $field_overview, '', $item_layout) ?>
                </ul>
                <?= FHtml::showViewMoreUrl($linkurl, $show_viewmore) ?>
            </div>
        </div>
        <?php
    } else {
        /*No Gallery*/
        $type = FHtml::getFieldValue($item, $field_type);
        $type_color = key_exists($type, $field_type_colors) ? $field_type_colors[$type] : $color;
        ?>
        <!-- Begin Easy Block -->

        <div class="col-md-<?= $item_side ?> col-sm-6" style="padding-right: 20px">
            <div class="tinto" style="height: auto; max-height: 300px; margin-bottom: 20px">
                <a href="<?= $linkurl ?>">
                    <?= FHtml::showImage($item, $image_folder, $image_width, $image_height, 'layer1 img img-responsive') ?>

                    <div class="nenxam"></div>
                    <div class="gradient"></div>
                    <div class="hinhvuong"></div>
                    <div class="chuto"><h3>
                            #<?= FHtml::showLinkUrl(FHtml::getFieldValue($item, $field_title), $linkurl, true) ?></h3>
                    </div>
                    <div class="chunho"> <?= FHtml::showModelField($item, $field_overview, '', $item_layout) ?></div>
                </a>
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
<div class="row high-rated">
    <?php
    }
    if ($count == $items_count && $items_count > 0)
        break;
    }
    }
    ?>
    <!-- End Easy Block -->
</div>



