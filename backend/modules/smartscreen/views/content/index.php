<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "SmartscreenContent".
*/

use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use common\components\CrudAsset;
use common\widgets\BulkButtonWidget;
use common\components\FHtml;
use common\components\Helper;
use backend\modules\smartscreen\models\SmartscreenContent;

$moduleName = 'SmartscreenContent';
$moduleTitle = 'Smartscreen Content';
$moduleKey = 'smartscreen-content';
$object_type = 'smartscreen-content';

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'] = [];
$this->params['breadcrumbs'][] = $this->title;

$this->params['toolBarActions'] = array (
'linkButton'=>array(),
'button'=>array(),
'dropdown'=>array(),
);
$this->params['mainIcon'] = 'fa fa-list';

//CrudAsset::register($this);

$currentRole = FHtml::getCurrentRole();
$viewType = FHtml::getRequestParam('view');
$gridControl = FHtml::settingPageView('_index');
$deviceId = FHtml::getRequestParam('device_id');

$id = FHtml::getRequestParam('id');
$schedule_id = FHtml::getRequestParam('schedule_id');

$footer_title = isset($description) ? $description : FHtml::getRequestParam(['footer', 'description']);
$header_title = isset($title) ? $title : FHtml::getRequestParam(['header', 'title']);
$refresh = isset($refresh) ? $refresh : FHtml::getRequestParam(['auto_refresh', 'refresh'], \backend\modules\smartscreen\Smartscreen::settingPageRefreshInterval());
$background = FHtml::getRequestParam(['background', 'bgcolor', 'bg'], FHtml::settingBackendMainColor());

if (in_array(strtolower($refresh), ['no', 'false']))
    $refresh =  0;

if (!is_numeric($refresh))
    $refresh = FHtml::getNumeric($refresh);

$type = !empty($models->type) ? $models->type : FHtml::getRequestParam('form_type');

if (!empty($id) && !in_array($type, [SmartscreenContent::TYPE_HIS_VIMES, SmartscreenContent::TYPE_HIS])) {
    if (!isset($models))
        $models = SmartscreenContent::findOne($id);
    if (!isset($models)) {
        echo "Content not found (id: $id)! ";
        die;
    }
    if (file_exists(__DIR__ . '/'  . $type . '/_index.php'))
        $gridControl = $type . '/_index.php';
    else
        $gridControl ='_index';

} else if (!empty($schedule_id) && !in_array($type, [SmartscreenContent::TYPE_HIS_VIMES, SmartscreenContent::TYPE_HIS])) {
    if (!isset($models))
        $models = \backend\modules\smartscreen\models\SmartscreenSchedules::findOne($schedule_id);
    if (!isset($models)) {
        echo "Schedule not found (id: $schedule_id)! ";
        die;
    }
    if (file_exists(__DIR__ . '/'  . $type . '/_index.php'))
        $gridControl = $type . '/_index.php';
    else
        $gridControl ='_index';

} else {
    //default ==> show fake HIS
    $models = isset($models) ? $models : \backend\modules\smartscreen\Smartscreen::getQueueModels('smartscreen_queue');

    if (is_array($models) && key_exists('title', $models) && !empty($models['title'])) {
        $header_title = ' ' . (is_numeric($models['title']) ? (FHtml::t('common', 'Room') . ' ' . $models['title']) : $models['title']);
        $header_title = FHtml::strReplace($header_title, [' Phong ' => ' Phòng ']);
    }

    if (is_array($models) && key_exists('doctor', $models) && !empty($models['doctor'])) {
        $doctor = $models['doctor'];
        if (!\yii\helpers\StringHelper::startsWith(strtolower($doctor), 'bs'))
            $doctor = 'BS ' . $doctor;

        $header_title .= ' (' . $doctor . ')';
    }
}
//$gridControl = '_index';
$header_title = trim($header_title);

?>

<?php \yii\widgets\Pjax::begin(['id' => "content"]);

?>

<?php if (!empty($header_title)) { ?>
    <div id="header" class="header">
        <?= $header_title ?>
    </div>
<?php } ?>

<div id="main" class="main">
    <?= FHtml::render($gridControl, $viewType, [
        'dataProvider' => isset($dataProvider) ? $dataProvider : null,
        'searchModel' => isset($searchModel) ? $searchModel : null,
        'models' => $models,
        'viewType' => $viewType
    ]) ?>
</div>

<?php if (!empty($footer_title)) { ?>
    <div id="footer" class="footer">
        <?= $footer_title ?>
    </div>
<?php } ?>
    <div class="hidden">
        <?= Html::a("<i class=\"fa fa-refresh\" aria-hidden=\"true\"></i>", FHtml::currentUrl(), ['class' => 'btn btn-lg btn-default', 'id' => 'refreshButton']) ?>
    </div>

