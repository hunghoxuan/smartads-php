<?php
/**
 * Created by PhpStorm.
 * User: Darkness
 * Date: 11/30/2016
 * Time: 2:00 PM
 */

namespace common\components;


use common\components\FConstant;
use common\config\FSettings;
use yii\base\Exception;
use Yii;
use yii\base\UnknownPropertyException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\VarDumper;


class FError extends FContent
{

    public static function getErrorMessage($errors)
    {
        if (!is_array($errors))
            return static::getErrorMsg($errors);

        $error_message = 'FAIL';
        $error_array = array();
        foreach ($errors as $field => $messages) {
            foreach ($messages as $message) {
                $error_array[] = $message;
            }
        }
        if (count($error_array) != 0) {
            $error_message = implode('. ', $error_array);
        }
        return $error_message;
    }

    public static function getErrorMsg($code = '')
    {
        $str = static::getErrorsArray($code);
        if ($code == 'all' || empty($code) || is_array($code))
            return $str;
        else
            return isset($str[$code]) ? $str[$code] : '';
    }

    public static function getErrorsArray($code = '') {
        $errors = static::ERRORS_ARRAY;

        if ($code == 'all' || empty($code))
            return $errors;

        if (!is_array($code) && !empty($code))
            $arr = [$code];
        else if (is_array($code))
            $arr = $code;
        else
            $arr = [];

        $result = [];

        foreach ($arr as $item) {
            if (key_exists($item, $errors))
                $result[$item] = $errors[$item];
        }

        return $result;
    }

    public static function showMessage($message = null, $type = 'message', $description = '', $isGrowl = true, $delay = 10000)
    {
        if (Yii::$app->session->hasFlash($type) && empty($message))
            $message = Yii::$app->session->getFlash($type);

        if (empty($message))
            return '';

        $result = [];
        if (!isset($message))
            $result = [];

        if (is_string($message))
            $result[] = FHtml::showToogleContent($message, $description);

        if (is_array($message)) {
            foreach ($message as $i => $message_item) {
                if (!is_string($message_item))
                    $message_item = FHtml::encode($message_item);
                $result[] = $message_item;
            }
        }

        if (is_object($message)) {
            if (self::settingShowError() && $message instanceof \Throwable)
                throw $message;

            $url = FHtml::currentUrl(['show_error' => true, '_show_error' => true]);

            $show_error = FHtml::showModalButton(FHtml::t('common', 'Detail'), $url, '_blank');

            $description = empty($description) ? FHtml::showArrayAsTable(self::logError($message)) : $description;
            $description = $description . $show_error;
            $message = $message->getMessage();

            if (!empty($message)) {
                $result[] = FHtml::showToogleContent($message, $description);
            }
        }

        if (!empty(FHtml::getRequestParam($type)))
            $result[] = FHtml::getRequestParam($type);


        if (empty($result))
            return '';

        foreach ($result as $i =>  $item)
        {
            if (empty($item))
                unset($result[$i]);
        }

        $message = implode('<br/>', $result);

        if (empty($type))
            return "<div class='alert alert-success hidden-print' style='margin-top:20px;margin-bottom:20px'>$message</div>";

        if ($type == 'message' || $type == 'messages')
            $type = 'success';
        else if ($type == 'error' || $type == 'errors')
            $type = 'danger';
        else
            $type = 'info';

        return FHtml::showAlert($message, 'alert-' . $type, $isGrowl, $delay);
    }

    public static function showErrorMessage($message = null, $description = '')
    {
        return self::showMessage($message, 'error', $description);
    }

    public static function showCurrentMessages() {
        $result = '';

        $result .= self::showMessage(null, 'message');
        $result .= self::showMessage(null, 'error');

        FError::clearMessages();
        echo $result;
        return $result;
    }

    public static function addError($errors, $message = '', $seperator = '<br/>') {

        if (!isset($errors) || empty($errors))
            return '';

        $is_api = FHtml::currentController() == 'api';

        $result = '';
        if (is_object($errors)) {//if parse Exception
            $message = $errors->getMessage() . ': ' . $message;

            $result = FHtml::showErrorMessage($errors);
        } else if (is_array($errors)) {
            $arr = [];
            foreach ($errors as $key => $message1) {
                if (is_array($message1))
                    $message1 = implode($seperator, $message1);
                $result .= "<i class=\"fa fa-times\" aria-hidden=\"true\"></i> " . $message1 . $seperator;
                $arr[] = $message1;
            }
            $message = implode('; ', $arr) . ' ' . $message;
        } else if (is_string($errors)) {
            $message = $errors . ' ' . $message;
            $result = "<div style='color:red'>$errors</div>"; //FHtml::showErrorMessage($errors);
        }

        if ($is_api) {
            var_dump(['message' => $message, 'code' => FApi::ERROR_FAIL, 'status' => FApi::ERROR]);
            die;
        }

        if (FHtml::isAjaxRequest()) {
            return $result;
        }

        FHtml::Session()->addFlash('error', $result);
        return false;
    }

