<?php
include_once 'functions.php';

if ( ! function_exists('define_if_null')) {

    function define_if_null($a, $b)
    {
        if (defined($a))
            return;
        define($a, $b);
    }
}

if ( ! function_exists('app')) {

    function app()
    {
        return Yii::$app;
    }
}

if ( ! function_exists('session_get')) {
    function session_get($key, $default_value = null)
    {
        if (session_id() == "") {
            session_start();
        }

        $value = null;

        if (isset($_SESSION[$key])) {
            $value = $_SESSION[$key];

            return $value;
        }

        $value = $default_value;
        $_SESSION[$key] = $default_value;
        return $value;
    }
}

if ( ! function_exists('server')) {
    function server($key, $default_value = null)
    {

        $value = $_SERVER[$key];

        if (isset($value)) {
            return $value;
        }

        $value = $default_value;
        $_SERVER[$key] = $default_value;
        return $value;
    }
}

if ( ! function_exists('application_id')) {
    function application_id()
    {
        include_once(dirname(__FILE__) . '/../config/global.php');
        if (defined(APPLICATIONS_ENABLED) && APPLICATIONS_ENABLED == true) {
            $a = session_get('application_id', DEFAULT_APPLICATION_ID);
        }
        else
            $a = DEFAULT_APPLICATION_ID;

        return $a;
    }
}

if ( ! function_exists('current_lang')) {
    function current_lang()
    {
        return session_get('lang', DEFAULT_LANG);
    }
}

if ( ! function_exists('frontend_framework')) {
    function frontend_framework($at_backend = false)
    {
        $framework = application_setting('frontend_framework');
        $application_id = application_id();
        if (isset($framework))
            return $framework;

        $root = $at_backend ? dirname(__DIR__) . '/' : '';
        $url = $_SERVER['REQUEST_URI'];
        if (strpos($url, 'wordpress') !== false) {
            return 'wordpress';
        }
        // default is wordpress (no /frontend folder defined)
        if (!is_dir($root . "applications/$application_id/frontend") && is_dir($root .'wordpress/wp-content')) {
            return 'wordpress';
        }

        //return 'wordpress';
        return FRONTEND_FRAMEWORK;
    }
}

if ( ! function_exists('application_setting')) {
    function application_setting($key = '', $default_value = null)
    {
        $application_folder = application_id();
        $files = ["config/params.php", "applications/$application_folder/config/params.php"];
        $arr = [];

        foreach ($files as $file) {
            if (file_exists($file))
                $arr = array_merge($arr, require($file));
        }

        if (empty($key))
            return $arr;

        if (!empty($key) && key_exists($key, $arr))
            return $arr[$key];

        return $default_value;
    }
}

if ( ! function_exists('current_domain')) {
    function current_domain()
    {
        $domain = $_SERVER["SERVER_NAME"];
        return $domain;
    }
}

if ( ! function_exists('current_sub_domain')) {
    function current_sub_domain()
    {
        $domain = $_SERVER["SERVER_NAME"];
        if (is_ipaddress($domain))
            return '';

        $key = explode('.', $domain);
        if (count($key) > 2)
            $subdomain = $key[0];
        else
            $subdomain = '';

        return $subdomain;
    }
}

if (!function_exists('is_ipaddress'))
{
    function is_ipaddress($domain) {
        if (filter_var($domain, FILTER_VALIDATE_IP) !== false) {
            return true;
        } else {
            return false;
        }
    }
}

if ( ! function_exists('application_path')) {
    function application_path($path = '')
    {
        $application_id = application_id();
        $file = dirname(__DIR__) ."/applications/$application_id/bootstrap/app.php";

        if (is_file($file))
            return $path;
        else
            return str_replace('laravel', 'applications/' . $application_id . '', $path);
    }
}

if ( ! function_exists('file_save_contents')) {

    function file_save_contents($dir, $contents)
    {
        $parts = explode('/', $dir);
        $file = array_pop($parts);
        $dir = '';
        foreach ($parts as $part)
            if (!is_dir($dir .= "/$part")) mkdir($dir);
        file_put_contents("$dir/$file", $contents);
    }
}

if ( ! function_exists('current_user_id')) {

    function current_user_id($user_id = null)
    {
        return session_get('moza.user_id', $user_id);
    }
}

if ( ! function_exists('current_user_role')) {

    function current_user_role($role_id = null)
    {
        return session_get('moza.user_role', $role_id);
    }
}

if ( ! function_exists('current_user_token')) {

    function current_user_token($role_id = null)
    {
        return session_get('moza.user_token', $role_id);
    }
}

if ( ! function_exists('current_user_name')) {

    function current_user_name($role_id = null)
    {
        return session_get('moza.user_name', $role_id);
    }
}


if ( ! function_exists('current_user_email')) {

    function current_user_email($role_id = null)
    {
        return session_get('moza.user_email', $role_id);
    }
}

if ( ! function_exists('current_user_displayname')) {

    function current_user_displayname($role_id = null)
    {
        return session_get('moza.user_displayname', $role_id);
    }
}

if ( ! function_exists('is_role_admin')) {

    function is_role_admin()
    {
        if (is_internal_call())
            return true;

        $role = current_user_role();
        return $role == 30;
    }
}

if (! function_exists('wp_get_option')) {

    function wp_get_option( $option, $default = false) {
        $url = get_root_url();

        $application_id = application_id();

        $arr = application_setting();
        if (is_array($arr) && key_exists($option, $arr))
            return $arr[$option];

        if ($option == 'home')
        {
            return $url;
        } else if ($option == 'siteurl') {
            if (is_dir(dirname(__DIR__) . "/applications/$application_id/wordpress")) {

                return $url . "/applications/$application_id/wordpress";
            }

            if (is_dir(dirname(__DIR__) . '/apps/wordpress'))
                return $url . '/apps/wordpress';
            return $url . '/wordpress';
        } else if ($option == 'blogname') {
            return  function_exists('application_name') ? application_name() : (function_exists('application_id') ? application_id() : false);
        } else if ($option == 'blogdescription') {
            return  function_exists('application_description') ? application_description() : false;
        } else if ($option == 'admin_email') {
            return  function_exists('admin_email') ? admin_email() : false;
        }

        return false;

    }
}

if (! function_exists('get_root_url')) {
    function get_root_url( ) {
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        $url1 = $_SERVER[REQUEST_URI];
        $arr = explode('/', $url1);
        $arr = array_unique($arr);
        $arr2 = [];
        foreach ($arr as $i => $item) {
            if (!empty($item))
                $arr2[] = $item;
        }
        $arr = $arr2;
        if (count($arr) > 0)
            $url .= '/' . $arr[0];

        return $url;
    }
}

if (! function_exists('frontend_theme')) {
    function frontend_theme( ) {
       return application_setting('frontend_theme');
    }
}

if (! function_exists('application_name')) {
    function application_name( ) {
        return application_setting('name');
    }
}

if (! function_exists('application_description')) {
    function application_description( ) {
        return application_setting('description');
    }
}

if (! function_exists('admin_email')) {
    function admin_email( ) {
        return application_setting('email');
    }
}

if (! function_exists('get_referral_url')) {
    function get_referral_url( ) {
        return isset($_SERVER[HTTP_REFERER]) ? $_SERVER[HTTP_REFERER] : '';
    }
}

if (! function_exists('is_internal_call')) {
    function is_internal_call( ) {
        $root_url = get_root_url();
        $referral_url = get_referral_url();
        if (!empty($referral_url) && strpos($referral_url, $root_url) !== false)
            return true;
        return false;
    }
}
?>
