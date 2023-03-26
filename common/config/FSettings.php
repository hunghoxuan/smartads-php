<?php

namespace common\config;

use backend\models\AuthMenu;
use backend\modules\app\App;
use backend\modules\cms\Cms;
use backend\modules\ecommerce\Ecommerce;
use backend\modules\system\System;
use backend\modules\travel\Travel;
use common\components\FHtml;
use common\components\FSecurity;
use yii\base\Component;

// SETTINGS
defined('CACHE_ENABLED') or define('CACHE_ENABLED', false);
defined('LANGUAGES_ENABLED') or define('LANGUAGES_ENABLED', true); // Multiple Languages in Labels
defined('DB_LANGUAGES_ENABLED') or define('DB_LANGUAGES_ENABLED', true); // Multiple Languages In Database
defined('LANGUAGES_AUTO_SAVED') or define('LANGUAGES_AUTO_SAVED', false); // Auto generate language files
defined('LANGUAGES_AUTO_SHOW_URL') or define('LANGUAGES_AUTO_SHOW_URL', false); // Auto generate language files

defined('NODEJS_ENABLED') or define('NODEJS_ENABLED', true); //

defined('DEFAULT_LANG') or define('DEFAULT_LANG', 'vi');
defined('ADMIN_THEME') or define('ADMIN_THEME', 'metronic');
defined('SHOW_ERROR') or define('SHOW_ERROR', false);

defined('DEFAULT_ADMIN_USERNAME') or define('DEFAULT_ADMIN_USERNAME', 'admin');
defined('DEFAULT_ADMIN_USERID') or define('DEFAULT_ADMIN_USERID', 1);

defined('DEFAULT_WP_ADMIN_USERNAME') or define('DEFAULT_WP_ADMIN_USERNAME', 'admin');
defined('DEFAULT_WP_ADMIN_USERID') or define('DEFAULT_WP_ADMIN_USERID', 1);

defined('ROOT_FOLDER') or define('ROOT_FOLDER', '');
defined('DEFAULT_ZONE') or define('DEFAULT_ZONE', '');
defined('BACKEND_URL_FOLDER') or define('BACKEND_URL_FOLDER', 'backend/web/index.php');
//defined('') or define('BACKEND_URL_FOLDER', 'admin'); // loi redirect khi chay tren share host

// PAYPAL
defined('PAYPAL_API_USERNAME') or define('PAYPAL_API_USERNAME',"hung.hoxuan_api1.gmail.com");
defined('PAYPAL_API_EMAIL') or define('PAYPAL_API_EMAIL',"hung.hoxuan@gmail.com");

defined('PAYPAL_API_PASSWORD') or define('PAYPAL_API_PASSWORD',"UBVDUPTMCEGA6Y88");
defined('PAYPAL_API_SIGNATURE') or define('PAYPAL_API_SIGNATURE',"AFcWxV21C7fd0v3bYYYRCpSSRl31AlKUjYQEm9StBtsQZbeWCwCJcHxC");
defined('PAYPAL_API_LIVE') or define('PAYPAL_API_LIVE', true);

// FOOTPRINT/ HASH CHECKOUT
defined('SECRET_KEY') or define('SECRET_KEY', 'mozagroup2017∂'); // used in SHCODE
defined('SECRET_HASH_ALGORITHM') or define('SECRET_HASH_ALGORITHM', 'sha256');

defined('FOOTPRINT_TIME_LIMIT') or define('FOOTPRINT_TIME_LIMIT', 20);
defined('SERVER_TIME_ZONE') or define('SERVER_TIME_ZONE', '');
defined('SERVER_LOCALE') or define('SERVER_LOCALE', '');
defined('DEFAULT_FOLDER_PERMISSION') or define('DEFAULT_FOLDER_PERMISSION', 0777);

defined('DEFAULT_UPLOAD_TYPE_TYPE') or define('DEFAULT_UPLOAD_TYPE_TYPE', 'image/*,video/*,audio/*,.docx,.txt,.xls,.pdf,.xlsx,.doc,.ppt');

