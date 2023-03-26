<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "SettingsText".
*/

use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use common\components\CrudAsset;
use common\widgets\BulkButtonWidget;
use common\components\FHtml;
use common\components\Helper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SettingsTextSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'SettingsText';
$moduleTitle = 'Settings Text';
$moduleKey = 'settings-text';
$object_type = 'settings-text';

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'] = [];
$this->params['breadcrumbs'][] = $this->title;

$this->params['toolBarActions'] = array(
'linkButton'=>array(),
'button'=>array(),
'dropdown'=>array(),
);
$this->params['mainIcon'] = 'fa fa-list';

CrudAsset::register($this);

$currentRole = FHtml::getCurrentRole();
$viewType = FHtml::getRequestParam('view');
$gridControl = FHtml::settingPageView('_index');

?>
<div class="col-md-12">
    <div class="col-md-3">
        <?php
        $arr = Yii::$app->getI18n()->translations;
        $files = [];
        $lang = FHtml::currentLang();
        $application_id = FHtml::currentApplicationId();
        $file1 = "applications/$application_id/messages/$lang/common.php";
        $content = \common\components\FFile::readFile($file1);
        if (!empty($content))
            $files[] = $file1;

        $file1 = "common/messages/$lang/common.php";
        $content = \common\components\FFile::readFile($file1);
        if (!empty($content))
            $files[] = $file1;

        var_dump($files);
        ?>
    </div>
    <div class="col-md-9">
        <?php
        $content = \common\components\FFile::readFile('common/messages/vi/common.php');
        var_dump($content);
        ?>
    </div>
</div>

