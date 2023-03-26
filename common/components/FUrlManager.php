<?php
/**
 * Created by PhpStorm.
 * User: tongd
 * Date: 2017-07-31
 * Time: 08:53
 */
namespace common\components;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii\web\UrlRule;

class FUrlManager extends \yii\web\UrlManager
{
    const SITE_PARAM = 'sites';
    const LANG_PARAM = 'lang';

    const CATEGORY_PARAM = 'category_id';
    const APPLICATION_PARAM = 'application_id';

    const NAME_PARAM = 'name';
    const ID_PARAM = 'id';
    const CATEGORY_PREFIX = 'c';
    const ID_PREFIX = 'p';

    const LIST_URL = '/{name}-c{category_id}';
    const VIEW_URL = '/{name}-p{id}-c{category_id}';


    public function createUrl($params) {
        $site_param = self::SITE_PARAM; $lang_param = self::LANG_PARAM;
        $application_param = self::APPLICATION_PARAM;

        //remove redundant params: application_id, lang
        if (!is_array($params)) {
            $params = [$params];
        }
        if (key_exists($application_param, $params))
            unset($params[$application_param]);

        if (key_exists($lang_param, $params))
            unset($params[$lang_param]);

        $url = parent::createUrl($params);

        $baseUrl = Yii::$app->urlManager->getBaseUrl();
        $replaced = '';

        $showScriptName = Yii::$app->urlManager->showScriptName;
        $enablePrettyUrl = Yii::$app->urlManager->enablePrettyUrl;

        if ($showScriptName)
            $baseUrl = $baseUrl . '/index.php';

        $application_id = FHtml::currentApplicationId();
        $lang_id = FHtml::currentLang();

        if (FHtml::isApplicationsEnabled() && $application_id != DEFAULT_APPLICATION_ID) {
            $replaced = "/$site_param/$application_id";
        }

        if (FHtml::settingLangShowInUrl() && $lang_id != DEFAULT_LANG) {
            $replaced .= "/$lang_param/$lang_id";
        }

        if (!empty($replaced)) {
            if(!empty($baseUrl))
                $url = str_replace($baseUrl . '/', $baseUrl . $replaced . '/', $url);
            else
                $url = $replaced . '/' . $url;
        }

        return $url;
    }

    public static function cleanRoute($route) {
        $url = Yii::$app->request->getUrl();
        $arr = explode('/', $url);
        $site_param = self::SITE_PARAM;
        $lang_param = self::LANG_PARAM;

        $application_id = '';
        foreach ($arr as $i => $item) {
            if ($item == $site_param) {
                $application_id = $arr[$i + 1];
                if (!empty($application_id)) {
                    FHtml::setApplicationId($application_id);
                }
            } else if ($item == $lang_param) {
                $lang = $arr[$i + 1];
                if (!empty($lang))
                    FHtml::setCurrentLang($lang);
            }
        }

        if (empty($application_id)) {
            FHtml::setApplicationId(DEFAULT_APPLICATION_ID);
        }

        $arr = explode('/', $route);
        $arr1 = [];
        $application_id = ''; $lang = '';

        foreach ($arr as $i => $item) {
            if ($item == $site_param) {
                $application_id = $arr[$i + 1];

                if (!empty($application_id)) {
                    FHtml::setApplicationId($application_id);
                }
            } else if ($item == $lang_param) {
                $lang = $arr[$i + 1];
                if (!empty($lang))
                    FHtml::setCurrentLang($lang);
            } else if ($item == $application_id || $item == $lang) {
                continue;
            } else {
                $arr1[] = $item;
            }
        }

        $route = implode('/', $arr1);

        return $route;
    }

