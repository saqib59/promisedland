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
        if (isset($p['seminar_id']) && !empty($p['seminar_id'])) {

            $seminar_id = $p['seminar_id'];

            if (checkBooking('seminar_booking', 'seminar_id', $seminar_id, $user_id)) {
                echo 'booked';
                exit();
            } else {
                $booking = $db->query("INSERT INTO `seminar_booking` (`id`, `seminar_id`, `user_id`) VALUES (NULL, ?, ?);", $seminar_id, $user_id);
                if ($booking) {

                    $seminarInfo = $db->query("SELECT * FROM `seminar` WHERE `id` = ?", $seminar_id)->fetchArray();
                    $data = array(
                        'subject' => $seminarInfo['title'],
                        'date' => $seminarInfo['event_date'],
                        'location' => $seminarInfo['location'],
                        'method' => $seminarInfo['method'],
                    );

                    $user_email = get_data($user, 'email', 'users');
                    // @@mail : send seminar booking email
                    booking_confirm($user_email, 'course', $data);

                    echo 'success';
                    exit();
                }
            }
            echo '0';
        } else {
            echo '0';
        }
    } else {
        echo 'logged';
    }
}
echo '0';
