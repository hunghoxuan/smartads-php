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

<div id="carousel-example-generic-v5" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <li class="active rounded-x" data-target="#carousel-example-generic-v5" data-slide-to="0"></li>
        <li class="rounded-x" data-target="#carousel-example-generic-v5" data-slide-to="1"></li>
        <li class="rounded-x" data-target="#carousel-example-generic-v5" data-slide-to="2"></li>
    </ol>

    <div class="carousel-inner">
        <?php
        $i = 1;
        foreach ($items as $item) :
            $i += 1;
            ?>
            <div class="item <?php echo $i == count($items) ? 'active' : '' ?>">
                <p><?= FHtml::getFieldValue($item, ['content', 'overview']) ?></p>
                <div style="">
                    <h2 style="color:<?=$color ?>"><?= FHtml::getFieldValue($item, ['name']) ?><p>
                    <span style="font-size:70%; color:lightgrey"><?= FHtml::getFieldValue($item, ['description']) ?></span>
                </div>

            </div>
        <?php endforeach; ?>
    </div>

    <div class="carousel-arrow">
        <a class="left carousel-control" href="#carousel-example-generic-v5" data-slide="prev">
            <i class="fa fa-angle-left"></i>
        </a>
        <a class="right carousel-control" href="#carousel-example-generic-v5" data-slide="next">
            <i class="fa fa-angle-right"></i>
        </a>
    </div>
</div>



