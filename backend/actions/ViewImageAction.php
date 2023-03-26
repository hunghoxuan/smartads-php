<?php

namespace backend\actions;

use common\components\FConstant;
use common\components\FHtml;

class ViewImageAction extends BaseAction
{
	public function run() {
		$d = isset($_REQUEST['d']) ? $_REQUEST['d'] : '';  //directory
		$f = isset($_REQUEST['f']) ? $_REQUEST['f'] : '';  //file name
		$s = isset($_REQUEST['s']) ? $_REQUEST['s'] : '';  //thumb

		//$file = FHtml::getFileURL($s.$f, $d, BACKEND, FConstant::NO_IMAGE);
		$file = FHtml::getFullUploadFolder($s . $f, $d);
		$file = rtrim($file, "/");
		if (!file_exists($file)) {
			$file = FHtml::getImagePath($s . $f, $d); ///also works
		}
		$info = getimagesize($file);

		header("Content-type: {$info['mime']}");

		readfile($file);
	}
}
