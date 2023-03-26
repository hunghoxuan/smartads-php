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
?>

<div class="panel-group" id="accordion">
    <div class="panel panel-<?= $color ?>">
        <div class="panel-heading">
            <h2 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                    <?= $title ?>
                    <i class="fa fa-angle-down pull-right" style="margin-top:8px"></i>
                </a>
            </h2>
        </div>
        <div id="collapseOne" class="panel-collapse collapse in">
            <div class="panel-body">
                <ul class="list-unstyled checkbox-list">
                    <?php
                    foreach ($items as $category) {
                        $linkurl = empty($category->linkurl) ? FHtml::createUrl($link_url, ['category_id' => $category->id]) : $category->linkurl;
                        $css = FHtml::getRequestParam('category_id') == $category->id ? 'font-weight:bold' : '';
                        ?>
                        <li style="<?= $css ?>">
                            <a href="<?= $linkurl ?>"><?php echo $category->name ?>
                                &nbsp;(<?= count($category->products) ?>)</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>
