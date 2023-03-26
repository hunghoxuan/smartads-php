<?php
/**
 * Created by PhpStorm.
 * User: Darkness
 * Date: 9/28/2016
 * Time: 2:58 PM
 *
 * @var $data \backend\models\CmsSlide
 */
use backend\assets\CustomAsset;
use frontend\components\Helper;
use common\components\FHtml;

//$asset = CustomAsset::register($this);
//$baseUrl = $asset->baseUrl;
$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$baseUrl .= '/frontend/themes';
$user = Yii::$app->user->identity;
?>


<link rel="stylesheet" href="<?php echo $baseUrl ?>/unify-blog/assets/plugins/master-slider/masterslider/style/masterslider.css">
<link rel="stylesheet" href="<?php echo $baseUrl ?>/unify-blog/assets/plugins/master-slider/masterslider/skins/default/style.css">
<!--    <link rel="stylesheet" href="--><?php //echo $baseUrl ?><!--/unify-blog/assets/css/blog.style.css">-->
<!--    <link rel="stylesheet" href="--><?php //echo $baseUrl ?><!--/unify-blog/assets/css/theme-colors/default.css">-->

<div class="blog-ms-v1 content-sm bg-color-darker margin-bottom-60">

    <div class="master-slider ms-skin-default" id="masterslider">
        <?php foreach($items as $item) {
            $linkurl = empty($item->linkurl) ? FHtml::createUrl($link_url, ['id' => $item->id, 'category_id' => $item->category_id]) : $item->linkurl;
            $type = !empty($item->linkurl) ? 'video' : '';
            ?>
        <div class="ms-slide blog-slider">
            <?= FHtml::showImage($item->image, $image_folder, $image_width, $image_height, 'img-responsive wow ') ?>
            <?php if($type == 'video') { ?>
            <a href="<?= $item->linkurl ?>" data-type="video"> <?= FHtml::getFieldValue($item, $field_title) ?> </a>
            <?php } ?>
            <span class="blog-slider-badge"><?= FHtml::getFieldValue($item, $field_overview) ?></span>
            <div class="ms-info"></div>
            <div class="blog-slider-title">
                <h2><a href="<?= $linkurl ?>"><?= FHtml::getFieldValue($item, $field_title) ?></a></h2>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<?php $this->registerJsFile($baseUrl . "/unify-blog/assets/plugins/master-slider/masterslider/masterslider.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php $this->registerJsFile($baseUrl . "/unify-blog/assets/plugins/master-slider/masterslider/jquery.easing.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php $this->registerJsFile($baseUrl . "/unify-blog/assets/js/plugins/master-slider-showcase1.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>

<?php $this->registerJs("$(document).ready(function () {    
        MasterSliderShowcase1.initMasterSliderShowcase1();
    });"); ?>