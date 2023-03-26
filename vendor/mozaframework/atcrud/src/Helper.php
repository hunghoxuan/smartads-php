<?php
/**
 * Created by PhpStorm.
 * User: Quyen_Bui
 * Date: 7/12/2016
 * Time: 3:10 PM
 */

namespace mozaframework\atcrud;


class Helper
{
    public static function humanize2id($text)
    {
        return strtolower(str_replace([" ", "-"], "_", $text));
    }

    public static function humanize2key($text)
    {
        return strtolower(str_replace([" ", "_"], "-", $text));
    }

    public static function prepareStringForExplode($string)
    {
        $new_string = str_replace(' ', '', $string);
        return $new_string;
    }
}