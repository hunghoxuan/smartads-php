<?php
/**
 * Created by PhpStorm.
 * User: Quyen_Bui
 * Date: 7/12/2016
 * Time: 3:10 PM
 */

namespace projectemplate\ptcrud;

use yii\base\Controller;

class Helper extends Controller
{
    const
        LOOKUP_KEYWORD = 'LOOKUP:',
        DROPDOWN_KEYWORD = 'DROPDOWN:';

    const
        SIMPLE = 'simple',
        COMPLEX = 'complex';

    public static function checkHiddenField($name, $array)
    {

        foreach ($array as $item) {
            if (strpos($item, '*') != 0) {
                if (strpos($name, trim($item, '*')) !== false) {
                    return true;
                }
            } else {
                if ($name == $item) {
                    return true;
                }
            }
        }

        return false;
    }

    public static function keyword($string)
    {
        $keywords = array(
            self::LOOKUP_KEYWORD,
            self::DROPDOWN_KEYWORD,
        );

        foreach ($keywords as $item) {
            if (strpos($string, $item) !== false) {
                return $item;
            }
        }
        return false;
    }


    public static function getContentAfterKeyword($text, $keyword)
    {
        $result = "";
        $position = strpos($text, $keyword);
        if ($position !== false) {
            $result = substr($text, $position + strlen($keyword), strlen($text));
        }

        return $result;
    }


    public static function dropdownDataFromDbComment($comment, $keyword)
    {
        $result = array();
        $content = self::getContentAfterKeyword($comment, $keyword);
        //complex array
        if (strpos($content, "{") !== false) {
            $type = "complex";
            $data = json_decode($content, true);
            $result["type"] = $type;
            $result["data"] = $data;
        }
        //simple array
        else{
            $type = "simple";
            $data = explode("|", $content);
            $result["type"] = $type;
            $result["data"] = $data;

        }
        return $result;
    }

    public static function lookupDataFromDbComment($comment, $keyword)
    {
        $result = array();
        $content = self::getContentAfterKeyword($comment, $keyword);

        $data = explode("|", $content);

        $result["table"] = $data[0];
        $result["key"] = $data[1];
        $result["value"] = $data[2];

        return $result;
    }

    public static function constantKey($field, $string)
    {
        $string = self::humanize2id($string);

        $result = strtoupper($field . "_" . $string);

        return $result;
    }

    public static function humanize2id($text)
    {
        return strtolower(str_replace([" ", "-"], "_", $text));
    }

    public static function humanize2key($text)
    {
        return strtolower(str_replace([" ", "_"], "-", $text));
    }
}