<?php
use common\components\FHtml;
use frontend\assets\CustomAsset;

$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$baseUrl .= '/frontend/themes';?>

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

    if ($i % 3 == 1) {
        $t = $i;
        echo '<div class="row content-boxes-v3 margin-bottom-40">';
    }

    ?>
    <div class="col-md-4 col-sm-6">
        <i class="icon-custom <?= $icon_size ?> icon-color-<?= $color1 ?> <?= $icon ?>"></i>
        <div class="content-boxes-in-v3">
            <h2 class="heading-sm">
                <span><?= FHtml::getFieldValue($item, $field_title) ?></span>
            </h2>
            <p><?= FHtml::getFieldValue($item, $field_overview) ?></p>
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

