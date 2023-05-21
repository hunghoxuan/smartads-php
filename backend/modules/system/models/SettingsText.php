<?php

namespace backend\modules\system\models;

use common\components\FFile;
use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**



 * This is the customized model class for table "settings_text".
 */
class SettingsText extends BaseModel
{
    public $id;
    public $name;
    public $content;
    public $lang;
    public $application_id;

    public static function getTableSchema()
    {
        return [];
    }

    public function attributes()
    {
        return [];
    }

    public static function className()
    {
        return '';
    }

    public static function findOne($condition, $applications_enabled = true)
    {
        $model = new SettingsText();
        $application_id = FHtml::currentApplicationId();
        $lang = FHtml::currentLang();

        $content = FHtml::includeFile("applications/$application_id/messages/$lang/common.php");
        $content1 = FHtml::includeFile("common/messages/$lang/common.php");
        $content = array_merge($content1, $content);
        $arr = FHtml::decode($content);
        $result = [];
        if (is_array($arr)) {
            foreach ($arr as $key => $value) {
                $result[] = ['key' => $key, 'value' => $value];
            }
            $model->content = $result;
        }

        $model->lang = $lang;
        $model->name = 'common';

        return $model;
    }

    function save($runValidation = true, $attributeNames = null)
    {
        $application_id = FHtml::currentApplicationId();
        $lang = FHtml::currentLang();

        $filename = "applications/$application_id/messages/$lang/common.php";
        if (is_array($this->content)) {
            $content = "<?php return [\n";

            foreach ($this->content as $i => $item) {
                $key = $item['key'];
                $value = $item['value'];
                $value = str_replace("'", "", $value);
                $content .= "'$key' => '$value', \n";
            }
            $content .= "]; ?>";
            FFile::createFile($filename, $content);
        }

        return false;
    }

    function load($data = null, $formName = null)
    {
        if (key_exists('SettingsText', $_POST)) {
            $this->content = $_POST['SettingsText']['content'];
            return true;
        }
        return false;
    }
}
