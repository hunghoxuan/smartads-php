<?php

namespace backend\modules\smartscreen\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "smartscreen_layouts".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $sort_order
 * @property integer $is_active
 * @property integer $is_default
 * @property string $demo_html
 * @property integer $created_date
 * @property integer $modified_date
 * @property string $appilication_id
 *
 * @property SmartscreenLayoutsFrame[] $smartscreenLayoutsFrames
 */
class SmartscreenLayoutsBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public $tableName = 'smartscreen_layouts';

    public static function tableName()
    {
        return 'smartscreen_layouts';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return FHtml::currentDb();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name', 'description', 'sort_order', 'is_active', 'is_default', 'demo_html', 'created_date', 'modified_date', 'appilication_id'], 'filter', 'filter' => 'trim'],
            [['name', 'created_date'], 'required'],
            [['description', 'demo_html'], 'string'],
            [['sort_order', 'is_active', 'is_default', 'created_date', 'modified_date'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['appilication_id'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('SmartscreenLayouts', 'ID'),
            'name' => FHtml::t('SmartscreenLayouts', 'Name'),
            'description' => FHtml::t('SmartscreenLayouts', 'Description'),
            'sort_order' => FHtml::t('SmartscreenLayouts', 'Sort Order'),
            'is_active' => FHtml::t('SmartscreenLayouts', 'Is Active'),
            'is_default' => FHtml::t('SmartscreenLayouts', 'Is Default'),
            'demo_html' => FHtml::t('SmartscreenLayouts', 'Demo Html'),
            'created_date' => FHtml::t('SmartscreenLayouts', 'Created Date'),
            'modified_date' => FHtml::t('SmartscreenLayouts', 'Modified Date'),
            'appilication_id' => FHtml::t('SmartscreenLayouts', 'Appilication ID'),
        ];
    }

    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        $i18n = Yii::$app->i18n;
        $i18n->translations['SmartscreenLayouts*'] = [
            'class' => 'common\components\FMessageSource',
            'basePath' => '@backend/modules/smartscreen/messages',
            'fileMap' => [
                'SmartscreenLayouts' => 'SmartscreenLayouts.php',
            ],
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSmartscreenLayoutsFrames()
    {
        return $this->hasMany(SmartscreenLayoutsFrame::className(), ['layout_id' => 'id']);
    }
}