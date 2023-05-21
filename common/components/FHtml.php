<?php

/**



 * This is the customized model class for table "<?= $generator->generateTableName($tableName) ?>".
 */

namespace common\components;

use backend\models;
use backend\models\ObjectSetting;
use common\widgets\FCheckbox;
use common\widgets\FCKEditor;
use common\widgets\FDateRangePicker;
use common\widgets\FFileInput;
use common\widgets\FListView;
use common\widgets\FNumericInput;
use common\widgets\FWidget;
use common\widgets\FWizard;
use dosamigos\ckeditor\CKEditorWidgetAsset;
use kartik\alert\Alert;
use kartik\alert\AlertBlock;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use kartik\dropdown\DropdownX;
use kartik\grid\GridView;
use kartik\slider\Slider;
use kartik\widgets\Growl;
use kartik\widgets\Select2;
use kartik\widgets\StarRating;
use kartik\widgets\SwitchInput;
use kartik\widgets\TimePicker;
use Yii;
use yii\base\Exception;
use yii\base\ViewContextInterface;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;

class FHtml extends FFrontend
{
    public static function button($type, $style, $htmlOptions = array(), $isEditable = TRUE)
    {
        if (empty($type) || empty($style) || !array_key_exists($style, self::$buttonIcons))
            return self::showEmpty();
        if (isset($htmlOptions['class']))
            $htmlOptions['class'] = $htmlOptions['class'] . ' btn btn-' . $style;
        else
            $htmlOptions['class'] = 'btn btn-' . $style;
        if (!$isEditable)
            $htmlOptions['class'] .= ' disabled';
        $html = '<button type="' . $type . '" ' . self::renderAttributes($htmlOptions) . '>';
        $html .= '  <i class="' . self::$buttonIcons[$style] . '"></i>';
        if (isset($htmlOptions['value'])) {
            $html .= '  ' . $htmlOptions['value'];
        } else {
            $html .= '  ' . self::buttonValue($style);
        }
        $html .= '</button>';
        return $html;
    }

    public static function showEmpty()
    {
        $str = '<span style=" font-style: italic" class="text muted">(' . FHtml::t('common', 'Empty') . ')</span>';
        return $str;
    }

    public static function renderAttributes($attributes = array())
    {
        $html = "";
        foreach ($attributes as $key => $value) {
            $html .= ' ' . $key . '="' . $value . '" ';
        }
        return $html;
    }

    private static function buttonValue($style)
    {
        return FHtml::t('button', $style);
    }


    //2017/03/28

    public static function showEmptyResult()
    {
        $str = '<span style=" font-style: italic" class="text muted">' . Yii::t('common', 'title.noResult') . '</span>';
        return $str;
    }

    public static function dynamicButton($type, $style, $text, $htmlOptions = array())
    {
        if (empty($type) || empty($style) || !array_key_exists($style, self::$buttonIcons))
            return self::showEmpty();
        if (isset($htmlOptions['class']))
            $htmlOptions['class'] = $htmlOptions['class'] . ' btn btn-' . $style;
        else
            $htmlOptions['class'] = 'btn btn-' . $style;
        $html = '<button type="' . $type . '" ' . self::renderAttributes($htmlOptions) . '>';
        $html .= '  <i class="' . self::$buttonIcons[$style] . '"></i>';
        $html .= '  ' . $text;
        $html .= '</button>';
        return $html;
    }

    public static function buttonSubmit($style, $htmlOptions = array(), $isSmall = FALSE, $isEditable = TRUE, $isShowtext = TRUE)
    {
        $type = self::SUBMIT_TYPE;
        if (empty($type) || empty($style) || !array_key_exists($style, self::$buttonIcons))
            return;
        if (isset($htmlOptions['class']))
            $htmlOptions['class'] = $htmlOptions['class'] . ' btn btn-' . $style;
        else
            $htmlOptions['class'] = 'btn btn-' . $style;
        if ($isSmall)
            $htmlOptions['class'] .= ' mini';
        if (!$isEditable)
            $htmlOptions['class'] .= ' disabled';
        $html = '<button type="' . $type . '" ' . self::renderAttributes($htmlOptions) . '>';
        $html .= '  <i class="' . self::$buttonIcons[$style] . '"></i>';
        if ($isShowtext)
            $html .= '  ' . self::buttonValue($style);
        $html .= '</button>';
        return $html;
    }

    public static function buttonDeleteAll($icon = '<span class="glyphicon glyphicon-trash"></span>', $title = 'Delete All', $action = 'delete-all', $color = 'warning')
    {
        $button = self::buttonAction($icon, $title, $action, $color, true);

        return $button;
    }

    public static function buttonAction($icon = '', $title = '', $action = '', $color = '', $confirm = false, $translated = true, $style = 'float:left; margin-right:10px;')
    {
        $view = FHtml::currentView();

        // Hung:
        //        if (!is_array($action)) {
        //            $controller = FHtml::currentController();
        //            $action = strpos($action, '/') === false ? "$controller/$action" : $action;
        //        }

        if (is_array($icon)) {
            $icon = $icon[0];
        }
        if (is_string($translated)) {
            $style = $translated;
            $translated = true;
        }

        $button = FHtml::a(
            trim($icon . '&nbsp;' . (empty($title) ? '' : ($translated ? FHtml::t('button', $title) : $title))),
            $action,
            $confirm ?
                [
                    "class" => !empty($color) && in_array($color, ['default', 'success', 'primary', 'danger', 'warning']) ? "btn btn-$color" : $color,
                    'role' => is_bool($confirm) ? 'modal-remote' : "modal-remote-bulk",
                    'data-confirm' => false, 'data-method' => false, // for overide yii data api
                    'data-request-method' => 'post',
                    'data-confirm-title' => FHtml::t('message', $title),
                    'data-confirm-message' => FHtml::t('message', 'Are you sure you want to do this') . ' ?',
                    'style' => $style
                ] :
                [
                    'role' => $view->params['editType'],
                    'data-pjax' =>  $view->params['isAjax'] == true ? 1 : 0,
                    'title' => FHtml::t('message', $title),
                    "class" => !empty($color) && in_array($color, ['default', 'success', 'primary', 'danger', 'warning']) ? "btn btn-$color" : $color,
                    'style' => $style
                ]
        );

        return $button;
    }

    public static function a($text, $url = null, $options = [], $keep_current_params = ['id'])
    {
        if ($url == [''])
            $keep_current_params = false;

        if (is_array($keep_current_params)) {
            $excluded_params = $keep_current_params;
            if (is_array($url)) {
                foreach ($url as $key => $value)
                    if (is_string($key))
                        $excluded_params[] = $key;
            }
            $params = FHtml::RequestParams($excluded_params);
        } else if ($keep_current_params == true)
            $params = FHtml::RequestParams(['id']);
        else
            $params = [];

        if (!isset($url) || empty($url)) {
            $url = FHtml::currentUrlPath();
            if (!empty($params)) {
                $url = ArrayHelper::merge((!is_array($url) ? [$url] : $url), $params);
            }
        }

        //        if (is_array($url) && count($url) == 1)
        //            $url = $url[0];
        //
        //        if (is_string($url) && strpos($url, '/') === false)
        //            $url = FHtml::currentController() . '/' . $url;

        //if is in popup mode (iframe) then keep layout=no in this link
        $is_layout = FHtml::getRequestParam('layout');
        if (!empty($is_layout)) {
            if (is_array($url))
                $url = array_merge($url, ['layout' => $is_layout]);
            else
                $url .= "&layout=$is_layout";
        }

        $url = Html::a($text, $url, $options);
        $url = FHtml::createFormalizedBackendLink($url);
        return $url;
    }

    public static function buttonDeleteBulk($icon = '<span class="glyphicon glyphicon-trash"></span>', $title = '', $action = 'bulk-delete', $color = 'danger')
    {
        $button = self::buttonAction($icon, $title, $action, $color, 'bulk');

        return $button;
    }

    public static function buttonBulkActions($items, $dropdown = true, $is_confirm = true)
    {
        if ($dropdown) {
            $bulkActionButton = '<div class="dropdown pull-left">&nbsp;<button class="btn btn-default" data-toggle="dropdown">' . FHtml::t('button', 'Actions') . '</button>' . DropdownX::widget([
                'items' => $items

            ]) . '</div>';
        } else {
            $bulkActionButton = '';
            foreach ($items as $label => $item) {
                $bulkActionButton .= static::buttonBulkAction('', key_exists('label', $item) ? $item['label'] : $label, key_exists('url', $item) ? $item['url'] : $item, 'btn btn-default', $is_confirm, false);
            }
        }
        return $bulkActionButton;
    }

    public static function buttonBulkAction($icon = '', $label, $action, $color = '', $confirm = true, $translated = true)
    {
        return static::buttonAction($icon, $label, $action, $color, $confirm ? 'bulk' : false, $translated);
    }

    public static function buttonBulkChangeFieldActions($items, $action = 'bulk-action', $confirm = true)
    {
        if (is_string($items)) {
            if (strpos($items, '.') !== false) {
                $arr = explode('.', $items);
                $field = $arr[1];
            }
            $items = [$field => FHtml::getComboArray($items)];
        }

        $result = '';
        foreach ($items as $field => $items) {
            if (is_string($items))
                $items = FHtml::getComboArray($items);

            foreach ($items as $id => $name) {
                if (is_numeric($id))
                    $id = $name;
                $result .= FHtml::buttonBulkAction('', FHtml::t('common', $field) . ": " . $name, is_string($action) ? [$action, 'action' => 'change', 'field' => $field, 'value' => $id] : array_merge($action, ['action' => 'change', 'field' => $field, 'value' => $id]), 'btn btn-default', $confirm, false);
            }
        }
        return $result;
    }

    public static function buildChangeFieldMenu($table, $field, $action = 'change')
    {
        return self::buildBulkActionsMenu('', '', $table, $field, $action);
    }

    public static function buildBulkActionsMenu($label, $key, $table, $field, $action = 'change')
    {
        if (empty($label))
            $label = FHtml::getFieldLabel($table, $field);

        if (in_array($action, ['change'])) {
            $items = FHtml::getComboArray($key, $table, $field);
            if (!key_exists('', $items))
                $items = ArrayHelper::merge(['' => FHtml::getNullValueText()], $items);

            $child = array();

            foreach ($items as $id => $name) {
                $child[] = '<li>' . static::buttonBulkAction('', $name, ["bulk-action", "action" => $action, "field" => $field, "value" => $id], '', false) . '</li>';
            }
            if (count($child) == 0)
                return null;

            $result = ['label' => $label, 'items' => $child];
        } else {
            $result = '<li>' . static::buttonBulkAction('<i class="glyphicon glyphicon-file"></i> ' . $label, '', ["bulk-action", "action" => $action, "field" => $field], '', false) . '</li>';
        }

        return $result;
    }

    public static function buildBulkDeleteMenu($action = [])
    {
        return self::buildBulkActionMenu('<i class="glyphicon glyphicon-trash"></i> ', 'Delete', !empty($action) ? $action : 'bulk-delete', 'color:red', 'bulk');
    }

    public static function buildBulkApproveMenu($action = [])
    {
        return self::buildBulkActionMenu('<i class="glyphicon glyphicon-ok"></i> ', 'Approve', !empty($action) ? $action : 'bulk-approve', 'color:blue', 'bulk');
    }

    public static function buildBulkRejectMenu($action = [])
    {
        return self::buildBulkActionMenu('<i class="glyphicon glyphicon-remove"></i> ', 'Reject', !empty($action) ? $action : 'bulk-reject', 'color:reject', 'bulk');
    }

    public static function buildBulkChangeFieldMenu($title, $field, $value)
    {
        return self::buildBulkActionMenu('<i class="glyphicon glyphicon-edit"></i> ', $title, ['bulk-action', 'action' => 'change', 'field' => $field, 'value' => $value], 'color:reject', 'bulk');
    }

    public static function buildBulkActionMenu($icon = '', $title = '', $action = '', $style = '', $confirm = false)
    {
        return '<li>' . FHtml::a(
            $icon . FHtml::t('button', $title),
            is_array($action) ? $action : [$action],
            $confirm ?
                [
                    'role' => is_bool($confirm) ? 'modal-remote' : "modal-remote-bulk",
                    'data-confirm' => false, 'data-method' => false, // for overide yii data api
                    'data-request-method' => 'post',
                    'data-confirm-title' => FHtml::t('message', 'Confirmation'),
                    'data-confirm-message' => FHtml::t('message', 'Are you sure'),
                    'style' => $style
                ] : [
                    'role' => '',
                    'data-confirm' => false, 'data-method' => false, // for overide yii data api
                    'data-request-method' => 'post',
                    'style' => $style
                ]
        ) . '</li>';
    }

    public static function buildDeleteAllMenu()
    {
        if (!FHtml::isRoleAdmin())
            return '';

        return self::buildBulkActionMenu('<i class="glyphicon glyphicon-remove"></i> ', 'Delete All', 'delete-all', 'color:red', true);
    }

    public static function buildPopulateMenu()
    {
        return '<li>' . FHtml::a(
            '<i class="glyphicon glyphicon-refresh"></i> ' . FHtml::t('button', 'Populate'),
            ["populate"],
            []
        ) . '</li>';
    }

    public static function buildBulkDividerMenu()
    {
        return '<li class="divider"></li>';
    }


    public static function getColorLabel($color)
    {
        return '<span class="label label-sm" style="background: ' . $color . '">' . $color . '</span>';
    }

    public static function var_dump($object, $condition = true)
    {
        if ($condition) {
            echo '<pre>';
            var_dump($object);
            //echo '<b>'; echo get_class($where); echo ': '; echo get_called_class(); echo ': '; echo '</b><br/>';
            //            if (is_object($object) || is_array($object)) {
            //	            //print_r($object);
            //            }
            //            else {
            //
            //	            //echo $object;
            //            }
            echo '</pre>';
        }
    }

    public static function print_r($object, $condition = true)
    {
        if ($condition) {
            echo '<pre>';
            //echo '<b>'; echo get_class($where); echo ': '; echo get_called_class(); echo ': '; echo '</b><br/>';
            if (is_object($object) || is_array($object))
                print_r($object);
            else
                echo $object;
            echo '</pre>';
        }
    }

    public static function dump($object, $condition = true)
    {
        return static::var_dump($object, $condition);
    }

    public static function showPre($data, $condition = true)
    {
        if ($condition) {
            echo "<pre>";
            print_r($data);
            die;
        }
    }

    public static function showDiv($content, $css = '', $showEmpty = '')
    {
        if (!empty($css) && strpos($css, ':') !== false)
            return "<div style='$css'>$content</div>";
        else if (!empty($css))
            return "<div class='$css'>$content</div>";
        else {
            if (empty($content))
                return $showEmpty;
            else
                return $content;
        }
    }

    public static function beautyResponse($array)
    {
        $array = array_map(function ($v) {
            return (!is_null($v)) ? is_array($v) ? array_map(function ($v) {
                return (!is_null($v)) ? $v : "";
            }, $v) : $v : "";
        }, $array);
        return $array;
    }


    public static function flushCachedText($key = '')
    {
        $key = empty($key) ? FHtml::getCachedKey('Settings_Text') : $key;
        FHtml::deleteCachedData($key);
    }

    public static function getCachedText($key = '')
    {
        $key = empty($key) ? FHtml::getCachedKey('Settings_Text') : $key;
        $textArray = [];
        $textArray = FHtml::getCachedData($key);
        if (!isset($textArray)) {
            $textModels = FHtml::getModels('settings_text');
            $textArray = [];
            foreach ($textModels as $model) {

                $textArray = array_merge($textArray, [$model->name => $model->toArray()]);
            }
            FHtml::saveCachedData($textArray, $key);
        }
        return $textArray;
    }

    public static function showCurrentUserAvatar($width = '50px', $height = '50px', $css = 'img-circle')
    {
        return FHtml::showImage(self::currentUserAvatar(), 'user', $width, $height, $css, '', true, 'none');
    }

    public static function showImageWithDownload($image, $model_dir = '', $width = '100%', $height = 0, $css = '', $title = '', $show_empty_image = TRUE, $hover_effect = '', $linkurl = '')
    {
        return self::showImage($image, $model_dir, $width, $height, $css, $title, $show_empty_image, 'download');
    }

    public static function showImageIfNotNull($image, $model_dir = '', $width = '100%', $height = 0, $css = 'bordered', $title = '', $show_empty_image = false, $hover_effect = '', $linkurl = '', $is_preview = true)
    {
        return static::showImage($image, $model_dir, $width, $height, $css, $title, $show_empty_image, $hover_effect, $linkurl, $is_preview);
    }

    public static function showImage($image, $model_dir = '', $width = '100%', $height = 0, $css = '', $title = '', $show_empty_image = TRUE, $hover_effect = '', $link_url = '', $is_preview = true)
    {
        if (is_object($image))  // if pass model as parameter
        {
            $image_dir = self::getImageFolder($image);
            if ($model_dir == $image_dir)
                $model_dir = '';

            $fields = !empty($model_dir) ? $model_dir : ['image', 'thumbnail', 'logo', 'banner', 'avatar'];
            $image_file = FHtml::getFieldValue($image, $fields);
            $model_dir = $image_dir;

            return self::showImage($image_file, $model_dir, $width, $height, $css, $title, $show_empty_image, $hover_effect, $link_url);
        }

        $str = '';
        $current_action = FHtml::currentAction();
        $image_existed = false;

        if (filter_var($image, FILTER_VALIDATE_URL)) {
            $image_existed = true;
        }

        if (strlen($image) > 0) {
            $imageLink1 = '';
            $showVideo = self::showVideo($image);

            if (!empty($showVideo)) {
                $image_existed = false;
                $imageLink = $image;
                $str = '<img src="' . FHtml::getImageUrl('video.png', 'www') . '" ';
                $width = '80px';
                $height = '80px';

                if (!empty($width)) {
                    $str = $str . ' width="' . $width . '" ';
                }
                if (!empty($height)) {
                    $str = $str . ' height="' . $height . '" ';
                }

                $str = $str . '/>';

                $hover_effect = 'download';
            } else if (strpos($image, 'http:') !== false || strpos($image, 'https:') !== false || strpos($image, 'www.') !== false) {
                $imageLink = $image;
            } else {
                $folder = FHtml::getFullUploadFolder($model_dir);
                $imagePath = $folder . DS . $image;

                if (StringHelper::startsWith($imagePath, 'applications') || StringHelper::startsWith($imagePath, 'backend')) {
                    $imagePath = DS . $imagePath;
                }

                if (is_file($imagePath)) {
                    $image_existed = self::is_image($imagePath, false);
                    $imageLink1 = $imagePath;
                    $imageLink = self::getImageUrl($image, $model_dir);
                } else {
                    if (FHtml::isViewAction($current_action)) {
                        $width = '70px';
                        $height = '50px';
                    }
                    $imageLink1 = 'Not found:' . $imagePath;
                    $image_existed = false;

                    $title = $imagePath;
                    $imageLink = FHtml::defaultImage($show_empty_image, $width, $height, $model_dir);
                }
            }
        } else {
            $imageLink1 = 'Empty Data';
            $image_existed = false;
            if (FHtml::isViewAction($current_action)) {
                $width = '70px';
                $height = '50px';
            }
            $imageLink = FHtml::defaultImage($show_empty_image, $width, $height, $model_dir);
        }
        if (empty($str)) {
            if (strlen($imageLink) > 0) {
                //                if (ends_with($imageLink, '.mp4') || ends_with($imageLink, '.avi')) {
                //                    return '<img src="' . FHtml::getImageUrl('video.png', 'www') . "\" width=$width height=$height />";
                //                }
                if ($image_existed || FHtml::is_image($imageLink)) {
                    if (FHtml::isExport()) {
                        return $imageLink;
                    }
                    $str .= '<img src="' . $imageLink . '"';
                    if (!empty($width) || !empty($height)) {
                        if (!empty($width)) {
                            $str = $str . ' width="' . $width . '" ';
                        }
                        if (!empty($height)) {
                            $str = $str . ' height="' . $height . '" ';
                        }
                        if (!empty($css)) {
                            if (strpos($css, ':') !== false) {
                                $str = $str . ' style="' . $css . '" ';
                            } else if ($css != 'img-responsive') {
                                $str = $str . ' class="' . $css . '" ';
                            }
                        }
                    } else {
                        $str = $str . ' class="img-responsive ' . $css . '" ';
                    }
                    if ($title !== '') {
                        $str = $str . ' alt="' . $title . '" ';
                    }
                    $str = $str . ' data="' . $imageLink1 . '" ';
                    $str .= ' />';
                } else {
                    $str = self::showFile($imageLink, $title, $imageLink1, '<i class="fa fa-file" aria-hidden="true"></i>');
                    return $str;
                }
            }
        }

        if (empty($link_url))
            $link_url = $imageLink;

        if ((empty($hover_effect) && self::currentZone() == BACKEND) && $image_existed && !in_array($current_action, ['create', 'update'])) {
            $hover_effect = 'download';
        }

        if ($hover_effect == 'download') {
            $title = "";
            $link = FHtml::createUrl("site/file", ['file' => $imageLink, 'file_type' => 'image', 'title' => $title, 'file_path' => $model_dir, 'file_name' => $image]);
            $str = FHtml::showModalButton($str, $link, 'modal-remote', '') . "<div class='visible-print'>$str</div>";
        } else if (!empty($hover_effect) && ($hover_effect !== 'none') && ($hover_effect !== 'download') && !(is_numeric($width) && is_numeric($height)) && strpos($imageLink, DEFAULT_IMAGE) === false) {
            $str = '<div class="' . $hover_effect . '">' . $str . '<div class="overlay"> <h2>' . $title . '</h2> <a class="info" target="_blank" href="' . $link_url . '">OPEN</a> </div></div>';
        }

        return $str;
    }

    public static function isPrint()
    {
        $print = FHtml::getRequestParam('print');
        return $print;
    }

    public static function isExport()
    {
        $export = FHtml::getRequestParam('export');
        return $export;
    }

    public static function showVideo($file, $title = '')
    {
        $parsed = FContent::parseUrl($file);
        $image = $file;
        if (!empty($parsed)) {
            $file_type = $parsed['type'];
            $url = $parsed['url'];
        } else {
            $file_type = '';
            $url = '';
        }

        if (in_array($file_type, ['youtube', 'vimeo'])) {
            return self::showIframe($url);
        } else if (strpos($image, 'api.soundcloud.com') !== false) {
            $image = str_replace('https://', 'https%3A//', $image);
            return ' <iframe width="100%" height="" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=' . $image . '&amp;auto_play=false&amp;hide_related=false&amp;visual=true"></iframe>';
        } else if (strpos($file, 'vimeo.com') !== false) {
            return '<div class="responsive-video"> <iframe width="100%" height=""  webkitAllowFullScreen mozallowfullscreen allowFullScreen scrolling="no" frameborder="0" src="' . $image . '"></iframe></div>';
        } else if (strpos($file, 'youtube.com') !== false) {
            if (!StringHelper::startsWith('//', $image))
                $image = '//' . $image;
            return '<div class="responsive-video"> <iframe width="100%" height=""  webkitAllowFullScreen mozallowfullscreen allowFullScreen scrolling="no" frameborder="0" src="' . $image . '"></iframe></div>';
        } else {
            return '';
        }
    }

    public static function showFile($file, $title = '', $data = '', $showName = true, $css = 'form-label')
    {

        $path_parts = pathinfo($file);
        $file_name = $path_parts['basename'];
        $file_ext = $path_parts['extension'];

        $file_path = is_file($data) ? $data : FFile::getFullFileName($file);
        $file_size = null;

        if (ends_with($file_path, "no_image.png")) {
            echo $file_path;
            die;
        }
        if (is_file($file_path)) {

            $file_size = self::convertToKBytes(filesize($file_path));

            if (empty($data))
                $data = $file;
            if (empty($title)) {
                $arr = explode('/', $file);
                $arr = explode('.', $arr[count($arr) - 1]);
                $title =  $arr[0];
            }

            if ($showName === true)
                $showName = $title;
            else {
                $icon  = '';
                if (in_array($file_ext, ['mp3']))
                    $showName = '<i class="fa fa-file-audio-o" aria-hidden="true"></i>';
                else if (in_array($file_ext, ['mp4', 'avi']))
                    $showName = '<i class="fa fa-file-movie-o" aria-hidden="true"></i>';
                else if (in_array($file_ext, ['xls', 'xlsx']))
                    $showName = '<i class="fa fa-file-excel-o" aria-hidden="true"></i>';
                else if (in_array($file_ext, ['pdf']))
                    $showName = '<i class="fa fa-file-pdf-o" aria-hidden="true"></i>';
                else if (in_array($file_ext, ['doc']))
                    $showName = '<i class="fa fa-file-word-o" aria-hidden="true"></i>';
                else if (in_array($file_ext, ['ppt']))
                    $showName = '<i class="fa fa-file-powerpoint-o" aria-hidden="true"></i>';

                $showName .= ' ' . (!empty($title) ? $title : $file_ext) . "<br/><small>$icon .$file_ext . $file_size</small>";
            }

            $link = FHtml::createUrl("site/file", ['file' => $file, 'file_name' => $file_name, 'title' => $title, 'file_type' => $file_ext, 'file_size' => $file_size, 'file_path' => $file_path]);

            $str = "<span title='$file_name' class='$css' style='height:60px;border:solid 1px transparent; box-shadow: 0 1px 3px rgba(0,0,0,.1), 0 1px 2px rgba(0,0,0,.18);text-align:center;vertical-align:middle;padding: 5px;cursor:pointer;display:inline-block;white-space:nowrap'> $showName </span>";
            $str = FHtml::showModalButton($str, $link, 'modal-remote', '');
        } else {
            $showName = FHtml::getNullValueText();
            $title = 'File not found: ' . $title;
            $str = "<span data='$data' title='$title' class='$css text-danger' style='margin-top:-7px'> $showName </span>";
        }
        return $str;
    }


    public static function defaultImage($show_empty_image = TRUE, $width = 50, $height = 50, $moder_dir = '')
    {
        $file = '';
        $baseUploadFolderLink = self::baseUploadFolderLink();
        if (is_array($moder_dir)) {
            $moder_dir = $moder_dir[0];
        }
        if ($show_empty_image === TRUE || $show_empty_image === 1) {
            if (!empty($width) && strpos($width, '%') > 0) {
                $width = 150;
            }
            if (!empty($moder_dir)) {
                $file = WWW_DIR . '/' . str_replace('image', $moder_dir, DEFAULT_IMAGE);
                if (is_file(UPLOAD_DIR . '/' . $file)) {
                    $file = $baseUploadFolderLink . '/' . $file;
                } else {
                    $file = $baseUploadFolderLink . '/' . WWW_DIR . '/' . DEFAULT_IMAGE;
                }
            } else {
                $file = $baseUploadFolderLink . '/' . WWW_DIR . '/' . DEFAULT_IMAGE;
            }
        } else if ($show_empty_image === FALSE || $show_empty_image === 0) {
            return '';
        } else if (is_string($show_empty_image) && !empty($show_empty_image)) {
            if (!empty($width) && strpos($width, '%') > 0) {
                $width = 150;
            }
            $file = $baseUploadFolderLink . '/' . WWW_DIR . '/' . $show_empty_image;
        }
        return $file;
    }


    //2017.4.18
    public static function showCurrentUser()
    {
        return self::showUser(self::currentUser());
    }

    public static function showUser($userid, $keyField = '', $displayField = 'name', $link_url = '')
    {
        $result = self::getUserName($userid, $keyField, $displayField);
        return $result;
    }

    public static function showGoogleMap($address, $zoom = '18', $maptype = 'roadmap', $displaytype = 'place', $width = '100%', $height = '350', $api_key = '')
    {
        if (empty($api_key))
            $api_key = FHtml::config(FHtml::SETTINGS_GOOGLE_API_KEY, 'AIzaSyCHpSPTym5KTydcgF5iwSE721IG0E-VNQA', null, 'Google');

        $address = str_replace(' ', '+', $address);
        $address = self::getURLFriendlyName($address);
        $src = "https://maps.google.com/maps?width=$width&height=$height&hl=en&q=$address&zoom=$zoom&output=embed";
        //echo $src; die;

        $result = '<iframe width="' . $width . '" height="' . $height . '" frameborder="0" style="border:0" src="' . $src . '"></iframe>';
        return $result;
    }

    public static function getURLFriendlyName($name, $demiliter = '-', $seperator_chars = [], $removed_chars = [])
    {
        $name = seo_friendly($name);
        if (!empty($seperator_chars))
            $name = str_replace($seperator_chars, $demiliter, $name);

        if (!empty($removed_chars))
            $name = str_replace($removed_chars, '', $name);

        return strtolower($name);
    }

    /**
     * sanitize uploaded file name
     * Fixes encoding for utf8 and non standard characters on windows.
     */
    public static function getFriendlyFileName($filename, $random = false)
    {
        if ($random)
            return date('Ymd') . '_' . rand(0, 1000);
        else
            $filename = self::getURLFriendlyName($filename, '_') . '_' . date('YmdHis');

        return $filename;
    }


