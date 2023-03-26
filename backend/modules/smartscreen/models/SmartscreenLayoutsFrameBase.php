<?php

namespace backend\modules\smartscreen\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the model class for table "smartscreen_layouts_frame".
 *

 * @property integer $layout_id
 * @property integer $frame_id
 */
class SmartscreenLayoutsFrameBase extends BaseModel //\yii\db\ActiveRecord
{

    /**
    * @inheritdoc
    */
    public $tableName = 'smartscreen_layouts_frame';

    public static function tableName()
    {
        return 'smartscreen_layouts_frame';
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
        
            [['layout_id', 'frame_id'], 'filter', 'filter' => 'trim'],
                
            [['layout_id', 'frame_id'], 'required'],
            [['layout_id', 'frame_id', 'sort_order'], 'integer'],
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
                    'layout_id' => FHtml::t('SmartscreenLayoutsFrame', 'Layout ID'),
                    'frame_id' => FHtml::t('SmartscreenLayoutsFrame', 'Frame ID'),
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
        $i18n->translations['SmartscreenLayoutsFrame*'] = [
            'class' => 'common\components\FMessageSource',
            'basePath' => '@backend/modules/smartscreen/messages',
            'fileMap' => [
                'SmartscreenLayoutsFrame' => 'SmartscreenLayoutsFrame.php',
            ],
        ];
    }




}
