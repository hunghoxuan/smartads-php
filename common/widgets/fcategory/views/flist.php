<?php
use backend\assets\CustomAsset;
use common\components\FHtml;

/* @var $this \yii\web\View */

//Get base url
$asset = CustomAsset::register($this);
$user = Yii::$app->user->identity;
$show_border = isset($show_border) ? $show_border : false;
$border = $show_border ? 'product-description-brd' : '';
$item_style = isset($item_style) ? $item_style : '';
$field = isset($field) ? $field : 'category_id';
$active_id = isset($active_id) ? $active_id : FHtml::getRequestParam($field);
$isIndexed = \yii\helpers\ArrayHelper::isIndexed($items, true);
?>

<?php

?>
<ul class="" role="tablist" style=" list-style-type: none; padding-left:0px">
    <?php foreach ($items as $key => $category):
        if ($isIndexed && is_numeric($key)) {
            $key = $category;
        }
        $is_active = $key == $active_id;
        ?>
        <li style="text-transform: uppercase; width: 100%; padding: 10px; margin-bottom: 1px; "  class="<?= $is_active ? 'nav-active' : 'nav-normal'; ?>"><a href="<?=FHtml::createUrl(FHtml::currentUrl([$field => $key]), [$field => $key]) ?>"><?= strtolower((empty($category) || (empty($key) && is_string($key))) ? FHtml::t('common', 'All') : FHtml::t('common', $category)) ?></a></li>
    <?php endforeach ?>
</ul>
