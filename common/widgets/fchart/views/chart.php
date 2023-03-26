<?php
/* @var $data array */
/* @var $this \yii\web\View */
/* @var $options array */
/**
 * items in array data
 * -- * is required --
 * string $data[0]['type'] *;
 * string $data[0]['portletTitle'];
 * int $data[0]['total_item'];
 * int $data[0]['is_active'];
 * array $data[0]['options'] *;
 * keys int array option
 * $option[0]['color']; // red, blue
 * $option[0]['value'];
 */
$container = isset($container) ? $container : true;
$num_col   = isset($columns) ? $columns : 4;
if ($num_col > count($data)) {
	$num_col = count($data);
}
$col_size = round(12 / $num_col, 0);
?>
<?php if ($container) { ?>

<div class="portlet light portlet-fit bordered col-md-12">
    <div class="portlet-title">
        <div class="caption">
            <i class=" icon-layers font-green"></i>
            <span class="caption-subject font-green bold uppercase"><?= $title; ?></span>
        </div>
        <div class="tools">
            <a href="#" class="collapse"></a>
            <a href="#" class="fullscreen"></a>
        </div>
        <div class="actions">
			<?php /*
                <a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
                    <i class="icon-cloud-upload"></i>
                </a>
                <a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
                    <i class="icon-wrench"></i>
                </a>
                <a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
                    <i class="icon-trash"></i>
                </a>
                */ ?>
        </div>
    </div>
	<?php } ?>
    <div class=" row col-md-<?= $columns; ?>">
		<?php if (count($data) == 1) : ?>
			<?php
			$item        = is_object($data) ? (array) $data : $data;
			$type        = $item['type'];
			$options     = $item['options'];
			$title       = isset($item['portletTitle']) ? $item['portletTitle'] : 'Chart Title';
			$size_option = isset($item['total_item']) ? $item['total_item'] : 0;
			if ($size_option > 3 && $size_option <= 7) {
				$col_size = 6;
			}
            elseif ($size_option > 7) {
				$col_size = 12;
			}
			$is_active = isset($item['is_active']) ? $item['is_active'] : true;
			?>
			<?php if ($is_active): ?>
                <div class="col-md-<?= $col_size ?> col-x-<?= $col_size ?>" style="margin-bottom: 15px;">
					<?= $this->render("_$type", ['item' => $item, 'options' => $options]); ?>
                </div>
			<?php endif; ?>
		<?php else: ?>
			<?php foreach ($data as $key => $item) : ?>
				<?php
				$item        = is_object($item) ? (array) $item : $item;
				$type        = $item['type'];
				$options     = $item['options'];
				$title       = isset($item['portletTitle']) ? $item['portletTitle'] : 'Chart Title';
				$size_option = isset($item['total_item']) ? $item['total_item'] : 0;
				if ($size_option > 3 && $size_option <= 7) {
					$col_size = 6;
				}
                elseif ($size_option > 7) {
					$col_size = 12;
				}
				$is_active = isset($item['is_active']) ? $item['is_active'] : true;
				?>
				<?php if ($is_active): ?>
                    <div class="col-md-<?= $col_size ?> col-x-<?= $col_size ?>" style="margin-bottom: 15px;">
						<?= $this->render("_$type", ['item' => $item, 'options' => $options]); ?>
                    </div>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
    </div>
	<?php if ($container) { ?>
</div>
<?php } ?>

