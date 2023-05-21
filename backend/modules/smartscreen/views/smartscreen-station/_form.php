<?php

/*
 * This is the customized model class for table "SmartscreenStation".
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
use kartik\money\MaskMoney;
use kartik\slider\Slider;
use common\widgets\formfield\FormObjectFile;
use common\widgets\formfield\FormObjectAttributes;
use common\widgets\formfield\FormRelations;

$form_Type = $this->params['activeForm_type'];

$moduleName = 'SmartscreenStation';
$moduleTitle = 'Smartscreen Station';
$moduleKey = 'smartscreen-station';

$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole($moduleName, 'update', $currentRole);
$canDelete = FHtml::isInRole($moduleName, 'delete', $currentRole);

$a = \backend\modules\smartscreen\Smartscreen::getHisContentUrl(null);
//FHtml::var_dump()


/* @var $this yii\web\View */
/* @var $model backend\modules\smartscreen\models\SmartscreenStation */
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
    'id' => 'smartscreen-station-form',
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
                </div>
                <div class="portlet-body form">
                    <div class="form">
                        <div class="form-body">
                            <div class="tab-content">
                                <div class="tab-pane active row" id="tab_1_1">
                                    <div class="col-md-12">
                                        <?= $form->field($model, 'name')->textInput() ?>
                                        <?= $form->field($model, 'description')->textInput() ?>
                                        <?= $form->field($model, 'channel_id')->dropDownList(FHtml::getComboArray('@smartscreen_channels', 'smartscreen_channels', 'channel_id', true, 'id', 'name')) ?>
                                        <?= $form->field($model, 'LicenseKey')->textInput() ?>

                                        <div style="margin-top:20px">
                                            <hr />
                                            <h4><?= FHtml::t('common', 'Device Information') ?></h4>

                                            <?= $form->field($model, 'ime')->staticInput() ?>
                                            <?= $form->field($model, 'MACAddress')->staticInput() ?>
                                            <?= $form->field($model, 'ScreenName')->staticInput() ?>
                                            <?= $form->field($model, 'last_activity')->staticInput() ?>

                                        </div>
                                        <?php if (\backend\modules\smartscreen\Smartscreen::settingHISEnabled()) { ?>
                                            <div style="margin-top:20px">
                                                <hr />
                                                <h3><?= FHtml::t('common', 'HIS Information') ?></h3>

                                                <?= $form->field($model, 'dept_id')->dropDownList(\backend\modules\smartscreen\Smartscreen::getHISDeptList()) ?>
                                                <?= $form->field($model, 'room_id')->textInput() ?>
                                                <?= $form->field($model, '_help')->label(false)->dropDownList(\backend\modules\smartscreen\Smartscreen::getHISRoomList($model->dept_id), ['value' => $model->dept_id . ':' . $model->room_id]) ?>

                                                <?= ""; // $form->field($model, 'branch_id')->dropDownList(FHtml::getComboArray('@qms_branch', 'qms_branch', 'branch_id', true, 'id', 'name')) 
                                                ?>

                                            </div>
                                        <?php } ?>
                                        <!--
                                        <div style="margin-top:20px">
                                            <hr />
                                            <h4><?= FHtml::t('common', 'Upgrade app version') ?></h4>
                                            <?= $form->field($model, '_app_version_id')->dropDownList(\backend\modules\app\models\AppVersion::findAllForCombo([], 'id', 'name', 'is_default desc, is_active desc, created_date desc')) ?>
                                            <?= $form->field($model, '_is_upgrade')->checkbox() ?>
                                        </div>
                                        -->

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

                <?= FHtml::showActionsButton($model, $canEdit, $canDelete)  ?>
            <?php } ?>
        </div>

    </div>
</div>
<?php FActiveForm::end(); ?>