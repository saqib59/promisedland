<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

$user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;

    $seminar_id = $p['seminar_id'];

    if (empty($seminar_id)) {
        echo '0';
    } else {
        $ratings = $db->query("SELECT * FROM `seminar_feedback` WHERE `seminar_id` = ?;", $seminar_id)->fetchAll();
        if ($ratings) {
            echo '<div class="seminar_feedback__list">';
            foreach ($ratings as $rating) {
?>

                <div class="seminar_feedback__list-view">
                    <div class="course_question__reply-item--left">
                        <img src="<?= LINK . get_data($rating['user_id'], 'image', 'users') ?>">
                    </div>
                    <div class="course_question__reply-item--info">
                        <div class="star_ratings">
                            <?php
                            for ($i = 0; $i < $rating["rating"]; $i++) {
                                echo '<i class="fa fa-star gold"></i>';
                            }
                            for ($i = 5; $i > $rating["rating"]; $i--) {
                                echo '<i class="fa fa-star"></i>';
                            }
                            ?>
                        </div>
                        <p><?= $rating["feedback"] ?></p>
                        <div class="course_question__main-meta">
                            <p><span>by</span> <strong><?= get_data($rating['user_id'], 'name', 'users') ?></strong></p>
                        </div>
                    </div>
                </div>

<?php
            }
            echo '</div>';
        } else {
            echo '0';
        }
    }
} else {
    echo '0';
}
