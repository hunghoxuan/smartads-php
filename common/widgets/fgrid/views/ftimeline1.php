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
$show_viewmore = true;
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/plugins/image-hover/css/img-hover.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/css/style.css">
<link rel="stylesheet" href="<?php echo $baseUrl ?>/unify/assets/css/pages/shortcode_timeline2.css">

<div class="margin-bottom-30">
    <ul class="timeline-v2" id="">
        <?php
    foreach ($items as $item) {
        $linkurl = empty($item->linkurl) ? FHtml::createUrl($link_url, ['id' => $item->id]) : $item->linkurl;
        ?>
        <li class="equal-height-columns">
            <div class="cbp_tmtime equal-height-column">
                <span><?=  FHtml::showUser(FHtml::getFieldValue($item, 'created_user', ''), '', 'username', 'user/view') ?></span>
                <span><?= FHtml::showDate(FHtml::getFieldValue($item, 'created_date')) ?></span>
            </div>
            <i class="cbp_tmicon rounded-x hidden-xs"></i>
            <div class="cbp_tmlabel equal-height-column">
                <h2><?= FHtml::getFieldValue($item, $field_title) ?></h2>
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
                        <p></p><?= FHtml::getFieldValue($item, $field_overview) ?></p>
                        <div class="clear-both margin-bottom-20"></div>
                        <?= FHtml::showViewMoreUrl($linkurl, $show_viewmore) ?>
                    </div>
                <?php }?>
                </div>
            </div>
        </li>

       <?php } ?>
    </ul>
</div><!--/container-->

