<?php
use backend\assets\CustomAsset;

/* @var $this \yii\web\View */

//Get base url
$asset = CustomAsset::register($this);
$baseUrl = $asset->baseUrl;
$user = Yii::$app->user->identity;
?>

<link rel="stylesheet" type="text/css"
      href="<?php echo $baseUrl ?>/frontend/themes/common/assets/plugins/image-hover/css/img-hover.css">


<div class="container content job-partners">
    <div class="title-box-v2">
        <h2>Our <span class="color-green">Featured</span> Partners</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
            dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex
            ea commodo consequat.</p>
    </div>

    <ul class="list-inline our-clients" id="effect-2">
        <li>
            <figure>
                <img src="<?php echo $baseUrl ?>/frontend/themes/unify/assets/img/clients2/ea-canada.png" alt="">
                <div class="img-hover">
                    <h4>Ea Canada</h4>
                </div>
            </figure>
        </li>
        <li>
            <figure>
                <img src="<?php echo $baseUrl ?>/frontend/themes/unify/assets/img/clients2/inspiring.png" alt="">
                <div class="img-hover">
                    <h4>Inspiring</h4>
                </div>
            </figure>
        </li>
        <li>
            <figure>
                <img src="<?php echo $baseUrl ?>/frontend/themes/unify/assets/img/clients2/ucweb.png" alt="">
                <div class="img-hover">
                    <h4>UcWeb</h4>
                </div>
            </figure>
        </li>
        <li>
            <figure>
                <img src="<?php echo $baseUrl ?>/frontend/themes/unify/assets/img/clients2/clarks.png" alt="">
                <div class="img-hover">
                    <h4>Clarks</h4>
                </div>
            </figure>
        </li>
        <li>
            <figure>
                <img src="<?php echo $baseUrl ?>/frontend/themes/unify/assets/img/clients2/corepreserves.png" alt="">
                <div class="img-hover">
                    <h4>Core Preserves</h4>
                </div>
            </figure>
        </li>
        <li>
            <figure>
                <img src="<?php echo $baseUrl ?>/frontend/themes/unify/assets/img/clients2/finals.png" alt="">
                <div class="img-hover">
                    <h4>USL Champions</h4>
                </div>
            </figure>
        </li>
        <li>
            <figure>
                <img src="<?php echo $baseUrl ?>/frontend/themes/unify/assets/img/clients2/getaround.png" alt="">
                <div class="img-hover">
                    <h4>GetAround</h4>
                </div>
            </figure>
        </li>
        <li>
            <figure>
                <img src="<?php echo $baseUrl ?>/frontend/themes/unify/assets/img/clients2/baderbrau.png" alt="">
                <div class="img-hover">
                    <h4>Baderbrau</h4>
                </div>
            </figure>
        </li>
        <li>
            <figure>
                <img src="<?php echo $baseUrl ?>/frontend/themes/unify/assets/img/clients2/emirates.png" alt="">
                <div class="img-hover">
                    <h4>Emirates</h4>
                </div>
            </figure>
        </li>
        <li>
            <figure>
                <img src="<?php echo $baseUrl ?>/frontend/themes/unify/assets/img/clients2/fddw.png" alt="">
                <div class="img-hover">
                    <h4>Field Days</h4>
                </div>
            </figure>
        </li>
        <li>
            <figure>
                <img src="<?php echo $baseUrl ?>/frontend/themes/unify/assets/img/clients2/district-karaoke.png" alt="">
                <div class="img-hover">
                    <h4>District Karaoke</h4>
                </div>
            </figure>
        </li>
        <li>
            <figure>
                <img src="<?php echo $baseUrl ?>/frontend/themes/unify/assets/img/clients2/marianos.png" alt="">
                <div class="img-hover">
                    <h4>Mariano's</h4>
                </div>
            </figure>
        </li>
        <li>
            <figure>
                <img src="<?php echo $baseUrl ?>/frontend/themes/unify/assets/img/clients2/grifting-tree.png" alt="">
                <div class="img-hover">
                    <h4>The Grifting Tree</h4>
                </div>
            </figure>
        </li>
        <li>
            <figure>
                <img src="<?php echo $baseUrl ?>/frontend/themes/unify/assets/img/clients2/jaguar.png" alt="">
                <div class="img-hover">
                    <h4>Jaguar</h4>
                </div>
            </figure>
        </li>
        <li>
            <figure>
                <img src="<?php echo $baseUrl ?>/frontend/themes/unify/assets/img/clients2/hermes.png" alt="">
                <div class="img-hover">
                    <h4>Hermes</h4>
                </div>
            </figure>
        </li>
        <li>
            <figure>
                <img src="<?php echo $baseUrl ?>/frontend/themes/unify/assets/img/clients2/starbucks.png" alt="">
                <div class="img-hover">
                    <h4>Starbucks</h4>
                </div>
            </figure>
        </li>
        <li>
            <figure>
                <img src="<?php echo $baseUrl ?>/frontend/themes/unify/assets/img/clients2/national-geographic.png" alt="">
                <div class="img-hover">
                    <h4>National Geographic</h4>
                </div>
            </figure>
        </li>
        <li>
            <figure>
                <img src="<?php echo $baseUrl ?>/frontend/themes/unify/assets/img/clients2/much-more.png" alt="">
                <div class="img-hover">
                    <h4>Much More</h4>
                </div>
            </figure>
        </li>
        <li>
            <figure>
                <img src="<?php echo $baseUrl ?>/frontend/themes/unify/assets/img/clients2/hotiron.png" alt="">
                <div class="img-hover">
                    <h4>Hotiron</h4>
                </div>
            </figure>
        </li>
        <li>
            <figure>
                <img src="<?php echo $baseUrl ?>/frontend/themes/unify/assets/img/clients2/fred-perry.png" alt="">
                <div class="img-hover">
                    <h4>Fred Perry</h4>
                </div>
            </figure>
        </li>
        <li>
            <figure>
                <img src="<?php echo $baseUrl ?>/frontend/themes/unify/assets/img/clients2/bellfield.png" alt="">
                <div class="img-hover">
                    <h4>Bellfield</h4>
                </div>
            </figure>
        </li>
        <li>
            <figure>
                <img src="<?php echo $baseUrl ?>/frontend/themes/unify/assets/img/clients2/getapp.png" alt="">
                <div class="img-hover">
                    <h4>GetApp</h4>
                </div>
            </figure>
        </li>
        <li>
            <figure>
                <img src="<?php echo $baseUrl ?>/frontend/themes/unify/assets/img/clients2/austrian-airlines.png" alt="">
                <div class="img-hover">
                    <h4>Austrian Airlines</h4>
                </div>
            </figure>
        </li>
        <li>
            <figure>
                <img src="<?php echo $baseUrl ?>/frontend/themes/unify/assets/img/clients2/general-electric.png" alt="">
                <div class="img-hover">
                    <h4>General Electronic</h4>
                </div>
            </figure>
        </li>
    </ul>
</div><!--/container-->

