<?php

namespace backend\models;

use common\base\BaseModelObject;
use common\components\FConfig;
use common\components\FFile;
use common\models\BaseModel;
use Yii;
use common\components\FHtml;
use common\components\FModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**



 * This is the customized model class for table "settings".
 */
class Settings extends BaseModelObject
{
    //default values
    public $name = DEFAULT_APPLICATION_NAME;
    public $description = '';

    public $languages_enabled = false;
    public $lang = DEFAULT_LANG;
    public $languages = [DEFAULT_LANG];

    public $website = '';
    public $facebook = '';
    public $address = '';
    public $email = '';
    public $phone = '';
    public $logo = 'logo.png';
    public $backend_background = 'background.jpg';
    public $favicon = 'favicon.png';

    public $backend_social_login = false;
    public $copyright = '';
    public $power_by_text = 'Powered by MOZA SOLUTION (www.mozasolution.com)';
    public $welcome_text = '';

    public $Website_background;
    public $Buttons_Style = 'icons';
    public $border_style = 'box';
    public $table_border_color = TABLE_BORDER_COLOR;
    public $table_border_width = '1px';
    public $table_header_color = TABLE_HEADER_COLOR;

    public $Theme_Style = DEFAULT_THEME_STYLE;
    public $Theme_Color = 'light2';
    public $Controls_Alignment = 'horizontal';
    public $db_settings_enabled = '1';
    public $backend_login_position = 'center';
    public $keywords = '';
    public $skype = '';
    public $app_installed = '2018-03-16 02:06:02';
    public $app_version = 'business';
    public $purchase_license = NULL;
    public $purchase_order = '';
    public $purchase_site = '';
    public $client_name = '';
    public $frontend_framework = FRONTEND_FRAMEWORK;
    public $frontend_theme = '';
    public $client_email = '';
    public $chat_livezilla_id = '';
    public $chat_type = '';
    public $Website_page_width = '85%';
    public $app_secret = '';
    public $Website_main_color = '#4d6de6';
    public $cart_enabled = true;
    public $format_date = 'Y.m.d';
    public $format_datetime = 'Y.m.d H:i A';
    public $time_format = 'H:i A';

    public $form_label_color = FORM_LABEL_BACKGROUND;
    public $form_label_border = 'left';
    public $form_label_spacing = 0;
    public $form_width = 'layout';
    public $form_background = '';
    public $form_control_color = '';
    public $form_control_border = FORM_CONTROL_BORDER;
    public $form_control_height = '';
    public $form_buttons_style = 'fixed';

    public $page_size = 12;
    public $backend_font_size = '14px';
    public $backend_main_color = BACKEND_MAIN_COLOR;
    public $theme_color = DEFAULT_BACKEND_THEME;
    public $portlet_style = DEFAULT_PORTLET_STYLE;
    public $backend_menu_background_color = BACKEND_MENU_BACKGROUND;
    public $field_layout = FHtml::LAYOUT_TABLE;
    public $thumbnail_size = 50;
    public $fonts = 'Calibri';
    public $page_width = '90%';
    public $main_color = '#0000ff';
    public $default_currency = DEFAULT_CURRENCY;
    public $backend_header_color = BACKEND_HEADER_COLOR;
    public $bottom_running_text = '';
    public $timezone;
    public $table_strip_light_color = '#ffffff';
    public $table_strip_dark_color = '#fbfcfd';
    public $GOOGLE_API_KEY = 'AIzaSyArRF8sBsrI-nmQakXhQO9Jq6jFV-sVQzo';
    public $backend_footer_style = 'normal';
    public $backend_menu_active_color = BACKEND_MENU_ACTIVE_COLOR;

    public $application_id;
    public $show_error = SHOW_ERROR;
    public $admin_inline_edit = ADMIN_INLINE_EDIT;
    public $db_languages_enabled = false;
    public $languages_auto_saved = LANGUAGES_AUTO_SAVED;
    public $display_decimals = 3;
    public $decimal_point = '.';
    public $thousands_sep = ',';
    public $locale = SERVER_LOCALE;
    public $upload_accepted_type = DEFAULT_UPLOAD_TYPE_TYPE;
    public $upload_max_size = '';
    public $db_security_enabled = DB_SECURITY_ENABLED;
    public $dynamic_object_enabled = DYNAMIC_OBJECT_ENABLED;
    public $settings_query_enabled = SETTINGS_QUERY_ENABLED;
    public $dynamic_form_enabled = DYNAMIC_FORM_ENABLED;
    public $admin_grid_show_views = true; // = ADMIN_GRID_SHOW_VIEWS;

