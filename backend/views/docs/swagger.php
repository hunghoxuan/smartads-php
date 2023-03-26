<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\components\FHtml;

$application_id = FHtml::currentApplicationId();
$root_folder = FHtml::getRootFolder();
$json_url = FHtml::setting('api_swagger_url', FHtml::getRequestParam(['url', 'file']));
//$json_url = 'https://petstore.swagger.io/v2/swagger.json';
$json_file = FHtml::getRootFolder() . "/applications/$application_id/documents/swagger.json";
$result = '';

if (!empty($json_url)) {
    //get from url
    $result = FHtml::getUrlContent($json_url, true);
} else if (is_file($json_file)) {
    //get from file
    $result = \common\components\FFile::readFile($json_file);
} else {
    //auto get from attonation (comments)
    include FHtml::getRootFolder() . "/vendor/zircote/swagger-php/src/functions.php";
    $scanPaths = ["$root_folder/applications/$application_id/backend", "$root_folder/applications/$application_id/actions", "$root_folder/backend/actions", "$root_folder/backend/models", "$root_folder/backend/controllers"];
    $modules = FHtml::getApplicationModulesComboArray();
    foreach ($modules as $module1) {
        $scanPaths =  array_merge($scanPaths, ["$root_folder/backend/modules/$module1/actions", "$root_folder/backend/modules/$module1/models", "$root_folder/backend/modules/$module1/controllers"]);
    }
    foreach ($scanPaths as $i => $scanPath) {
        if (!is_dir($scanPath))
            unset($scanPaths[$i]);
    }
    $openapi = \OpenApi\scan($scanPaths);

    if (empty($openapi->info) || is_string($openapi->info)) {
        $openapi->info = new \OpenApi\Annotations\Info([
            'title' => FHtml::settingCompanyName() . ' (' . FHtml::currentApplicationId() . ')',
            'description' => @"References: https://github.com/swagger-api/swagger-core/wiki/annotations"
        ]);
    }

    if (empty($openapi->servers) || is_string($openapi->servers)) {
        $openapi->servers = [];
        $openapi->servers[] = ['url' => FHtml::getRootUrl() . "backend/web/index.php/", 'description' => 'API Server'];
    }

    $in_code_paths = [];
    if (is_array($openapi->paths)) {
        foreach ($openapi->paths as $path) {
            $in_code_paths = array_merge($in_code_paths, [$path->path => $path]);
        }
    }

//    FHtml::var_dump($in_code_paths);
//    echo key_exists('/api/home', $in_code_paths);
    //die;

    $actions = \backend\models\SettingsApi::findAll([]);

    $paths = [];
    $tags = [];
    $tags_array = [];
    foreach ($actions as $actionModel) {
        if (empty($actionModel->code) || !$actionModel->is_active)
            continue;

        $api_path = str_replace('//', '/', '/' . $actionModel->code);
        $arr = explode('/', $actionModel->code);
        $module = !empty($actionModel->module) ? $actionModel->module : $arr[0];
        if (!in_array($module, $tags_array)) {
            $tags_array[] = $module;
            $tags[] = new \OpenApi\Annotations\Tag(['name' => $module, 'description' => 'Actions (API) of ' . \yii\helpers\BaseInflector::camelize($module) . ' module']);
        }

        if (key_exists($api_path, $in_code_paths)) {
            $path = $in_code_paths[$api_path];
        } else {
            $path = new OpenApi\Annotations\PathItem([
                'path' => $api_path
            ]);
        }
        $method = strtoupper($actionModel->method);
        if (empty($method))
            $method = 'GET';

        if ($method == 'POST') {
            if (empty($path->post) || is_string($path->post)) {
                $path_method = new \OpenApi\Annotations\Post([]);
                $path->post = $path_method;
            } else {
                $path_method = $path->post;
            }
        } else if ($method == 'PUT') {
            if (empty($path->put) || is_string($path->put)) {
                $path_method = new \OpenApi\Annotations\Put([]);
                $path->put = $path_method;
            } else {
                $path_method = $path->put;
            }
        } else if ($method == 'DELETE') {
            if (empty($path->delete) || is_string($path->delete)) {
                $path_method = new \OpenApi\Annotations\Delete([]);
                $path->delete = $path_method;
            } else {
                $path_method = $path->delete;
            }
        } else if ($method == 'PATCH') {
            if (empty($path->patch) || is_string($path->patch)) {
                $path_method = new \OpenApi\Annotations\Patch([]);
                $path->patch = $path_method;
            } else {
                $path_method = $path->patch;
            }
        } else {
            if (empty($path->get) || is_string($path->get)) {
                $path_method = new \OpenApi\Annotations\Get([]);
                $path->get = $path_method;
            } else {
                $path_method = $path->get;
            }
        }
        
        if (isset($path_method) && is_object($path_method)) {
            if (empty($path_method->method))
                $path_method->method = $method;
            if (empty($path_method->path))
                $path_method->path = $path->path;
            //if (empty($path_method->tags))
                $path_method->tags = [$module];
            if (empty($path_method->produces))
                $path_method->produces = ['application/json'];
            if (empty($path_method->summary) || !empty($actionModel->summary))
                $path_method->summary = $actionModel->summary;
            if (empty($path_method->description) || !empty($actionModel->description))
                $path_method->description = $actionModel->description;

            $arr1 = [];
            if (empty($path_method->responses) || is_string($path_method->responses)) {
                $path_method->responses = [];
                $arr1 = [];
            } else {
                foreach ($path_method->responses as $response) {
                    $arr1[] = $response->response;
                }
            }
            $arr = [FHtml::ERROR_OK, FHtml::ERROR_NOT_FOUND];
            foreach ($arr as $error_code) {
                if (in_array($error_code, $arr1))
                    continue;
                $path_method->responses[] = new \OpenApi\Annotations\Response(['response' => $error_code, 'description' => \common\components\FError::getErrorMessage($error_code)]);
            }
            if (!empty($actionModel->responses) && is_array($actionModel->responses)) {
                foreach ($actionModel->responses as $response) {
                    $path_method->responses[] = new \OpenApi\Annotations\Response(['response' => $response['code'], 'description' => isset($response['description']) ? $response['description'] : '']);
                }
            }

            if (empty($path->parameters) || is_string($path->parameters)) {
                if (!empty($actionModel->parameters)) {
                    foreach ($actionModel->parameters as $parameter) {
                        if (!isset($parameter['name']))
                            continue;
                        $path->parameters = [];
                        $path->parameters[] = new \OpenApi\Annotations\Parameter([
                            'name' => $parameter['name'],
                            'required' => $parameter['required'],
                            'in' => isset($parameter['in']) ? $parameter['in'] : 'query',
                            'type' => isset($parameter['type']) ? $parameter['type'] : 'string',
                            'format' => isset($parameter['format']) ? $parameter['format'] : null,
                            'description' => isset($parameter['description']) ? $parameter['description'] : '',
                            'schema' => new \OpenApi\Annotations\Schema(['type' => isset($parameter['schema']) ? $parameter['schema'] : 'string'])]);
                    }
                }
            }
        }

        $paths[] = $path;
    }
    $openapi->paths = $paths;
    $openapi->tags = $tags;


    $result = $openapi->toJson();
    //FHtml::var_dump($openapi);

}

echo $result;
die;

?>