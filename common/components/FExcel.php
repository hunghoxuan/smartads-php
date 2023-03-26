<?php

namespace common\components;

use common\base\BaseDataObject;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Worksheet_MemoryDrawing;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\i18n\Formatter;


/**
 * Excel Widget for generate Excel File or for load Excel File.
 * Usage
 * -----
 * Exporting data into an excel file.
 * ~~~
 * // export data only one worksheet.
 * \moonland\phpexcel\Excel::widget([
 *        'models' => $allModels,
 *        'mode' => 'export', //default value as 'export'
 *        'columns' => ['column1','column2','column3'],
 *        //without header working, because the header will be get label from attribute label.
 *        'header' => ['column1' => 'Header Column 1','column2' => 'Header Column 2', 'column3' => 'Header Column 3'],
 * ]);
 * \moonland\phpexcel\Excel::export([
 *        'models' => $allModels,
 *        'columns' => ['column1','column2','column3'],
 *        //without header working, because the header will be get label from attribute label.
 *        'header' => ['column1' => 'Header Column 1','column2' => 'Header Column 2', 'column3' => 'Header Column 3'],
 * ]);
 * // export data with multiple worksheet.
 * \moonland\phpexcel\Excel::widget([
 *        'isMultipleSheet' => true,
 *        'models' => [
 *            'sheet1' => $allModels1,
 *            'sheet2' => $allModels2,
 *            'sheet3' => $allModels3
 *        ],
 *        'mode' => 'export', //default value as 'export'
 *        'columns' => [
 *            'sheet1' => ['column1','column2','column3'],
 *            'sheet2' => ['column1','column2','column3'],
 *            'sheet3' => ['column1','column2','column3']
 *        ],
 *        //without header working, because the header will be get label from attribute label.
 *        'header' => [
 *            'sheet1' => ['column1' => 'Header Column 1','column2' => 'Header Column 2', 'column3' => 'Header Column 3'],
 *            'sheet2' => ['column1' => 'Header Column 1','column2' => 'Header Column 2', 'column3' => 'Header Column 3'],
 *            'sheet3' => ['column1' => 'Header Column 1','column2' => 'Header Column 2', 'column3' => 'Header Column 3']
 *        ],
 * ]);
 * \moonland\phpexcel\Excel::export([
 *        'isMultipleSheet' => true,
 *        'models' => [
 *            'sheet1' => $allModels1,
 *            'sheet2' => $allModels2,
 *            'sheet3' => $allModels3
 *        ],
 *        'columns' => [
 *            'sheet1' => ['column1','column2','column3'],
 *            'sheet2' => ['column1','column2','column3'],
 *            'sheet3' => ['column1','column2','column3']
 *        ],
 *        //without header working, because the header will be get label from attribute label.
 *        'header' => [
 *            'sheet1' => ['column1' => 'Header Column 1','column2' => 'Header Column 2', 'column3' => 'Header Column 3'],
 *            'sheet2' => ['column1' => 'Header Column 1','column2' => 'Header Column 2', 'column3' => 'Header Column 3'],
 *            'sheet3' => ['column1' => 'Header Column 1','column2' => 'Header Column 2', 'column3' => 'Header Column 3']
 *        ],
 * ]);
 * ~~~
 * New Feature for exporting data, you can use this if you familiar yii gridview.
 * That is same with gridview data column.
 * Columns in array mode valid params are 'attribute', 'header', 'format', 'value', and footer (TODO).
 * Columns in string mode valid layout are 'attribute:format:header:footer(TODO)'.
 * ~~~
 * \moonland\phpexcel\Excel::export([
 *    'models' => Post::find()->all(),
 *        'columns' => [
 *            'author.name:text:Author Name',
 *            [
 *                    'attribute' => 'content',
 *                    'header' => 'Content Post',
 *                    'format' => 'text',
 *                    'value' => function($model) {
 *                        return ExampleClass::removeText('example', $model->content);
 *                    },
 *            ],
 *            'like_it:text:Reader like this content',
 *            'created_at:datetime',
 *            [
 *                    'attribute' => 'updated_at',
 *                    'format' => 'date',
 *            ],
 *        ],
 *        'headers' => [
 *            'created_at' => 'Date Created Content',
 *        ],
 * ]);
 * ~~~
 * Import file excel and return into an array.
 * ~~~
 * $data = \moonland\phpexcel\Excel::import($fileName, $config); // $config is an optional
 * $data = \moonland\phpexcel\Excel::widget([
 *        'mode' => 'import',
 *        'fileName' => $fileName,
 *        'setFirstRecordAsKeys' => true, // if you want to set the keys of record column with first record, if it not set, the header with use the alphabet column on excel.
 *        'setIndexSheetByName' => true, // set this if your excel data with multiple worksheet, the index of array will be set with the sheet name. If this not set, the index will use numeric.
 *        'getOnlySheet' => 'sheet1', // you can set this property if you want to get the specified sheet from the excel data with multiple worksheet.
 * ]);
 * $data = \moonland\phpexcel\Excel::import($fileName, [
 *        'setFirstRecordAsKeys' => true, // if you want to set the keys of record column with first record, if it not set, the header with use the alphabet column on excel.
 *        'setIndexSheetByName' => true, // set this if your excel data with multiple worksheet, the index of array will be set with the sheet name. If this not set, the index will use numeric.
 *        'getOnlySheet' => 'sheet1', // you can set this property if you want to get the specified sheet from the excel data with multiple worksheet.
 *    ]);
 * // import data with multiple file.
 * $data = \moonland\phpexcel\Excel::widget([
 *        'mode' => 'import',
 *        'fileName' => [
 *            'file1' => $fileName1,
 *            'file2' => $fileName2,
 *            'file3' => $fileName3,
 *        ],
 *        'setFirstRecordAsKeys' => true, // if you want to set the keys of record column with first record, if it not set, the header with use the alphabet column on excel.
 *        'setIndexSheetByName' => true, // set this if your excel data with multiple worksheet, the index of array will be set with the sheet name. If this not set, the index will use numeric.
 *        'getOnlySheet' => 'sheet1', // you can set this property if you want to get the specified sheet from the excel data with multiple worksheet.
 * ]);
 * $data = \moonland\phpexcel\Excel::import([
 *            'file1' => $fileName1,
 *            'file2' => $fileName2,
 *            'file3' => $fileName3,
 *        ], [
 *        'setFirstRecordAsKeys' => true, // if you want to set the keys of record column with first record, if it not set, the header with use the alphabet column on excel.
 *        'setIndexSheetByName' => true, // set this if your excel data with multiple worksheet, the index of array will be set with the sheet name. If this not set, the index will use numeric.
 *        'getOnlySheet' => 'sheet1', // you can set this property if you want to get the specified sheet from the excel data with multiple worksheet.
 *    ]);
 * ~~~
 * Result example from the code on the top :
 * ~~~
 * // only one sheet or specified sheet.
 * Array([0] => Array([name] => Anam, [email] => moh.khoirul.anaam@gmail.com, [framework interest] => Yii2),
 * [1] => Array([name] => Example, [email] => example@moonlandsoft.com, [framework interest] => Yii2));
 * // data with multiple worksheet
 * Array([Sheet1] => Array([0] => Array([name] => Anam, [email] => moh.khoirul.anaam@gmail.com, [framework interest] => Yii2),
 * [1] => Array([name] => Example, [email] => example@moonlandsoft.com, [framework interest] => Yii2)),
 * [Sheet2] => Array([0] => Array([name] => Anam, [email] => moh.khoirul.anaam@gmail.com, [framework interest] => Yii2),
 * [1] => Array([name] => Example, [email] => example@moonlandsoft.com, [framework interest] => Yii2)));
 * // data with multiple file and specified sheet or only one worksheet
 * Array([file1] => Array([0] => Array([name] => Anam, [email] => moh.khoirul.anaam@gmail.com, [framework interest] => Yii2),
 * [1] => Array([name] => Example, [email] => example@moonlandsoft.com, [framework interest] => Yii2)),
 * [file2] => Array([0] => Array([name] => Anam, [email] => moh.khoirul.anaam@gmail.com, [framework interest] => Yii2),
 * [1] => Array([name] => Example, [email] => example@moonlandsoft.com, [framework interest] => Yii2)));
 * // data with multiple file and multiple worksheet
 * Array([file1] => Array([Sheet1] => Array([0] => Array([name] => Anam, [email] => moh.khoirul.anaam@gmail.com, [framework interest] => Yii2),
 * [1] => Array([name] => Example, [email] => example@moonlandsoft.com, [framework interest] => Yii2)),
 * [Sheet2] => Array([0] => Array([name] => Anam, [email] => moh.khoirul.anaam@gmail.com, [framework interest] => Yii2),
 * [1] => Array([name] => Example, [email] => example@moonlandsoft.com, [framework interest] => Yii2))),
 * [file2] => Array([Sheet1] => Array([0] => Array([name] => Anam, [email] => moh.khoirul.anaam@gmail.com, [framework interest] => Yii2),
 * [1] => Array([name] => Example, [email] => example@moonlandsoft.com, [framework interest] => Yii2)),
 * [Sheet2] => Array([0] => Array([name] => Anam, [email] => moh.khoirul.anaam@gmail.com, [framework interest] => Yii2),
 * [1] => Array([name] => Example, [email] => example@moonlandsoft.com, [framework interest] => Yii2))));
 * ~~~
 * @property string          $mode                 is an export mode or import mode. valid value are 'export' and 'import'
 * @property boolean         $isMultipleSheet      for set the export excel with multiple sheet.
 * @property array           $properties           for set property on the excel object.
 * @property array           $models               Model object or DataProvider object with much data.
 * @property array           $columns              to get the attributes from the model, this valid value only the exist attribute on the model.
 * If this is not set, then all attribute of the model will be set as columns.
 * @property array           $headers              to set the header column on first line. Set this if want to custom header.
 * If not set, the header will get attributes label of model attributes.
 * @property string|array    $fileName             is a name for file name to export or import. Multiple file name only use for import mode, not work if you use the export mode.
 * @property string          $savePath             is a directory to save the file or you can blank this to set the file as attachment.
 * @property string          $format               for excel to export. Valid value are 'Excel5','Excel2007','Excel2003XML','00Calc','Gnumeric'.
 * @property boolean         $setFirstTitle        to set the title column on the first line. The columns will have a header on the first line.
 * @property boolean         $asAttachment         to set the file excel to download mode.
 * @property boolean         $setFirstRecordAsKeys to set the first record on excel file to a keys of array per line.
 * If you want to set the keys of record column with first record, if it not set, the header with use the alphabet column on excel.
 * @property boolean         $setIndexSheetByName  to set the sheet index by sheet name or array result if the sheet not only one
 * @property string          $getOnlySheet         is a sheet name to getting the data. This is only get the sheet with same name.
 * @property mixed           activeSheet
 * @property array|Formatter $formatter            the formatter used to format model attribute values into displayable texts.
 *            This can be either an instance of [[Formatter]] or an configuration array for creating the [[Formatter]]
 *            instance. If this property is not set, the "formatter" application component will be used.
 * @author    Moh Khoirul Anam <moh.khoirul.anaam@gmail.com>
 * @copyright 2014
 * @since     1
 */
