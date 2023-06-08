<?php

namespace backend\modules\smartscreen;

use backend\models\AuthMenu;
use backend\models\User;
use backend\modules\smartscreen\actions\SmartscreenSchedulesAction;
use backend\modules\smartscreen\models\SmartscreenCampaigns;
use backend\modules\smartscreen\models\SmartscreenChannels;
use backend\modules\smartscreen\models\SmartscreenContent;
use backend\modules\smartscreen\models\SmartscreenContentAPI;
use backend\modules\smartscreen\models\SmartscreenFile;
use backend\modules\smartscreen\models\SmartscreenFileAPI;
use backend\modules\smartscreen\models\SmartscreenLayouts;
use backend\modules\smartscreen\models\SmartscreenQueue;
use backend\modules\smartscreen\models\SmartscreenSchedules;
use backend\modules\smartscreen\models\SmartscreenScripts;
use backend\modules\smartscreen\models\SmartscreenStation;
use backend\modules\smartscreen\models\SmartscreenStationAPI;
use common\components\FApi;
use common\components\FConfig;
use common\components\FConstant;
use common\components\FFile;
use common\components\FHtml;
use common\components\FModel;
use common\components\FSecurity;
use common\components\NodeJS;
use common\widgets\FUploadedFile;
use yii\base\Exception;
use yii\base\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\StringHelper;
use backend\modules\smartscreen\models\SmartscreenSchedulesAPI;
use yii\jui\Selectable;

require FHtml::getRootFolder() . '/node/node.php';


/**
 * api module definition class
 */
class Smartscreen extends Module
{
    const REFRESH_SCHEDULE = 'refreshSchedule';
    const REFRESH_SCHEDULE_NOW = 'refreshScheduleNow';

    const STATUS_WAITING = FHtml::STATUS_NEW;
    const STATUS_NEXT = FHtml::STATUS_PROCESSING;
    const BASIC_CONTENT_TYPE = ['slide', 'video', 'audio', 'news', 'text', 'image', 'html', 'url'];
    const EMPTY_TEXT = '.......';
    const DEVICE_DEFAULT_STATUS = 0;
    const SCHEDULE_TYPE_ADVERTISE = 'advertise';
    const SCHEDULE_TYPE_BASIC = 'basic';
    const SCHEDULE_TYPE_ADVANCE = 'advance';
    const SCHEDULE_TYPE_CAMPAIGN = 'campaign';

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const LOOKUP = [    // 'table.column' => array(), 'table.column' => 'table1.column1'
        'smartscreen_frame.contentLayout' => ['header', 'side', 'main', 'footer'],
        'smartscreen_queue.status' => [\backend\modules\smartscreen\Smartscreen::STATUS_WAITING, \backend\modules\smartscreen\Smartscreen::STATUS_NEXT],
        'smartscreen_queue.service_id' => '@qms_services',
        'smartscreen_queue.device_id' => '@smartscreen_station',

        'smartscreen_calendar.device_id' => '@smartscreen_station',
        'smartscreen_calendar.recurring_type' => ['daily', 'weekly'],
        'smartscreen_calendar.type' => ['patient', 'doctor'],
        'smartscreen_schedules.type' => [self::SCHEDULE_TYPE_BASIC, self::SCHEDULE_TYPE_ADVANCE],
        //'smartscreen_file.command' => [SmartscreenScripts::COMMAND_DSPTEXT => 'text', SmartscreenScripts::COMMAND_DSPCLIP => 'video/image', SmartscreenScripts::COMMAND_DSPIE => 'url', SmartscreenScripts::COMMAND_LOOP => 'return']
        'smartscreen_file.command' => [[SmartscreenContent::TYPE_IMAGE => 'image', SmartscreenContent::TYPE_VIDEO => 'video', SmartscreenContent::TYPE_URL => 'url', SmartscreenContent::TYPE_TEXT => 'text']]
    ];

    const SETTINGS_SCHEDULE_ALLOW_OVERLAP = true; // true -> autoFixTimes = false, false -> autoFixTimes = false
    const SETTINGS_SCHEDULE_ALLOW_INTERSECTED = true;

    public $controllerNamespace = 'backend\modules\smartscreen\controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    public static function createModuleMenu($menu = ['smartscreen-campaigns', 'smartscreen-schedules', 'smartscreen-channels', 'smartscreen-layouts', 'smartscreen-frame', 'smartscreen-content', 'smartscreen-scripts', 'smartscreen-station', 'smartscreen-queue',  'smartscreen-calendar', 'settings'], $title = 'Screens')
    {
        $controller = FHtml::currentController();
        $today = FHtml::Today();

        $menu1[] = AuthMenu::menuItem(
            '#',
            $title,
            'glyphicon glyphicon-cog',
            FHtml::isInArray($controller, $menu),
            [User::ROLE_ADMIN, User::ROLE_MANAGER],
            [
                !FHtml::isInArray('smartscreen-campaigns', $menu) ? null : AuthMenu::menuItem(
                    '/smartscreen/smartscreen-campaigns/index',
                    'Campaigns',
                    'glyphicon glyphicon-wrench',
                    $controller == 'smartscreen-campaigns',
                    [User::ROLE_ADMIN, User::ROLE_MANAGER, User::ROLE_USER]
                ),

                !FHtml::isInArray('smartscreen-schedules', $menu) ? null : AuthMenu::menuItem(
                    '/smartscreen/smartscreen-schedules/index?date=' . $today,
                    'Schedules',
                    'glyphicon glyphicon-wrench',
                    $controller == 'smartscreen-schedules',
                    [User::ROLE_ADMIN, User::ROLE_MANAGER, User::ROLE_USER]
                ),
                !FHtml::isInArray('smartscreen-layouts', $menu) ? null : AuthMenu::menuItem(
                    '/smartscreen/smartscreen-layouts/index',
                    'Layouts',
                    'glyphicon glyphicon-wrench',
                    $controller == 'smartscreen-layouts',
                    [User::ROLE_ADMIN]
                ),
                !FHtml::isInArray('smartscreen-frame', $menu) ? null : AuthMenu::menuItem(
                    '/smartscreen/smartscreen-frame/index',
                    'Frames',
                    'glyphicon glyphicon-wrench',
                    $controller == 'smartscreen-frame',
                    [User::ROLE_ADMIN]
                ),
                !FHtml::isInArray('smartscreen-content', $menu) ? null : AuthMenu::menuItem(
                    '/smartscreen/smartscreen-content/index',
                    'Content',
                    'glyphicon glyphicon-wrench',
                    $controller == 'smartscreen-content',
                    [User::ROLE_ADMIN, User::ROLE_MANAGER, User::ROLE_USER]
                ),
                !FHtml::isInArray('smartscreen-channels', $menu) ? null : AuthMenu::menuItem(
                    '/smartscreen/smartscreen-channels/index',
                    'Channels',
                    'glyphicon glyphicon-wrench',
                    $controller == 'smartscreen-channels',
                    [User::ROLE_ADMIN]
                ),
                !FHtml::isInArray('smartscreen-station', $menu) ? null : AuthMenu::menuItem(
                    '/smartscreen/smartscreen-station/index',
                    'Stations',
                    'glyphicon glyphicon-wrench',
                    $controller == 'smartscreen-station',
                    [User::ROLE_ADMIN]
                ),
                !FHtml::isInArray('smartscreen-queue', $menu) ? null : AuthMenu::menuItem(
                    '/smartscreen/smartscreen-queue/index',
                    'Queue List',
                    'glyphicon glyphicon-wrench',
                    $controller == 'smartscreen-queue',
                    [User::ROLE_ADMIN]
                ),
                !FHtml::isInArray('smartscreen-calendar', $menu) ? null : AuthMenu::menuItem(
                    '/smartscreen/smartscreen-calendar/index',
                    'Calendars List',
                    'glyphicon glyphicon-wrench',
                    $controller == 'smartscreen-calendar',
                    [User::ROLE_ADMIN]
                ),
                !FHtml::isInArray('settings', $menu) ? null : AuthMenu::menuItem(
                    '/smartscreen/settings/index',
                    'Settings',
                    'glyphicon glyphicon-wrench',
                    $controller == 'settings',
                    [User::ROLE_ADMIN]
                ),
            ]
        );

        return $menu1;
    }

    public static function countFields($model, $fields)
    {
        $num = 0;
        $result = [];

        if (is_string($fields)) {
            if (StringHelper::endsWith($fields, '$i')) {
                $result1 = '';
                for ($i = 1; $i < 20; $i++) {
                    $field_name = str_replace('$i', $i, $fields);
                    $field_value = FHtml::getFieldValue($model, $field_name);
                    if (!empty($field_value)) {
                        $num += 1;
                    }
                }
            }
        }

        return $num;
    }

    public static function showXml($model, $groups = [], $root = '', $default_values = [])
    {
        $result = '';
        foreach ($groups as $group => $fields) {
            $result1 = '';

            if (is_string($group)) {
                if (is_array($fields)) {
                    $result1 .= self::showXml($model, $fields, '', $default_values);
                    $result .= "\t<$group>\n$result1\n\t</$group>\n";
                } else if (is_string($fields)) {
                    $result1 = $fields;
                    $result .= "\t<$group>\n$result1\n\t</$group>\n";
                } else if (empty($fields)) {
                    $result1 = FHtml::getFieldValue($model, $group);
                    $result .= "\t<$group>$result1</$group>\n";
                }
            } else if (is_numeric($group) && is_string($fields)) {
                if (StringHelper::endsWith($fields, '$i')) {
                    $result1 = '';
                    for ($i = 1; $i < 20; $i++) {
                        $field_name = str_replace('$i', $i, $fields);
                        $field_value = FHtml::getFieldValue($model, $field_name);
                        if (!empty($field_value)) {
                            $result1 .= "\t\t<$field_name>$field_value</$field_name>\n";
                        }
                    }
                    $result .= "$result1";
                } else {

                    $field_name = $fields;

                    $field_value = FHtml::getFieldValue($model, $field_name);
                    if (empty($field_value) && !empty($default_values) && key_exists($fields, $default_values))
                        $field_value = $default_values[$fields];
                    if ($field_value === true)
                        $field_value = 'True';
                    else if ($field_value === 0)
                        $field_value = 'False';

                    $result .= "\t\t<$field_name>$field_value</$field_name>\n";
                }
            }
        }

        if (!empty($root))
            $result = "<$root>\n$result\n</$root>";

        return $result;
    }

    public static function showXmlSmartScreenScript($model)
    {
        return Smartscreen::showXml($model, [
            'ReleaseDate' => null,
            'WINDOWFORM' => ['Logo', 'TopBanner', 'BotBanner', 'ClipFooter', 'ScrollText'],
            'CLIP' => ['Clipnum', 'Clip$i'],
            'COMMAND' => ['CommandNumber', 'Line$i']
        ], 'SmartScreen');
    }

    public static function buildSmartScreenScriptFromSchedules($schedules, $name = '')
    {
        $result = $schedules;

        $files = Smartscreen::getDeviceScheduleFiles($result, true);

        $scriptModel = null;
        if (!empty($name)) {
            $scriptModel = SmartscreenScripts::findOne(['name' => $name]);
        }

        if (!isset($scriptModel)) {
            $scriptModel = new SmartscreenScripts();
            $scriptModel->ReleaseDate = FHtml::Now();
        }

        $scriptModel->Logo = FHtml::getCurrentLogo();
        $scriptModel->TopBanner = '';
        $scriptModel->BotBanner = '';
        $scriptModel->ClipFooter = '';
        $scriptModel->ClipHeader = '';

        $text_content = SmartscreenContent::getOne(['type' => 'text']);

        $scriptModel->ScrollText = isset($text_content) ? $text_content->description : '';

        $j = 0;
        foreach ($files as $i => $file) {
            if (is_array($file['url']))
                $file = $file['url'];

            $j = $i + 1;
            FHtml::setFieldValue($scriptModel, "Clip{$j}", $file['url']);
            FHtml::setFieldValue($scriptModel, "Line{$j}", Smartscreen::getScriptCommandLine(SmartscreenScripts::COMMAND_DSPCLIP, $j, 'w', $file['duration']));
        }
        $scriptModel->Clipnum = $j;
        $scriptModel->CommandNumber = $j;

        return $scriptModel;
    }

    //FApi::getOutputForAPI('', FConstant::ERROR, FConstant::MISSING_PARAMS, ['code' => 205]);
    public static function getOutputXML($result, $title, $message, $params = [])
    {
        return "$title$$message$" . implode(',', $params);
    }

    public static function getScriptCommandLine($command, $index, $mode, $duration)
    {
        if ($command == SmartscreenScripts::COMMAND_DSPTEXT) {
            $field_value = "$command\${$index}\${$duration}";
        } else if ($command == SmartscreenScripts::COMMAND_DSPBASICFORM) {
            $field_value = "$command\${$duration}";
        } else if ($command == SmartscreenScripts::COMMAND_DSPCLIP) {
            $field_value = "$command\${$index}\${$mode}\${$duration}";
        } else if ($command == SmartscreenScripts::COMMAND_DSPIE) {
            $field_value = "$command\${$index}\${$duration}";
        } else if ($command == SmartscreenScripts::COMMAND_LOOP) {
            $field_value = "$command\${$index}\${$duration}";
        } else {
            $field_value = '';
        }

        return $field_value;
    }

    public static function getScriptCommandLineArray($field_value)
    {
        $field_value = str_replace(SmartscreenScripts::COMMAND_DSPTEXT, 'DSPTEXT', $field_value); // delete $ in DSPTEXT before explode

        $arr = explode('$', $field_value);
        $command = '';
        $duration = '';
        $index = '';
        $mode = '';
        if (!empty($arr)) {
            $command = $arr[0];
            if ($command == SmartscreenScripts::COMMAND_DSPTEXT || $command == 'DSPTEXT') {
                $command = SmartscreenScripts::COMMAND_DSPTEXT;
                $index = $arr[1];
                $duration = $arr[2];
            } else if ($command == SmartscreenScripts::COMMAND_DSPBASICFORM) {
                $duration = $arr[1];
                $field_value = "$command\${$duration}";
            } else if ($command == SmartscreenScripts::COMMAND_DSPCLIP) {
                $index = $arr[1];
                $mode = $arr[2];
                if (empty($mode))
                    $mode = 'w';

                $duration = $arr[3];
            } else if ($command == SmartscreenScripts::COMMAND_DSPIE) {
                $index = $arr[1];
                $duration = $arr[2];
            } else if ($command == SmartscreenScripts::COMMAND_LOOP) {
                $index = $arr[1];
                $duration = $arr[2];
            } else {
                $field_value = '';
            }
        }

        return ['command' => $command, 'duration' => $duration, 'index' => $index, 'mode' => $mode];
    }

    public static function generateHash($arr, $secret_key = SECRET_KEY)
    {
        $arr = array_merge($arr, [$secret_key]);
        $str = implode(",", $arr);
        $sha1 = sha1($str, true);
        return bin2hex($sha1);
    }

    public static function checkHash($hash, $arr)
    {
        $sha1 = self::generateHash($arr, SECRET_KEY);
        if ($sha1 == $hash)
            return true;
        else
            return false;
    }

    public static function checkFootPrint($hash, $time, $arr, $max_duration = FOOTPRINT_TIME_LIMIT)
    {
        $time_value = strtotime($time);
        $duration = FHtml::time() - $time_value;

        if ($duration < 0 || $duration > $max_duration)
            return 'Fasle: Expired Footprint';

        if (!self::checkHash($hash, $arr))
            return 'False: Invalid Footprint';

        return '';
    }

    public static function buildScriptLink($station = 'station1')
    {
        $station = FHtml::getRequestParam('ScreenName', $station);
        $time = FHtml::date('Ymd His');
        $footprint = self::generateHash([$station, $time]);
        $url = FHtml::createUrl('smartscreen/smartscreen-scripts/check-update', ['ScreenName' => $station, 'Time' => $time, 'FootPrint' => $footprint],  true);
        return "<a href='$url' target='_blank'>$url</a>";
    }

    public static function showFramePreview($model, $style = 'background-color: #666;width: 100%;height:200px;position: relative;', $width = '', $height = '', $left = '', $top = '', $background = '', $text = '')
    {
        if (isset($model)) {
            $width = FHtml::getFieldValue($model, ['percentWidth', 'width'], 0);
            $height = FHtml::getFieldValue($model, ['percentHeight', 'height'], 0);
            $left = FHtml::getFieldValue($model, ['marginLeft', 'left'], 0);
            $top = FHtml::getFieldValue($model, ['marginTop', 'top'], 0);
            $background = FHtml::getFieldValue($model, ['backgroundColor', 'background'], '#ffffff');
            $text = FHtml::getFieldValue($model, ['name']);
            if (is_numeric($width))
                $width .= '%';
            if (is_numeric($height))
                $height .= '%';
            if (is_numeric($top))
                $top .= '%';
            if (is_numeric($left))
                $left .= '%';
        }

        $result = @"<div class='demo-frame' style='$style'>
                        <div class='clearfix''></div>
                        <div class='div-form' style='display: table-cell;vertical-align: middle; text-align:center;position: absolute;width:$width;height:$height;left:$left;top:$top;background-color:$background;color:black;font-size:70%'>$text</div>
                        <div class='clearfix'></div>
                    </div>";

        return $result;
    }

    public static function showLayoutPreview($model = null, $template = '../demo_html')
    {
        if (empty($model)) {
            return '<div class="demo-layout"><div class="clearfix"></div><div class="div-layout">
<div class="frame-for-layout frame_14 smartscreenlayouts-list_frame-0-frame" style="background-color: #d0e0e3;width: 100%;height: 100%;left: 0%;top: 0%;position: absolute;z-index: 0;border: 1px solid darkgrey;">
    <p style="
    text-align:center;
    margin: 0;
    position: relative; font-size:50%;
    top: 50%;
    transform: translateY(-50%);
    ">content</p></div></div></div>';
        }

        if (is_array($model)) {
            $full_frame = $model;
        } else if (!is_object($model)) {
            $model = SmartscreenLayouts::findOne($model);
            $full_frame = $model->frameQuery;
        } else if (is_object($model)) {
            $full_frame = $model->frameQuery;
        } else {
            $full_frame = null;
            $model = null;
        }

        if (!isset($model) || !isset($full_frame))
            return '';

        $result = @"<div class='demo-layout'><div class='clearfix'></div><div class='div-layout'>";

        if (isset($full_frame)) {
            $result .= FHtml::renderView($template, ['full_frame' => $full_frame]);
        }

        $result .= "</div><div class='clearfix'></div></div>";
        return $result;
    }

