<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

$user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;

    if (user() !== false) {
        if (
            isset($p['consulting_id']) && !empty($p['consulting_id']) &&
            isset($p['contact_number']) && !empty($p['contact_number']) &&
            isset($p['time']) && !empty($p['time'])
        ) {

            $upload = '';
            if (isset($_FILES['attachment']) && !empty($_FILES['attachment'])) {
                $upload = upload_files('attachments', $_FILES['attachment']);
            }

            $booking = $db->query("INSERT INTO `consulting_booking`(`id`, `consultant_id`, `user_id`, `object_id`, `files`, `contact`, `time`) VALUES (NULL, ?, ?, ?, ?, ?, ?);", $p['consulting_id'], $user_id, $p['object_id'], $upload, $p['contact_number'], $p['time']);
            if ($booking) {

                $consInfo = $db->query("SELECT * FROM `consulting` WHERE `id` = ?", $seminar_id)->fetchArray();
                    $data = array(
                        'subject' => $consInfo['title'],
                        'price' => $consInfo['price'],
                        'time' => $consInfo['time'],
                    );

                $user_email = get_data($user, 'email', 'users');
                // @@mail : send consultant booking email
                booking_confirm($user_email, 'consultant', $data);

                echo 'success';
                exit();
            } else {
                echo '0';
            }
        } else {
            echo '0';
        }
    } else {
        echo 'logged';
    }
} else {
    echo '0';
}
