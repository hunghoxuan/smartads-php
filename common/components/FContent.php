<?php
/**
 * Created by PhpStorm.
 * User: Darkness
 * Date: 11/30/2016
 * Time: 2:00 PM
 */

namespace common\components;


use common\components\vcard\VCard;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use yii\helpers\Html;
use yii\helpers\Json;
use Yii;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use DateTimeZone;

class FContent extends FConstant
{
    /**
     * @param $category
     * @param bool $message
     * @param array $params
     * @param null $language
     * @return bool|mixed|string
     */
    public static function t($category, $message = false, $replaced = [], $language = null)
    {
        if (is_object($message))
            return FHtml::getFieldValue($message, ['name', 'title']);

        if (empty($language))
            $language = FHtml::currentLang();

        $isLangEnabled =  FConfig::isLanguagesEnabled() || !empty($language);

        if (is_array($message)) {
            $replaced = $message;
            $message = $category;
            $category = 'common';
        }
        else if (empty($message)) {
            if (is_string($message) || !isset($message))
                return '';

            if (in_array($category, ['common', 'button']))
                return '';
            $message = $category;
            $category = 'common';
        }

        if (empty($message))
            return '';

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

        $params = FConfig::getApplicationTranslations($language);
        if (!is_array($params))
            return $message;

        $message_origin = $message;

        if (is_string($message))
            $message = trim($message, " '.:,\t\n\r\0\x0B");

        $message = str_replace("  ", " ", $message);

        if (is_string($category))
            $category = str_replace('-', '_', BaseInflector::camel2id(trim($category)));

        if ($message == 'common' || empty($message))
            return FHtml::getNullValueText();

        $message = FHtml::strReplace($message, FHtml::MULTILANG_TEXT_REMOVALS);
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
                FConfig::saveApplicationTranslations($params);
            return $result;
        }

