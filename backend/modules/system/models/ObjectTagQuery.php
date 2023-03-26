<?php

namespace backend\modules\system\models;

/**
 * This is the ActiveQuery class for [[ObjectTag]].
 *
 * @see ObjectTag
 */
class ObjectTagQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ObjectTag[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ObjectTag|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
