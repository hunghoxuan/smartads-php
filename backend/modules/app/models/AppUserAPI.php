<?php

namespace backend\modules\app\models;

use common\base\BaseAPIObject;
use common\components\FApi;
use common\components\FHtml;
use frontend\models\Auth;
use Yii;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;

/**
 *
 * @property AppDeviceAPI[] $devices
 * @property AppTokenAPI $loginToken
 *
 * @property Auth $auth

 * @property integer $id
 * @property string $avatar
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $description
 * @property string $content
 * @property string $gender
 * @property string $dob
 * @property string $phone
 * @property string $weight
 * @property string $height
 * @property string $address
 * @property string $country
 * @property string $state
 * @property string $city
 * @property string $balance
 * @property integer $point
 * @property string $card_number
 * @property string $card_cvv
 * @property string $card_exp
 * @property string $lat
 * @property string $long
 * @property double $rate
 * @property integer $rate_count
 * @property integer $is_online
 * @property integer $is_active
 * @property string $type
 * @property string $status
 * @property integer $role
 * @property string $provider_id
 * @property string $created_date
 * @property string $modified_date
 * @property string $application_id
 */
class AppUserAPI extends BaseAPIObject implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    const ROLE_USER = 10;
    const ROLE_MODERATOR = 20;
    const ROLE_ADMIN = 30;
    const TYPE_USER = 'user';
    const STATUS_NORMAL = 'normal';

    public function fields()
    {
        $fields = parent::fields();
        $folder = 'app-user';
        $image = FApi::getImageUrlForAPI($this->avatar, $folder);
        $this->avatar = $image;
        return $fields;
    }

    public function getApiFields()
    {
        $fields = [
            'id',
            'avatar',
            'name',
            'username',
            'email',
            'description',
            'balance',
            'gender',
            'phone',
            'dob',
            'address',
            'lat',
            'long',
            'is_active',
            'status',
            'rate',
            'rate_count',
            'created_date',
            'modified_date'
        ];
        return $fields;
    }

    /**
     * @inheritdoc
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'is_active' => self::STATUS_ACTIVE]);
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'is_active' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'is_active' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotFoundHttpException();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    public function getRole()
    {
        return $this->role;
    }

    public static function isAdmin($role)
    {
        return $role == self::ROLE_ADMIN;
    }

    public static function isModerator($role)
    {
        return $role == self::ROLE_MODERATOR;
    }

    public static function isNormalUser($role)
    {
        return $role == self::ROLE_USER;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @inheritdoc
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }


    public static function findUser($username)
    {
        return static::findOne(['username' => $username, 'is_active' => self::STATUS_ACTIVE]);
    }

    public function getDevices()
    {
        return $this->hasMany(AppDeviceAPI::className(), ['user_id' => 'id']);
    }

    public function getLoginToken()
    {
        return $this->hasOne(AppTokenAPI::className(), ['user_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuth()
    {
        return $this->hasOne(Auth::className(), ['id' => 'auth_id']);
    }

    public static function getRoleLabel($role)
    {
        $str = array(
            AppUserAPI::ROLE_USER => '<span class="label label-sm label-info">' . FHtml::t('common', 'User') . '</span>',
            AppUserAPI::ROLE_MODERATOR => '<span class="label label-sm label-warning">' . FHtml::t('common', 'Moderator') . '</span>',
            AppUserAPI::ROLE_ADMIN => '<span class="label label-sm label-danger">' . FHtml::t('common', 'Admin') . '</span>',
        );
        return isset($str[$role]) ? $str[$role] : $role;
    }

    public static function getStatusLabel($status)
    {
        $str = array(
            AppUser::STATUS_NORMAL => '<span class="label label-sm label-info">' . FHtml::t('common', 'Normal') . '</span>',
            AppUser::STATUS_BANNED => '<span class="label label-sm label-danger">' . FHtml::t('common', 'Banned') . '</span>',
            AppUser::STATUS_PENDING => '<span class="label label-sm label-warning">' . FHtml::t('common', 'Pending') . '</span>',
            //...
        );
        return isset($str[$status]) ? $str[$status] : $status;
    }

}
