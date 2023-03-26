<?php

namespace backend\modules\system\models;

/**
 * This is the ActiveQuery class for [[SettingsMenu]].
 *
 * @see SettingsMenu
 */
class SettingsMenuQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return SettingsMenu[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SettingsMenu|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
