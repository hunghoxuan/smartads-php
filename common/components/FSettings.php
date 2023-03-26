<?php

namespace common\components;

use backend\models\AuthMenu;
use backend\modules\app\App;
use backend\modules\cms\Cms;
use backend\modules\ecommerce\Ecommerce;
use backend\modules\system\System;
use backend\modules\travel\Travel;
use common\components\FHtml;
use common\components\FSecurity;
use common\models\User;
use yii\base\Component;

class FSettings extends Component
{
    const FIELDS_GROUP = [
        'lang*', '*type', '*status', '*parent_id', '*parentid', '*category_id', 'is_*'];

    const TABLES_COMMON = ['object_*', 'user*', 'application*', 'settings*', 'object*'];
    const FIELDS_PREVIEW = ['sort_order', 'count_*', '*_count', 'created_date', 'updated_date', 'modified_date', 'created_user', 'updated_user', 'modified_user', 'created_userid', 'updated_userid', 'modified_userid'];
    const FIELDS_HIDDEN = ['password*', 'auth_*', 'sort_order', 'application_id', 'created_date', 'updated_date', 'modified_date', 'created_user', 'updated_user', 'modified_user', 'created_userid', 'updated_userid', 'modified_userid'];
    const FIELDS_VISIBLE = ['code', 'name', 'description', 'overview', 'category_id', 'type', 'status', 'is_top', 'is_hot', 'is_active'];

    const FIELDS_COUNT = ['count_*', '*_count'];
    const FIELDS_UPLOAD = ['file*', 'document*', 'attachment*', 'thumbnail*', 'avatar*', 'image*', '*image', 'banner*', '*banner', 'banner', 'logo', 'logo*', '*logo'];
    const FIELDS_FILES = ['*file', '*document', '*attachment'];
    const FIELDS_IMAGES = array('image', 'thumbnail', 'avatar', 'banner', 'logo', '*thumbnail', '*avatar', '*image', '*banner', '*logo');
    const FIELDS_PRICE = ['cost*', '*cost', '*price', 'price*'];
    const FIELDS_DATE = ['date*', '*date', '*time', 'time*'];
    const FIELDS_HTML = ['content', 'note'];
    const FIELDS_COMMON = ['count*', 'created_date', 'updated_date', 'modified_date', 'created_user', 'updated_user', 'modified_user', 'created_userid', 'updated_userid', 'modified_userid'];
    const FIELDS_TEXTAREA = array('comment', '*_comment', 'overview', 'description', '*overview', 'overview*', '*description', 'description*');
    const FIELDS_TEXTAREASMALL = array('*_credit', '*_description', '*keywords', '*tags');
    const FIELDS_LOOKUP = array('type', 'type*', '*type', 'status', 'status*', '*status', '*id', '*user', '*userid', '*parent_id', '*parentid', 'lang', 'gender', 'gender_*', '*_gender', 'tax', '*_tax', 'tax_*', 'label_color', 'background_color');
    const FIELDS_TIME = array('*time', 'time_*');
    const FIELDS_DATETIME = array('*datetime', 'datetime*');
    const FIELDS_RATE = array('rates', 'rate');
    const FIELDS_BOOLEAN = array('is_*');
    const FIELDS_PERCENT = array('percent', 'progress', 'discount', '*_percent', '*_progress', '*_discount', 'percent_*', 'progress_*', 'discount_*');
    const FIELDS_PASSWORDS = array('password*', 'auth_*');
    const FIELDS_NAME = ['name', 'title', 'username'];
    const FIELDS_OVERVIEW = ['overview', 'description', 'summary'];
    const FIELDS_STATUS = ['category_id', 'type', 'status', 'is_top', 'is_hot', 'is_active'];
    const FIELDS_HISTORY = ['created_user', 'created_date', 'modified_user', 'modified_date'];

}