class FExcel extends \yii\base\Widget
{
	/**
	 * @var string mode is an export mode or import mode. valid value are 'export' and 'import'.
	 */
	public $mode = 'export';
	/**
	 * @var boolean for set the export excel with multiple sheet.
	 */
	public $isMultipleSheet = false;
	/**
	 * @var array properties for set property on the excel object.
	 */
	public $properties;
	/**
	 * @var Model object or DataProvider object with much data.
	 */
	public $models;
	/**
	 * @var array columns to get the attributes from the model, this valid value only the exist attribute on the model.
	 * If this is not set, then all attribute of the model will be set as columns.
	 */
	public $columns = [];
	/**
	 * @var array header to set the header column on first line. Set this if want to custom header.
	 * If not set, the header will get attributes label of model attributes.
	 */
	public $headers = [];
	/**
	 * @var string|array name for file name to export or save.
	 */
	public $fileName = '';

	public $fileType = 'excel';

	public $fieldSeperator = ',';

	/**
	 * @var string save path is a directory to save the file or you can blank this to set the file as attachment.
	 */
	public $savePath;
	/**
	 * @var string format for excel to export. Valid value are 'Excel5','Excel2007','Excel2003XML','00Calc','Gnumeric'.
	 */
	public $format = 'Excel2007';
	/**
	 * @var boolean to set the title column on the first line.
	 */
	public $setFirstTitle = true;
	/**
	 * @var boolean to set the file excel to download mode.
	 */
	public $asAttachment = true;
	/**
	 * @var boolean to set the first record on excel file to a keys of array per line.
	 * If you want to set the keys of record column with first record, if it not set, the header with use the alphabet column on excel.
	 */
	public $setFirstRecordAsKeys = false;
	/**
	 * @var boolean to set the sheet index by sheet name or array result if the sheet not only one.
	 */
	public $setIndexSheetByName = true;
	/**
	 * @var string sheetname to getting. This is only get the sheet with same name.
	 */
	public $getOnlySheet;
	/**
	 * @var boolean to set the import data will return as array.
	 */
	public $asArray;
	/**
	 * @var array to unread record by index number.
	 */
	public $leaveRecordByIndex = [];
	/**
	 * @var array to read record by index, other will leave.
	 */
	public $getOnlyRecordByIndex = [];
	/**
	 * @var array|Formatter the formatter used to format model attribute values into displayable texts.
	 * This can be either an instance of [[Formatter]] or an configuration array for creating the [[Formatter]]
	 * instance. If this property is not set, the "formatter" application component will be used.
	 */
	public $formatter;

