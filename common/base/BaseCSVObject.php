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
class BaseCSVObject extends BaseArrayObject
{
    public static function getDbType()
    {
        return FConstant::DB_TYPE_CSV;
    }

    public static function getDb()
    {
        $application_id = FHtml::currentApplicationId();
        $table = static::tableName();
        $folder = "applications\\$application_id\\data\\$table";
        FFile::createDir(FHtml::getRootFolder() . '\\' . $folder);
        return "$folder\all.csv";
    }

    public static function loadData($file)
    {
        return FModel::findArrayFromCSV($file);
    }

    public static function saveData($file, $data)
    {
        return FFile::saveToCSV($data, $file);
    }
}
