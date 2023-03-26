<?php

namespace common\base;


use common\components\FActiveDataProvider;
use common\components\FActiveQueryPHPFile;
use common\components\FConfig;
use common\components\FConstant;
use common\components\FFile;
use common\components\FFrontend;
use common\components\FHtml;
use common\components\FModel;
use yii\db\ActiveRecord;
use yii\helpers\BaseInflector;
use yii\helpers\StringHelper;

/**
 * Class BaseDataObject
 * @package common\base
 */
class BasePHPObject extends BaseArrayObject
{
    public static function getDbType()
    {
        return FConstant::DB_TYPE_PHP;
    }

    public static function getDb()
    {
        $application_id = FHtml::currentApplicationId();
        $table = static::tableName();
        $folder = "applications" . DS . $application_id . DS . "data" . DS . $table;
        FFile::createDir(FHtml::getRootFolder() . DS . $folder);
        return $folder . DS . "all.php";
    }

    public static function loadData($file)
    {
        return FHtml::includeFile($file);
    }

    public static function saveData($file, $data)
    {
        return FHtml::saveConfigFile($file, $data);
    }
}
