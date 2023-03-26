<?php
use common\components\FHtml;
$baseUrl = Yii::$app->getUrlManager()->getBaseUrl();
$frontendBaseUrl = \common\components\FHtml::currentBaseURL(FRONTEND);
?>
<div class="page-header-inner ">

    <!-- BEGIN LOGO -->
    <div class="page-logo">
        <div style="float:left; margin-top: -5px">
            <?= FHtml::showCurrentLogo('', '30px') ?>
        </div>
        <div class="menu-toggler sidebar-toggler">
                    <span>

                    </span>
        </div>
    </div>
    <!-- END LOGO -->
    <!-- BEGIN RESPONSIVE MENU TOGGLER -->
    <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
       data-target=".navbar-collapse">
        <span></span>
    </a>


    <!-- END RESPONSIVE MENU TOGGLER -->
    <!-- BEGIN TOP NAVIGATION MENU -->
    <?php if (!FHtml::currentDevice()->isMobile()) { ?>
        <div class="top-menu-wrapper">
            <!--
            <div class="top-menu pull-left nav navbar-nav" style="background-color: <?= FHtml::setting('backend_background_color', '#eef1f5') ?>; padding:16px">
                <b><?= FHtml::t('common', $this->title) ?></b>&nbsp;
                <?= (FHtml::isRoleAdmin() && FHtml::isSystemAdminEnabled() && !FHtml::isInArray(FHtml::currentModule(), ['system', '']))
                    ? FHtml::createLink('system/object-type/update', ['id' => FHtml::currentObjectType()], BACKEND, '<span class="glyphicon glyphicon-cog text-default small"></span>', '_blank', '') : '' ?>
            </div>
            -->
            <div class="pull-left" style="width:100px; background-color: white"></div>
            <div class="top-menu" style="">
                <ul class="nav navbar-nav pull-right">
                    <li class="dropdown dropdown-user">
                        <?= FHtml::showLangsMenu() ?>
                    </li>
                    <li class="dropdown dropdown-user">
                        <a href="<?php echo FHtml::createUrl('/docs') ?>">
                            <span
                                    class="username username-hide-mobile" style=""><i class="icon-info"></i> <?= FHtml::t('common', 'Help') ?></span>
                        </a>
                    </li>
                    <!-- BEGIN USER LOGIN DROPDOWN -->
                    <?php if (isset($user)) { ?>
                        <li class="dropdown dropdown-user">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"
                               data-hover="dropdown"
                               data-close-others="true">
                                <?= FHtml::showCurrentUserAvatar() ?>
                                <span
                                        class="username username-hide-mobile" style=""><?php echo ucwords($user->username) ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-default">
                                <li>
                                    <a href="<?php echo FHtml::createUrl('/user/profile') ?>">
                                        <i class="icon-user"></i> <?= FHtml::t('common', 'Profile') ?>
                                    </a>
                                </li>

                                <?php if (FHtml::isRoleAdmin()) { ?>
                                    <li class="divider">
                                    </li>
                                    <li>
                                        <a href="<?= FHtml::createUrl('site/refresh') ?>"><i
                                                    class="glyphicon glyphicon-refresh"></i>  <?= FHtml::t('common', 'Refresh Cache') ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= FHtml::createUrl('settings') ?>"><i
                                                    class="glyphicon glyphicon-cog"></i>  <?= FHtml::t('common', 'Configuration') ?>
                                        </a>
                                    </li>
                                    <?php if (FHtml::isLanguagesEnabled()) { ?>
                                        <li>
                                            <a href="<?= FHtml::createUrl('settings-text') ?>"><i
                                                        class="glyphicon glyphicon-cog"></i>  <?= FHtml::t('common', 'Translations') ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                <?php } ?>

                                <li class="divider">
                                </li>
                                <li>
                                    <a href="<?php echo FHtml::createUrl('/site/logout') ?>">
                                        <i class="icon-key"></i> <?= FHtml::t('common', 'Log Out') ?> </a>
                                </li>
                            </ul>
                        </li>

                    <?php } else { ?>

                    <a class="btn btn-sm" style="background-color: white; margin-top:8px" href="<?php echo FHtml::createUrl('/site/login') ?>">
                        <i class="icon-key"></i> <?= FHtml::t('common', 'Log In') ?> </a>
                    <?php } ?>
                    <!-- END USER LOGIN DROPDOWN -->
                </ul>
            </div>
            <!-- END TOP NAVIGATION MENU -->
            <div class="application-title text-center">
                <div class="" style="font-size: 18pt; text-transform: uppercase">
                    <b> <?= FHtml::settingCompanyName() ?></b>
                    <?php if (APPLICATIONS_ENABLED && FHtml::isRoleAdmin()) { ?>
                        <a href="<?= FHtml::createUrl('system/application') ?>"
                           style="color:white !important;"><span class="glyphicon glyphicon-triangle-right"></span></a>
                    <?php } ?>
                </div>

            </div>
        </div>
    <?php } else { ?>
        <div class="text-center" style="color: white; padding-top:10px">
            <b> <?= FHtml::settingCompanyName() ?></b>
        </div>
    <?php } ?>
</div>
