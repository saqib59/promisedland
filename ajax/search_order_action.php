<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once  '../inc/account/logged.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;

    $order_id = $p['order_id'];
    $action = $p['action'];

    $checkOrder = $db->query("SELECT * FROM `search_order` WHERE `id` = ?;", $order_id)->fetchArray();
    if ($checkOrder && !empty($checkOrder)) {
        if ($checkOrder["user"] == $user) {
            if (!empty($order_id) && !empty($action)) {
                $query = false;
                if ($action == 'delete') {
                    $query = $db->query("DELETE FROM `search_order` WHERE `id` = ?;", $order_id);
                } elseif ($action == 'pause') {
                    $query = updateDatabyId('1', 'pause', $order_id, 'search_order');
                } elseif ($action == 'resume') {
                    $query = updateDatabyId('0', 'pause', $order_id, 'search_order');
                }

                if ($query) {
                    echo 'success';
                    exit();
                } else {
                    echo '4';
                }
            } else {
                echo '3';
            }
        } else {
            echo '2';
        }
    } else {
        echo '1';
    }
}
echo '0';
