<?php
use common\components\FHtml;
use backend\assets\CustomAsset;
//$baseUrl = \common\components\FHtml::getBaseUrl($this);
$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$baseUrl .= '/frontend/themes';
if (count($items) >= 4)
    $size = 3;
else if (count($items) == 3)
    $size = 4;
else if (count($items) == 2)
    $size = 6;
else
    $size = 3;

?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/css/style.css">

<?php
$i = 1;
foreach ($items as $item) {

    if ($i % 4 == 1) {
        $t = $i;
        echo '<ul class="row list-row margin-bottom-40">';
    }

    ?>
    <li class="col-md-<?= $size?> col-sm-6 col-xs-12 md-margin-bottom-30">
        <div class="counters rounded">
            <span
                class="counter"><?= \common\components\FHtml::getFieldValue($item, ['value', 'quantity', 'total', 'total_count']) ?></span>
            <h4><?= FHtml::getFieldValue($item, $field_title) ?></h4>
        </div>
    </li>
    <?php
    if ($i == $t + 4 || ($i >= count($items)) || ($i >= $items_count && $items_count > 0)) {
        echo '</ul>';
    }
    if (($i >= count($items)) || ($i == $items_count && $items_count > 0))
        break;
    $i = $i + 1;
} ?>
