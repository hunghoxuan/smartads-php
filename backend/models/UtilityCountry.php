<?php

namespace backend\models;

use Yii;

/**
 */
class UtilityCountry extends UtilityCountryBase
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_active'], 'integer'],
            [['country_code'], 'string', 'max' => 2],
            [['country_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('utility', 'ID'),
            'country_code' => Yii::t('utility', 'Country Code'),
            'country_name' => Yii::t('utility', 'Country Name'),
            'is_active' => Yii::t('utility', 'Is Active'),
        ];
    }
}