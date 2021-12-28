<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

if (isset($_SESSION['admin']) && $_SESSION['role'] == 'tutor') {
    $user = $_SESSION['admin'];
    $role = 'tutor';
} else {
    $user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;
    $role = 'user';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;
    $role = $p['role'];
    $question = $p['question'];
    $reply = $p['reply'];

    if (empty($user) || empty($question) || empty($reply)) {
        echo '0';
    } else {
        $comment = $db->query("INSERT INTO `course_faq_comments`(`id`, `faq_id`, `reply`, `user`, `role`) VALUES (NULL, ?, ?, ?, ?);", $question, $reply, $user, $role);
        if ($comment) {
            $comment_id = $db->lastInsertID();
            $mine = $db->query("SELECT * FROM `course_faq_comments` WHERE `id` = ?;", $comment_id)->fetchArray();
?>
            <div class="course_question__reply-item">
                <div class="course_question__reply-item--left">
                    <?php
                    if ($mine['role'] == 'tutor') {
                        echo '<img src="' . LINK . get_col_data($mine['user'], 'tutor', 'image', 'course_author') . '">';
                        echo '<div class="course_question__author"><span>Author</span></div>';
                    } else {
                        echo '<img src="' . LINK . get_data($mine['user'], 'image', 'users') . '">';
                    }
                    ?>
                </div>
                <div class="course_question__reply-item--info">
                    <p><?= strip_tags($mine['reply']) ?></p>
                    <div class="course_question__main-meta">
                        <?php
                        if ($mine['role'] == 'tutor') {
                            echo '<p><span>by</span> <strong>' . get_col_data($mine['user'], 'tutor', 'name', 'course_author') . '</strong></p>';
                        } else {
                            echo '<p><span>by</span> <strong>' . get_data($mine['user'], 'name', 'users') . '</strong></p>';
                        }
                        ?>
                        <p>
                            <span>on</span>
                            <strong><?= $mine['updated_at'] ?></strong>
                        </p>
                    </div>
                </div>
            </div>
<?php
        } else {
            echo '0';
        }
    }
} else {
    echo '0';
}
