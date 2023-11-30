<?php

namespace common\base;

use backend\models\AuthPermission;
use backend\models\User;
use common\components\FConstant;
use common\components\FHtml;
use common\components\FSecurity;
use common\models\BaseModel;
use Yii;
use yii\base\NotSupportedException;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $first_name
 * @property string $last_name
 * @property string $image
 * @property string $overview
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property string $role
 * @property string $application_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */

class BaseUser extends \common\models\BaseModel implements IdentityInterface
{
    const STATUS_DELETED = FHtml::USER_STATUS_DELETED;
    const STATUS_DISABLED = FHtml::USER_STATUS_DELETED;
    const STATUS_ACTIVE = FHtml::USER_STATUS_ACTIVE;
    const STATUS_BANNED = FHtml::USER_STATUS_DELETED;
    const STATUS_REJECTED = FHtml::USER_STATUS_DELETED;
    const STATUS_PENDING = FHtml::USER_STATUS_DELETED;
    const STATUS_NORMAL = FHtml::USER_STATUS_ACTIVE;
    const STATUS_PRO = FHtml::USER_STATUS_ACTIVE;
    const STATUS_VIP = FHtml::USER_STATUS_ACTIVE;

    const ROLE_USER = FHtml::ROLE_USER;
    const ROLE_MODERATOR = FHtml::ROLE_MODERATOR;
    const ROLE_ADMIN = FHtml::ROLE_ADMIN;
    const ROLE_ALL = FHtml::ROLE_ALL;
    const ROLE_NONE = FHtml::ROLE_NONE;

    public $password_new;
    public $password_retype;

    public $rights_array;
    public $groups_array;
    public $_username;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKeyValue();
        //return $this->getAuthKey();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id, $check_applications = null)
    {
        if (is_string($id) && !is_numeric($id))
            return self::findIdentityByAccessToken($id);
        return parent::findOne($id, false);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return parent::getOne(['auth_key' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username, $check_applications = null)
    {
        if (!isset($check_applications)) {
            if (FHtml::currentApplicationId() !== DEFAULT_APPLICATION_ID) {
                $check_applications = true;
            } else if (APPLICATIONS_ENABLED) {
                $check_applications = false;
            } else if (empty(DEFAULT_APPLICATION_ID)) {
                $check_applications = false;
            } else {
                $check_applications = !in_array($username, FSecurity::USER_NAME_SUPERADMIN);
            }
        }

        if (in_array($username, FSecurity::USER_NAME_SUPERADMIN)) {
            $check_applications = false;
        }

        if (strpos($username, '@') !== false) {
            $model =  self::findOne(['email' => $username, 'status' => SELF::STATUS_ACTIVE], $check_applications);
            if (isset($model))
                return $model;
        }
        if (is_numeric($username))
            $column = 'id';
        else
            $column = 'username';

        $model =  self::findOne([$column => $username, 'status' => SELF::STATUS_ACTIVE], $check_applications);

        if ((!isset($model) || !is_object($model)) && in_array($username, FSecurity::USER_NAME_ADMIN)) {
            $username = $username . '_' . FHtml::currentApplicationId();
            $model =  self::findOne([$column => $username, 'status' => SELF::STATUS_ACTIVE], true);
        }

        return $model;
    }

    public static function findUser($username)
    {
        if (APPLICATIONS_ENABLED) {
            $check_applications = false;
        } else if (empty(DEFAULT_APPLICATION_ID)) {
            $check_applications = false;
        } else {
            $check_applications = !in_array($username, FSecurity::USER_NAME_SUPERADMIN);
        }

        if (strpos($username, '@') !== false) {
            $model =  self::findOne(['email' => $username, 'status' => self::STATUS_ACTIVE], $check_applications);
            if (isset($model))
                return $model;
        }

        $model = self::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE], $check_applications);

        if ((!isset($model) || !is_object($model)) && in_array($username, FSecurity::USER_NAME_ADMIN)) {
            $username = $username . '_' . FHtml::currentApplicationId();
            $model =  self::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE], $check_applications);
        }

        return $model;
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
            'status' => self::STATUS_ACTIVE,
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
    public function behaviors()
    {
        return [
            //TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            ['role', 'default', 'value' => self::ROLE_USER],
            ['role', 'in', 'range' => [self::ROLE_USER, self::ROLE_ADMIN, self::ROLE_MODERATOR]],
        ];
    }


    public function getRole()
    {
        return $this->role;
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
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
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

    public function beforeSave($insert)
    {
        if (!empty($this->_username))
            $this->username = $this->_username;

        if (empty($this->username)) {
            if (!empty($this->code)) {
                $this->username = str_replace(' ', '_', $this->code);
            } else if (!empty($this->email)) {
                $this->username = explode('@', $this->email)[0];
            } else {
                $this->username = str_replace(' ', '_', $this->name);
            }
        }
        $this->username = strtolower($this->username);

        if (key_exists('User', $_POST)) {
            $this->password_new = key_exists('password_new', $_POST['User']) ? $_POST['User']['password_new'] : '';
            $this->password_retype = key_exists('password_retype', $_POST['User']) ? $_POST['User']['password_retype'] : '';
        }

        if (empty($this->email))
            $this->email = $this->username . '@' . FHtml::settingEmailDomain();

        if (!empty($this->password_new)) {
            if ($this->password_new != $this->password_retype) {
                FHtml::addError('Password does not match');
                return false;
            }
        }

        if (!empty($this->password_new) || $insert)
            FHtml::setUserPassword($this, $this->password_new);

        if (!isset($this->status))
            $this->status = self::STATUS_ACTIVE;

        if (empty($this->role))
            $this->role = FHtml::ROLE_USER;

        $this->created_at = FHtml::Now();
        $this->updated_at = FHtml::Now();

        if ($this->isNewRecord)
            $this->application_id = FHtml::currentApplicationId();

        if (in_array($this->username, FSecurity::USER_NAME_SUPERADMIN))
            $this->application_id = null;

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    public function getGroups()
    {
        return [];
    }

    public function getGroupsArray()
    {
        return [];
    }

    public function getRights()
    {
        return [];
    }

    public function getRightsArray()
    {
        return [];
    }

    public function getDevice()
    {
        return null;
    }

    public function afterFind()
    {
        $this->_username = $this->username;
        return parent::afterFind();
    }

    public
    static function findOne($condition, $selected_fields = [], $asArray = false, $applications_enabled = true)
    {
        return parent::findOne($condition, $selected_fields, $asArray, $applications_enabled);
    }
}