    public static function showModelPrice($model, $color = 'red', $show_friendly = true, $show_price = true)
    {
        if (!isset($model) || $show_price === false)
            return '';

        $params = self::getModelPriceParams($model);
        $old_price = $params['old_price'];
        $price = $params['price'];
        $currency = $params['currency'];
        $discount = $params['discount'];

        return self::showPrice($old_price, $price, $discount, $currency, $color, $show_friendly, $show_price);
    }

    public static function getModelPriceParams($model)
    {
        if (!isset($model))
            return 0;

        $old_price = FHtml::getFieldValue($model, ['price', 'cost', 'total_price']);
        $discount = FHtml::getFieldValue($model, ['discount', 'sale_off'], FEcommerce::settingSaleOff());

        $price = 0;
        $prefix = FHtml::getCurrency($model);

        if (!empty($discount)) {
            if (StringHelper::endsWith($discount, '%'))
                $discount = str_replace('%', '', $discount);

            if (is_numeric($discount)) {
                $price = $old_price * (100 - $discount) / 100;
            }
        } else {
            $price = $old_price;
        }

        return ['price' => $price, 'old_price' => $old_price, 'discount' => $discount, 'currency' => $prefix];
    }

    public static function getCurrency($model = null)
    {
        if (isset($model) && is_object($model))
            $result = FHtml::getFieldValue($model, ['currency', 'product_currency']);

        if (empty($result))
            $result = self::getCurrentCurrency();

        return $result;
    }

    public static function getCurrentCurrency()
    {
        return FHtml::settingCurrency();
    }

    public static function showPrice($old_value, $value = '', $discount = '', $prefix = '', $color = 'red', $show_friendly = true, $show_price = true)
    {
        if ($show_price === false || FEcommerce::settingHidePrice())
            return FHtml::t('common', 'Contact');

        if (empty($discount))
            $discount = FEcommerce::settingSaleOff();

        if (!empty($discount)) {
            $discount = str_replace('%', '', $discount);
            if (self::is_numeric($discount) && empty($value) && self::is_numeric($old_value)) {
                $value = $old_value - $discount * 0.01 * $old_value;
            }
        } else {
            $value = $old_value;
        }

        if (!empty($color) && strpos($color, ':') !== false)
            $result = '<span class="price-new text-' . $color . '">' . self::showCurrency($value, $prefix) . '</span>';
        else
            $result = '<span class="price-new" style="' . $color . '">' . self::showCurrency($value, $prefix) . '</span>';

        if (!empty($old_value) && !empty($show_friendly) && $old_value != $value)
            $result .= '<span class = "price-old line-through text-default" style="font-size: 70%; color:grey; text-decoration: line-through; ">' . self::showCurrency($old_value, $prefix) . '</span>';

        if (!empty($discount) && !empty($show_friendly)) {
            if (is_numeric($discount))
                $discount = " ($discount%)";
            $result .= '<small class = "price-discount" style="font-size: 70%;color: red">' . $discount . '</small>';
        }
        return $result;
    }

    public static function showNumber($value, $currency = '')
    {

        if (!isset($value) || empty($value) || !is_numeric($value))
            return '';

        if (self::is_numeric($value) && $value > FConstant::NUMERIC_MAX)
            return $value;

        $decimals = 0;

        if (is_string($value) || is_numeric($value)) {
            $arr = self::number_breakdown($value);
            if (!empty($currency)) {
                $decimals = self::getCurrencyDecimalDigits($currency);

                if (is_numeric($decimals))
                    $value = round($value, $decimals);
            } else {

                $dec1 = FHtml::settingDigitsAfterDecimalFormat();
                $decimals = $arr[1] > 0 ? strlen($arr[1]) - 2 : 0;

                //if ($decimals > $dec1)
                //$decimals = $dec1;
            }

            return number_format($value, $decimals, FHtml::settingDecimalSeparatorFormat(), FHtml::settingThousandSeparatorFormat());
        } else
            return $value;
    }

    public static function showCurrency($value, $prefix = '', $locale = '', $default_value = '')
    {
        if ($value == 0)
            return FHtml::t('common', $default_value);

        if (!isset($value) || empty($value) || !is_numeric($value))
            return '';

        $symbol = self::getCurrencySymbol($prefix, $locale);

        if (is_numeric($value) || (is_string($value) && !empty($value))) {
            $decimal_digits = self::getCurrencyDecimalDigits($prefix);

            //if (is_numeric($decimal_digits))
            // $value = round($value, $decimal_digits);
            return number_format($value, $decimal_digits, Fhtml::settingDecimalSeparatorFormat(), Fhtml::settingThousandSeparatorFormat()) . " <small style='font-size:100%; color:grey'>" . $symbol . '</small> ';
        } else
            return '';
    }

    public static function showCurrencyInWords($value, $prefix = '')
    {
        if (empty($prefix))
            $prefix = FHtml::settingCurrency();

        $decimal_digits = self::getCurrencyDecimalDigits($prefix);

        if (is_numeric($decimal_digits))
            $value = round($value, $decimal_digits);

        //echo "Round Number : $value =-> ";

        if (!isset($value) || empty($value) || !is_numeric($value))
            return '';

        if (is_numeric($value) || (is_string($value) && !empty($value)))
            return self::showNumberInWords($value, $prefix, $decimal_digits);
        else
            return '';
    }

    public static function showNumberInWords($number, $currency = '', $after_decimal_display_as_number = false)
    {
        if (is_numeric($currency))
            $level = $currency + 1;
        else
            $level = 0;

        if (!empty($currency) && is_string($currency)) // show currency
        {
            $hyphen = ' ';
            $conjunction = ' ';
            $separator = ' ';
            $connector = FHtml::t('number', 'and');
            $prefix = FHtml::t('number', $currency);
            $prefix2 = FHtml::t('number', 'cents');
            $less_than_ten = '';
        } else {
            $hyphen = ' ';
            $conjunction = ' ';
            $separator = ' ';
            $connector = FHtml::t('number', 'point');
            $prefix2 = '';
            $prefix = '';
            $less_than_ten = $level > 0 ? FHtml::t('number', '_and_') : '';
        }


        $negative    = FHtml::t('number', 'negative') . ' ';

        $dictionary  = array(
            0                   => FHtml::t('number', 'Zero'),
            '01' => FHtml::t('number', 'First'),
            '02' => FHtml::t('number', 'Second'),
            '03' => FHtml::t('number', 'Third'),
            '_4' => FHtml::t('number', '_Four'),
            '_5' => FHtml::t('number', '_Five'),
            //'#24' => FHtml::t('common', 'Twenty-four'), //special cases
            1                   => FHtml::t('number', 'One'),
            2                   => FHtml::t('number', 'Two'),
            3                   => FHtml::t('number', 'Three'),
            4                   => FHtml::t('number', 'Four'),
            5                   => FHtml::t('number', 'Five'),
            6                   => FHtml::t('number', 'Six'),
            7                   => FHtml::t('number', 'Seven'),
            8                   => FHtml::t('number', 'Eight'),
            9                   => FHtml::t('number', 'Nine'),
            10                  => FHtml::t('number', 'Ten'),
            11                  => FHtml::t('number', 'Eleven'),
            12                  => FHtml::t('number', 'Twelve'),
            13                  => FHtml::t('number', 'Thirteen'),
            14                  => FHtml::t('number', 'Fourteen'),
            15                  => FHtml::t('number', 'Fifteen'),
            16                  => FHtml::t('number', 'Sixteen'),
            17                  => FHtml::t('number', 'Seventeen'),
            18                  => FHtml::t('number', 'Eighteen'),
            19                  => FHtml::t('number', 'Nineteen'),
            20                  => FHtml::t('number', 'Twenty'),
            30                  => FHtml::t('number', 'Thirty'),
            40                  => FHtml::t('number', 'Fourty'),
            50                  => FHtml::t('number', 'Fifty'),
            60                  => FHtml::t('number', 'Sixty'),
            70                  => FHtml::t('number', 'Seventy'),
            80                  => FHtml::t('number', 'Eighty'),
            90                  => FHtml::t('number', 'Ninety'),
            100                 => FHtml::t('number', 'hundred'),
            1000                => FHtml::t('number', 'thousand'),
            1000000             => FHtml::t('number', 'million'),
            1000000000          => FHtml::t('number', 'billion'),
            1000000000000       => FHtml::t('number', 'million billion'),
            1000000000000000    => FHtml::t('number', 'trillion'),
            1000000000000000000 => FHtml::t('number', 'billion billion'),
        );

        if (!is_numeric($number)) {
            return false;
        }

        if ($number < 0) {
            return $negative . self::showNumberInWords(abs($number));
        }

        $string = $fraction = null;
        $rounding = self::getCurrencyIsRound($currency);

        if ($rounding) {
            $number = round($number);
            $decimal = ' ';
            $prefix1 = ' ' . $prefix . ' ';
        } else {
            if (!empty($number) && strpos($number, '.') !== false) {
                list($number, $fraction) = explode('.', $number);
            }
            if (!empty($fraction)) {
                $decimal = ' ' . $prefix . ' ' . $connector . ' ';
                if (!empty($currency)) //showing Currency mode
                    $prefix1 = ' ' . $prefix2;
                else
                    $prefix1 = ' ';
            } else {
                $decimal = ' ' . $connector . ' ';
                $prefix1 = ' ' . $prefix;
            }
        }

        switch (true) {
            case key_exists('#' . $number, $dictionary):
                $string = $dictionary['#' . $number];
                break;
            case $number < 10:
                if (key_exists('_' . $number, $dictionary) & $level > 0) {
                    $number = '_' . $number;
                }
                $string = " $less_than_ten " . $dictionary[$number];
                break;
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:

                $tens = ((int)($number / 10)) * 10;
                $units = $number % 10;

                $string = $dictionary[$tens];

                if (key_exists('_' . $units, $dictionary)) {
                    $units = '_' . $units;
                }

                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . self::showNumberInWords($remainder, $level);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int)($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = self::showNumberInWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= self::showNumberInWords($remainder, $level);
                }
                break;
        }

        $string = str_replace('_', '', $string);


