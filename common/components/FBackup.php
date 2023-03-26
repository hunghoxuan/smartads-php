<?php
/**
 * @link https://orlov.io/
 * @copyright Copyright (c) 2016 Ivan Orlov
 * @author Ivan Orlov <gnasimed@gmail.com>
 */

namespace common\components;

use function GuzzleHttp\Psr7\str;
use kcfinder\zipFolder;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\caching\FileCache;
use yii\data\ArrayDataProvider;
use yii\db\Connection;
use yii\helpers\BaseInflector;
use yii\helpers\FileHelper;
use yii\helpers\StringHelper;
use yii\swiftmailer\Mailer;
use yii\web\HttpException;

/**
 * Backup component
 *
 * @package demi\backup
 */
class FBackup extends FDatabase
{
    const EXCLUDED_TABLES = ['user', 'application'];

    public $directories = [];
    public $enableZip = false;
    public $mysqldump = '';
    public $backupFilename = '';
    public $backupsFolder;

    /** @var string Path/Alias to folder for backups storing. e.g. "@app/backups" */
    // public $layout = '//layout2';

    public function getModule() {
        return FHtml::currentControllerObject()->module;
    }

    protected function backupDatabaseStart($saveTo = '', $addcheck = true) {
        if (empty($saveTo))
            $file_name = $this->path . $this->getBackupFilename('database') . '.sql';
        else
            $file_name = $saveTo . $this->getBackupFilename('database') . '.sql';

        $this->file_name = $file_name;

        $this->fp = fopen ( $this->file_name, 'w+' );
        echo $this->file_name;
        if ($this->fp == null)
            return false;
        fwrite ( $this->fp, '-- -------------------------------------------' . PHP_EOL );
        if ($addcheck) {
            fwrite ( $this->fp, 'SET AUTOCOMMIT=0;' . PHP_EOL );
            fwrite ( $this->fp, 'START TRANSACTION;' . PHP_EOL );
            fwrite ( $this->fp, 'SET SQL_QUOTE_SHOW_CREATE = 1;' . PHP_EOL );
        }
        fwrite ( $this->fp, 'SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;' . PHP_EOL );
        fwrite ( $this->fp, 'SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;' . PHP_EOL );
        fwrite ( $this->fp, '-- -------------------------------------------' . PHP_EOL );
        $this->writeComment ( 'START BACKUP' );
        return true;
    }

    protected function backupDatabaseEnd($addcheck = true) {
        fwrite ( $this->fp, '-- -------------------------------------------' . PHP_EOL );
        fwrite ( $this->fp, 'SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;' . PHP_EOL );
        fwrite ( $this->fp, 'SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;' . PHP_EOL );

        if ($addcheck) {
            fwrite ( $this->fp, 'COMMIT;' . PHP_EOL );
        }
        fwrite ( $this->fp, '-- -------------------------------------------' . PHP_EOL );
        $this->writeComment ( 'END BACKUP' );
        fclose ( $this->fp );
        $this->fp = null;
    }

    public function cleanAllDatabases($redirect = true) {
        if (!FHtml::isRoleAdmin()) {
            Yii::$app->user->setFlash ( 'error', "Is not admin, could not delete" );
            return false;
        }
        $ignore = self::EXCLUDED_TABLES;

        $tables = $this->getTables ();

        if (! $this->backupDatabaseStart() ()) {
            // render error
            Yii::$app->user->setFlash ( 'error', "Error" );
        }

        $message = '';

        foreach ( $tables as $tableName ) {
            if (in_array ( $tableName, $ignore ))
                continue;
            fwrite ( $this->fp, '-- -------------------------------------------' . PHP_EOL );
            fwrite ( $this->fp, 'DROP TABLE IF EXISTS ' . addslashes ( $tableName ) . ';' . PHP_EOL );
            fwrite ( $this->fp, '-- -------------------------------------------' . PHP_EOL );

            $message .= $tableName . ',';
        }

        $this->backupDatabaseEnd();

        // logout so there is no problme later .
        Yii::$app->user->logout ();

        $this->execSqlFile ( $this->file_name );
        unlink ( $this->file_name );
        $message .= ' are deleted.';
        Yii::$app->session->setFlash ( 'success', $message );
    }

