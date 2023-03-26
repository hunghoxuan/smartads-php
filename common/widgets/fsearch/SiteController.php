<?php
namespace common\widgets\FSearch;

use common\components\FHtml;
use common\models\LoginFormFrontend;
use frontend\components\Helper;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\SignupForm;
use backend\modules\ecommerce\models\Product;
use backend\modules\cms\models\CmsBlogs;

/**
 * Site controller
 */
class SiteController extends \frontend\components\FrontendController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['signup'],
                'rules' => [
                    [
                        'actions' => ['signup','login','training','detail','co-working','article'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'logout' => ['post'],
                ],
            ],
        ];
    }


    /**
     * Displays homepage.
     *
     * @return mixed
     */
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index', []);
    }

    public function actionLogin()
    {
        if (Yii::$app->request->isAjax) {
            $model = new LoginFormFrontend();
            $model->username = FHtml::getRequestParam('username');
            $model->password = FHtml::getRequestParam('password');
            $model->asAdmin = FHtml::getRequestParam('asAdmin');

            if (!isset($model->asAdmin))
                $model->asAdmin = false;

            if ($model->asAdmin == 'false')
                $model->asAdmin = false;
            else
                $model->asAdmin = true;

            if ($model->login())
                return 1;
            else
                return FHtml::t('common', 'Invalid Username & Password. Please check again.');
        } else {
            $model = new LoginFormFrontend();
            if ($model->load(Yii::$app->request->post())) {
                if ($model->login()) {
                    $url = Helper::createHomeUrl();
                    $text = FHtml::t('common', 'Login success !!! .');
                    FHtml::addMessage($text);
                    return $this->redirect($url);
                }
                $text = FHtml::t('common', 'Invalid Username & Password. Please check again.');
                FHtml::addError($text);
                return $this->render('login', [
                    'model' => $model,
                ]);
            }
            return $this->render('login', [
                'model' => $model
            ]);
        }
    }

    public function actionSignup()
    {
        if (Yii::$app->request->isAjax) {
            $model2 = new SignupForm();
            $model2->email = FHtml::getRequestParam('email');
            $model2->password = FHtml::getRequestParam('password');
            $model2->username = explode('@', $model2->email)[0];
            $model2->name = $model2->username;
            if ($user = $model2->signup()) {
                if (is_object($user))
                    $result = FHtml::currentUser()->login($user, 3600 * 24 * 30);
                if ($result)
                    return 1;
                else
                    return FHtml::t('common', 'Unable to register as Invalid Email & Password. Please check again.');
            }
            return FHtml::t('common', 'Unable to register as Invalid Email & Password. Please check again.');

        } else {
            $model2 = new SignupForm();

            if ($model2->load(Yii::$app->request->post())) {
                if ($user = $model2->signup()) {

                    if (is_object($user)){
                        FHtml::currentUser()->login($user, 3600 * 24 * 30);
                    }
                    else {
                        $text = FHtml::t('common', 'Invalid Email, Username && Password. Please check again.');
                        FHtml::addError($text);
                        return $this->render('login', [
                            'model' => $model2
                        ]);
                    }
                    $url = Helper::createHomeUrl();
                    $text = FHtml::t('common', 'Create new account success !!! .');
                    FHtml::addMessage($text);
                    return $this->redirect($url);
                }

            } else {
                return $this->render('login', [
                    'model' => $model2
                ]);
            }
        }
    }

    public function actionArticle(){
        return $this->render('index', []);
    }

    public function actionTerms()
    {
        return $this->render('terms', []);
    }

    public function actionMessages()
    {
        if (empty(FHtml::currentUserId())) {
            return $this->redirect(['/']);
        }

        return $this->render('messages', []);
    }

    public function actionInbox()
    {
        if (empty(FHtml::currentUserId())) {
            return $this->redirect(['/']);
        }
        return $this->render('inbox', []);
    }

    public function actionAbout()
    {
        return $this->render('about', []);
    }
    public function actionSearch()
    {

        if (Yii::$app->request->isAjax) {
            $txtName = FHtml::getRequestParam('txtName');
            $object_type = 'product';
            $condition = '';
            $data_search = array();
            if (!empty($txtName)) {

                $data_search = Product::find()
                    ->select(['id', 'name', 'overview', 'image', 'thumbnail', 'category_id'])
                    ->where(['like', 'name', $txtName])
                    ->all();

            } else {
                $data_search = Product::find()
                    ->select(['id', 'name', 'overview', 'image', 'thumbnail', 'category_id'])
                    ->all();
            }

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $data_cus = array();
            foreach ($data_search as $key => $item) {
                $data_cus[$key]['image'] = FHtml::showImage(FHtml::getFieldValue($item, [ 'thumbnail','image']), 'product', '', '', '', strip_tags(FHtml::getFieldValue($item, 'name')));
                $data_cus[$key]['name'] = FHtml::getFieldValue($item, 'name');
                $data_cus[$key]['readmore'] = FHtml::t('common', 'Read more');
                $data_cus[$key]['href'] = $item->createViewUrl('product');
            }
            return [
                'data_cus' => $data_cus,
            ];
        }
    }
}
