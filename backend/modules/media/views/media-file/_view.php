<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
use \common\components\FHtml;
use common\components\Helper;
use common\widgets\FDetailView;

$moduleName = 'MediaFile';
$currentRole = FHtml::getCurrentRole();

/* @var $this yii\web\View */
/* @var $model backend\modules\media\models\MediaFile */
?>
<?php if (!Yii::$app->request->isAjax) {
$this->title = FHtml::t('app', 'Media Files');
$this->params['toolBarActions'] = array(
'linkButton'=>array(),
'button'=>array(),
'dropdown'=>array(),
);
$this->params['mainIcon'] = 'fa fa-list';
} ?><?php if (Yii::$app->request->isAjax) { ?>
<div class="media-file-view">

       <?= FDetailView::widget([
    'model' => $model,
    'attributes' => [
                    'id',
                'name',
                           [
                           'attribute' => 'image',
                           'value' => FHtml::showImageThumbnail($model->image, 150, 'media-file'),
                           'format' => 'html',
                           ],
                'file',
                'file_path',
                'description',
                'file_type',
                'file_size',
                'file_duration',
                'is_active',
                'sort_order',
                'created_date',
                'created_user',
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
                    <?= FHtml::t('app', 'Media Files')?>
</span>
            <span class="caption-helper"><?=  FHtml::t('common', 'title.view') ?>
</span>
        </div>
        <div class="tools">
            <a href="#" class="fullscreen"></a>
            <a href="#" class="collapse"></a>
        </div>
        <div class="actions">
        </div>
    </div>
    <div class="portlet-body">
        <div class="row">
            <div class="col-md-12">
                <?= FDetailView::widget([
                'model' => $model,
                'attributes' => [
                                           'id',
                           'name',
                           [
                           'attribute' => 'image',
                           'value' => FHtml::showImageThumbnail($model->image, 150, 'media-file'),
                           'format' => 'html',
                           ],
                           'file',
                           'file_path',
                           'description',
                           'file_type',
                           'file_size',
                           'file_duration',
                           'is_active',
                           'sort_order',
                           'created_date',
                           'created_user',
                           'application_id',
                ],
                ]) ?>
                <p>
                    <?php if (FHtml::isInRole($moduleName, 'update', $currentRole)) { Html::a( FHtml::t('common', 'button.update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']); } ?>
                    <?php if (FHtml::isInRole($moduleName, 'delete', $currentRole)) {Html::a( FHtml::t('common', 'button.delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                    'confirm' => FHtml::t('common', 'message.confirmdelete'),
                    'method' => 'post',
                    ],
                    ]);} ?>
                    <?=  Html::a(FHtml::t('common', 'button.cancel'), ['index'], ['class' => 'btn
                    btn-default']) ?>
                </p>
            </div>
        </div>
    </div>
</div>
<?php } ?>
