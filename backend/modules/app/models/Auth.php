<?php

namespace frontend\models;

use backend\modules\app\models\AppUserAPI;

/**
 * This is the model class for table "auth".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $source
 * @property string $source_id
 * @property string $name
 * @property string $image
 *
 * @property AppUserAPI $user
 */
class Auth extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_auth';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'source', 'source_id'], 'required'],
            [['user_id'], 'integer'],
            [['source', 'source_id', 'name', 'image'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => AppUserAPI::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'source' => 'Source',
            'source_id' => 'Source ID',
            'name' => 'Name',
            'image' => 'Image'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(AppUserAPI::className(), ['id' => 'user_id']);
    }
}