	/**
	 * (non-PHPdoc)
	 * @see \yii\base\Object::init()
	 */
	public function init() {
		parent::init();
		if ($this->formatter == null) {
			$this->formatter = Yii::$app->getFormatter();
		}
		elseif (is_array($this->formatter)) {
			$this->formatter = Yii::createObject($this->formatter);
		}
		if (!$this->formatter instanceof Formatter) {
			throw new InvalidConfigException('The "formatter" property must be either a Format object or a configuration array.');
		}
	}

	/**
	 * Setting data from models
	 * @param null  $activeSheet
	 * @param       $models
	 * @param array $columns
	 * @param array $headers
	 */
	public function executeColumns(&$activeSheet = null, $models, $columns = [], $headers = []) {
		if ($activeSheet == null) {
			$activeSheet = $this->activeSheet;
		}
		$hasHeader = false;
		$row       = 1;
		$char      = 26;
		foreach ($models as $model) {
			/** @var BaseDataObject $model */
			if (empty($columns)) {
				$columns = $model->attributes();
			}
			if ($this->setFirstTitle && !$hasHeader) {
				$isPlus  = false;
				$colplus = 0;
				$colnum  = 1;
				foreach ($columns as $key => $column) {
					$col = '';
					if ($colnum > $char) {
						$colplus += 1;
						$colnum  = 1;
						$isPlus  = true;
					}
					if ($isPlus) {
						$col .= chr(64 + $colplus);
					}
					$col    .= chr(64 + $colnum);
					$header = '';
					if (is_array($column)) {
						if (isset($column['header'])) {
							$header = $column['header'];
						}
						elseif (isset($column['attribute']) && isset($headers[$column['attribute']])) {
							$header = $headers[$column['attribute']];
						}
						elseif (isset($column['attribute'])) {
							$header = $model->getAttributeLabel($column['attribute']);
						}
					}
					else {
						$header = $model->getAttributeLabel($column);
					}
					$activeSheet->setCellValue($col . $row, $header);
					$colnum++;
				}
				$hasHeader = true;
				$row++;
			}
			$isPlus  = false;
			$colplus = 0;
			$colnum  = 1;
			foreach ($columns as $key => $column) {
				$col = '';
				if ($colnum > $char) {
					$colplus++;
					$colnum = 1;
					$isPlus = true;
				}
				if ($isPlus) {
					$col .= chr(64 + $colplus);
				}
				$col .= chr(64 + $colnum);
				if (is_array($column)) {
					$column_value = $this->executeGetColumnData($model, $column);
				}
				else {
					$column_value = $this->executeGetColumnData($model, ['attribute' => $column]);
				}
				$activeSheet->setCellValue($col . $row, $column_value);
				$colnum++;
			}
			$row++;
		}
	}

