<?php
include_once 'main.php';

$user_id = current_user_id();
$user_role = current_user_role();

if (!is_role_admin()) {
    echo 'Access denied';
    die;
}

?>
