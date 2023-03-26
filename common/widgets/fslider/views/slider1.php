<?php
/**
 * Created by PhpStorm.
 * User: Darkness
 * Date: 9/28/2016
 * Time: 2:58 PM
 *
 * @var $data \backend\models\CmsSlide
 */;
use frontend\components\Helper;

//$asset = CustomAsset::register($this);
//$baseUrl = $asset->baseUrl;
use backend\assets\CustomAsset;
$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$baseUrl .= '/frontend/themes';
$user = Yii::$app->user->identity;
?>

    <link rel="stylesheet"
          href="<?php echo $baseUrl ?>/frontend/themes/common/assets/plugins/master-slider/masterslider/style/masterslider.css">
    <link rel="stylesheet"
          href="<?php echo $baseUrl ?>/frontend/themes/common/assets/plugins/master-slider/masterslider/skins/default/style.css">
    <!--    <link rel="stylesheet" href="--><?php //echo $baseUrl ?><!--/unify-blog/assets/css/blog.style.css">-->
    <!--    <link rel="stylesheet" href="--><?php //echo $baseUrl ?><!--/unify-blog/assets/css/theme-colors/default.css">-->

    <div class="blog-ms-v1 content-sm bg-color-darker margin-bottom-60">

        <div class="master-slider ms-skin-default" id="masterslider">
            <?php foreach ($items as $item) {
                $type = !empty($item->url2_link) ? 'video' : '';
                ?>
                <div class="ms-slide blog-slider">
                    <img src="<?= Helper::getImageUrl($item->image, 'cms-slide') ?>"
                         data-src="<?= Helper::getImageUrl($item->image, 'cms-slide') ?>"
                         alt="lorem ipsum dolor sit"/>
                    <?php if ($type == 'video') { ?>
                        <a href="<?= $item->url2_link ?>" data-type="video"> <?= $item->url2_label ?> </a>
                    <?php } ?>
                    <span class="blog-slider-badge"><?= $item->name ?></span>
                    <div class="ms-info"></div>
                    <div class="blog-slider-title">
                        <h2><a href="<?= $item->url1_link ?>"><?= $item->url1_label ?></a></h2>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

<?php $this->registerJsFile($baseUrl . "/themes/common/assets/plugins/master-slider/masterslider/masterslider.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php $this->registerJsFile($baseUrl . "/themes/common/assets/plugins/master-slider/masterslider/jquery.easing.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php $this->registerJsFile($baseUrl . "/unify-blog/assets/js/plugins/master-slider-showcase1.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>

<?php $this->registerJs("$(document).ready(function () {    
        MasterSliderShowcase1.initMasterSliderShowcase1();
    });"); ?>