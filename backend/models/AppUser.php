<?php

namespace backend\models;

use backend\modules\app\models\AppDevice;
use common\components\FHtml;
use Yii;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "app_user".
 *
 * @property integer $id
 * @property string $avatar
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string $auth_key
 * @property string $password
 * @property string $description
 * @property string $gender
 * @property string $dob
 * @property int $role
 * @property string $phone
 * @property string $address
 * @property integer $status
 * @property string $created_date
 * @property string $modified_date
 */
class AppUser extends \backend\modules\app\models\AppUser implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
        ];
    }

    /*
    * @property AppUserDevice[] $devices
    */
    public function getDevice()
    {
        return $this->hasMany(AppDevice::className(), ['user_id' => 'id']);
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
        throw new NotSupportedException();
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

    public static function getDb()
    {
        return FHtml::currentDb();
    }
}
