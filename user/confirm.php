<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

if (user() !== false) {
    header("Location: " . USER);
    exit();
}

if (isset($_GET['key']) && !empty($_GET['key'])) {

    $emailKey = $_GET['key'];
    $accounts = $db->query('SELECT * FROM `users` WHERE `email_key` = ?', $emailKey);
    if ($accounts->numRows() == 1) {
        $accounts = $accounts->fetchArray();

        $account_id = $accounts['id'];
        if ($accounts['verify'] == '1') {
            user_redirect('Dein Account wurde verifiziert und du kannst dich jetzt einloggen', 'info', USER . '/login/');
        } else {
            //$account_verify = $db->query("UPDATE `users` SET `email_key` = ?, `verify` = ?, `updated_at` = CURRENT_TIMESTAMP WHERE `id` = ?;", '', '1', $account_id);
            $account_verify = $db->query("UPDATE `users` SET `verify` = ?, `updated_at` = CURRENT_TIMESTAMP WHERE `id` = ?;", '1', $account_id);
            if ($account_verify) {
                user_redirect('Deine E-Mail wurde erfolgreich verifiziert. Logge dich ein um fortzufahren!', 'success', USER . '/login/?redirect=/packages/');
            } else {
                user_redirect('Etwas lief falsch!', 'error', USER . '/login/');
            }
        }
    } else {
        user_redirect('Ungültiger Link', 'error', LINK);
    }
} else {
    user_redirect('Ungültiger Link', 'error', LINK);
}