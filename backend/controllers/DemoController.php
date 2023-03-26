<?php
/**
 * Created by PhpStorm.
 * User: LongPC
 * Date: 08/30/2018
 * Time: 16:19
 */

namespace backend\controllers;

/*PHP WORD*/

use common\components\FHtml;
use PHPExcel_IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

define('CLI', (PHP_SAPI == 'cli') ? true : false);
define('EOL', CLI ? PHP_EOL : '<br />');
define('SCRIPT_FILENAME', basename($_SERVER['SCRIPT_FILENAME'], '.php'));
define('IS_INDEX', SCRIPT_FILENAME == 'index');

/*PHP WORD*/


class DemoController extends AdminController
{
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
							'calendar',
							'html-to-word',
							'html-to-word1',
                            'data',
                            'vue'
						],
						'allow'   => true,
					],
					[
						'actions' => ['index'],
						'allow'   => true,
						'roles'   => ['@'],
					],
                    [
                        'actions' => ['wordpress'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ]
				],
			],
			'verbs'  => [
				'class' => VerbFilter::className(),
			],
		];
	}

	public function actionCalendar() {
		return $this->render('calendar');
	}

    public function actionWordpress() {
        return $this->render('wordpress');
    }

    public function actionVue() {
        return $this->render('vue');
    }

	/**
	 * @return string
	 */
	public function actionIndex() {
		return $this->render('demo');
	}

    public function actionData() {
        return $this->render('data');
    }

	public function actionHtmlToWord1() {
		$request = Yii::$app->request;
		$content = $request->post('txtContent');
		$name    = $request->post('txtName');
		$format  = $request->post('txtFormat');

		$format = str_replace('${name}', $name, $format);
		$format = str_replace('${content}', $content, $format);

		$phpWord = new \PhpOffice\PhpWord\PhpWord;
		$phpWord->addParagraphStyle('Heading2', array('alignment' => 'center'));

		$format  = str_replace('<!DOCTYPE html>', '', $format);
		$format  = str_replace('</html>', '', $format);
		$format  = str_replace('</body>', '', $format);
		$format  = str_replace('<body>', '', $format);
		$format  = str_replace('<html>', '', $format);
		$format  = str_replace('</head>', '', $format);
		$format  = str_replace('<head>', '', $format);
		$section = $phpWord->addSection();
		$html    = $format;

		\PhpOffice\PhpWord\Shared\Html::addHtml($section, $html, false, false);
		$writers = array('Word2007' => 'docx');
		echo $this->write($phpWord, "ahih.docx", $writers);
	}


	public $enableCsrfValidation = false;

	/*PHP WORD*/
	public function actionHtmlToWord() {
		$request = Yii::$app->request;
		if ($request->post('btnSubmit') == 'word') {
			$content = $request->post('txtContent');
			$name    = $request->post('txtName');

			$file = UploadedFile::getInstanceByName('txtFile');
			if (!isset($file)) {
				$template = FHtml::getRootFolder() . '/backend/views/demo/template.docx';
			}
			else {
				$template = $file->tempName;
			}

			$templateProcessor = new TemplateProcessor($template);

			// Swap out our variables for the HTML content
			$templateProcessor->setValue('author', "Moza Solution");
			$templateProcessor->setValue('title', $name);
			$templateProcessor->setValue('content', $content);

			$filename = 'generated_' . date('YmdHis') . '.docx';

			header("Content-Disposition: attachment; filename='$filename'");
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
			if (!isset($file)) {
				$template = FHtml::getRootFolder() . '/backend/views/demo/template.xlsx';
			}
			else {
				$template = $file->tempName;
			}

			$content         = $request->post('txtContent');
			$name            = $request->post('txtName');
			$objPHPExcel     = PHPExcel_IOFactory::load($template);
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
			$filename = 'generated_' . date('YmdHis') . '.xlsx';

			header('Content-type: application/vnd.ms-excel');
			header("Content-Disposition: attachment; filename='$filename'");
			header('Content-Transfer-Encoding: binary');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Expires: 0');
			PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007')->save('php://output');
			exit();
		}
	}

	/**
	 * @param $writers
	 * @param $filename
	 * @return string
	 */
	private function getEndingNotes($writers, $filename) {
		$result = '';

		// Do not show execution time for index
		if (!IS_INDEX) {
			$result .= date('H:i:s') . ' Done writing file(s)' . EOL;
			$result .= date('H:i:s') . ' Peak memory usage: ' . (memory_get_peak_usage(true) / 1024 / 1024) . ' MB' . EOL;
		}

		// Return
		if (CLI) {
			$result .= 'The results are stored in the "results" subdirectory.' . EOL;
		}
		else {
			if (!IS_INDEX) {
				$types  = array_values($writers);
				$result .= '<p>&nbsp;</p>';
				$result .= '<p>Results: ';
				foreach ($types as $type) {
					if (!is_null($type)) {
						$resultFile = 'results/' . SCRIPT_FILENAME . '.' . $type;
						if (file_exists($resultFile)) {
							$result .= "<a href='{$resultFile}' class='btn btn-primary'>{$type}</a> ";
						}
					}
				}
				$result .= '</p>';

				$result .= '<pre>';
				if (file_exists($filename . '.php')) {
					$result .= highlight_file($filename . '.php', true);
				}
				$result .= '</pre>';
			}
		}

		return $result;
	}


	/**
	 * Write documents
	 * @param \PhpOffice\PhpWord\PhpWord $phpWord
	 * @param string                     $filename
	 * @param array                      $writers
	 * @return string
	 */
	function write($phpWord, $filename, $writers) {
		$result = '';

		// Write documents
		foreach ($writers as $format => $extension) {
			$result .= date('H:i:s') . " Write to {$format} format";
			if (null !== $extension) {
				$targetFile = __DIR__ . "/results/{$filename}.{$extension}";
				$targetFile = $filename;
				$filename   = 'generated_' . date('YmdHis') . '.docx';
				header("Content-Disposition: attachment; filename='$filename'");
				header('Content-Type: application/octet-stream');
				header('Content-Description: File Transfer');
				header('Expires: 0');
				//$phpWord->save($targetFile, $format);
				$phpWord->save('php://output', $format);
			}
			else {
				$result .= ' ... NOT DONE!';
			}
			$result .= EOL;
		}

		$result .= $this->getEndingNotes($writers, $filename);

		return $result;
	}
}