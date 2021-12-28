<?php
require_once '../config/config.php';

// ALTER TABLE `listing` ADD `lat` VARCHAR(100) NOT NULL AFTER `object_address`, ADD `lng` VARCHAR(100) NOT NULL AFTER `lat`;

$listings = $db->query("SELECT * FROM `listing` WHERE `lat` IS NULL AND `lng` IS NULL;")->fetchAll();

foreach ($listings as $loc) {
    // var_dump($loc);
    //if ($loc['object_address'] != '') {
        //echo $loc['object_address'] . ' <br>';
        if ($loc['object_address'] !== '') {
            $value = geocode($loc['object_address']);
            $booking = $db->query("UPDATE  `listing` SET `lat` = ? , `lng`= ? WHERE id =? ", $value[0], $value[1], $loc['id']);
            if ($booking) {
                echo 'success' . $loc['object_address'] . ' <br>';
            } else {
                echo 'failed' . $loc['object_address'] . ' <br>';
            }
        }
    //}
}
