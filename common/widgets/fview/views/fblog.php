<?php
use common\components\FHtml;
use frontend\assets\CustomAsset;

/* @var $model \frontend\models\ViewModel */
/* @var $this yii\web\View */
/* @var $category \backend\models\Category */

//$asset = CustomAsset::register($this);
//$baseUrl = $asset->baseUrl;
$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$baseUrl .= '/frontend/themes';
$galleries = $model->getGalleries();
$categories = $model->getObjectCategories();
$categories = \common\components\FHtml::getRelatedModels('product', '1', 'cms_blogs', \common\components\FHtml::RELATION_MANY_MANY);
$category = $model->category;
?>

<link rel="stylesheet" href="<?php echo $baseUrl ?>/unify/assets/css/style.css">

<!-- News v3 -->
<div class="news-v3 bg-color-white margin-bottom-30">
    <div class="news-v3-in imgtest_<?=$model->id;?>">
        <h2 style="color:<?= $color ?>"><?= FHtml::getFieldValue($model, ['name', 'title']) ?></h2>
        <div>
            <?= FHtml::showLabel('', $object_type, 'category_id', FHtml::getFieldValue($model, ['category_id'])) ?>
            <?= FHtml::showLabel('', $object_type, 'type', FHtml::getFieldValue($model, ['type'])) ?>
            <?= FHtml::showLabel('', $object_type, 'status', FHtml::getFieldValue($model, ['status'])) ?>
        </div>
        <br/>
        <?= FHtml::showImage($model->image, $image_folder, '100%', 0, 'img-responsive full-width', $model->name, false) ?>
        <br/>
        <p><?= FHtml::getFieldValue($model, ['overview', 'description']) ?></p>
        <p><?= FHtml::getFieldValue($model, ['content']) ?></p>
        <?= FHtml::showQuote(FHtml::getFieldValue($model, 'quote')); ?>
        <hr/>
        <div class="row">
            <div class="col-md-6">
                <ul class="post-shares post-shares-lg" style="margin-top:0px !important;">
                    <li>
                        <a href="#">
                            <i class="rounded-x icon-speech"></i>
                            <span><?= FHtml::getFieldValue($model, 'count_comments', rand(0, 200)) ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="rounded-x icon-share"></i>
                            <span><?= FHtml::getFieldValue($model, 'count_rates', rand(0, 1000)) ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="rounded-x icon-heart"></i>
                            <span><?= FHtml::getFieldValue($model, 'count_likes', rand(0, 1000)) ?></span>
                        </a>
                    </li>
                </ul>

            </div>
            <div class="col-md-6">
                <ul class="list-inline posted-info pull-right">
                    <li>
                        <?= FHtml::showDate(FHtml::getFieldValue($model, 'created_date')) ?>
                        <span class="text-default"><?= FHtml::showUser(FHtml::getFieldValue($model, 'created_user', ''), '', 'username', 'user/view') ?></span>
                    </li>

                </ul>
            </div>
        </div>

    </div>
</div>
<!-- End News v3 -->
