<?php
use common\components\FHtml;
use frontend\assets\CustomAsset;

/* @var $model \frontend\models\ViewModel */
/* @var $this yii\web\View */
$id = isset($id) ? $id : rand(1, 1000);

$name = isset($name) ? $name : ((isset($model) && isset($attribute)) ? \yii\helpers\Html::getInputName($model, $attribute) : \yii\helpers\BaseInflector::camelize($id));

$baseUrl = FHtml::currentBaseURL();

$hide_select = isset($hide_select) ? $hide_select : false;
$show_label = isset($show_label) ? $show_label : true;
$multiple = isset($multiple) ? $multiple : false;

if ($multiple && !\yii\helpers\StringHelper::endsWith($name, '[]')) {
    $name = $name . '[]';
}

$data = isset($data) ? $data : null;

if (is_string($data) && !empty($data) && empty($models)) {
    if (FHtml::isTableExisted($data))
        $data = FHtml::findAll($data);
    else
        $data = FHtml::getComboArray($data);
} else if (empty($data) && !empty($models)) {
    $data = $models;
}

$item_width = isset($item_width) ? $item_width : '200px';
$item_height = isset($item_height) ? $item_height : 'auto';
$item_style = isset($item_style) ? $item_style : '';
$value = isset($value) ? $value : (isset($_POST[$name]) ? $_POST[$name] : null);
$css = isset($css) ? $css : '';
$selected_color = isset($selected_color) ? $selected_color : FHtml::settingBackendMainColor();
$show_image = isset($show_image) ? $show_image : true;
?>

<?php
//https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js
$this->registerJsFile("$baseUrl/backend/web/js/jquery.min.js", ['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile("$baseUrl/common/widgets/fselect/assets/ddslick-master/jquery.ddslick.js", ['position' => \yii\web\View::POS_HEAD]);

?>

<style>

</style>

<select id="<?= $id ?>" name="<?= $name ?>" class="image-picker show-html <?= $css ?>" <?= $multiple ? 'multiple' : '' ?>>
    <?php
    $result = [];
    foreach ($data as $i => $item) {
        if (is_string($item)) {
            $item_value = $i;
            $item_text = $item;
            $item_image = '';
            $item_description = '';
        } else {
            $item_value = FHtml::getFieldValue($item, ['id', 'key', 'value']);
            $item_text = FHtml::getFieldValue($item, ['name', 'title', 'text', 'subject']);
            $item_image = $show_image ? FHtml::getImageUrl(FHtml::getFieldValue($item, ['thumbnail', 'image']), 'cms_blogs') : '';
            $item_description = FHtml::getFieldValue($item, ['overview', 'description']);
        }
        $selected = is_array($value) ? in_array($item_value, $value) : ($item_value == $value);
        ?>
        <option value="<?= $item_value ?>" <?= $selected ? 'selected' : '' ?> data-imagesrc="<?=  $item_image ?>" data-description = "<?= $item_description ?>">
            <?= $item_text ?>
        </option>
    <?php } ?>
</select>


<script type="text/javascript">

    $('#<?= $id ?>').ddslick({
        imagePosition: "left",
        selectText: "Select your favorite social network",
        onSelected: function (data) {
            console.log(data);
        }
    });
</script>



