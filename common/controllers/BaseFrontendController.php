<?php
namespace common\controllers;

use common\components\FFile;
use common\components\FHtml;
use frontend\components\Helper;
use Yii;
use yii\web\Controller;


class BaseFrontendController extends BaseController
{
    public $mainMenu = array();
    public $uploadFolder;

    public function currentController()
    {
        return $controller = $this->getUniqueId();
    }

    public function currentAction()
    {
        return $action = $this->action->id;
    }

    public function init()
    {
        parent::init();
        $this->view->params['uploadFolder'] = $this->uploadFolder;
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;

        $theme = FHtml::currentFrontendTheme();

        $application_folder = FHtml::currentApplicationFolder();

        if (!empty($application_folder) && empty($theme)) {

            if (is_dir(FHtml::getRootFolder() . '/applications/' . $application_folder . '/frontend')) {
                $pathMap = 'applications/' . $application_folder . '/frontend';
                $baseUrl = Yii::$app->getUrlManager()->getBaseUrl() . '/applications/' . $application_folder . '/frontend';
                $widgetUrl = 'applications/' . $application_folder . '/frontend/components/widgets';
            } else {
                $pathMap = 'applications/' . $application_folder ;
                $baseUrl = Yii::$app->getUrlManager()->getBaseUrl() . '/applications/' . $application_folder ;
                $widgetUrl = 'applications/' . $application_folder . '/components/widgets';
            }

        } else if (!empty($application_folder) && !empty($theme)) {

            //layout folder
            if (is_dir(FHtml::getRootFolder() . '/applications/' . $application_folder .'/frontend/themes/' . $theme .  '/layouts')) {
                $pathMap = "applications/$application_folder/themes/$theme";
            } else if (is_dir(FHtml::getRootFolder() . '/frontend/themes/' . $theme . '/layouts')) {
                $pathMap = '@frontend/themes/' . $theme;
            } else  if (is_dir(FHtml::getRootFolder() . '/applications/' . $theme . '/layouts')) {
                $pathMap = 'applications/' . $theme;
            } else if (is_dir(FHtml::getRootFolder() . '/applications/' . $application_folder . '/layouts')) {
                $pathMap = 'applications/' . $application_folder;
            } else {
                $pathMap = '@frontend/web';
            }

            //assets folder
            if (is_dir(FHtml::getRootFolder() . '/applications/' . $application_folder .'/frontend/themes/' . $theme)) {
                $baseUrl = Yii::$app->getUrlManager()->getBaseUrl() . "/applications/$application_folder/themes/$theme";
            } else if (is_dir(FHtml::getRootFolder() . '/frontend/themes/' . $theme)) {
                $baseUrl = Yii::$app->getUrlManager()->getBaseUrl() . '/frontend/themes/' . $theme;
            } else if (is_dir(FHtml::getRootFolder() . '/applications/' . $application_folder . '/assets')) {
                $baseUrl = Yii::$app->getUrlManager()->getBaseUrl() . '/applications/' . $application_folder;
            } else if (is_dir(FHtml::getRootFolder() . '/applications/' . $theme . '/assets')) {
                $baseUrl = Yii::$app->getUrlManager()->getBaseUrl() . '/applications/' . $theme;
            } else {
                $baseUrl = Yii::$app->getUrlManager()->getBaseUrl() . '/frontend/themes/default';
            }

            $widgetUrl = $pathMap . '/components/widgets';

        } else {

            $theme = 'default';
            $pathMap = '@frontend/views';
            $baseUrl = Yii::$app->getUrlManager()->getBaseUrl() . '/frontend/views';
            $widgetUrl = '@frontend/components/widgets';

        }

        Yii::$app->view->theme = new \yii\base\Theme(
            [
                'pathMap' => [
                    '@frontend/views' => $pathMap,
                    '@frontend/components/widgets' => $widgetUrl,
                ],
                'baseUrl' => $baseUrl,
            ]);


        $this->uploadFolder = Yii::getAlias('@' . UPLOAD_DIR);
        $this->createMenu();

        return parent::beforeAction($action);
    }


    protected function createMenu()
    {
        $controller = $this->getUniqueId();
        $action = $this->action->id;

        $this->view->params['mainMenu'] = $this->mainMenu;
    }

    public function getCategoryItemMenu($object_type, $list_url, $detail_url, $column_count = 4)
    {
        return Helper::getCategoryItemMenu($object_type, $list_url, $detail_url, $column_count);
    }

    public function getCategoryMenu($object_type, $list_url)
    {
        return Helper::getCategoryMenu($this->getUniqueId(), $this->action->id, $object_type, $list_url);
    }

    public function getArrayItemMenu($menuArrays = [])
    {
        return Helper::getArrayItemMenu($menuArrays);
    }

    public function getMegaContentV5($object_type = 'product', $controllerURL = 'product', $subItemsField = 'products', $column_count = 4)
    {
        return Helper::getMegaContentV5($object_type, $controllerURL, $subItemsField, $column_count);
    }

    public function getMegaContentV8($object_type = 'product', $controllerURL = 'category', $column_count = 4)
    {
        return Helper::getMegaContentV8($this->getUniqueId(), $this->action->id, $object_type, $controllerURL, $column_count);
    }

    public function getMegaContentV8Mix($type = 'cii', $object_type = 'product', $controllerURL = 'product', $condition = '') //$type = cii, cbi, bii c: category, b:big item, i: normal item
    {
        return Helper::getMegaContentV8Mix($type, $object_type, $controllerURL, $condition);
    }

    public function getTreeContentByCategory()
    {
        return Helper::getTreeContentByCategory($this->getUniqueId(), $this->action->id);
    }

    public function getChildrenOfCategory($item, $objects, $controller, $action, $params_id)
    {
        return Helper::getChildrenOfCategory($item, $objects, $controller, $action, $params_id);
    }
}

