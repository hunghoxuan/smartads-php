<?php

/*This is the customized model class for table "<?= $generator->generateTableName($tableName) ?>".
 */

namespace common\components;

use backend\models\Settings;
use backend\modules\cms\models\CmsPage;
use backend\modules\cms\models\CmsWidgets;
use Yii;
use yii\helpers\BaseInflector;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;

class FConfig extends FSecurity
{
    const VERSION = '2.0';

    const
        SETTINGS_PARAMS = ['form_width'],
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
        SETTINGS_CURRENCY = 'default_currency',
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
        SETTINGS_GOOGLE_API_KEY = 'GOOGLE_API_KEY',
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

    public static function frameworkVersion()
    {
        return 'business';
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
        return LANGUAGES_ENABLED && FConfig::setting('languages_enabled', null);
    }

    /**
     * @param null $table
     * @return bool|null
     */
    public static function isDBLanguagesEnabled($table = null)
    {
        $result = self::settingDBLanguaguesEnabled();

        if ($result && isset($table) && !empty($table)) {
            $table1 = '';
            if (is_object($table) || is_array($table)) {
                $table1 = FHtml::getTableName($table);
            }

            if (is_object($table) && isset($table) && method_exists($table, 'isDBLanguagesEnabled')) {
                $result = $table->isDBLanguagesEnabled();

                if (isset($result))
                    return $result;
            }
        }

        return $result;
    }


