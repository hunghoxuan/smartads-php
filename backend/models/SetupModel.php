<?php

namespace backend\models;

use common\base\BaseModelObject;
use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseDataObject;
use frontend\models\ViewModel;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "application".
 */
class SetupModel extends BaseModelObject
{
    public $secret;
    public $purchase_site;
    public $purchase_license;
    public $purchase_order;
    public $client_name;
    public $client_email;

    public $app_version;
    public $installed;

    public $db_host;
    public $db_name;
    public $db_username;
    public $db_password;
    public $db_database;

    public $app_name;
    public $image;
    public $app_description;
    public $app_website;
    public $admin_email;
    public $admin_username;
    public $admin_password;
    public $admin_phone;

    public $email_host;
    public $email_username;
    public $email_password;
    public $email_port;
    public $email_encryption;
    public $email_useTransport;
}
