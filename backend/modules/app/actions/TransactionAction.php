<?php
namespace backend\modules\app\actions;

use backend\actions\BaseAction;
use backend\modules\app\models\AppUserAPI;
use backend\modules\app\models\AppUserTransactionAPI;
use backend\modules\app\models\AppUserTransactionRequestAPI;
use common\components\FHtml;
use common\components\Response;
use Yii;
use yii\db\Exception;

class TransactionAction extends BaseAction
{
    public $is_secured = true;

    public function run()
    {

        if (($re = $this->isAuthorized()) !== true)
            return $re;

        $user_id = $this->user_id;

        $action = FHtml::getRequestParam('action', ''); //exchange/redeem/transfer
        $amount = FHtml::getRequestParam('amount', '');
        $note = FHtml::getRequestParam('note', '');
        $payment_method = FHtml::getRequestParam('payment_method', '');
        $destination_email = FHtml::getRequestParam('destination_email', '');
        $external_transaction_id = FHtml::getRequestParam('transaction_id', '');


        if (strlen($action) == 0 || strlen($amount) == 0) {
            return Response::getOutputForAPI('', \Globals::ERROR, \Globals::MISSING_PARAMS, ['code' => 202]);
        }

        $now = time();
        $today = date('Y-m-d H:i:s', $now);
        $destination_id = null;

        /* @var AppUserAPI $user */
        $user = AppUserAPI::findOne($user_id);

        if ($action == AppUserTransactionRequestAPI::TYPE_REDEEM) {
            $check = AppUserTransactionRequestAPI::find()
                ->where("user_id = $user_id AND status = 0 AND ( type = '" . AppUserTransactionRequestAPI::TYPE_REDEEM . "')")
                ->one();
            if (isset($check)) {
                return Response::getOutputForAPI('', \Globals::ERROR, Response::getErrorMsg(232), ['code' => 232]);
            } else {
                if ($user->balance < $amount) {
                    return Response::getOutputForAPI('', \Globals::ERROR, Response::getErrorMsg(230), ['code' => 230]);
                } else {
                    $check = new AppUserTransactionRequestAPI();
                    $check->user_id = $user_id;
                    $check->destination_id = $destination_id;
                    $check->amount = $amount;
                    $check->note = $note;
                    $check->created_date = $today;
                    $check->status = AppUserTransactionRequestAPI::STATUS_PENDING;
                    $check->time = $now;
                    $check->type = $action;
                    if ($check->save()) {
                        return Response::getOutputForAPI('', \Globals::SUCCESS, 'OK', ['code' => 200]);
                    } else {
                        return Response::getOutputForAPI('', \Globals::ERROR, 'FAIL', ['code' => 201]);
                    }
                }
            }
        } elseif ($action == AppUserTransactionRequestAPI::TYPE_TRANSFER) {

            if (strlen($destination_email) == 0) {
                return Response::getOutputForAPI('', \Globals::ERROR, \Globals::MISSING_PARAMS, ['code' => 202]);
            } else {
                $check_destination = AppUserAPI::findByEmail($destination_email);
                if (!isset($check_destination)) {
                    return Response::getOutputForAPI('', \Globals::ERROR, \Globals::EMAIL_DOES_NOT_EXIST, ['code' => 223]);
                } else {
                    if ($user->balance < $amount) {
                        return Response::getOutputForAPI('', \Globals::ERROR, Response::getErrorMsg(230), ['code' => 230]);
                    } else {
                        $destination_id = $check_destination->id;
                        $connection = \Yii::$app->db;
                        $transaction = $connection->beginTransaction();
                        try {
                            /*
                            $check = new AppUserTransactionRequestAPI();
                            $check->user_id = $user_id;
                            $check->destination_id = $destination_id;
                            $check->amount = $amount;
                            $check->note = $note;
                            $check->created_date = $today;
                            $check->status = AppUserTransactionRequestAPI::STATUS_APPROVED;
                            $check->time = $now;
                            $check->type = $action;
                            $check->save();
                            */
                            //if ($check->save()) {

                            $user->balance -= $amount;
                            $user->save();

                            AppUserAPI::updateAllCounters(['balance'=>$amount],['email'=>$destination_email]);

                            //if($user->save() && $check_destination->save()){

                            $user_transaction = new AppUserTransactionAPI();
                            $user_transaction->transaction_id = \Globals::generateTransactionId($user_id);
                            $user_transaction->user_id = $user_id;
                            $user_transaction->user_visible = 1;
                            $user_transaction->destination_id = $destination_id;
                            $user_transaction->destination_visible = 1;
                            $user_transaction->currency = '';
                            $user_transaction->payment_method = 'point';
                            $user_transaction->time = time();
                            $user_transaction->amount = $amount;
                            $user_transaction->action = AppUserTransactionAPI::ACTION_TRANSFER_POINT;
                            $user_transaction->type = '';
                            $user_transaction->is_active = 1;
                            $user_transaction->created_date = $today;
                            $user_transaction->created_user = $user_id;
                            $user_transaction->save();

                            //}
                            //else{
                            //    return Response::getOutputForAPI('', \Globals::ERROR, 'FAIL', ['code'=> 201]);
                            //}

                            $transaction->commit();
                            $msg2 = $amount . ' ' . Yii::t('common', 'push.transfer.approved.receiver');
                            $url2 = Yii::$app->urlManager->createAbsoluteUrl(['api/utility/pushNotification',
                                'msg' => $msg2,
                                'user_id' => $destination_id,
                                'type' => 'balance',
                                'additional_data' => $user_transaction->destination->balance,
                            ]);

                            ignore_user_abort(true); // CAUTION!  run over return
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // comment when test
                            curl_setopt($ch, CURLOPT_URL, $url2);
                            curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
                            curl_setopt($ch, CURLOPT_TIMEOUT, 1); // Skip receive return
                            curl_exec($ch);
                            curl_close($ch);
                            return Response::getOutputForAPI(array('balance' => $user_transaction->user->balance), \Globals::SUCCESS, 'OK', ['code' => 200]);

                            //}
                            //else {
                            //    return Response::getOutputForAPI('', \Globals::ERROR, 'FAIL', ['code'=> 201]);
                            //}

                        } catch (Exception $e) {
                            $transaction->rollBack();
                            return Response::getOutputForAPI('', \Globals::ERROR, 'FAIL', ['code' => 201]);
                        }
                    }
                }
            }
        } elseif ($action == AppUserTransactionRequestAPI::TYPE_EXCHANGE) {
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {

//                $check = new AppUserTransactionRequestAPI();
//                $check->user_id = $user_id;
//                $check->amount = $amount;
//                $check->note = $note;
//                $check->created_date = $today;
//                $check->status = AppUserTransactionRequestAPI::STATUS_APPROVED;
//                $check->time = $now;
//                $check->type = $action;
//                $check->save();

//                $user = AppUserAPI::findOne($user_id);

                $user->balance += $amount;
                if ($user->save()) {
                    $user_transaction = new AppUserTransactionAPI();
                    $user_transaction->transaction_id = \Globals::generateTransactionId($user_id);
                    $user_transaction->external_transaction_id = $external_transaction_id;
                    $user_transaction->user_id = $user_id;
                    $user_transaction->user_visible = 1;
                    $user_transaction->destination_id = 0;
                    $user_transaction->currency = '$';
                    $user_transaction->payment_method = $payment_method;
                    $user_transaction->time = $now;
                    $user_transaction->amount = $amount;
                    $user_transaction->is_active = 1;
                    $user_transaction->action = AppUserTransactionAPI::ACTION_EXCHANGE_POINT;
                    $user_transaction->type = AppUserTransactionAPI::TYPE_PLUS;
                    $user_transaction->created_date = $today;
                    $user_transaction->created_user = $user_id;
                    if ($user_transaction->save()) {
                        $transaction->commit();
                        return Response::getOutputForAPI(array('balance' => $user_transaction->user->balance), \Globals::SUCCESS, 'OK', ['code' => 200]);
                    } else {
                        $transaction->rollBack();
                        return Response::getOutputForAPI('', \Globals::ERROR, 'FAIL', ['code' => 201]);
                    }
                } else {
                    $transaction->rollBack();
                    return Response::getOutputForAPI('', \Globals::ERROR, 'FAIL', ['code' => 201]);
                }

            } catch (Exception $e) {
                $transaction->rollBack();
                return Response::getOutputForAPI('', \Globals::ERROR, 'FAIL', ['code' => 201]);
            }
        } else {
            return Response::getOutputForAPI('', \Globals::ERROR, 'FAIL', ['code' => 201]);
        }
    }
}
