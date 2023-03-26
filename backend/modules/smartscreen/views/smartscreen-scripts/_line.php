<?php
use common\components\FHtml;
use backend\modules\smartscreen\models\SmartscreenScripts;

$field_value = FHtml::getFieldValue($model, $attribute);

$arr = \backend\modules\smartscreen\Smartscreen::getScriptCommandLineArray($field_value);
$command = $arr['command'];
$duration = $arr['duration'];
$index = $arr['index'];
$mode = $arr['mode'];
?>

<div class="row">
    <div class="col-md-3">
        <select class='form-control' id='<?= $id ?>_command' name='<?= $formName ?>[<?= $attribute?>_command]' >
            <option value=""><div style="color:grey"><?= FHtml::t('common', '- Select Command -') ?></div></option>
            <option value="<?= SmartscreenScripts::COMMAND_DSPTEXT ?>" <?= $command == SmartscreenScripts::COMMAND_DSPTEXT ? 'selected' : '' ?> ><?= FHtml::t('common', 'Display Text') ?></option>
            <option value="<?= SmartscreenScripts::COMMAND_DSPBASICFORM ?>" <?= $command == SmartscreenScripts::COMMAND_DSPBASICFORM ? 'selected' : '' ?>><?= FHtml::t('common', 'Basic Queue Info') ?></option>
            <option value="<?= SmartscreenScripts::COMMAND_DSPCLIP ?>" <?= $command == SmartscreenScripts::COMMAND_DSPCLIP ? 'selected' : '' ?>><?= FHtml::t('common', 'Display Clip') ?></option>
            <option value="<?= SmartscreenScripts::COMMAND_DSPIE ?>" <?= $command == SmartscreenScripts::COMMAND_DSPIE ? 'selected' : '' ?>><?= FHtml::t('common', 'Display Website') ?></option>
            <option value="<?= SmartscreenScripts::COMMAND_LOOP ?>" <?= $command == SmartscreenScripts::COMMAND_LOOP ? 'selected' : '' ?>><?= FHtml::t('common', 'Loop from Line') ?></option>
        </select>
    </div>
    <div class="col-md-4">
        <input value="<?= $index ?>"  class='form-control' placeholder="<?= FHtml::t('common', 'Index/ Weblink') ?>" id='<?= $id ?>_index' name='<?= $formName ?>[<?= $attribute?>_index]' />
    </div>

    <div class="col-md-2">
        <input value="<?= $duration ?>" class='form-control' placeholder="<?= FHtml::t('common', 'Duration (s)') ?> " id='<?= $id ?>_duration' name='<?= $formName ?>[<?= $attribute?>_duration]' />
    </div>
    <div class="col-md-3">
        <select class='form-control' id='<?= $id ?>_mode' name='<?= $formName ?>[<?= $attribute?>_mode]' >
            <option value=""><div style="color:grey"><?= FHtml::t('common', '- Select Mode -') ?></div></option>

            <option value="w" <?= $mode == 'w' ? 'selected' : '' ?>><?= FHtml::t('common', 'Window') ?></option>
            <option value="f" <?= $command == 'f' ? 'selected' : '' ?>><?= FHtml::t('common', 'Full screen') ?></option>
        </select>
    </div>

</div>
<div class="row">
    <?= $form->field($model, $attribute)->textInput()->label(null)->readonly() ?>
</div>
