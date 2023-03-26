<?php
namespace backend\actions;

use common\components\FEmail;
use Yii;

class StartNodeServer extends BaseAction
{
    public function run()
    {
        \common\components\NodeJs::start();
    }
}
