<?php
use common\components\FHtml;
use common\components\Helper;
use unclead\multipleinput\MultipleInput;
use yii\helpers\Url;

?>

<?php
$type = '';
if (!isset($canEdit) && in_array(FHtml::currentAction(), ['view']))
    $canEdit = false;

$open = isset($open) ? $open : false;
?>


<?php if ($canEdit) { ?>
    <?= \common\widgets\FFormTable::widget(['model' => $model, 'title' => isset($title) ? $title : "SEO" , 'form' => $form, 'columns' => 1, 'open' => $open, 'attributes' => [
        'keywords' => ['value' => $form->fieldNoLabel($model, 'keywords')->textarea(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
        'page_title' => ['value' => $form->fieldNoLabel($model, 'page_title')->textarea(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
        'page_description' => ['value' => $form->fieldNoLabel($model, 'page_description')->textarea(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
        'page_image' => ['value' => $form->fieldNoLabel($model, 'page_image')->image(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],

    ]]); ?>

<?php } ?>






