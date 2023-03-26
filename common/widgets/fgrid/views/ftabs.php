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
$border = $show_border ? '' : '';
$show_viewmore = false;
$uid = uniqid();
$show_viewmore = true;
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/plugins/image-hover/css/img-hover.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/css/custom.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/css/style.css">

<div class="tab-v1" id="">
    <ul class="nav nav-tabs">
    <?php
    $i = 0;
    foreach ($items as $item) {
        $i += 1;    $css = $i == 1 ? 'active' : '';
        ?>
        <li class="<?= $css ?>"><a href="#<?= $uid . $i ?>" data-toggle="tab"><?= FHtml::getFieldValue($item, $field_title) ?></a></li>
    <?php }?>
    </ul>
    <div class="tab-content">
    <?php
    $i = 0;
foreach ($items as $item) {
    $i += 1;
    $css = $i == 1 ? 'active' : '';
    $linkurl = empty($item->linkurl) ? FHtml::createUrl($link_url, ['id' => $item->id]) : $item->linkurl;
    ?>
        <div class="tab-pane fade in <?= $css ?>" id="<?= $uid . $i ?>">
            <div class="row">
                <?php if (empty($item->image)) { ?>
                    <div class="col-md-12">
                        <p><?= FHtml::getFieldValue($item, $field_overview) ?></p>
                        <div class="clear-both margin-bottom-20"></div>
                        <?= FHtml::showViewMoreUrl($linkurl, $show_viewmore) ?>
                    </div>
                <?php } else { ?>
                    <div class="col-md-4">
                        <?= FHtml::showImage($item->image, $image_folder, $image_width, $image_height, 'img-responsive') ?>
                    </div>
                    <div class="col-md-8">
                        <p><?= FHtml::getFieldValue($item, $field_overview) ?></p>
                        <div class="clear-both margin-bottom-20"></div>
                        <?= FHtml::showViewMoreUrl($linkurl, $show_viewmore) ?>
                    </div>
                <?php }?>
            </div>
        </div>
   <?php } ?>
    </div>
</div>

