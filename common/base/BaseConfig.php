<?php

/**



 * This is the customized model class for table "<?= $generator->generateTableName($tableName) ?>".
 */

namespace common\base;

use backend\models\Application;
use backend\models\Settings;
use backend\modules\cms\models\CmsWidgets;
use common\components\FApplication;
use common\components\FConfig;
use common\components\FDatabase;
use common\components\FFile;
use common\components\FHtml;
use common\components\FModel;
use common\components\FSecurity;
use frontend\components\Helper;
use Yii;
use yii\base\Exception;
use yii\caching\FileCache;
use yii\db\Connection;
use yii\helpers\BaseInflector;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\swiftmailer\Mailer;

/**
 * Class BaseConfig
 * @package common\base
 */
class BaseConfig extends FSecurity
{
    const VERSION = '2.0';

    const
        SETTINGS_GROUPS = ['General', 'Contacts', 'Languages', 'Ecommerce', 'Format', 'Theme', 'Backend', 'Website', 'SEO'],
        SETTINGS_EXCLUDED = ['View', 'Form', 'Index', 'Index Print', 'ADMIN_THUMBNAIL_WIDTH', FHtml::SETTINGS_THUMBNAIL_SIZE, 'UPLOAD_FOLDER'],
        SETTINGS_FORM_VIEW_POPUP = 'FORM_VIEW_POPUP',
        SETTINGS_FORM_EDIT_POPUP = 'FORM_EDIT_POPUP',
        SETTINGS_GRID_BUTTONS_TYPE = 'Buttons Style',
        SETTINGS_FORM_TYPE = 'Form Style',
        SETTINGS_ADMIN_INLINE_EDIT = 'Allow Inline Edit',
        SETTINGS_ADMIN_MODULES = 'Admin Menu Modules',
        SETTINGS_FIELD_LAYOUT = 'Field Layout',
        SETTINGS_FORM_LAYOUT = 'Form Layout',
        SETTINGS_AJAX_ENABLED = 'AJAX_ENABLED',
        SETTINGS_MATERIAL_DESIGN = 'Theme Style',
        SETTINGS_CONTROLS_HAS_ROUND_BORDER = 'CONTROLS_HAS_ROUND_BORDER',
        SETTINGS_PORTLET_STYLE = 'Portlet Style',
        SETTINGS_BORDER_STYLE = 'Border Style',
        SETTINGS_DISPLAY_PORTLET = 'SETTINGS_DISPLAY_PORTLET',
        SETTINGS_MAIN_COLOR = 'main_color',
        SETTINGS_LANG = 'lang',
        SETTINGS_ADMIN_MAIN_COLOR = 'Theme Color',
        SETTINGS_FORM_CONTROLS_ALIGNMENT = 'Controls Alignment',
        SETTINGS_DISPLAY_PAGECONTENT_HEADER = 'DISPLAY_PAGECONTENT_HEADER',
        SETTINGS_PAGE_SIZE = 'Page Size',
        SETTINGS_CURRENCY = 'Format Currency',
        SETTINGS_DATE_FORMAT = 'Format Date',
        SETTINGS_THUMBNAIL_SIZE = 'thumbnail_size',
        SETTINGS_DAYS_OF_WEEK_DISABLED = 'DAYS_OF_WEEK_DISABLED',
        SETTINGS_HOURS_DISABLED = 'HOURS_DISABLED',
        SETTINGS_DATETIME_FORMAT = 'Format Datetime',
        SETTINGS_COMPANY_NAME = 'name',
        SETTINGS_COMPANY_COPYRIGHT = 'copyright',
        SETTINGS_COMPANY_PRIVACY = 'privacy',
        SETTINGS_COMPANY_TERMS_OF_SERVICE = 'terms_of_service',
        SETTINGS_COMPANY_PROFILE = 'profile',
        SETTINGS_COMPANY_DOMAIN = 'website',
        SETTINGS_COMPANY_LOGO = 'logo',
        SETTINGS_COMPANY_FAVICON = 'favicon',
        SETTINGS_COMPANY_FAX = 'fax',
        SETTINGS_COMPANY_SLOGAN = 'slogan',
        SETTINGS_COMPANY_DESCRIPTION = 'description',
        SETTINGS_COMPANY_KEYWORD = 'keyword',
        SETTINGS_COMPANY_EMAIL = 'email',
        SETTINGS_COMPANY_WEBSITE = 'website',
        SETTINGS_COMPANY_ADDRESS = 'address',
        SETTINGS_COMPANY_MAP = 'map',
        SETTINGS_COMPANY_PHONE = 'phone',
        SETTINGS_COMPANY_HOTLINE = 'hotline',
        SETTINGS_COMPANY_CHAT = 'chat',
        SETTINGS_COMPANY_FACEBOOK = 'facebook',
        SETTINGS_COMPANY_TWITTER = 'twitter',
        SETTINGS_COMPANY_GOOGLE = 'google',
        SETTINGS_COMPANY_YOUTUBE = 'youtube',
        SETTINGS_PAGE_TITLE = 'page_title',
        SETTINGS_PAGE_DESCRIPTION = 'Page Description',
        SETTINGS_PAGE_IMAGE = 'Page Image',
        SETTINGS_SITE_ANALYTICS = 'Google Analytics',
        SETTINGS_TIME_FORMAT = 'time_format',
        SETTINGS_WEB_THEME = 'web_theme',
        SETTINGS_BODY_CSS = 'body_css',
        SETTINGS_BODY_STYLE = 'body_style',
        SETTINGS_FRONTEND_INDEX_FILE = 'Frontend Index File',
        SETTINGS_PAGE_CSS = 'page_css',
        SETTINGS_PAGE_STYLE = 'page_style',
        SETTINGS_GOOGLE_API_KEY = 'Google API Key',
        SETTINGS_ADMIN_MENU_OPEN = 'ADMIN_MENU_OPEN',
        SETTINGS_CACHE_ENABLED = 'Cache Enabled',
        SETTINGS_DEFAULT_FRONTEND_MODULE = 'Default Frontend Module',
        SETTINGS_IS_CART_ENABLED = 'Shopping Cart Enabled',
        SETTINGS_PAYPAL_API_USERNAME = 'Paypal API Username',
        SETTINGS_PAYPAL_API_EMAIL = 'Paypal API Email',
        SETTINGS_PAYPAL_API_PASSWORD = 'Paypal API Password',
        SETTINGS_PAYPAL_API_SIGNATURE = 'Paypal API Signature',
        SETTINGS_PAYPAL_API_LIVE = 'Paypal API LIVE MODE',


        SETTINGS_PEM_FILE = 'PEM_FILE';

    /**
     * @var
     */
    private static $detect;

    /**
     * @return string
     */
    public static function frameworkVersion()
    {
        return 'framework';
    }

    /**
     * @return bool|mixed
     */
    public static function isAjaxRequest()
    {
        return Yii::$app->request->isAjax;
    }

    /**
     * @param null $table
     * @return bool|null
     */
    public static function isLanguagesEnabled($table = null)
    {
        $result = self::settingLanguagesEnabled();
        if ($result && isset($table) && !empty($table))
            $result = self::isDBLanguagesEnabled($table);

        return $result;
    }

    /**
     * @return null
     */
    public static function settingLanguagesEnabled()
    {
        return FConfig::getApplicationConfig('languages_enabled', false);
    }

    /**
     * @param null $table
     * @return bool|null
     */
    public static function isDBLanguagesEnabled($table = null)
    {
        $result = self::settingDBLanguaguesEnabled();

        if ($result && isset($table) && !empty($table)) {
            if (is_object($table) && isset($table) && method_exists($table, 'isDBLanguagesEnabled')) {
                $result = $table->isDBLanguagesEnabled();
            }

            if (!$result)
                return false;

            if (is_object($table) || is_array($table))
                $table = FHtml::getTableName($table);

            if (FHtml::isInArray($table, FHtml::EXCLUDED_TABLES_AS_MULTILANGS))
                return false;

            $result = FHtml::isInArray($table, FHtml::INCLUDED_TABLES_AS_MULTILANGS);
        }
        return $result;
    }

    /**
     * @return null
     */
    public static function settingDBLanguaguesEnabled()
    {
        return FConfig::getApplicationConfig('db_languages_enabled', DB_LANGUAGES_ENABLED);
    }

    /**
     * @param null $model
     * @param bool $skip_checked
     * @return bool
     */
    public static function isApplicationsEnabled($model = null, $skip_checked = false)
    {
        return APPLICATIONS_ENABLED;
    }

    /**
     * @return null
     */
    public static function isCacheEnabled()
    {
        return self::settingCacheEnabled();
    }

    /**
     * @return null
     */
    public static function settingCacheEnabled()
    {
        return FConfig::getApplicationConfig('cache_enabled', true);
    }

    /**
     * @param null $model
     * @return bool
     */
    public static function isObjectActionsLogEnabled($model = null)
    {
        return false;
    }

    /**
     * @return null
     */
    public static function isDBSecurityEnabled()
    {
        return self::settingDBSecurityEnabled();
    }

    //2017/5/3

    /**
     * @return null
     */
    public static function settingDBSecurityEnabled()
    {
        return FConfig::getApplicationConfig('db_security_enabled', DB_SECURITY_ENABLED) && FHtml::isTableExisted('auth_permission');
    }

    /**
     * @param string $moduleKey
     * @return bool
     */
    public static function isDynamicQueryEnabled($moduleKey = '')
    {
        return self::settingDynamicQueryEnabled();
    }

    // Hàm này để tương tác với URL, nó lấy ra đường link url hiện tại
    // Trong frontend/components/Url đây cũng là class cho phép tương tác NHIỀU hơn với URL

    /**
     * @return bool
     */
    public static function settingDynamicQueryEnabled()
    {
        return false;
    }

    /**
     * @return bool
     */
    public static function isSystemAdminEnabled()
    {
        return self::settingSystemAdminEnabled();
    }

    /**
     * @return bool
     */
    public static function settingSystemAdminEnabled()
    {
        return false;
    }

    /**
     * @param string $moduleKey
     * @return bool
     */
    public static function isDynamicObjectEnabled($moduleKey = '')
    {
        return self::settingDynamicObjectEnabled();
    }

    /**
     * @return bool
     */
    public static function settingDynamicObjectEnabled()
    {
        return false;
    }

    /**
     * @return string
     */
    public static function currentPageURL()
    {
        return self::getCurrentPageURL();
    }

