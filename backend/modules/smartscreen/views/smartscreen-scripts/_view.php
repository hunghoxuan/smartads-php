<?php

use \common\components\FHtml;
use common\widgets\FDetailView;
use yii\widgets\Pjax;

$moduleName = 'SmartscreenScripts';

$currentRole = FHtml::getCurrentRole();
$currentAction = FHtml::currentAction();

$print = isset($print) ? $print : true;
$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);

/* @var $this yii\web\View */
/* @var $model backend\modules\smartscreen\models\SmartscreenScripts */
?>
<?php if (!Yii::$app->request->isAjax) {
    $this->title = 'Smartscreen Scripts';
    $this->params['toolBarActions'] = array(
        'linkButton' => array(),
        'button' => array(),
        'dropdown' => array(),
    );
    $this->params['mainIcon'] = 'fa fa-list';
} ?>
<?php if ($ajax) Pjax::begin(['id' => 'crud-datatable']) ?>
<?php if (Yii::$app->request->isAjax) { ?>
    <div class="smartscreen-scripts-view">
        <?= FDetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'name',
                'Logo',
                'TopBanner',
                'BotBanner',
                'ClipHeader',
                'ClipFooter',
                'ScrollText',
                'Clipnum',
                'Clip1',
                'Clip2',
                'Clip3',
                'Clip4',
                'Clip5',
                'Clip6',
                'Clip7',
                'Clip8',
                'Clip9',
                'Clip10',
                'Clip11',
                'Clip12',
                'Clip13',
                'Clip14',
                'CommandNumber',
                'Line1',
                'Line2',
                'Line3',
                'Line4',
                'Line5',
                'Line6',
                'Line7',
                'Line8',
                'Line9',
                'Line10',
                'Line11',
                'Line12',
                'Line13',
                'Line14',
                'Line15',
                'Line16',
                'scripts_content',
                'scripts_file',
                'ReleaseDate',
                'sort_order',
                'is_active',
                'application_id',
            ],
        ]) ?>
    </div>
<?php } else { ?>
    <div class="row" style="padding: 20px">
        <div class="col-md-12" style="background-color: white; padding: 20px">
            <?= FDetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'name',
                    'Logo',
                    'TopBanner',
                    'BotBanner',
                    'ClipHeader',
                    'ClipFooter',
                    'ScrollText',
                    'Clipnum',
                    'Clip1',
                    'Clip2',
                    'Clip3',
                    'Clip4',
                    'Clip5',
                    'Clip6',
                    'Clip7',
                    'Clip8',
                    'Clip9',
                    'Clip10',
                    'Clip11',
                    'Clip12',
                    'Clip13',
                    'Clip14',
                    'CommandNumber',
                    'Line1',
                    'Line2',
                    'Line3',
                    'Line4',
                    'Line5',
                    'Line6',
                    'Line7',
                    'Line8',
                    'Line9',
                    'Line10',
                    'Line11',
                    'Line12',
                    'Line13',
                    'Line14',
                    'Line15',
                    'Line16',
                    'scripts_content',
                    'scripts_file',
                    'ReleaseDate',
                    'sort_order',
                    'is_active',
                    'application_id',
                ],
            ]) ?>
        </div>
    </div>
<?php } ?>
<?php if ($ajax) Pjax::end() ?>
