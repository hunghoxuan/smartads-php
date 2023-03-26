<?php

namespace common\controllers;

use common\components\FHtml;
use yii\helpers\Url;
use Yii;
use yii\base\InlineAction;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use yii\helpers\StringHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;


class BaseController extends Controller
{
	public function goBack($defaultUrl = null) {
        if (empty($defaultUrl)) {
			if (!is_string(\Yii::$app->request->referrer)) {
				parent::goHome();
			}
			else {
				if (!empty(\Yii::$app->request->referrer)) {
					Yii::$app->getResponse()->redirect(\Yii::$app->request->referrer);
				}
				else {
					parent::goBack(null);
				}
			}
		}
		else {
			Yii::$app->getResponse()->redirect($defaultUrl);
		}
	}

	public function checkAccess() {
		// need to be modified for security
		return true;
	}

	/**
	 * @return void|\yii\web\Response
	 */
	public function refreshPage() {
		$this->goBack();
	}

	protected function getController() {
		return $this->getUniqueId();
	}

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $result = parent::beforeAction($action);
        return $result;
    }

	public function createAction($id) {
        if ($id === '') {
			$id = $this->defaultAction;
		}

		$action_name           = BaseInflector::camelize($id);
		$controller_name       = BaseInflector::camelize($this->id);
		$application_namespace = FHtml::getApplicationNamespace();

		$actionMap      = $this->actions();
		$zone           = FHtml::currentZone();
		$current_module = FHtml::currentModule();

		if (isset($actionMap[$id])) { // if already defined in actions()
            $arr_item = $actionMap[$id];

			$action1  = $arr_item['class'];
			$action_name = last(explode('\\', $action1));
            $action2 = $application_namespace . '\\actions\\' . $action_name;
            $action3 = $application_namespace . '\\' . $action1;
            $action4 = 'backend\\modules\\' . $current_module . '\\actions\\' . $action_name;
            $action5 = 'backend\\actions\\' . $action_name;

            $actions        = [$action3, $action2, $action4, $action1, $action5];

            foreach ($actions as $action) {
                if (class_exists($action)) {
                    $arr_item['class'] = $action;
                    break;
                }
            }

			return Yii::createObject($arr_item, [$id, $this]);
		}
        else if ($this->id == 'api') { // if not defined, try to look for Action in actions folder

            $action2        = $application_namespace . '\\actions\\' . $action_name . 'Action';
            $action1        = $application_namespace . '\\' . $zone . '\\modules\\' . $current_module . '\\actions\\' . $action_name . 'Action';
            $action3        = 'backend\\modules\\' . $current_module . '\\actions\\' . $action_name . 'Action';
            $action4        = 'backend\\actions\\' . $action_name . 'Action';

            $actions        = [$action1, $action2, $action3, $action4];

            $controller_actions = FHtml::getApiControllerActions();

            if (!empty($controller_actions) && !key_exists($id, $controller_actions)) {
                header('Content-Type: application/json');
                $data = FHtml::getOutputForAPI(null, FHtml::ERROR, "Action not found.");
                echo json_encode($data, JSON_PRETTY_PRINT);
                die;
            }

            foreach ($actions as $action) {
                if (class_exists($action)) {
                    return \Yii::createObject(['class' => $action, 'checkAccess' => [$this, 'checkAccess']], [$id, $this]);
                }
            }
		}

        //Inline Action (in normal Controller)
		$controller_Class = FHtml::getApplicationNamespace() . '\\' . $zone . '\\modules\\' . $current_module . '\\controllers\\' . $controller_name . 'Controller';
		if (class_exists($controller_Class)) {
			$controller = \Yii::createObject($controller_Class, [$this->id, $this->module]);
			$actionMap  = $controller->actions();
			//echo $controller_Class; echo '<br/>'; var_dump($actionMap); die;
			if (isset($actionMap[$id])) {
				return \Yii::createObject($actionMap[$id], [$id, $this]);
			}
		}


        if (preg_match('/^[a-z0-9\\-_]+$/', $id) && strpos($id, '--') === false && trim($id, '-') === $id) {

			$methodName       = 'action' . str_replace(' ', '', ucwords(implode(' ', explode('-', $id))));
			$controller_Class = $application_namespace . '\\' . $zone . '\\modules\\' . $current_module . '\\controllers\\' . $controller_name . 'Controller';
			if (class_exists($controller_Class)) {
				$controller = \Yii::createObject($controller_Class, [$this->id, $this->module]);
				if (method_exists($controller, $methodName)) {
					$method = new \ReflectionMethod($controller, $methodName);
					if ($method->isPublic() && $method->getName() === $methodName) {
                        return new InlineAction($id, $controller, $methodName);
					}
				}
			}

            // create controller from application out site modules
			// long added
			$controller_Class = FHtml::getApplicationNamespace() . '\\' . $zone . '\\controllers\\' . $controller_name . 'Controller';
			if (class_exists($controller_Class)) {
				$controller = \Yii::createObject($controller_Class, [$this->id, $this->module]);
				if (method_exists($controller, $methodName)) {
					$method = new \ReflectionMethod($controller, $methodName);
					if ($method->isPublic() && $method->getName() === $methodName) {
						return new InlineAction($id, $controller, $methodName);
					}
				}
			}
		}

        $result = parent::createAction($id); // TODO: Change the autogenerated stub

        if (isset($result)) {
            return $result;
		}

		//No Action
		if (!is_object($result)) {
			if ($this->id == 'api') {
				header('Content-Type: application/json');
				$data = FHtml::getOutputForAPI(null, FHtml::ERROR, "Action not found.");
				echo json_encode($data, JSON_PRETTY_PRINT);
				die;
			}
			$this->goHome();
		}

		return $result;
	}

    public function render($view, $params = [])
    {
        $pr = FHtml::RequestParams(['id', 'product_id']);
        $type = FHtml::getRequestParam(['output']);
        $params = ArrayHelper::merge($params, $pr);

        if ($type == 'json') {
            header('Content-Type: application/json');
            $data = [];
            //$data = FHtml::getOutputForAPI($params, $this->id);
            echo json_encode($data, JSON_PRETTY_PRINT);
            die;
        } else {
            $view = FHtml::findView($view);
            $this->module = Yii::$app->controller->module;

            return parent::render($view, $params); // TODO: Change the autogenerated stub
        }
    }


    public function renderPartial($view, $params = [])
    {
        $pr = FHtml::RequestParams(['id', 'product_id']);
        $params = ArrayHelper::merge($params, $pr);

        $view = FHtml::findView($view);
        $this->module = Yii::$app->controller->module;
        return parent::renderPartial($view, $params); // TODO: Change the autogenerated stub
    }

    //2017/3/29
    public function redirect($url, $statusCode = 302)
    {
        $return_url = FHtml::getReturnUrl();

        if (!empty($return_url) && is_array($url) && $url[0] == 'index') {
            return parent::redirect($return_url);
        }

        if (is_string($url)) {
            if (StringHelper::startsWith($url, 'http')) {
                return parent::redirect($url);
            }

            $url = [$url];
        }

        if (is_array($statusCode)) {
            $params = FHtml::RequestParams(array_merge(['id'], $statusCode));
        } else {
            $params = FHtml::RequestParams(['id']);
        }

        if (!empty($params)) {
            foreach ($params as $key => $value) {
                if (!key_exists($key, $url))
                    $url = array_merge($url, [$key => $value]);
            }
        }

        $url = Url::to($url);
        $url = FHtml::createFormalizedBackendLink($url);

        if (!is_numeric($statusCode))
            $statusCode = 302;

        $response = Yii::$app->getResponse();
        $result = $response->redirect(Url::to($url), $statusCode);

        return $result;
    }
}