    public static function getDeviceCurrentSchedule($ime, $start_time = null, $finished_schedule_id = null, $channel_id = null, $schedule_id = null)
    {
        return self::getDeviceSchedules($ime, self::Today(), $start_time, $finished_schedule_id, $channel_id, $schedule_id, 1);
    }

    public static function getCurrentParams($params, $modelName = 'SmartscreenSchedulesSearch', $model = null, $fields = [])
    {
        $channel_id = Smartscreen::getCurrentChannelId($model, $modelName);
        $device_id  = Smartscreen::getCurrentDeviceId($model, $modelName);
        $campaign_id = Smartscreen::getCurrentCampaignId($model, $modelName);
        $layout = FHtml::getRequestParam('layout');
        $search = FHtml::getRequestParam('search');
        $show_all = 1; // FHtml::getRequestParam('show_all'); Hung: test

        $date = Smartscreen::getCurrentDate();
        if (empty($params))
            $params = [];

        if (!empty($channel_id) && (!empty($_POST) || !empty(FHtml::getRequestParam('channel_id', null)))) {
            $params = array_merge($params, ['channel_id' => $channel_id]);
        }
        if (!empty($device_id))
            $params = array_merge($params, ['device_id' => $device_id]);
        if (!empty($date))
            $params = array_merge($params, ['date' => $date]);

        if (!empty($campaign_id) && (!empty($_POST) || !empty(FHtml::getRequestParam('campaign_id', null))))
            $params = array_merge($params, ['campaign_id' => $campaign_id]);

        if (!empty($layout))
            $params = array_merge($params, ['layout' => $layout]);

        if (!empty($search))
            $params = array_merge($params, ['search' => $search]);

        if (isset($show_all))
            $params = array_merge($params, ['show_all' => $show_all]);

        foreach ($fields as $field) {
            $value = isset($_POST[$modelName][$field]) ? $_POST[$modelName][$field] : null;
            if (!empty($value))
                $params = array_merge($params, [$field => $value]);
            else if (isset($params[$field]))
                unset($params[$field]);
        }

        return $params;
    }

    public static function getCurrentDate($model = null, $modelName = 'SmartscreenSchedulesSearch')
    {
        if (!empty($_POST) && isset($_POST[$modelName]) && isset($_POST[$modelName]['date'])) {
            $date = $_POST[$modelName]['date'];
        } else
            $date = FHtml::getRequestParam('date');

        if (isset($model)) {
            if (!empty($model->date)) {
                $date = $model->date;
            }
        }
        return $date;
    }

    public static function getCurrentCampaignId($model = null, $modelName = 'SmartscreenSchedulesSearch')
    {
        if (!empty($_POST) && isset($_POST[$modelName]) && isset($_POST[$modelName]['campaign_id'])) {
            $campaign_id = $_POST[$modelName]['campaign_id'];
        } else
            $campaign_id = FHtml::getRequestParam('campaign_id');

        if (isset($model)) {
            if (!empty($model->campaign_id)) {
                $campaign_id = $model->campaign_id;
            }
        }
        return $campaign_id;
    }

    public static function getCurrentChannelId($model = null, $modelName = 'SmartscreenSchedulesSearch')
    {
        if (!empty($_POST) && isset($_POST[$modelName]) && isset($_POST[$modelName]['channel_id'])) {
            $channel_id = $_POST[$modelName]['channel_id'];
        } else
            $channel_id = FHtml::getRequestParam('channel_id');

        if (isset($model)) {
            if (!empty($model->channel_id)) {
                $channel_id = $model->channel_id;
            }
        }
        return $channel_id;
    }

    public static function getCurrentDeviceId($model = null, $modelName = 'SmartscreenSchedulesSearch')
    {
        if (!empty($_POST) && isset($_POST[$modelName]) && isset($_POST[$modelName]['device_id'])) {
            $device_id = $_POST[$modelName]['device_id'];
        } else
            $device_id = FHtml::getRequestParam('device_id');

        if (isset($model)) {
            if (!empty($model->device_id)) {
                $device_id = is_array($model->device_id) ? $model->device_id : json_decode($model->device_id);
                if (is_array($device_id) && isset($device_id[0]))
                    $device_id = $device_id[0];
            }
        }
        if (in_array($device_id, ['...', '?']))
            $device_id = null;
        return $device_id;
    }

    public static function getCacheDeviceLoadTime($ime)
    {
        $arr = self::Cache("loaded_times");
        $arr = FHtml::decode($arr);
        return isset($arr[$ime])  ? $arr[$ime] :  null;
    }

    public static function isDeviceNeedRefresh($ime, $schedules = null)
    {
        if (!isset($schedules))
            $schedules = Smartscreen::getCacheDeviceSchedulesForAPI($ime);
        if (!isset($schedules))
            return true;
        return false;
    }

    public static function setCacheDeviceLoadTime($ime)
    {
        $arr = self::Cache("loaded_times");
        $arr = FHtml::decode($arr);
        $arr[$ime] = time();

        self::Cache("loaded_times", $arr);
        return time();
    }

    public static function getCacheDeviceSchedulesForAPI($ime)
    {
        $result = self::Cache("schedules_$ime");
        return $result;
    }

    public static function setCacheDeviceSchedulesForAPI($ime, $data = null)
    {
        if (!isset($data)) {
            $cache = \Yii::$app->cache;
            $cache->delete(FHtml::getCachedKey("schedules_$ime"));
            return;
        }
        $result = self::Cache("schedules_$ime", $data);
        return $result;
    }

    public static function clearCache($ime = null)
    {
        if (empty($ime)) {
            FHtml::Cache()->flush();
            return;
        }
        Smartscreen::clearCacheKey("schedules_$ime");
    }

    public static function Cache($key, $value = null)
    {
        if (isset($value) && empty($value)) {
            $cache = \Yii::$app->cache;
            $cache->delete(FHtml::getCachedKey("$key"));
        }
        return FHtml::Cache($key, $value);
    }

    public static function clearCacheKey($key = null)
    {
        if (empty($key)) {
            FHtml::Cache()->flush();
            return;
        }
        $cache = \Yii::$app->cache;
        $cache->delete(FHtml::getCachedKey("$key"));
    }

    public static function isObjectCachable($table)
    {
        //return false;
        return in_array($table, [
            SmartscreenStation::tableName(),
            SmartscreenChannels::tableName(),
            SmartscreenSchedules::tableName(),
            SmartscreenLayouts::tableName(),
            SmartscreenCampaigns::tableName(),
            SmartscreenContent::tableName(),
            SmartscreenFile::tableName(),

        ]);
    }
    public static function getDeviceSchedulesForAPI($ime, $date, $start_time, $finished_schedule_id, $channel_id, $schedule_id, $limit)
    {
        if (Smartscreen::isStandByTime($start_time)) { //edit standby time (not working) at application/config/params.php
            $schedules = Smartscreen::getStandBySchedule();
        } else {
            //try get smartscreen_script first
            // $script = Smartscreen::getDeviceScripts($ime, $date, $start_time, $finished_schedule_id, $channel_id, $schedule_id, $limit);

            // if (isset($script)) {
            //     $schedules = Smartscreen::convertScheduleFromSmartscreenScript($script);
            //     $schedules = Smartscreen::fixSchedulesTime($schedules, $date, $start_time);
            // } else {
            //     $schedules = Smartscreen::getDeviceSchedules($ime, $date, $start_time, $finished_schedule_id, $channel_id, $schedule_id, $limit, $date, true);
            // }
            $schedules = Smartscreen::getDeviceSchedules($ime, $date, $start_time, $finished_schedule_id, $channel_id, $schedule_id, $limit, $date, true);
        }

        if (is_string($schedules)) {
            return FApi::getOutputForAPI('', FConstant::ERROR, $schedules, ['code' => 205]);
        }

        $inMinutes = static::settingDurationInMinutes();

        if (!isset($schedules[0])) {
            $schedules[] = ['date' => empty($date) ? date("Y-m-d") : $date, 'schedules' => []];
        } else if (is_object($schedules[0])) {
            $tmp = [];
            $tmp[] = ['date' => empty($date) ? date("Y-m-d") : $date, 'schedules' => $schedules];
            $schedules = $tmp;
        }

        $result = [];
        if (isset($schedules[0])) {
            $items = $schedules[0]['schedules'];

            foreach ($items as $i => $schedule) {
                $schedule['duration'] = $inMinutes ? $schedule['duration'] : (60 * $schedule['duration']);
                if (isset($schedule['date']))
                    unset($schedule['date']);
                $items[$i] = $schedule;
            }
            $schedules[0]['schedules'] = $items;

            $result = $schedules;
            $default_schedule = Smartscreen::getDefaultSchedule();

            $files = Smartscreen::getDeviceScheduleFiles($result);
            $files1 = Smartscreen::getDeviceScheduleFiles($default_schedule);
            $files = array_merge($files, $files1);

            if (is_string($result)) {
                return $result;
            }
        }
        $result = FApi::getOutputForAPI($result, FConstant::SUCCESS, 'OK', ['code' => 200]);

        $result['download_files'] = $files;

        $result['download_time'] = "";

        $result['default_schedule'] = $default_schedule;

        $settings = Smartscreen::getSettings();
        $result['settings'] = $settings;
        return $result;
    }

    public static function fixSchedule($schedule, $end_time = null, $date1 = null)
    {
        $start_time = is_numeric($schedule->start_time) ? date("H:i", $schedule->start_time) :  $schedule->start_time;

        if ($schedule->duration == 0) {
            return null;
        }

        //if (empty($date1))
        $date1 = date("Y-m-d");
        $schedule->start_time = trim($schedule->start_time);

        if (!isset($end_time))
            $end_time = strtotime("$date1") + (24 * 60 * 60 - 1);
        $duration_max = Smartscreen::getDurationBetween($schedule->start_time, 0, '24:00');
        if ($schedule->duration > $duration_max)
            $schedule->duration = $duration_max;

        $schedule->end_time = Smartscreen::getNextStartTime($schedule, 0, 1, null, false);
        //echo "Endtime: $end_time ($date1 $schedule->id $schedule->start_time $schedule->end_time $schedule->duration) <br/>";

        if ($schedule->end_time > $end_time) {
            $schedule->end_time = $end_time;
            $schedule->duration = ceil(($end_time - strtotime("$date1 $start_time")) / 60);
        }

        if ($schedule->duration > 0  && $schedule->duration < 1)
            $schedule->duration = 1;

        if (empty($schedule->loop))
            $schedule->loop = 1;

        return $schedule;
    }

    public static function findSchedulesForDevices($devices1, $date = null, $date_end = null, $start_time = null, $limit = -1, $forAPI = false, $showCurrentOnly = true)
    {
        $showCurrentOnly = false; //always show all schedules
        $schedules = [];
        foreach ($devices1 as $device) {
            $device_id = $device->id;
            $autoCalculateStarttime = !empty($device_id);
            $listSchedule = Smartscreen::findSchedules($device_id, null, null, null, $limit, $forAPI, $date, $date_end);
            $listSchedule = Smartscreen::fixSchedules($listSchedule, $date, $start_time, $forAPI, $showCurrentOnly || $autoCalculateStarttime);
            if (isset($listSchedule[0]['schedules'])) {
                $listSchedule = $listSchedule[0]['schedules'];
            }
            if (empty($start_time))
                $start_time = date("H:i");
            $schedule_end_time = null;
            foreach ($listSchedule as $i => $schedule) {
                $schedule->channel_id = $device->channel_id;
                $schedule->device_id = $device_id;
                $schedule_end_time = Smartscreen::getNextStartTime($schedule->start_time, $schedule->duration, 1, null, true);
                if ($showCurrentOnly) {
                    if ($schedule->start_time <= $start_time && $start_time <= $schedule_end_time) {
                        $listSchedule = [$schedule];
                        break;
                    }
                    if ($schedule_end_time < $start_time) {
                        unset($listSchedule[$i]);
                        continue;
                    }
                }
                $listSchedule[$i] = $schedule;
            }
            $listSchedule = array_values($listSchedule);

            if ($showCurrentOnly) {
                if (empty($listSchedule)) {
                    if (empty($schedule_end_time))
                        $schedule_end_time = date("H:i");
                    $schedule = Smartscreen::getDefaultSchedule();
                    $schedule->device_id = $device_id;
                    $schedule->start_time = $schedule_end_time;
                    $schedule->duration = Smartscreen::getDurationBetween($schedule_end_time, 0, "24:00");
                    $listSchedule[] = $schedule;
                } else {
                    $listSchedule = [$listSchedule[0]];
                }
            }
            $schedules = array_merge($schedules, $listSchedule);
        }

        return $schedules;
    }

    public static function findSchedulesForChannel($channel_id, $date = null, $date_end = null, $start_time = null, $limit = -1, $forAPI = false, $showCurrentOnly = true)
    {
        if (is_numeric($channel_id) && $channel_id > 0)
            $devices1 = SmartscreenStation::findAll(['channel_id' => $channel_id, 'is_active' => 1]);
        else {
            $devices1 = SmartscreenStation::findAll(['is_active' => 1], ['channel_id' => 'ASC']);
            $channel_id = FHtml::NULL_VALUE;
        }
        $schedules = self::findSchedulesForDevices($devices1, $date, $date_end, $start_time, $limit, $forAPI, $showCurrentOnly);
        return $schedules;
    }

    public static function findSchedulesForCampaign($campaign_id, $date = null, $date_end = null, $start_time = null, $limit = -1, $forAPI = false)
    {
        $schedules = [];
        $campaign  = SmartscreenCampaigns::findOne($campaign_id);
        if (isset($campaign)) {
            $listSchedule = Smartscreen::findSchedules(null, null, $campaign_id, null, $limit, $forAPI, $date, $date_end);
            $listSchedule = Smartscreen::fixSchedules($listSchedule, $date, $start_time, $forAPI, false);
            if (isset($listSchedule[0]['schedules'])) {
                $listSchedule = $listSchedule[0]['schedules'];
            }
            foreach ($listSchedule as $i => $item) {
                $item->channel_id = $campaign->channel_id ? $campaign->channel_id : FHtml::NULL_VALUE;
                $item->device_id = $campaign->device_id ? $campaign->device_id : FHtml::NULL_VALUE;
                $listSchedule[$i] = $item;
            }
            $schedules = array_merge($schedules, $listSchedule);
            // $channel_id = $campaign->channel_id;
            // $devices1 = [];
            // if (!empty($campaign->device_id) && $campaign->device_id != FHtml::NULL_VALUE) {
            //     $devices1 = FHtml::decode($campaign->device_id);
            // } else if (!empty($channel_id) && $campaign->channel_id != FHtml::NULL_VALUE) {
            //     $devices1 = SmartscreenStation::findAll(['channel_id' => $channel_id, 'is_active' => 1]);
            // } else {
            //     $devices1 = SmartscreenStation::findAll(['is_active' => 1]);
            // }

            // foreach ($devices1 as $device_id) {
            //     if (is_object($device_id))
            //         $device_id = $device_id->id;
            //     $autoCalculateStarttime = !empty($device_id);
            //     $listSchedule = Smartscreen::findSchedules($device_id, $channel_id, $campaign_id, null, $limit, $forAPI, $date, $date_end);
            //     $listSchedule = Smartscreen::fixSchedules($listSchedule, $date, $start_time, $forAPI, $autoCalculateStarttime);
            //     if (isset($listSchedule[0]['schedules'])) {
            //         $listSchedule = $listSchedule[0]['schedules'];
            //     }
            //     foreach ($listSchedule as $i => $item) {
            //         $item->channel_id = $channel_id;
            //         $item->device_id = $device_id;
            //         $listSchedule[$i] = $item;
            //     }
            //     $schedules = array_merge($schedules, $listSchedule);
            // }
        }
        return $schedules;
    }


    public static function getDateSqlCondition($date = null, $date_end = null)
    {
        if (empty($date_end) && empty($date))
            return '';
        else if (empty($date))
            $date = $date_end;
        else if (empty($date_end))
            $date_end = $date;
        else if ($date_end < $date)
            $date_end = $date;

        $sql_condition = "('$date' <= date_end or (date_end is null or date_end = '')) AND ('$date_end' >= date or (date is null or date=''))";
        return $sql_condition;
    }

    public static function findSchedules($device_id = null, $channel_id = null, $campaign_id = null, $schedule_id = null, $limit = -1, $forAPI = true, $date = null, $date_end = null)
    {
        $null_value = FHtml::NULL_VALUE;

        if ($channel_id == $null_value)
            $channel_id = null;

        if ($campaign_id == $null_value)
            $campaign_id = null;

        if ($device_id == $null_value)
            $device_id = null;

        $sql_condition = "";
        $sql_channel_null = "(channel_id = '...' or channel_id is null or channel_id = '' or channel_id = '[]')";
        $sql_device_null = "(device_id = '...' or device_id is null or device_id = '' or device_id = '[]')";

        $sql_device_has = "(device_id = '$device_id' or device_id like '%$device_id%')";
        $sql_channel_has = "(channel_id = '$channel_id' or channel_id like '%$channel_id%')";

        if (!empty($campaign_id)) {
            $sql_condition = "(" . SmartscreenSchedules::FIELD_CAMPAIGN_ID . " = '$campaign_id'" . ")";
        } else if (!empty($schedule_id)) {
            $sql_condition = "(id in (" . is_array($schedule_id) ? implode(',', $schedule_id) : $schedule_id . "))";
        } else if (!empty($device_id)) {
            $sql_condition = "($sql_device_has or ($sql_channel_null and $sql_device_null)";
            if (!empty($channel_id))
                $sql_condition .= " or ($sql_channel_has and $sql_device_null)";
            $sql_condition .= ")";
        } else {
            return [];
        }

        $date_condition = Smartscreen::getDateSqlCondition($date, $date_end);
        if (!empty($date_condition)) {
            $sql_condition .= " and $date_condition";
        }

        $sql_condition .= " and (type != 'campaign')";

        $query = SmartscreenSchedulesAPI::find();

        if (!empty($sql_condition))
            $query = $query->where($sql_condition);

        if ($forAPI) {
            if (empty($sql_condition))
                return [];
            $query = $query->andWhere([SmartscreenSchedules::FIELD_STATUS => 1]);
        } else {
            if (FHtml::isRoleUser()) {
                $query = $query->andWhere(['owner_id' => FHtml::getCurrentUserId()]);
            }
        }

        $query = $query->orderBy(['frame_id' => SORT_DESC,  'channel_id' => SORT_ASC, 'device_id' => SORT_ASC, 'start_time' => SORT_ASC]);

        if ($limit > 0)
            $query = $query->limit($limit);

        $schedules = $query->all();

        for ($i = 0; $i < count($schedules); $i++) {
            if (!empty($device_id))
                $schedules[$i]->device_id = $device_id;
            if (!empty($channel_id))
                $schedules[$i]->channel_id = $channel_id;
        }

        if (FHtml::getRequestParam('debug') == 'sql') {
            echo $sql_condition;
            FHtml::var_dump($schedules);
            die;
        }
        return $schedules;
    }

