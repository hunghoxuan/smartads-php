<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[ObjectAttributes]].
 *
 * @see ObjectAttributes
 */
class ObjectAttributesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ObjectAttributes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ObjectAttributes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
