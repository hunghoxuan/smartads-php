<?php
/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "<?= $generator->generateTableName($tableName) ?>".
 */
namespace common\components;

use backend\models;
use backend\modules\ecommerce\models\Product;
use backend\modules\system\models\SettingsSchema;
use common\config\FSettings;
use common\models\BaseDataList;
use common\models\BaseDataObject;
use common\widgets\FTabs;
use Imagine\Exception\InvalidArgumentException;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\db\ColumnSchemaBuilder;
use yii\db\Migration;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\StringHelper;
use common\components\FConstant;
use common\widgets\FUploadedFile;

class FDatabase extends Migration
{
    public $menu = [];
    public $tables = [];
    public $fp;
    public $file_name;
    public $_path = null;
    public $back_temp_file = '';
    public $databases = [];
    public $backupFileName = '{application_id}';
    public $backupFolder = '/backup/{application_id}/{date}';

    private static $_instance;
    public static function instance() {
        if (!isset(static::$_instance))
            static::$_instance = new FDatabase();

        return static::$_instance;
    }

    public function init()
    {
        $this->db = FHtml::currentDbName();
        parent::init();
    }

    public function currentDb($dbName = '') {
        return FHtml::currentDb($dbName);
    }

    public function execSqlFile($sqlFile) {
        return FModel::executeFileSql($sqlFile);
    }

    protected function getPath($backupFolder = '') {
        if (empty($backupFolder))
            $backupFolder = $this->backupFolder;

        $this->_path = FFile::getFullPath($backupFolder);
        return $this->_path;
    }

    public function getTables($dbName = null) {
        $sql = 'SHOW TABLES';
        $cmd = $this->currentDb($dbName)->createCommand($sql);
        $tables = $cmd->queryColumn();
        return $tables;
    }

    public function writeComment($string) {
        $result = $this->getComment($string);
        fwrite($this->fp, $result);
    }

    public function getComment($string) {
        $result = '';
        $result .= '-- -------------------------------------------' . PHP_EOL;
        $result .= '-- ' . $string . PHP_EOL;
        $result .= '-- -------------------------------------------' . PHP_EOL;
        return $result;
    }

    public function buildCreateTableSql($tableName, $fp = null) {
        if (!isset($fp) && isset($this->fp))
            $fp = $this->fp;

        $sql = 'SHOW CREATE TABLE ' . $tableName;
        $cmd = $this->currentDb()->createCommand($sql);
        $table = $cmd->queryOne();
        $create_query = $table['Create Table'] . ';';
        $create_query = preg_replace('/^CREATE TABLE/', 'CREATE TABLE IF NOT EXISTS', $create_query);
        $create_query = preg_replace('/AUTO_INCREMENT\s*=\s*([0-9])+/', '', $create_query);
        if ($fp) {
            $this->writeComment('TABLE `' . addslashes($tableName) . '`');
            $final = 'DROP TABLE IF EXISTS `' . addslashes($tableName) . '`;' . PHP_EOL . $create_query . PHP_EOL . PHP_EOL;
            fwrite($fp, $final);
            return $final;
        } else {
            $this->tables[$tableName]['create'] = $create_query;
            $final = 'DROP TABLE IF EXISTS `' . addslashes($tableName) . '`;' . PHP_EOL . $create_query . PHP_EOL . PHP_EOL;
            return $final;
        }
    }

    public function buildDropTableSql($tableName, $fp = null) {
        if (!isset($fp) && isset($this->fp))
            $fp = $this->fp;

        if ($fp) {
            $this->writeComment('TABLE `' . addslashes($tableName) . '`');
            $final = 'DROP TABLE IF EXISTS `' . addslashes($tableName) . '`;' . PHP_EOL ;
            fwrite($fp, $final);
            return $final;
        } else {
            $final = 'DROP TABLE IF EXISTS `' . addslashes($tableName) . '`;' . PHP_EOL;
            return $final;
        }
    }

    public function buildCleanTableSql($tableName, $fp = null) {
        if (!isset($fp) && isset($this->fp))
            $fp = $this->fp;

        if ($fp) {
            $this->writeComment('TABLE `' . addslashes($tableName) . '`');
            $final = 'TRUNCATE TABLE `' . addslashes($tableName) . '`;' . PHP_EOL ;
            fwrite($fp, $final);
            return $final;
        } else {
            $final = 'TRUNCATE TABLE `' . addslashes($tableName) . '`;' . PHP_EOL;
            return $final;
        }
    }