        //does not existed
        if (!empty($message)) {
            if ($auto_save)
                FConfig::saveApplicationTranslations([$category . '.' . $message1 => $message], $params);

            if (!empty($replaced))
                return static::strReplace($message, $replaced, '', '{', '}');

            return $message;
        } else {
            return $category;
        }
    }

    public static function strReplace($name, $pairs = [], $replaced_by = '', $begin = '', $end = '') {
        $p = [];
        foreach ($pairs as $token => $replace) {
            if (is_numeric($token)) {
                $token = $replace;
                $replace = $replaced_by;
            }
            $p[$begin . $token . $end] = $replace;
        }

        return !empty($pairs) ? strtr($name, $p) : $name;
    }

    public static function strReplaceTokens($name, $pairs = [], $replaced_by = '', $begin = '{{', $end = '}}') {
        return static::strReplace($name, $pairs, $replaced_by, $begin, $end);
    }

    public static function is_image($url, $check_existed = true)
    {
        $result = false;

        $extension = substr($url, strlen($url) - 4);

        if (in_array($extension, ['.jpg', '.png', '.bmp', '.gif', 'jpeg']))
            $result = true;

        if ($result && $check_existed) {

            $base_url = FHtml::currentHost();

            if (!StringHelper::startsWith($url, 'http'))
                $url = $base_url . $url;

            if (strpos($url, '/api/file') !== false)
                return true;

            if (strpos($url, '/api/image') !== false)
                return true;

//          This method is slow
//        $image_size = self::getImageSize($url);
//        if ($image_size !== false && is_array($image_size))
//            return true;
//        else
//            return false;

            $file_headers = @get_headers($url);

            if ($file_headers[0] == 'HTTP/1.1 404 Not Found') {
                $result = false;
            } else {
                $result = true;
            }
        }
        return $result;
    }


    public static function format_json($json)
    {
        //return json_encode(json_decode($json), JSON_PRETTY_PRINT);
        if (!is_string($json)) {
            if (phpversion() && phpversion() >= 5.4) {
                return json_encode($json, JSON_PRETTY_PRINT);
            }
            $json = json_encode($json);
        }
        $result = '';
        $pos = 0;               // indentation level
        $strLen = strlen($json);
        $indentStr = "\t";
        $newLine = "\n";
        $prevChar = '';
        $outOfQuotes = true;
        for ($i = 0; $i < $strLen; $i++) {
            // Speedup: copy blocks of input which don't matter re string detection and formatting.
            $copyLen = strcspn($json, $outOfQuotes ? " \t\r\n\",:[{}]" : "\\\"", $i);
            if ($copyLen >= 1) {
                $copyStr = substr($json, $i, $copyLen);
                // Also reset the tracker for escapes: we won't be hitting any right now
                // and the next round is the first time an 'escape' character can be seen again at the input.
                $prevChar = '';
                $result .= $copyStr;
                $i += $copyLen - 1;      // correct for the for(;;) loop
                continue;
            }

            // Grab the next character in the string
            $char = substr($json, $i, 1);

            // Are we inside a quoted string encountering an escape sequence?
            if (!$outOfQuotes && $prevChar === '\\') {
                // Add the escaped character to the result string and ignore it for the string enter/exit detection:
                $result .= $char;
                $prevChar = '';
                continue;
            }
            // Are we entering/exiting a quoted string?
            if ($char === '"' && $prevChar !== '\\') {
                $outOfQuotes = !$outOfQuotes;
            }
            // If this character is the end of an element,
            // output a new line and indent the next line
            else if ($outOfQuotes && ($char === '}' || $char === ']')) {
                $result .= $newLine;
                $pos--;
                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            } // eat all non-essential whitespace in the input as we do our own here and it would only mess up our process
            else if ($outOfQuotes && false !== strpos(" \t\r\n", $char)) {
                continue;
            }
            // Add the character to the result string
            $result .= $char;
            // always add a space after a field colon:
            if ($outOfQuotes && $char === ':') {
                $result .= ' ';
            }
            // If the last character was the beginning of an element,
            // output a new line and indent the next line
            else if ($outOfQuotes && ($char === ',' || $char === '{' || $char === '[')) {
                $result .= $newLine;
                if ($char === '{' || $char === '[') {
                    $pos++;
                }
                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            }
            $prevChar = $char;
        }
        return $result;
    }

    public static function isInArray($name, $arrays, $table = '', $character = '*')
    {
        if (is_object($table))
            $table = FModel::getTableName($table);

        if (is_array($table)) {
            $table = '';
        }

        if (empty($arrays))
            return true;

        if (is_string($name)) {
            $name_arr = strpos($name, ',') !== false ? explode(',', $name) : [$name];
        }
        else if (is_array($name))
            $name_arr = $name;
        else
            $name_arr = [];


        foreach ($arrays as $name) {
            if (StringHelper::startsWith($name, '_'))
                continue;

            $name1 = BaseInflector::camel2id($name);
            if (!in_array($name1, $arrays) && !empty($name1))
                $arrays[] = $name1;

            $name1 = BaseInflector::camelize($name);
            if (!in_array($name1, $arrays) && !empty($name1))
                $arrays[] = $name1;
        }

        foreach ($name_arr as $name) {
            foreach ($arrays as $item) {
                $quit_value = true;
                if (StringHelper::startsWith($item, '-') || StringHelper::startsWith($item, '!')) {
                    $quit_value = false;
                    $item = substr($item, 1);
                }
                if (StringHelper::startsWith($item, $character)) {
                    if (StringHelper::endsWith($name, trim($item, $character))) {
                        return $quit_value;
                    }
                    if (!empty($table) && StringHelper::endsWith($table . '.' . $name, trim($item, $character))) {
                        return $quit_value;
                    }
                } else if (StringHelper::endsWith($item, $character)) {
                    if (StringHelper::startsWith($name, trim($item, $character))) {
                        return $quit_value;
                    }
                    if (!empty($table) && StringHelper::startsWith($table . '.' . $name, trim($item, $character))) {
                        return $quit_value;
                    }
                } else {
                    if ($name === $item) {
                        return $quit_value;
                    }
                    if (!empty($table) && ($table . '.' . $name) == $item) {
                        return $quit_value;
                    }
                }
            }
        }

        return false;
    }

    //2017/3/6
    public static function parseValueAsArray($str) {
        if (is_numeric($str) || is_array($str))
            return $str;

        $arr = FHtml::decode($str);
        if (is_array($arr))
            return $arr;
        $str = trim($str, ";,");
        $arr = explode(",", $str);
        if (is_array($arr) && count($arr) > 1) {
            $arr = array_unique($arr);
            foreach ($arr as $item) {
                if (empty($arr[$item]))
                    unset($arr[$item]);
            }
            return $arr;
        }
        return $str;
    }


    public static function is_json($string)
    {
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }

    public function dateDiff( $start, $end )
    {
        $uts['start']      =    strtotime( $start );
        $uts['end']        =    strtotime( $end );
        if( $uts['start']!==-1 && $uts['end']!==-1 )
        {
            if( $uts['end'] >= $uts['start'] )
            {
                $diff    =    $uts['end'] - $uts['start'];
                if( $years=intval((floor($diff/31104000))) )
                    $diff = $diff % 31104000;
                if( $months=intval((floor($diff/2592000))) )
                    $diff = $diff % 2592000;
                if( $days=intval((floor($diff/86400))) )
                    $diff = $diff % 86400;
                if( $hours=intval((floor($diff/3600))) )
                    $diff = $diff % 3600;
                if( $minutes=intval((floor($diff/60))) )
                    $diff = $diff % 60;
                $diff    =    intval( $diff );
                return( array('years'=>$years,'months'=>$months,'days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
            }
            else
            {
                return ['error' => "Ending date/time is earlier than the start date/time"];
            }
        }
        else
        {
            return ['error' => "Invalid date/time data detected"];
        }
    }

    public static function generateRandomInArray($arrays)
    {
        $i = array_rand($arrays, 1);
        return $arrays[$i];
    }

    public static function Now($format = 'Y-m-d H:i:s', $timezone = SERVER_TIME_ZONE)
    {
        if (!empty($timezone))
            date_default_timezone_set($timezone);
        return date($format);
    }

    public static function time($timezone = SERVER_TIME_ZONE)
    {
        if (!empty($timezone))
            date_default_timezone_set($timezone);
        return time();
    }

    public static function date($format = 'Y-m-d', $timezone = SERVER_TIME_ZONE)
    {
        if (!empty($timezone))
            date_default_timezone_set($timezone);
        return date($format);
    }

    public static function toArrayFromDbComment($commment, $name = '')
    {
        $array = FHtml::toArray($commment, ';', ':');
        if (isset($array['data'])) {
            $a = FHtml::toArray(str_replace(['[', ']'], ['', ''], $array['data']), ',', '=');
            $arr1 = [];
            foreach ($a as $key => $value) {
                $arr1 = self::arrayMerge($arr1, [$key => $value]);
            }
            $array['data'] = $arr1;
        }

        if (!key_exists('group', $array))
            $array['group'] = null;
        if (!key_exists('editor', $array))
            $array['editor'] = null;
        if (!key_exists('related', $array))
            $array['related'] = null;
        if (!key_exists('meta', $array))
            $array['meta'] = null;
        return $array;
    }

    public static function toArray($text, $seprator1 = ';', $splitter1 = '=')
    {
        if (is_object($text)) {
            if (method_exists($text, 'asArray'))
                return $text->asArray();
        }

        //echo $text . ':';
        $arr = explode($seprator1, $text);
        $result = [];
        foreach ($arr as $item) {
            $arr1 = explode($splitter1, $item);
            $key = reset($arr1);
            $value = end($arr1); //echo $key . ' ' . $value;
            //echo $key . '=>' . $value . ' ';
            $result = self::arrayMerge($result, [$key => $value]);
        }

        return $result;
    }

    public static function firstOf($text, $char = '_')
    {
        if (strpos($text, $char) !== FALSE)
            return substr($text, 0, strpos($text, $char));
        else
            return '';
    }

    public static function Format()
    {
        return \Yii::$app->formatter;
    }

    public static function addDate($date = '', $diff = '', $format = "Y-m-d")
    {
        if (empty($diff))
            return $date;
        $formatedDate = FHtml::formatDate($date, $format, $to_format = 'Y-m-d H:i:s');
        $newdate = strtotime($diff, strtotime($formatedDate));
        return date($format, $newdate);
    }

    public static function formatDate($date, $from_format = 'Y-m-d', $to_format = 'Y-m-d')
    {
        $date_aux = date_create_from_format($from_format, $date);
        return date_format($date_aux, $to_format);
    }

    public static function strpos_array($haystack, $needles) {
        if ( is_array($needles) ) {
            foreach ($needles as $str) {
                if ( is_array($str) ) {
                    $pos = self::strpos_array($haystack, $str);
                } else {
                    $pos = strpos($haystack, $str);
                }
                if ($pos > -1) {
                    return $pos;
                }
            }
            return -1;
        } else {
            return strpos($haystack, $needles);
        }
    }


    public static function str_limit($str, $count = 200)
    {
        if ($count >= strlen($str))
            $count = strlen($str);

        $result = substr($str, 0, $count);
        $remain = substr($str, $count);
        $result = $result . (!empty($remain) ? '...' : '');
        return $result;
    }

    public static function str_upper($str, $charset = 'utf-8') {
        return mb_strtoupper($str, $charset);
    }

    public static function str_lower($str, $charset = 'utf-8') {

        return mb_strtolower($str, $charset);
    }

    public static function getDaysInWeek() {
        return self::ARRAY_DAYS_IN_WEEK;
    }

    public static function getWeekday($date = '') {
        if (empty($date))
            $date = date('Y-m-d');
        return date('w', strtotime($date));
    }

    public static function getWeekdayInWord($date = '') {
        $days = self::getDaysInWeek();
        $i = self::getWeekday($date);
        return $days[$i];
    }

    public static function getNumeric($value) {
        if (is_numeric($value))
            return (double) $value;
        return 0;
    }

    public static function getBool($value, $bool_array = [FHtml::STATUS_APPROVED, FHtml::STATUS_DONE, 'true', 'on', 'active', 'ok', 'yes']) {
        if (!isset($value))
            return null;

        if (is_bool($value) || is_numeric($value) || empty($value))
            return (bool) $value;

        $value = strtolower($value);
        if ($value === 1 || $value === true || $value === TRUE || (is_string($value) && in_array($value, $bool_array)))
            return true;

        return false;
    }

    /**
     * @param $date
     * @param string $format
     * @param array $attributes
     * @return false|string
     */
    public static function getDateFormat($date, $format = '',$attributes = []) {
        $attributes = array_merge([
            'year' => true,
            'month' => true,
            'day' => true,
            'hour' => false,
            'minute' => false,
            'second' => false,
            'prefix_date' => '-',
            'prefix_time' => ':',
        ], $attributes);

        if (empty($format)) {
            $year   = (isset($attributes['year'])   && $attributes['year'])   ? "Y" : '';
            $month  = (isset($attributes['month'])  && $attributes['month'])  ? "m" : '';
            $day    = (isset($attributes['day'])    && $attributes['day'])    ? "d" : '';
            $hour   = (isset($attributes['hour'])   && $attributes['hour'])   ? "H" . $attributes['prefix_time'] : '';
            $minute = (isset($attributes['minute']) && $attributes['minute']) ? "i" . $attributes['prefix_time'] : '';
            $second = (isset($attributes['second']) && $attributes['second']) ? "s" : '';
            if (FHtml::currentLang() == 'vi') {
                $format = $day . $attributes['prefix_date']  . $month .$attributes['prefix_date'] . $year . " ". $hour . $minute . $second;
            }
            else {
                $format = $year . $attributes['prefix_date']  . $month .$attributes['prefix_date'] . $day . " ". $hour . $minute . $second;
            }
        }

        return date($format, $date);
    }

    public static function getNullValueText($showAll = false) {
        return "(" . FHtml::t('common', is_string($showAll) ? $showAll : ($showAll ? 'All' : 'Empty')) . ")";
    }

    public static function removeEmptyValues($items, $removed = FHtml::NULL_VALUE, $replaced = '') {

        if (is_string($items))
            $items = str_replace($removed, $replaced, $items);
        else if (is_array($items))
        {
            if (is_string($removed))
                $removed = [$removed];
            foreach ($items as $key => $value) {
                if (in_array($value, $removed)) {
                    if (empty($replaced))
                        unset($items[$key]);
                    else
                        $items[$key] = $replaced;
                }
            }
        }
        return $items;
    }


    public static function cleanString($string)
    {
        return $string;
//        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
//        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
//
//        return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    }

    public static function cleanHtml($string) {
        $result = htmlentities(strip_tags(FHtml::strReplace( $string, ['<p style="text-align:justify">' => '', '</p>' => ''])));
        return $result;
    }

    public static function encodeHtml($string) {
        $result = htmlentities($string);

        return $result;
    }

    public static function decodeHtml($string) {
        $result = html_entity_decode($string);

        return $result;
    }

    public static function encode($string, $type = 'json', $seperator = ',', $quote = '', $quote1 = '"', $assign = ':')
    {
        if (is_string($string))
            $string = trim($string);

        if ($type == 'json')
            return json_encode($string, JSON_NUMERIC_CHECK);

        if (is_array($string)) {
            $arr = [];
            foreach ($string as $a => $value) {
                $arr[] = "$quote$a$quote$assign$quote1$value$quote1";
            }
            return implode($seperator, $arr);
        }

        return $string;
    }


    public static function decode($string, $alwaysArray = false, $keys = [])
    {
        if (empty($string))
            return $string;

        if (is_array($string))
            return $string;

        $string = trim($string, "\"");

        if ((is_string($alwaysArray) && strlen($alwaysArray) == 2) || is_array($alwaysArray)) {
            $s1 = $alwaysArray[0];
            $s2 = $alwaysArray[1];
        } else if ((is_string($alwaysArray) && strlen($alwaysArray) == 1)) {
            $s1 = '';
            $s2 = $alwaysArray;
        }else {
            $s1 = ':'; $s2 = ';';
        }

        if (self::is_json($string)) {
            return json_decode($string, true);
        }
        else if ($alwaysArray == false)
            return $string;
        else
        {
            $arr1 = explode($s2 , $string);
            $result = [];
            foreach ($arr1 as $i => $item) {
                if (empty($s1)) {
                    $result = self::arrayMerge($result, [$item]);
                } else {
                    $arr2 = explode($s1, $item);
                    if (count($arr2) == 2) {
                        $result = self::arrayMerge($result, [$arr2[0] => $arr2[1]]);
                    } else {
                        $result = self::arrayMerge($result, [$item]);
                    }
                }
            }
            
            if (!empty($keys))
                $result = FHtml::getKeyValueArray($result, false, 'common', $keys);

            return $result;
        }
    }


    //2015/3/23
    public static function Today($time = '', $format = 'Y-m-d', $timezone = '')
    {
        if (!empty($timezone))
            date_default_timezone_set($timezone);

        if (empty($time))
            return date($format);
        else
            return date($format, strtotime($time));
    }

    public static function getDate($date, $days = '0', $format = 'Y-m-d') {

        $date = strtotime("+".$days." days", strtotime($date));
        return  date($format, $date);

    }

    public static function generateRegisterCode()
    {
        $s = strtoupper(md5(time() . rand(1, 100)));
        return $s;
    }

    public static function generateActivationCode($email)
    {
        $s = strtoupper(md5(uniqid(rand(), true)));
        $e = strtoupper(md5($email));
        return substr($s . $e, 5, strlen($s));
    }


    public static function generateTransactionId($userId)
    {
        $s = strtoupper(md5(uniqid(rand(), true)));
        return substr($s . $userId, 18, strlen($s));
    }


    public static function is_url($url) {
        return StringHelper::startsWith($url, 'http') || StringHelper::startsWith($url, 'www');
    }

    public static function parseUrl($url) {
        $parsed = parse_url($url);

        if (!key_exists('host', $parsed)) {
            return [];
        }

        $hostname = $parsed['host'];  // WWW.YOUTUBE.COM
        $query = !key_exists('query', $parsed) ? '' : $parsed['query']; // v=5sRDHnTApSw&feature=youtu.be.......to end of the string
        $path = !key_exists('path', $parsed) ? '' : $parsed['path']; // this is for vimeo.com
        //print_r($parsed);


        //YOUTUBE.COM
        if ((isset($hostname)) && ($hostname=='www.youtube.com' || $hostname=='youtube.com')) {
            if (empty($query)) {
                $Arr = explode('/', $path);
                // from video id, until to end of the string like 5sRDHnTApSw&feature=youtu.be The master Bob Haro......
                $videoIDwithString =  count($Arr) > 1 ? $Arr[2] : '';
                $videoID = strlen($videoIDwithString) > 10 ? substr($videoIDwithString,0,11) : ''; // 5sRDHnTApSw
            } else {
                $Arr = explode('v=', $query);
                // from video id, until to end of the string like 5sRDHnTApSw&feature=youtu.be The master Bob Haro......
                $videoIDwithString = count($Arr) > 0 ? $Arr[1] : '';
                $videoID = strlen($videoIDwithString) > 10 ? substr($videoIDwithString, 0, 11) : ''; // 5sRDHnTApSw
                //print_r($videoID);
            }
            if (!empty($videoID)) {
                $file = "http://www.youtube.com/embed/$videoID";
                $file_type = 'youtube';
            } else {
                $file_type = '';
                $file = $url;
            }
        } else if ((isset($hostname)) && ($hostname=='www.youtu.be' || $hostname=='youtu.be')) {
            $Arr = explode('/', $path);
            // from video id, until to end of the string like 5sRDHnTApSw&feature=youtu.be The master Bob Haro......
            $videoIDwithString =  count($Arr) > 0 ? $Arr[1] : '';
            $videoID = strlen($videoIDwithString) > 10 ? substr($videoIDwithString,0,11) : ''; // 5sRDHnTApSw
            //print_r($videoID);
            if (!empty($videoID)) {
                $file = "http://www.youtube.com/embed/$videoID";
                $file_type = 'youtube';
            } else {
                $file_type = '';
                $file = $url;
            }
        } else if((isset($hostname)) && $hostname=='vimeo.com') {
            $ArrV = explode('://vimeo.com/', $path); // from ID to end of the string
            $videoID = substr($ArrV[0], 1, 8); // to get video ID
            $vimdeoIDInt = intval($videoID); // ID must be integer
            $file_type = 'vimeo';
            $file = "http://player.vimeo.com/video/$vimdeoIDInt";
        } else {
            $file_type = '';
            $file = $url;
        }

        return array_merge($parsed, [
            'type' => $file_type,
            'url' => $file
        ]);
    }

    public static function str_exists($str, $check) {
        if (is_string($check))
            $check = [$check];

        $result = [];
        foreach ($check as $item) {
            if (strpos($str, $item) > 0)
                $result[] = $item;
        }

        return $result;
    }

    public static function str_exists_all($str, $check) {
        if (is_string($check))
            $check = [$check];

        return count(self::str_exists($str, $check)) == count($check);
    }

    public static function str_exists_any($str, $check) {
        return !empty(self::str_exists($str, $check));
    }

    public static function str_exists_none($str, $check) {
        return empty(self::str_exists($str, $check));
    }

    public static function is_sql_condition($condition) {
        return self::str_exists_any($condition, [">", "<", "=", "like"]) && strpos($condition, ' ') > 0;
    }

    public static function number_breakdown($number, $returnUnsigned = false)
    {
        $negative = 1;
        if ($number < 0)
        {
            $negative = -1;
            $number *= -1;
        }

        if ($returnUnsigned){
            return array(
                floor($number),
                ($number - floor($number))
            );
        }

        return array(
            floor($number) * $negative,
            ($number - floor($number)) * $negative
        );
    }

    public static function array_existed($arr1, $arr2) {
        $sub_arr = array_keys(array_intersect_key($arr2, $arr1));
        $result = false;
        if (!empty($sub_arr) && count($sub_arr) == count($arr1))
        {
            $result = true;
            foreach ($sub_arr as $key)
                $result = $result && ($arr1[$key] == $arr2[$key]);
        }

        return $result;
    }

    public static function array_intersect($arr1, $arr2) {
        $sub_arr = array_keys(array_intersect_key($arr2, $arr1));
        $result = [];
        if (!empty($sub_arr))
        {
            foreach ($sub_arr as $key)
                $result = self::arrayMerge($result, [$key => array_unique([$arr1[$key], $arr2[$key]])]);
        }

        return $result;
    }

    public static function arrayMerge() {
        $args = func_get_args();
        $result = array(); $result1 = [];
        foreach ($args as &$array) {
            if (!is_array($array))
                $array = [$array];
            $result1 = $result1 + $array;
            //Hung: No need ?
//            foreach ($array as $key=>&$value) {
//                if (is_string($key))
//                    $result[$key] = $value;
//                else
//                    $result[] = $value;
//            }
        }

        return $result1;
    }

    public static function arrayRemove($array, $excluded_fields ) {
        if (is_array($array) && !empty($excluded_fields)) {
            if (!is_array($excluded_fields))
                $excluded_fields = explode(',', $excluded_fields);

            foreach ($excluded_fields as $excluded) {
                if (key_exists($excluded, $array)) {
                    unset($array[$excluded]);
                } else if (($key = array_search($excluded, $array)) !== false) {
                    unset($array[$key]);
                }
            }
        }

        return $array;
    }
    public static function arrayMap($models, $id_field = 'id', $display_name = 'name', $group = null, $hasNull = true) {
        $result = [];
        if (is_bool($group)) {
            $hasNull = $group;
            $group = null;
        }

        if ($hasNull)
            $result = [null => FContent::getNullValueText()];

        foreach ($models as $i => $element) {
            if (is_string($element))
                return $models;

            $key = "" . FModel::getFieldValue($element, $id_field) . "";
            $value = FModel::getFieldValue($element, $display_name);

            if (!empty($value) || !empty($key)) {
                if ($group !== null) {
                    $result[FModel::getFieldValue($element, $group)][$key] = $value;
                } else {
                    $result[$key] = $value;
                }
            }
        }

        return $result;
    }

    public static function getGoogleLink($value)
    {
        if (empty($value))
            return '#';
        if (strpos($value, 'google.com') > 0)
            $value = 'https://www.google.com/' . $value;
        return $value;
    }

    public static function showSocialLinks($array, $fields = [], $showEmptyValue = true)
    {
        $result = '';
        if (!is_array($array)) {
            if (empty($fields))
                $fields = ['fb', 'tw', 'gp', 'facebook', 'twitter', 'google', 'linkedin'];

            $array = FHtml::getFieldArray($array, $fields);
        }

        if (is_array($array)) {
            foreach ($array as $id => $value) {
                if (!$showEmptyValue && empty($value))
                    continue;
                if (empty($value))
                    $value = '#';

                if ($id == 'facebook' || $id == 'fb') {
                    $css = 'fb';
                    $value = self::getFacebookLink($value);
                } else if ($id == 'twitter' || $id == 'tw') {
                    $css = 'tw';
                    $value = self::getTwitterLink($value);
                } else if ($id == 'linkedin' || $id == 'li') {
                    $css = 'li';
                    $value = self::getLinkedInLink($value);
                } else if ($id == 'google' || $id == 'google') {
                    $css = 'gp';
                    $value = self::getYoutubeLink($value);
                } else
                    $css = '';

                if (empty($value)) {
                    $css .= ' disabled';
                    $value = '#';
                }

                $result .= str_replace(['{css}', '{id}', '{value}'], [$css, $id, $value], '<li><a data-placement="top" data-toggle="tooltip" class="{css} tooltips" data-original-title="{id}" href="{value}"><i class="fa fa-{id}"></i></a></li>');
            }
        }
        if (!empty($result))
            $result = '<ul class="list-inline team-social">' . $result . '</ul>';
        return $result;
    }

    public static function getFacebookLink($value)
    {
        if (empty($value))
            return '#';
        if (strpos($value, 'facebook.com') == 0)
            $value = 'https://www.facebook.com/' . $value;
        return $value;
    }

    public static function getFacebookChatLink($value)
    {
        if (empty($value))
            return '#';
        if (strpos($value, 'm.me') == 0)
            $value = 'https://m.me/' . $value;
        return $value;
    }

    public static function getWhatsappChatLink($phone, $text = 'Hello') {
        return "https://api.whatsapp.com/send?phone=$phone&text=$text";
    }

    //Aux function

    public static function getTwitterLink($value)
    {
        if (empty($value))
            return '#';
        if (strpos($value, 'twitter.com') == 0) {
            if (is_numeric($value))
                $value = 'https://www.twitter.com/intent/user?user_id=' . $value;
            else
                $value = 'https://www.twitter.com/' . $value;
        }

        return $value;
    }

    #cart

    public static function getLinkedInLink($value)
    {
        if (empty($value))
            return '#';
        if (strpos($value, 'linkedin.com') === false) {
            $value = 'https://www.linkedin.com/in/' . $value;
        }
        return $value;
    }

    public static function getYoutubeLink($value)
    {
        if (empty($value))
            return '#';
        if (strpos($value, 'youtube') == 0)
            $value = 'https://www.youtube.com/channel/' . $value;
        return $value;
    }

    public static function getTimeZoneArray() {
        static $regions = array(
            DateTimeZone::AFRICA,
            DateTimeZone::AMERICA,
            DateTimeZone::ANTARCTICA,
            DateTimeZone::ASIA,
            DateTimeZone::ATLANTIC,
            DateTimeZone::AUSTRALIA,
            DateTimeZone::EUROPE,
            DateTimeZone::INDIAN,
            DateTimeZone::PACIFIC,
        );

        $timezones = array();
        foreach( $regions as $region )
        {
            $timezones = array_merge( $timezones, DateTimeZone::listIdentifiers( $region ) );
        }

        $timezone_offsets = array();
        foreach( $timezones as $timezone )
        {
            $tz = new DateTimeZone($timezone);
            $timezone_offsets[$timezone] = $tz->getOffset(new \DateTime());
        }

        // sort timezone by offset
        asort($timezone_offsets);

        $timezone_list = array();
        foreach( $timezone_offsets as $timezone => $offset )
        {
            $offset_prefix = $offset < 0 ? '-' : '+';
            $offset_formatted = gmdate( 'H:i', abs($offset) );

            $pretty_offset = "UTC${offset_prefix}${offset_formatted}";

            $timezone_list[$timezone] = "(${pretty_offset}) $timezone";
        }

        return array_merge(['' => FHtml::getNullValueText()], $timezone_list);
    }

    public static function getCurrencyArray() {
        $arr = FConstant::ARRAY_CURRENCY;
        $result = [];
        foreach ($arr as $code => $name) {
            $symbol = FHtml::getCurrencySymbol($code);
            $result[$code] = "$code : $name ($symbol)";
        }
        return array_merge(['' => FHtml::getNullValueText()], $result);
    }

    public static function getCurrenciesCodeArray() {
        return static::getCurrenciesSymbolArray();
    }

    public static function getCurrenciesSymbolArray() {
        $arr = FConstant::ARRAY_CURRENCY;
        $result = [];
        foreach ($arr as $code => $name) {
            $symbol = FHtml::getCurrencySymbol($code);
            $result[$code] = $symbol;
        }
        return array_merge(['' => FHtml::getNullValueText()], $result);
    }

    public static function getLanguagesArray() {
        $result = array_merge(FConstant::ARRAY_LANG, FConstant::ARRAY_LANGUAGUES);
        return array_merge(['' => FHtml::getNullValueText()], $result);
    }

    public static function getIconsArray() {
        $arr = FConstant::ICON_ARRAYS;
        $result = [];
        foreach ($arr as $key => $value) {
            $result = array_merge($result, [$key => "<span class='$key'></span>"]); //<span class="glyphicon glyphicon-unchecked"></span>
        }
        return array_merge(['' => FHtml::getNullValueText()], $result);
    }

    public static function getArrayItemValue($array, $index = 0) {
        if (!is_array($array) || empty($array))
            return null;
        if (isset($array[$index]))
            return $array[$index];
        $i = 0;
        foreach ($array as $item => $value) {
            if ($i == $index)
                return $value;
            $i += 1;
        }
    }

    public static function createVCard($name, $birthday = null, $email = null, $phone = null, $address = null, $url = '', $categories = [], $company = '', $geo = '', $job = '', $image = '', $note = '')
    {
        require_once 'Behat-Transliterator/Transliterator.php';
        require_once 'vcard/VCard.php';

        if (is_object($name) || is_array($name)) {
            $model = $name;
            $table = is_object($model) ? FHtml::getUploadFolder($model) : 'wwww';
            $name = FHtml::getFieldValue($model, ['name', 'full_name', 'FullName']);
            $birthday = FHtml::getFieldValue($model, is_string($birthday) ? $birthday : ['dob', 'birth_date']);
            $email = FHtml::getFieldValue($model, is_string($email) ? $email : ['email', 'Email']);
            $address = FHtml::getFieldValue($model, is_string($address) ? $address : ['address', 'Address']);
            $url = FHtml::getFieldValue($model, is_string($url) ? $url : ['url', 'website']);
            $company = FHtml::getFieldValue($model, is_string($company) ? $company : ['company']);
            $geo = FHtml::getFieldValue($model, is_string($geo) ? $geo : ['geo', 'coordinate', 'location']);
            $job = FHtml::getFieldValue($model, is_string($job) ? $job : ['job', 'job_title', 'title']);
            $image = FHtml::getFieldValue($model, is_string($image) ? $image : ['image', 'avatar', 'thumbnail']);
            if (!empty($image))
                $image = FHtml::getImageUrl($image, $table);
            $note = FHtml::getFieldValue($model, is_string($note) ? $note : ['overview', 'description', 'note', 'content']);

        }

        // define vcard
        $vcardObj = new VCard();

        if (empty($name))
            $name = FHtml::settingCompanyName();

        if (empty($email))
            $email = FHtml::settingCompanyEmail();

        if (empty($address))
            $address = FHtml::settingCompanyAddress();

        if (empty($phone))
            $phone = FHtml::settingCompanyPhone();

        if (empty($url))
            $url = FHtml::settingCompanyWebsite();

        if (empty($image))
            $image = FHtml::getCurrentLogoUrl();

        if (empty($company))
            $company = FHtml::settingCompanyName();

        if (empty($note))
            $note = FHtml::settingCompanyDescription();

        // add personal data
        if (!empty($name))
            $vcardObj->addName($name);

        if (!empty($birthday))
            $vcardObj->addBirthday($birthday);

        if (!empty($email))
            $vcardObj->addEmail($email);

        if (!empty($phone))
            $vcardObj->addPhoneNumber($phone);

        if (!empty($address))
            $vcardObj->addAddress($address);

        if (!empty($categories))
            $vcardObj->addCategories($categories);

        if (!empty($url))
            $vcardObj->addURL($url);

        if (!empty($company))
            $vcardObj->addCompany($company);

        if (!empty($geo))
            $vcardObj->addGEO($geo);

        if (!empty($job))
            $vcardObj->addJobtitle($job);

        if (!empty($image))
            $vcardObj->addPhoto($image);

        if (!empty($note))
            $vcardObj->addNote($note);

        return $vcardObj;
    }

    public static function getVCardContent($name, $birthday = null, $email = null, $phone = null, $address = null, $url = '', $categories = [])
    {
        $vcard = static::createVCard($name, $birthday, $email, $phone, $address, $url, $categories);
        if (isset($vcard))
            return $vcard->getOutput();
        return '';
    }

    public static function downloadVCard($name, $birthday = null, $email = null, $phone = null, $address = null, $url = '', $categories = [])
    {
        $vcard = static::createVCard($name, $birthday, $email, $phone, $address, $url, $categories);
        if (isset($vcard)) {
            $vcard->download();
            die;
        }
    }

    public static function getVCardQRCode($name, $birthday = null, $email = null, $phone = null, $address = null, $url = '', $categories = [])
    {
        $vcard = static::createVCard($name, $birthday, $email, $phone, $address, $url, $categories);
        if (isset($vcard)) {
            return $vcard->getQRCodeURL();
        }

        return '';
    }
}