        if (null !== $fraction && is_numeric($fraction)) {
            if ($after_decimal_display_as_number === true)
                $dec_after_comma = FHtml::settingDigitsAfterDecimalFormat();
            else if (is_numeric($after_decimal_display_as_number))
                $dec_after_comma = $after_decimal_display_as_number;
            else
                $dec_after_comma = 0;

            if ($dec_after_comma > 0) { //show after comma as numbers

                $dec_remain = $dec_after_comma - strlen($fraction);
                if ($dec_remain > 0) {
                    for ($i = 0; $i < $dec_remain; $i++)
                        $fraction = $fraction . '0';
                }

                $t = false;
                $fraction_t = '';
                for ($i = 0; $i < $dec_after_comma; $i++) {
                    if ($fraction[$i] != '0')
                        $t = true;
                    if ($t)
                        $fraction_t = $fraction_t . $fraction[$i];
                }

                $fraction = $fraction_t;
                $string .= $decimal;
                $string .= self::showNumberInWords($fraction);
                $string .= $prefix1;
                return $string;
            } else { // show after comma as digits
                $string .= $decimal;
                $words = array();
                foreach (str_split((string) $fraction) as $number) {
                    $words[] = $dictionary[$number];
                }
                $string .= implode(' ', $words);
                $string .= $prefix1;
                return $string;
            }
        } else {
            return $string . (!empty($currency) ? $prefix1 : '');
        }
    }

    public static function showModelRates($model, $max = 5, $color = 'yellow', $show_rate = true, $postfix = 'star', $pull_right = false)
    {
        $value = FHtml::getFieldValue($model, ['rate']);

        return self::showRates($value, $max, $color, $show_rate, $postfix, $pull_right);
    }

    public static function showRates($value, $max = 5, $color = 'yellow', $show_rate = true, $postfix = 'star', $pull_right = false)
    {
        if (!isset($value) || $show_rate === false)
            return '';

        if (!is_numeric($value))
            return $value;
        else if ($value < 10) {
            if ($pull_right === true)
                $result = '<div class="star-vote pull-right">';
            else
                $result = '<div class="star-vote">';

            $result .= '<ul class="list-inline">';
            for ($i = 1; $i <= $max; $i = $i + 1) {
                if ($i <= $value)
                    $result .= '<li><i class="color-' . $color . ' fa fa-' . $postfix . '"></i></li>';
                else if ($i > $value)
                    $result .= '<li><i class="color-' . $color . ' fa fa-' . $postfix . '-o"></i></li>';
            }

            $result .= '</ul></div>';

            return $result;
        }
        return number_format($value, 0, ".", ",") . $postfix;
    }

    public static function showQuote($value)
    {
        if (!empty($value))
            return "<blockquote class='hero'><p><em>$value</em></p></blockquote>";
        else
            return "";
    }

    //HungHX: 20160801

    public static function  showAlert($message = '', $type = '', $isGrowl = false, $delay = 3000)
    {
        if (empty($message)) {
            echo AlertBlock::widget([
                'useSessionFlash' => empty($message) ? true : false,
                'delay' => $delay,
                'type' => $isGrowl == true ? AlertBlock::TYPE_GROWL : AlertBlock::TYPE_ALERT
            ]);
        } else {
            if ($type == Alert::TYPE_DANGER || empty($type))
                $delay = null;
            echo Alert::widget([
                'delay' => $delay,
                'body' => $message,
                'titleOptions' => ['icon' => 'info-sign'],
                'title' => '',
                'type' => !empty($type) ? $type : Alert::TYPE_SUCCESS
            ]);
        }
    }

    public static function showNotification($title = '', $message = '', $icon = 'glyphicon glyphicon-info-sign', $type = Growl::TYPE_INFO, $isShowOnTop = false, $isShowOnRight = true)
    {
        echo Growl::widget([
            'type' => $type,
            'title' => $title,
            'icon' => $icon,
            'body' => $message,
            'showSeparator' => true,
            'delay' => 1500,
            'pluginOptions' => [
                'showProgressbar' => true,
                'placement' => [
                    'from' => $isShowOnTop == true ? 'top' : 'bottom',
                    'align' => $isShowOnRight == true ? 'right' : 'left',
                ]
            ]
        ]);
    }

    public static function showModel($model, $nameField = ['name', 'title', 'username'], $descriptionField = ['description', 'overview', 'status', 'type', 'category_id'], $layout = self::LAYOUT_TABLE, $css = 'text-default small', $inlineEdit = null)
    {
        return static::showModelFields($model, $nameField, $descriptionField, $layout, $css, $inlineEdit);
    }

    public static function showModelFields($model, $nameField = ['name', 'title', 'username'], $descriptionField = ['description', 'overview', 'status', 'type', 'category_id'], $layout = self::LAYOUT_TABLE, $css = 'text-default small', $inlineEdit = null)
    {
        $canEdit = $inlineEdit && FHtml::isInRole($model, FHtml::ACTION_EDIT);
        if ($layout == self::LAYOUT_TABLE) {
            if (!is_array($nameField))
                $nameField = [$nameField];
            if (!is_array($descriptionField))
                $descriptionField = [$descriptionField];

            $descriptionField = array_merge($nameField, $descriptionField);
            $nameField = [];
        }

        $result = !empty($nameField) ? '<b>' . FHtml::showModelField($model, $nameField, '', $layout, $css, '', '', '', '', $canEdit) . '</b>' : '';
        if (!empty($descriptionField))
            $result = $result . FHtml::showModelField($model, $descriptionField, '', $layout, $css, '', '', '', '', $canEdit);

        return $result;
    }

    public static function showModelField($model, $field, $showType = '', $layout = self::LAYOUT_NO_LABEL, $css = 'text-default small', $table = '', $column = '', $dbType = '', $pjax_container = '', $inlineEdit = null)
    {
        if ($showType == 'readonly' || FHtml::isReadOnly($model)) {
            $inlineEdit = false;
            $showType = '';
        }

        if ($field == 'id')
            return $model[$field];

        $result = '';
        $arr = [];

        if (is_array($field)) {
            $arr = $field;
        } else
            $arr[] = $field;

        if (empty($arr))
            return '';

        if (is_array($field) && count($field) > 1 && $layout == self::LAYOUT_TABLE)
            $result .= '<table class="table table-bordered" style="width:100%">';

        if (empty($table))
            $table = self::getTableName($model);

        foreach ($arr as $field1) {
            try {

                $arr1 = FHtml::parseAttribute($field1);

                $field1 = $arr1['attribute'];
                $field_value = null;
                $field_existed = self::field_exists($model, $field1);

                if (FHtml::settingDynamicFieldEnabled() || $field_existed) {
                    $field_value = FHtml::getFieldValue($model, $field1);
                }

                if ($field_existed || !empty($field_value)) {
                    if (!empty($arr1['format']) && $arr1['format'] != 'raw')
                        $showType1 = $arr1['format'];
                    else if (empty($showType)) {
                        $showType1 = self::getShowType($model, $field1);
                    } else {
                        $showType1 = $showType;
                    }
                    if (!FHtml::isInArray($field1, FHtml::getFIELDS_GROUP(), $table))
                        $newline = '<br/>';
                    else
                        $newline = '&nbsp;';

                    if (!empty($arr1['label']))
                        $label = $arr1['label'];
                    else
                        $label = FHtml::getFieldLabel($model, $field1);

                    $result .= self::showField($label, $field_value, $showType1, $layout, $css, $table, $field1, $dbType, $model->primaryKey, FHtml::NULL_LABEL, $newline, $inlineEdit, $pjax_container);
                }
            } catch (Exception $e) {
                return FHtml::addError($e);
            }
        }

        if (is_array($field) && count($field) > 1 && $layout == self::LAYOUT_TABLE)
            $result .= '</table>';

        return $result;
    }

    public static function getShowType($model, $field1 = '')
    {
        $column = $field1;

        if (is_string($model)) {
            $showType1 = $model;
            if (in_array($showType1, [FHtml::EDITOR_SWITCH, FHtml::EDITOR_BOOLEAN]))
                return FHtml::SHOW_ACTIVE;
            $table = $model;
            $model = FHtml::createModel($model);
        } else {
            $table = FHtml::getTableName($model);
        }

        if (isset($model) && FHtml::field_exists($model, 'getShowType'))
            $showType1 = $model->getShowType($column);
        else
            $showType1 = '';

        if (!empty($showType1))
            return $showType1;

        $moduleName = FHtml::getModelModule($table);

        //check SHOW_LOOKUP, SHOW_LABEL first
        $lookup_params = self::getLookupSettings($model, $field1);

        if (!empty($lookup_params)) {
            $keys = ["$table.$column", "common.$column", "$moduleName.$column", "$column"];
            foreach ($keys as $key) {
                if (key_exists($key, $lookup_params))
                    return is_array($lookup_params[$key]) ? FHtml::SHOW_LABEL : (FHtml::SHOW_LOOKUP . $lookup_params[$key]);
            }
        }

        if (StringHelper::startsWith($field1, 'is_')) {
            $showType1 = FHtml::SHOW_ACTIVE;
        } else if (FHtml::isInArray($field1, FHtml::FIELDS_DATE, $model)) {
            $showType1 = FHtml::SHOW_DATE;
        } else if (FHtml::isInArray($field1, ['category_id', 'categoryid'])) {
            $showType1 = FHtml::SHOW_LABEL;
        } else if (FHtml::isInArray($field1, FHtml::getFIELDS_GROUP(), $model)) {
            $showType1 = FHtml::SHOW_LABEL;
        } else if (FHtml::isInArray($field1, FHtml::FIELDS_IMAGES, $model)) {
            $showType1 = FHtml::SHOW_IMAGE;
        } else if (StringHelper::endsWith($field1, '_id')) {
            $showType1 = FHtml::SHOW_LOOKUP;
        } else if (StringHelper::endsWith($field1, 'color')) {
            $showType1 = FHtml::SHOW_COLOR;
        } else if (FHtml::isInArray($field1, ['*user', '*user_id'])) {
            $showType1 = FHtml::SHOW_USER;
        } else if (FHtml::isInArray($field1, FHtml::FIELDS_PRICE, $model)) {
            $showType1 = FHtml::SHOW_CURRENCY;
        } else {
            $showType1 = '';
        }
        return $showType1;
    }

    public static function getLookupSettings($model, $column = '', $moduleName = null, $module = null)
    {
        if (is_string($model)) {
            $table = $model;
            $model = FHtml::createModel($table);
        } else {
            $table = FHtml::getTableName($model);
        }

        if (!isset($model))
            return [];

        $moduleName = isset($moduleName) ? $moduleName : FHtml::getModelModule($table);
        $module = isset($module) ? $module : FHtml::getModuleObject($moduleName);
        $lookup = [];
        if (isset($model)) {
            if (self::field_exists($model, 'LOOKUP') && $model::LOOKUP !== null)
                $lookup = array_merge($lookup, $model::LOOKUP);
        }

        if (isset($module)) {
            if (self::field_exists($module, 'LOOKUP') && $module::LOOKUP !== null)
                $lookup = array_merge($lookup, $module::LOOKUP);

            if (self::field_exists($module, 'SETTINGS') && $module::SETTINGS !== null)
                $lookup = array_merge($lookup, $module::SETTINGS);
        }

        //get data from ApplicationHelper
        $module = FHtml::getApplicationHelper();
        if (isset($module)) {
            if (self::field_exists($module, 'LOOKUP') && $module::LOOKUP !== null)
                $lookup = array_merge($lookup, $module::LOOKUP);

            if (self::field_exists($module, 'SETTINGS') && $module::SETTINGS !== null)
                $lookup = array_merge($lookup, $module::SETTINGS);
        }

        if (!empty($column)) {
            $arr =  FConfig::getApplicationParams(true, true, false);
            foreach ($arr as $key => $value) {
                if (is_array($value))
                    $lookup = array_merge($lookup, [$key => $value]);
            }

            $result = [];
            $keys = ["$table.$column", "common.$column", "$moduleName.$column", "$column"];
            foreach ($keys as $key) {
                if (key_exists($key, $lookup))
                    $result = array_merge([$key => $lookup[$key]]);
            }
            return $result;
        }

        return $lookup;
    }


    public static function showField($label, $value, $showType = '', $layout = self::LAYOUT_NEWLINE, $css = 'text-default small', $table = '', $column = '', $dbType = '', $model_id = '', $empty_value = FConstant::NULL_LABEL, $newline = '<br/>', $inlineEdit = null, $pjax_container = '')
    {
        $str = '';
        if ($layout == self::LAYOUT_NEWLINE) {
            if (strlen($label) > 0) {
                $str .= '<label class="[css]">[label]</label><br/>[value]';
            } else {
                $str .= '[value]';
            }
            $newline = !empty($newline) ? $newline : '<br/><br/>';
        } else if ($layout == self::LAYOUT_ONELINE) {
            if (strlen($label) > 0) {
                $str .= '<div class="row"><div class="col-md-4"><label class="[css]">[label]</label></div><div class="col-md-8">[value]</div></div>';
            } else {
                $str .= '[value]';
            }
            $newline = !empty($newline) ? $newline : '<br/>';
        } else if ($layout == self::LAYOUT_ONELINE_RIGHT) {
            if (strlen($label) > 0) {
                $str .= '<div class="row"><div class="col-md-12"><label class="[css]">[label]</label><span class="pull-right">[value]</span></div></div>';
            } else {
                $str .= '[value]';
            }
            $newline = !empty($newline) ? $newline : '<br/>';
        } else if ($layout == self::LAYOUT_TEXT) {
            if (strlen($label) > 0) {
                $str .= '<label class="[css]">[label]:&nbsp;&nbsp; </label>[value]';
            } else {
                $str .= '[value]';
            }
            $newline = !empty($newline) ? $newline : '<br/><br/>';
        } else if ($layout == self::LAYOUT_NO_LABEL) {
            $str .= '[value]';
            $newline = !empty($newline) ? $newline : '<br/><br/>';
        } else if ($layout == self::LAYOUT_TABLE) {
            $str .= '<tr><td class="col-md-3 col-xs-3 form-label">[label]</td></td><td>[value]</td></tr>';
            $newline = '';
        } else {
            if (empty($str) || strpos('[value]', $str) === false)
                $str .= '[value]';

            $newline = !empty($newline) ? $newline : '<br/>';
        }

        if ($label == FHtml::NULL_VALUE)
            $label = '&nbsp;';


        $result = self::showFieldValue($value, $model_id, $showType, $table, $column, $dbType, $inlineEdit, $pjax_container);
        //$result .= "Inline: $inlineEdit";

        if (empty($result))
            $result = $empty_value;

        $a = array($css, $label, $result);
        $b = array('[css]', '[label]', '[value]');
        $str = str_replace($b, $a, $str) . $newline;
        return $str;
    }

    //2017/3/6

    public static function showFieldValue($value, $model_id = '', $showType = '', $table = '', $column = '', $dbType = '', $inlineEdit = null, $pjax_container = '', $edit_type = '')
    {
        if (is_array($value)) {
            $result = FHtml::showContent($value, $showType, $table, $column, $dbType);
        } else {
            $result = $value;
        }

        if (empty($pjax_container))
            $pjax_container = self::getPjaxContainerId($table, 'crud-datatable');

        if (!isset($inlineEdit)) {
            $inlineEdit = FHtml::isAuthorized(FHtml::ACTION_EDIT, $table) || FHtml::isAuthorized(FHtml::ACTION_EDIT, FHtml::currentController());
        }

        if ($inlineEdit && !FHtml::isInArray($showType, ['readonly', 'view', 'image'])  && FHtml::settingAdminInlineEdit($edit_type)) { // has permission to edit
            if (FHtml::isInArray($column, FHtml::FIELDS_BOOLEAN, $table)) {
                $result = FHtml::showBooleanEditable($result, $value, $column, $model_id, $table, $inlineEdit, $pjax_container); // Make Field Editable
            } else {
                $result = FHtml::showContentEditable($result, $value, $column, $model_id, $table, $showType, true, false, $inlineEdit, $edit_type, null, $pjax_container); // Make Field Editable
            }
        }

        if (FHtml::isInArray($column, FHtml::FIELDS_BOOLEAN, $table)) {
            $result = '<span class="label-success glyphicon glyphicon-ok " style="border: none !important;"> </span>';
        }

        return $result;
    }

    public static function showContent($value, $showType = '', $table = '', $column = '', $dbType = '', $type = '', $empty_value = FConstant::NULL_LABEL, $image_width = '50%', $seperator = ' . ')
    {
        $column = str_replace('_id_array', '_id',  $column);

        $result = '';

        if (is_object($value))
            return '';

        if ($column == 'id') {
            $module = FHtml::currentModule();
            $controller = FHtml::currentController();

            $link_url = FHtml::createLink("$module/$controller/view-detail", ['id' => $value], BACKEND, $value, '', '');
            return $link_url;
        }

        if (FHtml::isInArray($column, ['*user_id', '*_user', 'user'])) {
            $showType = FHtml::SHOW_LOOKUP;
            $table = FHtml::TABLE_USER;
        } else if ($column == 'category_id') {
            $showType = FHtml::SHOW_LABEL;
            $table = FHTml::TABLE_CATEGORIES;
        } else if (FHtml::isInArray($column, ['is_*'])) {
            $showType = FHtml::SHOW_BOOLEAN;
        }

        if (StringHelper::startsWith($showType, FHtml::SHOW_LOOKUP)) {
            $table1 = str_replace(FHtml::SHOW_LOOKUP, '', $showType);
            if (!empty($table1))
                $table = str_replace('@', '', $table1);
            $showType = FHtml::SHOW_LOOKUP;
        }

        if ($showType == FHtml::SHOW_LOOKUP) {
            $value = self::parseValueAsArray($value);
        }

        if (is_array($value)) {
            return FHtml::showArrayAsTable($value, 'table');
        } else {

            if (is_numeric($value) && empty($showType) && FHtml::isInArray($column, ['*price', '*cost', '*quantity', 'count_*', '*_view', 'view_*', '*_count', '*_number']))
                $showType = FHtml::SHOW_NUMBER;

            if ($showType == FHtml::SHOW_LABEL)
                $result = self::showLabel($table . '\\' . $column, $table, $column, $value);
            else if ($showType == FHtml::SHOW_COLOR)
                $result = self::showColor($value);
            else if ($showType == FHtml::SHOW_HIDDEN)
                return "";
            else if ($showType == FHtml::SHOW_NUMBER)
                $result = self::showNumber($value);
            else if ($showType == FHtml::SHOW_CURRENCY)
                $result = self::showCurrency($value);
            else if ($showType == FHtml::SHOW_RATE)
                $result = self::showRates($value);
            else if ($showType == FHtml::SHOW_BOOLEAN) {
                $result = self::showBoolean($value);
            } else if ($showType == FHtml::SHOW_ACTIVE) {
                $result = self::showActive($value, FHtml::getFieldLabel($table, $column), FHtml::getColorFromLabel(str_replace('is_', '', $column)));
            } else if ($showType == FHtml::SHOW_PARENT) {
                $result = self::showParent($value, $table);
            } else if ($showType == FHtml::SHOW_LOOKUP) {
                $result = self::showLookup($value, $table);
            } else if ($showType == FHtml::SHOW_HTML) {
                $result = self::showHtml($value, $table);
            } else if ($showType == FHtml::SHOW_USER) {
                $result = self::showUser($value);
            } else if ($showType == FHtml::SHOW_ROLE) {
                $result = self::showRole($value);
            } else if ($showType == FHtml::SHOW_DATE)
                $result = self::showDate($value);
            else if ($showType == FHtml::SHOW_TIME)
                $result = self::showTime($value);
            else if ($showType == FHtml::SHOW_DATETIME)
                $result = self::showDateTime($value);
            else if ($showType == FHtml::SHOW_IMAGE) {
                if (empty($image_width))
                    $result = FHtml::showImageThumbnail($value, FHtml::config(FHtml::SETTINGS_THUMBNAIL_SIZE, 50), str_replace('_', '-', $table));
                else
                    $result = self::showImage($value, str_replace('_', '-', $table), 0, "100px");
            } else {
                if (!is_null($value)) {
                    $result = $value;
                    if (strpos($value, "</p>") === false)
                        $result = str_replace("\n", '<br/>', $result);
                } else
                    $result = '';
            }

            if ((!isset($result) || $result == '') && $empty_value != '')
                $result = '<small class="text-default hidden-print">' . $empty_value . '</small>';
        }


        return $result;
    }


    public static function getColorFromArray($key, $table, $column, $value)
    {
        $color = self::getColorFromLabel($value);
        if (!empty($color))
            return $color;

        if (is_array($key))
            $array = $key;
        else
            $array = self::getComboArray($key, $table, $column);

        $i = 0;

        foreach ($array as $id => $name) {
            if ($id == $value || strtolower($id) == strtolower($value))
                break;
            $i = $i + 1;
        }

        return $i < count(FHtml::COLORS) ? FHtml::COLORS[$i] : 'default';
    }

    //2017/3/30
    public static function getColorFromLabel($value)
    {
        $array = self::LABEL_COLORS;
        foreach ($array as $color => $arr) {
            if (FHtml::isInArray($value, $arr))
                return $color;
        }

        return '';
    }

    //2017/3/6
    public static function showLabel($key, $table, $column, $value, $color = 'default', $is_background = true, $empty_value = FConstant::NULL_LABEL)
    {
        if ($color == 'none' || $color == false) {
            $is_background = false;
            $color = false;
        }

        if (is_string($key)) {
            $key = str_replace('\\', '.', $key);
        }

        //sometime it pass 'Table.column' as $table, so we need to remove .column part
        if (!empty($table) && strpos($table, '.') > 0)
            $table = reset(explode('.', $table));
        if (!empty($table) && strpos($table, '@') > 0)
            $table = str_replace('@', '', $table);

        $text = '';
        $html = '';

        if (!isset($value) || strlen($value) == 0) {
            $html = empty($empty_value) ? $empty_value : ("<span class='text-default hidden-print'>" . $empty_value . "</span>");
        } else {

            $arr1 = FHtml::decode($value, true);
            if (is_string($arr1))
                $arr1 = [$arr1];

            if (is_array($arr1)) {

                if ($column == 'category_id') {
                    $key = '@object_category';
                }

                //Fix show Label issue
                $lookupArray = FHtml::getComboArray($key, $table, $column);

                foreach ($arr1 as $value) {
                    $value = trim($value, ',');
                    if (empty($value))
                        continue;

                    if (!empty($lookupArray) && isset($lookupArray[$value])) {
                        $text = $lookupArray[$value];
                        $color = $color === false ? $color : self::getColorFromArray($lookupArray, $table, $column, $value);
                    } else if (!empty($lookupArray) && isset($lookupArray[strtolower($value)])) {
                        $text = $lookupArray[strtolower($value)];
                        $color = $color === false ? $color : self::getColorFromArray($lookupArray, $table, $column, $value);
                    } else {
                        if ($column == 'category_id') {
                            $metaItem = models\ObjectCategory::findAll(['id' => explode(',', $value)]);

                            if (isset($metaItem)) {
                                $color = $color === false ? $color : "primary";
                                $names = array_column($metaItem, 'name');

                                foreach ($names as $name) {
                                    $html .= FHtml::showColor('primary', $name, $is_background) . ' ';
                                }
                                return $html;
                            } else {
                                $color = $color === false ? $color : "primary";
                                $text = "";
                            }
                        } else {
                            $metaItem = !FHtml::isTableExisted(FHtml::TABLE_OBJECT_SETTING) ? null : FHtml::getModel(FHtml::TABLE_OBJECT_SETTING, '', ['object_type' => $table, 'meta_key' => $column, 'key' => $value], null, false);
                            if (isset($metaItem)) {
                                $color = $color === false ? $color : $metaItem->color;
                                $text = FHtml::getFieldLabel($table, $metaItem->value);
                            } else {
                                $color = $color === false ? $color : self::getColorFromArray($key, $table, $column, $value);
                                $text = FHtml::getFieldLabel($table, $value);
                            }
                        }
                    }

                    //2017/3/30
                    if (FHtml::isInArray($column, FHtml::FIELDS_GROUP) && !empty($color))
                        $html .= self::showColor($color, $text, $is_background) . '';
                    else
                        $html .= $text;
                }
            }
        }

        return $html;
    }

    public static function showColor($color, $label = FConstant::NULL_LABEL, $is_background = true)
    {
        if (empty($color)) {
            $is_background = false;
        }

        if ($label == '') {
            $label = $color;
            $color = 'default';
        }

        $color = strtolower($color);

        if (StringHelper::startsWith($label, '#')) {
            $color = $label;
        }

        if (strlen($color) > 0) {
            if ($is_background) {
                if (strpos($color, '#') !== false || !in_array($color, ['default', 'primary', 'success', 'warning', 'alert', 'danger']))
                    return "<span class='label label-sm' style='background-color: {$color}; border:none !important;'> $label </span>  ";
                else {
                    return "<span class='label label-sm label-{$color}'>  $label </span> ";
                }
            } else {
                if (empty($color))
                    return $label;
                else if (strpos($color, '#') !== false || !in_array($color, ['default', 'primary', 'success', 'warning', 'alert']))
                    return "<span class='' style='color: {$color}'> $label </span> ";
                else
                    return "<span class='text-{$color}'>  $label </span> ";
            }
        } else
            return "";
    }

    //2017/3/30

    public static function showBoolean($value)
    {
        return FHtml::isRoleAdmin() ? self::showBooleanEditable(self::showIsActiveLabel($value), $value) : self::showIsActiveLabel($value);
    }

    public static function showIsActiveLabel($key, $label = '', $empty_value = FConstant::NULL_LABEL)
    {
        if (empty($label))
            $label = FHtml::t('common', "Yes");

        $str = array(
            true => '<span class="label label-sm label-success">' . $label . '</span>',
            false => '<span class="label label-sm label-default">' . $empty_value . '</span>',
            1 => '<span class="label label-sm label-success">' . $label . '</span>',
            0 => '<span class="label label-sm label-default">' . $empty_value . '</span>',
        );
        return isset($str[$key]) ? $str[$key] : $key;
    }

    public static function showActive($value, $label = '', $color = '')
    {
        $label = strtolower($label);
        $bool = self::getBool($value);
        if (empty($color))
            $color = 'success';

        if (!empty($label) && $bool) {
            $html = '<span class="label label-sm label-' . $color . '">' . $label . '</span>';
        } else {
            $html = self::showIsActiveLabel($bool);
        }
        return $html;
    }

    public static function showParent($value, $table, $keyField = 'id', $displayField = 'name')
    {
        return self::showLookup($value, $table, $keyField, $displayField);
    }

    public static function showLookup($value, $table, $keyField = 'id', $displayField = 'name', $link_url = '{module}/view')
    {
        $table = str_replace('@', '', $table);

        if ($table == 'user' || $table == 'app_user') {
            $keyField = 'id';
            $displayField = 'name';
        }

        if (!isset($value))
            return '';

        if (!FHtml::isTableExisted($table))
            return "$value";

        // long test with postgres sql
        if ($keyField == 'id' && $value == '') {
            $value = 0;
        }

        $sql_select = '*';
        $sql_table = $table;
        $query = new FQuery();
        $query->select($sql_select)
            ->from($sql_table);
        $query->andWhere([$keyField => $value]);
        $data = $query->one();

        if (isset($data) && FHtml::field_exists($data, $displayField)) {
            $result = FHtml::getFieldValue($data, $displayField);

            if (!empty($link_url) && FHtml::field_exists($data, $keyField)) {
                $module = self::getModelModule($table);
                if (!empty($module))
                    $module = $module . '/' . BaseInflector::camel2id($table);
                else
                    $module = BaseInflector::camel2id($table);

                $link_url = str_replace('{module}', $module, $link_url);
                $link_url = FHtml::createUrl($link_url, [$keyField => FHtml::getFieldValue($data, $keyField)]);
                $result = $result .  ' <a title="' . FHtml::t('common', 'View Detail') . '" href="' . $link_url . '" data-pjax=0 role="modal-remote" target="_blank" class="hidden-print"><span class="glyphicon glyphicon-link" style="color:grey; font-size:80%"></span></a>';
            }
            return $result;
        } else
            return $value;
    }

    //HungHX: 20160801

    public static function showHtml($content, $display_type = '', $replace_params = ["\n" => '<br/>', '-' => '<i class="fa fa-check" aria-hidden="true"></i>', '*' => '<i class="fa fa-check" aria-hidden="true"></i>'])
    {
        if (empty($content))
            return '';

        if (in_array($display_type, ['pre', 'script', 'style', 'p', 'div', 'span', 'section'])) {
            $content = "<$display_type>\n$content\n</$display_type>";
        }

        if ($display_type == 'markdown') {
            $Parsedown = new Parsedown();
            return $Parsedown->text($content);
        }

        $content = self::strReplace($content, $replace_params);

        return Html::decode($content);
    }

    /**
     * @param string $text
     * @param array $htmlOptions
     * @param string $icon
     * @return string
     */
    public static function showLink($link_url = '', $text = '', $htmlOptions = array(), $icon = '')
    {
        if (empty($link_url))
            return '#';

        if (!StringHelper::startsWith($link_url, 'http') && !StringHelper::startsWith($link_url, 'www')) //not full link url
            $link_url = FHtml::createUrl($link_url);

        if (empty($text))
            return $link_url;

        $html = '<a href="' . $link_url . '" ' . self::renderAttributes($htmlOptions) . '>';
        if (!empty($icon))
            $html .= '<i class="' . $icon . '"></i> ';
        $html .= $text;
        $html .= '</a>';
        return $html;
    }

    //HungHX: 20160801

    public static function showRole($value, $table = '', $keyField = 'id', $displayField = 'name')
    {
        if (!isset($value))
            return '';

        $array = self::getComboArray('', '', 'role');
        if (isset($array[$value]))
            return $array[$value];
        else
            return $value;
    }

    public static function showDate($date = null, $format = '', $showTime = false)
    {
        if (!isset($date) || empty($date))
            return '';

        $date = trim($date);
        if (empty($format))
            $format = FHtml::settingDateFormat();
        else if (is_bool($format)) {
            $showTime = $format;
            $format = FHtml::settingDateFormat();
        } else if ($format == 'ago' || $format == 'friendly') {
            return self::showTimeAgo($date);
        }

        if (!isset($showTime)) {
            if (is_string($date) && strlen($date) > 10) {
                $showTime = true;
            } else {
                $showTime = false;
            }
        }

        if (strlen($format) <= 10 && $showTime) {
            $time_format = FConfig::settingTimeFormat();
            $format = $format . ' ' . $time_format; //m.d.Y H:ipm
        }

        if (self::is_timestamp($date)) { // TimeStamp format
            if ($date > date('Y') * 10) {
                if (strlen($format) <= 10 && $showTime)
                    $format = $format . ' g:i A';

                return date($format, $date);
            } else
                return $date;
        } else {
            $timestamp = strtotime($date);
        }

        $f = \DateTime::createFromFormat('Y-m-d', $date);

        $valid = \DateTime::getLastErrors();
        if ($valid['warning_count'] == 0 and $valid['error_count'] == 0) {
            return FHtml::strReplace(date($format, $timestamp), ['12:00 AM', '00:00 AM', '00:00:00', '0000-00-00']);
        }

        $f = \DateTime::createFromFormat('Y-m-d H:i:s', $date);
        $valid = \DateTime::getLastErrors();
        if ($valid['warning_count'] == 0 and $valid['error_count'] == 0) {
            return FHtml::strReplace(date($format, $timestamp), ['12:00 AM', '00:00 AM', '00:00:00', '0000-00-00']);
        }

        return FHtml::strReplace($date, ['12:00 AM', '00:00 AM', '00:00:00', '0000-00-00']);
    }

    public static function showDateTime($time, $format = '')
    {
        if (empty($format))
            $format = FHtml::settingDateTimeFormat();

        return self::showDate($time, $format, true);
    }

    public static function showTime($time, $format = '')
    {
        if (empty($format))
            $format = FHtml::settingTimeFormat();

        if (is_string($time))
            return $time;

        if (strlen($time) != 0) {
            return $time = date($format, strtotime($time));
        } else
            return '';
    }

    public static function showSeconds($seconds)
    {
        return gmdate("H:i:s", $seconds);
    }


    //HungHX: 20160801
    public static function showTimeAgo($date, $html = true)
    {
        $ago = self::timeAgo($date);
        return $html ? "<small style='color:grey'>$ago</small>" : $ago;
    }

    public static function showDateTimeAgo($date, $format = '', $html = true)
    {
        return FHtml::showDateTime($date, $format) . '<br/>' . FHtml::showTimeAgo($date, $html);
    }

    public static function timeAgo($time_ago)
    {
        if (!is_numeric($time_ago))
            $time_ago = strtotime($time_ago);

        $cur_time   = time();
        $time_elapsed   = $cur_time - $time_ago;
        $seconds    = $time_elapsed;
        $minutes    = round($time_elapsed / 60);
        $hours      = round($time_elapsed / 3600);
        $days       = round($time_elapsed / 86400);
        $weeks      = round($time_elapsed / 604800);
        $months     = round($time_elapsed / 2600640);
        $years      = round($time_elapsed / 31207680);
        // Seconds
        if ($seconds <= 60) {
            return Fhtml::t('datetime', 'Just now');
        }
        //Minutes
        else if ($minutes <= 60) {
            if ($minutes == 1) {
                return FHtml::t('datetime', 'One minute ago');
            } else {
                return $minutes . ' ' . FHtml::t('datetime', 'minutes ago');
            }
        }
        //Hours
        else if ($hours <= 24) {
            if ($hours == 1) {
                return FHtml::t('datetime', 'An hour ago');
            } else {
                return $hours . ' ' . FHtml::t('datetime', 'hrs ago');
            }
        }
        //Days
        else if ($days <= 7) {
            if ($days == 1) {
                return FHtml::t('datetime', 'Yesterday');
            } else {
                return $days .  ' ' . FHtml::t('datetime', 'days ago');
            }
        }
        //Weeks
        else if ($weeks <= 4.3) {
            if ($weeks == 1) {
                return FHtml::t('datetime', 'A week ago');
            } else {
                return $weeks . ' ' . FHtml::t('datetime', 'weeks ago');
            }
        }
        //Months
        else if ($months <= 12) {
            if ($months == 1) {
                return FHtml::t('datetime', 'A month ago');
            } else {
                return $months . ' ' . FHtml::t('datetime', 'months ago');
            }
        }
        //Years
        else {
            if ($years == 1) {
                return FHtml::t('datetime', 'One year ago');
            } else {
                return $years . ' ' . FHtml::t('datetime', 'years ago');
            }
        }
    }

    public static function showImageThumbnail($image, $width = 50, $model_dir = PRODUCT_DIR)
    {
        if (empty($width))
            $width = 50;
        return self::showImage($image, $model_dir, $width, 0);
    }


    public static function registerEditorJS($field, $type = FHtml::EDIT_TYPE_INPUT, $container = 'crud-datatable-pjax')
    {
        $container_name = BaseInflector::camelize($container);
        if ($type == FHtml::EDIT_TYPE_INPUT) {
            $js = '$("body").on("change", ".editable-' . $field . '", function() {
                saveChange("' . FHtml::createBaseAPIUrl('change') . '", $(this), "' . $container . '");
            });';

            FHtml::currentView()->registerJs($js, View::POS_END);
            //FHtml::currentView()->registerJs($js, FView::POS_AJAX_COMPLETE);

        } else if ($type == FHtml::EDIT_TYPE_INLINE) {
            $js = @'
                function saveEditor' . $container_name . '($editorid) {
                    saveEditor("' . FHtml::createBaseAPIUrl('change') . '", $editorid, "' . $container . '");
                }
                
                function saveBoolean' . $container_name . '($editorid) {
                    saveBoolean("' . FHtml::createBaseAPIUrl('active') . '", $editorid, "' . $container . '");
                }
                
                function saveChange' . $container_name . '($editorid) {
                    saveChange("' . FHtml::createBaseAPIUrl('change') . '", $editorid, "' . $container . '");
                }
               ';

            FHtml::currentView()->registerJs($js, View::POS_END);
            //FHtml::currentView()->registerJs($js, FView::POS_AJAX_COMPLETE);
        }
    }

    public static function showSaveChangeButton($content, $value, $field, $id, $object_type = '', $canEdit = true, $pjax_container = '', $jsFunction = 'saveChange{container_id}')
    {

        if ($canEdit != true || (is_string($canEdit) && !FHtml::isAuthorized($canEdit, $object_type, $field)))
            return '';

        if (empty($pjax_container))
            $pjax_container = self::getPjaxContainerId($object_type, 'crud-datatable');
        $container_name = BaseInflector::camelize($pjax_container);

        FHtml::registerEditorJS($field, FHtml::EDIT_TYPE_INLINE, $pjax_container);

        $result = '';
        $rand = $object_type; //rand(0, 100000);
        $field_label = FHtml::getFieldLabel($object_type, $field);

        if ((empty($value) && is_string($value)) || $value === FHtml::NULL_VALUE || empty($value))
            $content = self::getInlineEditLabel($field_label);

        $result = '<span id = "{uid}-{id}-{field}" title="' . $field_label . '" value="{value}" model_id="{id}" model_field="{field}" object_type="{object_type}" onMouseOver="this.style.cursor=\'pointer\'" onclick="{js_function}(\'{uid}-{id}-{field}\')">{content}</span>';

        $result = str_replace('{id}', $id, $result);
        $result = str_replace('{field}', $field, $result);
        $result = str_replace('{value}', $value, $result);
        $result = str_replace('{content}', $content, $result);
        $result = str_replace('{uid}', $rand, $result);
        $result = str_replace('{js_function}', $jsFunction, $result);
        $result = str_replace('{container_id}', $container_name, $result);

        $result = str_replace('{object_type}', $object_type, $result);
        return $result;
    }

    public static function showBooleanEditable($content, $value, $field = '', $id = '', $object_type = '', $canEdit = true, $pjax_container = '')
    {
        return self::showSaveChangeButton($content, $value, $field, $id, $object_type, $canEdit, $pjax_container, 'saveBoolean{container_id}');
    }

    public static function getInlineEditLabel($field)
    {
        return "<span class='hidden-print' style='color:lightgrey' title='" . FHtml::t('common', 'Click to edit') . " '>(" . $field . ")</span>";
    }

    public static function showContentEditable($content, $value, $field = '', $id = '', $object_type = '', $editor = 'textarea', $showSaveButtons = true, $showEditor = false, $canEdit = true, $edit_type = '', $model = null, $pjax_container = '')
    {
        $action = FHtml::currentAction();
        $zone = FHtml::currentZone();
        $controller = str_replace('-', '_', FHtml::currentController());

        if (!empty($canEdit)) {
            $is_authorized = FHtml::isAuthorized(FHtml::ACTION_EDIT, $object_type, $field);

            if ($controller != $object_type && !$is_authorized)
                $is_authorized = $is_authorized || FHtml::isAuthorized(FHtml::ACTION_EDIT, $controller, $field);
        } else {
            $is_authorized = false;
        }

        if (FConfig::settingAdminInlineEdit($edit_type) !== true && ($action == 'index' && $zone == BACKEND) && (FHtml::isInArray($field, ['id']))) {
            $module = FHtml::currentModule();
            $controller = str_replace('_', '-', $object_type);
            $url = FHtml::createUrl("/$module/$controller/view", ['id' => $id]);
            return "<a data-pjax='0' href='$url'>$content</a>";
        }

        if ((FConfig::settingAdminInlineEdit($edit_type) !== true && ($action == 'index' && $zone == BACKEND) && !FHtml::isInArray($editor, ['label', 'textarea', FHtml::EDITOR_BOOLEAN, FHtml::SHOW_BOOLEAN, FHtml::SHOW_ACTIVE])) || empty($canEdit) || $is_authorized != true)
            return $content;

        if (FHtml::isInArray($field, FHtml::FIELDS_BOOLEAN, $object_type)) {
            $result = FHtml::showBooleanEditable($content, $value, $field, $id, $object_type, $canEdit, $pjax_container); // Make Field Editable
            return $result;
        }

        $canEdit = is_string($canEdit) ? $canEdit : FHtml::getRequestParam('edit_type', $edit_type);
        if (empty($pjax_container))
            $pjax_container = self::getPjaxContainerId($object_type, 'crud-datatable');
        $container_name = BaseInflector::camelize($pjax_container);

        if (is_array($value))
            $value = FHtml::encode($value);

        $result = '';
        $rand = $object_type; // rand(0, 100000);

        if ($canEdit == FHtml::EDIT_TYPE_INPUT) {
            if (in_array($editor, [FHtml::EDITOR_SELECT]) || (!empty($field) && FHtml::isInArray($field, FHtml::getFIELDS_GROUP(), $object_type)) || $editor == FHtml::SHOW_LABEL || key_exists($object_type . '.' . $field, FHtml::LOOKUP)) {
                $data = FHtml::getComboArray('', $object_type, $field, true, 'id', 'name');

                $result = Html::dropDownList(
                    "$object_type-$field",
                    $value,
                    $data,
                    ['class' => 'form-control editable-' . $field, 'object_type' => $object_type, 'model_id' => $id, 'model_field' => $field]
                );
            } else {
                $result = Html::textarea(
                    "$object_type-$field",
                    $value,
                    ['id' => $id, 'class' => 'form-control editable-' . $field, 'object_type' => $object_type, 'model_id' => $id, 'model_field' => $field]
                );

                if (in_array($field, ['content', 'overview', 'description', 'note']))
                    $result .= FHtml::showModalButtonHtmlEditor($id, true, '');
            }

            FHtml::registerEditorJS($field, $editor, $pjax_container);
        } else {

            $field_label = FHtml::getFieldLabel($object_type, $field);

            if ((empty($value) && is_string($value)) || $content === FHtml::NULL_VALUE)
                $content = self::getInlineEditLabel($field_label);

            //            if ($showEditButton)
            //                $result .= $content . ' ' . self::showEditorButton($canEdit);
            //            else
            $result = '<span  id = "{uid}-{id}-{field}-label" title="' . $field_label . '" onMouseOver="this.style.cursor=\'pointer\'" onclick="showEditor(\'{uid}-{id}-{field}\')">{content}</span>';
            //$result .= '&nbsp;<i class="fa fa-pencil hidden-print" style="font-size:80%;color:#e0d4d4" aria-hidden="true"></i>';
            $editor = self::showEditor($value, $field, $id, $object_type, 'editable-{field}', $editor, $model);

            $result .= '<div style="display:none" class="form-input input-append {css}" id="{uid}-{id}-{field}-form">'
                . $editor
                . '';

            if ($showSaveButtons)
                $result .= '<span class="btn btn-xs btn-success glyphicon glyphicon-ok pull-left" onclick="saveEditor' . $container_name . '(\'{uid}-{id}-{field}\')"></span>';

            $result .= '<span class="btn btn-xs btn-default glyphicon glyphicon-remove pull-left" onclick="closeEditor(\'{uid}-{id}-{field}\')"></span>';
            $result .= '</div>';

            $result = str_replace('{id}', $id, $result);
            $result = str_replace('{field}', $field, $result);
            $result = str_replace('{value}', $value, $result);
            $result = str_replace('{css}', '', $result);
            $result = str_replace('{uid}', $rand, $result);
            $result = str_replace('{content}', $showEditor ? $editor : $content, $result);

            FHtml::registerEditorJS($field, FHtml::EDIT_TYPE_INLINE, $pjax_container);
        }

        return $result;
    }

    public static function showEditorButton($canEdit = true)
    {
        if ($canEdit != true)
            return '';

        $result = '<span class="hidden-print" onMouseOver="this.style.cursor=\'pointer\'" onclick="showEditor(\'{id}-{field}\')" style="font-size:8pt;color:lightgrey;margin-left:5px" class="glyphicon glyphicon-pencil"></span>';
        return $result;
    }

    //HungHX: 20160801
    public static function showModelFieldEditorInput($model, $field, $edit_type = FHtml::EDIT_TYPE_INPUT, $data_key = '', $pjax_container = '', $hide_input_when_have_data = false, $has_save_buttons = false)
    {
        return self::showModelFieldEditor($model, $field, $edit_type, $data_key, $pjax_container, $hide_input_when_have_data, $has_save_buttons);
    }

    public static function showModelFieldEditorInline($model, $field, $edit_type = FHtml::EDIT_TYPE_INLINE, $data_key = '', $pjax_container = '', $hide_input_when_have_data = true, $has_save_buttons = true)
    {
        return self::showModelFieldEditor($model, $field, $edit_type, $data_key, $pjax_container, $hide_input_when_have_data, $has_save_buttons);
    }

    public static function showModelFieldEditor($model, $field, $edit_type = '', $data_key = '', $pjax_container = '', $hide_input_when_have_data = true, $has_save_buttons = true)
    {

        $value = self::getFieldValue($model, $field);
        $id = FHtml::getFieldValue($model, ['id']);
        $object_type = FHtml::getTableName($model);
        $field_id = BaseInflector::camelize($object_type) . "[$field]";
        $data = FHtml::getComboArray($data_key);
        $result = $value;

        if (empty($edit_type))
            $edit_type = self::getFieldValue($model, 'editor', 'textarea');

        if (!empty($value) && $hide_input_when_have_data) {
            return self::showModelField($model, $field, '', self::LAYOUT_NO_LABEL, '', '', $object_type, $field, $pjax_container);
        }

        if (!$has_save_buttons) {
            FHtml::registerEditorJS($field, FHtml::EDIT_TYPE_INPUT, $pjax_container);
            $control = self::showEditor($value, $field, $id, $object_type, "editable-$field", $edit_type, $data);
        } else {
            $control = FHtml::showContentEditable($result, $value, $field, $id, $object_type, 'textarea', true, true, true, '', $model, $pjax_container);
        }

        $control = str_replace('{control_name}', $field_id, $control);
        $control = str_replace('{field}', $field, $control);
        $control = str_replace('{id}', $id, $control);
        $control = str_replace('{uid}', $id, $control);

        return $control;
    }

    public static function showEditor($value, $field = '', $id = '', $object_type = '', $css = 'editable-{field}', $edit_type = '', $data = null)
    {
        if (is_object($value)) {
            return FHtml::showErrorMessage("Value of field $field is object. Can not edit inline.");
        }

        $t = 'model_id = "' . $id . '" ';
        $t .= 'model_field = "' . $field . '" ';
        $t .= 'object_type = "' . $object_type . '" ';

        $field_id = empty($id) ? str_replace('_', '', $object_type) . "-$field" : '{uid}-{id}-{field}';

        $key = "";

        if (FHtml::isInArray($field, FHtml::getFIELDS_GROUP(), $object_type) || $edit_type == FHtml::EDITOR_SELECT || $edit_type == FHtml::SHOW_LABEL) {
            $edit_type = FHtml::EDITOR_SELECT;
            $data = FHtml::getComboArray($key, $object_type, $field);
        }

        if (!isset($value))
            $value = FHtml::getRequestParam($field);

        if (FHtml::isInArray($field, FHtml::FIELDS_PRICE)) {
            $edit_type = FHtml::EDITOR_NUMERIC;
        } else if (FHtml::isInArray($field, FHtml::FIELDS_DATE)) {
            $edit_type = FHtml::EDITOR_DATE;
        } else if (empty($edit_type)) {
            $edit_type = 'textarea';
        }

        if (is_array($value))
            $value = implode(',', $value);

        $field_label = FHtml::getFieldLabel($object_type, $field);

        if ((!empty($data) && is_array($data))) {
            $result = Html::dropDownList(
                '{control_name}',
                $value,
                $data,
                ['class' => 'form-control {css} ' . $css, 'value' => $value, 'object_type' => $object_type, 'model_id' => $id, 'model_field' => $field, 'id' => $field_id, 'placeholder' => $field_label]
            );
        } else if ($edit_type == FHtml::EDITOR_NUMERIC || $edit_type == FHtml::SHOW_NUMBER || $edit_type == 'numeric') {
            $result = Html::textInput(
                '{control_name}',
                $value,
                ['class' => 'form-control {css} ' . $css, 'value' => $value, 'type' => 'number', 'object_type' => $object_type, 'model_id' => $id, 'model_field' => $field, 'id' => $field_id, 'placeholder' => $field_label]
            );
        } else if ($edit_type == FHtml::EDITOR_DATE || $edit_type == FHtml::SHOW_DATE) {
            $result = Html::textInput(
                '{control_name}',
                $value,
                ['class' => 'form-control {css} ' . $css, 'value' => $value, 'type' => 'date', 'object_type' => $object_type, 'model_id' => $id, 'model_field' => $field, 'id' => $field_id, 'placeholder' => $field_label]
            );
        } else if ($edit_type == FHtml::EDITOR_BOOLEAN || $edit_type == 'checkbox' || $edit_type == FHtml::SHOW_BOOLEAN || $edit_type == FHtml::SHOW_ACTIVE) {
            $result = self::checkbox(
                '{control_name}',
                $value,
                ['name' => '{control_name}', 'class' => 'form-control {css} ' . $css, 'value' => $value, 'object_type' => $object_type, 'model_id' => $id, 'model_field' => $field, 'id' => $field_id, 'placeholder' => $field_label]
            );
        } else if ($edit_type == FHtml::EDITOR_FILE ||  $edit_type == 'file' || FHtml::isInArray($field, array_merge(FHtml::FIELDS_IMAGES, FHtml::FIELDS_FILES))) {

            $result = Html::textInput(
                '{control_name}',
                $value,
                ['type' => 'file', 'class' => 'form-control {css} ' . $css, 'value' => $value, 'rows' => 2, 'object_type' => $object_type, 'model_id' => $id, 'model_field' => $field, 'id' => $field_id, 'placeholder' => $field_label]
            );
        } else {
            $result = Html::textarea(
                '{control_name}',
                $value,
                ['class' => 'form-control {css} ' . $css, 'value' => $value, 'rows' => 2, 'object_type' => $object_type, 'model_id' => $id, 'model_field' => $field, 'id' => $field_id, 'placeholder' => $field_label]
            );

            //$result .=  FHtml::showModalButton('html', FHtml::strReplace(FHtml::createUrl('site/editor', ['id' => "$field_id"]), ['%7B' => '{', '%7D' => '}']), $role = 'modal-remote', $css = 'btn btn-xs btn-default');
            $result .= FHtml::showModalButtonHtmlEditor($field_id, false, '');
        }
        $result = str_replace('{attr}', $t, $result);
        return "<div style='dislplay:block'>$result</div>";
    }

    public static function checkbox($name, $value = 0, $option = [])
    {
        $content = Html::checkbox($name, $value, $option);
        $result = "<div class='switch_box-wrapper'><div class='switch_box'>$content</div></div>";
        return $result;
    }

    public static function showModelPreview($model, $nameField = FHtml::FIELDS_NAME, $descriptionField = FHtml::FIELDS_OVERVIEW, $imageField = ['image', 'avatar', 'banner', 'thumbnail'], $statusField = FHtml::FIELDS_STATUS, $layout = self::LAYOUT_TABLE, $css = 'text-default small', $table = '', $column = '', $dbType = '', $type = '')
    {
        if ($model->isNewRecord)
            return '';

        $canEdit = false;
        $image = '';
        foreach ($imageField as $fieldImg) {
            $img = FHtml::getFieldValue($model, $fieldImg);

            if (!empty($img)) {
                $image .= FHtml::showImageWithDownload($img, FHtml::getImageFolder($model), '100%', 0, '', $fieldImg, true);
                break;
            }
        }
        $result = '';

        $result .= !empty($nameField) ? '<h3 style="margin-bottom:10px;">' . FHtml::showModelField($model, $nameField, '', self::LAYOUT_NO_LABEL, $css, '', '', '', '', $canEdit) . '</h3>' : '';
        if (!empty($image))
            $result .= '<div style="margin-bottom:10px;">' . $image . '</div>';

        if (!empty($descriptionField))
            $result = $result . '<div style="margin-bottom:10px; color: darkgrey">' . FHtml::showModelField($model, $descriptionField, '', self::LAYOUT_NO_LABEL, $css, '', '', '', '', $canEdit) . '</div>';

        if (!empty($statusField))
            $result = $result . FHtml::showModelField($model, $statusField, '', $layout, $css, '', '', '', '', $canEdit);
        return $result;
    }

    public static function showModelPreviewTop($model, $nameField = FHtml::FIELDS_NAME, $descriptionField = FHtml::FIELDS_OVERVIEW, $imageField = FHtml::FIELDS_IMAGES, $statusField = FHtml::FIELDS_STATUS, $layout = self::LAYOUT_TABLE, $css = 'text-default small', $table = '', $column = '', $dbType = '', $type = '')
    {
        $canEdit = FHtml::isInRole($model, FHtml::ACTION_EDIT);
        $image = '';
        $name = '';
        $description = '';
        $status = '';
        foreach ($imageField as $fieldImg) {
            $img = FHtml::getFieldValue($model, $fieldImg);
            if (!empty($img))
                $image .= FHtml::showImage($img, FHtml::getImageFolder($model), '100%', 0, '', $fieldImg, false);
        }
        if (!empty($image))
            $image = '<div class=\'col-xs-2\'><div style="margin-bottom:10px;">' . $image . '</div></div>';
        $name = !empty($nameField) ? '<div style="margin-bottom:10px; font-size:150%">' . FHtml::showModelField($model, $nameField, '', self::LAYOUT_NO_LABEL, $css) . '</div>' : '';
        if (!empty($descriptionField))
            $description = '<div style="margin-bottom:10px; color: darkgrey">' . FHtml::showModelField($model, $descriptionField, '', self::LAYOUT_NO_LABEL, $css) . '</div>';
        if (!empty($statusField))
            $status = FHtml::showModelField($model, $statusField, '', $layout, $css);

        $result = "<div class='row'>$image<div class='col-xs-7'>$name $description</div> <div class='col-xs-3'>$status</div></div>";
        return $result;
    }

    public static function showModelHistory($model, $fields = FHtml::FIELDS_HISTORY, $layout = self::LAYOUT_ONELINE, $css = '')
    {
        $result = FHtml::showModelField($model, $fields, '', $layout, $css);
        return $result;
    }

    public static function showModelFieldValue($model, $field, $showType = '', $dbType = '', $inlineEdit = null, $pjax_container = '', $edit_type = '', $field_separator = '&nbsp;&nbsp;')
    {
        if ($showType == 'readonly') {
            $inlineEdit = false;
            $showType = '';
        }

        $result = '';
        if (is_string($field)) {
            $search_custom = true;
            $fieldArray = [$field];
        } else if (is_array($field)) {
            $fieldArray = $field;
            $search_custom = false;
        } else
            return '';

        $table = FHtml::getTableName($model);

        if (empty($pjax_container))
            $pjax_container = self::getPjaxContainerId($table, 'crud-datatable');

        foreach ($fieldArray as $field) {
            if (!$search_custom && !self::field_exists($model, $field) && !method_exists($model, 'get' . ucfirst($field)))
                continue;

            if (empty($showType)) {
                $showType = self::getShowType($model, $field);
            }

            $value = FHtml::getFieldValue($model, $field);

            $result .= self::showFieldValue($value, $model->getPrimaryKeyValue(), $showType, $table, $field, $dbType, $inlineEdit, $pjax_container, $edit_type);
            if (count($fieldArray) > 1)
                $result .= $field_separator;
        }
        return trim($result);
    }

    //Datetime
    public static function showObjectPreview($model, $id_field = 'id', $object_type_field = '', $layout = '', $layout_fields = [], $inlineEdit = false, $pjax_container = '')
    {
        if (is_bool($id_field)) {
            $inlineEdit = $id_field;
            $layout_fields = $object_type_field;
        } else if (is_array($id_field)) {
            $layout_fields = $id_field;
            $inlineEdit = $object_type_field;
        } else if (!empty($object_type_field)) {

            $object_id = FHtml::getFieldValue($model, $id_field);
            $object_type = FHtml::getFieldValue($model, $object_type_field);
            $model = FHtml::getModel($object_type, '', $object_id);
        }

        if (empty($pjax_container))
            $pjax_container = self::getPjaxContainerId(FHtml::getTableName($model), 'crud-datatable');

        if (empty($layout_fields))
            $layout_fields = ['image' => ['image', 'thumbnail', 'avatar'], 'name' => ['name', 'title', 'username'], 'description' => ['description', 'overview'], 'status' => ['category_id', 'type', 'status', 'is_active']];

        //        $result = "<div class='row' style='padding-top:15px; padding-bottom:15px;border-bottom: 1px lightgray dashed'><div class='col-md-2 col-xs-3'>" . FHtml::showImage($model) . "</div><div class='col-md-9 col-xs-9'>" . FHtml::showModel($model, $layout_fields, [], FHtml::LAYOUT_TABLE, '', '') . "</div></div></div>";
        //        return $result;

        $arr = [];
        $layout1 = '<br/>';
        foreach ($layout_fields as $key => $key_fields) {
            if (is_numeric($key) && is_string($key_fields)) {
                $key = $key_fields;
            }
            $value = '';
            if (FHtml::isInArray($key, FHtml::FIELDS_IMAGES))
                $value = FHtml::showImage(FHtml::getFieldValue($model, $key_fields), FHtml::getImageFolder($model), '100%', '');
            else
                $value = FHtml::showModelFieldValue($model, $key_fields, '', '', $inlineEdit, $pjax_container);

            if (!in_array($key, ['image', 'name', 'description']))
                $layout1 .= "{{$key}}";
            $arr = array_merge($arr, ['{' . $key . '}' => $value]);
        }

        if (empty($layout)) {
            $layout = "<div class='row'><div class='col-md-2 col-xs-3'>{image}</div><div class='col-md-10 col-xs-9' style='padding-left:25px'><b style='font-size:120%'>{name}</b> <br/><div>{description}</div> $layout1 </div></div>";
        } else if (strpos($layout, ':') !== false) {
            $arr = explode(':', $layout);
            $idx1 = $arr[0];
            $idx2 = $arr[1];
            $layout = "<div class='row'><div class='col-md-$idx1 col-xs-$idx1'>{image}</div><div class='col-md-$idx2 col-xs-$idx2'><b style='font-size:120%'>{name}</b> <br/><div>{description}</div>  <br/> $layout1 </div></div>";
        }
        $result = self::strReplace($layout, $arr);

        return $result;
    }

    public static function showModelList($model, $field, $showType = '', $layout = self::LAYOUT_TABLE, $css = 'text-default small', $table = '', $column = '', $dbType = '', $type = '')
    {
        $result = '';
        $arr = [];

        if (is_array($field)) {
            $arr = $field;
        } else
            $arr[] = $field;

        if (is_array($field) && count($field) > 1 && $layout == self::LAYOUT_TABLE)
            $result .= '<table class="table table-bordered">';

        if (is_array($model))
            $modelList = $model;
        else
            $modelList[] = $model;

        foreach ($modelList as $model) {
            foreach ($arr as $field1) {
                if (isset($model[$field1]))
                    $result .= self::showField($model->attributeLabels()[$field1], $model[$field1], $showType, $layout, $css, $table, $column, $dbType, $model->id);
            }
        }

        if (is_array($field) && count($field) > 1 && $layout == self::LAYOUT_TABLE)
            $result .= '</table>';

        return $result;
    }

    //HungHX: 20160801

    public static function showModels($model, $field, $showType = '', $layout = self::LAYOUT_TABLE, $css = 'text-default small', $table = '', $column = '', $dbType = '', $type = '')
    {
        $result = '';
        $arr = [];

        if (is_array($field)) {
            $fields = $field;
        } else
            $fields[] = $field;

        if (is_array($field) && count($field) > 1 && $layout == self::LAYOUT_TABLE)
            $result .= '<table class="table table-bordered" style="width:100%">';

        if (is_array($model))
            $modelList = $model;
        else
            $modelList[] = $model;

        if (empty($modelList))
            return '';

        $result .= '<tr>';
        foreach ($fields as $field1) {
            $result .= '<td>' . $modelList[0]->attributeLabels()[$field1] . '</td>';
        }
        $result .= '</tr>';

        foreach ($modelList as $model) {
            $result .= '<tr>';
            foreach ($fields as $field1) {
                //$result .= '<td>'. self::showField($model->attributeLabels()[$field1], $model[$field1], $showType, FHtml::LAYOUT_NO_LABEL, $css, $table, $column, $dbType, $model->id) . '</td>';
                $result .= '<td>' . FHtml::getFieldValue($model, $field1) . '</td>';
            }
            $result .= '</tr>';
        }

        if (is_array($field) && count($field) > 1 && $layout == self::LAYOUT_TABLE)
            $result .= '</table>';

        return $result;
    }

    public static function showArray($model, $showType = '', $layout = self::LAYOUT_TABLE, $css = 'text-default small', $table = '', $column = '', $dbType = '', $type = '')
    {
        $result = '';
        $arr = [];

        if ($layout == self::LAYOUT_TABLE)
            $result .= '<table class="table table-bordered">';

        if (is_array($model))
            $modelList = $model;
        else
            $modelList[] = $model;

        foreach ($modelList as $field1 => $value1) {
            if (!is_string($value1) && !is_numeric($value1))
                $value1 = '<div class="co-md-12" style="style="white-space: break-word; !important; word-wrap:break-word;"">'
                    . json_encode($value1) . '</div>';
            $result .= self::showField($field1, $value1, $showType, $layout, $css, $table, $column, $dbType, $type);
        }

        if ($layout == self::LAYOUT_TABLE)
            $result .= '</table>';

        return $result;
    }

    public static function showItemsArray($models, $itemTemplate = '{name}', $fields = ['name'], $separator = '; ')
    {
        $items = self::getItemsArray($models, $itemTemplate, $fields);
        return implode($separator, $items);
    }

    public static function getItemsArray($models, $itemTemplate = '{name}', $fields = ['name'])
    {
        $arr = [];
        if (isset($models) & is_array($models)) {
            foreach ($models as $model) {
                $item = $itemTemplate;
                foreach ($fields as $field) {
                    $item = str_replace('{' . $field . '}', FHtml::getFieldValue($model, [$field], ''), $item);
                }
                $arr[] = $item;
            }
        }

        return $arr;
    }

    public static function showAttribute($label, $value, $showType = '', $css = 'text-default small', $table = '', $column = '', $dbType = '', $type = '')
    {
        return self::showField($label, $value, $showType, $css, $table, $column, $dbType);
    }

    public static function getLabel($key, $table, $column, $value)
    {
        $text = '';
        if (!isset($value) || strlen($value) == 0)
            $text = self::NULL_VALUE;
        else {
            $model = self::getModel($table);
            $arr = isset($model) && method_exists($model, 'getLookupArray') ? $model::getLookupArray($column = '') : [];
            if (!empty($arr) && isset($arr[$value]))
                return $text =  FHtml::getFieldLabel($model, $arr[$value]);;

            if ($column == 'category_id') {
                $metaItem = models\ObjectCategory::findOne(['object_type' => $table, 'id' => $value]);
                if (isset($metaItem)) {
                    $text = FHtml::getFieldLabel($model, $metaItem->name);
                } else {
                    $text = FHtml::t('common', $value);
                }
            } else {
                if (FHtml::isTableExisted('object_setting')) {
                    $metaItem = ObjectSetting::findOne(['object_type' => $table, 'meta_key' => $column, 'key' => $value]);
                    if (isset($metaItem)) {
                        $text = FHtml::getFieldLabel($model, $metaItem->value);
                    } else {
                        $text = FHtml::t('common', $value);
                    }
                }
            }
        }

        return $text;
    }

    public static function getComboArrayFilter($key, $table, $column, $isCache = true, $id_field = 'id', $name_field = 'name', $hasNull = true, $search_params = [], $limit = 0)
    {
        $data = self::getComboArray($key, $table, $column, $isCache, $id_field, $name_field, $hasNull, $search_params, $limit);
        //var_dump($data);die;
        $result = [];
        foreach ($data as $id => $value)
            $result = ArrayHelper::merge($result, [',' . $id . ',' => $value]);
        return $result;
    }

    public static function getComboArrayNoNull($key, $table, $column, $isCache = true, $id_field = 'id', $name_field = 'name')
    {
        return ArrayHelper::merge(['' => FHtml::getNullValueText()], self::getComboArray($key, $table, $column, $isCache, $id_field, $name_field));
    }

    public static function buildSerialGridColumn()
    {
        return [
            'class' => 'kartik\grid\SerialColumn',
            'width' => '30px',
        ];
    }

    public static function buildLookupGridColumn($moduleName, $field)
    {
        return [ //name: user_id, dbType: int(11), phpType: integer, size: 11, allowNull:
            'class' => FHtml::getColumnClass($moduleName, $field, ''),
            'visible' => FHtml::isVisibleInGrid($moduleName, $field, ''),
            'format' => 'raw',
            'attribute' => 'user_id',
            'value' => function ($model) {
                return FHtml::showContent($model->user_id, FHtml::SHOW_LABEL, 'truck_driver', 'user_id', 'int(11)', 'truck-driver');
            },
            'hAlign' => 'left',
            'vAlign' => 'middle',
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => ''],
            'filter' => FHtml::getComboArray('@user', 'truck_driver', $field, true, 'id', 'name'),
            'contentOptions' => ['class' => 'col-md-1 nowrap'],
        ];
    }

    public static function getColumnClass($module, $field, $form_type = '', $manualValue = null)
    {
        if (isset($manualValue) && is_string($manualValue))
            return $manualValue;

        if (FHtml::isEditInGrid($module, $field, $form_type))
            return self::COLUMN_EDIT;
        else {
            return self::COLUMN_VIEW;
        }
    }

    public static function showSummary($modelSearch, $params = [], $type = 'count', $table = '', $column = '', $value = '')
    {
        $sum = self::getSummary($modelSearch, $params, $type, $table, $column);
        $color = self::getColor('', $table, $column, $value);
        $colort = self::showBadge($sum, $color);
        return $colort;
    }

    public static function getSummary($modelSearch, $params = [], $type = 'count', $table = '', $column = '')
    {
        $modelProvider = $modelSearch->search($params);
        if ($type == 'count')
            return $modelProvider->query->count();
        else if ($type == 'sum')
            return $modelProvider->query->sum();
        else
            return $modelProvider->query->count();
    }

    public static function getColor($key, $table, $column, $value)
    {
        if (FHtml::isDBSettingsEnabled()) {
            $metaItem = ObjectSetting::findOne(['object_type' => $table, 'meta_key' => $column, 'key' => $value]);
            if (isset($metaItem)) {
                return $metaItem->color;
            }
        }
        return self::getColorFromArray($key, $table, $column, $value);
    }

    public static function showBadge($value, $color = 'default', $cssClass = 'pull-right')
    {
        return '<span class="label label-sm label-' . $color . ' ' . $cssClass . '">' . $value . '</span>';
    }

    public static function displayPercentage($value, $defaultValue = '')
    {
        return self::showPercentage($value, $defaultValue);
    }

    //2017/3/21: Add counter
    public static function showPercentage($value, $defaultValue = '')
    {
        if (isset($value)) {
            return $value . '%';
        } else
            return $defaultValue;
    }


    //2017/3/21: add counter


    public static function getAttribute($model, $field)
    {
        return self::getFieldValue($model, $field);
    }

    public static function buildGridFiltersVertical($table, $field = ['category_id', 'type', 'status', 'is_active', 'is_top', 'is_hot', 'lang'], $seperator = '', $css = 'tabs tab-normal')
    {
        $seperator = false;
        return self::buildGridFilters($table, $field, $seperator, $css);
    }

    public static function buildGridFilters($table, $field = ['category_id', 'type', 'status', 'is_active', 'is_top', 'is_hot', 'lang'], $seperator = '', $css = 'tabs tab-normal')
    {
        $result = '';
        $model = FHtml::createModel($table);
        if (is_string($model))
            return $model;

        if (!isset($model))
            return '';

        if (is_string($field))
            $field = explode(',', $field);

        if ($seperator === 'tab' || $seperator === 'horizontal' || $seperator === true) {
            $seperator = '';
            $css = 'tabs tab-normal';
        } else if ($seperator === 'list' || $seperator === 'vertical' || $seperator === false) {
            $seperator = '<br/>';
            $css = 'tab-normal';
        }

        if (is_array($field)) {
            foreach ($field as $fieldid => $fielditem) {
                if (is_numeric($fieldid) && is_string($fielditem)) {
                    if (self::field_exists($model, $fielditem))
                        $result = $result . self::buildTabs("$table.$fielditem", $css, is_string($fieldid) ? $fieldid : $fielditem) . $seperator;
                    else if (strpos($fielditem, '.') !== false || strpos($fielditem, '@') !== false) {
                        $arr = FHtml::getArray($fielditem);
                        $result = $result . self::buildTabs($arr, $css, str_replace('.', '_', is_string($fieldid) ? $fieldid : $fielditem)) . $seperator;
                    } else if (!in_array($fielditem, FHtml::FIELDS_STATUS)) {
                        $result = $result . self::buildTabs($table . '.' . $fielditem, $css, is_string($fieldid) ? $fieldid : $fielditem) . $seperator;
                    } else {
                        $result = $result . self::buildTabs("$table.$$fielditem", $css, is_string($fieldid) ? $fieldid : $fielditem) . $seperator;
                    }
                } else if (is_numeric($fieldid) && is_array($fielditem)) {
                    $arr = $fielditem;
                    foreach ($arr as $fielditem => $fielditemValue) {
                        $result = $result . self::buildTabs($fielditemValue, $css, $fielditem) . $seperator;
                    }
                } else if (is_string($fieldid) && is_array($fielditem)) {
                    $fielditemValue = $fielditem;
                    $fielditem = $fieldid;
                    $result = $result . self::buildTabs($fielditemValue, $css, $fielditem) . $seperator;
                } else if (is_string($fieldid)) {
                    $fielditemValue = $fielditem;
                    $fielditem = $fieldid;
                    if (in_array($fielditemValue, ['daterange', 'date_range', 'DateRangePicker', FDateRangePicker::className()])) {
                        $result = $result . "<div class='col-md-3'>" . \common\widgets\FDateRangePicker::widget([
                            'border' => false, 'name' => $fieldid,
                            'value' => FHtml::getRequestParam($fieldid), 'options' => ['class' => 'auto-refresh-content-textbox']
                        ]) . "</div>" . $seperator;
                    } else if (strpos($fielditemValue, ' ') !== false || strpos($fielditemValue, '<') !== false) {
                        $result = $result . $fielditemValue . $seperator;
                    } else {
                        $fielditemValue = FHtml::getComboArray($fielditemValue);
                        $result = $result . self::buildTabs($fielditemValue, $css, $fielditem) . $seperator;
                    }
                } else {
                }
            }
        }

        return $result;
    }

    public static function buildArrayDisplay($key, $css = '', $field = 'id', $url = '', $value = '', $table = '', $column = '', $isCache = true, $showCounter = false, $pull_right = false, $is_combo = null, $pjax_container = '')
    {
        if ($key == 'object_type') {
            $key = FHtml::settingApplicationModules();
        }

        $field_url = '';
        if (is_array($field)) {
            foreach ($field as $field => $field_url) {
                break;
            }
        } else {
            $field_url = $field;
        }

        if (empty($value))
            $value = FHtml::getRequestParam($field_url);

        $items = [];

        if (is_array($key)) {
            if (isset($key[0]) && is_array($key[0])) {
                $items = $key;
            } else {
                $items = [];
                $is_index = ArrayHelper::isIndexed($key, true);
                foreach ($key as $id => $name) {
                    if ($is_index)
                        $id = $name;

                    if ($name == 'All' || $name == FHtml::NULL_VALUE)
                        $id = '';

                    if (empty($name))
                        $name = $id;

                    $items[] = ['id' => $id, 'name' => $name];
                }
            }
        } else {
            if (strpos($key, '.') !== false) {
                $arr = explode('.', $key);
                $table = isset($arr[0]) ? $arr[0] : $table;
                $column = isset($arr[1]) ? $arr[1] : $field;
            } else if (!empty($field)) {
                $table = $key;
                $key = "$key.$field";
                $column = $field;
            } else {
                $table = $key;
                $column = '';
            }

            if (strpos($key, '.') == false && !FHtml::field_exists($table, $column))
                return ['items' => '', 'combo' => '', 'tabs' => '', 'content' => ''];

            if (StringHelper::startsWith($field, 'is_')) {
                if (FHtml::field_exists($table, $column))
                    $items[] = ['id' => 1, 'name' => FHtml::getFieldLabel($table, $field)];
            } else {
                $data = FHtml::getComboArray($key, $table, $column, $isCache);
                if (!ArrayHelper::isIndexed($data, true)) {
                    $items = [];
                    foreach ($data as $key1 => $value1) {
                        if (is_array($value1)) {
                            $key1 = FHtml::getFieldValue($value1, ['id', 'key']);
                            $value1 = FHtml::getFieldValue($value1, ['name', 'value', 'title']);
                        }
                        $items[] = ['id' => $key1, 'name' => $value1];
                    }
                } else {
                    $items = $data;
                }
            }
        }

        $str = '';

        $combo = '';

        if (!empty($items)) {
            if (!isset($is_combo))
                $is_combo = count($items) > 3;

            $columnText =  '- ' . mb_strtoupper(FHtml::getFieldLabel($table, $column)) . ' -';

            $is_tab = (StringHelper::startsWith($css, 'tabs') || $css == 'nav-tabs') ? true : false;
            $div_css = $is_tab ? 'pull-left' : '';
            $ul_css = $is_tab ? ('pull-left nav nav-tabs ' . $css) : 'nav';
            $label = $is_tab ?  '' : ('<div style="font-weight:bold"><small style="color:grey">' . $columnText . '</small></div>');
            $style = $is_tab ? ($pull_right ? '' : 'padding-right:15px; margin-right:15px') : '';
            $value = empty($value) ? FHtml::getRequestParam($field_url) : $value;
            $li_css = !empty($value) ? 'font-weight:bold;border-bottom:2px solid #2bb8c4;' : 'color:lightgrey;';
            $str = "<div class='$div_css' style='$style'>$label<ul class='$ul_css' style='margin-bottom:0px !important'>";
            $combo = "<div class='$div_css' style='$style'>$label <ul class='nav-tabs' style='border:none'><li class='$ul_css' style='$li_css padding-left:15px;margin-left:-50px; padding-bottom:5px; margin-bottom:0px !important'> <select id='$table-$field_url-select' class='form-control no-bordered auto-refresh-content' style='border:none !important; width:100%; margin-bottom:0px !important'>";
            $combo .= "<option style='' value='' selected disabled>" .   $columnText . "</option><option field='" . $field_url . "' value=''>" . strtoupper(FHtml::getNullValueText(true)) . "</option>";

            $url = empty($url) ? '{module}/{controller}/index' : $url;
            $field = empty($field) && is_string($key) ? $key : $field;

            foreach ($items as $id => $item) {
                if (is_array($item['name'])) {
                    FHtml::addError("Error at function buildArrayDisplay \$items['name'] can not be array: ");
                    FHtml::var_dump($items);
                    return '';
                }

                if (empty($item['id']))
                    continue;

                if (empty($item['name']) || ($is_combo && StringHelper::startsWith($item['name'], '<')))
                    $item['name'] = $item['id'];

                $table = empty($table) ? $key : $table;

                if ($is_tab) {
                    if ($value == $item['id']) {

                        $li_css = 'tab-active active bold';
                        $tab_style = '';
                        $url1 = FHtml::createUrl($url, FHtml::mergeRequestParams(FHtml::RequestParams($field_url)));
                    } else {
                        $li_css = 'tab-normal';
                        $tab_style = '';
                        $url1 = FHtml::createUrl($url, FHtml::mergeRequestParams(FHtml::RequestParams(), [$field_url => $item['id']]));
                    }
                } else {

                    if ($value == $item['id']) {
                        $li_css = StringHelper::startsWith($css, 'tabs') ? 'nav-active active bold' : 'nav-active active bold';
                        $tab_style = '';
                        $url1 = FHtml::createUrl($url, FHtml::mergeRequestParams(FHtml::RequestParams($field_url)));
                    } else {
                        $li_css = 'nav-normal';
                        $tab_style = '';
                        $url1 = FHtml::createUrl($url, FHtml::mergeRequestParams(FHtml::RequestParams(), [$field_url => $item['id']]));
                    }
                }

                $pjax = in_array($field_url, ['edit_type1']) ? "data-pjax='0'" : '';

                if (!$is_tab) {
                    $li_css .= '';
                    $tab_style .= " ";
                } else {
                    $tab_style = "";
                }

                $str .= '<li class="' . $li_css . '" style= "text-transform: uppercase; ' . $tab_style . ' "><a class="auto-refresh-content-span" ' . $pjax . '" url="' . $url1 . '" field="' . $field_url . '" value="' . $item['id'] . '" title="' . $columnText . '" url="' . $url1 . '">' . FHtml::showLabelWithCounter($item['id'], $table, $field, $item['name'], $showCounter) . '</a></li>';
                $combo .= '<option class="' . $li_css . '" url="' . $url1 . '" field="' . $field_url . '" value="' . $item['id'] . '"' . ($item['id'] == $value ? ' selected' : '') . '><a title="' . $columnText . '" href="' . $url1 . '">' . FHtml::showLabelWithCounter($item['id'], $table, $field, mb_strtoupper($item['name']), $showCounter) . '</a></option>';
            }

            $str .= '</ul></div>';
            $combo .= '</select></li></ul></div>';
        }

        if ($is_combo)
            $content = $combo;
        else
            $content = $str;

        if (!empty($pjax_container)) {
        }

        return ['items' => $items, 'combo' => $combo, 'tabs' => $str, 'content' => $content];
    }

    public static function buildTabs($key, $css = '', $field = 'id', $url = '', $value = '', $table = '', $column = '', $isCache = true, $showCounter = false, $pull_right = false, $is_combo = null)
    {
        $arr = self::buildArrayDisplay($key, $css, $field, $url, $value, $table, $column, $isCache, $showCounter, $pull_right, $is_combo);
        if (key_exists('content', $arr))
            return $arr['content'];
        return '';
    }

    public static function showCombo($key, $field = 'id', $url = '', $value = '', $table = '', $column = '', $isCache = true, $showCounter = false)
    {
        $arr = self::buildArrayDisplay($key, '', $field, $url, $value, $table, $column, $isCache, $showCounter);
        if (key_exists('combo', $arr))
            return $arr['combo'];
        return '';
    }

    public static function showCategoryList($key, $field = 'id', $url = null, $value = null)
    {
        if (is_string($key))
            $items = FHtml::getComboArray($key);
        else if (is_array($key))
            $items = $key;
        else
            return '';
        return \common\widgets\fcategory\FCategorySimple::widget(['items' => $items, 'field' => $field, 'link_url' => $url, 'active_id' => $value]);
    }

    public static function showTabs($key, $field = 'id', $url = '', $value = '', $table = '', $column = '', $isCache = true, $showCounter = false)
    {
        $arr = self::buildArrayDisplay($key, '', $field, $url, $value, $table, $column, $isCache, $showCounter);
        if (key_exists('tabs', $arr))
            return $arr['tabs'];
        return '';
    }

    public static function showTabsList($key, $field = 'id', $url = '', $value = '', $table = '', $column = '', $isCache = true, $showCounter = false)
    {
        $arr = self::buildArrayDisplay($key, 'nav', $field, $url, $value, $table, $column, $isCache, $showCounter);
        if (key_exists('tabs', $arr))
            return $arr['tabs'];
        return '';
    }

    public static function showLabelWithCounter($key, $model, $field, $name, $showCounter = false)
    {
        $count = '';
        if ($showCounter == true) {
            $table = $model;
            $count = FModel::countModels($table, [$field => $key]); //2017/3/21: get Counter
            if ($count != 0)
                $count = '&nbsp;<span style="">' . self::showBadge($count, FHtml::getColor('', $table, $field, $key)) . '</span>';
            else $count = '';
        }

        return $name . $count;
    }

    public static function buildGridFiltersTab($table, $field = ['category_id', 'type', 'status', 'is_active', 'is_top', 'is_hot', 'lang'], $seperator = '', $css = 'tabs tab-normal')
    {
        $seperator = true;
        return self::buildGridFilters($table, $field, $seperator, $css);
    }

    public static function buildAdminToolbar($table, $field = ['category_id', 'type', 'status', 'is_active', 'is_top', 'is_hot', 'lang'], $views = [], $css = 'hidden-print', $title = '')
    {
        $title = strlen($title) != 0 ? $title : FHtml::t($table, BaseInflector::camel2words($table));
        return "<div class='row'><div class='col-md-12 caption-title font-blue-madison bold uppercase'><h3 style='margin-top:-5px'>$title</h3></div></div>";
    }

    public static function buildGridToolbar($table, $field = ['category_id', 'type', 'status', 'is_active', 'is_top', 'is_hot', 'lang'], $views = [], $edit_types = true, $items_count = 1, $css = 'hidden-print')
    {
        $result = '';
        $excluded_fields = array_merge(['lang'], FConfig::setting('admin_grid_excluded_filters', ['is_active', 'is_top']));

        $field = FContent::arrayRemove($field, $excluded_fields);

        if (FHtml::currentDevice()->isMobile()) {
            $field1 = self::buildGridFilters($table, ['category_id', 'status']);
            $result = "<div class='col-md-12 $css hidden-print'><div class='col-md-12 col-xs-12'>$field1</div></div>";
        } else {
            $field1 = self::buildGridFilters($table, $field);
            if (!empty($views) && ($items_count > 0))
                $views1 = self::buildViewOptions($views);
            else
                $views1 = '';

            if (is_bool($edit_types) && $edit_types) {
                $edit_types = ($items_count > 0 && !empty($views)) ? ['input' => '<span class="glyphicon glyphicon-edit"></span>'] : [];
            }

            if (!empty($edit_types)) {
                $edit_type = self::buildTabsSmall($edit_types, 'tabs nav', 'edit_type');
                $views1 .= $edit_type;
            }

            //$field1 .="<div class='col-md-2'>" . \common\widgets\FDateRangePicker::widget(['name' => 'date_range']) . "</div>";
            $result = "<div class='col-md-12 $css no-padding admin-toolbar hidden-print'>$field1<div class='pull-right text-right no-padding'>$views1</div></div>";
        }
        return "<div class=''>$result</div>";
    }

    public static function buildViewOptions($views = [], $right_position = true)
    {
        $key = [
            FListView::VIEW_GRID_SMALL => '<span class="glyphicon glyphicon-th"></span>',
            FListView::VIEW_GRID_BIG => '<span class="glyphicon glyphicon-th-large"></span>',
            FListView::VIEW_LIST => '<span class="glyphicon glyphicon-list"></span>',
            FListView::VIEW_IMAGE => '<span class="glyphicon glyphicon-picture"></span>',
            //FListView::VIEW_PRINT => '<span class="glyphicon glyphicon-print"></span>',
        ];

        if (is_bool($views)) {
            $right_position = $views;
            $views = ['list', 'image'];
        }

        $items = [];
        foreach ($views as $key1 => $value) {
            if (is_string($value) && is_numeric($key1))
                $items = array_merge($items, [$value => key_exists($value, $key) ? $key[$value] : FHtml::t('common', $value)]);
            else if (is_string($value) && is_string($key1))
                $items = array_merge($items, [$key1 => $value]);
        }

        $result = self::buildTabsSmall($items, 'tabs nav', 'view');
        if ($right_position)
            $result = '<div class="pull-right">' . $result . '</div>';
        return $result;
    }

    public static function buildTabsSmall($key, $css = 'tabs nav-small', $field = 'id', $url = '', $value = '', $table = '', $column = '', $isCache = true, $showCounter = false, $pull_right = true)
    {
        return self::buildTabs($key, $css, $field, $url, $value, $table, $column, $isCache, $showCounter, $pull_right, false);
    }

    public static function renderViewWidget($view, $params = [])
    {
        return self::render($view, '', $params, FHtml::currentView(), true, true);
    }

    public static function renderFile($view, $params = [], $context = null, $displayError = true, $is_widget = false)
    {
        return static::renderView($view, $params, $context, $displayError, $is_widget);
    }

    public static function render($view, $viewType = '', $params = [], $context = null, $displayError = true, $is_widget = false)
    {
        if (!isset($context))
            $context = FHtml::currentControllerObject();

        if (empty($view))
            return '';

        if (is_array($viewType)) {
            $is_widget = false;
            $displayError = true;
            $context = $params;
            $params = $viewType;
            $viewType = '';
        }

        $arr = self::getViews($view, $is_widget);


        if (is_string($is_widget))
            $is_widget = false;

        //try to look into array of Views
        if (is_array($arr)) {
            foreach ($arr as $view1) {
                $result = self::renderView($view1, $params, $context, false, $is_widget);

                if ($result !== false)
                    return $result;
            }
        }

        if (is_array($view))
            $view = $view[0];

        if (!empty($viewType)) {
            if (StringHelper::startsWith($viewType, '_')) {
                $viewType = substr($viewType, 1, strlen($viewType) - 1);
            }
            $viewFile = self::findViewFile($view . '_' . $viewType, $context);
            if (!empty($viewFile)) {
                return self::renderView($view . '_' . $viewType, $params, $context, $displayError, $is_widget);
            }
        }

        return self::renderView($view, $params, $context);
    }

    public static function renderViewLayout($view, $viewType = '', $params = [])
    {
        return self::render($view, $viewType, $params, null, true, 'layout');
    }

    /**
     * @param        $view
     * @param string $viewType
     * @param array  $params
     * @param null   $context
     * @param bool   $displayError
     * @param bool   $is_widget
     * @return bool|string
     * @throws \Exception
     * @throws \yii\base\InvalidConfigException
     */
    public static function renderPartial($view, $viewType = '', $params = [], $context = null, $displayError = true, $is_widget = false)
    {
        if (!isset($context)) {
            $context = FHtml::currentControllerObject();
        }

        if (empty($view)) {
            return '';
        }

        if (is_array($viewType) && empty($params)) {
            $params   = $viewType;
            $viewType = '';
        }

        $arr = self::getViews($view, $is_widget);

        //try to look into array of Views
        if (is_array($arr)) {
            foreach ($arr as $view1) {
                $result = self::renderView($view1, $params, $context, false, $is_widget);
                if ($result !== false) {
                    return $result;
                }
            }
            if ($displayError) {
                echo self::showErrorMessage(FHtml::t('messages', 'View not found') . implode('; ', $arr));
            }
        }

        if (empty($viewType)) {
            return self::renderView($view, $params, $context, $displayError, $is_widget);
        }

        $viewFile = self::findViewFile($view . $viewType, $context);
        if (empty($viewFile)) {
            return self::renderView($view . $viewType, $params, $context, $displayError, $is_widget);
        }

        $viewFile = Yii::getAlias($viewFile);

        $page = self::currentView();

        if ($page->theme !== null) {
            $viewFile = $page->theme->applyTo($viewFile);
        }
        if (is_file($viewFile)) {
            return self::renderView($view . $viewType, $params, $context);
        } else {
            return self::renderView($view, $params, $context);
        }
    }

    public static function getViews($view, $type = 'widget')
    {
        $application_id = FHtml::currentApplicationFolder();
        $controller = FHtml::currentController();
        $page = self::currentView();
        $zone = FHtml::currentZone();

        if ($zone == FRONTEND)
            $theme = FHtml::currentFrontendTheme();
        else
            $theme = FHtml::currentBackendTheme();

        $module = FHtml::currentModule();
        $root_folder = FHtml::getRootFolder();

        if (is_bool($type)) {
            $is_widget = $type;
            $is_layout = false;
        } else if ($type == 'widget') {
            $is_widget = true;
            $is_layout = false;
        } else if ($type == 'layout') {
            $is_widget = false;
            $is_layout = true;
        } else {
            $is_widget = true;
            $is_layout = false;
        }

        $arr1 = [];

        if (is_string($view))
            $view = [$view];

        if (is_array($view)) {
            $views = $view;
            $view = null;
            foreach ($views as $i => $view1) {
                if (!is_string($view1))
                    continue;

                if (StringHelper::endsWith($view1, '.php'))
                    $view1 = str_replace('.php', '', $view1);

                if (in_array($view1, ['main', 'header', 'head', 'foot', 'footer', 'menu', 'menu_top', 'menu_left'])) {
                    $is_layout = true;
                }

                if (strpos($view1, '/') !== false && $view1 != '/') {
                    $arr1[] = $view1;
                } else if (!empty($application_id)) {
                    if ($is_layout) {
                        if (!empty($zone)) {

                            $arr1[] = "@applications/$application_id/$zone/layouts/$view1.php";
                            if ($zone == BACKEND && !empty($theme)) {
                                $arr1[] = "@$zone/web/themes/$theme/layouts/$view1.php";
                            } else if ($zone == FRONTEND && !empty($theme)) {
                                $arr1[] = "@$zone/themes/$theme/layouts/$view1.php";
                            }
                        }
                    }
                    if (true) {

                        if ($zone == FRONTEND) {
                            if (!empty($theme) && is_dir("$root_folder/$zone/themes/$theme")) {
                                $arr1[] = "@$zone/themes/$theme/$view1.php";

                                if (!empty($controller))
                                    $arr1[] = "@$zone/themes/$theme/$controller/$view1.php";

                                if (!empty($module))
                                    $arr1[] = "@$zone/themes/$theme/modules/$module/views/$controller/$view1.php";
                            }

                            if (!empty($theme) && $theme != $application_id && is_dir("$root_folder/applications/$theme")) {
                                $arr1[] = "@applications/$theme/$zone/$view1.php";

                                if (!empty($controller))
                                    $arr1[] = "@applications/$theme/$zone/$controller/$view1.php";

                                if (!empty($module))
                                    $arr1[] = "@applications/$theme/$zone/modules/$module/views/$controller/$view1.php";
                            }
                        }

                        if ($controller == 'site')
                            $arr1[] = "@applications/$application_id/$zone/$view1.php";

                        if (!empty($zone))
                            $arr1[] = "@applications/$application_id/$zone/$controller/$view1.php";

                        if (!empty($module))
                            $arr1[] = "@applications/$application_id/$zone/modules/$module/views/$controller/$view1.php";
                        else
                            $arr1[] = "@applications/$application_id/$zone/views/$controller/$view1.php";

                        if ($is_widget) {
                            if (!empty($zone)) {

                                $arr1[] = "@$zone/views/$controller/$view1.php";
                                if (!empty($module))
                                    $arr1[] = "@$zone/modules/$module/views/$controller/$view1.php";
                            }
                        }
                    }
                }
            }
        }

        $view_param = FHtml::getRequestParam('view');
        $result = [];

        foreach ($arr1 as $view1) {
            $view1_file = str_replace('@', $root_folder . '/', $view1);
            if (!is_file($view1_file))
                continue;

            if (!empty($view_param)) {
                $result[] = str_replace('.php', "_$view_param.php", $view1);
            }
            $result[] = $view1;
        }

        return $result;
    }

    public static function renderWidget($view, $params = [], $context = null, $displayError = true, $is_widget = true)
    {
        return static::renderView($view, $params, $context, $displayError, $is_widget);
    }

    public static function renderView($view, $params = [], $context = null, $displayError = true, $is_widget = false)
    {
        if (is_string($is_widget))
            $is_widget = false;

        if (!isset($context))
            $context = FHtml::currentControllerObject();

        $page = self::currentView();

        if (is_file($view)) {
            return $page->renderFile($view, $params, $context, $displayError, $is_widget);
        }

        $viewFile = self::findViewFile($view, $context);
        $viewFile = Yii::getAlias($viewFile);

        if (is_file($viewFile)) {
            if ($page->theme !== null) {
                $viewFile = $page->theme->applyTo($viewFile);
            }
            if ($is_widget) {
                return FWidget::widget(array_merge(['display_type' => $view, 'params' => $params], $params));
            } else {
                return $page->renderFile($viewFile, $params, $context);
            }
        } else {
            if ($displayError) {
                if (self::is_url($view))
                    return self::showIframe($view);

                echo self::showErrorMessage(FHtml::t('message', 'View not found') . ': ' . $view);
            }

            return false;
        }
    }

    public static function showIframe($url, $style = 'position: relative; height: 100%; min-height: 600px; width: 100%;')
    {

        return "<iframe src='$url' scrolling='auto' frameborder='0' style='$style'></iframe>";
    }

    public static function findViewFile($view, $context = null)
    {
        $page = self::currentView();

        if (is_array($view)) {
            $view = $view[0];
        }

        $view = str_replace('.php', '', $view);


        if (strncmp($view, '@', 1) === 0) {
            // e.g. "@app/views/main"
            $file = Yii::getAlias($view);
        } elseif (strncmp($view, '//', 2) === 0) {
            // e.g. "//layouts/main"
            $file = Yii::$app->getViewPath() . DIRECTORY_SEPARATOR . ltrim($view, '/');
        } elseif (strncmp($view, '/', 1) === 0) {
            // e.g. "/site/index"
            if (Yii::$app->controller !== null) {
                $file = Yii::$app->controller->module->getViewPath() . DIRECTORY_SEPARATOR . ltrim($view, '/');
            } else {

                return '';
            }
        } elseif ($context instanceof ViewContextInterface) {
            $file = $context->getViewPath() . DIRECTORY_SEPARATOR . $view;
        } elseif (($currentViewFile = $page->getViewFile()) !== false) {
            $file = dirname($currentViewFile) . DIRECTORY_SEPARATOR . $view;
        } else {
            return '';
        }

        if (pathinfo($file, PATHINFO_EXTENSION) !== '' && is_file($file)) {
            return $file;
        }
        $path = $file . '.' . $page->defaultExtension;
        if ($page->defaultExtension !== 'php' && !is_file($path)) {
            $path = $file . '.php';
        }

        $path = FFile::getFullFileName($path);

        if (is_file($path))
            return $path;
        else {
            $path1 = FHtml::currentViewPath() . DS . $view . '.php';
            if ($path1 != $path && is_file($path1))
                return $path1;

            return false;
        }
    }

    public static function findView($view, $return_empty_if_not_found = false, $is_widget = true)
    {
        $arr1 = self::getViews($view, $is_widget);

        //try to look into array of Views
        if (is_array($arr1)) {
            foreach ($arr1 as $view1) {
                $result = FHtml::findViewFile($view1);
                if (is_file($result)) {
                    return $view1;
                }
            }
        }

        if ($return_empty_if_not_found) {
            return false;
        } else
            return $view;
    }

    public static function buildCategoriesMenu($object_type, $linkurl = '', $ul_css = 'list-unstyled simple-list margin-bottom-20', $li_label = '<i class="fa fa-angle-right"></i>    ')
    {
        if (empty($linkurl))
            $linkurl = '/' . strtolower(BaseInflector::id2camel($object_type)) . '/list';

        $result = "<ul class='" . $ul_css . "'>";
        $cats = FHtml::getCategoriesByType($object_type);
        foreach ($cats as $cat) {
            $result .= '<li>' . $li_label . '<a href="' . FHtml::createUrl($linkurl, ['category_id' => $cat->id]) . '">' . $cat->name . '</a></li>';
        }
        $result .= '</ul>';

        return $result;
    }

    public static function buttonCloseQuickAdd($model = null)
    {
        return FHtml::a('<i class="fa fa-undo"></i> ' . FHtml::t('button', 'Close'), ['index', 'form_enabled' => 0], ['class' => 'btn btn-default', 'data-pjax' => 0]);
    }

    //2017/3/14

    public static function showViewButtons($model, $canEdit = true, $canDelete = true, $template = '{print}{edit}{cancel}')
    {
        if (Yii::$app->request->isAjax) {
            return FHtml::buttonCloseModal();
        }

        $is_mobile = FHtml::currentDevice()->isMobile();
        if ($is_mobile) {
            return '';
        } else {
            return self::showActionsButton($model, $canEdit, $canDelete, $template);
        }
    }

    public static function showSearchButtons($model, $template = '{search}{cancel}', $container = '')
    {
        $is_mobile = FHtml::currentDevice()->isMobile();
        if ($is_mobile) {
            return '';
        } else {
            if (Yii::$app->request->isAjax) {
                $result = self::buttonSearchAjax($model, $container) . self::buttonCancel($model);
                //$result = self::buttonSearch($model) . self::buttonCancel($model);
            } else {
                $result = self::buttonSearchAjax($model, $container) . self::buttonCancel($model);
                //$result = self::buttonSearch($model) . self::buttonCancel($model);
            }
        }

        return '<div class="hidden-print" style="border-top:1px dashed lightgrey; padding-top:20px">' . $result . '</div>';
    }

    public static function getReturnUrl()
    {
        $return_url = FHtml::getRequestParam(['return_url', '__returnUrl']);
        $return_url1 = FHtml::Session('__returnUrl');

        return !empty($return_url) ? $return_url : (!empty($return_url1) ? $return_url1 : '');
    }

    public static function setReturnUrl($return_url = '')
    {
        if (!empty($return_url))
            $return_url = FHtml::currentUrl();
        FHtml::Session('__returnUrl', $return_url);
    }

    public static function showActionsButton($model, $canEdit = true, $canDelete = true, $template = '', $custom_buttons = [], $container = '', $is_popup = null)
    {
        $saveT = '';
        $deleteT = '';
        $cancelT = '';
        $cloneT = '';
        $editT = '';
        $viewT = '';
        $printT = '';
        $appendText = '';

        if (!isset($is_popup))
            $is_popup = !empty(FHtml::getRequestParam('layout'));

        if (is_array($template)) {
            $custom_buttons = $template;
            $template = '';
        }

        $return_url = FHtml::getReturnUrl();

        if (empty($template)) {
            if (empty($return_url))
                $template = '<div class="row"><div class="col-md-6 col-xs-12">{save} {cancel} </div> <div class="col-md-6 col xs-12"><div class="pull-right"> {view} {delete} {clone} </div></div></div>';
            else
                $template = '<div class="row"><div class="col-md-12 col-xs-12">{save}{cancel} </div> </div>';
        }

        if ($template == '_form_add')
            $template = '{save}{cancel}';
        else if ($template === false) {
            $template = '{save}{cancel}';
            $form_enabled = true;
        }

        $action = FHtml::currentAction();
        $is_mobile = FHtml::currentDevice()->isMobile();
        $form_enabled = !empty(FHtml::getRequestParam('form_enabled'));

        if (!$form_enabled && Yii::$app->request->isAjax) {

            if (FHtml::isInArray($action, ['view', 'view-detail']))
                return '';

            if ($canEdit)
                $saveT = FHtml::buttonCreateAjax($model);

            $cancelT = FHtml::buttonCloseModal();

            $result = $template;
        } else {
            if (in_array($action, ['index'])) {

                $template = '<div class="row"><div class="col-md-12 col-xs-12">{save}{cancel} </div> </div>';
                if (empty($container))
                    $container = self::getPjaxContainerId(FHtml::getTableName($model), 'crud-datatable');

                $saveT = self::showPlusButton($model, false, false, $container);
                $cancelT = self::buttonCancel($model, ['form_enabled', 'id']);
                $appendText = '';
            } else {
                if ($is_mobile) {
                    $result = '<div class="row"><div class="col-md-12">{save} {view} {cancel} {delete} </div></div>';
                    if ($canEdit) {
                        $saveT = self::buttonSaveBack($model);
                    }
                    $saveT = self::buttonSaveBack($model);

                    if (!$model->isNewRecord && $canDelete) {
                        $deleteT = self::buttonDelete($model);
                    }
                    $cancelT = self::buttonCancel($model);
                } else {
                    if (!$model->isNewRecord && $canDelete) {
                        $deleteT = self::buttonDelete($model);
                    }

                    if ($canEdit) {
                        $saveT = self::buttonSaveBack($model);
                        $editT = empty($model->id) ? '' : '  ' . self::buttonEdit($model);

                        $saveT .= self::buttonSave($model);
                        $cloneT = FHtml::submitButton('<i class="fa fa-copy"></i> ' . FHtml::t('button', 'Save & Clone '), ['class' => 'btn btn-success pull-right', 'onclick' => 'submitForm("clone")']);
                    }

                    $cancelT = self::buttonCancel($model);
                    $viewT = empty($model->id) ? '' : '  ' . self::buttonView($model);
                    $printT = self::buttonPrint();
                }
                $appendText = ''; //self::showModelHistory2($model);
            }
            $result = $template;
        }

        $searchT = self::buttonSearch($model);

        if (key_exists('save', $custom_buttons))
            $saveT = $custom_buttons['save'];

        if (key_exists('clone', $custom_buttons))
            $cloneT = $custom_buttons['clone'];

        if (key_exists('delete', $custom_buttons))
            $deleteT = $custom_buttons['delete'];

        if (key_exists('view', $custom_buttons))
            $viewT = $custom_buttons['view'];

        if (key_exists('cancel', $custom_buttons))
            $cancelT = $custom_buttons['cancel'];

        if (key_exists('search', $custom_buttons))
            $searchT = $custom_buttons['search'];

        if (key_exists('edit', $custom_buttons))
            $editT = $custom_buttons['edit'];

        if ($is_popup) {
            $saveT = self::buttonSave($model, 'primary');
            $cancelT = '';
            $cloneT = '';
            $deleteT = '';
        }

        $result = str_replace('{save}', $saveT, $result);
        $result = str_replace('{clone}', $cloneT, $result);
        $result = str_replace('{delete}', $deleteT, $result);
        $result = str_replace('{view}', $viewT, $result);
        $result = str_replace('{edit}', $editT, $result);
        $result = str_replace('{print}', $printT, $result);
        $result = str_replace('{search}', $searchT, $result);

        $result = str_replace('{cancel}', $cancelT, $result);

        $js = @"<input type=\"hidden\" id=\"saveType\" name=\"saveType\"> <script language=\"javascript\" type=\"text/javascript\">
                function submitForm(saveType) {
                    $('#saveType').val(saveType);
                }
            </script>";

        $result .= $js;

        return self::showFixedDiv($result . $appendText, !empty(FHtml::getRequestParam('form_enabled')) ? 'relative' : '', 'form-buttons');
    }

    public static function showFixedDiv($content, $position = '', $css = '', $width = '100%', $height = 'auto', $left = '0px', $right = '0px', $padding = '15px')
    {
        if (empty($position))
            $position = FHtml::setting('form_buttons_style', 'fixed');

        return "<div class='hidden-print form-label $css' style='padding:$padding; padding-bottom:0px; right:$right; left:$left; position: $position; height: $height;bottom: 0;width: $width; border-top:1px dashed lightgrey; z-index:2;'>$content</div>";
    }

    public static function buttonSaveBack($model = null)
    {
        return FHtml::submitButton('<i class="fa fa-save"></i> ' . FHtml::t('button', 'Save & Back'), ['class' => 'btn btn-primary']);
    }

    //build frontend Menu by Categories
    public static function submitButton($content = 'Submit', $options = [])
    {
        $content = FHtml::t('button', $content);
        $options['type'] = 'submit';
        return Html::button($content, $options);
    }

    //2017.5.8
    public static function buttonDelete($model = null, $confirm = 'Are you sure')
    {
        return FHtml::a('<span class="glyphicon glyphicon-trash"></span>' . FHtml::t('button', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger pull-right',
            'data' => [
                empty($confirm) ? null : 'confirm' => (FHtml::t('message', $confirm) . ' ?'),
                'method' => 'post'

            ],
        ]) . '';
    }

    public static function buttonCancel($model = null, $excluded_params = ['id'])
    {
        $return_url = FHtml::getRequestParam('return_url');

        if (is_string($model) || is_array($model))
            $url = $model;
        else if (!empty($return_url))
            $url = $return_url;
        else {

            $params = Yii::$app->request->getQueryParams();

            foreach ($params as $key => $param) {
                if (!in_array($key, ['type', 'status'])) {
                    unset($params[$key]);
                }
            }

            $url = ['index'] + $params;
        }

        return FHtml::a('<i class="fa fa-undo"></i> ' . FHtml::t('button', 'Close'), $url, ['class' => 'btn btn-default', 'data-pjax' => 0], array_merge($excluded_params, ['id']));
    }

    public static function buttonSearch($model = null, $color = 'primary')
    {
        return FHtml::submitButton('<i class="fa fa-search"></i> ' . FHtml::t('button', 'Search'), ['class' => 'btn btn-' . $color, 'onclick' => 'submitForm("search")']);
    }

    public static function buttonEdit($model = null)
    {
        if (is_string($model) || is_array($model))
            $url = $model;
        else
            $url = ['update', 'id' => $model->id];

        return FHtml::a('<i class="fa fa-edit"></i> ' . FHtml::t('button', 'update'), $url, ['class' => 'btn btn-warning pull-right', 'data-pjax' => 0]);
    }

    public static function buttonSave($model = null, $color = 'default')
    {
        return FHtml::submitButton('<i class="fa fa-save"></i> ' . FHtml::t('button', 'Save'), ['class' => 'btn btn-' . $color, 'onclick' => 'submitForm("save")']);
    }

    public static function buttonView($model = null)
    {
        if (is_string($model) || is_array($model))
            $url = $model;
        else
            $url = ['view', 'id' => $model->id];

        return FHtml::a('<i class="fa fa-print"></i> ' . FHtml::t('button', 'Preview'), $url, ['class' => 'btn btn-default', 'data-pjax' => 0]);
    }

    public static function buttonPrint($model = null)
    {
        return @"<a class=\"btn blue hidden-print \" onclick=\"javascript:window.print();\"> Print <i class=\"fa fa-print\"></i> </a>";
    }

    public static function showModelHistory2($model, $fields = FHtml::FIELDS_HISTORY, $layout = self::LAYOUT_ONELINE, $css = '')
    {
        $created = FHtml::showUser(FHtml::getFieldValue($model, ['created_user', 'created_by', 'created'])) . ' at ' . FHtml::showDateTime(FHtml::getFieldValue($model, ['created_date', 'created_at', 'created']));
        $modified = FHtml::showUser(FHtml::getFieldValue($model, ['modified_user', 'modified_by', 'modified', 'updated_user', 'updated_by', 'updated'])) . ' at ' . FHtml::showDateTime(FHtml::getFieldValue($model, ['modified_date', 'modified_at', 'modified', 'updated_date', 'updated_at', 'updated']));

        $result = !empty($created) ? FHtml::t('common', 'Created by') . ' <b>' . $created . '</b>. ' : '';
        $result .= !empty($modified) ? FHtml::t('common', 'Modified by') . ' <b>' .  $modified  . '</b>' : '';
        return "<div style='font-size:80%;color:darkgrey;'>$result</div>";
    }

    public static function showPrintHeader($title = '')
    {
        $image = FHtml::showCurrentLogo();
        $company = '<b>' . FHtml::settingCompanyName() . '</b><br/>';
        $desc = $company . FHtml::showDate(date('Y-m-d'));
        $result = "<div class='col-md-12 col-xs-12 no-padding' style='padding:20px; color:grey; border-bottom: 1px dashed lightgrey; padding-bottom: 10px;margin-bottom: 20px'><div class='col-xs-2 pull-left' style='padding-top:10px'>{$image}</div><div class='col-xs-7'><h2>{$title}</h2></div><div class='col-xs-3 pull-right text-right' style='padding:10px'><small>{$desc}</small></div></div>";
        return "<div class='row visible-print'>$result</div>";
    }

    public static function showLinkUrl($label, $url, $display_viewmore = false, $params = [], $target = '')
    {
        if (!empty($params))
            $url = self::createUrl($url, $params);

        if (empty($url) || $url == '#' || $display_viewmore)
            return $label;

        $css = '';
        if (empty($label) || ($label == '...')) {
            $label = $display_viewmore ? FHtml::t('button', 'View more') : $url;
            $css = 'btn-u btn-u-sm rgba-' . FHtml::config(FHtml::SETTINGS_MAIN_COLOR, 'green', null, 'Theme', FHtml::EDITOR_SELECT, 'color');
        }

        return '<a class="' . $css . '" target="' . $target . '" href="' . $url . '">' . $label . '</a>';
    }

    public static function showViewMoreUrl($url, $display_viewmore = true, $params = [], $label = '', $color = '', $css = '')
    {
        if (!$display_viewmore)
            return '';

        if (!empty($params))
            $url = self::createUrl($url, $params);
        if (empty($color))
            $color = FHtml::currentApplicationMainColor();
        if (empty($label))
            $label = FHtml::t('button', 'View more');

        $css = empty($css) ? 'btn-u btn-u-sm' : $css;
        $css = $css . ' rgba-' . $color;
        return '<a class="' . $css . '" href="' . $url . '">' . $label . '</a>';
    }

    public static function getCurrentUserRole()
    {
        return self::getCurrentRole();
    }

    public static function buildEditor1($model, $attribute, $form, $editor = '', $lookup = '', $options = null)
    {
        ob_start(); // clear buffer string
        $editor = strtolower($editor);

        if (is_object($model)) {
            $name = BaseInflector::camelize($model->getTableName()) . "[$attribute]";

            if (empty($editor))
                $editor = FHtml::getShowType($model, $attribute);
            $value = FHtml::getFieldValue($model, $attribute);
        } else {
            $name = $model;
            $value = $attribute;
            $model = null;
            $attribute = null;
        }

        $items = null;
        if (empty($lookup) && is_object($model)) // $lookup is empty means it will get data from object_settings table
            $lookup = $model->getTableName();
        else if (is_string($lookup))
            $lookup = '@' . $lookup; // if not empty, means it will take from real table

        if (is_string($lookup))
            $items = FHtml::getComboArray($lookup, $lookup, $attribute, true, 'id', 'name');
        else
            $items = $lookup;

        if (!is_array($options))
            $options = [];

        if (in_array($editor, ['datetime', FHtml::EDITOR_DATETIME, FHtml::SHOW_DATETIME]) || (empty($editor) && FHtml::isInArray($attribute, FHtml::FIELDS_DATE))) {
            $result = DateTimePicker::widget(array_merge(['pluginOptions' => $options], ['type' => DateTimePicker::TYPE_INPUT, 'model' => $model, 'attribute' => $attribute, 'name' => $name]));
        } else if (in_array($editor, ['time', FHtml::EDITOR_TIME])) {
            $result = TimePicker::widget(array_merge(['pluginOptions' => $options], ['type' => DateTimePicker::TYPE_INPUT, 'model' => $model, 'attribute' => $attribute, 'name' => $name]));
        } else if (in_array($editor, ['date', FHtml::EDITOR_DATE,  FHtml::SHOW_DATE])) {
            //$result = MaskedInput::widget(array_merge([], ['model' => $model, 'attribute' => $attribute, 'name' => $name, 'value' => $value, 'clientOptions' => ['alias' => 'date', 'groupSeparator' => ',', 'autoGroup' => true, 'removeMaskOnSubmit' => false]]));
            $result = DatePicker::widget(array_merge(['pluginOptions' => $options], ['type' => DateTimePicker::TYPE_INPUT, 'model' => $model, 'attribute' => $attribute, 'name' => $name, 'value' => $value]));
        } else if (in_array($editor, ['file', 'upload', FHtml::EDITOR_FILE]) || FHtml::isInArray($attribute, FHtml::FIELDS_FILES)) {
            $result = FFileInput::widget(array_merge(['pluginOptions' => $options], ['model' => $model, 'attribute' => $attribute, 'name' => $name, 'pluginOptions' => ['maxFileSize' => FHtml::settingMaxFileSize(), 'options' => ['accept' => 'image/*', 'multiple' => false], 'showPreview' => true, 'showCaption' => false, 'showRemove' => true, 'showUpload' => true, 'pluginOptions' => ['browseLabel' => '', 'removeLabel' => '', 'previewFileType' => 'any', 'uploadUrl' => Url::to([FHtml::config('UPLOAD_FOLDER', '/site/file-upload')])]]]));
        } else if (in_array($editor, ['image', 'thumbnail', FHtml::EDITOR_FILE]) || FHtml::isInArray($attribute, FHtml::FIELDS_IMAGES)) {
            $result = FFileInput::widget(array_merge(['pluginOptions' => $options], ['model' => $model, 'attribute' => $attribute, 'name' => $name, 'pluginOptions' => ['model' => $model, 'form' => $form, 'attribute' => $attribute, 'name' => $name, 'maxFileSize' => FHtml::settingMaxFileSize(), 'options' => ['accept' => 'image/*', 'multiple' => false], 'showPreview' => true, 'showCaption' => false, 'showRemove' => true, 'showUpload' => true, 'pluginOptions' => ['browseLabel' => '', 'removeLabel' => '', 'previewFileType' => 'any', 'uploadUrl' => Url::to([FHtml::config('UPLOAD_FOLDER', '/site/file-upload')])]]]));
        } else if (in_array($editor, ['array', 'select', 'dropdown', FHtml::EDITOR_SELECT]) || FHtml::isInArray($attribute, FHtml::FIELDS_GROUP)) {
            if (is_object($model))
                $result = Html::activeDropDownList($model, $attribute, $items, array_merge($options, ['class' => 'form-control', 'rows' => 3]));
            else
                $result = Html::dropDownList($name, $value, $items, array_merge($options, ['class' => 'form-control', 'rows' => 3]));
            //$result = \kartik\widgets\Select2::widget(array_merge(['pluginOptions' => $options], ['model' => $model, 'attribute' => $attribute, 'name' => $name, 'data' => $items, 'options' => ['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]));

        } else if (in_array($editor, ['arraymany', 'selectmany', FHtml::EDITOR_SELECT])) {
            $result = \kartik\widgets\Select2::widget(array_merge(['pluginOptions' => $options], ['model' => $model, 'attribute' => $attribute, 'name' => $name, 'data' => $items, 'options' => ['multiple' => true], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]));
        } else if (in_array($editor, ['bool', 'boolean', 'checkbox', FHtml::EDITOR_BOOLEAN]) || FHtml::isInArray($attribute, FHtml::FIELDS_BOOLEAN)) {
            //$result = \kartik\checkbox\CheckboxX::widget(array_merge(['pluginOptions' => $options], ['model' => $model, 'attribute' => $attribute, 'name' => $name, 'pluginOptions' => ['model' => $model, 'form' => $form, 'attribute' => $attribute, 'name' => $name, 'theme' => 'krajee-flatblue', 'size' => 'md', 'threeState' => false]]));
            $result = FCheckbox::widget(array_merge(['pluginOptions' => $options], ['model' => $model, 'attribute' => $attribute, 'name' => $name, 'value' => $value, 'pluginOptions' => ['model' => $model, 'form' => $form, 'attribute' => $attribute, 'name' => $name, 'theme' => 'krajee-flatblue', 'size' => 'md', 'threeState' => false]]));
        } else if (in_array($editor, ['text', 'input', 'textarea']) || FHtml::isInArray($attribute, FHtml::FIELDS_TEXTAREA)) {
            if (is_object($model))
                $result = Html::activeTextarea($model, $attribute,  array_merge($options, ['class' => 'form-control', 'rows' => 3]));
            else
                $result = Html::textarea($name, $value, array_merge($options, ['class' => 'form-control', 'rows' => 3]));
        } else if (in_array($editor, ['html', FHtml::EDITOR_HTML]) || FHtml::isInArray($attribute, FHtml::FIELDS_HTML)) {
            $result = FCKEditor::widget(array_merge(['clientOptions' => $options], ['model' => $model, 'attribute' => $attribute, 'name' => $name, 'options' => ['rows' => 5, 'disabled' => false], 'preset' => 'normal']));
        } else if (in_array($editor, ['numeric', 'currency', FHtml::EDITOR_NUMERIC]) || FHtml::isInArray($attribute, FHtml::FIELDS_PRICE)) {
            //$result = MaskedInput::widget(array_merge([], ['model' => $model, 'attribute' => $attribute, 'name' => $name,  'value' => $value, 'clientOptions' => ['alias' => 'decimal', 'groupSeparator' => ',', 'autoGroup' => true, 'removeMaskOnSubmit' => true]]));
            $result = FNumericInput::widget(array_merge([], ['model' => $model, 'attribute' => $attribute, 'name' => $name,  'value' => $value, 'clientOptions' => ['alias' => 'decimal', 'groupSeparator' => ',', 'autoGroup' => true, 'removeMaskOnSubmit' => true]]));
        } else if (in_array($editor, ['slide', FHtml::EDITOR_SLIDE])) {
            $result = Slider::widget(array_merge(['pluginOptions' => $options], ['model' => $model, 'attribute' => $attribute, 'name' => $name, 'sliderColor' => Slider::TYPE_GREY, 'handleColor' => Slider::TYPE_DANGER, 'pluginOptions' => ['min' => 0, 'max' => 100, 'step' => 1]]));
        } else if (in_array($editor, ['rate', FHtml::EDITOR_RATE])) {
            $result = StarRating::widget(array_merge(['pluginOptions' => $options], ['model' => $model, 'attribute' => $attribute, 'name' => $name, 'pluginOptions' => ['stars' => 5, 'min' => 0, 'max' => 5, 'step' => 1, 'showClear' => true, 'showCaption' => true, 'defaultCaption' => '{rating}', 'starCaptions' => [0 => '', 1 => 'Poor', 2 => 'OK', 3 => 'Good', 4 => 'Super', 5 => 'Extreme']]]));
        } else {
            if (is_object($model))
                $result = Html::activeTextarea($model, $attribute,  array_merge(['pluginOptions' => $options], ['class' => 'form-control']));
            else
                $result = Html::textInput($attribute, $value, array_merge(['pluginOptions' => $options], ['class' => 'form-control']));
        }

        ob_end_clean();
        return $result;
    }

    public static function buildEditor($model, $attribute, $form, $editor = '', $lookup = '', $field = null)
    {
        $result = '';

        if (empty($lookup)) // $lookup is empty means it will get data from object_settings table
            $lookup = $model->getTableName();
        else {
            $lookup = '@' . $lookup; // if not empty, means it will take from real table
        }

        $name = BaseInflector::camelize($model->getTableName()) . "[$attribute]";

        if (isset($form)) {
            if (!isset($field)) {
                $field = $form->field($model, $attribute);
            }

            if (empty($editor)) {
                if (FHtml::isInArray($attribute, FHtml::FIELDS_IMAGES))
                    $editor = FHtml::EDITOR_FILE;
                else if (FHtml::isInArray($attribute, FHtml::FIELDS_HTML))
                    $editor = FHtml::EDITOR_TEXT;
                else if (FHtml::isInArray($attribute, FHtml::FIELDS_OVERVIEW))
                    $editor = 'textarea';
                else if (FHtml::isInArray($attribute, FHtml::FIELDS_BOOLEAN))
                    $editor = FHtml::EDITOR_BOOLEAN;
                else if (FHtml::isInArray($attribute, FHtml::FIELDS_PRICE))
                    $editor = FHtml::EDITOR_CURRENCY;
                else if (FHtml::isInArray($attribute, FHtml::FIELDS_DATETIME))
                    $editor = FHtml::EDITOR_DATETIME;
                else if (FHtml::isInArray($attribute, FHtml::FIELDS_DATE))
                    $editor = FHtml::EDITOR_DATE;
                else if (FHtml::isInArray($attribute, FHtml::FIELDS_FILES))
                    $editor = FHtml::EDITOR_FILE;
                else if (FHtml::isInArray($attribute, FHtml::FIELDS_TIME))
                    $editor = FHtml::EDITOR_TIME;
                else if (!empty($lookup) || FHtml::isInArray($attribute, FHtml::FIELDS_LOOKUP))
                    $editor = FHtml::EDITOR_SELECT;
                else if (FHtml::isInArray($attribute, FHtml::FIELDS_PERCENT))
                    $editor = FHtml::EDITOR_NUMERIC;
                else
                    $editor = FHtml::getShowType($model, $attribute);
            }

            if (in_array($editor, ['date', FHtml::EDITOR_DATE])) {
                $result = $field->date();
            } else if (in_array($editor, ['datetime', FHtml::EDITOR_DATETIME])) {
                $result = $field->date();
            } else if (in_array($editor, ['time', FHtml::EDITOR_TIME])) {
                $result = $field->time();
            } else if (in_array($editor, ['file', 'upload', FHtml::EDITOR_FILE])) {
                $result = $field->file();
            } else if (in_array($editor, ['image', FHtml::EDITOR_FILE])) {
                $result = $field->image();
            } else if (in_array($editor, ['select', FHtml::EDITOR_SELECT])) {
                $result = $field->select();
            } else if (in_array($editor, ['selectmany', FHtml::EDITOR_SELECT])) {
                $result = $field->selectMany();
            } else if (in_array($editor, ['bool', 'boolean', 'checkbox', FHtml::EDITOR_BOOLEAN])) {
                $result = $field->checkbox();
            } else if (in_array($editor, ['textarea', 'description', 'overview'])) {
                $result = $field->textarea(['rows' => 3]);
            } else if (in_array($editor, ['html', 'content', 'note', FHtml::EDITOR_TEXT])) {
                $result = $field->html();
            } else if (in_array($editor, ['currency', FHtml::EDITOR_CURRENCY])) {
                $result = $field->currency();
            } else if (in_array($editor, ['numeric', 'int', 'float', 'double', FHtml::EDITOR_NUMERIC])) {
                $result = $field->numeric();
            } else if (in_array($editor, ['rate', FHtml::EDITOR_RATE])) {
                $result = $field->rate();
            } else {
                if (FHtml::field_exists($field, $editor))
                    $result = $field->{$editor}();
                else
                    $result = $field->textInput();
            }
        } else {
            return self::buildEditor1($model, $attribute, $form, $editor, $lookup, []);
        }

        return $result;
    }

    public static function buildEditableOptionsInGridColumn($model, $column, $dbType = '')
    {
        if ($model->editor == 'boolean' || $model->editor == FHtml::EDITOR_BOOLEAN) {
            return [
                'size' => 'md',
                'inputType' => \kartik\editable\Editable::INPUT_SWITCH,
                'widgetClass' => SwitchInput::className(),
                'options' => [
                    'pluginOptions' => [
                        'autoclose' => true
                    ]
                ]
            ];
        } else if (!empty($model->lookup) && ($model->editor == 'select' || $model->editor == FHtml::EDITOR_SELECT)) {
            $data = FHtml::getComboArray($model->lookup, '', '', true, 'id', 'name');
            //self::var_dump($data);
            return [
                'size' => 'md',
                'inputType' => 'dropDownList',
                'widgetClass' => Select2::className(),
                'data' => $data,
                'displayValueConfig' => $data, 'options' => [
                    'pluginOptions' => [
                        'autoclose' => true,
                    ]
                ]
            ];
        } else {
            return [
                'size' => 'md',
                'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                'widgetClass' => 'kartik\datecontrol\InputControl',
                'options' => [
                    'pluginOptions' => [
                        'autoclose' => true
                    ]
                ]
            ];
        }
    }

    public static function buildTutorJsAttribute($data_step, $data_title, $data_intro = '', $condition = true, $data_position = 'top')
    {
        if (!$condition)
            return '';

        $result = '';
        $result .= " data-step='$data_step' ";
        $content = empty($data_intro) ? $data_title : "<span style='font-weight:bold;color:darkblue'>$data_title</span><br/>$data_intro";
        $result .= " data-intro='$content'";
        $result .= " data-position='$data_position'";

        return $result;
    }

    public static function displayHeadlineTitle($title, $color = 'green')
    {
        $arr = explode(' ', $title);
        if (count($arr) > 1) {
            $arr[1] = '<span class="color-' . $color . '">' . $arr[1] . '</span>';
        }

        return implode(' ', $arr);
    }

    public static function displayProductOverview($item, $type, $alignment, $color)
    {
        if ($type == 'estate') {
            if ($color == 'default') {
                $color = 'green';
            }
            return
                '<li><span class="color-' . $color . '">' . FHtml::t('estate', 'estate.beds') . ': </span><span class="' . $alignment . '">' . $item->type . ' ' . FHtml::t('estate', 'estate.items') . '</span></li>' .
                '<li><span class="color-' . $color . '">' . FHtml::t('estate', 'estate.baths') . ': </span><span class="' . $alignment . '">' . $item->status . ' ' . FHtml::t('estate', 'estate.items') . '</span></li>' .
                '<li><span class="color-' . $color . '">' . FHtml::t('estate', 'estate.houseSize') . ': </span><span class="' . $alignment . '">' . $item->category_id . ' ' . FHtml::t('estate', 'estate.m2') . '</span></li>' .
                '<li><span class="color-' . $color . '">' . FHtml::t('estate', 'estate.lotSize') . ': </span><span class="' . $alignment . '">' . $item->price . ' ' . FHtml::t('estate', 'estate.m2') . '</span></li>';
        } else
            return $item->overview;
    }

    public static function displayStar($item, $type, $alignment, $color)
    {
        if ($type == 'estate')

            return
                '<li><span class="color-' . $color . '">' . FHtml::t('estate', 'estate.beds') . ': </span><span class="' . $alignment . '">' . $item->type . ' ' . FHtml::t('estate', 'estate.items') . '</span></li>' .
                '<li><span class="color-' . $color . '">' . FHtml::t('estate', 'estate.baths') . ': </span><span class="' . $alignment . '">' . $item->status . ' ' . FHtml::t('estate', 'estate.items') . '</span></li>' .
                '<li><span class="color-' . $color . '">' . FHtml::t('estate', 'estate.houseSize') . ': </span><span class="' . $alignment . '">' . $item->category_id . ' ' . FHtml::t('estate', 'estate.m2') . '</span></li>' .
                '<li><span class="color-' . $color . '">' . FHtml::t('estate', 'estate.lotSize') . ': </span><span class="' . $alignment . '">' . $item->price . ' ' . FHtml::t('estate', 'estate.m2') . '</span></li>';

        else
            return $item->overview;
    }

    public static function showPayPalButton($description = '', $amount = '', $currency = '', $receiver_email = '', $return_url = '', $cancel_url = '')
    {
        $url = (FHtml::paypalAPILive() == true) ? 'https://www.paypal.com/cgi-bin/webscr' : 'https://sandbox.paypal.com/cgi-bin/webscr';
        $receiver_email = empty($receiver_email) ? FHtml::paypalAPIEmail() : $receiver_email;
        $currency = empty($currency) ? FHtml::settingCurrency() : $currency;
        $return_url = empty($return_url) ? FHtml::currentHost() . FHtml::createUrl('ecommerce/order/complete') : $return_url;
        $cancel_url = empty($cancel_url) ? FHtml::currentHost() . FHtml::createUrl('ecommerce/order/checkout') : $cancel_url;
        $amount = empty($amount) ? 1 : $amount;
        $description = empty($description) ? FHtml::t('common', 'Have fun') : $description;

        $result = "<form action='$url' method='post'>";
        $result .= "<input type='hidden' name='business' value='$receiver_email'>";
        $result .= "<input type='hidden' name='cmd' value='_xclick'>";
        $result .= "<input type='hidden' name='item_name' value='$description'>";
        $result .= "<input type='hidden' name='amount' value='$amount'>";

        $result .= "<input type='hidden' name='currency_code' value='$currency'>";
        $result .= "<input type='hidden' name='return' value='$return_url'>";
        $result .= "<input type='hidden' name='cancel_url' value='$cancel_url'>";
        $result .= '<input class="sm-margin-bottom-10"  type="image" src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/buy-logo-large.png" border="0" height="60" name="submit" alt="Make payments with PayPal - it\'s fast, free and secure!">';

        $result .= '</form>';

        return $result;
    }

    //2017/3/24

    public static function registerJs($js, $pos = View::POS_END)
    {
        FHtml::currentView()->registerJs($js, $pos);
    }

    public static function registerReadyJs($js, $pos = View::POS_READY)
    {
        FHtml::currentView()->registerJs($js, $pos);
        FHtml::currentView()->registerJs($js, FView::POS_AJAX_COMPLETE);
    }

    //HungHX: 20160801
    public static function registerAutoRefreshWithTimerJS($id = 'page-content', $url = '', $inside_pjax = true, $time = 3000)
    {
        if (empty($url))
            $url = FHtml::currentUrl();

        if ($inside_pjax) {
            echo "<a href='$url' class='hidden-print hidden' style='display:none' id='$id'></a>";
            $script = <<< JS
                //$("#auto-refresh-button").attr("href", "$url");
                setInterval(function(){ $("#$id").click(); }, $time);
        
JS;
        } else {

            $script = <<< JS
                $.ajaxSetup(
                {
                    cache: false,
                    beforeSend: function() {
                        $('#$id').hide();
                        $('#loading').show();
                    },
                    complete: function() {
                        $('#loading').hide();
                        $('#$id').show();
                    },
                    success: function() {
                        $('#loading').hide();
                        $('#$id').show();
                    }
                });
                var container = $("#$id");
                container.load("$url");
                var refreshId = setInterval(function()
                {
                    container.load('$url');
                }, $time);
JS;
        }

        FHtml::registerJs($script, View::POS_READY);
        FHtml::registerJs($script, FView::POS_AJAX_COMPLETE);
    }

    public static function registerAutoRefreshJS($pjax_containter)
    {
        FHtml::registerRefreshJS($pjax_containter, '.auto-refresh-content', 'change', ':selected');
        FHtml::registerSpanRefreshJS($pjax_containter, '.auto-refresh-content-span', 'click', '');
        FHtml::registerTextboxRefreshJS($pjax_containter, '.auto-refresh-content-textbox', 'change', '');
    }

    public static function registerRefreshJS($pjax_containter = '', $refresh_element = '.auto-refresh-content', $action = 'change', $find_element = ':selected', $url = '')
    {
        if (empty($refresh_element)) { //all
            static::registerAutoRefreshJS($pjax_containter);
        }

        //$refresh_script = empty($pjax_containter) ? "window.location.href = url" : " $.pjax.reload({container: '#$pjax_containter', timeout: false, url: url });";

        if (empty($url)) {
            if (!empty($find_element))
                $url = "$(this).find('$find_element').attr('url')";
            else
                $url = "$(this).attr('url')";
        } else {
            $url = "'$url'";
        }

        if (!empty($find_element)) {
            $get_field = "$(this).find(\"$find_element\").attr('field')";
            $get_value = "$(this).find(\"$find_element\").attr('value')";
        } else {
            $get_field = "$(this).attr('name')";
            $get_value = "$(this).val()";
        }

        if ($refresh_element == '.auto-refresh-content-span') {
            $change_css = " if ($(this).parent().attr('class') == 'tab-normal')
            $(this).parent().attr('class', 'tab-active active bold');
        else
            $(this).parent().attr('class', 'tab-normal');";
        } else if ($refresh_element == '.auto-refresh-content') {
            $change_css = " if ($get_value !== '')
            $(this).parent().attr('class', 'tab-active active bold');
        else
            $(this).parent().attr('class', 'tab-normal');";
        } else {
            $change_css = '';
        }

        $refresh_script = "refresh_url(url, field, val, '#$pjax_containter');";
        $script = <<< JS
$('body').on('$action' , '$refresh_element', function() {
        var field = $get_field;
        var val = $get_value;
        var url = $url;
        
        $change_css

        refresh_url(url, field, val, '#$pjax_containter');
    });
JS;
        FHtml::registerReadyJs($script);
    }


    public static function registerTextboxRefreshJS($pjax_containter = '', $refresh_element = '.auto-refresh-content-textbox', $action = 'change', $find_element = '', $url = '')
    {
        return static::registerRefreshJS($pjax_containter, $refresh_element, $action, $find_element, $url);
    }

    public static function registerSpanRefreshJS($pjax_containter = '', $refresh_element = '.auto-refresh-content-span', $action = 'click', $find_element = '', $url = '')
    {
        return static::registerRefreshJS($pjax_containter, $refresh_element, $action, $find_element, $url);
    }

    public static function getFunctionNameJS($model, $container = '', $function_name_extend = '')
    {
        if (is_object($model))
            $model = FHtml::getTableName($model);

        if (is_array($container)) {
            $function_name_extend = $container[1];
            $container = $container[0];
        }

        if (empty($container))
            $function_name = BaseInflector::camelize($model);
        else
            $function_name = BaseInflector::camelize($model . '_' . $container);

        $function_name .= $function_name_extend;
        return $function_name;
    }

    public static function getAppendFormDataJS($model, $columns = [], $field_name = '{object}Search[{column}]', $default_fields = [], $excluded_fields = ['sort_order', 'created_user', 'modified_user', 'created_date', 'modified_date', 'application_id'])
    {
        if (!is_array($columns))
            $columns = [];

        $columns = array_merge($columns, ['id']);

        if (is_object($model))
            $model = FHtml::getTableName($model);

        $modelName = BaseInflector::camelize($model);

        if (empty($model))
            return ['GET' => '', 'POST' => '', 'QUERY' => null, 'FIELDS' => $columns];


        $t = 'object: "' . $model . '", ';
        $form = "appendFormData(formData, 'object', '$model');";
        $query = '"" ';
        $log = '';
        $model_columns = FHtml::getModelFields($model);
        $columns = array_unique(array_merge($columns, $model_columns));

        foreach ($columns as $column) {
            if (FHtml::isInArray($column, $excluded_fields))
                continue;

            if (!StringHelper::startsWith('#', $field_name)) {
                $field_id = '[name=\'' . $field_name . '\']';
                $field_id = str_replace('{object}', $modelName, $field_id);
                $field_id = str_replace('{column}', $column, $field_id);
            } else {
                $field_id = $field_name;
                $field_id = str_replace('{object}', strtolower($modelName), $field_id);
                $field_id = str_replace('{column}', $column, $field_id);
            }

            $field_value = FHtml::getRequestParam($field_name);
            if (!empty($field_value)) { // if existed param in url
                $t .= "$column: '$field_value', ";
                $form = $form . "appendFormData(formData,'$column', '$field_value');";
            } else {
                $t .= $column . ': $("' . $field_id . '").value(), ';

                $form = $form . "appendFormData(formData, '$column', " . 'getVal($("' . $field_id . '")));';
                //$form = $form . "console.log(" . 'getVal($("' . $field_id. '")));';
                //$form = $form . "formData.append('$column', " . '$("' . $field_id. '").value());';
                //$form = $form . '$("' . $field_id. '").val(null);';

            }
            $query .= '+ "&' . $column . '=" + ' . '$("' . $field_id . '").value()';
        }

        $field_names = '';
        if (!empty($default_fields)) {
            foreach ($default_fields as $column => $value) {
                if (FHtml::isInArray($column, $excluded_fields))
                    continue;

                $t .= $column . ': "' . $value . '", ';
                $form = $form .  "appendFormData(formData,'$column', '$value');";

                $field_names .= $column . ',';
            }
        }

        return ['GET' => $t, 'POST' => $form, 'QUERY' => $query, 'FIELDS' => $field_names];
    }

    public static function registerPlusJS($model, $columns = [], $container = 'crud-datatable-pjax', $field_name = '{object}Search[{column}]', $default_fields = [], $form_id = '', $excluded_fields = ['sort_order', 'created_user', 'modified_user', 'created_date', 'modified_date', 'application_id'])
    {
        if (is_object($model))
            $model = FHtml::getTableName($model);

        if (is_array($container)) {
            $function_name_extend = $container[1];
            $container = $container[0];
        } else {
            $function_name_extend = '';
        }
        $function_name = self::getFunctionNameJS($model, $container, $function_name_extend);

        $method = 'POST';

        if (empty($form_id))
            $form_id = str_replace('_', '-', $model) . $container;
        else {
            if ($form_id == 'GET' || $form_id == 'POST')
                $method = $form_id;
            $form_id = str_replace('_', '-', $model);
        }

        $arr = self::getAppendFormDataJS($model, $columns, $field_name, $default_fields, $excluded_fields);
        $form = $arr['POST'];
        $t = $arr['GET'];

        $zone = FHtml::currentZone();

        if ($method == 'GET' || $method == false || strpos($field_name, 'Search[') !== false) {
            $js = @'
            function plus' . $function_name . '() {
                var query = $(\'#' . $form_id . '-form\').serialize();

                $.ajax({
                        url: "' . FHtml::createBaseAPIUrl('plus', [], $zone) . '?" + query,
                        type: "POST",
                        data: { 
                            ' . $t .
                '},
                        success: function (data) {
                            if(data == 1 || data == "") // save true
                            {
                                refreshPage(\'#' . $container . '\');
                            }
                            else
                                if (typeof data == "string") alert(data);
                        }
                     })
            }';
        } else {

            $js = @'
            function plus' . $function_name . '() {
                //var formData = new FormData($(\'#' . $form_id . '-form\')[0]);
                var formData = new getFormData(\'#' . $form_id . '-form\', \'' . BaseInflector::camelize($model) . '\'); 
                ' . $form . '
                $.ajax({
                        url: "' . FHtml::createBaseAPIUrl('plus', [], $zone) . '",
                        type: "POST",
                        data: formData,
                        processData: false,  // tell jQuery not to process the data
                        contentType: false,  // tell jQuery not to set contentType
                        success: function (data) {
                            $( \'#' . $form_id . '-form\' ).each(function(){
                                this.reset();
                            });
                            
                            if(data == 1 || data == "") // save true
                            {
                                refreshPage([\'#' . $container . '\', \'#' . str_replace('_', '-', $model) . '-form-pjax\']);
                            }
                            else
                                if (typeof data == "string") alert(data);
                        }
                     })
            }';
        }

        FHtml::currentView()->registerJs($js, View::POS_END);
        self::registerDeleteJs($model, $container);
    }

    public static function registerSearchJS($model, $columns = [], $container = 'crud-datatable-pjax', $field_name = '{object}Search[{column}]', $default_fields = [], $form_id = '', $excluded_fields = ['sort_order', 'created_user', 'modified_user', 'created_date', 'modified_date', 'application_id'])
    {
        if (is_object($model))
            $model = FHtml::getTableName($model);

        if (is_array($container)) {
            $function_name_extend = $container[1];
            $container = $container[0];
        } else {
            $function_name_extend = '';
        }
        $function_name = self::getFunctionNameJS($model, $container, $function_name_extend);

        if (empty($form_id))
            $form_id = str_replace('_', '-', $model) . $container;
        else {
            if ($form_id == 'GET' || $form_id == 'POST')
                $method = $form_id;
            $form_id = str_replace('_', '-', $model);
        }

        $arr = self::getAppendFormDataJS($model, $columns, $field_name, $default_fields, $excluded_fields);
        $form = $arr['POST'];
        $query = $arr['QUERY'];

        $js = '';
        //$js .= @'
        //            function search' . $function_name . '() {
        //                self.location = "' . str_replace('\\', '/', FHtml::createUrl(FHtml::currentUrlPath(), ArrayHelper::merge(['action' => 'filter'], FHtml::RequestParams($columns)))) . '&" + ' . $query . ';
        //            }';

        $js .= @'
            function search' . $function_name . '() {
                //var formData = new FormData($(\'#' . $form_id . '-form\')[0]);
                var formData = new getFormData(\'#' . $form_id . '-form\', \'' . BaseInflector::camelize($model) . '\');
                ' . $form . '
                $.ajax({
                        url: "' . FHtml::createBaseAPIUrl('post') . '",
                        type: "POST",
                        data: formData,
                        processData: false,  // tell jQuery not to process the data
                        contentType: false,  // tell jQuery not to set contentType
                        success: function (data) {
                            refreshPage([\'#' . $container . '\', \'#' . str_replace('_', '-', $model) . '-form-pjax\']);
                        }
                     })
            }';

        FHtml::currentView()->registerJs($js, View::POS_END);
    }

    //2017/3/6
    public static function registerResetJs($model, $params = [], $container = 'crud-datatable-pjax')
    {
        if (is_object($model))
            $model = FHtml::getTableName($model);

        $modelName = BaseInflector::camelize($model . '_' . $container);

        if (empty($model))
            return;

        $zone = FHtml::currentZone();

        $js = @'
            function reset' . $modelName . '($id) {
                $.ajax({
                        url: "' . FHtml::createBaseAPIUrl('reset', [], $zone) . '",
                        type: "POST",
                        data: { 
                            object: "' . $model . '", id: $id, ' . FHtml::encode($params, 'array') . '
                        },
                        success: function (data) {
                            if(data == 1 || data == "") // save true
                            {
                                refreshPage(\'#' . $container . '\');
                                //$.pjax.reload(\'#' . $container . '\', {timeout : false});
                                return false;
                            }
                            else
                                if (typeof data == "string") alert(data);
                        }
                     })
            }';



        FHtml::currentView()->registerJs($js, View::POS_END);
    }

    public static function registerUnlinkJs($model, $params = [], $container = 'crud-datatable-pjax')
    {
        if (is_object($model))
            $model = FHtml::getTableName($model);

        $modelName = BaseInflector::camelize($model . '_' . $container);

        if (empty($model))
            return;

        $zone = FHtml::currentZone();

        $js = @'
            function unlink' . $modelName . '($id) {
                $.ajax({
                        url: "' . FHtml::createBaseAPIUrl('unlink', [], $zone) . '",
                        type: "POST",
                        data: { 
                            object: "' . $model . '", id: $id, ' . FHtml::encode($params, 'array') . '
                        },
                        success: function (data) {
                            if(data == 1 || data == "") // save true
                            {
                                //refreshPage(\'#' . $container . '\');
								let url = window.location;
                                $.pjax.reload(\'#' . $container . '\', {timeout : false, url : url});
                                return false;
                            }
                            else
                                if (typeof data == "string") alert(data);
                        }
                     })
            }';

        FHtml::currentView()->registerJs($js, View::POS_END);
    }

    public static function registerDeleteJs($model, $container = 'crud-datatable-pjax')
    {
        if (is_object($model))
            $model = FHtml::getTableName($model);

        $modelName = BaseInflector::camelize($model . '_' . $container);

        if (empty($model))
            return;

        $zone = FHtml::currentZone();

        $js = @'
            function delete' . $modelName . '($id) {
                jQuery.ajax({
                        url: "' . FHtml::createBaseAPIUrl('remove', [], $zone) . '",
                        type: "POST",
                        data: { 
                            object: "' . $model . '", id: $id
                        },
                        success: function (data) {
                            if(data == 1 || data == "") // save true
                            {
                                $(\'#' . $container . '_\' + $id).remove(); // remove current grid row
                                //refreshPage(\'#' . $container . '\');
                                let url = window.location;
                                $.pjax.reload(\'#' . $container . '\', {timeout : false, url : url});
                                return true;
                            }
                            else
                                if (typeof data == "string") alert(data);
                        }
                     })
            }';

        FHtml::currentView()->registerJs($js, View::POS_END);
    }

    public static function registerSortOrder($tableName,  $container = 'crud-datatable-pjax', $reload = false)
    {
        $url = FHtml::createBaseAPIUrl('sort-order', [], FHtml::currentZone());
        $function_name = str_replace('-', '_', "sort_order_$container");
        if ($reload)
            $reloadFunc = " refreshPage('#$container'); //$.pjax.reload('#$container', {timeout : false});";
        else
            $reloadFunc = '';

        $str = @"function $function_name(url){
                var result = [];
            
                $('#$container tbody').children().each(function (index, element) {
                    result.push($(this).attr('data-key'));
                });
            
                $.ajax({
                    type: 'POST',
                    url: '$url?object_type=$tableName&sort_orders=' + result,
                    data: {
                        object_type: '$tableName',
                        sort_orders: result
                    },
                    success: function (data) {
                        $reloadFunc
                    }
                });
            }";

        $str .= @"  $('.moveup').on('click', function () {
                    var elem = $(this).closest('tr');
                    elem.prev().before(elem);
            
                    $function_name('$url');
                });
            
                $('.movedown').on('click', function () {
                    var elem = $(this).closest('tr');
                    elem.next().after(elem);
            
                    $function_name('$url');
                });";
        FHtml::currentView()->registerJs($str, View::POS_END);
    }

    public static function showSortOrderArrowsButton($value = '')
    {
        $result = '<a href="javascript:void(0)" class="moveup icon-up-down"><span class="glyphicon glyphicon-arrow-up" ></span></a>&nbsp;' . $value . '&nbsp;<a href="javascript:void(0)" class="movedown icon-up-down"><span class="glyphicon glyphicon-arrow-down" ></span></a>';

        return $result;
    }

    public static function registerLookup($modelName, $changeFields = [], $search_object, $search_field = 'name', $id_field = 'id', $isMultipleForm = false)
    {
        return self::getSelect2Options($modelName, $changeFields, $search_object, $search_field, $id_field, $isMultipleForm);
    }

    public static function getSelect2Options($modelName, $changeFields = [], $search_object, $search_field = 'name', $id_field = 'id', $isMultipleForm = false)
    {
        $options1 = self::getSelect2LookupAjaxOptions($modelName, $search_object, $search_field);
        $options2 = self::getSelect2OnChangeAjaxOptions($modelName, $changeFields, $search_object, $id_field, $isMultipleForm);
        $result = ['pluginOptions' => $options1, 'pluginEvents' => $options2];
        return $result;
    }

    public static function getSelect2LookupAjaxOptions($modelName, $search_object = '', $search_field = 'name')
    {
        $search_params = '';
        if (is_array($search_object)) {
            $arr = $search_object;
            $search_object = $arr[0];
            $search_params = FHtml::encode($arr[1]);
        }

        $url = self::createBaseAPIUrl('list-lookup', ['search_object' => $search_object, 'search_field' => $search_field, 'search_params' => $search_params]);
        //self::var_dump($url ); die;
        return [
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'No result found !'; }"),
            ],
            'ajax' => [
                'url' => $url,
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; },
                 success: function(data) {
                        if (typeof data == "string") alert(data);
                 },')
            ],
        ];
    }

    public static function getSelect2OnChangeAjaxOptions($modelName, $changeFields = [], $search_object = '', $search_field = 'id', $isMultipleForm = false)
    {
        if (is_array($search_object))
            $search_object = $search_object[0];

        $urlDetail = self::createBaseAPIUrl('detail-lookup', ['search_object' => $search_object, 'search_field' => $search_field]);
        $populateFields = '';
        $tempIDs = '';
        $controlID = str_replace('_', '', $modelName);

        if (is_array($changeFields)) {
            foreach ($changeFields as $oldField => $newField) {
                if ($isMultipleForm) {
                    $tempIDs .= "var id_" . $oldField . " = this.id.replace('" . $controlID . "', '" . $oldField . "');";
                    $populateFields .= "$('#'+ id_" . $oldField . ").val(data['" . $newField . "']);";
                } else {
                    $populateFields .= "$('#" . $controlID . "-" . $oldField . "').val(data['" . $newField . "']);";
                }
            }
        } else if (is_string($changeFields)) {
            $populateFields = $changeFields; //execute local javascript function
        }
        $populateFields1 = str_replace('\'', '"', $populateFields);
        return [
            "select2:close" => "function(e) { $tempIDs;
                                                     //console.log('URL : $urlDetail' + ' . Keys: ' + $(this).val());            
                                                     $.ajax({
                                                        url: '$urlDetail',
                                                        type: 'post',
                                                        data: {keys: $(this).val()},
                                                        success: function(data) {
                                                            if (typeof data == 'string') alert(data);
                                                            //console.log('Loaded Select2OnChange: #' + data['id'] + data['name'] + '. JS: ' + '$populateFields1'); 
                                                            $populateFields
                                                        },
                                                        error: function(data) {
                                                            if(data == 1 || data == '') // save true
                                                            {
                                                               //console.log('Error Select2OnChange: ' + data);
                                                            } else {
                                                                //console.log('Error Select2OnChange: ' + data);
                                                                if (typeof data == 'string') alert(data);
                                                            }
                                                        },
                                                    });
                                                }"
        ];
    }


    public static function getPjaxContainerId($id, $type = 'gridview')
    {
        if ($type == 'gridview')
            return $id . '-pjax';
        else if ($type == 'crud-datatable')
            return 'crud-datatable' . BaseInflector::camelize($id) . '-pjax';
        return $id;
    }

    //2017/3/10: change from a -> button
    public static function showPlusButton($object_type, $is_column = true, $is_search = false, $container = '', $add_t = '', $search_t = '')
    {
        if (empty($container))
            $container = FHtml::getRequestParam('pjax_container');

        if (is_object($object_type))
            $object_type = FHtml::getTableName($object_type);

        if ($is_column === false) {
            $add_t = !empty($add_t) ? $add_t : (($is_column === false) ? FHtml::t('button', 'Save') : '');
        }

        $function_name = self::getFunctionNameJS($object_type, $container);

        if ($is_search == true) {
            $result = '<button class="btn btn-sm btn-primary hidden-print" href="#" onclick="plus' . $function_name . '()" value="+"> <span class="glyphicon glyphicon-plus"></span>' . $add_t . '</button>';
        } else {
            $result = '<div class="btn btn-primary hidden-print" data-dismiss="modal" href="#" onclick="plus' . $function_name . '()" value="+"> <span class="glyphicon glyphicon-plus"></span> ' . $add_t . ' </div>';
        }

        return $result;
    }

    public static function buttonSearchAjax($object_type, $container = '', $search_t = '')
    {
        if (empty($container))
            $container = FHtml::getRequestParam('pjax_container');

        if (is_object($object_type))
            $object_type = FHtml::getTableName($object_type);

        $search_t = !empty($search_t) ? $search_t : FHtml::t('button', 'Search');
        $result = '<div class="btn btn-sm btn-primary" style="padding-left:8px;padding-right:8px" href="#" data-dismiss="modal" onclick="search' . BaseInflector::camelize($object_type) . BaseInflector::camelize($container) . '()"> <i class="fa fa-search"></i> ' . $search_t . ' </div>';
        return $result;
    }

    public static function buttonCreateAjax($object_type, $is_column = false, $is_search = false, $container = '', $add_t = '', $search_t = '')
    {
        return self::showPlusButton($object_type, $is_column, $is_search, $container, $add_t, $search_t);
    }

    public static function buttonCreateModal($object_type,  $container = '', $form_fields = [], $default_fields = [], $form_field_template = '{object}[{column}]')
    {
        $create_url = isset($create_url) ? $create_url : FHtml::createModelUrl($object_type, 'create', ['pjax_container' => $container]);
        $result = FHtml::buttonModal('<i class="glyphicon glyphicon-plus"></i>',  $create_url, 'modal-remote', 'btn btn-success');
        FHtml::registerPlusJS($object_type, $form_fields, $container, $form_field_template, $default_fields, 'POST');
        return $result;
    }

    public static function buttonAjax($object_type, $container = '',  $label = '', $css = 'btn-success', $action = 'plus', $function_name = '')
    {
        return self::showJSButton($object_type, $container, $label, $css, $action, $function_name);
    }

    public static function buttonCloseModal($action = 'Cancel')
    {
        return '<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">' . FHtml::t('button', $action) . '</button>';
    }

    public static function showJSButton($object_type, $container = '',  $label = '', $css = 'btn-success', $action = 'plus', $function_name = '')
    {
        if (is_object($object_type))
            $object_type = FHtml::getTableName($object_type);

        $label = !empty($label) ? $label : FHtml::t('button', BaseInflector::camel2words($action));

        $function_name = !empty($function_name) ? $function_name : self::getFunctionNameJS($object_type, $container);
        $result = "<div class='btn $css' href='#' onclick='$action$function_name()'>$label</div>";

        return $result;
    }

    public static function showPlusTableRow($model, $columns = [], $container = 'crud-datatable-pjax', $field_name = '{object}Search[{column}]', $is_search = true)
    {
        $modelName = BaseInflector::camelize($model);
        $r = '<tr style = "border:1px solid lightgrey; background-color: #eef1f5" id="' . $container . '-forms">';
        $cells = [];
        foreach ($columns as $column) {

            $jump = false;
            $field_id = $field_name;
            $field_id = str_replace('{object}', $modelName, $field_id);
            $field_id = str_replace('{column}', $column, $field_id);
            if (!isset($column)) {
                $control = '<td></td>';
            } else if ($column == 'action') {
                $control = '<td class="skip-export kv-align-center kv-align-middle">' . FHtml::buttonCreateAjax($model, true, $is_search, $container) . '' . '</td>';
            } else if ($column == 'null') {
                $control = '<td></td>';
            } else if ($column == 'group') {
                $control = '<td></td>';
                $jump = true;
            } else {
                $control = self::showEditor('', $column, '', $model, '');
                $control = '<td>' . str_replace('{control_name}', $field_id, $control) . '</td>';
            }
            if ($jump)
                continue;
            $cells[] = $control;
        }
        $r .= implode('', $cells); //implode('', $cells);
        $r .= '</tr>';

        return $r;
    }

    public static function showPlusFormDynamic($model, $columns = [], $container = 'crud-datatable-pjax', $field_name = '{object}Search[{column}]', $is_search = true)
    {
        if (is_object($model)) {
            $model = BaseInflector::camelize($model);
        }
        $modelName = BaseInflector::camelize($model);

        $r = '<div class="row" style = "margin:1px; padding:10px; border:1px solid lightgrey; background-color: #eef1f5">';
        foreach ($columns as $column) {
            $field_id = $field_name;
            $field_id = str_replace('{object}', $modelName, $field_id);
            $field_id = str_replace('{column}', $column, $field_id);
            $control = self::showEditor('', $column, '', $model, '');
            $control = str_replace('{control_name}', $field_id, $control);
            $size = '2';
            if (FHtml::isInArray($column, FHtml::getFIELDS_GROUP(), $model))
                $size = '1';
            else
                $size = '2';
            $r .= '<div class="col-md-' . $size . '">' . $control . '</div>';
        }

        $r .= '<div class="col-md-1 pull-right" style="padding-top:3px">' . FHtml::buttonCreateAjax($model, false, $is_search, $container) . '</div>';

        $r .= '</div>';

        return $r;
    }

    public static function showPlusFormView($model, $columns = [], $container = 'crud-datatable-pjax', $view = '_form')
    {
        $modelName = '';
        $object_type = '';
        if (is_string($model)) {
            $modelName = BaseInflector::camelize($model);
            $object_type = $model;
            $model = FHtml::createModel($object_type);
        } else if (is_object($model)) {
            $object_type = FHtml::getTableName($model);
            $modelName = BaseInflector::camelize($object_type);
        } else {
            return '';
        }
        $count = count($columns);
        $r = '<tr style = "border:1px solid lightgrey; background-color: #eef1f5" id="' . $container . '-forms"><td colspan="' . $count . '">';
        $r .= FHtml::render($view, '', ['model' => $model, 'modulePath' => FHtml::getUploadFolder($model), 'pjax_container' => $container, 'columns' => $columns]);
        $r .= '</td></tr>';

        return $r;
    }

    public static function getModelFormAttributes($model, $form, $fields = [])
    {
        $settings = [];
        foreach ($fields as $column => $column_settings) {
            if (self::is_numeric($column)) {
                $column = $column_settings;
                $column_settings = '';
            }
            if (empty($column_settings)) {
                $column_settings = ['value' => $form->fieldNoLabel($model, $column)->editor()];
            } else if (is_string($column_settings)) {
                $column_settings = ['value' => $form->fieldNoLabel($model, $column)->editor($column_settings)];
            }

            $settings = array_merge($settings, [$column => $column_settings]);
        }
        return $settings;
    }

    public static function getModelGridColumns($model, $fields = [], $table = '')
    {
        $settings = [];
        if (empty($table)) {
            $table = FHtml::getTableName($model);
        }

        foreach ($fields as $column => $column_settings) {
            if (self::is_numeric($column)) {
                $column = $column_settings;
                $column_settings = '';
            }
            if (empty($column_settings)) {
                $column_settings = ['class' => FHtml::COLUMN_EDIT, 'format' => 'inline', 'attribute' => $column];
            } else if (is_string($column_settings)) {
                $column_settings = ['class' => FHtml::COLUMN_EDIT, 'format' => 'inline', 'attribute' => $column];
            }

            $settings[] = $column_settings;
        }
        return $settings;
    }

    public static function showTags($tags, $url = '', $template = '<span class="label" style="font-size:120%; margin-right:10px; margin-bottom:15px; padding:5px; background-color: {color}"> {tag} </span>', $color = '#81C6B6')
    {
        if (empty($tags))
            return '';

        if (is_string($tags))
            $tags = FHtml::decode($tags);
        if (is_string($tags))
            $tags = [$tags => $tags];

        $arr = [];
        foreach ($tags as $id => $tag) {
            $url1 = $url;
            $tag = trim($tag);
            if (is_numeric($id))
                $id = $tag;
            $item = str_replace('{color}', $color, $template);
            $item = str_replace('{tag}', '#' . $tag, $item);
            if (!empty($url1)) {
                $url1 = FHtml::createUrl($url1, ['tag' => $id]);
                $item = "<a href='$url1' alt='$tag'>{$item}</a>";
            }

            $arr[] = $item;
        }

        return implode(' ', $arr);
    }

    //2017/04/05

    public static function showGroupHeader($label = null, $content = '')
    {
        $label = FHtml::t('common', $label);
        $label = $label . ' ' . $content;
        return "<div class='group-header' style=''>{$label}</div>";
    }

    //2017/11/3

    public static function saveModelAjax($controller, $model, $modelMeta = null, $controller_id = '')
    {
        $request = Yii::$app->request;
        $id = FHtml::getFieldValue($model, ['id']);

        if (empty($controller_id))
            $controller_id = FHtml::getTableName($model);
        /*
            *   Process for ajax request
            */
        $table = FHtml::getTableName($model);
        $title = FHtml::t($table, BaseInflector::camel2words($table)) . " #" . $id;
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($request->isGet) {
            return [
                'title' => $title,
                'content' => $controller->renderAjax('update', [
                    'model' => $model, 'modelMeta' => $modelMeta,
                ]),
                //                'footer'=> Html::button(FHtml::t('button', 'Close'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                //                    Html::button(FHtml::t('button','Save'),['class'=>'btn btn-primary','type'=>"submit"])
            ];
        } else if ($model->load($request->post()) && $model->save()) {
            return [
                'forceReload' => '#' . self::getPjaxContainerId($controller_id),
                'title' => $title,
                'content' => $controller->renderAjax('view', [
                    'model' => $model, 'modelMeta' => $modelMeta,
                ]),
                //                'footer'=> Html::button(FHtml::t('button','Close'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                //                    Html::a(FHtml::t('button','Update'),['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
            ];
        } else {
            return [
                'title' => $title,
                'content' => $controller->renderAjax('update', [
                    'model' => $model, 'modelMeta' => $modelMeta
                ]),
                //                'footer'=> Html::button(FHtml::t('button','Close'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                //                    Html::button(FHtml::t('button','Save'),['class'=>'btn btn-primary','type'=>"submit"])
            ];
        }
    }

    public static function showObjectConfigLink($model, $name_fields = FHtml::FIELDS_NAME)
    {
        $object_id = FHtml::getFieldValue($model, ['id']);
        $object_type = FHtml::getTableName($model);
        $name = FHtml::getFieldValue($model, $name_fields);
        if (is_array($name))
            $name = FHtml::encode($name);
        $link = !FHtml::isRoleModerator() ? '' : FHtml::createLink('object-config/view', ['object_id' => $object_id, 'object_type' => $object_type], BACKEND, '<span class="glyphicon glyphicon-cog"></span>', '_blank', '');
        return "$name &nbsp; $link";
    }

    public static function showJsonAsTable($jsonText = '')
    {
        if (is_array($jsonText))
            $arr = $jsonText;
        else if (is_string($jsonText))
            $arr = json_decode($jsonText, true);
        else
            $arr = [];

        $html = "";
        if ($arr && is_array($arr)) {
            $html .= self::showArrayAsTable($arr);
        }
        return $html;
    }

    public static function showArrayAsTable($arr, $layout_type = 'table', $translated = true, $key_value_seperator = ': ', $column_seperator = ', ', $row_seperator = '<br/> ', $background = '#fafbfc')
    {
        $str = '';
        if (is_object($arr))
            return self::showObjectAsTable($arr);

        if (!is_array($arr))
            return trim($arr, ",;");

        if (ArrayHelper::isIndexed($arr) && $layout_type == 'table') {
            $layout_type = '';
            $row_seperator = '<br/>';
        }

        if (is_array($layout_type)) {
            $str = "<table class='table table-bordered table-condensed table-stripped' width='100%' border='0px' cellpadding='30' cellspacing='0' style=''><tbody>";
            $str .= "<tr>";
            foreach ($layout_type as $field1 => $field2) {
                $field2 = ($translated ? FHtml::t('common', $field2) : $field2);
                $str .= "<td style='padding: 5px;border:1px dashed lightgray; background-color:$background'><b>$field2</b></td>";
            }
            $str .= "</tr>";

            foreach ($arr as $key => $val) {
                if (is_object($val)) {
                    if (!method_exists($val, 'asArray'))
                        continue;
                    $val = $val->asArray();
                }

                $str .= "<tr>";
                foreach ($layout_type as $field1 => $field2) {
                    if (is_array($val) && key_exists($field2, $val))
                        $value = $val[$field2];
                    else
                        $value = $field2;

                    //$value = $field2;

                    $str .= "<td style='padding: 5px;border:1px dashed lightgray;'>$value</td>";
                }
                $str .= "</tr>";
            }
            $str .= "</tbody></table>";
        } else if ($layout_type == 'table') {
            $str = "<table class='table table-bordered table-condensed table-stripped' width='100%' border='0px' cellpadding='30' cellspacing='0' style=''><tbody>";
            foreach ($arr as $key => $val) {
                if (is_object($val)) {
                    if (!method_exists($val, 'asArray'))
                        continue;
                    $val = $val->asArray();
                }

                $key = is_string($key) ? ($translated ? FHtml::t('common', $key) : $key) : '';
                $val = is_array($val) ? (self::showArrayAsTable($val, '')) : ($val);

                $str .= "<tr>";
                $str .= "<td class='col-md-3 col-xs-3' width='30%' style='padding: 5px;border:1px dashed lightgray; background-color:$background'><b>$key</b></td>";
                $str .= "<td style='padding: 5px; border:1px dashed lightgray; ' width='70%'>$val</td></tr>";
            }
            $str .= "</tbody></table>";
        } else if ($layout_type == 'line') {
            $str = '<div style="padding: 10px; margin-bottom: 10px; border:1px dashed lightgray"> ';
            foreach ($arr as $key => $val) {
                if (is_object($val)) {
                    if (!method_exists($val, 'asArray'))
                        continue;
                    $val = $val->asArray();
                }
                $key = is_string($key) ? ($translated ? FHtml::t('common', $key) : $key) : '';
                $val = is_array($val) ? (self::showArrayAsTable($val, $layout_type)) : ($val . $column_seperator);
                if (strpos($layout_type, "{") !== false) {
                    $str .= !empty($key) ? FHtml::strReplace($layout_type, ['{key}' => $key, '{value}' => $val]) : $val;
                } else {
                    $str .= (!empty($key) ? '<b>' . ucfirst($key) . '</b>' . $key_value_seperator : '') . $val;
                }
            }
            $str = trim($str, ",;");
            $str .= '</div>';
        } else {
            foreach ($arr as $key => $val) {
                if (is_object($val)) {
                    if (!method_exists($val, 'asArray'))
                        continue;
                    $val = $val->asArray();
                }

                $key = is_string($key) ? ($translated ? FHtml::t('common', $key) : $key) : '';
                $val = is_array($val) ? (self::showArrayAsTable($val, $layout_type) . $row_seperator) : (is_string($val) ? ($val . $column_seperator) : '');
                //$str .= '<div class="col-md-12" style="border:1px solid lightgrey; padding: 5px; margin: 5px">';
                if (strpos($layout_type, "{") !== false) {
                    $str .= (!empty($key) ? FHtml::strReplace($layout_type, ['{key}' => $key, '{value}' => $val]) : ($val));
                } else {
                    $str .= (!empty($key) ? '<b>' . ucfirst($key) . '</b>' . $key_value_seperator : '') . $val . '';
                }
                //$str .= '</div>';
            }
            $str = trim($str, ",;");
        }

        return trim($str, ",;");
    }

    public static function showObjectAsTable($changedItems, $columns = [], $fieldName = ['name'])
    {
        if (is_object($changedItems))
            $changedItems = FHtml::getFieldValue($changedItems, $fieldName);

        if (is_string($changedItems))
            $changedItems = FHtml::decode($changedItems);

        $result = '';
        foreach ($changedItems as $item) {
            $result .= "<tr>";
            $i = 0;

            foreach ($columns as $column) {
                $class = $i == 0 ? 'col-md-2' : '';
                $result .= "<td class='$class' style='padding: 10px;'>$item[$i]</td>";
                $i += 1;
            }
            $result .= "</tr>";
        }
        if (!empty($result))
            $result = "<table class='table table-bordered table-condensed' width='100%' border='1' cellpadding='30' cellspacing='0' style='border:1px solid lightgray'>$result</table>";
        return $result;
    }

    public static function showLangsMenu($showFlag = false, $color = '')
    {
        if (!FHtml::isLanguagesEnabled())
            return '';

        if (is_string($showFlag)) {
            $color = $showFlag;
            $showFlag = false;
        }

        if (empty($color))
            $color = FHtml::setting('backend_header_color', 'white');

        if (!$showFlag) {
            $lang = FHtml::currentLang();
            $lang_array = FHtml::applicationLangsArray();
            if (empty($lang_array))
                return '';
            $lang_name = isset($lang_array[$lang]) ? $lang_array[$lang] : $lang;
            $result = "<a style='color:$color !important;' href='javascript:;' class='dropdown-toogle' data-toogle='dropdown' data-hover='dropdown' data-close-others='true'><span class='username username-hide-mobile'>$lang_name</span></a>";
            if (count($lang_array) > 1) {
                $result .= "<ul class='dropdown-menu dropdown-menu-default'>";

                foreach ($lang_array as $lang_item => $lang_name) {
                    if (is_numeric($lang_item)) {
                        $lang_item = $lang_name;
                    }
                    $url = FHtml::currentUrl([FHtml::LANGUAGES_PARAM => $lang_item]);
                    $result .= "<li><a href='$url'>$lang_name<span class='pull-right'>$lang_item</span></a></li>";
                }
                $result .= "</ul>";
            }
            return $result;
        } else {
            //$id = self::currentApplicationId();
            $lang_array = FHtml::applicationLangsArray();
            if (empty($lang_array))
                return '';
            $result = '';
            if (count($lang_array) > 1) {
                foreach ($lang_array as $lang_item => $lang_name) {
                    if (is_numeric($lang_item)) {
                        $lang_item = $lang_name;
                    }
                    $url = FHtml::currentUrl([FHtml::LANGUAGES_PARAM => $lang_item]);
                    $image = FHtml::getImageUrl($lang_item . ".png", 'www/flag');
                    $result .= "<li >  <a href='$url' style='padding-right: 0'>  <img width='32' height='20' src='$image' ></a> </li>";
                }
            }
            return $result;
        }
    }

    public static function showEmptyMessage($model = null, $text = 'Sorry, there is no data available or we could not find any matches', $action = true)
    {
        if (is_bool($model)) {
            $action = $model;
            if ($model == false)
                return '<div style="color:darkgrey; padding:5px">' . FHtml::t('message', 'Empty data') . '</div>';
        }

        if (!empty($model))
            return '';

        if (is_string($model))
            $text = $model;

        $t = FHtml::t('message', $text);

        $t1 = '<div style="font-size: 40pt; text-align: center; padding-bottom: 30px"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></div>';
        $t = "<div style='color:darkgrey;font-size:20pt;text-align: center; padding:50px'> $t1 $t.</div>";
        if (is_bool($action) && $action && FHtml::isRoleModerator() && FHtml::currentZone() == BACKEND) {
            $t .= "<div style='text-align:center; padding-bottom:50px;'>" . self::buttonAction('<i class="glyphicon glyphicon-plus"></i>', 'Create First Record', 'create', 'success', false, true, '')  . "</div>";
        } elseif (is_string($action)) {
            $t .= "<div style='text-align:center; padding-bottom:50px;'>" . $action . "</div>";
        } elseif (is_array($action)) {
            $t .= "<div style='text-align:center; padding-bottom:50px;'>" . implode('&nbsp;&nbsp;', $action) . "</div>";
        }

        return $t;
    }


    public static function buttonCreate($icon = '<i class="glyphicon glyphicon-plus"></i>', $title = 'Create', $action = 'create', $color = 'success', $style = 'float:left; margin-right:10px;')
    {
        if ($icon === false) {
            $quick_add = false;
            $icon = '<i class="glyphicon glyphicon-plus"></i>';
        } else
            $quick_add = true;

        $button = self::buttonAction($icon, $title, $action, $color, false, true, $style);
        $form_enabled = FHtml::getRequestParam('form_enabled');
        //        if ($quick_add) {
        //            $title = FHtml::t('button', 'Quick Create');
        //            if (!$form_enabled) {
        //                $url = Url::current(['form_enabled' => !$form_enabled]);
        //                $button .= "<a class='btn btn-success' title = '$title' style='float:left; margin-left:-9px' href='$url' data-pjax='0'><i class='glyphicon glyphicon-plus-sign'></i></a>";
        //            } else {
        //                $url = Url::current(['form_enabled' => !$form_enabled]);
        //                $button .= "<a class='btn btn-default' title = '$title'  style='float:left; margin-left:-9px' href='$url' data-pjax='0'><i class='glyphicon glyphicon-remove-sign'></i></a>";
        //            }
        //        }

        return $button;
    }

    public static function showPageWidthScript()
    {
        $zoom = FHtml::settingPageWidth();
        if (!empty($zoom) && !in_array($zoom, ['full', '100%']))
            FHtml::currentView()->registerJs("document.body.style.zoom='$zoom';");
        return '';
    }

    /**
     * @param $icon
     * @return string
     */
    public static  function showIcon($icon)
    {
        if (StringHelper::startsWith($icon, 'fa'))
            return '<i class="fa ' . $icon . '" aria-hidden="true"></i>';
        if (StringHelper::startsWith($icon, 'glyphicon'))
            return '<span class="glyphicon ' . $icon . '" aria-hidden="true"></span>';
    }

    public static function showModelFieldToogle($model, $attribute, $content = '', $show_content_if_not_empty = false, $edit_label = '', $edit_icon = '')
    {
        if (is_object($model)) {
            $title = FHtml::showModelFieldValue($model, $attribute, '', '', false);
        } else {
            $title = $model;
            $content = $attribute;
        }

        if ((!isset($title) || $title === 0 || $title === 1 || $title === false || $title === true) && (StringHelper::startsWith($attribute, 'is_') || StringHelper::endsWith($attribute, 'enabled'))) {
            return $content;
        }

        if (!empty($title) && $show_content_if_not_empty)
            return $content;

        if (empty($edit_icon))
            $edit_icon = '<i class="fa fa-pencil hidden-print" style="font-size:80%;color:lightgrey" aria-hidden="true"></i>&nbsp;';

        if (empty($edit_label))
            $edit_label = FHtml::t('common', 'Edit');

        if (empty($title))
            $title = '<span style="color:lightgrey">' . $edit_label . '</span>';

        $id = empty($id) ? ('toogle-' . time() . rand(1, 100)) : $id;
        if (!empty($content))
            $result = @"<div id='label_$id' onclick=\"$('#content_$id').toggle(); $('#label_$id').hide(); \" class='col-md-12' style='cursor: pointer; padding: 15px' title='$edit_label'> $title &nbsp; $edit_icon</div>
                   <div id='content_$id' class='' style='display:none;'> 
                      $content 
                   </div>";
        else
            $result = @"<div class='' style=''>$title</div>";
        return $result;
    }

    public static function showToogleContent($title, $content, $id = '', $title_css = '', $title_style = '', $content_css = '', $content_style = 'fone-size:80%; background-color: #eef1f5; color: black; padding: 10px; margin-top:10px;')
    {
        $id = empty($id) ? ('toogle-' . rand(1, 200)) : $id;
        if (!empty($content))
            $result = @"<div onclick=\"$('#content_$id').toggle();\" class='$title_css' style='cursor: pointer; $title_style'>$title</div>
                   <div id='content_$id' class='$content_css' style='display:none; $content_style'> 
                      $content 
                   </div>";
        else
            $result = @"<div class='$title_css' style='$title_style'>$title</div>";

        return $result;
    }

    public static function showModalContent($title, $content, $id = '', $title_css = '', $title_style = '', $content_css = '', $content_style = 'fone-size:80%; background-color: #eef1f5; color: black; padding: 10px; margin-top:10px;')
    {
        $id = empty($id) ? rand(0, 1000) . time() : $id;
        $content = self::strReplace($content, ["'" => "\'"]);
        $result = @"<div onclick=\"alert('$content');\" class='$title_css' style='cursor: pointer; $title_style'>$title</div>
                   ";
        return $result;
    }

    public static function showToogleHtmlControl($input_id1 = '', $input_id2 = '', $content, $input_container_id = '', $title = '', $title_css = '', $title_style = '', $content_css = '', $content_style = '')
    {
        $id = empty($input_container_id) ? "$input_id1-container" : $input_container_id;

        if (empty($content)) {
            $title = $input_id2;
        }

        if (empty($title) && is_string($title))
            $title = '"<div style=\'padding-top:8px\'><i class=\'fa fa-pencil\'></i></div>"';

        if ($title !== false)
            $title = "<div onclick=\"$('#$id-hidden').toggle(); $('#$id').toggle();\" class='$title_css' style='cursor: pointer; $title_style'>$title</div>";

        if (empty($content)) {
            return $title;
        }

        $result = $title . "<div id='$id-hidden' class='$content_css' style='display:none; $content_style'> 
                      $content 
                   </div>";
        return $result;
    }

    public static function getWidgetID($view, $postfix = '-widget')
    {
        return str_replace(['/', ':', '.'], '', $view) . $postfix;
    }

    public static function showView($view, $params = [], $label = 'Click here', $type = '', $header = null, $footer = null, $label_class = 'btn btn-default')
    {
        if (is_array($label))
            $toggleButton = $label;
        else if (StringHelper::startsWith($label, '<'))
            $toggleButton = ['label' => $label];
        else
            $toggleButton = ['label' => $label, 'class' => $label_class];

        $widget = FWidget::begin([
            'options' => [
                'id' =>  self::getWidgetID($view),
                'tabindex' => false // important for Select2 to work properly
            ],
            'header' => $header, 'footer' => $footer, 'display_type' => $type,
            'toggleButton' => $toggleButton
        ]);

        try {
            echo self::renderView($view, $params);
            $widget->end();
        } catch (\Exception $e) {
            return FHtml::showErrorMessage($e);
        }

        return '';
    }

    public static function buttonModal($label, $url, $role = 'modal-remote', $css = 'btn btn-default', $confirm_title = null, $confirm_message = null, $method = 'get')
    {
        return self::showModalButton($label, $url, $role, $css, $confirm_title, $confirm_message, $method);
    }

    public static function showModal1($url)
    {
        $result = "<div class=\"modal inmodal\" id=\"modalCalendar\" tabindex=\"-1\" role=\"dialog\" aria-hidden=\"true\">
			    <div class=\"modal-dialog\">
			        <div class=\"modal-content animated fadeIn\">
			            <div class=\"modal-header\">
			                <button type=\"button\" class=\"close\" data-dismiss=\"modal\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
			            </div>
			            <div class=\"modal-body\">
			                <div id='modalContent'></div>
			                <iframe id='modalIframe' frameborder='0' width='100%' height='680px' scrolling='yes'></iframe>
			            </div>            
			        </div>
			    </div>
			</div>";

        $js = @"function clickModal() {
                $('#modalCalendar').modal('show');
                //$(\"#modalCalendar\").find(\"#modalContent\").load(\"{$url}?id=\" + event.id);
                $('#modalCalendar').find('#modalIframe').attr('src', '{$url}?layout=no');
            }
            ";
        FHtml::registerJs($js);
        return $result;
    }

    public static function buttonLink($icon = '', $title = '', $action = '', $color = 'default', $data_pjax = false,  $target = '', $options = [], $style = 'float:left; margin-right:10px;')
    {
        $view = FHtml::currentView();
        if (strpos($action, "/") !== false) {
            $url = $action;
        } else
            $url = [$action];

        if ($target == 'iframe') {
            $button = Html::button(
                $icon . (empty($title) ? '' : ('&nbsp;' . FHtml::t('button', $title))),
                array_merge([
                    'data-pjax' => $data_pjax ? 1 : 0,
                    'title' => FHtml::t('message', $title),
                    'class' => 'hidden-print btn btn-' . $color,
                    'onclick' => "showModalIframe(this)",
                    'style' => $style,
                    'data-url' => $url,
                    //'target' => $target
                ], $options)
            );
        } else if ($target == 'modal' || $target == 'modal-remote') {

            $button = Html::button(
                $icon . (empty($title) ? '' : ('&nbsp;' . FHtml::t('button', $title))),
                array_merge([
                    'data-pjax' => $data_pjax ? 1 : 0,
                    'title' => FHtml::t('message', $title),
                    'class' => 'hidden-print btn btn-' . $color,
                    'onclick' => "showModal(this)",
                    'style' => $style,
                    'data-url' => $url,
                ], $options)
            );
        } else {
            $button = FHtml::a(
                $icon . (empty($title) ? '' : ('&nbsp;' . FHtml::t('button', $title))),
                $url,
                array_merge([
                    'data-pjax' => $data_pjax ? 1 : 0,
                    'title' => FHtml::t('message', $title),
                    'class' => 'hidden-print btn btn-' . $color,
                    'style' => $style,
                    'target' => $target
                ], $options)
            );
        }

        return $button;
    }

    public static function showLinkButton($label, $url, $css = 'btn btn-default', $target = '_blank', $data_pjax = false)
    {
        return FHtml::buttonLink('', $label, $url, $css, $data_pjax, $target);
    }

    public static function showModalIframeButton($label, $url, $role = 'iframe', $css = 'btn btn-default', $confirm_title = null, $confirm_message = null, $method = 'get')
    {
        return self::showModalButton($label, $url, $role, $css, $confirm_title, $confirm_message, $method);
    }

    public static function showModalButton($label, $url, $role = 'modal-remote', $css = 'btn btn-default', $confirm_title = null, $confirm_message = null, $method = 'get')
    {
        $is_print = (FHtml::getRequestParam('view') == 'print');
        if (!empty($is_print))
            return $label;

        if (empty($css))
            $css = 'text-default';

        if ($role == 'iframe') {
            return FHtml::buttonLink('', $label, $url, $css, false, 'iframe');
            //return FHtml::renderModal($url, [], $label);
        }
        if (!in_array($role, ['modal-remote'])) {
            return FHtml::a(
                $label,
                $url,
                [
                    'data-pjax' => 0,
                    "class" => $css . ' hidden-print',
                    'target' => $role

                ]
            );
        }
        return FHtml::a(
            "$label",
            $url,
            [
                'role' => $role,
                "class" => $css . ' hidden-print',
                'data-confirm' => false, 'data-method' => false, // for overide yii data api
                'data-request-method' => $method,
                'data-confirm-title' => $confirm_title,
                'data-confirm-message' => $confirm_message,
            ]
        );
    }

    public static function showPanelContent($label, $content, $id = '', $title_css = '', $title_style = '', $content_css = '', $content_style = 'padding: 10px; margin-top:10px;')
    {
        $header = '<div class="portlet light bordered">
                        <div class="portlet-title tabbable-line hidden-print ' . $title_css . '">
                            <div class="caption caption-md">
                                 <span class="caption-subject font-blue-madison bold uppercase">' . $label . '</span>
                            </div>
                            <div class="tools pull-right">
                                <a href="#" class="fullscreen"></a>
                                <a href="#" class="collapse"></a>
                            </div>                            
                        </div>
                        <div class="portlet-body ' . $content_css . '" style="' . $content_style . '">
                            <div>';

        $footer = '         </div>
                        </div>
                   </div>';

        if ($content == 'header' || $content === true)
            return $header;
        else if ($content == 'footer' || $content === false)
            return $footer;
        else
            return $header . $content . $footer;
    }

    public static function renderPanel($view, $params = [], $label = 'Click here', $header = null, $footer = null, $label_class = 'btn btn-default')
    {
        return self::showView($view, $params, $label, FWidget::TYPE_PANEL, $header, $footer, $label_class);
    }

    public static function renderModal($view, $params = [], $label = 'Click here', $header = null, $footer = null, $label_class = 'btn btn-default')
    {
        if (is_array($view)) {
            return self::showModalButton($label, $view);
        }

        return self::showView($view, $params, $label, FWidget::TYPE_MODAL, $header, $footer, $label_class);
    }

    public static function renderToogle($view, $params = [], $label = 'Click here', $context = null)
    {
        $content = self::renderView($view, $params);
        return self::showToogleContent($label, $content, '', '', '', '', 'margin-top:20px; padding-top:10px; padding-bottom:20px;');
    }

    public static function showWizard($id, $items = [],  $buttons = [], $start_step = 0, $complete_content = false)
    {
        if (empty($items))
            return '';

        if (is_string($buttons) && !empty($buttons)) {
            return FWizard::widget(['id' => $id, 'steps' => $items, 'start_step' => $start_step, 'complete_content' => $complete_content, 'saveButton' => $buttons]);
        } else
            return FWizard::widget(['id' => $id, 'steps' => $items, 'start_step' => $start_step, 'complete_content' => $complete_content, 'buttons' => $buttons]);
    }

    public static function showTabsFull($id, $items = [])
    {
        if (empty($items))
            return '';
        return FWizard::widget(['id' => $id, 'steps' => $items, 'buttons' => false]);
    }

    public static function showNumberInFileSize($bytes)
    {
        return self::convertToKBytes($bytes);
    }

    public static function saveRequestPost($post = null)
    {
        if (!isset($post))
            $post = self::getRequestPost();

        if (!empty($post))
            FHtml::Session('LastPost', $post);
    }

    public static function getRequestPost()
    {
        $post = FHtml::Session('LastPost');

        if (isset($post)) {
            FHtml::DestroySession('LastPost');
            return $post;
        }

        return array_merge($_REQUEST, $_POST);
    }

    public static function showToogleHtmlTextArea($input_id, $label = 'html', $class = '')
    {
        $uploadPath = FHtml::getRootFolder() . '/applications/' . FHtml::currentApplicationId() . '/upload/editor';
        $js3 = @"function toogleHtml(name) { var editor = CKEDITOR.instances[name];
                if (editor) { editor.destroy(); return; 
            }
            CKEDITOR.editorConfig = function( config ) {
              config.uploadURL = '$uploadPath';
            };
            CKEDITOR.replace(name); };";

        $result = "<a href='#' class='$class' style='color:lightgrey' onclick='toogleHtml(\"$input_id\")'>$label</a>";

        FHtml::registerJs("$js3");


        CKEditorWidgetAsset::register(FHtml::currentView());

        return $result;
    }

    public static function showToogleMarkdownTextArea($input_id, $label = 'markdown', $class = '')
    {
        if (empty($input_id))
            return '';

        $baseUrl = Yii::$app->getUrlManager()->getBaseUrl();
        $editor_id = str_replace('-', '_', $input_id);

        $js = @"editor_$editor_id = editormd(name, {
                    width   : '100%',
                     //height   : '100%',
                    height  : 400,
                    syncScrolling : \"single\",
                    tex      : true,
                    htmlDecode : true,
                    flowChart : true,
                    taskList  : true,
                    sequenceDiagram : true,
                    //autoHeight           : true,
                    path    : \"{$baseUrl}/plugins/editor_md/lib/\"
                });
                        
                editormd.loadScript(\"{$baseUrl}/plugins/editor_md/languages/\" + 'en', function() {
                    editor_$editor_id.lang = 'en';
                    editor_$editor_id.recreate();
                });";

        $js3 = @"var editor_$editor_id; 
                function toogleMarkdown_$editor_id(name) {
                    if (editor_$editor_id) {
                        var textarea1 = editor_$editor_id.editor.children('textarea');
                        var id = textarea1.attr('id');
                        var name = textarea1.attr('name');
                        var css = 'form-control';
                        editor_$editor_id.editor.html('<textarea id = \"' + id + '\" name = \"' + name + '\" class = \"' + css + '\" style=\"width:100%;height:100%;border:none\">' + editor_$editor_id.editor.children('textarea').html() + '</textarea>');
                        editor_$editor_id = null;
                        return;
                    } else {
                        $js
                    }
                };";

        if (!empty($label)) {
            $result = "<a href='#' style='color:lightgrey' class='$class' onclick='toogleMarkdown_$editor_id(\"$input_id\")'>$label</a>";
            FHtml::registerJs("$js3");
        } else {
            $result = '';
            FHtml::registerReadyJs("var " . str_replace('(name,', "('$input_id',", $js));
        }

        FHtml::currentView()->registerCssFile($baseUrl . '/plugins/editor_md/css/editormd.css');
        FHtml::currentView()->registerJsFile($baseUrl . '/plugins/editor_md/editormd.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

        return $result;
    }

    public static function showModalButtonHtmlEditor($input_id, $url = true, $css = 'btn btn-xs btn-default', $label = 'html')
    {
        CKEditorWidgetAsset::register(FHtml::currentView());

        if (is_string($url)) {
            $url1 = $url;
            $url = true;
        } else {
            $url1 = FHtml::createUrl('site/editor', ['id' => $input_id]);
        }
        if (!$url)
            $url1 = FHtml::strReplace($url1, ['%7B' => '{', '%7D' => '}']);

        $result = FHtml::showModalButton($label, $url1, 'modal-remote', $css);
        return $result;
    }

    /**
     * @param        $text
     * @param int    $number_word
     * @param string $character_end
     * @return string
     */
    public static function showText($text, $number_word = 100, $character_end = "...")
    {
        $words = preg_split("/\s/", $text);
        $words = array_splice($words, 0, $number_word);
        if (str_word_count($text) > $number_word) {
            return implode(" ", $words) . $character_end;
        } else {
            return $text;
        }
    }

    /**
     * @param        $str
     * @param string $demiliter
     * @param bool   $tolower
     * @return mixed|null|string|string[]
     */
    public static function cleanStringVietnamese($str, $demiliter = '-', $tolower = false)
    {
        $unicode = array(

            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',

            'd' => 'đ',

            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',

            'i' => 'í|ì|ỉ|ĩ|ị',

            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',

            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',

            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',

            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',

            'D' => 'Đ',

            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',

            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',

            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',

            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',

            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',

        );

        foreach ($unicode as $nonUnicode => $uni) {
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }

        $str = str_replace(' ', $demiliter, $str);

        if ($tolower)
            $str = strtolower($str);

        return $str;
    }


    /**
     * @param bool $url
     * @return null|string
     */
    public static function showTwitter($url = true)
    {
        if (empty($result))
            $result = FConfig::settingCompanyTwitter();
        if ($url && !empty($result))
            $result = FHtml::getTwitterLink($result);
        return $result;
    }

    /**
     * @param bool $url
     * @return null|string
     */
    public static function showGoogle($url = true)
    {
        if (empty($result))
            $result = FHtml::settingCompanyGoogle();
        if ($url && !empty($result))
            $result = FHtml::getGoogleLink($result);
        return $result;
    }

    /**
     * @param bool $url
     * @return mixed|null|string
     */
    public static function showPhone($result = '', $url = true, $append = '<i class="fa fa-phone" aria-hidden="true"></i> Phone:')
    {
        if (empty($result))
            $result = FHtml::settingCompanyPhone();

        if (!empty($result)) {
            $result = str_replace(' ', '', $result);
            if (!StringHelper::startsWith($result, '+'))
                $result = '+' . $result;
            $link = "tel:$result";
            if ($url && !empty($link))
                $result = "<a href='$link' target='_blank'>$append $result</a>";
            else
                $result = $link;
        }

        return $result;
    }

    public static function showWhatsapp($result = '', $url = true, $append = '<i class="fa fa-whatsapp" aria-hidden="true"></i> Whatsapp:', $text = 'Hello')
    {
        if (is_string($url)) {
            $text = $url;
            $url = true;
        }

        if (empty($result))
            $result = FHtml::settingApplication('whatsapp', '', [], 'Config');
        if (empty($result))
            $result = FHtml::settingCompanyPhone();

        if (!empty($result)) {
            $result = str_replace(' ', '', $result);
            if (StringHelper::startsWith($result, '+'))
                $result = str_replace('+', '', $result);
            $link = FHtml::getWhatsappChatLink($result, $text);
            if ($url && !empty($link))
                $result = "<a href='$link' target='_blank'>$append $result</a>";
            else
                $result = $link;
        }

        return $result;
    }

    public static function showHotline($result = '', $url = true, $append = '<i class="fa fa-phone" aria-hidden="true"></i> Phone:')
    {
        if (empty($result))
            $result = FHtml::settingCompanyPhone();

        if (!empty($result)) {
            $result = str_replace(' ', '', $result);
            if (!StringHelper::startsWith($result, '+'))
                $result = '+' . $result;

            $link = "tel:$result";

            if ($url)
                $result = "<a href='$link' target='_blank'>$append $result</a>";
            else
                $result = $link;
        }
        return $result;
    }

    /**
     * @param bool $url
     * @return null|string
     */
    public static function showSkype($result = '', $url = true, $append = '<i class="fa fa-skype" aria-hidden="true"></i> Skype:', $text = 'Hello')
    {
        if (is_string($url)) {
            $text = $url;
            $url = true;
        }

        if (empty($result))
            $result = FHtml::settingCompanyChat();

        $link = "skype:$result?chat&text=$text";

        if (!empty($result) && $url)
            $result = "<a href='$link'>$append $result</a>";
        else
            $result = $link;

        return $result;
    }

    /**
     * @return null|string
     */
    public static function showAddress($result = '')
    {
        if (empty($result))
            $result = FHtml::settingCompanyAddress();
        return $result;
    }

    /**
     * @param bool $url
     * @return null|string
     */
    public static function showEmail($result = '', $url = true, $append = '<i class="fa fa-envelope" aria-hidden="true"></i> Email:', $text = 'Hello')
    {
        if (is_string($url)) {
            $text = $url;
            $url = true;
        }

        if (empty($result))
            $result = FHtml::settingCompanyEmail();

        $link = "mailto:$result";

        if (!empty($result) && $url)
            $result = "<a href='$link' target='_blank'>$append $result</a>";
        else
            $result = $link;

        return $result;
    }

    /**
     * @param bool $url
     * @return null|string
     */
    public static function showWebsite($result = '', $url = true, $append = '')
    {
        if (empty($result))
            $result = FHtml::settingCompanyWebsite();
        if (!empty($result) && $url)
            $result = "<a href='$result' target='_blank'>$append $result</a>";
        return $result;
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
     * @param        $relation
     * @param string $field
     * @param string $default_value
     * @return mixed|string
     */
    public static function getRelationName($relation, $field = 'name', $default_value = '')
    {
        if ($relation instanceof ActiveQuery) {
            $relation = $relation->one();
        }

        return isset($relation) ? $relation->{$field} : $default_value;
    }

    /**
     * @param        $relation
     * @param string $field
     * @param string $default_value
     * @return mixed|string
     */
    public static function getRelationNames($relation, $field = 'name', $default_value = '')
    {
        if ($relation instanceof ActiveQuery) {
            $relation = $relation->all();
        }

        return isset($relation) ? implode(array_column($relation, $field), ', ') : $default_value;
    }

    public static function getTreeViewNodeName($model, $as_html = true, $has_tree_node = true)
    {
        $model_level = FHtml::getFieldValue($model, ['tree_level', 'level']);
        $model_index = FHtml::getFieldValue($model, ['tree_index', 'index']);
        $name = FHtml::getFieldValue($model, ['name', 'title']);

        if ($model_level == 0) {
            $level = '';
            $css = "text-transform:uppercase; font-weight:bold;";
            $tag = $has_tree_node ? "h2" : '';
        } else if ($model_level == 1) {
            $level = '<small style="color:white">' . str_repeat('&nbsp;&nbsp;', $model_level) . "</small><small style='color:lightgrey'>└ " . '</small>&nbsp;';
            $css = "font-weight: bold;";
            $tag = $has_tree_node ? "h3" : '';
        } else {
            $css = "";
            $tag = "";
            $level = '<small style="color:white">' . str_repeat('&nbsp;&nbsp;', $model_level) . "</small><small style='color:lightgrey'> └ " . '</small>&nbsp;';
        }

        if (!$has_tree_node) {
            $level = '';
            $model_index = '';
        }

        if ($as_html) {
            return (empty($model_index) ? '' : ($level . $model_index . '. ')) . "<span style='$css'>" . $name . "</span>";
        } else
            return str_repeat('__', $model_level) . $name;
    }
}
