<?php
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\switchinput\SwitchInput;
use common\components\FHtml;

$moduleName = 'ObjectSetting';
$moduleTitle = FHtml::t('common', 'Object Setting');
$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole('', FHtml::currentAction(), $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);
$object_type = FHtml::getRequestParam('object_type');

/* @var $this yii\web\View */
/* @var $model backend\modules\system\models\ObjectSetting */
/* @var $form yii\widgets\ActiveForm */
?>

<?php if (!Yii::$app->request->isAjax) {
    $this->params['mainIcon'] = 'fa fa-list';
    $this->params['toolBarActions'] = array(
        'linkButton'=>array(),
        'button'=>array(),
        'dropdown'=>array(),
    );
} ?>
<?php if (Yii::$app->request->isAjax) { ?>

    <?php $form = \common\widgets\FActiveForm::begin(
        ['id' => 'object-setting-form',
            'type' => $this->params['activeForm_type'],
//ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
            'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
            'staticOnly' => false, // check the Role here
            'readonly' => false, // check the Role here
            'options' => [//'class' => 'form-horizontal',
//'enctype' => 'multipart/form-data'
            ]]); ?>

    <input type="hidden" id="saveType" name="saveType">

    <?= $form->field($model, 'object_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'meta_key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'value')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'icon')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'color')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_active')->widget(SwitchInput::classname(), []) ?>

    <?= $form->field($model, 'sort_order')->textInput() ?>

    <?= $form->field($model, 'application_id')->textInput(['maxlength' => true]) ?>

    <?php \common\widgets\FActiveForm::end(); ?>


<?php } else { ?>

    <div class="object-setting-form">

                <?php $form = \common\widgets\FActiveForm::begin(
                    ['id' => 'object-setting-form',
                        'type' => ActiveForm::TYPE_VERTICAL,
                        //ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
                        'formConfig' => ['labelSpan' => 0, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
                        'staticOnly' => false, // check the Role here
                        'readonly' => false, // check the Role here
                        'options' => [
                            //'class' => 'form-horizontal',
                            'enctype' => 'multipart/form-data'
                        ]]);
                ?>

        <div class="form">
            <div class="row">

                <div class="col-md-10">
                    <div class="portlet light">
                        <div class="portlet-title tabbable-line">
                            <div class="caption caption-md">
                                <i class="icon-globe theme-font hide"></i>
                                <span class="caption-subject font-blue-madison uppercase"> <b><?= FHtml::t('common', $object_type) ?> </b> / <?= FHtml::t('common', $moduleTitle) ?></span>
                            </div>
                            <div class="tools pull-right">
                                <a href="#" class="fullscreen"></a>
                                <a href="#" class="collapse"></a>
                            </div>
                            <ul class="nav nav-tabs">
                                <li class="<?= FHtml::currentController() == 'object-type' ? 'active' : '' ?>">
                                    <a href="<?= FHtml::createUrl('object-type/update', ['id' => $object_type]) ?>"><?= FHtml::t('common', 'Info') ?></a>
                                </li>
                                <li class="<?= FHtml::currentController() == 'object-setting' ? 'active' : ''  ?>">
                                    <a href="<?= FHtml::createUrl('object-setting/index', ['object_type' => $object_type]) ?>"><?= FHtml::t('common', 'Settings') ?></a>
                                </li>
                                <li class="<?= FHtml::currentController() == 'object-category' ? 'active' : '' ?>">
                                    <a href="<?= FHtml::createUrl('object-category/index', ['object_type' => $object_type]) ?>"><?= FHtml::t('common', 'Categories') ?></a>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div>
                                <?= $form->field($model, 'object_type')->hiddenInput()->label(false) ?>
                                <div class="margin-top-20 profile-desc-link">
                                    <?= $form->field($model, 'meta_key')->textInput(['readonly' => !$model->isNewRecord]) ?>
                                </div>
                            </div>
                            <div class="form">
                                <div class="form-body">
                                    <div class="tab-content">
                                        <div class="tab-pane active row" id="tab_1_1">
                                            <div class="col-md-12">
                                                <?= $form->field($model, 'Items')->widget(\unclead\multipleinput\MultipleInput::className(), [
                                                    'columns' => [
                                                        [
                                                            'name'  => 'key',
                                                            'enableError' => true,
                                                            'title' => 'Key',
                                                            'options' => [
                                                                'class' => 'input-priority'
                                                            ]
                                                        ],
                                                        [
                                                            'name'  => 'value',
                                                            'enableError' => true,
                                                            'title' => 'Value',
                                                            'options' => [
                                                                'class' => 'input-priority'
                                                            ]
                                                        ],
                                                      /*  [
                                                            'name'  => 'icon',
                                                            'enableError' => true,
                                                            'title' => 'Icon',
                                                            'options' => [
                                                                'class' => 'input-priority'
                                                            ]
                                                        ],*/
                                                    ]
                                                ]);
                                                ?>

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


                <div class="">
                    <?= Html::submitButton($model->isNewRecord ? FHtml::t('common', 'Create')
                        : FHtml::t('common', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' :
                        'btn btn-primary']) ?>
                    <?php  if (!$model->isNewRecord) {?>
                        <?=  Html::a(FHtml::t('common', 'Delete'), ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]); ?>
                        <?=  Html::a(FHtml::t('common', 'Cancel'), ['index', 'object_type' => $object_type], ['class' => 'btn btn-default']) ?>
                    <?php } else { ?>
                        <?=  Html::a(FHtml::t('common', 'Cancel'), ['index', 'object_type' => $object_type], ['class' => 'btn btn-default']) ?>
                    <?php } ?>                </div>
            </div>
                <div class="profile-sidebar col-md-2">
                    <!-- END MENU -->
                    <div class="portlet light">
                        <?php echo FHtml::buildTabs('object_type', 'nav'); ?>
                    </div>
                </div>
        </div>





                <?php \common\widgets\FActiveForm::end(); ?>
            </div>

<?php } ?>


