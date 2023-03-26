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
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/plugins/image-hover/css/img-hover.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/css/custom.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/css/style.css">

<div class="margin-bottom-30">
    <ul class="list-inline our-clients" id="effect-2">
        <?php
    foreach ($items as $item) {
        $linkurl = empty($item->linkurl) ? FHtml::createUrl($link_url, ['id' => $item->id]) : $item->linkurl;
        ?>
        <li>
            <a href="<?= $linkurl ?>" target="_blank">
            <figure>
                <?= FHtml::showImage($item->image, $image_folder, $image_width, $image_height,  'img-responsive') ?>
                <div class="img-hover">
                    <h4><?= FHtml::showLinkUrl(FHtml::getFieldValue($item, $field_title), $linkurl, $show_viewmore) ?></h4>
                </div>
            </figure>
            </a>
        </li>
       <?php } ?>
    </ul>
</div><!--/container-->