    public static function fixSchedules($schedules, $date = null, $start_time = null, $forAPI = true, $autoCaculateStartTime = !SmartScreen::SETTINGS_SCHEDULE_ALLOW_OVERLAP)
    {
        $autoCaculateStartTime = false;
        $day_in_week = FHtml::getWeekday($date);
        $start_time = null;
        $last_end_time = null;
        $result = [];
        $tmp = [];

        //delete the tmp/garbage schedules
        foreach ($schedules as $i => $schedule) {
            if (empty($schedule->start_time)) {
                unset($schedules[$i]);
                continue;
            }

            if (!in_array($schedule->type, [Smartscreen::SCHEDULE_TYPE_BASIC, Smartscreen::SCHEDULE_TYPE_ADVANCE])) {
                unset($schedules[$i]);
                continue;
            }

            if (!empty($date)) {
                if (!empty($schedule->date) && $schedule->date > $date) {
                    unset($schedules[$i]);
                    continue;
                }
                if (!empty($schedule->date_end) && $schedule->date_end < $date) {
                    unset($schedules[$i]);
                    continue;
                }
            }

            if (!empty($date) && !empty($schedule->days) && !in_array($day_in_week, FHtml::decode($schedule->days))) {
                unset($schedules[$i]);
                continue;
            }

            $schedule = static::fixSchedule($schedule, null, $date);
            $schedule_end_time = Smartscreen::getNextStartTime($schedule);

            // remove intersected (overlapped time) schedules 
            if ($forAPI && !SmartScreen::SETTINGS_SCHEDULE_ALLOW_INTERSECTED) {
                if ($schedule_end_time <= $last_end_time) {
                    unset($schedules[$i]);
                    continue;
                }
            }

            if (isset($schedule) && $schedule->duration > 0) {
                $schedules[$i] = $schedule;
                if (!isset($start_time))
                    $start_time = $schedule->start_time;
                $last_end_time  = $schedule_end_time;
            }
        }

        //fill content for schedules
        $schedules = self::fixSchedulesContent($schedules);
        $schedules = self::fixSchedulesLayout($schedules, null);

        // return consecutive schedules by time. still keep schedule->duration
        if ($autoCaculateStartTime && !SmartScreen::SETTINGS_SCHEDULE_ALLOW_OVERLAP) {
            $schedules = self::fixSchedulesTime($schedules, $date, $start_time, null, $forAPI);
        }

        return $schedules;
    }

    public static function getDeviceSchedules($ime, $date = null, $start_time = null, $finished_schedule_id = null,  $channel_id = null, $schedule_id = null, $limit = -1, $date_end = null, $forAPI = true, $campaign_id = null)
    {
        $date = FHtml::getRequestParam('date', $date);

        if (empty($date))
            $date = self::Today();

        if (!isset($date_end))
            $date_end =  self::getDate($date, "+0 days");

        $channel_id = FHtml::getRequestParam('channel_id', $channel_id);

        $null_value = FHtml::NULL_VALUE;

        if (empty($ime) && empty($device_id)) {
            $device_id = $null_value;
            $ime = $null_value;
        } else if ($ime != $null_value) {
            if (!is_numeric($ime)) {
                $device = SmartscreenStation::find()->where(['ime' => $ime])->one();
                $device_id = isset($device) ? $device->id : null;
            } else {
                $device_id = $ime;
            }
        } else {
            $device_id = $null_value;
        }

        if (empty($channel_id) && isset($device) && is_object($device)) { //show schedules of channel in general
            $channel_id = $device->channel_id;
        }

        $result = [];

        $schedulesAll = static::findSchedules($device_id, $channel_id, $campaign_id, null, $limit, $forAPI, $date, $date_end);

        $date1 = $date;
        while ($date1 <= $date_end) {
            $schedules = $schedulesAll;
            $schedules = self::fixSchedules($schedules, $date1, $start_time, $forAPI);
            $result = array_merge($result, $schedules);
            $date1 = self::getDate($date1, "+1 day");
        }

        return $result;
    }

    public static function isEqualDate($start_time_next, $date)
    {
        if (is_string($start_time_next))
            return true;
        return date("Y-m-d",  $start_time_next) == $date || (date("Y-m-d",  $start_time_next) > $date && date("H:i", $start_time_next) == '00:00');
    }

    public static function getDurationBetween($time_start, $duration = 0, $time_end = '24:00', $inMinutes = true)
    {
        if (is_object($time_start)) {
            $model = $time_start;
            $time_start = $model->start_time;
            $duration = $model->duration;
        }
        if ($time_end == '00:00')
            $time_end = '24:00';

        $result = strtotime($time_end) - strtotime($time_start . " +" . $duration . ' minutes');

        return $inMinutes ? $result / 60 : $result * 1000;
    }

    public static function settingDurationInMinutes()
    {
        $t = FConfig::setting('smartads.duration_unit', SmartscreenSchedules::DURATION_KIND_MINUTES);
        return $t == SmartscreenSchedules::DURATION_KIND_MINUTES;
    }

    //always return minutes
    public static function getDuration($smartscreen_file, $file_kind = false, $forSchedule = false)
    {
        if (is_object($smartscreen_file) && isset($smartscreen_file->file_duration)) {
            $value = FHtml::getNumeric($smartscreen_file->file_duration);
            $file_kind = $smartscreen_file->file_kind;
        } else if (is_object($smartscreen_file) && isset($smartscreen_file->duration)) {
            $value = FHtml::getNumeric($smartscreen_file->duration);
            $file_kind = SmartscreenSchedules::DURATION_KIND_MINUTES;
        } else {
            $value = $smartscreen_file;
        }

        $value = FHtml::getNumeric($value);

        $inMinutes = static::settingDurationInMinutes();
        if ($inMinutes) {
            if ($file_kind == SmartscreenSchedules::DURATION_KIND_SECOND)
                $value = (float) number_format($value / 60, 2);
        } else {
            if ($file_kind == SmartscreenSchedules::DURATION_KIND_MINUTES)
                $value = $value * 60;
        }

        //$value = (float) number_format($value, 3);

        if ($forSchedule) {
            if ($value > 0 && $value < 1)
                $value = 1;
            else
                $value = ceil($value);
        }
        return $value;
    }

    public static function getObjectFiles($model)
    {
        if (is_object($model)) {
            if (is_numeric($model->content_id) && !empty($model->content_id)) {
                return SmartscreenFileAPI::findAll(['object_id' => $model->content_id, 'object_type' => SmartscreenContent::tableName()], 'sort_order');
            }
            return SmartscreenFileAPI::findAll(['object_id' => $model->id, 'object_type' => $model->getTableName()], 'sort_order');
        } else
            return SmartscreenFileAPI::findAll(['object_id' => $model, 'object_type' => SmartscreenContent::tableName()], 'sort_order');
    }

    public static function getFileKind($smartscreen_file)
    {
        $value = is_object($smartscreen_file) ? $smartscreen_file->file_kind : $smartscreen_file;
        if (empty($value))
            $value = 'time';

        $inMinutes = self::settingDurationInMinutes();
        if ($inMinutes)
            return $value == 'second' ? 'time' : $value;
        return $value;
    }

    public static function getFileUrl($smartscreen_file, $folder = 'smartscreen-file', $forAPI = true)
    {
        if (!isset($smartscreen_file) || empty($smartscreen_file))
            return '';

        $value = is_object($smartscreen_file) ? $smartscreen_file->file : $smartscreen_file;
        if (starts_with($value, 'http')) {
            $value = str_replace('//', '/', $value);
            $value = str_replace(':/', '://', $value);

            return $value;
        }
        if ($forAPI) {
            $file_url = !empty($value) ? FHtml::getFileURL($value, $folder) : '';
            $file_url = str_replace('\\', '/', $file_url);
        } else {
            $file_url = FHtml::getFilePath($value, $folder);
        }
        $file_url = str_replace('//', '/', $file_url);
        $file_url = str_replace(':/', '://', $file_url);

        return $file_url;
    }

    public static function createIframeSchedule($schedule, $type = null, $params = [])
    {
        if (!isset($type) && !empty($schedule->dataType)) {
            $type = $schedule->dataType;
        }

        $url = static::getSmartscreenContentUrl(array_merge(['type' => $type, 'schedule_id' => $schedule->id, 'device_id' => FHtml::getRequestParam('device_id'), 'layout' => 'no', 'auto_refresh' => 0, 'background' => ''], $params));
        $list_content[] = array(
            'id' => $schedule->id,
            'title' => '',
            'url' => $url,
            'description' => '',
            'kind' => static::getFileKind($schedule->kind),
            'duration' => static::getDuration($schedule),
            'dataType' => 'html',
        );
        $scheduleData = static::createFullScreenScheduleData($list_content, 'html', '#ffffff');

        $schedule->setData($scheduleData);

        return $schedule;
    }