defined('API_CHECK_FOOTPRINT') or define('API_CHECK_FOOTPRINT', false); // in API Actions, check Time and FootPrint
defined('API_CHECK_TOKEN') or define('API_CHECK_TOKEN', false); // in API Actions, check Tokens
defined('DEFAULT_PASSWORD') or define('DEFAULT_PASSWORD', '123456');

// Advanced Configuration: DYNAMIC FORMS etc. Only used for Super Administrators. Set all to FALSE to gain max performance !
defined('SYSTEM_ADMIN_ENABLED') or define('SYSTEM_ADMIN_ENABLED', false); // Allow admin to edit objects, schema, views etc or not
defined('DYNAMIC_FORM_ENABLED') or define('DYNAMIC_FORM_ENABLED', false); // Build form dynamically

defined('DYNAMIC_FIELD_ENABLED') or define('DYNAMIC_FIELD_ENABLED', false); // Get/sets field dynamically
defined('DYNAMIC_API_ENABLED') or define('DYNAMIC_API_ENABLED', false); // allow admin to modify API Settings

defined('REQUIRED_INDEX_PHP') or define('REQUIRED_INDEX_PHP', false); // Auto add index.php in Url
defined('DB_SETTINGS_ENABLED') or define('DB_SETTINGS_ENABLED', true); // Get Setting from Database
defined('DB_OBJECT_SETTINGS_ENABLED') or define('DB_OBJECT_SETTINGS_ENABLED', false); // Get Setting from Database
defined('DB_SECURITY_ENABLED') or define('DB_SECURITY_ENABLED', false); // Get Security, Auths, Check ROles from Database

defined('WIDGETS_ENABLED') or define('WIDGETS_ENABLED', false); // Manage Widgets and Properties
defined('FRONTEND_MENU_FROM_MODULE') or define('FRONTEND_MENU_FROM_MODULE', false); // true, false or Module name or Application name (string)
defined('FRONTEND_MENU_FROM_DB') or define('FRONTEND_MENU_FROM_DB', false);
defined('DYNAMIC_OBJECT_ENABLED') or define('DYNAMIC_OBJECT_ENABLED', false); // Build object dynamically, Settings_Schema
defined('SETTINGS_QUERY_ENABLED') or define('SETTINGS_QUERY_ENABLED', false); // Enable Settings_Query table
defined('ADVANCED_GRID_ENALBED') or define('ADVANCED_GRID_ENALBED', false); // Enable Advanced Grid View
defined('IMAGES_AUTO_HANDLE') or define('IMAGES_AUTO_HANDLE', false);

defined('LOGS_OBJECT_ACTIONS') or define('LOGS_OBJECT_ACTIONS', false); // Enable Object_actions Logs Object Actions
defined('LOGS_USER_ACTIONS') or define('LOGS_USER_ACTIONS', false); // Enable Object_actions Logs Object Actions

//ADMIN USER INTERFACE
defined('SHOW_PREVIEW_COLUMN') or define('SHOW_PREVIEW_COLUMN', true); // Show Preview column (first column in Grid)
defined('ADMIN_INLINE_EDIT') or define('ADMIN_INLINE_EDIT', false); // Allow admin to inline edit in Grid columns
defined('ADMIN_GRID_SHOW_VIEWS') or define('ADMIN_GRID_SHOW_VIEWS', true); // Allow admin to inline edit in Grid columns

//API SECURITY
defined('API_ALLOWED_IPADDRESS') or define('API_ALLOWED_IPADDRESS', 'localhost,127.0.0.1'); // Is Authorized is true if access locally
defined('API_ALLOWED_TOKEN') or define('API_ALLOWED_TOKEN', ''); // Use this Token for any API


