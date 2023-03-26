<?php

namespace backend\modules\system\models;

/**
 * This is the ActiveQuery class for [[ObjectSetting]].
 *
 * @see ObjectSetting
 */
class ObjectSettingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ObjectSetting[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ObjectSetting|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
