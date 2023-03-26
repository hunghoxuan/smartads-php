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
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/css/pages/blog_masonry_3col.css">

<div class="blog_masonry_3col">
    <div class="grid-boxes">
        <?php
    foreach ($items as $item) {
        $coin = rand(0, 3);
        $linkurl = empty($item->linkurl) ? FHtml::createUrl($link_url, ['id' => $item->id]) : $item->linkurl;
        if ($coin == 0 || empty($item->image)) {
        ?>
            <div class="grid-boxes-in grid-boxes-qoute">
                <div class="grid-boxes-caption grid-boxes-quote <?= FHtml::generateRandomInArray(['bg-color-sea', 'bg-color-blue', '']) ?>">
                    <p><?= FHtml::getFieldValue($item, $field_overview) ?></p>
                    <span>- <?= FHtml::showLinkUrl(FHtml::getFieldValue($item, $field_title), $linkurl, $show_viewmore) ?> -</span>
                </div>
            </div>
        <?php } else { ?>
            <div class="grid-boxes-in">
                <?= FHtml::showImage($item->image, $image_folder, $image_width, $image_height,  'img-responsive', FHtml::getFieldValue($item, $field_title), false) ?>
                <div class="grid-boxes-caption">
                    <h3><b><?= FHtml::showLinkUrl(FHtml::getFieldValue($item, $field_title), $linkurl, $show_viewmore) ?></b></h3>
                    <p><?= FHtml::getFieldValue($item, $field_overview) ?></p>
                    <ul class="list-inline grid-boxes-news">
                        <li><i class="fa fa-clock-o"></i> <?= FHtml::showDate(FHtml::getFieldValue($item, 'created_date', date('Y-m-d'))) ?></li>
                        <li><?=  FHtml::showUser(FHtml::getFieldValue($item, 'created_user', ''), '', 'username', 'user/view') ?></li>
                        <li class="pull-right"><a href="#"><i class="fa fa-comments-o"></i> <?= FHtml::getFieldValue($item, 'count_views', 0)?></a></li>
                    </ul>
                </div>
            </div>
        <?php }
        } ?>
    </div>
</div>


<?php $this->registerJsFile($baseUrl . "/unify/assets/plugins/masonry/jquery.masonry.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php $this->registerJsFile($baseUrl . "/unify/assets/js/app.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php $this->registerJsFile($baseUrl . "/unify/assets/js/pages/blog-masonry.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>


<?php $this->registerJs("$(document).ready(function () {
        App.init();
    });"); ?>



