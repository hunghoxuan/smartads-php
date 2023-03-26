<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[\backend\modules\app\models\AppUserTransaction]].
 *
 * @see \backend\modules\app\models\AppUserTransaction
 */
class AppUserTransactionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \backend\modules\app\models\AppUserTransaction[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \backend\modules\app\models\AppUserTransaction|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
