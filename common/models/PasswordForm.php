<?php
namespace common\models;

use Yii;
use yii\base\Model;

//use backend\models\AppUser;

/* @var $user User */
class PasswordForm extends Model
{
    public $oldPass;
    public $newPass;
    public $repeatNewPass;

    public function rules()
    {
        return [
            [['oldPass', 'newPass', 'repeatNewPass'], 'required'],
            ['oldPass', 'findPasswords'],
            ['repeatNewPass', 'compare', 'compareAttribute' => 'newPass'],
        ];
    }

    public function findPasswords($attribute, $params)
    {
        $current_user = Yii::$app->user->identity;
        /* @var $current_user User */

        $user = User::find()->where([
            'username' => $current_user->username
        ])->one();
        //$password = $user->password_hash;
        //if($password != $this->oldPass)
        /* @var $user User */
        if (!$user || !$user->validatePassword($this->oldPass)) {
            $this->addError($attribute, 'Old password is incorrect');
        }
    }

    public function attributeLabels()
    {
        return [
            'oldPass' => 'Old Password',
            'newPass' => 'New Password',
            'repeatNewPass' => 'Repeat New Password',
        ];
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
}