<?php

namespace backend\modules\smartscreen\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 *
 ***
 * This is the model class for table "smartscreen_frame".
 *

 * @property integer $id
 * @property string $name
 * @property string $backgroundColor
 * @property integer $layout_id
 * @property integer $percentWidth
 * @property integer $percentHeight
 * @property integer $marginTop
 * @property integer $marginLeft
 * @property string $contentLayout
 * @property integer $created_date
 * @property integer $modified_date
 * @property string $application_id
 * @property string $file
 * @property string $content
 * @property integer $content_id
 * @property string $font_size
 * @property string $font_color
 * @property string $alignment
 * @property integer $sort_order
 * @property integer $is_active
 */
class SmartscreenFrameBase extends BaseModel //\yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public $tableName = 'smartscreen_frame';

    public static function tableName()
    {
        return 'smartscreen_frame';
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

            [['id', 'name', 'backgroundColor', 'layout_id', 'percentWidth', 'percentHeight', 'marginTop', 'marginLeft', 'contentLayout', 'created_date', 'modified_date', 'application_id', 'file', 'content', 'content_id', 'font_size', 'font_color', 'alignment', 'sort_order', 'is_active'], 'filter', 'filter' => 'trim'],

            [['name', 'percentWidth', 'percentHeight', 'created_date'], 'required'],
            [['layout_id', 'percentWidth', 'percentHeight', 'marginTop', 'marginLeft', 'created_date', 'modified_date', 'content_id', 'sort_order', 'is_active'], 'integer'],
            [['content'], 'string'],
            [['name', 'contentLayout', 'application_id', 'file', 'font_size', 'font_color', 'alignment'], 'string', 'max' => 255],
            [['backgroundColor'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('SmartscreenFrame', 'ID'),
            'name' => FHtml::t('SmartscreenFrame', 'Name'),
            'backgroundColor' => FHtml::t('SmartscreenFrame', 'Background Color'),
            'layout_id' => FHtml::t('SmartscreenFrame', 'Layout ID'),
            'percentWidth' => FHtml::t('SmartscreenFrame', 'Percent Width'),
            'percentHeight' => FHtml::t('SmartscreenFrame', 'Percent Height'),
            'marginTop' => FHtml::t('SmartscreenFrame', 'Margin Top'),
            'marginLeft' => FHtml::t('SmartscreenFrame', 'Margin Left'),
            'contentLayout' => FHtml::t('SmartscreenFrame', 'Content Layout'),
            'created_date' => FHtml::t('SmartscreenFrame', 'Created Date'),
            'modified_date' => FHtml::t('SmartscreenFrame', 'Modified Date'),
            'application_id' => FHtml::t('SmartscreenFrame', 'Application ID'),
            'file' => FHtml::t('SmartscreenFrame', 'File'),
            'content' => FHtml::t('SmartscreenFrame', 'Content'),
            'content_id' => FHtml::t('SmartscreenFrame', 'Content ID'),
            'font_size' => FHtml::t('SmartscreenFrame', 'Font Size'),
            'font_color' => FHtml::t('SmartscreenFrame', 'Font Color'),
            'alignment' => FHtml::t('SmartscreenFrame', 'Alignment'),
            'sort_order' => FHtml::t('SmartscreenFrame', 'Sort Order'),
            'is_active' => FHtml::t('SmartscreenFrame', 'Is Active'),
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
        $i18n->translations['SmartscreenFrame*'] = [
            'class' => 'common\components\FMessageSource',
            'basePath' => '@backend/modules/smartscreen/messages',
            'fileMap' => [
                'SmartscreenFrame' => 'SmartscreenFrame.php',
            ],
        ];
    }
}
