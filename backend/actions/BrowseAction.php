<?php
namespace backend\actions;

use backend\actions\BaseAction;
use backend\modules\ecommerce\models\Product;
use common\components\FHtml;
use yii\helpers\Json;
use yii\helpers\StringHelper;

class BrowseAction extends BaseAction
{
    public function run()
    {
        $this->is_secured = false;
        if (($re = $this->isAuthorized()) !== true)
            return $re;

        $paramArray = FHtml::decode($this->params);
        $listArray = FHtml::decode($this->listname);
        $out = [];
        if (isset($listArray) && is_array($listArray)) {
            $list = [];
            foreach ($listArray as $listname1 => $params1) {

                if (!empty($params1) && strpos($params1, 'params') > -1) {
                    $arr = FHtml::toArray($params1, ';', ':');
                    $params = !empty($this->params) ? $this->params : trim(FHtml::getArrayParam($arr, 'params'));
                    $orderby = !empty($this->orderby) ? $this->orderby : trim(FHtml::getArrayParam($arr, 'orderby'));
                    $limit = !empty($this->limit) ? $this->limit : trim(FHtml::getArrayParam($arr, 'limit'));
                    $page = !empty($this->page) ? $this->page : trim(FHtml::getArrayParam($arr, 'page'));
                } else {
                    $params = $params1;
                    $orderby = !empty($this->orderby) ? $this->orderby : '';
                    $limit = !empty($this->limit) ? $this->limit : '';
                    $page = !empty($this->page) ? $this->page : '';
                }

                $list[] = [$listname1 => FHtml::getModelsForAPI($listname1, FHtml::mergeRequestParams($params, $this->paramsArray), $orderby, $limit, $page, false, '', $this->fields)];
            }

            $out['status'] = 'SUCCESS';
            $out['code'] = '';
            $out['data'] = $list;
            $out['name'] = $this->listname;
            $out['message'] = '';
            $out['type'] = 'list_many';
        } else {
            $paramArray = FHtml::decode($this->params);
            $listArray = FHtml::decode($this->listname);
            $this->listname = FHtml::getRequestParam(['list', 'name', 'object'], 'product');
            $list = FHtml::getModelsList($this->listname, FHtml::mergeRequestParams($paramArray, $this->paramsArray), $this->orderby, $this->limit, $this->page, false, '');
            $out = FHtml::getOutputForAPI(FHtml::prepareDataForAPI($list->models, '', FHtml::decode($this->fields)), $this->listname, '', 'data', $list->pagination->pageCount, $list->pagination->pageSize, $list->pagination->page);
            $out['code'] = $this->params;
        }
        return $out;
    }
}