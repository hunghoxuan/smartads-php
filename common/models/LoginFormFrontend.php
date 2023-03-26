<?php
namespace common\models;

use backend\models\AppUser;
use common\components\FSecurity;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginFormFrontend extends Model
{
    public $username;
    public $password;
    public $email;
    public $rememberMe = true;
    public $asAdmin = false;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['email', 'password'], 'required'],
            //['email','email'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            ['asAdmin', 'boolean'],
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
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return AppUser|null
     */

    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = FSecurity::getUser($this->email, $this->asAdmin ? BACKEND : FRONTEND);
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
        if ($this->validate()) {
            if ($this->asAdmin) {
                return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
            } else {
                return Yii::$app->appuser->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
            }
        } else {
            return false;
        }
    }
}
