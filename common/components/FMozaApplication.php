<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 28/03/2017
 * Time: 11:31 SA
 */

namespace common\components;

use backend\modules\system\models\Application;
use common\components\FHtml;
use frontend\components\Helper;
use frontend\modules\cms\Cms;
use frontend\modules\travel\TravelHelper;
use Yii;
use yii\base\ErrorException;
use yii\base\InvalidRouteException;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\UrlManager;

class FMozaApplication extends \yii\web\Application
{
    /**
     * Returns the configuration of core application components.
     * @see set()
     */
    public function coreComponents()
    {
        return [
            'log' => ['class' => 'yii\log\Dispatcher'],
            'view' => ['class' => 'common\components\FView'],
            'formatter' => ['class' => 'yii\i18n\Formatter'],
            'i18n' => ['class' => 'common\components\FI18N'],
            //'mailer' => ['class' => 'yii\swiftmailer\Mailer'],
            'mailer' => ['class' => 'common\components\FEmail'],
            'urlManager' => ['class' => 'common\components\FUrlManager'],
            //'urlManager' => ['class' => 'yii\web\UrlManager'],
            'assetManager' => ['class' => 'yii\web\AssetManager'],
            'security' => ['class' => 'yii\base\Security'],
                'request' => ['class' => 'yii\web\Request'],
                'response' => ['class' => 'common\components\FResponse'],
            //'response' => ['class' => 'yii\web\Response'],
                //'session' => ['class' => 'yii\web\Session'],
                'user' => ['class' => 'yii\web\User'],
                'errorHandler' => ['class' => 'yii\web\ErrorHandler'],
        ];
    }

    public $settings;
    public $translations;
    public $lang;
    public $email;

    public function run() {
        $start_time = microtime(TRUE);
        //start NodeJS
        $this->settings = FHtml::findApplicationParams();
        $this->translations = FHtml::findApplicationTranslations();
        $this->name = FHtml::settingCompanyName();
        //$this->language = FHtml::currentLang();
//        $this->email = FHtml::settingCompanyEmail();
//        $this->lang = FHtml::currentLang();

        $result = parent::run();
        $end_time = microtime(TRUE);
        $time_taken =($end_time - $start_time)*1000;
        $time_taken = round($time_taken,5);
        return $result;
    }

    public function preInit(&$config)
    {
        // merge core components with custom components
        foreach ($this->coreComponents() as $id => $component) {
            if (!isset($config['components'][$id])) {
                $config['components'][$id] = $component;
            } elseif (is_array($config['components'][$id])) {
                $config['components'][$id]['class'] = $component['class'];
            }
        }

        parent::preInit($config);

    }

    /**
     * Returns the time zone used by this application.
     * This is a simple wrapper of PHP function date_default_timezone_get().
     * If time zone is not configured in php.ini or application config,
     * it will be set to UTC by default.
     * @return string the time zone used by this application.
     * @see http://php.net/manual/en/function.date-default-timezone-get.php
     */
    public function getTimeZone()
    {
        return date_default_timezone_get();
    }

    /**
     * Sets the time zone used by this application.
     * This is a simple wrapper of PHP function date_default_timezone_set().
     * Refer to the [php manual](http://www.php.net/manual/en/timezones.php) for available timezones.
     * @param string $value the time zone used by this application.
     * @see http://php.net/manual/en/function.date-default-timezone-set.php
     */
    public function setTimeZone($value)
    {
        date_default_timezone_set($value);
    }

    /**
     * Returns the database connection component.
     * @return \yii\db\Connection the database connection.
     */
    public function getDb()
    {
        return $this->get('db');
    }

    /**
     * Returns the user component.
     * @return User the user component.
     */
    public function getUser()
    {
        $user = $this->get('user');
        return $user;
    }

    public function addError($errors, $message = null) {
        return FHtml::addError($errors, $message);
    }

    public function getSettings()
    {
        return $this->get('settings');
    }

    /**
     * Handles the specified request.
     * @param Request $request the request to be handled
     * @return Response the resulting response
     * @throws NotFoundHttpException if the requested route is invalid
     */
    public function handleRequest($request)
    {
        if (empty($this->catchAll)) {
            try {
                list ($route, $params) = $request->resolve();
            } catch (ErrorException $e) {
                //throw $e;
                throw new NotFoundHttpException(FHtml::t('message', 'Page not found'), 404, $e);
            }
        } else {
            $route = $this->catchAll[0];
            $params = $this->catchAll;
            unset($params[0]);
        }
        try {
            Yii::trace("Route requested: '$route'", __METHOD__);

            //var_dump($route);
            $route = FUrlManager::cleanRoute($route);

            $this->requestedRoute = $route;
            //var_dump($route); var_dump($params);

            $result = $this->runAction($route, $params);

            if ($result instanceof \yii\web\Response) {
                return $result;
            }  else {
                $response = $this->getResponse();
                if ($result !== null) {
                    $response->data = $result;
                }

                return $response;
            }
        } catch (InvalidRouteException $e) {
            //return $this->response->redirect(FFrontend::createHomeUrl());
            throw new NotFoundHttpException(FHtml::t('message', 'Page not found') . ': ' . $route, $e->getCode(), $e);
        }
    }

    /**
     * Runs a controller action specified by a route.
     * This method parses the specified route and creates the corresponding child module(s), controller and action
     * instances. It then calls [[Controller::runAction()]] to run the action with the given parameters.
     * If the route is empty, the method will use [[defaultRoute]].
     * @param string $route the route that specifies the action.
     * @param array $params the parameters to be passed to the action
     * @return mixed the result of the action.
     * @throws InvalidRouteException if the requested route cannot be resolved into an action successfully
     */
    public function runAction($route, $params = [])
    {
        $parts = $this->createController($route);
        if (is_array($parts)) {
            /* @var $controller Controller */
            list($controller, $actionID) = $parts;
            $oldController = Yii::$app->controller;
            Yii::$app->controller = $controller;
            $result = $controller->runAction($actionID, $params);
            //FHtml::var_dump($controller);
//            echo $actionID; var_dump($params);
//            var_dump($result);
            Yii::$app->controller = $oldController;

            return $result;
        } else {
            $id = $this->getUniqueId();
            throw new InvalidRouteException('Unable to resolve the request "' . ($id === '' ? $route : $id . '/' . $route) . '".');
        }
    }

}