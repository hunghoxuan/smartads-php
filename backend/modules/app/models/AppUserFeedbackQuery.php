<?php

namespace backend\modules\app\models;

/**
 * This is the ActiveQuery class for [[\backend\modules\app\models\AppUserFeedback]].
 *
 * @see \backend\modules\app\models\AppUserFeedback
 */
class AppUserFeedbackQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \backend\modules\app\models\AppUserFeedback[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \backend\modules\app\models\AppUserFeedback|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
