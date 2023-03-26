<?php
use backend\assets\CustomAsset;
use common\components\FHtml;

/* @var $this \yii\web\View */

//Get base url
$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$baseUrl .= '/frontend/themes';
$user = Yii::$app->user->identity;
$show_border = isset($show_border) ? $show_border : false;

$border = $show_border ? 'product-description-brd' : '';

?>

<ul class="list-inline tags-v2 margin-bottom-50" style="margin:0px">
    <?php
    foreach ($items as $category) {
        $linkurl = empty($category->linkurl) ? FHtml::createUrl($link_url, ['category_id' => $category->id]) : $category->linkurl;
        $css = FHtml::getRequestParam('category_id') == $category->id ? 'font-weight:bold; background-color:lightgrey' : '';
        ?>
        <li>
            <a href="<?= $linkurl ?>" style="<?= $css ?>"><?php echo $category->name ?>
                &nbsp;(<?= count($category->products) ?>)</a>
        </li>
    <?php } ?>
</ul>
