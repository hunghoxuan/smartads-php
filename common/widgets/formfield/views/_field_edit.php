<?php
use common\components\FHtml;
use common\components\Helper;
use yii\helpers\Url;

?>

<?php
$type = '';
if (isset($field) && !empty($field))
    $attribute = $field;

if (isset($column) && !empty($column->editor)) {
    $editor = $column->editor;
    $lookup = $column->lookup;
}

?>

<?php

echo FHtml::buildEditor($model, $attribute, $form, $editor, $lookup);

?>



