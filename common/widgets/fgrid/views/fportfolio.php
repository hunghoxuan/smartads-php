<?php
/**
 * Created by PhpStorm.
 * User: tongd
 * Date: 2017-07-27
 * Time: 15:46
 */
use backend\assets\CustomAsset;
use common\components\FHtml;

//$asset = CustomAsset::register($this);
//$baseUrl = $asset->baseUrl;
$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$user = Yii::$app->user->identity;

/* @var $items array */
/* @var $alignment string */
/* @var $itemsCount integer */
/* @var $color string */

?>
<section id="portfolio" class="bg-light-gray">
    <div class="container">
        <div class="row">
            <?php
            if (count($items) != 0) :
                $i = 0;
                foreach ($items as $item):
                    /*Gallery*/
                    ?>

                    <div class="col-md-4 col-sm-6 portfolio-item">
                        <a href="#portfolioModal<?php echo $i ?>" class="portfolio-link" data-toggle="modal">
                            <div class="portfolio-hover">
                                <div class="portfolio-hover-content">
                                    <i class="fa fa-plus fa-3x"></i>
                                </div>
                            </div>
                            <?= FHtml::showImage(FHtml::getFieldValue($item, ['thumbnail', 'image']), FHtml::getImageFolder($item), '', '', 'img-centered', strip_tags(FHtml::getFieldValue($item, 'name'))); ?>
                        </a>
                        <div class="portfolio-caption">
                            <h4 style="font-weight: bold"><?= FHtml::getFieldValue($item, 'name') ?></h4>
                            <p class="text-muted"><?= FHtml::getFieldValue($item, 'overview') ?></p>
                        </div>
                    </div>

                    <?php
                $i++;
                endforeach;
            endif;
            ?>
        </div>
    </div>
</section>
<!-- Portfolio Modals -->

<?php
if (count($items) != 0) :
    $i = 0;
    foreach ($items as $item):
        /*Gallery*/
        ?>
        <!-- Portfolio Modal 1 -->
        <div class="portfolio-modal modal fade" id="portfolioModal<?php echo $i ?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="close-modal" data-dismiss="modal">
                        <div class="lr">
                            <div class="rl">
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-8 col-lg-offset-2">
                                <div class="modal-body">
                                    <!-- Project Details Go Here -->
                                    <h2><?= FHtml::getFieldValue($item, 'name') ?></h2>
                                    <p class="item-intro text-muted"><?= FHtml::getFieldValue($item, 'overview') ?></p>
                                    <?= FHtml::showImage(FHtml::getFieldValue($item, ['thumbnail', 'image']), FHtml::getImageFolder($item), '', '', 'img-centered', strip_tags(FHtml::getFieldValue($item, 'name'))); ?>
                                    <div class="text-center" style="margin-bottom: 30px">
                                        <?= strip_tags(FHtml::getFieldValue($item, 'content')) ?>
                                    </div>
                                    <a href="<?= FHtml::createUrl('contact') ?>" class="btn btn-primary" ><i
                                                class="fa fa-check"></i> <?= FHtml::t('common','I need similar App like this') ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- jQuery -->

        <?php
        $i++;
    endforeach;
endif;
?>


