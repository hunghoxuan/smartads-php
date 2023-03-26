<?php
/**
 * Node.php v0.4
 * (c) 2016 Jerzy Głowacki
 * MIT License
 */
include 'node.php';

function node_dispatch() {
    if(ADMIN_MODE) {
        node_head();
        if(isset($_GET['install'])) {
            node_install();
        } elseif(isset($_GET['uninstall'])) {
            node_uninstall();
        } elseif(isset($_GET['start'])) {
            node_start($_GET['start']);
        } elseif(isset($_GET['stop'])) {
            node_stop();
        } elseif(isset($_GET['npm'])) {
            node_npm($_GET['npm']);
        } else {
            echo "You are in Admin Mode. Switch back to normal mode to serve your node app.";
        }
        node_foot();
    } else {
        if(isset($_GET['path'])) {
            node_serve($_GET['path']);
        } else {
            node_serve();
        }
    }
}

node_dispatch();