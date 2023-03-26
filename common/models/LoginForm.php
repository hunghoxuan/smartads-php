<?php
namespace common\models;

use common\components\FHtml;
use common\components\FSecurity;
use Yii;
use yii\base\Model;


/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    public $asAdmin = true;

    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // username is validated by validateUsername()
            ['username', 'validateUsername'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (isset($user) && !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Invalid Password');
            }
        }
    }

    public function validateUsername($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user) {
                $this->addError($attribute, 'User not found');
            }
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === null) {
            $username = $this->username;
            if (in_array($username, FSecurity::USER_NAME_ADMIN) && FHtml::currentApplicationId() != DEFAULT_APPLICATION_ID && !empty(DEFAULT_APPLICATION_ID)) { // can not save new user with same name as existing user, so create temporary name
                $username = $this->username . '_' . FHtml::currentApplicationId();
            }
            $zone = FHtml::currentZone();
            $this->_user = FSecurity::getUser($username, $zone);
        }
        return $this->_user;
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        $user = $this->getUser();

        // auto add first Admin User
//        if ((!is_object($user) || !isset($user)) &&  in_array($this->username,  array_merge(FSecurity::USER_NAME_SUPERADMIN, FSecurity::USER_NAME_ADMIN )) && ($this->password == DEFAULT_PASSWORD)) {
//            $username = $this->username;
//            if (in_array($username, FSecurity::USER_NAME_ADMIN)) { // can not save new user with same name as existing user, so create temporary name
//                $username = $this->username . '_' . FHtml::currentApplicationId();
//            }
//
//            $user = FSecurity::addUser($username, $username .'@' . FHtml::settingEmailDomain(), DEFAULT_PASSWORD,   FHtml::currentApplicationId() == FHtml::currentApplicationCode() ? User::ROLE_ADMIN : User::ROLE_MODERATOR);
//        }

        if ($this->validate()) {
            $result = Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
            FSecurity::logAction(['log_date' => FHtml::Today(), 'user_id' => FHtml::currentUserId(), 'action' => 'login'], 'login');
            return $result;
        } else {
            return false;
        }
    }
}
