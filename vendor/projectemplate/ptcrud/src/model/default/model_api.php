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
foreach ($tableSchema->columns as $column)
{
    $columnArray[] = $column->name;
}

echo "<?php\n";
?>
namespace <?= $generator->ns ?>;

class <?= $className ?>API extends <?= $className ?>Base
{
    public function fields()
    {
        $fields = parent::fields();
        return $fields;
    }

    public function rules()
    {
        return [];
    }
}