<?php

namespace backend\modules\smartscreen\actions;

use backend\modules\smartscreen\models\SmartscreenQueue;
use backend\modules\smartscreen\Smartscreen;
use common\components\FApi;
use common\components\FConstant;
use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;
use backend\modules\smartscreen\models\SmartscreenQueueAPI;
use common\actions\BaseApiAction;

/**



 * This is the customized model class for table "smartscreen_queue".
 */
class SmartscreenHisUpdateAction extends BaseApiAction
{
    public $is_secured = false;
    public function run()
    {
        if (($re = $this->isAuthorized()) !== true)
            return $re;

        $data = FHtml::getRequestParam('data');

        $ime = FHtml::getRequestParam('ime');
        $name = FHtml::getRequestParam('name');
        $code = FHtml::getRequestParam('code');
        $id = FHtml::getRequestParam('id');
        $service = FHtml::getRequestParam('service');
        $ticket = FHtml::getRequestParam('ticket');
        $counter = FHtml::getRequestParam('counter');
        $status = FHtml::getRequestParam('status');
        $sort_order = FHtml::getRequestParam('sort_order');
        $action = FHtml::getRequestParam('action');

        if (!empty($data)) {
            $result = [];
            $data = FHtml::decode($data);
            if (is_array($data)) {
                foreach ($data as $item) {
                    $ime = FHtml::getFieldValue($item, 'ime');
                    $name = FHtml::getFieldValue($item, 'name');
                    $code = FHtml::getFieldValue($item, 'code');
                    $id = FHtml::getFieldValue($item, 'id');
                    $service = FHtml::getFieldValue($item, 'service');
                    $ticket = FHtml::getFieldValue($item, 'ticket');
                    $counter = FHtml::getFieldValue($item, 'counter');
                    $status = FHtml::getFieldValue($item, 'status');
                    $sort_order = FHtml::getFieldValue($item, 'sort_order');
                    $action = FHtml::getFieldValue($item, 'action');
                    $model = Smartscreen::updateQueueModel($ime, $name, $id, $code, $service, $status, $counter, $ticket, $sort_order, $action);
                    $result[] = $model;
                }
            }
        } else {
            //updateQueueModel($ime, $name, $id = '', $code = '', $service = '', $status = '', $counter = '', $ticket = '', $sort_order = 0, $action = '') {
            $model = Smartscreen::updateQueueModel($ime, $name, $id, $code, $service, $status, $counter, $ticket, $sort_order, $action);
            $result[] = $model;
        }

        return FApi::getOutputForAPI($result, FConstant::SUCCESS, 'OK', ['code' => 200]);
    }
}
