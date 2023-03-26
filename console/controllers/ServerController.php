<?php
namespace console\controllers;

use consik\yii2websocket\WebSocketServer;
use yii\console\Controller;

class ServerController extends Controller
{
    public function actionStart()
    {
        $port = 81;
        $server = new WebSocketServer();
        if ($port) {
            echo 'Server on ' . $port;
            $server->port = $port;
        }
        $server->start();
    }
}