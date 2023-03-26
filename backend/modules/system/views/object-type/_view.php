<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
use \common\components\FHtml;


$moduleName = 'ObjectType';
$currentRole = FHtml::getCurrentRole();

/* @var $this yii\web\View */
/* @var $model backend\models\ObjectType */
?>
<?php if (!Yii::$app->request->isAjax) {
    $this->title = 'Object Types';
    $this->params['toolBarActions'] = array(
        'linkButton' => array(),
        'button' => array(),
        'dropdown' => array(),
    );
    $this->params['mainIcon'] = 'fa fa-list';
} ?><?php if (Yii::$app->request->isAjax) { ?>
    <div class="object-type-view">

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'object_type',
                'name',
                'sort_order',
                'is_active',
            ],
        ]) ?>
    </div>
<?php } else { ?>
    <div class="<?= $this->params['portletStyle'] ?>">
        <div class="portlet-title hidden-print">
            <div class="caption-title uppercase font-dark">
                <span class="caption-subject bold uppercase">
                <i class="<?php echo $this->params['mainIcon'] ?>"></i>
                    <?= 'Object Types' ?>
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
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'object_type',
                            'name',
                            'sort_order',
                            'is_active',
                        ],
                    ]) ?>
                    <p>
                        <?php if (FHtml::isInRole('', 'update', $currentRole)) {
                            Html::a(FHtml::t('common', 'Update'), ['update', 'id' => $model->object_type], ['class' => 'btn btn-primary']);
                        } ?>
                        <?php if (FHtml::isInRole('', 'delete', $currentRole)) {
                            Html::a(FHtml::t('common', 'Delete'), ['delete', 'id' => $model->object_type], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => FHtml::t('common', 'Are you sure to delete?'),
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
