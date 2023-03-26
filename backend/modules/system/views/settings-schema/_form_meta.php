<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\switchinput\SwitchInput;
use kartik\widgets\Typeahead;
use common\components\FHtml;

use common\widgets\FCKEditor;
use kartik\money\MaskMoney;
use yii\widgets\MaskedInput;
use kartik\slider\Slider;
?>

<?php
;
$type = FHtml::getRequestParam('type');
if (empty($type))
    $type = $model->type;
else
    $model->type = $type;
?>

<?php  if (isset($modelMeta)) { ?>
    <div class="portlet light">
        <div class="portlet-title tabbable-line">
            <div class="caption caption-md">
                <i class="icon-globe theme-font hide"></i>
                <span class="caption-subject font-blue-madison bold uppercase"><?= FHtml::t('common', $type)?></span>
            </div>
        </div>
        <div class="">
            <div class="tab-content">
                <div class="tab-pane active row" id="tab_1_1">
                    <div class="col-md-12">
                        <?php                         if (!empty($type)) {
                            echo $this->render('../settings-schema-'. $type .'/_fields', [
                                'model' => $modelMeta,
                                'form_Type' => $this->params['activeForm_type'],
                                'canEdit' => $canEdit
                            ]);
                        }
                        ?> 
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>



