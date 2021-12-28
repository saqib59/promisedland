<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

$user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;

    $listing_id = $p['listing_id'];
    $method = $p['method'];

    if (empty($user) || empty($listing_id) || empty($method)) {
        echo '0';
    } else {

        if (checkFav($user_id, $listing_id) == false) {
            if ($method == 'add') {
                $fav = $db->query("INSERT INTO `favorite` (`id`, `user_id`, `listing_id`) VALUES (NULL, ?, ?);", $user, $listing_id);
            } elseif ($method == 'remove') {
                $fav = $db->query("DELETE FROM `favorite` WHERE `user_id` = ? AND `listing_id` = ?;", $user, $listing_id);
            }
        } else {
            $fav = true;
        }

        if ($fav) {
            echo 'success';
        } else {
            echo '0';
        }
    }
} else {
    echo '0';
}
