<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\components\FHtml;

$this->title = 'Login';
$type = FHtml::getRequestParam('type');

?>
<div class="row" style="padding:10px">
    <?php
//    $config['model'] = $this->model;
//    $config['attribute'] = $this->attribute;
//    $config['view'] = $this->form->getView();
    //echo $id;
    $name = str_replace('-', '_', $id);


    $config['options'] = [];
    $config['name'] = $name;
    $config['id'] = $name;

    $config['class'] = 'form-control';
    if (empty($type))
        $type = 'html';

    if ($type == 'markdown') {
        echo Html::textarea($name, '', ['class' => 'form-control']);
        FHtml::showToogleMarkdownTextArea($name, false);
        //echo \common\widgets\FMarkdownEditor::widget($config);
    } else {
        $config['preset'] = 'default';
        echo common\widgets\FCKEditor::widget($config);
    }

    ?>
</div>
<hr/>
<?php
echo   Html::button(FHtml::t('button', 'OK'),['onclick' => 'closeHtmlEditor()', 'class'=>'btn btn-primary','data-dismiss'=>"modal"]) . Html::button(FHtml::t('button', 'Close'),['class'=>'btn btn-default pull-right','data-dismiss'=>"modal"])
?>

<script>
    $( document ).ready(function() {
        val1 = $('#' + '<?= $id ?>').val();

        $('#<?= $name ?>').val(val1);
        <?php if ($type == 'html') { ?>
            CKEDITOR.replace("<?= $name ?>");
        <?php } else {

            ?>
        <?php } ?>
    });

    function closeHtmlEditor() {
        <?php if ($type == 'html') { ?>
            val1 = CKEDITOR.instances['<?= $name ?>'].getData();
            //console.log('Close' + val1);
        <?php } else if ($type == 'markdown') { ?>
            editor1 = editormd("<?= $name ?>");
            val1 = editor1.getMarkdown();
        <?php } ?>


        $('#' + '<?= $id ?>').val(val1);
        $('#' + '<?= $id ?>').trigger('change');
    }
</script>

