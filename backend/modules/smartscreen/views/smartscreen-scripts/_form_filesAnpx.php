<?php
use common\components\FHtml;
use unclead\multipleinput\MultipleInput;
use yii\helpers\Url;

$models = $model->getSmartscreenFiles();

?>

<?php
$type = '';
?>

<?php if ($canEdit) { ?>
    <div class="row">
        <div class="col-md-12">

            <?= $form->field($model, 'list_content')->widget(MultipleInput::className(), [
                'min' => 0,
                'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header
                'columns' => [
                    [
                        'name' => 'file_upload',
                        'type' => \kartik\widgets\FileInput::className(),
                        'options' => [
                            'pluginOptions' => ['browseLabel' => '', 'removeLabel' => '', 'model' => $model, 'maxFileSize' => FHtml::settingMaxFileSize(), 'options' => ['class' => 'small_size', 'multiple' => false], 'showPreview' => false, 'showCaption' => false, 'showRemove' => true, 'showUpload' => false, 'pluginOptions' => ['previewFileType' => 'any', 'uploadUrl' => Url::to([FHtml::config('UPLOAD_FOLDER', '/site/file-upload')])]]
                        ],
                        'headerOptions' => [
                            'style' => 'border:none;width:50px',
                        ]
                    ],

                    [
                        'name' => 'file',
                        'title' => FHtml::t('common', 'File/URL'),
                        'options' => [
                            'class' => 'col-md-5',
                        ],
                        'headerOptions' => [
                            'style' => 'border:none;',
                        ]
                    ],
                    [
                        'name' => 'description',
                        'enableError' => true,
                        'title' => FHtml::t('common', 'Description'),

                        'options' => [
                            'class' => 'col-md-4',
                        ],
                        'headerOptions' => [
                            'style' => 'border:none',
                        ]
                    ],
//                    [
//                        'name'  => 'file_size',
//                        'title' => FHtml::t('common', 'File Size'),
//                        'options' => [
//                            'style' => 'width:80px',
//                        ],
//                        'headerOptions' => [
//                            'style' => 'border:none;',
//                        ]
//                    ],
//                    [
//                        'name' => 'file_type',
//                        'type'  => \unclead\multipleinput\MultipleInputColumn::TYPE_DROPDOWN,
//                        'enableError' => true,
//                        'title' => FHtml::t('common', 'Type'),
//                        'items' =>  [
//                            '' => '...',
//                            'Image' => FHtml::t('common', 'Image'),
//                            'Audio' => FHtml::t('common', 'Audio'),
//                            'Video' => FHtml::t('common', 'Video'),
//                            'File' => FHtml::t('common', 'File'),
//                            'Link' => FHtml::t('common', 'Link')
//                        ],
//                        'options' => [
//                            'disabled' => 'disabled'
//                        ],
//                        'headerOptions' => [
//                            'style' => 'width:120px',
//                        ]
//                    ],

                    [
                        'name' => 'file_duration',
                        'title' => FHtml::t('common', 'Loop'),
                        'options' => [
                            'style' => 'width:100px',
                        ],
                        'headerOptions' => [
                            'style' => 'border:none;',
                        ]
                    ],
                    [
                        'name' => 'file_kind',
                        'type'  => \unclead\multipleinput\MultipleInputColumn::TYPE_DROPDOWN,
                        'enableError' => true,
                        'title' => FHtml::t('common', ''),
                        'items' =>  [
                            '' => '...',
                            'number' => FHtml::t('common', 'Times'),
                            'time' => FHtml::t('common', 'Minutes'),
                        ],
                        'headerOptions' => [
                            'style' => 'width:120px',
                        ]
                    ],
//                    [
//                        'value' => function ($data) {
//                            return FHtml::showImage(FHtml::getFieldValue($data, 'file'), 'object-file', '80px', '50px', 'margin-top:-15px');
//                        },
//                        'type' => 'static',
//                        'name' => 'file',
//                        'enableError' => true,
//                        'title' => '',
//                        'options' => [
//                            'class' => 'no-border',
//                            'style' => 'border:none; margin-top:-10px;width:50px',
//                        ],
//                        'headerOptions' => [
//                            'style' => 'border:none; width:50px',
//                        ]
//                    ],
                    [
                        'name' => 'id',
                        'options' => [
                            'style' => 'border:none;width:0px;visible:none',
                        ],
                        'headerOptions' => [
                            'style' => 'border:none;width:0px;visible:none',
                        ]
                    ],
                    [
                        'value' => function ($data) {
                            return FHtml::showImage(FHtml::getFieldValue($data, 'file'), 'smartscreen-file', '80px', '50px', 'margin-top:-15px', 'btn btn-large', false, 'download');
                        },
                        'type' => 'static',
                        'name' => 'id',
                        'enableError' => true,
                        'title' => '',
                        'options' => [
                            'class' => 'no-border',
                            'style' => 'border:none; margin-top:-10px;width:50px',
                        ],
                        'headerOptions' => [
                            'style' => 'border:none; width:50px',
                        ],
                    ],


                ]
            ])->label(false);
            ?>
        </div>
    </div>

<?php } else { ?>


<?php } ?>



