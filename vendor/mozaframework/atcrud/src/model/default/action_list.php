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

namespace <?= str_replace('models', 'actions', $generator->ns) ?>;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;
use <?= $generator->ns ?>\<?= $className ?>API;
use common\actions\BaseApiAction;

/**



* This is the customized model class for table "<?= $generator->generateTableName($tableName) ?>".
*/
class <?= $className ?>Action extends BaseApiAction
{
public $is_secured = false;
public function run()
{
if (($re = $this->isAuthorized()) !== true)
return $re;

if (!empty($this->objectid)) {

$object = <?= $className ?>API::findOne($this->objectid);

$out = FHtml::getOutputForAPI($object, $this->objectname, '', 'data', 1);
$out['code'] = $this->objectid;
return $out;
} else {

$list = <?= $className ?>API::getDataProvider(Fhtml::mergeRequestParams(['name' => '%'.$this->keyword], $this->paramsArray), $this->orderby, $this->limit, $this->page, false);
$out = FHtml::getOutputForAPI($list->getModels(), $this->listname, '', 'data', $list->pagination->pageCount);
$out['code'] = $this->params;
return $out;
}
}
}