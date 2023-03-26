<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
use \common\components\FHtml;


$moduleName = 'ObjectSetting';
$currentRole = FHtml::getCurrentRole();

/* @var $this yii\web\View */
/* @var $model backend\modules\system\models\ObjectSetting */
?>
<?php if (!Yii::$app->request->isAjax) {
    $this->title = FHtml::t('app', 'Object Settings');
    $this->params['toolBarActions'] = array(
        'linkButton' => array(),
        'button' => array(),
        'dropdown' => array(),
    );
    $this->params['mainIcon'] = 'fa fa-list';
} ?><?php if (Yii::$app->request->isAjax) { ?>
    <div class="object-setting-view">

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'object_type',
                'meta_key',
                'key',
                'value',
                'description:ntext',
                'icon',
                'color',
                'is_active',
                'sort_order',
                'application_id',
            ],
        ]) ?>
    </div>
<?php } else { ?>
    <div class="<?= $this->params['portletStyle'] ?>">
        <div class="portlet-title hidden-print">
            <div class="caption-title uppercase font-dark">
                <span class="caption-subject bold uppercase">
                <i class="<?php echo $this->params['mainIcon'] ?>"></i>
                    <?= FHtml::t('app', 'Object Settings') ?>
</span>
                <span class="caption-helper"><?= FHtml::t('common', 'title.view') ?>
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
                    <?= \common\widgets\FDetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'object_type',
                            'meta_key',
                            'key',
                            'value',
                            'description:ntext',
                            'icon',
                            'color',
                            'is_active',
                            'sort_order',
                            'application_id',
                        ],
                    ]) ?>
                    <p>
                        <?php if (FHtml::isInRole('', 'update', $currentRole)) {
                            Html::a(FHtml::t('common', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
                        } ?>
                        <?php if (FHtml::isInRole('', 'delete', $currentRole)) {
                            Html::a(FHtml::t('common', 'Delete'), ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => FHtml::t('common', 'Are you sure to delete ?'),
                                    'method' => 'post',
                                ],
                            ]);
                        } ?>
                        <?= Html::a(FHtml::t('common', 'Cancel'), ['index'], ['class' => 'btn
                    btn-default']) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
