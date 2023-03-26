<?php
namespace common\controllers;

use backend\models\AuthMenu;
use backend\models\PasswordResetRequestForm;
use backend\models\ResetPasswordForm;
use backend\models\SetupModel;
use backend\models\User;
use common\components\FBackup;
use common\components\FEmail;
use common\components\FFile;
use common\components\FSecurity;
use common\config\FSettings;
use common\models\LoginForm;
use yii\helpers\StringHelper;
use yii\web\Response;
use kartik\form\ActiveForm;
use Yii;
use yii\base\InlineAction;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use common\components\FHtml;
use yii\web\NotFoundHttpException;
use common\components\FConfig;

class BaseAdminController extends BaseController
{
    public $mainMenu = array();
    public $uploadFolder;
    public $currentController;
    public $currentAction;
    protected $moduleName = '';
    protected $moduleTitle = '';
    protected $moduleKey = '';
    protected $object_type = '';

    public function init()
    {
        parent::init();

        $this->view->params['toolBarActions'] = [];
        $this->view->params['uploadFolder'] = $this->uploadFolder;
        $isAjax = false;
        $this->view->params['isAjax'] = $isAjax;
        $this->view->params['displayType'] = $isAjax ? "modal-remote" : "";
        $this->view->params['editType'] = "";
        $this->view->params['buttonsType'] = (FConfig::setting(FHtml::SETTINGS_GRID_BUTTONS_TYPE, 'icons', ['icons', 'combos'], 'Backend', FHtml::EDITOR_SELECT) == 'icons') ? false: true;

        $isMD = FConfig::setting(FHtml::SETTINGS_MATERIAL_DESIGN, 'Material Design', ['Material Design', 'Bootstrap'], 'Theme', FHtml::EDITOR_SELECT) == 'Material Design' ? true : false; //material design

        if ($isMD == false) {
            $this->view->params['cssComponents'] = "components-rounded";
            $this->view->params['cssPlugins'] = "plugins";
            $this->view->params['page-md'] = "";
        } else {
            $this->view->params['cssComponents'] = "components-md";
            $this->view->params['cssPlugins'] = "plugins-md";
            $this->view->params['page-md'] = "page-md";
        }
        $this->view->params['portletStyle'] = FHtml::WIDGET_TYPE_LIGHT;

        $color = ''; //(FConfig::setting(FHtml::SETTINGS_PORTLET_STYLE, FHtml::WIDGET_TYPE_BOX) == FHtml::WIDGET_TYPE_BOX) ? FConfig::setting(FHtml::SETTINGS_MAIN_COLOR, FHtml::WIDGET_COLOR_DEFAULT) : '';

        $this->view->params['portletStyle'] = 'portlet' . ' ' . FHtml::WIDGET_TYPE_LIGHT;
        $this->view->params['mainIcon'] = ''; //FConfig::setting('mainIcon', '');
        $this->view->params['mainColor'] = FConfig::setting(FHtml::SETTINGS_ADMIN_MAIN_COLOR, FHtml::WIDGET_COLOR_DEFAULT, FHtml::ARRAY_ADMIN_THEME, 'Theme', FHtml::EDITOR_SELECT);
        $this->view->params['displayPortlet'] = true; //FConfig::setting(FHtml::SETTINGS_DISPLAY_PORTLET, false);
        $this->view->params['activeForm_type'] = FConfig::setting(FHtml::SETTINGS_FORM_CONTROLS_ALIGNMENT, ActiveForm::TYPE_HORIZONTAL, [ActiveForm::TYPE_HORIZONTAL, ActiveForm::TYPE_INLINE, ActiveForm::TYPE_VERTICAL], 'Theme', FHtml::EDITOR_SELECT);
        $this->view->params['displayPageContentHeader'] = false; //FConfig::setting(FHtml::SETTINGS_DISPLAY_PAGECONTENT_HEADER, false);
        $this->view->params['left_menu'] = 'menu.php';
        $this->view->params['left_menu_width'] = "235px";

        //echo 13; die;
    }

