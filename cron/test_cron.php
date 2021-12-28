<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

$test = $db->query("INSERT INTO `settings`(`id`, `setting_name`, `setting_value`) VALUES (NULL, ?, ?);", 'cron', 'cron_settings_3');

if ($test) echo 'value inserted';