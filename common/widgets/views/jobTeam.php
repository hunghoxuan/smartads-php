<?php
use backend\assets\CustomAsset;
use common\components\FHtml;

/* @var $this \yii\web\View */

//Get base url
$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$user = Yii::$app->user->identity;
?>

<div class="row">
    <!-- Team v2 -->
    <div class="col-md-3 col-sm-6">
        <div class="team-v2">
            <img class="img-responsive" src="<?= FHtml::getImageUrl('avatar.jpg', HOSTAGENT_DIR, FRONTEND) ?>" alt=""/>
            <div class="inner-team">
                <h3>Jack Anderson</h3>
                <small class="color-green">Estate Host</small>
                <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, justo sit amet risus etiam porta
                    sem...</p>
                <hr>
                <ul class="list-inline team-social">
                    <li>
                        <a data-placement="top" data-toggle="tooltip" class="fb tooltips" data-original-title="Facebook"
                           href="#">
                            <i class="fa fa-facebook"></i>
                        </a>
                    </li>
                    <li>
                        <a data-placement="top" data-toggle="tooltip" class="tw tooltips" data-original-title="Twitter"
                           href="#">
                            <i class="fa fa-twitter"></i>
                        </a>
                    </li>
                    <li>
                        <a data-placement="top" data-toggle="tooltip" class="gp tooltips"
                           data-original-title="Google plus" href="#">
                            <i class="fa fa-google-plus"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- End Team v2 -->

    <!-- Team v2 -->
    <div class="col-md-3 col-sm-6">
        <div class="team-v2">
            <img class="img-responsive" src="<?= FHtml::getImageUrl('avatar2.jpg', HOSTAGENT_DIR, FRONTEND) ?>" alt=""/>
            <div class="inner-team">
                <h3>Kate Metus</h3>
                <small class="color-green">Sale Agent</small>
                <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, justo sit amet risus etiam porta
                    sem...</p>
                <hr>
                <ul class="list-inline team-social">
                    <li><a data-placement="top" data-toggle="tooltip" class="fb tooltips" data-original-title="Facebook"
                           href="#"><i class="fa fa-facebook"></i></a></li>
                    <li><a data-placement="top" data-toggle="tooltip" class="tw tooltips" data-original-title="Twitter"
                           href="#"><i class="fa fa-twitter"></i></a></li>
                    <li><a data-placement="top" data-toggle="tooltip" class="gp tooltips"
                           data-original-title="Google plus" href="#"><i class="fa fa-google-plus"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- End Team v2 -->

    <!-- Team v2 -->
    <div class="col-md-3 col-sm-6">
        <div class="team-v2">
            <img class="img-responsive" src="<?= FHtml::getImageUrl('avatar.jpg', HOSTAGENT_DIR, FRONTEND) ?>" alt=""/>
            <div class="inner-team">
                <h3>Porta Gravida</h3>
                <small class="color-green">Sale Agent</small>
                <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, justo sit amet risus etiam porta
                    sem...</p>
                <hr>
                <ul class="list-inline team-social">
                    <li>
                        <a data-placement="top" data-toggle="tooltip" class="fb tooltips" data-original-title="Facebook"
                           href="#">
                            <i class="fa fa-facebook"></i>
                        </a>
                    </li>
                    <li>
                        <a data-placement="top" data-toggle="tooltip" class="tw tooltips" data-original-title="Twitter"
                           href="#">
                            <i class="fa fa-twitter"></i>
                        </a>
                    </li>
                    <li>
                        <a data-placement="top" data-toggle="tooltip" class="gp tooltips"
                           data-original-title="Google plus" href="#">
                            <i class="fa fa-google-plus"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- End Team v2 -->

    <!-- Team v2 -->
    <div class="col-md-3 col-sm-6">
        <div class="team-v2">
            <img class="img-responsive" src="<?= FHtml::getImageUrl('avatar2.jpg', HOSTAGENT_DIR, FRONTEND) ?>" alt=""/>
            <div class="inner-team">
                <h3>Donec Elisson</h3>
                <small class="color-green">Estate Host</small>
                <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, justo sit amet risus etiam porta
                    sem...</p>
                <hr>
                <ul class="list-inline team-social">
                    <li>
                        <a data-placement="top" data-toggle="tooltip" class="fb tooltips" data-original-title="Facebook"
                           href="#">
                            <i class="fa fa-facebook"></i></a>
                    </li>
                    <li>
                        <a data-placement="top" data-toggle="tooltip" class="tw tooltips" data-original-title="Twitter"
                           href="#">
                            <i class="fa fa-twitter"></i>
                        </a>
                    </li>
                    <li>
                        <a data-placement="top" data-toggle="tooltip" class="gp tooltips"
                           data-original-title="Google plus" href="#">
                            <i class="fa fa-google-plus"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- End Team v2 -->
</div>