    public static function logError($errors = null) {

        if ($errors instanceof UnknownPropertyException) {
            //may be handle some actions here, like auto create column
        }

        $result = [];
        if (is_object($errors)) {
            $trace = $errors->getTraceAsString();
            $result = [
                'File' => $errors->getFile() . ' (line: ' . $errors->getLine() . ')',
                'Trace' => str_replace('#', '<br/>#', substr($trace, 0, strpos($trace, 'base\View.php')))
            ];
        } elseif (is_string($errors) && !empty($errors)) {
            $result = [
                'Message' => $errors,
            ];
        }

        $result = array_merge($result,  [
            'Called Class' => get_called_class(),
            'URL' => FHtml::currentPageURL(),
            'IPAddress' => FHtml::currentIPAddress(),
            'User' => FHtml::currentUsername() . ' (Id: ' . FHtml::currentUserId() . ')',
            'Time' => date('Y-m-d H:i:s')
        ]);

        return $result;
    }

    public static function log($content, $type = 'message')
    {
        self::addLog($content);
    }

    public static function addLog($content, $type = 'message') {
        $logs = self::currentLog();
        $title = date('Y-m-d h:i') . ' / module: ' . FHtml::currentModule() . ' / controller: '. FHtml::currentController() . ' / action: ' . FHtml::currentAction() . ' / object: ' .(is_object($content)? FHtml::getTableName($content) : '');
        $content = VarDumper::dumpAsString($content);

        $newlog = "<small style='color:grey'>$title</small> <br/> <pre>$content</pre><br/>";
        $logs = $newlog . $logs;
        FHtml::Session()['logs'] = $logs;
    }

    public static function clearLog() {
        FHtml::Session()->remove('logs');
    }

    public static function currentLog() {
        $logs = '';
        if (FHtml::Session()->has('logs'))
            $logs = FHtml::Session('logs');
        return $logs;
    }

    public static function addMessage($errors) {
        if (empty($errors))
            return;
        $result = '';

        if (is_array($errors))
        {
            foreach ($errors as $key => $message) {
                $result .= $message . '; ';
            }
        } else
            $result = $errors;

        FHtml::Session()->addFlash('message', $result);
    }

    public static function clearMessages() {
        FHtml::Session()->removeFlash('error');
        FHtml::Session()->removeFlash('message');
    }

    public static function logObjectActions($model, $action, $oldContent = [], $attributes = [], $name = '') {
        if (!FHtml::isTableExisted('object_actions'))
            return false;

        $object_model = FHtml::createModel('object_actions');
        $changedContent = [];
        foreach ($attributes as $field => $value) {
            if (key_exists($field, $oldContent))
                $old_value = $oldContent[$field];
            else
                $old_value = '';

            if ($old_value != $value) {
                $changedContent[] = [$field, $old_value, $value];
                //$changedContent = array_merge($changedContent, [$field => ['old' => $old_value, 'new' => $value]]);
            }
        }

        if (isset($object_model)) {
            if (empty($name)) {
                $name = strtoupper($action) . '. Changed fields: ';
                if (!empty($changedContent)) {
                    foreach ($changedContent as $item) {
                        $name .= $item[0] . ', ';
                    }
                }
            }

            // long test with postgres sql
	        $columns = $object_model->getAttributes();

	        ArrayHelper::remove($columns, 'id');

	        $columns = array_combine(array_keys($columns), [
		        !empty(FHtml::getFieldValue($model, ['id'])) ? FHtml::getFieldValue($model, ['id']) : 0,
		        $model->tableName,
		        $name,
		        FHtml::encode($oldContent),
		        FHtml::encode($oldContent),
		        $action,
		        1,
		        FHtml::Now(),
		        FHtml::currentUserId(),
		        FHtml::currentApplicationCode()
	        ]);

	        return FHtml::currentDb()->createCommand()->insert($object_model::tableName(), $columns)->execute();

//            $object_model->application_id = FHtml::currentApplicationCode();
//            $object_model->created_date = FHtml::Now();
//            $object_model->created_user = FHtml::currentUserId();
//            $object_model->action = $action;
//            $object_model->is_active = 1;
//            $object_model->old_content = FHtml::encode($oldContent);
//            $object_model->content = FHtml::encode($changedContent); //FHtml::encode($attributes);
//            $object_model->name = $name;
//            $object_model->object_type = $model->tableName;
//            $object_model->object_id = !empty(FHtml::getFieldValue($model, ['id'])) ? FHtml::getFieldValue($model, ['id']) : 0;
//            return $object_model->save();
        }

        return false;
    }

    public static function showObjectActions($changedItems, $columns = [], $fieldName = ['content']) {
        if (is_object($changedItems))
            $changedItems = FHtml::getFieldValue($changedItems, $fieldName);

        if (is_string($changedItems))
            $changedItems = FHtml::decode($changedItems);

        $result = '';
        foreach ($changedItems as $item) {
            $field = $item[0];
            $old_value = $item[1];
            $new_value = $item[2];
            $new_value = str_replace($old_value, "<span style='color:grey'>$old_value</span>", $new_value);
            $result .= "<tr><td class='col-md-2'>$field</td><td class='col-md-5'>$old_value</td><td class='col-md-5'>$new_value</td></tr>";
        }
        if (!empty($result))
            $result = "<table class='table table-bordered table-condensed'>$result</table>";
        return $result;
    }

    /**
     * @return null|string
     */
    public static function settingShowError()
    {
        return FHtml::settingApplication('show_error', SHOW_ERROR);
    }

    public static function console_log($data)
    {
        $time = date('Ymd H:i:s');
        $controller = FHtml::currentController();
        if (is_array($data) || is_object($data)) {
            $data = json_encode($data);
        }

        echo(@"<script>
            if(console.debug!='undefined'){
                console.log('$time: $data');
            }</script>");

    }
}