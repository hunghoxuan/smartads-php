<?php

namespace backend\modules\system\controllers;

use backend\modules\system\actions\CheckConnectAction;

/**
 * Default controller for the `api` module
 */
class ApiController extends \backend\controllers\ApiController
{
	/**
	 * Renders the index view for the module
	 * @return array
	 */

	public $modelClass = 'backend\modules\system\models\ObjectComment';

	public function actions() {
		return [
			'review'              => ['class' => 'backend\modules\system\actions\ReviewAction', 'checkAccess' => [$this, 'checkAccess']],
			'review-list'         => ['class' => 'backend\modules\system\actions\ReviewListAction', 'checkAccess' => [$this, 'checkAccess']],
			'comment'             => ['class' => 'backend\modules\system\actions\CommentAction', 'checkAccess' => [$this, 'checkAccess']],
			'comment-list'        => ['class' => 'backend\modules\system\actions\CommentListAction', 'checkAccess' => [$this, 'checkAccess']],
			'request'             => ['class' => 'backend\modules\system\actions\RequestAction', 'checkAccess' => [$this, 'checkAccess']],
			'link'                => ['class' => 'backend\modules\system\actions\LinkAction', 'checkAccess' => [$this, 'checkAccess']],
			'social-list'         => ['class' => 'backend\modules\system\actions\SocialListAction', 'checkAccess' => [$this, 'checkAccess']],
			'message'             => ['class' => 'backend\modules\system\actions\MessageAction', 'checkAccess' => [$this, 'checkAccess']],
			'application-setting' => ['class' => 'backend\modules\system\actions\ApplicationSettingAction', 'checkAccess' => [$this, 'checkAccess']],
			'activity'            => ['class' => 'backend\modules\system\actions\ActivityAction', 'checkAccess' => [$this, 'checkAccess']],
			'banner'              => ['class' => 'backend\modules\system\actions\BannerAction', 'checkAccess' => [$this, 'checkAccess']],
			'check-connect'       => ['class' => CheckConnectAction::className(), 'checkAccess' => [$this, 'checkAccess']],
		];
	}
}
