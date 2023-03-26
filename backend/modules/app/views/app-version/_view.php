<?php

use \common\components\FHtml;
use common\widgets\FDetailView;
use yii\widgets\Pjax;

$moduleName = 'AppVersion';

$currentRole = FHtml::getCurrentRole();
$currentAction = FHtml::currentAction();

$canEdit = FHtml::isInRole('', 'edit', $currentRole, FHtml::getFieldValue($model, ['user_id', 'created_user']));
$canDelete = FHtml::isInRole('', 'delete', $currentRole);

$print = isset($print) ? $print : true;
$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);

/* @var $this yii\web\View */
/* @var $model backend\modules\app\models\AppVersion */
?>
<?php if (!Yii::$app->request->isAjax) {
    $this->title = 'App Versions';
    $this->params['toolBarActions'] = array(
        'linkButton' => array(),
        'button' => array(),
        'dropdown' => array(),
    );
    $this->params['mainIcon'] = 'fa fa-list';
} ?>
<?php if ($ajax) Pjax::begin(['id' => 'crud-datatable']) ?>
<?php if (Yii::$app->request->isAjax) { ?>
    <div class="app-version-view">
        <?= FDetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'name',
                'description',
                'package_version',
                'package_name',
                'platform',
                'platform_info',
                'file',
                'count_views',
                'count_downloads',
                'is_active',
                'is_default',
                'history',
                'created_date',
                'created_user',
                'modified_date',
                'modified_user',
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
                    'description',
                    'package_version',
                    'package_name',
                    'platform',
                    'platform_info',
                    'file',
                    'count_views',
                    'count_downloads',
                    'is_active',
                    'is_default',
                    'history',
                    'created_date',
                    'created_user',
                    'modified_date',
                    'modified_user',
                    'application_id',
                ],
            ]) ?>
        </div>
    </div>
<?php } ?>
<?php if ($ajax) Pjax::end() ?>
