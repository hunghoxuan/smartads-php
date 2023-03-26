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
$uid = uniqid();
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/plugins/image-hover/css/img-hover.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/css/custom.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/css/style.css">

<div class="panel-group acc-v1" id="accordion-<?=$uid?>">
    <?php
foreach ($items as $item) {
    $uid1 = uniqid();
    $linkurl = empty($item->linkurl) ? FHtml::createUrl($link_url, ['id' => $item->id]) : $item->linkurl;
    ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-<?=$uid?>" href="#collapse-One<?=$uid1?>">
                    <?= FHtml::getFieldValue($item, $field_title) ?>
                </a>
            </h2>
        </div>
        <div id="collapse-One<?=$uid1?>" class="panel-collapse collapse in">
            <div class="panel-body">
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
        </div>
    </div>
   <?php } ?>
</div>