    public function buildInsertDataSql($tableName, $fp = null) {
        $sql = 'SELECT * FROM ' . $tableName;
        $cmd = $this->currentDb()->createCommand ( $sql );
        $dataReader = $cmd->query ();

        if (!isset($fp) && isset($this->fp))
            $fp = $this->fp;

        if ($fp) {
            $this->writeComment ( 'TABLE DATA ' . $tableName );
        }

        $final = '';
        foreach ( $dataReader as $data ) {
            $data_string = '';
            $itemNames = array_keys ( $data );
            $itemNames = array_map ( "addslashes", $itemNames );
            $items = join ( '`,`', $itemNames );
            $itemValues = array_values ( $data );
            $itemValues = array_map ( "addslashes", $itemValues );
            $valueString = join ( "','", $itemValues );
            $valueString = "('" . $valueString . "'),";
            $values = "\n" . $valueString;
            if ($values != "") {
                $data_string .= "INSERT INTO `$tableName` (`$items`) VALUES" . rtrim ( $values, "," ) . ";" . PHP_EOL;
            }
            if ($fp) {
                fwrite ( $fp, $data_string );
            }
            $final .= $data_string . PHP_EOL;
        }

        if ($fp) {
            $this->writeComment ( 'TABLE DATA ' . $tableName );
            fwrite ( $fp, PHP_EOL . PHP_EOL . PHP_EOL );
        } else {

        }
        return $final;
    }


    public function defaultTableOptions() {
        $tableOptions = null;
        $this->db = FHtml::currentDb();
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        return $tableOptions;
    }

    /**
     * Executes a SQL statement.
     * This method executes the specified SQL statement using [[db]].
     * @param string $sql the SQL statement to be executed
     * @param array $params input parameters (name => value) for the SQL execution.
     * See [[Command::execute()]] for more details.
     */
    public function execute($sql, $params = [])
    {
        $this->db->createCommand($sql)->bindValues($params)->execute();
    }

    /**
     * Creates and executes an INSERT SQL statement.
     * The method will properly escape the column names, and bind the values to be inserted.
     * @param string $table the table that new rows will be inserted into.
     * @param array $columns the column data (name => value) to be inserted into the table.
     */
    public function insert($table, $columns)
    {
        $this->db->createCommand()->insert($table, $columns)->execute();
    }

    /**
     * Creates and executes an batch INSERT SQL statement.
     * The method will properly escape the column names, and bind the values to be inserted.
     * @param string $table the table that new rows will be inserted into.
     * @param array $columns the column names.
     * @param array $rows the rows to be batch inserted into the table
     */
    public function batchInsert($table, $columns, $rows)
    {
        $this->db->createCommand()->batchInsert($table, $columns, $rows)->execute();
    }

    /**
     * Creates and executes an UPDATE SQL statement.
     * The method will properly escape the column names and bind the values to be updated.
     * @param string $table the table to be updated.
     * @param array $columns the column data (name => value) to be updated.
     * @param array|string $condition the conditions that will be put in the WHERE part. Please
     * refer to [[Query::where()]] on how to specify conditions.
     * @param array $params the parameters to be bound to the query.
     */
    public function update($table, $columns, $condition = '', $params = [])
    {
        $this->db->createCommand()->update($table, $columns, $condition, $params)->execute();
    }

    /**
     * Creates and executes a DELETE SQL statement.
     * @param string $table the table where the data will be deleted from.
     * @param array|string $condition the conditions that will be put in the WHERE part. Please
     * refer to [[Query::where()]] on how to specify conditions.
     * @param array $params the parameters to be bound to the query.
     */
    public function delete($table, $condition = '', $params = [])
    {
        $this->db->createCommand()->delete($table, $condition, $params)->execute();
    }

    /**
     * Builds and executes a SQL statement for creating a new DB table.
     *
     * The columns in the new  table should be specified as name-definition pairs (e.g. 'name' => 'string'),
     * where name stands for a column name which will be properly quoted by the method, and definition
     * stands for the column type which can contain an abstract DB type.
     *
     * The [[QueryBuilder::getColumnType()]] method will be invoked to convert any abstract type into a physical one.
     *
     * If a column is specified with definition only (e.g. 'PRIMARY KEY (name, type)'), it will be directly
     * put into the generated SQL.
     *
     * @param string $table the name of the table to be created. The name will be properly quoted by the method.
     * @param array $columns the columns (name => definition) in the new table.
     * @param string $options additional SQL fragment that will be appended to the generated SQL.
     */
    public function createTable($table, $columns, $options = null)
    {
        $columns1 = [];
        foreach ($columns as $name => $type) {
            if (is_numeric($name))
            {
                $name = $type;
                $type = 'string';
            }
            $columns1 = array_merge($columns1, [$name => $type]);
        }
        $columns = $columns1;
        $this->db->createCommand()->createTable($table, $columns, $options)->execute();
        foreach ($columns as $column => $type) {
            if ($type instanceof ColumnSchemaBuilder && $type->comment !== null) {
                $this->db->createCommand()->addCommentOnColumn($table, $column, $type->comment)->execute();
            }
        }
    }

