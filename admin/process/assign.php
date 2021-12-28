<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../../config/config.php';

if (admin() == false) {
    redirect("Please login first!", ADMIN . '/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if(!isset($_POST['user']) || empty($_POST['user'])) {
        echo 'user_missing';
        exit();
    }
    if(!isset($_POST['listings']) || empty($_POST['listings'])) {
        echo 'listings_missing';
        exit();
    }

    $assign = false;

    $admin = $_POST['user'];
    foreach( $_POST['listings'] as $item ) {
        $assign = $db->query("UPDATE `listing` SET `admin` = ? WHERE `id` = ?;", $admin, $item);
    }

    if($assign) {
        echo 'success';
    }
    
} else {
    echo 'error';
}

?>