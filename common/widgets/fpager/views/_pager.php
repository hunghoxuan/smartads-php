<?php
/**
 * Created by PhpStorm.
 * User: Darkness
 * Date: 9/6/2016
 * Time: 4:43 PM
 */
use common\components\FHtml;
use common\widgets\Helper;
use frontend\assets\CustomAsset;

/* @var $product \backend\modules\ecommerce\models\Products */
/* @var $this yii\web\View */
/* @var $products array */
/* @var $page integer */
/* @var $category_id integer */
/* @var $title string */
/* @var $keyword string */
/* @var $total int */
/* @var $start_index int */

//$asset = CustomAsset::register($this);
//$baseUrl = $asset->baseUrl;
$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$baseUrl .= '/frontend/themes';
//$total_this_page = count($products);
$last_page = ceil($total / FHtml::getCurrentPageSize());
if (empty($category_id))
    $category_id = FHtml::getRequestParam('category_id');
if (empty($page))
    $page = FHtml::getRequestParam('page');
$redirect_array = array('/course/list');

?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/education/assets/css/custom.shop.style.css">


<!--=== Content Part ===-->

<div class="text-center">
    <ul class="pagination pagination-v2">

        <?php if ($page <= $last_page && $total_this_page > 0) { ?>

            <?php if ($page > 2 && $last_page >= 3) {

                $redirect_array_first = $redirect_array;
                $redirect_array_first['page'] = 1;
                if ($category_id)
                    $redirect_array_first['category_id'] = $category_id;
                if ($keyword)
                    $redirect_array_first['keyword'] = $keyword;
                ?>
                <li><a href="<?= FHtml::createUrl($redirect_array_first) ?>"><i
                            class="fa fa-angle-double-left"></i></a></li>

            <?php } ?>

            <?php if ($page >= 2 && $last_page >= 2) {

                $redirect_array_prev = $redirect_array;
                $redirect_array_prev['page'] = $page - 1;
                if ($category_id)
                    $redirect_array_prev['category_id'] = $category_id;
                if ($keyword)
                    $redirect_array_prev['keyword'] = $keyword;

                ?>

                <li>
                    <a href="<?= FHtml::createUrl($redirect_array_prev) ?>"><?= $page - 1 ?></a>
                </li>

            <?php } ?>

            <?php if ($last_page > 1) { ?>

                <li class="active"><a><?= $page ?></a></li>

            <?php } ?>

            <?php if ($last_page > $page) {

                $redirect_array_next = $redirect_array;
                $redirect_array_next['page'] = $page + 1;
                if ($category_id)
                    $redirect_array_next['category_id'] = $category_id;
                if ($keyword)
                    $redirect_array_next['keyword'] = $keyword;

                ?>

                <li>
                    <a href="<?= FHtml::createUrl($redirect_array_next) ?>"><?= $page + 1 ?></a>
                </li>

            <?php } ?>

            <?php if ($last_page - $page > 1) {

                $redirect_array_last = $redirect_array;
                $redirect_array_last['page'] = $last_page;
                if ($category_id)
                    $redirect_array_last['category_id'] = $category_id;
                if ($keyword)
                    $redirect_array_last['keyword'] = $keyword;
                ?>

                <li><a href="<?= FHtml::createUrl($redirect_array_last) ?>"><i
                            class="fa fa-angle-double-right"></i></a></li>

            <?php } ?>

        <?php } else { ?>
            <h4><?= FHtml::t('common', 'No items') ?></h4>
        <?php } ?>

    </ul>
</div><!--/end pagination-->
