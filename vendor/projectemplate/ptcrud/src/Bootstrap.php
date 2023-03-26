<?php

namespace projectemplate\ptcrud;

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
        Yii::setAlias("@ptcrud", __DIR__);
        Yii::setAlias("@projectemplate/ptcrud", __DIR__);
        if ($app->hasModule('gii')) {
            if (!isset($app->getModule('gii')->generators['ptcrud'])) {
                $app->getModule('gii')->generators['crud'] = 'projectemplate\ptcrud\generators\Generator';
                $app->getModule('gii')->generators['model'] = 'projectemplate\ptcrud\model\Generator';
                //keep crud and model: auto fill working (but default is hidden)
            }
        }
    }

}
