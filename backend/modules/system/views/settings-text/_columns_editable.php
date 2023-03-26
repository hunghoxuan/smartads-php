<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "SettingsText".
*/

use yii\helpers\Url;
use common\components\FHtml;

use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$currentRole = FHtml::getCurrentRole();
$moduleName = 'SettingsText';
$moduleTitle = 'Settings Text';
$moduleKey = 'settings-text';
$form_type = FHtml::getRequestParam('form_type');

$isEditable = FHtml::isInRole('', 'update');
return [
    [
        'class' => 'common\widgets\grid\CheckboxColumn',
        'width' => '20px',
    ],
//    [
//        'class' => 'kartik\grid\SerialColumn',
//        'width' => '30px',
//    ],
    [
        'class'=>'kartik\grid\ExpandRowColumn',
        'width'=>'50px',
        'value'=>function ($model, $key, $index, $column) {
        return GridView::ROW_COLLAPSED;
        },
        'detail'=>function ($model, $key, $index, $column) {
        return Yii::$app->controller->renderPartial('_view_preview', ['model'=>$model]);
        },
        'headerOptions'=>['class'=>'kartik-sheet-style'],
        'expandOneOnly'=>false
    ],
//    [ //name: id, dbType: bigint(10), phpType: string, size: 10, allowNull:
//        'class'=>'kartik\grid\DataColumn',
//        'format'=>'html',
//        'attribute'=>'id',
//        'visible' => FHtml::isVisibleInGrid($moduleName, 'id', $form_type, true),
//        'value' => function($model) { return '<b>' . FHtml::showContent($model-> id, FHtml::SHOW_NUMBER, 'settings_text', 'id','bigint(10)','settings-text') . '</b>' ; },
//        'hAlign'=>'center',
//        'vAlign'=>'middle',
//        'width'=>'50px',
//    ],
    [ //name: metaKey, dbType: varchar(255), phpType: string, size: 255, allowNull:
        'format'=>'raw',
        'attribute'=>'group',
        'visible' => true,
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-md-1 nowrap'],
        'group' => true,
    ],
    [ //name: name, dbType: varchar(255), phpType: string, size: 255, allowNull:
        'class' => 'kartik\grid\DataColumn',
        'format'=>'raw',
        'attribute'=>'name',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'name', $form_type, true),
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
    [ //name: description, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
        'class' => FHtml::getColumnClass($moduleName, 'description', $form_type, true),
        'format'=>'raw',
        'attribute'=>'description',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'description', $form_type, true),
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
//    [ //name: description_en, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
//        'class' => FHtml::getColumnClass($moduleName, 'description_en', $form_type, true),
//        'format'=>'raw',
//        'attribute'=>'description_en',
//        'visible' => FHtml::isVisibleInGrid($moduleName, 'description_en', $form_type, true),
//        'hAlign'=>'left',
//        'vAlign'=>'middle',
//        'contentOptions'=>['class'=>'col-md-1 nowrap'],
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
    [ //name: description_vi, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
        'class' => FHtml::getColumnClass($moduleName, 'description_vi', $form_type, true),
        'format'=>'raw',
        'attribute'=>'description_vi',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'description_vi', $form_type, true),
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
    [ //name: description_es, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
        'class' => FHtml::getColumnClass($moduleName, 'description_es', $form_type, true),
        'format'=>'raw',
        'attribute'=>'description_es',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'description_es', $form_type, true),
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
    [ //name: description_pt, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
        'class' => FHtml::getColumnClass($moduleName, 'description_pt', $form_type, true),
        'format'=>'raw',
        'attribute'=>'description_pt',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'description_pt', $form_type, true),
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
    [ //name: description_de, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
        'class' => FHtml::getColumnClass($moduleName, 'description_de', $form_type, true),
        'format'=>'raw',
        'attribute'=>'description_de',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'description_de', $form_type, true),
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
    [ //name: description_fr, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
        'class' => FHtml::getColumnClass($moduleName, 'description_fr', $form_type, true),
        'format'=>'raw',
        'attribute'=>'description_fr',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'description_fr', $form_type, true),
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
//    [ //name: description_it, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
//        'class' => FHtml::getColumnClass($moduleName, 'description_it', $form_type, true),
//        'format'=>'raw',
//        'attribute'=>'description_it',
//        'visible' => FHtml::isVisibleInGrid($moduleName, 'description_it', $form_type, true),
//        'hAlign'=>'left',
//        'vAlign'=>'middle',
//        'contentOptions'=>['class'=>'col-md-1 nowrap'],
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
    [ //name: description_ko, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
        'class' => FHtml::getColumnClass($moduleName, 'description_ko', $form_type, true),
        'format'=>'raw',
        'attribute'=>'description_ko',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'description_ko', $form_type, true),
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
    [ //name: description_ja, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
        'class' => FHtml::getColumnClass($moduleName, 'description_ja', $form_type, true),
        'format'=>'raw',
        'attribute'=>'description_ja',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'description_ja', $form_type, true),
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

//    [ //name: description_zh, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
//        'class' => FHtml::getColumnClass($moduleName, 'description_zh', $form_type, true),
//        'format'=>'raw',
//        'attribute'=>'description_zh',
//        'visible' => FHtml::isVisibleInGrid($moduleName, 'description_zh', $form_type, true),
//        'hAlign'=>'left',
//        'vAlign'=>'middle',
//        'contentOptions'=>['class'=>'col-md-1 nowrap'],
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
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => $this->params['buttonsType'], // Dropdown or Buttons
        'hAlign'=>'center',
        'vAlign'=>'middle',
        'width'=>'80px',
        'urlCreator' => function($action, $model) {
            return FHtml::createBackendActionUrl([$action, 'id' => $model->id]);
        },
        'visibleButtons' =>[
            'update' => FHtml::isInRole('', 'update', $currentRole),
            'delete' => FHtml::isInRole('', 'delete', $currentRole),
        ],
        'viewOptions'=>['role'=>$this->params['displayType'],'title'=>FHtml::t('common', 'title.view'),'data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>$this->params['editType'],'title'=>FHtml::t('common', 'title.update'), 'data-toggle'=>'tooltip'],
        'deleteOptions'=>[
            'role'=>'modal-remote',
            'title'=>FHtml::t('common', 'title.delete'),
            'data-confirm'=>false,
            'data-method'=>false,// for overide yii data api
            'data-request-method'=>'post',
            'data-toggle'=>'tooltip',
            'data-confirm-title'=>FHtml::t('common', 'Confirmation'),
            'data-confirm-message'=>FHtml::t('common', 'messege.confirmdelete')
        ],
    ],
];