	/**
	 * Setting label or keys on every record if setFirstRecordAsKeys is true.
	 * @param array $sheetData
	 * @return array :multitype:array
	 */
	public function executeArrayLabel($sheetData) {
		$keys = ArrayHelper::remove($sheetData, '1');

		$new_data = [];

		foreach ($sheetData as $values) {
			$new_data[] = array_combine($keys, $values);
		}

		return $new_data;
	}

	/**
	 * Leave record with same index number.
	 * @param array $sheetData
	 * @param array $index
	 * @return array
	 */
	public function executeLeaveRecords($sheetData = [], $index = []) {
		foreach ($sheetData as $key => $data) {
			if (in_array($key, $index)) {
				unset($sheetData[$key]);
			}
		}

		return $sheetData;
	}

	/**
	 * Read record with same index number.
	 * @param array $sheetData
	 * @param array $index
	 * @return array
	 */
	public function executeGetOnlyRecords($sheetData = [], $index = []) {
		foreach ($sheetData as $key => $data) {
			if (!in_array($key, $index)) {
				unset($sheetData[$key]);
			}
		}

		return $sheetData;
	}

	/**
	 * Getting column value.
	 * @param Model $model
	 * @param array $params
	 * @return mixed|null|string <NULL, string, mixed>
	 */
	public function executeGetColumnData($model, $params = []) {
		$value = null;
		if (isset($params['value']) && $params['value'] !== null) {
			if (is_string($params['value'])) {
				$value = ArrayHelper::getValue($model, $params['value']);
			}
			else {
				$value = call_user_func($params['value'], $model, $this);
			}
		}
		elseif (isset($params['attribute']) && $params['attribute'] !== null) {
			$value = ArrayHelper::getValue($model, $params['attribute']);
		}

		if (isset($params['format']) && $params['format'] != null) {
			$value = $this->formatter()->format($value, $params['format']);
		}

		return $value;
	}

	/**
	 * Populating columns for checking the column is string or array. if is string this will be checking have a formatter or header.
	 * @param array $columns
	 * @return array :multitype:array
	 */
	public function populateColumns($columns = []) {
		$_columns = [];
		foreach ($columns as $key => $value) {
			if (is_string($value)) {
				$value_log      = explode(':', $value);
				$_columns[$key] = ['attribute' => $value_log[0]];

				if (isset($value_log[1]) && $value_log[1] !== null) {
					$_columns[$key]['format'] = $value_log[1];
				}

				if (isset($value_log[2]) && $value_log[2] !== null) {
					$_columns[$key]['header'] = $value_log[2];
				}
			}
			elseif (is_array($value)) {
				if (!isset($value['attribute']) && !isset($value['value'])) {
					throw new InvalidParamException('Attribute or Value must be defined.');
				}
				$_columns[$key] = $value;
			}
		}

		return $_columns;
	}

	/**
	 * Formatter for i18n.
	 * @return Formatter
	 */
	public function formatter() {
		if (!isset($this->formatter)) {
			$this->formatter = Yii::$app->getFormatter();
		}

		return $this->formatter;
	}

