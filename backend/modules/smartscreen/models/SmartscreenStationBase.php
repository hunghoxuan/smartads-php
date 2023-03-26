<?php

namespace backend\modules\smartscreen\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "smartscreen_station".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $ime
 * @property integer $status
 * @property string $last_activity
 * @property integer $last_update
 * @property string $ScreenName
 * @property string $MACAddress
 * @property string $LicenseKey
 * @property string $branch_id
 * @property string $channel_id
 * @property string $dept_id
 * @property string $room_id
 * @property string $disk_storage
 * @property string $created_date
 * @property string $application_id
 */
class SmartscreenStationBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public $tableName = 'smartscreen_station';

    public static function tableName()
    {
        return 'smartscreen_station';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name', 'description', 'ime', 'status', 'last_activity', 'last_update', 'ScreenName', 'MACAddress', 'LicenseKey', 'branch_id', 'channel_id', 'dept_id', 'room_id', 'disk_storage', 'created_date', 'application_id'], 'filter', 'filter' => 'trim'],
            [['name', 'ime'], 'required'],
            [['status', 'last_update'], 'integer'],
            [['created_date'], 'safe'],
            [['name', 'ScreenName', 'MACAddress', 'disk_storage', 'LicenseKey'], 'string', 'max' => 255],
            [['description', 'last_activity'], 'string', 'max' => 2000],
            [['ime', 'branch_id', 'channel_id', 'dept_id', 'room_id', 'application_id'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('SmartscreenStation', 'ID'),
            'name' => FHtml::t('SmartscreenStation', 'Name'),
            'description' => FHtml::t('SmartscreenStation', 'Description'),
            'ime' => FHtml::t('SmartscreenStation', 'Ime'),
            'status' => FHtml::t('SmartscreenStation', 'Status'),
            'last_activity' => FHtml::t('SmartscreenStation', 'Last Activity'),
            'last_update' => FHtml::t('SmartscreenStation', 'Last Update'),
            'ScreenName' => FHtml::t('SmartscreenStation', 'Screen Name'),
            'MACAddress' => FHtml::t('SmartscreenStation', 'Macaddress'),
            'LicenseKey' => FHtml::t('SmartscreenStation', 'License Key'),
            'branch_id' => FHtml::t('SmartscreenStation', 'Branch ID'),
            'channel_id' => FHtml::t('SmartscreenStation', 'Channel ID'),
            'dept_id' => FHtml::t('SmartscreenStation', 'Dept ID'),
            'room_id' => FHtml::t('SmartscreenStation', 'Room ID'),
            'disk_storage' => FHtml::t('SmartscreenStation', 'Disk Storage'),
            'created_date' => FHtml::t('SmartscreenStation', 'Created Date'),
            'application_id' => FHtml::t('SmartscreenStation', 'Application ID'),
        ];
    }


}