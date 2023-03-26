<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\switchinput\SwitchInput;
use kartik\widgets\Typeahead;
use common\components\FHtml;

use kartik\checkbox\CheckboxX;

$form_Type = $this->params['activeForm_type'];
$moduleName = 'ObjectSetting';
$moduleTitle = 'Object Setting';
$moduleKey = 'object-setting';

$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole('', FHtml::currentAction(), $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);

/* @var $this yii\web\View */
/* @var $model backend\modules\system\models\ObjectSetting */
/* @var $form yii\widgets\ActiveForm */
?>

<?php if (!Yii::$app->request->isAjax) {
    $this->title = FHtml::t($moduleTitle);
    $this->params['mainIcon'] = 'fa fa-list';
    $this->params['toolBarActions'] = array(
        'linkButton' => array(),
        'button' => array(),
        'dropdown' => array(),
    );
} ?>
<?php if (Yii::$app->request->isAjax) { ?>

    <?php $form = \common\widgets\FActiveForm::begin(
        ['id' => 'object-setting-form',
            'type' => $form_Type, //ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
            'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
            'staticOnly' => false, // check the Role here
            'readonly' => false, // check the Role here
            'options' => [//'class' => 'form-horizontal',
//'enctype' => 'multipart/form-data'
            ]]); ?>

    <input type="hidden" id="saveType" name="saveType">

    <?= //name: object_type, dbType: varchar(255), phpType: string, size: 255, allowNull:
    $form->field($model, 'object_type')->dropDownList(FHtml::getComboArray('object_setting.object_type', 'object_setting', 'object_type', true, 'id', 'name'), ['prompt' => '']) ?>

    <?= //name: meta_key, dbType: varchar(255), phpType: string, size: 255, allowNull:
    $form->field($model, 'meta_key')->textInput(['maxlength' => true]) ?>

    <?= //name: key, dbType: varchar(255), phpType: string, size: 255, allowNull:
    $form->field($model, 'key')->textInput(['maxlength' => true]) ?>

    <?= //name: value, dbType: varchar(255), phpType: string, size: 255, allowNull:
    $form->field($model, 'value')->textInput(['maxlength' => true]) ?>

    <?= //name: description, dbType: text, phpType: string, size: , allowNull: 1
    $form->field($model, 'description')->textarea(['rows' => 3]) ?>

    <?= //name: icon, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
    $form->field($model, 'icon')->textInput(['maxlength' => true]) ?>

    <?= //name: color, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
    $form->field($model, 'color')->widget(\kartik\widgets\ColorInput::className(), ['pluginOptions' => []]) ?>

    <?= //name: is_active, dbType: tinyint(1), phpType: integer, size: 1, allowNull:
    $form->field($model, 'is_active')->widget(\kartik\widgets\CheckboxX::className(), ['pluginOptions' => ['theme' => 'krajee-flatblue', 'size' => 'md', 'threeState' => false]]) ?>

    <?= //name: sort_order, dbType: int(5), phpType: integer, size: 5, allowNull:
    $form->field($model, 'sort_order')->widget(\kartik\widgets\TouchSpin::className(), ['pluginOptions' => ['initval' => 1, 'min' => 0, 'max' => 10000000, 'step' => 1, 'decimals' => 0, 'verticalbuttons' => true, 'verticalupclass' => 'glyphicon glyphicon-plus', 'verticaldownclass' => 'glyphicon glyphicon-minus', 'prefix' => '', 'postfix' => '']]) ?>

    <?= //name: application_id, dbType: varchar(255), phpType: string, size: 255, allowNull:
    $form->field($model, 'application_id')->textInput(['maxlength' => true]) ?>

    <?php \common\widgets\FActiveForm::end(); ?>


<?php } else { ?>

    <div class="object-setting-form">
        <div class="<?= $this->params['portletStyle'] ?>">
            <div class="portlet-title hidden-print">
                <div class="caption-title uppercase font-dark">
                <span class="caption-subject bold uppercase">
                    <i class="<?php echo $this->params['mainIcon'] ?>"></i>
                    <?= FHtml::t('common', $moduleTitle) ?></span>
                    <span
                        class="caption-helper"><?= ($model->isNewRecord) ? FHtml::t('common', 'title.create') : FHtml::t('common', 'title.update') ?></span>
                </div>
                <div class="tools">
                    <a href="#" class="fullscreen"></a>
                    <a href="#" class="collapse"></a>
                </div>
                <div class="actions">
                </div>
            </div>
            <div class="portlet-body form">
                <?php $form = \common\widgets\FActiveForm::begin([
                    'id' => 'object-setting-form',
                    'type' => $form_Type, //ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
                    'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
                    'staticOnly' => false, // check the Role here
                    'readonly' => !$canEdit, // check the Role here
                    'options' => [
                        //'class' => 'form-horizontal',
                        'enctype' => 'multipart/form-data'
                    ]
                ]);
                ?>


                <div class="form">
                    <div class="form-body">

                        <?= //name: object_type, dbType: varchar(255), phpType: string, size: 255, allowNull:
                        $form->field($model, 'object_type')->dropDownList(FHtml::getComboArray('object_setting.object_type', 'object_setting', 'object_type', true, 'id', 'name'), ['prompt' => '']) ?>

                        <?= //name: meta_key, dbType: varchar(255), phpType: string, size: 255, allowNull:
                        $form->field($model, 'meta_key')->textInput(['maxlength' => true]) ?>

                        <?= //name: key, dbType: varchar(255), phpType: string, size: 255, allowNull:
                        $form->field($model, 'key')->textInput(['maxlength' => true]) ?>

                        <?= //name: value, dbType: varchar(255), phpType: string, size: 255, allowNull:
                        $form->field($model, 'value')->textInput(['maxlength' => true]) ?>

                        <?= //name: description, dbType: text, phpType: string, size: , allowNull: 1
                        $form->field($model, 'description')->textarea(['rows' => 3]) ?>

                        <?= //name: icon, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
                        $form->field($model, 'icon')->textInput(['maxlength' => true]) ?>

                        <?= //name: color, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
                        $form->field($model, 'color')->widget(\kartik\widgets\ColorInput::className(), ['pluginOptions' => []]) ?>

                        <?= //name: is_active, dbType: tinyint(1), phpType: integer, size: 1, allowNull:
                        $form->field($model, 'is_active')->widget(\kartik\widgets\CheckboxX::className(), ['pluginOptions' => ['theme' => 'krajee-flatblue', 'size' => 'md', 'threeState' => false]]) ?>

                        <?= //name: sort_order, dbType: int(5), phpType: integer, size: 5, allowNull:
                        $form->field($model, 'sort_order')->widget(\kartik\widgets\TouchSpin::className(), ['pluginOptions' => ['initval' => 1, 'min' => 0, 'max' => 10000000, 'step' => 1, 'decimals' => 0, 'verticalbuttons' => true, 'verticalupclass' => 'glyphicon glyphicon-plus', 'verticaldownclass' => 'glyphicon glyphicon-minus', 'prefix' => '', 'postfix' => '']]) ?>


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
                            <?= Html::a(FHtml::t('common', 'Cancel'), ['index'], ['class' => 'btn btn-default']) ?>
                        <?php } else { ?>
                            <?= Html::a(FHtml::t('common', 'Cancel'), ['index'], ['class' => 'btn btn-default']) ?>
                        <?php } ?>                </div>
                </div>
                <?php \common\widgets\FActiveForm::end(); ?>
            </div>

        </div>
    </div>
<?php } ?>


