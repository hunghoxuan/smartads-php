<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\helpers\StringHelper;
use yii\db\ActiveRecordInterface;
use yii\helpers\Inflector;

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

$file_fields = array(
    'thumbnail',
    'image',
    'icon',
    'logo',
    'avatar',
    'attachment',
    'cover',
);

$datetime_fields = array(
    'created_date'
);
$namespace = StringHelper::dirname(ltrim($generator->controllerClass, '\\'));

echo "<?php\n";
?>

namespace <?= $namespace ?>;

use Yii;
use <?= ltrim($generator->modelClass, '\\') ?>;
<?php if (!empty($generator->searchModelClass)): ?>
use <?= ltrim($generator->searchModelClass, '\\') . (isset($searchModelAlias) ? " as $searchModelAlias" : "") ?>;
<?php else: ?>
use yii\data\ActiveDataProvider;
<?php endif; ?>
//use <?= ltrim($generator->baseControllerClass, '\\') ?>;
<?php if($namespace != 'backend\controllers'): ?>
use backend\controllers\BackendController;
<?php endif; ?>
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Html;
use yii\helpers\Json;
use common\components\AccessRule;
use common\models\User;
use yii\filters\AccessControl;
use common\components\FHtml;
<?php
$columnsNames= $generator->getColumnNames();
$check = array_intersect($file_fields, $columnsNames);
if(count($check)!=0){ ?>
use yii\web\UploadedFile;
<?php } ?>
<?php
$title = Inflector::camel2words(StringHelper::basename($generator->modelClass));
$folder_name = Inflector::camel2id(StringHelper::basename($generator->modelClass));
$lower_name = str_replace('-','_',$folder_name);
$check_datetime = array_intersect($datetime_fields, $columnsNames);
?>


/**
 * <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
 */
