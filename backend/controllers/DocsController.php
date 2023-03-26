<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\controllers;

use backend\modules\cms\models\Category;
use backend\modules\cms\models\CmsDocs;
use common\components\FHtml;
use common\controllers\BaseApiController;
use common\widgets\FDocs;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;


class DocsController extends AdminController
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
                            'swagger', 'index', 'api', 'download', 'vcard'
                        ],
                        'allow'   => true,
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

    public function actionIndex() {
        $category_id = FHtml::getRequestParam('category_id');
        if (!empty($category_id)) {
            return $this->actionList($category_id);
        }
        return $this->render('index');
    }

    public function actionSwagger() {
        return $this->render('swagger');
    }

    public function actionVcard() {
        return $this->render('vcard');
    }

    public function actionDownload($category_id) {
        echo FDocs::widget(['category_id' => $category_id]);
    }

    /**
     * @param $category_id
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionList($category_id, $id = null) {
        $category = Category::find()->where(['id' => $category_id])->one();

        if (!isset($category) || empty($category)) {
            $models = [];
            $menus  = "";
            $slug   = '';

            return $this->render('list', compact('models', 'menus', 'slug', 'category'));
        }

        if (!isset($id)) {
            $id = FHtml::getRequestParam(['slug', 'id']);
            $id_arr = explode('-', $id);
            $id = count($id_arr) > 1 ? $id_arr[count($id_arr) - 1] : $id;
        }

        $models = CmsDocs::findAll(['category_id' => $category_id]);
        $menus = $this->recursiveMenu($models, $id);

        if (!empty($id)) {
            $models = $this->buildChildModels($models, $id);
        }

        //FHtml::var_dump($models);
        return $this->render('list', compact('models', 'menus', 'slug', 'category', 'id', 'category_id'));
    }

    /**
     * @param        $models
     * @param int    $parent_id
     * @param array  $menus
     * @param array  $arrIndex
     * @param string $level
     * @param int    $i
     * @return array|string
     */
    public function recursiveMenu($models, $id = null) {

        $data = $models;
        $menus = '';
        if ($data) {
            $menus .= "";

            /** @var CmsDocs $item */
            foreach ($data as $key => $item) {
                $level = FHtml::getFieldValue($item, ['tree_level', 'level']);
                $url   = FHtml::createViewUrl('docs', ['category_id' => $item->category_id, 'id' => $item->id, 'name' => str_slug($item->name)]);
                $css = $level == 0 ? "text-transform:uppercase;" : "";
                $css .= $level == 1 ? "font-weight:bold;" : "";

                $css1 = $id == $item->id ? "active" : "";
                $menus .= "<div class='item $css1' style='width: 100%; $css'><a href='{$url}'>" . FHtml::getTreeViewNodeName($item) . "</a></div>";
            }
            $menus .= '';
        }

        return $menus;
    }

    private function buildChildModels($models, $id = null) {
        $root_level = -1;
        $result = [];
        foreach ($models as $i => $model) {
            $level = FHtml::getFieldValue($model, ['tree_level', 'level']);
            if ($level == $root_level)
                break;
            if ($model->id == $id) {
                $result[] = $model;
                $root_level = $level;
            } else if ($level > $root_level && $root_level > -1) {
                $result[] = $model;
            }
        }
        return $result;
    }
}
