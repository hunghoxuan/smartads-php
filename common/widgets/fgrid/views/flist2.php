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
$css = '';
$show_viewmore = false;
$tag_title = 'h2';
$link_url = '';
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/plugins/animate.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/css/style.css">

<div class="row">
    <?php
    $coin = 0;
    foreach ($items as $item) {
        $linkurl = empty($item->linkurl) ? FHtml::createUrl($link_url, ['id' => $item->id]) : $item->linkurl;
        ?>
        <div class="row margin-bottom-20">
            <?php if (empty($item->image)) { ?>
                <div class="col-md-12">
                    <h2><?= FHtml::showLinkUrl(FHtml::getFieldValue($item, $field_title), $linkurl, $show_viewmore) ?></h2>
                    <p><?= FHtml::getFieldValue($item, $field_overview) ?></p>
                    <div class="clear-both margin-bottom-20"></div>
                    <?= FHtml::showViewMoreUrl($linkurl, $show_viewmore) ?>
                </div>
            <?php } else if ($coin == 0) {
                $coin = 1 - $coin;
                $css = FHtml::generateRandomInArray(FHtml::CSS_ANIMATED_ARRAYS);
                ?>
                <div class="col-md-4">
                    <?= FHtml::showImage($item->image, $image_folder, $image_width, $image_height, 'img-responsive wow ' . $css) ?>
                </div>
                <div class="col-md-8">
                    <h2><?= FHtml::showLinkUrl(FHtml::getFieldValue($item, $field_title), $linkurl, $show_viewmore) ?></h2>
                    <p><?= FHtml::getFieldValue($item, $field_overview) ?></p>
                    <div class="clear-both margin-bottom-20"></div>
                    <?= FHtml::showViewMoreUrl($linkurl, $show_viewmore) ?>
                </div>
            <?php } else if ($coin == 1) {
                $coin = 1 - $coin;
                $css = FHtml::generateRandomInArray(FHtml::CSS_ANIMATED_ARRAYS);

                ?>
                <div class="col-md-8">
                    <h2><?= FHtml::showLinkUrl(FHtml::getFieldValue($item, $field_title), $linkurl, $show_viewmore) ?></h2>
                    <p><?= FHtml::getFieldValue($item, $field_overview) ?></p>
                    <div class="clear-both margin-bottom-20"></div>
                    <?= FHtml::showViewMoreUrl($linkurl, $show_viewmore) ?>
                </div>
                <div class="col-md-4">
                    <?= FHtml::showImage($item->image, $image_folder, $image_width, $image_height, 'img-responsive wow ' . $css) ?>
                </div>
            <?php }?>
         </div>
        <div class="row margin-bottom-20"><hr/></div>
    <?php } ?>
</div>

<?php $this->registerJsFile($baseUrl . "/unify/assets/plugins/wow-animations/js/wow.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>

<?php $this->registerJs("$(document).ready(function () {
        new WOW().init();
    });"); ?>
