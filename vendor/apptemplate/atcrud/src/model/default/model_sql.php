<?php
/**
 * This is the template for generating the model class of a specified table.
 */

use common\components\FHtml;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $queryClassName string query class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

$columnArray = [];
$primaryField = '';
foreach ($tableSchema->columns as $column)
{
    $columnArray[] = $column->name;

    if ($column->isPrimaryKey)
        $primaryField = $column->name;
}

?>
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `<?= $tableName ?>`;
CREATE TABLE `<?= $tableName ?>` (
<?php foreach ($tableSchema->columns as $column): ?>
    `<?= $column->name ?>` <?= $column->dbType ?> <?= !empty($column->defaultValue) ? "DEFAULT $column->defaultValue" : '' ?> <?= $column->allowNull ? 'NULL' : 'NOT NULL' ?> <?= $column->autoIncrement ? 'AUTO_INCREMENT' : '' ?> <?= !empty($column->comment) ? " COMMENT $column->comment" : '' ?>,
<?php endforeach; ?>
    PRIMARY KEY (`<?= $primaryField ?>`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

<?php if (!empty($relations)): ?>
<?php foreach ($relations as $name => $relation): ?>
<?php endforeach; ?>
<?php endif; ?>

