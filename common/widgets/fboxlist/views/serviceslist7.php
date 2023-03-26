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
$half = round((count($items) / 2));

foreach ($items as $item) {
    $icon = !empty($item->icon) ? $item->icon : 'random';
    if ($icon == 'random')
        $icon = FHtml::generateRandomInArray(FHtml::ICON_ARRAYS);

    $color1 = !empty($item->color) ? $item->color : 'random';
    if ($color1 == 'random')
        $color1 = FHtml::generateRandomInArray(FHtml::COLORS_BACKGROUND_ARRAYS);

    if ($i == 1) {
        $t = $i;
        echo '<div class="col-sm-6 content-boxes-v3 content-boxes-v3-right sm-margin-bottom-30">';
    }

    ?>
    <div class="clearfix margin-bottom-30">
        <i class="icon-custom  <?= $icon_size ?> icon-color-<?= $color1 ?> <?= $icon ?>"></i>
        <div class="content-boxes-in-v3">
            <h2 class="heading-sm"><?= FHtml::getFieldValue($item, $field_title) ?></h2>
            <p><?= FHtml::getFieldValue($item, $field_overview) ?></p>
        </div>
    </div>

    <?php
    if ($i == $half) {
        echo '</div><div class="col-sm-6 content-boxes-v3 sm-margin-bottom-30">';
    }
    if (($i >= count($items)) || ($i == $items_count && $items_count > 0)) {
        echo('</div>');
        break;
    }
    $i = $i + 1;
} ?>

