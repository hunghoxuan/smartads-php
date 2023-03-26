<?php
use backend\assets\CustomAsset;
//$baseUrl = \common\components\FHtml::getBaseUrl($this);
$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$baseUrl .= '/frontend/themes';
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/css/style.css">

<div class="quote-v1 parallaxBg">
    <div class="container">
        <?= $title ?>
        <span><?= $overview ?></span>
    </div>
</div>