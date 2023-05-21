<?php

namespace common\base;

use common\components\FHtml;
use yii\db\ActiveRecord;
use yii\helpers\BaseInflector;
use Yii;
use yii\helpers\StringHelper;

class BaseActiveRecord1 extends ActiveRecord
{
    protected $api_fields;

    public static function getInstance()
    {
        return Yii::createObject(['class' => get_called_class()]);
    }

    public static function tableName()
    {
        $arr = explode('\\', get_called_class());
        $name = $arr[count($arr) - 1];

        if (StringHelper::endsWith($name, 'API'))
            $name = str_replace('API', '', $name);

        if (StringHelper::endsWith($name, 'Search'))
            $name = str_replace("Search", "", $name);

        $name = BaseInflector::camel2id($name);
        $name = str_replace('-', '_', $name);
        return $name;
    }

    public function field_exists($fields = [])
    {
        $result = [];
        if (is_string($fields))
            $fields = [$fields];

        if (is_array($fields)) {
            foreach ($fields as $field)
                if (FHtml::field_exists($this, $field))
                    $result[] = $field;
        }
        return $result;
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function attributes()
    {
        $schema = static::getTableSchema();
        if (isset($schema))
            return array_keys($schema->columns);
        else {
            return $this->fields();
        }
    }

    public function getApiFields()
    {
        $schema = static::getTableSchema();
        if (isset($schema))
            return array_keys($schema->columns);
        else {
            return array_keys($this->attributeLabels());
        }
    }

    public function fields()
    {
        $fields = $this->getRequestFields();
        if (!empty($fields))
            return $fields;

        $fields = $this->getApiFields();

        return $fields;
    }

    public function getRequestFields()
    {
        $fields = FHtml::getRequestParam([static::tableName() . '_fields', 'fields']);
        if (!empty($fields)) {
            $fields = explode(',', $fields);
            if (!is_array($fields))
                $fields = [];
            return $fields;
        }
        return [];
    }

    public function beforeSave($insert)
    {
        if ($this->field_exists('application_id'))
            $this->application_id = FHtml::currentApplicationCode();

        return parent::beforeSave($insert);
    }
}
