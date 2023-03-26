<?php
/**
 * Created by PhpStorm.
 * User: LongPC
 * Date: 12/27/2018
 * Time: 14:31
 */

namespace common\widgets;


use backend\modules\cms\models\Category;
use backend\modules\cms\models\CmsDocs;
use common\components\FConfig;
use common\components\FHtml;
use TCPDF;
use yii\base\Widget;

/**
 * Class FDocs
 * @package common\widgets
 */
class FDocs extends Widget
{
	public $category_id = 0;
	public $category;
    public $models = null;
    public $saveType = 'I';
    public $name;
    public $description;
    public $content;
    public $file_name;

	/**
	 * @return string
	 * @throws \yii\base\InvalidConfigException
	 */
	public function run() {
		return $this->export();
	}

	/**
	 * @return string
	 * @throws \yii\base\InvalidConfigException
	 */
	protected function export($saveType = '') {
	    if (empty($saveType))
	        $saveType = $this->saveType;

		$category_id = $this->category_id;
		define('K_PATH_IMAGES', '');
		// create new PDF document

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


		$logo = FHtml::getCurrentLogoUrl();
		//$logo = FHtml::getImageUrl('logo.png', 'www');

		// set default header data
		$pdf->SetHeaderData($logo, 40, FConfig::settingCompanyName(), FConfig::settingCompanyWebsite() . "\n" . FConfig::settingCompanyAddress(), [0, 0, 0], [0, 0, 0]);
		$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

		// set header and footer fonts
		$pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
		$pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// ---------------------------------------------------------

		// set default font subsetting mode
		$pdf->setFontSubsetting(true);

		// Set font
		// dejavusans is a UTF-8 Unicode font, if you only need to
		// print standard ASCII chars, you can use core fonts like
		// helvetica or times to reduce file size.
		$pdf->SetFont('dejavusans', '', 10, '', true);

		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage();

		// set text shadow effect
		//$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));

		/** @var Category $category */
		$category = isset($this->category) ? $this->category : Category::find()->where(['id' => $category_id])->one();
        $name = !empty($this->name) ? $this->name : (isset($category) ? FHtml::getFieldValue($category, ['name', 'title']) : FHtml::t('common', 'Documents'));
        $description = !empty($this->description) ? $this->description : (isset($category) ? FHtml::getFieldValue($category, ['description', 'overview']) : FHtml::t('common', 'Documents'));

        $html = "<br><br><br><br><strong><h1 style='font-size:200%'>" . $name . "</h1></strong>";
		$html .= "<p>" . $description . "</p>";
        $html .= '<small>' . FHtml::t('common', 'Modified') . ': ' . FHtml::Today() . '</small>';

		$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

		// Set some content to print
        $pdf->AddPage();

        $html      = '';
		$htmlIndex = "<br><h1>" . FHtml::t('common', 'Index') . "</h1>";

		if (!isset($models)) {
		    $condition = !empty($category_id) ? ['category_id' => $category_id] : [];
            $models = CmsDocs::findAll($condition);
        }

		$array = $this->getHtmlExport($models, $html, $htmlIndex);

		$pdf->writeHTMLCell(0, 0, '', '', $htmlIndex, 0, 1, 0, true, '', true);
		$pdf->AddPage();
		$array = explode("<h1>", $html);
		foreach ($array as $html1) {
		    if (empty($html1))
		        continue;
            $html1 = "<h1>" . $html1;
            // Print text using writeHTMLCell()
            $pdf->writeHTMLCell(0, 0, '', '', $html1, 0, 1, 0, true, '', true);
            $pdf->AddPage();
        }

        $htmlEnd = "<br><br><br><br><strong><h1 style='font-size:200%'>" . $name . "</h1></strong>";

        $htmlEnd = "<br><br><br><p>" . "________________________" . "</p>";
        $htmlEnd .= "<br><br><p>" . FConfig::settingCompanyName() . "<br/>" .  FConfig::settingCompanyAddress() . "<br/>" . FConfig::settingCompanyWebsite() . "<br/>" . FConfig::settingCompanyPhone(true) . "</p>";

        $pdf->writeHTMLCell(0, 0, '', '', $htmlEnd, 0, 1, 0, true, '', true);

        // ---------------------------------------------------------


		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
        if (!empty($this->file_name)) {
            $file_name = $this->file_name;
        } else {
            $file_name = isset($category) ? FHtml::normalizeFileName($category->name) : '';
            $file_name .= date('Y.m.d') . ".pdf";
        }

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(FConfig::settingCompanyName());
        $pdf->SetTitle(isset($category) ? $category->name : FHtml::t('common', 'Documents'));
        $pdf->SetSubject(isset($category) ? $category->name : FHtml::t('common', 'Documents'));
        $pdf->SetKeywords(FHtml::settingCompanyDescription());

		return $pdf->Output($file_name, $saveType);
		//============================================================+
		// END OF FILE
		//============================================================+
	}

