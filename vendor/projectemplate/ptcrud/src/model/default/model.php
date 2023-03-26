<?php
use projectemplate\ptcrud\Helper;
use yii\helpers\BaseInflector;

/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $queryClassName string query class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */


//image and attachment field

$image_fields = array(
    'thumbnail',
    'image',
    'icon',
    'logo',
    'avatar',
    'cover'
);

$file_fields = array(
    'attachment',
);
$file_names = array();
foreach ($labels as $name => $label){
    $file_names [] = $name;
}
$check_image = array_intersect($image_fields, $file_names);
$check_file = array_intersect($file_fields, $file_names);

if(count($check_image)!=0) {
    foreach ($check_image as $field_image_original_name) {
        $field_image_name = $field_image_original_name.'_file';
        $field_image_label = \yii\helpers\Inflector::camel2words($field_image_name);
        $labels[$field_image_name] = $field_image_label;
        $rules[] = "[['$field_image_name'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg, gif, png']";
    }
}
if(count($check_file)!=0) {
    foreach ($check_file as $field_file_original_name) {
        $field_file_name = $field_file_original_name . '_file';
        $field_file_label = \yii\helpers\Inflector::camel2words($field_file_name);
        $labels[$field_file_name] = $field_file_label;
        $rules[] = "[['$field_file_name'], 'file', 'skipOnEmpty' => true]";
    }
}
//lookup and dropdown
$lookup = array();
$dropdown = array();

foreach ($tableSchema->columns as $column) :
    if (!empty($column->comment)) {
        $check_keyword = Helper::keyword($column->comment);
        if ($check_keyword !== false) {
            $keyword = $check_keyword;
            if ($keyword == Helper::DROPDOWN_KEYWORD) {
                $comment_data = Helper::dropdownDataFromDbComment($column->comment, $keyword);
                if (count($comment_data) != 0) {
                    $type = $comment_data['type'];
                    foreach ($comment_data['data'] as $key => $value) {
                        if($type == Helper::SIMPLE){
                            $constant = $value;
                            $constantValue = $value;
                            $constantKey = Helper::constantKey($column->name, $constant);
                            $option_value = ucfirst($value);

                        } else{
                            $constant = $value;
                            $constantValue = $key;
                            $constantKey = Helper::constantKey($column->name, $constant);
                            $option_value = $value;
                        }
                        $dropdown[$column->name][] = array(
                            'option_constant' => $constantKey,
                            'option_key' => $value,
                            'option_value' => $option_value
                        );
                    }
                }
            }
            if ($keyword == Helper::LOOKUP_KEYWORD){
                $lookup[$column->name] = Helper::lookupDataFromDbComment($column->comment, $keyword);
            }
        }
    }
endforeach;

//content start here
echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

use Yii;
use common\components\FHtml;

<?php if(count($lookup) != 0): ?>
use yii\helpers\ArrayHelper;
<?php endif; ?>

/**
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
 * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?= $className ?> extends <?= $className ?>Base
{
<?php if(count($check_image)!=0) : ?>
<?php foreach ($check_image as $field_image_original_name): ?>
    <?= 'public $'.$field_image_original_name."_file;\n" ?>
<?php endforeach; ?>
<?php endif; ?>
<?php if(count($check_file)!=0) : ?>
<?php foreach ($check_file as $field_file_original_name) : ?>
    <?= 'public $'.$field_file_original_name."_file;\n" ?>
<?php endforeach; ?>
<?php endif; ?>

<?php if ($generator->db !== 'db'): ?>

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return \common\components\FHtml::currentDb();
    }
<?php endif; ?>
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [<?= "\n            " . implode(",\n            ", $rules) . ",\n        " ?>];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
<?php foreach ($labels as $name => $label): ?>
            <?= "'$name' => " . $generator->generateString($label) . ",\n" ?>
<?php endforeach; ?>
        ];
    }
<?php foreach ($relations as $name => $relation): ?>

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get<?= $name ?>()
    {
        <?= $relation[0] . "\n" ?>
    }
<?php endforeach; ?>
<?php if ($queryClassName): ?>
<?php
    $queryClassFullName = ($generator->ns === $generator->queryNs) ? $queryClassName : '\\' . $generator->queryNs . '\\' . $queryClassName;
    echo "\n";
?>
    /**
     * @inheritdoc
     * @return <?= $queryClassFullName ?> the active query used by this AR class.
     */
    public static function find()
    {
        return new <?= $queryClassFullName ?>(get_called_class());
    }
<?php endif; ?>
<?php if(count($dropdown)> 0) : ?>
<?php foreach ($dropdown as $field_name => $data): ?>

    public static function <?= $field_name ?>Array()
    {
        return array(
<?php foreach ($data as $item): ?>
            '<?= $item['option_key'] ?>' => <?= $generator->generateString($item['option_value']) ?>,
<?php endforeach; ?>
        );
    }

    public static function <?= $field_name ?>Label($key)
    {
        $str = array(
<?php foreach ($data as $item): ?>
            <?= $className ?>Base::<?= $item['option_constant'] ?> => '<span class="label label-sm label-info">' . <?= $generator->generateString($item['option_value']) ?> . '</span>',
<?php endforeach; ?>
        );
        return isset($str[$key]) ? $str[$key] : '';
    }
<?php endforeach; ?>
<?php endif; ?>
<?php if(count($lookup)> 0) : ?>

    public static function lookupData($table_name, $key, $value)
    {
        $result = array();
        $objects = array();
<?php foreach ($lookup as $field_name => $data): ?>
        if($table_name == "<?= $data['table'] ?>"){
            $objects = <?= BaseInflector::camelize($data['table']) ?>::find()->all();
        }
<?php endforeach; ?>
        if(count($objects)!=0){
            $result = ArrayHelper::map($objects, $key, $value);
        }
        return $result;
    }

    public static function lookupLabel($table_name, $lookup_key, $lookup_value, $needle)
    {
        if(is_numeric($lookup_value)){
            $condition = "$lookup_key = $lookup_value";
        }else{
            $condition = "$lookup_key = '$lookup_value'";
        }
<?php foreach ($lookup as $field_name => $data): ?>
        if($table_name == "<?= $data['table'] ?>"){
            $object = <?= BaseInflector::camelize($data['table']) ?>::find()->where($condition)->one();
        }
<?php endforeach; ?>
        return isset($object) ? $object->{$needle} : "";
    }
<?php endif; ?>
}