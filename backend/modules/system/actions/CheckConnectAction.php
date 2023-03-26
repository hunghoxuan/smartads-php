<?php
/**
 * Created by PhpStorm.
 * User: LongPC
 * Date: 11/09/2018
 * Time: 15:20
 */

namespace backend\modules\system\actions;


use backend\actions\BaseAction;
use common\components\FApi;
use common\components\FConstant;
use common\components\FHtml;

class CheckConnectAction extends BaseAction
{
	public function run() {
		return FApi::getOutputForAPI(null, FConstant::SUCCESS, "success", [
			'code'        => 200,
			'total'       => 0,
			'page_limit'  => 0,
			'page_offset' => 0,
			'time'        => FHtml::Now(),
			'object_type' => ''
		]);
	}
}