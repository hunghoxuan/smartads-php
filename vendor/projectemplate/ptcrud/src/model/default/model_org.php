<?php
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

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

/**
 * This is the model class for table "<?= $generator->generateTableName($tableName) ?>".
 *
<?php foreach ($tableSchema->columns as $column): ?>
 * @property <?= "{$column->phpType} \${$column->name}\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
 * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?= $className ?> extends <?= '\\' . ltrim($generator->baseClass, '\\') . "\n" ?>
{
<?php
    $file_fields = array(
        'image',
        'icon',
        'logo',
        'avatar',
        'attachment',
    );
    $file_names = array();
    foreach ($labels as $name => $label){
        $file_names [] = $name;
    }

    $check = array_intersect($file_fields, $file_names)

?>

<?php if(count($check)!=0) {
        foreach ($check as $field_name){ ?>
    <?= 'public $'.$field_name."_file;\n" ?>
<?php
        }
}
?>

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '<?= $generator->generateTableName($tableName) ?>';
    }
<?php if ($generator->db !== 'db'): ?>

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('<?= $generator->db ?>');
    }
<?php endif; ?>

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [<?= "
        \n            " . implode(",\n            ", $rules) . "," ?>
        <?php if(count($check)!=0) {
            foreach ($check as $field_name){ ?>
                <?= "\n            [['" ?><?= $field_name ?><?="_file'], 'file','skipOnEmpty' => true, 'extensions'=>'jpg, gif, png'],"?>
                <?php
            }
        } ?>
        <?= "\n        " ?>];
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
<?php if(count($check)!=0): ?>
<?php foreach ($check as $field_original_name):
            $field_name = $field_original_name.'_file';
            $field_label = \yii\helpers\Inflector::camel2words($field_name); ?>
            <?= "'$field_name' => " . $generator->generateString($field_label) . ",\n" ?>
    <?php endforeach; ?>
<?php endif ?>
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
}
