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

?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/plugins/image-hover/css/img-hover.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/css/custom.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/css/style.css">

<div class="container">
<ul class="list-unstyled blog-trending margin-bottom-50">
    <?php
foreach ($items as $item) {
    $linkurl = empty($item->linkurl) ? FHtml::createUrl($link_url, ['id' => $item->id]) : $item->linkurl;
    ?>
        <li>
            <h3><?= FHtml::showLinkUrl(FHtml::getFieldValue($item, $field_title), $linkurl, $show_viewmore) ?></h3>
            <small><?= FHtml::showLabel('', $object_type, 'category_id', FHtml::getFieldValue($item, 'category_id')) ?></small>
        </li>
   <?php } ?>
</ul>
</div>
