<?php
$i = 0;
foreach ($full_frame as $frame) {
    echo $this->render('get-frame', [
        'frame' => $frame,
        'classSelect' => 'smartscreenlayouts-list_frame-' . $i . '-frame',
    ]);
    $i++;
}
?>
