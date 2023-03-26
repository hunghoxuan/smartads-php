<?php
/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "SmartscreenSchedules".
 */

namespace backend\modules\smartscreen\controllers;

use backend\modules\smartscreen\models\SmartscreenCampaigns;
use backend\modules\smartscreen\models\SmartscreenContent;
use backend\modules\smartscreen\models\SmartscreenFrame;
use backend\modules\smartscreen\models\SmartscreenLayouts;
use backend\modules\smartscreen\models\SmartscreenStationAPI;
use backend\modules\smartscreen\Smartscreen;
use Yii;
use backend\modules\smartscreen\models\SmartscreenSchedules;
use backend\modules\smartscreen\models\SmartscreenSchedulesSearch;
use backend\controllers\AdminController;
use yii\console\Exception;
use yii\helpers\Url;
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
 * SmartscreenSchedulesController implements the CRUD actions for SmartscreenSchedules model.
 */
class SmartscreenCampaignsController extends SmartscreenController
{
	protected $moduleName  = 'SmartscreenCampaigns';
	protected $moduleTitle = 'Smartscreen Campaigns';
	protected $moduleKey   = 'smartscreen_campaigns';
	protected $object_type = 'smartscreen_campaigns';

	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return FHtml::getControllerBehaviours([
			'verbs'  => [
				'class'   => VerbFilter::className(),
				'actions' => [
					'delete'      => ['post'],
					'bulk-delete' => ['post'],
				],
			],
			'access' => [
				'class'      => AccessControl::className(),
				'ruleConfig' => [
					'class' => AccessRule::className(),
				],
				'only'       => ['create', 'update', 'delete', 'view', 'index'],
				'rules'      => [
					[
						'actions' => ['view', 'index', 'create'],
						'allow'   => true,
						'roles'   => [
							User::ROLE_USER,
							User::ROLE_MODERATOR,
							User::ROLE_ADMIN
						],
					],
					[
						'actions' => ['update', 'delete'],
						'allow'   => true,
						'roles'   => [
							User::ROLE_MODERATOR,
							User::ROLE_ADMIN,
                            User::ROLE_USER,
                        ],
					],
				],
			],
		]);
	}

	/**
	 * Lists all SmartscreenSchedules models.
	 * @return mixed
	 */
	public function actionIndex() {

		$searchModel = SmartscreenCampaigns::createNew();
		$searchModel->load(Yii::$app->request->post());

		if (empty($searchModel->device_id)) {
			$searchModel->device_id = FHtml::NULL_VALUE;
		}

		if (!empty($_POST)) {

            $params = Smartscreen::getCurrentParams(['index'], 'SmartscreenCampaigns', null, ['date', 'date_end']);
            $url = Url::to($params);
            $url = FHtml::createFormalizedBackendLink($url);

            $response = Yii::$app->getResponse();
            return $response->redirect(Url::to($url), 302);
        }

		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);


		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}


	/**
	 * Displays a single SmartscreenSchedules model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id) {
		$request = Yii::$app->request;

		$model = $this->findModel($id);
		$type  = FHtml::getFieldValue($model, 'type');

		if ($request->isAjax) {
			Yii::$app->response->format = Response::FORMAT_JSON;

			return [
				'title'   => FHtml::t($this->moduleName) . " #" . $id,
				'content' => $this->renderPartial('view', [
					'model' => $model
				]),
				'footer'  => Html::a(FHtml::t('Update'), ['update', 'id' => $id], [
						'class' => 'btn btn-primary pull-left',
						'role'  => $this->view->params['displayType']
					]) . Html::button(FHtml::t('Close'), ['class' => 'btn btn-default', 'data-dismiss' => "modal"])
			];
		}
		else {
			return $this->render('view', ['model' => $model]);
		}
	}

	/**
	 * Creates a new SmartscreenSchedules model.
	 * For ajax request will return json object
	 * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate($type = false) {
		$request = Yii::$app->request;

		$model = $this->createModel($this->object_type);

		if ($request->isAjax) {
			return FHtml::saveModelAjax($this, $model, null);
		}
		else {
			if ($model->load($request->post())) {
                $model->id = null;

                $model->save();

				if ($this->saveType() == 'save' && !empty($model_id)) {
					return $this->redirect(['update', 'id' => $model_id]);
				}

				return $this->redirect(['index']);


			}
			else {
				return $this->render('create', ['model' => $model]);
			}
		}
	}


	public function actionUpdate($id = '') {
		$request = Yii::$app->request;
		$model   = SmartscreenCampaigns::findOne($id);

		if ($request->isAjax) {
			return FHtml::saveModelAjax($this, $model, null);
		}
		else {
			if ($model->load($request->post())) {
			    $model->save();

				if ($this->saveType() == 'save') {
                    return $this->render('update', ['model' => $model]);
				}

				return $this->redirect(Smartscreen::getCurrentParams(['index']));
			}
			else {
				return $this->render('update', ['model' => $model]);
			}
		}
	}

	public function actionDelete($id) {
		$request = Yii::$app->request;

		$this->findModel($id)->delete();

		if ($request->isAjax) {
			Yii::$app->response->format = Response::FORMAT_JSON;

			return ['forceClose' => true, 'forceReload' => '#' . $this->getPjaxContainerId()];
		}
		else {
			return $this->redirect(['index']);
		}
	}


	public function actionBulkDelete() {
		$request = Yii::$app->request;

		$pks = explode(',', $request->post('pks')); // Array or selected records primary keys
		foreach ($pks as $pk) {
			$model = SmartscreenCampaigns::findOne($pk); // FHtml::findOne($this->object_type, $pk);
			if (isset($model)) {
				$model->delete();
			}
		}

		if ($request->isAjax) {
			Yii::$app->response->format = Response::FORMAT_JSON;

			return ['forceClose' => true, 'forceReload' => '#' . $this->getPjaxContainerId()];
		}
		else {
			return $this->redirect(['index']);
		}
	}

	public function actionBulkAction($action = '', $field = '', $value = '') {
		$request = Yii::$app->request;

		$pks = explode(',', $request->post('pks')); // Array or selected records primary keys
		foreach ($pks as $pk) {
			$model = FHtml::findOne($this->object_type, $pk);
			if (isset($model)) {
				if ($action == 'change') {
					FHtml::setFieldValue($model, $field, $value);
					$model->save();
				}
			}
		}

		if ($request->isAjax) {
			Yii::$app->response->format = Response::FORMAT_JSON;

			return ['forceClose' => true, 'forceReload' => '#' . $this->getPjaxContainerId()];
		}
		else {
			return $this->redirect(['index']);
		}
	}


	/**
	 * Finds the SmartscreenSchedules model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return SmartscreenSchedules the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		$model = parent::findModel($id);
		return $model;
	}

	protected function createModel($className = '', $id = '', $params = null) {
		$model = parent::createModel($className, $id, $params);

		//$model->start_time = null;
		return $model;
	}


	public function actionLayoutContent() {
		$action = $_REQUEST['action'];
	}


}
