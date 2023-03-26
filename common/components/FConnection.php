<?php
/**
 * Created by PhpStorm.
 * User: Darkness
 * Date: 11/30/2016
 * Time: 2:00 PM
 */

namespace common\components;


use common\components\FConstant;
use common\config\FSettings;
use common\widgets\fview\FView;
use yii\base\Exception;
use Yii;
use yii\base\Theme;
use yii\db\Connection;
use yii\helpers\VarDumper;
use yii\web\View;


class FConnection extends Connection
{
//    protected function createPdoInstance()
//    {
//        $this->pdoClass = 'common/components/FConnection';
//        $pdoClass = $this->pdoClass;
//        if ($pdoClass === null) {
//            $pdoClass = 'PDO';
//            if ($this->_driverName !== null) {
//                $driver = $this->_driverName;
//            } elseif (($pos = strpos($this->dsn, ':')) !== false) {
//                $driver = strtolower(substr($this->dsn, 0, $pos));
//            }
//            if (isset($driver)) {
//                if ($driver === 'mssql' || $driver === 'dblib') {
//                    $pdoClass = 'yii\db\mssql\PDO';
//                } elseif ($driver === 'sqlsrv') {
//                    $pdoClass = 'yii\db\mssql\SqlsrvPDO';
//                }
//            }
//        }
//
//        $dsn = $this->dsn;
//        if (strncmp('sqlite:@', $dsn, 8) === 0) {
//            $dsn = 'sqlite:' . Yii::getAlias(substr($dsn, 7));
//        }
//        return new $pdoClass($dsn, $this->username, $this->password, $this->attributes);
//        //return new $pdoClass($dsn, 'moza', 'moza123!', $this->attributes);
//    }
}