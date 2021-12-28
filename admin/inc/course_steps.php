<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

if (admin() == false) {
    redirect("Please login first!", ADMIN . '/login.php');
    exit();
}

define('CURRENT', basename($_SERVER["SCRIPT_NAME"], '.php'));

if (isset($_GET['course_id']) && !empty($_GET['course_id'])) {
    $course_id = $_GET['course_id'];
?>

    <div class="listing_steps">
        <div class="listing_steps__inner">
            <a class="btn <?= CURRENT == 'new_course' ? 'current' : ''; ?>" href="<?= ADMIN ?>/new_course.php?course_id=<?= $course_id ?>&edit=1">Course Information</a>
            <a class="btn <?= CURRENT == 'course_learn' ? 'current' : ''; ?>" href="<?= ADMIN ?>/course_learn.php?course_id=<?= $course_id ?>&edit=1">You May Learn</a>
            <a class="btn <?= CURRENT == 'course_videos' ? 'current' : ''; ?>" href="<?= ADMIN ?>/course_videos.php?course_id=<?= $course_id ?>&edit=1">Course Videos</a>
            <a class="btn <?= CURRENT == 'course_faq' ? 'current' : ''; ?>" href="<?= ADMIN ?>/course_faq.php?course_id=<?= $course_id ?>&edit=1">Course F.A.Q</a>
        </div>
    </div>

<?php } ?>