<?php
/**
 * Created by PhpStorm.
 * User: Darkness
 * Date: 11/30/2016
 * Time: 2:00 PM
 */

namespace common\components;


use common\base\BaseModelObject;
use creocoder\flysystem\FtpFilesystem;
use Imagine\Image\Box;
use League\Flysystem\Filesystem;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use yii\helpers\Html;
use yii\helpers\Json;
use Yii;
use Globals;
use common\components\Setting;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\imagine\Image;

include_once('simple_html_dom.php');

class FApi extends FNotification
{
    /**
     * API URL
     */
    const GOOGLE_TRANSLATE_API_URL = 'https://www.googleapis.com/language/translate/v2';

    const BASE_API_URL = 'admin/';

    /**
     * @return bool|mixed
     */
    public static function isAjaxRequest()
    {
        return Yii::$app->request->isAjax;
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
    public static function getCurrentPageURL($url = '')
    {
        if (empty($url))
            $url = $_SERVER["REQUEST_URI"];

        $pageURL = static::getCurrentDomain();
        $pageURL .= $url;

        return $pageURL;
    }

    public static function getCurrentDomain()
    {
        $pageURL = 'http';

        if (!empty($_SERVER['HTTPS'])) {
            if ($_SERVER['HTTPS'] == 'on') {
                $pageURL .= "s";
            }
        }

        $pageURL .= "://";

        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"];
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
        if (isset($_SERVER['HTTPS']) &&
            ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
            isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
            $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
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


    public static function createBaseAPIUrl($action, $params = [], $position = null)
    {
        if (empty($position)) {
            $position = FHtml::currentZone();
        }

        $params['language'] = FHtml::currentLang();
        $params['application_id'] = FHtml::currentApplicationId();

        return FHtml::createUrl(self::BASE_API_URL . $action, $params, $position);
    }

    /**
     * Forming query parameters
     * @param  string $method API method
     * @param  string $text Source text string
     * @param  string $source Source language
     * @param  string $target Target language
     * @return array          Data properties
     */
    public static function getGoogleAPIRequest($method, $text = '', $source = '', $target = '', $key = '')
    {
        if (empty($key)) {
            $key = FConfig::config(FConfig::SETTINGS_GOOGLE_API_KEY);
        }

        $request = self::GOOGLE_TRANSLATE_API_URL . '/' . $method . '?' . http_build_query([
                'key' => $key,
                'source' => $source,
                'target' => $target,
                'q' => Html::encode($text),
            ]);

        return $request;
    }

    /**
     * You can translate text from one language
     * to another language
     * @param string $source Source language
     * @param string $target Target language
     * @param string $text Source text string
     * @return array
     */
    public static function translate($source, $target, $text)
    {
        return self::getResponse(self::getGoogleAPIRequest('', $text, $source, $target));
    }

    /**
     * You can discover the supported languages of this API
     * @return array Array supported languages
     */
    public static function discoverLanguage()
    {
        return self::getResponse(self::getGoogleAPIRequest('languages'));
    }

    /**
     * You can detect the language of a text string
     * @param  string $text Source text string
     * @return array        Data properties
     */
    public function detectLanguage($text)
    {
        return self::getResponse(self::getGoogleAPIRequest('detect', $text));
    }

    /**
     * Getting response
     * @param string $request
     * @return array
     */
    public static function getResponse($request)
    {
        $response = '';
        if (is_string($request)) {
            $response = file_get_contents($request);
        }

        return Json::decode($response, true);
    }

    /**
     * @param       $des
     * @param array $data
     * @param bool $is_test
     */
    public static function async($des, $data = [], $is_test = false)
    {
        $url = '';
        if (filter_var($des, FILTER_VALIDATE_URL)) {
            $url = $des;
        } elseif (is_array($des)) {
            $url = Yii::$app->urlManager->createAbsoluteUrl($des);
        }
        ignore_user_abort(true); // CAUTION!  run over return
        $data = http_build_query($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //if (!$is_test) {
        curl_setopt($ch, CURLOPT_TIMEOUT, 1); // Skip receive return
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // comment when test
        //}
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        $result = curl_exec($ch);
        curl_close($ch);
        //echo $result; die;
        //return $result;
    }

    // Function to get the client IP address
    public static function currentIPAddress()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        }
        //        else if(getenv('HTTP_X_FORWARDED_FOR'))
        //            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        //        else if(getenv('HTTP_X_FORWARDED'))
        //            $ipaddress = getenv('HTTP_X_FORWARDED');
        //        else if(getenv('HTTP_FORWARDED_FOR'))
        //            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        //        else if(getenv('HTTP_FORWARDED'))
        //            $ipaddress = getenv('HTTP_FORWARDED');
        elseif (getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else {
            $ipaddress = 'UNKNOWN';
        }
        if ($ipaddress == '::1') {
            $ipaddress = 'localhost';
        }

        return $ipaddress;
    }

    public static function sendPushNotification($message, $user_id = 0, $action = [], $params = [])
    {
        $url = [
            'api/utility/pushNotification',
            'user_id' => $user_id,
            'msg' => $message,
            'action' => $action,
            'params' => $params,
        ];
        FHtml::async($url);
    }

    public static function getOutputDenied($message = '')
    {
        return FApi::getOutputForAPI('', FConstant::ERROR, !empty($message) ? $message : FError::ACCESS_DENIED, ['code' => 201]);
    }

    public static function getOutputDataNotFound($message = '')
    {
        return FApi::getOutputForAPI('', FConstant::ERROR, !empty($message) ? $message : FError::DATA_NOT_FOUND, ['code' => 201]);
    }

    public static function getOutputMissingParam($message = '')
    {
        return FApi::getOutputForAPI('', FConstant::ERROR, !empty($message) ? $message : FError::MISSING_PARAMS, ['code' => 201]);
    }

    public static function getOutputInvalidToken($message = '')
    {
        return FApi::getOutputForAPI('', FConstant::ERROR, !empty($message) ? $message : FError::INVALID_FOOTPRINT, ['code' => 201]);
    }

    /**
     * @param        $data
     * @param string $status
     * @param string $message
     * @param array $dataParam
     * @param null $totalPage
     * @param null $pageSize
     * @param null $pageIndex
     * @return array
     */
    public static function getOutputForAPI($data, $status = '', $message = '', $dataParam = [], $totalPage = null, $pageSize = null, $pageIndex = null)
    {
        $out = array();
        $code = FError::ERROR_OK;
        if (!isset($status)) { //if only pass data then auto detect $status and $code
            if (!isset($data)) {
                $status = FError::FAIL;
                $code = FError::ERROR_NOT_FOUND;
                $message = static::getErrorMsg($code);
            } else if (empty($data)) {
                $status = FError::SUCCESS;
                $code = FError::ERROR_NOT_FOUND;
                $message = static::getErrorMsg($code);
            } else if (is_string($data)) {
                $status = FError::FAIL;
                $code = FError::ERROR_INVALID_PARAMS;
                $message = $data;
            } else if (is_array($data)) {
                $status = isset($data['status']) ? $data['status'] : FError::SUCCESS;
                $code = isset($data['code']) ? $data['code'] : FError::ERROR_OK;
                $message = isset($data['message']) ? $data['message'] : '';
            }
        }

        if (is_object($data) && $data instanceof BaseModelObject) {
            if (!isset($data)) {
                $status = FError::SUCCESS;
                $code = FError::ERROR_NOT_FOUND;
                $message = static::getErrorMsg($code);
            } else {
                if (empty($message))
                    $message = $data->getInnerMessage();
                if (empty($status)) {
                    if (!empty($data->getErrors())) {
                        $status = FError::FAIL;
                        $code = FError::ERROR_INVALID_PARAMS;
                    } else {
                        $status = FError::SUCCESS;
                        $code = FError::ERROR_OK;
                    }
                }
            }
        }

//        if (empty($data))
//            $data = null;

        if (empty($status))
            $status = FError::SUCCESS;

        if (empty($code))
            $status = FError::ERROR_OK;

        if (empty($message))
            $message = static::getErrorMsg($code);

        $out['status'] = $status;
        $out['data'] = $data;
        $out['code'] = (int)$code;

        if (isset($totalPage)) {
            $out['total_page'] = $totalPage;
        }
        if (isset($pageSize)) {
            $out['page_size'] = $pageSize;
        }
        if (isset($pageIndex)) {
            $out['page_index'] = $pageIndex;
        }
        if (!empty($dataParam) && is_array($dataParam)) {
            foreach ($dataParam as $key => $value) {
                $out[$key] = $value;
            }
        }
        $out['message'] = $message;

        $out['application_id'] = FHtml::currentApplicationId();
        $out['language'] = FHtml::defaultLang();

        return $out;
    }

    public static function getOutputForLookup($search_object, $search_field = 'name', $q = null, $id = null)
    {
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q) || !empty($q)) {
            $data = FHtml::getArray('@' . $search_object, $search_object, '', true, '', ['like', $search_field, $q]);
            $out['results'] = array_values($data);
        } elseif (is_null($q) || empty($q)) {
            $data = FHtml::getArray('@' . $search_object, $search_object, '', true, '', []);
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $model = self::getModel($search_object, '', $id);
            $out['results'] = ['id' => $id, 'text' => FHtml::getFieldValue($model, ['name', 'title'])];
        }

        return $out;
    }

    public static function crawlLinksInUrl($url)
    {
        $html = self::loadHtmlFromUrl($url);

        $dom = new \DOMDocument;

        @$dom->loadHTML($html);
        $result = [];
        foreach ($dom->getElementsByTagName('a') as $link) {
            $result[] = $link->getAttribute('href');
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
            elseif ($outOfQuotes && ($char === '}' || $char === ']')) {
                $result .= $newLine;
                $pos--;
                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            } // eat all non-essential whitespace in the input as we do our own here and it would only mess up our process
            elseif ($outOfQuotes && false !== strpos(" \t\r\n", $char)) {
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
            elseif ($outOfQuotes && ($char === ',' || $char === '{' || $char === '[')) {
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

    public static function searchGoogles($keyword)
    {
        $url = 'http://www.google.com/search?q=' . str_replace(' ', '+', $keyword);
        $html = FHtml::getHtmlDom($url);

        $linkObjs = $html->find('h3.r a');
        $result = [];
        foreach ($linkObjs as $linkObj) {
            $title = trim($linkObj->plaintext);
            $link = trim($linkObj->href);

            // if it is not a direct link but url reference found inside it, then extract
            if (!preg_match('/^https?/', $link) && preg_match('/q=(.+)&amp;sa=/U', $link, $matches) && preg_match('/^https?/', $matches[1])) {
                $link = $matches[1];
            } elseif (!preg_match('/^https?/', $link)) { // skip if it is not a valid link
                continue;
            }

            $result = array_merge($result, [$title => $link]);
        }

        return $result;
    }

    public static function getHtmlDom($url, $use_include_path = false, $context = null, $offset = -1, $maxLen = -1, $lowercase = true, $forceTagsClosed = true, $target_charset = DEFAULT_TARGET_CHARSET, $stripRN = true, $defaultBRText = DEFAULT_BR_TEXT, $defaultSpanText = DEFAULT_SPAN_TEXT)
    {
        if (strpos($url, '<') !== false && strpos($url, '>') !== false) {
            return str_get_html($url);
        }

        $url = self::getFullURL($url);

        return file_get_html($url, $use_include_path, $context, $offset, $maxLen, $lowercase, $forceTagsClosed, $target_charset, $stripRN, $defaultBRText, $defaultSpanText);

        //        if (self::checkURL($url)) {
        //            return file_get_html($url, $use_include_path, $context, $offset, $maxLen, $lowercase, $forceTagsClosed, $target_charset, $stripRN, $defaultBRText, $defaultSpanText);
        //        } else
        //            return null;
    }

    public static function getFullURL($url)
    {
        if (StringHelper::startsWith($url, 'http')) {
            return $url;
        }

        if (StringHelper::startsWith($url, '/')) {
            $url = self::currentBaseURL() . $url;
        } else if (strpos($url, '.') === false) {
            $url = self::currentRootUrl() . self::createUrl($url);
        }

        if (!StringHelper::startsWith($url, 'http')) {
            $url = self::currentProtocol() . $url;
        }

        return $url;
    }

    public static function checkURL($url, $timeout = 2)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout); // drop connection after xxx seconds

        $result = curl_exec($curl);
        if ($result !== false) {
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($statusCode == 404) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public static function searchPlace($keyword)
    {
        if (empty($keyword)) {
            $q = FHtml::getRequestParam(['q', 'search', 'place']);
        } else {
            $q = $keyword;
        }

        $url = 'https://www.google.com/search?q=' . str_replace(' ', '+', $q);

        $html = FHtml::getHtmlDom($url);
        $a = $html->find('span._tA');
        $result = [];
        //$result = array_merge($result, ['search url' => $url]);

        $i = 0;
        foreach ($a as $a1) {
            $i = $i + 1;
            $text = trim($a1->plaintext);
            if (strlen($text) > 50 && $i == 1) {
                $result = array_merge($result, ['address' => $text]);
                continue;
            }
            if (StringHelper::startsWith($text, '+')) {
                $result = array_merge($result, ['tel' => $text]);
                continue;
            }
            if (strpos($text, ':') > 0) {
                $result = array_merge($result, ['hours' => $text]);
                continue;
            }
        }
        $a = $html->find('div._IGf a.fl');
        foreach ($a as $a1) {
            $i = $i + 1;
            $text = trim($a1->href);
            if (StringHelper::startsWith($text, '/url?q=')) {
                $result = array_merge($result, ['website' => substr($text, 7, strpos($text, '/', strpos($text, '://') + 3) - 7)]);
                continue;
            }
        }

        return $result;
    }

    public static function loadJsonFromUrl($url, $array_key = '', $params = [])
    {
        $output = static::loadHtmlFromUrl($url);

        $result = FHtml::decode($output);

        if (is_array($result) && !empty($result)) {
            if (ArrayHelper::isIndexed($result) && count($result) == 1) {
                return $result[0];
            } elseif (!empty($array_key) && is_string($array_key) && key_exists($array_key, $result)) {
                return $result[$array_key];
            }
        }

        return $result;
    }

    public static function loadHtmlFromUrl($url, $params = [])
    {
        $ch = curl_init();
        $timeout = 5;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

        if (StringHelper::startsWith($url, 'https')) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        $html = curl_exec($ch);

        curl_close($ch);

        return $html;
    }

    public static function getHtmlFromUrl($url, $params = [])
    {
        return static::loadHtmlFromUrl($url, $params);
    }

    public static function getJsonFromUrl($url, $array_key = '', $params = [])
    {
        return static::loadJsonFromUrl($url, $array_key, $params);
    }

    public static function getHtmlContent($url)
    {
        $html = self::getHtmlDom($url);
        if (!isset($html)) {
            return $html->outertext;
        } else {
            return '';
        }
    }

    public static function getUrlContent($url, $get_raw_content = true, $array_key = '', $params = [])
    {
        $output = FApi::loadHtmlFromUrl($url);
        $result = FHtml::decode($output);
        return $result;
        if ($get_raw_content) {
            return static::loadHtmlFromUrl($url, $params);
        } else {
            return static::loadJsonFromUrl($url, $array_key, $params);
        }
    }

    public static function downloadContent($content, $params = null)
    {
        ob_end_clean();
        $content = ob_get_clean();

        ob_start();
        if (is_string($content)) {
            if (StringHelper::startsWith($content, 'http')) {
                $content = static::loadHtmlFromUrl($content, $params);
            }
            echo $content;
        } elseif (is_callable($content)) {
            call_user_func($content, $params);
        }

        ob_end_flush();
        exit();
    }

    public static function getHtmlLinks($url)
    {
        $html = self::getHtmlDom($url);
        $results = [];

        foreach ($html->find('a') as $element) {
            $results[] = $element->href;
        }

        return $results;
    }

    public static function getHtmlImages($url)
    {
        $html = self::getHtmlDom($url);
        $results = [];

        foreach ($html->find('img') as $element) {
            $results[] = $element->src;
        }

        return $results;
    }

    public static function getHtmlElements($url, $search, $plaintext = true)
    {
        $html = self::getHtmlDom($url);
        $results = [];

        foreach ($html->find($search) as $element) {
            $results[] = $plaintext ? $element->plaintext : $element->innertext;
        }

        return $results;
    }


    public static function createLink($url, $params = [], $position = BACKEND, $label = '...', $target = '_blank', $css = 'btn btn-xs btn-default')
    {
        $url = self::createUrl($url, $params, $position);

        return '<a data-pjax=0 href="' . $url . '" target="' . $target . '" class="hidden-print ' . $css . '">' . $label . '</a><span class="visible-print">' . $label . '</span> ';
    }

    public static function createFormalizedBackendLink($url, $position = BACKEND, $folder = BACKEND_URL_FOLDER)
    {
        $url = str_replace('//', '/', $url);

        if ($position == BACKEND) {

            if (Yii::$app->urlManager->showScriptName != true && $position == BACKEND && REQUIRED_INDEX_PHP == true && !StringHelper::startsWith($url, 'index.php/')) {
                $url = 'index.php/' . $url;
            }
        } else {
            if (strpos($url, 'backend/web') === false) {
                $url = str_replace('index.php', 'backend/web/index.php', $url);
            }
        }

        if (!empty($folder) && strpos($url, 'backend/web')) {
            $url = str_replace('backend/web/index.php', $folder, $url);
        }

        $url = str_replace(':/', '://', $url);

        return $url;
    }

    public static function createModelUrl($table, $action, $params = [], $position = BACKEND, $module = '', $controller = '')
    {
        $module = empty($module) ? FHtml::getModelModule($table) : $module;
        $controller = empty($controller) ? str_replace('_', '-', $table) : $controller;

        return self::createUrl("$module/$controller/$action", $params, $position);
    }

    //2017/4/25
    public static function createFullUrl($url, $params = [])
    {
        $url = static::createUrl($url, $params, BACKEND, true);
        return $url;
    }

    public static function createUrl($url, $params = [], $position = BACKEND, $full_url = false)
    {
        if (is_array($url)) {

            if ($position == BACKEND) {
                $result = Yii::$app->urlManager->createUrl(ArrayHelper::merge($url, $params));
            } else {
                $result = Yii::$app->urlManagerBackend->createUrl(ArrayHelper::merge($url, $params));
            }

            $result = FHtml::createFormalizedBackendLink($result, $position);

            if ($full_url)
                $result = FHtml::getCurrentDomain() . $result;

            return $result;
        }

        if (empty($url) || $url == '#') {
            return '#';
        }

        if (StringHelper::startsWith($url, 'http')) {
            return $url;
        }

        if (StringHelper::startsWith($url, 'www')) {
            return self::currentProtocol() . $url;
        }

        $module = FHtml::currentModule();
        if (empty($module)) {
            $url = str_replace('{module}/', '', $url);
        } else {
            $url = str_replace('{module}', FHtml::currentModule(), $url);
        }

        $url = str_replace('{controller}', FHtml::currentController(), $url);
        $url = str_replace('{action}', FHtml::currentAction(), $url);
        $url = str_replace('{domain}', FHtml::currentDomain(), $url);
        $url = str_replace('{site}', FHtml::currentApplicationId(), $url);
        $url = str_replace('{application}', FHtml::currentApplicationId(), $url);

        if (is_array($params)) {
            foreach ($params as $key => $value) {
                if (!empty($url) && strpos($url, '{' . $key . '}') !== false) {
                    unset($params[$key]);
                    $url = str_replace('{' . $key . '}', self::is_numeric($value) ? $value : FHtml::getURLFriendlyName($value), $url);
                }

            }
        }

        if (!isset($params)) {
            $params = [];
        }

        if ($position == BACKEND) {
            $result = Yii::$app->urlManager->createUrl(ArrayHelper::merge([$url], $params));
        } else {
            $result = Yii::$app->urlManagerBackend->createUrl(ArrayHelper::merge([$url], $params));
        }

        $result = FHtml::createFormalizedBackendLink($result);

        if ($full_url)
            $result = FHtml::getCurrentDomain() . $result;

        return $result;
    }


    public static function createModuleUrl($module, $url, $params = [], $position = BACKEND)
    {
        if (!empty($module)) {
            $url = '/' . $module . '/' . $url;
        }
        $url = str_replace('//', '/', $url);

        return self::createUrl($url, $params, $position);
    }

    public static function getHomeUrl()
    {
        return static::createUrl('/');
    }

    public static function createBackendUrl($object_type, $params = null)
    {
        $module = FHtml::getModelModule($object_type);
        if (!empty($module)) {
            $module = $module . '/';
        }
        $controller = str_replace('_', '-', $object_type);
        $url = FHtml::createUrl($module . $controller . '/index', $params, FRONTEND);
        $url = FHtml::createFormalizedBackendLink($url);

        return $url;
    }

    public static function createBackendActionUrl($url, $excluded_params = ['id', 'form_enabled'])
    {
        $params = self::RequestParams($excluded_params);
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $url = array_merge($url, [$key => $value]);
            }
        }
        $url = Url::to($url);

        $url = FHtml::createFormalizedBackendLink($url);

        return $url;
    }

    public static function makeModelLinkUrl($item, $link_url, $included_params = ['id', 'name', 'category_id'], $params = [])
    {
        foreach ($included_params as $param) {
            if (strpos($link_url, "{$param}") !== false) {
                $link_url = str_replace("{{$param}}", FHtml::getFieldValue($item, $param), $link_url);
            }
        }
        $linkurl = FHtml::getFieldValue($item, ['linkurl', 'website']);
        $linkurl = empty($linkurl) ? FHtml::createUrl($link_url, $params) : $linkurl;

        return $linkurl;
    }

    public static function toSEOFriendlyString($str, $replace = array(), $delimiter = '-')
    {
        if (!empty($replace)) {
            $str = str_replace((array)$replace, ' ', $str);
        }

        $str = self::cleanStringforSEO($str);

        $str = self::cleanString($str);

        return $str;
    }

    public static function getImageUrl($image, $model_dir = false)
    {
        if (is_object($image)) {
            $image = FHtml::getFieldValue($image, FHtml::FIELDS_IMAGES);
        }

        if (!is_array($image)) {
            $images = [$image];
        } else {
            $images = $image;
        }

        foreach ($images as $image) {
            $image = strtolower($image);
            $result = FHtml::getFileUrl($image, $model_dir, \Globals::NO_IMAGE);
            return $result;

            if (self::is_image($result)) {
                return $result;
            }
        }

        return '';
    }

    /**
     * Returns an string clean of UTF8 characters. It will convert them to a similar ASCII character
     * www.unexpectedit.com
     */
    public static function cleanStringforSEO($text)
    {
        return seo_friendly($text);
    }

    public static function getUrlInfo($url, $module_name = '')
    {
        return static::parseUrl($url, $module_name);
    }

    public static function parseUrl($url, $module_name = '')
    {
        $url_arr = explode('/', $url);
        $action = count($url_arr) > 1 ? $url_arr[count($url_arr) - 1] : '';
        $l = strpos($action, '?');
        if ($l > 0) {
            $action = substr($action, 0, $l);
        }

        $controller = count($url_arr) > 2 ? $url_arr[count($url_arr) - 2] : '';
        $module = count($url_arr) > 3 ? $url_arr[count($url_arr) - 3] : '';
        $active = $module == FHtml::currentModule() && $controller == FHtml::currentController() && $action == FHtml::currentAction();

        $array = ['module' => $module, 'controller' => $controller, 'action' => $action, 'active' => $active, 'module_name' => BaseInflector::camel2words($module)];
        $array1 = parse_url($url);
        if (isset($array1['path'])) {
            $array1['dirname'] = dirname($array1['path']);  //                      🡺 /myfolder
            $array1['filename'] = basename($array1['path']);      //                      🡺 sympony.mp3
            $array1['extension'] = pathinfo($array1['path'], PATHINFO_EXTENSION);
        }

        return array_merge($array, $array1);
    }

    public static function getImageUrlForAPI($image, $folder, $mode = "render")
    { //mode: render / direct

        if (filter_var($image, FILTER_VALIDATE_URL)) {
            $image_url = $image;
        } else {
            if ($mode == 'render') {
                $image_url = Yii::$app->urlManager->createAbsoluteUrl(['api/file', 'f' => $image, 'd' => $folder, 's' => '', 't' => 'image']);
            } else {
                $image_url = FHtml::getImageUrl($image, $folder);
            }
        }
        return $image_url;
    }

    public static function getFileURLForAPI($file, $folder = '', $mode = "render") //mode: render / direct
    {
        if (filter_var($file, FILTER_VALIDATE_URL)) {
            $file_url = $file;
        } else {
            $file_url = FHtml::getFileURL($file, $folder);
            if (strlen($file_url) != 0) {
                if ($mode == "render") {
                    $file_url = Yii::$app->urlManager->createAbsoluteUrl(['api/file', 'f' => $file, 'd' => $folder, 's' => '', 't' => 'file']);
                }
            }
        }
        return $file_url;
    }

    /**
     * @param        $file_upload
     * @param        $save_path
     * @param string $old_file
     * @return string
     */
    public static function uploadFileAPI($file_upload, $save_path, $old_file = '')
    {
        $file_name = "";
        if (isset($_FILES[$file_upload])) {
            $ext = pathinfo($_FILES[$file_upload]['name'], PATHINFO_EXTENSION);
            $now = time();
            $imageName = $now . $file_upload . "." . $ext;
            $image_path = $save_path . $imageName;
            $upload = move_uploaded_file($_FILES[$file_upload]['tmp_name'], $image_path);
            if ($upload) {
                $file_name = $imageName;
                Image::getImagine()->open($image_path)->thumbnail(new Box(300, 300))->save($save_path . 'thumb' . $imageName, ['quality' => 100]);
            } else {
                if (strlen($old_file) != 0) {
                    $file_name = $old_file;
                }
            }
        } else {
            if (strlen($old_file) != 0) {
                $file_name = $old_file;
            }
        }

        return $file_name;
    }

    public static function deleteFileAPI($file_name, $save_path)
    {
        if (strlen($file_name) != 0) {
            if (is_file($save_path . '/' . $file_name)) {
                unlink($save_path . '/' . $file_name);
            }
            if (is_file($save_path . '/thumb' . $file_name)) {
                unlink($save_path . '/thumb' . $file_name);
            }
        }
    }

    /**
     * @param string $url
     * @param array $params
     * @return mixed
     */
    public static function callApi($url, $params = [])
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $url = Yii::$app->urlManager->createAbsoluteUrl([$url]);
        }

        $ch = curl_init();
        $params = http_build_query($params);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // comment when test
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)');
        //curl_setopt($ch, CURLOPT_TIMEOUT, 1); // Skip receive return
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded"));

        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result);

        return $result;
    }
}