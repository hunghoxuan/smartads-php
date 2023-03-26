<?php
namespace backend\models;


/**
 * @property integer $id
 * @property string $country_code
 * @property string $country_name
 * @property integer $is_active
 */
class UtilityCountryBase extends \common\models\BaseModel
{

    /**
     * @inheritdoc
     */
    public $tableName = 'utility_country';

    public static function tableName()
    {
        return 'utility_country';
    }

}