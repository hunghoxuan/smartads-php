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
$show_viewmore = false;
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/plugins/image-hover/css/img-hover.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/css/style.css">
<link rel="stylesheet" href="<?php echo $baseUrl ?>/unify/assets/css/pages/shortcode_timeline1.css">

<div class="margin-bottom-30">
    <ul class="timeline-v1" id="">
        <?php
        $coin = 1;
    foreach ($items as $item) {
        $coin = 1 - $coin;
        $linkurl = empty($item->linkurl) ? FHtml::createUrl($link_url, ['id' => $item->id]) : $item->linkurl;
        ?>
        <li class="<?= $coin == 0 ? '' : 'timeline-inverted' ?>">
            <div class="timeline-badge primary"><i class="glyphicon glyphicon-record <?= $coin == 0 ? '' : 'inverted' ?>"></i></div>
            <div class="timeline-panel">
                <div class="timeline-heading">
                    <?= FHtml::showImage($item->image, $image_folder, $image_width, $image_height,  'img-responsive', FHtml::getFieldValue($item, $field_title), false) ?>
                </div>
                <div class="timeline-body text-justify">
                    <h2 class="font-light"><?= FHtml::showLinkUrl(FHtml::getFieldValue($item, $field_title), $linkurl, $show_viewmore) ?></h2>
                    <p><?= FHtml::getFieldValue($item, $field_overview) ?></p>
                    <?= FHtml::showLabel('', $object_type, 'category_id', FHtml::getFieldValue($item, 'category_id')) ?>
                    <?= FHtml::showViewMoreUrl($linkurl, $show_viewmore) ?>
                </div>
                <div class="timeline-footer">
                    <ul class="list-unstyled list-inline blog-info">
                        <li><i class="fa fa-clock-o"></i> <?= FHtml::showDate(FHtml::getFieldValue($item, 'created_date')) ?></li>
                        <li><?=  FHtml::showUser(FHtml::getFieldValue($item, 'created_user', ''), '', 'username', 'user/view') ?></li>
                    </ul>

                    <a class="likes" href="#" style="margin-left:10px"><i class="fa fa-comments-o"></i><?= FHtml::getFieldValue($item, 'count_comments', 0)?></a>
                    <a class="likes" href="#" style="margin-left:5px"><i class="fa fa-heart"></i><?= FHtml::getFieldValue($item, 'count_views', 0)?></a>
                </div>
            </div>
        </li>

       <?php } ?>
    </ul>
</div><!--/container-->

