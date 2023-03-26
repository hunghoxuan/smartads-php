<?php

namespace backend\controllers;

use backend\components\AuthHandler;
use backend\models\ResetPassForm;
use backend\modules\cms\models\CmsFaqElastic;
use common\components\FHtml;
use common\components\FSecurity;
use DOMDocument;
use Faker\Factory;
use kcfinder\file;
use mikemadisonweb\elasticsearch\components\Finder;
use PHPExcel;
use PHPExcel_IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use Yii;
use yii\base\InvalidParamException;
use yii\elasticsearch\Command;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\UploadedFile;

///*PHP WORD*/
//define('CLI', (PHP_SAPI == 'cli') ? true : false);
//define('EOL', CLI ? PHP_EOL : '<br />');
//define('SCRIPT_FILENAME', basename($_SERVER['SCRIPT_FILENAME'], '.php'));
//define('IS_INDEX', SCRIPT_FILENAME == 'index');
///*PHP WORD*/

/**
 * Site controller
 */
class SiteController extends AdminController
{
	public $enableCsrfValidation = false;

	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => [
							'login',
							'error',
							'request-password-reset',
							'reset-password',
							'reset-pass',
							'setup',
							'about',
							'confirm-registration',
							'auth',
							'html-to-word',
							'swagger',
							'index1',
							'insert'
						],
						'allow'   => true,
					],
					[
						'actions' => ['logout', 'index', 'log', 'refresh', 'apps', 'file', 'editor', 'preview', 'change-password'],
						'allow'   => true,
						'roles'   => ['@'],
					],
				],
			],
			'verbs'  => [
				'class'   => VerbFilter::className(),
				'actions' => [
					'logout' => ['get', 'post'],
				],
			],
		];
	}


	/**
	 * @inheritdoc
	 */
	public function actions() {
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
			'auth'  => [
				'class'           => 'yii\authclient\AuthAction',
				'successCallback' => [$this, 'onAuthSuccess'],
			],
		];
	}

	public function createAction($id) {
		$user = FHtml::currentUserIdentity();
		if (empty($id)) {
			$id = !isset($user) ? 'login' : 'index';
		}

		$result = parent::createAction($id);

		return $result;
	}

	/**
	 * @param $client
	 */
	public function onAuthSuccess($client) {
		(new AuthHandler($client))->handle();
	}


	/**
	 * @return string
	 */
	public function actionIndex() {
		//$this->redirect('site/about');
		return $this->render('index', ['name' => Yii::$app->name]);
	}

	public function actionIndex1() {
		set_time_limit(0);

		$elastic                 = new CmsFaqElastic;
		$elastic->id             = 1;
		$elastic->name           = 'New in Elasticsearch 6.0';
		$elastic->content        = 'Lots of stuff...';
		$elastic->type           = 'Elasticsearch';
		$elastic->sort_order     = 3;
		$elastic->lang           = 'vi';
		$elastic->is_active      = 1;
		$elastic->is_top         = 1;
		$elastic->created_date   = FHtml::Now();
		$elastic->created_user   = 1;
		$elastic->modified_date  = 0;
		$elastic->modified_user  = 0;
		$elastic->application_id = DEFAULT_APPLICATION_ID;
		$elastic->save();

		$time_start = microtime(true);


		$time_end = microtime(true);
		echo $time_end - $time_start;
	}

	public function actionInsert() {
		/** @var Command $command */
		$limit   = 1000 * 1000;
		set_time_limit(0);
		$time_start = microtime(true);
		$faker = Factory::create();
		$command = Yii::$app->get('elasticsearch')->createCommand();
		for ($i = 0; $i < $limit; $i++) {
			$data = [
				'id'             => $i,
				'name'           => $faker->name,
				'content'        => $faker->text(),
				'type'           => $faker->creditCardType,
				'sort_order'     => $i,
				'lang'           => $faker->languageCode,
				'is_active'      => 1,
				'is_top'         => 1,
				'created_date'   => FHtml::Now(),
				'created_user'   => 1,
				'modified_date'  => FHtml::Now(),
				'modified_user'  => 1,
				'application_id' => DEFAULT_APPLICATION_ID,
			];
			$command->insert(CmsFaqElastic::index(), CmsFaqElastic::type(), $data, $i);
		}


		$time_end = microtime(true);
		echo $time_end - $time_start;
	}

	/**
	 * @return string
	 */
	public function actionSwagger() {
		return $this->render('swagger');
	}


	public function actionApps() {
		return $this->render('apps');
	}

	/**
	 * @throws \PhpOffice\PhpWord\Exception\CopyFileException
	 * @throws \PhpOffice\PhpWord\Exception\CreateTemporaryFileException
	 */
	public function actionHtmlToWord1() {

		$content = "";

		$data = $content;
		$dom  = new DOMDocument;
		$dom->loadHTML($data);

		// Now, extract the content we want to insert into our docx template

		// 1 - The page title
		$documentTitle = $dom->getElementById('title')->nodeValue;

		// 2 - The article body content
		$documentContent = $dom->getElementById('content')->nodeValue;

		// Load the template processor
		$templateProcessor = new TemplateProcessor(FHtml::getRootFolder() . '\backend\views\site\template.docx');
		// change folder template

		// Swap out our variables for the HTML content
		$templateProcessor->setValue('author', "Robin Metcalfe");
		$templateProcessor->setValue('title', $documentTitle);
		$templateProcessor->setValue('content', $documentContent);

		header("Content - Description: File Transfer");
		header('Content-Disposition: attachment; filename="generated . docx"');
		header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Expires: 0');
		$templateProcessor->saveAs("php://output");
		exit;
	}

	/*PHP WORD*/
	public function actionHtmlToWord() {
		$request = Yii::$app->request;
		if ($request->post('btnSubmit') == 'word') {
			$content           = $request->post('txtContent');
			$name              = $request->post('txtName');
			$file              = UploadedFile::getInstanceByName('txtFile');
			$templateProcessor = new TemplateProcessor($file->tempName);

			// Swap out our variables for the HTML content
			$templateProcessor->setValue('author', "Robin Metcalfe");
			$templateProcessor->setValue('title', $name);
			$templateProcessor->setValue('content', $content);

			header('Content-Disposition: attachment; filename="generated.docx"');
			header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
			header('Content-Transfer-Encoding: binary');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Expires: 0');

			$templateProcessor->saveAs("php://output");
			exit();
		}
		$this->actionHtmlToEXCEL();
	}

	/*PHP EXCEL*/
	public function actionHtmlToEXCEL() {
		$request = Yii::$app->request;
		if ($request->post('btnSubmit') == 'excel') {
			$file = UploadedFile::getInstanceByName('txtFile');

			$content         = $request->post('txtContent');
			$name            = $request->post('txtName');
			$objPHPExcel     = PHPExcel_IOFactory::load($file->tempName);
			$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
			foreach ($cell_collection as $cell) {
				$data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();

				if ($data_value == '${name}') {
					$objPHPExcel->getActiveSheet()->setCellValue($cell, $name);
				}
				if ($data_value == '${content}') {
					$objPHPExcel->getActiveSheet()->setCellValue($cell, $content);
				}
			}

			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="data.xlsx"');
			header('Content-Transfer-Encoding: binary');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Expires: 0');
			PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007')->save('php://output');
			exit();
		}
	}

	/**
	 * Resets password for app user.
	 * @param string $token
	 * @return mixed
	 * @throws BadRequestHttpException
	 */
	public function actionResetPass($token) {
		$this->layout = 'login';

		try {
			$model = new ResetPassForm($token);
		} catch (InvalidParamException $e) {
			throw new BadRequestHttpException($e->getMessage());
		}

		if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
			Yii::$app->session->setFlash('success', 'New password was saved.');
		}

		return $this->render('resetPassword', [
			'model' => $model,
		]);
	}
}
