<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\controllers;

use backend\models\SetupModel;
use backend\models\User;
use common\components\AccessRule;
use common\components\FBackup;
use common\components\FConfig;
use common\components\FDatabase;
use common\components\FEmail;
use common\components\FFile;
use common\components\FHtml;
use common\components\FSecurity;
use common\controllers\BaseAdminController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

/**
 * ActiveController implements a common set of actions for supporting RESTful access to ActiveRecord.
 *
 * The class of the ActiveRecord should be specified via [[modelClass]], which must implement [[\yii\db\ActiveRecordInterface]].
 * By default, the following actions are supported:
 *
 * - `index`: list of models
 * - `view`: return the details of a model
 * - `create`: create a new model
 * - `update`: update an existing model
 * - `delete`: delete an existing model
 * - `options`: return the allowed HTTP methods
 *
 * You may disable some of these actions by overriding [[actions()]] and unsetting the corresponding actions.
 *
 * To add a new action, either override [[actions()]] by appending a new action class or write a new action method.
 * Make sure you also override [[verbs()]] to properly declare what HTTP methods are allowed by the new action.
 *
 * You should usually override [[checkAccess()]] to check whether the current user has the privilege to perform
 * the specified action against the specified model.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ToolsController extends BaseAdminController
{
    public $defaultAction = 'index';

    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [

                    [
                        'actions' => ['logout', 'index', 'backup', 'api', 'cache', 'copy', 'database'],
                        'allow' => true,
                        'roles' => [FHtml::ROLE_ADMIN],
                    ],
                    [
                        'actions' => ['setup'],
                        'allow' => true,
                        'roles' => ['*'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function execute($action = '', $application_id = '') {
        if (empty($action) && key_exists('action', $_POST))
            $action = $_POST['action'];

        if (empty($application_id) && key_exists('application_id', $_POST))
            $application_id = $_POST['application_id'];

        if (empty($application_id))
            $application_id = FHtml::currentApplicationId();

        $model = [];
        $arr = explode(':', $action);
        $action = $arr[0];
        $object = count($arr) > 1 ? $arr[1] : [];
        $date = date('Y.m.d');

        if ($action == 'backup_files') {
            $backup = new FBackup();

            $database = $backup->backupTables([], "/backup/$application_id/$date");
            $backup->backupFiles([$application_id => "@applications/$application_id"], "/backup/$application_id/$date");

        } else if ($action == 'backup_database') {
            $backup = new FBackup();
            $file = $backup->backupTables([], "/backup/$application_id/$date");

        } else if ($action == 'restore_backup') {
            $backup = new FBackup();
            $file = FHtml::getRootFolder() . "/backup/$application_id/$object/all.sql";

            $file = $backup->restoreDatabase( $file);

        } else if ($action == 'download_backup_sql') {
            $backup = new FBackup();
            $dbName = FHtml::currentDatabaseName();
            $file = FHtml::getRootFolder() . "/backup/$application_id/$object/all.sql";

            FFile::downloadFile($file, true, "$dbName" . "_all_" . date('Y.m.d') . ".sql");

        } else if ($action == 'download_backup_files') {
            $backup = new FBackup();
            $file = FHtml::getRootFolder() . "/backup/$application_id/$object/$application_id" . "_". "$object.zip";

            FFile::downloadFile($file);

        } else if ($action == 'restore_table') {
            $file = FHtml::getRootFolder() . "/backup/$application_id/$date/";

        } else if ($action == 'download_database') {
            $backup = new FBackup();
            $dbName = FHtml::currentDatabaseName();
            $backup->backupTables([], "/backup/$application_id/$date");
            if (empty($object))
                $object = 'all';

            if (!empty($object)) {
                $file = FHtml::getRootFolder() . "/backup/$application_id/$date/$object.sql";
            }
            FFile::downloadFile($file, true, "$dbName" . "_{$object}_" . date('Y.m.d') . ".sql");

        }  else if ($action == 'clear_database') {
            $tables = FHtml::getApplicationTables();
            $backup = new FDatabase();
            foreach ($tables as $table) {
                if (in_array($table, ['user']))
                    continue;
                $backup->truncateTable($table);
            }

        } else if ($action == 'delete_backup') {
            $folder = FHtml::getRootFolder() . "/backup/$application_id/$object";

            if (is_dir($folder)) {
                FFile::deleteDir($folder);
            }

        } else if ($action == 'backup_table') {
            $backup = new FBackup();
            $file = $backup->backupTables($object, "/backup/$application_id/$date");
            $folder = str_replace('_', '-', $object);
            $folder1 = FHtml::getRootFolder() . "/applications/$application_id/upload/$folder";

            if (is_dir($folder1)) {
                $backup->backupFiles([$object => $folder1], "/backup/$application_id/$date");
            }

        } else if ($action == 'create_sql') {
            $backup = new FBackup();
            $file = $backup->backupTables($object, "/applications/$application_id/setup", false, true, false);

        } else if ($action == 'download_sql') {
            $backup = new FBackup();
            $file = $backup->backupTables($object, "/applications/$application_id/setup", false, true, false);

            FFile::downloadFile($file, false);

        } else if ($action == 'truncate_table') {
            $db = new FDatabase();
            if (FHtml::isTableExisted($object))
                $file = $db->truncateTable($object);
            $folder = str_replace('_', '-', $object);
            $file = FHtml::getRootFolder() . "/applications/$application_id/upload/$folder";

            if (is_dir($file)) {
                FFile::delete($file);
            }

            FHtml::showMessage("Table [$object] is truncated.");

        } else if ($action == 'delete_table') {
            $db = new FDatabase();
            if (FHtml::isTableExisted($object))
                $db->dropTable($object);

            $object = 'application';
            $folder = str_replace('_', '-', $object);
            $file = FHtml::getRootFolder() . "/applications/$application_id/upload/$folder";
            if (is_dir($file)) {
                FFile::delete($file);
            }

            FHtml::showMessage("Table [$object] is deleted.");

        } else if ($action == 'all') {
            $backup = new FBackup();
            $files = $backup->backup([$application_id =>  "@applications/$application_id"]);
            FFile::downloadFile($files[0], false);
        }

        if (empty($application_id))
            $application_id = FHtml::currentApplicationId();

        return $model;
    }

    public function actionIndex($url = '')
    {
        return $this->render('index');
    }

    public function actionCopy($url = '')
    {
        return $this->render('copy');
    }


    public function actionDatabase($action = '', $application_id = '')
    {
        if (empty($application_id))
            $application_id = FHtml::currentApplicationId();

        $model = $this->execute($action, $application_id);

        return $this->render('database.php', ['model' => $model, 'application_id' => $application_id]);
    }

    public function actionCache($action = '', $key = '')
    {
        $model = [];
        if ($action == 'clear' || $action == 'remove') {
            $cache = FHtml::Cache();
            if (isset($cache) && !empty($key) && $cache->exists($key)) {
                $cache->delete($key);
            }

        } else if ($action == 'flush' || $action == 'refresh') {
            $cache = FHtml::Cache();
            if (empty($key) && isset($cache)) {
                FHtml::Cache()->flush();
            }
            $dirs = [FHtml::getRootFolder() . "/assets", FHtml::getRootFolder() . "/frontend/runtime/cache", FHtml::getRootFolder() . "/backend/runtime/cache"];
            foreach ($dirs as $dir) {
                if (is_dir($dir)) {
                    FHtml::deleteDir($dir);
                }
            }
        } else if ($action == 'view') {
            $cache = FHtml::Cache();

            if (empty($key))
                $model = FHtml::Cache();
            else {
                if ($cache->exists($key))
                    $model = [$key => FHtml::getCachedData($key)];
                else
                    $model = null;
            }
        }

        return $this->render('cache.php', ['model' => $model]);
    }



    public function actionBackup($action = '', $application_id = '')
    {
        if (empty($application_id))
            $application_id = FHtml::currentApplicationId();
        $model = $this->execute($action, $application_id);
        return $this->render('backup.php', ['model' => $model, 'application_id' => $application_id]);
    }

    public function actionApi($object = '', $params = '', $orderby = '', $limit = '', $page = '', $fields = '', $action = '', $url = '')
    {
        $model = null;

        if (!empty($object))
            $url = FHtml::currentBaseURL() . 'backend/web/index.php/api/list?object=' . trim($object);

        if (!empty($params))
            $url .= '&' . $params;

        if (!empty($orderby))
            $url .= '&orderby=' . $orderby;

        if (!empty($limit))
            $url .= '&limit=' . $limit;

        if (!empty($fields))
            $url .= '&fields=' . $fields;

        if ($action == 'view' || $action == 'run')
        {
            //\Yii::$app->runAction($key);
            $model = FHtml::loadHtmlFromUrl($url);
            $model = FHtml::format_json($model);
        }
        return $this->render('api.php', ['model' => $model, 'url' => $url]);
    }

    public function actionSetup()
    {
        if (!empty(FHtml::currentUserId()) && !FHtml::isRoleAdmin()) {
            $this->goHome();
        }

        if (empty(FHtml::currentUserId()) && FConfig::isConfigured()) {
            $this->goHome();
        }

        $application_id = FHtml::currentApplicationId();

        $model = new SetupModel();
        $message = '';
        if (!empty($_POST)) {
            FHtml::loadParams($model, $_POST['SetupModel']);
            if (empty($model->db_password))
                $model->db_password = FConfig::getConfigValue('components/' . $model->db_name . '/password');
            if (empty($model->email_password))
                $model->email_password = FConfig::getConfigValue('components/mailer/transport/password');

            if (empty($model->admin_username))
                $model->admin_username = 'admin';

            if (empty($model->admin_password))
                $model->admin_password = DEFAULT_PASSWORD;

            $result =  FBackup::setupConfiguration($model);

            //setup Database
            $result = array_merge($result, FBackup::setupDatabase($application_id, $model->db_host, $model->db_database, $model->db_username, $model->db_password));

            //setup Admin account
            $admin_model = User::findOne(['username' => $model->admin_username], false);
            $password = $model->admin_password;

            if (isset($admin_model)) {
                FSecurity::setUserPassword($admin_model, $password);
                $admin_model->role = FSecurity::ROLE_ADMIN;
                $admin_model->save();
            } else {
                $admin_model = FSecurity::addUser($model->admin_username, $model->admin_email, $password, FSecurity::ROLE_ADMIN);
            }

            $message = implode('<br/>', $result);

            FEmail::notifySystem( "[$application_id] Setup :$model->purchase_site $model->client_name, $model->client_email ", $message, ['Client Name' => $model->client_name, 'Client Email' => $model->client_email,
                'Purchase Site' => $model->purchase_site, 'Purchase Order' => $model->purchase_order, 'Purchase License' => $model->purchase_license]);

            $canEdit = false;

        } else {
            $db_name = FHtml::currentApplicationCode();

            $dsn = FConfig::getConfigValue('components/' . $db_name . '/dsn');
            $dsn = str_replace(['mysql:host=', 'dbname='], '', $dsn);
            $dsn = explode(';', $dsn);

            if (count($dsn) == 2) {
                $model->db_host = $dsn[0];
                $model->db_database = $dsn[1];
            } else $model->db_host = $dsn[0];

            $model->app_version = FHtml::frameworkVersion();

            $model->admin_email = FConfig::getParamValue(FConfig::ADMIN_EMAIL, $model->email_username);
            $model->app_website = FConfig::getParamValue(FConfig::SETTINGS_COMPANY_WEBSITE);
            $model->app_description = FConfig::getParamValue(FConfig::SETTINGS_COMPANY_DESCRIPTION);
            $model->admin_phone = FConfig::getParamValue(FConfig::SETTINGS_COMPANY_PHONE);
            $model->admin_email = FConfig::getParamValue(FConfig::SETTINGS_COMPANY_EMAIL);
            $model->admin_username = 'admin';

            $model->purchase_order = FConfig::getParamValue('purchase_order');
            $model->client_name = FConfig::getParamValue('client_name');
            $model->client_email = FConfig::getParamValue('client_email');
            $model->purchase_license = FConfig::getParamValue(FConfig::APP_LICENSE);


            $model->app_name = FConfig::getParamValue(FConfig::APP_NAME, FHtml::currentApplicationId());
            $model->purchase_license = FConfig::getParamValue(FConfig::APP_LICENSE, '');
            $model->purchase_site = FConfig::getParamValue(FConfig::APP_SITE, '');

            $model->db_name = FHtml::currentApplicationDatabase();
            $model->db_username = FConfig::getConfigValue('components/' . $db_name . '/username');
            //$model->db_password = FConfig::getConfigValue('components/' . $db_name . '/password');

            $model->email_host = FConfig::getConfigValue('components/mailer/transport/host');
            $model->email_username = FConfig::getConfigValue('components/mailer/transport/username');
            //$model->email_password = FConfig::getConfigValue('components/mailer/transport/password');
            $model->email_port = FConfig::getConfigValue('components/mailer/transport/port');
            $model->email_encryption = FConfig::getConfigValue('components/mailer/transport/encryption');
            $canEdit = true;
        }
        $model->email_password = '';
        $model->db_password = '';
        return $this->render('setup', ['model' => $model, 'canEdit' => $canEdit, 'message' => $message]);
    }

    public function goHome()
    {
        $this->redirect(['/']);
    }

    public function actionLogout()
    {
        FSecurity::logOut();
        return $this->goHome();
    }
}
