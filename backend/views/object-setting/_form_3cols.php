<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\FHtml;

$moduleName = 'ObjectSetting';
$moduleTitle = FHtml::t('common', 'Object Setting');
$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole('', FHtml::currentAction(), $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);
$object_type = isset($model) ? $model->object_type : FHtml::getRequestParam('object_type');

/* @var $this yii\web\View */
/* @var $model backend\modules\system\models\ObjectSetting */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="object-setting-form">


    <?php $form = \common\widgets\FActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]
    ); ?>
    <div class="form">
        <div class="row">

            <div class="col-md-10">
                <div class="portlet light">
                    <div>
                        <div class="margin-top-20 profile-desc-link">
                            <?= $form->field($model, 'object_type')->hiddenInput()->label(false) ?>
                        </div>
                        <div class="margin-top-20 profile-desc-link">
                            <?= $form->field($model, 'meta_key')->hiddenInput()->label(false) ?>
                        </div>
                    </div>
                    <div class="portlet-title tabbable-line">
                        <div class="caption caption-md">
                            <i class="icon-globe theme-font hide"></i>
                            <span
                                class="caption-subject font-blue-madison uppercase"> <b><?= FHtml::t('common', $object_type) ?> </b> / <?= FHtml::t('common', $moduleTitle) ?></span>
                        </div>
                        <div class="tools pull-right">
                            <a href="#" class="fullscreen"></a>
                            <a href="#" class="collapse"></a>
                        </div>
                        <ul class="nav nav-tabs">
                            <li class="<?= FHtml::currentController() == 'object-type' ? 'active' : '' ?>">
                                <a href="<?= FHtml::createUrl('object-type/update', ['id' => $object_type]) ?>"><?= FHtml::t('common', 'Info') ?></a>
                            </li>
                            <li class="<?= FHtml::currentController() == 'object-setting' ? 'active' : '' ?>">
                                <a href="<?= FHtml::createUrl('object-setting/index', ['object_type' => $object_type]) ?>"><?= FHtml::t('common', 'Settings') ?></a>
                            </li>
                            <li class="<?= FHtml::currentController() == 'object-category' ? 'active' : '' ?>">
                                <a href="<?= FHtml::createUrl('object-category/index', ['object_type' => $object_type]) ?>"><?= FHtml::t('common', 'Categories') ?></a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="form">
                            <div class="form-body">
                                <div class="tab-content">
                                    <div class="tab-pane active row" id="tab_1_1">
                                        <div class="col-md-12">
                                            <?= $form->field($model, 'key')->textInput(['maxlength' => true]) ?>
                                            <?= $form->field($model, 'value')->textarea() ?>
                                            <?= $form->field($model, 'description')->textarea() ?>
                                            <?= $form->field($model, 'icon')->file() ?>
                                            <?= $form->field($model, 'color')->widget(\kartik\widgets\ColorInput::className()) ?>
                                            <?= $form->field($model, 'is_active')->widget(\kartik\widgets\SwitchInput::className(), []) ?>
                                        </div>
                                    </div>
                                    <div class="tab-pane row" id="tab_1_2">
                                        <div class="col-md-12">

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <?php if ($canEdit) {
                        echo Html::submitButton($model->isNewRecord ? FHtml::t('common', 'Create')
                            : FHtml::t('common', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' :
                            'btn btn-primary']);
                    } ?>
                    <?php if (!$model->isNewRecord && $canDelete) { ?>
                        <?= Html::a(FHtml::t('common', 'Delete'), ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => FHtml::t('common', 'Are you sure to delete ?'),
                                'method' => 'post',
                            ],
                        ]); ?>
                        <?= Html::a(FHtml::t('common', 'Cancel'), ['index', 'object_type' => $object_type], ['class' => 'btn btn-default']) ?>
                    <?php } else { ?>
                        <?= Html::a(FHtml::t('common', 'Cancel'), ['index', 'object_type' => $object_type], ['class' => 'btn btn-default']) ?>
                    <?php } ?>                            </div>
            </div>
            <div class="profile-sidebar col-md-2">
                <!-- END MENU -->
                <div class="portlet light">
                    <?php echo FHtml::buildTabs('object_type', 'nav', 'object_type'); ?>
                </div>
            </div>
        </div>
    </div>

    <?php \common\widgets\FActiveForm::end(); ?>

</div>