    public $api_ipaddress_allowed = API_ALLOWED_IPADDRESS;
    public $api_token_allowed = API_ALLOWED_TOKEN;

    const KEYS_MAPPING = [
        'defaultCurrency'  => 'default_currency'
    ];

    public static function getTableSchema($db = null)
    {
        return null;
    }

    public static function getLookupArray($column)
    {
    }

    public function getTableName()
    {
        return '';
    }

    public function attributes()
    {
        return array_keys(static::getApplicationParams());
    }

    public function getAttributes($names = [], $except = [])
    {
        return static::getApplicationParams();
    }

    public function fields()
    {
        return [];
    }

    public static function className()
    {
        return '';
    }

    public static function settingDynamicFieldEnabled()
    {
        return false;
    }

    public static function getInstance($data = [])
    {
        $model = parent::getInstance($data); // TODO: Change the autogenerated stub
        if (isset($model))
            $model->load();
        return $model;
    }

    public function load($data = [], $formName = '')
    {
        if (is_object($data))
            $model = $data;
        else
            $model = $this;

        $params = FHtml::getApplicationParams();
        foreach ($params as $param => $param_value) {
            if (!empty($param_value) || !FHtml::field_exists($model, $param))
                FHtml::setFieldValue($model, $param, $param_value);
        }

        return parent::load(); // TODO: Change the autogenerated stub
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        if (!empty($_FILES)) {
            FFile::saveUploadedFile($_FILES["Settings"], 'www', ['logo'  => $this->logo, 'backend_background' => $this->backend_background, 'favicon' => $this->favicon]);
        }

        if (!empty($_POST) && key_exists('Settings', $_POST)) {
            $result = $_POST['Settings'];
        } else {
            $result = [];
        }

        FHtml::saveApplicationParams(array_merge($result, []));
        return true;
    }

    public static function getApplicationParams($isCached = false)
    {
        return FHtml::getApplicationParams(true, false, false, $isCached);
    }

    protected static function getSettingKey($key)
    {
        if (key_exists($key, static::KEYS_MAPPING))
            return static::KEYS_MAPPING[$key];
        return $key;
    }

    public static function getSettingValueByKey($key, $default_value = '')
    {
        $key = self::getSettingKey($key);
        return FConfig::config($key, $default_value);
    }

    /**
     * @param array $keys
     * @return object | array
     * @throws \yii\base\InvalidConfigException
     */
    public static function getSettingValueByKeys($keys = [])
    {
        $data = [];
        if (is_array($keys)) {
            $i = 1;
            foreach ($keys as $key) {
                $data = array_merge($data, [$key => static::getSettingValueByKey($key)]);
            }
        }

        return $data;
    }

    /**
     * @param $key
     * @param $value
     * @return int
     * @throws \yii\db\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public static function setSettingValueByKey($key, $value)
    {
        if (empty($key))
            return false;
        $key = self::getSettingKey($key);
        FConfig::saveApplicationParams([$key => $value]);
    }

    /**
     * @param array $keys
     * @param array $values
     * @return int
     * @throws \yii\db\Exception
     */
    public static function setSettingValueByKeys($keys = [], $values = [])
    {
        $keyValues = array_combine($keys, $values);
        return FConfig::saveApplicationParams($keyValues);
    }

    /**
     * @param $key
     * @return int|string
     * @throws \yii\base\InvalidConfigException
     */
    public static function isExistKey($key)
    {
        $params = FHtml::getApplicationParams();
        return key_exists($key, $params);
    }

    /**
     * @param $data
     * @return array
     */
    public static function combineKeyValues($data)
    {
        if (empty($data) || !is_array($data))
            return [];
        $is_object_array = false;
        foreach ($data as $i => $value) {
            if (is_numeric($i) && is_object($value))
                $is_object_array = true;
            else
                $is_object_array = false;
            break;
        }

        if ($is_object_array) {
            $keys = array_column($data, 'metaKey');
            $values = array_column($data, 'metaValue');

            return array_combine($keys, $values);
        } else {
            return $data;
        }
    }

    public static function findAll($condition = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $display_fields = [], $asArray = false, $load_activeonly = true)
    {
        $params = FHtml::getApplicationParams();
        return $params;
    }
}
