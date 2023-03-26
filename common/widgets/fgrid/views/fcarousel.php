<?php
use backend\assets\CustomAsset;
use common\components\FHtml;

/* @var $this \yii\web\View */

//Get base url
$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$baseUrl .= '/frontend/themes';
$user = Yii::$app->user->identity;
if ($display_type == '1')
{
    $css1 = 'owl-carousel-v1'; $css2 = 'owl-slider';
} else if ($display_type == '2')
{
    $css1 = 'owl-carousel-v2 owl-carousel-style-v1'; $css2 = 'owl-slider-v2';
}
else if ($display_type == '3')
{
    $css1 = 'owl-carousel-style-v2'; $css2 = 'owl-slider-v3';
} else {
    $css1 = 'owl-carousel-v4'; $css2 = 'owl-slider-v4';

}
?>

<link rel="stylesheet" href="<?php echo $baseUrl ?>/unify/assets/plugins/owl-carousel/owl-carousel/owl.carousel.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/css/style.css">

<div class="<?= $css1 ?> margin-bottom-50">

    <div class="<?= $css2 ?>">
        <?php
        foreach ($items as $item) :
            $linkurl = empty($item->linkurl) ? FHtml::createUrl($link_url, ['id' => $item->id]) : $item->linkurl;
            ?>
            <div class="item">
                <?= FHtml::showImage($item->image, $image_folder, $image_width, $image_height, 'img-responsive', '', TRUE, '', $linkurl ) ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if (in_array($display_type, ['1', '2', '3'])) { ?>
    <div class="owl-navigation">
        <div class="customNavigation">
            <a class="owl-btn prev-v1"><i class="fa fa-angle-left"></i></a>
            <a class="owl-btn next-v1"><i class="fa fa-angle-right"></i></a>
        </div>
    </div>
    <?php } ?>
</div>

<?php $this->registerJsFile($baseUrl . "/unify/assets/plugins/owl-carousel/owl-carousel/owl.carousel.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php $this->registerJsFile($baseUrl . "/unify/assets/js/plugins/owl-carousel.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>


<?php $this->registerJs("$(document).ready(function () {
        OwlCarousel.initOwlCarousel();
    });"); ?>



