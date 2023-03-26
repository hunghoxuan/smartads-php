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

<link rel="stylesheet" type="text/css"
      href="<?php echo $baseUrl ?>/unify/assets/plugins/image-hover/css/img-hover.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/css/custom.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/css/style.css">

<div class="row text-center">
    <?php
    $i = 0;
    $count = count($items);
    foreach ($items as $item) {
        $i = $i + 1;
        $linkurl = empty($item->linkurl) ? FHtml::createUrl($link_url, ['id' => $item->id]) : $item->linkurl;
        $target = empty($item->linkurl) ? '' : '_blank';
        $endItem = $i < $count ? ' | ' : '';
        ?>
        <span style="font-size:20px; color:lightgrey"><?= FHtml::showLinkUrl(FHtml::getFieldValue($item, $field_title), $linkurl, $show_viewmore, [], $target) . $endItem ?>  </span>
    <?php } ?>
</div>
