<?php
namespace backend\modules\app\actions;

use backend\actions\BaseAction;
use backend\modules\app\models\AppUserFavourite;
use backend\modules\app\models\AppUserFavouriteAPI;
use backend\modules\product\models\ProductDealAPI;
use common\components\FHtml;
use common\components\Response;

class FavouriteAction extends BaseAction
{
    public $is_secured = true;

    public function run()
    {
        if (($re = $this->isAuthorized()) !== true)
            return $re;

        $user_id = $this->user_id;

        $object_id = FHtml::getRequestParam('object_id', '');
        $object_type = FHtml::getRequestParam('object_type', ''); //deal


        $check_condition = "
        user_id = $user_id 
        AND object_id = $object_id
        AND object_type = '$object_type'
        ";
        $favourite = AppUserFavourite::find()->where($check_condition)->one();
        /* @var  $review AppUserFavouriteAPI */
        $now = time();
        if (isset($favourite)) {
            if ($favourite->delete()) {
                if ($object_type == \Globals::OBJECT_TYPE_DEAL) {
                    $object_deal = ProductDealAPI::findOne($object_id);
                    if (isset($object_deal)) {
                        $object_deal->updateCounters(['like_count' => -1]);
                        $object_deal->save();
                    }
                }
            } else {
                return Response::getOutputForAPI('', \Globals::ERROR, 'Fail', ['code'=> 201]);
            }
        } else {
            $favourite = new AppUserFavouriteAPI();
            $favourite->user_id = $user_id;
            $favourite->object_id = $object_id;
            $favourite->object_type = $object_type;
            if ($favourite->save()) {
                if ($object_type == \Globals::OBJECT_TYPE_DEAL) {
                    if (isset($favourite->objectDeal)) {
                        $favourite->objectDeal->updateCounters(['like_count' => 1]);
                        $favourite->objectDeal->save();
                    }
                }
            } else {
                $errors = $review->getErrors();
                $error_message = Response::getErrorMessage($errors);
                return Response::getOutputForAPI('', \Globals::ERROR, $error_message,['code'=> 201]);
            }
        }
        return Response::getOutputForAPI('', \Globals::SUCCESS, 'OK',['code'=> 200]);
    }
}
