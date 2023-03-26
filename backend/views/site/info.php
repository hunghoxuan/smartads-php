<?php
/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "Book".
 */
use common\components\FHtml;
use common\widgets\FActiveForm;
use common\widgets\FFormTable;
use common\widgets\formfield\FormObjectAttributes;
use common\widgets\formfield\FormObjectFile;
use kartik\form\ActiveForm;
use kartik\money\MaskMoney;
use yii\widgets\Pjax;

$form_Type = $this->params['activeForm_type'];

$moduleName = 'SETUP';
$moduleTitle = 'Setup';
$moduleKey = 'setup';

$currentRole = FHtml::getCurrentRole();
$currentAction = FHtml::currentAction();

if (! function_exists('get_absolute_path')) {

// Function to get object from a relative path to this script
    function get_absolute_path($path = null)
    {
        if (empty($path)) $path = dirname(__FILE__);
        $path = str_replace('\\', '/', $path);
        $parts = array_filter(explode('/', $path), 'strlen');
        $absolutes = array();
        foreach ($parts as $part) {
            if ('.' == $part) continue;
            if ('..' == $part) {
                array_pop($absolutes);
            } else {
                $absolutes[] = $part;
            }
        }
        return ((substr(PHP_OS, 0, 3) == 'WIN') ? '' : '/') . implode('/', $absolutes);
    }
}


if (! function_exists('return_bytes')) {

    function return_bytes($val)
    {
        $val = 1024;
        $val = trim($val);
        switch (strtolower($val[strlen($val) - 1])) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        return $val;
    }
}

$arr = \common\components\FSystem::getSystemInfo();

/* @var $this yii\web\View */
/* @var $model backend\modules\book\models\Book */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="row">
        <div class="col-md-4">
            <h2>System Requirements</h2>
            <?php \common\components\FSystem::showCheckArray(\common\components\FSystem::getSystemInfo()) ?>
        </div>

        <div class="col-md-8">
            <h2>Files & Permissions</h2>
            <?php \common\components\FSystem::showCheckArray(\common\components\FSystem::getFilesPermissions()) ?>

            <h2>Database Connection Check</h2>
            <?php $ok = \common\components\FConfig::checkDbConnection();
                $db = '';
                echo '<b>' . FHtml::getConfigFile() . '] </b>: <br/>';
                echo '  - Master [Db]: ' . \common\components\FConfig::getConfigDsn();
                if (isset($ok) && !empty($ok))
                    echo ': <span class="ok">[Active]</span>';
                else
                    echo ': <span class="error">[Invalid]</span>';
                echo '<br/>';

                $application_id = FHtml::currentApplicationId();
                echo '  - Application [' . $application_id .  ']: ' . \common\components\FConfig::getConfigDsn($application_id);
                if (isset($ok) && !empty($ok))
                    echo ': <span class="ok">[Active]</span>';
                else
                    echo ': <span class="error">[Invalid]</span>';
                echo '<br/>';
            ?>
        </div>
    </div>
<div class="row">
    <div class="col-md-4">
        <h2>OpCache Status</h2>
        <?= function_exists('opcache_get_status') ? FHtml::var_dump(opcache_get_status(false)) : '<span class="error">Opcache is disabled. Please Enable Opcache</span>' ?>

    </div>

    <div class="col-md-8">

        <h2>OpCache Configuration</h2>
        <?= function_exists('opcache_get_configuration') ? FHtml::var_dump(opcache_get_configuration()) : '<span class="error">Opcache is disabled</span>' ?>

    </div>
</div>