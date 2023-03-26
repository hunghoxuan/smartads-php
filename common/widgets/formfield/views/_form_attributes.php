<?php
use common\components\FHtml;
use unclead\multipleinput\MultipleInput;

?>

<?php
$type = '';
$propertyField = $model->getPropertyField();

//if (empty($propertyField) && FHtml::isTableExisted(FHtml::TABLE_PROPERTIES))
//    $propertyField = FHtml::FIELD_PROPERTIES;

$is_properties = !empty($propertyField);

$is_object_attributes = FHtml::isTableExisted(\common\components\FModel::TABLE_ATTRIBUTES);
$properties_model = $model->getPropertiesModel();

?>

<?php if ($canEdit && ($is_properties || $is_object_attributes)) { ?>

    <div class="row">

        <?php
        if ($is_properties) {
            echo $form->field($model, $propertyField)->arrayInput(['meta_key', 'meta_value'])->label(false);
        } else if ($is_object_attributes) {
            echo $form->field($model, 'ObjectAttributes')->widget(MultipleInput::className(), [
                'min' => 0,
                'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header
                'columns' => [
                    [
                        'name' => 'meta_key',
                        'enableError' => true,
                        'title' => FHtml::t('common', 'Custom Field'),
                        'options' => [
                            'class' => 'form-label',
                            'style' => 'border:none;border-bottom:dashed 1px lightgray',
                        ],
                        'headerOptions' => [
                            'class' => 'col-md-4', 'style' => 'margin-bottom:10px'
                        ]
                    ],
                    [
                        'name' => 'meta_value',
                        'enableError' => true,
                        'type' => \unclead\multipleinput\MultipleInputColumn::TYPE_TEXT_INPUT,

                        'title' => FHtml::t('common', 'Value'),
                        'options' => [
                        ],
                        'headerOptions' => [
                            'class' => 'col-md-8'

                        ]
                    ],
                    [
                        'name' => 'id',
                        'options' => [
                            'style' => 'border:none;width:0px;visible:none',
                        ],
                        'headerOptions' => [
                            'style' => 'border:none;width:0px;visible:none',
                        ]
                    ],
                ]
            ])->label(false);
        }
        ?>
    </div>

<?php } else { ?>

<?php } ?>


