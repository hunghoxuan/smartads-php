<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\controllers;

use common\components\FHtml;
use common\controllers\BaseApiController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;


class DocumentsController extends AdminController
{
    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'swagger', 'index', 'api'
                        ],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get', 'post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'auth'  => [
                'class'           => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionApi($format = '') {
        return $this->render('api');
    }

    public function actionSwagger() {
        return $this->render('swagger');

    }
}