    /**
     * @return null|string
     */
    public static function getCurrentPageSize()
    {
        return FHtml::config(FHtml::SETTINGS_PAGE_SIZE, FHtml::DEFAULT_ITEMS_PER_PAGE, null, 'Data', FHtml::EDITOR_NUMERIC);
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
            $item = \backend\models\Application::findOne($id, false);
        } else {

            $item = \backend\models\Application::findOne(['code' => $id], false);
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
    public static function currentApplicationMainColor($default_value = '#337ab7')
    {
        return FConfig::settingWebsiteMainColor($default_value);
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

    public static function currentViewPath()
    {
        $view_file = static::currentViewFile();
        $file = basename($view_file);
        return str_replace(DS . $file, '', $view_file);
    }

    public static function currentControllerPath()
    {
        return Yii::$app->controller->getViewPath();
    }

    public static function currentViewFile()
    {
        $view = static::currentView();
        if (isset($view))
            return $view->getViewFile();

        return '';
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
        if (isset(Yii::$app->controller, Yii::$app->controller->action)) {
            $action = Yii::$app->controller->action->id;
        } else {
            $action = '';
        }

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

    public static function getCurrentLogoUrl()
    {
        $logo = self::getCurrentLogo();
        return FHtml::getImageUrl($logo, 'www');
    }

    /**
     * @param string $default
     * @return string
     */
    public static function getCurrentFavicon($default = 'favicon.png')
    {
        return $default;
    }

    public static function getCurrentFaviconUrl()
    {
        $files = ['favicon.png', 'logo.png'];
        $file = '';
        foreach ($files as $file) {
            $file1 = FHtml::getFilePath($file, 'www');
            if (is_file($file1))
                break;
        }

        return FHtml::getFileUrl($file, 'www');
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
        $a = FHtml::getFileUrl("background.jpg", 'www');
        return $a;
    }

    /**
     * @return null|string
     */
    public static function settingCompanyCopyRight()
    {
        $result = FHtml::setting(FHtml::SETTINGS_COMPANY_COPYRIGHT, '@' . date('Y') . ' Copyright by <b>' . self::settingCompanyName() . '</b>', [], 'Config');
        return $result;
    }

    /**
     * @return null|string
     */
    public static function settingCompanyName($default = null)
    {
        return FHtml::settingApplication(FHtml::SETTINGS_COMPANY_NAME, $default, [], 'Config');
    }

    /**
     * @return string
     */
    public static function settingCompanyPowerby($default = DEFAULT_APPLICATION_WEBSITE)
    {
        $result = static::setting('power_by_text', 'Powered by <b>' . $default . ' </b>');

        return $result;
    }

    public static function settingBottomText()
    {
        $result = static::config('bottom_running_text');
        $result .= '. ' . FHtml::t('common', 'Contact') . ': ' . FHtml::settingCompanyAddress() . '  ' . FHtml::settingCompanyEmail() . '  ' . FHtml::settingCompanyPhone();
        $result .= '. <div class="pull-right1">' . static::settingCompanyCopyRight() . '. ' . static::settingCompanyPowerby() . '</div>';
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

        return $result;
    }

    /**
     * @param bool $url
     * @return null|string
     */
    public static function settingCompanyYoutube($url = false)
    {
        $result = FHtml::settingApplication(FHtml::SETTINGS_COMPANY_YOUTUBE, '', [], 'Config');

        return $result;
    }

    /**
     * @param bool $url
     * @return null|string
     */
    public static function settingCompanyChat($url = false, $param = 'skype')
    {
        if (empty($param))
            $param = FHtml::SETTINGS_COMPANY_CHAT;
        $result = FHtml::settingApplication($param, '', [], 'Config');
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
    public static function settingCompanyTwitter($url = false)
    {
        $result = FHtml::settingApplication(FHtml::SETTINGS_COMPANY_TWITTER, '', [], 'Config');

        return $result;
    }

    /**
     * @param bool $url
     * @return null|string
     */
    public static function settingCompanyGoogle($url = false)
    {
        $result = FHtml::settingApplication(FHtml::SETTINGS_COMPANY_GOOGLE, '', [], 'Config');
        return $result;
    }

    /**
     * @param bool $url
     * @return mixed|null|string
     */
    public static function settingCompanyPhone($url = false)
    {
        $result = FHtml::settingApplication(FHtml::SETTINGS_COMPANY_PHONE, '', [], 'Config');
        return $result;
    }

    public static function settingCompanyWhatsapp($url = false)
    {
        $result = FHtml::settingApplication('whatsapp', '', [], 'Config');
        if (empty($result))
            $result = FHtml::settingApplication(FHtml::SETTINGS_COMPANY_PHONE, '', [], 'Config');
        return $result;
    }

    public static function settingCompanyHotline($url = false)
    {
        $result = FHtml::settingApplication(FHtml::SETTINGS_COMPANY_HOTLINE, '', [], 'Config');
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
    public static function settingCompanyEmail($url = false)
    {
        $result = FHtml::settingApplication(FHtml::SETTINGS_COMPANY_EMAIL, '', [], 'Config');
        return $result;
    }

    /**
     * @param bool $url
     * @return null|string
     */
    public static function settingCompanyWebsite($url = true)
    {
        $result = FHtml::settingApplication(FHtml::SETTINGS_COMPANY_DOMAIN, '', [], 'Config');
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

    public static function settingWebsiteScripts($default_value = '')
    {
        return FConfig::settingWebsite('website_scripts', $default_value);
    }

    public static function settingWebsiteStyleSheets($default_value = '')
    {
        return FConfig::settingWebsite('website_stylesheets', $default_value);
    }

    public static function settingWebsiteMainColor($default_value = '#337ab7')
    {
        return FConfig::settingWebsite('main_color', $default_value);
    }

    public static function settingBackendMainColor($default = BACKEND_MAIN_COLOR)
    {
        return FHtml::setting('backend_main_color', $default);
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
    public static function flushCache($key = '')
    {
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

    public static function getRequestParamRequired($param, $defaultvalue = '')
    {
        $result = static::getRequestParam($param);
        if (!empty($result))
            return $result;
        return $defaultvalue;
    }

    public static function getRequestParamInt($param, $defaultvalue = 0)
    {
        $result = static::getRequestParam($param);
        if (is_numeric($result))
            return (int)$result;
        return $defaultvalue;
    }

    public static function getRequestParamBoolean($param, $defaultvalue = false)
    {
        $result = static::getRequestParam($param);
        if (is_bool($result))
            return (bool)$result;
        return $defaultvalue;
    }

    /**
     * @param $param string | array
     * @param null $default_value
     * @param bool $exact
     * @param array $setting_keys
     * @return bool|int|mixed|string|null
     */
    public static function getRequestParam($param, $default_value = null, $exact = false, $setting_keys = FHtml::SETTINGS_PARAMS)
    {
        if (is_object($default_value)) {
            $default_value = FHtml::getFieldValue($default_value, $param);
        }

        $param_array = [];
        if (is_string($param)) {
            $param_array = [$param];
        } elseif (is_array($param)) {
            $param_array = $param;
        }

        $params = $_REQUEST; //$_REQUEST = merge($_GET, $_POST)

        foreach ($params as $key => $value) {
            unset($params[$key]);
            $params[strtolower($key)] = $value;
        }

        $result = $default_value;
        if (!empty($param_array)) {
            $found = 0;
            foreach ($param_array as $param_item) {
                if (isset($params[strtolower($param_item)])) {
                    $found = 1;
                    $result = $params[strtolower($param_item)];
                    break;
                }
            }
            if ($found == 0) {
                //param is string
                if (is_string($param) && in_array($param, $setting_keys)) {
                    $result = FConfig::setting($param);
                } else { //param is array
                    $result = $default_value;
                }
            }
        }

        if ($result === 'undefined' || $result === 'null') {
            $result = null;
        }
        if ($exact) {
            if (is_numeric($result)) {
                return (int)$result;
            }
        }
        return $result;
    }

    public static function getRequestParams()
    {
        return Yii::$app->request->queryParams;
    }

    public static function getSearchParams()
    {
        return array_merge(static::getRequestParams(), FHtml::getRequestPost());
    }

    public static function isEmpty($result)
    {
        return !isset($result) || (is_string($result) && trim($result) == '');
    }

    /**
     * @return string
     */
    public static function currentZone()
    {
        $url = \Yii::$app->request->baseUrl;
        $url1 = FHtml::currentUrlPath();

        if (strpos($url, '/backend') !== false || StringHelper::startsWith($url1, 'backend')) {
            return BACKEND;
        } else
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

        if (in_array($controller, ['site']))
            $controller = 'home';

        if ($zone == FRONTEND) {
            return $controller;
        }

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
    public static function settingPageImage($description = null)
    {
        $result = FConfig::settingPage('image', $description, [], 'Style', FHtml::EDITOR_TEXT);

        if (!empty($result))
            return FHtml::getImageUrl($result, 'cms-page');
        else
            return FHtml::getImageUrl('banner.jpg', 'www');
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
    public static function settingWebsiteWidth($default_value = '90%')
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

    public static function getApplicationConfigFile($application_id = '', $file_name = 'params.php')
    {
        if (empty($application_id))
            $application_id = FHtml::currentApplicationId();

        $file_name = str_replace(".php", "", $file_name);
        return FHtml::getRootFolder() . DS . "applications" . DS . $application_id . DS . "config" . DS . "$file_name.php";
    }

    public static function getApplicationConfigFileContent($application_id = '', $file_name = 'params.php')
    {
        $file = static::getApplicationConfigFile($application_id, $file_name);
        return static::includeFile($file);
    }

    public static function saveApplicationConfigFileContent($application_id = '', $file_name = 'params.php', $values)
    {
        $file = static::getApplicationConfigFile($application_id, $file_name);
        static::saveConfigFile($file, $values);
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
            $paramFile = static::getApplicationConfigFile($application_id);

        $params = FHtml::getApplicationParams(true, false, false, false);

        if (is_array($config)) {
            foreach ($config as $key => $value) {
                $key1 = str_replace('_', ' ', $key);
                $key2 = str_replace('_', '.', $key);

                $keys = [$key1, ucwords($key1), $key2, ucfirst($key2)];

                foreach ($keys as $key2) {
                    if (key_exists($key2, $params)) {
                        unset($params[$key2]);
                    }
                }
            }
        }
        $params = array_merge($params, $config);
        self::saveParamsFile($params, $paramFile, true);
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

    public static function getApplicationParams($application_config = true, $global = true, $application_settings = false, $isCached = true)
    {
        return Yii::$app->settings;
    }


    /**
     * @param bool $application_config
     * @param bool $global
     * @param bool $application_settings
     * @param bool $isCached
     * @return array|mixed|null
     */
    public static function findApplicationParams($application_config = true, $global = true, $application_settings = false, $isCached = true)
    {

        $application_id = FHtml::currentApplicationId();

        //if read content directly from file, very slow -> read via Cache
        $params = [];

        if ($global) {
            $params = array_merge($params, Yii::$app->params);
        }

        if ($application_settings && !empty($application_id)) {
            $config = FHtml::getApplicationHelper();
            if (isset($config)) {
                if (isset($config) && method_exists($config, 'getSettings')) {
                    $params = array_merge($params, $config->getSettings());
                }

                if (isset($config) && defined($config::className() . '::SETTINGS')) {
                    $params = array_merge($params, $config::SETTINGS);
                }
            }
        }

        $paramFile = static::getApplicationConfigFile($application_id);

        if ($application_config) {
            //$params = FFile::
            $file_name = FFile::getFullFileName($paramFile);
            $file_name_global = str_replace("params.php", "default_params.php", $paramFile);

            if (self::isRefreshCached())
                $isCached = true;

            if (!is_file($file_name)) {
                $folder = FHtml::getRootFolder() . "/applications/$application_id/config/";
                if (!is_dir($folder) && FApplication::isNotEmptyApplication()) {
                    FFile::createDir($folder);
                }
                $content = "<?php return [];";

                if (FApplication::isNotEmptyApplication()) {
                    FFile::createFile($file_name, $content);
                }
            }

            //try load
            if (!empty($application_id) && is_file($file_name_global)) {
                $params1 = self::includeFile($file_name_global, $isCached);

                if (isset($params1) && is_array($params1))
                    $params = array_merge($params, $params1);
            }

            if (!empty($application_id) && is_file($file_name)) {
                $params1 = self::includeFile($file_name, $isCached);

                if (isset($params1) && is_array($params1))
                    $params = array_merge($params, $params1);
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
        if (empty($lang) || $lang == FHtml::currentLang())
            return Yii::$app->translations;
        return static::findApplicationTranslations($lang);
    }

    public static function getApplicationTranslationsFile($application_id = '', $lang = '')
    {
        if (empty($application_id))
            $application_id = FHtml::currentApplicationId();
        if (empty($lang))
            $lang = FHtml::currentLang();

        $file = FHtml::getRootFolder() . "/applications/$application_id/messages/$lang/common.php";
        return $file;
    }

    public static function findApplicationTranslations($lang = '', $isCached = true)
    {
        $application_id = FHtml::currentApplicationId();

        if (empty($lang))
            $lang = FHtml::currentLang();

        $file = static::getApplicationTranslationsFile($application_id, $lang);

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
            $paramFile = static::getApplicationTranslationsFile($application_id, $lang);;

        if (is_file($paramFile) && !is_writable($paramFile)) {
            return false;
            //            if (empty(self::Session('error'))) {
            //                return FHtml::addError("File" . $paramFile ." is not writeable. Please set permission for it");
            //            }
        }

        if (empty($params))
            $params = FHtml::getApplicationTranslations($lang, false);

        //echo $paramFile; FHtml::var_dump($config); FHtml::var_dump($params);
        self::addParamsFile($config, $params, $paramFile);
    }

    public static function deleteApplicationTranslations($keys = [], $params = [], $lang = '', $paramFile = '')
    {
        $application_id = FHtml::currentApplicationId();
        if (empty($lang))
            $lang = FHtml::currentLang();

        if (empty($paramFile))
            $paramFile = static::getApplicationTranslationsFile($application_id, $lang);;

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

        self::saveParamsFile($params, $paramFile, true);
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

        self::saveParamsFile($params, $paramFile, true);
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


    public static function config($category, $default_value = '', $params = [], $group = 'Config', $editor = '', $lookup = '', $override_if_empty = false)
    {
        $arr = is_array($category) ? $category : [$category];
        foreach ($arr as $category1) {
            $result = self::getApplicationConfig($category1);
            if (isset($result))
                return $result;

            if (!empty($group)) {
                $result = self::getApplicationConfig("$group.$category1");
                if (isset($result))
                    return $result;
            }

            // Default values, no need to lookup at database
            if (FHtml::isDBSettingsEnabled() && !FHtml::isInArray($category1, self::getExcludedSettings())) {
                $result = self::getApplicationConfigFromDb($category1, $default_value, $params, $group, $editor, $lookup, $override_if_empty);
                if (isset($result))
                    return $result;
            }
        }

        return $default_value;
    }

    public static function setting($category, $default_value = '', $params = [], $group = 'Config', $editor = '', $lookup = '', $override_if_empty = false)
    {
        return self::config($category, $default_value, $params, $group, $editor, $lookup, $override_if_empty);
    }

    public static function getSettingValueByKey($key, $default_value = '', $params = [], $group = '', $editor = '', $lookup = '')
    {
        return self::config($key, $default_value, $params, $group, $editor, $lookup);
    }

    public static function getApplicationConfigFromDb($category, $default_value = '', $params = [], $group = 'Config', $editor = '', $lookup = '', $override_if_empty = false)
    {
        return $default_value;

        //        if (empty($editor))
        //            $editor = 'textarea';
        //
        //        // 3. If not, get from Config table
        //        $cachedKey = FHtml::currentApplicationId() . '::SETTINGS';
        //        $settings = FHtml::getCachedData($cachedKey);
        //
        //        if (!isset($settings)) {
        //            $settingsModel = Settings::findAll([]);
        //            $settings = [];
        //            if (isset($settingsModel) && !empty($settingsModel)) {
        //                foreach ($settingsModel as $model) {
        //                    $settings = ArrayHelper::merge($settings, [$model->metaKey => $model->metaValue]);
        //                }
        //            }
        //
        //            FHtml::saveCachedData($settings, $cachedKey);
        //        }
        //
        //        if (is_array($settings) && key_exists($category, $settings)) {
        //            return $settings[$category];
        //        } else {
        //            // Not yet existed in Settings DB, save it into next time
        //            $model = Settings::findOne(['metaKey' => $category]);
        //
        //            if (isset($model)) {
        //                if (!empty($model->metaValue))
        //                    return $model->metaValue;
        //                else {
        //                    if ($override_if_empty) {
        //                        $model->metaValue = $default_value;
        //                        $model->save();
        //                    }
        //                }
        //            } else {
        //                $model = new Settings();
        //                $model->metaKey = $category;
        //                $model->metaValue = (!is_array($default_value) || !is_object($default_value)) ? $default_value : '';
        //
        //                if (isset($default_value) && (is_bool($default_value) || $default_value === 1 || $default_value === 0 || $default_value == 'on')) {
        //                    $model->editor = FHtml::EDITOR_BOOLEAN;
        //                } else if (is_numeric($default_value)) {
        //                    $model->editor = FHtml::EDITOR_NUMERIC;
        //                }
        //
        //                if (!empty($params)) {
        //                    $model->editor = FHtml::EDITOR_SELECT;
        //                    $model->lookup = FHtml::encode($params);
        //                }
        //
        //                if (!empty($editor))
        //                    $model->editor = $editor;
        //
        //                if (!empty($lookup))
        //                    $model->lookup = $lookup;
        //
        //                if ($category == FHtml::SETTINGS_FIELD_LAYOUT) {
        //                    $model->editor = FHtml::EDITOR_SELECT;
        //                    $group = 'Theme';
        //                    $model->lookup = 'field_layout';
        //                }
        //
        //                if (empty($lookup) && $model->editor == FHtml::EDITOR_SELECT)
        //                    $model->lookup = str_replace(' ', '_', strtolower($category));
        //
        //                if (strpos($category, 'Format') !== false)
        //                    $group = 'Format';
        //
        //                if (empty($group))
        //                    $group = 'Others';
        //
        //                if (!empty($group))
        //                    FHtml::setFieldValue($model, 'group', $group);
        //
        //                $model->application_id = FHtml::currentApplicationCode();
        //                $model->is_system = 0;
        //                $model->save();
        //                FHtml::deleteCachedSettings();
        //            }
        //        }
        //
        //
        //        //To be done: check if category setting already existed in Configuration table, if not then return $default_value
        //        return $default_value;
    }

    public static function settingLanguagesAutoSaved()
    {
        return LANGUAGES_AUTO_SAVED && FConfig::setting('languages_auto_saved', false);
    }

    public static function settingLangShowInUrl()
    {
        $result = self::settingLanguagesEnabled() && FConfig::setting('show_lang_in_url', LANGUAGES_AUTO_SHOW_URL) && FHtml::currentLang() != self::defaultLang();

        return $result;
    }

    public static function getApplicationConfig($category, $default_value = null, $checkHelperOnly = true)
    {
        $category1 = strtolower($category);

        $method_name1 = str_replace(' ', '', $category1);
        $method_name2 = str_replace(' ', '_', $category1);
        $method_name3 = str_replace('_', '', $method_name1);

        $method_names = [$category, $method_name1, $method_name2, $method_name3];

        $params = self::getApplicationParams(true, true);

        foreach ($method_names as $method_name) {
            if (key_exists('_' . $method_name, $_REQUEST))
                return $_REQUEST['_' . $method_name];
            if (isset($params)) {
                if (key_exists($method_name, $params))
                    return $params[$method_name];
            }
        }

        if (!$checkHelperOnly && FHtml::isApplicationsEnabled()) {
            // Always loop when call: Application::findOne()
            $config = self::currentApplication();
            if (isset($config) && !empty($config)) {
                if (FModel::field_exists($config, $category)) {
                    $result = $config->$category;
                    if (isset($result) && !empty($result))
                        return $result;

                    return $default_value;
                }
            }
        }

        return $default_value;
    }

    public static function isApplicationsEnabled($model = null, $skip_checked = false)
    {

        $result = APPLICATIONS_ENABLED || !empty(DEFAULT_APPLICATION_ID);

        if (!$result || !isset($model)) // if false then return immediately
            return $result;

        if (isset($model) && is_object($model)) {
            $table = FHtml::getTableName($model);
        } else {
            $table = $model;
        }

        if (!empty($table) && !$skip_checked) {
            $result = $result && FHtml::field_exists($model, 'application_id', true) && !FHtml::isInArray($table, FHtml::EXCLUDED_TABLES_AS_APPLICATIONS);
        }

        return $result;
    }

    public static function isObjectActionsLogEnabled($model = null)
    {
        $result = self::settingObjectActionsLogEnabled();
        if (!$result)
            return $result;

        if (isset($model) && is_object($model)) {
            $model = FHtml::getTableName($model);
        }

        if (is_string($model) && !empty($model)) {
            $result = $result && !FHtml::isInArray($model, FHtml::EXCLUDED_TABLES_AS_OBJECT_CHANGES);
        }

        return $result;
    }

    public static function settingObjectActionsLogEnabled()
    {
        return LOGS_OBJECT_ACTIONS && FConfig::setting('logs_object_actions', true);
    }

    public static function settingUserActionsLogEnabled()
    {
        return LOGS_USER_ACTIONS && FConfig::setting('logs_user_actions', true);
    }

    public static function settingPage($category, $default_value = '', $params = [], $group = '', $editor = '', $lookup = '', $override_if_empty = false)
    {
        $page_code = self::currentPageCode();

        if (FHtml::isDBSettingsEnabled() && FHtml::isTableExisted('cms_page')) {
            $page_model = CmsPage::findOne(['code' => $page_code]); //FHtml::getModel('cms_page', '', ['code' => $code], '', true);
            $method_name = str_replace(' ', '_', strtolower($category));
            if (isset($page_model)) {
                if (FHtml::field_exists($page_model, $method_name)) {
                    $value = FHtml::getFieldValue($page_model, $method_name);
                    return $value;
                }
            } else {
                $page_model = new CmsPage();
                $page_model->code = $page_code;
                $page_model->application_id = FHtml::currentApplicationCode();
                $page_model->name = FHtml::currentController();
                $page_model->created_date = FHtml::Today();
                $page_model->is_active = 1;
                $page_model->created_user = FHtml::currentUserId();
                FHtml::setFieldValue($page_model, $method_name, $default_value);
                $page_model->save();
                if (FHtml::field_exists($page_model, $method_name)) {
                    return $default_value;
                }
            }
        }

        if (!FHtml::isDBSettingsEnabled() || FHtml::isInArray($category, self::getExcludedSettings()))
            return $default_value;

        $category = $page_code . '_' . strtolower($category);

        return self::config($category, $default_value, $params, $group, $editor, $lookup, $override_if_empty);
    }

    public static function currentApplicationId()
    {
        if (!APPLICATIONS_ENABLED) { //fixed Application ID, always return that value
            $id = DEFAULT_APPLICATION_ID;
            self::setApplicationId($id);

            return $id;
        }

        //Check Application Id by subdomain
        $id = FHtml::getCurrentSubdomain();

        if (!empty($id)) {
            if (in_array($id, array_column(FHtml::ARRAY_LANG, 0))) {
                self::setCurrentLang($id);
            } else if (!in_array($id, ['www', 'info', 'admin', 'demo'])) {
                self::setApplicationId($id);
                return $id;
            }
        }

        $domain = FHtml::currentDomainWithoutExtension();

        if (key_exists($domain, \applications\FSettings::APPLICATIONS_RULES)) {
            $id = \applications\FSettings::APPLICATIONS_RULES[$domain];
            self::setApplicationId($id);
            return $id;
        }

        $id = FHtml::getRequestParam('application_id');
        if (!empty($id)) {
            self::setApplicationId($id);
        }

        $id = self::Session("application_id");

        if (isset($id) && !empty($id))
            return $id;

        if (empty($id)) {
            $id = DEFAULT_APPLICATION_ID;
            self::setApplicationId($id);
        }

        return $id;
    }


    public static function setApplicationId($id)
    {
        if (empty($id))
            return;

        $application_id = self::Session("application_id");

        self::Session("application_id", $id);

        if ($id != $application_id && !empty($application_id) && FHtml::currentZone() == BACKEND) // change application
        {
            \Yii::$app->user->switchIdentity(null);
        }

        return $id;
    }


    public static function settingWidgetsEnabled()
    {
        return FConfig::setting('widgets_enabled', WIDGETS_ENABLED);
    }

    public static function settingCacheEnabled()
    {
        return FConfig::setting('cache_enabled', CACHE_ENABLED);
    }

    public static function settingDBSettingsEnabled()
    {
        return FConfig::getApplicationConfig('db_settings_enabled', DB_SETTINGS_ENABLED);
    }

    public static function settingDBObjectSettingsEnabled()
    {
        return FConfig::setting('db_object_settings_enabled', DB_SETTINGS_ENABLED);
    }

    public static function settingDBSecurityEnabled()
    {
        return FConfig::setting('db_security_enabled', DB_SECURITY_ENABLED);
    }

    public static function settingDBLanguaguesEnabled()
    {
        return FHtml::settingLanguagesEnabled() && FConfig::setting('db_languages_enabled', DB_LANGUAGES_ENABLED);
    }

    public static function settingDynamicObjectEnabled()
    {
        return FConfig::setting('dynamic_object_enabled', DYNAMIC_OBJECT_ENABLED);
    }

    public static function settingDynamicQueryEnabled()
    {
        return FConfig::setting('settings_query_enabled', SETTINGS_QUERY_ENABLED);
    }

    public static function settingDynamicFormEnabled()
    {
        return FConfig::setting('dynamic_form_enabled', DYNAMIC_FORM_ENABLED);
    }

    public static function settingDynamicFieldEnabled()
    {
        return FConfig::setting('dynamic_field_enabled', DYNAMIC_FIELD_ENABLED);
    }

    public static function settingSystemAdminEnabled()
    {
        return FConfig::setting('system_admin_enabled', SYSTEM_ADMIN_ENABLED);
    }

    public static function settingAPICheckFootPrint()
    {
        return FConfig::setting('api_check_footprint', API_CHECK_FOOTPRINT);
    }

    public static function settingAPICheckToken()
    {
        return API_CHECK_TOKEN && FConfig::setting('api_check_token', true);
    }

    public static function settingAdminInlineEdit($edit_type = '')
    {
        if (empty($edit_type))
            $edit_type = FHtml::getRequestParam('edit_type');

        if (in_array($edit_type, ['inline', 'input']))
            return true;

        return FConfig::setting('admin_inline_edit', ADMIN_INLINE_EDIT);
    }

    public static function settingShowPreviewColumn()
    {
        return FConfig::setting('show_preview_column', SHOW_PREVIEW_COLUMN);
    }

    public static function settingThemeColor()
    {
        return FHtml::setting(FHtml::SETTINGS_ADMIN_MAIN_COLOR, FHtml::WIDGET_COLOR_DEFAULT, FHtml::ARRAY_ADMIN_THEME, 'Theme', FHtml::EDITOR_SELECT);
    }

    public static function settingThemePortletStyle($default = DEFAULT_PORTLET_STYLE)
    {
        return FHtml::setting(FHtml::SETTINGS_PORTLET_STYLE, $default, FHtml::ARRAY_PORTLET_STYLE, 'Theme', FHtml::EDITOR_SELECT);
    }

    public static function settingEmailDomain()
    {
        $a = self::setting('email.domain');
        if (empty($a))
            $a = self::settingCompanyWebsite(false);
        if (!empty($a))
            return FHtml::strReplace($a, ['http://' => '', 'https://' => '', 'www.' => '']);
        return 'gmail.com';
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

    public static function currentApplicationUploadFolder()
    {
        $application_id = FFrontend::currentApplicationFolder();
        $root = FHtml::getRootFolder();
        return "$root/applications/$application_id/upload/";
    }

    public static function currentApplicationUploadUrl()
    {
        $application_id = FFrontend::currentApplicationFolder();
        $root = FHtml::getRootUrl();
        return "$root/applications/$application_id/upload/";
    }

    public static function currentTheme($zone = 'frontend', $default = '')
    {
        $result = FConfig::getApplicationConfig("$zone" . "_theme", $default);
        if (!empty($result))
            return $result;

        $root = FHtml::getRootFolder();
        $application_id = FHtml::currentApplicationId();

        if (is_dir("$root/applications/$application_id/$zone/layouts"))
            return $default;

        if ($zone == FRONTEND) {
            if (is_dir("$root/$zone/themes/$application_id"))
                return $application_id;
        } else if ($zone == BACKEND) {
            if (is_dir("$root/$zone/web/themes/$application_id"))
                return $application_id;
        }

        return $default;
    }

    public static function currentFrontendTheme($default = '')
    {
        return static::currentTheme(FRONTEND, $default);
    }

    public static function currentBackendTheme($default = 'metronic')
    {
        return static::currentTheme(BACKEND, $default);
    }

    public static function currentFrontendBaseUrl($theme = '')
    {
        //	    $application_id = FHtml::currentApplicationId();
        //	    $theme = FHtml::currentFrontendTheme();
        //	    $root_folder = FHtml::getRootFolder();

        //        if (is_dir($root_folder . "/frontend/themes/$theme/layouts"))
        //            return $root_folder . "/frontend/themes/$theme/layouts";
        //
        //	    if (is_dir($root_folder . "/applications/$application_id/frontend/layouts"))
        //	        return $root_folder . "/applications/$application_id/frontend/layouts";

        $baseUrl = FHtml::getBaseUrl();
        $root_folder = FHtml::getRootFolder();

        if (!empty($theme)) {
            if (is_dir("$root_folder/frontend/themes/$theme"))
                return "$baseUrl/frontend/themes/$theme";
        }
        $folder = Yii::$app->getView()->theme->baseUrl;
        return $folder;
    }

    public static function currentFrontendBaseFolder($theme = '')
    {
        $application_id = FHtml::currentApplicationId();
        if (empty($theme))
            $theme = FHtml::currentFrontendTheme();
        $root_folder = FHtml::getRootFolder();

        if (is_dir("$root_folder/frontend/themes/$theme"))
            return "$root_folder/frontend/themes/$theme";

        if (is_dir($root_folder . "/applications/$application_id/frontend/layouts"))
            return $root_folder . "/applications/$application_id/frontend";

        $folder = Yii::$app->getView()->theme->baseUrl;
        return $folder;
    }

    public static function currentFrontendAssetsUrl($theme = '')
    {
        $folder = self::currentFrontendBaseFolder($theme);
        $base_url = self::currentFrontendBaseUrl($theme);
        $application_id = FHtml::currentApplicationId();

        if (empty($theme))
            $theme = FHtml::currentFrontendTheme();
        $root_folder = FHtml::getRootFolder();

        if (!empty($theme)) {
            if (is_dir($root_folder . "/frontend/themes/$theme/assets"))
                return $base_url . "/assets/";

            if (is_dir($root_folder . "/applications/$application_id/frontend/assets"))
                return $base_url . "/applications/$application_id/frontend/assets/";
        }

        if (is_dir($folder . '/assets/'))
            return $base_url . '/assets/';

        return $base_url . '/';
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
    public static function applicationLangsArray($application_only = true)
    {
        $langs = FContent::getLanguagesArray();
        if (!$application_only)
            return $langs;
        $arr = FHtml::decode(FConfig::getApplicationConfig('languages', [], false));
        $result = [];
        if (!empty($arr)) {
            foreach ($arr as $code => $name) {
                if (is_numeric($code)) {
                    $code = $name;
                    $name = key_exists($code, $langs) ? $langs[$code] : $name;
                }
                $result = array_merge($result, [$code => $name]);
            }
        } else {
            $result = $langs;
        }
        return $result;
    }

    public static function getFrontendThemesArray()
    {
        $path = FHtml::getRootFolder() . "/frontend/themes";
        $a = FFile::listFolders($path, false);
        $a = array_keys($a);
        $result = [];
        $application_id = FHtml::currentApplicationId();
        $result = [$application_id];
        foreach ($a as $theme) {
            $result[] = $theme;
        }
        return $result;
    }

    /**
     * @return null
     */
    public static function defaultLang()
    {
        return FConfig::settingApplicationLang(DEFAULT_LANG);
    }

    public static function currentApplication()
    {
        return Yii::$app;
    }

    public static function currentApp()
    {
        return static::currentApplication();
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

            $lang = isset($_SESSION[FHtml::SETTINGS_LANG]) ? $_SESSION[FHtml::SETTINGS_LANG] : FConfig::Session(FHtml::SETTINGS_LANG);
            if (!empty($lang)) {
                return $lang;
            }

            //            $lang = Yii::$app->language;
            //            if (!empty($lang))
            //                return $lang;

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
        //setcookie(FHtml::LANGUAGES_PARAM, $lang);
        //Yii::$app->request->cookies[FHtml::LANGUAGES_PARAM] = $lang;
        $session = FConfig::Session();
        Yii::$app->language = $lang;
        $_SESSION[FHtml::SETTINGS_LANG] = $lang;
        if (isset($session)) {
            $session->remove(FHtml::SETTINGS_LANG);
            $session->set(FHtml::SETTINGS_LANG, $lang);
        }
    }

    public static function Cookies($key = '', $value = null)
    {
        $cookies = Yii::$app->request->cookies;
        if (empty($key))
            return $cookies;
        if (isset($cookies) && isset($cookies[$key]) && !isset($value))
            return $cookies[$key];
        return $value;
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
    public static function settingDateFormat($format = '')
    {
        if (empty($format))
            $format = DATE_FORMAT;
        return FConfig::settingApplication(FHtml::SETTINGS_DATE_FORMAT, $format, [], 'Format');
    }

    /**
     * @return null|string
     */
    public static function settingDateTimeFormat($format = '')
    {
        if (empty($format))
            $format = self::settingDateFormat() . ' ' . self::settingTimeFormat();
        return FConfig::settingApplication(FHtml::SETTINGS_DATETIME_FORMAT, $format, [], 'Format');
    }

    /**
     * @return null|string
     */
    public static function settingTimeFormat($format = '')
    {
        if (empty($format))
            $format = TIME_FORMAT;
        return FConfig::settingApplication(FHtml::SETTINGS_TIME_FORMAT, $format, [], 'Format');
    }

    /**
     * @return null|string
     */
    public static function settingDecimalSeparatorFormat()
    {
        return FConfig::settingApplication('decimal_point', '.', [], 'Format');
    }

    /**
     * @return null|string
     */
    public static function settingThousandSeparatorFormat()
    {
        return FConfig::settingApplication('thousands_sep', ',', [], 'Format');
    }

    /**
     * @return null|string
     */
    public static function settingDigitsAfterDecimalFormat()
    {
        return FConfig::settingApplication('display_decimals', 0, [], 'Format');
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
        return FConfig::settingApplication('Locale', locale_get_default(), [], 'Format');
    }

    /**
     * @return null|string
     */
    public static function settingMaxFileSize()
    {
        $setting = FConfig::settingApplication('upload_max_size', 0, [], 'Format');
        if (empty($setting))
            $setting = FHtml::getUploadMaxFileSize();
        return $setting;
    }

    /**
     * @return null|string
     */
    public static function settingAcceptedFileType()
    {
        return FConfig::settingApplication('upload_accepted_type', DEFAULT_UPLOAD_TYPE_TYPE, [], 'Format');
    }

    /**
     * @return \League\Flysystem\Config|null|string
     */
    public static function settingThemeBorderStyle($default = TABLE_BORDER_STYLE)
    {
        return FConfig::setting(FHtml::SETTINGS_BORDER_STYLE, $default, FHtml::ARRAY_PORTLET_STYLE, 'Backend', FHtml::EDITOR_SELECT);
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

        $result = [];
        foreach ($config as $key => $value) {
            if (is_object($value))
                $result = array_merge($result, [$key => FHtml::toArray($value)]);
            else
                $result = array_merge($result, [$key => $value]);
        }
        $config = $result;

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
            $file = static::getApplicationTranslationsFile();;
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
        $file = static::getApplicationConfigFile($application_id);
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
                $paramFile = static::getApplicationConfigFile($application_id);

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
        return FHtml::setting('backend_font_size', $default, ['9px', '10px', '11px', '12px', '13px', '14px'], 'Theme', FHtml::EDITOR_SELECT);
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
            $symbol = html_entity_decode(FHtml::CURRENCY_SYMBOL[$prefix]);
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

    public static function getConfigDsn($db_name = 'db')
    {
        $db = FHtml::currentDb();
        if (isset($db) && is_object($db) && method_exists($db, 'dsn')) {
            return $db->dsn;
        }
        if (empty($db_name))
            $db_name = FHtml::currentDbName();

        return FConfig::getConfigValue('components/' . $db_name . '/dsn');
    }

    public static function getApplications()
    {
        $folfer = FHtml::getRootFolder() . DS . 'applications';
        $arr = FFile::listFolders($folfer, false);
        return $arr;
    }

    public static function getApplicationTables($getNamesOnly = true, $dbName = '')
    {
        if (empty($dbName))
            $dbName = FModel::currentDatabaseName();

        if ($getNamesOnly) {
            $sql = 'SHOW TABLES';
            $cmd = FHtml::currentDb()->createCommand($sql);
            $tables = $cmd->queryColumn();
            return $tables;
        } else {
            $sql = @"SELECT TABLE_NAME AS 'name', table_rows AS 'rows', ROUND( ( data_length + index_length ) /1024, 2 ) AS 'size' 
            FROM information_schema.TABLES 
            WHERE information_schema.TABLES.table_schema = '$dbName'";
            $cmd = FHtml::currentDb()->createCommand($sql);

            $tables = $cmd->queryAll();

            $result = [];
            foreach ($tables as $table) {
                $result[$table['name']] = $table;
            }

            return $result;
        }
    }

    const SETTINGS_NODEJS_ENABLED = 'nodejs.enabled';

    public static function isNodeJsEnabled()
    {
        return self::config(self::SETTINGS_NODEJS_ENABLED, NODEJS_ENABLED) && file_exists(FHtml::getRootFolder() . '/node/node.php');
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

    public static function settingDynamicAPIEnabled()
    {
        return DYNAMIC_API_ENABLED && self::setting('dynamic_api_enabled', false);
    }

    public static function isDynamicObjectEnabled($moduleKey = '')
    {
        return self::settingDynamicObjectEnabled();
    }

    public static function isSystemAdminEnabled()
    {
        return self::settingSystemAdminEnabled();
    }

    public static function isDynamicQueryEnabled($moduleKey = '')
    {
        return self::settingDynamicQueryEnabled();
    }

    public static function setLocale($locale = '')
    {
        if (empty($locale))
            $locale = FHtml::settingLocale();
        if (!empty($locale)) {
            ini_set('intl.default_locale', $locale);
        }
        setlocale(LC_ALL, $locale);
    }

    public static function getCurrentLocale()
    {
        return locale_get_default();
    }

    //For API
    public static function settingAllowedIPAddress($default = API_ALLOWED_IPADDRESS)
    {
        $str = static::setting('api_ipaddress_allowed', $default);
        if (is_string($str))
            $str = FHtml::strReplace($str, ["|" => ',', ";" => ',']);

        return is_array($str) ? $str : explode(',', $str);
    }

    public static function settingBlockedIPAddress($default = '')
    {
        $str = static::setting('api_ipaddress_blocked', $default);
        if (is_string($str))
            $str = FHtml::strReplace($str, ["|" => ',', ";" => ',']);

        return is_array($str) ? $str : explode(',', $str);
    }

    public static function settingAllowedToken($default = API_ALLOWED_TOKEN)
    {
        $str = static::setting('api_token_allowed', $default);
        if (is_string($str))
            $str = FHtml::strReplace($str, ["|" => ',', ";" => ',']);
        return is_array($str) ? $str : explode(',', $str);
    }

    public static function settingBlockedToken($default = '')
    {
        $str = static::setting('api_token_blocked', $default);
        if (is_string($str))
            $str = FHtml::strReplace($str, ["|" => ',', ";" => ',']);

        return is_array($str) ? $str : explode(',', $str);
    }

    public static function isGlobalIPAddress($ipaddress = '')
    {
        if (empty($ipaddress))
            $ipaddress = FHtml::currentIPAddress();

        $arr = static::settingAllowedIPAddress();
        $arr2 = static::settingBlockedIPAddress();

        if (!empty($arr2) && !empty($ipaddress) && in_array($ipaddress, $arr2))
            return false;
        if (!empty($arr) && !empty($ipaddress) && in_array($ipaddress, $arr))
            return true;
        return false;
    }

    public static function isGlobalToken($token = '')
    {
        $arr = static::settingAllowedToken();
        $arr2 = static::settingBlockedToken();
        if (!empty($arr2) && !empty($token) && in_array($token, $arr2))
            return false;
        if (!empty($arr) && !empty($token) && in_array($token, $arr))
            return true;
        return false;
    }

    public static function getDefaultFontPath($default = 'verdana')
    {
        return FHtml::getFullFileName('/backend/web/fonts/' . FHtml::setting('default_font_file', $default) . '.ttf');
    }

    public static function getDefaultUserName($default = 'admin')
    {
        return static::setting('api_default_username', $default);
    }
}
