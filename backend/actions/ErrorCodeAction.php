<?php
namespace backend\actions;

use common\components\FApi;
use common\components\FConstant;

class ErrorCodeAction extends BaseAction
{
    public function run()
    {
        $data =  FApi::getErrorMsg('all');
        return FApi::getOutputForAPI($data, FConstant::SUCCESS, 'OK', ['code'=> 200]);

    }
}

