<?php

namespace backend\modules\smartscreen\models;

use common\models\BaseModel;
use Yii;
use common\components\FHtml;
use yii\helpers\ArrayHelper;


/*
 * This is the model class for table "smartscreen_scripts".
 *

 * @property integer $id
 * @property string $name
 * @property string $Logo
 * @property string $TopBanner
 * @property string $BotBanner
 * @property string $ClipHeader
 * @property string $ClipFooter
 * @property string $ScrollText
 * @property integer $Clipnum
 * @property string $Clip1
 * @property string $Clip2
 * @property string $Clip3
 * @property string $Clip4
 * @property string $Clip5
 * @property string $Clip6
 * @property string $Clip7
 * @property string $Clip8
 * @property string $Clip9
 * @property string $Clip10
 * @property string $Clip11
 * @property string $Clip12
 * @property string $Clip13
 * @property string $Clip14
 * @property integer $CommandNumber
 * @property string $Line1
 * @property string $Line2
 * @property string $Line3
 * @property string $Line4
 * @property string $Line5
 * @property string $Line6
 * @property string $Line7
 * @property string $Line8
 * @property string $Line9
 * @property string $Line10
 * @property string $Line11
 * @property string $Line12
 * @property string $Line13
 * @property string $Line14
 * @property string $Line15
 * @property string $Line16
 * @property string $scripts_content
 * @property string $scripts_file
 * @property string $ReleaseDate
 * @property integer $sort_order
 * @property integer $is_active
 * @property string $application_id
 */

class SmartscreenScriptsBase extends BaseModel //\yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public $tableName = 'smartscreen_scripts';

    public static function tableName()
    {
        return 'smartscreen_scripts';
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

            [['id', 'name', 'Logo', 'TopBanner', 'BotBanner', 'ClipHeader', 'ClipFooter', 'ScrollText', 'Clipnum', 'Clip1', 'Clip2', 'Clip3', 'Clip4', 'Clip5', 'Clip6', 'Clip7', 'Clip8', 'Clip9', 'Clip10', 'Clip11', 'Clip12', 'Clip13', 'Clip14', 'CommandNumber', 'Line1', 'Line2', 'Line3', 'Line4', 'Line5', 'Line6', 'Line7', 'Line8', 'Line9', 'Line10', 'Line11', 'Line12', 'Line13', 'Line14', 'Line15', 'Line16', 'scripts_content', 'scripts_file', 'ReleaseDate', 'sort_order', 'is_active', 'application_id'], 'filter', 'filter' => 'trim'],

            [['name'], 'required'],
            [['ScrollText', 'scripts_content'], 'string'],
            [['Clipnum', 'CommandNumber', 'sort_order', 'is_active'], 'integer'],
            [['ReleaseDate'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['Logo', 'TopBanner', 'BotBanner', 'ClipHeader', 'ClipFooter', 'Clip1', 'Clip2', 'Clip3', 'Clip4', 'Clip5', 'Clip6', 'Clip7', 'Clip8', 'Clip9', 'Clip10', 'Clip11', 'Clip12', 'Clip13', 'Clip14', 'scripts_file'], 'string', 'max' => 300],
            [['Line1', 'Line2', 'Line3', 'Line4', 'Line5', 'Line6', 'Line7', 'Line8', 'Line9', 'Line10', 'Line11', 'Line12', 'Line13', 'Line14', 'Line15', 'Line16'], 'string', 'max' => 2000],
            [['application_id'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'Logo' => 'Logo',
            'TopBanner' => 'Top Banner',
            'BotBanner' => 'Bot Banner',
            'ClipHeader' => 'Clip Header',
            'ClipFooter' => 'Clip Footer',
            'ScrollText' => 'Scroll Text',
            'Clipnum' => 'Clipnum',
            'Clip1' => 'Clip1',
            'Clip2' => 'Clip2',
            'Clip3' => 'Clip3',
            'Clip4' => 'Clip4',
            'Clip5' => 'Clip5',
            'Clip6' => 'Clip6',
            'Clip7' => 'Clip7',
            'Clip8' => 'Clip8',
            'Clip9' => 'Clip9',
            'Clip10' => 'Clip10',
            'Clip11' => 'Clip11',
            'Clip12' => 'Clip12',
            'Clip13' => 'Clip13',
            'Clip14' => 'Clip14',
            'CommandNumber' => 'Command Number',
            'Line1' => 'Line1',
            'Line2' => 'Line2',
            'Line3' => 'Line3',
            'Line4' => 'Line4',
            'Line5' => 'Line5',
            'Line6' => 'Line6',
            'Line7' => 'Line7',
            'Line8' => 'Line8',
            'Line9' => 'Line9',
            'Line10' => 'Line10',
            'Line11' => 'Line11',
            'Line12' => 'Line12',
            'Line13' => 'Line13',
            'Line14' => 'Line14',
            'Line15' => 'Line15',
            'Line16' => 'Line16',
            'scripts_content' => 'Scripts Content',
            'scripts_file' => 'Scripts File',
            'ReleaseDate' => 'Release Date',
            'sort_order' => 'Sort Order',
            'is_active' => 'Is Active',
            'application_id' => 'Application ID',
        ];
    }

    public static function tableSchema()
    {
        return FHtml::getTableSchema(self::tableName());
    }

    public static function Columns()
    {
        return self::tableSchema()->columns;
    }

    public static function ColumnsArray()
    {
        return ArrayHelper::getColumn(self::tableSchema()->columns, 'name');
    }

    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        $i18n = Yii::$app->i18n;
        $i18n->translations['SmartscreenScripts*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/modules/smartscreen/messages',
            'fileMap' => [
                'SmartscreenScripts' => 'SmartscreenScripts.php',
            ],
        ];
    }
}