//////////////////////////////////////////////////////////////////////////////
defined('DEFAULT_APPLICATION_NAME') or define('DEFAULT_APPLICATION_NAME', 'MOZA SOLUTION');
defined('DEFAULT_APPLICATION_WEBSITE') or define('DEFAULT_APPLICATION_WEBSITE', 'https://mozasolution.com');
defined('DEFAULT_APPLICATION_EMAIL') or define('DEFAULT_APPLICATION_EMAIL', 'mozasolution@gmail.com');
defined('DEFAULT_APPLICATION_VERSION') or define('DEFAULT_APPLICATION_VERSION', '1.0');

defined('BACKEND_MAIN_COLOR') or define('BACKEND_MAIN_COLOR', '#337ab7');
defined('DEFAULT_BACKEND_THEME') or define('DEFAULT_BACKEND_THEME', 'light');

defined('BACKEND_HEADER_COLOR') or define('BACKEND_HEADER_COLOR', 'white');
defined('BACKEND_MENU_BACKGROUND') or define('BACKEND_MENU_BACKGROUND', '');
defined('BACKEND_MENU_ACTIVE_COLOR') or define('BACKEND_MENU_ACTIVE_COLOR', '');
defined('FORM_LABEL_BACKGROUND') or define('FORM_LABEL_BACKGROUND', '#fafafa');
defined('FORM_CONTROL_BORDER') or define('FORM_CONTROL_BORDER', 'round2');
defined('FORM_HEADER_COLOR') or define('FORM_HEADER_COLOR', 'darkblue');
defined('TABLE_BORDER_COLOR') or define('TABLE_BORDER_COLOR', 'lightgrey');
defined('DEFAULT_PORTLET_STYLE') or define('DEFAULT_PORTLET_STYLE', 'box');
defined('TABLE_BORDER_STYLE') or define('TABLE_BORDER_STYLE', 'box');

defined('TABLE_HEADER_COLOR') or define('TABLE_HEADER_COLOR', '');

defined('DEFAULT_THEME_STYLE') or define('DEFAULT_THEME_STYLE', 'bootstrap');

defined('TIME_FORMAT') or define('TIME_FORMAT', 'g:i A');
defined('DATE_FORMAT') or define('DATE_FORMAT', 'd.m.Y');
defined('DATETIME_FORMAT') or define('DATETIME_FORMAT', 'd.m.Y g:i A');
defined('DEFAULT_CURRENCY') or define('DEFAULT_CURRENCY', 'USD');



class FSettings extends \common\components\FSettings
{
    const MODULES = [
        '' => ['object_category', 'object_attributes', 'object_file', 'object_actions', 'object_relation', 'object_properties', 'object_setting', 'settings', 'settings_text', 'auth*', 'user'],
        'system' => ['object_*', 'application*', 'settings_schema', 'settings_*', 'tools_*', 'object_type', 'settings_menu'],
        'ecommerce' => ['product*', 'provider*', 'promotion*', 'ecommerce*'],
        'users' => ['user_*'],
        'app' => ['app_*'],
        'cms' => ['cms_*'],
        'travel' => ['travel_*'],
        'pm' => ['pm_*'],
        'music' => ['music_*', 'music'],
        'book' => ['book_*', 'book'],
        'event' => ['event', 'event_*'],
        'fashion' => ['fashion', 'fashion_*'],
        'company' => ['company*', 'kpi*'],
        'sc' => ['ModelMaster']
    ];

    const LABEL_COLORS = [
        'success' => ['started', 'processing', 'active'],
        'warning' => ['pending', 'late', 'hot'],
        'danger' => ['alert', 'fail', 'risk', 'top'],
        'primary' => ['done', 'closed']
    ];

    const ARRAY_LANG = [
        'en' => 'English', 'vi' => 'Vietnam', 'ru' => 'Russia', 'jp' => 'Japanese', 'kr' => 'Korea', 'es' => 'Spain', 'pt' => 'Potugal', 'fr' => 'French', 'de' => 'Germany', 'cn' => 'China'
    ];

    const LOOKUP = [
    ];

    public static function backendMenu($controller = '', $action = '') {
        return FSecurity::getBackendMenu($controller, $action);
    }
}