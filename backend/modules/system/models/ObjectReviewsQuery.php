<?php

namespace backend\modules\system\models;

/**
 * This is the ActiveQuery class for [[ObjectReviews]].
 *
 * @see ObjectReviews
 */
class ObjectReviewsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ObjectReviews[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ObjectReviews|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
