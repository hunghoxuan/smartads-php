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



* This is the customized model class for table "<?= $generator->generateTableName($tableName) ?>".
*/
class <?= $className ?> extends <?= $className ?>Base //<?= '\\' . ltrim($generator->baseClass, '\\') . "\n" ?>
{
<?php
$lookup = '';
$meta_objects = [];
$related_objects = [];
$upload_fields = [];
$lookup_objects = [];
$foreign_objects = [];

foreach ($tableSchema->columns as $column) {
    if (!empty($column->comment)) {
        $array = FHtml::toArrayFromDbComment($column->comment, $column->name);

        if (isset($array['related']))
            $related_objects = array_merge($related_objects, explode(',', $array['related']));

        if (isset($array['editor']) && in_array($array['editor'], ['file', 'image', 'upload']) || $column->size == 300 || FHtml::isInArray($column->name, FHtml::FIELDS_UPLOAD))
            $upload_fields[] = $column->name;

        if (isset($array['meta']))
            $meta_objects = array_merge($meta_objects, explode(',', $array['meta']));

        if (isset($array['lookup'])) {
            if ((\yii\helpers\StringHelper::startsWith($array['lookup'], '@') && $column->name  != 'category_id'))
                $lookup_objects = array_merge($lookup_objects, [$column->name => $array['lookup']]);
        }

        if (isset($array['data'])) {
            $a = $array['data'];
            $str = '';
            foreach ($a as $key => $value) {
                $str .= "\t['id' => " . $className . "::" . strtoupper($column->name) . "_" . str_replace(' ', '', str_replace('-', '_', strtoupper($key))) . ", 'name' => '" . $key . "'],\n ";
            }

            $lookup .= "        '" . $column->name . "' => [\n\t" . $str . "],\n";
        }
    }
}
?>
const LOOKUP = [<?= $lookup ?>];

const COLUMNS_UPLOAD = [<?php foreach ($labels as $name => $label) {
                            if (FHtml::isInArray($name, FHtml::FIELDS_UPLOAD) || in_array($name, $upload_fields))
                                echo "'" . $name . "',";
                        } ?>];

public $order_by = '<?php foreach ($labels as $name => $label) {
                        if (FHtml::isInArray($name, ['sort_order']))
                            echo $name . " asc,";
                    } ?><?php foreach ($labels as $name => $label) {
                                            if (FHtml::isInArray($name, ['is_promotion', 'is_top', 'is_hot', 'is_active', 'created_date']))
                                                echo $name . " desc,";
                                        } ?>';

const OBJECTS_RELATED = [<?php foreach ($related_objects as $object) {
                                echo "'" . $object . "', ";
                            } ?>];
const OBJECTS_META = [<?php foreach ($meta_objects as $object) {
                            echo "'" . $object . "', ";
                        } ?>];

public static function getLookupArray($column = '') {
if (key_exists($column, self::LOOKUP))
return self::LOOKUP[$column];
return [];
}

public function fields()
{
$fields = array_merge(parent::fields(), self::OBJECTS_RELATED);

foreach (self::COLUMNS_UPLOAD as $field) {
$this->{$field} = FHtml::getFileURL($this->{$field}, $this->getTableName());
}
return $fields;
}

<?php foreach ($related_objects as $object) {
    $object_field_name = str_replace('\\', '_', $object);

    $arr = explode('\\', $object);
    if (count($arr) > 1) {
        $object_type = $arr[0];
        $object_relation = $arr[1];
    } else {
        $object_type = $object;
        $object_relation = '';
    }
?>
    // Related Object: <?= $object_type ?>

    private $<?= $object_field_name ?>;

    public function get<?= \yii\helpers\BaseInflector::camelize($object_field_name) ?>() {
    if (!isset($this-><?= $object_field_name ?>))
    $this-><?= $object_field_name ?> = FHtml::getRelatedModels($this->getTableName(), $this->id, '<?= $object_type ?>', '<?= $object_relation ?>');

    return $this-><?= $object_field_name ?>;
    }

    public function set<?= \yii\helpers\BaseInflector::camelize($object_field_name) ?>($value) {
    $this-><?= $object_field_name ?> = $value;
    }
<?php } ?>

<?php foreach ($lookup_objects as $object => $lookup_object) {
    $object_field_name = str_replace('\\', '_', $object);
    $object_field_name = str_replace('_id', '', $object_field_name);
    $lookup_object = str_replace('@', '', $lookup_object);

    $arr = explode('\\', $object);
    if (count($arr) > 1) {
        $object_type = $arr[0];
        $object_relation = $arr[1];
    } else {
        $object_type = $object;
        $object_relation = '';
    }
?>

    // Lookup Object: <?= $object_field_name ?>\n
    public $<?= $object_field_name ?>;
    public function get<?= \yii\helpers\BaseInflector::camelize($object_field_name) ?>() {
    if (!isset($this-><?= $object_field_name ?>))
    $this-><?= $object_field_name ?> = FHtml::getModel('<?= $lookup_object ?>', '', $this-><?= $object_field_name ?>_id, '', false);

    return $this-><?= $object_field_name ?>;
    }
    public function set<?= \yii\helpers\BaseInflector::camelize($object_field_name) ?>($value) {
    $this-><?= $object_field_name ?> = $value;
    }
<?php } ?>

<?php foreach ($meta_objects as $object) : ?>
    // Meta Field: <?= $object ?>\n
    public function getModelMeta<?= \yii\helpers\BaseInflector::camelize($object) ?>($type = '') {
    $type = empty($type) ? FHtml::getFieldValue($this, 'type') : $type;
    return FHtml::getModel($this->getTableName(), $type, $this->id);
    }
<?php endforeach; ?>

public function prepareCustomFields() {
parent::prepareCustomFields();

<?php foreach ($lookup_objects as $object => $lookup_object) {
    $object_field_name = str_replace('\\', '_', $object);
    $object_field_name = str_replace('_id', '', $object_field_name);
    $lookup_object = str_replace('@', '', $lookup_object);

    $arr = explode('\\', $object);
    if (count($arr) > 1) {
        $object_type = $arr[0];
        $object_relation = $arr[1];
    } else {
        $object_type = $object;
        $object_relation = '';
    }
?>
    $this-><?= $object_field_name ?> = self::get<?= \yii\helpers\BaseInflector::camelize($object_field_name) ?>();
<?php } ?>
<?php foreach ($related_objects as $object) {
    $object_field_name = str_replace('\\', '_', $object);

    $arr = explode('\\', $object);
    if (count($arr) > 1) {
        $object_type = $arr[0];
        $object_relation = $arr[1];
    } else {
        $object_type = $object;
        $object_relation = '';
    }
?>
    $this-><?= $object_field_name ?> = self::get<?= \yii\helpers\BaseInflector::camelize($object_field_name) ?>();
<?php } ?>
}


public static function getRelatedObjects() {
return self::OBJECTS_RELATED;
}

public static function getMetaObjects() {
return self::OBJECTS_META;
}
}