<?php
use backend\assets\CustomAsset;
use common\components\FHtml;

//$asset = CustomAsset::register($this);
//$baseUrl = $asset->baseUrl;
$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$baseUrl .= '/frontend';
$user = Yii::$app->user->identity;

/* @var $item backend\modules\ecommerce\models\Product */
/* @var $items array */
/* @var $alignment string */
/* @var $itemsCount integer */
/* @var $color string */


$item_side = 12 / $columns_count;
$image_width = '500px';
$image_height = '350px !important';
$show_viewmore = true;
?>
<!-- Easy Block -->
<?php
if (count($items) != 0) {
    $count = 0;
    foreach ($items as $item) {
        $arr = [];
        $linkurl = FHtml::createUrl($link_url, $arr); //empty($item->linkurl) ? FHtml::createUrl($link_url, $arr) : $item->linkurl;

        if (strpos($link_url, '{id}') !== false) {
            $linkurl = str_replace('{id}', $item->id, $linkurl);
            //$arr = array_merge($arr, ['id' => $item->id]);
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


        <!-- Blog Post Excerpt -->
        <div class="col-sm-6">
            <div class="blog-post blog-single-post">
                <div class="single-post-title">
                    <h3><?= FHtml::showModelField($item, $field_title, '', $item_layout) ?></h3>
                </div>

                <div class="single-post-image">
                    <?= FHtml::showImage($item, $image_folder, $image_width, $image_height, 'layer1 img img-responsive') ?>
                </div>
                <div class="single-post-content" style="border-bottom: none">
                    <div style="height: 100px;overflow: hidden;margin-bottom: 20px">
                        <p>
                            <?= FHtml::showModelField($item, $field_overview, '', $item_layout) ?>
                        </p>
                    </div>
                    <?= FHtml::showViewMoreUrl($linkurl, $show_viewmore) ?>

                </div>
            </div>
        </div>
        <!-- End Blog Post Excerpt -->

        <?php
        if ($count == $items_count && $items_count > 0)
            break;
    }
}
?>
<!-- End Easy Block -->