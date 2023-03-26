<?php
namespace backend\modules\app\actions;

use backend\actions\BaseAction;
use backend\modules\app\models\AppUserTransactionAPI;
use common\components\FHtml;
use common\components\Response;

class AccountingAction extends BaseAction
{
    public $is_secured = true;

    public function run()
    {
        if (($re = $this->isAuthorized()) !== true)
            return $re;

        $user_id = $this->user_id;

        $type = FHtml::getRequestParam('type', ''); //deal/trip

        if(strlen($type) == 0){
            return Response::getOutputForAPI('', \Globals::ERROR, \Globals::MISSING_PARAMS, ['code'=> 202]);
        }

        $year = date('Y');
        $start = mktime(0, 0, 0, 1, 1, $year);
        $end = mktime(23, 59, 59, 12, 31, $year);

        $transactions = AppUserTransactionAPI::find()->where("object_type = '$type' AND user_id = '" . $user_id . "' AND `time` > $start and `time` < $end ")->all();

        $data = array();

        foreach($transactions as $item){
            $month = (int) date('m',$item->time);
            $data[$month]['month'] = $month;
            $old_revenue = isset($data[$month]['revenue'])? $data[$month]['revenue'] : 0 ;
            $old_expense = isset($data[$month]['expense'])? $data[$month]['expense'] : 0 ;

            if($item->type == AppUserTransactionAPI::TYPE_PLUS){
                $data[$month]['revenue'] = $old_revenue + $item->amount;
                $data[$month]['expense'] = $old_expense;

            }
            if($item->type == AppUserTransactionAPI::TYPE_MINUS){
                $data[$month]['revenue'] = $old_revenue;
                $data[$month]['expense'] = $old_expense + $item->amount;
            }
        }

        for ($i=1; $i<=12; $i++){
            if(!array_key_exists ($i,$data)){
                $data[$i] = array('month'=> $i, 'revenue'=> 0, 'expense'=> 0);
            }
        }
        ksort($data);
        $data = array_values($data);
        return Response::getOutputForAPI($data, \Globals::SUCCESS, 'OK', ['code' => 200]);
    }
}
