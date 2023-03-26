<?php
use backend\assets\CustomAsset;
use common\components\FHtml;

//$asset = CustomAsset::register($this);
//$baseUrl = $asset->baseUrl;
$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$baseUrl .= '/frontend/themes';
$user = Yii::$app->user->identity;
?>

    <link rel="stylesheet" type="text/css"
          href="<?php echo $baseUrl ?>/common/assets/plugins/revolution-slider/rs-plugin/css/settings.css">

    <div class="tp-banner-container">
        <div class="tp-banner">
            <ul>
                <?php foreach ($items as $item) {
                    ?>
                    <!-- SLIDE -->
                    <li class="revolution-mch-1"
                        data-transition="<?= FHtml::getValue($item->transition_type, 'random') ?>" data-slotamount="5"
                        data-masterspeed="<?= FHtml::getValue($item->transition_speed, FHtml::ARRAY_TRANSITION_SPEED) ?>"
                        data-title="<?= FHtml::getFieldValue($item, $field_title) ?>">
                        <!-- MAIN IMAGE -->
                        <img
                            src="<?php echo FHtml::getImageUrl($item->image, 'cms-slide') ?>"
                            alt="darkblurbg" data-bgfit="cover" data-bgposition="left top" data-bgrepeat="no-repeat">

                        <div class="tp-caption revolution-ch1 sft start"
                             data-x="<?= $item->alignment ?>"
                             data-hoffset="0"
                             data-y="100"
                             data-speed="<?= FHtml::getValue($item->transition_speed, FHtml::ARRAY_TRANSITION_SPEED) ?>"
                             data-start="500"
                             data-easing="Back.easeInOut"
                             data-endeasing="Power1.easeIn"
                             data-endspeed="300">
                            <?= FHtml::getFieldValue($item, $field_title) ?>
                        </div>

                        <!-- LAYER -->
                        <div class="tp-caption revolution-ch2 sft"
                             data-x="<?= $item->alignment ?>"
                             data-hoffset="0"
                             data-y="190"
                             data-speed="<?= FHtml::getValue($item->transition_speed, FHtml::ARRAY_TRANSITION_SPEED) ?>"
                             data-start="2000"
                             data-easing="Power4.easeOut"
                             data-endspeed="300"
                             data-endeasing="Power1.easeIn"
                             data-captionhidden="off"
                             style="z-index: 6">
                            <?= FHtml::getFieldValue($item, $field_overview) ?>
                        </div>
                        <?php if (!empty($item->url1_link) || !empty($item->url2_link) || !empty($item->url2_link)) { ?>
                        <!-- LAYER -->
                        <div class="tp-caption sft"
                             data-x="<?= $item->alignment ?>"
                             data-hoffset="0"
                             data-y="310"
                             data-speed="<?= FHtml::getValue($item->transition_speed, FHtml::ARRAY_TRANSITION_SPEED) ?>"
                             data-start="2800"
                             data-easing="Power4.easeOut"
                             data-endspeed="300"
                             data-endeasing="Power1.easeIn"
                             data-captionhidden="off"
                             style="z-index: 6">
                            <?php
                            if (!empty($item->url1_link)) { ?>}
                                <a href="<?= $item->url1_link ?>"
                                   class="btn-u btn-brd btn-brd-hover btn-u-light"><?= empty($item->url1_label) ? 'View more' : $item->url1_label ?></a>
                            <?php } ?>
                            <?php
                            if (!empty($item->url2_link)) { ?>}
                                <a href="<?= $item->url2_link ?>"
                                   class="btn-u btn-brd btn-brd-hover btn-u-light"><?= empty($item->url2_label) ? 'View more' : $item->url2_label ?></a>
                            <?php } ?>
                            <?php
                            if (!empty($item->url3_link)) { ?>}
                                <a href="<?= $item->url3_link ?>"
                                   class="btn-u btn-brd btn-brd-hover btn-u-light"><?= empty($item->url3_label) ? 'View more' : $item->url3_label ?></a>
                            <?php } ?>
                        </div>
                        <?php } ?>
                    </li>
                    <!-- END SLIDE -->
                <?php } ?>

            </ul>
            <div class="tp-bannertimer tp-bottom"></div>
        </div>
    </div>

<?php

?>
<?php $this->registerJsFile($baseUrl . "/common/assets/plugins/revolution-slider/rs-plugin/js/jquery.themepunch.tools.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php $this->registerJsFile($baseUrl . "/common/assets/plugins/revolution-slider/rs-plugin/js/jquery.themepunch.revolution.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php $this->registerJsFile($baseUrl . "/common/assets/plugins/fancybox/source/jquery.fancybox.pack.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php $this->registerJsFile($baseUrl . "/common/assets/plugins/revolution-slider.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>

<?php $this->registerJs("$(document).ready(function () {
        RevolutionSlider.initRSfullWidth();
            });"); ?>