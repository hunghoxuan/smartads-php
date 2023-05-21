<?php

/**
 *
 ***
 * This is the customized model class for table "SettingsText".
 */

use yii\helpers\Url;
use common\components\FHtml;
use common\components\Helper;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$currentRole = FHtml::getCurrentRole();
$moduleName = 'SettingsText';
$moduleTitle = 'Settings Text';
$moduleKey = 'settings-text';
$object_type = 'settings_text';

$form_type = FHtml::getRequestParam('form_type');

$isEditable = FHtml::isInRole('', 'update');

return [

    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    //    [
    //        'class'=>'kartik\grid\ExpandRowColumn',
    //        'width'=>'30px',
    //        'value'=>function ($model, $key, $index, $column) {
    //        return GridView::ROW_COLLAPSED;
    //        },
    //        'detail'=>function ($model, $key, $index, $column) {
    //             return Yii::$app->controller->renderPartial('_view', ['model'=>$model, 'print' => false]);
    //        },
    //        'headerOptions'=>['class'=>'kartik-sheet-style'],
    //        'expandOneOnly'=>false
    //    ],
    //    [ //name: id, dbType: int(11), phpType: integer, size: 11, allowNull:
    //        'class'=>'kartik\grid\DataColumn',
    //        'format'=>'html',
    //        'attribute'=>'id',
    //        'visible' => FHtml::isVisibleInGrid($object_type, 'id', $form_type),
    //        'value' => function($model) { return '<b>' . FHtml::showContent($model-> id, FHtml::SHOW_NUMBER, 'settings_text', 'id','int(11)','settings-text') . '</b>' ; },
    //        'hAlign'=>'center',
    //        'vAlign'=>'middle',
    //        'width'=>'50px',
    //    ],
    //    [ //name: lang, dbType: varchar(100), phpType: string, size: 100, allowNull:
    //        'class' => FHtml::getColumnClass($object_type, 'lang', $form_type),
    //        'format'=>'raw',
    //        'attribute'=>'lang',
    //        'visible' => FHtml::isVisibleInGrid($object_type, 'lang', $form_type),
    //        'value' => function($model) { return FHtml::showContent($model-> lang, FHtml::SHOW_LABEL, 'settings_text', 'lang','varchar(100)','settings-text'); },
    //        'hAlign'=>'left',
    //        'vAlign'=>'middle',
    //        'width'=>'80px',
    //        'filterType' => GridView::FILTER_SELECT2,
    //        'filterWidgetOptions'=>[
    //                            'pluginOptions'=>['allowClear' => true],
    //                            ],
    //        'filterInputOptions'=>['placeholder'=>''],
    //        'filter'=> FHtml::getComboArray('settings_text', 'settings_text', 'lang', true, 'id', 'name'),
    //    ],
    //    [ //name: name, dbType: varchar(255), phpType: string, size: 255, allowNull:
    //        'class' => FHtml::COLUMN_VIEW,
    //        'format'=>'raw',
    //        'attribute'=>'group',
    //        'value' =>  function($model) {
    //            $a = FHtml::getFieldValue($model, 'name');
    //            $arr = explode('.', $a);
    //            return (count($arr) > 1) ? $arr[0] : $a;
    //        },
    //        'hAlign'=>'left',
    //        'vAlign'=>'middle',
    //        'contentOptions'=>['class'=>'col-md-1 nowrap form-label'],
    //    ],
    [ //name: name, dbType: varchar(255), phpType: string, size: 255, allowNull:  
        'class' => FHtml::COLUMN_VIEW,
        'format' => 'raw',
        'attribute' => 'name',
        //        'value' =>  function($model) {
        //            $a = FHtml::getFieldValue($model, 'name');
        //            $arr = explode('.', $a);
        //            $key = (count($arr) > 1) ? $arr[1] : $a;
        //            $group = (count($arr) > 1) ? $arr[0] : '';
        //            $key = str_replace('_', ' ', $key);
        //
        //            return "$key<br/><span style='color:grey;font-size:80%'>$group</span>";
        //        },
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'contentOptions' => ['class' => 'col-md-5 form-label', 'style' => 'max-width:300px !important; word-break: break-all'],
    ],

    [ //name: name, dbType: varchar(255), phpType: string, size: 255, allowNull:
        'class' => FHtml::COLUMN_VIEW,
        'format' => 'raw',
        'attribute' => 'content',
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'contentOptions' => ['class' => 'col-md-6 nowrap'],
    ],

];
