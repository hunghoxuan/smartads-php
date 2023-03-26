<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\components\FHtml;

$this->title = 'Login';
$object_type = isset($object_type) ? $object_type : FHtml::getRequestParam('object_type');
$object_id = isset($object_id) ? $object_id : FHtml::getRequestParam('object_id');
$file = isset($file) ? $file : FHtml::getRequestParam('file');
$file_type = isset($file_type) ? $file_type : FHtml::getRequestParam('file_type');
$file_path = isset($file_path) ? $file_path : FHtml::getRequestParam('file_path');
$file_size = isset($file_size) ? $file_size : FHtml::getRequestParam('file_size');
$file_name = isset($file_name) ? $file_name : FHtml::getRequestParam('file_name');
$root_url = FHtml::getRootUrl();

if (empty($file_name))
    $file_name = $file;

$file_name = str_replace($root_url, '', $file_name);

$folder = isset($folder) ? $folder : FHtml::getRequestParam('folder');

$parsed_urls = \common\components\FContent::parseUrl($file);
if (!empty($parsed_urls['type'])) {
    $file_type = $parsed_urls['type'];
    $file = $parsed_urls['url'];
}

if (is_file($file)) {
    $download = "<a target='_blank' class='btn btn-primary pull-left'  data-pjax=0 title='$file' href='$file?action=download' class='' > <i class=\"fa fa-download\" aria-hidden=\"true\"></i> " . $file_size . "  </a>";
} else {
    $download = '';
}

?>
<div class="row" style="padding:10px">
    <?php if (!empty($file)) {
        if ($file_type == 'image')
            echo FHtml::showImage($file);
        else
            echo FHtml::showIframe($file);
    }
    ?>

</div>
<small>
<?= "Url: " . $file . "<br/> File name: $file_name. Path: $file_path. Type: $file_type" ?>
</small>
<hr/>
<div class="row">
    <div class="col-md-12">
        <?php echo Html::button(FHtml::t('button', 'Close'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]) ?>

        <?= $download?>
        <?php if (FHtml::isRoleAdmin()) { ?>
            <button class="btn btn-danger pull-right" data-dismiss="modal" onclick="deleteFile()"><?= FHtml::t('button', 'Detete') ?></button>
            <button class="btn btn-primary pull-right" data-dismiss="modal" onclick="uploadFile()"><?= FHtml::t('button', 'Upload') ?></button>
            <input class="pull-right btn" type="file" id="fileUpload" name="fileUpload" />

        <?php } ?>
 </div>
</div>
<?php if (FHtml::isRoleAdmin()) { ?>

<script>
    $( document ).ready(function() {

    });

    function uploadFile() {
        var formData = new FormData();
        formData.append( 'file', $('#fileUpload')[0].files[0]);
        formData.append( 'action', 'upload');
        formData.append( 'file', '<?= $file_name ?>');
        formData.append( 'folder', '<?= $folder ?>');

        $.ajax({
            url : '<?= FHtml::createUrl("site/file") ?>',
            type : 'POST',
            data : formData,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            enctype: 'multipart/form-data',
            async: false,
            cache: false,
            success : function(data) {
                console.log(data);
                alert(data);
            }
        });
    }

    function deleteFile() {
        var formData = new FormData();
        formData.append( 'action', 'delete');
        formData.append( 'file', '<?= $file_name ?>');
        formData.append( 'folder', '<?= $folder ?>');

        $.ajax({
            url : '<?= FHtml::createUrl("site/file") ?>',
            type : 'POST',
            data : formData,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            success : function(data) {
                console.log(data);
                alert(data);
            }
        });
    }
</script>
<?php } ?>
