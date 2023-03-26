<?php

namespace backend\modules\system\models;

/**
 * This is the ActiveQuery class for [[ObjectMessage]].
 *
 * @see ObjectMessage
 */
class ObjectMessageQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ObjectMessage[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ObjectMessage|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
