<?php
use backend\assets\CustomAsset;
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use common\components\PageToolbar;
use common\components\FHtml;
use common\components\FConfig;

/* @var $content string */
/* @var $this \yii\web\View */
/* @var $user \common\models\User */

//Get base url
$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$user = Yii::$app->user->identity;
$layout = FHtml::getRequestParam('layout');
$view = FHtml::getRequestParam('view');
$export = FHtml::getRequestParam('export');
$print = FHtml::getRequestParam('print');
\common\components\CrudAsset::register($this);

if (Yii::$app->request->isAjax || !empty($export) || !empty($print))
    $layout = 'no';

if (!empty($print) || $view == 'print') {
    FHtml::registerReadyJs("window.print();");
}

if ($layout == 'no') {
    $this->beginPage();
    echo FHtml::render('head', ['user' => $user]);
    $this->beginBody();
    echo $content;
    echo FHtml::render('footer', ['layout' => $layout, 'user' => $user]);
    $this->endBody();
    $this->endPage();
    die;
}


?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--[if IE 8]>
<html lang="<?= Yii::$app->language ?>" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="<?= Yii::$app->language ?>" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="<?= Yii::$app->language ?>">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head class="hidden-print">
    <?= FHtml::render('head.php') ?>
    <?= Html::csrfMetaTags() ?>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="<?= FConfig::settingBackendBodyStyle() ?>" onunload="">

<?php $this->beginBody() ?>
<div id="loader"></div>

<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top hidden-print">
    <?php
    echo FHtml::render('menu_top', ['user' => $user]);
    ?>
</div>
<!-- END HEADER -->
<!-- BEGIN HEADER & CONTENT DIVIDER -->
<div class="clearfix"></div>
<!-- END HEADER & CONTENT DIVIDER -->
<!-- BEGIN CONTAINER -->
<div class="page-container">
    <?php if (!empty($this->params['left_menu'])) { ?>
        <!-- BEGIN SIDEBAR -->
        <div class="page-sidebar-wrapper hidden-print">
            <!-- BEGIN SIDEBAR -->
            <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
            <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
            <?php
                echo FHtml::render('menu', ['user' => $user]);
            ?>
            <!-- END SIDEBAR -->
        </div>
    <?php } ?>

    <!-- END SIDEBAR -->
    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <!-- BEGIN CONTENT BODY -->
        <div class="page-content" id="page-content">
            <?php
            if ($this->params['displayPageContentHeader'] == true) { ?>
                <!-- BEGIN PAGE HEADER-->
                <!-- BEGIN THEME PANEL -->
                <!-- END THEME PANEL -->
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    'options' => ['class' => 'breadcrumb hidden-print']
                ]) ?>
                <div class="pt-toolbar">
                    <?php if (isset($this->params['toolBarActions'])): ?>
                        <?= PageToolbar::widget(['toolBarActions' => isset($this->params['toolBarActions']) ? $this->params['toolBarActions'] : []]); ?>
                    <?php endif ?>
                </div>

                <!-- END PAGE HEADER-->
            <?php } ?>
            <!-- BEGIN PAGE CONTENT INNER -->
            <?= $content ?>

            <!-- END PAGE CONTENT INNER -->
        </div>
        <img src="<?= FHtml::getImageUrl('loading.gif', 'www') ?>" id="loading" alt="loading" style="display:none;vertical-align: middle" />

        <!-- END PAGE CONTENT BODY -->
        <!-- END CONTENT BODY -->
    </div>
    <!-- END CONTENT -->
</div>
<!-- END CONTAINER -->


<!-- BEGIN FOOTER -->
<div class="page-footer hidden-print">
    <div class=""> <?= FHtml::settingBottomText() ?></div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
</div>
<!-- END FOOTER -->


<!-- BEGIN MODAL -->

<div id="ajaxCrubModal" class="fade modal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header form-label">
                <div class="kv-zoom-actions pull-right">
                    <button type="button" class="btn btn-sm btn-kv btn-default btn-outline-secondary btn-close" title="Close detailed preview" data-dismiss="modal" aria-hidden="true">&times; </button>
                </div>
            </div>
            <div class="modal-body">
                <div id='modalAjax-body'></div>
            </div>
            <div class="modal-footer" style="padding:5px;">
            </div>
        </div>
    </div>
</div>
<div id="modalIframe" class="modal fade in" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header form-label">
                <div class="kv-zoom-actions pull-right">
                    <!--
                    <button type="button" class="btn btn-sm btn-kv btn-default btn-outline-secondary btn-toggleheader" title="Toggle header" data-toggle="button" aria-pressed="false" autocomplete="off"><i class="glyphicon glyphicon-resize-vertical"></i></button>
                    <button type="button" class="btn btn-sm btn-kv btn-default btn-outline-secondary btn-fullscreen" title="Toggle full screen" data-toggle="button" aria-pressed="false" autocomplete="off"><i class="glyphicon glyphicon-fullscreen"></i></button>
                    -->
                    <button type="button" class="btn btn-sm btn-kv btn-default btn-outline-secondary btn-close" title="Close detailed preview" data-dismiss="modal" aria-hidden="true">&times; </button>
                </div>
            </div>
            <div class="modal-body" style="padding:5px;">
                <iframe id="modalIframe-body" frameborder="0" style="" width="100%" height="680px" scrolling="yes" src=""></iframe>
            </div>
        </div>
    </div>
</div>
<!-- END MODAL -->

<script>
    var GlobalsAssetsPath = '<?php echo Yii::getAlias('@web') . '/themes/metronic/assets/' ?>';
</script>

<?= FHtml::render('footer.php', ['layout' => $layout]) ?>
<?php $this->endBody() ?>

</body>
<!-- END BODY -->
</html>
<?php

$time_taken = round(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"],  5);

if  ($layout != 'no')
echo '<div style="text-align: center; font-size: 80%; color: grey">Page generated in '.$time_taken.' seconds. </div>';

$this->endPage() ?>

