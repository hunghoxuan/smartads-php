<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "Application".
*/

use yii\helpers\Url;
use common\components\FHtml;
use common\components\Helper;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$currentRole = FHtml::getCurrentRole();
$moduleName = 'Application';
$moduleTitle = 'Application';
$moduleKey = 'application';
$object_type = 'application';

$form_type = FHtml::getRequestParam('form_type');

$isEditable = FHtml::isInRole('', 'update');

return [
    [
        'class' => 'common\widgets\grid\CheckboxColumn',
        'width' => '20px',
    ],
/*
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],*/
    [
        'class'=>'kartik\grid\ExpandRowColumn',
        'width'=>'30px',
        'value'=>function ($model, $key, $index, $column) {
        return GridView::ROW_COLLAPSED;
        },
        'detail'=>function ($model, $key, $index, $column) {
             return Yii::$app->controller->renderPartial('_view', ['model'=>$model, 'print' => false]);
        },
        'headerOptions'=>['class'=>'kartik-sheet-style'],
        'expandOneOnly'=>false
    ],
    [ //name: id, dbType: int(11), phpType: integer, size: 11, allowNull:  
        'class'=>'kartik\grid\DataColumn',
        'format'=>'html',
        'attribute'=>'id',
        'visible' => FHtml::isVisibleInGrid($object_type, 'id', $form_type),
        'value' => function($model) { return '<b>' . FHtml::showContent($model-> id, FHtml::SHOW_NUMBER, 'application', 'id','int(11)','application') . '</b>' ; }, 
        'hAlign'=>'center',
        'vAlign'=>'middle',
        'width'=>'50px',
    ],
    [ //name: logo, dbType: varchar(300), phpType: string, size: 300, allowNull: 1 
        'class' => FHtml::getColumnClass($object_type, 'logo', $form_type),
        'format'=>'html',
        'attribute'=>'logo',
        'visible' => FHtml::isVisibleInGrid($object_type, 'logo', $form_type),
        'value' => function($model) { return FHtml::showImageThumbnail($model-> logo, FHtml::config(FHtml::SETTINGS_THUMBNAIL_SIZE, 50), 'application'); }, 
        'hAlign'=>'center',
        'vAlign'=>'middle',
        'width'=>'50px',
        'editableOptions'=>[                       
                            'size'=>'md',
                            'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            'widgetClass'=> 'kartik\datecontrol\InputControl',
                            'options'=>[
                                'options'=>[
                                    'pluginOptions'=>[
                                        'autoclose'=>true
                                    ]
                                ]
                            ]
                        ],
    ],
    [ //name: code, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class' => FHtml::getColumnClass($object_type, 'code', $form_type),
        'format'=>'raw',
        'attribute'=>'code',
        'visible' => FHtml::isVisibleInGrid($object_type, 'code', $form_type),
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-md-1 nowrap'],
        'editableOptions'=>[                       
                            'size'=>'md',
                            'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            'widgetClass'=> 'kartik\datecontrol\InputControl',
                            'options'=>[
                                'options'=>[
                                    'pluginOptions'=>[
                                        'autoclose'=>true
                                    ]
                                ]
                            ]
                        ],
    ],
    [ //name: name, dbType: varchar(255), phpType: string, size: 255, allowNull:  
        'class' => FHtml::getColumnClass($object_type, 'name', $form_type),
        'format'=>'raw',
        'attribute'=>'name',
        'visible' => FHtml::isVisibleInGrid($object_type, 'name', $form_type),
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-md-2 nowrap'],
        'editableOptions'=>[                       
                            'size'=>'md',
                            'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            'widgetClass'=> 'kartik\datecontrol\InputControl',
                            'options'=>[
                                'options'=>[
                                    'pluginOptions'=>[
                                        'autoclose'=>true
                                    ]
                                ]
                            ]
                        ],
    ],
    [ //name: description, dbType: varchar(1000), phpType: string, size: 1000, allowNull: 1 
        'class' => FHtml::getColumnClass($object_type, 'description', $form_type),
        'format'=>'raw',
        'attribute'=>'description',
        'visible' => FHtml::isVisibleInGrid($object_type, 'description', $form_type),
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-md-2 nowrap'],
        'editableOptions'=>[                       
                            'size'=>'md',
                            'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            'widgetClass'=> 'kartik\datecontrol\InputControl',
                            'options'=>[
                                'options'=>[
                                    'pluginOptions'=>[
                                        'autoclose'=>true
                                    ]
                                ]
                            ]
                        ],
    ],
//    [ //name: keywords, dbType: varchar(1000), phpType: string, size: 1000, allowNull: 1
//        'class' => FHtml::getColumnClass($object_type, 'keywords', $form_type),
//        'format'=>'raw',
//        'attribute'=>'keywords',
//        'visible' => FHtml::isVisibleInGrid($object_type, 'keywords', $form_type),
//        'hAlign'=>'left',
//        'vAlign'=>'middle',
//        'contentOptions'=>['class'=>'col-md-2 nowrap'],
//        'editableOptions'=>[
//                            'size'=>'md',
//                            'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
//                            'widgetClass'=> 'kartik\datecontrol\InputControl',
//                            'options'=>[
//                                'options'=>[
//                                    'pluginOptions'=>[
//                                        'autoclose'=>true
//                                    ]
//                                ]
//                            ]
//                        ],
//    ],
    //[ //name: note, dbType: varchar(3000), phpType: string, size: 3000, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'note', $form_type),
        //'format'=>'raw',
        //'attribute'=>'note',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'note', $form_type),
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-md-2 nowrap'],
        //'editableOptions'=>[                       
                            //'size'=>'md',
                            //'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            //'widgetClass'=> 'kartik\datecontrol\InputControl',
                            //'options'=>[
                                //'options'=>[
                                    //'pluginOptions'=>[
                                        //'autoclose'=>true
                                    //]
                                //]
                            //]
                        //],
    //],
    [ //name: lang, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        'class' => FHtml::getColumnClass($object_type, 'lang', $form_type),
        'format'=>'raw',
        'attribute'=>'lang',
        'visible' => FHtml::isVisibleInGrid($object_type, 'lang', $form_type),
        'value' => function($model) { return FHtml::showContent($model-> lang, FHtml::SHOW_LABEL, 'application', 'lang','varchar(100)','application'); }, 
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'width'=>'80px',
        'filterType' => GridView::FILTER_SELECT2, 
        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear' => true],
                            ],
        'filterInputOptions'=>['placeholder'=>''],
        'filter'=> FHtml::getComboArray('application', 'application', 'lang', true, 'id', 'name'),
    ],
    [ //name: modules, dbType: varchar(500), phpType: string, size: 500, allowNull: 1 
        'class' => FHtml::getColumnClass($object_type, 'modules', $form_type),
        'format'=>'raw',
        'attribute'=>'modules',
        'visible' => FHtml::isVisibleInGrid($object_type, 'modules', $form_type),
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-md-2 nowrap'],
        'editableOptions'=>[                       
                            'size'=>'md',
                            'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            'widgetClass'=> 'kartik\datecontrol\InputControl',
                            'options'=>[
                                'options'=>[
                                    'pluginOptions'=>[
                                        'autoclose'=>true
                                    ]
                                ]
                            ]
                        ],
    ],
    [ //name: storage_max, dbType: bigint(20), phpType: string, size: 20, allowNull: 1 
        'class' => FHtml::getColumnClass($object_type, 'storage_max', $form_type),
        'format'=>'raw', //['decimal', 0],
        'attribute'=>'storage_max',
        'visible' => FHtml::isVisibleInGrid($object_type, 'storage_max', $form_type),
        'value' => function($model) { return FHtml::showContent($model-> storage_max, FHtml::SHOW_NUMBER, 'application', 'storage_max','bigint(20)','application'); }, 
        'hAlign'=>'right',
        'vAlign'=>'middle',
        'width'=>'50px',
        'editableOptions'=>[                       
                            'size'=>'md',
                            'inputType'=>\kartik\editable\Editable::INPUT_SPIN, //'\kartik\money\MaskMoney',
                            'options'=>[
                                'pluginOptions'=>[
                                    'min'=>0, 'max' => 50000000000, 'precision' => 0, 
                                ]
                            ]
                        ],
    ],
    //[ //name: storage_current, dbType: bigint(20), phpType: string, size: 20, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'storage_current', $form_type),
        //'format'=>'raw', //['decimal', 0],
        //'attribute'=>'storage_current',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'storage_current', $form_type),
        //'value' => function($model) { return FHtml::showContent($model-> storage_current, FHtml::SHOW_NUMBER, 'application', 'storage_current','bigint(20)','application'); }, 
        //'hAlign'=>'right',
        //'vAlign'=>'middle',
        //'width'=>'50px',
        //'editableOptions'=>[                       
                            //'size'=>'md',
                            //'inputType'=>\kartik\editable\Editable::INPUT_SPIN, //'\kartik\money\MaskMoney',
                            //'options'=>[
                                //'pluginOptions'=>[
                                    //'min'=>0, 'max' => 50000000000, 'precision' => 0, 
                                //]
                            //]
                        //],
    //],
    //[ //name: address, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'address', $form_type),
        //'format'=>'raw',
        //'attribute'=>'address',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'address', $form_type),
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
        //'editableOptions'=>[                       
                            //'size'=>'md',
                            //'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            //'widgetClass'=> 'kartik\datecontrol\InputControl',
                            //'options'=>[
                                //'options'=>[
                                    //'pluginOptions'=>[
                                        //'autoclose'=>true
                                    //]
                                //]
                            //]
                        //],
    //],
    //[ //name: map, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'map', $form_type),
        //'format'=>'raw',
        //'attribute'=>'map',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'map', $form_type),
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
        //'editableOptions'=>[                       
                            //'size'=>'md',
                            //'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            //'widgetClass'=> 'kartik\datecontrol\InputControl',
                            //'options'=>[
                                //'options'=>[
                                    //'pluginOptions'=>[
                                        //'autoclose'=>true
                                    //]
                                //]
                            //]
                        //],
    //],
    //[ //name: website, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'website', $form_type),
        //'format'=>'raw',
        //'attribute'=>'website',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'website', $form_type),
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
        //'editableOptions'=>[                       
                            //'size'=>'md',
                            //'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            //'widgetClass'=> 'kartik\datecontrol\InputControl',
                            //'options'=>[
                                //'options'=>[
                                    //'pluginOptions'=>[
                                        //'autoclose'=>true
                                    //]
                                //]
                            //]
                        //],
    //],
    //[ //name: email, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'email', $form_type),
        //'format'=>'raw',
        //'attribute'=>'email',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'email', $form_type),
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
        //'editableOptions'=>[                       
                            //'size'=>'md',
                            //'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            //'widgetClass'=> 'kartik\datecontrol\InputControl',
                            //'options'=>[
                                //'options'=>[
                                    //'pluginOptions'=>[
                                        //'autoclose'=>true
                                    //]
                                //]
                            //]
                        //],
    //],
    //[ //name: phone, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'phone', $form_type),
        //'format'=>'raw',
        //'attribute'=>'phone',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'phone', $form_type),
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
        //'editableOptions'=>[                       
                            //'size'=>'md',
                            //'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            //'widgetClass'=> 'kartik\datecontrol\InputControl',
                            //'options'=>[
                                //'options'=>[
                                    //'pluginOptions'=>[
                                        //'autoclose'=>true
                                    //]
                                //]
                            //]
                        //],
    //],
    //[ //name: fax, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'fax', $form_type),
        //'format'=>'raw',
        //'attribute'=>'fax',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'fax', $form_type),
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
        //'editableOptions'=>[                       
                            //'size'=>'md',
                            //'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            //'widgetClass'=> 'kartik\datecontrol\InputControl',
                            //'options'=>[
                                //'options'=>[
                                    //'pluginOptions'=>[
                                        //'autoclose'=>true
                                    //]
                                //]
                            //]
                        //],
    //],
    //[ //name: chat, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'chat', $form_type),
        //'format'=>'raw',
        //'attribute'=>'chat',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'chat', $form_type),
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
        //'editableOptions'=>[                       
                            //'size'=>'md',
                            //'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            //'widgetClass'=> 'kartik\datecontrol\InputControl',
                            //'options'=>[
                                //'options'=>[
                                    //'pluginOptions'=>[
                                        //'autoclose'=>true
                                    //]
                                //]
                            //]
                        //],
    //],
    //[ //name: facebook, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'facebook', $form_type),
        //'format'=>'raw',
        //'attribute'=>'facebook',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'facebook', $form_type),
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
        //'editableOptions'=>[                       
                            //'size'=>'md',
                            //'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            //'widgetClass'=> 'kartik\datecontrol\InputControl',
                            //'options'=>[
                                //'options'=>[
                                    //'pluginOptions'=>[
                                        //'autoclose'=>true
                                    //]
                                //]
                            //]
                        //],
    //],
    //[ //name: twitter, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'twitter', $form_type),
        //'format'=>'raw',
        //'attribute'=>'twitter',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'twitter', $form_type),
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
        //'editableOptions'=>[                       
                            //'size'=>'md',
                            //'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            //'widgetClass'=> 'kartik\datecontrol\InputControl',
                            //'options'=>[
                                //'options'=>[
                                    //'pluginOptions'=>[
                                        //'autoclose'=>true
                                    //]
                                //]
                            //]
                        //],
    //],
    //[ //name: google, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'google', $form_type),
        //'format'=>'raw',
        //'attribute'=>'google',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'google', $form_type),
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
        //'editableOptions'=>[                       
                            //'size'=>'md',
                            //'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            //'widgetClass'=> 'kartik\datecontrol\InputControl',
                            //'options'=>[
                                //'options'=>[
                                    //'pluginOptions'=>[
                                        //'autoclose'=>true
                                    //]
                                //]
                            //]
                        //],
    //],
    //[ //name: youtube, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'youtube', $form_type),
        //'format'=>'raw',
        //'attribute'=>'youtube',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'youtube', $form_type),
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
        //'editableOptions'=>[                       
                            //'size'=>'md',
                            //'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            //'widgetClass'=> 'kartik\datecontrol\InputControl',
                            //'options'=>[
                                //'options'=>[
                                    //'pluginOptions'=>[
                                        //'autoclose'=>true
                                    //]
                                //]
                            //]
                        //],
    //],
    //[ //name: copyright, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'copyright', $form_type),
        //'format'=>'raw',
        //'attribute'=>'copyright',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'copyright', $form_type),
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
        //'editableOptions'=>[                       
                            //'size'=>'md',
                            //'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            //'widgetClass'=> 'kartik\datecontrol\InputControl',
                            //'options'=>[
                                //'options'=>[
                                    //'pluginOptions'=>[
                                        //'autoclose'=>true
                                    //]
                                //]
                            //]
                        //],
    //],
    //[ //name: terms_of_service, dbType: varchar(300), phpType: string, size: 300, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'terms_of_service', $form_type),
        //'format'=>'raw',
        //'attribute'=>'terms_of_service',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'terms_of_service', $form_type),
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
        //'editableOptions'=>[                       
                            //'size'=>'md',
                            //'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            //'widgetClass'=> 'kartik\datecontrol\InputControl',
                            //'options'=>[
                                //'options'=>[
                                    //'pluginOptions'=>[
                                        //'autoclose'=>true
                                    //]
                                //]
                            //]
                        //],
    //],
    //[ //name: profile, dbType: varchar(300), phpType: string, size: 300, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'profile', $form_type),
        //'format'=>'raw',
        //'attribute'=>'profile',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'profile', $form_type),
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
        //'editableOptions'=>[                       
                            //'size'=>'md',
                            //'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            //'widgetClass'=> 'kartik\datecontrol\InputControl',
                            //'options'=>[
                                //'options'=>[
                                    //'pluginOptions'=>[
                                        //'autoclose'=>true
                                    //]
                                //]
                            //]
                        //],
    //],
    //[ //name: privacy_policy, dbType: varchar(300), phpType: string, size: 300, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'privacy_policy', $form_type),
        //'format'=>'raw',
        //'attribute'=>'privacy_policy',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'privacy_policy', $form_type),
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
        //'editableOptions'=>[                       
                            //'size'=>'md',
                            //'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            //'widgetClass'=> 'kartik\datecontrol\InputControl',
                            //'options'=>[
                                //'options'=>[
                                    //'pluginOptions'=>[
                                        //'autoclose'=>true
                                    //]
                                //]
                            //]
                        //],
    //],

