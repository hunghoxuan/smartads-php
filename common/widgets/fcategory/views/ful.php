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
$link_url = isset($link_url) ? $link_url : FHtml::currentUrl();
$border = $show_border ? 'product-description-brd' : '';
$item_style = isset($item_style) ? $item_style : '';

?>

<ul class="list-unstyled checkbox-list" style="margin:0px">
    <?php
    $i = 0;
    foreach ($items as $category) {

        if (empty($category->name))
            continue;
        $linkurl = empty($category->linkurl) ? FHtml::createUrl($link_url, ['category_id' => $category->id]) : $category->linkurl;
        $css = FHtml::getRequestParam('category_id') == $category->id ? 'font-weight:bold;' : ''; $css .= $item_style;
        ?>
        <li style="<?= $css ?>">
            <a href="<?= $linkurl ?>"><?php echo $category->name ?>
                </a>
        </li>
    <?php } ?>
</ul>
