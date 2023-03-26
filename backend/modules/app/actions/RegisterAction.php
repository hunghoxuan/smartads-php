<?php

namespace backend\modules\app\actions;

use backend\actions\BaseAction;
use backend\modules\app\models\AppUserAPI;
use common\components\FConstant;
use common\components\FApi;
use Imagine\Image\Box;
use Yii;
use yii\imagine\Image;

class RegisterAction extends BaseAction
{
    public $is_secured = false;

    public function run()
    {
        $username = FApi::getRequestParam('username', '');
        $password = FApi::getRequestParam('password', '');
        $name = FApi::getRequestParam('name', '');
        $gender = FApi::getRequestParam('gender', '');
        $address = FApi::getRequestParam('address', '');
        $phone = FApi::getRequestParam('phone', '');
        $dob = FApi::getRequestParam('dob', '');

        if (strlen($username) == 0
            || strlen($name) == 0
            || strlen($password) == 0
        ) {
            return FApi::getOutputForAPI('', FConstant::ERROR, FConstant::MISSING_PARAMS, ['code' => 202]);
        }

        $check = AppUserAPI::find()->where("username = '" . $username . "'")->one();

        if (isset($check)) {
            return FApi::getOutputForAPI('', FConstant::ERROR, FApi::getErrorMsg(225), ['code' => 225]);
        } else {
            $now = time();
            $today = date('Y-m-d H:i:s', $now);

            $new_user = new AppUserAPI();
            $new_user->name = $name;
            $new_user->email = $username;
            $new_user->username = $username;
            $new_user->gender = $gender;
            $new_user->address = $address;
            $new_user->phone = $phone;
            $new_user->is_active = FConstant::STATE_ACTIVE;
            $new_user->status = FConstant::LABEL_NORMAL;
            $new_user->created_date = $today;
            $new_user->dob = $dob;
            $new_user->balance = 1000000;
            $new_user->rate = 0;
            $new_user->rate_count = 0;
            $new_user->role = AppUserAPI::ROLE_USER;
            $new_user->type = AppUserAPI::TYPE_USER;
            $new_user->status = AppUserAPI::STATUS_NORMAL;
            $reset_token = md5($now);
            $new_user->password_reset_token = $reset_token;
            $new_user->setPassword($password);
            $new_user->generateAuthKey();

            //Save avatar
            $upload = false;
            $now = time();
            $file_path = Yii::getAlias('@' . UPLOAD_DIR) . '/' . 'app-user' . '/';
            $imageName = '';
            if (isset($_FILES['avatar'])) {
                $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
                $imageName = $now . 'avatar.' . $ext;

                $image_path = $file_path . $imageName;
                $upload = move_uploaded_file($_FILES['avatar']['tmp_name'], $image_path);

                if ($upload) {
                    $new_user->avatar = $imageName;
                    Image::getImagine()->open($image_path)
                        ->thumbnail(new Box(300, 300))
                        ->save($file_path . 'thumb' . $imageName, ['quality' => 100]);
                }
            }

            if ($new_user->save()) {
                $send = \Yii::$app->mailer->compose(['html' => 'welcome-html', 'text' => 'welcome-text', 'htmlLayout' => '@layouts/welcome-html.php'], ['user' => $new_user])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                    ->setTo($new_user->email)
                    ->setSubject('[' . Yii::$app->name . '] Welcome new member')
                    ->send();
                if ($send) {
                    return FApi::getOutputForAPI('', FConstant::SUCCESS, 'OK', ['code' => 200]);
                } else {
                    if (isset($_FILES['avatar'])) {
                        if ($upload && strlen($imageName) != 0) {
                            if (is_file($file_path . '/' . $imageName)) {
                                unlink($file_path . '/' . $imageName);
                            }
                            if (is_file($file_path . '/thumb' . $imageName)) {
                                unlink($file_path . '/thumb' . $imageName);
                            }
                        }
                    }
                    $new_user->delete();
                    return FApi::getOutputForAPI('', FConstant::ERROR, FApi::getErrorMsg(229), ['code' => 229]);
                }
            } else {
                $errors = $new_user->getErrors();
                if (isset($_FILES['avatar'])) {
                    if ($upload && strlen($imageName) != 0) {
                        if (is_file($file_path . '/' . $imageName)) {
                            unlink($file_path . '/' . $imageName);
                        }
                        if (is_file($file_path . '/thumb' . $imageName)) {
                            unlink($file_path . '/thumb' . $imageName);
                        }
                    }
                }
                return FApi::getOutputForAPI('', FConstant::ERROR, FApi::getErrorMessage($errors), ['code' => 203]);
            }
        }
    }
}
