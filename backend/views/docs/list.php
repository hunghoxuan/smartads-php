<?php
/**
 * Created by PhpStorm.
 * User: LongPC
 * Date: 12/25/2018
 * Time: 10:59
 */

use common\components\FHtml;
use frontend\assets\CustomAsset;

$asset      = CustomAsset::register($this);
$baseUrl    = $asset->baseUrl;
$main_color = FHtml::currentApplicationMainColor();

/** @var \backend\modules\cms\models\Category $category */
$category_id = isset($category_id) ? $category_id : FHtml::getRequestParam('category_id', 0);
$id = isset($id) ? $id : FHtml::getRequestParam(['slug', 'id']);
$anchorLink  = [];

$models = isset($models) ? $models : \backend\modules\cms\models\CmsDocs::findAll(['category_id' => $category_id]);

?>
<style type="text/css">
    .item {
        padding: 10px;
    }
    .active {
        background-color: #eee;
        border-left: 3px solid lightgrey;
    }
    .post {
        padding-bottom: 20px;
    }

</style>
<?php \yii\widgets\Pjax::begin(['id' => 'text']); ?>
<div class="bg-color-light" style="position: relative; background-color: white; clear:both">
    <div class="col-md-12" style="background-color: #f8f9fa; border: 1px solid lightgrey; margin-top: 30px; padding-bottom: 20px; padding-top:20px">

        <?php if (isset($category)): ?>
            <div class="col-md-8">
                <h2><span><strong><?= $category->name ?></strong></span></h2>
                <span><?= $category->description ?></span>
            </div>
            <div class="col-md-4 pull-right hidden-print" style="padding-top: 20px">
                <a style="" class="pull-right btn btn-default" href="<?= FHtml::createUrl('docs') ?>"><i class="fa fa-arrow-circle-left"></i>&nbsp;<?= FHtml::t('common', 'back') ?>
                </a> &nbsp;
                <?php if (!empty($models)) { ?>
                <a style="margin-bottom: 10px" target="_blank" data-ajax=0 class="pull-right btn btn-primary" href="<?= FHtml::createUrl('docs/download', ['category_id' => $category_id]) ?>"><i class="fa fa-file-pdf-o "></i>&nbsp;<?= FHtml::t('common', 'Download') ?>
                </a>
                <?php } ?>
            </div>

        <?php endif; ?>
    </div>

    <?php if (!empty($models)): ?>

    <div class="col-md-3 menu-doc hidden-print" style="clear:both; background-color: #f8f9fa; padding-top:20px; border-left: 1px solid lightgrey; border-right: 1px solid lightgrey;overflow-y: auto;position: sticky;top: 0;height: calc(100vh - 0);">
        <ul class="nav parent">
            <li style="width: 100%;" class="<?= empty($id) ? 'active' : '' ?>"><a href="<?= FHtml::createUrl('docs', ['category_id' => $category_id, 'name' => str_slug($category->name)]) ?>"><?= FHtml::t('common', 'All') ?></a></li>
        </ul>
        <?= $menus ?>
    </div>
    <div class="col-md-9 docs" style="background-color: white; padding-top: 20px; border: 1px solid lightgrey;">
        <?php
        $first_level = true;

        foreach ($models as $index => $model) : ?>
            <?php
                $level = FHtml::getFieldValue($model, ['tree_level', 'level']);
                if ($level == 0) {
                    if (!$first_level)
                     echo "<hr/>";
                    $first_level = false;
                }

                if ($level == 0) {
                    $tag = "h1";
                    $padding = '0';
                }
                else if ($level == 1) {
                    $tag = "h3";
                    $padding = '30';
                }
                else if ($level == 2) {
                    $tag = "h4";
                    $padding = '60';
                }
                else {
                    $tag = "h4";
                    $padding = '90';
                }
                $padding = '0';
                $level += 1;
            ?>
            <div class="post">
                <<?= $tag ?> style="padding-left: <?= $padding ?>px ; text-transform: uppercase" id="<?= str_slug($model->name) ?>"><?= FHtml::getFieldValue($model, ['tree_index', 'index']) ?>. <?= $model->name ?></<?= $tag ?>>
                <br/>
                <p><?= $model->overview ?></p>
                <p><?= $model->content ?></p>
            </div>
        <?php endforeach; ?>
    </div>
    <!--
    <div class="col-md-2 hidden-print" style="background-color: #f8f9fa; padding-top: 20px; border-left: 1px solid aquamarine;    position: sticky;border-left: 1px solid lightgrey;overflow-y: auto;top: 0;height: calc(100vh - 0);">
        <?php
    $anchorLink = [];
    foreach ($anchorLink as $link): ?>
            <a href="#<?= str_slug($link) ?>" class="anchor-link"><small><?= $link ?></small></a>
        <?php endforeach; ?>
    </div>
    -->
    <?php else: ?>
        <div class="clearfix"></div>
        <div class="alert alert-warning" style="margin-top: 20px">
            <span><strong><?= FHtml::showEmptyMessage(false) ?></strong></span>
        </div>
    <?php endif; ?>
</div>

<?php
$script = <<<JS

    // $(window).on('load', function() {
    //     $('.menu-doc').find('ul').find('li').each(function(index, item) {
    //         $(item).find('a').on('click', function(evt) {
    //             // evt.preventDefault();
    //         });
    //         if(window.location.protocol + "//" + window.location.hostname + $(item).find('a').attr('href') === window.location.href) {
    //             $(item).addClass('active');    
    //         }
    //        
    //         $(item).on('click', function(evt) {
    //             $(this).next('ul').toggleClass('hide');
    //         });            
    //     });
    // });
    // $('.anchor-link').click(function(evt) {
    //     evt.preventDefault();
    //     let top = $($(this).attr('href')).offset().top;
    //     if (window.pageYOffset === 0) {
    //         top = top - 102;
    //     } 
    //     $('html, body').animate({scrollTop : top - 102}, 'slow');
    // });
    // $('.docs').scroll(function(evt) {
    //     console.log(this);
    // });
JS;
$this->registerJS($script, \yii\web\View::POS_END);
?>
<?php \yii\widgets\Pjax::end() ?>
