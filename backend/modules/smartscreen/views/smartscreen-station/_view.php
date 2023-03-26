<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
use \common\components\FHtml;
use common\components\Helper;
use common\widgets\FDetailView;

$moduleName = 'SmartscreenStation';
$currentRole = FHtml::getCurrentRole();


/* @var $this yii\web\View */
/* @var $model backend\modules\smartscreen\models\SmartscreenStation */
?>
<?php if (!Yii::$app->request->isAjax) {
$this->title = 'Smartscreen Stations';
$this->params['toolBarActions'] = array(
'linkButton'=>array(),
'button'=>array(),
'dropdown'=>array(),
);
$this->params['mainIcon'] = 'fa fa-list';
} ?><?php if (Yii::$app->request->isAjax) { ?>
<div class="smartscreen-station-view">

       <?= FDetailView::widget([
    'model' => $model,
    'attributes' => [
                    'id',
                'name',
                'ScreenName',
                'MACAddress',
                'LicenseKey',
                'branch_id',
                'script_id',
                'script_update',
                'created_date',
                'application_id',
    ],
    ]) ?>
</div>
<?php } else { ?>
<div class="<?= $this->params['portletStyle'] ?>">
    <?= $this->render(\Globals::VIEWS_PRINT_HEADER, ['title' => '',]) ?>
    <div class="portlet-title">
        <div class="caption font-dark">
                <span class="caption-subject bold uppercase">
                <i class="<?php  echo $this->params['mainIcon'] ?>"></i>
                    <?= $model->name . ' - ' . $model->ime ?>
</span>
            <span class="caption-helper">
</span>
        </div>
        <div class="tools">
            <a href="#" class="fullscreen"></a>
            <a href="#" class="collapse"></a>
        </div>
        <div class="actions">
        </div>
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab_1_1" data-toggle="tab"><?= FHtml::t('common', 'HIS')?></a>
                <a href="#tab_1_2" data-toggle="tab"><?= FHtml::t('common', 'HIS API')?></a>

            </li>

        </ul>
    </div>
    <div class="portlet-body">


        <div class="tab-content">
            <div class="tab-pane active row" id="tab_1_1">
                <div class="col-md-12">
                    <?php
                    $api_data = \backend\modules\smartscreen\Smartscreen::getQueueModels('smartscreen_queue', [], $model);
                    $header = is_array($api_data) && key_exists('title', $api_data) ? $api_data['title'] : '';

                    ?>
                    <iframe width="100%" src="<?= \backend\modules\smartscreen\Smartscreen::getHisContentUrl($model) ?>&header=<?=$header?>" height="680" frameborder="0">

                    </iframe>
                    <?php
                    echo "<h1>HiS DATA</h1>";
                    FHtml::var_dump($api_data);
                    ?>
                </div>
            </div>
            <div class="tab-pane row" id="tab_1_2">
                <div class="col-md-12">


                </div>
            </div>
        </div>
        <div>
            <?= FHtml::showViewButtons($model) ?>
        </div>


    </div>
</div>
<?php } ?>
