<?php

// DIRECTORIES
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
defined('UPLOAD_DIR') or define('UPLOAD_DIR', 'upload');


//DEFAULT VALUES
defined('DEFAULT_IMAGE') or define('DEFAULT_IMAGE', 'no_image.jpg');
defined('FRONTEND') or define('FRONTEND', 'frontend');
defined('BACKEND') or define('BACKEND', 'backend');

// DIRECTORIES
defined('SITE') or define('SITE', 'site');
defined('IMAGES_DIR') or define('IMAGES_DIR', 'images');
defined('WEB_DIR') or define('WEB_DIR', 'web');
defined('ADMIN_DASHBOARD_MODULE') or define('ADMIN_DASHBOARD_MODULE', '');
defined('PRODUCTS_DIR') or define('PRODUCTS_DIR', 'product');
defined('CATEGORY_DIR') or define('CATEGORY_DIR', 'object-category');
defined('SALEOFF_DIR') or define('SALEOFF_DIR', 'saleoff');
defined('NEWS_DIR') or define('NEWS_DIR', 'cms-blogs');
defined('ABOUTUS_DIR') or define('ABOUTUS_DIR', 'cms-about');
defined('EMPLOYEE_DIR') or define('EMPLOYEE_DIR', 'cms-employee');

defined('CHAPTER_DIR') or define('CHAPTER_DIR', 'book-chapter');

defined('WWW_DIR') or define('WWW_DIR', 'www');
defined('APP_USER_DIR') or define('APP_USER_DIR', 'app-user');
defined('PACKAGE_DIR') or define('PACKAGE_DIR', 'package');
defined('VERIFICATION_DIR') or define('VERIFICATION_DIR', 'verification');
defined('PRODUCT_DIR') or define('PRODUCT_DIR', 'product');
defined('ICON_DIR') or define('ICON_DIR', 'icon');
defined('ARTICLE_DIR') or define('ARTICLE_DIR', 'cms-article');
defined('SLIDER_DIR') or define('SLIDER_DIR', 'cms-slide');
defined('GALLERY_DIR') or define('GALLERY_DIR', 'gallery');
defined('HOSTAGENT_DIR') or define('HOSTAGENT_DIR', 'hostagent');
defined('PEM_DIR') or define('PEM_DIR', 'pem');
defined('TRANSPORT_DRIVER_DIR') or define('TRANSPORT_DRIVER_DIR', 'transport-driver');
defined('TRANSPORT_VEHICLE_DIR') or define('TRANSPORT_VEHICLE_DIR', 'transport-vehicle');
defined('PRODUCT_DEAL_DIR') or define('PRODUCT_DEAL_DIR', 'product-deal');


if (!isset($root_dir)) $root_dir = dirname(dirname(dirname(__FILE__)));

Yii::setAlias('@' . IMAGES_DIR, $root_dir . DS . BACKEND . DS . WEB_DIR . DS . IMAGES_DIR);
Yii::setAlias('@' . UPLOAD_DIR, $root_dir . DS . BACKEND . DS . WEB_DIR . DS . UPLOAD_DIR);
Yii::setAlias('@' . SITE, $root_dir);

Yii::setAlias('@common', dirname(dirname(__DIR__)) . '/common');
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@applications', dirname(dirname(__DIR__)) . '/applications');

if(!class_exists('Globals')) {
    class Globals extends \common\components\FHtml
    {

    }
}

return [];