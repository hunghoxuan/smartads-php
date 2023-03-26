<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'prod');

if (is_file(__DIR__ . '/../../config/global.php'))
    include_once(__DIR__ . '/../../config/global.php');

include_once(__DIR__ . '/../../common/main.php');

session_start();

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../common/config/bootstrap.php');
require(__DIR__ . '/../config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/main.php'),
    require(__DIR__ . '/../../config/main.php'),
    require(__DIR__ . '/../config/main.php')

);

if (is_dir('/../../wordpress')) {
    require(dirname(__FILE__) . '/../../wordpress/index.php');
}

$application = new \common\components\FMozaApplication($config);
$application->run();
