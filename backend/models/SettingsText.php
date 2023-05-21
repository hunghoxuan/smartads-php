<?php

namespace backend\models;

use common\components\FFile;
use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseDataObject;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/**



 * This is the customized model class for table "settings_text".
 */
class SettingsText extends \common\models\BaseModel
{
    public $id;
    public $name;
    public $content;
    public $lang;
    public $group;
    public $application_id;

    public static function getTableSchema($db = null)
    {
        return null;
    }

    public function getTableName()
    {
        return 'settings_text';
    }

    public function attributes()
    {
        return [];
    }


    public static function findAll($condition = [], $order_by = [], $page_size = -1, $page = 1, $isCached = false, $display_fields = [], $asArray = false, $load_activeonly = true)
    {
        if (self::isSqlDb())
            return parent::findAll($condition, $order_by, $page_size, $page, $isCached, $display_fields, $asArray, $load_activeonly);

        $lang = FHtml::currentLang();
        $arr = self::getApplicationTranslations(); //get directly from File, no Cache
        $result = [];
        $i = 1;

        if (key_exists('SettingsTextSearch', $condition))
            $condition = $condition['SettingsTextSearch'];

        $name = FHtml::getFieldValue($condition, 'name');
        $content = FHtml::getFieldValue($condition, 'content');

        foreach ($arr as $key => $value) {
            if ((empty($name) || strpos(strtolower($key), strtolower($name)) !== false) && (empty($content) || strpos(strtolower($value), strtolower($content)) !== false)) {

                $model = new SettingsText();
                $model->name = $key;
                $model->content = $value;
                $model->lang = $lang;
                $model->id = $i;
                $model->group = $model->getGroupName();

                $result[] = $model;
            }

            $i += 1;
        }
        return $result;
    }

    public function getGroupName($name = '')
    {

        $a = empty($name) ? $this->name : $name;
        $arr = explode('.', $a);
        return (count($arr) > 1) ? $arr[0] : $a;
    }

    public static function deleteAll($condition = '', $params = [])
    {
        FHtml::deleteApplicationTranslations();
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        if (self::isSqlDb())
            return parent::save($runValidation, $attributeNames);

        $lang = FHtml::getRequestParam(['language', 'lang'], FHtml::currentLang());

        if (!empty($_POST) && key_exists('SettingsText', $_POST)) {
            $arr = $_POST['SettingsText'];

            foreach ($arr as $key => $value) {
                if (StringHelper::startsWith($key, '_')) {
                    FHtml::saveApplicationTranslations([$arr['name'] => $arr[$key]], null, str_replace('_', '', $key));
                }
            }
        } else {
            FHtml::saveApplicationTranslations([$this->name => $this->content], null, $lang);
        }

        if (empty($this->id))
            $this->id = $this->name;

        return true;
    }

    public static function findOne($condition, $selected_fields = [], $asArray = false, $applications_enabled = true)
    {
        if (self::isSqlDb())
            return parent::findOne($condition);

        if (!is_array($condition)) {
            $params = self::getApplicationTranslations();
            $i = 1;
            foreach ($params as $key => $value) {
                if ((is_numeric($condition) && $i == $condition) || (is_string($condition) && $key == $condition)) {
                    $model = new SettingsText();
                    $model->name = $key;
                    $model->content = $value;
                    $model->id = $condition;
                    $model->group = $model->getGroupName();
                    return $model;
                }
                $i += 1;
            }
        }

        return null;
    }

    public static function getDb()
    {
        return 'text';
    }

    function load($data = null, $formName = null)
    {
        if (key_exists('SettingsText', $_POST)) {
            $arr = $_POST['SettingsText'];
            parent::load($arr);
            return true;
        }
        return false;
    }

    public static function getApplicationTranslations($isCached = false)
    {
        return FHtml::getApplicationTranslations(FHtml::currentLang(), $isCached);
    }
}
