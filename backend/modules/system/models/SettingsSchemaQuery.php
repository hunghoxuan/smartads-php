<?php

namespace backend\modules\system\models;

/**
 * This is the ActiveQuery class for [[SettingsSchema]].
 *
 * @see SettingsSchema
 */
class SettingsSchemaQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return SettingsSchema[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SettingsSchema|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
