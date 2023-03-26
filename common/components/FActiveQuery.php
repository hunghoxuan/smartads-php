<?php
/**
 * Created by PhpStorm.
 * User: Darkness
 * Date: 11/30/2016
 * Time: 2:00 PM
 */

namespace common\components;


use backend\modules\cms\models\CmsBlogs;
use backend\modules\wp\models\WpPosts;
use yii\db\ActiveQuery;
use yii\db\Exception;
use yii\db\Expression;
use yii\helpers\ArrayHelper;


class FActiveQuery extends ActiveQuery
{
    public $output_fields;
    public $columnMapping;
    public $db;
    public $model;
    public $table;
    public $primaryField;
    public $object_type;
    public $page;
    public $isCached = false;
    public $isBasicQuery = false;

    public function output($fields) {
        $this->output_fields = $fields;

        return $this;
    }

    public function select($columns, $options = null) {
        if ($this->isBasicQuery) {
            return parent::select($columns, $options);
        }

	    $columns1 = [];
	    if ($columns instanceof Expression) {
            $columns = [$columns];
		    return parent::select($columns, $options);
        } elseif (!is_array($columns)) {
            $columns = preg_split('/\s*,\s*/', trim($columns), -1, PREG_SPLIT_NO_EMPTY);
        }

        $hasExpression = false;
        foreach ($columns as $column => $column_value) {
            if (is_numeric($column))
                $column = $column_value;

            if (is_callable($column_value))
                $hasExpression = true;

            if ($this->field_exists($column))
                $columns1[] = $column;
            else if (!empty($this->columnMapping) && is_array($this->columnMapping) && key_exists($column, $this->columnMapping) && $this->field_exists($this->columnMapping[$column])) {
                $columns1[] = $this->columnMapping[$column];
            }
        }

        //Hung: check ?
        if (!empty($columns1))
            $columns = $columns1;

        $this->output_fields = $columns;
        if ($hasExpression)
            return $this;

        return parent::select($columns, $options);
    }

    public function where($condition, $params = []) {
        if ($this->isBasicQuery || empty($condition)) {
            return parent::where($condition, $params);
        }

        $id_field = $this->getIdField();

        if (is_string($condition)) {
            $condition1 = FHtml::decode($condition, true);
            if (is_array($condition1) && count($condition1) == 1) {
                if (ArrayHelper::isIndexed($condition1))
                    $condition1 = $condition1[0];
            }
        } else {
            $condition1 = $condition;
        }
        $model = $this->getModel();
        $select_str = $this->getSelectString();

        if (is_array($condition1))
        {
            //return parent::where($condition1);
            //HUNG: ERROR HERE

            foreach ($condition1 as $field => $field_value) {
                //if normal $condition
                if  (is_string($field)) {
                    if (!$this->field_exists($field, $select_str, $model))
                        continue;

                    if (is_string($field_value) && strpos($field_value, '%') !== false) {
                        $this->andWhere(['like', $field, str_replace('%', '', $field_value)]);
                    } else {
                        $this->andWhere([$field => $field_value]);
                    }
                } else if (is_numeric($field)) {
                    return parent::where($condition1);
                } else {
                    $this->andWhere([$field => $field_value]);
                }
            }
        } else if (is_numeric($condition1) && !empty($id_field)) { //select one
            //return parent::where($condition1);
            $this->where(null);
            $this->where([$id_field => $condition1]);

        } else if (is_string($condition1)) {

            return parent::where($condition1);
        }


        return $this;
    }

    /**
     * Adds additional parameters to be bound to the query.
     * @param array $params list of query parameter values indexed by parameter placeholders.
     * For example, `[':name' => 'Dan', ':age' => 31]`.
     * @return $this the query object itself
     * @see params()
     */
    public function addParams($params)
    {
        if (!empty($params)) {
            if (empty($this->params)) {
                $this->params = $params;
            } else {
                foreach ($params as $name => $value) {
                    if (is_int($name)) {
                        $this->params[] = $value;
                    } else {
                        $this->params[$name] = $value;
                    }
                }
            }
        }
        return $this;
    }

    public function normalize() {
        if ($this->isBasicQuery) {
            return ;
        }

        $id_field = $this->getIdField();

//        if (!empty($id_field) && $this->field_exists( $id_field)) {
//            if (is_array($this->select) && !empty($this->select))
//                $this->select = array_merge($this->select, [$id_field]);
//            else if (is_string($this->select) && !empty($this->select))
//                $this->select = $this->select . ',' . $id_field;
//        }

        $this->normalizeWhereCondition();
        $this->normalizeOrderBy1();

        return $this;
    }

    protected function normalizeWhereCondition() {
        return $this->where = FModel::normalizeSqlCondition($this->where, $this->getModel());
    }

    protected function getSelectString() {
        return FModel::getQuerySelectString($this);
    }