    /**
     * Builds and executes a SQL statement for renaming a DB table.
     * @param string $table the table to be renamed. The name will be properly quoted by the method.
     * @param string $newName the new table name. The name will be properly quoted by the method.
     */
    public function renameTable($table, $newName)
    {
        $this->db->createCommand()->renameTable($table, $newName)->execute();
    }

    /**
     * Builds and executes a SQL statement for dropping a DB table.
     * @param string $table the table to be dropped. The name will be properly quoted by the method.
     */
    public function dropTable($table)
    {
        $this->db->createCommand()->dropTable($table)->execute();
    }

    /**
     * Builds and executes a SQL statement for truncating a DB table.
     * @param string $table the table to be truncated. The name will be properly quoted by the method.
     */
    public function truncateTable($table)
    {
        $this->db->createCommand()->truncateTable($table)->execute();
    }

    /**
     * Builds and executes a SQL statement for adding a new DB column.
     * @param string $table the table that the new column will be added to. The table name will be properly quoted by the method.
     * @param string $column the name of the new column. The name will be properly quoted by the method.
     * @param string $type the column type. The [[QueryBuilder::getColumnType()]] method will be invoked to convert abstract column type (if any)
     * into the physical one. Anything that is not recognized as abstract type will be kept in the generated SQL.
     * For example, 'string' will be turned into 'varchar(255)', while 'string not null' will become 'varchar(255) not null'.
     */
    public function addColumn($table, $column, $type)
    {
        $this->db->createCommand()->addColumn($table, $column, $type)->execute();
        if ($type instanceof ColumnSchemaBuilder && $type->comment !== null) {
            $this->db->createCommand()->addCommentOnColumn($table, $column, $type->comment)->execute();
        }
    }

    /**
     * Builds and executes a SQL statement for dropping a DB column.
     * @param string $table the table whose column is to be dropped. The name will be properly quoted by the method.
     * @param string $column the name of the column to be dropped. The name will be properly quoted by the method.
     */
    public function dropColumn($table, $column)
    {
        $this->db->createCommand()->dropColumn($table, $column)->execute();
    }

    /**
     * Builds and executes a SQL statement for renaming a column.
     * @param string $table the table whose column is to be renamed. The name will be properly quoted by the method.
     * @param string $name the old name of the column. The name will be properly quoted by the method.
     * @param string $newName the new name of the column. The name will be properly quoted by the method.
     */
    public function renameColumn($table, $name, $newName)
    {
        $this->db->createCommand()->renameColumn($table, $name, $newName)->execute();
    }

    /**
     * Builds and executes a SQL statement for changing the definition of a column.
     * @param string $table the table whose column is to be changed. The table name will be properly quoted by the method.
     * @param string $column the name of the column to be changed. The name will be properly quoted by the method.
     * @param string $type the new column type. The [[QueryBuilder::getColumnType()]] method will be invoked to convert abstract column type (if any)
     * into the physical one. Anything that is not recognized as abstract type will be kept in the generated SQL.
     * For example, 'string' will be turned into 'varchar(255)', while 'string not null' will become 'varchar(255) not null'.
     */
    public function alterColumn($table, $column, $type)
    {
        $this->db->createCommand()->alterColumn($table, $column, $type)->execute();
        if ($type instanceof ColumnSchemaBuilder && $type->comment !== null) {
            $this->db->createCommand()->addCommentOnColumn($table, $column, $type->comment)->execute();
        }
    }

    /**
     * Builds and executes a SQL statement for creating a primary key.
     * The method will properly quote the table and column names.
     * @param string $name the name of the primary key constraint.
     * @param string $table the table that the primary key constraint will be added to.
     * @param string|array $columns comma separated string or array of columns that the primary key will consist of.
     */
    public function addPrimaryKey($name, $table, $columns)
    {
        $time = microtime(true);
        $this->db->createCommand()->addPrimaryKey($name, $table, $columns)->execute();
    }

