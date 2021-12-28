<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

$user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;
    // check for listing id and user
    if (isset($p['listing_id']) && !empty($p['listing_id'])) {

        if ($user == 0) {
            echo 'logged';
        } else {
            // update the request to db
            $request = $db->query("INSERT INTO `request`(`id`, `listing_id`, `user`) VALUES (NULL, ?, ?)", $p['listing_id'], $user);
            if ($request) {
                echo 'success';
            }
        }
    }
}
