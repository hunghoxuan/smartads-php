<?php
use common\components\FHtml;
use unclead\multipleinput\MultipleInput;
use yii\helpers\Url;
use backend\modules\smartscreen\models\SmartscreenContent;

$models = $model->getSmartscreenFiles();
$accept_files = !empty($accept_files) ? $accept_files : "video/*,image/*,audio/*";

?>

<?php
$type = isset($type) ? $type : $model->type;
?>

<?php if ($canEdit) { ?>
    <div class="row">
        <div class="">

            <?= $form->field($model, 'list_content')->widget(MultipleInput::className(), [
                'min' => 0,
                'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header
                'columns' => [

                    [
                        'type'  => $type == SmartscreenContent::TYPE_SLIDE ? \unclead\multipleinput\MultipleInputColumn::TYPE_DROPDOWN : \unclead\multipleinput\MultipleInputColumn::TYPE_STATIC,
                        'enableError' => true,
                        'title' => FHtml::t('common', 'Type'),
                        'items' =>  [
                            'marquee' => FHtml::t('common', 'Scrolling Text'),
                            'image' => FHtml::t('common', 'Image'),
                            'video' => FHtml::t('common', 'Video'),
                            'embed' => FHtml::t('common', 'Embed HTML'),
                            'url' => FHtml::t('common', 'URL Link'),
                            'youtube' => FHtml::t('common', 'Youtube'),
                            'facebook' => FHtml::t('common', 'Facebook'),
                        ],
                        'headerOptions' => [
                            'class' => 'col-md-1',
                        ],
                        'name' => 'command',
                        'options' => [
                            'style' => $type == SmartscreenContent::TYPE_SLIDE ? null : 'width:0px;visible:none',
                        ],

                    ],
                    [
                        'name' => 'description',
                        'enableError' => true,
                        'title' => FHtml::t('common', 'Description'),

                        'options' => [
                        ],
                        'headerOptions' => [
                            'class' => 'col-md-5',
                        ]
                    ],

                    [
                        'name' => 'file_duration',
                        'title' => FHtml::t('common', 'Duration'),
                        'options' => [
                            'style' => $type == SmartscreenContent::TYPE_VIDEO ? null : null, //'border:none;width:0px;visible:none',
                        ],
                        'headerOptions' => [
                            'class' => 'col-md-1',
                            'style' => $type == SmartscreenContent::TYPE_VIDEO ? null : null,//'width:0px;visible:none',
                        ]
                    ],
                    [
                        'name' => 'file_kind',
                        'type'  => \unclead\multipleinput\MultipleInputColumn::TYPE_DROPDOWN,
                        'enableError' => true,
                        'title' => FHtml::t('common', ''),
                        'items' =>  [
                            'second' => FHtml::t('common', 'Seconds'),
                            'time' => FHtml::t('common', 'Minutes'),
                            //'number' => FHtml::t('common', 'Times'),
                        ],
                        'headerOptions' => [
                            'class' => 'col-md-1',
                            'style' => $type == SmartscreenContent::TYPE_VIDEO ? null : null, //'width:0px;visible:none',
                        ],
                        'options' => [
                            'style' => $type == SmartscreenContent::TYPE_VIDEO ? null : null, //'border:none;width:0px;visible:none',
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
                        'name' => '_file_upload',
                        'type' => \kartik\widgets\FileInput::className(),
                        'title' => FHtml::t('common', 'File'),

                        'options' => [
                            'options' => ['accept' => $accept_files, 'class' => 'small_size', 'multiple' => false],
                            'pluginOptions' => ['browseLabel' => '', 'removeLabel' => '', 'model' => $model, 'maxFileSize' => FHtml::settingMaxFileSize(),  'showPreview' => false, 'showCaption' => false, 'showRemove' => true, 'showUpload' => false]
                        ],
                        'headerOptions' => [
                            'class' => 'col-md-1',
                        ]
                    ],
                    [
                        'value' => function ($data) {
                            $file = FHtml::showImage(FHtml::getFieldValue($data, 'file'), 'smartscreen-file', '80px', '50px', 'margin-top:-8px', 'btn btn-large', false, 'download');
                            if (ends_with($file, FHtml::NO_IMAGE))
                                return FHtml::getFieldValue($data, 'file');
                            else
                                return $file;
                            },
                        'type' => 'static',
                        'name' => 'id',
                        'enableError' => true,
                        'title' => FHtml::t('common', ''),
                        'options' => [
                            'style' => 'margin-top:-10px;',
                        ],
                        'headerOptions' => [
                            'class' => 'col-md-1',
                        ],
                    ],
                    [
                        'name' => 'sort_order',
                        'title' => FHtml::t('common', 'STT'),
                        'headerOptions' => [
                            'class' => 'col-md-1',
                        ]
                    ],


                ]
            ])->label(false);
            ?>
            <small style="font-style:italic;color:grey">
                Ghi chú:
                - Thời lượng: thời gian chờ một slide. Nếu Kiểu là video có thể đặt bằng 0 để tự động chơi hết video. <br/>
                - Facebook: https://www.facebook.com/xxx/videos/xxx <br/>
                - Youtube: https://www.youtube.com/embed/xxx <br/>
                - URL: Đường link website. <br/>
                - HTML: Nội dung Html bất kỳ hoặc mã nhúng html dạng < iframe..
            </small>
        </div>
    </div>

<?php } else { ?>


<?php } ?>



