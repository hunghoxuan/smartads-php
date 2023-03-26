<?php
/**
 * Created by PhpStorm.
 * User: Quan
 * Date: 22/06/2017
 * Time: 17:21 CH
 */
use backend\assets\CustomAsset;
use common\components\FHtml;

//$asset = CustomAsset::register($this);
//$baseUrl = $asset->baseUrl;
$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$baseUrl .= '/frontend/themes';
$user = Yii::$app->user->identity;

/* @var $item backend\models\Product */
/* @var $items array */
/* @var $alignment string */
/* @var $itemsCount integer */
/* @var $color string */

$item_side = 12 / $columns_count;

?>
<div class="shop-products">
<!-- Easy Block -->
<?php
    if (count($items) != 0) {
    $count = 0;
    foreach ($items as $item) {
    $arr = [];
    $linkurl = FHtml::createUrl($link_url, $arr);
    if (strpos($link_url, '{id}') !== false) {
        $linkurl = str_replace('{id}', $item->id, $linkurl);
    }
    if (strpos($link_url, '{name}') !== false)
        $arr = array_merge($arr, ['name' => $item->name]);
    if (strpos($link_url, '{category_id}') !== false)
        $arr = array_merge($arr, ['category_id' => $item->category_id]);
    $count++;
    $type = FHtml::getFieldValue($item, $field_type);
    $type_color = key_exists($type, $field_type_colors) ? $field_type_colors[$type] : $color;
?>
<!-- Begin Easy Block -->

        <!-- Single Product Start -->
        <div class="col-sm-4 col-md-3 fix">
            <div class="product-item fix">
                <div class="product-img-hover">
                    <!-- Product image -->
                    <a href="<?= FHtml::createUrl('/product-detail', ['id' => $item->id,'category_id' => $item->category_id]) ?>" class="pro-image fix" ><?= FHtml::showImage($item,$image_folder) ?></a>
                    <!-- Product action Btn -->
                    <div class="product-action-btn">
                        <a class="quick-view" href="#"><i class="fa fa-search"></i></a>
                        <a class="favorite" href="#"><i class="fa fa-heart-o"></i></a>
                        <a class="add-cart" href="#"><i class="fa fa-shopping-cart"></i></a>
                    </div>
                </div>
                <div class="pro-name-price-ratting">
                    <!-- Product Name -->
                    <div class="pro-name">
                        <a href="<?= FHtml::createUrl('/product-detail', ['id' => $item->id,'category_id' => $item->category_id]) ?>"><?= FHtml::getFieldValue($item,'name') ?></a>
                    </div>
                    <!-- Product Ratting -->
                    <div class="pro-ratting">
                        <i class="on fa fa-star"></i>
                        <i class="on fa fa-star"></i>
                        <i class="on fa fa-star"></i>
                        <i class="on fa fa-star"></i>
                        <i class="on fa fa-star-half-o"></i>
                    </div>
                    <!-- Product Price -->
                    <div class="pro-price fix">
                        <p><span class="old"><?= FHtml::getFieldValue($item,'cost') ?></span><span class="new">$<?= FHtml::showModelPrice($item,$color,false,$show_price) ?></span></p>
                    </div>
                </div>
            </div>
        </div><!-- Single Product End -->
</div>

<?php

if ($count == $items_count && $items_count > 0)
    break;
}
}
?>

