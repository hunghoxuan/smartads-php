<?php
use backend\assets\CustomAsset;
use common\components\FHtml;

/* @var $this \yii\web\View */

//Get base url
$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$baseUrl .= '/frontend/themes';
$user = Yii::$app->user->identity;
$border = $show_border ? 'product-description-brd' : '';
$item_style = isset($item_style) ? $item_style : '';
$category_id = isset($category_id) ? $category_id : FHtml::getRequestParam('category_id');

?>

<ul class="tabs-list" role="tablist">
    <?php foreach ($items as $category): ?>
        <li <?php echo !($category->id == $category_id) ?: 'class="active"'; ?>><a class="btn" href="<?=$category->createListUrl($object_type) ?>"><?= strtolower($category->getFieldValue(['name', 'title'])) ?></a></li>
    <?php endforeach ?>
</ul>
<style>
    .tabs-list > li {
        display: inline-block;
        border: 3px solid lightgray;
        border-radius: 15px;
        margin-left: 5px;
        margin-right: 5px;
    }
</style>
