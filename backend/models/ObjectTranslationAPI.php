<?php

namespace backend\models;

/**



 * This is the customized model class for table "object_translation".
 */
class ObjectTranslationAPI extends ObjectTranslation
{
    public function fields()
    {
        //Customize fields to be displayed in API
        $fields = ['id', 'object_id', 'object_type', 'lang', 'content',];

        return $fields;
    }

    public function rules()
    {
        //No Rules required for API object
        return [];
    }
}
