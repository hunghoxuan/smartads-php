<?php
/**
 * Created by PhpStorm.
 * User: tongd
 * Date: 2017-07-11
 * Time: 08:35
 */
use backend\assets\CustomAsset;
use common\components\FHtml;
use backend\modules\cms\models\CmsBlogs;
/* @var $this \yii\web\View */

//Get base url
$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$baseUrl .= '/frontend/themes';
$user = Yii::$app->user->identity;
$category_id = Yii::$app->request->get('category_id');
?>
<style>
    .services-cate {
        margin-bottom: 70px;
    }
    ul {
        margin: 0;
        padding: 0;
    }
    .services-cate .services-cate-item {
        font-size: 16px;
        -webkit-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
        border: none;
        float: none;
    }
    li {
        list-style: none;
        display: list-item;
        text-align: -webkit-match-parent;
    }
    a:active, a:hover {
        outline: 0;
        font-weight: 700;
    }
    /*a, */
    .services-cate-item a:hover, a:active, a:focus {
        text-decoration: none;
        font-weight: 700;

    }
    a:focus {
        outline: 5px auto -webkit-focus-ring-color;
        outline-offset: -2px;
    }
    /*.services-cate .services-cate-item.active a, */
    .services-cate .services-cate-item.active a, .services-cate .services-cate-item a:hover, .services-cate .services-cate-item a:focus {
        color: #337AB7 ;
        border: none ;
        border-left: 5px solid #337AB7 ;
        background: #fff ;
        font-weight: 700;

    }
    .services-cate .services-cate-item a {
        line-height: 50px;
        display: block;
        padding: 0 24px;
        border: none;
        border-left: 5px solid #e4e4e4;
        color: #333;
        -webkit-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
    }
    .quanimage{
        padding-right: 50px;
    }
    @media only screen and(max-width: 480px){
        .quanimage{
            padding-right: 0;
        }
    }
</style>

<div class="service-sidebar"  >
    <ul class="services-cate">
        <li  class="services-cate-item text-uppercase  <?= empty($category_id) ? 'active' : '' ?>"><a href="<?= \common\components\FFrontend::createListUrl($object_type) ?>"><?= FHtml::t('common', 'All') ?></a></li>
        <?php foreach ($items as $category){ $active = ($category->id == $category_id)  ? 'active' :''; ?>
            <li  class="services-cate-item text-uppercase  <?= $active ?>"><a href="<?=$category->createListUrl($object_type) ?>"><?= ucfirst(strtolower($category->getFieldValue(['name', 'title']))) ?></a></li>
        <?php } ?>
    </ul>
</div>