    public function deleteFile($id) {
        $list = $this->getFileList ();
        $list = array_merge ( $list, $this->getFileList ( '*.zip' ) );
        $list = array_reverse($list);
        $file = $list [$id];
        if (isset ( $file )) {
            $sqlFile = $this->path . basename ( $file );
            if (file_exists ( $sqlFile ))
                unlink ( $sqlFile );
        } else
            throw new HttpException( 404, Yii::t ( 'app', 'File not found' ) );
    }

    public function download($file = null) {
        if (isset ( $file )) {
            $sqlFile = $this->path . basename ( $file );
            if (file_exists ( $sqlFile )) {
                $request = Yii::$app->getRequest ();
                $request->sendFile( basename ( $sqlFile ), file_get_contents ( $sqlFile ) );
            }
        }
        throw new HttpException ( 404, Yii::t ( 'app', 'File not found' ) );
    }

    protected function getFileList($ext = '*.sql') {
        $path = $this->path;
        $dataArray = array ();
        $list = array ();
        $list_files = glob ( $path . $ext );
        if ($list_files) {
            $list = array_map ( 'basename', $list_files );
            sort ( $list );
        }
        return $list;
    }

    protected function initBackup() {
        $list = $this->getFileList ();
        $list = array_merge ( $list, $this->getFileList ( '*.zip' ) );
        $dataArray = [ ];

        foreach ( $list as $id => $filename ) {
            $columns = array ();
            $columns ['id'] = $id;
            $columns ['name'] = basename ( $filename );
            $columns ['size'] = filesize ( $this->path . $filename );

            $columns ['create_time'] = date ( 'Y-m-d H:i:s', filectime ( $this->path . $filename ) );
            $columns ['modified_time'] = date ( 'Y-m-d H:i:s', filemtime ( $this->path . $filename ) );
            if (date ( 'M-d-Y' . ' \a\t ' . ' g:i A', filemtime ( $this->path . $filename ) ) > date ( 'M-d-Y' . ' \a\t ' . ' g:i A', filectime ( $this->path . $filename ) )) {
                $columns ['modified_time'] = date ( 'M-d-Y' . ' \a\t ' . ' g:i A', filemtime ( $this->path . $filename ) );
            }

            $dataArray [] = $columns;
        }

        $dataProvider = new ArrayDataProvider( [
            'allModels' => array_reverse ( $dataArray ),
            'sort' => [
                'attributes' => [
                    'modified_time' => SORT_ASC
                ]
            ]
        ] );
        return $dataProvider;
    }

    public function restoreFiles($filename) {
        $sqlZipFile = $this->path . basename ( $filename );
        $status = FFile::unzip($sqlZipFile );
        return $status;
    }

    public function restoreDatabase($filename) {
        if (is_file($filename))
            return FHtml::executeFileSql($filename);
        return false;
    }

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        $this->db = FHtml::currentDbName();
        $this->backupsFolder = $this->getBackupFolder();
        $application_id = FHtml::currentApplicationId();
        $this->directories = [$application_id => "@applications/$application_id"];

        // Check backup folder
        if (!is_dir($this->backupsFolder)) {
            FHtml::createDir($this->backupsFolder);
        } elseif (!is_writable($this->backupsFolder)) {
            chmod($this->backupsFolder, 0777);
        }

        // Add site database to primary databases list
        $dbComponent = FHtml::currentDb();
        /** @var \yii\db\Connection $dbComponent */

        // Get default database name
        $dbName = $dbComponent->createCommand('select database()')->queryScalar();
        $this->databases[$dbName] = [
            'db' => $dbName,
            'host' => 'localhost',
            'username' => $dbComponent->username,
            'password' => addcslashes($dbComponent->password, '\''),
        ];

