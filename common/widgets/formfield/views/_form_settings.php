<?php
use common\components\FHtml;
use unclead\multipleinput\MultipleInput;
use yii\helpers\Url;

?>

<?php
$type = '';
$field_name = empty($field_name) ? 'ObjectSettings' : $field_name;
?>

<?php if ($canEdit) { ?>

    <div class="">
        <div class="col-md-12">
            <?= $form->field($model, $field_name)->widget(MultipleInput::className(), [
                'limit' => 50,
                'min' => 0,
                'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header
                'columns' => [
                    [
                        'name' => 'key',
                        'enableError' => true,
                        'title' => FHtml::t('common', 'Custom Field'),
                        'options' => [
                            'style' => 'border:none;border-bottom:dashed 1px lightgray',
                        ],
                        'headerOptions' => [
                            'style' => 'border:none',
                            'class' => 'col-md-2'
                        ]
                    ],
                    [
                        'name' => 'value',
                        'enableError' => true,
                        'title' => FHtml::t('common', 'Value'),
                        'options' => [
                            'class' => 'col-md-3'
                        ],
                        'headerOptions' => [
                            'style' => 'border:none',
                        ]
                    ],
                    [
                        'name' => 'description',
                        'enableError' => true,
                        'title' => FHtml::t('common', 'Description'),
                        'options' => [
                            'class' => 'col-md-3'
                        ],
                        'headerOptions' => [
                            'style' => 'border:none',
                        ]
                    ],
                    [
                        'name' => 'is_active',
                        'enableError' => true,
                        'title' => FHtml::t('common', 'Is Active'),
                        'options' => [
                            'class' => 'col-md-1'
                        ],
                        'headerOptions' => [
                            'style' => 'border:none',
                        ]
                    ],
                    [
                        'name' => 'icon',
                        'title' => FHtml::t('common', 'Icon/Image'),
                        'options' => [
                            'class' => 'col-md-1',
                        ],
                        'headerOptions' => [
                            'style' => 'border:none;',
                        ]
                    ],

                    [
                        'name' => 'icon_upload',
                        'type' => \kartik\widgets\FileInput::className(),
                        'options' => [
                            'pluginOptions' => ['browseLabel' => '', 'removeLabel' => '', 'model' => $model, 'maxFileSize' => FHtml::settingMaxFileSize(), 'options' => ['class' => 'small_size', 'multiple' => false], 'showPreview' => false, 'showCaption' => false, 'showRemove' => true, 'showUpload' => false, 'pluginOptions' => ['previewFileType' => 'any', 'uploadUrl' => Url::to([FHtml::config('UPLOAD_FOLDER', '/site/file-upload')])]]
                        ],
                        'headerOptions' => [
                            'style' => 'border:none;width:50px',
                        ]
                    ],
                ]
            ])->label(false);
            ?>
        </div>
    </div>

<?php } else { ?>

<?php } ?>


