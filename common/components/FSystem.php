<?php
/**
 * @link https://github.com/creocoder/yii2-flysystem
 * @copyright Copyright (c) 2015 Alexander Kochetov
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace common\components;

use yii\helpers\Html;
use yii\helpers\StringHelper;

class FSystem extends FError
{
    public static function callFunc($call_arg, array $param_array)
    {
        return self::execFunction($call_arg, $param_array);
    }

    /**
     * Calls a method, function or closure. Parameters are supplied by their names instead of their position.
     * @param $call_arg like $callback in call_user_func_array()
     * Case1: {object, method}
     * Case2: {class, function}
     * Case3: "class::function"
     * Case4: "function"
     * Case5: closure
     * @param array $param_array A key-value array with the parameters
     * @return result of the method, function or closure
     * @throws \Exception when wrong arguments are given or required parameters are not given.
     */
    public static function execFunction($call_arg, array $param_array, $ignoreError = false)
    {
        $Func = null;
        $Method = null;
        $Object = null;
        $Class = null;

        // The cases. f means function name
        // Case1: f({object, method}, params)
        // Case2: f({class, function}, params)
        // Case5: f(closure, params)
        if(is_object($call_arg) && $call_arg instanceof \Closure)
        {
            $Method = $call_arg;
            return $Method($param_array);
        }
        else if(is_object($call_arg))
        {
            $Class = $call_arg;
            $Method = $param_array[0];
            unset($param_array[0]);
            return $Class->$Method($param_array);
        } // Case3: f("class::function", params)
        else if(is_string($call_arg) && strpos($call_arg, "::") !== FALSE)
        {

            list($Class, $Method) = explode("::", $call_arg);
        }
        // Case4: f("function", params)
        else if(is_string($call_arg) && strpos($call_arg, "->") !== FALSE)
        {

            list($Class, $Method) = explode("->", $call_arg);
            if (class_exists($Class)) {
                $properties = FHtml::getFieldValue($param_array, ['properties', 'objectProperties', 1]);
                if (!empty($properties))
                    $objectClass = array_merge($properties, ['class' => $Class::className()]);
                else
                    $objectClass = ['class' => $Class::className()];
                $Object = \Yii::createObject( $objectClass, FHtml::getFieldValue($param_array, ['constructors', 'objectParams', 0]));
                return $Object->$Method(FHtml::getFieldValue($param_array, ['methodParams', 'params', 2]));
            }
        }
        else if(is_string($call_arg) && strpos($call_arg, "::") === FALSE)
        {

            $Method = $call_arg;
        } else if(is_array($call_arg) && count($call_arg) == 2)
        {
            if(is_object($call_arg[0]))
            {
                $Object = $call_arg[0];
                $Class = get_class($Object);
            }
            else if(is_string($call_arg[0]))
            {
                $Class = $call_arg[0];
            }

            if(is_string($call_arg[1]))
            {
                $Method = $call_arg[1];
            } else if (is_array($call_arg[1])) {
                list($Class, $Method) = explode("->", $call_arg[0]);
                if (class_exists($Class)) {
                    $Object = \Yii::createObject(['class' => $Class::className()], $call_arg[1]);
                    return $Object->$Method($param_array);
                }
            }
        }
        else {
            if (!$ignoreError)
                throw new \Exception("Could not execute function " . FHtml::encode($call_arg));
        }

        if($Class) $Func = new \ReflectionMethod($Class, $Method);
        else $Func = new \ReflectionFunction($Method);
        $params = array();
        foreach($Func->getParameters() as $Param)
        {
            if($Param->isDefaultValueAvailable()) $params[$Param->getPosition()] = $Param->getDefaultValue();
            if(array_key_exists($Param->name, $param_array)) $params[$Param->getPosition()] = $param_array[$Param->name];
            if(!$Param->isOptional() && !isset($params[$Param->getPosition()])) die("No Defaultvalue available and no Value supplied!\r\n");
        }
        if($Func instanceof \ReflectionFunction) return $Func->invokeArgs($params);
        if($Func->isStatic()) return $Func->invokeArgs(null, $params);
        else return $Func->invokeArgs($Object, $params);
    }


    public static function execSystemCommand($cmd, $service_name = '', $runInBackground = true) {
        $cmd_arr = [];
        if (is_string($cmd))
            $cmd_arr = [$cmd];
        else if (is_array($cmd))
            $cmd_arr = $cmd;

        $service_arr = [];
        if (!empty($service_name)) {
            if (is_string($service_name))
                $service_arr = [$service_name];
            else if (is_array($service_name))
                $service_arr = $service_name;
        }

        $output = [];
        $retArr = []; $retVal = [];
        if (substr(php_uname(), 0, 7) == "Windows") {
            if (!empty($service_name)) {
                foreach ($service_arr as $service_name) {
                    exec("Taskkill /IM $service_name /F");
                }
            }

            foreach ($cmd_arr as $cmd) {
                if ($runInBackground) {
                    pclose(popen("start /B " . $cmd, "r"));
                } else {
                    exec($cmd, $retArr, $retVal);
                    $output = array_merge($output, $retArr);
                }
            }
        }
        else {
            foreach ($cmd_arr as $cmd) {
                if ($runInBackground) {
                    exec($cmd . " > /dev/null &", $retArr, $retVal);
                    //$retVal = shell_exec($cmd . " > /dev/null &");
                    $output = array_merge($output, $retArr);
                } else {
                    exec($cmd . " 2>&1", $retArr, $retVal);
                    //$retVal = shell_exec($cmd . " > /dev/null &");
                    $output = array_merge($output, $retArr);
                }
            }
        }

        return $output;
    }

    public static function killCommand($service_name) {
        $service_arr = [];
        if (!empty($service_name)) {
            if (is_string($service_name))
                $service_arr = [$service_name];
            else if (is_array($service_name))
                $service_arr = $service_name;
        }

        if (substr(php_uname(), 0, 7) == "Windows") {
            if (!empty($service_name)) {
                foreach ($service_arr as $service_name) {
                    exec("Taskkill /IM $service_name /F");
                }
            }
        }
        else {

        }
    }

    public static function saveRTMPStream($stream_url, $folder = '', $file = '') {
        $rootFolder = FHtml::getRootFolder();
        if (empty($folder))
            $folder = 'videos';

        if (empty($file))
            $file = str_replace(["rtmp", ":", "/", ".", "\\"], '', $stream_url) . date('Y-m-d') . '.mp4';

        FHtml::createDir("$rootFolder/$folder");

        $cmd = "$rootFolder/ffmpeg/ffmpeg.exe -i $stream_url -acodec copy -vcodec copy $rootFolder/$folder/$file";
        self::execSystemCommand($cmd, 'ffmpeg.exe');
    }

    public static function execRemoteUrl($url, $params = [], $options = [], $save_to = true)
    {
        $root = FHtml::getRootFolder();

        if (!empty($save_to) && !StringHelper::startsWith($save_to, $root)) {
            $save_to = "$root/$save_to";
        }

        if (is_array($url))
            return self::execRemoteUrlsAsync($url, $params, $options, $save_to);

        if (is_bool($options)) {
            $save_to = $options;
            $options = [];
        }
        $root_url = FHtml::currentRootUrl();
        if (!StringHelper::startsWith($url, $root_url) && !StringHelper::startsWith($url, 'http')) {
            $url = $root_url . FHtml::createUrl($url, $params);
        }

        if (!empty($save_to) && is_string($save_to)) { //download
            $fp = fopen($save_to, "w");
            flock($fp, LOCK_EX);
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_FILE, $fp);

            // extra options?
            if (!empty($options)) {
                curl_setopt_array($curl, $options);
            }

            $resp = curl_exec($curl);
            curl_close($curl);
            flock($fp, LOCK_UN);
            fclose($fp);
            return $resp;
        } else {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            /* if $url is redirect then to follow redirection */
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl, CURLOPT_MAXREDIRS, 3);
            curl_setopt($curl, CURLOPT_POST, 0);

            $time_out = is_numeric($save_to) ? $save_to : ($save_to === false ? 1 : 0);
            if ($time_out > 0)
                curl_setopt($curl, CURLOPT_TIMEOUT, $time_out); //if $save_to == 0 means wait until response


            // extra options?
            if (!empty($options)) {
                curl_setopt_array($curl, $options);
            }

            $resp = curl_exec($curl);
            curl_close($curl);
            return $resp;
        }

        return false;
    }

    public static function execRemoteUrlsAsync($urls, $params = [], $options = array(), $save_to = '') {
        if (is_string($urls))
            $data = [$urls];
        else
            $data = $urls;

        if (is_bool($options)) {
            $save_to = $options;
            $options = [];
        }

        // array of curl handles
        $curly = array();
        // data to be returned
        $result = array();

        // multi handle
        $mh = curl_multi_init();

        // loop through $data and create curl handles
        // then add them to the multi-handle
        $i = 0;
        foreach ($data as $id => $d) {
            $curly[$i] = curl_init();
            $i += 1;

            if (is_string($id)) {
                $url = $id;
                $id = $i;
            }
            else
                $url = (is_array($d) && isset($d['url']) && !empty($d['url'])) ? $d['url'] : $d;

            $time_out = is_numeric($save_to) ? $save_to : ($save_to === false ? 1 : 0);
            if ($time_out > 0)
                curl_setopt($curly[$id], CURLOPT_TIMEOUT, $time_out); //if $save_to == 0 means wait until response

            curl_setopt($curly[$id], CURLOPT_URL,            $url);
            /* if $url is redirect then to follow redirection */
            curl_setopt($curly[$id], CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curly[$id], CURLOPT_MAXREDIRS, 3);

            curl_setopt($curly[$id], CURLOPT_HEADER,         0);
            curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);

            // post?
            $post = $params;

            if (is_array($d)) {
                $post = (isset($d['post']) && !empty($d['post'])) ? $d['post'] : $d;
            }
            if (!empty($post)) {
                curl_setopt($curly[$id], CURLOPT_POST, 1);
                curl_setopt($curly[$id], CURLOPT_POSTFIELDS, $post);
            }

            // extra options?
            if (!empty($options)) {
                curl_setopt_array($curly[$id], $options);
            }

            curl_multi_add_handle($mh, $curly[$id]);
        }

        // execute the handles
        $running = null;
        do {
            curl_multi_exec($mh, $running);
        } while($running > 0);


        // get content and remove handles
        foreach($curly as $id => $c) {
            $result[$id] = curl_multi_getcontent($c);

            curl_multi_remove_handle($mh, $c);
        }

        // all done
        curl_multi_close($mh);

        return $result;
    }

    // This limits the request rate to the size of the $rolling_window
    // http://www.onlineaspect.com/2009/01/26/how-to-use-curl_multi-without-blocking/
    public static function execRemoteUrlsMass($urls, $callback, $custom_options = null)
    {
        // make sure the rolling window isn't greater than the # of urls
        $rolling_window = 5;
        $rolling_window = (sizeof($urls) < $rolling_window) ? sizeof($urls) : $rolling_window;
        $master = curl_multi_init();
        $curl_arr = array();
        // add additional curl options here
        $std_options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5
        );
        $options = ($custom_options) ? ($std_options + $custom_options) : $std_options;
        // start the first batch of requests
        for ($i = 0; $i < $rolling_window; $i++)
        {
            $ch = curl_init();
            $options[CURLOPT_URL] = $urls[$i];
            curl_setopt_array($ch,$options);
            curl_multi_add_handle($master, $ch);
        }
        do {
            while (($execrun = curl_multi_exec($master, $running)) == CURLM_CALL_MULTI_PERFORM);
            if($execrun != CURLM_OK)
                break;
            // a request was just completed -- find out which one
            while($done = curl_multi_info_read($master))
            {
                $info = curl_getinfo($done['handle']);
                $output = curl_multi_getcontent($done['handle']);
                // request successful.  process output using the callback function.
                if (is_callable($callback))
                    $callback($output, $info);

                if(isset($urls[$i + 1]))
                {
                    // start a new request (it's important to do this before removing the old one)
                    $ch = curl_init();
                    $options[CURLOPT_URL] = $urls[$i++];  // increment i
                    curl_setopt_array($ch,$options);
                    curl_multi_add_handle($master, $ch);
                }
                // remove the curl handle that just completed
                curl_multi_remove_handle($master, $done['handle']);
            }
            usleep(10000); // stop wasting CPU cycles and rest for a couple ms
        } while ($running);
        curl_multi_close($master);
    }

    /**
     * Get Results of the Application SystemCheck.
     *
     * Fields
     *  - title
     *  - state (OK, WARNING or ERROR)
     *  - hint
     *
     * @return array|Array
     */
    public static function getSystemInfo()
    {
        $application_id = FHtml::currentApplicationId();
        /**
         * ['title']
         * ['state']    = OK, WARNING, ERROR
         * ['hint']
         */
        $checks = [];

        // Checks PHP Version
        $title = 'PHP - Version - ' . PHP_VERSION;

        # && version_compare(PHP_VERSION, '5.9.0', '<')
        if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
            $checks[] = [
                'title' => $title,
                'state' => 'OK'
            ];
        } elseif (version_compare(PHP_VERSION, '5.3.0', '<=')) {
            $checks[] = [
                'title' => $title,
                'state' => 'ERROR',
                'hint'  => 'Minimum 5.4'
            ];
        }
        if (function_exists('apache_get_modules')) $installed_apache_modules = apache_get_modules();

        $checks[] = ['title' => 'PHP ini.php'];