    public function saveType($hiddenId = 'saveType')
    {
        return isset($_POST[$hiddenId]) ? $_POST[$hiddenId] : '';
    }

    protected function createMenu()
    {
        $user = FHtml::currentUserIdentity();
        if (!isset($user)) {
            return;
        }

        $this->mainMenu = self::buildMainMenu($this->mainMenu);
    }

    public function beforeAction($action)
    {
        $user = FHtml::currentUserIdentity();
        $controller = $this->getUniqueId();

        if (isset($user)) {
            if ($controller !== 'api')
                $this->createMenu();
            $this->view->params['mainMenu'] = $this->mainMenu;
        }

        $this->uploadFolder = Yii::getAlias('@' . UPLOAD_DIR);

        return parent::beforeAction($action);
    }

    protected function buildMainMenu($mainMenu, $modules = [], $group = BACKEND)
    {
        $this->currentController = $this->getUniqueId();
        $this->currentAction = $this->action->id;

        // return in FSettings first
        $result = FSecurity::getBackendMenu($this->currentController, $this->currentAction);

        return $result;
    }

    public function actionApi($format = \yii\web\Response::FORMAT_JSON) {
        \Yii::$app->response->format = $format;

        $id = FHtml::getRequestParam(['id']);
        $this->object_type = FHtml::currentObjectType();

        $fields = FHtml::getRequestParam(['fields', 'columns']);
        $keyword = FHtml::getRequestParam(['keyword', 'k']);

        $params = FHtml::getRequestParam(['params', 'search', 'filter', 's']);

        $orderby = FHtml::getRequestParam(['sort', 'order', 'sort_by', 'order_by', 'orderby']);
        $limit = FHtml::getRequestParam(['limit', 'page_size', 'pagesize'], -1);
        $page = FHtml::getRequestParam(['page', 'p', 'page_index'], 1);
        $lang = FHtml::getRequestParam(['lang', 'l']);
        $application_id = FHtml::getRequestParam(['application_id', 'client_id']);
        $user_id = FHtml::getRequestParam('user_id');
        $token = FHtml::getRequestParam('token');

        $paramsArray = FHtml::RequestParams();
        //Default Search Params: lang, application_id
        $paramsArray = FHtml::mergeRequestParams($paramsArray,
            [
                'lang' => $lang,
                'application_id' => $application_id,
            ]);

        if (!empty($this->params))
            $paramsArray = FHtml::mergeRequestParams($paramsArray, FHtml::decode($params));

        if (empty($id))
        {
            return FHtml::findAll($this->object_type, $paramsArray, $orderby, $limit, $page, true, $fields);
        } else {
            return FHtml::findOne($this->object_type, $id);
        }
    }


    public function loadModel($model, $params = []) {
        $request = Yii::$app->request;
        if (empty($params))
            $params = $request->post();

        if ($model->load($params))
            return $model;
        return false;
    }

    public function returnView($action, $id) {
        if ($this->saveType() == 'clone') {
            return $this->redirect(['create', 'id' => $id]);
        } else if ($this->saveType() == 'add') {
            return $this->redirect(['create']);
        } else if ($this->saveType() == 'save') {
            return $this->redirect(['update', 'id' => $id]);
        }
        return $this->redirect(['index']);
    }

    public function cancel() {
        $return_url = FHtml::getReturnUrl();
        if (!empty($return_url))
            return $this->redirect($return_url);

        return $this->redirect(['index']);
    }

    public function goBack($default_url = '') {
        $return_url = FHtml::getReturnUrl();

        if (!empty($return_url))
            return parent::redirect($return_url);

        return parent::goBack($default_url);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (Yii::$app->request->isAjax)
            $model = FHtml::findOne($this->object_type . '', $id);
        else
            $model = FHtml::findOne($this->object_type, $id);

        if (isset($model)) {
            return $model;
        } else {
            throw new NotFoundHttpException(FHtml::t('message', 'Data does not exist') . '. Id = ' . $id);
        }
    }

    protected function getSelectedIds() {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
        return $pks;
    }