	/**
	 * @param        $models
	 * @param string $html
	 * @param string $htmlIndex
	 * @param int    $parent_id
	 * @param array  $arrIndex
	 * @param int    $level
	 * @return string
	 */
//	protected function getHtmlExport1($models, &$html = '', &$htmlIndex = '', $parent_id = 0, $arrIndex = [], $level = 1) {
//	    $result = [];
//		/** @var CmsDocs $model */
//		foreach ($models as $i => $model) {
//			if ($model->parent_id == $parent_id) {
//				if (isset($arrIndex[$level])) {
//					$arrIndex[$level] = $arrIndex[$level] + 1;
//				}
//				else {
//					$arrIndex[$level] = 1;
//				}
//
//				$srtIndex = implode('.', $arrIndex);
//
//				$indexTitle = 1;
//				$space      = '';
//				if ($level == 2) {
//					$indexTitle = 2;
//					$space      = str_repeat("&nbsp;", 2);
//                }
//				if ($level == 3) {
//					$indexTitle = 3;
//					$space      = str_repeat("&nbsp;", 4);
//				}
//				elseif ($level >= 4) {
//					$indexTitle = 4;
//					$space      = str_repeat("&nbsp;", 6    );
//				}
//				if ($indexTitle == 1)
//				    $indexTag = "h3";
//				else if ($indexTitle == 2)
//                    $indexTag = "h4";
//				else
//				    $indexTag = "p";
//
//				$htmlIndex .= "<$indexTag>$space $srtIndex. " . FHtml::getFieldValue($model, ['name', 'title']) . "</$indexTag>";
//				$html      .= "<h$indexTitle>$space $srtIndex. " . FHtml::getFieldValue($model, ['name', 'title']) . "</h$indexTitle>";
//                $html      .= "<p>" . FHtml::getFieldValue($model, ['overview', 'description']) . "</p>";
//                $html      .= "<p>" . FHtml::getFieldValue($model, ['content']) . "</p>";
//                //if ($indexTitle <= 2)
//                $html .= str_repeat("<p></p>", 1);
//
//				unset($models[$i]);
//
//				$this->getHtmlExport($models, $html, $htmlIndex, $model->id, $arrIndex, $level + 1);
//			}
//		}
//
//		return $html;
//	}

    protected function getHtmlExport($models, &$html = '', &$htmlIndex = '', $parent_id = 0, $arrIndex = [], $level = 1) {

        $result = [];
        /** @var CmsDocs $model */
        foreach ($models as $i => $model) {
            $level = FHtml::getFieldValue($model, ['tree_level', 'level']);
            $indexTitle = 1;
            $srtIndex = FHtml::getFieldValue($model, ['tree_index', 'index']);
            $space      = '';

            if ($level == 1) {
                $indexTitle = 2;
                $space      = str_repeat("&nbsp;", 4);
            }
            if ($level == 2) {
                $indexTitle = 3;
                $space      = str_repeat("&nbsp;", 8);
            }
            elseif ($level >= 3) {
                $indexTitle = 4;
                $space      = str_repeat("&nbsp;", 12 );
            }
            if ($indexTitle == 1)
                $indexTag = "h3";
            else if ($indexTitle == 2)
                $indexTag = "h4";
            else
                $indexTag = "p";

            $htmlIndex .= "<$indexTag>$space $srtIndex. " . FHtml::getFieldValue($model, ['name', 'title']) . "</$indexTag>";
            $html      .= "<h$indexTitle>$space $srtIndex. " . FHtml::getFieldValue($model, ['name', 'title']) . "</h$indexTitle>";
            $html      .= "<p>" . FHtml::getFieldValue($model, ['overview', 'description']) . "</p>";
            $html      .= "<p>" . FHtml::getFieldValue($model, ['content']) . "</p>";
            //if ($indexTitle <= 2)
            $html .= str_repeat("<p></p>", 1);

        }

        return $html;
    }

	/**
	 * @return mixed
	 */
	public function getCategory_id() {
		return $this->category_id;
	}

	/**
	 * @param mixed $category_id
	 */
	public function setCategory_id($category_id) {
		$this->category_id = $category_id;
	}
}