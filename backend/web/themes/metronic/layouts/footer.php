<?php
use common\components\FHtml;
$baseUrl = Yii::$app->getUrlManager()->getBaseUrl();
$frontendBaseUrl = \common\components\FHtml::currentBaseURL(FRONTEND);
$layout = isset($layout) ? $layout : '';

?>

<!--[if lt IE 9]>
<?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/global/plugins/respond.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/global/plugins/excanvas.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<![endif]-->


<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->

<?php //$this->registerJsFile($baseUrl . "/js/jquery.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php //$this->registerJsFile($baseUrl . "/themes/metronic/assets/global/plugins/bootstrap/js/bootstrap.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>

<?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/global/plugins/js.cookie.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/global/plugins/jquery.blockui.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<!--
-->

<!-- END CORE PLUGINS -->


<!-- BEGIN THEME GLOBAL SCRIPTS -->
<!-- END THEME LAYOUT SCRIPTS -->
<?php $this->registerJsFile($baseUrl . "/js/eModal.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php $this->registerJsFile($baseUrl . "/js/init.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>

<!-- END THEME GLOBAL SCRIPTS -->

<!-- BEGIN THEME LAYOUT SCRIPTS -->
<?php $this->registerJsFile($baseUrl . "/plugins/pnotify/pnotify.custom.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>

<?php //$this->registerJsFile($baseUrl . "/plugins/fullcalendar/moment.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php //$this->registerJsFile($baseUrl . "/plugins/fullcalendar/fullcalendar.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>

<?php if ($layout !== 'no') { ?>
    <?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/global/scripts/app.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
    <?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/layouts/layout/scripts/layout.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/layouts/layout/scripts/demo.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php $this->registerJs("makeTabsAnchorOnCurrentURL();") ?>
<?php } ?>
<!-- END THEME LAYOUT SCRIPTS -->

<!-- END JAVASCRIPTS -->

<style>
    <?php

    $user = isset($user) ? $user : FHtml::currentUserIdentity();

    $background_color = FHtml::setting('backend_background_color', BACKEND_MENU_BACKGROUND);
    $border_style = \common\components\FConfig::settingThemeBorderStyle(TABLE_BORDER_STYLE);
        $border_color = \common\components\FConfig::setting('table_border_color', TABLE_BORDER_COLOR);
        $border_width = \common\components\FConfig::setting('table_border_width', '1px');
        $strip_dark_color = \common\components\FConfig::setting('table_strip_dark_color', '#fafafa');
        $strip_light_color = \common\components\FConfig::setting('table_strip_light_color', '#fff');
        $table_header_color = \common\components\FConfig::setting('table_header_color', '');
        $form_label_color = \common\components\FConfig::setting('form_label_color', '#fafafa');
        $form_label_border = \common\components\FConfig::setting('form_label_border', 'left');
        $form_label_spacing = \common\components\FConfig::setting('form_label_spacing', 0);
        $backend_font_size = \common\components\FConfig::settingBackendFontSize();

        $form_background =  \common\components\FConfig::setting('form_background', '');

        $form_control_color =  \common\components\FConfig::setting('form_control_color', '#fff'); if (empty($form_control_color)) $form_control_color = '#fff';
        $form_control_border =  \common\components\FConfig::setting('form_control_border', 'round2');
        $form_control_height =  \common\components\FConfig::setting('form_control_height', '');
        if (empty($form_control_height)) {
            if ($backend_font_size == '20px')
                $form_control_height = '40px';
            elseif ($backend_font_size == '24px')
                $form_control_height = '50px';

        }

        $main_color = FHtml::settingBackendMainColor();
         $backend_header_background = FHtml::setting('backend_header_background', $main_color);
         if (empty($backend_header_background))
             $backend_header_background = $main_color;

        $backend_menu_active_color = FHtml::setting('backend_menu_active_color', $backend_header_background);
        $backend_menu_background_color = FHtml::setting('backend_menu_background_color', $form_label_color);

        if (empty($backend_menu_active_color))
            $backend_menu_active_color = $main_color;

        $footer_style = FHtml::setting('backend_footer_style', 'on');
        $backend_header_color = FHtml::setting('backend_header_color', 'white');
        $border_radius = '0px';

    ?>
    <?php if (empty($user)) { ?>
        .page-content {
            margin-left: 0px !important;
        }
    <?php } ?>
    <?php if (\common\components\FConfig::settingThemePortletStyle() == 'none' || empty(\common\components\FConfig::settingThemePortletStyle())) { ?>
        .page-content {
            background-color: #fff !important;
        }
        .portlet.light {
            border: none !important;
            padding: 0px !important;
            background-color: #fff !important;
        }

        .portlet.light.bordered {
            border: none !important;
        }

        .portlet {
            box-shadow: none !important;
        }

    <?php } ?>

    /* TABLE GRID */
    .kv-grid-container > .table-bordered, .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
        <?php if ($border_style == 'none' || empty($border_color) || empty($border_width)) { ?>
            border:  none;
        <?php } else if ($border_style == 'all') { ?>
            border: <?= $border_width ?> solid <?= $border_color ?>;
        <?php } else if ($border_style == 'line') { ?>
            border: none ;
            border-top: <?= $border_width ?> solid <?= $border_color ?>;
        <?php } else { ?>
            border: <?= $border_width ?> solid <?= $border_color ?>;

        <?php } ?>
    }

    .table-striped>tbody>tr:nth-of-type(odd) {
        background-color: <?=  $strip_dark_color ?>;
    }

    .table-striped>tbody>tr:nth-of-type(even) {
        background-color: <?=  $strip_light_color ?>;
    }

        /* MENU LEFT */
    .page-sidebar .page-sidebar-menu .sub-menu li, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu .sub-menu li {

    }

    .bordered {
        border: <?= $border_width ?> solid <?= $border_color ?>;
    }

    .admin-toolbar {
        <?php if (!empty($table_header_color)) { ?>
        background-color: <?=  $table_header_color ?>;
        border: <?= $border_width ?> solid <?= $border_color ?>;
        padding: 5px;
        <?php } else { ?>
        border-bottom: <?= $border_width ?> solid <?= $border_color ?>;
        <?php } ?>

    }

    .nav-tabs {
        border-bottom: none !important;
    }

    .help-block {
        padding-left: 15px;
    }

    .nav-active {
        background-color:#eef1f5; font-weight:bold; border-left:2px solid lightgrey;
    }

    .nav-normal {
        background-color:<?= $form_label_color ?>; border-left:2px solid lightgrey; margin-bottom: 1px;
    }

    /* FORMS */
    .form-label {
        <?php if (in_array($form_label_border, ['left', 'top', 'bottom', 'right'])) { ?>
            border-<?= $form_label_border ?>: 2px solid lightgrey;
        <?php } else if ($form_label_border == 'all') { ?>
            border: 1px solid lightgrey;
        <?php } else  { ?>
             border: none;
        <?php } ?>
        background-color: <?= $form_label_color ?>;
    <?php if (!empty($form_label_spacing) || (in_array($form_label_border, ['all', 'top', 'bottom']))) { ?>
        margin-top: 5px;
        padding: 8px !important;
        <?php } ?>

        min-height: <?= $form_control_height?> ;
    }

    .portlet  {
        background-color: <?= $form_background ?> !important;
    }

    .form-control, .select2-container--krajee .select2-selection {
        background-color: <?= $form_control_color ?>;
        <?php if ($form_control_border == 'line') { ?>
            border: none !important;
            border-bottom: 1px solid lightgrey !important;
        <?php } else  { ?>
            border: none;
        <?php } ?>
        /*
        box-shadow: 0 1px 3px rgba(0,0,0,.1), 0 1px 2px rgba(0,0,0,.18) !important;
        transition: box-shadow .28s cubic-bezier(.4,0,.2,1) !important;
        */
    }

    .form-buttons {
        padding:15px; padding-bottom:0px; right:0px; left:0px; position: relative; height: auto;bottom: 0;width: 100%; border-top:1px dashed lightgrey; z-index:2;
    }

     .form-control, .select2-container--krajee .select2-selection, .btn, .label {
        border-width: <?= $border_width?>;
        <?php if ($form_control_border == 'round') { $border_radius = '10px'; }
        else if ($form_control_border == 'round2') { $border_radius = '20px'; }
        else if ($form_control_border == 'normal') { $border_radius = '4px'; }
        else if ($form_control_border == 'box') { $border_radius = '0px'; } ?>

        border: 1px solid lightgrey;
        border-radius: <?= $border_radius ?> !important;
        min-height: <?= $form_control_height?> ;
        font-size: <?= $backend_font_size ?>;
    }

    .input-group-html5 .input-group-addon:first-child {
        border-radius:  <?= $border_radius ?> 0px 0px <?= $border_radius ?> !important;
    }

    input[type="checkbox"].form-control:checked, .nav-tabs > li > a::after {
        background-color: <?= $main_color ?>;
    }

    .group-header, .card .nav-tabs > li.active > a, .nav-tabs > li > a:hover {
        color: <?= $main_color ?> !important;
    }

    .caption-title, .caption-subject, h3 {
        color: <?= $main_color ?> !important; font-weight: bold !important; font-size: 24px !important;
    }

    .caption-title, .caption-subject {
        /*border-bottom: 1px solid ; margin-bottom: 50px;*/
    }

    /* FORMS */

    .btn-danger, .label-danger, .badge-danger {
        background-color: white; color: #e73d4a; border-color: #e73d4a; border: 1px solid; font-weight: bolder;
    }

    .btn-primary, .label-primary, .badge-primary, .btn-info {
        background-color: white; color: #337ab7; border-color: #337ab7; border: 1px solid; font-weight: bolder;
    }

    .btn-success, .label-success, .badge-success {
        background-color: white; color: #36c6d3; border-color: #36c6d3; border: 2px solid; font-weight: bolder;
    }

    .btn-warning, .label-warning, .badge-warning {
        background-color: white; color: #c29d0b; border-color: #c29d0b; border: 1px solid; font-weight: bolder;
    }

    .label-default, .badge-default {
        background-color: white; color: #bac3d0; border-color: #bac3d0; border: 1px solid; font-weight: bolder; color: darkgrey;
    }

    .badge-danger, .badge-default, .badge-primary, .badge-success {
        font-size: 13px !important;
        padding: 2px 5px !important;
    }

    .label, .badge, .label-default, .label-success, .label-primary, .label-danger, .label-info, .label-warning, .badge-default, .badge-success, .badge-primary, .badge-danger, .badge-info, .badge-warning {
        box-shadow: none !important; border: 1px solid !important; font-weight: normal !important; font-size: <?= $backend_font_size ?>;
    }

    .label-default, .badge-default {
        color: black !important;
    }

    body,
    .form-control, .btn,
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th,
    .portlet.light>.portlet-title>.nav-tabs>li>a,
    .nav-pills>li>a, .nav-tabs>li>a, a
    {
        font-size:<?= $backend_font_size ?> !important;
    }

    .page-content {
        background-color: <?= $background_color ?>;
    }
    .page-sidebar-menu, .page-container {
        background-color:  <?= $backend_menu_background_color ?>;
    }
    .page-sidebar .page-sidebar-menu>li.active.open>a:hover, .page-sidebar .page-sidebar-menu>li.active>a:hover, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu>li.active.open>a:hover, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu>li.active>a:hover
    {
        background-color: <?= $backend_menu_active_color ?> !important;
    }

    .page-sidebar .page-sidebar-menu>li.active.open>a, .page-sidebar .page-sidebar-menu>li.active>a, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu>li.active.open>a, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu>li.active>a {
        background-color: <?= $backend_menu_active_color ?> !important;
    }
    .page-sidebar .page-sidebar-menu>li.active.open>a>.selected, .page-sidebar .page-sidebar-menu>li.active>a>.selected, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu>li.active.open>a>.selected, .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu>li.active>a>.selected {
        border-left: 8px solid <?= $backend_menu_active_color ?>;
    }
    .nav-item .active {
        /*
        border-right: 2px solid !important;
        background-color: <?= $backend_menu_active_color ?> !important;
        font-weight: bold !important; */
    }
    .page-footer {
        font-size: 8pt;
        color: <?= $backend_header_color ?>;
        text-align: center;
        background-color: <?= $backend_menu_background_color ?>;
    <?= in_array($footer_style, ['on', 'fixed']) ? "position: fixed; bottom: 0;" : ''; ?>
        width: 100%;
    }

    .username {
        color: <?= $backend_header_color ?> !important;
    }
    .page-header.navbar {
        background-color: <?= $backend_header_background ?> !important;
    }

    .application-title {
        color: <?= $backend_header_color ?> !important;
    }

    .checkbox, .radio {
        float: left;
        margin-right: 30px;
    }

    @media (max-width: 440px) {
        .top-menu-wrapper {
            visibility: hidden;
        }
    }

    html {
        overflow-x: hidden !important;
    }
    .file-drop-zone {
        height: 50px !important;
        margin:0px;padding:0px;
        background-color: #fafafa;
    }
    .file-drop-zone-title {
        height: 50px; padding-top:20px;
        /*visibility: hidden; !important; */
    }
    .file-preview-frame .kv-file-content {
        height: 60px !important;
    }
    .krajee-default.file-preview-frame {
        height: 50px !important;
    }
    .file-drop-zone .file-preview-thumbnails {
    }
    .krajee-default.file-preview-frame {
        margin:0px !important; padding:0px !important; box-shadow: none !important; border: none !important;
    }
    .krajee-default .file-footer-buttons {
        visibility: hidden;
    }
    .modal-header {
        padding:5px; padding-left:20px;padding-top:10px;
        background-color: <?= $form_label_color ?>;
    }
    .input-group-html5 .input-group-addon:first-child {
        border-right: none !important;;
    }

    .dropdown-submenu > a:after {
        margin-right: 0px !important;
    }

    .page-header.navbar .top-menu .navbar-nav>li.dropdown-user .dropdown-toggle>img {
        width: 30px !important;
        height: 30px !important;
    }

    /* Customize the label (the container) */
    .checkmark-container {
        display: block;
        position: relative;
        padding-top: 5px !important;
        padding-left: 35px !important;
        margin-bottom: 12px;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    /* Hide the browser's default checkbox */
    .checkmark-container input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    /* Create a custom checkbox */
    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 25px;
        width: 25px;
        background-color: #eee;
    }

    /* On mouse-over, add a grey background color */
    .checkmark-container:hover input ~ .checkmark {
        background-color: #ccc;

    }

    /* When the checkbox is checked, add a blue background */
    .checkmark-container input:checked ~ .checkmark {
        background-color: <?= $main_color?>;;
    }

    /* Create the checkmark/indicator (hidden when not checked) */
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    /* Show the checkmark when checked */
    .checkmark-container input:checked ~ .checkmark:after {
        display: block;
    }

    /* Style the checkmark/indicator */
    .checkmark-container .checkmark:after {
        left: 9px;
        top: 5px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 3px 3px 0;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
    }

</style>