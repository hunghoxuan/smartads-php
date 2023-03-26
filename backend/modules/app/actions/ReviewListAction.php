<?php
namespace backend\modules\app\actions;

use backend\actions\BaseAction;
use backend\modules\app\models\AppUserReviewAPI;
use common\components\FHtml;
use common\components\Response;
use Yii;
use yii\db\Expression;

class ReviewListAction extends BaseAction
{
    public $is_secured = true;

    public function run()
    {
        if (($re = $this->isAuthorized()) !== true)
            return $re;
        $user_id = $this->user_id;

        $object_id = FHtml::getRequestParam('object_id', '');
        $object_type = FHtml::getRequestParam('object_type', '');  //basic/pro/trip/deal
        $page = FHtml::getRequestParam('page', 1);

        if (
            strlen($object_id) == 0
            || strlen($object_type) == 0
        ) {
            return Response::getOutputForAPI('', \Globals::ERROR, \Globals::MISSING_PARAMS, ['code' => 202]);
        }
        if ($object_type == 'pro' || $object_type == 'basic') {
            $condition = 'destination_id = ' . $object_id . ' AND is_active = ' . \Globals::STATE_ACTIVE;
        } else {
            $condition = 'is_active = ' . \Globals::STATE_ACTIVE;
        }

        $ext_condition = array();

        if ($object_type == 'pro') {
            $ext_condition = ['or',
                ['destination_role' => \Globals::ROLE_SELLER],
                ['destination_role' => \Globals::ROLE_DRIVER]
            ];
        } elseif ($object_type == 'basic') {
            $ext_condition = ['or',
                ['destination_role' => \Globals::ROLE_BUYER],
                ['destination_role' => \Globals::ROLE_PASSENGER]
            ];
        } elseif ($object_type == \Globals::OBJECT_TYPE_DEAL || $object_type == \Globals::OBJECT_TYPE_TRIP) {
            $ext_condition = ['and',
                ['object_id' => $object_id],
                ['object_type' => $object_type]
            ];
        }
        //SELECT * FROM `app_user_review` ORDER BY author_id = 1 DESC

        $order_expression = new Expression("author_id = $user_id DESC, modified_date DESC");

        $recordPerPage = \Globals::DEFAULT_ITEMS_PER_PAGE;

        $total = AppUserReviewAPI::find()->where($condition)->andWhere($ext_condition)->count();

        $total_page = ceil($total / $recordPerPage);
        $start_index = $page * $recordPerPage - $recordPerPage;

        $reviews = AppUserReviewAPI::find()->where($condition)->andWhere($ext_condition)
            ->limit($recordPerPage)
            ->offset($start_index)
            ->orderBy($order_expression)
            ->all();

        $data = array();
        /* @var AppUserReviewAPI $review */
        foreach ($reviews as $review) {
            $data[] = $review;
        }

        return Response::getOutputForAPI($data, \Globals::SUCCESS, 'OK', ['total_page' => $total_page, 'code' => 200]);


    }
}
