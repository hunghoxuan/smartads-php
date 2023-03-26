<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[ObjectCategory]].
 *
 * @see ObjectCategory
 */
class ObjectCategoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ObjectCategory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ObjectCategory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
