<?php
if (!empty($toolBarActions)) {
    $buttons = $toolBarActions['button'];
    $linkButtons = $toolBarActions['linkButton'];
    $dropdowns = $toolBarActions['dropdown'];
    $default_class = 'btn  btn-default';
    $default_icon = 'fa fa-list';

    ?>


    <div class="btn-group">

        <?php if (count($linkButtons) != 0) {
            foreach ($linkButtons as $link) {
                ?>
                <a href="<?php echo $link['htmlOptions']['href'] ?>"
                   class="<?php echo isset($link['htmlOptions']['class'])?$link['htmlOptions']['class']:$default_class ?>">
                    <i class="<?php echo isset($link['icon'])? $link['icon'] : $default_icon ?>"></i>
                    <?php echo $link['label'] ?>

                </a>
            <?php }
        } ?>
        <?php if (count($buttons) != 0) {
            foreach ($buttons as $button) { ?>
                <button type="button" onclick="<?php echo $button['htmlOptions']['onclick'] ?>"
                        class="<?php echo isset($button['htmlOptions']['class'])?$button['htmlOptions']['class'] : $default_class ?>">
                    <i class="<?php echo isset($button['icon'])? $button['icon'] : $default_icon ?>"></i>
                    <?php echo $button['label'] ?>

                </button>
            <?php }
        } ?>
        <?php if (count($dropdowns) != 0) {
            ?>
            <button type="button" class="btn grey-salt dropdown-toggle" data-toggle="dropdown"
                    data-hover="dropdown" data-delay="1000" data-close-others="true">
                Actions
                <i class="fa fa-angle-down"></i>
            </button>
            <ul class="dropdown-menu pull-right" role="menu">
                <?php foreach ($dropdowns as $dropdown) { ?>
                    <?php if ($dropdown['label'] != 'divider') { ?>
                        <li><a href="<?php echo $dropdown['htmlOptions']['href'] ?>"
                               data-toggle="modal"><?php echo $dropdown['label'] ?></a></li>
                    <?php } else { ?>
                        <li class="divider"></li>
                    <?php }
                } ?>
            </ul>
            <?php
        } ?>
    </div>

<?php } else {
    echo '<div class="btn-group pull-right"></div>';
}
?>

