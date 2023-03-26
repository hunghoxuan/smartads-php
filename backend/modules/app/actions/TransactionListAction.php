<?php
namespace backend\modules\app\actions;

use backend\actions\BaseAction;
use backend\modules\app\models\AppUserTransactionAPI;
use common\components\FHtml;
use common\components\Response;

class TransactionListAction extends BaseAction
{
    public $is_secured = true;

    public function run()
    {

        if (($re = $this->isAuthorized()) !== true)
            return $re;

        $user_id = $this->user_id;
        $page = FHtml::getRequestParam('page', 1);

        $order = ("time DESC");

        $action_exchange = AppUserTransactionAPI::ACTION_EXCHANGE_POINT;
        $action_redeem = AppUserTransactionAPI::ACTION_REDEEM_POINT;
        $action_transfer = AppUserTransactionAPI::ACTION_TRANSFER_POINT;

        $condition = "((user_id = $user_id AND user_visible = 1) OR  (destination_id = $user_id AND destination_visible = 1)) AND (action = '$action_exchange' OR action = '$action_redeem' OR action = '$action_transfer')";

        $recordPerPage = \Globals::DEFAULT_ITEMS_PER_PAGE;

        $total = AppUserTransactionAPI::find()->where($condition)->count();

        $total_page = ceil($total / $recordPerPage);
        $start_index = $page * $recordPerPage - $recordPerPage;

        $transactions = AppUserTransactionAPI::find()->where($condition)
            ->limit($recordPerPage)
            ->offset($start_index)
            ->orderBy($order)
            ->all();

        return Response::getOutputForAPI($transactions, \Globals::SUCCESS, 'OK', ['total_page'=>$total_page, 'code'=> 200]);
    }

}
