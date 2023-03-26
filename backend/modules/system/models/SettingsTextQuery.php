<?php

namespace backend\modules\system\models;

/**
 * This is the ActiveQuery class for [[SettingsText]].
 *
 * @see SettingsText
 */
class SettingsTextQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return SettingsText[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SettingsText|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
