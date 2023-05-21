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
foreach ($tableSchema->columns as $column) {
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
*
***
* This is the model class for table "<?= $generator->generateTableName($tableName) ?>".
*

<?php foreach ($tableSchema->columns as $column) : ?>
    * @property <?= "{$column->phpType} \${$column->name}\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)) : ?>
    *
    <?php foreach ($relations as $name => $relation) : ?>
        * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
    <?php endforeach; ?>
<?php endif; ?>
*/
class <?= $className ?>Base extends \common\models\BaseModel //<?= '\\' . ltrim($generator->baseClass, '\\') . "\n" ?>
{
<?php
$lookup = '';
$meta_objects = [];
$related_objects = [];

foreach ($tableSchema->columns as $column) {
    if (!empty($column->comment)) {
        $array = FHtml::toArrayFromDbComment($column->comment, $column->name);

        if (isset($array['data'])) {
            $a = $array['data'];
            $str = '';
            foreach ($a as $key => $value) {
                if (is_numeric($value))
                    echo "    const " . strtoupper($column->name) . "_" . str_replace(' ', '', str_replace('-', '_', strtoupper($key))) . " = $value;\n";
                else
                    echo "    const " . strtoupper($column->name) . "_" . str_replace(' ', '', str_replace('-', '_', strtoupper($key))) . " = '$value';\n";
            }
        }
    }
}
?>

/**
* @inheritdoc
*/
public $tableName = '<?= $generator->generateTableName($tableName) ?>';

public static function tableName()
{
return '<?= $generator->generateTableName($tableName) ?>';
}

/**
* @return \yii\db\Connection the database connection used by this AR class.
*/
public static function getDb()
{
return FHtml::currentDb();
}

/**
* @inheritdoc
*/
public function rules()
{
return [
<?= "\n            [['" . implode('\', \'', $columnArray)  . "'], 'filter', 'filter' => 'trim'],\n        " ?>
<?= "\n            " . implode(",\n            ", $rules) . ",\n        " ?>];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
<?php foreach ($labels as $name => $label) : ?>
    <?= "'$name' => " . $generator->generateString($label, null, $className) . ",\n" ?>
<?php endforeach; ?>
];
}

public static function tableSchema()
{
return FHtml::getTableSchema(self::tableName());
}

public static function Columns()
{
return self::tableSchema()->columns;
}

public static function ColumnsArray()
{
return ArrayHelper::getColumn(self::tableSchema()->columns, 'name');
}

public function init()
{
parent::init();
$this->registerTranslations();
}

public function registerTranslations()
{
$i18n = Yii::$app->i18n;
$i18n->translations['<?= $className ?>*'] = [
'class' => 'common\components\FMessageSource',
'basePath' => '@<?= str_replace('models', 'messages', str_replace('\\', '/', $generator->ns)) ?>',
'fileMap' => [
'<?= $className ?>' => '<?= $className ?>.php',
],
];
}


<?php foreach ($relations as $name => $relation) : ?>

    /**
    * @return \yii\db\ActiveQuery
    */
    public function get<?= $name ?>()
    {
    <?= $relation[0] . "\n" ?>
    }
<?php endforeach; ?>


}