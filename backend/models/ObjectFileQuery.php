<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[ObjectFile]].
 *
 * @see ObjectFile
 */
class ObjectFileQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ObjectFile[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ObjectFile|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
