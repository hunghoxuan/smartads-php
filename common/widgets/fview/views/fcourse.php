<?php
use common\components\FHtml;
use frontend\assets\CustomAsset;
use frontend\components\Helper;

/* @var $model \frontend\models\ViewModel */
/* @var $this yii\web\View */
/* @var $category \backend\models\Category */

//$asset = CustomAsset::register($this);
//$baseUrl = $asset->baseUrl;
$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$baseUrl .= '/frontend/themes';
$employees = $model->employees;
$galleries = $model->getGalleries();
$categories = $model->getObjectCategories();
$categories = \common\components\FHtml::getRelatedModels('product', '1', 'cms_blogs', \common\components\FHtml::RELATION_MANY_MANY);

$classes = $model->classes;
$category = $model->category;
?>


    <link rel="stylesheet" href="<?php echo $baseUrl ?>/education/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo $baseUrl ?>/education/assets/css/pages/page_job_inner.css">
    <link rel="stylesheet"
          href="<?php echo $baseUrl ?>/common/assets/plugins/fancybox/source/jquery.fancybox.css">

    <!--=== Block Description ===-->
    <div class="block-description">
        <div class="container">
            <div class="title-box-v2">
                <h2><?php echo $model->name ?></h2>
                <p>Course Description</p>
            </div>
            <div class="row">
                <div class="col-md-7">
                    <div class="left-inner">

                        <h2>Overview</h2>
                        <p><?php echo $model->description ?></p>

                        <hr>

                        <h2>Content</h2>
                        <p><?php echo $model->content ?></p>

                        <hr>

                        <h2>Schedules</h2>
                        <table class="class-detail-table margin-bottom-20">
                            <tbody>
                            <?php
                            if (count($classes) != 0) {
                                ?>
                                <tr>
                                    <th class="col-md-2">Class</th>
                                    <th class="col-md-2">Start Date</th>
                                    <th class="col-md-2">End Date</th>
                                    <th class="col-md-2">Status</th>
                                </tr>
                                <?php
                                /* @var $class \backend\modules\ecommerce\models\ProductsClass */

                                foreach ($classes as $class) { ?>
                                    <tr style="line-height: 40pt">
                                        <td class="col-md-2"><i
                                                class="fa fa-clock-o color-green"></i>&nbsp;<?= $class->name ?></td>
                                        <td class="col-md-2"><?= $class->startDate ?></td>
                                        <td class="col-md-2"><?= $class->endDate ?></td>
                                        <td class="col-md-2"><i class="fa fa-check color-green"></i></td>
                                        <td class="col-md-2">
                                            <button type="button"
                                                    class="btn-sm btn-danger"
                                                    onclick="addToCart(<?php echo $model->id ?>,1,<?php echo $class->id ?>)">
                                                ENROLL
                                            </button>
                                        </td>
                                    </tr>
                                    <?php
                                    foreach ($class->schedules as $schedule) { ?>
                                        <tr>
                                            <td class="col-md-2"><?= $schedule->weekday ?></td>
                                            <td class="col-md-2"><?= $schedule->startTime ?></td>
                                            <td class="col-md-2"><?= $schedule->endTime ?></td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>

                            <?php } else { ?>
                                <tr style="line-height: 40pt">
                                    No class found
                                </tr>
                            <?php } ?>

                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="col-md-5">
                    <div class="right-inner">

                        <h2 class="text-center">Price: <span style="color: #db002b;"><?= $model->price ?>$</span></h2>

                        <h2>Category</h2>
                        <?php if (isset($category)): ?>
                            <a href="<?php echo FHtml::createUrl('/course/list', ['category_id' => $category->id]) ?>"><?= $category->name ?></a>
                        <?php endif ?>
                        <hr>
                        <h2>Tutor</h2>
                        <?php
                        if (count($employees) != 0) {
                            /* @var $employee \backend\modules\ecommerce\models\ProductsEmployee */
                            foreach ($employees as $employee) :
                                ?>
                                <img src="<?= Helper::getImageUrl($employee->employee->image, EMPLOYEE_DIR)
                                ?>" alt="">
                                <div class="overflow-h">
                                    <span class="font-s"><?= $employee->employee->name ?></span>
                                    <p class="color-green">Position: <span
                                            class="hex"><?= $employee->employee->position ?></span></p>
                                    <ul class="social-icons">
                                        <li><a class="social_googleplus" data-original-title="Google Plus"
                                               href="mailto:<?= $employee->employee->email ?>"></a></li>
                                        <li><a class="social_skype" data-original-title="Skype"
                                               href="skype:<?= $employee->employee->skype ?>?call"></a></li>
                                    </ul>
                                </div>
                                <p><?= $employee->employee->description ?></p>
                                <hr>
                                <?php
                            endforeach;
                        } else { ?>
                            <p>No tutor found</p>
                            <hr>
                        <?php } ?>

                    </div>
                </div>
            </div>

            <!-- Three Columns -->
            <div class="text-center margin-bottom-50">
                <h2 class="title-v2 title-center">GALLERY</h2>
            </div>
            <div class="row  margin-bottom-30">
                <?php
                /* @var $galleries array */
                /* @var $gallery \backend\models\Galleries */
                $i = 1;
                foreach ($galleries as $gallery):
                ?>
                <div class="col-sm-3 sm-margin-bottom-30">
                    <a href="<?= \common\components\FHtml::getFileURL($gallery->toBaseModel()->name, PRODUCTS_DIR) ?>"
                       rel="gallery1"
                       class="fancybox img-hover-v1"
                       title="Image <?= $i ?>">
                            <span><?= \common\components\FHtml::showImage($gallery->toBaseModel()->name, 'product') ?>
                            </span>
                    </a>
                </div>
                <?php if ($i % 4 == 0){ ?>
            </div>
            <div class="row margin-bottom-30">
                <?php } ?>
                <?php
                $i += 1;
                endforeach; ?>
            </div>
            <!-- End Three Columns -->

        </div>
    </div>
    <!--=== End Block Description ===-->

<?php $this->registerJsFile($baseUrl . "/common/assets/plugins/fancybox/source/jquery.fancybox.pack.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php $this->registerJsFile($baseUrl . "/common/assets/plugins/fancy-box.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php $this->registerJsFile($baseUrl . "/common/assets/plugins/bootstrap-notify.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>

    <script>
        function addToCart(id, sl, classId) {

            $.ajax({
                url: "<?php echo FHtml::createUrl('cart/cart-ajax') ?>",
                type: "POST",
                data: {
                    id: id,
                    sl: sl,
                    classId: classId,
                    action: 'add'
                },
                cache: false,
                success: function (data) {
                    if (data == 'success') {
                        $.notify({
                            icon: 'fa fa-check-square-o',
                            message: "Add to cart success"
                        }, {
                            delay: 1000,
                            type: "success"
                        });
                        setTimeout(function () {
                            location.reload();
                        }, 1500);
                    } else {
                        $.notify({
                            icon: 'fa fa-check-square-o',
                            message: "Add to cart failed"
                        }, {
                            delay: 1000,
                            type: "danger"
                        });
                    }
                },
                error: function () {
                    $.notify({
                        icon: 'fa fa-check-square-o',
                        message: "Add to cart failed"
                    }, {
                        delay: 1000,
                        type: "danger"
                    });
                }
            })
        }
    </script>


<?php $this->registerJs("$(document).ready(function () {
        FancyBox.initFancybox();
    });"); ?>