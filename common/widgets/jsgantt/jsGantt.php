<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2016
 * @package yii2-widgets
 * @subpackage yii2-widget-fileinput
 * @version 1.0.4
 */

namespace common\widgets\jsgantt;

use backend\modules\cms\models\CmsBlogs;
use common\components\FHtml;
use common\widgets\BaseWidget;
use kartik\base\Html5Input;
use kartik\base\TranslationTrait;
use kartik\file\FileInput;
use kartik\file\FileInputAsset;
use Yii;
use yii\bootstrap\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Wrapper for the Bootstrap FileInput JQuery Plugin by Krajee. The FileInput widget is styled for Bootstrap 3.0 with
 * ability to multiple file selection and preview, format button styles and inputs. Runs on all modern browsers
 * supporting HTML5 File Inputs and File Processing API. For browser versions IE9 and below, this widget will
 * gracefully degrade to normal HTML file input.
 *
 * @see http://plugins.krajee.com/bootstrap-fileinput
 * @see https://github.com/kartik-v/bootstrap-fileinput
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 2.0
 * @see http://twitter.github.com/typeahead.js/examples
 */
class jsGantt extends Widget
{
    public $type = 'textarea';
    public $orientation;
    public $style;
    public $data;
    public $id;
    public $models;
    public $columns;
    public $colHeaders;
    public $attribute;
    public $model;
    public $name;
    public $fieldID = ['id', 'pID'];
    public $fieldName = ['name', 'title'];
    public $fieldStartDate = ['start', 'start_date', 'startDate', 'created_date'];
    public $fieldEndDate = ['end', 'end_date', 'endDate', 'modified_date'];
    public $fieldResource = ['user_name', 'user', 'owner', 'resource'];
    public $fieldMileStone = ['is_milestone', 'milestone'];
    public $fieldLink = ['link_url', 'link', 'url'];
    public $fieldColor = ['color'];
    public $fieldCompleted = ['progress', 'completed'];
    public $fieldGroup = ['pGroup', 'treeview_group'];
    public $fieldParent = ['parent_id'];
    public $fieldDepend = ['depend_id'];
    public $fieldNote = ['overview', 'description', 'content'];

    /**
     * @inherit doc
     */
    public function run() {
        $this->models = CmsBlogs::findAll();
        //$this->display_type = 'jexcel';
        return $this->render('jsgantt', [
            'id' => $this->id, 'name' => $this->name, 'models' => $this->models, 'data' => $this->data, 'columns' => $this->columns, 'colHeaders' => $this->colHeaders, 'model' => $this->model
            , 'fieldID' => $this->fieldID
            , 'fieldName' => $this->fieldName
            , 'fieldStartDate' => $this->fieldStartDate
            , 'fieldEndDate' => $this->fieldEndDate
            , 'fieldResource' => $this->fieldResource
            , 'fieldMileStone' => $this->fieldMileStone
            , 'fieldLink' => $this->fieldLink
            , 'fieldColor' => $this->fieldColor
            , 'fieldCompleted' => $this->fieldCompleted
            , 'fieldGroup' => $this->fieldGroup
            , 'fieldParent' => $this->fieldParent
            , 'fieldDepend' => $this->fieldDepend
            , 'fieldNote' => $this->fieldNote

        ]);
    }
}
