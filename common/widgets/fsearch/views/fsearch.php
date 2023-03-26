<?php
/**
 * Created by PhpStorm.
 * User: Quan
 * Date: 02/08/2017
 * Time: 14:01 CH
 */
use backend\assets\CustomAsset;
use common\components\FHtml;
use backend\modules\ecommerce\models\Product;
use backend\modules\cms\models\CmsBlogs;
use yii\bootstrap\ActiveForm;

$application = FHtml::currentApplicationId();
$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
//$baseUrl .= '/frontend/themes';
$baseUrl .= '/applications/'.$application.'/assets/';
$user = Yii::$app->user->identity;


/* @var $height array */

$category_id = isset($category_id) ? $category_id : FHtml::getRequestParam('category_id');
if(!isset($categories)){
    $search_type = isset($search_type) ? $search_type : 'product';

    if($search_type == 'product') $categories = Product::findAllCategories('product');
    if($search_type == 'blog') $categories = CmsBlogs::findAllCategories('blog');

}
else $categories = Product::findAllCategories('product');

?>

<?php $this->registerJsFile($baseUrl . "js/bootstrap-select.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<link rel="stylesheet" href="<?php echo $baseUrl ?>/css/bootstrap-select.css">

<style>
    .is_active, .nav >li.is_active > a:hover {
        border: 1px solid #337ab7;
        border-radius: 5px;
        background: #337ab7;
    }
</style>

<div class="container">
    <nav class="" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <ul class="nav navbar-nav">
                    <li <?php echo (!empty($category_id)) ? '' : 'class="is_active"'; ?>><a href="<?= FHtml::createUrl('/'.$search_type) ?>"><?= FHtml::t('common', 'All Product') ?></a></li>
                    <?php foreach ($categories as $category): ?>
                        <li <?php echo !($category->id == $category_id) ? '' : 'class="is_active"'; ?>><a class="btn" href="<?= $category->createListUrl(strtolower($category->object_type)) ?>"><?= ucfirst(FHtml::getFieldValue($category, ['name'])) ?></a></li>
                    <?php endforeach ?>
                </ul>
            </div>
            <?php $form = ActiveForm::begin(['id' => 'search-form',
                'options' => [
                    'class' => 'navbar-form navbar-right',
                    'role' => 'search',
                ]]);
            ?>

            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search" id="txtName" name="txtName">
                <div class="input-group-btn">
                    <button type="button" class="btn btn-default" data-href="<?= FHtml::createUrl('site/search') ?>"
                            id="search"><i class="glyphicon glyphicon-search"></i></button>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
        <!-- .container-fluid -->
    </nav>
</div>

<?php FHtml::registerJs("
    $('#txtName').keypress(function(event){
        $.ajax({
            url: $(\"button[id^='search']\").data('href'),
            type: 'post',
            data: {
                'txtName': $(this).val()
            },
            success: function (response) {
                var html = '<div class=\" FProduct FProduct_w2 \"><div><section class=\"blog-page page fix\"><div class=\"row\">';
                $.each(response.data_cus, function (index, value) {

                    html += '<div class=\"col-sm-6 col-md-4\"> <div class=\"single-blog\"> <div class=\"content fix\">';
                    html += '<a class=\"image fix\" href=\"' + value.href + '\"> ' + value.image + '</a>';
                    html += '<h2><a href=\"' + value.href + '\">' + value.name + '</a></h2>';
                    html += '<div class=\"pro-price pull-left\"><a class=\"btn btn-success btn-xs\" href=\"' + value.href + '\">' + value.readmore + '+</a></div>';
                    html += '</div></div></div>';
                });
                html += '</div></div></section></div></div>';
                $('#q').append().html(html);
            }
        });
    });
    $(\"button[id^='search']\").click(function (event) {
        event.preventDefault();
        $.ajax({
            url: $(this).data('href'),
            type: 'post',
            data: {
                'txtName': $(\"#txtName\").val()
            },
            success: function (response) {
                var html = '<div class=\" FProduct FProduct_w2 \"><div><section class=\"blog-page page fix\"><div class=\"row\">';
                $.each(response.data_cus, function (index, value) {

                    html += '<div class=\"col-sm-6 col-md-4\"> <div class=\"single-blog\"> <div class=\"content fix\">';
                    html += '<a class=\"image fix\" href=\"' + value.href + '\"> ' + value.image + '</a>';
                    html += '<h2><a href=\"' + value.href + '\">' + value.name + '</a></h2>';
                    html += '<div class=\"pro-price pull-left\"><a class=\"btn btn-success btn-xs\" href=\"' + value.href + '\">' + value.readmore + '+</a></div>';
                    html += '</div></div></div>';
                });
                html += '</div></div></section></div></div>';
                $('#q').append().html(html);
            }
        });
    });
") ?>