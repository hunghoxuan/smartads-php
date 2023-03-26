<?php

namespace backend\modules\app\models;

/**
 * This is the ActiveQuery class for [[AppUserBookmark]].
 *
 * @see AppUserBookmark
 */
class AppUserBookmarkQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return AppUserBookmark[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AppUserBookmark|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