    //HungHX: 20160801

    /**
     * @return string
     */
    public static function getCurrentPageURL()
    {
        $pageURL = 'http';

        if (!empty($_SERVER['HTTPS'])) {
            if ($_SERVER['HTTPS'] == 'on') {
                $pageURL .= "s";
            }
        }

        $pageURL .= "://";

        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }

        return $pageURL;
    }

    /**
     * @return string
     */
    public static function getCurrentDomainExtension()
    {
        $domain = $_SERVER["SERVER_NAME"];
        if (is_ipaddress($domain))
            return '';

        $key = explode('.', $domain);
        if (count($key) > 1)
            $subdomain = $key[count($key) - 1];
        else
            $subdomain = '';

        return $subdomain;
    }

    /**
     * @param $domain
     * @return bool
     */
    public static function is_ipaddress($domain)
    {
        return is_ipaddress($domain);
    }

    // merge Current Url with params

    /**
     * @return string
     */
    public static function currentSubDomain()
    {
        return self::getCurrentSubdomain();
    }

    /**
     * @return string
     */
    public static function getCurrentSubdomain()
    {
        $domain = $_SERVER["SERVER_NAME"];
        if (self::is_ipaddress($domain))
            return '';

        $key = explode('.', $domain);
        if (count($key) > 2)
            $subdomain = $key[0];
        else
            $subdomain = '';

        return $subdomain;
    }

    /**
     * @return null|string
     */
    public static function getCurrentPageSize()
    {
        return FHtml::config(FHtml::SETTINGS_PAGE_SIZE, FHtml::DEFAULT_ITEMS_PER_PAGE, null, 'Data', FHtml::EDITOR_NUMERIC);
    }

    /**
     * @param array $params
     * @return mixed|string
     * @throws \yii\base\InvalidConfigException
     */
    public static function currentUrl($params = [])
    {
        $url = self::currentHost() . \Yii::$app->getRequest()->getUrl();

        $join = strpos($url, '?') === false ? '?' : '&';
        if (!empty($params)) {
            $url .= $join;
            foreach ($params as $key => $value) {
                if (strpos($url, $key) !== false) {
                    if (!StringHelper::endsWith($url, '&'))
                        $url = $url . '&';
                    $pos1 = strpos($url, "$key=");
                    $pos2 = strpos($url, '&', $pos1);
                    $substr = substr($url, $pos1, $pos2 - $pos1);
                    $url = str_replace($substr, "$key=$value", $url);
                } else {
                    $url .= "$key=$value&";
                }
            }
        }

        $url = rtrim($url, '&');
        return $url;
    }

    /**
     * @return string
     */
    public static function currentQueryString()
    {
        return \Yii::$app->getRequest()->getQueryString(); // will return var=val,
    }

    /**
     * @return Application|null
     */
    public static function currentConfig()
    {
        return self::currentApplication();
    }

    /**
     * @param bool $isCached
     * @return Application|null
     */
    public static function currentApplication($isCached = true)
    {
        $id = FHtml::currentApplicationId();
        $item = self::getCachedData('ApplicationObject');

        if (isset($item) && $isCached) {
            if ($id == $item->code)
                return $item;
        }

        $item = self::getApplication($id);

        if (isset($item)) {
            self::saveCachedData($item, 'ApplicationObject');
        }

        return $item;
    }

    /**
     * @param $key
     * @param string $table
     * @param string $column
     * @return null
     */
    public static function getCachedData($key, $table = '', $column = '')
    {
        $key = self::getCachedKey($key, $table, $column);
        $cache = self::Cache();

        if (isset($cache) && $cache->exists($key)) {
            return $cache->get($key);
        } else
            return null;
    }

    /**
     * @param $key
     * @param string $table
     * @param string $column
     * @param string $id
     * @return string
     */
    public static function getCachedKey($key, $table = '', $column = '', $id = '')
    {
        $application_id = FHtml::currentApplicationId();
        $prefix = "application[$application_id]";

        if (StringHelper::startsWith($key, $prefix))
            $prefix = '';

        if (!empty($key) && $key !== $table && $key !== '@' . $table || empty($table))
            return $prefix . $key;

        if (empty($column))
            return $prefix . $table;
        else
            return $prefix . $table . '\\' . $column;
    }

    /**
     * @param string $key
     * @param null $value
     * @return mixed|null|\yii\caching\Cache
     */
    public static function Cache($key = '', $value = null)
    {
        $cache = \Yii::$app->cache;

        if (empty($key))
            return $cache;

        $key = self::getCachedKey($key);

        if (isset($cache) && $cache->exists($key)) {
            if (isset($value)) {
                $cache->set($key, $value);
                return $value;
            }
            return $cache->get($key);
        } else if (isset($cache) && isset($value) && !empty($key)) {
            $cache->set($key, $value);
            return $value;
        } else
            return $value;
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function getApplication($id)
    {
        if (!FHtml::isTableExisted('application')) {
            return null;
        }

        if (is_numeric($id)) {
            $item = Application::findOne($id, false);
        } else {

            $item = Application::findOne(['code' => $id], false);
        }

        if (!isset($item)) {
            $folder = FHtml::getRootFolder() . "/applications/$id";
            if (is_dir($folder)) {
                $item = new FApplication();
                $item->code = $id;
            }
        }

        return $item;
    }

    /**
     * @param $data
     * @param $key
     * @param string $table
     * @param string $column
     */
    public static function saveCachedData($data, $key, $table = '', $column = '')
    {

        $key = self::getCachedKey($key, $table, $column);
        if (self::Cache()->exists($key)) {
            self::Cache()->delete($key);
        }

        self::Cache()->set($key, $data);
    }

    //2017.5.4

    /**
     * @return null
     */
    public static function paypalAPIUsername()
    {
        return self::config(FHtml::SETTINGS_PAYPAL_API_USERNAME, PAYPAL_API_USERNAME, [], 'Paypal');
    }

    /**
     * @param $category
     * @param string $default_value
     * @param array $params
     * @param string $group
     * @param string $editor
     * @param string $lookup
     * @param bool $override_if_empty
     * @return null
     */
    public static function config($category, $default_value = '', $params = [], $group = 'Config', $editor = '', $lookup = '', $override_if_empty = false)
    {
        return self::getApplicationConfig($category, $default_value);
    }

    /**
     * @return null
     */
    public static function paypalAPIEmail()
    {
        return self::config(FHtml::SETTINGS_PAYPAL_API_EMAIL, PAYPAL_API_EMAIL, [], 'Paypal');
    }

    /**
     * @return null
     */
    public static function paypalAPIPassword()
    {
        return self::config(FHtml::SETTINGS_PAYPAL_API_PASSWORD, PAYPAL_API_PASSWORD, [], 'Paypal');
    }

    /**
     * @return null
     */
    public static function paypalAPISignature()
    {
        return self::config(FHtml::SETTINGS_PAYPAL_API_SIGNATURE, PAYPAL_API_SIGNATURE, [], 'Paypal');
    }

    /**
     * @return null
     */
    public static function paypalAPILive()
    {
        return self::config(FHtml::SETTINGS_PAYPAL_API_LIVE, PAYPAL_API_LIVE, [], 'Paypal', FHtml::EDITOR_BOOLEAN);
    }

    /**
     * @return \common\components\Mobile_Detect
     */
    public static function currentDevice()
    {
        if (!isset($detect))
            $detect = new \common\components\Mobile_Detect();

        return $detect;
    }

    /**
     * @return \yii\console\Controller|\yii\web\Controller
     */
    public static function currentControllerObject()
    {
        return Yii::$app->controller;
    }

    /**
     * @return mixed
     */
    public static function currentObjectType()
    {
        return str_replace('-', '_', self::currentController());
    }

    /**
     * @return string
     */
    public static function currentController()
    {
        if (!isset(Yii::$app->controller->id))
            return '';

        return Yii::$app->controller->id;
    }

    /**
     * @param string $default_value
     * @return null|string
     */
    public static function getCurrentMainColor($default_value = '')
    {
        return self::currentApplicationMainColor($default_value);
    }

    /**
     * @param string $default_value
     * @return null|string
     */
    public static function currentApplicationMainColor($default_value = '')
    {
        $result = $main_color = FHtml::getApplicationConfig('main_color');
        if (empty($result))
            return $default_value;
        return $result;
    }

    /**
     * @param string $default_value
     * @return null|string
     */
    public static function configWelcomeMessage()
    {
        return FHtml::config('WELCOME MESSAGE', "Hello, welcome to " . self::getAuthor());
    }

    /**
     * @return \yii\base\View|\yii\web\View
     */
    public static function currentView()
    {
        return Yii::$app->controller->getView();
    }

    /**
     * @param string $action
     * @return bool
     */
    public static function isViewAction($action = '')
    {
        if (empty($action))
            $action = FHtml::currentAction();
        return FHtml::isInArray($action, ['view', 'view-detail']);
    }

    /**
     * @return string
     */
    public static function currentAction()
    {
        if (isset(Yii::$app->controller))
            $action = Yii::$app->controller->action->id;
        else
            $action = '';

        return $action;
    }

    /**
     * @param string $action
     * @return bool
     */
    public static function isListAction($action = '')
    {
        if (empty($action))
            $action = FHtml::currentAction();
        return FHtml::isInArray($action, ['list', 'index']);
    }

    /**
     * @param string $action
     * @return bool
     */
    public static function isEditAction($action = '')
    {
        if (empty($action))
            $action = FHtml::currentAction();
        return FHtml::isInArray($action, ['create', 'update', 'edit', 'delete', 'update-detail']);
    }

    /**
     * @return null
     */
    public static function currentAdminModules()
    {
        return self::config(FHtml::SETTINGS_ADMIN_MODULES, ['App,System']);
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function currentDomain()
    {
        return \Yii::$app->getUrlManager()->getHostInfo();
    }

    /**
     * @return string
     */
    public static function currentDomainWithoutExtension()
    {
        $domain = $_SERVER["SERVER_NAME"];
        if (self::is_ipaddress($domain))
            return '';

        $key = explode('.', $domain);
        if (count($key) > 1)
            $domain = $key[count($key) - 2];
        else
            $domain = '';

        return $domain;
    }

    /**
     * @return string
     */
    public static function currentProtocol()
    {
        if (
            isset($_SERVER['HTTPS']) &&
            ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
            isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
            $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'
        ) {
            $protocol = 'https://';
        } else {
            $protocol = 'http://';
        }

        return $protocol;
    }

    /**
     * @return string
     */
    public static function currentRootUrl()
    {
        return self::currentHost();
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function currentHost()
    {
        return \Yii::$app->getUrlManager()->getHostInfo();
    }

    /**
     * @return string
     */
    public static function currentApplicationId()
    {
        $id = DEFAULT_APPLICATION_ID;
        self::setApplicationId($id);
        return $id;
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function setApplicationId($id)
    {
        return $id;
    }

    /**
     * @return null|string
     */
    public static function settingCompanyLogo()
    {
        return self::getCurrentLogo();
    }

    /**
     * @param string $default
     * @return null|string
     */
    public static function getCurrentLogo($default = 'logo.png')
    {
        return FHtml::settingApplication(FHtml::SETTINGS_COMPANY_LOGO, $default);
    }

    /**
     * @param $category
     * @param null $default_value
     * @param array $params
     * @param string $group
     * @param string $editor
     * @param string $lookup
     * @param bool $override_if_empty
     * @return null|string
     */
    public static function settingApplication($category, $default_value = null, $params = [], $group = 'Config', $editor = '', $lookup = '', $override_if_empty = false)
    {
        return FConfig::setting($category, $default_value, $params, $group);
    }

    /**
     * @param $table
     * @param $category
     * @param null $default_value
     */
    public static function settingModel($table, $category, $default_value = null)
    {
    }

    public static function settingAdminBackground()
    {
        return "background.png";
    }

    public static function getAdminLoginBackgroudUrl()
    {
        $files = ['background.png', 'background.jpg'];
        foreach ($files as $file) {
            $file1 = FHtml::getFilePath($file, 'www');
            if (is_file($file1))
                break;
        }

        return FHtml::getFileUrl($file, 'www');
    }

    /**
     * @return null|string
     */
    public static function settingCompanyCopyRight()
    {
        $result = FHtml::settingApplication(FHtml::SETTINGS_COMPANY_COPYRIGHT, "Powered by MOZASOLUTION (<a href='https://www.mozasolution.com'>www.mozasolution.com</a>)", [], 'Config');
        if (empty($result))
            $result = '@' . date('Y') . ' Copyright by ' . self::settingCompanyName();

        return $result;
    }

    /**
     * @return null|string
     */
    public static function settingCompanyName()
    {
        return FHtml::settingApplication(FHtml::SETTINGS_COMPANY_NAME, '', [], 'Config');
    }

    /**
     * @return string
     */
    public static function settingCompanyPowerby()
    {
        $result = "Copyright by " . "<b><a href='" . FHtml::settingCompanyWebsite(false) . "' target='_blank'>" . FHtml::settingCompanyName() . "</a></b>. ";
        $result .= FHtml::settingCompanyAddress() . '  ' . FHtml::settingCompanyEmail() . '  ' . FHtml::settingCompanyPhone();

        return $result;
    }
    /**
     * @return null|string
     */
    public static function settingCompanyTerms()
    {
        return FHtml::settingApplication(FHtml::SETTINGS_COMPANY_TERMS_OF_SERVICE, '', [], 'Config');
    }

    /**
     * @param bool $url
     * @return null|string
     */
    public static function settingCompanyFacebook($url = true)
    {
        $result = FHtml::settingApplication(FHtml::SETTINGS_COMPANY_FACEBOOK, '', [], 'Config');
        if ($url && !empty($result))
            $result = FHtml::getFacebookLink($result);

        return $result;
    }

    /**
     * @param bool $url
     * @return null|string
     */
    public static function settingCompanyYoutube($url = true)
    {
        $result = FHtml::settingApplication(FHtml::SETTINGS_COMPANY_YOUTUBE, '', [], 'Config');
        if ($url && !empty($result))
            $result = FHtml::getYoutubeLink($result);
        return $result;
    }

    /**
     * @param bool $url
     * @return null|string
     */
    public static function settingCompanyChat($url = true)
    {
        $result = FHtml::settingApplication(FHtml::SETTINGS_COMPANY_CHAT, '', [], 'Config');
        if (!empty($result) && $url)
            $result = "<a href='skype:$result?chat'>$result</a>";
        return $result;
    }

    /**
     * @return null|string
     */
    public static function settingCompanySlogan()
    {
        return FHtml::settingApplication(FHtml::SETTINGS_COMPANY_SLOGAN, '', [], 'Config');
    }

    /**
     * @return null|string
     */
    public static function settingCompanyPrivacy()
    {
        return FHtml::settingApplication(FHtml::SETTINGS_COMPANY_PRIVACY, '', [], 'Config');
    }

    /**
     * @param bool $url
     * @return null|string
     */
    public static function settingCompanyTwitter($url = true)
    {
        $result = FHtml::settingApplication(FHtml::SETTINGS_COMPANY_TWITTER, '', [], 'Config');
        if ($url && !empty($result))
            $result = FHtml::getTwitterLink($result);
        return $result;
    }

    /**
     * @param bool $url
     * @return null|string
     */
    public static function settingCompanyGoogle($url = true)
    {
        $result = FHtml::settingApplication(FHtml::SETTINGS_COMPANY_GOOGLE, '', [], 'Config');
        if ($url && !empty($result))
            $result = FHtml::getGoogleLink($result);
        return $result;
    }

    /**
     * @param bool $url
     * @return mixed|null|string
     */
    public static function settingCompanyPhone($url = true)
    {
        $result = FHtml::settingApplication(FHtml::SETTINGS_COMPANY_PHONE, '', [], 'Config');

        if (!empty($result) && $url) {
            $result = str_replace(' ', '', $result);
            if (!StringHelper::startsWith($result, '+'))
                $result = '+' . $result;

            $result = "<a href='callto:$result'>$result</a>";
        }
        return $result;
    }

    public static function settingCompanyHotline($url = true)
    {
        $result = FHtml::settingApplication(FHtml::SETTINGS_COMPANY_HOTLINE, '', [], 'Config');

        if (!empty($result) && $url) {
            $result = str_replace(' ', '', $result);
            if (!StringHelper::startsWith($result, '+'))
                $result = '+' . $result;

            $result = "<a href='callto:$result'>$result</a>";
        }
        return $result;
    }

    /**
     * @return null|string
     */
    public static function settingCompanyAddress()
    {
        return FHtml::settingApplication(FHtml::SETTINGS_COMPANY_ADDRESS, '', [], 'Config');
    }

    /**
     * @param bool $url
     * @return null|string
     */
    public static function settingCompanyEmail($url = true)
    {
        $result = FHtml::settingApplication(FHtml::SETTINGS_COMPANY_EMAIL, '', [], 'Config');
        if (!empty($result) && $url)
            $result = "<a href='mailto:$result'>$result</a>";
        return $result;
    }

    /**
     * @param bool $url
     * @return null|string
     */
    public static function settingCompanyWebsite($url = true)
    {
        $result = FHtml::settingApplication(FHtml::SETTINGS_COMPANY_DOMAIN, '', [], 'Config');
        if (!empty($result) && $url)
            $result = "<a href='$result' target='_blank'>$result</a>";
        return $result;
    }

    /**
     * @return null|string
     */
    public static function currentCompanyName()
    {
        return self::settingCompanyName();
    }

    /**
     * @param string $default
     * @return string
     */
    public static function getCurrentFavicon($default = 'favicon.png')
    {
        return $default;
    }

    /**
     * @param string $width
     * @param string $height
     * @param string $css
     * @param string $image_file
     * @param string $link_url
     * @return string
     */
    public static function showCurrentLogo2($width = '', $height = '50px', $css = 'logo-default', $image_file = 'logo_2.png', $link_url = '')
    {
        return self::showCurrentLogo($width, $height, $css, $image_file, $link_url);
    }

    /**
     * @param string $width
     * @param string $height
     * @param string $css
     * @param string $image_file
     * @param string $link_url
     * @return string
     */
    public static function showCurrentLogo($width = '', $height = '50px', $css = 'logo-default', $image_file = '', $link_url = '')
    {
        $image_folder = 'www';
        if (empty($image_file))
            $image_file = self::settingCompanyLogo();

        $result = FHtml::showImage($image_file, $image_folder, $width, $height, $css, FHtml::settingCompanyName() . ', ' . FHtml::settingCompanyDescription() . ', ' . FHtml::settingWebsiteKeyWords(), false, 'none');

        if (!empty($link_url) && !empty($result))
            $result = '<a href="' . $link_url . '">' . $result . '</a>';

        return $result;
    }

    /**
     * @return null|string
     */
    public static function settingCompanyDescription()
    {
        return FHtml::settingApplication(FHtml::SETTINGS_COMPANY_DESCRIPTION, '', [], 'Config');
    }

    /**
     * @param string $default_value
     * @return null|string
     */
    public static function settingWebsiteKeyWords($default_value = '')
    {
        return FConfig::settingWebsite('keywords', $default_value);
    }

    /**
     * @param $category
     * @param string $default_value
     * @return null|string
     */
    public static function settingWebsite($category, $default_value = '')
    {
        return FConfig::setting($category, $default_value, [], 'Website');
    }

    //Save Attributes, Files, and Related Objects

    /**
     * @param $model
     * @param string $module
     * @return string
     */
    public static function getBannerStyleCSS($model, $module = '')
    {
        $result = FHtml::getBannerImage($model, $module);
        if (!empty($result))
            return "background:url($result) !IMPORTANT;";
        return '';
    }

    /**
     * @param $model
     * @param string $module
     * @return mixed|string
     */
    public static function getBannerImage($model, $module = '')
    {
        $result = FHtml::getFieldValue($model, 'banner');
        if (!empty($result))
            return FHtml::getImageUrl($result, BaseInflector::camel2id($module));
        return '';
    }

    /**
     * @param $model
     */
    public static function refreshConfig($model)
    {
        if (!isset($model))
            return;

        FHtml::deleteCachedData('application\\' . $model->id);
        FHtml::deleteCachedData('application\\' . $model->code);
        FHtml::saveCachedData($model, 'application\\' . $model->code);
        FHtml::saveCachedData($model, 'application\\' . $model->id);
    }

    /**
     * @param $key
     * @param string $table
     * @param string $column
     */
    public static function deleteCachedData($key, $table = '', $column = '')
    {
        $key = self::getCachedKey($key, $table, $column);
        if (isset($cache) && self::Cache()->exists($key)) {
            self::Cache()->delete($key);
        }
    }

    /**
     *
     */
    public static function flushCache()
    {
        if (empty($key))
            self::Cache()->flush();
    }

    /**
     *
     */
    public static function deleteCachedSettings()
    {
        $cachedKey = 'Settings@' . FHtml::currentApplicationId();
        FHtml::deleteCachedData($cachedKey);
    }

    /**
     * @param null $user
     * @return array
     */
    public static function getUserApplications($user = null)
    {
        $ids1 = [];
        $ids1[] = FHtml::currentApplicationId();

        if (!isset($user) || empty($username)) {
            $user = FHtml::currentBackendUser();
            $username = FHtml::currentUsername();
            $application_id = FHtml::getFieldValue($user, 'application_id');
        } else if (is_object($user)) {
            $username = FHtml::getFieldValue($user, 'username');

            $application_id = FHtml::getFieldValue($user, 'application_id');
        } else {
            $user = '';
        }

        if (in_array($username, FSecurity::USER_NAME_SUPERADMIN))
            return [];

        if (!empty($application_id) && !in_array($username, FSecurity::USER_NAME_SUPERADMIN))
            $ids1[] = $application_id;

        return $ids1;
    }

    /**
     * @return string
     */
    public static function currentUsername()
    {
        $identity = self::currentUserIdentity();
        if (isset($identity))
            return self::currentUserIdentity()->username;
        else
            return '';
    }

    /**
     * @return Helper|mixed
     */
    public static function Helper()
    {
        $helper = \Yii::$app->helper;

        if (!isset($helper))
            return $helper;
        else
            return new Helper();
    }

    /**
     * @return mixed|string
     */
    public static function currentUserAvatar()
    {
        $user = self::currentBackendUser();
        $image = $user->identity->auth ? $user->identity->auth->image : $user->identity->image;
        return $image;
    }

    /**
     * @param string $category_id
     * @return BaseAPIObject|null|static
     */
    public static function currentCategory($category_id = '')
    {
        if (empty($category_id))
            $category_id = self::currentCategoryId();
        $category = null;
        if (!empty($category_id)) {
            $category = \backend\models\ObjectCategory::findOne($category_id);
        }

        return $category;
    }

    /**
     * @return null
     */
    public static function currentCategoryId()
    {
        $category_id = FHtml::getRequestParam(['category_id', 'categoryid']);
        return $category_id;
    }

    /**
     * @param $param
     * @param null $defaultvalue
     * @return null
     */
    public static function getRequestParam($param, $defaultvalue = null)
    {
        if (is_object($defaultvalue)) {
            $defaultvalue = FHtml::getFieldValue($defaultvalue, $param);
        }

        if (is_array($param)) {
            foreach ($param as $param1) {
                if (key_exists($param1, $_GET))
                    return $_GET[$param1];

                if (key_exists($param1, $_REQUEST))
                    return $_REQUEST[$param1];
            }
            return $defaultvalue;
        }

        if (key_exists($param, $_GET))
            return $_GET[$param];

        if (key_exists($param, $_REQUEST))
            $result = $_REQUEST[$param];
        else
            $result = null;

        return !self::isEmpty($result) ? $result : $defaultvalue;
    }

    public static function isEmpty($result)
    {
        return !isset($result) || (is_string($result) && trim($result) == '');
    }

    /**
     * @param $category
     * @param string $default_value
     * @param array $params
     * @param string $group
     * @param string $editor
     * @param string $lookup
     * @param bool $override_if_empty
     * @return null|string
     */
    public static function setting($category, $default_value = '', $params = [], $group = '', $editor = '', $lookup = '', $override_if_empty = false)
    {
        return FConfig::config($category, $default_value, $params, $group, $editor, $lookup, $override_if_empty);
    }

    /**
     * @return string
     */
    public static function currentZone()
    {
        $url = \Yii::$app->request->baseUrl;
        $url1 = FHtml::currentUrlPath();

        if (strpos($url, '/backend') !== false || strpos($url1, 'backend') !== false)
            return BACKEND;
        else
            return empty(DEFAULT_ZONE) ? FRONTEND : DEFAULT_ZONE;
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function currentUrlPath()
    {
        return Yii::$app->getRequest()->getPathInfo(); // will return forum/index,
    }

    /**
     * @return string
     */
    public static function currentModule()
    {
        if (!isset(Yii::$app->controller->module))
            return '';

        $id = Yii::$app->controller->module->id;
        if ($id == ADMIN_DASHBOARD_MODULE || $id == 'app-frontend' || $id == 'app-backend')
            return '';
        return $id;
    }

    /**
     * @param $category
     * @param string $default_value
     * @param array $params
     * @param string $group
     * @param string $editor
     * @param string $lookup
     * @param bool $override_if_empty
     * @return null|string
     */
    public static function settingPage($category, $default_value = '', $params = [], $group = '', $editor = '', $lookup = '', $override_if_empty = false)
    {
        $page_code = FConfig::currentPageCode();

        if (!FHtml::isDBSettingsEnabled() || FHtml::isInArray($category, FConfig::getExcludedSettings()))
            return $default_value;

        $category = $page_code . '?' . ucfirst($category);

        return FConfig::config($category, $default_value, $params, $group, $editor, $lookup, $override_if_empty);
    }

    /**
     * @param null $zone
     * @param null $module
     * @param null $controller
     * @param null $action
     * @return string
     */
    public static function currentPageCode($zone = null, $module = null, $controller = null, $action = null)
    {
        $zone = isset($zone) ? $zone : FHtml::currentZone();

        $module = isset($module) ? $module : BaseInflector::camel2words(FHtml::currentModule());
        $controller = isset($controller) ? $controller : FHtml::currentController();
        $action = isset($action) ? $action : FHtml::currentAction();

        if ($zone == FRONTEND)
            $zone = '';

        if (in_array($controller, ['site']))
            $controller = '';

        if (in_array($action, ['index']))
            $action = '';

        $result = "$zone/$module/$controller/$action";

        $result = trim(str_replace('//', '/', $result), "/");

        if (empty($result))
            $result = 'home';

        return strtolower($result);
    }

    /**
     * @return null
     */
    public static function isDBSettingsEnabled()
    {
        return FConfig::settingDBSettingsEnabled();
    }

    /**
     * @return null
     */
    public static function settingDBSettingsEnabled()
    {
        $result = FConfig::getApplicationConfig('db_settings_enabled', false);
        if ($result)
            return FHtml::isTableExisted('object_setting');
        return false;
    }

    /**
     * @return array
     */
    public static function getExcludedSettings()
    {
        return FHtml::SETTINGS_EXCLUDED;
    }

    /**
     * @param string $style
     * @return null|string
     */
    public static function settingPageStyleSheet($style = '')
    {
        $result = FConfig::settingPage('page_style', $style, [], 'Style', FHtml::EDITOR_TEXT);

        if (!isset($result) || empty($result))
            $result = $style;
        if (!empty($result) && !StringHelper::startsWith($result, '<style'))
            return "<style>\n$result\n</style>";
        else
            return $result;
    }

    /**
     * @param string $script
     * @return null|string
     */
    public static function settingPageScript($script = '')
    {
        $result = FConfig::settingPage('page_script', $script, [], 'Style', FHtml::EDITOR_TEXT);

        if (!isset($result) || empty($result))
            $result = $script;
        if (!empty($result) && !StringHelper::startsWith($result, '<script'))
            return "<script>\n$result\n</script>";
        else
            return $result;
    }

    /**
     * @param string $width
     * @return null|string
     */
    public static function settingPageWidth($width = '')
    {
        $result = FConfig::settingPage('page_width', $width, [], 'Style', FHtml::EDITOR_TEXT);

        if (isset($result) && !empty($result))
            return $result;

        return $width;
    }

    /**
     * @param string $title
     * @return null|string
     */
    public static function settingPageTitle($title = '')
    {
        $result = FConfig::settingPage('page_title', $title, [], 'Style', FHtml::EDITOR_TEXT);

        if (isset($result) && !empty($result))
            return $result;

        return $title;
    }

    /**
     * @param string $keywords
     * @return null|string
     */
    public static function settingPageKeywords($keywords = '')
    {
        $result = FConfig::settingPage('keywords', $keywords, [], 'Style', FHtml::EDITOR_TEXT);

        if (isset($result) && !empty($result))
            return $result;

        return $keywords;
    }

    /**
     * @param string $description
     * @return null|string
     */
    public static function settingPageDescription($description = '')
    {
        $result = FConfig::settingPage('description', $description, [], 'Style', FHtml::EDITOR_TEXT);

        if (isset($result) && !empty($result))
            return $result;

        return $description;
    }

    /**
     * @param string $description
     * @return mixed|null|string
     */
    public static function settingPageImage($description = '')
    {
        $result = FConfig::settingPage('image', $description, [], 'Style', FHtml::EDITOR_TEXT);

        if (isset($result) && !empty($result)) {
        } else
            $result = $description;

        if (!empty($result))
            return FHtml::getImageUrl($result, 'cms-page');
        else
            return FHtml::getCurrentLogo();
    }

    /**
     * @param string $style
     * @return null|string
     */
    public static function settingPageBodyCSS($style = '')
    {
        $result = FConfig::settingPage(FHtml::SETTINGS_BODY_CSS, $style, [], 'Style', FHtml::EDITOR_TEXT);

        if (isset($result) && !empty($result))
            return $result;

        return $style;
    }

    /**
     * @param string $style
     * @return null|string
     */
    public static function settingPageBodyStyle($style = '')
    {
        $result = FConfig::settingPage('body_style', $style, [], 'Style', FHtml::EDITOR_TEXT);

        if (isset($result) && !empty($result))
            return $result;

        return $style;
    }

    /**
     * @param $category
     * @param string $default_value
     * @param array $params
     * @param string $group
     * @param string $editor
     * @param string $lookup
     * @param bool $override_if_empty
     * @return null|string
     */
    public static function settingModule($category, $default_value = '', $params = [], $group = '', $editor = '', $lookup = '', $override_if_empty = false)
    {
        if (!FHtml::isDBSettingsEnabled() || FHtml::isInArray($category, FConfig::getExcludedSettings()))
            return $default_value;

        $zone = ucfirst(FHtml::currentZone());
        $module = BaseInflector::camel2words(FHtml::currentModule());
        if (empty($module))
            $module = 'System';

        if (empty($group))
            $group = $module;

        $category = $module . '/ ' . ucfirst($category);

        return FConfig::config($category, $default_value, $params, $group, $editor, $lookup, $override_if_empty);
    }

    /**
     * @param null $default_value
     * @return null|string
     */
    public static function settingApplicationDefaultModule($default_value = null)
    {
        return FConfig::settingApplication('menu_group', $default_value);
    }

    /**
     * @param string $default_value
     * @return null|string
     */
    public static function settingApplicationDatabase($default_value = 'db')
    {
        return FConfig::settingApplication('database', $default_value);
    }

    /**
     * @param null $default_value
     * @return null
     */
    public static function settingApplicationModules($default_value = null)
    {
        $result = FConfig::getApplicationConfig('modules', $default_value, true);
        if (!empty($result))
            return $result;

        $result = FSecurity::getApplicationModulesComboArray();

        return $result;
    }

    /**
     * @param null $default_value
     * @return null
     */
    public static function settingApplicationObjectTypes($default_value = null)
    {
        $result = FConfig::getApplicationConfig('object_type', $default_value, true);
        if (!empty($result))
            return $result;

        $result = FSecurity::getApplicationObjectTypes();

        return $result;
    }

    /**
     * @param string $default_value
     * @return null|string
     */
    public static function settingWebsiteHeaderView($default_value = '')
    {
        return FConfig::settingWebsite('header_view', $default_value);
    }

    /**
     * @param string $default_value
     * @return null|string
     */
    public static function settingWebsiteFonts($default_value = '')
    {
        return FConfig::settingWebsite('fonts', $default_value);
    }

    /**
     * @param string $default_value
     * @return null|string
     */
    public static function settingWebsiteWidth($default_value = '')
    {
        $page_width = FConfig::settingWebsite('page_width', $default_value);
        if (empty($page_width))
            return '';

        if (is_numeric($page_width) && $page_width < 100)
            $page_width = $page_width . '%';
        else if (is_numeric($page_width) && $page_width > 100)
            $page_width = $page_width . 'px';

        return $page_width;
    }

    /**
     * @param string $default_value
     * @return null|string
     */
    public static function settingWebsiteFooterView($default_value = '')
    {
        return FConfig::settingWebsite('footer_view', $default_value);
    }

    /**
     * @param string $default_value
     * @return null|string
     */
    public static function settingWebsitePageHeader($default_value = '')
    {
        return FConfig::settingWebsite('header_content', $default_value);
    }

    /**
     * @param string $default_value
     * @return null|string
     */
    public static function settingWebsiteBodyCSS($default_value = '')
    {
        return FConfig::settingWebsite('body_css', $default_value);
    }

    /**
     * @param string $default_value
     * @return null|string
     */
    public static function settingWebsiteBackground($default_value = '')
    {
        return FConfig::settingWebsite('background', $default_value);
    }

    /**
     * @param string $default_value
     * @return null|string
     */
    public static function settingWebsiteName($default_value = '')
    {
        return FConfig::settingWebsite('name', $default_value);
    }

    /**
     * @param string $default_value
     * @return null|string
     */
    public static function settingWebsiteDescription($default_value = '')
    {
        return FConfig::settingWebsite('description', $default_value);
    }

    /**
     * @param string $default_value
     * @return null|string
     */
    public static function settingWebsiteBodyStyle($default_value = '')
    {
        return FConfig::settingWebsite('body_style', $default_value);
    }

    /**
     * @param string $default_value
     * @return null|string
     */
    public static function settingWebsitePageFooter($default_value = '')
    {
        return FConfig::settingWebsite('footer_content', $default_value);
    }

    /**
     * @param string $default_value
     * @return null|string
     */
    public static function settingWebsitePageCSS($default_value = '')
    {
        return FConfig::settingWebsite('page_css', $default_value);
    }

    /**
     * @param string $default_value
     * @return null|string
     */
    public static function settingWebsitePageStyle($default_value = '')
    {
        $result = FConfig::settingWebsite('page_style', $default_value);
        if (!empty($result) && !StringHelper::startsWith($result, '<style'))
            return "<style>\n$result\n</style>";
        else
            return $result;
    }

    /**
     * @param $widget_id
     * @param string $default_value
     * @return null|string
     */
    public static function settingWidgetCSS($widget_id, $default_value = '')
    {
        return FConfig::settingWidget($widget_id, 'width_css', $default_value);
    }

    /**
     * @param $widget_id
     * @param $category
     * @param string $default_value
     * @return null|string
     */
    public static function settingWidget($widget_id, $category, $default_value = '')
    {
        $page_code = FHtml::currentPageCode();
        $model = CmsWidgets::findOne(['page_code' => $page_code, 'name' => $widget_id]);
        if (isset($model)) {
            if (FHtml::field_exists($model, $category)) {
                $result = $model->$category;
                if (!empty($result))
                    return $result;
                $model->$category = $default_value;
                $model->save();
            }
        }

        $result = FConfig::config($page_code . '_' . $widget_id . '_' . $category, $default_value, [], 'Widgets');
        if (!empty($result))
            return $result;
        return $default_value;
    }

    /**
     * @param $widget_id
     * @param string $default_value
     * @return null|string
     */
    public static function settingWidgetStyle($widget_id, $default_value = '')
    {
        return FConfig::settingWidget($widget_id, 'style', $default_value);
    }

    /**
     * @param $category
     * @param string $default_value
     * @return null|string
     */
    public static function settingConfig($category, $default_value = '')
    {
        return FConfig::settingApplication($category, $default_value, [], 'Config');
    }

    /**
     * @return bool
     */
    public static function settingDynamicGrid()
    {
        return FConfig::isDynamicFormEnabled() && FHtml::settingApplication('dynamic_grid', false, [], '', FConfig::EDITOR_BOOLEAN);
    }

    /**
     * @param string $moduleKey
     * @return bool
     */
    public static function isDynamicFormEnabled($moduleKey = '')
    {
        return self::settingDynamicFormEnabled();
    }

    /**
     * @return bool
     */
    public static function settingDynamicFormEnabled()
    {
        return false;
    }

    /**
     * @return bool
     */
    public static function settingDynamicForm()
    {
        return FConfig::isDynamicFormEnabled() && FHtml::settingApplication('dynamic_form', false, [], '', FConfig::EDITOR_BOOLEAN);
    }

    /**
     * @param string $default_value
     * @param string $action
     * @param array $params
     * @param string $group
     * @param string $editor
     * @param string $lookup
     * @return null|string
     */
    public static function settingPageView($default_value = '', $action = '', $params = [], $group = '', $editor = '', $lookup = '')
    {
        $action = empty($action) ? FHtml::currentAction() : $action;
        $category = BaseInflector::camel2words($action);


        if (FHtml::isInArray($action, FHtml::EXCLUDED_ACTIONS_AS_PAGEVIEW_SETTINGS)) {
            $action = FHtml::currentAction();

            if (Yii::$app->request->isAjax && $action == 'create') {
                if ($default_value == '_form')
                    $default_value = ['_form_add', '_form_small', '_form'];
                else if ($default_value == '_view')
                    $default_value = ['_view_small', '_view'];
            }
            $formType = FHtml::getRequestParam('form_type');

            if (!empty($formType))
                $default_value = "$formType/$default_value";

            return $default_value;
        }

        return FConfig::settingPage($category, $default_value, $params, $group, $editor, $lookup);
    }


    /**
     * @param string $default_value
     * @param array $params
     * @param string $group
     * @param string $editor
     * @param string $lookup
     * @return string
     */
    public static function settingPageObject($default_value = '', $params = [], $group = '', $editor = '', $lookup = '')
    {
        return $default_value;
        //return FConfig::settingPage('Page Object', $default_value, $params, $group, $editor, $lookup);
    }

    /**
     * @param $category
     * @param string $default_value
     * @param array $params
     * @param string $group
     * @param string $editor
     * @param string $lookup
     * @return null|string
     */
    public static function article($category, $default_value = '', $params = [], $group = '', $editor = '', $lookup = '')
    {
        return FConfig::config($category, $default_value, $params, $group, $editor, $lookup);
    }

    /**
     * @param string $content
     * @param null $model
     * @param string $field
     * @param string $object_type
     * @return mixed|string
     */
    public static function content($content = '', $model = null, $field = 'overview', $object_type = 'cms_article')
    {
        if (is_string($model)) // search $model by code
        {
            $data = FHtml::getModel($object_type, '', ['code' => $model], null, false);
            if (!isset($data))
                return FHtml::getFieldValue($data, $field, $content);
            else {
                $data = FHtml::createModel($object_type);
                FHtml::setFieldValue($data, $field, $content);
                return $content;
            }
        } else { // return

        }
    }

    /**
     * @param array $config
     * @param string $paramFile
     * @param bool $save
     */
    public static function saveApplicationParams($config = [], $paramFile = '')
    {
        $application_id = FHtml::currentApplicationId();
        if (empty($paramFile))
            $paramFile = "applications/$application_id/config/params.php";

        $params = FHtml::getApplicationParams(true, false, false, false);
        $params = array_merge($params, $config);
        self::saveParamsFile($params,  $paramFile, true);
    }

    public static function getSettingParams($key = '', $isCached = false)
    {
        $cachedKey = "getSettingParams";
        $params = [];
        if ($isCached) {
            $params = self::Cache($cachedKey);
            if (isset($params) && is_array($params))
                return $params;
            else
                $params = [];
        }

        $params = FFile::includeFile("common/config/params_setting.php");

        if ($isCached)
            self::Cache($cachedKey, $params);

        if (!empty($key)) {
            if (key_exists($key, $params))
                return $params[$key];
            else
                return [];
        }

        return $params;
    }

    public static function saveSettingParams($params = [])
    {
        $params1 = self::getSettingParams(false);
        $params1 = array_merge($params1, $params);
        self::saveParamsFile($params1, "common/config/params_setting.php", true);
        return $params;
    }

    public static function isRefreshCached()
    {
        return !empty(FHtml::getRequestParam('refresh'));
    }

    /**
     * @param bool $application_config
     * @param bool $global
     * @param bool $application_settings
     * @param bool $isCached
     * @return array|mixed|null
     */
    public static function getApplicationParams($application_config = true, $global = true, $application_settings = false, $isCached = true)
    {
        $application_id = FHtml::currentApplicationId();

        //if read content directly from file, very slow -> read via Cache
        $params = [];

        if ($global) {
            $params = array_merge($params, Yii::$app->params);
        }

        if ($application_config) {
            //$params = FFile::
            $file_name = FFile::getFullFileName("applications/$application_id/config/params.php");
            if (self::isRefreshCached())
                $isCached = true;

            if (!is_file($file_name)) {
                $folder = FHtml::getRootFolder() . "/applications/$application_id/config/";
                if (!is_dir($folder) && FApplication::isNotEmptyApplication()) {
                    FFile::createDir($folder);
                }
                $content = "
                   <?php
                        return [];
                ";
                if (FApplication::isNotEmptyApplication()) {
                    FFile::createFile($file_name, $content);
                }
            }

            if (!empty($application_id) && is_file($file_name)) {
                $params1 = self::includeFile($file_name, $isCached);

                if (isset($params1) && is_array($params1))
                    $params = array_merge($params, $params1);
            }
        }

        if ($application_settings && !empty($application_id)) {
            $config = FHtml::getApplicationHelper();
            if (isset($config)) {
                if (isset($config) && method_exists($config, 'getSettings')) {
                    $params = array_merge($params,  $config->getSettings());
                }

                if (isset($config) && defined($config::className() . '::SETTINGS')) {
                    $params = array_merge($params,  $config::SETTINGS);
                }
            }
        }

        return $params;
    }

    public static function syncApplicationParamsToDb()
    {
        if (!FHtml::isTableExisted('settings'))
            return;

        $params = FHtml::getApplicationParams(true, false, false, false);
        foreach ($params as $key => $value) {
            $model = Settings::findOne(['metaKey' => $key]);
            $params = FConfig::getSettingParams($key);
            if (!isset($model)) {
                $model = new Settings();
                $model->metaKey = $key;
                $model->is_active = 1;
                $model->application_id = FHtml::currentApplicationId();
            }
            $model->metaValue = $value;
            $model->group = FHtml::getFieldValue($params, 'group');
            $model->is_system = FHtml::getFieldValue($params, 'is_system', 1);
            $model->editor = FHtml::getFieldValue($params, 'editor');

            $model->save();
        }
    }

    public static function syncTranslationsToDb()
    {
    }

    public static function getApplicationTranslations($lang = '', $isCached = true)
    {
        $application_id = FHtml::currentApplicationId();

        if (empty($lang))
            $lang = FHtml::currentLang();

        $file = FHtml::getRootFolder() . "/applications/$application_id/messages/$lang/common.php";

        $content = FHtml::includeFile($file, $isCached);

        $content1 = FHtml::includeFile("common/messages/$lang/common.php", $isCached);
        $auto_save = FHtml::settingLanguagesAutoSaved();

        if (empty($content) && $auto_save)
            FHtml::saveApplicationTranslations([], $content1, $lang, $file);

        if (is_array($content1) && is_array($content))
            $content = array_merge($content1, $content);
        else if (!is_array($content) && is_array($content1))
            $content = $content1;

        if (!is_array($content)) {
            $arr = FHtml::decode($content);
            if (empty($arr))
                return [];
        } else {
            $arr = $content;
        }

        return $arr;
    }

    public static function getApplicationTranslationsGroups($lang = '')
    {
        $params = self::getApplicationTranslations($lang);
        $result = [];
        foreach ($params as $key => $value) {
            $arr = explode('.', $key);
            $key = (count($arr) > 1) ? $arr[0] : 'common';
            if (!key_exists($key, $result))
                $result = array_merge($result, [$key => $key]);
        }
        return $result;
    }

    public static function saveApplicationTranslations($config = [], $params = [], $lang = '', $paramFile = '')
    {
        $application_id = FHtml::currentApplicationId();
        if (empty($lang))
            $lang = FHtml::currentLang();

        if (empty($lang))
            return false;

        if (empty($paramFile))
            $paramFile = FHtml::getRootFolder() . "/applications/$application_id/messages/$lang/common.php";

        if (is_file($paramFile) && !is_writable($paramFile)) {
            return false;
            //            if (empty(self::Session('error'))) {
            //                return FHtml::addError("File" . $paramFile ." is not writeable. Please set permission for it");
            //            }
        }

        if (empty($params))
            $params = FHtml::getApplicationTranslations($lang, false);

        //echo $paramFile; FHtml::var_dump($config); FHtml::var_dump($params);
        self::addParamsFile($config, $params,  $paramFile);
    }

    public static function deleteApplicationTranslations($keys = [], $params = [], $lang = '', $paramFile = '')
    {
        $application_id = FHtml::currentApplicationId();
        if (empty($lang))
            $lang = FHtml::currentLang();

        if (empty($paramFile))
            $paramFile = FHtml::getRootFolder() . "/applications/$application_id/messages/$lang/common.php";

        return self::deleteParamsFile($keys, $params, $paramFile);
    }

    public static function addParamsFile($config = [], $params = [], $paramFile = '')
    {
        if (empty($paramFile))
            return false;

        if (is_array($params) && !empty($params))
            $params = array_merge($params, $config);
        else if (is_array($config))
            $params = $config;
        else
            return;

        self::saveParamsFile($params,  $paramFile, true);
    }

    public static function deleteParamsFile($keys = [], $params = [], $paramFile = '')
    {
        if (empty($paramFile))
            return false;

        if (!empty($params) && !empty($keys)) {
            foreach ($keys as $key) {
                if (key_exists($key, $params))
                    unset($params, $key);
            }
        } else {
            $params = [];
        }

        self::saveParamsFile($params,  $paramFile, true);
    }

    /**
     * @param $category
     * @param null $default_value
     * @param bool $checkHelperOnly
     * @return null
     */
    public static function getApplicationConfig($category, $default_value = null, $checkHelperOnly = true)
    {
        $category1 = strtolower($category);

        $method_name1 = str_replace(' ', '', $category1);
        $method_name2 = str_replace(' ', '_', $category1);
        $method_name3 = str_replace('_', '', $method_name1);

        $method_names = [$category, $method_name1, $method_name2, $method_name3];

        $params = FConfig::getApplicationParams(true, true);

        foreach ($method_names as $method_name) {
            if (key_exists($method_name, $params))
                return $params[$method_name];
        }

        return $default_value;
    }

    /**
     * @param string $application_id
     * @param string $namespace
     * @return string
     */
    public static function getApplicationNamespace($application_id = '', $namespace = '')
    {
        if (empty($application_id))
            $application_id = strtolower(FHtml::currentApplicationId());

        if (!empty($application_id))
            return 'applications\\' . $application_id;

        return $namespace;
    }

    /**
     * @param $table
     * @param string $namespace
     * @return string
     */
    public static function getModelNamespace($table, $namespace = 'backend\\models\\')
    {
        $table = FHtml::getTableName($table);

        $module = FModel::getModelModule(strtolower($table));
        if (!empty($module))
            return 'backend\\modules\\' . $module . '\\models\\';

        return $namespace;
    }

    /**
     * @param string $module
     * @return FApplication|object
     * @throws \yii\base\InvalidConfigException
     */
    public static function getApplicationHelper($module = '')
    {
        if (empty($module))
            $module = FHtml::currentApplicationId();

        $module = strtolower($module);
        $className = 'applications\\' . $module . '\\' . BaseInflector::camelize($module);
        if (class_exists($className)) {
            return Yii::createObject(['class' => $className::className()]);
        }
        return new FApplication();
    }

    /**
     * @return mixed|null|string|\yii\web\Session
     */
    public static function currentApplicationFolder()
    {
        $result = FConfig::getApplicationConfig('application_folder');
        if (!empty($result))
            return $result;

        return FHtml::currentApplicationId();
    }

    public static function currentFrontendTheme($default = '')
    {
        $result = FConfig::getApplicationConfig('frontend_theme', $default);
        if (!empty($result))
            return $result;

        return $default;
    }

    public static function currentFrontendBaseUrl($default = '')
    {
        $folder = Yii::$app->getView()->theme->baseUrl;

        return $folder;
    }

    public static function currentFrontendAssetsUrl($default = '')
    {
        return self::currentFrontendBaseUrl() . '/assets/';
    }

    /**
     * @return mixed|null|string|\yii\web\Session
     */
    public static function currentApplicationDatabase()
    {
        $result = FConfig::getApplicationConfig('database');

        if (!empty($result))
            return $result;

        return FHtml::currentApplicationId();
    }

    /**
     * @return mixed|null|string
     */
    public static function currentApplicationCode()
    {
        $result = FConfig::getApplicationConfig('application_id');
        if (!empty($result))
            return $result;

        return FHtml::currentApplicationId();
    }

    /**
     * @return array
     */
    public static function applicationLangsArray()
    {
        $arr = FHtml::decode(FConfig::getApplicationConfig('languages', FHtml::encode(FConfig::ARRAY_LANG), false));
        return $arr;
    }

    /**
     * @param $category
     * @param bool $message
     * @param array $params
     * @param null $language
     * @return bool|mixed|string
     */
    public static function t($category, $message = false, $params = [], $language = null)
    {
        if (is_object($message))
            return FHtml::getFieldValue($message, ['name', 'title']);

        if (empty($language))
            $language = FHtml::currentLang();

        $isLangEnabled =  FConfig::isLanguagesEnabled() || !empty($language);

        if (empty($message)) {
            if (in_array($category, ['common', 'button']))
                return '';
            $message = $category;
            $category = 'common';
        }

        $a = $message[0];
        if (in_array($a, ['<'])) {
            return $message;
        }
        if (in_array($a, ['@', '_', '::'])) {
            return substr($message, strlen($a));
        }

        if (strpos($message, '.') === false) {
            $arr = explode('.', $message);
            $category = count($arr) > 1 ? $arr[0] : $category;
            $message = count($arr) > 1 ? $arr[1] : $arr[0];
        }

        if (is_integer($category) || !is_string($category) || !$isLangEnabled || !is_string($message)) {
            return $message;
        }

        $params = self::getApplicationTranslations($language);
        if (!is_array($params))
            return $message;

        $message_origin = $message;
        if (is_string($message))
            $message = trim($message, " ()'.:,\t\n\r\0\x0B");

        $message = str_replace("  ", " ", $message);

        if (is_string($category))
            $category = str_replace('-', '_', BaseInflector::camel2id(trim($category)));

        if ($message == 'common' || empty($message))
            return FHtml::NULL_VALUE;

        foreach (FHtml::MULTILANG_TEXT_REMOVALS as $item) {
            $message = str_replace($item, '', $message); //tricky
        }

        $message1 =  str_replace(' ', '_', strtolower($message));

        $messages = [];
        $messages[] = $message1;
        $messages[] = $message;
        $messages[] = ucwords($message);

        foreach ($messages as $message) {
            if ($category != "common")
                $messages[] = "common." . $message;
            $messages[] = $category . '.' . $message;
            $messages[] = str_replace('-', '_', BaseInflector::camel2id($category)) . '.' . $message;
            $messages[] = str_replace(' ', '', BaseInflector::camel2words($category)) . '.' . $message;
        }
        $messages = array_unique($messages);


        $result = null;
        $count1 = count($params);
        foreach ($messages as $messagex) {
            if (key_exists($messagex, $params)) {
                if (empty($result)) // return first apperance
                    $result = $params[$messagex];
                else
                    unset($params[$messagex]); //remove other redundants
            }
        }

        $auto_save = FHtml::settingLanguagesAutoSaved();

        // if not empty ($result)
        if (!empty($result)) {
            if ($auto_save && count($params) != $count1)
                self::saveApplicationTranslations($params);
            return $result;
        }

        //does not existed
        if (!empty($message)) {
            if ($auto_save)
                self::saveApplicationTranslations([$category . '.' . $message1 => $message], $params);
            return $message;
        } else {
            return $category;
        }
    }

    /**
     * @return null
     */
    public static function defaultLang()
    {
        return FConfig::settingApplicationLang(DEFAULT_LANG);
    }

    /**
     * @return mixed|null|string
     */
    public static function currentLang()
    {
        if (FHtml::isLanguagesEnabled()) {

            $lang = FHtml::getRequestParam('lang'); // only return lang if pass in request params
            if (!empty($lang) && isset($_REQUEST['lang'])) {
                return $lang;
            }

            $lang = FHtml::getRequestParam(FHtml::LANGUAGES_PARAM);  // save current lang into session for global uses
            if (!empty($lang)) {
                FConfig::setCurrentLang($lang);
                return $lang;
            }

            $lang = FConfig::Session(FHtml::SETTINGS_LANG);
            if (!empty($lang)) {
                return $lang;
            }

            if (isset(Yii::$app->request->cookies[FHtml::LANGUAGES_PARAM]->value)) //If there is language defined in cookie, use it
            {
                $lang = Yii::$app->request->cookies[FHtml::LANGUAGES_PARAM]->value;
                FConfig::setCurrentLang($lang);
                return $lang;
            }
        }

        $lang = self::defaultLang();

        if (!empty($lang)) {
            FConfig::setCurrentLang($lang);
        }

        return $lang;
    }

    /**
     * @param $lang
     */
    public static function setCurrentLang($lang)
    {
        $session = FConfig::Session();
        Yii::$app->language = $lang;
        if (isset($session)) {
            $session->remove(FHtml::SETTINGS_LANG);
            $session->set(FHtml::SETTINGS_LANG, $lang);
        }
    }

    /**
     * @param string $key
     * @param null $value
     * @return mixed|null|\yii\web\Session
     */
    public static function Session($key = '', $value = null)
    {
        $session = \Yii::$app->session;

        if (empty($key))
            return $session;
        else if (isset($session) && $session->has($key)) {
            if (isset($value)) {
                $session->set($key, $value);
                return $value;
            }

            return $session->get($key);
        } else if (isset($session) && isset($value) && !empty($key)) {
            $session->set($key, $value);
            return $value;
        } else
            return $value;
    }

    /**
     *
     */
    public static function RefreshAllSystem()
    {
        FHtml::clearMessages();
        FHtml::clearLog();
        FHtml::Cache()->flush();
        FHtml::Session()->destroy();
    }

    public static function refreshPage()
    {
        $controller = self::currentControllerObject();
        if (isset($controller) && method_exists($controller, 'refreshPage'))
            $controller->refreshPage();
    }

    /**
     *
     */
    public static function RefreshCache()
    {
        FHtml::Cache()->flush();
    }

    /**
     *
     */
    public static function DestroySession($key = '')
    {
        if (empty($key))
            FHtml::Session()->destroy();
        else {
            $session = FHtml::Session();
            if ($session->has($key)) {
                if (method_exists($session, 'remove'))
                    $session->remove($key);
                else if (is_array($session))
                    unset($session[$key]);
            }
        }
    }

    /**
     * @param null $default_value
     * @return null
     */
    public static function settingApplicationLang($default_value = null)
    {
        return FConfig::getApplicationConfig('lang', $default_value, true);
    }

    /**
     * @return null|string
     */
    public static function settingDateFormat()
    {
        return FConfig::settingApplication(FHtml::SETTINGS_DATE_FORMAT, 'd.m.Y', [], 'Format');
    }

    /**
     * @return null|string
     */
    public static function settingDateTimeFormat()
    {
        return FConfig::settingApplication(FHtml::SETTINGS_DATETIME_FORMAT, 'd.m.Y hh:ii', [], 'Format');
    }

    /**
     * @return null|string
     */
    public static function settingTimeFormat()
    {
        return FConfig::settingApplication(FHtml::SETTINGS_TIME_FORMAT, 'hh:ii', [], 'Format');
    }

    /**
     * @return null|string
     */
    public static function settingDecimalSeparatorFormat()
    {
        return FConfig::settingApplication('Decimal Symbol', '.', [], 'Format');
    }

    /**
     * @return null|string
     */
    public static function settingThousandSeparatorFormat()
    {
        return FConfig::settingApplication('Thousand Grouping Symbol', ',', [], 'Format');
    }

    /**
     * @return null|string
     */
    public static function settingNullDisplayFormat()
    {
        return FConfig::settingApplication('Null Display (empty value)', '...', [], 'Format');
    }

    /**
     * @return null|string
     */
    public static function settingDigitsAfterDecimalFormat()
    {
        return FConfig::settingApplication('Digit After Decimal', 0, [], 'Format');
    }

    /**
     * @return null|string
     */
    public static function settingCurrency()
    {
        $value = FConfig::settingApplication(FHtml::SETTINGS_CURRENCY, 'USD', [], 'Format');
        return $value;
    }

    /**
     * @return null|string
     */
    public static function settingLocale()
    {
        return FConfig::settingApplication('Locale', 'en-US', [], 'Format');
    }

    /**
     * @return null|string
     */
    public static function settingMaxFileSize()
    {
        return FConfig::settingApplication('Max File Size', FHtml::getUploadMaxFileSize(), [], 'Format');
    }

    /**
     * @return null|string
     */
    public static function settingAcceptedFileType()
    {
        return FConfig::settingApplication('Accepted Files uploaded', 'image/*,video/*,audio/*,.docx,.txt,.xls,.pdf,.xlsx,.doc,.ppt', [], 'Format');
    }

    /**
     * @return bool
     */
    public static function settingObjectActionsLogEnabled()
    {
        return false;
    }

    /**
     * @return null
     */
    public static function settingWidgetsEnabled()
    {
        return FConfig::getApplicationConfig('widgets_enabled', null);
    }

    /**
     * @return bool
     */
    public static function settingAPICheckFootPrint()
    {
        return false;
    }

    /**
     * @return bool
     */
    public static function settingAPICheckToken()
    {
        return false;
    }

    /**
     * @return null
     */
    public static function settingAdminInlineEdit()
    {
        return FConfig::getApplicationConfig('admin_inline_edit', null);
    }

    /**
     * @return null
     */
    public static function settingShowPreviewColumn()
    {
        return FConfig::getApplicationConfig('show_preview_column', null);
    }

    /**
     * @return \League\Flysystem\Config|null|string
     */
    public static function settingThemeColor()
    {
        return FConfig::setting(FHtml::SETTINGS_ADMIN_MAIN_COLOR, FHtml::WIDGET_COLOR_DEFAULT, FHtml::ARRAY_ADMIN_THEME, 'Backend', FHtml::EDITOR_SELECT);
    }

    /**
     * @return \League\Flysystem\Config|null|string
     */
    public static function settingThemePortletStyle()
    {
        return FConfig::setting(FHtml::SETTINGS_PORTLET_STYLE, 'box', FHtml::ARRAY_PORTLET_STYLE, 'Backend', FHtml::EDITOR_SELECT);
    }

    /**
     * @return \League\Flysystem\Config|null|string
     */
    public static function settingThemeBorderStyle()
    {
        return FConfig::setting(FHtml::SETTINGS_BORDER_STYLE, 'box', FHtml::ARRAY_PORTLET_STYLE, 'Backend', FHtml::EDITOR_SELECT);
    }

    /**
     * Checks if database connections works
     *
     * @return boolean
     */
    public static function checkDbConnection($dbName = '')
    {
        try {
            // Check DB Connection
            if (is_string($dbName))
                return FHtml::currentDb($dbName);
            else
                return Yii::$app->db;
        } catch (Exception $e) {
            print_r($e->getMessage());
        }

        return FALSE;
    }

    /**
     * Checks if the application is already configured.
     */
    public static function isConfigured()
    {
        $val = FConfig::getParamValue(FConfig::APP_SECRET);
        if ($val != '') {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * @param $key
     * @param string $default_value
     * @param array $params
     * @param string $seperator
     * @return array|Array|mixed|string
     */
    public static function getParamValue($key, $default_value = '', $params = [], $seperator = '/')
    {
        if (is_string($key))
            $keys = explode($seperator, $key);
        else if (is_array($key))
            $keys = $key;
        else
            return $key;

        if (empty($params) || $params == 'params')
            $params = FConfig::getParamFileContent();
        else if ($params == 'config')
            $params = FConfig::getConfigFileContent();

        foreach ($keys as $i => $key) {
            if (key_exists($key, $params)) {
                $params = $params[$key];
            } else {
                if (count($keys) == 1 || $i == count($keys) - 1)
                    return $default_value;
            }
        }
        return $params;
    }

    /**
     * @param $key
     * @param string $default_value
     * @param string $seperator
     * @return array|Array|mixed|string
     */
    public static function getConfigValue($key, $default_value = '', $seperator = '/')
    {
        return self::getParamValue($key, $default_value, self::getConfigFileContent(), $seperator);
    }

    /**
     * Returns the dynamic configuration file as array
     *
     * @return Array Configuration file
     */
    public static function getConfigFileContent($file = '')
    {
        if (empty($file))
            $file = self::getConfigFile();

        $result = self::includeFile($file);

        if (empty($result))
            return [];
        else
            return $result;
    }

    /**
     * Sets configuration into the file
     *
     * @param array $config
     */
    public static function setConfigFileContent($config = [], $value = null)
    {
        if (is_string($config)) {
            $key = $config;
            $config = self::getConfigFileContent();
            if (is_array($config))
                $config[$key] = $value;
            self::setConfigFileContent($config);
        } else {
            $configFile = self::getConfigFile();
            $application_id = '';
            if (empty($configFile))
                $configFile = FHtml::getRootFolder() . "/applications/$application_id/config/main.php";
            $config['vendorPath'] = "{dirname}";
            self::saveConfigFile($configFile, $config, true);
        }

        return $config;
    }

    /**
     * @param $configFile
     * @param array $config
     */
    public static function saveConfigFile($configFile, $config = [], $header = true, $footer = false)
    {
        if ($header === true) {
            $header = "<?php \n return \n";
            $footer = "; ?>";
        }

        $content = is_string($header) ? $header : "";
        $content .= is_array($config) ? FHtml::strReplace(var_export($config, TRUE), []) : $config;
        $content .= is_string($footer) ? $footer : "";

        $content = str_replace("'{dirname}'", 'dirname(__DIR__) . "/vendor"', $content);

        $configFile = FHtml::getFullFileName($configFile);

        if (FApplication::isNotEmptyApplication()) {
            FFile::createFile($configFile, $content);
        }

        //refresh Session
        FFile::clearCache($configFile);
    }

    /**
     * @return mixed|string
     */
    public static function getConfigFile()
    {
        $paramFile = [];
        $config = [];

        $application_id = FHtml::currentApplicationId();
        $paramFile[] = FHtml::getRootFolder() . "/applications/$application_id/config/main.php";

        $paramFile[] = FHtml::getRootFolder() . "/config/main.php";

        foreach ($paramFile as $file) {
            if (is_file($file)) {
                return $file;
            }
        }
        return '';
    }

    public static function clearCacheFileContent($file = '')
    {
        if (empty($file))
            $file = FHtml::getRootFolder() . "/applications/" . FHtml::currentApplicationId() . "/messages/" . FHtml::currentLang() . "/common.php";
        if (function_exists('opcache_reset')) {
            opcache_invalidate($file);
        }
        FHtml::clearCache($file);
    }

    public static function clearCacheLanguageFileContent($file = '')
    {
        return self::clearCacheFileContent();
    }

    /**
     * @return mixed|string
     */
    public static function getParamFile()
    {
        $paramFile = [];
        $param = [];

        $application_id = FHtml::currentApplicationId();
        $file = FHtml::getRootFolder() . "/applications/$application_id/config/params.php";
        $paramFile[] = $file;

        foreach ($paramFile as $file) {
            if (is_file($file)) {
                return $file;
                break;
            }
        }
        return '';
    }

    public static function saveParamsFile($params, $paramFile, $header = "", $footer = "")
    {
        return self::saveConfigFile($paramFile, $params, $header, $footer);
    }

    /**
     * Returns the dynamic params file as array
     *
     * @return array|mixed Params file
     */
    public static function getParamFileContent($file = '')
    {
        if (empty($file))
            $file = self::getParamFile();

        if (!empty($file))
            return FHtml::includeFile($file);

        return [];
    }

    /**
     * Sets params into the file
     *
     * @param array $config
     */
    public static function setParamFileContent($config = [], $value = null)
    {
        if (is_string($config)) {
            $key = $config;
            $config = self::getParamFileContent();
            if (is_array($config)) {
                $config[$key] = $value;
            }
            self::setParamFileContent($config);
        } else {
            $paramFile = self::getParamFile();
            $application_id = FHtml::currentApplicationId();
            if (empty($paramFile))
                $paramFile = FHtml::getRootFolder() . "/applications/$application_id/config/params.php";

            self::saveConfigFile($paramFile, $config);
        }

        return $config;
    }

    /**
     * @return string
     */
    public static function getAuthor($default = DEFAULT_APPLICATION_NAME)
    {
        return FHtml::setting('author', $default);
    }

    /**
     * @return string
     */
    public static function getAuthorEmail($default = DEFAULT_APPLICATION_EMAIL)
    {
        return FHtml::setting('author_email', $default);
    }

    /**
     * @return string
     */
    public static function getAuthorWebsite($default = DEFAULT_APPLICATION_WEBSITE)
    {
        return FHtml::setting('author_website', $default);
    }

    /**
     * @param string $text
     * @param string $url
     * @return string
     */
    public static function getAuthorPowerByMessage($html = true, $text = '', $url = '')
    {
        if (empty($text)) {
            $text = self::getAuthor();
        }

        if (empty($url)) {
            $url = self::getAuthorWebsite();
        }


        $result = 'Powered by ' . Html::a(Html::encode($text), $url, ['target' => '_blank']);
        if ($html)
            $result = "<div style='background-color: lightgrey; font-size: 80%; text-align: center'>$result</div>";
        return $result;
    }

    /**
     * @return string
     */
    public static function getVersion()
    {
        return FConfig::frameworkVersion() . ' ' . FConfig::VERSION;
    }

    /**
     * @return \League\Flysystem\Config|null|string
     */
    public static function settingBackendMenuStyle($default = 'open')
    {
        return FConfig::setting('backend_menu_style', $default, ['open', 'closed'], 'Theme', FHtml::EDITOR_SELECT);
    }

    /**
     * @return string
     */
    public static function settingBackendBodyStyle($default = 'open')
    {
        $menu_style = self::settingBackendMenuStyle($default);

        if ($menu_style === 'closed') {

            return 'page-header-fixed page-sidebar-closed-hide-logo page-content-white page-sidebar-closed';
        }
        return 'page-header-fixed page-sidebar-closed-hide-logo page-content-white';
    }

    /**
     * @return string
     */
    public static function settingBackendSidebarStyle($default = 'open')
    {
        $menu_style = self::settingBackendMenuStyle($default);
        if ($menu_style === 'closed') {
            return 'page-sidebar-menu page-header-fixed page-sidebar-menu-closed';
        }

        return 'page-sidebar-menu  page-header-fixed';
    }

    /**
     * @return \League\Flysystem\Config|null|string
     */
    public static function settingBackendFontSize($default = '14px')
    {
        return FHtml::getApplicationConfig('backend_font_size', $default, ['9px', '10px', '11px', '12px', '13px', '14px'], 'Theme', FHtml::EDITOR_SELECT);
    }

    public static function settingBackendSocialLogin($default = 'google, facebook, git, twitter')
    {
        return FConfig::setting('backend_social_login', $default, ['google', 'facebook', 'twitter', 'linkedin', 'git'], 'Security', FHtml::EDITOR_SELECT);
    }

    public static function settingBackendLoginPosition($default = 'center')
    {
        return FConfig::setting('backend_login_position', $default, ['center', 'left', 'right'], 'Theme', FHtml::EDITOR_SELECT);
    }

    public static function settingSocialLogin($default = '')
    {
        return FConfig::setting('social_login', $default, ['google', 'facebook', 'twitter', 'linkedin', 'git'], 'Security', FHtml::EDITOR_SELECT);
    }

    public static function getCountrySettings($code = '')
    {
        $file = 'common/config/country_setting.php';

        $arr = self::includeFile($file, []);

        if (!empty($code) && key_exists($code, $arr))
            return $arr[$code];
        else
            return $arr;
    }

    public static function getCountrySetting($code, $param, $default_value = '')
    {
        $array = self::getCountrySettings($code);

        if (!empty($array) && key_exists($param, $array))
            return $array[$param];

        return $default_value;
    }

    public static function getCurrencySymbol($prefix = '', $locale = '')
    {
        if (!empty($prefix) && strlen($prefix) < 3)
            return $prefix;

        if (empty($prefix))
            $prefix = FHtml::settingCurrency();

        if (key_exists($prefix, FHtml::CURRENCY_SYMBOL))
            $symbol = FHtml::CURRENCY_SYMBOL[$prefix];
        else
            $symbol = $prefix;

        return $symbol;
    }

    public static function getCurrencySettings($code = '')
    {
        $file = 'common/config/currency_setting.php';

        $arr = self::includeFile($file, []);

        if (!empty($code) && key_exists($code, $arr))
            return $arr[$code];
        else
            return $arr;
    }

    public static function getCurrencySetting($currency, $param, $default_value = '')
    {
        $array = self::getCurrencySettings($currency);

        if (!empty($array) && key_exists($param, $array))
            return $array[$param];

        return $default_value;
    }

    public static function getCurrencyCode($currency)
    {
        return self::getCurrencySetting($currency, 'symbol');
    }

    public static function getCurrencyDecimalDigits($currency)
    {
        return self::getCurrencySetting($currency, 'decimal_digits', FHtml::settingDigitsAfterDecimalFormat());
    }

    public static function getCurrencyIsRound($currency)
    {
        return self::getCurrencySetting($currency, 'rounding', false);
    }

    public static function getCurrenciesCodeArray()
    {
        return ['USD' => '$', 'VND' => 'đ'];
    }

    public static function getConfigDsn($db_name = 'db')
    {
        if (empty($db_name))
            $db_name = FHtml::currentDbName();

        return FConfig::getConfigValue('components/' . $db_name . '/dsn');
    }

    public static function getApplications()
    {
        $folfer = FHtml::getRootFolder() . '/applications';
        $arr = FFile::listFolders($folfer, false);
        return $arr;
    }

    public static function getApplicationTables()
    {
        $sql = 'SHOW TABLES';
        $cmd = FHtml::currentDb()->createCommand($sql);
        $tables = $cmd->queryColumn();
        return $tables;
    }

    const SETTINGS_NODEJS_ENABLED = 'nodejs.enabled';
    public static function isNodeJsEnabled()
    {
        return self::config(self::SETTINGS_NODEJS_ENABLED, true) &&  file_exists(FHtml::getRootFolder() . '/node/node.php');
    }

    public static function currentBaseURL($position = FRONTEND)
    {
        if ($position == FRONTEND) {
            $base_url = str_replace(BACKEND . '/web', '', Url::base(true));
        } else {
            $base_url = \Yii::$app->urlManager->baseUrl;
        }

        return $base_url;
    }

    public static function getRootUrl()
    {
        return self::currentBaseURL();
    }
}
