<?php
use backend\assets\CustomAsset;
use common\components\FHtml;

/* @var $this \yii\web\View */

//Get base url
//$asset = CustomAsset::register($this);
//$baseUrl = $asset->baseUrl;
$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$baseUrl .= '/frontend/themes';
$user = Yii::$app->user->identity;
?>


<section id="photostack-1" class="photostack photostack-start">
    <div>
        <?php
        $i = 1;
        foreach ($items as $item) :
            $linkurl = empty($item->linkurl) ? FHtml::createUrl($link_url, ['id' => $item->id]) : $item->linkurl;
            $i += 1;
        ?>
        <figure>
            <a href="<?= $linkurl ?>" class="photostack-img">
                <?= FHtml::showImage($item->image, $image_folder) ?>
            </a>
            <figcaption>
                <h2 class="photostack-title"><?= FHtml::getFieldValue($item, $field_title) ?></h2>
            </figcaption>
        </figure>
        <?php endforeach; ?>
    </div>
</section>



