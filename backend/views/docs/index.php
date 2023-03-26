<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\components\FHtml;

$categories = isset($categories) ? $categories : \backend\models\ObjectCategory::findAllCategories('cms_docs');
$root = FHtml::getRootFolder();
$application_id = FHtml::currentApplicationId();
$files = FHtml::listFiles(["$root/applications/$application_id/docs", "$root/docs"]);
$filesURL = [];
foreach ($files as $file) {
    $info = FHtml::parseUrl($file);
    if (in_array($info['extension'], ['php']))
        continue;
    $filesURL[$info['filename']] = str_replace(FHtml::getRootFolder(), FHtml::getRootUrl(), $file);
}
?>

<style type="text/css">
    ul[class*="child"] {
        margin-left: 20px;
    }

    ul.navHelp {
        list-style-type: none;;
    }
    ul.navHelp li.active {
        background-color: #eee;
    }

    ul.navHelp li {
        list-style-type: none;;

        background-color: white;
        border: 1px solid lightgrey; padding: 10px; margin-bottom: 10px
    }

</style>

<div class="bg-color-light" style="position: relative;">
        <div class="row">
            <div class="col-md-12">
                <h1><span><strong><?= FHtml::t('common', 'Documents') ?></strong></span></h1>
                <div style="padding-top: 30px; margin-left: -30px">
                    <ul class="navHelp" style="display: block">

                        <h3><?= empty($categories) ? '' : FHtml::t('common', 'Help') ?></h3>
                        <?php $i = 0; foreach ($categories as $index => $category): $i += 1; ?>
                            <li>
                                <a href="<?= FHtml::createListUrl('docs', ['category_id' => $category->id, 'name' => str_slug($category->name)])?>"><span><?= $i . '. ' . $category->name ?></span></a>
                            </li>
                        <?php endforeach; ?>
                        <h3><?= empty($filesURL) ? '' : FHtml::t('common', 'Download') ?></h3>
                        <?php foreach ($filesURL as $name => $url): $i += 1; ?>
                            <li>
                                <a rel="external" data-pjax=0 target="_blank" href="<?= $url ?>"><span><?= $i . '. ' . $name ?></span></a>
                            </li>
                        <?php endforeach; ?>

                        <h3>API</h3>
                        <li>
                            <a rel="external" data-pjax=0 target="_blank" href="<?= FHtml::getRootUrl() . "/apps/swagger" ?>"><span>API Document (Swagger)</span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
</div>
