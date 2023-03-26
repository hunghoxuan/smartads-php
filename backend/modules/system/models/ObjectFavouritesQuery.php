<?php

namespace backend\modules\system\models;

/**
 * This is the ActiveQuery class for [[ObjectFavourites]].
 *
 * @see ObjectFavourites
 */
class ObjectFavouritesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ObjectFavourites[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ObjectFavourites|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
