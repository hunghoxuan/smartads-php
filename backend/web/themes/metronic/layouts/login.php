<?php
use backend\assets\AppAsset;
use common\components\FHtml;
use yii\helpers\Html;

$asset = AppAsset::register($this);
$baseUrl = $asset->baseUrl;
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <!--
    Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.4
    Version: 3.3.0
    Author: KeenThemes
    Website: http://www.keenthemes.com/
    Contact: support@keenthemes.com
    Follow: www.twitter.com/keenthemes
    Like: www.facebook.com/keenthemes
    Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
    License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
    -->
    <!--[if IE 8]>
    <html lang="en" class="ie8 no-js"> <![endif]-->
    <!--[if IE 9]>
    <html lang="en" class="ie9 no-js"> <![endif]-->
    <!--[if !IE]><!-->
    <html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8"/>
        <title><?= FHtml::settingCompanyName() ?> - <?= Html::encode($this->title) ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport"/>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <meta content="" name="description"/>
        <meta content="" name="author"/>


        <!-- BEGIN GLOBAL MANDATORY STYLES -->

        <link href="<?php echo($baseUrl) ?>/themes/metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css"
              rel="stylesheet" type="text/css"/>
        <link
            href="<?php echo($baseUrl) ?>/themes/metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css"
            rel="stylesheet" type="text/css"/>
        <link
            href="<?php echo($baseUrl) ?>/themes/metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css"
            rel="stylesheet" type="text/css"/>
        <link href="<?php echo($baseUrl) ?>/themes/metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css"
              rel="stylesheet" type="text/css"/>

        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="<?php echo($baseUrl) ?>/themes/metronic/assets//global/plugins/select2/css/select2.min.css"
              rel="stylesheet" type="text/css" id="style_color"/>
        <link href="<?php echo($baseUrl) ?>/themes/metronic/assets/global/plugins/select2/css/select2-bootstrap.min.css"
              rel="stylesheet" type="text/css"/>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="<?php echo($baseUrl) ?>/themes/metronic/assets/global/css/components-rounded.min.css"
              id="style_components" rel="stylesheet" type="text/css"/>
        <link href="<?php echo($baseUrl) ?>/themes/metronic/assets/global/css/plugins.css" rel="stylesheet"
              type="text/css"/>
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="<?php echo($baseUrl) ?>/themes/metronic/assets/pages/css/login.min.css" rel="stylesheet"
              type="text/css"/>
        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="<?php echo $baseUrl . '/' . UPLOAD_DIR . '/' . WWW_DIR . '/favicon.ico' ?>">
        <link href="<?php echo($baseUrl) ?>/themes/metronic/assets/custom/css/login-custom.css" rel="stylesheet"
              type="text/css"/>
        <style>
            .login {
                   height:100%;
                   background-repeat: no-repeat;
                   background-position: center center;
                   background-size: cover;
                   background-image: url('<?= FHtml::getAdminLoginBackgroudUrl() ?>');
               }

            .login .content h3 {
                color: black !important;
            }
        </style>
        <?php $this->head() ?>
    </head>
    <!-- END HEAD -->
    <!-- BEGIN BODY -->
    <body class="login" style="overflow-x: hidden;">
    <?php $this->beginBody() ?>
        <!-- END LOGO -->
    <!-- BEGIN LOGIN -->
    <?php echo $content; ?>
    <!-- END LOGIN -->
    <!-- BEGIN COPYRIGHT -->

    <!-- END COPYRIGHT -->
    <script>
        var GlobalsAssetsPath = '<?php echo Yii::getAlias('@web') . '/themes/metronic/assets/' ?>';
    </script>
    <!--[if lt IE 9]>
    <?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/global/plugins/respond.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
    <?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/global/plugins/excanvas.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
    <![endif]-->
    <!-- BEGIN CORE PLUGINS -->
    <?php //$this->registerJsFile($baseUrl . "/themes/metronic/assets/global/plugins/bootstrap/js/bootstrap.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
    <?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/global/plugins/js.cookie.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
    <?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
    <?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
    <?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/global/plugins/jquery.blockui.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
    <?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/global/plugins/jquery-validation/js/jquery.validate.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
    <?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/global/plugins/jquery-validation/js/additional-methods.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
    <?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/global/plugins/select2/js/select2.full.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
    <?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/global/plugins/backstretch/jquery.backstretch.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
    <?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/pages/scripts/login.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/global/scripts/app.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
    <!-- END THEME GLOBAL SCRIPTS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <!-- END PAGE LEVEL SCRIPTS -->
    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <!-- END THEME LAYOUT SCRIPTS -->

    <?php $this->endBody() ?>
    </body>
    <!-- END BODY -->
    </html>
<?php $this->endPage() ?>