<?php
use common\components\FHtml;
use unclead\multipleinput\MultipleInput;

?>

<?php
$type = '';
$field_name = isset($field_name) ? $field_name : 'content';
$items = isset($items) ? $items : [];
$items1 = [];
$items2 = [];
$i = 0;
foreach ($items as $item) {
    $active = ($i == 0 ? true : false);
    $i += 1;
    $items1[] = ['label' => FHtml::t('common', $item), 'url' => "#tab_" . $item, 'active' => $active, 'content' => ''];
}
$labelSpan = isset($labelSpan) ? $labelSpan : false;
?>

<?php if ($canEdit) { ?>

    <div class="row">
        <div class="col-md-12">
            <?= (empty($items)) ?
             $form->field($model, $field_name)->widget(\common\widgets\FCKEditor::className(), ['name' => $field_name, 'model' => $model, 'attribute' => $field_name])->labelSpan($labelSpan) :
                $form->field($model, $field_name)->widget(\common\widgets\FTabs::className(), [
                'id' => "$field_name-tabs",

                'renderTabContent' => false, // If each tab is displayed, it is custom DIV perhaps Render Other page files , Set as false.
                'linkOptions' => ['data-toggle' => "tab"],
                'items' => $items1
            ])->labelSpan($labelSpan);
            ?>
            <div class="tab-content">
                <!-- Modify personal information -->
                　　<?php $i = 0; foreach ($items as $item) { $active = ($i == 0 ? 'active' : ''); $i += 0;  ?>

                    <div class="tab-pane <?= $active ?>" id="tab_<?= $item ?>">
                        <?= \common\widgets\FCKEditor::widget(['name' => $field_name . "[$item]", 'model' => $model, 'attribute' => $field_name . "[$item]"]) ?>
                        　　
                    </div>
                <?php } ?>

            </div>
        </div>

    </div>


<?php } else { ?>

<?php } ?>


