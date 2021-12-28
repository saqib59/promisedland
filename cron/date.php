<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

$listings = $db->query("SELECT * FROM `listing`;")->fetchAll();
foreach ($listings as $item) {
    $new_date = cleanForcDate($item['foreclosure_date']);
    //dump($item['id'] . ' ' . $new_date);

    /* if ($new_date !== false && !empty($new_date)) {
        $update_date = $db->query("UPDATE `listing` SET `foreclosure_date` = ? WHERE `id` = ?;", $new_date, $item['id']);
        if ($update_date) {
            echo 'Date updated on ' . $item['id'] . '<br>';
        }
    } */
}
