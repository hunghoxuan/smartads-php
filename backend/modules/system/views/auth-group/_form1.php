<?php
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\switchinput\SwitchInput;
use common\components\FHtml;

/* @var $this yii\web\View */
/* @var $model backend\models\AuthGroup */
/* @var $form yii\widgets\ActiveForm */

?>

<?php if (!Yii::$app->request->isAjax) {
    $this->title = Yii::t('auth', 'Auth Group');
    $this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'index'];
    $this->params['breadcrumbs'][] = ($model->isNewRecord) ? Yii::t('auth', 'Create') : Yii::t('auth', 'Update');
    $this->params['mainIcon'] = 'fa fa-list';
    $this->params['toolBarActions'] = array(
        'linkButton' => array(),
        'button' => array(),
        'dropdown' => array(),
    );
} ?>
<?php if (Yii::$app->request->isAjax) { ?>

    <?php $form = ActiveForm::begin(
        [
            'id' => 'auth-group-form',
            'type' => $this->params['activeForm_type'],//ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
            'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
            'staticOnly' => false, // check the Role here
            'readonly' => false, // check the Role here
            'options' => [
                //'class' => 'form-horizontal',
            ]
        ]); ?>

    <input type="hidden" id="saveType" name="saveType">

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_active')->textInput() ?>

      <?php ActiveForm::end(); ?>


<?php } else { ?>

    <div class="auth-group-form">
        <div class="<?= $this->params['portletStyle'] ?>">
            <div class="portlet-title hidden-print">
                <div class="caption-title uppercase font-dark">
                <span class="caption-subject bold uppercase">
                    <i class="<?php echo $this->params['mainIcon'] ?>"></i>
                    <?= Yii::t('auth', 'Auth Group') ?></span>
                    <span class="caption-helper"><?= ($model->isNewRecord) ? Yii::t('common', 'title.create') : Yii::t('common', 'title.update') ?></span>
                </div>
                <div class="tools">
                    <a href="#" class="collapse"></a>
                    <a href="#" class="fullscreen"></a>
                </div>
                <div class="actions">
                </div>
            </div>
            <div class="portlet-body form">
                <?php $form = \common\widgets\FActiveForm::begin([
                    'id' => 'auth-group-form',
                    'type' => $this->params['activeForm_type'],//ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
                    'formConfig' => ['labelSpan' => 2, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
                    'staticOnly' => false, // check the Role here
                    'readonly' => false, // check the Role here
                    'options' => [
                        //'class' => 'form-horizontal',
                    ]
                ]);
                ?>


                <div class="form">
                    <div class="form-body">
                        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'rights')->widget(Select2::classname(), [
                            'data' => \common\components\FHtml::getApplicationRolesComboArray(),
                            'options' => [
                                'multiple' => true,
                                'placeholder' => 'Select roles. Input key to search ...',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]); ?>
                        <?= $form->field($model, 'users')->widget(Select2::classname(), [
                            'data' => \common\components\FHtml::getApplicationUsersComboArray(),
                            'options' => [
                                'multiple' => true,
                                'placeholder' => 'Select users. Input key to search ...',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]); ?>
                        <?= $form->field($model, 'is_active')->widget(SwitchInput::classname(), ['containerOptions' => ['class' => '']]) ?>

                    </div>
                    <div class="form-actions">
                        <?= Html::submitButton($model->isNewRecord ? Yii::t('common', 'Create')
                            : Yii::t('common', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' :
                            'btn btn-primary']) ?>
                        <?php if (!$model->isNewRecord) { ?>
                            <?= Html::a(Yii::t('common', 'Delete'), ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger pull-right',
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this item?',
                                    'method' => 'post',
                                ],
                            ]); ?>
                            <?= Html::a(Yii::t('common', 'Cancel'), ['index'], ['class' => 'btn btn-default']) ?>
                        <?php } else { ?>
                            <?= Html::a(Yii::t('common', 'Cancel'), ['index'], ['class' => 'btn btn-default']) ?>
                        <?php } ?>                </div>
                </div>
                <?php \common\widgets\FActiveForm::end(); ?>
            </div>

        </div>
    </div>
<?php } ?>


