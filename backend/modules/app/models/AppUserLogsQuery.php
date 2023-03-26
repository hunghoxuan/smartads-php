<?php

namespace backend\modules\app\models;

/**
 * This is the ActiveQuery class for [[AppUserLogs]].
 *
 * @see AppUserLogs
 */
class AppUserLogsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return AppUserLogs[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AppUserLogs|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