    /**
     * Builds and executes a SQL statement for dropping a primary key.
     * @param string $name the name of the primary key constraint to be removed.
     * @param string $table the table that the primary key constraint will be removed from.
     */
    public function dropPrimaryKey($name, $table)
    {
        $time = microtime(true);
        $this->db->createCommand()->dropPrimaryKey($name, $table)->execute();
    }

    /**
     * Builds a SQL statement for adding a foreign key constraint to an existing table.
     * The method will properly quote the table and column names.
     * @param string $name the name of the foreign key constraint.
     * @param string $table the table that the foreign key constraint will be added to.
     * @param string|array $columns the name of the column to that the constraint will be added on. If there are multiple columns, separate them with commas or use an array.
     * @param string $refTable the table that the foreign key references to.
     * @param string|array $refColumns the name of the column that the foreign key references to. If there are multiple columns, separate them with commas or use an array.
     * @param string $delete the ON DELETE option. Most DBMS support these options: RESTRICT, CASCADE, NO ACTION, SET DEFAULT, SET NULL
     * @param string $update the ON UPDATE option. Most DBMS support these options: RESTRICT, CASCADE, NO ACTION, SET DEFAULT, SET NULL
     */
    public function addForeignKey($name, $table, $columns, $refTable, $refColumns, $delete = null, $update = null)
    {
        $time = microtime(true);
        $this->db->createCommand()->addForeignKey($name, $table, $columns, $refTable, $refColumns, $delete, $update)->execute();
    }

    /**
     * Builds a SQL statement for dropping a foreign key constraint.
     * @param string $name the name of the foreign key constraint to be dropped. The name will be properly quoted by the method.
     * @param string $table the table whose foreign is to be dropped. The name will be properly quoted by the method.
     */
    public function dropForeignKey($name, $table)
    {
        $this->db->createCommand()->dropForeignKey($name, $table)->execute();
    }

    /**
     * Builds and executes a SQL statement for creating a new index.
     * @param string $name the name of the index. The name will be properly quoted by the method.
     * @param string $table the table that the new index will be created for. The table name will be properly quoted by the method.
     * @param string|array $columns the column(s) that should be included in the index. If there are multiple columns, please separate them
     * by commas or use an array. Each column name will be properly quoted by the method. Quoting will be skipped for column names that
     * include a left parenthesis "(".
     * @param boolean $unique whether to add UNIQUE constraint on the created index.
     */
    public function createIndex($name, $table, $columns, $unique = false)
    {
        $this->db->createCommand()->createIndex($name, $table, $columns, $unique)->execute();
    }

    /**
     * Builds and executes a SQL statement for dropping an index.
     * @param string $name the name of the index to be dropped. The name will be properly quoted by the method.
     * @param string $table the table whose index is to be dropped. The name will be properly quoted by the method.
     */
    public function dropIndex($name, $table)
    {
        $this->db->createCommand()->dropIndex($name, $table)->execute();
    }

    /**
     * Builds and execute a SQL statement for adding comment to column
     *
     * @param string $table the table whose column is to be commented. The table name will be properly quoted by the method.
     * @param string $column the name of the column to be commented. The column name will be properly quoted by the method.
     * @param string $comment the text of the comment to be added. The comment will be properly quoted by the method.
     * @return $this the command object itself
     * @since 2.0.8
     */
    public function addCommentOnColumn($table, $column, $comment)
    {
        $this->db->createCommand()->addCommentOnColumn($table, $column, $comment)->execute();
    }

    /**
     * Builds a SQL statement for adding comment to table
     *
     * @param string $table the table whose column is to be commented. The table name will be properly quoted by the method.
     * @param string $comment the text of the comment to be added. The comment will be properly quoted by the method.
     * @return $this the command object itself
     * @since 2.0.8
     */
    public function addCommentOnTable($table, $comment)
    {
        $this->db->createCommand()->addCommentOnTable($table, $comment)->execute();
    }

    /**
     * Builds and execute a SQL statement for dropping comment from column
     *
     * @param string $table the table whose column is to be commented. The table name will be properly quoted by the method.
     * @param string $column the name of the column to be commented. The column name will be properly quoted by the method.
     * @return $this the command object itself
     * @since 2.0.8
     */
    public function dropCommentFromColumn($table, $column)
    {
        $this->db->createCommand()->dropCommentFromColumn($table, $column)->execute();
    }

    /**
     * Builds a SQL statement for dropping comment from table
     *
     * @param string $table the table whose column is to be commented. The table name will be properly quoted by the method.
     * @return $this the command object itself
     * @since 2.0.8
     */
    public function dropCommentFromTable($table)
    {
        $this->db->createCommand()->dropCommentFromTable($table)->execute();
    }
}