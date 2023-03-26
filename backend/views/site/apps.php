<?php

use common\components\FHtml;


/* @var $this yii\web\View */

$this->title = FHtml::t('Apps');
$folder = FHtml::getRootFolder() . '/apps';
$apps = FHtml::listFolders($folder, false);
$links = \common\components\FFile::includeFile($folder . '/index.php');

$app = FHtml::getRequestParam('app');
if (!key_exists($app, $apps) && !key_exists($app, $links))
    $app = '';
$app_url = key_exists($app, $links) ? $links[$app] : (!empty($app) ? FHtml::currentBaseURL() . "/apps/$app" : '');

?>
<div class="row">

    <?php if (empty($app)) {
        echo "<center><h1>" . FHtml::t('common', 'Apps') . "</h1></center> <hr/>";
    } else if (!empty($app_url)) {
      echo "<div class='col-md-12 no-padding form-label' style=' border:1px solid darkgrey; '><div class='col-md-10 caption-title font-blue-madison bold uppercase' style='font-size:150%; padding-top:10px; '>$app</div><div class='col-md-2 pull-right' style='padding-top:10px; padding-right: 20px;' >" .
          FHtml::showLink("site/apps?app", 'X', ['class' => 'btn btn-xs btn-danger pull-right']) . "<div class='pull-right'>&nbsp;&nbsp;</div>".
          FHtml::showLink($app_url, 'OPEN', ['target' => '_blank', 'class' => 'btn btn-xs btn-success pull-right']) .
          '</div></div>';
      echo "<div class='col-md-12 no-padding' style='border:1px solid darkgrey; width:100%; '>";
      echo FHtml::showIframe($app_url, 'position: relative; height: 100%; min-height: 700px; width: 100%;');
      echo "</div>";
    }  ?>

</div>
<?php if (empty($app)) { ?>
<div class="col-md-12 no-padding" style="<?= empty($app) ? 'padding-top:50px' : "padding:5px; position: fixed; bottom: 30px" ?>">

    <?php
    foreach ($apps as $name => $folder) {
        echo FHtml::showLink("site/apps?app=$name", $name, ['class' => 'btn btn-sm ' . (empty($app) ? 'col-md-3 ' : ' ') . ($name == $app ? 'btn-primary' : 'btn-default')]);
    }
    foreach ($links as $name => $link) {
        echo FHtml::showLink("site/apps?app=$name", $name, ['class' => 'btn btn-sm ' . (empty($app) ? 'col-md-3 ' : ' ') . ($name == $app ? 'btn-primary' : 'btn-default')]);
    }
    ?>
</div>
<?php } ?>