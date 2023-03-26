<?php
use common\components\FHtml;
use frontend\assets\CustomAsset;

$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$baseUrl .= '/frontend/themes';
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl ?>/unify/assets/css/style.css">

<?php
$i = 1;

foreach ($items as $item) {
    $icon = !empty($item->icon) ? $item->icon : 'random';
    if ($icon == 'random')
        $icon = FHtml::generateRandomInArray(FHtml::ICON_ARRAYS);

    $color1 = !empty($item->color) ? $item->color : 'random';
    if ($color1 == 'random')
        $color1 = FHtml::generateRandomInArray(FHtml::COLORS_BACKGROUND_ARRAYS);

    $t = 0;
    if ($i % 3 == 1) {
        $t = $i;
        echo '<div class="row margin-bottom-40">';
    }

    ?>
    <div class="col-md-4 col-sm-6">
        <div class="service">
            <i class="service-icon <?= $icon ?> color-<?= $color1 ?>"></i>
            <div class="desc">
                <h4 class="heading-md"><?= FHtml::getFieldValue($item, $field_title) ?></h4>
                <p><?= FHtml::getFieldValue($item, $field_overview) ?></p>
            </div>
        </div>
    </div>
    <?php
    if ($i == $t + 3 || ($i >= count($items)) || ($i >= $items_count && $items_count > 0)) {
        echo '</div>';
    }
    if (($i >= count($items)) || ($i == $items_count && $items_count > 0))
        break;
    $i = $i + 1;
} ?>

