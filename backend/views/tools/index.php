<?php
use common\components\FHtml;


/* @var $this yii\web\View */

$this->title = FHtml::t('Apps');
$folder = FHtml::getRootFolder() . '/apps';
$apps = [];
$links = [
    'cache', 'backup', 'copy', 'setup'
];


$app = FHtml::getRequestParam('app');
if (!key_exists($app, $apps) && !key_exists($app, $links))
    $app = '';
$app_url = key_exists($app, $links) ? $links[$app] : (!empty($app) ? FHtml::currentBaseURL() . "/tools/$app" : '');

?>
<?= FHtml::render('_menu_right') ?>

