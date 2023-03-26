<?php
/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "SmartscreenSchedules".
 */

namespace backend\modules\smartscreen\controllers;

use backend\modules\smartscreen\models\SmartscreenCampaigns;
use backend\modules\smartscreen\models\SmartscreenContent;
use backend\modules\smartscreen\models\SmartscreenFile;
use backend\modules\smartscreen\models\SmartscreenFrame;
use backend\modules\smartscreen\models\SmartscreenLayouts;
use backend\modules\smartscreen\models\SmartscreenStationAPI;
use backend\modules\smartscreen\Smartscreen;
use Yii;
use backend\modules\smartscreen\models\SmartscreenSchedules;
use backend\modules\smartscreen\models\SmartscreenSchedulesSearch;
use backend\controllers\AdminController;
use yii\console\Exception;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Html;
use yii\helpers\Json;
use common\components\AccessRule;
use common\models\User;
use yii\filters\AccessControl;
use common\components\FHtml;

/**
 * SmartscreenSchedulesController implements the CRUD actions for SmartscreenSchedules model.
 */
class SmartscreenSchedulesController extends SmartscreenController
{
	protected $moduleName  = 'SmartscreenSchedules';
	protected $moduleTitle = 'Smartscreen Schedules';
	protected $moduleKey   = 'smartscreen_schedules';
	protected $object_type = 'smartscreen_schedules';

	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return FHtml::getControllerBehaviours([
			'verbs'  => [
				'class'   => VerbFilter::className(),
				'actions' => [
					'delete'      => ['post'],
					'bulk-delete' => ['post'],
				],
			],
			'access' => [
				'class'      => AccessControl::className(),
				'ruleConfig' => [
					'class' => AccessRule::className(),
				],
				'only'       => ['create', 'update', 'delete', 'view', 'index'],
				'rules'      => [
					[
						'actions' => ['view', 'index', 'create'],
						'allow'   => true,
						'roles'   => [
							User::ROLE_USER,
							User::ROLE_MODERATOR,
							User::ROLE_ADMIN
						],
					],
					[
						'actions' => ['update', 'delete'],
						'allow'   => true,
						'roles'   => [
							User::ROLE_MODERATOR,
							User::ROLE_ADMIN,
                            User::ROLE_USER,
                        ],
					],
				],
			],
		]);
	}

	/**
	 * Lists all SmartscreenSchedules models.
	 * @return mixed
	 */
	public function actionIndex() {
        if (!empty($_POST)) {
            $params = Smartscreen::getCurrentParams(['index'], 'SmartscreenSchedulesSearch', null, ['date', 'date_end', 'show_all']);

            $url = Url::to($params);
            $url = FHtml::createFormalizedBackendLink($url);

            $response = Yii::$app->getResponse();
            return $response->redirect(Url::to($url), 302);
        }

        $searchModel = SmartscreenSchedulesSearch::createNew();
        $searchModel->load(Yii::$app->request->post());

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		if (Yii::$app->request->post('hasEditable')) {
			$Id = Yii::$app->request->post('editableKey');
			$model = SmartscreenSchedules::findOne($Id);

			$out = Json::encode(['output' => '', 'message' => '']);

			$post                         = [];
			$posted                       = current($_POST['SmartscreenSchedules']);
			$post['SmartscreenSchedules'] = $posted;

			if ($model->load($post)) {
			    die;
				$model->save();
				$output = '';
				$out    = Json::encode(['output' => $output, 'message' => '']);
			}
			echo $out;

			return;
		}

		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}


	/**
	 * Displays a single SmartscreenSchedules model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id) {
		$request = Yii::$app->request;

		$model = $this->findModel($id);
		$type  = FHtml::getFieldValue($model, 'type');

		if ($request->isAjax) {
			Yii::$app->response->format = Response::FORMAT_JSON;

			return [
				'title'   => FHtml::t($this->moduleName) . " #" . $id,
				'content' => $this->renderPartial('view', [
					'model' => $model
				]),
				'footer'  => Html::a(FHtml::t('Update'), ['update', 'id' => $id], [
						'class' => 'btn btn-primary pull-left',
						'role'  => $this->view->params['displayType']
					]) . Html::button(FHtml::t('Close'), ['class' => 'btn btn-default', 'data-dismiss' => "modal"])
			];
		}
		else {
			return $this->render('view', ['model' => $model]);
		}
	}

	/**
	 * Creates a new SmartscreenSchedules model.
	 * For ajax request will return json object
	 * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate($type = false) {
		$request = Yii::$app->request;

		$model = $this->createModel($this->object_type);

		if ($request->isAjax) {
			return FHtml::saveModelAjax($this, $model, null);
		}
		else {
			if ($model->load($request->post())) {
                $model->id = null;

				$smart_schedules = $_REQUEST['SmartscreenSchedules'];
				$devices         = isset($smart_schedules['device_id']) ? $smart_schedules['device_id'] : [];

				if (key_exists('layout_id', $_REQUEST['SmartscreenSchedules'])) {
					$data    = $_REQUEST['SmartscreenSchedules']['layout_id'];
					$mode_id = '';

					if (!empty($data)) {
						try {
							foreach ($data as $item) {
								if (!key_exists('content', $item) || empty($item['content'])) {
									continue;
								}
								if (!key_exists('layout', $item) || empty($item['layout'])) {
									continue;
								}

								$time = isset($item['start_time']) ? $item['start_time'] : $model->start_time;

								$schedule             = new SmartscreenSchedules;
								$schedule->device_id  = $devices;
								$schedule->layout_id  = isset($item['layout']) ? $item['layout'] : 0;;
								$schedule->start_time = $time;
								$schedule->date       = $model->date;
								$schedule->date_end   = $model->date_end;
								$schedule->days       = $model->days;

                                $schedule->channel_id = $model->channel_id;
								$schedule->type       = $model->type;
								$schedule->content_id = isset($item['content']) ? $item['content'] : '';
								$schedule->{SmartscreenSchedules::FIELD_STATUS}  = 1;

                                $kind = isset($value['file_kind']) ? $item['file_kind'] : '';
                                if ($kind == SmartscreenSchedules::DURATION_KIND_MINUTES) {
                                    //
                                    $schedule->duration = $item['file_duration'];
                                } elseif ($kind == SmartscreenSchedules::DURATION_KIND_SECOND)  {
                                    $schedule->duration = $item['file_duration'] / 60;
                                } elseif ($kind == SmartscreenSchedules::DURATION_KIND_LOOP)  {
                                    $schedule->duration = 0;
                                }

								$schedule->checkFormResubmission(false);

								if ($schedule->save()) {
									if ($schedule->date == Smartscreen::Today() || empty($schedule->date)) {
										self::callSocket($schedule, Smartscreen::REFRESH_SCHEDULE_NOW);
									}
									$model_id = $model->id;
								}
								else {
									FHtml::addError($model->errors);
								}
							}

						} catch (Exception $e) {
							FHtml::addError($e);
						}
					}
				}
				elseif (key_exists('list_content', $_REQUEST['SmartscreenSchedules'])) {
					$data    = $_REQUEST['SmartscreenSchedules']['list_content'];

				}
				else {
					$model->save();

					return $this->redirect(['update', 'id' => $model->id]);
				}

				if ($this->saveType() == 'save' && !empty($model_id)) {
					$device_id = json_encode($smart_schedules['device_id']);
					$date      = $smart_schedules['date'];
					$layout_id = json_encode($smart_schedules['layout_id']);

					return $this->redirect(['update', 'id' => $model_id]);
				}

				return $this->redirect(['index']);


			}
			else {
				return $this->render('create', ['model' => $model]);
			}
		}
	}

	/**
	 * Updates an existing SmartscreenSchedules model.
	 * For ajax request will return json object
	 * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id = '') {
		$request = Yii::$app->request;
		$model   = $this->findModel($id);

		$ime                  = FHtml::getRequestParam('device_id', $model->device_id);
		$date                 = FHtml::getRequestParam('date', $model->date);
		$start_time           = null;
		$finished_schedule_id = null;
		$channel_id           = Smartscreen::getCurrentChannelId($model);
		$schedule_id          = null;
		$limit                = -1;
		$action = FHtml::getRequestParam('action');

		if (!empty($id)) {
			$listSchedule = [$model];
		}
		else {
			$listSchedule = Smartscreen::getDeviceSchedules($ime, $date, $start_time, $finished_schedule_id, $channel_id, $schedule_id, $limit);
			$listSchedule = Smartscreen::getDeviceSchedulesByDate($listSchedule, $date);
		}

		$schedule = [];

		if ($action == 'cancel') {
		    if (empty($model->start_time)) {
		        $this->actionDelete($model->id);
            }
            return $this->redirect(Smartscreen::getCurrentParams(['index']));
        }

		if (!is_array($listSchedule)) {
			return $this->redirect(Smartscreen::getCurrentParams(['index']));
		}

		foreach ($listSchedule as $item) {
			$schedule[$item->id] = array(
				'id'         => $item->id,
				'start_time' => !empty($item->start_time) ? (is_numeric($item->start_time) ? date('g:i A', $item->start_time) : $item->start_time) : '',
				'layout'     => is_numeric($item->layout_id) ? $item->layout_id : null,
				SmartscreenSchedules::FIELD_STATUS       => $item->{SmartscreenSchedules::FIELD_STATUS},
                'duration'       => $item->duration,
				'file_duration'   => $item->duration,
                'file_kind'       => $item->kind
			);
		}

		$model->layout_id = $schedule;

		if ($request->isAjax) {
			return FHtml::saveModelAjax($this, $model, null);
		}
		else {

			if ($model->load($request->post())) {
			    $campaign_id = $model->campaign_id;
			    $campaign = null;
			    //update information base on current campaign
			    if (!empty($campaign_id)) {
			        $campaign = SmartscreenCampaigns::findOne($campaign_id);
			        Smartscreen::updateCampaignSchedules($campaign, $model);
                }

                $times = isset($_REQUEST['SmartscreenSchedules']['_times']) ? $_REQUEST['SmartscreenSchedules']['_times'] : [];
                $content_name = isset($_REQUEST['SmartscreenSchedules']['name']) ? $_REQUEST['SmartscreenSchedules']['name'] : '';
                $content_id = isset($_REQUEST['SmartscreenSchedules']['_content_id']) ? $_REQUEST['SmartscreenSchedules']['_content_id'] : '';

                if (empty($times)) {
                    $times = [['_start_time' => $model->start_time,'_end_time' => $model->end_time, '_duration' => $model->duration]];
                }
                if (empty($model->start_time) && empty($times))
                {
                    FHtml::addError('Must set Start time !!');
                    return $this->render('update', ['model' => $model]);
                }
				if (key_exists('layout_id', $_REQUEST['SmartscreenSchedules'])) { //advance mode
					$data = $_REQUEST['SmartscreenSchedules']['layout_id'];

					if (!empty($data)) {
						try {
							$arr = [];
							foreach ($data as $key => $value) {
							    foreach ($times as $timeIdx => $timeItem) {
							        //var_dump($_REQUEST); die;
                                    $schedule = null;
                                    if (!empty($value['id']) && $timeIdx == 0) {
                                        $arr[] = $value['id'];
                                        $schedule = SmartscreenSchedules::findOne($value['id']);
                                    }
                                    if (!isset($schedule)) {
                                        $schedule = new SmartscreenSchedules;
                                    }

                                    $content_id = isset($value['content']) ? $value['content'] : [];

                                    $time = $timeItem['_start_time'];
                                    $duration = $timeItem['_duration'];
                                    $end_time = $timeItem['_end_time'];
                                    if (!empty($end_time) && empty($duration))
                                        $duration = Smartscreen::getDurationBetween($time, 0, $end_time);

                                    $schedule->device_id = $model->device_id;
                                    $schedule->campaign_id = $model->campaign_id;
                                    $schedule->layout_id = isset($value['layout']) ? $value['layout'] : 0;
                                    $schedule->start_time = $time;
                                    $schedule->end_time = $end_time;
                                    $schedule->date = $model->date;
                                    $schedule->content_id = $content_id;
                                    $schedule->type = $model->type;
                                    $schedule->channel_id = $model->channel_id;
                                    $schedule->days = $model->days;
                                    $schedule->date_end = $model->date_end;
                                    $schedule->{SmartscreenSchedules::FIELD_STATUS} = $model->{SmartscreenSchedules::FIELD_STATUS};

                                    $kind = isset($value['file_kind']) ? $value['file_kind'] : '';
                                    if ($kind == SmartscreenSchedules::DURATION_KIND_MINUTES) {
                                        $schedule->duration = $value['file_duration'];
                                    } else if ($kind == SmartscreenSchedules::DURATION_KIND_SECOND) {

                                        $schedule->duration = $value['file_duration'];
                                    } elseif ($kind == SmartscreenSchedules::DURATION_KIND_LOOP) {
                                        $schedule->duration = $duration;
                                    } else { //if hidden
                                        $schedule->duration = $duration;
                                    }
                                    $schedule->checkFormResubmission(false);

                                    if (empty($schedule->start_time)) {
                                        continue;
                                    }

                                    $schedule->save();

                                    $model = $schedule;
                                }
							}

							foreach ($listSchedule as $schedule) {
								if (!in_array($schedule->id, $arr)) {
									$schedule->delete();
								}
							}

							if ($schedule->date == Smartscreen::Today() || empty($schedule->date)) {
								self::callSocket($schedule, Smartscreen::REFRESH_SCHEDULE_NOW);
							}

						} catch (Exception $e) {
						    //die;
							FHtml::addError($e);
						}
					}
				}
				elseif (key_exists('list_content', $_REQUEST['SmartscreenSchedules'])) {
					$list_content = key_exists('list_content', $_REQUEST['SmartscreenSchedules']) ? $_REQUEST['SmartscreenSchedules']['list_content'] : null;
                    if (empty($model->start_time) && !empty($times)) {
                        $smartfiles = [];
                        $changeContentId = (!empty($content_id) && $model->content_id != $content_id);

                        if (!empty($list_content) && !$changeContentId) {
                            if ((!empty($content_name) || !empty($model->content_id))) {
                                $content_id = $model->content_id;
                                if (is_numeric($content_id))
                                    $content = SmartscreenContent::findOne($content_id);
                                else {
                                    $content = new SmartscreenContent();
                                    $content->type = SmartscreenContent::TYPE_SLIDE;
                                }
                                if (!empty($content_name))
                                    $content->title = $content_name;
                                $content->save();
                                Smartscreen::updateSmartFile($list_content, $content->id, $content, $model);
                                $smartfiles = $content->smartscreenFiles();
                            }
                        }

                        foreach ($times as $timeIdx => $timeItem) {
                            $schedule = null;
                            if (!empty($value['id']) && $timeIdx == 0) {
                                $arr[] = $value['id'];
                                $schedule = SmartscreenSchedules::findOne($value['id']);
                            }
                            if (!isset($schedule)) {
                                $schedule = new SmartscreenSchedules;
                            }

                            $time = $timeItem['_start_time'];
                            $duration = $timeItem['_duration'];
                            $end_time = $timeItem['_end_time'];
                            if (!empty($end_time))
                                $duration = Smartscreen::getDurationBetween($time, 0, $end_time);

                            $schedule->campaign_id = $model->campaign_id;
                            $schedule->device_id = $model->device_id;
                            $schedule->layout_id = isset($value['layout']) ? $value['layout'] : 0;
                            $schedule->start_time = $time;
                            $schedule->end_time = $end_time;

                            $schedule->date = $model->date;
                            $schedule->content_id = isset($content) ? $content->id : $content_id;
                            $schedule->type = $model->type;
                            $schedule->channel_id = $model->channel_id;
                            $schedule->days = $model->days;
                            $schedule->date_end = $model->date_end;
                            $schedule->{SmartscreenSchedules::FIELD_STATUS} = $model->{SmartscreenSchedules::FIELD_STATUS};

                            $kind = isset($value['file_kind']) ? $value['file_kind'] : '';
                            if ($kind == SmartscreenSchedules::DURATION_KIND_MINUTES) {
                                $schedule->duration = $value['file_duration'];
                            } elseif ($kind == SmartscreenSchedules::DURATION_KIND_LOOP) {
                                $schedule->duration = $duration;
                            } else { //if hidden
                                $schedule->duration = $duration;
                            }

                            $schedule->checkFormResubmission(false);

                            if (!empty($schedule->start_time)) {
                                $saveSchedule = $schedule->save();

                                if (!empty($list_content) && !isset($content)) {
                                    Smartscreen::updateSmartFile(!empty($smartfiles) ? $smartfiles : $list_content, $schedule->id, $schedule);
                                }
                                if (empty($smartfiles))
                                    $smartfiles = $schedule->smartscreenFiles();
                                $model = $schedule;
                            }
                        }


                    } else {
                        $model->layout_id = !empty($content_name) ? $content_name : "";
                        $changeContentId = (!empty($content_id) && $model->content_id != $content_id);
                        $model->content_id = $content_id;
                        $model->save(); //must save before updateSmartFile :)
                        if (!empty($list_content) && !$changeContentId) {
                            if ((!empty($content_name) || !empty($model->content_id))) {
                                $content_id = $model->content_id;
                                if (is_numeric($content_id) && !empty($content_id))
                                    $content = SmartscreenContent::findOne($content_id);
                                if (!isset($content)) {
                                    $content = new SmartscreenContent();
                                    $content->type = SmartscreenContent::TYPE_SLIDE;
                                }
                                if (!empty($content_name))
                                    $content->title = $content_name;
                                $content->save();
                                Smartscreen::updateSmartFile($list_content, $content->id, $content, $model);
                                if (!empty($content->id)) {
                                    \Yii::$app->db->createCommand("UPDATE smartscreen_schedules SET content_id = $content->id WHERE id = $model->id")
                                        ->execute();
                                }
                            } else {
                                Smartscreen::updateSmartFile($list_content, $model->id, $model);
                            }
                        }
                    }
				}

				if ($this->saveType() == 'save') {
					return $this->redirect(Smartscreen::getCurrentParams(['update', 'id' => $model->id]));
				}

				return $this->redirect(Smartscreen::getCurrentParams(['index']));

			}
			else {

				return $this->render('update', ['model' => $model]);
			}

		}
	}

	/**
	 * Delete an existing SmartscreenSchedules model.
	 * For ajax request will return json object
	 * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id) {
		$request = Yii::$app->request;

		$this->findModel($id)->delete();

		if ($request->isAjax) {
			Yii::$app->response->format = Response::FORMAT_JSON;

			return ['forceClose' => true, 'forceReload' => '#' . $this->getPjaxContainerId()];
		}
		else {
			return $this->redirect(['index']);
		}
	}

	/**
	 * Delete multiple existing SmartscreenSchedules model.
	 * For ajax request will return json object
	 * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionBulkDelete() {
		$request = Yii::$app->request;

		$pks = explode(',', $request->post('pks')); // Array or selected records primary keys
		foreach ($pks as $pk) {
		    if (!is_numeric($pk))
		        continue;
			$model = FHtml::findOne($this->object_type, $pk);
			if (isset($model)) {
				$model->delete();
			}
		}

		if ($request->isAjax) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ['forceClose' => true, 'forceReload' => '#' . $this->getPjaxContainerId()];
		}
		else {
			return $this->redirect(['index']);
		}
	}

	public function actionBulkAction($action = '', $field = '', $value = '') {
		$request = Yii::$app->request;

		$pks = explode(',', $request->post('pks')); // Array or selected records primary keys
		foreach ($pks as $pk) {
			$model = FHtml::findOne($this->object_type, $pk);
			if (isset($model)) {
				if ($action == 'change') {
					FHtml::setFieldValue($model, $field, $value);
					$model->save();
				}
			}
		}

		if ($request->isAjax) {
			Yii::$app->response->format = Response::FORMAT_JSON;

			return ['forceClose' => true, 'forceReload' => '#' . $this->getPjaxContainerId()];
		}
		else {
			return $this->redirect(['index']);
		}
	}


	/**
	 * Finds the SmartscreenSchedules model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return SmartscreenSchedules the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		$model = parent::findModel($id);
		return $model;
	}

	protected function createModel($className = '', $id = '', $params = null) {
		$model = parent::createModel($className, $id, $params);

		//$model->start_time = null;
		return $model;
	}


	public function actionLayoutContent() {
		$action = $_REQUEST['action'];
	}


}