        // Set db name if not exists in databases config array
        foreach ($this->databases as $name => $params) {
            if (!isset($params['db'])) {
                $this->databases[$name]['db'] = $name;
            }
        }
    }

    /**
     * Create dump of all directories and all databases and save result to bakup folder with timestamp named tar-archive
     *
     * @return string Full path to created backup file
     * @throws Exception
     */
    public function backup($directories = [], $databases = '', $folder = '')
    {
        if (empty($folder))
            $folder = $this->getBackupFolder();

        $files = $this->backupFiles($directories, $folder);
        $db = $this->backupDatabase($databases, $folder);

        return $files;
    }

    /**
     * Create backups for $directories and save it to "<backups folder>"
     *
     * @param string $saveTo
     *
     * @return bool
     */
    public function backupFiles($directories = [], $saveTo = '', $excluded = [])
    {
        if (empty($saveTo))
            $saveTo = $this->getBackupFolder();

        if (is_string($directories))
            $directories = [$directories => $directories];
        else if (empty($directories))
            $directories = $this->directories;

        $result = [];

        foreach ($directories as $name => $value) {
            if (is_array($value)) {
                // if exists config, use it
                $folder = Yii::getAlias($value['path']);
                $regex = isset($value['regex']) ? $value['regex'] : null;
            } else {
                $regex = null;
                $folder = Yii::getAlias($value);
            }

            $archiveFile = $saveTo . DIRECTORY_SEPARATOR . self::getBackupFilename(!empty($name) ? $name : $value) . '.zip';
            $archiveFile = FHtml::getFullFileName(str_replace('//', '/', $archiveFile));
            //FHtml::createFile($archiveFile, ''); //if $archive folder does not exist then create dummy file

            FFile::zipFolder($folder, $archiveFile, $excluded);
            $result[] = $archiveFile;
        }

        return $result;
    }

    public function getBackupFolders($application_id = '') {
        if (empty($application_id))
            $application_id = FHtml::currentApplicationId();
        $folder = FHtml::getRootFolder() . "/backup/$application_id";

        $arr = FFile::listFolders($folder, false);
        return $arr;
    }

    /**
     * Create backups for $databases and save it to "<backups folder>/sql"
     *
     * @param string $saveTo
     *
     * @return bool
     */
    public function backupDatabase($dbName = '', $saveTo = '',   $backupSchema = true, $backupData = true)
    {
        if (empty($saveTo))
            $saveTo = $this->getBackupFolder();

        if (!StringHelper::endsWith($saveTo, '/'))
            $saveTo = $saveTo . '/';

        $tables = $this->getTables ($dbName);

        if (! $this->backupDatabaseStart($saveTo)) {
            return false;
        }

        if ($backupSchema) {
            foreach ($tables as $tableName) {
                $this->buildCreateTableSql($tableName);
            }
        }

        if ($backupData) {
            foreach ($tables as $tableName) {
                $this->buildInsertDataSql($tableName);
            }
        }

        $this->backupDatabaseEnd();

        return true;
    }

    public function backupTables($tables = [], $saveTo = '', $multiple_files = false,  $backupSchema = true, $backupData = true) {

        $saveTo = $this->getBackupFolder($saveTo);
        if (!StringHelper::endsWith($saveTo, '/'))
            $saveTo = $saveTo . '/';

        if (is_string($tables)) {
            $tables = [$tables];
            $multiple_files = true;
        }

        if (empty($tables))
            $tables = $this->getTables ();

        $total = ''; $total_create = ''; $total_data = '';
        $result = [];
        foreach ($tables as $tableName) {
            $sql = '';
            $sql_create = $backupSchema ?  $this->getComment($tableName . ' SCHEMA') . $this->buildCreateTableSql($tableName) : '';
            $sql_data = $backupData ?  $this->getComment($tableName . ' DATA') . $this->buildInsertDataSql($tableName) : '';

            $sql = $sql_create . $sql_data;

            $total .= $sql;
            $total_create .= $sql_create;
            $total_data .= $sql_data;

            if ($multiple_files) {
                FFile::createFile($saveTo . $tableName . '/structure.sql', $sql_create);
                FFile::createFile($saveTo . $tableName . '/data.sql', $sql_data);
                FFile::createFile($saveTo . $tableName . '/all.sql',  $sql);

                $result[] = $saveTo . $tableName . '/all.sql';
            }
        }

        if (!$multiple_files) {
            FFile::createFile($saveTo . 'all.sql', $total);
            FFile::createFile($saveTo . 'data.sql', $total_data);
            FFile::createFile($saveTo . 'create.sql', $total_create);

            $result[] = $saveTo . 'all' . '.sql';

        }

        return count($result) == 1 ? $result[0] : $result;
    }

    /**
     * Delete expired files
     *
     * @return bool
     */
    public function deleteJunk()
    {
        if (empty($this->expireTime)) {
            // Prevent deleting if expireTime is disabled
            return true;
        }

        $backupsFolder = Yii::getAlias($this->backupsFolder);
        // Calculate expire date
        $expireDate = time() - $this->expireTime;

        $filter = function ($path) use ($expireDate) {
            // Check extension
            if (substr($path, -4) !== '.tar') {
                return false;
            }

            if (is_file($path) && filemtime($path) <= $expireDate) {
                // if the time has come - delete file
                return true;
            }

            return false;
        };

        // Find expired backups files
        $files = FileHelper::findFiles($backupsFolder, ['recursive' => false, 'filter' => $filter]);

        foreach ($files as $file) {
            if (@unlink($file)) {
                Yii::info('Backup file was deleted: ' . $file, 'demi\backup\Component::deleteJunk()');
            } else {
                Yii::error('Cannot delete backup file: ' . $file, 'demi\backup\Component::deleteJunk()');
            }
        }

        return true;
    }

    /**
     * Generate backup filename
     *
     * @return string
     */
    public function getBackupFilename($prefix = '')
    {
        $prefix = str_replace('@', '', $prefix);
        $prefix = str_replace('/', '_', $prefix);
        $prefix = str_replace('-', '_', $prefix);

        if (is_callable($this->backupFilename)) {
            return call_user_func($this->backupFilename, $this);
        } else {
            $file = $this->backupFilename;
            if (empty($file))
                $file = '{date}';

            $file = str_replace('{date}', date('Y.m.d'), $file);

            return $prefix . '_'. $file;
        }
    }

    /**
     * Get full path to backups folder.
     * Directory will be automatically created.
     *
     * @return string
     * @throws Exception
     */
    public function getBackupFolder($folder = '')
    {
        return $this->getPath($folder);
    }

    /**
     * Rewrites the configuration file
     */
    public static function setupConfiguration($setupModel = null)
    {
        $result = [];
        $application_id = FHtml::currentApplicationId();

        // Get Current Configuration
        $config = FConfig::getConfigFileContent();

        $root_folder = FHtml::getRootFolder();

        // Add Application Name to Configuration

        // Add Caching
        if (!isset($config['cache']['class']))
            $config['components']['cache']['class'] = FileCache::className();

        $config['components']['mail']['class'] = Mailer::className();
        $config['components']['mail']['useTransport'] = $setupModel->email_useTransport;
        $config['components']['mail']['transport']['class'] = 'Swift_SmtpTransport';
        $config['components']['mail']['transport']['host'] = $setupModel->email_host;
        $config['components']['mail']['transport']['username'] = $setupModel->email_username;
        $config['components']['mail']['transport']['password'] = $setupModel->email_password;
        $config['components']['mail']['transport']['port'] = $setupModel->email_port;
        $config['components']['mail']['transport']['encryption'] = $setupModel->email_encryption;

        $db_name = $setupModel->db_name;
        if (empty($db_name))
            $db_name = $application_id;

        $config['components'][$db_name]['class'] = Connection::className();
        $config['components'][$db_name]['dsn'] =  "mysql:host=$setupModel->db_host;dbname=$setupModel->db_database";
        $config['components'][$db_name]['username'] = $setupModel->db_username;
        $config['components'][$db_name]['password'] = $setupModel->db_password;

        $config['components'][$db_name]['charset'] = 'utf8';
        $config['components'][$db_name]['enableSchemaCache'] = true;
        $config['components'][$db_name]['schemaCacheDuration'] = 3600;
        $config['components'][$db_name]['schemaCache'] = 'session';

        FConfig::setConfigFileContent($config);
        $result[] = "Configure Database & Email at: [$root_folder/config/main.php] .... [DONE]" ;

        if (!empty($setupModel->admin_email) && !empty($setupModel->admin_name)) {

        }

        $params = FHtml::getParamFileContent();
        $params[FConfig::APP_INSTALLED] = date('Y-m-d H:i:s');
        $params[FConfig::APP_NAME] = $setupModel->app_name;
        $params[FConfig::APP_VERSION] = FHtml::frameworkVersion();
        $params[FConfig::APP_LICENSE] = $setupModel->purchase_license;
        $params['purchase_order'] = $setupModel->purchase_order;

        $params[FConfig::APP_SITE] = $setupModel->purchase_site;
        $params[FConfig::SETTINGS_COMPANY_DESCRIPTION] = $setupModel->app_description;
        $params[FConfig::SETTINGS_COMPANY_WEBSITE] = $setupModel->app_website;
        $params[FConfig::SETTINGS_COMPANY_PHONE] = $setupModel->admin_phone;
        $params[FConfig::SETTINGS_COMPANY_EMAIL] = $setupModel->admin_email;

        $params['client_name'] = $setupModel->client_name;
        $params['client_email'] = $setupModel->client_email;

        $params[FConfig::APP_SECRET] = FSecurity::generateHash([$params[FConfig::APP_NAME], $setupModel->purchase_site, $setupModel->purchase_license, $params[FConfig::APP_INSTALLED]]);
        FConfig::setParamFileContent($params);

        $result[] = "Save Application Settings at: [$root_folder/applications/$application_id/config/params.php] .... [DONE]" ;

        return $result;
    }

    public static function setupDatabase($application_id = '', $server = 'localhost', $database = '', $username = '', $password = '') {
        if (empty($application_id))
            $application_id = FHtml::currentApplicationId();

        $root_folder = FHtml::getRootFolder();

        $result = [];

        if (!empty($database))
            FBackup::createDatabase($database, $server);

        if (!empty($username))
            FBackup::createDatabaseUser($username, $password, $server, $database);

        $connection = new \yii\db\Connection([
              'dsn' => "mysql:host=$server;dbname=$database",
              'username' => $username,
              'password' => $password,
          ]);
        $connection->open();

        $sql_array = ['clean.sql', 'structure.sql', 'data.sql', 'all.sql'];
        $a = false;
        foreach ($sql_array as $sql_file) {
            $file_all = FFile::readFile(FHtml::getRootFolder() . "/applications/$application_id/setup/$sql_file");
            if (!empty($file_all))
            {
                try {
                    $connection->createCommand($file_all)->execute();
                    $a = true;
                    $result[] = "Execute SQL file: [$root_folder/applications/$application_id/setup/$sql_file] .... [DONE]";
                } catch (\yii\db\Exception $ex) {
                    FHtml::addError($ex);
                }
            }
        }

       if (!$a) {
            $contents = FFile::listFiles(FHtml::getRootFolder() .  "/applications/$application_id/setup");
            foreach ($contents as $file_name => $file) {
                try {
                    $connection->createCommand(FFile::readFile($file))->execute();
                    $result[] = "Execute SQL file: [$file] .... [DONE]" ;
                } catch (\yii\db\Exception $ex) {
                   FHtml::addError($ex);
               }
            }
        }

        return $result;
    }

    public static function createDatabaseUser($user, $password, $server = 'localhost', $database = '') {

        $sql1 = "SELECT 1 FROM `mysql`.`user` WHERE `user` = '$user'";
        $check = FHtml::currentDb('db')->createCommand($sql1)->queryScalar();

        if ($check)
            return false;

        try {
            $sql = "CREATE USER IF NOT EXISTS '$user'@'$server' IDENTIFIED BY '$password'; GRANT ALL PRIVILEGES ON $database.* TO '$user'@'$server' identified by '$password'; FLUSH PRIVILEGES;";
            FHtml::currentDb('db')->createCommand($sql)->execute();
        } catch (\yii\db\Exception $ex) {
            FHtml::addError($ex);
        }
    }

    public static function createDatabase($databasename = '', $server = 'localhost') {
        $sql = "CREATE DATABASE IF NOT EXISTS $databasename; ";
        FHtml::currentDb('db')->createCommand($sql)->execute();
    }

    public static function loadExcelContent($file, $sheet_name = '', $file_type = 'excel')
    {
        if (is_object($file)) {
            $model = $file;
            $file = FHtml::getFullUploadFolder($model) . '/' . $model->file;
        }

        if (!is_file($file))
            return [];

        $config = [];
        if (!empty($sheet_name))
            $config = array_merge($config, ['getOnlySheet' => $sheet_name]);

        $data = FExcel::import($file, $config, $file_type); // $config is an optional
        return $data;
    }

    public static function import($file, $table = '', $columns = [], $first_row = 1, $last_row = -1, $default_values = [], $key_fields = ['id', 'code'], $importData = true) {
        $model = null;
        $file_type = '';

        if (is_object($file)) {
            $model = $file;
            $file = FHtml::getFullUploadFolder($model) . '/' . $model->file;
            $table = $model->object_type;
            $file_type = $model->file_type;

            $first_row = isset($model->first_row) ? $model->first_row : 1;
            $last_row = isset($model->last_row) ? $model->last_row : -1;
            $columns = !empty($model->columns) ? FHtml::decode($model->columns, ":;") : $columns;
            $key_fields = !empty($model->key_columns) ? FHtml::decode($model->key_columns, ',') : $key_fields;
            $default_values = !empty($model->default_values) ? FHtml::decode($model->default_values, ":;") : $default_values;
        }

        $columns = FModel::getKeyValueArray($columns, false, '', false, 'field', 'excel_column');
        $default_values = FModel::getKeyValueArray($default_values, false, '', false, 'field', 'value');

        $result = [];
        $fail_result = [];
        $empty_result = [];
        $ids_success = [];
        $ids_fail = [];
        $excel_columns = [];
        $excel_columns = array_merge(['#'], $excel_columns);
        $sheet_name = '';
        $tableColumns = [];
        $data = [];

        if (is_file($file)) {
            $path_info = pathinfo($file);

            if (empty($file_type))
            {
                $file_type = isset($path_info['extension']) ? $path_info['extension'] : 'excel';
            }

            if (empty($table)) {
                $table = $path_info['filename'];
            }

            $data = self::loadExcelContent($file, isset($model) ? $model->sheet_name : $sheet_name, $file_type);

            if (empty($columns) && isset($data['columns'])) {
                $tableColumns = $data['columns'];
                $columns = [];
                foreach ($tableColumns as $name) {
                    $columns[$name] = $name;
                }
            } else
                $tableColumns = array_column($columns, 'field');

            $rows = isset($data['rows']) ? $data['rows'] : [];
            $data = isset($data['data']) ? $data['data'] : $data;

            if (!FHtml::isTableExisted($table) && !empty($tableColumns)) {
                FHtml::createTable($table, $tableColumns);
                if (!$importData)
                    return;
            }

            if ($last_row == -1)
                $last_row = count($data);


            foreach ($columns as $data_field => $excel_field) {
                if (is_numeric($data_field)) {
                    $data_field = $excel_field;
                }
                $excel_columns[] = "$excel_field [$data_field]";
            }

            $excel_columns = array_merge($excel_columns, ['id', 'action', 'result']);

            $model_columns = FHtml::getTableColumns($table);

            if (!empty($data) && !empty($table)) {
                $model = FHtml::createModel($table);
                if (isset($model)) {
                    $i = 0;
                    $result = [];

                    foreach ($data as $data_item) {
                        $i += 1;

                        if ($i < $first_row)
                            continue;

                        if ($last_row > 1 && $i > $last_row)
                            break;

                        $condition = [];
                        foreach ($columns as $data_field => $excel_field) {
                            if (is_numeric($data_field)) {
                                $data_field = $excel_field;
                            }
                            if (FHtml::field_exists($model, $data_field) && in_array($data_field, $key_fields)) {
                                $condition = array_merge($condition, [$data_field => FHtml::getFieldValue($data_item, $excel_field)]);
                            }
                        }
                        if (!empty($condition)) {
                            $model = FHtml::findOne($table, $condition);
                        }

                        if (!isset($model)) {
                            $model = FHtml::createModel($table);
                            $action = 'created';
                        } else {
                            $action = 'updated';
                        }

                        $data_validated = false;
                        $col_result = ['#' => $i];

                        foreach ($columns as $data_field => $excel_field) {
                            if (is_numeric($data_field)) {
                                $data_field = $excel_field;
                            }
                            if (!key_exists($data_field, $model_columns))
                                continue;

                            $value = trim(FHtml::getFieldValue($data_item, $excel_field));
                            $value = self::processRawData($value, $model_columns[$data_field]->type);

                            if (!empty($value))
                                $data_validated = true;

                            $model->setFieldValue($data_field, $value);
                            $col_result = array_merge($col_result, ["$excel_field [$data_field]" => $value]);
                        }

                        if (!$data_validated) {
                            $empty_result[] = $col_result;
                            continue;
                        }

                        foreach ($default_values as $field => $value) {
                            $model->setFieldValue($field, $value);
                        }

                        $saved = $model->save();

                        if (!empty($model->id)) {
                            $url = FHtml::createModelUrl($table, FHtml::isAuthorized('update', $table) ? 'update' : 'view', ['id' => $model->id]);
                            $a = "<a href='$url' data-pjax='0' target='_blank'>$model->id </a>";
                        } else {
                            $a = '';
                        }

                        if (!$saved) {
                            $ids_fail[] = $model->id;
                            $col_result = array_merge($col_result, ['id' => $a, 'action' => $action, 'result' => '<span class="text-dander">' . FHtml::addError($model->errors, $col_result) . '</span>']);
                            $fail_result[] = $col_result;

                        } else {
                            $ids_success[] = $model->id;
                            $col_result = array_merge($col_result, ['id' => $a, 'action' => $action, 'result' => '<span class="text-success">' . 'OK' . '</span>']);
                        }

                        $result[] = $col_result;
                    }
                } else {
//                $rows = isset($data['rows']) ? $data['rows'] : [];
//                if (is_array($rows)) {
//                    FDatabase::instance()->batchInsert($table, $columns1, $rows);
//                }
                    foreach ($data as $row) {
                        if (!empty($default_values))
                            $row = array_merge($default_values);
                        $existed = null;
                        $condition = '';
                        if (!empty($key_fields)) {
                            $sql = "SELECT COUNT(*) FROM $table WHERE 1 = 1 ";
                            $where = [];
                            foreach ($key_fields as $i => $key_field) {
                                if (FHtml::field_exists($table, $key_field) && isset($row[$key_field])) {
                                    $where[] = "$key_field = '" . FHtml::getSqlValue($row[$key_field]) . "'";
                                }
                            }

                            if (!empty($where)) {
                                $condition = implode(' AND ', $where);
                                $sql .= $condition;
                                $existed = FHtml::executeSql($sql);
                            }
                        }
                        if (empty($existed))
                            FDatabase::instance()->insert($table, $row);
                        else
                            FDatabase::instance()->update($table, $row, $condition);

                    }
                }
            }
        }

        $result1 = [];
        $result1['message'] = FHtml::showCurrentMessages();
        $result1['table'] = $table;
        $result1['columns'] = $tableColumns;

        $result1['data'] = $result;
        $result1['fail'] = $fail_result;
        $result1['skip'] = $empty_result;
        $result1['excel_data'] = $data;
        $result1['excel_columns'] = $excel_columns;
        $result1['ids_success'] = $ids_success;
        $result1['ids_fail'] = $ids_fail;

        return $result1;
    }

    public static function processRawData($value, $type) {
        $value = ucfirst(trim($value));
        if (in_array($type, ['integer', 'float' ,'decimal', 'double']))
            $value = str_replace(',', '', $value);
        $value = FHtml::strReplace($value, ['  ' => ' ', "\n" => ' ', '( ' => '(', ' )' => ')', "'" => '"']);
        return $value;
    }
}