    //tự động tao schedule cho các schedule dạng basic
    public static function fixSchedulesContent($schedules)
    {

        $result1 = [];
        $convertContentToIFRAME = static::settingConvertContentToIframe();
        $convertVideoToIFRAME = static::settingConvertVideoToIframe();
        $convertImageToIFRAME = static::settingConvertImageToIframe();
        $convertMarqueeToIFRAME = static::settingConvertMarqueToIframe();
        $showEachFile = self::settingSlideContentSplitRecursive(); // = True --> auto split slides into multiple schedules. But could be long-time

        //echo "$convertContentToIFRAME - $convertVideoToIFRAME - $convertImageToIFRAME <br/>";
        //        foreach ($schedules as $schedule) {
        //            echo "$schedule->id $schedule->start_time $schedule->duration <br/>";
        //        }

        foreach ($schedules as $i => $schedule) {
            if ($schedule->type == Smartscreen::SCHEDULE_TYPE_BASIC) {

                if ($convertContentToIFRAME) {
                    //show mixed slides
                    $schedule = self::createIframeSchedule($schedule);
                    $result1[] = $schedule;
                } else {
                    $smartscreen_files = static::getObjectFiles($schedule);

                    $video_content = [];
                    $image_content = [];
                    $duration = $schedule->duration;

                    $previous_content_type = '';
                    $content_type = '';
                    $previous_schedule = null;
                    $limit = 0;
                    while ($duration > 0) {
                        $limit += 1;
                        if ($limit == 250) {
                            break;
                        }
                        $content_duration = 0;
                        foreach ($smartscreen_files as $smartscreen_file) {
                            $file_url = static::getFileUrl($smartscreen_file);
                            if (ends_with($file_url, FHtml::NO_IMAGE)) {
                                continue;
                            }

                            if (!empty($smartscreen_file->file)) { //video
                                $content_type = self::getFileType($smartscreen_file->file);
                                ///
                                if ($showEachFile) {
                                    if (!empty($content_type) && !empty($previous_content_type) && $content_type != $previous_content_type) {
                                        if (!empty($video_content)) {
                                            $video_schedule = self::createScheduleFromContent(SmartscreenContent::TYPE_VIDEO, $video_content, '', $schedule, true, $convertVideoToIFRAME);
                                            if (isset($video_schedule)) {
                                                $video_schedule = self::fixSchedule($video_schedule);

                                                if (isset($video_schedule) && $video_schedule->duration > 0) {
                                                    if ($convertVideoToIFRAME) {
                                                        $video_schedule = static::createIframeSchedule($video_schedule, SmartscreenContent::TYPE_VIDEO, ['slide_id' => $smartscreen_file->id]);
                                                    }
                                                    if (isset($previous_schedule)) {
                                                        $video_schedule->start_time = Smartscreen::getNextStartTime($previous_schedule->start_time, $previous_schedule->duration, 1, null, true);
                                                    }

                                                    $duration = $duration - $video_schedule->duration;
                                                    $content_duration += $video_schedule->duration;

                                                    if ($duration < 0) {
                                                        $video_schedule->duration = $video_schedule->duration + $duration;
                                                    }

                                                    if ($video_schedule->duration > 0) //recursively ?
                                                        $result1[] = $video_schedule;
                                                    $video_content = [];
                                                    $previous_schedule = $video_schedule;
                                                }
                                            }
                                        }
                                        if (!empty($image_content)) {
                                            $image_schedule = self::createScheduleFromContent(SmartscreenContent::TYPE_IMAGE, $image_content, '', $schedule, true, $convertImageToIFRAME);
                                            if (isset($image_schedule)) {
                                                $image_schedule = self::fixSchedule($image_schedule);

                                                if (isset($image_schedule) && $image_schedule->duration > 0) {
                                                    if ($convertImageToIFRAME) {
                                                        $image_schedule = static::createIframeSchedule($image_schedule, SmartscreenContent::TYPE_IMAGE, ['slide_id' => $smartscreen_file->id]);
                                                    }
                                                    if (isset($previous_schedule)) {
                                                        $image_schedule->start_time = Smartscreen::getNextStartTime($previous_schedule->start_time, $previous_schedule->duration, 1, null, true);
                                                    }

                                                    $duration = $duration - $image_schedule->duration;
                                                    $content_duration += $image_schedule->duration;

                                                    if ($duration < 0) {
                                                        $image_schedule->duration = $image_schedule->duration + $duration;
                                                    }

                                                    if ($image_schedule->duration > 0)
                                                        $result1[] = $image_schedule;
                                                    $image_content = [];
                                                    $previous_schedule = $image_schedule;
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    $previous_schedule = $schedule;
                                }
                                ///
                                ///

                                if ($content_type == SmartscreenContent::TYPE_VIDEO) {
                                    $video_content[] = array(
                                        'id' => $smartscreen_file->id,
                                        'title' => $smartscreen_file->description,
                                        'url' => $file_url,
                                        'kind' => static::getFileKind($smartscreen_file),
                                        'duration' => static::getDuration($smartscreen_file),
                                        'description' => $smartscreen_file->description,
                                        'dataType' => $content_type
                                    );
                                    $previous_content_type = $content_type;
                                } else if ($content_type == SmartscreenContent::TYPE_IMAGE) {
                                    $image_content[] = array(
                                        'id' => $smartscreen_file->id,
                                        'title' => $smartscreen_file->description,
                                        'url' => $file_url,
                                        'kind' => static::getFileKind($smartscreen_file),
                                        'duration' => static::getDuration($smartscreen_file),
                                        'description' => $smartscreen_file->description,
                                        'dataType' => $content_type
                                    );
                                    $previous_content_type = $content_type;
                                } else {
                                    continue;
                                }
                            } else {
                                $html_schedule = self::createScheduleFromContent(SmartscreenContent::TYPE_HTML, $smartscreen_file, $schedule, null, false, $convertMarqueeToIFRAME);
                                if (isset($html_schedule) && $html_schedule->duration > 0) {
                                    $duration = $duration - $html_schedule->duration;
                                    $content_duration += $html_schedule->duration;

                                    if ($duration < 0)
                                        $html_schedule->duration = $html_schedule->duration + $duration;
                                    if (isset($previous_schedule))
                                        $html_schedule->start_time = Smartscreen::getNextStartTime($previous_schedule->start_time, $previous_schedule->duration);
                                    $result1[] = $html_schedule;
                                    $previous_schedule = $html_schedule;
                                    $previous_content_type = 'html';
                                }
                            }
                        }

                        if (!$showEachFile) {
                            if (!empty($image_content)) {
                                $image_schedule = self::createScheduleFromContent(SmartscreenContent::TYPE_IMAGE, $image_content, '', $previous_schedule, !empty($video_content), $convertImageToIFRAME);
                                if (isset($image_schedule)) {
                                    $image_schedule = self::fixSchedule($image_schedule);
                                    if (isset($image_schedule) && $image_schedule->duration > 0) {
                                        if ($convertImageToIFRAME) {
                                            $image_schedule = static::createIframeSchedule($image_schedule, SmartscreenContent::TYPE_IMAGE);
                                        }
                                        $previous_schedule = $image_schedule;

                                        $duration = $duration - $image_schedule->duration;
                                        $content_duration += $image_schedule->duration;

                                        if ($duration < 0)
                                            $image_schedule->duration = $image_schedule->duration + $duration;
                                        if ($image_schedule->duration > 0)
                                            $result1[] = $image_schedule;
                                        //$image_content = [];
                                    }
                                }
                            }

                            if (!empty($video_content)) {
                                $video_schedule = self::createScheduleFromContent(SmartscreenContent::TYPE_VIDEO, $video_content, '', $previous_schedule, !empty($image_content), $convertVideoToIFRAME);
                                if (!empty($image_content)) {
                                    $video_schedule->start_time = static::getNextStartTime($previous_schedule->start_time, $previous_schedule->duration, 1, null, true);
                                    $video_schedule->duration = $duration;
                                }

                                $video_schedule = self::fixSchedule($video_schedule);
                                if (isset($video_schedule) && $video_schedule->duration > 0) {
                                    if ($convertVideoToIFRAME) {
                                        $video_schedule = static::createIframeSchedule($video_schedule, SmartscreenContent::TYPE_VIDEO);
                                    }
                                    $video_schedule->duration = $duration;

                                    $duration = $duration - $video_schedule->duration;
                                    $content_duration += $video_schedule->duration;

                                    if ($duration < 0)
                                        $video_schedule->duration = $video_schedule->duration + $duration;

                                    if ($video_schedule->duration > 0)
                                        $result1[] = $video_schedule;
                                }
                            }
                        }

                        //if content has no duration  --> must quit  to avoid unlimited recursive !
                        if ($content_duration == 0) {
                            break;
                        }
                    }
                }
            } else if ($schedule->type != Smartscreen::SCHEDULE_TYPE_CAMPAIGN) {
                //if (!empty($schedule->data))
                $result1[] = $schedule;
            }
        }

        return $result1;
    }

    public static function fixSchedulesLayout($schedules, $channel_model)
    {
        //if channel has fixed layout then must use layout from channel
        if (isset($channel_model) && !empty($channel_model->layout_id) && !empty($channel_model->content_id)) {
            $result1 = [];
            $layout_model = SmartscreenLayouts::findOne($channel_model->layout_id);
            $channel_frames = $layout_model->frameQuery;
            if (!is_array($channel_model->content_id)) {
                $channel_model->content_id = json_decode($channel_model->content_id);
            }
            $channel_content_ids = $channel_model->content_id;

            foreach ($schedules as $i => $schedule) {

                if (!is_array($schedule->content_id)) {
                    $schedule->content_id = json_decode($schedule->content_id);
                }

                if (isset($schedule->layout)) {
                    $schedule_frames = $schedule->layout->frameQuery;
                    $schedule->layout_id = $channel_model->layout_id; //su dung layout cua channel

                    $schedule_content_ids = $schedule->content_id;

                    $channel_i = 0;
                    foreach ($channel_frames as $channel_frame) {
                        $schedule_i = 0;
                        foreach ($schedule_frames as $schedule_frame) {

                            if ($channel_frame->contentLayout == $schedule_frame->contentLayout && in_array($schedule_frame->contentLayout, ['main', 'full'])) {

                                if (!empty($schedule_content_ids[$schedule_i])) {
                                    $channel_content_ids[$channel_i] = $schedule_content_ids[$schedule_i];
                                }
                            }
                            $schedule_i += 1;
                        }
                        $channel_i += 1;
                    }
                    $schedule->content_id = $channel_content_ids;

                    $schedule1 = new SmartscreenSchedulesAPI(); // have to create new model
                    $schedule1->id = $schedule->id;
                    $schedule1->layout_id = $schedule->layout_id;
                    $schedule1->content_id = $schedule->content_id;
                    $schedule1->device_id = $schedule->device_id;
                    $schedule1->start_time = $schedule->start_time;
                    $schedule1->loop = 1; // $schedule->loop;
                    $schedule1->{SmartscreenSchedules::FIELD_CAMPAIGN_ID} = $schedule->{SmartscreenSchedules::FIELD_CAMPAIGN_ID};
                    $schedule1->duration = static::getDuration($schedule->duration);
                    $schedule1->{SmartscreenSchedules::FIELD_STATUS} = $schedule->{SmartscreenSchedules::FIELD_STATUS};
                    $schedule1->is_active = $schedule->{SmartscreenSchedules::FIELD_STATUS};
                    $schedule1->setData($schedule->getData());

                    $result1[] = $schedule1;
                } else {

                    $channel_data = self::getLayoutData($channel_frames, $channel_content_ids);
                    $channel_i = 0;

                    foreach ($channel_frames as $channel_frame) {
                        if (in_array($channel_frame->contentLayout, ['main', 'full']))
                            break;
                        $channel_i += 1;
                    }

                    if ($channel_i <= count($channel_data) - 1 && !empty($schedule['data'])) {
                        $channel_data[$channel_i]['data'] = $schedule['data'][0]['data'];
                        $channel_data[$channel_i]['contentLayout'] = $schedule['data'][0]['contentLayout'];
                    }

                    $schedule['data'] = $channel_data;

                    $result1[] = $schedule;
                }
            }
            $schedules = $result1;
        }
        return $schedules;
    }

    public static function createScheduleFromContent($contentType, $data = [], $schedule_id = '', $old_schedule_model = null, $reCalculate =  false, $convertContentToIFRAME = null)
    {
        if (!isset($data) || empty($data))
            return null;
        if (!isset($convertContentToIFRAME))
            $convertContentToIFRAME = self::settingConvertContentToIframe();

        if (is_object($schedule_id)) {
            $old_schedule_model = $schedule_id;
            $schedule_id = $schedule_id->id;
        } else if (empty($schedule_id) && isset($old_schedule_model)) {
            $schedule_id = $old_schedule_model->id;
        }

        $schedule = new SmartscreenSchedulesAPI(); // have to create new model
        $schedule->id = !empty($schedule_id) ? $schedule_id : time();
        $schedule->device_id = !isset($old_schedule_model) ? null : $old_schedule_model->device_id;
        $schedule->{SmartscreenSchedules::FIELD_CAMPAIGN_ID} =  !isset($old_schedule_model) ? null : $old_schedule_model->{SmartscreenSchedules::FIELD_CAMPAIGN_ID};
        $schedule->campaign_id =  !isset($old_schedule_model) ? null : $old_schedule_model->campaign_id;
        $schedule->channel_id =  !isset($old_schedule_model) ? null : $old_schedule_model->channel_id;
        $schedule->is_active =  !isset($old_schedule_model) ? null : $old_schedule_model->{SmartscreenSchedules::FIELD_STATUS};;

        $schedule->layout_id = 0; //su dung layout cua channel
        $schedule->start_time = isset($old_schedule_model) ? $old_schedule_model->start_time : null;
        $schedule->date = isset($old_schedule_model) ? $old_schedule_model->date : null;
        $schedule->date_end = isset($old_schedule_model) ? $old_schedule_model->date_end : null;
        $schedule->days = isset($old_schedule_model) ? $old_schedule_model->days : null;
        $schedule->{SmartscreenSchedules::FIELD_STATUS} = isset($old_schedule_model) ? $old_schedule_model->{SmartscreenSchedules::FIELD_STATUS} : null;
        $schedule->frame_id = isset($old_schedule_model) ? $old_schedule_model->frame_id : null;


        $html_content = [];
        if (is_object($old_schedule_model) && !$reCalculate) {
            $duration = static::getDuration($old_schedule_model);
        } else {
            $duration = 0;
            if (is_object($data)) {
                $smartscreen_file = $data;
                $html_content[] = array(
                    'id' => $smartscreen_file->id,
                    'title' => $smartscreen_file->description,
                    'url' => static::getFileUrl($smartscreen_file),
                    'kind' => static::getFileKind($smartscreen_file),
                    'duration' => static::getDuration($smartscreen_file),
                    'description' => $smartscreen_file->description,
                    'dataType' => SmartscreenContent::TYPE_HTML
                );
                $data = $html_content;
            }

            foreach ($data as $item) {
                $duration += key_exists('duration', $item) ? static::getDuration($item['duration']) : 0;
            }
        }

        $schedule->duration = static::getDuration($duration, false, true);

        //tự chuyênn thành image
        $schedule->setData([array(
            'id' => time(),
            'name' => 'main',
            'percentWidth' => 100,
            'percentHeight' => 100,
            'marginTop' => 0,
            'marginLeft' => 0,
            'backgroundColor' => self::getDefaultBackgroundColor(),
            'contentLayout' => $contentType,
            'data' => $data
        )]);

        return $schedule;
    }

    public static function getDeviceScripts($ime, $date = null, $start_time = null, $finished_schedule_id = null, $channel_id = null, $schedule_id = null, $limit = null)
    {
        if (empty($date))
            $date = FHtml::Now();

        $script_models = SmartscreenScripts::findAll("ReleaseDate <= '$date' and is_active = 1", "ReleaseDate DESC");
        $scriptModel = null;
        if (count($script_models) > 0)
            $scriptModel = $script_models[0];
        return $scriptModel;
    }

    public static function convertScheduleFromSmartscreenScript($script)
    {
        $clip_num = $script->CommandNumber;
        $result1 = [];
        $video_content = [];
        $image_content = [];
        for ($i = 0; $i < $clip_num; $i++) {
            $arr = \backend\modules\smartscreen\Smartscreen::getScriptCommandLineArray(FHtml::getFieldValue($script, "Line$i"));
            $command = $arr['command'];
            $duration = $arr['duration'];
            $index = $arr['index'];

            if ($command == SmartscreenScripts::COMMAND_DSPCLIP) { //video
                $file = FHtml::getFieldValue($script, "Clip$index");
                if (empty($file))
                    continue;
                $arr = explode('.', $file);

                $extension = count($arr) > 0 ? $arr[1] : '';
                if (in_array($extension, ['mp4', 'avi', 'mov', 'mkv'])) {
                    $video_content[] = array(
                        'id' => $i,
                        'title' => '',
                        'url' => static::getFileUrl($file, 'smartscreen-scripts'),
                        'kind' => '',
                        'duration' => ceil(FHtml::getNumeric($duration) / 60),
                        'description' => '',
                        'dataType' => SmartscreenContent::TYPE_VIDEO
                    );
                } else if (in_array($extension, ['jpg', 'png', 'bmp', 'jpeg'])) {
                    $image_content[] = array(
                        'id' => $i,
                        'title' => '',
                        'url' => static::getFileUrl($file, 'smartscreen-scripts'),
                        'kind' => '',
                        'duration' => ceil(FHtml::getNumeric($duration) / 60),
                        'description' => '',
                        'dataType' => SmartscreenContent::TYPE_IMAGE
                    );
                } else {
                    continue;
                }

                if (!empty($video_content)) {
                    $video_schedule = self::createScheduleFromContent(SmartscreenContent::TYPE_VIDEO, $video_content);
                    if (isset($video_schedule))
                        $result1[] = $video_schedule;
                } else if (!empty($image_content)) {
                    $image_schedule = self::createScheduleFromContent(SmartscreenContent::TYPE_IMAGE, $image_content);
                    if (isset($image_schedule))
                        $result1[] = $image_schedule;
                }
            } else if ($command == SmartscreenScripts::COMMAND_DSPTEXT) {
                continue; //
                $html_schedule = self::createScheduleFromContent(SmartscreenContent::TYPE_HTML, [[
                    'id' => $i,
                    'title' => $index,
                    'url' => '',
                    'kind' => '',
                    'duration' => ceil(FHtml::getNumeric($duration) / 60),
                    'description' => $index,
                    'dataType' => 'html'
                ]]);
                if (isset($html_schedule) && !empty($index))
                    $result1[] = $html_schedule;
            } else if ($command == SmartscreenScripts::COMMAND_DSPIE) {
                $html_schedule = self::createScheduleFromContent(SmartscreenContent::TYPE_HTML, [[
                    'id' => $i,
                    'title' => '',
                    'url' => $index,
                    'kind' => '',
                    'duration' => ceil(FHtml::getNumeric($duration) / 60),
                    'description' => $index,
                    'dataType' => SmartscreenContent::TYPE_HTML
                ]]);
                if (isset($html_schedule) && !empty($index))
                    $result1[] = $html_schedule;
            }
        }

        return $result1;
    }

    public static function getTime($time, $isString = true)
    {
        if ($isString) {
            return is_numeric($time) ? date("H:i", $time) : $time;
        }
        return is_numeric($time) ? $time : strtotime(date('Y-m-d') . ' ' . $time);
    }

    public static function getDefaultDuration()
    {
        return FHtml::getNumeric(FConfig::setting('smartads.default_duration', 10));
    }

    public static function fixSchedulesTime($schedules, $date_start = '', $start_time = '', $default_duration = null, $for_api = false, $defaultSchedule = null)
    {
        $campaign_id = null;
        $campaign = null;
        $campaignHasTime = FHtml::setting('smartscreen.campaign_has_time', false);

        if (empty($default_duration))
            $default_duration = self::getDefaultDuration();

        if (!isset($defaultSchedule))
            $defaultSchedule = Smartscreen::getDefaultSchedule();

        if (empty($date_start))
            $date_start = self::Today();

        if (empty($start_time))
            $start_time1 =  strtotime(date("$date_start H:i:s")); // FHtml::setting('date.start_time', '08:00:00');
        else
            $start_time1 = strtotime(date("$date_start $start_time"));

        $result = [];

        foreach ($schedules as $schedule) {

            $schedule->start_time2 = $schedule->start_time;
            $schedule->date2 = $schedule->date;

            $schedule->duration = self::getScheduleDuration($schedule, $default_duration);

            if (empty($schedule->start_time)) {
                $schedule->start_time = date('H:i', $start_time1); //$start_time;
            } else {
                $start_time1 = is_string($schedule->start_time) ? strtotime($schedule->start_time) : $schedule->start_time;
                $schedule->start_time = date('H:i', $start_time1); //$start_time;
            }

            $date = $date_start;

            //Important Today: $schedule->date = $date_start;
            if (key_exists($date, $result)) {
                $arr = $result[$date];
                $arr[] = $schedule;
                $result[$date] = $arr;
            } else {
                $result[$date] = [$schedule];
            }

            //calculate next start time
            $start_time1 = static::getNextStartTime($start_time1, $schedule->duration, $schedule->loop, $date);
        }

        //24.04.2018: make new format: ['date' => $date, 'schedules' => $date_schedules]
        $schedules = [];
        foreach ($result as $date => $date_schedules) {

            $tmp_array = [];
            $schedule_groups = [];
            foreach ($date_schedules as $i => $tmp_schedule) {

                if (isset($tmp_array[$tmp_schedule->id]))
                    continue;
                $tmp_array[$tmp_schedule->id] = $tmp_schedule->start_time;
            }
            asort($tmp_array); //sort by start_time
            foreach ($tmp_array as $id => $start_time) {
                $schedule_groups[] = ['id' => $id, 'start_time' => $start_time];
            }

            $date_schedules1 = [];

            $groupIdx = 0;
            $next_group_start_time = isset($schedule_groups[$groupIdx + 1]) ? $schedule_groups[$groupIdx + 1]['start_time'] : null;
            $group_schedule_id = isset($schedule_groups[$groupIdx]) ? $schedule_groups[$groupIdx]['id'] : null;

            $next_group__schedule_id = null;

            foreach ($date_schedules as $i => $date_schedule1) {
                $campaign_id = $schedule->frame_id;
                if ($campaignHasTime && !isset($campaign) && !empty($campaign_id))
                    $campaign = SmartscreenCampaigns::findOneCached($campaign_id);

                if (!empty($group_schedule_id)) {
                    if ($date_schedule1->id != $group_schedule_id) {
                        continue;
                    } else
                        $group_schedule_id = null;
                }

                $next_schedule1 = isset($date_schedules[$i  + 1]) ? $date_schedules[$i + 1] : null;
                $date_schedule1_end_time = Smartscreen::getNextStartTime($date_schedule1->start_time, $date_schedule1->duration, 1, null, true);

                if (isset($next_schedule1) && !empty($next_group_start_time) && $next_schedule1->start_time >= $next_group_start_time) {
                    if ($date_schedule1_end_time < $next_group_start_time) {
                        if ($date_schedule1->duration > 0)
                            $date_schedules1[] = $date_schedule1;
                        $date_schedules1[] = Smartscreen::getDefaultSchedule(null, $date_schedule1_end_time, Smartscreen::getDurationBetween($date_schedule1_end_time, 0, $next_group_start_time));
                    } else {
                        $date_schedule1->duration = Smartscreen::getDurationBetween($date_schedule1->start_time, 0, $next_group_start_time);
                        if ($date_schedule1->duration > 0)
                            $date_schedules1[] = $date_schedule1;
                    }
                    $groupIdx += 1;
                    $next_group_start_time = isset($schedule_groups[$groupIdx + 1]) ? $schedule_groups[$groupIdx + 1]['start_time'] : null;
                    $group_schedule_id = isset($schedule_groups[$groupIdx]) ? $schedule_groups[$groupIdx]['id'] : null;
                } else {
                    //echo "$date_schedule1->id $date_schedule1->start_time $date_schedule1->duration <br/> \n";

                    if ($date_schedule1->duration > 0)
                        $date_schedules1[] = $date_schedule1;
                }
            }

            if (empty($date))
                $date = date("Y-m-d");

            //genereate schedules base on
            if (isset($campaign)) {
                //var_dump($campaign); die;
                $start_time = $campaign->start_time;
                $end_time = $campaign->end_time;
                $date_schedules1 = Smartscreen::generateSchedules($date_schedules1, $start_time, $end_time, null, 150, $for_api);
            } else if (false) {
                $start_time = FHtml::setting('smartscreen.test_start_time', "00:00");
                $end_time = FHtml::setting('smartscreen.test_end_time', "24:00");
                $date_schedules1 = Smartscreen::generateSchedules($date_schedules1, $start_time, $end_time, null, 150, $for_api);
            }

            //add default schedule !!
            if (empty($date_schedule1)) {
                $schedule_duration_between = Smartscreen::getDurationBetween('00:00', 0, '24:00');
                $schedule = Smartscreen::getDefaultSchedule('', '00:00', $schedule_duration_between, $defaultSchedule);
                $date_schedules1[] = $schedule;
            } else {
                $start_time_next = $date_schedules1[0]->start_time;
                if ($start_time_next > '00:00') {
                    $schedule_duration_between = Smartscreen::getDurationBetween('00:00', 0, $start_time_next);
                    $schedule = Smartscreen::getDefaultSchedule('', '00:00', $schedule_duration_between, $defaultSchedule);
                    $date_schedules1 = array_merge([$schedule], $date_schedules1);
                }

                $schedule = $date_schedules1[count($date_schedules1) - 1];
                $start_time_next = Smartscreen::getNextStartTime($schedule); // Smartscreen::getTime($date_schedules1[count($date_schedules1) - 1]->end_time);
                $schedule_duration_between = Smartscreen::getDurationBetween($schedule->start_time, 0, '24:00');
                if ($schedule_duration_between < $schedule->duration) {
                    $schedule->duration = $schedule_duration_between;
                    $date_schedule1[count($date_schedules1) - 1]  =  $schedule;
                } else if ($schedule_duration_between > $schedule->duration) {
                    $start_time_next = Smartscreen::getNextStartTime($schedule->start_time, $schedule->duration, 1, null, true);
                    $schedule_duration_between = $schedule_duration_between - $schedule->duration;
                    $schedule = Smartscreen::getDefaultSchedule('', $start_time_next, $schedule_duration_between, $defaultSchedule);
                    if (Smartscreen::isEqualDate($start_time_next, $date))
                        $date_schedules1[] = $schedule;
                }
            }
        }

        if (empty($date_schedules1)) {
            $schedule = Smartscreen::getDefaultSchedule("", "00:00", Smartscreen::getDurationBetween("00:00", 0, "24:00"));
            $date_schedules1[] = $schedule;
        }

        if ($for_api)
            $schedules[] = ['date' => $date_start, 'schedules' => $date_schedules1];
        else {
            $schedules = array_merge($schedules, $date_schedules1);
        }

        return $schedules;
    }

    public static function getNextStartTime($start_time, $duration = null, $loop = 1, $date = null, $showText = null)
    {


        if (empty($date))
            $date = self::Today();
        $inMinutes = static::settingDurationInMinutes();

        if (is_object($start_time)) {
            $schedule = $start_time;
            $start_time = $schedule->start_time;
            $duration = $schedule->duration; //duration of schedule is always in mins
            if (!isset($showText))
                $showText = true;
        }
        if (!isset($showText))
            $showText = false;

        $loop = 1; //does not multiply
        if (empty($start_time))
            return null;
        $start_time = is_numeric($start_time) ? $start_time : strtotime(date("$date $start_time"));
        $end_time = strtotime(date("Y-m-d H:i:s", $start_time + ($loop) * ($duration) * 60));

        $result = $showText ? date('H:i', $end_time) : $end_time;
        if ($result == '00:00')
            $result = '24:00';

        return trim($result);
    }

    public static function getScheduleDuration($schedule, $default_duration = null)
    {

        if (empty($default_duration))
            $default_duration = self::getDefaultDuration();

        if (is_array($schedule) && key_exists('data', $schedule)) {
            if (key_exists('duration', $schedule) && !empty($schedule['duration']))
                return (int) static::getDuration($schedule['duration']);
            $schedule_data = $schedule['data'];
        } else if (is_array($schedule) && !key_exists('data', $schedule)) {
            $schedule_data = $schedule;
        } else if (is_array($schedule) && key_exists('duration', $schedule)) {
            return (int) static::getDuration($schedule['duration']);
        } else if (is_object($schedule)) {
            if (!empty($schedule->duration))
                return (int) static::getDuration($schedule->duration);
            $schedule_data = $schedule->getData();
        }

        $duration = $default_duration;

        foreach ($schedule_data as $frame_content) {
            if (!isset($frame_content['data']))
                continue;

            $frame_data = $frame_content['data'];
            $frame_duration = 0;

            foreach ($frame_data as $content_data) {
                $frame_duration += $content_data['duration'];
            }

            if ($duration < $frame_duration) //get max frame duration
                $duration = $frame_duration;
        }
        return (int)$duration;
    }

    public static function getCurrentIme()
    {
        return FHtml::getRequestParam(['ime']);
    }

    public static function getBackgroundColor($color)
    {
        if (strlen($color) == 4)
            return $color  .  substr($color, 1);
        return  $color;
    }

    public static function fixFrame($frame)
    {
        if (empty($frame->marginTop) || $frame->marginTop > 100)
            $frame->marginTop = 0;
        if (empty($frame->marginLeft) || $frame->marginLeft > 100)
            $frame->marginLeft = 0;
        if (empty($frame->percentWidth) || $frame->percentWidth > 100)
            $frame->percentWidth = 100 - $frame->marginLeft;
        if (empty($frame->percentHeight) || $frame->percentHeight > 100)
            $frame->percentHeight = 100 - $frame->marginTop;

        return $frame;
    }

    public static function getDefaultSchedule($ime = '', $start_time = '', $duration = 0, $defaultSchedule = null)
    {
        $schedule = null;

        if (empty($ime)) {
            $ime = static::getCurrentIme();
        }

        $schedule = isset($defaultSchedule) ? $defaultSchedule : self::Cache("schedules_default");
        $schedule = null;
        $data = [];

        if (!isset($schedule)) {
            $content = self::getDefaultContent();
            if (!isset($content))
                return [];

            $contentData = self::getContentData($content, $ime);
            $useDefaultLayoutInDB = false;

            if ($useDefaultLayoutInDB) {
                $layout = SmartscreenLayouts::findOne(['is_default' => 1, 'is_active' => 1]);
                $frames = isset($layout) ? $layout->frameQuery : [];
                $data = [];
                foreach ($frames as $i => $frame) {
                    $frame = self::fixFrame($frame);
                    $data[] = array(
                        'id' => $i,
                        'name' => $frame->name,
                        'percentWidth' => $frame->percentWidth,
                        'percentHeight' => $frame->percentHeight,
                        'marginTop' => $frame->marginTop,
                        'marginLeft' => $frame->marginLeft,
                        'backgroundColor' =>  static::getBackgroundColor($frame->backgroundColor),
                        'contentLayout' => ($frame->contentLayout == 'main' ? $content->type : 'text'),
                        'data' => ($frame->contentLayout == 'main' ? $contentData : [['id' => $i, 'title' => '', 'url' => '', 'description' => '', 'kind' => '', 'duration' => 0, 'dataType' => 'text']])
                    );
                }
            } else {
                $contentData = self::cleanContent($contentData);
                $data = self::createFullScreenScheduleData($contentData, $content->type, "#000000"); // self::getDefaultBackgroundColor());
            }

            $schedule = new SmartscreenSchedulesAPI();
            $schedule->id = (int) str_replace(":", "", $start_time);
            $schedule->device_id = '';
            $schedule->content_id = [$content->id];
            $schedule->is_active = 1;
            $schedule->loop = 1;

            $schedule->setData($data);
            self::Cache("schedules_default", $schedule);
        }

        $schedule1 = clone $schedule;
        $schedule1->start_time = !empty($start_time) ? $start_time : '';
        if (self::isAPI()) {
            $schedule1->id = (int)str_replace(":", "", $start_time);
        } else {
            $schedule1->id = null;
        }
        $duration_max = Smartscreen::getDurationBetween($schedule1->start_time, 0, '24:00', true);
        if ($duration > $duration_max)
            $duration = $duration_max;

        $schedule1->duration = !empty($duration) ? $duration : self::getScheduleDuration($data);
        $schedule1->date = FHtml::Today();
        $schedule1->loop = 1;
        $schedule1->content_id = $schedule->content_id;
        $schedule1->device_id = "";

        return $schedule1;
    }

    public static function createFullScreenScheduleData($contentData = [],  $contentLayout = null, $background = null)
    {
        if (empty($background))
            $background = self::getDefaultBackgroundColor();
        $data = [];
        $data[] = array(
            'id' => 1,
            'name' => 'main',
            'percentWidth' => 100,
            'percentHeight' => 100,
            'marginTop' => 0,
            'marginLeft' => 0,
            'backgroundColor' => static::getBackgroundColor($background),
            'contentLayout' => $contentLayout,
            'data' => $contentData
        );
        return $data;
    }

    public static function getStandBySchedule($date = null, $start_time = null,  $duration = null)
    {
        $data = static::createFullScreenScheduleData(
            [['id' => 0,  'description' => '', 'title' => '', 'kind' => '', 'url' => '', 'duration' => 0, 'dataType' => 'text']],
            'text',
            '#000000'
        );

        $schedule = new SmartscreenSchedulesAPI();
        $schedule->id = 0;
        $schedule->device_id = '';
        $schedule->start_time = isset($start_time) ? $start_time : date('H:i');
        $schedule->date = isset($date) ? $date : FHtml::Today();
        $schedule->duration = isset($duration) ? $duration : self::getDefaultDuration();
        $schedule->is_active = 1;

        $schedule->setData($data);
        return [['date' => date('Y-m-d'), 'schedules' => [$schedule]]];
    }

    public static function getDeviceSchedulesByDate($schedules, $date_start = '')
    {
        if (empty($date_start))
            $date_start = self::Today();

        $date = self::Today();

        if (is_string($schedules))
            return FHtml::addError($schedules);

        foreach ($schedules as $item) {
            if ($item['date'] == $date_start || $item['date'] == $date) {
                $schedules = $item['schedules'];
                break;
            }
        }

        return $schedules;
    }

    public static function getDevice($ime)
    {
        if (is_numeric($ime)) //id
            $device = SmartscreenStationAPI::find()
                //->select(['id', 'ime', 'status', 'name'])
                ->where(['id' => $ime])
                ->one();
        else
            $device = SmartscreenStationAPI::find()
                //->select(['id', 'ime', 'status', 'name'])
                ->where(['ime' => $ime])
                ->one();

        if (!isset($device)) {
            return 'Device not registered';
        } else if ($device->status == -1 || !isset($device->status) || $device->status == FHtml::STATUS_REJECTED) {
            return 'Device is disabled';
        }

        return $device;
    }

    public static function refreshSchedulesAndPushToDevices($device_id = FHtml::NULL_VALUE, $action = Smartscreen::REFRESH_SCHEDULE, $channel_id = '', $schedule_id = '')
    {
        if (isset($device_id) && is_object($device_id)) // pass Schedule as object
        {
            $schedule = $device_id;
            $device_id = FHtml::getFieldValue($schedule, 'device_id');
            $channel_id = FHtml::getFieldValue($schedule, 'channel_id');
            $schedule_id = FHtml::getFieldValue($schedule, 'id');
        }

        if ($device_id !== FHtml::NULL_VALUE && !empty($device_id)) {

            $device = SmartscreenStationAPI::find()
                ->where(['id' => $device_id])
                ->all();

            if (!isset($device) || empty($device))
                return;

            if (!is_array($device))
                $device_arr = [$device];
            else
                $device_arr = $device;

            $result = [];
            foreach ($device_arr as $device) {
                $result[] = $device->ime;
            }

            $device_ime = implode(',', $result);
        } else {

            $device_ime = 'all';
        }
        try {
            return \Yii::$app->redis->executeCommand('PUBLISH', [
                'channel' => 'notification',
                'message' => Json::encode(['device' => $device_ime, 'action' => $action, 'channel_id' => $channel_id, 'schedule_id' => $schedule_id])
            ]);
        } catch (Exception $e) {
            //return FHtml::addError($e);
        }
    }

    public static function settingAutoAddDefaultSchedule()
    {
        return true; //always add
        $controller = FHtml::currentController();
        if ($controller != 'api' && !empty($controller))
            return true;

        return FHtml::setting('smartscreen.auto_add_default_schedule', true);
    }

    public static function settingCacheEnabled()
    {
        $debug = FHtml::getRequestParam('debug');
        if ($debug == true || $debug == 1)
            return false;
        return true; // always add
        return FHtml::setting('smartscreen.cache_enabled', true);
    }

    public static function settingConvertContentToIframe($type = '', $default = false)
    {
        $controller = FHtml::currentController();
        if ($controller != 'api' && !empty($controller))
            return true;

        if ($controller == 'api' && in_array($type, [SmartscreenContent::TYPE_VIDEO]))
            return false;

        $value = FHtml::setting('smartscreen.convert_content_to_iframe', $default);
        if ($value == 0 || $value == '0' || empty($value))
            return false;

        return (bool) $value;
    }

    public static function settingSlideContentSplitRecursive()
    {
        return false;
    }

    public static function settingAutoRestartAppWithUncaughtError($default = true)
    {
        $value = FHtml::setting('smartads.autoRestartAppWithUnCaughtError', $default);
        if ($value == 0 || $value == '0' || empty($value))
            return false;

        return (bool) $value;
    }

    public static function settingConvertImageToWebview($default = false)
    {
        $controller = FHtml::currentController();
        if ($controller != 'api' && !empty($controller))
            return true;

        $value = FHtml::setting('smartscreen.convert_image_to_iframe', $default);
        if ($value == 0 || $value == '0' || empty($value))
            return false;

        if ($value == 1 || $value == '1')
            return true;

        return false;
    }

    public static function settingConvertVideoToWebview($default = false)
    {
        $controller = FHtml::currentController();
        if ($controller != 'api' && !empty($controller))
            return true;

        $value = FHtml::setting('smartscreen.convert_video_to_iframe', $default);
        if ($value == 0 || $value == '0' || empty($value))
            return false;

        if ($value == 1 || $value == '1')
            return true;

        return false;
    }

    public static function settingConvertImageToIframe($default = false)
    {
        $controller = FHtml::currentController();
        if ($controller != 'api' && !empty($controller))
            return true;

        $value = FHtml::setting('smartscreen.convert_image_to_iframe');
        if ($value == 2 || $value == '2')
            return true;

        return false;
    }

    public static function settingConvertVideoToIframe($default = false)
    {
        $controller = FHtml::currentController();
        if ($controller != 'api' && !empty($controller))
            return true;

        $value = FHtml::setting('smartscreen.convert_image_to_iframe');
        if ($value == 2 || $value == '2')
            return true;

        return false;
    }

    public static function isAPI()
    {
        $controller = FHtml::currentController();
        return $controller == 'api';
    }

    public static function settingConvertMarqueToIframe($default = true)
    {
        $controller = FHtml::currentController();
        if ($controller != 'api' && !empty($controller))
            return true;

        return true;
        $value = FHtml::setting('smartscreen.convert_marquee_to_iframe', $default);
        if ($value == 0 || $value == '0' || empty($value))
            return false;

        return (bool) $value;
    }

    public static function getContentData(&$content, $ime = '', $isApi = null)
    {
        if (!isset($content))
            return [];

        $list_content = [];

        if (empty($ime))
            $ime = static::getCurrentIme();

        $convertContentToIFRAME = static::settingConvertContentToIframe($content->type);
        $convertImageToIFRAME = static::settingConvertImageToIframe();
        $convertVideoToIFRAME = static::settingConvertVideoToIframe();
        $convertMarqueeToIFRAME = static::settingConvertMarqueToIframe();

        //specific cases
        if (!in_array($content->type, self::BASIC_CONTENT_TYPE)) {
            $url = static::getSmartscreenContentUrl(['ime' => $ime, 'layout' => 'no', 'form_type' => $content->type, 'id' => $content->id, 'title' => $content->title, 'description' => $content->description, 'auto_refresh' => static::settingPageRefreshInterval()]);
            $list_content[] = array(
                'id' => $content->id,
                'title' => $content->title,
                'url' => $url,
                'description' => $content->description,
                'kind' => static::getFileKind($content->kind),
                'duration' => static::getDuration($content),
                'dataType' => SmartscreenContent::TYPE_HTML,
            );
            return $list_content;
        } else if (($convertContentToIFRAME == 1 || $convertVideoToIFRAME === true) && $content->type == SmartscreenContent::TYPE_SLIDE) {
            $url = static::getSmartscreenContentUrl(['id' => $content->id, 'layout' => 'no', 'auto_refresh' => 0, 'background' => $content->_bgcolor]);
            $list_content[] = array(
                'id' => $content->id,
                'title' => $content->title,
                'url' => $url,
                'description' => $content->description,
                'kind' => static::getFileKind($content->kind),
                'duration' => static::getDuration($content),
                'dataType' => SmartscreenContent::TYPE_HTML,
            );
            $content->type = SmartscreenContent::TYPE_HTML;
            return $list_content;
        } else if ($convertMarqueeToIFRAME && $content->type == SmartscreenContent::TYPE_TEXT && $content->_direction != 'normal') {
            $list_content[] = array(
                'id' => $content->id,
                'title' => $content->title,
                'url' => '',
                'description' => !empty($content->kind) ? $content->getContent() : $content->description,
                'kind' => $content->kind,
                'duration' => static::getDuration($content),
                'dataType' => SmartscreenContent::TYPE_HTML,
            );
            $content->type = SmartscreenContent::TYPE_HTML;
            return $list_content;
        } else if (in_array(
            $content->type,
            [SmartscreenContent::TYPE_HTML, SmartscreenContent::TYPE_URL]
        )) {

            if (starts_with($content->description, "http")) {
                $url = $content->description;
                $content->description = '';
            } else {
                $url = ''; //static::getSmartscreenContentUrl(['id' => $content->id, 'layout' => 'no', 'auto_refresh' => 0, 'background' => $content->_bgcolor]);
            }
            $list_content[] = array(
                'id' => $content->id,
                'title' => $content->title,
                'url' => $url,
                'description' => Smartscreen::getHtmlContent($content->description),
                'kind' => static::getFileKind($content->kind),
                'duration' => static::getDuration($content),
                'dataType' => SmartscreenContent::TYPE_HTML,
            );
            $content->type = SmartscreenContent::TYPE_HTML;

            return $list_content;
        } else if (
            $convertImageToIFRAME
            && in_array(
                $content->type,
                [SmartscreenContent::TYPE_IMAGE]
            )
        ) {

            $url = static::getSmartscreenContentUrl(['type' => SmartscreenContent::TYPE_IMAGE, 'id' => $content->id, 'layout' => 'no', 'auto_refresh' => 0, 'background' => $content->_bgcolor]);
            $list_content[] = array(
                'id' => $content->id,
                'title' => $content->title,
                'url' => $url,
                'description' => $content->description,
                'kind' => static::getFileKind($content->kind),
                'duration' => static::getDuration($content),
                'dataType' => 'html',
            );
            $content->type = SmartscreenContent::TYPE_HTML;

            return $list_content;
        } else if (
            $convertVideoToIFRAME
            && in_array(
                $content->type,
                [SmartscreenContent::TYPE_VIDEO]
            )
        ) {

            $url = static::getSmartscreenContentUrl(['type' => SmartscreenContent::TYPE_VIDEO, 'id' => $content->id, 'layout' => 'no', 'auto_refresh' => 0, 'background' => $content->_bgcolor]);
            $list_content[] = array(
                'id' => $content->id,
                'title' => $content->title,
                'url' => $url,
                'description' => $content->description,
                'kind' => static::getFileKind($content->kind),
                'duration' => static::getDuration($content),
                'dataType' => 'html',
            );
            $content->type = SmartscreenContent::TYPE_HTML;

            return $list_content;
        }

        //general cases
        if (!empty($content->url) || !empty($content->description)) {
            $list_content[] = array(
                'id' => $content->id,
                'title' => $content->title,
                'url' => static::getFileUrl($content->url, 'smartscreen-content'),
                'description' => $content->description,
                'kind' => static::getFileKind($content->kind),
                'duration' => static::getDuration($content),
                'dataType' => $content->type,
            );
        }

        $smartscreen_files = self::getObjectFiles($content);

        // nếu là gallary ảnh nhưng chỉ có 1 ảnh --> convert thành html
        if ($convertImageToIFRAME && $content->type == SmartscreenContent::TYPE_IMAGE && count($smartscreen_files) == 1) {
            $url = static::getSmartscreenContentUrl(['id' => $content->id, 'layout' => 'no', 'auto_refresh' => 0, 'background' => $content->_bgcolor]);
            $list_content[] = array(
                'id' => $content->id,
                'title' => $content->title,
                'url' => $url,
                'description' => $content->description,
                'kind' => static::getFileKind($content->kind),
                'duration' => static::getDuration($content),
                'dataType' => 'html',
            );
            $content->type = SmartscreenContent::TYPE_HTML;
        } else {
            $video_content = [];
            $image_content = [];

            foreach ($smartscreen_files as $smartscreen_file) {
                $url = static::getFileUrl($smartscreen_file->file);
                if (ends_with($url, FHtml::NO_IMAGE) && in_array($smartscreen_file->command, [SmartscreenContent::TYPE_IMAGE, SmartscreenContent::TYPE_VIDEO]))
                    continue;
                $dataType = self::getFileType($url, $smartscreen_file->command);

                if ($dataType ==  SmartscreenContent::TYPE_URL) {
                    $url = $smartscreen_file->description;
                    $smartscreen_file->description = '';
                    $dataType  = SmartscreenContent::TYPE_HTML;
                } else if ($dataType ==  'embed') {
                    $bgcolor = FHtml::getCurrentMainColor();
                    $dataType  = SmartscreenContent::TYPE_HTML;
                    $smartscreen_file->description = self::getHtmlContent($smartscreen_file->description);
                } else if ($dataType == 'marquee' || $dataType == 'text') {
                    $dataType = SmartscreenContent::TYPE_TEXT;
                    $smartscreen_file->title = '';
                    //$smartscreen_file->description = Smartscreen::getMarqueeContent($smartscreen_file->description) ;
                    $smartscreen_file->description = self::getHtmlContent($smartscreen_file->description);
                }
                $contentArr = array(
                    'id' => isset($content->id) ? $content->id : '',
                    'title' => $dataType == SmartscreenContent::TYPE_HTML ? '' : $smartscreen_file->description,
                    'url' => $url,
                    'kind' => static::getFileKind($smartscreen_file->file_kind),
                    'duration' => static::getDuration($smartscreen_file),
                    'description' => isset($smartscreen_file->description) ? $smartscreen_file->description : '',
                    'dataType' => $dataType,
                );
                if ($contentArr['dataType'] == SmartscreenContent::TYPE_IMAGE)
                    $image_content[] = $contentArr;
                else if ($contentArr['dataType'] == SmartscreenContent::TYPE_VIDEO)
                    $video_content[] = $contentArr;
                $list_content[] = $contentArr;
            }
            if ($content->type == SmartscreenContent::TYPE_SLIDE) {
                if (!$convertContentToIFRAME) {
                } else {
                    if (!empty($video_content)) {
                        $list_content = $video_content;
                        $content->type = SmartscreenContent::TYPE_VIDEO;
                    } else if (!empty($image_content)) {
                        $list_content = $image_content;
                        $content->type = SmartscreenContent::TYPE_IMAGE;
                    }
                }
            }
        }

        $result = self::cleanContent($list_content);
        return $result;
    }

    public static function getHtmlContent($content, $showCenterContainer = false, $bgcolor = null)
    {
        if (!$showCenterContainer)
            return $content;

        if (!isset($bgcolor))
            $bgcolor = FHtml::getCurrentMainColor();

        return trim(@"<html style='height:100%;width:100%'>
                <head><meta charset='UTF-8'/></head>
                    <style>
                        @keyframes marquee {
                            0%   { transform: translate(0, 0); }
                            100% { transform: translate(-100%, 0); }
                        }
                    </style>
                    <body style='background-color:$bgcolor;height:100%;width:100%'>
                        <div class='outer' style='display: table;position: absolute;top: 0;left: 0;height: 100%;width: 100%;background-color:$bgcolor'>
                            <div class='middle' style='display: table-cell;vertical-align: middle;'>
                                <div class='inner' style='text-align:center;margin-left: auto;margin-right: auto;'>
                                    $content
                                </div>
                            </div>
                        </div>
                    </body>
                </html>");
    }

    public static function getMarqueeContent($description, $_background = null, $_size = null, $_height = null, $_padding = null, $_margin = null, $_bgcolor = '', $_speed = '', $_color = '', $_direction = '', $_font = null)
    {
        $content = '';
        if (empty($_background))
            $background = FHtml::getRequestParam(['background', 'bg'], FHtml::settingWebsiteMainColor());
        else
            $background = $_background;

        if (empty($_speed))
            $_speed = 30;

        if (is_numeric($_size))
            $font_size  = "{$_size}px";
        else if (empty($_size) || strtolower($_size) == 'auto')
            $font_size = "30vh";
        else
            $font_size  = $_size;

        if (is_numeric($_font))
            $_font = 'Arial';

        if (empty($_color))
            $_color = "#ffffff";

        if (is_numeric($_height))
            $height = "{$_height}px";
        else if (empty($_height))
            $height = '100%';
        else
            $height = $_height;

        if (is_numeric($_padding))
            $padding = "{$_padding}px";
        else if (empty($_padding))
            $padding = '0px';
        else
            $padding = $_padding;

        if (is_numeric($_margin))
            $margin = "{$_margin}px";
        else if (empty($_margin))
            $margin = '0px';
        else
            $margin = $_margin;

        if (empty($_style))
            $style = [];
        else {
            $style = [];
            if (is_array($_style)) {
                foreach ($_style as $_style) {
                    if (in_array($_style, ['italic']))
                        $style[] = "font-style:$_style";
                    else if (in_array($_style, ['bold', 'lighter', 'light']))
                        $style[] = "font-weight:$_style";
                    else if (in_array($_style, ['center', 'justify']))
                        $style[] = "text-align:$_style";
                    else if (in_array($_style, ['top', 'middle', 'bottom']))
                        $style[] = "vertical-align:$_style";
                    else if (in_array($_style, ['overline', 'line-through', 'underline', 'underline overline']))
                        $style[] = "text-decoration:$_style";
                    else if (!empty($_style))
                        $style[] = $_style;
                }
            }
        }

        $transform = [];

        if (!empty($_scaleX) && is_numeric($_scaleX))
            $transform[] = ($_scaleX > 20 ? $_scaleX / 100 : $_scaleX);
        else
            $transform[] = 1;

        if (!empty($_scaleY) && is_numeric($_scaleY))
            $transform[] = ($_scaleY > 20 ? $_scaleY / 100 : $_scaleY);
        else
            $transform[] = 1;
        $style[] = "transform:scale(" . implode(',', $transform) . ')';

        $style = implode(';', $style);

        $description = str_replace("\n", '<br/>', $description);
        if ($_direction == 'no-scroll') {
            $content = "<div style='width: 100%;height:$height;padding:$padding;background-color: $_bgcolor;color:$_color;word-wrap: break-word;text-align: justify;text-justify: inter-word;font-family:\"$_font\";$style;font-size:{$font_size};margin:$margin'>$description</div>";
        } else {
            $content = "<div class='marquee' style=\"width: 100%;height:$height;font-family:'$_font';background-color:$background;margin:$margin;padding: $padding;color:$_color;font-size:$font_size;white-space: nowrap;overflow: hidden;box-sizing: border-box;$style;\">
	            <p style=\"display: inline-block;padding-left:100%;animation: marquee {$_speed}s linear infinite;\"><b>$description</b></p></div>";
        }
        return trim($content);
    }

    public static function cleanContent($list_content)
    {
        //clean up data, remove empty data etc
        $result = [];
        foreach ($list_content as $i => $content) {
            if (in_array($content['dataType'], ['video', 'image', 'file', 'gallery', 'url']) && empty($content['url']))
                continue;
            $result[] = $content;
        }

        return $result;
    }

    public static function getFileType($file, $default = '')
    {
        $arr = explode('.', strtolower($file));
        $extension = count($arr) > 0 ? $arr[count($arr) - 1] : '';
        if (in_array($extension, ['mp4', 'avi', 'mov', 'mkv'])) {
            $content_type = SmartscreenContent::TYPE_VIDEO;
        } else if (in_array($extension, ['jpg', 'png', 'bmp', 'jpeg'])) {
            $content_type = SmartscreenContent::TYPE_IMAGE;
        } else {
            $content_type = $default;
        }
        return $content_type;
    }

    public static function getVideoDuration1($file, $format = 'minute')
    {
        if (file_exists($file)) {
            ## open and read video file
            $handle = fopen($file, "r");
            ## read video file size
            $contents = fread($handle, filesize($file));

            fclose($handle);
            $make_hexa = hexdec(bin2hex(substr($contents, strlen($contents) - 3)));

            if (strlen($contents) > $make_hexa) {
                $pre_duration = hexdec(bin2hex(substr($contents, strlen($contents) - $make_hexa, 3)));
                $post_duration = $pre_duration / 1000;
                $timehours = $post_duration / 3600;
                $timeminutes = ($post_duration % 3600) / 60;
                $timeseconds = ($post_duration % 3600) % 60;
            }
            //echo $post_duration / 60; die;
            if ($format == 'minute' || $format == 'm' || $format == 'time')
                return $timehours * 60 + $timeminutes + ceil($timeseconds / 60);
            else if ($format == 'second' || $format == 's')
                return ($timehours * 60 * 60 + $timeminutes * 60 + $timeseconds);
            else if ($format == 'milisecond' || $format == 'ms')
                return ($timehours * 60 * 60 + $timeminutes * 60 + $timeseconds) * 1000;
            else if ($format == 'hours' || $format == 'h')
                return number_format(($timehours * 60 + $timeminutes + ceil($timeseconds / 60)) / 60, 2);

            $timehours = explode(".", $timehours);
            $timeminutes = explode(".", $timeminutes);
            $timeseconds = explode(".", $timeseconds);
            $duration = $timehours[0] . ":" . $timeminutes[0] . ":" . $timeseconds[0];
            return $duration;
        } else {

            return false;
        }
    }

    public static function getVideoDuration($file, &$format = 'time')
    {
        $info = static::getVideoInfo($file);

        $post_duration = isset($info['playtime_seconds']) ? $info['playtime_seconds'] : null;
        if (empty($post_duration))
            return null;
        $timehours = floor($post_duration / 3600);
        $timeminutes = floor(($post_duration % 3600) / 60);
        $timeseconds = ($post_duration % 3600) % 60;
        if ($post_duration < 60) {
            $format = 'second';
            return ceil($post_duration);
        }

        //echo $post_duration / 60; die;
        if ($format == 'minute' || $format == 'm' || $format == 'time')
            return $timehours * 60 + $timeminutes + number_format($timeseconds / 60, 2);
        else if ($format == 'second' || $format == 's')
            return ($timehours * 60 * 60 + $timeminutes * 60 + $timeseconds);
        else if ($format == 'milisecond' || $format == 'ms')
            return ($timehours * 60 * 60 + $timeminutes * 60 + $timeseconds) * 1000;
        else if ($format == 'hours' || $format == 'h')
            return number_format(($timehours * 60 + $timeminutes + ceil($timeseconds / 60)) / 60, 2);

        return isset($info['playtime_string']) ? ceil((float) $info['playtime_string']) : null;
    }

    public static function getVideoInfo($file, $field = '')
    {
        return [];

        //        if (file_exists($file)) {
        //            $path = \Yii::getAlias("@common/components/getid3/getid3.php");
        //            try {
        //                require_once($path);
        //                $getID3 = new \getID3;
        //                $info = $getID3->analyze($file);
        //                if (!empty($field) && is_string($field))
        //                    return isset($info[$field]) ? $info[$field] : null;
        //                else if (!empty($field) && is_array($field)) {
        //                    $array = $field;
        //                    foreach ($array as $i => $field) {
        //                        unset($array[$i]);
        //                        $array[$field] = isset($info[$field]) ? $info[$field] : null;
        //                    }
        //                    return $array;
        //                }
        //                return $info;
        //            } catch (Exception $exception) {
        //                return false;
        //            }
        //        } else {
        //            return false;
        //        }
    }


    public static function getContentFiles($content, $getFullData = false)
    {
        if (is_object($content)) {
            $listContent = self::getContentData($content);
        } else if (is_array($content)) {
            $listContent = $content;
        } else {
            return [];
        }
        $result = [];

        foreach ($listContent as $item) {

            $type = key_exists('dataType', $item) ? $item['dataType'] : (key_exists('type', $item) ? $item['type'] : '');
            $is_file = in_array($type, ['video', 'mp3', 'audio', 'file', 'image', 'image']);
            if (key_exists('url', $item) && $is_file && !empty($item['url'])) {
                if ($getFullData)
                    $result[] = ['url' => $item];
                else
                    $result[] = ['url' => $item['url']];
            }
        }
        return $result;
    }

    public static function getScheduleFiles($content, $getFullData = false)
    {
        if (is_object($content)) {
            $listContent = self::getScheduleData($content);
        } else if (is_array($content)) {
            $listContent = $content;
        } else {
            return [];
        }


        $result = [];

        if (key_exists('url', $listContent)) {
            $result = self::getContentFiles([$listContent]);
            return $result;
        }

        foreach ($listContent as $item1) {

            if (!is_array($item1))
                continue;

            if (is_array($item1) && key_exists('schedules', $item1)) {
                $item1 = $item1['schedules'];
            }

            if (is_array($item1) && key_exists('data', $item1)) {
                $result = array_merge($result, self::getContentFiles($item1['data'], $getFullData));
            } else if (is_array($item1)) {
                foreach ($item1 as $item2) {
                    if (is_array($item2) && key_exists('data', $item2)) {
                        $result = array_merge($result, self::getContentFiles($item2['data'], $getFullData));
                    }
                }
            } else {
            }
        }

        return $result;
    }

    public static function getDeviceScheduleFiles($content, $getFull = false)
    {
        if (is_object($content) && FHtml::field_exists($content, 'url')) {
            $files = ['url' => $content->url];
            return $files;
        }
        if (is_string($content)) { //$content == ime
            $content = Smartscreen::getDeviceSchedules($content, Smartscreen::Today());
        }

        $files = [];

        if (is_object($content) && FHtml::field_exists($content, 'data'))
            $content = $content['data'];

        if (is_array($content)) {
            foreach ($content as $item) {
                if (is_array($item) && key_exists('schedules', $item))
                    $item_arr = $item['schedules'];
                else if (is_array($item) && key_exists('data', $item))
                    $item_arr = $item['data'];
                else
                    $item_arr = [$item];

                if (!is_array($item_arr))
                    $item_arr = [$item_arr];

                foreach ($item_arr as $item) {
                    $arr = Smartscreen::getScheduleFiles($item, $getFull);
                    foreach ($arr as $file) {
                        if (!in_array($file, $files))
                            $files[] = $file;
                    }
                    //$files = array_merge($files, Smartscreen::getScheduleFiles($item));
                }
            }
        }

        return $files;
    }

    public static function getDefaultChannel()
    {
        $channel = SmartscreenChannels::getOne(['is_default' => 1, 'is_active' => 1]);
        if (!isset($channel))
            $channel = SmartscreenChannels::getOne([]);
        return $channel;
    }

    public static function getDefaultContent()
    {
        $content = SmartscreenContentAPI::getOne(['is_default' => 1, 'is_active' => 1]);
        if (!isset($content)) {
            $content = new SmartscreenContentAPI();
            $content->id = 0;
            $content->title = '';
            $content->description = '';
            $content->type = 'text';
        }

        return $content;
    }

    public static function getScheduleData($schedule, $ime = '')
    {
        if (!isset($schedule))
            return null;
        $data = null;

        if (is_array($schedule)) {
            $schedule = $schedule[0]["schedules"][0];
        } else if (is_numeric($schedule)) {
            $schedule = SmartscreenSchedules::findOne($schedule);
        }
        if (is_object($schedule)) {

            if (!is_array($schedule->content_id)) {
                $schedule->content_id = json_decode($schedule->content_id);
            }

            if (empty($ime))
                $ime = static::getCurrentIme();

            if (FHtml::field_exists($schedule, 'data')) // if is API object
            {
                if (isset($schedule->data))
                    $data = $schedule->data;
                else
                    $data = [];
            } else {
                $layout = isset($schedule->layout) ? $schedule->layout : null;
                $content_ids = $schedule->content_id;

                $data = self::getLayoutData($layout, $content_ids, $ime);
            }
        }

        return $data;
    }

    public static function getLayoutData($layout, $content_ids, $ime = '')
    {
        if (empty($ime))
            $ime = static::getCurrentIme();

        if (is_object($layout))
            $list_frame = isset($layout) ? $layout->frameQuery : null;
        else if (is_array($layout))
            $list_frame = $layout;
        else
            return [];
        $result = [];


        $data = array();
        if (!empty($list_frame)) {
            if (!is_array($content_ids))
                $content_ids = json_decode($content_ids, true);

            foreach ($list_frame as $key => $frame) {
                $frame = self::fixFrame($frame);
                $list_content = array();
                if (!isset($content_ids[$key])) {
                    continue;
                }

                $content = SmartscreenContent::findOneCached($content_ids[$key]);

                if (!isset($content))
                    continue;

                $list_content = Smartscreen::getContentData($content, $ime);
                $contentLayout = !in_array($content->type, self::BASIC_CONTENT_TYPE) ? 'html' : $content->type;

                $data[] = array(
                    'id' => $frame->id,
                    'name' => $frame->name,
                    'marginLeft' => $frame->marginLeft,
                    'marginTop' => $frame->marginTop,
                    'percentWidth' => $frame->percentWidth,
                    'percentHeight' => $frame->percentHeight,
                    'backgroundColor' => static::getBackgroundColor($frame->backgroundColor),
                    'contentLayout' => $contentLayout,
                    'data' => $list_content
                );
            }
        }

        return $data;
    }

    public static function getScheduleBackgroundAudio($schedule)
    {
        if (!isset($schedule))
            return null;

        $list_frame = isset($schedule->layout) ? $schedule->layout->frameQuery : null;

        $list_audio = array();
        if (!is_array($schedule->content_id)) {
            $schedule->content_id = json_decode($schedule->content_id);
        }
        if (!empty($list_frame)) {
            $count_list_frame = count($list_frame);
            $count_content =  count($schedule->content_id);

            if ($count_content > $count_list_frame) {
                $last_index = $count_content - 1;
                $content_id = $schedule->content_id[$last_index];

                if (!empty($content_id)) {
                    $content_audio = SmartscreenContentAPI::getOne(['id' => $content_id]);

                    if ($content_audio) {
                        $list_audio = Smartscreen::getContentData($content_audio);
                    }
                }
            }
        }

        return $list_audio;
    }

    public static function Today($time = '')
    {
        if (empty($time))
            return date("Y-m-d");
        else
            return date("Y-m-d", strtotime($time));
    }

    public static function getDate($date_start, $date_plus = '')
    {

        return  date('Y-m-d', strtotime(trim("$date_start $date_plus")));
    }

    public static function Now()
    {
        return time();
    }

    public static function getCurrentRoom()
    {
        return FHtml::getRequestParam(['room', 'room_id']);
    }

    public static function getCurrentDepartment()
    {
        return FHtml::getRequestParam(['dept', 'dept_id']);
    }

    public static function getQueueModels($object_type, $params = [], $ime = '')
    {
        if (empty($ime))
            $ime = static::getCurrentIme();

        $room_id = static::getCurrentRoom();
        $dept_id = static::getCurrentDepartment();
        $name = '';
        $device = null;

        if (is_object($ime)) {
            $device = $ime;
            $ime = $device->ime;
        }

        if (!empty($ime)) {
            $device_id = null;
            if (is_string($ime)) {
                if (!isset($device)) {
                    $device = SmartscreenStation::findOne(['ime' => $ime]);
                }
                $device_id = isset($device) ? $device->id : null;
                if (empty($room_id))
                    $room_id = isset($device) ? $device->room_id : '';
                if (empty($dept_id))
                    $dept_id = isset($device) ? $device->dept_id : '';

                $name = isset($device) ? $device->name : '';
            } else {
                $device_id = $ime;
            }
            if (is_array($params)) {
                $params = array_merge($params, ['device_id' => [$ime, $device_id]]);
            } else {
            }
        }

        //if not set room_id, then get from name
        $name_arr = explode('-', $name);
        if (empty($room_id)) {
            if (count($name_arr) > 2) {
                $room_id = $name_arr[2];
                $room_id = FHtml::strReplace($room_id, ["LCD" => '', 'LC' => '']);
            }
        }

        if (empty($dept_id)) {
            if (count($name_arr) > 3 && !is_numeric($name[3])) {
                $dept_id = $name_arr[3];
            }
        }

        $api_url = Smartscreen::settingHISAPI_PatientsList();

        if (!empty($room_id) && !empty($api_url)) {
            $api_url = FHtml::strReplace($api_url, ['{dept_id}' => $dept_id, '{room_id}' => $room_id]);
            $data = FApi::getUrlContent($api_url);
            if (!is_array($data)) {
                $data = [];
                if (strtolower(self::settingAppMode()) == 'test') {
                    $data['title'] = FHtml::t('common', 'Room') . ' 2';
                    $data['doctor'] = 'BS Nguyen Van A';
                    $pendinglist = [];
                    $callinglist = [];
                    $i = rand(2, 5);
                    for ($j = 0; $j < $i; $j++) {
                        $a = rand(0, 100);
                        $pendinglist[] = ['receptno' => $a, 'patientname' => FHtml::t('common', 'Patient') . ' ' . $a, 'status' => 'O', 'status_desc' => FHtml::t('common', 'Waiting')];
                    }

                    $i = rand(1, 2);
                    for ($j = 0; $j < $i; $j++) {
                        $a = rand(0, 100);

                        $callinglist[] = ['receptno' => $a, 'patientname' => FHtml::t('common', 'Patient') . ' ' . $a, 'status' => 'C', 'status_desc' => FHtml::t('common', 'Calling')];
                    }
                    $data['pendinglist'] = $pendinglist;
                    $data['callinglist'] = $callinglist;
                }

                return $data;
            }

            $descrption = '';
            $screen_name = '';
            if (key_exists('pendinglist', $data)) {
                $pendinglist = $data['pendinglist'];
                $result = [];
                foreach ($pendinglist as $item) {
                    if (key_exists('patientname', $item))
                        $item['patientname'] = mb_strtoupper($item['patientname']);
                    if (key_exists('status_desc', $item))
                        $item['status_desc'] = mb_strtoupper($item['status_desc']);
                    $result[] = $item;
                }
                $data['pendinglist'] = $result;
            }
            if (key_exists('callinglist', $data)) {
                $callinglist = $data['callinglist'];
                $result = [];
                foreach ($callinglist as $item) {
                    if (key_exists('patientname', $item))
                        $item['patientname'] = mb_strtoupper($item['patientname']);
                    if (key_exists('status_desc', $item))
                        $item['status_desc'] = mb_strtoupper($item['status_desc']);
                    $result[] = $item;
                }
                $data['callinglist'] = $result;
            }
            if (key_exists('title', $data) && isset($device) && $device->ScreenName != $data['title']) {
                $screen_name = FModel::getSqlValue($data['title']);
                $screen_name = FHtml::strReplace($screen_name, [' Phong ' => ' Phòng ']);
                $data['title'] = $screen_name;
            }

            if (key_exists('doctor', $data) && isset($device) && $device->description != $data['doctor']) {
                $descrption = FModel::getSqlValue($data['doctor']);

                if (!\yii\helpers\StringHelper::startsWith(strtolower($descrption), 'bs'))
                    $descrption = 'BS ' . $descrption;

                $data['doctor'] = $descrption;
            }

            if (!empty($data) && is_array($data)) {
                return $data;
            }
            return $data;
        }

        return [];
    }

    public static function updateQueueModel($ime, $name, $id = '', $code = '', $service = '', $status = '', $counter = '', $ticket = '', $sort_order = 0, $action = '')
    {
        $model = null;
        if (!empty($id)) {
            $model = SmartscreenQueue::findOne(['id' => $id, 'ime' => $ime]);
        } else if (!empty($name)) {
            $model = SmartscreenQueue::findOne(['name' => $name, 'ime' => $ime]);
        }

        if (isset($model) && in_array($action, ['delete', 'remove'])) {
            $model->delete();
            return $model;
        }

        if (!isset($model)) {
            $model = new SmartscreenQueue();
        }
        $model->code = $code;
        $model->name = $name;
        $model->service = $service;
        $model->status = $status;
        $model->sort_order = $sort_order;
        $model->device_id = $ime;
        $model->counter = $counter;
        $model->ticket = $ticket;
        $model->is_active = 1;
        $model->application_id = FHtml::currentApplicationId();
        $model->created_date = FHtml::Now();
        $model->created_user = FHtml::currentUserId();

        $model->save();

        return $model;
    }

    public static function getContentTypeComboArray()
    {
        return FHtml::getComboArray('smartscreen_content', 'smartscreen_content', 'type', true, 'id', 'name');
    }

    public static function updateSmartFile($list_content, $id, $model, $modelHasFile = null)
    {
        if (!is_array($list_content))
            $list_content = [];

        if (!isset($list_content))
            return;

        $object_type = FHtml::getTableName($model);

        $oldListFile = array();
        $newListFile = array();
        if (!isset($modelHasFile))
            $modelHasFile = $model;

        // Tim tat ca file cu
        $listFile = SmartscreenFile::find()
            ->where(['object_id' => $id, 'object_type' => FHtml::getTableName($model)])
            ->orderBy('sort_order')
            ->all();

        foreach ($listFile as $file) {
            $oldListFile[] = $file->id;
        }

        $last_order = 0;
        $cloneMode = false;
        foreach ($list_content as $key => $content) {
            if (is_object($content)) {
                $cloneMode = true;
                break;
            }
        }
        if ($cloneMode) {
            \Yii::$app->db->createCommand("DELETE FROM smartscreen_file WHERE object_type = '$object_type' and object_id = '$id'")
                ->execute();
        }

        foreach ($list_content as $key => $content) {
            if (is_object($content)) {
                $cloneMode = true;
                $smart_file = clone $content;
                $smart_file->id = null;
                $smart_file->isNewRecord = true;
                $smart_file->object_id = $id;
                $smart_file->object_type = FHtml::getTableName($model);
                $smart_file->save();
            } else if (is_array($content)) {
                $smart_file = null;
                if (!empty($content['id']))
                    $smart_file = SmartscreenFile::findOne($content['id']);

                if (!isset($smart_file)) {
                    $smart_file = new SmartscreenFile();
                }

                $video_file = FUploadedFile::getInstance($modelHasFile, 'list_content[' . $key . '][_file_upload]');

                $video_file_name = '';
                if (isset($video_file)) {
                    $extention = substr($video_file, strrpos($video_file->name, ".") + 1);
                    $video_file_name = $smart_file->file;
                    if (!empty($video_file_name)) {
                        $old_file = FFile::getFilePath($video_file_name, 'smartscreen-file');
                        if (!ends_with($old_file, $video_file_name)) //do not no_image.jpg
                            FFile::deleteFile(FFile::getFilePath($video_file_name, 'smartscreen-file'));
                        $video_file_name = '';
                    }

                    if (empty($video_file_name)) //$video_file_name must be unique and new
                    {
                        $idx = $key + 1;
                        $video_file_name = FHtml::getTableName($model) . "_{$id}_{$idx}_" . (!empty($content['id']) ? $content['id'] : '') . '_' . uniqid() . ".$extention";
                    }
                    $result = FHtml::saveUploadedFile($video_file, FHtml::getFullUploadFolder('smartscreen-file'), $video_file_name);
                    if (!is_string($result))
                        $video_file_name = '';

                    $content['command'] = Smartscreen::getFileType($video_file_name, isset($content['command']) ? $content['command'] : '');
                    $smart_file->file = (!empty($video_file_name) ? $video_file_name : $smart_file->file);
                } else {
                    if (isset($content['command']) && !in_array($content['command'], [SmartscreenContent::TYPE_VIDEO, SmartscreenContent::TYPE_IMAGE])) {
                        $smart_file->file = '';
                    } else {
                        $smart_file->file = (!empty($video_file_name) ? $video_file_name : $smart_file->file);
                    }
                }

                $smart_file->object_id = $id;
                $smart_file->object_type = FHtml::getTableName($model);
                $smart_file->command = isset($content['command']) ? $content['command'] : $smart_file->command;
                if (empty($smart_file->command))
                    $smart_file->command = $model->type; //image,video,slide

                $smart_file->description = isset($content['description']) ? $content['description'] : $smart_file->description;
                $smart_file->file_kind = isset($content['file_kind']) ? $content['file_kind'] : Smartscreen::getFileType($smart_file->file); // $smart_file->file_kind;
                $smart_file->file_duration = isset($content['file_duration']) ? $content['file_duration'] : $smart_file->file_duration;
                $smart_file->is_active = 1;

                if (empty($content['sort_order']) || !is_numeric($content['sort_order'])) {
                    $smart_file->sort_order = $key + 1;
                } else {
                    $smart_file->sort_order = $content['sort_order'];
                }

                if (
                    in_array($smart_file->command, ['video', SmartscreenScripts::COMMAND_DSPCLIP, 'image'])
                    && empty($smart_file->file)
                    && starts_with($smart_file->description, 'http')
                ) {
                    $smart_file->file = $smart_file->description;
                    $smart_file->description = '';
                }

                if (!$smart_file->save()) {
                    FHtml::addError($smart_file->errors);
                }

                $newListFile[] = $content['id'];
            }
        }

        if (!$cloneMode) {
            //        Xoa cac file khac
            $compareFile = array_diff($oldListFile, $newListFile);

            if (!empty($compareFile)) {
                $file_delete = implode(',', $compareFile);

                \Yii::$app->db->createCommand("DELETE FROM smartscreen_file WHERE id IN ($file_delete)")
                    ->execute();
            }
        }
    }

    public static function createSmartFile($list_content, $id, $model)
    {
        $time_string = time();
        if (!isset($list_content))
            return;

        foreach ($list_content as $key => $content) {
            $video_file = FUploadedFile::getInstance($model, 'list_content[' . $key . '][file_upload]');

            $extention = substr($video_file, strrpos($video_file->name, ".") + 1);

            $video_file_name = strtolower($time_string . $key . '_video.' . $extention);

            FHtml::saveUploadedFile($video_file, FHtml::getRootFolder() . '/backend/web/upload/smartscreen-file', $video_file_name);

            $smart_file = new SmartscreenFile(); //create

            $smart_file->object_id = $id;
            $smart_file->file = $video_file_name;
            $smart_file->description = $content['description'];
            $smart_file->file_kind = $content['file_kind'];
            $smart_file->file_duration = $content['file_duration'];

            $smart_file->save();
        }
    }

    public static function getDefaultBackgroundColor()
    {
        return FHtml::settingWebsiteBackground('#274e13');
    }

    public static function getDefaultDeviceStatus()
    {
        return self::DEVICE_DEFAULT_STATUS || FHtml::setting('smartads.default_device_status', 0);
    }

    public static function settingCheckDeviceIsRegistered()
    {
        return !self::getDefaultDeviceStatus();
    }

    public static function settingSaveDeviceNameWhenRegistered()
    {
        return FHtml::setting('smartads.save_device_name', true);
    }

    public static function settingAutoOverrideDeviceNameWhenRegistered()
    {
        return true;
        //return FHtml::setting('smartads.override_device_name', true);
    }

    public static function settingHISAPIServer($default_value = '10.0.0.94:8089')
    {
        $url = FHtml::setting('smartads.his_api_server');
        if (empty($url))
            return $default_value;
        return $url;
    }

    //API Danh sach doi kham
    public static function settingHISAPI_PatientsList($default_value = "/api/wb/exam_pending?dept={dept_id}&room={room_id}")
    {
        $url = self::settingHISAPIServer();
        if (!empty($url))
            return $url . FHtml::setting('smartads.his_api_patientslist', $default_value);
        return '';
    }

    //API Danh sach khoa
    public static function settingHISAPI_DepartmentsList($default_value = "/api/wb/deptlist")
    {
        $url = self::settingHISAPIServer();
        if (!empty($url))
            return $url . FHtml::setting('smartads.his_api_departmentslist', $default_value);
        return '';
    }

    //API Danh sach khoa
    public static function settingHISAPI_RoomsList($default_value = "/api/wb/roomlist?dept={dept_id}")
    {
        $url = self::settingHISAPIServer();
        if (!empty($url))
            return $url . FHtml::setting('smartads.his_api_roomslist', $default_value);
        return '';
    }

    public static function getHISRoomList($dept_id = '')
    {
        $deptList = [];
        if (empty($dept_id)) {
            $deptList1 = self::getHISDeptList();
            foreach ($deptList1 as $id => $name) {
                $deptList[] = $id;
            }
        } else {
            $deptList = [$dept_id];
        }

        $result = [];
        $room_url = self::settingHISAPI_RoomsList();
        foreach ($deptList as $dept_id1) {
            $api = FHtml::strReplace($room_url, ['{dept_id}' => $dept_id1]);
            $roomList = FApi::getUrlContent($api);
            if (is_array($roomList)) {
                foreach ($roomList as $item) {
                    $result = array_merge($result, [($dept_id1 . ':' . $item['id']) => ('[' . $dept_id1 . '_roomid_' . $item['id'] . '] ' . $item['name'] . ' (' . $item['doctor_name'] . ')')]);
                }
            }
        }

        return $result;
    }

    public static function getHisContentUrl($model, $params = [], $room_id = null, $dept_id = null, $form_type = SmartscreenContent::TYPE_HIS_VIMES)
    {
        if (is_array($model)) {
            $params  = $model;
            $model = null;
        }

        if (empty($params))
            $params = [];

        if (!empty($dept_id))
            $params = array_merge($params, ['dept' => $dept_id]);

        if (!empty($room_id))
            $params = array_merge($params, ['room' => $room_id]);

        if (!empty($form_type))
            $params = array_merge($params, ['form_type' => $form_type]);

        return static::getSmartscreenContentUrl($model, $params);
    }

    public static function getSmartscreenContentUrl($model, $params = [])
    {
        if (is_array($model)) {
            $params  = $model;
            $model = null;
        }

        if (empty($params))
            $params = ["layout" => "no", "ime" => (is_string($model) ? $model : (isset($model) ? $model->ime : '')), 'auto_refresh' => self::settingPageRefreshInterval()];

        if (!key_exists('auto_refresh', $params))
            $params = array_merge($params, ['auto_refresh' => self::settingPageRefreshInterval()]);

        if (!key_exists('layout', $params))
            $params = array_merge($params, ['layout' => 'no']);

        $url = FHtml::createUrl("/smartscreen/content", $params, true);
        if (!starts_with($url, 'http')) {
            $url = FHtml::getCurrentDomain() . $url;
        }
        return $url;
    }

    public static function getSmartscreenScheduleUrl($model, $params = [])
    {
        if (is_array($model)) {
            $params  = $model;
            $model = null;
        }

        if (empty($params))
            $params = ["layout" => "no", "id" => is_object($model) ? $model->id : '',  "ime" => (is_string($model) ? $model : (isset($model) ? $model->ime : '')), 'auto_refresh' => self::settingPageRefreshInterval()];

        if (!key_exists('auto_refresh', $params))
            $params = array_merge($params, ['auto_refresh' => self::settingPageRefreshInterval()]);

        if (!key_exists('layout', $params))
            $params = array_merge($params, ['layout' => 'no']);

        $url = FHtml::createUrl("/smartscreen/schedules", $params, true);
        if (!starts_with($url, 'http')) {
            $url = FHtml::getCurrentDomain() . $url;
        }
        return $url;
    }

    public static function getHISDeptList()
    {
        $dept_url = self::settingHISAPI_DepartmentsList();
        //$room_url = self::settingHISAPI_RoomsList();
        $dept_url = self::settingHISAPI_DepartmentsList();
        $data = FApi::getUrlContent($dept_url);
        if (!is_array($data))
            $data = [];

        $result = [];
        foreach ($data as $item) {
            $result = array_merge($result, [$item['id'] => $item['name']]);
        }
        return FHtml::getComboArray($result);
    }

    public static function settingPageRefreshInterval($default = 60)
    {
        $value = FHtml::setting('smartads.page_refresh_interval', $default);
        if (empty($value) || $value < 0)
            $value = $default;
        return $value;
    }

    public static function settingMainTimer($default = 5)
    {
        $value = FHtml::setting('smartads.main_timer', $default);
        if (empty($value) || $value < 0)
            $value = $default;
        return $value;
    }

    public static function settingAppRefreshInterval($default = 1200)
    {
        $value = FHtml::setting('smartads.app_refresh_interval', $default);
        if (empty($value) || $value < 0)
            $value = $default;
        return $value;
    }

    public static function settingLCDRowCount()
    {
        return FHtml::setting('lcd.rowCount', 4);
    }

    public static function settingLCDRowHeight()
    {
        $rowcount = self::settingLCDRowCount();
        if ($rowcount == 3)
            return 20;
        else if ($rowcount == 4)
            return 16;
        else if ($rowcount == 5)
            return 13;

        return FHtml::setting('lcd.rowViewHeight', 16);
    }

    public static function getSettings($device_model = null)
    {
        $settings = [];
        $settings['background'] = FHtml::setting('lcd.background', '#ffffff');;
        $settings['headerViewColor'] = FHtml::setting('lcd.headerViewColor', Smartscreen::getDefaultBackgroundColor());
        $settings['headerViewHeight'] = FHtml::setting('lcd.headerViewHeight', 12);
        $settings['titleColor'] = FHtml::setting('lcd.titleColor', '#ffffff');

        $settings['bottomViewColor'] = FHtml::setting('lcd.bottomViewColor', '#000000');
        $settings['bottomViewHeight'] = FHtml::setting('lcd.bottomViewHeight', 8);
        $settings['fontColor'] = FHtml::setting('lcd.fontColor', '#000000');
        $settings['rowViewHeight'] = self::settingLCDRowHeight();
        $settings['rowCount'] = self::settingLCDRowCount();

        $settings['listViewHeaderBackground'] = FHtml::setting('lcd.listViewHeaderBackground', '#e1e1e1');
        $settings['textTicketTitle'] = FHtml::setting('lcd.textTicketTitle', FHtml::t('common', 'Ticket'));
        $settings['textNameTitle'] = FHtml::setting('lcd.textNameTitle', FHtml::t('common', 'Patient Name'));
        $settings['textStatusTitle'] = FHtml::setting('lcd.textStatusTitle', FHtml::t('common', 'Status'));
        $settings['isBlink'] = FHtml::setting('lcd.isBlink', true);
        $settings['autoRestartAppWithUnCaughtError'] = Smartscreen::settingAutoRestartAppWithUncaughtError(true);

        $settings['useWebViewToShowImage'] = Smartscreen::settingConvertImageToWebview();
        $settings['useWebViewToShowVideo'] = Smartscreen::settingConvertVideoToWebview();
        $settings['VideoFillMode'] = FHtml::setting('smartscreen.VideoFillMode', 0);

        //$settings['isBlink'] = FHtml::setting('lcd.isBlink', true);
        //$settings['isBlink'] = FHtml::setting('lcd.isBlink', true);

        $settings['refresh'] = (int) self::settingPageRefreshInterval();
        $settings['mainTimer'] = (int) self::settingMainTimer();

        $app_refresh = FHtml::getModelFieldValue($device_model, 'app_refresh');
        $settings['refreshApp'] = (int) (!empty($app_refresh) ? $app_refresh : self::settingAppRefreshInterval());
        $settings['socket'] = (bool) FHtml::setting('nodejs.enabled', true);

        $settings['logo_url'] = FHtml::setting('smartads.logo_url', '');
        $settings['logo_position'] = FHtml::setting('smartads.logo_position', '');
        $logo_opacity = FHtml::setting('smartads.logo_opacity', 0);

        if ($logo_opacity >= 100)
            $logo_opacity = 100;

        if ($logo_opacity > 0 && ($settings['logo_url'] == 'logo' || empty($settings['logo_url'])))
            $settings['logo_url'] = FHtml::getImageUrl('logo.png', 'www');

        $settings['logo_opacity'] = FHtml::getNumeric($logo_opacity);
        $settings['duration_unit'] = '';
        $settings['VideoFillMode'] = (int) FHtml::setting('smartscreen.VideoFillMode', 0);

        //sojo 2022
        $settings['website_timer'] = (int) FHtml::setting('website_timer', 30); //thoi gian idle screen
        $settings['homepage'] = FHtml::setting('homepage', '');
        $settings['app_mode'] = FHtml::setting('app_mode', '');
        $settings['license'] = FHtml::setting('license', '');

        // $settings['bottom_center_image'] = FHtml::setting('bottom_center_image', '');
        // $settings['bottom_center_url'] = FHtml::setting('bottom_center_url', '');
        // $settings['bottom_left_image'] = FHtml::setting('bottom_left_image', '');
        // $settings['bottom_left_url'] = FHtml::setting('bottom_left_url', '');
        // $settings['bottom_right_image'] = FHtml::setting('bottom_right_image', '');
        // $settings['bottom_right_url'] = FHtml::setting('bottom_right_url', '');

        $inMinutes = static::settingDurationInMinutes();

        $settings['refresh'] = $inMinutes ? $settings['refresh'] : (60 * $settings['refresh']);
        $settings['refreshApp'] = $inMinutes ? $settings['refreshApp'] : (60 * $settings['refreshApp']);
        $settings['mainTimer'] = $inMinutes ? $settings['mainTimer'] : (60 * $settings['mainTimer']);
        //$settings['website_timer'] = $settings['website_timer'];

        $settings['duration_unit'] = $inMinutes ? 'minute' : 'second';

        return $settings;
    }

    public static function getDefaultVideoLoop()
    {
        return (int) FConfig::setting('smartads.default_video_loop', 0);
    }

    public static function isStandByTime($start_time = null, $default = null)
    {
        return !self::isWorkingTime($start_time, $default);
    }

    public static function setDefaultTimezone($default = 'Asia/Ho_Chi_Minh')
    {
        $timezone = FHtml::setting('timezone');
        if (empty($timezone))
            $timezone = $default;

        date_default_timezone_set($timezone);
    }

    public static function isWorkingTime($time = null, $default = null)
    {
        if (empty($time))
            $time = date('H:i');

        $start = static::settingStartTimeWorking();
        $end = static::settingEndTimeWorking();
        if (empty($start))
            $start = '00:00';
        if (empty($end))
            $end = '24:00';

        return ($time >= $start && $time <= $end);
    }

    public static function settingWorkingTime()
    {
        $start_time = FHtml::setting('smartads.start_time', '06:00');
        $end_time = FHtml::setting('smartads.end_time', '20:00');

        if (is_array($start_time))
            $start_time = '06:00';

        if (is_array($end_time))
            $end_time = '20:00';

        return FHtml::setting('smartads.working_time', [
            'default' => "$start_time-$end_time"
        ]);
    }

    public static function settingStartTimeWorking()
    {
        $start_time = FHtml::setting('smartads.start_time', null);
        return $start_time;
    }

    public static function settingEndTimeWorking()
    {
        $end_time = FHtml::setting('smartads.end_time', null);
        return $end_time;
    }

    public static function settingAppMode($default = 'live')
    {
        return FHtml::setting('smartads.app_mode', $default);
    }

    public static function settingHISEnabled()
    {
        $enabled1 = FHtml::setting('smartads.his_enabled', false);
        $enabled2 = FHtml::setting('smartads.his_api_server', '');
        return $enabled1;
    }

    public static function isNodeJsEnabled()
    {
        return FConfig::isNodeJsEnabled();
    }

    public static function getNodeJsStartCommand($app = 'server.js')
    {
        $cmd = FHtml::setting('nodejs.command');
        if (empty($cmd)) {
            if (!empty(NODE_DIR) && !is_dir(NODE_DIR))
                return;

            $cmd = NODE_DIR;
        }

        if (!ends_with($cmd, '/node') && !ends_with($cmd, $app))
            $cmd = $cmd . '/node';

        if (!ends_with($cmd, $app))
            $cmd = $cmd . ' ' . FHtml::getRootFolder() . '/node/' . $app;

        return $cmd;
    }

    public static function startSocket($app = 'server.js')
    {
        $cmd = static::getNodeJsStartCommand($app);
        \common\components\FHtml::execSystemCommand($cmd);
        return $cmd;
    }

    public static function checkSocketPort($host = '127.0.0.1', $port = null, $timeout = 2)
    {
        if (empty($port))
            $port = FConfig::setting('nodejs.port', 8890);

        $ipaddress = FHtml::currentDomain();
        $ipaddress = substr($ipaddress, strpos($ipaddress, '//') + 2);
        $host = FHtml::setting('nodejs.server', $ipaddress);
        if (empty($host))
            $host = $ipaddress;

        $tbegin = microtime(true);

        try {
            error_reporting(E_ERROR | E_PARSE);
            $fp = fsockopen($host, $port, $errno, $errstr, $timeout);
        } catch (Exception $ex) {
            return -1;
        }

        $responding = 1;
        if (!$fp) {
            $responding = 0;
        }

        $tend = microtime(true);

        fclose($fp);

        $mstime = ($tend - $tbegin) * 1000;
        $mstime = round($mstime, 2);

        if ($responding) {
            return $mstime;
        } else {
            return false;
        }
    }

    public static function showScheduleOverview($model, $showAll = true)
    {
        if (empty($model->id)) {
            $result = FHtml::t('Default');
            //var_dump($model->data[0]['data'][0]);
            if (isset($model->data[0]['data'][0])) {
                $result = '<span class="label label-sm label-primary">' . $model->data[0]['contentLayout'] . '</span> ' . $model->data[0]['data'][0]['title'];
                $content = SmartscreenContent::findOneCached($model->data[0]['data'][0]['id']);
                if (isset($content))
                    return Smartscreen::showScheduleOverview($content);
            }
            return "<span style='color:grey'>" . $result . "</span>";
        }

        $result = null;
        //$result = self::Cache("Schedule_{$model->id}_Overview");
        if (isset($result))
            return $showAll ? $result : FHtml::showToogleContent(FHtml::t('Content') . '&nbsp;<span class="glyphicon glyphicon-collapse-down"></span>', @"<div>$result</div>");
        $items  = FHtml::decode($model->content_id);

        if (!empty($items)) {
            if (!is_array($items))
                $items = [$items];

            if (is_array($items)) {
                //$data = $model->data;

                foreach ($items as $i => $item) {
                    if (empty($item)) {
                        unset($items[$i]);
                        continue;
                    }
                }
                //var_dump($items);
                $items = array_values($items);
                foreach ($items as $i => $item) {
                    if (empty($item)) {
                        continue;
                    }
                    $content_model = SmartscreenContent::findOneCached($item);
                    if (!isset($content_model) || in_array($content_model->type, ['text'])) {
                        continue;
                    }
                    $title = '[' . $content_model->type . '] ' . $content_model->title . ' (id:' . $content_model->id . ')';
                    $url = FHtml::createUrl('smartscreen/smartscreen-content/update', ['id' => $content_model->id]);

                    $result .= "<a href='$url' data-pjax=0>$title</a>" . '<br/>';
                    // //$frame = isset($model->data[$i]) ? $model->data[$i]['name'] : "Zone $i";
                    // if (count($items) > 1) {
                    //     $css = ($i < count($items) - 1) ? "padding-bottom:5px; border-bottom:1px solid lightgrey;" : '';
                    //     $result .= "<div style='clear:both;$css; width: 100%; display: inline-block'>";
                    //     //$result .= "<div style='color: #eee; font-size: 80%; float:left;writing-mode: vertical-rl;text-orientation: mixed; '>$frame</div>";
                    // }

                    // $result .= Smartscreen::showContentOverview($content_model, null, true);
                    // if (count($items) > 1) {
                    //     $result .= '</div>';
                    // }
                }
            }

            $file_kind = $model->file_kind === SmartscreenSchedules::DURATION_KIND_MINUTES ? 'm' : ($model->file_kind === SmartscreenSchedules::DURATION_KIND_SECOND ? 's' : ' x');
            $file_duration = ($model->file_kind === SmartscreenSchedules::DURATION_KIND_MINUTES || $model->file_kind == SmartscreenSchedules::DURATION_KIND_SECOND) ? $model->duration : 0;
            if ($file_duration > 0) {
                $result .= "<div class='col-md-2 pull-right' style=\"color:grey; text-align: right\"> " . $file_duration . '' . $file_kind . " </div>";
            }
        } else {
            $result = Smartscreen::showContentOverview($model);
        }

        if (!empty($result))
            self::Cache("Schedule_{$model->id}_Overview", $result);
        return  $showAll ? $result : FHtml::showToogleContent(FHtml::t('Content') . '&nbsp;<span class="glyphicon glyphicon-collapse-down"></span>', @"<div>$result</div>");
    }

    public static function showContentOverview($object_id, $object_type = 'smartscreen_schedules', $showTitle = false)
    {
        $model = null;
        if (is_object($object_id)) {
            $model = $object_id;
            $object_type = $model->getTableName();
            $object_id = $model->id;
        }
        $result = self::Cache("Content_{$object_id}_Overview");
        if (isset($result))
            return $result;

        if (isset($model)) {
            if ($model->type == SmartscreenContent::TYPE_HTML)
                $result = ($showTitle ? $model->title : '') . '&nbsp;&nbsp;' . FHtml::showModalContent($model->type, $model->description, '', 'btn btn-xs btn-default');
            else if ($model->type == SmartscreenContent::TYPE_URL)
                $result = ($showTitle ? $model->title : '') . '&nbsp;&nbsp;' . FHtml::showModalIframeButton($showTitle ? $model->title : $model->type, $model->description, '', 'btn btn-xs btn-default');
            else {
                $result = empty($model->description) ? '<div style="color: lightgrey;float:left">No data</div>' : $model->description;
            }
        }

        $files = SmartscreenFile::findAllCached(); //SmartscreenFile::findAll(['object_id' => $object_id, 'object_type' => $object_type]);
        $tmp = [];
        foreach ($files as $i => $file) {
            if ($file->object_id != $object_id || $file->object_type != $object_type) {
                unset($files[$i]);
            }
        }
        $files = array_values($files);

        if (is_array($files) && !empty($files)) {
            $result = '';
            foreach ($files as $item) {
                if (empty($item)) {
                    continue;
                }
                //$result = $result . $item->description . '<span style="color:grey"> [' . $item->file . '] </span>' . '<br/>';
                $file_kind = $item->file_kind === 'time' ? 'm' : ($item->file_kind === 'second' ? 's' : ' times');
                $image = !empty($item->file) ? FHtml::showImage($item->file, 'smartscreen-file', '', '62px', 'border:1px solid lightgrey;', !empty($item->description) ? $item->description : (is_object($model) ? $model->title : '')) : '<span class="label label-sm label-default">' . $item->command . '</span>';
                //$result .= "<div class='row'> <div class='col-md-10' style='padding-bottom: 5px; margin-bottom: 5px;'>" . $image .  "<span style=\"color:grey\"> " . $item->description . "</span></div>" . "<div class='col-md-2 pull-right' style=\"color:grey; text-align: right\"> "  . $item->file_duration . '' . $file_kind . " </div>" . "</div>";
                $result .= "<div class='' style='float:left;margin-right:5px;'>" . $image . (!empty($item->file_duration) ? "<div style='font-size: 80%;color:grey;text-align: center'>$item->file_duration $file_kind</div>" : "") . "</div>";
            }
        }

        if (!empty($result))
            self::Cache("Content_{$object_id}_Overview", $result);
        return $result;
    }

    public static function updateCampaignSchedules($campaign, &$model)
    {
        $campaign = is_object($campaign) ? $campaign : SmartscreenCampaigns::findOne($campaign);
        if (isset($campaign)) {
            $devices = FHtml::decode($campaign->device_id);
            if (!is_array($devices))
                $devices = [];

            $arr = is_array($model->device_id) ? $model->device_id : [$model->device_id];
            foreach ($arr as $item) {
                $item = trim($item);
                if ($item == 'null')
                    $item = null;
                if (!in_array($item, $devices) && !empty($item) && is_numeric($item))
                    $devices[] = $item;
            }
            foreach ($devices as $i => $item) {
                if (!is_numeric($item))
                    unset($devices[$i]);
            }
            if (empty($devices))
                $devices = null;

            $model->device_id =  $devices;
            $model->channel_id = $campaign->channel_id;
            $model->days = $campaign->days;
            $model->date = $campaign->date;
            $model->date_end = $campaign->date_end;
            $model->{SmartscreenSchedules::FIELD_STATUS} = $campaign->{SmartscreenSchedules::FIELD_STATUS};
            $model->is_active = $campaign->{SmartscreenSchedules::FIELD_STATUS};
        }
        return $model;
    }

    public static function addDeviceLog($ime, $log, $date = null, $start_time  = null)
    {
        if (empty($date))
            $date = date("Y-m-d");
        if (empty($start_time))
            $start_time = date("H:i");
        $logContent = self::getDeviceLog($ime);
        if (!isset($logContent))
            $logContent = '';
        $logContent .= "[ $date $start_time ] \n $log \n";
        self::Cache("$ime.log", str_replace("\n", "<br/>", $logContent));
        $file = FFile::getFullUploadFolder("logs") . DS . "$ime.log";
        try {
            file_put_contents($file, $logContent);
        } catch (Exception $ex) {
        }
    }

    public static function getDeviceLog($ime, $date = null)
    {
        if (empty($date))
            $date = date("Y-m-d");
        $logContent = self::Cache("$ime.log");
        if (empty($logContent)) {
            $file = FFile::getFullUploadFolder("logs") . DS . "$ime.log";
            if (file_exists($file)) {
                $logContent = file_get_contents($file);
            }
        }
        return $logContent;
    }

    public static function generateSchedules($schedules, $start_time = null, $end_time = null, $duration = null, $max = 150, $forAPI = true)
    {
        $tmp = [];
        $j = 0;
        $i = 0;
        //$end_time = isset($end_time) ? $end_time : "24:00";
        if (is_numeric($end_time)) //schedule duration
        {
            $end_time = Smartscreen::getNextStartTime($start_time, $end_time, 1, null, true);
        }
        $last_time = $start_time;
        $next_start_time = null;
        $running = !empty($schedules);
        $rounds = 1;
        $passed = false;
        while ($running) {

            $schedule = clone $schedules[$j];
            if (!empty($duration)) {
                $schedule->duration = (int) $duration;
            }
            if (empty($start_time)
                //|| ($rounds == 1 && $j == 0 && $start_time >= $schedule->start_time)
            ) {
                $start_time = $schedule->start_time;
                $last_time = $start_time;
            }

            $schedule->start_time = $last_time;
            $next_start_time = date("H:i", strtotime($last_time . " +" . ($schedule->duration . ' minutes')));
            if ($forAPI && self::isAPI())
                $schedule->id = (int) str_replace(":", "", $schedule->start_time);
            $i += 1;

            if ($max > 0 && $i == $max)
                $running = false;
            else if (!empty($next_start_time) && $next_start_time < $last_time) { //over 24:00
                $schedule->duration = Smartscreen::getDurationBetween($schedule->start_time, 0, "24:00");
                $running = false;
                $passed = true;
            } else if (!empty($end_time) && strtotime($next_start_time) >= strtotime($end_time)) {
                if ($rounds > 1) {
                    $schedule->duration = Smartscreen::getDurationBetween($schedule->start_time, 0, $end_time);
                    $running = false;
                }
                $passed = true;
            }
            $last_time = $next_start_time;

            $tmp[] = $schedule;
            if ($j < count($schedules) - 1)
                $j += 1;
            else {
                $j = 0;
                $rounds += 1;
                if ($passed || empty($end_time))
                    $running = false;
            }
        }
        $schedules = array_values($tmp);

        return $schedules;
    }

    public static function showDeviceLastUpdate($model, $showHtml = true, $showDateTime = true)
    {
        $last_update = \backend\modules\smartscreen\Smartscreen::getCacheDeviceLoadTime($model->ime);
        $last_update = $last_update > $model->last_update ? $last_update : $model->last_update;
        $url = FHtml::createUrl(['/smartscreen/smartscreen-station/update', 'id' => $model->id]);

        if ($showHtml) {
            $css = (!empty($model->status) && ceil(abs((time() - $last_update)) / 60) < 30) ? 'success' : 'danger';

            if (is_string($showHtml)) {
                $result = '<label class="label label-' . $css . '">' . $showHtml . '</label>&nbsp;' . FHtml::showTimeAgo($last_update);
            } else {
                $result = '<span class="glyphicon glyphicon-ok-sign text-' . $css . '">&nbsp;' . FHtml::showTimeAgo($last_update) . '</span>';
            }

            //$result .= '<small style="color:grey">' . FHtml::showTimeAgo($last_update) . ' </small>';
            if ($showDateTime) {
                $result .= '<br/><small>' . FHtml::showDateTime($last_update) . '</small>';
            }
            return "<a href='$url' data-pjax='0'> $result </a>";
        } else {
            return $last_update;
        }
    }
}