$checks[] = ['title' => 'register_globals' .  ini_get('register_globals'),'state' => in_array(strtolower(ini_get('register_globals')), array('off', 'false', '', '0')) ? 'OK': 'ERROR', 'hint' => 'Not recommended'];
$checks[] = ['title' => 'arg_separator.output' .  htmlspecialchars(ini_get('arg_separator.output')), 'state' => (ini_get('arg_separator.output') == '&') ? 'OK': 'ERROR', 'hint' => 'Not recommended'];
$checks[] = ['title' => 'memory_limit' .  ini_get('memory_limit'), 'state' => (return_bytes(ini_get('memory_limit')) >= 128*1024*1024) ? 'OK': 'ERROR', 'hint' => 'Not recommended'];
        $checks[] = ['title' => 'Extensions'];
$checks[] = ['title' => 'dom ', 'state' => extension_loaded('dom') ? 'OK': 'ERROR', 'hint' => 'Missing'];
$checks[] = ['title' => 'mbstring ', 'state' => extension_loaded('mbstring') ? 'OK': 'ERROR', 'hint' => 'Missing'];
$checks[] = ['title' => 'mysqli ', 'state' => extension_loaded('mysqli') ? 'OK': (extension_loaded('mysql') ? '<span class="warning">[Warning] Obsolete extension mysql, install mysqli instead</span>' : 'ERROR'), 'hint' => 'Missing'];
$checks[] = ['title' => 'gd / imagick ', 'state' => extension_loaded('imagick') ? 'OK': (extension_loaded('gd') ? 'OK': 'ERROR'), 'hint' => 'Missing'];
        $title = 'PDO extension';
        if (extension_loaded('pdo')) {
            $checks[] = [
                'title' => $title,
                'state' => 'OK'
            ];
        } else {
            $checks[] = [
                'title' => $title,
                'state' => 'ERROR',
                'hint'  => 'Install PDO Extension'
            ];
        }

        // PDO MySQL extension
        $title = 'PDO MySQL extension';
        if (extension_loaded('pdo_mysql')) {
            $checks[] = [
                'title' => $title,
                'state' => 'OK'
            ];
        } else {
            $checks[] = [
                'title' => $title,
                'state' => 'ERROR',
                'hint'  => 'Required by database'
            ];
        }

        // Checks GD Extension
        $title = 'PHP - GD Extension';
        if (function_exists('gd_info')) {
            $checks[] = [
                'title' => $title,
                'state' => 'OK'
            ];
        } else {
            $checks[] = [
                'title' => $title,
                'state' => 'ERROR',
                'hint'  => 'Install GD Extension'
            ];
        }

        // PHP SMTP
        $title = 'PHP Mail SMTP';
        if (strlen(ini_get('SMTP')) > 0) {
            $checks[] = [
                'title' => $title,
                'state' => 'OK'
            ];
        } else {
            $checks[] = [
                'title' => $title,
                'state' => 'WARNING',
                'hint'  => 'SMTP is required to send mails'
            ];
        }


        // Only for Windows
        if (strpos(strtolower(php_uname('s')), 'windows') !== FALSE) {
            // Check COM support
            $title = 'PHP COM Support';
            if (extension_loaded('com_dotnet')) {
                $checks[] = [
                    'title' => $title,
                    'state' => 'OK'
                ];
            } else {
                $checks[] = [
                    'title' => $title,
                    'state' => 'ERROR',
                    'hint'  => 'Install COM extension - ' . Html::a('COM Support', 'http://php.net/manual/en/book.com
					.php', ['target' => 'blank'])
                ];
            }
        }


        $checks[] = ['title' => 'Apache 2 compatible HTTP daemon'];
$checks[] = ['title' => 'mod_auth_basic ', 'state' => (!empty($installed_apache_modules)) && (in_array('mod_auth', $installed_apache_modules) || in_array('mod_auth_basic', $installed_apache_modules)) ? 'OK': 'ERROR', 'hint' => 'Missing'];
$checks[] = ['title' => 'mod_deflate ', 'state' => (!empty($installed_apache_modules) && in_array('mod_deflate', $installed_apache_modules)) ? 'OK': 'ERROR', 'hint' => 'Missing'];
$checks[] = ['title' => 'mod_env ', 'state' => (!empty($installed_apache_modules) && in_array('mod_env', $installed_apache_modules)) ? 'OK': 'ERROR', 'hint' => 'Missing'];
$checks[] = ['title' => 'mod_headers ', 'state' => (!empty($installed_apache_modules) && in_array('mod_headers', $installed_apache_modules)) ? 'OK': 'ERROR', 'hint' => 'Missing'];
$checks[] = ['title' => 'mod_rewrite ', 'state' => (!empty($installed_apache_modules) && in_array('mod_rewrite', $installed_apache_modules)) ? 'OK': 'ERROR', 'hint' => 'Missing'];



// PDO extension

        return $checks;
    }

    public static function getFilesPermissions($paths = []) {
        $application_id = FHtml::currentApplicationId();
        $root_folder = FHtml::getRootFolder();

        if (empty($paths)) {
            $paths = [
                'Config File' => "applications/$application_id/config/params.php",
                "Cache Folder" => "backend/runtime",
                "htaccess" => '.htaccess',
            ];

            $langs = FFile::listFolders($root_folder . "/applications/$application_id/messages");
            foreach ($langs as $lang => $folder)
            {
                $arr = explode('/', $folder);
                $m1 = $arr[count($arr) - 1];
                $paths = array_merge($paths, ["Lang $m1" => $lang . '/common.php']);
            }
        }
        $result = [];
        foreach($paths as $title => $path) {
            $path = FHtml::getFullFileName($path);
            if (file_exists($path) && is_writable( $path)) {
                $result[] = ['title' => "<b>$title</b>: " . $path, 'state' => 'OK'];
            } else if (file_exists($path) && is_writable( pathinfo($path, PATHINFO_DIRNAME))) {
                $result[] = ['title' => "<b>$title</b>: " . $path, 'state' => 'OK'];
            } else if (file_exists($path)) {
                $result[] = ['title' => "<b>$title</b>: " . $path, 'state' => 'ERROR', 'hint'  => 'Read-only, please make path writable'];
            } else {
                $result[] = ['title' => "<b>$title</b>: " . $path, 'state' => 'ERROR', 'hint'  => 'Not existed'];
            }
        }

        return $result;
    }

    public static function showCheckArray($arr)
    {
        $result = "<ul class='list-unstyled'><ul>";
        foreach ($arr as $item) {
            if (!key_exists('state', $item)) {
                $result .= '<br/><b>' . $item['title'] . '</b>';
                continue;
            }

            $result .= '<li>' . $item['title'] . ' <span class="' . strtolower($item['state']) . '">[' . (key_exists('hint', $item) && $item['state'] == 'ERROR' ? $item['hint'] :  $item['state']) . ']</span></li>';
        }
        $result .= "</ul></ul>";
        echo $result;
    }
}
