<?php
use backend\assets\CustomAsset;
use common\components\FHtml;

//$asset = CustomAsset::register($this);
//$baseUrl = $asset->baseUrl;
$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$baseUrl .= '/frontend/themes';
$user = Yii::$app->user->identity;
$main_color = isset($main_color) ? $main_color : FHtml::settingWebsiteMainColor();

/* @var $items array */
/* @var $alignment string */
/* @var $itemsCount integer */
/* @var $color string */

$columns_count = isset($columns_count) ? $columns_count : 2;
$column_size = round(12 / $columns_count);
$image_height = isset($image_height) ? $image_height : '100px';
$image_width = isset($image_width) ? $image_width : '100%';
?>
<section class="blog-page page fix">
    <div class="row">    <!-- Easy Block -->
        <?php
        if (count($items) != 0) {
        $count = 0;
        foreach ($items as $item) {
        $linkurl = empty($item->linkurl) ? FHtml::createUrl($link_url, ['id' => $item->id, 'category_id' => $item->category_id , 'name' => FHtml::getFieldValue($item, ['name', 'title'])]) : $item->linkurl;
        $count++;
        /*Gallery*/
        ?>

        <div class="col-sm-12 col-md-<?= $column_size ?>" style="margin-bottom: 50px">
            <div class="single-blog" style="padding:2px">
                <div class="content fix">
                    <a class="image fix" href="<?= $linkurl ?>">

                        <?= FHtml::showImage(FHtml::getFieldValue($item, ['thumbnail', 'image']), FHtml::getImageFolder($item), $image_width, $image_height, '', strip_tags(FHtml::getFieldValue($item, 'overview'))); ?>
                        <?php
                        if ($item->is_hot == 1) {
                            echo "<div class='date'><h3 style='color: #fff !important;    
                                                                   font-size: 20px;
                                                                   line-height: 2;
                                                                   text-align: center;'>New</h3></div>";
                        }
                        ?>
                    </a>
                    <div style="padding: 20px">
                        <h2 class="">
                            <a href="<?= $linkurl ?>"><?= FHtml::getFieldValue($item, 'name') ?></a>
                        </h2>
                        <div class="pro-price">
                            <a class="readmore"
                               href="<?= $linkurl ?>"><?= FHtml::t('common', 'Read more') ?> +</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php

        if ($count % $columns_count == 0) {
            echo '<div style="clear:both"></div>';

        ?>
    </div>
</section>
<div class="row high-rated">
    <?php
    }
    if ($count == $items_count && $items_count > 0)
        break;
    }
    }
    ?>
    <!-- End Easy Block -->
</div>



