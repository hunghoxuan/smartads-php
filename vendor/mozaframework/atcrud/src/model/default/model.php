<?php
/**
 * This is the template for generating the model class of a specified table.
 */

use common\components\FHtml;
use mozaframework\atcrud\Helper;
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

$searchConditions = $generator->generateSearchConditions(false, $tableSchema);
$searchExactConditions = $generator->generateSearchConditions(true, $tableSchema);

$columnArray = [];
foreach ($tableSchema->columns as $column)
{
    $columnArray[] = $column->name;
}

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

use common\components\FHtml;
use common\components\FActiveDataProvider;


class <?= $className ?> extends <?= $className ?>Base
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
            $related_objects = array_merge($related_objects, explode(',', Helper::prepareStringForExplode($array['related'])));

        if (isset($array['editor']) && in_array($array['editor'], ['file', 'image', 'upload']) || $column->size == 300 || FHtml::isInArray($column->name, FHtml::FIELDS_UPLOAD))
            $upload_fields[] = $column->name;

        if (isset($array['meta']))
            $meta_objects = array_merge($meta_objects, explode(',', Helper::prepareStringForExplode($array['meta'])));

        if (isset($array['lookup'])) {
            if ((\yii\helpers\StringHelper::startsWith($array['lookup'], '@') && $column->name  != 'category_id'))
                $lookup_objects = array_merge($lookup_objects, [$column->name => $array['lookup']]);
        }
    }
}
?>
    const LOOKUP = [
<?php
foreach ($tableSchema->columns as $column) {
    if (!empty($column->comment)) {
        $array = FHtml::toArrayFromDbComment($column->comment, $column->name);
        if (isset($array['data'])) {
            $a = $array['data'];
?>
        <?= "'$column->name'" ?> => [
<?php foreach ($a as $key => $value) {?>
            <?= "['id' => " . $className . "::" . strtoupper($column->name) . "_" . str_replace(' ', '', str_replace('-', '_', strtoupper($key))) . ", 'name' => '" . $key . "'],\n" ?>
<?php } ?>
        ],
<?php } else if (in_array($column->name, FHtml::FIELDS_STATUS)) { ?>
    <?= "'$column->name'" ?> => [],
<?php }
    }
}?>
    ];

<?php
    $column_upload = array();
    foreach ($labels as $name => $label) {
    if (FHtml::isInArray($name, FHtml::FIELDS_UPLOAD) || in_array($name, $upload_fields)) {
        $column_upload[] = "'$name'";
    }
}
if (count($column_upload) != 0) { ?>
    const COLUMNS_UPLOAD = [<?= implode(', ', $column_upload)?> ];
<?php } else { ?>
    const COLUMNS_UPLOAD = [];
<?php }  ?>

<?php
$column_order = array();
foreach ($labels as $name => $label) {
    if (FHtml::isInArray($name, ['sort_order'])) {
        $column_order[] = $name . " asc";
    }
    if (FHtml::isInArray($name, ['id'])) {
        $column_order[] = $name . " desc";
    }
}
if (count($column_upload) != 0) { ?>
    public $order_by = '<?= implode(', ', $column_order)?>';
<?php } ?>

    public $order_by = 'id desc';

<?php
$column_related = array();
foreach ($related_objects as $object) {
    $column_related[] = "'$object'";
}
$column_meta = array();
foreach ($meta_objects as $object) {
    $column_meta[] = "'$object'";
}
?>
    const OBJECTS_META = [<?= implode(', ', $column_meta)?>];
    const OBJECTS_RELATED = [<?= implode(', ', $column_related)?>];

    public static function getLookupArray($column = '')
    {
        if (key_exists($column, self::LOOKUP))
            return self::LOOKUP[$column];
        return [];
    }

<?php foreach ($related_objects as $object) {
    $object_field_name = str_replace('\\', '_', $object);

    $arr = explode('\\', $object);
    if (count($arr) > 1)
    {
        $object_type = $arr[0];
        $object_relation = $arr[1];
    } else
    {
        $object_type = $object;
        $object_relation = '';
    }
    ?>
    // Related Object: <?= $object_type ?>

    private $<?=$object_field_name?>;

    public function get<?= \yii\helpers\BaseInflector::camelize($object_field_name) ?>()
    {
        if (!isset($this-><?=$object_field_name?>))
            $this-><?=$object_field_name?> = FHtml::getRelatedModels($this->getTableName(), $this->id, '<?=$object_type?>', '<?= $object_relation ?>');

        return $this-><?=$object_field_name?>;
    }

    public function set<?= \yii\helpers\BaseInflector::camelize($object_field_name) ?>($value)
    {
        $this-><?=$object_field_name?> = $value;
    }
<?php } ?>

