<?php
use common\components\FHtml;
use common\components\Helper;
use unclead\multipleinput\MultipleInput;
use yii\helpers\Url;

?>

<?php
$type = '';
$models = $model->getObjectFiles();
if (in_array(FHtml::currentAction(), ['view']))
    $canEdit = false;

$object_fields = (isset($object_fields) && !empty($object_fields)) ? $object_fields : ['file_upload', 'title', 'description', 'file_type'];
$columns = [];
if (in_array('file', $object_fields))
    $columns[] = [
        'name' => 'file',
        'title' => FHtml::t('common', 'File/URL'),
        'options' => [
            'class' => 'col-md-2',
        ],
        'headerOptions' => [
            'class' => ''
        ]
    ];

if (in_array('title', $object_fields))
    $columns[] =  [
        'name' => 'title',
        'enableError' => true,
        'title' => FHtml::t('common', 'Title'),

        'options' => [
            'class' => 'col-md-2'
        ],
        'headerOptions' => [
            'class' => ''
        ]
    ];

if (in_array('description', $object_fields))
    $columns[] =  [
        'name' => 'description',
        'enableError' => true,
        'title' => FHtml::t('common', 'Description'),

        'options' => [
            'class' => 'col-md-3'
        ],
        'headerOptions' => [
            'class' => ''
        ]
    ];


if (in_array('file_type', $object_fields))
    $columns[] =  [
        'name' => 'file_type',
        'type'  => \unclead\multipleinput\MultipleInputColumn::TYPE_DROPDOWN,
        'enableError' => true,
        'title' => FHtml::t('common', 'Type'),
        'items' =>  [
            '' => '...',
            'Image' => FHtml::t('common', 'Image'),
            'Audio' => FHtml::t('common', 'Audio'),
            'Video' => FHtml::t('common', 'Video'),
            'File' => FHtml::t('common', 'File'),
            'Link' => FHtml::t('common', 'Link')
        ],
        'headerOptions' => [
            'class' => ''
        ]
    ];

if (in_array('file_size', $object_fields))
    $columns[] =  [
        'name'  => 'file_size',
        'title' => FHtml::t('common', 'File Size'),
        'options' => [
            'style' => 'width:80px',
        ],
        'headerOptions' => [
            'class' => ''
        ]
    ];

if (in_array('file_duration', $object_fields))
    $columns[] =
        [
            'name' => 'file_duration',
            'title' => FHtml::t('common', 'Duration'),
            'options' => [
                'style' => 'width:80px',
            ],
            'headerOptions' => [
                'class' => ''
            ]
        ];
?>

<div class="row">
    <div class="">
        <?php if ($canEdit) { ?>
            <div class="col-md-12">
                <div class="" style="margin-top:-5px">
                <?php
                echo \kartik\widgets\FileInput::widget([
                    'model' => $model,
                    'attribute' => '_ObjectFiles[]',
                    'resizeImages' => true,
                    'options' => ['class' => 'small_size', 'multiple' => true],

                    'pluginOptions' => ['browseLabel' => FHtml::t('button', 'Upload Files'), 'browseClass' => 'btn btn-primary','showCaption' => false, 'showUpload' => false, 'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ']
                ]);
                ?>
                </div>
            </div>
            <?= $form->field($model, 'ObjectFiles')->widget(MultipleInput::className(), [
                'min' => 0,
                'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header
                'columns' => array_merge([

                ],
                    $columns,
                    [
                        [
                            'value' => function ($data) {
                                $file = FHtml::getFieldValue($data, 'file');
                                return FHtml::showImageWithDownload($file, 'object-file', '80px', '50px', 'margin-top:-15px', $file, TRUE, 'download');
                            },
                            'type' => 'static',
                            'name' => 'id',
                            'enableError' => true,
                            'title' => '',
                            'options' => [
                                'style' => 'margin-top:-10px;',
                            ],
                            'headerOptions' => [
                                'class' => 'col-md-1',
                            ],
                        ],

                        [

                            'name' => 'id',
                            'options' => [
                                'style' => 'border:none;width:0px;visible:none',
                            ],
                            'headerOptions' => [
                                'style' => 'width:0px;visible:none',

                            ]
                        ],

                        [
                            'name' => 'file_upload',
                            'type' => \kartik\widgets\FileInput::className(),
                            'options' => [

                                'pluginOptions' => ['browseLabel' => '', 'removeLabel' => '', 'model' => $model, 'maxFileSize' => FHtml::settingMaxFileSize(), 'options' => ['class' => 'small_size', 'multiple' => false], 'showPreview' => false, 'showCaption' => false, 'showRemove' => true, 'showUpload' => false, 'pluginOptions' => ['previewFileType' => 'any', 'uploadUrl' => Url::to([FHtml::config('UPLOAD_FOLDER', '/site/file-upload')])]]
                            ],
                            'headerOptions' => [
                                    'class' => 'col-md-1',
                            ]
                        ]

                    ])
            ])->label(false);
            ?>

        <?php } else if (!empty($models)) {
            ?>
            <table class="table table-bordered">

                <?php foreach ($models as $model) { ?>
                    <tr>
                        <td class="col-md-2 col-xs-2 text-center" style="padding-top:15px">
                            <?= FHtml::showImage($model->file, 'object-file', '100%', '50px', '', $model->file, false, 'download') ?>
                        </td>
                        <td class="col-md-10 col-xs-10">
                            <?= FHtml::showModelField($model, 'title') ?>
                            <small>
                                <?= FHtml::showModelField($model, 'description') ?>
                            </small>
                            <small style="color: grey">
                                <?= $model->file ?>
                            </small>
                        </td>

                    </tr>
                <?php } ?>
            </table>
        <?php } ?>
    </div>
</div>