//    [ //name: type, dbType: varchar(100), phpType: string, size: 100, allowNull: 1
//        'class' => FHtml::getColumnClass($object_type, 'type', $form_type),
//        'format'=>'raw',
//        'attribute'=>'type',
//        'visible' => FHtml::isVisibleInGrid($object_type, 'type', $form_type),
//        'value' => function($model) { return FHtml::showContent($model-> type, FHtml::SHOW_LABEL, 'application', 'type','varchar(100)','application'); },
//        'hAlign'=>'left',
//        'vAlign'=>'middle',
//        'width'=>'80px',
//        'filterType' => GridView::FILTER_SELECT2,
//        'filterWidgetOptions'=>[
//                            'pluginOptions'=>['allowClear' => true],
//                            ],
//        'filterInputOptions'=>['placeholder'=>''],
//        'filter'=> FHtml::getComboArray('application', 'application', 'type', true, 'id', 'name'),
//    ],
    [ //name: status, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        'class' => FHtml::getColumnClass($object_type, 'status', $form_type),
        'format'=>'raw',
        'attribute'=>'status',
        'visible' => FHtml::isVisibleInGrid($object_type, 'status', $form_type),
        'value' => function($model) { return FHtml::showContent($model-> status, FHtml::SHOW_LABEL, 'application', 'status','varchar(100)','application'); }, 
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'width'=>'80px',
        'filterType' => GridView::FILTER_SELECT2, 
        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear' => true],
                            ],
        'filterInputOptions'=>['placeholder'=>''],
        'filter'=> FHtml::getComboArray('application', 'application', 'status', true, 'id', 'name'),
    ],
    [ //name: is_active, dbType: tinyint(1), phpType: integer, size: 1, allowNull: 1
        'class'=>'kartik\grid\BooleanColumn',
        'format'=>'raw',
        'attribute'=>'is_active',
        'visible' => FHtml::isVisibleInGrid($object_type, 'is_active', $form_type),
        'value' => function($model) { return FHtml::showContent($model-> is_active, FHtml::SHOW_BOOLEAN, 'application', 'is_active','tinyint(1)','application'); },
        'hAlign'=>'center',
        'vAlign'=>'middle',
        'width'=>'20px',
    ],
    //[ //name: page_size, dbType: int(5), phpType: integer, size: 5, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'page_size', $form_type),
        //'format'=>'raw', //['decimal', 0],
        //'attribute'=>'page_size',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'page_size', $form_type),
        //'value' => function($model) { return FHtml::showContent($model-> page_size, FHtml::SHOW_NUMBER, 'application', 'page_size','int(5)','application'); }, 
        //'hAlign'=>'right',
        //'vAlign'=>'middle',
        //'width'=>'50px',
        //'editableOptions'=>[                       
                            //'size'=>'md',
                            //'inputType'=>\kartik\editable\Editable::INPUT_SPIN, //'\kartik\money\MaskMoney',
                            //'options'=>[
                                //'pluginOptions'=>[
                                    //'min'=>0, 'max' => 50000000000, 'precision' => 0, 
                                //]
                            //]
                        //],
    //],
    //[ //name: main_color, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'main_color', $form_type),
        //'format'=>'raw',
        //'attribute'=>'main_color',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'main_color', $form_type),
        //'value' => function($model) { return FHtml::showContent($model-> main_color, FHtml::SHOW_COLOR, 'application', 'main_color','varchar(255)','application'); }, 
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'width'=>'20px',
        //'filterType' => GridView::FILTER_COLOR, 

        //'editableOptions'=> [

                            //'size'=>'md',
                            //'widgetClass'=> 'kartik\widgets\ColorInput',                          
                            //'inputType'=>\kartik\editable\Editable::INPUT_COLOR,] 
    //],
    //[ //name: cache_enabled, dbType: tinyint(1), phpType: integer, size: 1, allowNull: 1 
        //'class'=>'kartik\grid\BooleanColumn',
        //'format'=>'raw',
        //'attribute'=>'cache_enabled',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'cache_enabled', $form_type),
        //'hAlign'=>'center',
        //'vAlign'=>'middle',
        //'width'=>'50px',
    //],
    //[ //name: currency_format, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'currency_format', $form_type),
        //'format'=>'raw',
        //'attribute'=>'currency_format',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'currency_format', $form_type),
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
        //'editableOptions'=>[                       
                            //'size'=>'md',
                            //'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            //'widgetClass'=> 'kartik\datecontrol\InputControl',
                            //'options'=>[
                                //'options'=>[
                                    //'pluginOptions'=>[
                                        //'autoclose'=>true
                                    //]
                                //]
                            //]
                        //],
    //],
    //[ //name: date_format, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'date_format', $form_type),
        //'format'=>'raw',
        //'attribute'=>'date_format',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'date_format', $form_type),
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
        //'editableOptions'=>[                       
                            //'size'=>'md',
                            //'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            //'widgetClass'=> 'kartik\datecontrol\InputControl',
                            //'options'=>[
                                //'options'=>[
                                    //'pluginOptions'=>[
                                        //'autoclose'=>true
                                    //]
                                //]
                            //]
                        //],
    //],
    //[ //name: web_theme, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'web_theme', $form_type),
        //'format'=>'raw',
        //'attribute'=>'web_theme',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'web_theme', $form_type),
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
        //'editableOptions'=>[                       
                            //'size'=>'md',
                            //'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            //'widgetClass'=> 'kartik\datecontrol\InputControl',
                            //'options'=>[
                                //'options'=>[
                                    //'pluginOptions'=>[
                                        //'autoclose'=>true
                                    //]
                                //]
                            //]
                        //],
    //],
    //[ //name: admin_form_alignment, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'admin_form_alignment', $form_type),
        //'format'=>'raw',
        //'attribute'=>'admin_form_alignment',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'admin_form_alignment', $form_type),
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
        //'editableOptions'=>[                       
                            //'size'=>'md',
                            //'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            //'widgetClass'=> 'kartik\datecontrol\InputControl',
                            //'options'=>[
                                //'options'=>[
                                    //'pluginOptions'=>[
                                        //'autoclose'=>true
                                    //]
                                //]
                            //]
                        //],
    //],
    //[ //name: body_css, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'body_css', $form_type),
        //'format'=>'raw',
        //'attribute'=>'body_css',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'body_css', $form_type),
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
        //'editableOptions'=>[                       
                            //'size'=>'md',
                            //'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            //'widgetClass'=> 'kartik\datecontrol\InputControl',
                            //'options'=>[
                                //'options'=>[
                                    //'pluginOptions'=>[
                                        //'autoclose'=>true
                                    //]
                                //]
                            //]
                        //],
    //],
    //[ //name: body_style, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'body_style', $form_type),
        //'format'=>'raw',
        //'attribute'=>'body_style',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'body_style', $form_type),
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
        //'editableOptions'=>[                       
                            //'size'=>'md',
                            //'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            //'widgetClass'=> 'kartik\datecontrol\InputControl',
                            //'options'=>[
                                //'options'=>[
                                    //'pluginOptions'=>[
                                        //'autoclose'=>true
                                    //]
                                //]
                            //]
                        //],
    //],
    //[ //name: page_css, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'page_css', $form_type),
        //'format'=>'raw',
        //'attribute'=>'page_css',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'page_css', $form_type),
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
        //'editableOptions'=>[                       
                            //'size'=>'md',
                            //'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            //'widgetClass'=> 'kartik\datecontrol\InputControl',
                            //'options'=>[
                                //'options'=>[
                                    //'pluginOptions'=>[
                                        //'autoclose'=>true
                                    //]
                                //]
                            //]
                        //],
    //],
    //[ //name: page_style, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'page_style', $form_type),
        //'format'=>'raw',
        //'attribute'=>'page_style',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'page_style', $form_type),
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
        //'editableOptions'=>[                       
                            //'size'=>'md',
                            //'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            //'widgetClass'=> 'kartik\datecontrol\InputControl',
                            //'options'=>[
                                //'options'=>[
                                    //'pluginOptions'=>[
                                        //'autoclose'=>true
                                    //]
                                //]
                            //]
                        //],
    //],
    //[ //name: owner_id, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'owner_id', $form_type),
        //'format'=>'raw',
        //'attribute'=>'owner_id',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'owner_id', $form_type),
        //'value' => function($model) { return FHtml::showContent($model-> owner_id, FHtml::SHOW_LOOKUP, '@user,id,username', 'owner_id','varchar(100)','application'); }, 
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'width'=>'80px',
        //'filterType' => GridView::FILTER_SELECT2, 
        //'filterWidgetOptions'=>[
                            //'pluginOptions'=>['allowClear' => true],
                            //],
        //'filterInputOptions'=>['placeholder'=>''],
        //'filter'=> FHtml::getComboArray('@user,id,username', 'user,id,username', 'owner_id', true, 'id', 'name'),
    //],
    //[ //name: created_date, dbType: datetime, phpType: string, size: , allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'created_date', $form_type),
        //'format'=>'raw', // date 
        //'attribute'=>'created_date',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'created_date', $form_type),
        //'hAlign'=>'right',
        //'vAlign'=>'middle',
        //'width'=>'50px',
        //'editableOptions'=>[                       
                            //'size'=>'md',
                            //'inputType'=>\kartik\editable\Editable::INPUT_WIDGET,
                            //'widgetClass'=> 'kartik\datecontrol\DateControl',
                            //'options'=>[
                                //'type'=>\kartik\datecontrol\DateControl::FORMAT_DATE,
                                //'displayFormat'=> FHtml::config(FHtml::SETTINGS_DATE_FORMAT, 'Y.m.d'),
                                //'saveFormat'=>'php:Y-m-d',
                                //'options'=>[
                                    //'pluginOptions'=>[
                                        //'autoclose'=>true
                                    //]
                                //]
                            //]
                        //],
    //],
    //[ //name: created_user, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'created_user', $form_type),
        //'format'=>'raw',
        //'attribute'=>'created_user',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'created_user', $form_type),
        //'value' => function($model) { return FHtml::showContent($model-> created_user, FHtml::SHOW_LABEL, 'application', 'created_user','varchar(100)','application'); }, 
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'width'=>'80px',
        //'filterType' => GridView::FILTER_SELECT2, 
        //'filterWidgetOptions'=>[
                            //'pluginOptions'=>['allowClear' => true],
                            //],
        //'filterInputOptions'=>['placeholder'=>''],
        //'filter'=> FHtml::getComboArray('application', 'application', 'created_user', true, 'id', 'name'),
    //],
    //[ //name: modified_date, dbType: datetime, phpType: string, size: , allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'modified_date', $form_type),
        //'format'=>'raw', // date 
        //'attribute'=>'modified_date',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'modified_date', $form_type),
        //'hAlign'=>'right',
        //'vAlign'=>'middle',
        //'width'=>'50px',
        //'editableOptions'=>[                       
                            //'size'=>'md',
                            //'inputType'=>\kartik\editable\Editable::INPUT_WIDGET,
                            //'widgetClass'=> 'kartik\datecontrol\DateControl',
                            //'options'=>[
                                //'type'=>\kartik\datecontrol\DateControl::FORMAT_DATE,
                                //'displayFormat'=> FHtml::config(FHtml::SETTINGS_DATE_FORMAT, 'Y.m.d'),
                                //'saveFormat'=>'php:Y-m-d',
                                //'options'=>[
                                    //'pluginOptions'=>[
                                        //'autoclose'=>true
                                    //]
                                //]
                            //]
                        //],
    //],
    //[ //name: modified_user, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        //'class' => FHtml::getColumnClass($object_type, 'modified_user', $form_type),
        //'format'=>'raw',
        //'attribute'=>'modified_user',
        //'visible' => FHtml::isVisibleInGrid($object_type, 'modified_user', $form_type),
        //'value' => function($model) { return FHtml::showContent($model-> modified_user, FHtml::SHOW_LABEL, 'application', 'modified_user','varchar(255)','application'); }, 
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'width'=>'100px',
        //'filterType' => GridView::FILTER_SELECT2, 
        //'filterWidgetOptions'=>[
                            //'pluginOptions'=>['allowClear' => true],
                            //],
        //'filterInputOptions'=>['placeholder'=>''],
        //'filter'=> FHtml::getComboArray('application', 'application', 'modified_user', true, 'id', 'name'),
    //],
    [ //name: is_system, dbType: tinyint(1), phpType: integer, size: 1, allowNull: 1
        'class' => 'kartik\grid\DataColumn',
        'label' => '',
        'format' => 'raw',
        'attribute' => '',
        'visible' => true,
        'value' => function ($model) {
            return ''
                . '<a class="btn btn-xs btn-edit btn-warning" data-pjax=0 href="' . FHtml::createUrl('site/index', ['application_id' => $model->code], BACKEND) . '"> SELECT ' . '</a>';
        },
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '70px',
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => $this->params['buttonsType'], // Dropdown or Buttons
        'hAlign'=>'center',
        'vAlign'=>'middle',
        'width'=>'80px',
        'urlCreator' => function($action, $model) {
            return FHtml::createBackendActionUrl([$action, 'id' => FHtml::getFieldValue($model, ['id', 'product_id'])]);
        },
        'visibleButtons' =>[
            'update' => FHtml::isInRole('', 'update', $currentRole),
            'delete' => FHtml::isInRole('', 'delete', $currentRole),
        ],
        'viewOptions'=>['role'=>$this->params['editType'],'title'=>FHtml::t('common', 'title.view'),'data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>$this->params['editType'],'title'=>FHtml::t('common', 'title.update'), 'data-toggle'=>'tooltip'],
        'deleteOptions'=>[
            'role'=>'modal-remote',
            'title'=>FHtml::t('common', 'title.delete'),
            'data-confirm'=>false,
            'data-method'=>false,// for overide yii data api
            'data-request-method'=>'post',
            'data-toggle'=>'tooltip',
            'data-confirm-title'=>FHtml::t('message', 'Confirmation'),
            'data-confirm-message'=>FHtml::t('common', 'message.confirmdelete')
        ],
    ],
];