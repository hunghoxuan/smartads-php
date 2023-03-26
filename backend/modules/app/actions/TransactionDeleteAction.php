<?php
namespace backend\modules\app\actions;

use backend\actions\BaseAction;
use backend\models\Transaction;
use backend\modules\app\models\AppUserAPI;
use backend\modules\app\models\AppUserTransactionAPI;
use backend\modules\app\models\AppUserTransactionRequestAPI;
use common\components\FHtml;
use common\components\Response;
use yii\db\Exception;

class TransactionDeleteAction extends BaseAction
{
    public $is_secured = true;

    public function run()
    {
        if (($re = $this->isAuthorized()) !== true)
            return $re;

        $user_id = $this->user_id;

        $transaction_id = FHtml::getRequestParam('transaction_id', '');

        if(strlen($transaction_id) == 0){
            AppUserTransactionAPI::updateAll(['user_visible' => 0], ['user_id' => $user_id]);
            AppUserTransactionAPI::updateAll(['destination_visible' => 0], ['destination_id' => $user_id]);
            return Response::getOutputForAPI('', \Globals::SUCCESS, 'OK', ['code' => 200]);
        }else{
            $transaction = AppUserTransactionAPI::findOne($transaction_id);
            if(isset($transaction)){
                if($transaction->user_id = $user_id){
                    $transaction->user_visible = 0;
                }
                if($transaction->destination_id = $user_id){
                    $transaction->destination_visible = 0;
                }
                $transaction->save();
            }
            return Response::getOutputForAPI('', \Globals::SUCCESS, 'OK', ['code' => 200]);
        }


    }

}
