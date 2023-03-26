<?php

namespace backend\modules\survey\models;

use common\components\FHtml;


class Survey extends SurveyBase
{
    const LOOKUP = [
    ];

    const COLUMNS_UPLOAD = [];


    const OBJECTS_META = [];
    const OBJECTS_RELATED = ['survey_question'];

    public static function getLookupArray($column = '')
    {
        if (key_exists($column, self::LOOKUP))
            return self::LOOKUP[$column];
        return [];
    }

    public function fields()
    {
        $fields = array_merge(parent::fields(), self::OBJECTS_RELATED);

        foreach (self::COLUMNS_UPLOAD as $field) {
            $this->{$field} = FHtml::getFileURL($this->{$field}, $this->getTableName());
        }
        return $fields;
    }

    public function prepareCustomFields()
    {
        parent::prepareCustomFields();

    }

    public static function getRelatedObjects()
    {
        return self::OBJECTS_RELATED;
    }

    public static function getMetaObjects()
    {
        return self::OBJECTS_META;
    }

    public function getSurveyQuestion() {
        return $this->hasMany(SurveyQuestion::className(), ['survey_id' => $this->id]);
        //return FHtml::getRelatedModels(self::tableName(), $this->id, 'survey_question');
    }


}