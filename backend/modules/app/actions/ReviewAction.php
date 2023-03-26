<?php
namespace backend\modules\app\actions;

use backend\actions\BaseAction;
use backend\modules\app\models\AppUserReviewAPI;
use common\components\FHtml;
use common\components\Response;


class ReviewAction extends BaseAction
{
    public $is_secured = true;

    public function run()
    {
        if (($re = $this->isAuthorized()) !== true)
            return $re;

        $author_id = $this->user_id;

        $destination_id = FHtml::getRequestParam('destination_id', '');
        $author_role = FHtml::getRequestParam('author_role', '');  //passenger/driver/seller/buyer
        $destination_role = FHtml::getRequestParam('destination_role', ''); //passenger/driver/seller/buyer
        $object_id = FHtml::getRequestParam('object_id', '');
        $object_type = FHtml::getRequestParam('object_type', ''); //deal/trip
        $rate = FHtml::getRequestParam('rate', '');
        $content = FHtml::getRequestParam('content', '');


        if (
            strlen($object_type) == 0 ||
            strlen($object_id) == 0 ||
            strlen($rate) == 0 ||
            strlen($content) == 0 ||
            strlen($destination_id) == 0
        ) {
            return Response::getOutputForAPI('', \Globals::ERROR, \Globals::MISSING_PARAMS, ['code' => 202]);
        }

        $check_condition = "
        author_id = $author_id 
        AND destination_id = $destination_id 
        AND author_role = '$author_role' 
        AND destination_role = '$destination_role'
        AND object_id = $object_id
        AND object_type = '$object_type'
        ";
        $review = AppUserReviewAPI::find()->where($check_condition)->one();
        /* @var  $review AppUserReviewAPI*/
        $now = time();
        $today = date('Y-m-d H:i:s', $now);
        if(isset($review)){
            $review->rate = $rate;
            $review->content = $content;
            $review->modified_date = $today;
        }else{
            $review = new AppUserReviewAPI();
            $review->rate = $rate;
            $review->content = $content;
            $review->author_id = $author_id;
            $review->destination_id = $destination_id;
            $review->author_role = $author_role;
            $review->destination_role = $destination_role;
            $review->object_id = $object_id;
            $review->object_type = $object_type;
            $review->is_active = \Globals::STATE_ACTIVE;
            $review->created_date = $today;
            $review->modified_date = $today;
        }
        if($review->save()){
            if($destination_role == \Globals::ROLE_BUYER || $destination_role == \Globals::ROLE_PASSENGER){
                if(isset($review->destination)){
                    $destination = $review->destination;
                    $destination->rate_count = count($destination->toMeReviews);
                    $destination->save();

                    \Yii::$app->db->createCommand("UPDATE app_user SET rate = (SELECT AVG(rate) 
FROM app_user_review 
WHERE destination_id = :id AND ( destination_role = :destination_role_buyer OR destination_role = :destination_role_passenger)) WHERE id = :id")
                        ->bindValue(':id', $destination_id)
                        ->bindValue(':destination_role_buyer', \Globals::ROLE_BUYER )
                        ->bindValue(':destination_role_passenger', \Globals::ROLE_PASSENGER)
                        ->execute();
                }
            }
            if($destination_role == \Globals::ROLE_SELLER || $destination_role == \Globals::ROLE_DRIVER){
                if(isset($review->destinationPro)){
                    $destinationPro = $review->destinationPro;
                    $destinationPro->rate_count = count($destinationPro->toMeReviews);
                    $destinationPro->save();

                    \Yii::$app->db->createCommand("UPDATE app_user_pro SET rate = (SELECT AVG(rate) 
FROM app_user_review 
WHERE destination_id = :id AND ( destination_role = :destination_role_seller OR destination_role = :destination_role_driver) ) WHERE user_id = :id")
                        ->bindValue(':id', $destination_id)
                        ->bindValue(':destination_role_seller', \Globals::ROLE_SELLER)
                        ->bindValue(':destination_role_driver', \Globals::ROLE_DRIVER)
                        ->execute();
                }

            }

            if($object_type == \Globals::OBJECT_TYPE_DEAL){
                if(isset($review->objectDeal)){
                    $object_deal = $review->objectDeal;
                    $object_deal->rate_count = count($object_deal->reviews);
                    $object_deal->save();

                    \Yii::$app->db->createCommand("UPDATE product_deal SET rate = (SELECT AVG(rate) FROM app_user_review WHERE object_id = :object_id AND object_type = :object_type ) WHERE id = :id")
                        ->bindValue(':id', $object_deal->id)
                        ->bindValue(':object_id', $object_id)
                        ->bindValue(':object_type', \Globals::OBJECT_TYPE_DEAL)
                        ->execute();
                }
            }

            if($object_type == \Globals::OBJECT_TYPE_TRIP){
                if(isset($review->objectTrip)){
                    $object_trip = $review->objectTrip;
                    if($author_id == $object_trip->passenger_id){
                        $object_trip->passenger_rated = 1;
                    }
                    if($author_id == $object_trip->driver_id){
                        $object_trip->driver_rated = 1;
                    }
                    $object_trip->save();
                }
            }

            return Response::getOutputForAPI($review, \Globals::SUCCESS, 'OK', ['code'=> 200]);
        }else{
            $errors = $review->getErrors();
            $error_message = Response::getErrorMessage($errors);
            return Response::getOutputForAPI('', \Globals::ERROR, $error_message, ['code'=> 203]);
        }
    }
}
