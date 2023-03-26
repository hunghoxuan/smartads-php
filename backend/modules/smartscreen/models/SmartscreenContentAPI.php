<?php

namespace backend\modules\smartscreen\models;
use common\components\FHtml;

/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "smartscreen_content".
 */
class SmartscreenContentAPI extends SmartscreenContent
{
    public function checkCustomField($name) {
        if (in_array($name, self::COLUMNS_API))
            return true;

        if (in_array($name,  self::COLUMNS_CUSTOM))
            return true;

        return parent::checkCustomField($name);
    }

    //Customize fields to be displayed in API
    const COLUMNS_API = ['id', 'title', 'url', 'description', 'type', 'kind', 'duration', 'expire_date', 'owner_id', 'is_active', ];
    const COLUMNS_CUSTOM = ['list_content', 'layout' ];

    public function fields()
    {
        $fields = $this::COLUMNS_API;
        $this->url = !empty($this->url) ? FHtml::getFileURL($this->url, 'smartscreen-content') : '';

        return $fields;
    }

    public function rules()
    {
        //No Rules required for API object
        return [];
    }
}
