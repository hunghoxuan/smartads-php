<?php

use colee\vue\VueAsset;
use common\components\FHtml;


/* @var $this yii\web\View */

$this->title = FHtml::t('Dashboard');
$baseUrl     = Yii::$app->getUrlManager()->getBaseUrl();
$files = \common\components\FFile::listFiles(FHtml::getRootFolder() . "/backend/views/test");
?>
<!--Form-->
<div class="portlet light">
    <div class="portlet-title tabbable-line hidden-print">
        <div class="caption caption-md">
            <i class="icon-globe theme-font hide"></i>
            <span class="caption-subject font-blue-madison bold uppercase">TEST</span>
        </div>
        <div class="tools pull-right">
            <a href="#" class="fullscreen"></a>
            <a href="#" class="collapse"></a>
        </div>
    </div>
    <div class="portlet-body form">
        <div class="form">
            <div class="form-body">
                <div class="tab-content">
                    <div class="tab-pane active row" id="tab_1_1">
                        <div class="row">
                            <div class="panel1 panel-default">
                                <div class="panel-body">
                                    <div class="col-md-12">
                                    <?php foreach ($files as $file) {
                                        $info = FHtml::parseUrl($file);
                                        $name = $info['filename'];
                                        if (!\yii\helpers\StringHelper::endsWith($name, '.php') || in_array($name, ['index.php']))
                                            continue;
                                        ?>
                                        <a target="_blank" class="btn btn-default col-md-3" href="<?= FHtml::createUrl('test', ['view' => $info['filename']]) ?>"><?= $info['filename'] ?></a>
                                    <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
