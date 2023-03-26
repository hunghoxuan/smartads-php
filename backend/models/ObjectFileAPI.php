<?php

namespace backend\models;

use common\base\BaseAPIObject;
use common\components\FApi;

/**
 * This is the model class for table "object_file".
 *
 * @property string $id
 * @property integer $object_id
 * @property string $object_type
 * @property string $file
 * @property string $title
 * @property string $description
 * @property string $file_type
 * @property string $file_size
 * @property string $file_duration
 * @property integer $is_active
 * @property integer $sort_order
 * @property string $created_date
 * @property string $created_user
 * @property string $application_id
 */
class ObjectFileAPI extends BaseAPIObject
{
    public function fields()
    {
        $fields = parent::fields(); // TODO: Change the autogenerated stub
        $folder = 'object-file';
        $file = FApi::getFileURLForAPI($this->file, $folder);
        $this->file = $file;
        return $fields;
    }

    public function getApiFields()
    {
        //$fields = parent::getApiFields(); // TODO: Change the autogenerated stub
        $fields = [
            'id',
            'object_id',
            'object_type',
            'file',
            'title',
            'description',
            'file_type',
            'file_size',
            'file_duration'
        ];
        return $fields;
    }
}