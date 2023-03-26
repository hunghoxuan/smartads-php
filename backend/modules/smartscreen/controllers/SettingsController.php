<?php

namespace backend\modules\smartscreen\controllers;

use backend\controllers\AdminController;
use yii\filters\VerbFilter;
use common\components\AccessRule;
use common\models\User;
use yii\filters\AccessControl;

/**
 * SettingsController implements the CRUD actions for Settings model.
 */
class SettingsController extends AdminController
{
	protected $moduleName  = 'Settings';
	protected $moduleTitle = 'Settings';
	protected $moduleKey   = 'settings';

	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
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
						'actions' => ['view', 'index'],
						'allow'   => true,
						'roles'   => [
							User::ROLE_MODERATOR,
							User::ROLE_ADMIN
						],
					]
				],
			],
		];
	}

	/**
	 * Lists all Settings models.
	 * @return mixed
	 */
	public function actionIndex() {
		$model = new \backend\modules\smartscreen\models\Settings;
		$model->load();
		if (!empty($_POST)) {
			$model->load($_POST['Settings']);
			$model->save();
		}

		return $this->render('index', [
			'searchModel'  => null,
			'dataProvider' => null,
			'model'        => $model
		]);
	}
}
