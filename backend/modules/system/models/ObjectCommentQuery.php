<?php

namespace backend\modules\system\models;

/**
 * This is the ActiveQuery class for [[ObjectComment]].
 *
 * @see ObjectComment
 */
class ObjectCommentQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ObjectComment[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ObjectComment|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