    /**
     * Initializes UrlManager.
     */
    public function init()
    {
        $zone = FHtml::currentZone();
        if ($zone == FRONTEND) {
            $rules = $this->rules;
            $site_param = self::SITE_PARAM;
            $lang_param = self::LANG_PARAM;
            $application_param = self::APPLICATION_PARAM;
            $name_param = self::NAME_PARAM;
            $cat_param = self::CATEGORY_PARAM;
            $id_param = self::ID_PARAM;
            $id_prefix = self::ID_PREFIX;
            $cat_prefix = self::CATEGORY_PREFIX;

            $prefix_arr = ["$site_param/<$application_param:[a-zA-Z0-9_ -]+>/", "$site_param/<$application_param:[a-zA-Z0-9_ -]+>/$lang_param/<$lang_param:[a-zA-Z0-9_ -]+>/", "$lang_param/<$lang_param:[a-zA-Z0-9_ -]+>/", ""];

            //$prefix_arr = [''];
            foreach ($rules as $key => $value) {
                foreach ($prefix_arr as $prefix) {
                    if (!empty($prefix))
                        $this->rules = array_merge($this->rules, [$prefix . $key => $value]);

                    if (!StringHelper::endsWith($value, '/view')) {
                        $this->rules = array_merge($this->rules, [$prefix . $key . "/<$name_param:[a-zA-Z0-9_ -]+>-$id_prefix<$id_param:\d+>-$cat_prefix" => $value . '/view']);
                        $this->rules = array_merge($this->rules, [$prefix . $key . "/<$name_param:[a-zA-Z0-9_ -]+>-$id_prefix<$id_param:\d+>" => $value . '/view']);
                        $this->rules = array_merge($this->rules, [$prefix . $key . "/<$name_param:[a-zA-Z0-9_ -]+>-$id_prefix<$id_param:\d+>-$cat_prefix<$cat_param:\d+>" => $value . '/view']);
                    }
                    if (!StringHelper::endsWith($value, '/list')) {
                        $this->rules = array_merge($this->rules, [$prefix . $key . "/<$name_param:[a-zA-Z0-9_ -]+>-$cat_prefix<$cat_param:\d+>" => $value . '/list']);
                    }
                }
            }

            //FHtml::var_dump($this->rules); die;

        } else {
            $rules = $this->rules;
            $site_param = self::SITE_PARAM;
            $lang_param = self::LANG_PARAM;
            $application_param = self::APPLICATION_PARAM;

            $prefix_arr = ["$site_param/<$application_param:[a-zA-Z0-9_ -]+>/", "$site_param/<$application_param:[a-zA-Z0-9_ -]+>/$lang_param/<$lang_param:[a-zA-Z0-9_ -]+>/", "$lang_param/<$lang_param:[a-zA-Z0-9_ -]+>/"];

            foreach ($rules as $key => $value) {
                foreach ($prefix_arr as $prefix) {
                    $this->rules = array_merge($this->rules, [$prefix . $key => $value]);
                }
            }
        }

        parent::init();
    }

    /**
     * Parses the user request.
     * @param Request $request the request component
     * @return array|boolean the route and the associated parameters. The latter is always empty
     * if [[enablePrettyUrl]] is false. False is returned if the current request cannot be successfully parsed.
     */
    public function parseRequest($request)
    {
        if ($this->enablePrettyUrl) {
            $pathInfo = $request->getPathInfo();
            /* @var $rule UrlRule */
            foreach ($this->rules as $rule) {
                if (($result = $rule->parseRequest($this, $request)) !== false) {
                    return $result;
                }
            }

            if ($this->enableStrictParsing) {
                return false;
            }

            Yii::trace('No matching URL rules. Using default URL parsing logic.', __METHOD__);

            // Ensure, that $pathInfo does not end with more than one slash.
            if (strlen($pathInfo) > 1 && substr_compare($pathInfo, '//', -2, 2) === 0) {
                return false;
            }

            $suffix = (string) $this->suffix;
            if ($suffix !== '' && $pathInfo !== '') {
                $n = strlen($this->suffix);
                if (substr_compare($pathInfo, $this->suffix, -$n, $n) === 0) {
                    $pathInfo = substr($pathInfo, 0, -$n);
                    if ($pathInfo === '') {
                        // suffix alone is not allowed
                        return false;
                    }
                } else {
                    // suffix doesn't match
                    return false;
                }
            }

            return [$pathInfo, []];
        } else {
            Yii::trace('Pretty URL not enabled. Using default URL parsing logic.', __METHOD__);
            $route = $request->getQueryParam($this->routeParam, '');
            if (is_array($route)) {
                $route = '';
            }

            return [(string) $route, []];
        }
    }
}