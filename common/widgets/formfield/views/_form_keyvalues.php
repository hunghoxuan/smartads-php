<?php
use common\components\FHtml;
use unclead\multipleinput\MultipleInput;
?>

<?php
$type = '';
$field_name = isset($field_name) ? $field_name : 'content';

?>

<?php if ($canEdit) { ?>

    <div class="">
        <div class="col-md-12">
            <?= $form->field($model, $field_name)->widget(MultipleInput::className(), [
                'min' => 0,
                'addButtonPosition' => MultipleInput::POS_FOOTER, // show add button in the header
                'columns' => [
                    [
                        'name' => 'key',
                        'enableError' => true,
                        'title' => FHtml::t('common', 'Key'),
                        'options' => [
                            'style' => 'border:none;border-bottom:dashed 1px lightgray',
                        ],
                        'headerOptions' => [
                            'style' => 'border:none',
                            'class' => 'col-md-4'
                        ]
                    ],
                    [
                        'name' => 'value',
                        'enableError' => true,
                        'title' => FHtml::t('common', 'Value'),
                        'options' => [
                            'class' => 'col-md-8'
                        ],
                        'headerOptions' => [
                            'style' => 'border:none',
                        ]
                    ]
                ]
            ])->label(false);
            ?>
        </div>
    </div>

<?php } else { ?>

<?php } ?>


