<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

$user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;
    $question_id = $p['question_id'];

    // main question
    $question = $db->query("SELECT * FROM `course_faq` WHERE `id` = ?;", $question_id)->fetchArray();
    if ($question) {

        $total_likes_count = faqCountInfo($question['id'], 'course_faq_likes');
?>

        <div class="course_question__main">
            <h4><?= $question['title'] ?></h4>
            <div class="course_question__main-meta">
                <p>
                    <i class="fa fa-user"></i>
                    <span>von</span>
                    <strong><?= get_data($question['user'], 'name', 'users') ?></strong>
                </p>
                <p>
                    <i class="fa fa-clock"></i>
                    <span>am</span>
                    <strong><?= $question['updated_at'] ?></strong>
                </p>
                <p>
                    <i class="fa fa-thumbs-up"></i>
                    <strong class="show_like_count"><?= $total_likes_count ?></strong>
                    <span>Daumen hoch</span>
                </p>
            </div>
            <div class="course_question__main-content">
                <div class="course_question__main-content--text"><?= $question['question'] ?></div>
            </div>

            <?php
            $like = $db->query("SELECT * FROM `course_faq_likes` WHERE faq_id = ? AND `user` = ?;", $question['id'], $user);
            $like_check = $like->numRows();
            ?>
            <div class="course_question__main-alert"></div>

            <?php if ($like_check > 0) { ?>
                <button id="faq_like" data-faq="<?= $question['id'] ?>" data-like="unlike" class="btn btn-dark btn-sm">
                    <i class="fa fa-thumbs-up"></i>
                    <span>Liked!</span>
                </button>
            <?php } else { ?>
                <button id="faq_like" data-faq="<?= $question['id'] ?>" data-like="like" class="btn btn-dark-outline btn-sm">
                    <i class="fa fa-thumbs-up"></i>
                    <span>Gefällt mir</span>
                </button>
            <?php } ?>


        </div>

        <div class="course_question__reply">
            <?php
            $comments = $db->query("SELECT * FROM `course_faq_comments` WHERE `faq_id` = ?;", $question_id)->fetchAll();
            if ($comments) { ?>
                <?php foreach ($comments as $item) { ?>

                    <div class="course_question__reply-item <?= $item['answer'] == 1 ? 'answer' : ''; ?>">

                        <?php if ($item['answer'] == 1) { ?>
                            <div class="course_question__answer">
                                <strong>
                                    <i class="fa fa-star"></i>
                                    <span>Antwort</span>
                                </strong>
                            </div>
                        <?php } ?>

                        <div class="course_question__reply-item--left">
                            <?php
                            if ($item['role'] == 'tutor') {
                                echo '<img src="' . LINK . get_col_data($item['user'], 'tutor', 'image', 'course_author') . '">';
                                echo '<div class="course_question__author"><span>Verfasser</span></div>';
                            } else {
                                echo '<img src="' . LINK . get_data($item['user'], 'image', 'users') . '">';
                            }
                            ?>
                        </div>
                        <div class="course_question__reply-item--info">
                            <p><?= strip_tags($item['reply']) ?></p>
                            <div class="course_question__main-meta">
                                <?php
                                if ($item['role'] == 'tutor') {
                                    echo '<p><span>von</span> <strong>' . get_col_data($item['user'], 'tutor', 'name', 'course_author') . '</strong></p>';
                                } else {
                                    echo '<p><span>von</span> <strong>' . get_data($item['user'], 'name', 'users') . '</strong></p>';
                                }
                                ?>
                                <p>
                                    <span>on</span>
                                    <strong><?= $item['updated_at'] ?></strong>
                                </p>
                            </div>
                        </div>
                    </div>

                <?php } ?>
            <?php } ?>
        </div>

        <div class="course_question__comment">
            <div class="course_question__comment-alert"></div>
            <?php if (isset($_SESSION['admin']) && $_SESSION['role'] == 'tutor') { ?>
                <div class="course_question__comment-info">
                    <p>Reply as the Author,</p>
                </div>
            <?php } ?>
            <form id="faq_reply" action="<?= $_SERVER['PHP_SELF']; ?>" method="POST">
                <input name="question" type="hidden" value="<?= $question['id'] ?>">
                <div class="form-group">
                    <textarea name="reply" class="form-control" placeholder="Schreibe hier deine Antwort..." required></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-blue btn-sm">
                        <i class="fa fa-reply"></i>
                        <span>Beitrag senden</span>
                    </button>
                </div>
            </form>
        </div>

<?php
    } else {
        echo '0';
    }
} else {
    echo '0';
}
