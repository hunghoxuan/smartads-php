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

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "<?= $generator->generateTableName($tableName) ?>".
 */
class <?= $className ?>API extends <?= $className ?>
{
    //Customize fields to be displayed in API
    const COLUMNS_API = [<?php foreach ($tableSchema->columns as $column) {
    if (!FHtml::isInArray($column->name, FHtml::FIELDS_HIDDEN))
        echo "'" . $column->name . "', ";
}?>];

    public function fields()
    {
        $fields = $this::COLUMNS_API;

        return $fields;
    }

    public function rules()
    {
        //No Rules required for API object
        return [];
    }
}
