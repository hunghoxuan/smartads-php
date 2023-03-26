<?php

use colee\vue\VueAsset;
use common\components\FHtml;


/* @var $this yii\web\View */

$this->title = FHtml::t('Dashboard');
$baseUrl     = Yii::$app->getUrlManager()->getBaseUrl();
?>
<!--Form-->
<div class="portlet light">
    <div class="portlet-title tabbable-line hidden-print">
        <div class="caption caption-md">
            <i class="icon-globe theme-font hide"></i>
            <span class="caption-subject font-blue-madison bold uppercase">DEMO GENERATE WORD/EXCEL</span>
        </div>
        <div class="tools pull-right">
            <a href="#" class="fullscreen"></a>
            <a href="#" class="collapse"></a>
        </div>
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab_1_1" data-toggle="tab"><?= FHtml::t('common', 'Use Template file') ?></a>
            </li>
            <li>
                <a href="#tab_1_2" data-toggle="tab"><?= FHtml::t('common', 'Use HTML Content') ?></a>
            </li>
        </ul>
    </div>
    <div class="portlet-body form">
        <div class="form">
            <div class="form-body">
                <div class="tab-content">
                    <div class="tab-pane active row" id="tab_1_1">
                        <div class="row">
                            <div class="panel1 panel-default">
                                <div class="panel-body">

                                    <form action="<?= FHtml::createUrl('test/html-to-word') ?>" method="POST" role="form" enctype="multipart/form-data">
                                        <div class="col-md-6">
                                            <b>Edit Params</b>
                                            <div class="form-group">
                                                <label for="">${name}</label>
                                                <input type="text" name="txtName" class="form-control" id="" value="Test Name" placeholder="">
                                            </div>
                                            <div class="form-group">
                                                <label for="">${content}</label>
                                                <textarea type="text" name="txtContent" class="form-control" id="" placeholder="">Test Content </textarea>
                                            </div>

                                            <button class="btn btn-primary" type="submit" name="btnSubmit" value="word">Export to Word</button>
                                            <button class="btn btn-success" type="submit" value="excel" name="btnSubmit">Export to Excel</button>
                                        </div>
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <b for="">Use default template or Upload your own template (with Params above)</b>
                                                <input type="file" name="txtFile" class="form-control" id="" placeholder="">
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane row" id="tab_1_2">
                        <div class="row">
                            <div class="panel1 panel-default">
                                <div class="panel-body">
                                    <form action="<?= FHtml::createUrl('test/html-to-word1') ?>" method="POST" role="form" enctype="multipart/form-data">
                                        <div class="col-md-6">
                                            <b>Edit Params</b>
                                            <div class="form-group">
                                                <label for="">${name}</label>
                                                <input type="text" name="txtName" class="form-control" value="Test Name" id="" placeholder="">
                                            </div>
                                            <div class="form-group">
                                                <label for="">${content}</label>
                                                <textarea type="text" name="txtContent" class="form-control" id="" placeholder="">Test Content</textarea>
                                            </div>
                                            <br />
                                            <button class="btn btn-primary" type="submit" name="btnSubmit" value="word">Export to Word</button>
                                        </div>
                                        <div class="col-md-6">
                                            <b>Edit Template</b>

                                            <textarea id="content" name="txtFormat" cols="60" rows="10">
                            <h1>${name}</h1><br />
                                ${content}
                            </textarea>

                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="form col-md-12">

</div>
<hr />
<div class="form col-md-12">

</div>
<!--Form-->
<!--    <br>-->
<!--    <div class="col-md-8">-->
<!--    <textarea id="content" cols="60" rows="10">-->
<!--        {{name}}-->
<!--        {{content}}-->
<!--    </textarea>-->
<!--    </div>-->
<!--Html to doc using html doc js-->
<!--    <div class="page-orientation">-->
<!--        <span>Page orientation:</span>-->
<!--        <label><input type="radio" name="orientation" value="portrait" checked>Portrait</label>-->
<!--        <label><input type="radio" name="orientation" value="landscape">Landscape</label>-->
<!--    </div>-->

<!--    <button id="convert">Convert</button>-->
<script src="http://tinymce.cachefly.net/4.1/tinymce.min.js"></script>
<?php

$this->registerJsFile($baseUrl . '/plugins/html_docx/build/html-docx.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile($baseUrl . '/plugins/html_docx/vendor/FileSaver.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$script = <<<JS
tinymce.init({
            selector: '#content',
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen fullpage",
                "insertdatetime media table contextmenu paste"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | " +
                          "alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | " +
                          "link image"
        });
        // document.getElementById('convert').addEventListener('click', function(e) {
        //     e.preventDefault();
        //     convertImagesToBase64();
        //     // for demo purposes only we are using below workaround with getDoc() and manual
        //     // HTML string preparation instead of simple calling the .getContent(). Becasue
        //     // .getContent() returns HTML string of the original document and not a modified
        //     // one whereas getDoc() returns realtime document - exactly what we need.
        //     var contentDocument = tinymce.get('content').getDoc();
        //     var content = '<!DOCTYPE html>' + contentDocument.documentElement.outerHTML;
        //     var orientation = document.querySelector('.page-orientation input:checked').value;
        //     var converted = htmlDocx.asBlob(content, {orientation: orientation});
        //
        //     saveAs(converted, 'test.docx');
        //
        //     var link = document.createElement('a');
        //     link.href = URL.createObjectURL(converted);
        //     link.download = 'document.docx';
        //     link.appendChild(
        //         document.createTextNode('Click here if your download has not started automatically'));
        //     var downloadArea = document.getElementById('download-area');
        //     downloadArea.innerHTML = '';
        //     downloadArea.appendChild(link);
        // });

        function convertImagesToBase64 () {
            contentDocument = tinymce.get('content').getDoc();
            var regularImages = contentDocument.querySelectorAll("img");
            var canvas = document.createElement('canvas');
            var ctx = canvas.getContext('2d');
            [].forEach.call(regularImages, function (imgElement) {
                // preparing canvas for drawing
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                canvas.width = imgElement.width;
                canvas.height = imgElement.height;

                ctx.drawImage(imgElement, 0, 0);
                // by default toDataURL() produces png image, but you can also export to jpeg
                // checkout function's documentation for more details
                var dataURL = canvas.toDataURL();
                imgElement.setAttribute('src', dataURL);
            });
            canvas.remove();
        }
JS;
$this->registerJs($script, \yii\web\View::POS_END);
?>
<!--Html to doc using html doc js-->

<!--Html to doc-->
<br><br>
<!--    <a href="--><? //= FHtml::createUrl('site/html-to-word') ?><!--" class="btn btn-primary">Html to word</a>-->
<!--Html to doc-->

<!--Markdown-->
<!--    <div id="test-editormd"></div>-->
<?php

$this->registerCssFile($baseUrl . '/plugins/editor_md/css/editormd.css');
$this->registerJsFile($baseUrl . '/plugins/editor_md/editormd.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$script = <<<JS
        var testEditor;
        $(function() {
            testEditor = editormd("test-editormd", {
                width   : "90%",
                height  : 400,
                syncScrolling : "single",
                path    : "{$baseUrl}/plugins/editor_md/lib/"
            });
            
            editormd.loadScript("{$baseUrl}/plugins/editor_md/languages/" + 'en', function() {
                testEditor.lang = 'en';
                testEditor.recreate();
            });
        });
JS;

//$this->registerJs($script, \yii\web\View::POS_END);
?>
<!--Markdown-->