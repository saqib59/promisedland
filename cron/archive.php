<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

$listings = $db->query("SELECT * FROM `listing` WHERE `foreclosure_date` < ? LIMIT 0, 10;", today())->fetchAll();
//$listings = $db->query("SELECT * FROM `listing`;")->fetchAll();

foreach ($listings as $item) {

    //$archive = $db->query("UPDATE `listing` SET `completed` = '2' WHERE `id` = ?;", $item['id']);
    $archive = updateDatabyId('2', 'completed', $item['id'], 'listing');
    if ($archive) echo 'Listing Archived';
}
