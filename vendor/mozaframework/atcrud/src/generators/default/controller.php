<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\helpers\StringHelper;
use yii\db\ActiveRecordInterface;
use yii\helpers\BaseInflector;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass . 'Search';
}

/* @var $class ActiveRecordInterface */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use Yii;
use <?= ltrim($generator->modelClass, '\\') ?>;
<?php if (!empty($generator->searchModelClass)): ?>
use <?= ltrim($generator->searchModelClass, '\\') . (isset($searchModelAlias) ? " as $searchModelAlias" : "") ?>;
<?php else: ?>
use yii\data\ActiveDataProvider;
<?php endif; ?>
use backend\controllers\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Html;
use yii\helpers\Json;
use common\components\AccessRule;
use common\models\User;
use yii\filters\AccessControl;
use common\components\FHtml;

/**
 * <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
 */
class <?= $controllerClass ?> extends AdminController
{
    protected $moduleName = '<?= $modelClass ?>';
    protected $moduleTitle = '<?= BaseInflector::camel2words($modelClass) ?>';
    protected $moduleKey = '<?= BaseInflector::camel2id($modelClass, '_') ?>';
    protected $object_type = '<?= BaseInflector::camel2id($modelClass, '_') ?>';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return FHtml::getControllerBehaviours([
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'only' => ['create', 'update', 'delete', 'view', 'index'],
                'rules' => [
                    [
                        'actions' => ['view', 'index', 'create'],
                        'allow' => true,
                        'roles' => [
                            User::ROLE_MODERATOR, User::ROLE_ADMIN, User::ROLE_USER
                        ],
                    ],
                    [
                        'actions' => ['update', 'delete'],
                        'allow' => true,
                        'roles' => [
                            User::ROLE_ADMIN
                        ],
                    ],
                ],
            ],
        ]);
    }

    /**
     * Lists all <?= $modelClass ?> models.
     * @return mixed
     */
    public function actionIndex()
    {
<?php if (!empty($generator->searchModelClass)): ?>
        /* @var $searchModel <?= $modelClass ?>Search */
        $searchModel = <?= $modelClass ?>::createNew();
        $dataProvider = $searchModel->search(array_merge(Yii::$app->request->queryParams, FHtml::getRequestPost()));

        //Save model if has Create new form in Index view
        FHtml::saveModel($this->object_type);

        if (Yii::$app->request->post('hasEditable')) {
            $Id = Yii::$app->request->post('editableKey');

            $model = <?= $modelClass ?>::findOne($Id);

            $out = Json::encode(['output' => '', 'message' => '']);

            $post = [];
            $posted = current($_POST['<?= $modelClass ?>']);
            $post['<?= $modelClass ?>'] = $posted;

            if ($model->load($post)) {
                $model->save();
                $output = '';
                $out = Json::encode(['output' => $output, 'message' => '']);
            }
            echo $out;
            return;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
<?php else: ?>
        $dataProvider = new ActiveDataProvider([
            'query' => <?= $modelClass ?>::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
<?php endif; ?>
    }


    /**
     * Displays a single <?= $modelClass ?> model.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionView(<?= $actionParams ?>)
    {
        return parent::actionView(<?= $actionParams ?>);
    }

    /**
     * Creates a new <?= $modelClass ?> model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        return parent::actionCreate();
    }

    /**
     * Updates an existing <?= $modelClass ?> model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionUpdate(<?= $actionParams ?>)
    {
        return parent::actionUpdate(<?= $actionParams ?>);
    }

    /**
     * Delete an existing <?= $modelClass ?> model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionDelete(<?= $actionParams ?>)
    {
        return parent::actionDelete(<?= $actionParams ?>);
    }

    /**
     * Delete multiple existing <?= $modelClass ?> model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionBulkDelete()
    {
        return parent::actionBulkDelete();
    }

    public function actionBulkAction($action = '', $field = '', $value = '')
    {
        return parent::actionBulkAction($action, $field, $value);
    }

    /**
     * Finds the <?= $modelClass ?> model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return <?= $modelClass ?> the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(<?= $actionParams ?>)
    {
<?php
if (count($pks) === 1) {
    $condition = '$id';
} else {
    $condition = [];
    foreach ($pks as $pk) {
        $condition[] = "'$pk' => \$$pk";
    }
    $condition = '[' . implode(', ', $condition) . ']';
}
?>
        $model = <?= $modelClass ?>::findOne(<?= $actionParams ?>);
        if (isset($model)) {
            return $model;
        } else {
            throw new NotFoundHttpException('Data does not exist');
        }
    }

    /**
     * Create the <?= $modelClass ?> model.
     * @return <?= $modelClass ?> the loaded model
     */
    protected function createModel($className = '', $id = '', $params = null)
    {
        $model = parent::createModel($className, $id, $params);
        return $model;
    }

    public function saveModelAjax($model, $params = null, $modelMeta = null)
    {
        return parent::saveModelAjax($model, $params, $modelMeta);
    }

    public function loadModel($model, $params = []) {
        return parent::loadModel($model, $params);
    }

    public function returnView($action, $id) {
        return parent::returnView($action, $id);
    }

    protected function getPjaxContainerId($object_type = '', $type = 'crud-datatable') {
        return parent::getPjaxContainerId($object_type, $type);
    }
}