	/**
	 * Setting header to download generated file xls
	 */
	public function setHeaders() {
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $this->getFileName() . '"');
		header('Cache-Control: max-age=0');
	}

	/**
	 * Getting the file name of exporting xls file
	 * @return string
	 */
	public function getFileName() {
	    if (empty($this->fileName))
	        $this->fileName = FHtml::currentController() . '_' . date('Y_m_d_Hms') . "." . $this->getFileExtension();
		return $this->fileName;
	}

	public function getFileExtension() {
	    if ($this->fileType == 'excel')
	        return $this->format == 'Excel2007' ? 'xls' : 'xlsx';
	    else
	        return ".$this->fileType";
    }

    public function getFiletype() {
	    return $this->fileType;
    }

	/**
	 * Setting properties for excel file
	 * @param PHPExcel $objectExcel
	 * @param array    $properties
	 */
	public function properties(&$objectExcel, $properties = []) {
		foreach ($properties as $key => $value) {
			$keyname = "set" . ucfirst($key);
			$objectExcel->getProperties()->{$keyname}($value);
		}
	}

	/**
	 * saving the xls file to download or to path
	 * @param $sheet
	 * @throws \PHPExcel_Reader_Exception
	 * @throws \PHPExcel_Writer_Exception
	 */
	public function writeFile($sheet) {
		if (!isset($this->format)) {
			$this->format = 'Excel2007';
		}
		$objectwriter = PHPExcel_IOFactory::createWriter($sheet, $this->format);
		$path         = 'php://output';
		if (isset($this->savePath) && $this->savePath != null) {
			$path = $this->savePath . '/' . $this->getFileName();
		}
		$objectwriter->save($path);
		exit();
	}

	/**
	 * reading the xls file
	 * @param $fileName
	 * @return array|mixed
	 * @throws \PHPExcel_Exception
	 * @throws \PHPExcel_Reader_Exception
	 */
	public function readFile($fileName = '', $fileType = '', $field_seperator = '') {
	    if (empty($fileName))
	        $fileName = $this->getFileName();

	    if (empty($fileType))
	        $fileType = $this->getFiletype();

	    $sheetDatas = [];

	    if ($fileType == 'excel') {

            if (!isset($this->format)) {
                $this->format = PHPExcel_IOFactory::identify($fileName);
            }
            $objectreader = PHPExcel_IOFactory::createReader($this->format);
            $objectPhpExcel = $objectreader->load($fileName);

            $sheetCount = $objectPhpExcel->getSheetCount();

            $sheetDatas = [];

            if ($sheetCount > 1) {

                foreach ($objectPhpExcel->getSheetNames() as $sheetIndex => $sheetName) {
                    $objectPhpExcel->setActiveSheetIndexByName($sheetName);
                    $indexed = $this->setIndexSheetByName == true ? $sheetName : $sheetIndex;
                    $sheetDatas[$indexed] = $objectPhpExcel->getActiveSheet()->toArray(null, true, true, true);
                    if ($this->setFirstRecordAsKeys) {
                        $sheetDatas[$indexed] = $this->executeArrayLabel($sheetDatas[$indexed]);
                    }
                    if (!empty($this->getOnlyRecordByIndex) && isset($this->getOnlyRecordByIndex[$indexed]) && is_array($this->getOnlyRecordByIndex[$indexed])) {
                        $sheetDatas = $this->executeGetOnlyRecords($sheetDatas, $this->getOnlyRecordByIndex[$indexed]);
                    }
                    if (!empty($this->leaveRecordByIndex) && isset($this->leaveRecordByIndex[$indexed]) && is_array($this->leaveRecordByIndex[$indexed])) {
                        $sheetDatas[$indexed] = $this->executeLeaveRecords($sheetDatas[$indexed], $this->leaveRecordByIndex[$indexed]);
                    }
                }
                if (isset($this->getOnlySheet) && $this->getOnlySheet != null) {
                    if (is_string($this->getOnlySheet) && !is_numeric($this->getOnlySheet)) {
                        $indexed = $this->setIndexSheetByName == true ? $this->getOnlySheet : $objectPhpExcel->getIndex($objectPhpExcel->getSheetByName($this->getOnlySheet));
                    } elseif (is_numeric($this->getOnlySheet)) {
                        $indexed = $this->getOnlySheet;
                    } else {
                        $indexed = 0;
                    }

                    return $sheetDatas[$indexed];
                }
            } else {
                $sheetDatas = $objectPhpExcel->getActiveSheet()->toArray(null, true, true, true);


                if ($this->setFirstRecordAsKeys) {
                    $sheetDatas = $this->executeArrayLabel($sheetDatas);
                }

                if (!empty($this->getOnlyRecordByIndex)) {
                    $sheetDatas = $this->executeGetOnlyRecords($sheetDatas, $this->getOnlyRecordByIndex);
                }

                if (!empty($this->leaveRecordByIndex)) {
                    $sheetDatas = $this->executeLeaveRecords($sheetDatas, $this->leaveRecordByIndex);
                }
            }
        } else if ($fileType == 'csv') {
	        $content = FFile::readFile($fileName);
	        $arr = explode("\n", $content);
	        $columns = null;
	        $result = [];
            $rows = [];
	        if (!empty($arr)) {
	            foreach ($arr as $arr_item) {
	                if (empty($field_seperator) || strpos($arr_item, $field_seperator) === false) {
                        $field_seperator = strpos($arr_item, ',') !== false ? ',' : (strpos($arr_item, '|') !== false ? '|' : "\t");
                    }
	                $arr_item = explode($field_seperator, trim($arr_item));
	                if (!isset($columns)) {
                        $columns = $arr_item;
                        continue;
                    }
	                $arr_item1 = [];
	                foreach ($columns as $i => $item) {
	                    $arr_item1 = array_merge($arr_item1, [trim($item) => isset($arr_item[$i]) ? trim($arr_item[$i]) : null ]);
                    }
                    $rows[] = $arr_item;
                    $result[] = $arr_item1;
                }
            }
	        $sheetDatas['data'] = $result;
            $sheetDatas['rows'] = $rows;
            $sheetDatas['columns'] = $columns;
        }

		return $sheetDatas;
	}

	/**
	 * (non-PHPdoc)
	 * @see \yii\base\Widget::run()
	 */
	public function run() {
		if ($this->mode == 'export') {
			$sheet = new PHPExcel;

			if (!isset($this->models)) {
				throw new InvalidConfigException('Config models must be set');
			}

			if (isset($this->properties)) {
				$this->properties($sheet, $this->properties);
			}

			if ($this->isMultipleSheet) {
				$index     = 0;
				$worksheet = [];
				foreach ($this->models as $title => $models) {
					$sheet->createSheet($index);
					$sheet->getSheet($index)->setTitle($title);
					$worksheet[$index] = $sheet->getSheet($index);
					$columns           = isset($this->columns[$title]) ? $this->columns[$title] : [];
					$headers           = isset($this->headers[$title]) ? $this->headers[$title] : [];
					$this->executeColumns($worksheet[$index], $models, $this->populateColumns($columns), $headers);
					$index++;
				}
			}
			else {
				$worksheet = $sheet->getActiveSheet();
				$this->executeColumns($worksheet, $this->models, isset($this->columns) ? $this->populateColumns($this->columns) : [], isset($this->headers) ? $this->headers : []);
			}

			if ($this->asAttachment) {
				$this->setHeaders();
			}
			$this->writeFile($sheet);
		}
		elseif ($this->mode == 'import') {
		    $filenames = $this->getFileName();
			if (is_array($filenames)) {
				$datas = [];
				foreach ($filenames as $key => $filename) {
					$datas[$key] = $this->readFile($filename, $this->getFiletype(), $this->fieldSeperator);
				}

				return $datas;
			}
			else {
				return $this->readFile($this->getFileName(), $this->getFiletype(), $this->fieldSeperator);
			}
		}
	}

	/**
	 * Exporting data into an excel file.
	 * ~~~
	 * \moonland\phpexcel\Excel::export([
	 *        'models' => $allModels,
	 *        'columns' => ['column1','column2','column3'],
	 *        //without header working, because the header will be get label from attribute label.
	 *        'header' => ['column1' => 'Header Column 1','column2' => 'Header Column 2', 'column3' => 'Header Column 3'],
	 * ]);
	 * ~~~
	 * New Feature for exporting data, you can use this if you familiar yii gridview.
	 * That is same with gridview data column.
	 * Columns in array mode valid params are 'attribute', 'header', 'format', 'value', and footer (TODO).
	 * Columns in string mode valid layout are 'attribute:format:header:footer(TODO)'.
	 * ~~~
	 * \moonland\phpexcel\Excel::export([
	 *    'models' => Post::find()->all(),
	 *        'columns' => [
	 *            'author.name:text:Author Name',
	 *            [
	 *                    'attribute' => 'content',
	 *                    'header' => 'Content Post',
	 *                    'format' => 'text',
	 *                    'value' => function($model) {
	 *                        return ExampleClass::removeText('example', $model->content);
	 *                    },
	 *            ],
	 *            'like_it:text:Reader like this content',
	 *            'created_at:datetime',
	 *            [
	 *                    'attribute' => 'updated_at',
	 *                    'format' => 'date',
	 *            ],
	 *        ],
	 *        'headers' => [
	 *            'created_at' => 'Date Created Content',
	 *        ],
	 * ]);
	 * ~~~
	 * @param array $config
	 * @return string
	 * @throws InvalidConfigException
	 */
	public static function export($config = []) {
		$config = ArrayHelper::merge(['mode' => 'export'], $config);

		return self::widget($config);
	}

	/**
	 * Import file excel and return into an array.
	 * ~~~
	 * $data = \moonland\phpexcel\Excel::import($fileName, ['setFirstRecordAsKeys' => true]);
	 * ~~~
	 * @param string!array $fileName to load.
	 * @param array $config is a more configuration.
	 * @return string
	 * @throws InvalidConfigException
	 */
	public static function import($fileName, $config = [], $file_type = 'excel') {
		$config = ArrayHelper::merge(['mode' => 'import', 'fileName' => $fileName, 'asArray' => true, 'fileType' => $file_type], $config);
		return self::widget($config);
	}

	/**
	 * @param array $config
	 * @return string
	 * @throws InvalidConfigException
	 */
	public static function widget($config = []) {
		if ($config['mode'] == 'import' && !isset($config['asArray'])) {
			$config['asArray'] = true;
		}

		if (isset($config['asArray']) && $config['asArray'] == true) {
			$config['class'] = get_called_class();
			$widget          = Yii::createObject($config);

			return $widget->run();
		} else {
			return parent::widget($config);
		}
	}

	/**
	 * @param        $array
	 * @param bool   $download
	 * @param array  $columns
	 * @param string $file_name
	 * @param array  $included_keys
	 * @param string $content_type
	 * @param bool   $auto_translated
	 * @return string
	 */
	public static function exportToCSV($array, $download = true, $columns = [], $file_name = '', $included_keys = [], $content_type = 'text/csv', $auto_translated = true) {
		$csv     = array();
		$newline = false;
		$csv1    = array();

		foreach ($array as $key => $item) {
			if (!empty($included_keys)) {
				if (!in_array($key, $included_keys)) {
					continue;
				}
			}

			if (is_array($item)) {
				if (!$newline) {
					$csv1[]  = "\n";
					$newline = true;
				}
				$a      = self::exportToCSV($item, false, $columns, $file_name, $included_keys);
				$csv1[] = $a;
				$csv1[] = "\n";
			}
			else {
				if ($newline) {
					//$csv[] = "\n";
					$newline = false;
				}
				$csv[] = str_replace(',', '', $item);
			}
		}

		$csv = array_merge($csv, $csv1);
		//var_dump($csv); die;

		$a = implode(',', $csv);
		if ($download) {
			$a_arr = explode("\n", $a);
			$a     = '';
			foreach ($a_arr as $item) {
				if (empty($item)) {
					continue;
				}
				$item = trim($item, ",");
				$a    .= $item . "\n";
			}

			if (!empty($columns)) {
				if ($auto_translated) {
					foreach ($columns as $i => $column) {
						$columns[$i] = FHtml::t('common', $column);
					}
				}
				$a = implode(',', $columns) . "\n" . $a;
			}

			if (is_string($download)) {
				$file_name = $download;
			}
			elseif (empty($file_name)) {
				$file_name = FHtml::currentController() . '_' . date('Y_m_d_Hms') . '.csv';
			}

			header("Content-Type: $content_type; charset=UTF-8");
			header("Content-Disposition: attachment;filename=$file_name");
			# Disable caching - HTTP 1.1
			header("Cache-Control: no-cache, no-store, must-revalidate");
			# Disable caching - HTTP 1.0
			header("Pragma: no-cache");
			# Disable caching - Proxies
			header("Expires: 0");
			echo pack("CCC", 0xef, 0xbb, 0xbf);

			echo $a;
		}

		return $a;
	}

	/**
	 * @var PHPExcel $excel
	 */
	protected $excel;
	protected $rowStart         = 1;
	protected $currentRow       = 1;
	protected $rowHeight        = 40;
	protected $styleAlignCenter = array(
		'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		)
	);

	/**
	 * @return $this
	 */
	public static function getInstance() {
		$instance        = new static;
		$instance->excel = new PHPExcel;

		return $instance;
	}

	/**
	 * @param array|string $headers
	 * @param int          $sheetIndex
	 * @return FExcel
	 * @throws \PHPExcel_Exception
	 */
	public function setHeaderCell($headers, $sheetIndex = 0) {
		$this->getExcel()->setActiveSheetIndex($sheetIndex);
		if (is_array($headers)) {
			foreach ($headers as $key_h => $header) {
				$rowStart = $key_h + $this->getRowStart();
				$col      = $this->getCellIndex($key_h + $this->getRowStart(), 1);
				$this->getExcel()->getActiveSheet()->setCellValue($col, FHtml::t('common', $header));
			}
		}
		else {
			$col = $this->getCellIndex($this->getRowStart(), 1);
			$this->getExcel()->getActiveSheet()->setCellValue($col, FHtml::t('common', $headers));
		}
		$this->setRowStart(2);
		$this->setRowStart(2);

		return $this;
	}

	/**
	 * @param $pColumn
	 * @return string
	 */
	public function getColumnIndex($pColumn) {
		return chr(64 + $pColumn);
	}

	/**
	 * @param int    $pColumn
	 * @param string $pRow
	 * @return string
	 */
	public function getCellIndex($pColumn, $pRow) {
		return $this->getColumnIndex($pColumn) . $pRow;
	}


	/**
	 * @param $dataList
	 * @return $this
	 * @throws \PHPExcel_Exception
	 */
	public function setCellValues($dataList) {
		$dataList = array_values($dataList);
		foreach ($dataList as $index => $item) {
			$cell = $this->getCellIndex($index + 1, $this->getCurrentRow() + 1);
			if (filter_var($item, FILTER_VALIDATE_URL)) {
				$this->getExcel()->getActiveSheet()->getRowDimension($this->getCurrentRow() + 1)->setRowHeight($this->getRowHeight());
				$this->getExcel()->getActiveSheet()->getStyle($cell)->applyFromArray($this->styleAlignCenter);
				$this->setCellValueDrawing($item, $cell);
			}
			else {
				$this->getExcel()->getActiveSheet()->setCellValue($cell, $item);
			}
		}

		$this->setCurrentRow($this->getCurrentRow() + 1);

		return $this;
	}

	/**
	 * @throws \PHPExcel_Reader_Exception
	 * @throws \PHPExcel_Writer_Exception
	 */
	public function downloadFile() {
        ob_end_clean();
	    $content = ob_get_clean();
		ob_start();
		header('Content-type: application/vnd.ms-excel');
        //header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="' . $this->getFileName() . '"');
		PHPExcel_IOFactory::createWriter($this->getExcel(), 'Excel2007')->save('php://output');
		ob_end_flush();
		exit();
	}

	public function download() {
	    return $this->downloadFile();
    }

    /**
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function save() {
        PHPExcel_IOFactory::createWriter($this->getExcel(), 'Excel2007')->save($this->getSavePath() . "/" . $this->getFileName());
    }

	/**
	 * @param array|string $fileName
	 * @return FExcel
	 */
	public function setFileName($fileName) {
		$this->fileName = $fileName;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getSavePath() {
		return $this->savePath;
	}

	/**
	 * @param string $savePath
	 * @return FExcel
	 */
	public function setSavePath($savePath) {
		$this->savePath = $savePath;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getRowStart() {
		return $this->rowStart;
	}

	/**
	 * @param $rowStart
	 */
	public function setRowStart($rowStart) {
		$this->rowStart = $rowStart;
	}

	/**
	 * @return PHPExcel
	 */
	public function getExcel() {
		return $this->excel;
	}

	/**
	 * @param PHPExcel $excel
	 */
	public function setExcel(PHPExcel $excel) {
		$this->excel = $excel;
	}

	/**
	 * @return int
	 */
	public function getCurrentRow() {
		return $this->currentRow;
	}

	/**
	 * @param int $currentRow
	 */
	public function setCurrentRow($currentRow) {
		$this->currentRow = $currentRow;
	}

	/**
	 * @param string $filename
	 * @param string $coordinate
	 * @param int    $height
	 * @param int    $width
	 * @throws \PHPExcel_Exception
	 */
	public function setCellValueDrawing($filename, $coordinate, $height = 50, $width = 50) {
		$gdImage = $this->imageCreateFromString($filename);

		$objDrawing = new PHPExcel_Worksheet_MemoryDrawing;
		$objDrawing->setImageResource($gdImage);
		$objDrawing->setRenderingFunction($this->getRendingFileDrawing($filename));
		$objDrawing->setMimeType($this->getMimeType($filename));

		$objDrawing->setHeight($height);
		$objDrawing->setWidth($width);
		$objDrawing->setCoordinates($coordinate);
		$objDrawing->setOffsetX(10);
		$objDrawing->setOffsetY(10);
		$objDrawing->setWorksheet($this->getExcel()->getActiveSheet());
	}

	/**
	 * @param $filename
	 * @return resource
	 */
	public function imageCreateFromString($filename) {
		if (filter_var($filename, FILTER_VALIDATE_URL)) {
			return imagecreatefromstring(file_get_contents($filename));
		}
		$this->getMimeType($filename, $extension);
		switch ($extension) {
			case 'jpeg':
			case 'jpg':
				return imagecreatefromjpeg($filename);
				break;
			case 'png':
				return imagecreatefrompng($filename);
				break;
			case 'gif':
				return imagecreatefromgif($filename);
				break;
		}
	}

	/**
	 * @param string $filename
	 * @param string $extension
	 * @return string
	 */
	public function getMimeType($filename, &$extension = '') {
		$headers = get_headers($filename, 1);
		if (isset($headers['Content-Type'])) {
			$content_type = $headers['Content-Type'];
			$extension    = explode('/', $content_type);
			if (count($extension) == 2) {
				$extension = $extension[1];
			}

			return $headers['Content-Type'];
		}

		return '';
	}

	/**
	 * @param $filename
	 * @return mixed
	 */
	public function getRendingFileDrawing($filename) {
		return str_replace('/', '', $this->getMimeType($filename));
	}

	/**
	 * @param int $rowHeight
	 * @return int
	 */
	public function setRowHeight($rowHeight) {
		return $this->rowHeight = $rowHeight;
	}

	/**
	 * @return int
	 */
	public function getRowHeight() {
		return $this->rowHeight;
	}

	public function prepareDataExcel() {
		$models   = $this->getModels();
		$columns  = $this->getColumns();
		$cols    = array_column($columns, 'attribute');
		$this->setHeaderCell($cols);
		$setValue = function(&$item, $key, $columns) use (&$models) {
			/** @var ActiveRecord $item */
			$attributes = [];
			array_walk($columns, function($column, $key) use ($item, &$attributes) {
				if (isset($column['attribute'])) {
					if ($column['attribute'] == 'image') {
					    if (isset($column['value'])) {
                            if (is_callable($column['value'])) {
                                $value = call_user_func($column['value'], $item);
                            } else {
                                $value = $column['value'];
                            }
                        } else {
					        $value = FHtml::showImage($item, $column['attribute']);
                        }
						$attributes[$column['attribute']] = $value;
					}
					elseif (isset($column['value'])) {
						if (is_callable($column['value'])) {
							$value = call_user_func($column['value'], $item);
						}
						else {
							$value = $column['value'];
						}

						$attributes[$column['attribute']] = trim($value);
					}
					elseif (isset($column['content'])) {
						if (is_callable($column['content'])) {
							$value = call_user_func($column['content'], $item);
						}
						else {
							$value = $column['content'];
						}

						$attributes[$column['attribute']] = trim($value);
					}
					else {
						$attributes[$column['attribute']] = trim($item->{$column['attribute']});
					}
				}
			});

			$this->setCellValues($attributes);
			//$models[$key] = $attributes;
		};
		array_walk($models, $setValue, $columns);

		return $this;
	}

	/**
	 * @param $models
	 * @return $this
	 */
	public function setModels($models) {
		$this->models = $models;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getColumns() {
		return $this->columns;
	}

	/**
	 * @param array $columns
	 * @return FExcel
	 */
	public function setColumns($columns) {
		$this->columns = $columns;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getModels() {
		return $this->models;
	}
}
