<?php

namespace backend\models;

use common\components\FHtml;


class ObjectProperties extends ObjectPropertiesBase
{
    const LOOKUP = [
    ];

    const COLUMNS_UPLOAD = ['image', 'banner', 'thumbnail' ];

    public $order_by = 'id desc';

    public function isDBLanguagesEnabled() {
        return FHtml::settingDBLanguaguesEnabled();
    }

    public function getPropertiesModel() {
        return null;
    }
}