    protected function normalizeOrderBy1($columns = null)
    {
        if ($this->isBasicQuery) {
            return ;
        }

        if (!isset($columns))
            $columns = $this->orderBy;

        /**
         * Long.
         * Vd. 'distance ASC': field distance khong ton tai trong model.
         * no la 1 field ao duoc tao ra tu cau lenh sql
         * vd: 111.111 * DEGREES(ACOS(COS(RADIANS(latitude)) * COS(RADIANS({$latitude})) * COS(RADIANS(longitude - {$longitude})) + SIN(RADIANS(latitude))* SIN(RADIANS({$latitude})))) * 1000 AS distance
         * => voi dieu kien ben duoi no se khong them duoc vao cau lenh orderby.
         * check field ton tai => khong can thiet.
         */
        //return parent::orderBy($columns); HUNG: FIXED

        $model = $this->getModel();
        $select_str = $this->getSelectString();

        if (is_array($columns)) {
            $result = [];
            foreach ($columns as $column => $asc_desc) {

                if (is_string($column) && $this->field_exists($column, $select_str, $model)) {
                    $result = array_merge($result, [$column => $asc_desc]);
                }
                else if (is_integer($column)) {
                    $column = $asc_desc;
                    $column1 = FHtml::strReplace($column, [' ' => '', 'asc' => '', 'desc' => '']);
                    if ($this->field_exists($column1, $select_str, $model))
                        $result[] = $column;
                }
            }

            $columns = $result;
        } else if (is_string($columns)) {
            $columns = explode(',', $columns);
            $columns1 = [];

            foreach ($columns as $i => $column) {
                $column = strtolower(trim($column));
                $column1 = FHtml::strReplace($column, [' ' => '', 'asc' => '', 'desc' => '']);
                if ($this->field_exists($column1, $select_str, $model))
                    $columns1[] = $column;
            }

            $columns = implode(',', $columns1);
        }

        return parent::orderBy($columns);
    }

    public function all($db = null)
    {
        if ($db === null) {
            $db = $this->getDb();
        }

        if (!is_object($db)) {
            $model = $this->getModel();
            return isset($model) ? $model::findAll($this->where, $this->orderBy, $this->limit, (isset($this->offset) && !empty($this->limit)) ? (cell($this->offset/$this->limit) + 1) : 0) : [];
        }

        //if select only some fields then make sure include primary key - id in selected fields (used in get TranslatedModels)
        $this->normalize();

        try {
            $models = parent::all($db);

            $models = FHtml::getTranslatedModels($models, $this->output_fields);

            if (!empty($this->object_type)) {
                $outputModel = FModel::createModel($this->object_type);
                $models = $outputModel::createMany($models, $this->columnMapping);
            }

            return $models;
        } catch (\PDOException $ex) {

            FHtml::addError($ex);
            return [];
        }
    }

    public function one($db = null)
    {
        if ($db === null) {
            $db = $this->getDb();
        }

        if (!is_object($db)) {
            $model = $this->getModel();
            return isset($model) ? $model::findOne($this->where) : null;
        }

        if (!$this->table_existed()) {
            FHtml::addError("Table does not existed :" . $this->getTableName(), FHtml::encode($this));
            return null;
        }

        //Hung: if select only some fields then make sure include primary key - id in selected fields (used in get TranslatedModels)
        $this->normalize();

        $item = parent::one($db); // TODO: Change the autogenerated stub

        $model = FHtml::getTranslatedModel($item, '', $this->output_fields);

        if (!empty($this->object_type)) {
            $outputModel = FModel::createModel($this->object_type);
            $model = $outputModel::createModel($model, $this->columnMapping);
        }

        return $model;
    }

    public function count($q = '*', $db = null) {
        if ($db === null) {
            $db = $this->getDb();
        }

        if (!is_object($db)) {
            $model = $this->getModel();
            $models = isset($model) ? $model::findAll($this->where, $this->orderBy, $this->limit, $this->offset) : [];
            return count($models);
        }

        if (!$this->table_existed())
            return 0;

        $result = parent::count($q, $db);
        return FModel::getNumeric($result); //always return numeric value
    }

    public function createCommand($db = null)
    {
        if ($db === null) {
            $db = $this->getDb();
        }

        if (isset($db) && is_object($db)) {
            list ($sql, $params) = $db->getQueryBuilder()->build($this);

            return $db->createCommand($sql, $params);
        } else {
            return null;
        }
    }

    public function getDb() {
        if (isset($this->db))
            return $this->db;

        $model = $this->getModel();
        $this->db = isset($model) ? $model->getDb() : FHtml::currentDB();
        return $this->db;
    }

    public function getIdField() {
        if (!empty($this->primaryField))
            return $this->primaryField;

        $model = $this->getModel();
        $this->primaryField = isset($model) ? $model->getPrimaryKey() : (FHtml::field_exists($model, 'id') ? 'id' : '');

        return $this->primaryField;
    }

    public function getModel() {
        if (isset($this->model))
            return $this->model;

        if (isset($this->primaryModel))
            return $this->primaryModel;

        $this->model = FHtml::createModel($this->getTableName());
        return $this->model;
    }

    public function field_exists($column, $select_str = null, $model = null) {
        if (!isset($model))
            $model = $this->getModel();
        if (!isset($select_str))
            $select_str = $this->getSelectString();

        if (strpos($column, '.') !== false) {
            $column = substr($column, strpos($column, '.') + 1);
        }

        return FModel::field_existsInQuery($column, $select_str, $model);
    }

    public function getTableName()
    {
        if (!empty($this->table))
            return $this->table;

        $query = $this;
        if (empty($query->from)) {
            /* @var $modelClass ActiveRecord */
            $modelClass = $query->modelClass;
            $tableName = $modelClass::tableName();
        } else {
            $tableName = '';
            foreach ($query->from as $alias => $tableName) {
                if (is_string($alias)) {
                    $this->table = $tableName;
                    return $tableName;
                } else {
                    break;
                }
            }
        }
        $this->table = $tableName;
        return $tableName;
    }

    public function table_existed($table = '', $db = null) {
        if (empty($table))
            $table = $this->getTableName();

        if (empty($db))
            $db = $this->getDb();

        return FHtml::isTableExisted($table, $db);

    }

    public function page($page) {
        $this->page = $page;
        $number_per_page = $this->limit;
        //if (!isset($this->offset) && $number_per_page > 0) {
            $this->offset($page * $number_per_page - $number_per_page);
        //}

        return $this;
    }
}