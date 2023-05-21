<?php

/**
 * 

 * 
 * This is the customized model class for table "SmartscreenScripts".
 */

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use common\widgets\FActiveForm;
use kartik\switchinput\SwitchInput;
use kartik\widgets\Typeahead;
use common\components\FHtml;
use kartik\checkbox\CheckboxX;
use common\widgets\FCKEditor;
use yii\widgets\MaskedInput;
use common\widgets\formfield\FormObjectFileAnpx1;
use kartik\slider\Slider;
use common\widgets\formfield\FormObjectFile;
use common\widgets\formfield\FormObjectAttributes;
use common\widgets\formfield\FormRelations;

$form_Type = $this->params['activeForm_type'];

$moduleName = 'SmartscreenScripts';
$moduleTitle = 'Smartscreen Scripts';
$moduleKey = 'smartscreen-scripts';

$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole($moduleName, 'update', $currentRole);
$canDelete = FHtml::isInRole($moduleName, 'delete', $currentRole);

/* @var $this yii\web\View */
/* @var $model backend\modules\smartscreen\models\SmartscreenScripts */
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


<?php $form = FActiveForm::begin([
    'id' => 'smartscreen-scripts-form',
    'type' => $form_Type, //ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
    'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
    'staticOnly' => false, // check the Role here
    'readonly' => !$canEdit, // check the Role here
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
    'options' => [
        //'class' => 'form-horizontal',
        'enctype' => 'multipart/form-data'
    ]
]);
?>


<div class="form">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light">
                <div class="portlet-title tabbable-line">
                    <div class="caption caption-md">
                        <i class="icon-globe theme-font hide"></i>
                        <span class="caption-subject font-blue-madison bold uppercase"><?= FHtml::t('common', $moduleTitle) ?></span>
                    </div>
                    <div class="tools pull-right">
                        <a href="#" class="fullscreen"></a>
                        <a href="#" class="collapse"></a>
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_1_1" data-toggle="tab"><?= FHtml::t('common', 'Form') ?></a>
                        </li>
                        <li>
                            <a href="#tab_1_2" data-toggle="tab"><?= FHtml::t('common', 'Clips') ?></a>
                        </li>
                        <li>
                            <a href="#tab_1_3" data-toggle="tab"><?= FHtml::t('common', 'Commands') ?></a>
                        </li>
                        <li>
                            <a href="#tab_1_4" data-toggle="tab"><?= FHtml::t('common', 'Scripts') ?></a>
                        </li>
                    </ul>
                </div>
                <div class="portlet-body form">
                    <div class="form">
                        <div class="form-body">
                            <div class="tab-content">
                                <div class="tab-pane active row" id="tab_1_1">
                                    <div class="col-md-12">
                                        <?= $form->field($model, 'name')->textInput() ?>

                                        <?= $form->field($model, 'Logo')->image() ?>

                                        <?= $form->field($model, 'TopBanner')->image() ?>

                                        <?= $form->field($model, 'BotBanner')->image() ?>

                                        <?= $form->field($model, 'ClipHeader')->video() ?>

                                        <?= $form->field($model, 'ClipFooter')->video() ?>

                                        <?= $form->field($model, 'ScrollText')->textarea(['rows' => 6]) ?>

                                        <?= $form->field($model, 'ReleaseDate')->datetime() ?>
                                        <?= $form->field($model, 'is_active')->checkbox() ?>

                                    </div>
                                </div>

                                <div class="tab-pane row" id="tab_1_2">
                                    <div class="col-md-12">
                                        <?= $form->field($model, 'Clip1')->file() ?>

                                        <?= $form->field($model, 'Clip2')->file() ?>

                                        <?= $form->field($model, 'Clip3')->file() ?>

                                        <?= $form->field($model, 'Clip4')->file() ?>

                                        <?= $form->field($model, 'Clip5')->file() ?>

                                        <?= $form->field($model, 'Clip6')->file() ?>

                                        <?= $form->field($model, 'Clip7')->file() ?>

                                        <?= $form->field($model, 'Clip8')->file() ?>

                                        <?= $form->field($model, 'Clip9')->file() ?>

                                        <?= $form->field($model, 'Clip10')->file() ?>

                                        <?= $form->field($model, 'Clip11')->file() ?>

                                        <?= $form->field($model, 'Clip12')->file() ?>

                                        <?= $form->field($model, 'Clip13')->file() ?>

                                        <?= $form->field($model, 'Clip14')->file() ?>

                                    </div>
                                </div>

                                <div class="tab-pane row" id="tab_1_3">
                                    <div class="col-md-12">
                                        <?= $form->field($model, 'Line1')->renderView("_line")->hintLabel('') ?>

                                        <?= $form->field($model, 'Line2')->renderView("_line") ?>

                                        <?= $form->field($model, 'Line3')->renderView("_line") ?>

                                        <?= $form->field($model, 'Line4')->renderView("_line") ?>

                                        <?= $form->field($model, 'Line5')->renderView("_line") ?>

                                        <?= $form->field($model, 'Line6')->renderView("_line") ?>

                                        <?= $form->field($model, 'Line7')->renderView("_line") ?>

                                        <?= $form->field($model, 'Line8')->renderView("_line") ?>

                                        <?= $form->field($model, 'Line9')->renderView("_line") ?>

                                        <?= $form->field($model, 'Line10')->renderView("_line") ?>

                                        <?= $form->field($model, 'Line11')->renderView("_line") ?>

                                        <?= $form->field($model, 'Line12')->renderView("_line") ?>

                                        <?= $form->field($model, 'Line13')->renderView("_line") ?>

                                        <?= $form->field($model, 'Line14')->renderView("_line") ?>

                                        <?= $form->field($model, 'Line15')->renderView("_line") ?>

                                        <?= $form->field($model, 'Line16')->renderView("_line") ?>

                                    </div>
                                </div>

                                <div class="tab-pane row" id="tab_1_4">
                                    <div class="col-md-12">
                                        <?= $form->field($model, 'scripts_content')->textarea(['rows' => 30]) ?>
                                        <?= $form->field($model, 'scripts_file')->file() ?>

                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <?php $type = FHtml::getFieldValue($model, 'type');
            if (isset($modelMeta) && !empty($type))
                echo FHtml::render('..\\' . $moduleKey . '-' . $type . '\\_form.php', '', ['model' => $modelMeta, 'display_actions' => false, 'canEdit' => $canEdit, 'canDelete' => $canDelete]);
            ?>
            <script language="javascript" type="text/javascript">
                function submitForm($saveType) {
                    $('#saveType').val($saveType);
                }
            </script>

            <?php if (Yii::$app->request->isAjax) { ?>

                <input type="hidden" id="saveType" name="saveType">

            <?php } else { ?>
                <input type="hidden" id="saveType" name="saveType">

                <?= FHtml::showActionsButton($model, $canEdit, $canDelete) ?>
            <?php } ?>
        </div>
    </div>
</div>
<?php FActiveForm::end(); ?>