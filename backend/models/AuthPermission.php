<?php

namespace backend\models;
use Yii;

/**
 * @property AuthGroup $group
 * @property AuthRole $role
 */
class AuthPermission extends AuthPermissionBase
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        
            [['object_id', 'object_type', 'object2_id', 'object2_type', 'sort_order'], 'required'],
            [['object_id', 'object2_id', 'sort_order'], 'integer'],
            [['created_date'], 'safe'],
            [['object_type', 'object2_type', 'relation_type', 'created_user'], 'string', 'max' => 100],                
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('auth', 'ID'),
            'object_id' => Yii::t('auth', 'Object ID'),
            'object_type' => Yii::t('auth', 'Object Type'),
            'object2_id' => Yii::t('auth', 'Object2 ID'),
            'object2_type' => Yii::t('auth', 'Object2 Type'),
            'relation_type' => Yii::t('auth', 'Relation Type'),
            'sort_order' => Yii::t('auth', 'Sort Order'),
            'created_date' => Yii::t('auth', 'Created Date'),
            'created_user' => Yii::t('auth', 'Created User'),
        ];
    }

    /**
     * Connections
     */
    public function getGroup()
    {
        return $this->hasOne(AuthGroup::className(), ['id' => 'object_id']);
    }

    public function getRole()
    {
        return $this->hasOne(AuthRole::className(), ['id' => 'object2_id']);
    }
}
