<?php

namespace backend\modules\app\controllers;

use common\controllers\BaseApiController;
use common\components\FHtml;
use backend\modules\ecommerce\models\Product;
use yii\base\Exception;
use yii\db\Query;
use yii\helpers\Json;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

class ApiController extends BaseApiController
{
    public function actions()
    {
        return [
            'login' => ['class' => 'backend\modules\app\actions\LoginAction', 'checkAccess' => [$this, 'checkAccess']],
            'register' => ['class' => 'backend\modules\app\actions\RegisterAction', 'checkAccess' => [$this, 'checkAccess']],
            'profile' => ['class' => 'backend\modules\app\actions\ProfileAction', 'checkAccess' => [$this, 'checkAccess']],
            'logout' => ['class' => 'backend\modules\app\actions\LogoutAction', 'checkAccess' => [$this, 'checkAccess']],
            'forget-password' => ['class' => 'backend\modules\app\actions\ForgetPasswordAction', 'checkAccess' => [$this, 'checkAccess']],
            'update-profile' => ['class' => 'backend\modules\app\actions\UpdateProfileAction', 'checkAccess' => [$this, 'checkAccess']],
            'change-password' => ['class' => 'backend\modules\app\actions\ChangePasswordAction', 'checkAccess' => [$this, 'checkAccess']],
            'device' => ['class' => 'backend\modules\app\actions\DeviceAction', 'checkAccess' => [$this, 'checkAccess']],
            'registerDevice' => ['class' => 'backend\modules\app\actions\RegisterDeviceAction', 'checkAccess' => [$this, 'checkAccess']],
        ];
    }
}
