<?php
use common\components\FHtml;
use frontend\assets\CustomAsset;

/* @var $model \frontend\models\ViewModel */
/* @var $this yii\web\View */
/* @var $category \backend\models\Category */

//$asset = CustomAsset::register($this);
//$baseUrl = $asset->baseUrl;

$galleries = $model->getGalleries();
$categories = $model->getObjectCategories();
$categories = \common\components\FHtml::getRelatedModels('product', '1', 'cms_blogs', \common\components\FHtml::RELATION_MANY_MANY);
$category = $model->category;
?>

<link rel="stylesheet" href="<?php echo $baseUrl ?>/frontend/themes/unify/assets/css/style.css">

<!-- News v3 -->
<div class="news-v3 bg-color-white margin-bottom-30">
    <div class="news-v3-in">
        <h2 style="color:<?= $color ?>"><?= FHtml::getFieldValue($model, ['name', 'title']) ?></h2>
        <ul class="list-inline posted-info">
            <li>
                <?= FHtml::showLabel('', $object_type, 'category_id', FHtml::getFieldValue($model, ['category_id'])) ?>
                <?= FHtml::showLabel('', $object_type, 'type', FHtml::getFieldValue($model, ['type'])) ?>
                <?= FHtml::showLabel('', $object_type, 'status', FHtml::getFieldValue($model, ['status'])) ?>
            </li>
        </ul>
        <br/>
        <?= FHtml::showImage($model->image, $image_folder, '100%', 0, 'img-responsive full-width', $model->name, false) ?>

        <p><?= FHtml::getFieldValue($model, ['overview', 'description']) ?></p>
        <p><?= FHtml::getFieldValue($model, ['content']) ?></p>
        <?= FHtml::showQuote(FHtml::getFieldValue($model, 'quote')); ?>
        <hr/>
    </div>
</div>
<!-- End News v3 -->
