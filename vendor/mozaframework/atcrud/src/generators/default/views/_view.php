<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use \common\components\FHtml;
use common\widgets\FDetailView;
use yii\widgets\Pjax;

$moduleName = '<?= StringHelper::basename($generator->modelClass) ?>';

$role = isset($role) ? $role : FHtml::getCurrentRole();
$action = isset($action) ? $action : FHtml::currentAction();

$print = isset($print) ? $print : true;
$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($action) ? false : true);

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
?>
<?= "<?php" ?> if (!Yii::$app->request->isAjax) {
    $this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
    $this->params['toolBarActions'] = array(
        'linkButton' => array(),
        'button' => array(),
        'dropdown' => array(),
    );
    $this->params['mainIcon'] = 'fa fa-list';
}<?= " ?>\n" ?>
<?= "<?php "?>if ($ajax) Pjax::begin(['id' => 'crud-datatable'])<?= " ?>\n" ?>
<?= "<?php "?>if (Yii::$app->request->isAjax) {<?= " ?>\n" ?>
    <div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-view">
        <?= "<?= " ?>FDetailView::widget([
            'model' => $model,
            'attributes' => [
<?php
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) { ?>
           <?= $name . "',\n"; ?>
<?php }
} else {
        foreach ($generator->getTableSchema()->columns as $column) {
            $format = $generator->generateColumnFormat($column);
            echo "                '" . $column->name  . "',\n";
        }
    }
    ?>
            ],
        ]) ?>
    </div>
<?= '<?php } else { ?>' . "\n" ?>
    <div class="row" style="padding: 20px">
        <div class="col-md-12" style="background-color: white; padding: 20px">
            <?= "<?= " ?>FDetailView::widget([
                'model' => $model,
                'attributes' => [
<?php
if (($tableSchema = $generator->getTableSchema()) === false) {
foreach ($generator->getColumnNames() as $name) { ?>
                    <?= $name . "',\n"; ?>
<?php }
} else {
    foreach ($generator->getTableSchema()->columns as $column) {
        $format = $generator->generateColumnFormat($column); ?>
                    <?= "'".$column->name . "',\n"; ?>
<?php }
}
?>
                ],
            ]) ?>
        </div>
    </div>
<?= "<?php } ?>\n" ?>
<?= "<?php if (\$ajax) Pjax::end() ?>\n"  ?>