<?php
use yii\helpers\Url;
use common\components\FHtml;

use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$colorPluginOptions = [
    'showPalette' => true,
    'showPaletteOnly' => true,
    'showSelectionPalette' => true,
    'showAlpha' => false,
    'allowEmpty' => true,
    'preferredFormat' => 'name',
    'palette' => [
        [
            "white", "black", "grey", "silver", "gold", "brown",
        ],
        [
            "red", "orange", "yellow", "indigo", "maroon", "pink"
        ],
        [
            "blue", "green", "violet", "cyan", "magenta", "purple",
        ],
    ]
];

$currentRole = FHtml::getCurrentRole();
$moduleName = 'ObjectType';

$classControl = '';
$isEditable = true;
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
    [
        'class' => 'kartik\grid\ExpandRowColumn',
        'width' => '50px',
        'value' => function ($model, $key, $index, $column) {
            return GridView::ROW_COLLAPSED;
        },
        'detail' => function ($model, $key, $index, $column) {
            return Yii::$app->controller->renderPartial('_view', ['model' => $model]);
        },
        'headerOptions' => ['class' => 'kartik-sheet-style'],
        'expandOneOnly' => false
    ],
    [ //name: metaKey, dbType: varchar(255), phpType: string, size: 255, allowNull:
        'format'=>'raw',
        'attribute'=>'group',
        'visible' => true,
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-md-1 nowrap'],
        'group' => true,
        'groupedRow' => true,
    ],
    [ //name: object_type, dbType: varchar(255), phpType: string, size: 255, allowNull:  
        'class' => 'kartik\grid\DataColumn',
        'format' => 'raw',
        'attribute' => 'object_type',
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'contentOptions' => ['class' => 'col-md-2 nowrap', 'style' => ''],
        'editableOptions' => [
            'size' => 'md',
            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
            'widgetClass' => 'kartik\datecontrol\InputControl',
            'options' => [
                'options' => [
                    'pluginOptions' => [
                        'autoclose' => true
                    ]
                ]
            ]
        ],
    ],
    [ //name: name, dbType: varchar(255), phpType: string, size: 255, allowNull:  
        'class' => $classControl,
        'attribute' => 'name',
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'contentOptions' => ['class' => 'col-md-5 nowrap'],
        'editableOptions' => [
            'size' => 'md',
            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
            'widgetClass' => 'kartik\datecontrol\InputControl',
            'options' => [
                'options' => [
                    'pluginOptions' => [
                        'autoclose' => true
                    ]
                ]
            ]
        ],
    ],
//    [ //name: sort_order, dbType: int(5), phpType: integer, size: 5, allowNull:
//        'class'=>$classControl,
//        'format'=>['decimal', 0],
//        'attribute'=>'sort_order',
//        'value' => function($model) { return FHtml::showContent($model-> sort_order, '', 'object_type', 'sort_order','int(5)','object-type'); },
//        'hAlign'=>'right',
//        'vAlign'=>'middle',
//        'width'=>'50px',
//        'editableOptions'=>[
//                            'size'=>'md',
//                            'inputType'=>\kartik\editable\Editable::INPUT_SPIN,
//                            'options'=>[
//                                'pluginOptions'=>[
//                                    'min'=>0, 'max' => 500000
//                                ]
//                            ]
//                        ],
//    ],
    [ //name: is_active, dbType: tinyint(1), phpType: integer, size: 1, allowNull:
        'class' => $classControl,
        'format'=>'html',

        'attribute' => 'is_system',
        //'value' => function($model) { return FHtml::showBoolean($model-> is_active); },
        'value' => function ($model) {
            return FHtml::showContent($model->is_system, FHtml::SHOW_ACTIVE, 'object_type', 'is_system', 'tinyint(1)', 'object-type');
        },
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '20px',
        'editableOptions'=>[
            'size'=>'md',
            'inputType'=>\kartik\editable\Editable::INPUT_SWITCH,
            'options'=>[
                'options'=>[
                    'pluginOptions'=>[
                        'autoclose'=>true
                    ]
                ]
            ]
        ],
    ],
    [ //name: is_active, dbType: tinyint(1), phpType: integer, size: 1, allowNull:  
        'class' => $classControl,
        'format'=>'html',

        'attribute' => 'is_active',
        //'value' => function($model) { return FHtml::showBoolean($model-> is_active); },
        'value' => function ($model) {
            return FHtml::showContent($model->is_active, FHtml::SHOW_ACTIVE, 'object_type', 'is_active', 'tinyint(1)', 'object-type');
        },
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '20px',
        'editableOptions'=>[
            'size'=>'md',
            'inputType'=>\kartik\editable\Editable::INPUT_SWITCH,
            'options'=>[
                'options'=>[
                    'pluginOptions'=>[
                        'autoclose'=>true
                    ]
                ]
            ]
        ],
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => $this->params['buttonsType'], // Dropdown or Buttons
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '80px',
        'urlCreator' => function ($action, $model) {
            return Url::to([$action, 'id' => $model->object_type]);
        },
        'template' => '{update}{delete}',
        'visibleButtons' => [
            'update' => FHtml::isInRole('', 'update', $currentRole),
            'delete' => FHtml::isInRole('', 'delete', $currentRole),
        ],
        'viewOptions' => ['role' => $this->params['displayType'], 'title' => FHtml::t('common', 'title.view'), 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => $this->params['editType'], 'title' => FHtml::t('common', 'title.update'), 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote',
            'title' => FHtml::t('common', 'title.delete'),
            'data-confirm' => false,
            'data-method' => false,// for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => FHtml::t('message', 'Confirmation'),
            'data-confirm-message' => FHtml::t('common', 'message.confirmdelete')
        ],
    ],
];