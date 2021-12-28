<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

$begin = date("Y-m-d 00:00:00", strtotime("-1 days"));
$end = date("Y-m-d 23:59:59", strtotime("-1 days"));

$membships = $db->query("SELECT * FROM `membership` WHERE `end_dt` BETWEEN ? AND ?;", $begin, $end)->fetchAll();

if ($membships && !empty($membships)) {
    foreach ($membships as $item) {
        $mem_id = $item['id'];
        updateDatabyId('expired', 'status', $mem_id, 'membership');
        echo 'Membership Expired for row: ' . $mem_id;
    }
}
