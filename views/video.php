<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';
require_once HOME . '/inc/account/logged.php';

if (!isset($_GET['slug']) || empty($_GET['slug'])) {
    redirect("Ungültiger Link", LINK);
}
$course_slug = $_GET['slug'];

// information
$course = $db->query('SELECT * FROM `course` WHERE `slug` = ?', $course_slug)->fetchArray();
if ($course) {
    $course_author = $course["author"];
    $author = $db->query('SELECT * FROM `course_author` WHERE `id` = ?', $course_author)->fetchArray();
}

if (checkCourse($user, $course['id']) == false) {
    redirect("Ungültiger Link", LINK);
}

// videos
$videos = $db->query('SELECT * FROM `course_video` WHERE `course_id` = ?', $course['id'])->fetchArray();

// faq
$faq = $db->query('SELECT * FROM `course_faq` WHERE `course_id` = ?', $course['id'])->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title><?= $course['title'] ?> - Promised Land</title>
</head>

<body class="white">

    <!-- Start . Section : Header -->
    <?php include HOME . '/block/header.php'; ?>
    <!-- End . Section : Header -->

    <?php if ($videos) { ?>
        <section id="course_video" class="light">
            <div class="container">
                <div class="row">

                    <?php
                    $video_list = json_decode($videos["videos"], true);
                    if (!empty($video_list)) { ?>

                        <div class="col-xl-9 col-lg-8 col-md-8 col-sm-12 col-12">
                            <div class="course_video__embed">
                                <video width="100%" controls>
                                    <source src="<?= LINK . $video_list['course'][0]['video'] ?>" type="video/mp4">
                                    Your browser does not support HTML video.
                                </video>
                                <div class="overlay"></div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-12 col-12">
                            <div class="couse_video__list">
                                <?php foreach ($video_list['course'] as $k => $item) { ?>
                                    <div class="couse_video__list-item <?= $k == '0' ? 'active' : ''; ?>" data-video="<?= LINK . $item['video'] ?>">
                                        <div class="couse_video__list-item--checkbox <?= $item['watch'] == '1' ? 'checked' : '' ?>">
                                            <i class="fa fa-check"></i>
                                        </div>
                                        <div class="couse_video__list-item--title">
                                            <h4><?= $item['title'] ?></h4>
                                            <p>
                                                <i class="fa fa-clock"></i>
                                                <span><?= $item['length'] >= 3600 ? gmdate("H:i:s", $item['length']) : gmdate("i:s", $item['length']); ?></span>
                                            </p>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                    <?php } ?>

                </div>
        </section>
    <?php } ?>

    <?php if ($author) { ?>
        <section id="course_author" class="dark">
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                        <div class="course_author__img">
                            <img src="<?= LINK . $author['image'] ?>">
                            <div class="course_author__img-info">
                                <h4><?= $author['name'] ?></h4>
                                <p><?= $author['position'] ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12">
                        <div class="course_author__info">
                            <div class="course_author__info-title">
                                <h4>Redner:</h4>
                            </div>
                            <div class="course_author__info-content">
                                <div class="course_author__info-content--item">
                                    <div class="course_author__info-content--item---content"><?= $author['content'] ?></div>
                                </div>
                                <div class="course_author__info-content--item">
                                    <h4>Arbeitsbereiche</h4>
                                    <p><?= $author['focus_work'] ?></p>
                                </div>
                                <div class="course_author__info-content--item">
                                    <h4>Erfahrungen</h4>
                                    <p><?= $author['experience'] ?></p>
                                </div>
                                <div class="course_author__info-content--item">
                                    <h4>Schlussgedanke</h4>
                                    <p><?= $author['final_thoughts'] ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php } ?>

    <section id="course_faq">
        <div class="container">

            <div class="course_faq">

                <div class="course_faq__title">
                    <div class="course_faq__title-text">
                        <h4>Fragen und Antworten</h4>
                    </div>
                    <?php if (isset($_SESSION['user']) && !empty($_SESSION['user'])) { ?>
                        <div class="course_faq__title-button">
                            <button class="btn btn-blue" data-bs-toggle="modal" data-bs-target="#threadModal">
                                <i class="fa fa-comment-alt-plus"></i>
                                <span>Frage stellen</span>
                            </button>
                        </div>
                    <?php } ?>
                </div>

                <?php if ($faq) { ?>
                    <div class="course_faq__list">

                        <?php foreach ($faq as $item) { ?>
                            <div class="course_faq__item" data-question="<?= $item['id'] ?>">

                                <?php
                                // comments
                                $answered = 0;
                                $comments = $db->query("SELECT * FROM `course_faq_comments` WHERE `faq_id` = ?;", $item['id'])->fetchAll();
                                foreach ($comments as $comm) {
                                    if ($comm['answer'] == '1') {
                                        $answered = 1;
                                        break;
                                    }
                                }
                                ?>

                                <?php if ($answered == '1') { ?>
                                    <div class="course_question__answer">
                                        <strong>
                                            <i class="fa fa-star"></i>
                                            <span>Beantwortet</span>
                                        </strong>
                                    </div>
                                <?php } ?>

                                <div class="course_faq__item___inner">
                                    <div class="course_faq__item-author">
                                        <img src="<?= LINK ?>/assets/img/house.jpg">
                                        <strong><?= get_data($item['user'], 'name', 'users') ?></strong>
                                    </div>
                                    <div class="course_faq__item-content">
                                        <h4><?= $item['title'] ?></h4>
                                        <p><?= substr(strip_tags($item['question']), 0, 380) ?>...</p>
                                    </div>
                                    <div class="course_faq__item-info">
                                        <div class="course_faq__item-info--inner">
                                            <div class="course_faq__item-info--inner---option">
                                                <i class="fa fa-thumbs-up"></i>
                                                <span class="show_like_count"><?= faqCountInfo($item['id'], 'course_faq_likes') ?></span>
                                            </div>
                                            <div class="course_faq__item-info--inner---option">
                                                <i class="fa fa-comments"></i>
                                                <span class="show_comment_count"><?= faqCountInfo($item['id'], 'course_faq_comments') ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                    </div>
                <?php } ?>

            </div>

        </div>
    </section>

    <?php include HOME . '/inc/faq.php'; ?>
    <?php include HOME . '/inc/question.php'; ?>

    <!-- Start . Section : Footer -->
    <?php include HOME . '/block/footer.php'; ?>
    <!-- End . Section : Footer -->

    <?php include HOME . '/block/scripts.php'; ?>
</body>

</html>