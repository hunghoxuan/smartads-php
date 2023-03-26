<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\controllers;


use common\components\FHtml;
use common\components\FModel;
use common\controllers\BaseAdminController;
use yii\base\Exception;
use yii\helpers\BaseInflector;


/**
 * Controller is the base class for RESTful API controller classes.
 *
 * Controller implements the following steps in a RESTful API request handling cycle:
 *
 * 1. Resolving response format (see [[ContentNegotiator]]);
 * 2. Validating request method (see [[verbs()]]).
 * 3. Authenticating user (see [[\yii\filters\auth\AuthInterface]]);
 * 4. Rate limiting (see [[RateLimiter]]);
 * 5. Formatting response data (see [[serializeData()]]).
 *
 * @author Hung Ho (Steve) | www.apptemplate.co, wwww.moza-tech.com, www.codeyii.com | skype: hung.hoxuan  <hung.hoxuan@gmail.com>
 * @since 2.0
 */
class BackendController extends AdminController
{

}
