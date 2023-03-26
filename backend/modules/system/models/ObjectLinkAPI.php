<?php

namespace backend\modules\system\models;

use common\base\BaseAPIObject;
use common\components\FApi;
use common\components\FHtml;

/**
 * This is the model class for table "object_link".
 *
 * @property integer $id
 * @property integer $object_id
 * @property string $object_type
 * @property string $name
 * @property string $link_url
 * @property string $type
 * @property integer $is_active
 * @property string $created_date
 * @property string $created_user
 * @property string $application_id
 *
 * @property string $image
 *
 */

class ObjectLinkAPI extends BaseAPIObject
{
    public $image;

    public function getApiFields()
    {
        $fields = [
            'id',
            'object_id',
            'object_type',
            'name',
            'link_url',
            'type',
            'image'
        ];
        return $fields;
    }

    public function fields()
    {
        $fields = parent::fields();

        $folder = 'www/social';
        $logo = FApi::getImageUrlForAPI($this->type . ".png", $folder);
        $this->image = $logo;
        return $fields;
    }
}