<?php foreach ($lookup_objects as $object => $lookup_object) {
    $object_field_name = str_replace('\\', '_', $object);
    $object_field_name = str_replace('_id', '', $object_field_name);
    $lookup_object = str_replace('@', '', $lookup_object);

    $arr = explode('\\', $object);
    if (count($arr) > 1)
    {
        $object_type = $arr[0];
        $object_relation = $arr[1];
    } else
    {
        $object_type = $object;
        $object_relation = '';
    }
    ?>

    // Lookup Object: <?= $object_field_name."\n" ?>
    public $<?=$object_field_name?>;

    public function get<?= \yii\helpers\BaseInflector::camelize($object_field_name) ?>()
    {
        if (!isset($this-><?=$object_field_name?>))
            $this-><?=$object_field_name?> = FHtml::getModel('<?=$lookup_object?>', '', $this-><?=$object_field_name?>_id, '', false);

        return $this-><?=$object_field_name?>;
    }

    public function set<?= \yii\helpers\BaseInflector::camelize($object_field_name) ?>($value)
    {
        $this-><?=$object_field_name?> = $value;
    }
<?php } ?>

<?php foreach ($meta_objects as $object): ?>
    // Meta Field: <?= $object ?>\n
    public function getModelMeta<?= \yii\helpers\BaseInflector::camelize($object) ?>($type = '')
    {
        $type = empty($type) ? FHtml::getFieldValue($this, 'type') : $type;
        return FHtml::getModel($this->getTableName(), $type, $this->id);
    }
<?php endforeach; ?>

    // add custom (default) search params here
    public function getDefaultFindParams()
    {
        $arr = [];
        return $arr;
    }

    public function prepareCustomFields()
    {
        parent::prepareCustomFields();

<?php foreach ($lookup_objects as $object => $lookup_object) {
    $object_field_name = str_replace('\\', '_', $object);
    $object_field_name = str_replace('_id', '', $object_field_name);
    $lookup_object = str_replace('@', '', $lookup_object);

    $arr = explode('\\', $object);
    if (count($arr) > 1)
    {
        $object_type = $arr[0];
        $object_relation = $arr[1];
    } else
    {
        $object_type = $object;
        $object_relation = '';
    }
    ?>
        $this-><?=$object_field_name?> = self::get<?= \yii\helpers\BaseInflector::camelize($object_field_name) ?>();
<?php } ?>
<?php foreach ($related_objects as $object) {
    $object_field_name = str_replace('\\', '_', $object);

    $arr = explode('\\', $object);
    if (count($arr) > 1)
    {
        $object_type = $arr[0];
        $object_relation = $arr[1];
    } else
    {
        $object_type = $object;
        $object_relation = '';
    }
    ?>
        $this-><?=$object_field_name?> = self::get<?= \yii\helpers\BaseInflector::camelize($object_field_name) ?>();
<?php } ?>
    }

    public function getPreviewFields() {
        return ['name'];
    }

    public static function getRelatedObjects()
    {
        return self::OBJECTS_RELATED;
    }

    public static function getMetaObjects()
    {
        return self::OBJECTS_META;
    }

    public function getDefaultFindParams()
    {
        return [];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $andWhere = '')
    {
        return parent::search($params, $andWhere);

/*
        $query = <?= $className ?>::find();

        $dataProvider = new FActiveDataProvider([
            'query' => $query,
        ]);

        $searchExact = FHtml::getRequestParam('SearchExact', false);

        FHtml::loadParams($this, $params);

        if ($searchExact) {
            <?= implode("\n        ", $searchExactConditions) ?>
        } else {
            <?= implode("\n        ", $searchConditions) ?>
        }

        if (!empty($andWhere))
            $query->andWhere($andWhere);

        $params = $this->getDefaultFindParams();
        if (!empty($params))
            $query->andWhere($params);

        if (empty(FHtml::getRequestParam('sort')))
            $query->orderby(FHtml::getOrderBy($this));

        return $dataProvider;
*/
    }
}