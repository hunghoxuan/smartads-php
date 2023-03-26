<?php
namespace backend\components;

use backend\models\Auth;
use common\models\User;
use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;

/**
 * AuthHandler handles successful authentication via Yii auth component
 */
class AuthHandler
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function handle()
    {
        $attributes = $this->client->getUserAttributes();
        $duration = 30*24*3600;
        //email
        //avatar
        //graph.facebook.com/3402191759830854/picture
        //name
        //id
        //username

        $clientId = $this->client->getId();
        $clientTitle = $this->client->getTitle();


        $email = ArrayHelper::getValue($attributes, 'email');
        if($clientId == 'google') {
            $email = $attributes['emails'][0]['value'];
        }

        $id = ArrayHelper::getValue($attributes, 'id');

        if($clientId == 'google') {
            $nickname = $attributes['displayName'];
            $image = $attributes['image']['url'];
        } elseif ($clientId == 'github') {
            $nickname = ArrayHelper::getValue($attributes, 'login');
            $image = $attributes['avatar_url'];
        } else {
            if($clientId == 'twitter') {
                $image = $attributes['profile_image_url'];
                $nickname = ArrayHelper::getValue($attributes, 'name');

            } elseif ($clientId == "facebook") {
                $image = $attributes['picture']['data']['url'];
                $nickname = ArrayHelper::getValue($attributes, 'name');

            } else {
                $image = '';
                $nickname = '';
            }
        }


        /* @var Auth $auth */
        $auth = Auth::find()->where([
            'source' => $clientId,
            'source_id' => $id,
        ])->one();

        if (Yii::$app->user->isGuest) {
            if ($auth) { // login
                /* @var User $user */

                $auth->name = $nickname;
                $auth->image = $image;
                $auth->save();

                $user = $auth->user;
                $user->auth_id = $auth->id;
                $user->save();
                Yii::$app->user->login($user, $duration);
            } else { // signup
                $user = User::find()->where(['email' => $email])->one();
                if ($email !== null && isset($user)//User::find()->where(['email' => $email])->exists()
                ) {
                    /*
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', "User with the same email as in {client} account already exists in our system. Please try to login that email", ['client' => $this->client->getTitle()]),
                    ]);
                    */
                    /* @var $user User*/
                    $transaction = User::getDb()->beginTransaction();
                    $auth = new Auth([
                        'user_id' => $user->id,
                        'source' => $clientId,
                        'source_id' => (string)$id,
                        'name' => $nickname,
                        'image' => $image
                    ]);
                    if ($auth->save()) {
                        $user->auth_id = $auth->id;
                        $user->save();
                        $transaction->commit();
                        Yii::$app->user->login($user, $duration);
                    } else {
                        Yii::$app->getSession()->setFlash('error', [
                            Yii::t('app', 'Unable to save {client} account: {errors}', [
                                'client' => $clientTitle,
                                'errors' => json_encode($auth->getErrors()),
                            ]),
                        ]);
                    }
                } else {
                    $password = Yii::$app->security->generateRandomString(6);
                    $user = new User([
                        'username' => $email,
                        'email' => $email,
                        'password' => $password,
                    ]);
                    $user->generateAuthKey();
                    $user->generatePasswordResetToken();

                    $transaction = User::getDb()->beginTransaction();

                    if ($user->save()) {
                        $auth = new Auth([
                            'user_id' => $user->id,
                            'source' => $clientId,
                            'source_id' => (string)$id,
                            'name' => $nickname,
                            'image' => $image
                        ]);
                        if ($auth->save()) {
                            $user->auth_id = $auth->id;
                            $user->save();
                            $transaction->commit();
                            Yii::$app->user->login($user, $duration);
                        } else {
                            Yii::$app->getSession()->setFlash('error', [
                                Yii::t('app', 'Unable to save {client} account: {errors}', [
                                    'client' => $clientTitle,
                                    'errors' => json_encode($auth->getErrors()),
                                ]),
                            ]);
                        }
                    } else {
                        Yii::$app->getSession()->setFlash('error', [
                            Yii::t('app', 'Unable to save user: {errors}', [
                                'client' => $clientTitle,
                                'errors' => json_encode($user->getErrors()),
                            ]),
                        ]);
                    }
                }
            }
        } else { // user already logged in => sync
            if (!$auth) { // add auth provider
                $auth = new Auth([
                    'user_id' => Yii::$app->user->id,
                    'source' => $clientId,
                    'source_id' => (string)$attributes['id'],
                    'name' => $nickname,
                    'image' => $image
                ]);
                if ($auth->save()) {
                    /** @var User $user */
                    $user->auth_id = $auth->id;
                    $user->save();
                    Yii::$app->getSession()->setFlash('success', [
                        Yii::t('app', 'Linked {client} account.', [
                            'client' => $clientTitle
                        ]),
                    ]);
                } else {
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', 'Unable to link {client} account: {errors}', [
                            'client' => $clientTitle,
                            'errors' => json_encode($auth->getErrors()),
                        ]),
                    ]);
                }
            } else { // there's existing auth
                Yii::$app->getSession()->setFlash('error', [
                    Yii::t('app',
                        'Unable to link {client} account. There is another user using it.',
                        ['client' => $clientTitle]),
                ]);
            }
        }
    }
}