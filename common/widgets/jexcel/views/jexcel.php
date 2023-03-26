<?php
use common\components\FHtml;
use frontend\assets\CustomAsset;

/* @var $model \frontend\models\ViewModel */
/* @var $this yii\web\View */
/* @var $category \backend\models\Category */
$id = isset($id) ? $id : rand(1, 1000);
$name = isset($name) ? $name : ((isset($model) && isset($attribute)) ? \yii\helpers\Html::getInputName($model, $attribute) : \yii\helpers\BaseInflector::camelize($id));

if (!isset($models))
    $models = [['', ''], ['', '']];

$baseUrl = FHtml::currentBaseURL();

if (is_string($data)) {
    //echo $data;
    $arr = explode(',', $data);
    $result = [];
    $result1 = [];
    while (is_array($arr) && !empty($arr)) {
        $result1 = [];
        foreach ($colHeaders as $item) {
            if (!isset($arr[0]))
                break;
            $result1[] = $arr[0];
            array_shift($arr);
        }
        $result[] = $result1;
    }
    $data = $result;
}

$data = isset($data) ? $data : $models;

if (!isset($colHeaders)) {
    $colHeaders = $data[0];
    array_shift($data);
}

if (!isset($colWidths)) {
    $colWidths = [];
    foreach ($colHeaders as $colHeader) {
        $colWidths[] = round(100/count($colHeaders)) . "%";
    }
}

if (!isset($colAlignments)) {
    $colAlignments = [];
    foreach ($colHeaders as $colHeader) {
        $colAlignments[] = 'left';
    }
}

if (!isset($columns)) {
    $columns = [];
    foreach ($colHeaders as $colHeader) {
        $columns[] = ['type' => 'text'];
    }
}

?>
<style>
    .jexcel {
        width: 100% !important;
    }
</style>
<?php
//https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js
$this->registerJsFile("$baseUrl/backend/web/js/jquery.min.js", ['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile("$baseUrl/common/widgets/jexcel/assets/dist/js/jquery.jexcel.js", ['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile("$baseUrl/common/widgets/jexcel/assets/dist/js/jquery.jcalendar.js", ['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile("$baseUrl/common/widgets/jexcel/assets/dist/js/jquery.jdropdown.js", ['position' => \yii\web\View::POS_HEAD]);

$this->registerCssFile("$baseUrl/common/widgets/jexcel/assets/dist/css/jquery.jexcel.css");
$this->registerCssFile("$baseUrl/common/widgets/jexcel/assets/dist/css/jquery.jcalendar.css");
$this->registerCssFile("$baseUrl/common/widgets/jexcel/assets/dist/css/jquery.jdropdown.css");
?>

<div id="jexcel_<?= $id ?>" class="col-md-12">
</div>
<input type="hidden" class="form-control" id="<?= $id ?>" name="<?= $name ?>" value="<?= FHtml::encode($data) ?>" />

<!--
<a class="btn btn-xs btn-default" href="#" onclick="downloadJExcel('<?= $id ?>')"> Download</a>
-->

<script type="text/javascript">
    function downloadJExcel(id) {
        val1 = $('#jexcel_<?= $id ?>').jexcel('getData', false);
        $('#' + id).jexcel('download');
    }
    $('#jexcel_<?= $id ?>').jexcel({
        data: <?= FHtml::encode($data) ?>,
        colHeaders:  <?= FHtml::encode($colHeaders) ?>,
        colWidths: <?= FHtml::encode($colWidths) ?>,
        colAlignments: <?= FHtml::encode($colAlignments) ?>,
        columns: <?= FHtml::encode($columns) ?>,
        tableOverflow:true,

        table: function (instance, cell, col, row, val, id) {
            if (col == 4 || col == 5) {
                if (val < 0) {
                    $(cell).css('color', '#ff0000');
                } else {
                    $(cell).css('color', '#249D7F');
                }
            }
        },
        onbeforechange: function(obj, cell, val) {
//            console.log('My table id: ' + $(obj).prop('id'));
//            console.log('Cell changed: ' + $(cell).prop('id'));
            console.log('Value: ' + val);
            val = val.replace(',', ';');
        },
        onchange: function(obj, cell, val) {
//            console.log('My table id: ' + $(obj).prop('id'));
//            console.log('Cell changed: ' + $(cell).prop('id'));
//            console.log('Value: ' + val);
            data = this.data;
            result = Array(data.length);
            for (i = 0; i < data.length; i++) {
                result[i] = data[i];
            }
            $('#<?= $id ?>').val(result);

            //console.log(result);
            //console.log(this.data.length);
        },
        oninsertcolumn: null,

        oninsertrow: function(obj) {
            //alert('new row added on table: ' + $(obj).prop('id'));
        },
        ondeleterow: function(obj) {
            //alert('row excluded on table: ' + $(obj).prop('id'));
        },
//        columns: [
//            { type: 'text' },
//            { type: 'text' },
//            { type: 'dropdown', source:[ {'id':'1', 'name':'Fruits'}, {'id':'2', 'name':'Legumes'}, {'id':'3', 'name':'General Food'} ] },
//            { type: 'checkbox' },
//            { type: 'calendar' },
//        ]
    });

</script>



