<?php

namespace backend\modules\system\models;

/**
 * This is the ActiveQuery class for [[ObjectPeople]].
 *
 * @see ObjectPeople
 */
class ObjectPeopleQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ObjectPeople[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ObjectPeople|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
