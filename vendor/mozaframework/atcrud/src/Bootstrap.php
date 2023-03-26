<?php

namespace mozaframework\atcrud;

use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;


class Bootstrap implements BootstrapInterface {

    /**
     * Bootstrap method to be called during application bootstrap stage.
     *
     * @param Application $app the application currently running
     */
    public function bootstrap($app) {
        Yii::setAlias("@atcrud", __DIR__);
        Yii::setAlias("@mozaframework/atcrud", __DIR__);
        if ($app->hasModule('gii')) {
            if (!isset($app->getModule('gii')->generators['atcrud'])) {
                $app->getModule('gii')->generators['atcrud'] = 'mozaframework\atcrud\generators\Generator';
                $app->getModule('gii')->generators['atmodel'] = 'mozaframework\atcrud\model\Generator';
            }
        }
    }

}
