<?php
/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "Settings".
 */
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\switchinput\SwitchInput;
use kartik\widgets\Typeahead;
use common\components\FHtml;
use kartik\checkbox\CheckboxX;
use common\widgets\FCKEditor;
use yii\widgets\MaskedInput;
use kartik\money\MaskMoney;
use kartik\slider\Slider;
use common\widgets\formfield\FormObjectFile;
use common\widgets\formfield\FormObjectAttributes;
use common\widgets\formfield\FormRelations;

$form_Type = $this->params['activeForm_type'];

$moduleName = 'Settings';
$moduleTitle = 'Settings';
$moduleKey = 'settings';

$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole('', FHtml::currentAction(), $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);

/* @var $this yii\web\View */
/* @var $model backend\models\Settings */
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


<?php $form = ActiveForm::begin([
    'id' => 'settings-form',
    'type' => $form_Type, //ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
    'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
    'staticOnly' => false, // check the Role here
    'readonly' => !$canEdit, // check the Role here
    'enableClientValidation' => false,
    'enableAjaxValidation' => false,
    'options' => [
        //'class' => 'form-horizontal',
        'enctype' => 'multipart/form-data'
    ]
]);
?>


<div class="form">
    <div class="row">

        <div class="col-md-10">
            <div class="portlet light">
                <div class="portlet-title tabbable-line">
                    <div class="caption caption-md">
                        <i class="icon-globe theme-font hide"></i>
                        <span
                            class="caption-subject font-blue-madison bold uppercase"><?= FHtml::t('common', $moduleTitle) ?></span>
                    </div>
                    <div class="tools pull-right">
                        <a href="#" class="fullscreen"></a>
                        <a href="#" class="collapse"></a>
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_1_1" data-toggle="tab"><?= FHtml::t('common', 'Info') ?></a>
                        </li>

                    </ul>
                </div>
                <div class="body">
                    <div class="form">
                        <div class="form-body">
                            <div class="tab-content">
                                <div class="tab-pane active row" id="tab_1_1">
                                    <div class="col-md-12">
                                        <?= //name: metaKey, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull:
                                        $form->field($model, 'metaKey')->textInput(['readonly' => true]) ?>

                                        <?= \common\widgets\formfield\FieldEdit::widget([
                                            'model' => $model, 'form' => $form, 'field' => 'metaValue', 'lookup' => $model->lookup, 'editor' => $model->editor,
                                            'canEdit' => $canEdit, 'moduleKey' => $moduleKey, 'modulePath' => 'object-file'
                                        ]) ?>

                                    </div>
                                </div>


                                <!--<div class="tab-pane row" id="tab_1_p">
                                    <div class="col-md-12">
                                                                                </div>
                                </div>
                                -->

                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!--
            <div class="portlet light">
                <div class="portlet-title tabbable-line">
                    <div class="caption caption-md">
                        <i class="icon-globe theme-font hide"></i>
                        <span
                            class="caption-subject font-blue-madison bold uppercase"><?= FHtml::t('common', 'Grouping') ?></span>
                    </div>
                </div>
                <div class="">
                    <div class="tab-content">
                        <div class="tab-pane active row" id="tab_1_1">
                            <div class="col-md-12">
                                <?= //name: editor, comment: editor:select;lookup:editor, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
                                //$form->field($model, 'editor')->dropDownList(FHtml::getComboArray('editor', 'editor', 'editor', true, 'id', 'name'), ['prompt' => ''])
                                $form->field($model, 'editor')->widget(\kartik\widgets\Select2::className(), ['data' => FHtml::getComboArray('editor', 'editor', 'editor', true, 'id', 'name'), 'options' => ['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>


                                <?= //name: metaKey, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull:
                                $form->field($model, 'lookup')->textInput() ?>

                                <?= //name: is_system, comment: , dbType: tinyint(1), phpType: integer, size: 1, allowNull: 1
                                //$form->field($model, 'is_system')->widget(\kartik\widgets\SwitchInput::className(), [])
                                $form->field($model, 'is_system')->widget(\kartik\checkbox\CheckboxX::className(), ['pluginOptions' => ['theme' => 'krajee-flatblue', 'size' => 'md', 'threeState' => false]]) ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            -->
            <!--
                <div class="portlet light">
                    <div class="portlet-title tabbable-line">
                        <div class="caption caption-md">
                            <i class="icon-globe theme-font hide"></i>
                            <span class="caption-subject font-blue-madison bold uppercase"><?= FHtml::t('common', 'Pricing') ?></span>
                        </div>
                    </div>
                    <div class="">
                        <div class="tab-content">
                            <div class="tab-pane active row" id="tab_1_1">
                                <div class="col-md-12">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                -->


            <script language="javascript" type="text/javascript">
                function submitForm($saveType) {
                    $('#saveType').val($saveType);
                }
            </script>

            <?php if (Yii::$app->request->isAjax) { ?>

                <input type="hidden" id="saveType" name="saveType">

            <?php } else { ?>
                <input type="hidden" id="saveType" name="saveType">

                <div class="">
                    <?php if ($canEdit) {
                        echo Html::submitButton('<i class="fa fa-save"></i> ' . FHtml::t('common', 'Save'), ['class' => 'btn btn-primary']);
                        echo '  ' . Html::submitButton('<i class="fa fa-copy"></i> ' . FHtml::t('common', 'Save') . ' & ' . FHtml::t('common', 'Clone'), ['class' => 'btn btn-warning', 'onclick' => 'submitForm("clone")']);
                    } ?>
                    <?php if (!$model->isNewRecord && $canDelete) { ?>
                        <?= FHtml::a('<i class="fa fa-trash"></i> ' . FHtml::t('common', 'Delete'), ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger pull-right',
                            'data' => [
                                'confirm' => FHtml::t('common', 'Are you sure to delete ?'),
                                'method' => 'post',
                            ],
                        ]); ?>
                    <?php } ?>
                    <?= ' | ' . FHtml::a('<i class="fa fa-undo"></i> ' . FHtml::t('common', 'Cancel'), ['index'], ['class' => 'btn btn-default']) ?>
                </div>
            <?php } ?>
        </div>
        <div class="col-md-2">
            <div class="">
                <?php echo '';// FHtml::buildTabs(['Config', 'Website', 'Theme', 'Backend', 'Frontend', 'All'], 'nav', 'group'); ?>
                <?php echo FHtml::buildTabs('settings#group', 'nav', 'group'); ?>

            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>