class <?= $controllerClass ?> extends BackendController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
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
                            User::ROLE_USER,
                            User::ROLE_MODERATOR,
                            User::ROLE_ADMIN
                        ],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => [
                            User::ROLE_MODERATOR,
                            User::ROLE_ADMIN
                        ],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => [
                            User::ROLE_ADMIN
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all <?= $modelClass ?> models.
     * @return mixed
     */
    public function actionIndex()
    {
       <?php if (!empty($generator->searchModelClass)): ?>
 $searchModel = new <?= isset($searchModelAlias) ? $searchModelAlias : $searchModelClass ?>();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // validate if there is a editable input saved via AJAX
        if (Yii::$app->request->post('hasEditable')) {
            // instantiate your book model for saving
            $Id = Yii::$app->request->post('editableKey');

            $model = <?= $modelClass ?>::findOne($Id);

            // store a default json response as desired by editable
            $out = Json::encode(['output' => '', 'message' => '']);

            // fetch the first entry in posted data (there should
            // only be one entry anyway in this array for an
            // editable submission)
            // - $posted is the posted data for Book without any indexes
            // - $post is the converted array for single model validation
            $post = [];
            $posted = current($_POST['<?= $modelClass ?>']);
            $post['<?= $modelClass ?>'] = $posted;

            // load model like any single model validation
            if ($model->load($post)) {
                // can save model or do something before saving model
                $model->save();

                // custom output to return to be displayed as the editable grid cell
                // data. Normally this is empty - whereby whatever value is edited by
                // in the input by user is updated automatically.
                $output = '';
                // similarly you can check if the name attribute was posted as well
                // if (isset($posted['name'])) {
                //   $output =  ''; // process as you need
                // }
                $out = Json::encode(['output' => $output, 'message' => '']);
            }
            // return ajax json encoded response and exit
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
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "<?= $title ?> #" . <?= $actionParams ?>,
                'content' => $this->renderPartial('view', [
                    'model' => $this->findModel(<?= $actionParams ?>),
                ]),
                'footer' => Html::a('Edit', ['update', '<?= substr($actionParams,1) ?>' => <?= $actionParams ?>], ['class' => 'btn btn-primary pull-left', 'role' => $this->view->params['displayType']]) .
                    Html::button('Close', ['class' => 'btn btn-default', 'data-dismiss' => "modal"])
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel(<?= $actionParams ?>),
            ]);
        }
    }

    /**
     * Creates a new <?= $modelClass ?> model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new <?= $modelClass ?>();

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Create new <?= $title ?>",
                    'content' => $this->renderPartial('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } elseif ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#' . $this->getPjaxContainerId(),
                    'title' => "Create new <?= $title ?>",
                    'content' => '<span class="text-success">Create <?= $modelClass ?> success</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Create new <?= $title ?>",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post())) {
<?php if((count($check_datetime)!=0)||count($check)!=0){ ?>
                $time_string = time();
<?php } ?>
<?php if(count($check)!=0): ?>
<?php foreach ($check as $field) :?>
                $<?= $field ?>_file = UploadedFile::getInstance($model, '<?= $field ?>_file');
                if ($<?= $field ?>_file) {
                    $model-><?= $field ?> = $time_string . rand(0, 1000) . '_<?= $lower_name ?>_<?= $field ?>.' . $<?= $field ?>_file->extension;
                }
<?php endforeach; ?>
<?php endif; ?>
<?php if((count($check_datetime)!=0)){ ?>
                $model->created_date = date('Y-m-d H:i:s', $time_string);
<?php } ?>
                if ($model->save()) {
<?php if(count($check)!=0): ?>
<?php foreach ($check as $field) :?>
                    if ($<?= $field ?>_file) {
                        $<?= $field ?>_path = $this->uploadFolder . '/<?= $folder_name ?>/' . $model-><?= $field ?>;
                        $<?= $field ?>_file->saveAs($<?= $field ?>_path);
                    }
<?php endforeach; ?>
<?php endif; ?>

                    return $this->redirect(['index']);
                } else {
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
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
        $request = Yii::$app->request;
        $model = $this->findModel(<?= $actionParams ?>);

<?php if(count($check)!=0): ?>
<?php foreach ($check as $field) :?>
        $<?= $field ?>_old = $model-><?= $field ?>;
<?php endforeach; ?>
<?php endif; ?>

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Update <?= $title ?> #" . <?= $actionParams ?>,
                    'content' => $this->renderPartial('update', [
                        'model' => $this->findModel(<?= $actionParams ?>),
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } elseif ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#' . $this->getPjaxContainerId(),
                    'title' => "<?= $title ?> #" . <?= $actionParams ?>,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Edit', ['update', '<?= substr($actionParams,1) ?>' => <?= $actionParams ?>], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update <?= $title ?> #" . <?= $actionParams ?>,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post())) {

<?php if((count($check_datetime)!=0)||count($check)!=0){ ?>
                $time_string = time();
<?php } ?>
<?php if(count($check)!=0): ?>
<?php foreach ($check as $field) :?>
                $<?= $field ?>_file = UploadedFile::getInstance($model, '<?= $field ?>_file');
                if ($<?= $field ?>_file) {
                    if (isset($<?= $field ?>_old)) {
                        if (is_file($this->uploadFolder . '/<?= $folder_name ?>/' . $<?= $field ?>_old)) {
                            unlink($this->uploadFolder . '/<?= $folder_name ?>/' . $<?= $field ?>_old);
                        }
                    }
                    $model-><?= $field ?> = $time_string . rand(0, 1000) . '_<?= $lower_name ?>_<?= $field ?>.' . $<?= $field ?>_file->extension;
                }
<?php endforeach; ?>
<?php endif; ?>
<?php if((count($check_datetime)!=0)){ ?>
                $model->modified_date = date('Y-m-d H:i:s', $time_string);
<?php } ?>

                if ($model->save()) {

<?php if(count($check)!=0): ?>
<?php foreach ($check as $field) :?>
                    if ($<?= $field ?>_file) {
                        $<?= $field ?>_path = $this->uploadFolder . '/<?= $folder_name ?>/' . $model-><?= $field ?>;
                        $<?= $field ?>_file->saveAs($<?= $field ?>_path);
                    }
<?php endforeach; ?>
<?php endif; ?>

                    return $this->redirect(['index']);
                } else {
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
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
        $request = Yii::$app->request;
        $model = $this->findModel(<?= $actionParams ?>);
        $model->delete();

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#' . $this->getPjaxContainerId()];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }


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
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#' . $this->getPjaxContainerId()];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }

    public function actionBulkAction($action = '', $field = '', $value = '')
    {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            if (isset($model)) {
                if ($action == FHtml::CHANGE_TYPE) {
                    $model[$field] = $value;
                    $model->save();
                }
                if ($action == FHtml::CLEAR_TYPE) {
                    $model[$field] = 0;
                    $model->save();
                }
                if ($action == FHtml::FILL_TYPE) {
                    $model[$field] = rand(0, 99999);
                    $model->save();
                }
                //do something with other actions
            }
        }

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#' . $this->getPjaxContainerId()];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }


    /**
     * Finds the <?= $modelClass ?> model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return <?=                   $modelClass ?> the loaded model
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
        if (($model = <?= $modelClass ?>::findOne(<?= $condition ?>)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}