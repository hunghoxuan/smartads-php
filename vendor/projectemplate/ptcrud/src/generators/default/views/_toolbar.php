<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$quick_set_fields = array(
    'status',
    'type',
);

$columnsNames = $generator->getColumnNames();
$tableSchema = $generator->getTableSchema();

$check = array();

foreach ($columnsNames as $name){
    $column = $tableSchema->columns[$name];
    if($column->dbType == 'tinyint(1)' || in_array($name, $quick_set_fields)){
        $check[] = $name;
    }
}


$modelClass = StringHelper::basename($generator->modelClass);
$table_name = $generator->tableSchema->name;

echo "<?php\n";
?>

use yii\helpers\Html;
use kartik\dropdown\DropdownX;
use common\components\FHtml;

$bulkActionButton = '<div class="dropdown pull-left">&nbsp;<button class="btn btn-default" data-toggle="dropdown">' . Yii::t('common', 'Actions') . '</button>' .
    DropdownX::widget([
        'items' => \yii\helpers\ArrayHelper::merge(
<?php if(count($check)!=0):?>
<?php foreach($check as $field) :?>
            [FHtml::buildBulkActionsMenu(FHtml::CHANGE_TYPE, Yii::t('common', 'Set') . ' ' . Yii::t('common', '<?= Inflector::camel2words($field) ?>'), '<?= $table_name ?>', '<?= $field ?>')],
<?php endforeach; ?>
            ['<li class="divider"></li>'],
<?php endif; ?>
            //[FHtml::buildBulkActionsMenu(FHtml::CLEAR_TYPE, ucwords(FHtml::CLEAR_TYPE) . ' ' . Yii::t('common', 'Amount'), false, 'amount')],
            //[FHtml::buildBulkActionsMenu(FHtml::FILL_TYPE, ucwords(FHtml::FILL_TYPE) . ' ' . Yii::t('common', 'Amount'), false, 'amount')],
            ['<li class="divider"></li>'],
            [FHtml::buildBulkDeleteMenu()]
        )
    ])
    . '</div>';

return [
    [
        'content' =>
            Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                [
                    'role' => $this->params['displayType'],
                    'data-pjax' => $this->params['isAjax'] == true ? 1 : 0,
                    'title' => Yii::t('common', 'title.create'),
                    'class' => 'btn btn-success',
                    'style' => 'float:left;'
                ]) .
            '{export}' .
            Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''], ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => Yii::t('common', 'Reset Grid')]) .
            '{toggleData}' .
            $bulkActionButton,
        'options' => ['class' => 'text-right kv-panel-before']
    ],
];