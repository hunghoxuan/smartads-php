<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
use \common\components\FHtml;
use common\components\Helper;
use common\widgets\FDetailView;
use yii\widgets\Pjax;

$moduleName = 'SmartscreenLayouts';

$currentRole = FHtml::getCurrentRole();
$currentAction = FHtml::currentAction();

$canEdit = FHtml::isInRole('', 'edit', $currentRole, FHtml::getFieldValue($model, ['user_id', 'created_user']));
$canDelete = FHtml::isInRole('', 'delete', $currentRole);

$print = isset($print) ? $print : true;
$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);

/* @var $this yii\web\View */
/* @var $model backend\modules\smartscreen\models\SmartscreenLayouts */
?>
<?php if (!Yii::$app->request->isAjax) {
$this->title = 'Smartscreen Layouts';
$this->params['toolBarActions'] = array(
'linkButton'=>array(),
'button'=>array(),
'dropdown'=>array(),
);
$this->params['mainIcon'] = 'fa fa-list';
} ?>
<?php if ($ajax) Pjax::begin(['id' => 'crud-datatable'])  ?>

<?php if (Yii::$app->request->isAjax) { ?>
<div class="smartscreen-layouts-view">

       <?= FDetailView::widget([
    'model' => $model,
    'attributes' => [
                    'id',
                'name',
                'description',
                'sort_order',
                'is_active',
                'frame_header',
                'frame_sidebar',
                'frame_main',
                'frame_footer',
                'created_date',
                'modified_date',
                'appilication_id',
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
                'description',
                'sort_order',
                'is_active',
                'frame_header',
                'frame_sidebar',
                'frame_main',
                'frame_footer',
                'created_date',
                'modified_date',
                'appilication_id',
                ],
                ]) ?>

            </div>

        </div>

<?php } ?><?php if ($ajax) Pjax::end()  ?>

