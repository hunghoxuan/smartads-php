<?php
use common\components\FHtml;
use common\components\Helper;
use unclead\multipleinput\MultipleInput;
use yii\widgets\Pjax;
use backend\modules\workflow\Workflow;
use yii\bootstrap\Modal;
use kartik\select2\Select2;
?>

<?php
if (empty($object_type) || !isset($model)) {
    echo FHtml::showErrorMessage(FHtml::t('common', "Object Type [$object_type] is not valid or not found"));
    return;
}
$field_name = isset($field_name) ? $field_name : 'users';

$data1 = \backend\modules\system\models\SettingsAlias::findComboArray(['object_type' => 'user'], 'object_id', 'name');
$data1 = \common\components\FModel::getUsersComboArray();
?>
<div class="row">
    <div class="col-md-12">
        <?= $form->fieldNoLabel($model, $field_name)->selectMany(FHtml::getUsersComboArray()); ?>
    </div>
    <div class="col-md-12">
        <a href="#" onclick="selectUsers<?=$field_name?>(120);">Select first option</a>
        <?php
        // Using a select2 widget inside a modal dialog
        Modal::begin([
            'options' => [
                'id' => 'kartik-modal',
                'tabindex' => false // important for Select2 to work properly
            ],
            'header' => null, 'footer' => null,
            'toggleButton' => ['label' => 'Show Modal', 'class' => 'btn btn-xs btn-primary'],
        ]);
        echo Select2::widget([
            'name' => 'state_40',
            'data' => $data1,
            'options' => ['placeholder' => 'Select a state ...'],
            'pluginOptions' => [
                'allowClear' => true, 'multiple' => true,
            ],
            'pluginEvents' => [
                'change' => 'function() { 
                
               selectUsers' . $field_name. '($(this).val());
             }'
            ]
        ]);
        Modal::end();
        ?>

    </div>
</div>

<script>
    function selectUsers<?=$field_name?>(value) {
        $('#workflowtransitions-users').val(value).trigger("change");
    }
</script>