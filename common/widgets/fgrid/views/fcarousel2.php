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

<div class="row" id="home-row-2">
    <div class="col-md-12">
        <div class="carousel slide" id="myCarousel">
            <div class="carousel-inner">
                <?php
                $i = 0;
                foreach ($items as $item) :
                    $linkurl = empty($item->linkurl) ? FHtml::createUrl($link_url, ['id' => $item->id, 'category_id' => $item->category_id]) : $item->linkurl;
                    $i += 1;
                    ?>
                    <div class="item<?php echo $i == count($items) ? ' active' : '' ?>">
                        <div class="col-md-4" style="height:600px; margin-right:-10px">
                            <a href="<?= $linkurl ?>">

                                <div class="tinto" style="height: auto; max-height:300px; width: 100%;">
                                    <?= FHtml::showImage($item, 'cms-blogs', '100%', '', 'img-responsive') ?>
                                </div>
                                <div style="background-color: white; height: 200px;margin-left:10px; margin-right:10px">
                                    <div class="home-p1" style="color:#81C6B6 ; font-weight: bold; font-size:18px">
                                        <?= FHtml::getFieldValue($item, ['name']) ?>
                                    </div>
                                    <div
                                        style="height:110px;overflow:hidden;font-weight: normal; padding-top:10px; padding-bottom:20px"><?= FHtml::getFieldValue($item, ['overview', 'description'], '', 150) ?></div>
                                </div>
                                <p class="home-p4 text-center" style="margin-left:10px; margin-right:10px"><a
                                        href="<?= $linkurl ?>" class="home-a1"><?= FHtml::t('common', 'Read more') ?> <i
                                            class="fa fa-long-arrow-right" aria-hidden="true"></i></a></p>
                            </a>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <a class="left carousel-control" href="#myCarousel" data-slide="prev"><i
                class="glyphicon glyphicon-chevron-left"></i></a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next"><i
                class="glyphicon glyphicon-chevron-right"></i></a>
    </div>
</div>


<?php $this->registerCssFile($baseUrl . "/themes/common/assets/plugins/carouseller/carouseller.css", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php $this->registerJsFile($baseUrl . "/themes/common/assets/plugins/carouseller/carouseller.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php $this->registerJsFile($baseUrl . "/themes/common/assets/plugins/carouseller/home.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>


