<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

$listings = $db->query("SELECT * FROM `listing` WHERE `foreclosure_date` < '2021-09-15 00:00:00';")->fetchAll();
foreach ($listings as $item) {
    $archive = $db->query("UPDATE `listing` SET `completed` = '2' WHERE `id` = ?;", $item['id']);
    if ($archive) echo 'Listing Archived';
}