    protected function getSelectedModels() {
        $pks = self::getSelectedIds();
        $result = [];
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            if (isset($model)) {
                $result[] = $model;
            }
        }
        return $result;
    }

    protected function exitAction($returnParams = [], $action = 'index') {
        $request = Yii::$app->request;

        if (empty($returnParams))
            $returnParams = FHtml::RequestParams(['id']);

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#' . $this->getPjaxContainerId()];
        } else {
            /*
                *   Process for non-ajax request
                */
            return $this->redirect(ArrayHelper::merge([$action], $returnParams ));
        }
    }

    protected function getModel($object_type, $object_id = '', $field = '') {

        if (empty($object_id))
            $model = FHtml::createModel($object_type);
        else
            $model = FHtml::getModel($object_type, '', $object_id, null, false);

        if (is_string($model))
            return $model;

        if (!isset($model)) {
            if (!FHtml::isTableExisted($object_type))
                return FHtml::showErrorMessage("Table [$object_type] does not exist.");
            else
                return FHtml::showErrorMessage("Object [$object_type #$object_id] does not exist.");
        }

//        if (!empty($field) && !FHtml::field_exists($model, $field))
//            return FHtml::showErrorMessage('Field name [' . $field . '] is invalid.');

        return $model;
    }

    protected function createSearchModel() {
        return $this->createModel();
    }

    protected function getSearchParams() {
        return FHtml::getSearchParams();
    }

    protected function getPjaxContainerId($object_type = '', $type = 'crud-datatable') {
        if (empty($object_type))
            $object_type = $this->object_type;
        return FHtml::getPjaxContainerId(BaseInflector::camelize($object_type), $type);
    }

    protected function saveModelAjax($model, $params = null) {
        $request = Yii::$app->request;
        $id = FHtml::getFieldValue($model, ['id']);

        if (empty($controller_id))
            $controller_id = FHtml::getTableName($model);
        /*
            *   Process for ajax request
            */
        $table = FHtml::getTableName($model);
        $title = FHtml::t($table, BaseInflector::camel2words($table)) . " #" . $id;
        Yii::$app->response->format = Response::FORMAT_JSON;
        if($request->isGet){
            return [
                'title'=> $title,
                'content'=>$this->renderAjax('update', [
                    'model' => $model, 'modelMeta' => null,
                ])
            ];
        } else if($model->load($request->post()) && $model->save()){
            return [
                'forceReload'=>'#' . self::getPjaxContainerId($controller_id),
                'title'=> $title,
                'content'=>$this->renderAjax('view', [
                    'model' => $model, 'modelMeta' => null,
                ]),
            ];
        } else{
            return [
                'title'=> $title,
                'content'=> $this->renderAjax('update', [
                    'model' => $model, 'modelMeta' => null
                ])
            ];
        }
    }

    public function saveModel($model = null, $request = null) {
        if (!isset($request))
            $request = Yii::$app->request->post();

        return FHtml::saveModel($model, $request);

//        if (is_string($model) || empty($model))
//            $model = $this->createModel($model);
//
//        if ($model->load($request->post())) {
//            if  ($model->save())
//                return $model;
//            else {
//                FHtml::addError($model->errors);
//                return false;
//            }
//        }
//
//        return false;
    }

    protected function createModel($className = '', $id = '', $params = null) {
        if (empty($className))
            $className = $this->object_type;

        $id = empty($id) ? FHtml::getRequestParam('id') : $id;
        $params = FHtml::merge($params, FHtml::RequestParams());

        if (Yii::$app->request->isAjax)
            $model = FHtml::getCloneModel($className . '', $id, $params); //FHtml::getCloneModel($className . '_search', $id, $params);
        else
            $model = FHtml::getCloneModel($className, $id, $params);
        return $model;
    }

    protected function isAjax() {
        if (Yii::$app->request->isAjax)
            return true;
        return false;
    }

    public function actionLogin()
    {
        $this->layout = 'login';

        $user = FHtml::currentUserIdentity();
        $referrer_url = \Yii::$app->request->referrer;
        $return_url = FHtml::getReturnUrl();

        if (!\Yii::$app->user->isGuest || isset($user)) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if (!empty($_POST) && FSecurity::logInBackend($model)) {
            if (StringHelper::endsWith($referrer_url, '/logout') || empty($return_url))
                return $this->goHome();

            //echo $return_url; echo FHtml::currentUrl(); die;
            return $this->goHome();
            //return $this->goBack($return_url);
        } else {
            return $this->render('login2', [
                'model' => $model,
            ]);
        }
    }

    public function actionIndex() {
        return null;
    }

    public function goHome()
    {
        $result = $this->redirect(['/']);

        return $result;
    }

    public function actionLogout()
    {
        FSecurity::logOut();
        return $this->goHome();
    }

    /**
     * @return void|\yii\web\Response
     */
    public function actionRefresh()
    {
        FHtml::RefreshCache();
        return $this->refreshPage();
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionLog()
    {
        $action = FHtml::getRequestParam('action');
        if ($action == 'clear')
            FHtml::clearLog();

        return $this->render('log');
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $this->layout = 'login';

        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        $this->layout = 'login';

        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionSetup()
    {
        $user_id = FHtml::currentUserId();
        if (!empty($user_id) && !FHtml::isRoleAdmin()) {
            $this->goHome();
        }

        if (empty($user_id) && FConfig::isConfigured()) {
            $this->goHome();
        }

        $application_id = FHtml::currentApplicationId();

        $model = new SetupModel();
        $message = '';

        if (empty($user_id)) {
            $this->view->params['left_menu'] = false;
            $this->view->params['left_menu_width'] = "0px";
        }
        set_time_limit(120);

        if (!empty($_POST)) {
            FHtml::loadParams($model, $_POST['SetupModel']);
            if (empty($model->db_password))
                $model->db_password = FConfig::getConfigValue('components/' . $model->db_name . '/password');
            if (empty($model->email_password))
                $model->email_password = FConfig::getConfigValue('components/mailer/transport/password');

            if (empty($model->admin_username))
                $model->admin_username = 'admin';


            $result =  FBackup::setupConfiguration($model);

            //setup Database
            $result = array_merge($result, FBackup::setupDatabase($application_id, $model->db_host, $model->db_database, $model->db_username, $model->db_password));

            //setup Admin account
            $admin_model = User::findOne(['username' => $model->admin_username], false);
            $password = $model->admin_password;

            if (isset($admin_model) && !empty($password)) {
                FSecurity::setUserPassword($admin_model, $password);
                //$admin_model->role = FSecurity::ROLE_ADMIN;
                $admin_model->save();
            } else if (!isset($admin_model)) {
                if (empty($model->admin_password))
                    $model->admin_password = DEFAULT_PASSWORD;
                $admin_model = FSecurity::addUser($model->admin_username, $model->admin_email, $password, FSecurity::ROLE_ADMIN);
            }

            $message = implode('<br/><br/>', $result);

            FEmail::notifySystem( "[$application_id] User Setup :$model->purchase_site $model->client_name, $model->client_email ", $message, ['Client Name' => $model->client_name, 'Client Email' => $model->client_email,
                'Purchase Site' => $model->purchase_site, 'Purchase Order' => $model->purchase_order, 'Purchase License' => $model->purchase_license]);

            $canEdit = false;

        } else {
            $db_name = FHtml::currentApplicationDatabase();

            $dsn = FConfig::getConfigValue('components/' . $db_name . '/dsn');
            $dsn = str_replace(['mysql:host=', 'dbname='], '', $dsn);
            $dsn = explode(';', $dsn);

            if (count($dsn) == 2) {
                $model->db_host = $dsn[0];
                $model->db_database = $dsn[1];
            } else $model->db_host = $dsn[0];

            $model->app_version = FHtml::frameworkVersion();

            $model->admin_email = FConfig::getParamValue(FConfig::ADMIN_EMAIL, $model->email_username);
            $model->app_website = FConfig::getParamValue(FConfig::SETTINGS_COMPANY_WEBSITE);
            $model->app_description = FConfig::getParamValue(FConfig::SETTINGS_COMPANY_DESCRIPTION);
            $model->admin_phone = FConfig::getParamValue(FConfig::SETTINGS_COMPANY_PHONE);
            $model->admin_email = FConfig::getParamValue(FConfig::SETTINGS_COMPANY_EMAIL);
            $model->admin_username = 'admin';

            $model->purchase_order = FConfig::getParamValue('purchase_order');
            $model->client_name = FConfig::getParamValue('client_name');
            $model->client_email = FConfig::getParamValue('client_email');
            $model->purchase_license = FConfig::getParamValue(FConfig::APP_LICENSE);


            $model->app_name = FConfig::getParamValue(FConfig::APP_NAME, FHtml::currentApplicationId());
            $model->purchase_license = FConfig::getParamValue(FConfig::APP_LICENSE, '');
            $model->purchase_site = FConfig::getParamValue(FConfig::APP_SITE, '');

            $model->db_name = FHtml::currentApplicationDatabase();
            $model->db_username = FConfig::getConfigValue('components/' . $db_name . '/username');
            //$model->db_password = FConfig::getConfigValue('components/' . $db_name . '/password');

            $model->email_host = FConfig::getConfigValue('components/mailer/transport/host');
            $model->email_username = FConfig::getConfigValue('components/mailer/transport/username');
            //$model->email_password = FConfig::getConfigValue('components/mailer/transport/password');
            $model->email_port = FConfig::getConfigValue('components/mailer/transport/port');
            $model->email_encryption = FConfig::getConfigValue('components/mailer/transport/encryption');
            $canEdit = true;

        }
        $model->email_password = '';
        $model->db_password = '';
        return $this->render('setup', ['model' => $model, 'canEdit' => $canEdit, 'message' => $message]);
    }

    public function actionFile($file_name = null, $file_path = null)
    {
        if (!empty($_POST)) {
            $folder = FHtml::getRequestParam('folder');
            $file_name = FHtml::getRequestParam('file');
            $action = FHtml::getRequestParam('action');
            $uploaded_file = FHtml::getFullUploadFolder($folder) . '/' . $file_name;
            if ($action == 'delete') {
                if (FFile::delete($uploaded_file)) {
                    return FHtml::t('message', 'File deleted successfully') . ': ' .  $uploaded_file;
                }
                return 'Deleted: ' . $uploaded_file;
            }
            if (!empty($_FILES))
            {
                foreach ($_FILES as $field => $file) {

                    if (FFile::delete($uploaded_file)) {
                        //return 'deleted' . $uploaded_file;
                    }
                    if (move_uploaded_file($file['tmp_name'], $uploaded_file)) {
                        return FHtml::t('message', 'File uploaded successfully') . ': ' .  $uploaded_file;
                    }
                }
                return 'Uploaded: ' . $uploaded_file;

            }
            return '';
        }

        $object_type = FHtml::getRequestParam('object_type');
        $object_id = FHtml::getRequestParam('object_id');
        $file = FHtml::getRequestParam('file');
        $folder = FHtml::getRequestParam('folder');

        $title = FHtml::getRequestParam('title');

        $file_name = !empty($file_name) ? $file_name : FHtml::getRequestParam('file_name');
        $file_path = !empty($file_path) ? $file_path :FHtml::getRequestParam('file_path');

        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return [
                'title'=> (!empty($title) ? $title : FHtml::t('common', 'Preview')),
                'content'=> $this->renderPartial('file', [
                    'file' => $file, 'file_path' => $file_path, 'file_name' => $file_name, 'object_type' => $object_type, 'object_id' => $object_id, 'folder' => $folder
                ]),
                'footer'=> null

            ];
        } else
            return FHtml::downloadFile($file_name, true);

    }

    public function actionEditor($id = null, $content = null)
    {
        $id = !empty($id) ? $id : FHtml::getRequestParam('id');
        $content = !empty($content) ? $content :FHtml::getRequestParam('content');

        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return [
                'title'=> (!empty($title) ? $title : FHtml::t('common', 'Editor')),
                'content'=> $this->renderPartial('editor', [ 'id' => $id, 'content' => $content ]),
                'footer'=> null
            ];
        }

        return $this->render('editor');
    }

}



