<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

if (!isset($_GET['slug']) || empty($_GET['slug'])) {
    redirect("Ungültiger Link", LINK);
}
$course_slug = $_GET['slug'];

$user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;

// information
$course = $db->query('SELECT * FROM `course` WHERE `slug` = ?', $course_slug)->fetchArray();
if (!$course) {
    redirect("Ungültiger Link", LINK);
}

// learn
$learn = $db->query('SELECT * FROM `course_learn` WHERE `course_id` = ?', $course['id'])->fetchArray();

// videos
$videos = $db->query('SELECT * FROM `course_video` WHERE `course_id` = ?', $course['id'])->fetchArray();

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

    <section id="course_title" class="light">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                    <div class="course_title__info">
                        <div class="course_title__info-content">
                            <h2><?= $course['title'] ?></h2>
                            <div class="course_title__info-content--info"><?= $course['content'] ?></div>
                        </div>

                        <?php if (!empty($user) && checkCourse($user, $course['id'])) { ?>
                            <div class="course_title__info-btn">
                                <a href="<?= LINK ?>/courses/view/<?= $course_slug ?>/" class="btn btn-blue">
                                    <i class="fa fa-play-circle"></i>
                                    <span>Browse Course</span>
                                </a>
                            </div>
                        <?php } else { ?>
                            <div class="course_title__info-btn">
                                <form action="<?= LINK ?>/course/purchase/" method="POST">

                                    <input name="course_id" type="hidden" value="<?= $course['id'] ?>">

                                    <?php if ($course['price'] == '0' || $course['price'] == '0,00') { ?>
                                        <button class="btn btn-white">Jetzt kostenfrei ansehen</button>
                                    <?php } else { ?>
                                        <?php if (contentStatus(array('premium', 'plus'))) { ?>
                                            <button class="btn btn-white">Jetzt kostenfrei ansehen</button>
                                        <?php } else { ?>
                                            <a href="<?= LINK ?>/packages/" class="btn btn-blue">Kaufe Premium und nimm kostenlos teil</a>
                                            <button class="btn btn-white">Jetzt für <?= $course['price'] ?>&euro; ansehen</button>
                                        <?php } ?>
                                    <?php } ?>

                                </form>
                            </div>
                        <?php } ?>

                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-8 col-sm-12 col-12 offset-xl-2 offset-lg-2 offset-md-2">
                    <div class="course_title__img">
                        <img src="<?= LINK . $course['image'] ?>">
                        <div class="course_title__img-play" data-title="Intro: <?= $course['title'] ?>" data-intro="<?= LINK . $course['intro'] ?>">
                            <i class="fa fa-play"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    if ($learn) {
        $course_content = json_decode($learn['course_content'], true);
        if (!empty($course_content) && $course_content !== '{}') { ?>
            <section id="course_learn" class="no-bot">
                <div class="container">

                    <div class="course_learn__title">
                        <h4>Du lernst:</h4>
                    </div>

                    <div class="course_learn__body">
                        <div class="score_list meant">
                            <div class="row row-cols-xl-3 row-cols-lg-3 row-cols-md-2 row-cols-sm-1 row-cols-1">

                                <?php foreach ($course_content["learn"] as $item) { ?>
                                    <div class="col">
                                        <div class="score_list__item">
                                            <div class="score_list__item-title">
                                                <i class="fa fa-check"></i>
                                                <strong><?= $item['title'] ?></strong>
                                            </div>
                                            <div class="score_list__item-body">
                                                <p><?= $item['content'] ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                    </div>

                </div>
            </section>
    <?php }
    } ?>

    <?php
    if ($videos) {
        $videos = json_decode($videos['videos'], true);
        if (!empty($videos) && $videos !== '{}') { ?>
            <section id="course_list">
                <div class="container">

                    <div class="course_learn__title">
                        <h4>Kursinhalte:</h4>
                    </div>

                    <div class="course_learn__body">

                        <div class="course_list">

                            <?php foreach ($videos["course"] as $item) { ?>
                                <div class="course_list__item">
                                    <div class="course_list__item-icon">
                                        <i class="fa fa-play-circle"></i>
                                    </div>
                                    <div class="course_list__item-body">
                                        <h4><span>(<?= $item['length'] >= 3600 ? gmdate("H:i:s", $item['length']) : gmdate("i:s", $item['length']); ?>)</span><?= $item['title'] ?></h4>
                                        <p><?= substr($item['content'], 0, 140) ?>...</p>
                                    </div>
                                </div>
                            <?php } ?>

                        </div>

                    </div>

                </div>
            </section>
    <?php }
    } ?>

    <!-- Intro Modal -->
    <?php include HOME . '/inc/intro.php'; ?>

    <!-- Start . Section : Footer -->
    <?php include HOME . '/block/footer.php'; ?>
    <!-- End . Section : Footer -->

    <?php include HOME . '/block/scripts.php'; ?>
</body>

</html>