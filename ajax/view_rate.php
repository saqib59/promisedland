<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

$user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;

    $row_id = $p['row_id'];
    $row_type = $p['row_type'];

    if($row_type == 'seminar') {
        $table = 'seminar_feedback';
        $column = 'seminar_id';
    } else {
        $table = 'consulting_feedback';
        $column = 'consultant_id';
    }

    if (empty($row_id)) {
        echo '0';
    } else {
        $rating = $db->query("SELECT * FROM `{$table}` WHERE `{$column}` = ? AND `user_id` = ?;", $row_id, $user)->fetchArray();
        if ($rating) {
?>
            <div class="seminar_feedback__view">

                <?php if (!empty($rating["rating"])) { ?>
                    <div class="seminar_feedback__view-item">
                        <div class="seminar_feedback__view-item--label">
                            <h4>Star Rating</h4>
                        </div>
                        <div class="seminar_feedback__view-item--content">
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
                        </div>
                    </div>
                <?php } ?>

                <?php if (!empty($rating["feedback"])) { ?>
                    <div class="seminar_feedback__view-item">
                        <div class="seminar_feedback__view-item--label">
                            <h4>Feedback</h4>
                        </div>
                        <div class="seminar_feedback__view-item--content">
                            <p><?= $rating["feedback"] ?></p>
                        </div>
                    </div>
                <?php } ?>

            </div>
<?php
        } else {
            echo '0';
        }
    }
} else {
    echo '0';
}