<?php \yii\widgets\Pjax::end(); ?>


<?php
if ($refresh > 0) {
    $script = <<< JS
$(document).ready(function() {
    setInterval(function(){ $("#refreshButton").click(); }, $refresh);
});
JS;
    $this->registerJs($script);
}
?>



    <style>
        html, body
        {
            width: 100%;
            height: 100%;
            margin: 0px !important;
            background-color: <?= $background ?>;
        }
        body
        {
            margin: 0px !important;
            width: 100%;
            height: 100%;
        }
        .blink {
            animation: blink-animation 1s steps(5, start) infinite;
            -webkit-animation: blink-animation 1s steps(5, start) infinite;
        }
        @keyframes blink-animation {
            to {
                visibility: hidden;
            }
        }
        @-webkit-keyframes blink-animation {
            to {
                visibility: hidden;
            }
        }

        video::-webkit-media-controls-overlay-play-button {
            display: none !important;
        }

        video::-webkit-media-controls-play-button {
            display: none !important;
        }

        video::-webkit-media-controls{
            display: none !important;
            -webkit-appearance: none !important;
            opacity: 0;
        }

        *::-webkit-media-controls-panel {
            display: none!important;
            -webkit-appearance: none;
        }

        /* Old shadow dom for play button */

        *::-webkit-media-controls-play-button {
            display: none !important;
            -webkit-appearance: none;
        }

        /* New shadow dom for play button */

        /* This one works! */

        *::-webkit-media-controls-start-playback-button {
            display: none !important;
            -webkit-appearance: none;
        }

        body, p, .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
            padding:10px;
        }

        #content {
            position: absolute;
            width:100%;
            height: 100%;
        }

        .main {
            position: relative;
            <?php if (!empty($header_title)) { ?>
            top: 90px;
            <?php } ?>
            overflow-y:auto;
            height: 100%;
        }

        .table>caption+thead>tr:first-child>td, .table>caption+thead>tr:first-child>th, .table>colgroup+thead>tr:first-child>td, .table>colgroup+thead>tr:first-child>th, .table>thead:first-child>tr:first-child>td, .table>thead:first-child>tr:first-child>th {
            padding:10px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        td {
            text-align: left;
            padding: 20px;
            padding-top:30px; padding-bottom: 30px;
            text-transform: uppercase;
            font-size: 34pt !important;
            line-spacing: 0px !important;
            vertical-align:middle !important;
        }

        th {
            text-align: left;
            text-transform: uppercase;
            font-size: 34pt !important;
            vertical-align:middle !important;
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {background-color: #f2f2f2;}

        .arrow {
            font-size:20pt !important;padding-top:10px !important;color:grey;
        }

        .footer {
            position: fixed;
            width: 100%;
            bottom: 0;
            padding: 1rem;
            background-color: #000;
            z-index: 9999999;
        }

        .main-panel {
            background-color: white;
            border: solid 1px darkslategray;
            margin:0 auto;
            padding:20px;
            padding-top:50px;
            padding-bottom: -200px;
            text-align:center;
            overflow:hidden;
            height: 80%;
            margin-left:20px;
        }

        .panel-title {
            font-size: 55pt !important;
            color: black;
            margin-bottom:50px;
            text-transform: uppercase;
        }

        .sidebar-title {
            margin-top:20px; background-color: #f2f2f2; color: darkblue; padding: 20px;text-align: center; font-size:30pt !important;text-transform: uppercase;
        }

        .header {
            background-color: #274e13; color: white; padding: 10px !important;text-align: center; font-size:30pt !important;text-transform: uppercase;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 9999999;
        }


        @media (max-width: 768px) {

            body, p, .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
                padding:5px;
            }

            .table>caption+thead>tr:first-child>td, .table>caption+thead>tr:first-child>th, .table>colgroup+thead>tr:first-child>td, .table>colgroup+thead>tr:first-child>th, .table>thead:first-child>tr:first-child>td, .table>thead:first-child>tr:first-child>th {
                padding:5px;
            }

            td {
                font-size: 14pt !important; padding:5px;
            }

            th {
                font-size: 12pt !important; padding:5px;
            }

            .arrow {
                font-size:12pt !important; padding:5px;
            }
            .panel-title {
                font-size: 25pt !important;
                margin-bottom:20px;
            }

            .sidebar-title {
                margin-top:10px; padding: 10px; font-size:15pt !important;

            }

            .header {
                 padding: 10px !important;font-size:15pt !important;

            }

            .main-panel {
                margin-left:10px;
                padding:10px;
                padding-bottom: -50px;
                background-color:#FFF;
                height: 80%;
            }

            .main {
                top: 0px !important;
            }
        }
    </style>