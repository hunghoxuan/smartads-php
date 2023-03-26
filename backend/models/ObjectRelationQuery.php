<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[ObjectRelation]].
 *
 * @see ObjectRelation
 */
class ObjectRelationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ObjectRelation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ObjectRelation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
