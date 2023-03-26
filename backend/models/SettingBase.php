<?php

namespace backend\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "setting".
 *
 * @property integer $id
 * @property string $metaKey
 * @property string $metaValue
 * @property string $group
 * @property integer $is_active
 * @property string $application_id
 */
class SettingBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public $tableName = 'setting';

    public static function tableName()
    {
        return 'setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'metaKey', 'metaValue', 'group', 'is_active', 'application_id'], 'filter', 'filter' => 'trim'],
            [['metaValue'], 'string'],
            [['is_active'], 'integer'],
            [['metaKey', 'group'], 'string', 'max' => 255],
            [['application_id'], 'string', 'max' => 100],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('Setting', 'ID'),
            'metaKey' => FHtml::t('Setting', 'Meta Key'),
            'metaValue' => FHtml::t('Setting', 'Meta Value'),
            'group' => FHtml::t('Setting', 'Group'),
            'is_active' => FHtml::t('Setting', 'Is Active'),
            'application_id' => FHtml::t('Setting', 'Application ID'),
        ];
    }


}