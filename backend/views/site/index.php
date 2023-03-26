<?php

use backend\modules\smartscreen\models\SmartscreenSchedulesSearch;
use backend\modules\smartscreen\Smartscreen;
use common\components\FHtml;
use common\widgets\fchart\Statistic;
use common\widgets\FGridView;

/* @var $this yii\web\View */

$this->title = FHtml::t('Dashboard');
$baseUrl     = Yii::$app->getUrlManager()->getBaseUrl();

$folders = [''];
Smartscreen::startSocket();

$port = \common\components\FConfig::setting('nodejs.port', 8890);
$nodeJsEnabled = Smartscreen::isNodeJsEnabled();
$checkport = $nodeJsEnabled ? Smartscreen::checkSocketPort() : true;

$cmd = Smartscreen::getNodeJsStartCommand();

if (!$checkport && $nodeJsEnabled)
    Smartscreen::startSocket();

//Smartscreen::setDefaultTimezone();
$timezone = date_default_timezone_get();
$timezone_ini = ini_get('date.timezone');
$action = FHtml::getRequestParam('action');
if ($action == 'info') {
    phpinfo();
}

?>

<div class="site-index">
    <h3 class="page-title">
        <?= FHtml::t('common', 'Dashboard') ?>
        <small><?= FHtml::t('common', 'reports & statistics') ?></small>
    </h3>
    <!--Markdown-->
    <!-- END DASHBOARD STATS -->

    <div class="row">
        <?php
        $channels  = \backend\modules\smartscreen\models\SmartscreenChannels::getTotalRecord();
        $devices = \backend\modules\smartscreen\models\SmartscreenStation::getTotalRecord();
        $users = \common\models\User::getTotalRecord();
        $content = \backend\modules\smartscreen\models\SmartscreenContent::getTotalRecord();

        ?>
        <?= Statistic::showHtmlStatistic($channels, FHtml::t('Channels'), '', '#', Statistic::PURPLE_PLUM); ?>
        <?= Statistic::showHtmlStatistic($devices, FHtml::t('Devices'), '', '#', Statistic::BLUE_MADISON); ?>
        <?= Statistic::showHtmlStatistic($users, FHtml::t('Users'), '', '#', Statistic::GREEN_HAZE); ?>
        <?= Statistic::showHtmlStatistic($content, FHtml::t('Content'), '', '#', Statistic::RED_INTENSE); ?>

        <div class="col-md-12">
            <label class="label label-<?= $checkport ? 'success' : 'danger' ?>" style="font-size: 14px; padding: 5px">
                <?php if ($nodeJsEnabled) { ?>
                    Socket Port <?= $port ?>: <?= $checkport ? 'OPEN' : 'CLOSED. Please manually run this command on Terminal: ' ?>
                <?php } else { ?>
                    Socket is disabled. Enable it in Settings/Smartscreen menu.
                <?php } ?>
            </label>
            <?php if ($nodeJsEnabled && !$checkport && !empty($cmd)) echo "<pre>$cmd</pre>" ?>
            <div style="padding-top: 10px" class="">
                System Timezone: <?= $timezone ?>. PHP.ini Timezone: <?= $timezone_ini ?>. (Must set Timezone=Asia/Ho_Chi_Minh)
                <?= FHtml::createLink('/', ['action' => 'info', 'layout' => 'no']) ?>
            </div>
        </div>
    </div>

    <div class="clearfix">
        <?php
        if (false) {
            $searchModel = SmartscreenSchedulesSearch::createNew();
            $searchModel->load(Yii::$app->request->post());
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $object_type = \backend\modules\smartscreen\models\SmartscreenSchedules::tableName();
        ?>

            <div class="row">
                <div class="col-md-12 no-padding">
                    <div class="col-md-10">
                        <h3><?= FHtml::t('Schedules') ?></h3>
                    </div>
                    <div class="col-md-2">
                        <div style="float: right">
                            <?= FHtml::showModal1(FHtml::createUrl('/smartscreen/schedules', ['layout' => 'no'])); ?>
                            <a href="<?= FHtml::createUrl('/smartscreen/schedules', ['layout' => 'no']) ?>" target="_blank" class="btn btn-primary"><?= FHtml::t('common', 'View') ?></a>
                        </div>
                    </div>
                </div>
            </div>

            <?= FGridView::widget([
                'id' => 'crud-datatable',
                'dataProvider' => $dataProvider,
                'filterModel' => null,
                'filter' => null,
                'object_type' => $object_type,
                'edit_type' => false,
                'render_type' => FHtml::RENDER_TYPE_AUTO,
                'readonly' => true,
                'field_business' => ['', ''],
                'toolbar' => null,
                'columns' => require(__DIR__ . '/../../modules/smartscreen/views/smartscreen-schedules/_columns_view.php'),
            ]) ?>

        <?php } ?>
    </div>

    <?= \common\widgets\FDashboard::widget(); ?>
</div>



<?php
//$this->registerJsFile("/moza-business/node/node_modules/socket.io/socket.js", ['depends' => [\yii\web\JqueryAsset::className()]]);
$script = <<<JS
jQuery(document).ready(function() {

        var socket = io.connect('http://localhost:8890');
        
        socket.on('connect',function() {
            console.log('Client has connected to the server!');
        });
        // Add a connect listener
        socket.on('message',function(data) {
            console.log('Received a message from the server!',data);
        });

        // Add a connect listener
        socket.on('notification',function(data) {
            console.log('Received a Notification from the server!',data);
        });
        // Add a disconnect listener
        socket.on('disconnect',function() {
            console.log('The client has disconnected!');
        });

        // Sends a message to the server via sockets
        function sendMessageToServer(message) {
            socket.send(message);
        }
    });
JS;
//$this->registerJs($script, \yii\web\View::POS_END);
?>
<!--<script src="/moza-business/backend/web/assets/2d8538fc/jquery.js"></script>-->
<!--<script src="/moza-business/node/node_modules/socket.io/socket.js"></script>-->
<!---->
<!--<script type="text/javascript">-->
<!--    jQuery(document).ready(function() {-->
<!---->
<!--        var socket = io.connect('http://localhost:8890');-->
<!---->
<!--        // Add a connect listener-->
<!--        socket.on('connect',function() {-->
<!--            console.log('Client has connected to the server!');-->
<!--        });-->
<!--        // Add a connect listener-->
<!--        socket.on('message',function(data) {-->
<!--            console.log('Received a message from the server!',data);-->
<!--        });-->
<!---->
<!--        // Add a connect listener-->
<!--        socket.on('notification',function(data) {-->
<!--            console.log('Received a Notification from the server!',data);-->
<!--        });-->
<!--        // Add a disconnect listener-->
<!--        socket.on('disconnect',function() {-->
<!--            console.log('The client has disconnected!');-->
<!--        });-->
<!---->
<!--        // Sends a message to the server via sockets-->
<!--        function sendMessageToServer(message) {-->
<!--            socket.send(message);-->
<!--        };-->
<!---->
<!--    });-->
<!--</script>-->


<style>
    .demo-layout {
        background-color: #666;
        width: 100%;
        height: 80px;
    }

    .div-layout {
        width: 100%;
        height: 100%;
        position: relative;
    }
</style>