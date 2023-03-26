<?php

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'prod');

if (is_file(__DIR__ . '/config/global.php'))
    include_once(__DIR__ . '/config/global.php');

if (is_file(__DIR__ . '/common/main.php'))
    include_once(__DIR__ . '/common/main.php');

session_start();

$frontend_framework = frontend_framework();
$application_id = application_id();
$frontend_theme = frontend_theme();


if (is_dir(__DIR__  . '/wordpress/wp-load.php')) {
    // Load the WordPress library. Set up the WordPress query.
    require_once(__DIR__ . '/wordpress/wp-load.php');
    wp();
}

if (is_dir('blog/wp-content')) {
    require(dirname(__FILE__) . '/blog/index.php');
    return;
} else if (is_dir('blogs/wp-content')) {
    require(dirname(__FILE__) . '/blogs/index.php');
} else if (is_dir('apps/wordpress/wp-content')) {
    require(dirname(__FILE__) . '/apps/wordpress/index.php');
    return;
} else if (is_dir("applications/$application_id/wordpress/wp-content")) {
    require(dirname(__FILE__) . "/applications/$application_id/wordpress/index.php");
    return;
}
else if ($frontend_framework == 'wordpress' && is_file("applications/$application_id/config/wp-config.php")) {
    require(dirname(__FILE__) . "/wordpress/index.php");
    return;
} else if ($frontend_framework == 'wordpress' && is_dir('wordpress')) {
    require(dirname(__FILE__) . '/wordpress/index.php');
    return;
} else if ($frontend_framework == 'laravel' && is_dir('laravel')) {

    define('LARAVEL_START', microtime(true));
    require __DIR__.'/laravel/vendor/autoload.php';

    if (is_file(__DIR__."/applications/$application_id/bootstrap/app.php"))
        $app = require_once __DIR__."/applications/$application_id/bootstrap/app.php";
    else
        $app = require_once __DIR__.'/laravel/bootstrap/app.php';

    require_once __DIR__.'/laravel/helper/function.php';
    require_once __DIR__.'/laravel/helper/constant.php';

    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    );

    $response->send();

    $kernel->terminate($request, $response);

}
else if ($frontend_framework == 'laravel') {

    define('LARAVEL_START', microtime(true));
	require __DIR__."/applications/$application_id/vendor/autoload.php";
	$app = require_once __DIR__."/applications/$application_id/bootstrap/app.php";

    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    );

    $response->send();

    $kernel->terminate($request, $response);

} else {
    // default is Yii2
    if (is_dir('frontend')) {

        require(__DIR__ . '/vendor/autoload.php');
        require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');
        require(__DIR__ . '/common/config/bootstrap.php');

        require(__DIR__ . '/frontend/config/bootstrap.php');

        $config_file = __DIR__ . "/applications/$application_id/frontend/config/main.php";

        $config = yii\helpers\ArrayHelper::merge(
            require(__DIR__ . '/common/config/main.php'),
            require(__DIR__ . '/config/main.php'),
            require(__DIR__ . '/frontend/config/main.php'),
            is_file($config_file) ? require ($config_file) : []
        );

        $application = new \common\components\FMozaApplication($config);
        $application->run();

    } else if ($frontend_framework != 'wordpress') {
        header('Location: backend/web/index.php'); // redirect
    }
}


?>
