<?php
namespace common\models;

use yii\base\Model;
use Yii;

/**
 * Class ChangePasswordForm
 * @package common\models
 */
class ChangePasswordForm extends Model
{
    /**
     * @var
     */
    public $token;
    /**
     * @var
     */
    public $email;
    /**
     * @var
     */
    public $newPassword;
    /**
     * @var
     */
    public $newPasswordRepeat;

    /**
     * @return array validation rules for model attributes.
     */

    public function rules()
    {
        return [
            [['email', 'token', 'newPassword', 'newPasswordRepeat'], 'required'],
            ['token', 'string', 'min' => 5, 'max' => 255],
            ['email', 'email'],
            ['email', 'string', 'min' => 5, 'max' => 255],
            [['newPassword' , 'newPasswordRepeat'], 'safe'],
            [
                'newPasswordRepeat', 'compare',
                'compareAttribute' => 'newPassword',
            ],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'email' => 'Email',
            'token' => 'Token',
            'newPassword' => 'New password',
            'newPasswordRepeat' => 'Repeat Password',
        );
    }
}