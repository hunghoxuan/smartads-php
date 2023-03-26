<?php

use yii\bootstrap\Nav;
use common\components\FHtml;

if (!isset($this->params['mainMenu'])) {
	return;
}

?>
<div class="page-sidebar navbar-collapse collapse  hidden-print">

    <!-- BEGIN SIDEBAR MENU -->
    <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
    <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
    <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
    <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
    <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->

    <ul class="<?= \common\components\FConfig::settingBackendSidebarStyle() ?>" data-keep-expanded="false" data-auto-scroll="true"
        data-slide-speed="200" style="padding-top: 20px">
        <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
        <li class="sidebar-toggler-wrapper hide">

            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
            <div class="sidebar-toggler">
                <span></span>
            </div>
            <!-- END SIDEBAR TOGGLER BUTTON -->
        </li>

		<?php

		$currentUrl     = FHtml::currentUrl();
		$is_cached_menu = isset($this->params['cachedMenu']) ? $this->params['cachedMenu'] : false;

		$menus = $this->params['mainMenu'];

		foreach ($menus as $item) {

			if (isset($item) && $item) {
				if (isset($item['visible']) && !$item['visible']) {
					continue;
				}

				$url = (is_array($item) && key_exists('url', $item)) ? $item['url'] : '';

				if ($is_cached_menu) {
					if (!empty($url) && FHtml::parseUrl($url)['active']) {
						$item['active'] = true;
					}
                    elseif (is_array($item)) {
						$item['active'] = false;
					}
				}

				if (isset ($item['children']) AND is_array($item['children'])) {
					if ($is_cached_menu) {
						$countChildren = 0;
						$m_active      = false;
						foreach ($item['children'] as $j => $child) {

							$url = (is_array($child) && key_exists('url', $child)) ? $child['url'] : '';

							if (FHtml::parseUrl($url)['active']) {
								$child['active'] = true;
								$m_active        = true;
							}
							else {
								$child['active'] = false;
							}

							$item['children'][$j] = $child;

							if ($child) {
								$countChildren++;
							}
						}
						$item['active'] = $m_active;
						if ($countChildren == 0) {
							continue;
						}
					}
					?>
                    <li class="nav-item <?php if (isset($item['active']) AND $item['active']) echo "active open" ?> <?php if (isset($item['open']) AND $item['open']) echo " open" ?>">
                        <a class="active nav-link nav-toggle"
                           target="<?= (isset($item['url']) && \yii\helpers\StringHelper::startsWith($item['url'], 'http')) ? '_blank' : '' ?>"
                           href="<?php echo isset($item['url']) ? $item['url'] : 'javascript:;' ?>">
                            <i class="<?php echo $item['icon'] ?>"></i>
                            <span class="title"><?php echo FHtml::t('common', \yii\helpers\Html::decode($item['name'])) ?></span>

							<?php if (isset($item['badge']) && $item['badge'] > -1 ): ?>
                                <span class="badge badge-danger" style="margin-top:4px;margin-right: 20px; "><?= $item['badge'] ?></span>
							<?php endif; ?>

							<?php if (isset($item['children']) AND is_array($item['children']) AND count($item['children']) != 0): ?>
								<?php if (isset($item['active']) AND $item['active']) { ?>
                                    <span class="selected"></span>
                                    <span class="arrow open"></span>
								<?php } else { ?>
                                    <span class="arrow"></span>
								<?php } ?>
							<?php endif; ?>
                        </a>

                        <ul class="sub-menu"
                            style="<?php if (isset($item['open']) AND $item['open']) echo "display:block" ?>">
							<?php foreach ($item['children'] as $children):
								if (!isset($children)) {
									continue;
								}
								if (!key_exists('name', $children) || empty($children) || !isset($children) || (isset($children['visible']) && !$children['visible'])) {
									continue;
								} ?>
                                <li class="nav-item  <?php if (isset($children['active']) AND $children['active']) echo 'active open' ?>">
                                    <a target="<?= (isset($children['url']) && \yii\helpers\StringHelper::startsWith($children['url'], 'http')) ? '_blank' : '' ?>"
                                       href="<?php echo isset($children['url']) ? $children['url'] : 'javascript:;' ?>"
                                       class="nav-link nav-toggle">
                                        <i class="<?= $children['icon'] ?>"></i>
                                        <span class="title"><?= FHtml::t('common', \yii\helpers\Html::decode($children['name'])) ?></span>

										<?php if (isset($children['badge']) && $children['badge'] > -1): ?>
                                            <span class="badge badge-danger" style="margin-top:4px; "><?= $children['badge'] ?></span>
										<?php endif; ?>

										<?php if (isset($children['children']) AND is_array($children['children']) AND count($children['children']) != 0): ?>
											<?php if (isset($children['active']) AND $children['active']) { ?>
                                                <span class="selected"></span>
                                                <span class="arrow open"></span>
											<?php } else { ?>
                                                <span class="arrow"></span>
											<?php } ?>
										<?php endif; ?>
                                    </a>

									<?php if (isset($children['children']) AND is_array($children['children']) AND count($children['children']) != 0): ?>
                                        <ul class="sub-menu"
                                            style="<?php if (isset($item['open']) AND $item['open']) echo "display:block" ?>">
											<?php foreach ($children['children'] as $little_children):
												if (empty($little_children) || !isset($little_children) || (isset($little_children['visible']) && !$little_children['visible'])) {
													continue;
												} ?>
                                                <li class="nav-item <?php if (isset($little_children['active']) AND $little_children['active']) {
													echo "active open";
												} ?> <?php if (isset($item['open']) AND $item['open']) echo " open" ?>">

                                                    <a target="<?= (isset($little_children['url']) && \yii\helpers\StringHelper::startsWith($little_children['url'], 'http')) ? '_blank' : '' ?>"
                                                       href="<?php echo isset($little_children['url']) ? $little_children['url'] : 'javascript:;' ?>"
                                                       class="nav-link ">
                                                        <i class="<?= $little_children['icon'] ?>"></i>
														<?= FHtml::t('common', \yii\helpers\Html::decode($little_children['name'])) ?>
                                                    </a>
                                                </li>

											<?php endforeach; ?>
                                        </ul>
									<?php endif; ?>
                                </li>
							<?php endforeach; ?>
                        </ul>
                    </li>
					<?php
				}
				else {
					if (isset($item['url'])) {
						?>
                        <li class="<?php if (isset($item['active']) AND $item['active']) echo 'active' ?><?php if (isset($item['open']) AND $item['open']) echo ' open' ?>">
                            <a href="<?php echo $item['url'] ?>">
                                <i class="<?php echo $item['icon'] ?>"></i>
                                <span class="title"><?php echo FHtml::t('common', $item['name']); ?></span>

								<?php if (isset($item['badge']) && $item['badge'] > -1 ): ?>
                                    <span class="badge badge-danger" style="margin-top:4px; "><?= $item['badge'] ?></span>
								<?php endif; ?>

								<?php if (isset($item['active']) AND $item['active']) echo '<span class="selected"></span>' ?>
                            </a>
                        </li>
						<?php
					}
				}
			}
		}
		?>
    </ul>
    <!-- END SIDEBAR MENU -->
</div>
