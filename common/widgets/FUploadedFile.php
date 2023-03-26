<?php
/**
 * Created by PhpStorm.
 * User: HY
 * Date: 1/4/2016
 * Time: 3:33 PM
 */

namespace common\widgets;

use common\components\FHtml;
use yii\web;

class FUploadedFile extends web\UploadedFile
{
    public $oldName;
    public $fieldName;
    private static $_files;

    public function saveAs($file, $deleteTempFile = true)
    {
        if ($this->error == UPLOAD_ERR_OK) {
            if ($deleteTempFile) {
                return move_uploaded_file($this->tempName, $file);
            } elseif (is_uploaded_file($this->tempName)) {
                return copy($this->tempName, $file);
            }
        }
        return false;
    }

    public static function getInstanceByName($name)
    {
        //echo $name;

        $files = self::loadFiles();
        //echo $name;
        //FHtml::var_dump($files);
        return isset($files[$name]) ? $files[$name] : null;
    }

    private static function loadFilesRecursive($key, $names, $tempNames, $types, $sizes, $errors)
    {
        if (is_array($names)) {
            foreach ($names as $i => $name) {
                self::loadFilesRecursive($key . '[' . $i . ']', $name, $tempNames[$i], $types[$i], $sizes[$i], $errors[$i]);
            }
        } elseif ((int)$errors !== UPLOAD_ERR_NO_FILE) {
            self::$_files[$key] = new static([
                'name' => $names,
                'tempName' => $tempNames,
                'oldName' => '',
                'fieldName' => '',
                'type' => $types,
                'size' => $sizes,
                'error' => $errors,
            ]);
        }
    }

    /**
     * Creates UploadedFile instances from $_FILE.
     * @return array the UploadedFile instances
     */
    private static function loadFiles()
    {
        if (self::$_files === null) {
            self::$_files = [];
            if (isset($_FILES) && is_array($_FILES)) {
                foreach ($_FILES as $class => $info) {
                    self::loadFilesRecursive($class, $info['name'], $info['tmp_name'], $info['type'], $info['size'], $info['error']);
                }
            }
        }
        return self::$_files;
    }
}