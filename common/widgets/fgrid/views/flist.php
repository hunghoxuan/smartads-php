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
$border = $show_border ? 'product-description-brd' : '';
?>
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/plugins/image-hover/css/img-hover.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/css/custom.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/css/style.css">

<div class="row">
    <?php
foreach ($items as $item) {
    $linkurl = empty($item->linkurl) ? FHtml::createUrl($link_url, ['id' => $item->id]) : $item->linkurl;
    ?>
    <div class="list-product-description <?= $border ?> margin-bottom-30"  style="border-bottom:1px dashed lightgray">
        <div class="row">
            <div class="col-sm-4" style="padding-left:20px">
                <a href="<?= $linkurl ?>" alt="<?= FHtml::getFieldValue($item, $field_title) ?>">
                    <?= FHtml::showImage($item->image, $image_folder, $image_width, $image_height, 'img-responsive') ?>
                </a>
            </div>
            <div class="col-sm-8 product-description" style="padding-left:20px">
                <div class="overflow-h margin-bottom-5">
                    <ul class="list-inline overflow-h">
                        <li><h3 class="title-price">
                                <b><?= FHtml::showLinkUrl(FHtml::getFieldValue($item, $field_title), $linkurl, $show_viewmore) ?></b>
                            </h3>
                        </li>
                    </ul>
                    <div class="margin-bottom-20">
                        <span style="color: #db002b; font-size: 18px"><?= FHtml::showCurrency($item->price, FHtml::getCurrency($item)) ?></span>
                    </div>
                    <p class="margin-bottom-20">
                        <?= FHtml::showModelField($item, $field_overview , '', $item_layout) ?>
                        <br/>
                        <?= FHtml::showViewMoreUrl($linkurl, $show_viewmore) ?>
                    </p>

                </div>
            </div>
        </div>
    </div>
   <?php } ?>
</div>

