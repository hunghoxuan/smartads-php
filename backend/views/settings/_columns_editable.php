<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "Settings".
*/

use yii\helpers\Url;
use common\components\FHtml;

use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$currentRole = FHtml::getCurrentRole();
$moduleName = 'Settings';
$form_type = FHtml::getRequestParam('form_type');

$isEditable = FHtml::isInRole('', 'update');
if (FHtml::isInRole('', 'update', $currentRole)) {
    $classControl = 'kartik\grid\EditableColumn';
    $isEditable = true;
} else {
    $classControl = 'kartik\grid\DataColumn';
    $isEditable = false;
}

return [
    [
        'class' => 'common\widgets\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
//    [
//        'class'=>'kartik\grid\ExpandRowColumn',
//        'width'=>'50px',
//        'value'=>function ($model, $key, $index, $column) {
//        return GridView::ROW_COLLAPSED;
//        },
//        'detail'=>function ($model, $key, $index, $column) {
//        return Yii::$app->controller->renderPartial('_view_preview', ['model'=>$model]);
//        },
//        'headerOptions'=>['class'=>'kartik-sheet-style'],
//        'expandOneOnly'=>false
//    ],
    //[ //name: id, dbType: int(11), phpType: integer, size: 11, allowNull:  
        //'class'=>'kartik\grid\DataColumn',
        //'format'=>'raw',
        //'attribute'=>'id',
        //'visible' => FHtml::isVisibleInGrid($moduleName, 'id', $form_type, true),
        //'value' => function($model) { return FHtml::showContent($model->id, FHtml::SHOW_LABEL, 'settings', 'id','int(11)','settings'); }, 
        //'hAlign'=>'left',
        //'vAlign'=>'middle',
        //'filterType' => GridView::FILTER_SELECT2, 
        //'filterWidgetOptions'=>[
                            //'pluginOptions'=>['allowClear' => true],
                            //],
        //'filterInputOptions'=>['placeholder'=>''],
        //'filter'=> FHtml::getComboArray('settings', 'settings', 'id', true, 'id', 'name'),
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
        //'editableOptions'=> function ($model, $key, $index, $widget) {
                                    //$fields = FHtml::getComboArray('settings', 'settings', 'id', true, 'id', 'name');
                                    //return [
                                    //'inputType' => 'dropDownList',
                                    //'displayValueConfig' => $fields,
                                    //'data' => $fields
                                    //];
                                    //},
    //],
    [ //name: metaKey, dbType: varchar(255), phpType: string, size: 255, allowNull:
        'class' => $classControl,

        'format'=>'raw',
        'attribute'=>'group',
        'value' => function($model) { return FHtml::t('common', \yii\helpers\BaseInflector::camel2words($model->group)); },

        'visible' => true,
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-md-1 nowrap'],
        'group' => true,
        'groupedRow' => true,
    ],
    [ //name: metaKey, dbType: varchar(255), phpType: string, size: 255, allowNull:
        'format'=>'raw',
        'attribute'=>'metaKey',
        'edit_type' => 'default',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'metaKey', $form_type, true),
        'value' => function($model) { return '' . FHtml::t('common', \yii\helpers\BaseInflector::camel2words($model->metaKey)); },
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-md-3 nowrap'],
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
    [ //name: metaValue, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'format'=>'raw',
        'class' => $classControl,
        'attribute'=>'metaValue',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'metaValue', $form_type, true),
        'value' => function($model) { return FHtml::showContent($model->metaValue, $model->editor , '@' . $model->lookup, 'lookup','varchar(100)','settings'); },
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-md-6 nowrap'],
        'editableOptions'=> function($model, $attribute) {return FHtml::buildEditableOptionsInGridColumn($model, $attribute); },
    ],
//    [ //name: editor, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
//        'class'=>'kartik\grid\DataColumn',
//        'format'=>'raw',
//        'attribute'=>'editor',
//        'visible' => FHtml::isVisibleInGrid($moduleName, 'editor', $form_type, true),
//        'value' => function($model) { return FHtml::showContent($model-> editor, FHtml::SHOW_LABEL, 'editor', 'editor','varchar(255)','settings'); },
//        'hAlign'=>'left',
//        'vAlign'=>'middle',
//        'width'=>'100px',
//        'filterType' => GridView::FILTER_SELECT2,
//        'filterWidgetOptions'=>[
//                            'pluginOptions'=>['allowClear' => true],
//                            ],
//        'filterInputOptions'=>['placeholder'=>''],
//        'filter'=> FHtml::getComboArray('editor', 'editor', 'editor', true, 'id', 'name'),
//    ],
//    [ //name: lookup, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
//        'class'=>'kartik\grid\DataColumn',
//        'format'=>'raw',
//        'attribute'=>'lookup',
//        'visible' => FHtml::isVisibleInGrid($moduleName, 'lookup', $form_type, true),
//        'value' => function($model) { return FHtml::showContent($model-> lookup, FHtml::SHOW_LABEL, 'object_type', 'lookup','varchar(255)','settings'); },
//        'hAlign'=>'left',
//        'vAlign'=>'middle',
//        'width'=>'100px',
//        'filterType' => GridView::FILTER_SELECT2,
//        'filterWidgetOptions'=>[
//                            'pluginOptions'=>['allowClear' => true],
//                            ],
//        'filterInputOptions'=>['placeholder'=>''],
//        'filter'=> FHtml::getComboArray('object_type', 'object_type', 'lookup', true, 'id', 'name'),
//    ],
//    [ //name: is_system, dbType: tinyint(1), phpType: integer, size: 1, allowNull: 1
//        'class'=>'kartik\grid\BooleanColumn',
//        'format'=>'raw',
//        'attribute'=>'is_system',
//        'visible' => FHtml::isVisibleInGrid($moduleName, 'is_system', $form_type, true),
//        'value' => function($model) { return FHtml::showContent($model-> is_system, FHtml::SHOW_BOOLEAN, 'settings', 'is_system','tinyint(1)','settings'); },
//        'hAlign'=>'center',
//        'vAlign'=>'middle',
//        'width'=>'20px',
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
            'view' => false,
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