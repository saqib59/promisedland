<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

$begin = date("Y-m-d 00:00:00", strtotime("-7 days"));
$end = date("Y-m-d 23:59:59", strtotime("-7 days"));

$membships = $db->query("SELECT * FROM `membership` WHERE `end_dt` BETWEEN ? AND ?;", $begin, $end);

if ($membships && !empty($membships)) {
    foreach ($membships as $item) {
        $memb_id = $item['id'];
        $user_id = $item['user_id'];

        $current_memb_id = get_data($user_id, 'membership_id', 'users');
        if ($memb_id == $current_memb_id) {
            updateDatabyId('0', 'membership_id', $user_id, 'users');
        }
    }
}
