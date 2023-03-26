<?php
namespace mozaframework\atcrud\generators;

use common\components\FHtml;
use mozaframework\atcrud\Helper;
use Yii;
use yii\base\Exception;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Schema;
use yii\gii\CodeFile;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\helpers\VarDumper;
use yii\web\Controller;

/**
 * Generates CRUD
 *
 * @property array $columnNames Model column names. This property is read-only.
 * @property string $controllerID The controller ID (without the module ID prefix). This property is
 * read-only.
 * @property array $searchAttributes Searchable attributes. This property is read-only.
 * @property boolean|\yii\db\TableSchema $tableSchema This property is read-only.
 * @property string $viewPath The controller view path. This property is read-only.
 *
 */
class Generator extends \yii\gii\Generator
{
    public $modelClass = '';
    public $modelClassArrays = [];
    public $db = 'db';

    public $baseFolder = 'backend';
    public $baseFolderFrontend = 'frontend';
    public $controllerClass;
    public $actionClass;
    public $viewPath;
    public $baseControllerClass = 'yii\web\Controller';
    public $searchModelClass = '';
    public $gridFields = '';
    public $moduleName = '';
    public $ns = 'backend\_modules_\models';


    /**
     * @inheritdoc
     */
    public function getName()
    {
        $this->enableI18N = true;
        return 'Moza CRUD Generator';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator generates a controller and views that implement CRUD (Create, Read, Update, Delete)
            operations for the specified data model with template for Single Page Ajax Administration';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['controllerClass', 'modelClass', 'searchModelClass', 'baseControllerClass'], 'filter', 'filter' => 'trim'],
            [['modelClass'], 'required'],
            [['searchModelClass'], 'compare', 'compareAttribute' => 'modelClass', 'operator' => '!==', 'message' => 'Search Model Class must not be equal to Model Class.'],
            //[['modelClass', 'controllerClass', 'baseControllerClass', 'searchModelClass'], 'match', 'pattern' => '/^[\w\\\\]*$/', 'message' => 'Only word characters and backslashes are allowed.'],
            //[['modelClass'], 'validateClass', 'params' => ['extends' => BaseActiveRecord::className()]],
            [['baseControllerClass'], 'validateClass', 'params' => ['extends' => Controller::className()]],
            [['controllerClass'], 'match', 'pattern' => '/Controller$/', 'message' => 'Controller class name must be suffixed with "Controller".'],
            [['controllerClass'], 'match', 'pattern' => '/(^|\\\\)[A-Z][^\\\\]+Controller$/', 'message' => 'Controller class name must start with an uppercase letter.'],
            [['controllerClass', 'searchModelClass'], 'validateNewClass'],
            //[['modelClass'], 'validateModelClass'],
            [['enableI18N'], 'boolean'],
            [['messageCategory'], 'validateMessageCategory', 'skipOnEmpty' => false],
            ['viewPath', 'safe'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'baseFolder' => 'Base Folder',
            'modelClass' => 'Model Class',
            'controllerClass' => 'Controller Class',
            'viewPath' => 'View Path',
            'baseControllerClass' => 'Base Controller Class',
            'searchModelClass' => 'Search Model Class',
            'ns' => 'Namespace',
            'db' => 'Database Connection ID',
            'tableName' => 'Table Name',
            'baseClass' => 'Base Class',
            'generateRelations' => 'Generate Relations',
            'generateLabelsFromComments' => 'Generate Labels from DB Comments',
            'generateQuery' => 'Generate ActiveQuery',
            'queryNs' => 'ActiveQuery Namespace',
            'queryClass' => 'ActiveQuery Class',
            'queryBaseClass' => 'ActiveQuery Base Class',
        ]);
    }


    /**
     * @inheritdoc
     */
    public function hints()
    {
        return array_merge(parent::hints(), [
            'modelClass' => 'This is the ActiveRecord class associated with the table that CRUD will be built upon.
                You should provide a fully qualified class name, e.g., <code>app\models\Post</code><br/>
                Input <code>backend\modules\test\models\Test, backend\modules\tool\models\Tool</code> etc and more if you want to generate CRUD for multiple models at the same time',
            'controllerClass' => 'This is the name of the controller class to be generated. You should
                provide a fully qualified namespaced class (e.g. <code>app\controllers\PostController</code>),
                and class name should be in CamelCase with an uppercase first letter. Make sure the class
                is using the same namespace as specified by your application\'s controllerNamespace property.',
            'viewPath' => 'Specify the directory for storing the view scripts for the controller. You may use path alias here, e.g.,
                <code>/var/www/basic/controllers/views/post</code>, <code>@app/views/post</code>. If not set, it will default
                to <code>@app/views/ControllerID</code>',
            'baseControllerClass' => 'This is the class that the new CRUD controller class will extend from.
                You should provide a fully qualified class name, e.g., <code>yii\web\Controller</code>.',
            'searchModelClass' => 'This is the name of the search model class to be generated. You should provide a fully
                qualified namespaced class name, e.g., <code>app\models\PostSearch</code>.',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function requiredTemplates()
    {
        return ['controller.php'];
    }

    /**
     * @inheritdoc
     */
    public function stickyAttributes()
    {
        return array_merge(parent::stickyAttributes(), ['baseControllerClass']);
    }

    public function getModelClassArray() {
        $arrays = explode(',', Helper::prepareStringForExplode($this->modelClass));
        return $arrays;
    }
    /**
     * Checks if model class is valid
     */
    public function validateModelClass()
    {
        if (empty($this->modelClassArrays) && count($this->modelClassArrays) == 0) {
            $arrays = explode(',', Helper::prepareStringForExplode($this->modelClass));

            $this->modelClassArrays = [];
            foreach ($arrays as $item) {
                if (strpos($item, '\\') === false) {
                    if (empty($this->moduleName))
                        $this->moduleName = FHtml::getModelModule($item);
                    if (!empty($this->moduleName) || !FHtml::isInArray($item, FHtml::TABLES_COMMON)) {
                        if (strpos($item, '\\') !== false) {
                            $item = end(explode('\\', $item));
                        } else {
                            $item = Inflector::camelize(trim($item));
                        }

                        if (strpos($item, '\\') == false) {
                            if (strpos($this->ns, '_modules_') !== false) {
                                if (!empty($this->moduleName))
                                    $this->ns = str_replace('_modules_\\', 'modules\\' . $this->moduleName . '\\', $this->ns);
                                else
                                    $this->ns = str_replace('_modules_\\', '', $this->ns);
                                $item = str_replace(Inflector::camelize($this->ns), '', $item);
                            }

                            $item = $this->ns . '\\' . $item;
                        }
                    } else {
                        if (strpos($item, '\\') !== false) {
                            $item = end(explode('\\', $item));
                        } else {
                            $item = Inflector::camelize(trim($item));
                        }

                        $this->ns = 'backend\\models';
                        $item = $this->ns . '\\' . $item;
                    }
                }

                try {
                    $pk = $item::primaryKey();
                    if (!empty($pk)) {
                        $this->modelClassArrays[] = $item;
                    }
                } catch (Exception $e) {
                    echo $e->getMessage();  die;
                }
            }
        }

        if (!empty($this->gridFields)) {

        }
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        $files = [];
        $moduleFolder = '';
        self::validateModelClass();
        foreach ($this->modelClassArrays as $modelClassItem) {
            $this->modelClass = $modelClassItem; // Reset this value and pass into each file
            $array = explode('/', str_replace('\\', '/', ltrim($modelClassItem, '\\')));
            $model = end($array);

            if (empty($this->moduleName)) {
                $idx = strpos($modelClassItem, 'modules\\') + 8;
                $this->moduleName = strtolower(substr($modelClassItem, $idx, strpos($modelClassItem, '\\', $idx + 1) - $idx));
            }

            // 2017-01-19 - Ha edited - Fix issue always generate modules (Naiscorp)
            if (!empty($this->moduleName))
            {
                if(substr_count($_REQUEST['Generator']['ns'], 'modules') == 0)
                    $moduleFolder = '';
                else
                    $moduleFolder = '\\modules\\' . $this->moduleName;
            }
            else
                $moduleFolder = '';


            $this->searchModelClass = $this->baseFolder . $moduleFolder . '\\models\\' . $model . 'Search';
            $this->controllerClass = $this->baseFolder . $moduleFolder . '\\controllers\\' . $model . 'Controller';
            $this->actionClass = $this->baseFolder . $moduleFolder . '\\actions\\' . $model . 'Action';

            $this->viewPath = '@' . $this->baseFolder . str_replace('\\', '/', $moduleFolder) . '/views/' . Inflector::camel2id($model);
            if (!empty($this->controllerClass)) {
                $controllerFile = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->controllerClass, '\\')) . '.php');
                $files[] = new CodeFile($controllerFile, $this->render('controller.php'));
            }

            // generate backend files
            $viewPath = $this->getViewPath();
            $templatePath = $this->getTemplatePath() . '/views';
            foreach (scandir($templatePath) as $file) {
                if (empty($this->searchModelClass) && $file === '_search.php') {
                    continue;
                }
                if (is_file($templatePath . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                    $files[] = new CodeFile("$viewPath/$file", $this->render("views/$file"));
                }
            }

            // generate api actions
//            $this->viewPath = '@' . $this->baseFolder . str_replace('\\', '/', $moduleFolder) . '/actions/';
//            $viewPath = $this->getViewPath();
//            $templatePath = $this->getTemplatePath() . '/actions';
//            foreach (scandir($templatePath) as $file) {
//                if (is_file($templatePath . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
//                    $files[] = new CodeFile("$viewPath/$model$file", $this->render("actions/$file"));
//                }
//            }

            //
            //$this->viewPath = '@' . $this->baseFolder . '/web/upload/' . Inflector::camel2id($model);
            $this->viewPath = FHtml::getRootFolder() . '/applications/' . FHtml::currentApplicationId(). '/upload/' . Inflector::camel2id($model);
            $viewPath = $this->getViewPath();
            $files[] = new CodeFile("$viewPath/.gitignore", $this->render('gitignore.txt'));
        }

        return $files;
    }

    /**
     * @return string the controller ID (without the module ID prefix)
     */
    public function getControllerID()
    {
        $pos = strrpos($this->controllerClass, '\\');
        $class = substr(substr($this->controllerClass, $pos + 1), 0, -10);

        return Inflector::camel2id($class);
    }

    /**
     * @return string the controller view path
     */
    public function getViewPath()
    {
        if (empty($this->viewPath)) {
            return Yii::getAlias('@app/views/' . $this->getControllerID());
        } else {
            return Yii::getAlias($this->viewPath);
        }
    }


    public function getNameAttribute()
    {
        foreach ($this->getColumnNames() as $name) {
            if (!strcasecmp($name, 'name') || !strcasecmp($name, 'title')) {
                return $name;
            }
        }
        /* @var $class \yii\db\ActiveRecord */
        $class = $this->modelClass;
        $pk = $class::primaryKey();

        return $pk[0];
    }

    public function generateString($string = '', $placeholders = [], $languageNS = '')
    {
        $string = addslashes($string);
        if ($this->enableI18N) {
            // If there are placeholders, use them
            if (!empty($placeholders)) {
                $ph = ', ' . VarDumper::export($placeholders);
            } else {
                $ph = '';
            }
            $str = "FHtml::t('" . $this->messageCategory . "', '" . $string . "'" . $ph . ")";
        } else {
            // No I18N, replace placeholders by real words, if any
            if (!empty($placeholders)) {
                $phKeys = array_map(function($word) {
                    return '{' . $word . '}';
                }, array_keys($placeholders));
                $phValues = array_values($placeholders);
                $str = "'" . str_replace($phKeys, $phValues, $string) . "'";
            } else {
                // No placeholders, just the given string
                $str = "'" . $string . "'";
            }
        }
        return $str;
    }


    public function generateActiveFieldNoLabel($attribute, $shortForm = false)
    {
        $input = self::generateActiveField($attribute, '', "\$form->fieldNoLabel", $shortForm);
        $template = "'" . $attribute . "' => ['value' => $input, 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],\n";
        return $template;
    }

    /**
     * Generates code for active field
     * @param string $attribute
     * @return string
     */
    public function generateActiveField($attribute, $colSpan = '', $function = "\$form->field", $shortForm = false)
    {
        $previewAttributes = FHtml::FIELDS_PREVIEW;
        $hiddenAttributes = FHtml::FIELDS_HIDDEN;
        $countAttributes = FHtml::FIELDS_COUNT;
        $groupAttributes = FHtml::getFIELDS_GROUP();
        $uploadAttributes = FHtml::FIELDS_UPLOAD;
        $priceAttributes = FHtml::FIELDS_PRICE;
        $dateAttributes = FHtml::FIELDS_DATE;
        $commonAttributes = FHtml::FIELDS_COMMON;

        $htmlAttributes =  FHtml::FIELDS_HTML;
        $textareaAttributes = FHtml::FIELDS_TEXTAREA;
        $textareaSmallAttributes = FHtml::FIELDS_TEXTAREASMALL;
        $lookupAttributes = FHtml::FIELDS_LOOKUP;
        $dateAttributes = FHtml::FIELDS_DATE;
        $timeAttributes = FHtml::FIELDS_TIME;
        $datetimeAttributes = FHtml::FIELDS_DATETIME;

        $rateAttributes = FHtml::FIELDS_RATE;

        $booleanAttributes = FHtml::FIELDS_BOOLEAN;
        $fileAttributes = FHtml::FIELDS_FILES;
        $imageAttributes = FHtml::FIELDS_IMAGES;
        $percentAttributes = FHtml::FIELDS_PERCENT;

        $specialEditors = ['tags'];

        $tableSchema = $this->getTableSchema();
        $result = "";

        if ($colSpan > 0)
            $colSpan = "->labelSpan(" . $colSpan . ")";

        if ($tableSchema === false || !isset($tableSchema->columns[$attribute])) {
            if (preg_match('/^(password|pass|passwd|passcode)$/i', $attribute)) {
                $result = "$function(\$model, '$attribute')->passwordInput()";
            } else {
                $result = "$function(\$model, '$attribute')";
            }
        }

        $column = $tableSchema->columns[$attribute];
        $commentArray = FHtml::toArrayFromDbComment($column->comment, $column->name);
        $editor = isset($commentArray['editor']) ? $commentArray['editor'] : '';
        $commentOut = $editor == 'hidden' ? '//' : '';

        $editorSettings = isset($commentArray['setting']) ? $commentArray['setting'] : '';
        $lookup_key = isset($commentArray['lookup']) ? $commentArray['lookup'] : $tableSchema->name;
        $lookup_table = StringHelper::startsWith($lookup_key, '@') ? str_replace('@', '', $lookup_key) : $lookup_key;

        if (in_array($attribute, $specialEditors)) {
            $editor = $attribute;
            $result = "$function(\$model, '$attribute')->$editor()";
        } else if (key_exists($attribute, $specialEditors)) {
            $editor = $specialEditors[$attribute];
            $result = "$function(\$model, '$attribute')->$editor()";
        }
        else if ($editor == 'boolean' || $column->phpType === 'boolean' || FHtml::isInArray($column->name, $booleanAttributes) || (StringHelper::startsWith($column->dbType, 'tinyint') && $column->size == 1)) {
            //Boolean
            $editor = FHtml::EDITOR_BOOLEAN;
            if (empty($editorSettings)) $editorSettings = FHtml::EDITOR_BOOLEAN_SETTINGS;
            //$result .= "$function(\$model, '$attribute')->widget($editor::className(), [$editorSettings])";
            $result = "$function(\$model, '$attribute')->checkbox()";
        } elseif ($editor == 'label' || $editor == 'readonly') {
            $result = self::generateShowModelField($attribute);
        }
        elseif ($editor == 'text' || $column->dbType === 'text') {
            if (FHtml::isInArray($column->name, $htmlAttributes) && !$shortForm) {
                $editor = FHtml::EDITOR_TEXT;
                if (empty($editorSettings)) $editorSettings = FHtml::EDITOR_TEXT_SETTINGS;
                //$result = "$function(\$model, '$attribute')->widget($editor::className(), [$editorSettings]) ";
                $result = "$function(\$model, '$attribute')->html()";
            } else {
                $result = "$function(\$model, '$attribute')->textarea(['rows' => 3])";
            }
        } elseif ($editor == 'date' || (strpos($column->dbType, 'varchar') !== false && ($column->size == 11 || $column->size == 20 || $column->size == 12)) || (empty($editor) && (FHtml::isInArray($column->name, $dateAttributes) || StringHelper::startsWith($column->dbType, 'date')))) {
            //Date
            $editor = FHtml::EDITOR_DATE;
            if (empty($editorSettings)) $editorSettings = FHtml::EDITOR_DATE_SETTINGS;

            if (strpos($column->dbType, 'varchar') !== false)
                $result .= "$function(\$model, '$attribute')->dateInput()";
            else
                $result .= "$function(\$model, '$attribute')->date()";

        } elseif ($editor == 'time' || (strpos($column->dbType, 'varchar') !== false && $column->size == 8) || FHtml::isInArray($column->name, $timeAttributes) || strpos($column->dbType, 'time') !== false) {
            //Time
            $editor = FHtml::EDITOR_TIME;
            if (empty($editorSettings)) $editorSettings = FHtml::EDITOR_TIME_SETTINGS;
            //$result = "$function(\$model, '$attribute')->widget($editor::className(), [$editorSettings])\n";
            $result .= "$function(\$model, '$attribute')->time()";
        } elseif ($editor == 'datetime' || (strpos($column->dbType, 'varchar') !== false && $column->size == 19) || FHtml::isInArray($column->name, $datetimeAttributes) || strpos($column->dbType, 'datetime') !== false) {
            //Time
            $editor = FHtml::EDITOR_DATETIME;
            if (empty($editorSettings)) $editorSettings = FHtml::EDITOR_DATETIME_SETTINGS;
            //$result = "$function(\$model, '$attribute')->widget($editor::className(), [$editorSettings])\n";
            $result .= "$function(\$model, '$attribute')->datetime()";
        } elseif (strpos($column->dbType, 'int') !== false) {

            if ($editor == 'select' || FHtml::isInArray($column->name, $lookupAttributes)) {
                $editor = FHtml::EDITOR_SELECT;
                if (empty($editorSettings)) $editorSettings = FHtml::EDITOR_SELECT_SETTINGS;
                //$result .= "$function(\$model, '$attribute')->widget($editor::className(),['data' => FHtml::getComboArray('$lookup_key', '$lookup_table', '$column->name', true, 'id', 'name'), 'options'=>['multiple' => false], $editorSettings])";
                $result .= "$function(\$model, '$attribute')->select(FHtml::getComboArray('$lookup_key', '$lookup_table', '$column->name', true, 'id', 'name'))";

            }
            else if ($editor == 'range' || strpos($column->name, 'range') !== false || strpos($column->name, 'point') !== false)
            {
                $editor = FHtml::EDITOR_SLIDE;
                if (empty($editorSettings)) $editorSettings = FHtml::EDITOR_RANGE_SETTINGS;
                //$result = "$function(\$model, '$attribute')->widget($editor::className(), [$editorSettings])";
                $result = "$function(\$model, '$attribute')->range()";
            }
            else if ($editor == 'rate' || FHtml::isInArray($column->name, $rateAttributes))
            {
                $editor = FHtml::EDITOR_RATE;
                if (empty($editorSettings)) $editorSettings = FHtml::EDITOR_RATE_SETTINGS;
                //$result = "$function(\$model, '$attribute')->widget($editor::className(), [$editorSettings])";
                $result = "$function(\$model, '$attribute')->rate()";
            }
            else if ($editor == 'slide' || $column->size == 3 || FHtml::isInArray($column->name, $percentAttributes))
            {
                $editor = FHtml::EDITOR_SLIDE;
                if (empty($editorSettings)) $editorSettings = FHtml::EDITOR_SLIDE_SETTINGS;
                //$result = "$function(\$model, '$attribute')->widget($editor::className(), [$editorSettings])";
                $result = "$function(\$model, '$attribute')->slide()";

            }
            else if ($editor == 'checkbox' || $column->size == 1 || FHtml::isInArray($column->name, $booleanAttributes))
            {
                $editor = FHtml::EDITOR_BOOLEAN;
                if (empty($editorSettings)) $editorSettings = FHtml::EDITOR_BOOLEAN_SETTINGS;
                //$result = "$function(\$model, '$attribute')->widget($editor::className(), [$editorSettings])";
                $result = "$function(\$model, '$attribute')->checkbox()";
            }
            else {
                //Int
                $editor = FHtml::EDITOR_NUMERIC;
                if (empty($editorSettings)) $editorSettings = FHtml::EDITOR_NUMERIC_SETTINGS;
                //$result = "$function(\$model, '$attribute')->widget($editor::className(), [$editorSettings])\n";
                $result = "$function(\$model, '$attribute')->numeric()";

            }
        } elseif (StringHelper::startsWith($column->dbType, 'decimal') || StringHelper::startsWith($column->dbType, 'double') || StringHelper::startsWith($column->dbType, 'float')) {
                if ($column->precision > 0) // assume it is money
                {
                    $editor = FHtml::EDITOR_CURRENCY;
                    if (empty($editorSettings)) $editorSettings = FHtml::EDITOR_CURRENCY_SETTINGS;
                    //$result = "$function(\$model, '$attribute')->widget($editor::className(), [$editorSettings])";
                    $result = "$function(\$model, '$attribute')->currency()";

                } else {
                    $editor = FHtml::EDITOR_CURRENCY;
                    if (empty($editorSettings)) $editorSettings = FHtml::EDITOR_CURRENCY_SETTINGS;
                    //$result .= "$function(\$model, '$attribute')->widget($editor::className(), [$editorSettings])";
                    $result = "$function(\$model, '$attribute')->money()";
                }

        } elseif (is_array($column->enumValues) && count($column->enumValues) > 0) {
            $dropDownOptions = [];
            foreach ($column->enumValues as $enumValue) {
                $dropDownOptions[$enumValue] = Inflector::humanize($enumValue);
            }
            $result = "$function(\$model, '$attribute')->dropDownList("
                . preg_replace("/\n\s*/", ' ', VarDumper::export($dropDownOptions)).", ['prompt' => ''])";
        }
        else {
            //varchar
            if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name)) {
                $result = "$function(\$model, '$attribute')->passwordInput()";

            } else if ($editor == 'color' || (empty($color) && strpos($column->name, 'color') !== false))
            {
                $editor = FHtml::EDITOR_COLOR;
                if (empty($editorSettings)) $editorSettings = FHtml::EDITOR_COLOR_SETTINGS;
                //$result = "$function(\$model, '$attribute')->widget($editor::className(), [$editorSettings])";
                $result = "$function(\$model, '$attribute')->color()";

            }
            else if ($editor == 'email' || strpos($column->name, 'email') !== false)
            {
                //$result = "$function(\$model, '$attribute')->input('email')";
                $result = "$function(\$model, '$attribute')->emailInput()";

            }
            else if ($editor == 'range' || strpos($column->name, 'range') !== false)
            {
                $editor = FHtml::EDITOR_SLIDE;
                if (empty($editorSettings)) $editorSettings = FHtml::EDITOR_SLIDE_RANGE_SETTINGS;
                //$result = "$function(\$model, '$attribute')->widget($editor::className(), [$editorSettings])";
                $result = "$function(\$model, '$attribute')->slide()";

            }
            else if ($editor == 'date' || (empty($editor) && (FHtml::isInArray($column->name, $dateAttributes) || FHtml::isInArray($column->name, $datetimeAttributes))))
            {
                $editor = FHtml::EDITOR_DATE;
                if (empty($editorSettings)) $editorSettings = FHtml::EDITOR_DATE_SETTINGS;
                if (strpos($column->dbType, 'varchar') !== false)
                    $result .= "$function(\$model, '$attribute')->dateInput()";
                else
                    $result .= "$function(\$model, '$attribute')->date()";

            }
            else if ($editor == 'file' ||FHtml::isInArray($column->name, $fileAttributes) || ($column->size == 310) || ($column->size == 930))
            {
                $editor = FHtml::EDITOR_FILE;
                if (empty($editorSettings)) $editorSettings = FHtml::EDITOR_FILE_SETTINGS;
                if ($column->size == 310)
                    $result = "$function(\$model, '$attribute')->file()";
                //$result = "$function(\$model, '$attribute')->widget($editor::className(), ['pluginOptions' => [ 'model' => \$model,  'maxFileSize'=> FHtml::config('FILE_SIZE_MAX', 4000000), 'options' => ['multiple' => false], 'showPreview' => true, 'showCaption' => false, 'showRemove' => true,'showUpload' => true, $editorSettings]])";
                else
                    $result = "$function(\$model, '$attribute')->file()";
                //$result = "$function(\$model, '$attribute')->widget($editor::className(), ['pluginOptions' => [ 'model' => \$model,  'maxFileSize'=> FHtml::config('FILE_SIZE_MAX', 4000000), 'options' => ['multiple' => true], 'showPreview' => true, 'showCaption' => false, 'showRemove' => true,'showUpload' => true, $editorSettings]])";
            }
            else if ($editor == 'image' || FHtml::isInArray($column->name, $imageAttributes) || ($column->size == 300) || ($column->size == 900))
            {
                $editor = FHtml::EDITOR_FILE;
                if (empty($editorSettings)) $editorSettings = FHtml::EDITOR_FILE_SETTINGS;
                if ($column->size == 300)
                    $result = "$function(\$model, '$attribute')->image()";
                //$result = "$function(\$model, '$attribute')->widget($editor::className(), ['pluginOptions' => [ 'model' => \$model, 'maxFileSize'=> FHtml::config('FILE_SIZE_MAX', 4000000), 'options' => ['accept' => 'image/*', 'multiple' => false], 'showPreview' => true, 'showCaption' => false, 'showRemove' => true,'showUpload' => true, $editorSettings ]])";
                else
                    $result = "$function(\$model, '$attribute')->image()";
                //$result = "$function(\$model, '$attribute')->widget($editor::className(), ['pluginOptions' => [ 'model' => \$model, 'maxFileSize'=> FHtml::config('FILE_SIZE_MAX', 4000000), 'options' => ['accept' => 'image/*', 'multiple' => true], 'showPreview' => true, 'showCaption' => false, 'showRemove' => true,'showUpload' => true, $editorSettings ]])";

                if (FHtml::isInArray($column->name, ['*_description']))
                    $result = "$function(\$model, '$attribute')->textarea(['rows' => 3])";
            }
            else if ($editor == 'select' || FHtml::isInArray($column->name, $lookupAttributes) || ($column->size == 500) || ($column->size == 100) || ($column->size == 10) || ($column->size == 20) || ($column->size == 50))
            {
                $editor = FHtml::EDITOR_SELECT;
                if (empty($editorSettings)) $editorSettings = FHtml::EDITOR_SELECT_SETTINGS;
                $append = in_array($attribute, ['category_id']) ? '_array' : '';
                $attribute = $attribute . $append;
                if ($column->size <= 100)
                    $result = "$function(\$model, '$attribute')->select(FHtml::getComboArray('$lookup_key', '$lookup_table', '$column->name', true, 'id', 'name'))";
                //$result .= "$function(\$model, '$attribute')->widget($editor::className(),['data' => FHtml::getComboArray('$lookup_key', '$lookup_table', '$column->name', true, 'id', 'name'), 'options'=>['multiple' => false], $editorSettings])";
                else
                    $result = "$function(\$model, '$attribute')->selectMany(FHtml::getComboArray('$lookup_key', '$lookup_table', '$column->name', true, 'id', 'name'))";
                //$result .= "$function(\$model, '$attribute')->widget($editor::className(),['data' => FHtml::getComboArray('$lookup_key', '$lookup_table', '$column->name', true, 'id', 'name'), 'options'=>['multiple' => true], $editorSettings])";
            }
            else if ($editor == 'text' || FHtml::isInArray($column->name, $htmlAttributes) || ($column->size > 2000))
            {
                $editor = FHtml::EDITOR_TEXT;
                if (empty($editorSettings)) $editorSettings = FHtml::EDITOR_TEXT_SETTINGS;
                if (!$shortForm) {
                    $result = "$function(\$model, '$attribute')->html()";
                } else {
                    $result = "$function(\$model, '$attribute')->textarea(['rows' => 3])";
                }
            }
            else if ($editor == 'textarea' || FHtml::isInArray($column->name, $textareaAttributes) || ($column->size > 1000 && $column->size <= 2000))
            {
                $result = "$function(\$model, '$attribute')->textarea(['rows' => 3])";
            }
            else if ($editor == 'textarea' || FHtml::isInArray($column->name, $textareaSmallAttributes) || ($column->size > 500 && $column->size <= 1000))
            {
                $result = "$function(\$model, '$attribute')->textarea(['rows' => 3])";
            }
            else if ($column->isPrimaryKey)
            {
                $result = ""; //"$function(\$model, '$attribute')->textInput(['disabled' => true])";
            }
            else if ($editor == 'mask' || $column->size == 90) { // MaskInput, with mask value get from column comments
                $editor = FHtml::EDITOR_MASK;
                if (empty($editorSettings)) $editorSettings = isset($commentArray['mask']) ? $commentArray['mask'] : '';
                //$result = "$function(\$model, '$attribute')->widget($editor::className(), ['pluginOptions' => ['mask' => ['$editorSettings'']]])";
                $result = "$function(\$model, '$attribute')->maskedInput('$editorSettings')";

            } else {
               $result = "$function(\$model, '$attribute')->textInput()";
            }
        }


        $result = $commentOut . $result . $colSpan;
        return $result;

    }

    public function generateShowModelField($attribute)
    {
        $previewAttributes = FHtml::FIELDS_PREVIEW;
        $hiddenAttributes = FHtml::FIELDS_HIDDEN;
        $countAttributes = FHtml::FIELDS_COUNT;
        $groupAttributes = FHtml::getFIELDS_GROUP();
        $uploadAttributes = FHtml::FIELDS_UPLOAD;
        $priceAttributes = FHtml::FIELDS_PRICE;
        $dateAttributes = FHtml::FIELDS_DATE;
        $commonAttributes = FHtml::FIELDS_COMMON;

        $htmlAttributes =  FHtml::FIELDS_HTML;
        $textareaAttributes = FHtml::FIELDS_TEXTAREA;
        $textareaSmallAttributes = FHtml::FIELDS_TEXTAREASMALL;
        $lookupAttributes = FHtml::FIELDS_LOOKUP;
        $dateAttributes = FHtml::FIELDS_DATE;
        $datetimeAttributes = FHtml::FIELDS_TIME;
        $rateAttributes = FHtml::FIELDS_RATE;

        $booleanAttributes = FHtml::FIELDS_BOOLEAN;
        $fileAttributes = FHtml::FIELDS_FILES;
        $imageAttributes = FHtml::FIELDS_IMAGES;
        $percentAttributes = FHtml::FIELDS_PERCENT;

        $tableSchema = $this->getTableSchema();
        $result = "";

        if ($tableSchema === false || !isset($tableSchema->columns[$attribute])) {
            if (preg_match('/^(password|pass|passwd|passcode)$/i', $attribute)) {
                $result = "";
            } else {
                $result = "";
            }
        }
        $column = $tableSchema->columns[$attribute];
        $commentArray = FHtml::toArrayFromDbComment($column->comment, $column->name);
        $editor = isset($commentArray['editor']) ? $commentArray['editor'] : '';
        $editorSettings = isset($commentArray['setting']) ? $commentArray['setting'] : '';
        $commentOut = $editor == 'hidden' ? '//' : '';
        $lookup_key = isset($commentArray['lookup']) ? $commentArray['lookup'] : $tableSchema->name;

        if ($editor == 'boolean' || $column->phpType === 'boolean' || FHtml::isInArray($column->name, $booleanAttributes) || (StringHelper::startsWith($column->dbType, 'tinyint') && $column->size == 1)) {
            $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_ACTIVE, \$field_layout, \$form_label_CSS, '$tableSchema->name', '$column->name', '$column->dbType', '', '')";
        } elseif ($editor == 'text' || $column->dbType === 'text') {
            $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_HTML, \$field_layout, \$form_label_CSS, '$tableSchema->name', '$column->name', '$column->dbType', '', '')";

        } elseif ($editor == 'date' || (empty($editor) && (FHtml::isInArray($column->name, $dateAttributes) || StringHelper::startsWith($column->dbType, 'date')))) {
            $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_DATE, \$field_layout, \$form_label_CSS, '$tableSchema->name', '$column->name', '$column->dbType', '', '')";

        } elseif ($editor == 'time' || FHtml::isInArray($column->name, $datetimeAttributes) || strpos($column->dbType, 'time') !== false) {
            $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_DATETIME, \$field_layout, \$form_label_CSS, '$tableSchema->name', '$column->name', '$column->dbType', '', '')";

        } elseif (strpos($column->dbType, 'int') != false || StringHelper::startsWith($column->dbType, 'tinyint') || (StringHelper::startsWith($column->dbType, 'int') && $column->size > 1)) {

            if ($editor == 'select' || FHtml::isInArray($column->name, $lookupAttributes)) {
                $show_type = StringHelper::startsWith($lookup_key, '@') ? 'LOOKUP' : 'LABEL';
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_$show_type, \$field_layout, \$form_label_CSS, '$lookup_key', '$column->name', '$column->dbType', '', '')";
            }
            else if ($editor == 'range' || strpos($column->name, 'range') !== false || strpos($column->name, 'point') !== false)
            {
                $result .= "FHtml::showModelField(\$model,'$attribute', \$field_layout, \$form_label_CSS, '$tableSchema->name', '$column->name', '$column->dbType', '', '')";
            }
            else if ($editor == 'rate' || FHtml::isInArray($column->name, $rateAttributes))
            {
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_RATE, \$field_layout, \$form_label_CSS, '$tableSchema->name', '$column->name', '$column->dbType', '', '')";
            }
            else if ($editor == 'slide' || $column->size == 3 || FHtml::isInArray($column->name, $percentAttributes))
            {
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_NUMBER, \$field_layout, \$form_label_CSS, '$tableSchema->name', '$column->name', '$column->dbType', '', '')";
            }
            else {
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_NUMBER, \$field_layout, \$form_label_CSS, '$tableSchema->name', '$column->name', '$column->dbType', '', '')";
            }

        } elseif (StringHelper::startsWith($column->dbType, 'decimal') || StringHelper::startsWith($column->dbType, 'double') || StringHelper::startsWith($column->dbType, 'float')) {
            if ($column->precision > 0) // assume it is money
            {
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_CURRENCY, \$field_layout, \$form_label_CSS, '$tableSchema->name', '$column->name', '$column->dbType', '', '')";

            } else {
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_DECIMAL, \$field_layout, \$form_label_CSS, '$tableSchema->name', '$column->name', '$column->dbType', '', '')";
            }

        } elseif (is_array($column->enumValues) && count($column->enumValues) > 0) {
            $dropDownOptions = [];
            foreach ($column->enumValues as $enumValue) {
                $dropDownOptions[$enumValue] = Inflector::humanize($enumValue);
            }
            $result = "\$form->field(\$model, '$attribute')->dropDownList("
                . preg_replace("/\n\s*/", ' ', VarDumper::export($dropDownOptions)).", ['prompt' => ''])";
        }
        else {
            //varchar
            if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name)) {
                $result = "";

            } else if ($editor == 'color' || strpos($column->name, 'color') !== false)
            {
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_COLOR, \$field_layout, \$form_label_CSS, '$tableSchema->name')";
            }
            else if ($editor == 'email' || strpos($column->name, 'email') !== false)
            {
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_EMAIL, \$field_layout, \$form_label_CSS, '$tableSchema->name')";
            }
            else if ($editor == 'range' || strpos($column->name, 'range') !== false)
            {
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_RANGE, \$field_layout, \$form_label_CSS, '$tableSchema->name')";
            }
            else if ($editor == 'date' || (empty($editor) && (FHtml::isInArray($column->name, $dateAttributes) || FHtml::isInArray($column->name, $datetimeAttributes))))
            {
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_DATE, \$field_layout, \$form_label_CSS, '$tableSchema->name')";
            }
            else if ($editor == 'file' ||FHtml::isInArray($column->name, $fileAttributes) || ($column->size == 310) || ($column->size == 930))
            {
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_FILE, \$field_layout, \$form_label_CSS, '$tableSchema->name')";
            }
            else if ($editor == 'image' || FHtml::isInArray($column->name, $imageAttributes) || ($column->size == 300) || ($column->size == 900))
            {
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_IMAGE, \$field_layout, \$form_label_CSS, '$tableSchema->name')";
            }
            else if ($editor == 'select' || FHtml::isInArray($column->name, $lookupAttributes) || ($column->size == 500) || ($column->size == 100) || ($column->size == 10) || ($column->size == 20) || ($column->size == 50))
            {
                $show_type = StringHelper::startsWith($lookup_key, '@') ? 'LOOKUP' : 'LABEL';

                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_$show_type, \$field_layout, \$form_label_CSS, '$lookup_key')";
            }
            else if ($editor == 'text' || FHtml::isInArray($column->name, $htmlAttributes) || ($column->size > 2000))
            {
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_TEXT, \$field_layout, \$form_label_CSS, '$tableSchema->name')";
            }
            else if ($editor == 'textarea' || FHtml::isInArray($column->name, $textareaAttributes) || ($column->size > 1000 && $column->size <= 2000))
            {
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_TEXT, \$field_layout, \$form_label_CSS, '$tableSchema->name')";
            }
            else if ($editor == 'textarea' || FHtml::isInArray($column->name, $textareaSmallAttributes) || ($column->size > 500 && $column->size <= 1000))
            {
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_TEXT, \$field_layout, \$form_label_CSS, '$tableSchema->name')";
            }
            else if ($column->isPrimaryKey)
            {
                $result = ""; //"\$form->field(\$model, '$attribute')->textInput(['disabled' => true])";
            }
            else if ($editor == 'mask' || $column->size == 90) { // MaskInput, with mask value get from column comments
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_MASK, \$field_layout, \$form_label_CSS, '$tableSchema->name')";

            } else
            {
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_TEXT, \$field_layout, \$form_label_CSS, '$tableSchema->name')";
            }
        }

        if (empty($result))
            $result = "'<br/>'; ";
        //$result .= " //name: $column->name, dbType: $column->dbType, phpType: $column->phpType, size: $column->size, allowNull: $column->allowNull \n" . $result;
        return "" . $commentOut . $result;

    }


    /**
     * Generates code for active search field
     * @param string $attribute
     * @return string
     */
    public function generateActiveSearchField($attribute)
    {
        $tableSchema = $this->getTableSchema();
        if ($tableSchema === false) {
            return "\$form->field(\$model, '$attribute')";
        }
        $column = $tableSchema->columns[$attribute];
        if ($column->phpType === 'boolean') {
            return "\$form->field(\$model, '$attribute')->checkbox()";
        } else {
            return "\$form->field(\$model, '$attribute')";
        }
    }

    /**
     * Generates column format
     * @param \yii\db\ColumnSchema $column
     * @return string
     */
    public function generateColumnFormat($column)
    {
        if ($column->phpType === 'boolean') {
            return 'boolean';
        } elseif ($column->type === 'text') {
            return 'ntext';
        } elseif (stripos($column->name, 'time') !== false && $column->phpType === 'integer') {
            return 'datetime';
        } elseif (stripos($column->name, 'email') !== false) {
            return 'email';
        } elseif (stripos($column->name, 'url') !== false) {
            return 'url';
        } else {
            return 'text';
        }
    }

    /**
     * Generates validation rules for the search model.
     * @return array the generated validation rules
     */
    public function generateSearchRules()
    {
        if (($table = $this->getTableSchema()) === false) {
            return ["[['" . implode("', '", $this->getColumnNames()) . "'], 'safe']"];
        }
        $types = [];
        foreach ($table->columns as $column) {
            switch ($column->type) {
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
                    $types['integer'][] = $column->name;
                    break;
                case Schema::TYPE_BOOLEAN:
                    $types['boolean'][] = $column->name;
                    break;
                case Schema::TYPE_FLOAT:
                case Schema::TYPE_DOUBLE:
                case Schema::TYPE_DECIMAL:
                case Schema::TYPE_MONEY:
                    $types['number'][] = $column->name;
                    break;
                case Schema::TYPE_DATE:
                case Schema::TYPE_TIME:
                case Schema::TYPE_DATETIME:
                case Schema::TYPE_TIMESTAMP:
                default:
                    $types['safe'][] = $column->name;
                    break;
            }
        }

        $rules = [];
        foreach ($types as $type => $columns) {
            $rules[] = "[['" . implode("', '", $columns) . "'], '$type']";
        }

        return $rules;
    }

    protected function getDbConnection()
    {
        if (class_exists(FHtml::className()))
            return FHtml::currentDb();
        else
            return Yii::$app->get($this->db, false);
    }

    /**
     * @inheritdoc
     */
    public function autoCompleteData()
    {
        $db = $this->getDbConnection();
        if ($db !== null) {
            return [
                'tableName' => function () use ($db) {
                    return $db->getSchema()->getTableNames();
                },
            ];
        } else {
            return [];
        }
    }

    protected function getSchemaNames()
    {
        $db = $this->getDbConnection();
        $schema = $db->getSchema();
        if ($schema->hasMethod('getSchemaNames')) { // keep BC to Yii versions < 2.0.4
            try {
                $schemaNames = $schema->getSchemaNames();
            } catch (NotSupportedException $e) {
                // schema names are not supported by schema
            }
        }
        if (!isset($schemaNames)) {
            if (($pos = strpos($this->tableName, '.')) !== false) {
                $schemaNames = [substr($this->tableName, 0, $pos)];
            } else {
                $schemaNames = [''];
            }
        }
        return $schemaNames;
    }

    /**
     * @return array searchable attributes
     */
    public function getSearchAttributes()
    {
        return $this->getColumnNames();
    }

    /**
     * Generates the attribute labels for the search model.
     * @return array the generated attribute labels (name => label)
     */
    public function generateSearchLabels()
    {
        /* @var $model \yii\base\Model */
        $model = new $this->modelClass();
        $attributeLabels = $model->attributeLabels();
        $labels = [];
        foreach ($this->getColumnNames() as $name) {
            if (isset($attributeLabels[$name])) {
                $labels[$name] = $attributeLabels[$name];
            } else {
                if (!strcasecmp($name, 'id')) {
                    $labels[$name] = 'ID';
                } else {
                    $label = Inflector::camel2words($name);
                    if (!empty($label) && substr_compare($label, ' id', -3, 3, true) === 0) {
                        $label = substr($label, 0, -3) . ' ID';
                    }
                    $labels[$name] = $label;
                }
            }
        }

        return $labels;
    }

    /**
     * Generates search conditions
     * @return array
     */
    public function generateSearchConditions($searchExact = false)
    {
        $columns = [];
        if (($table = $this->getTableSchema()) === false) {
            $class = $this->modelClass;
            /* @var $model \yii\base\Model */
            $model = new $class();
            foreach ($model->attributes() as $attribute) {
                $columns[$attribute] = 'unknown';
            }
        } else {
            foreach ($table->columns as $column) {
                if ($column->size < 200)
                    $name = '@' . $column->name;
                else
                    $name = $column->name;

                $columns[$name] = $column->type;
            }
        }

        $likeConditions = [];
        $hashConditions = [];
        foreach ($columns as $column => $type) {
            switch ($type) {
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
                case Schema::TYPE_BOOLEAN:
                case Schema::TYPE_FLOAT:
                case Schema::TYPE_DOUBLE:
                case Schema::TYPE_DECIMAL:
                case Schema::TYPE_MONEY:
                case Schema::TYPE_DATE:
                case Schema::TYPE_TIME:
                case Schema::TYPE_DATETIME:
                case Schema::TYPE_TIMESTAMP:
                    $column = str_replace('@', '', $column);
                    $hashConditions[] = "'{$column}' => \$this->{$column},";
                    break;
                default:
                    if (StringHelper::startsWith($column, '@') || $searchExact) {
                        $column = str_replace('@', '', $column);
                        $hashConditions[] = "'{$column}' => \$this->{$column},";
                    }
                    else
                        $likeConditions[] = "->andFilterWhere(['like', '{$column}', \$this->{$column}])";
                    break;
            }
        }

        $conditions = [];
        if (!empty($hashConditions)) {
            $conditions[] = "\$query->andFilterWhere([\n"
                . str_repeat(' ', 12) . implode("\n" . str_repeat(' ', 12), $hashConditions)
                . "\n" . str_repeat(' ', 8) . "]);\n";
        }
        if (!empty($likeConditions)) {
            $conditions[] = "\$query" . implode("\n" . str_repeat(' ', 12), $likeConditions) . ";\n";
        }

        return $conditions;
    }

    /**
     * Generates URL parameters
     * @return string
     */
    public function generateUrlParams()
    {
        /* @var $class ActiveRecord */
        $class = $this->modelClass;
        $pks = $class::primaryKey();
        if (count($pks) === 1) {
            if (is_subclass_of($class, 'yii\mongodb\ActiveRecord')) {
                return "'id' => (string)\$model->{$pks[0]}";
            } else {
                return "'id' => \$model->{$pks[0]}";
            }
        } else {
            $params = [];
            foreach ($pks as $pk) {
                if (is_subclass_of($class, 'yii\mongodb\ActiveRecord')) {
                    $params[] = "'$pk' => (string)\$model->$pk";
                } else {
                    $params[] = "'$pk' => \$model->$pk";
                }
            }

            return implode(', ', $params);
        }
    }

    /**
     * Generates action parameters
     * @return string
     */
    public function generateActionParams()
    {
        /* @var $class ActiveRecord */
        $class = $this->modelClass;
        $pks = $class::primaryKey();
        if (count($pks) === 1) {
            return '$id';
        } else {
            return '$' . implode(', $', $pks);
        }
    }

    /**
     * Generates parameter tags for phpdoc
     * @return array parameter tags for phpdoc
     */
    public function generateActionParamComments()
    {
        /* @var $class ActiveRecord */
        $class = $this->modelClass;
        $pks = $class::primaryKey();
        if (($table = $this->getTableSchema()) === false) {
            $params = [];
            foreach ($pks as $pk) {
                $params[] = '@param ' . (substr(strtolower($pk), -2) == 'id' ? 'integer' : 'string') . ' $' . $pk;
            }

            return $params;
        }
        if (count($pks) === 1) {
            return ['@param ' . $table->columns[$pks[0]]->phpType . ' $id'];
        } else {
            $params = [];
            foreach ($pks as $pk) {
                $params[] = '@param ' . $table->columns[$pk]->phpType . ' $' . $pk;
            }

            return $params;
        }
    }

    /**
     * Returns table schema for current model class or false if it is not an active record
     * @return boolean|\yii\db\TableSchema
     */
    public function getTableSchema()
    {
        /* @var $class ActiveRecord */
        $class = $this->modelClass;
        if (is_subclass_of($class, 'yii\db\ActiveRecord')) {
            return $class::getTableSchema();
        } else {
            return false;
        }
    }

    /**
     * @return array model column names
     */
    public function getColumnNames()
    {
        /* @var $class ActiveRecord */
        $class = $this->modelClass;
        if (is_subclass_of($class, 'yii\db\ActiveRecord')) {
            return $class::getTableSchema()->getColumnNames();
        } else {
            /* @var $model \yii\base\Model */
            $model = new $class();

            return $model->attributes();
        }
    }

    /**
     * @return array model column names
     */
    public function getGridColumnNames()
    {
        if (strlen($this->gridFields) > 0) {
            return explode(",", $this->gridFields);
        }

        /* @var $class ActiveRecord */
        $class = $this->modelClass;
        if (is_subclass_of($class, 'yii\db\ActiveRecord')) {
            return $class::getTableSchema()->getColumnNames();
        } else {
            /* @var $model \yii\base\Model */
            $model = new $class();

            return $model->attributes();
        }
    }

    public function getColumns() {
        $tableSchema = self::getTableSchema();
        return $tableSchema->columns;
    }

    public function generateFieldLabel($attribute)
    {
        $previewAttributes = FHtml::FIELDS_PREVIEW;
        $hiddenAttributes = FHtml::FIELDS_HIDDEN;
        $countAttributes = FHtml::FIELDS_COUNT;
        $groupAttributes = FHtml::getFIELDS_GROUP();
        $uploadAttributes = FHtml::FIELDS_UPLOAD;
        $priceAttributes = FHtml::FIELDS_PRICE;
        $dateAttributes = FHtml::FIELDS_DATE;
        $commonAttributes = FHtml::FIELDS_COMMON;

        $htmlAttributes =  FHtml::FIELDS_HTML;
        $textareaAttributes = FHtml::FIELDS_TEXTAREA;
        $textareaSmallAttributes = FHtml::FIELDS_TEXTAREASMALL;
        $lookupAttributes = FHtml::FIELDS_LOOKUP;
        $dateAttributes = FHtml::FIELDS_DATE;
        $datetimeAttributes = FHtml::FIELDS_TIME;
        $rateAttributes = FHtml::FIELDS_RATE;

        $booleanAttributes = FHtml::FIELDS_BOOLEAN;
        $fileAttributes = FHtml::FIELDS_FILES;
        $imageAttributes = FHtml::FIELDS_IMAGES;
        $percentAttributes = FHtml::FIELDS_PERCENT;

        $tableSchema = $this->getTableSchema();
        $result = "";

        if ($tableSchema === false || !isset($tableSchema->columns[$attribute])) {
            if (preg_match('/^(password|pass|passwd|passcode)$/i', $attribute)) {
                $result = "";
            } else {
                $result = "";
            }
        }
        $column = $tableSchema->columns[$attribute];
        $commentArray = FHtml::toArrayFromDbComment($column->comment, $column->name);
        $editor = isset($commentArray['editor']) ? $commentArray['editor'] : '';
        $editorSettings = isset($commentArray['setting']) ? $commentArray['setting'] : '';
        $commentOut = $editor == 'hidden' ? '//' : '';
        $lookup_key = isset($commentArray['lookup']) ? $commentArray['lookup'] : $tableSchema->name;

        if ($editor == 'boolean' || $column->phpType === 'boolean' || FHtml::isInArray($column->name, $booleanAttributes) || (StringHelper::startsWith($column->dbType, 'tinyint') && $column->size == 1)) {
            $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_ACTIVE, \$field_layout, \$form_label_CSS, '$tableSchema->name', '$column->name', '$column->dbType', '', '')";
        } elseif ($editor == 'text' || $column->dbType === 'text') {
            $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_HTML, \$field_layout, \$form_label_CSS, '$tableSchema->name', '$column->name', '$column->dbType', '', '')";

        } elseif ($editor == 'date' || (empty($editor) && (FHtml::isInArray($column->name, $dateAttributes) || StringHelper::startsWith($column->dbType, 'date')))) {
            $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_DATE, \$field_layout, \$form_label_CSS, '$tableSchema->name', '$column->name', '$column->dbType', '', '')";

        } elseif ($editor == 'time' || FHtml::isInArray($column->name, $datetimeAttributes) || strpos($column->dbType, 'time') !== false) {
            $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_DATETIME, \$field_layout, \$form_label_CSS, '$tableSchema->name', '$column->name', '$column->dbType', '', '')";

        } elseif (strpos($column->dbType, 'int') != false || StringHelper::startsWith($column->dbType, 'tinyint') || (StringHelper::startsWith($column->dbType, 'int') && $column->size > 1)) {

            if ($editor == 'select' || FHtml::isInArray($column->name, $lookupAttributes)) {
                $show_type = StringHelper::startsWith($lookup_key, '@') ? 'LOOKUP' : 'LABEL';
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_$show_type, \$field_layout, \$form_label_CSS, '$lookup_key', '$column->name', '$column->dbType', '', '')";
            }
            else if ($editor == 'range' || strpos($column->name, 'range') !== false || strpos($column->name, 'point') !== false)
            {
                $result .= "FHtml::showModelField(\$model,'$attribute', \$field_layout, \$form_label_CSS, '$tableSchema->name', '$column->name', '$column->dbType', '', '')";
            }
            else if ($editor == 'rate' || FHtml::isInArray($column->name, $rateAttributes))
            {
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_RATE, \$field_layout, \$form_label_CSS, '$tableSchema->name', '$column->name', '$column->dbType', '', '')";
            }
            else if ($editor == 'slide' || $column->size == 3 || FHtml::isInArray($column->name, $percentAttributes))
            {
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_NUMBER, \$field_layout, \$form_label_CSS, '$tableSchema->name', '$column->name', '$column->dbType', '', '')";
            }
            else {
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_NUMBER, \$field_layout, \$form_label_CSS, '$tableSchema->name', '$column->name', '$column->dbType', '', '')";
            }

        } elseif (StringHelper::startsWith($column->dbType, 'decimal') || StringHelper::startsWith($column->dbType, 'double') || StringHelper::startsWith($column->dbType, 'float')) {
            if ($column->precision > 0) // assume it is money
            {
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_CURRENCY, \$field_layout, \$form_label_CSS, '$tableSchema->name', '$column->name', '$column->dbType', '', '')";

            } else {
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_DECIMAL, \$field_layout, \$form_label_CSS, '$tableSchema->name', '$column->name', '$column->dbType', '', '')";
            }

        } elseif (is_array($column->enumValues) && count($column->enumValues) > 0) {
            $dropDownOptions = [];
            foreach ($column->enumValues as $enumValue) {
                $dropDownOptions[$enumValue] = Inflector::humanize($enumValue);
            }
            $result = "\$form->field(\$model, '$attribute')->dropDownList("
                . preg_replace("/\n\s*/", ' ', VarDumper::export($dropDownOptions)).", ['prompt' => ''])";
        }
        else {
            //varchar
            if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name)) {
                $result = "";

            } else if ($editor == 'color' || strpos($column->name, 'color') !== false)
            {
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_COLOR, \$field_layout, \$form_label_CSS, '$tableSchema->name')";
            }
            else if ($editor == 'email' || strpos($column->name, 'email') !== false)
            {
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_EMAIL, \$field_layout, \$form_label_CSS, '$tableSchema->name')";
            }
            else if ($editor == 'range' || strpos($column->name, 'range') !== false)
            {
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_RANGE, \$field_layout, \$form_label_CSS, '$tableSchema->name')";
            }
            else if ($editor == 'date' || (empty($editor) && (FHtml::isInArray($column->name, $dateAttributes) || FHtml::isInArray($column->name, $datetimeAttributes))))
            {
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_DATE, \$field_layout, \$form_label_CSS, '$tableSchema->name')";
            }
            else if ($editor == 'file' ||FHtml::isInArray($column->name, $fileAttributes) || ($column->size == 310) || ($column->size == 930))
            {
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_FILE, \$field_layout, \$form_label_CSS, '$tableSchema->name')";
            }
            else if ($editor == 'image' || FHtml::isInArray($column->name, $imageAttributes) || ($column->size == 300) || ($column->size == 900))
            {
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_IMAGE, \$field_layout, \$form_label_CSS, '$tableSchema->name')";
            }
            else if ($editor == 'select' || FHtml::isInArray($column->name, $lookupAttributes) || ($column->size == 500) || ($column->size == 100) || ($column->size == 10) || ($column->size == 20) || ($column->size == 50))
            {
                $show_type = StringHelper::startsWith($lookup_key, '@') ? 'LOOKUP' : 'LABEL';

                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_$show_type, \$field_layout, \$form_label_CSS, '$lookup_key')";
            }
            else if ($editor == 'text' || FHtml::isInArray($column->name, $htmlAttributes) || ($column->size > 2000))
            {
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_TEXT, \$field_layout, \$form_label_CSS, '$tableSchema->name')";
            }
            else if ($editor == 'textarea' || FHtml::isInArray($column->name, $textareaAttributes) || ($column->size > 1000 && $column->size <= 2000))
            {
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_TEXT, \$field_layout, \$form_label_CSS, '$tableSchema->name')";
            }
            else if ($editor == 'textarea' || FHtml::isInArray($column->name, $textareaSmallAttributes) || ($column->size > 500 && $column->size <= 1000))
            {
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_TEXT, \$field_layout, \$form_label_CSS, '$tableSchema->name')";
            }
            else if ($column->isPrimaryKey)
            {
                $result = ""; //"\$form->field(\$model, '$attribute')->textInput(['disabled' => true])";
            }
            else if ($editor == 'mask' || $column->size == 90) { // MaskInput, with mask value get from column comments
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_MASK, \$field_layout, \$form_label_CSS, '$tableSchema->name')";

            } else
            {
                $result .= "FHtml::showModelField(\$model,'$attribute', FHtml::SHOW_TEXT, \$field_layout, \$form_label_CSS, '$tableSchema->name')";
            }
        }

        if (empty($result))
            $result = "'<br/>'; ";
        //$result .= " //name: $column->name, dbType: $column->dbType, phpType: $column->phpType, size: $column->size, allowNull: $column->allowNull \n" . $result;
        return "" . $commentOut . $result;

    }
}
