<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once './config/config.php';

$course = $db->query('SELECT * FROM `course`;')->fetchAll();
//$course = $db->query('SELECT * FROM `course` WHERE id = 10;')->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title>Videobibliothek - Promised Land</title>
</head>

<body>

    <!-- Start . Section : Header -->
    <?php include HOME . '/block/header.php'; ?>
    <!-- End . Section : Header -->

    <!-- Start . Section : Title -->
    <section id="title" class="no-gap">
        <div class="page_header">
            <div class="container">
                <div class="page_header__title">
                    <h2>Videobibliothek</h2>
                    <p>Hier findest du verschiedene Videos undaufgenommene <br>Seminare rund um Zwangsversteigerungen.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- End . Section : Title -->

    <!-- Start . Section : Quote -->
    <section id="quote" class="sm">
        <div class="container">
            <div class="quote">
                <div class="quote_title">
                    <h4>Eine Investition in Wissen bringt noch immer die besten Zinsen.</h4>
                    <p>-Benjamin Franklin </p>
                </div>
            </div>
        </div>
    </section>
    <!-- Start . Section : Quote -->

    <section id="videos" class="no-top">
        <div class="container">


            <?php if (isset($course) && !empty($course)) { ?>
                <div class="course_landing">
                    <div class="row row-cols-xl-4 row-cols-lg-3 row-cols-md-2 row-cols-sm-1 row-cols-1">
                        <?php foreach ($course as $item) { ?>
                            <div class="col">
                                <div class="video_item">
                                    <div class="video_item-img">
                                        <img src="<?= LINK . $item['image'] ?>">
                                        <div class="course_title__img-play" data-title="Intro: <?= $item['title'] ?>" data-intro="<?= LINK . $item['intro'] ?>">
                                            <i class="fa fa-play"></i>
                                        </div>
                                    </div>
                                    <div class="video_item-title">
                                        <a href="<?= LINK . '/courses/' . $item['slug'] ?>/"><?= $item['title'] ?></a>
                                    </div>
                                    <div class="video_item-meta">
                                        <div class="video_item-meta--item">
                                            <i class="fa fa-clock"></i>
                                            <span><?= gmdate("H:i:s", courseVideoInfo($item['id'], 'duration')) ?></span>
                                        </div>
                                        <div class="video_item-meta--item">
                                            <i class="fa fa-folder-open"></i>
                                            <span><?= courseVideoInfo($item['id'], 'videos') ?> Videos</span>
                                        </div>
                                    </div>
                                    <div class="video_item-info">
                                        <p><?= substr($item['content'], 0, 100) ?>...</p>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } else { ?>

                <div class="coming_soon">
                    <div class="coming_soon__txt">
                        <h4>Coming Soon!</h4>
                    </div>
                </div>

            <?php } ?>


        </div>
    </section>

    <!-- Intro Modal -->
    <?php include HOME . '/inc/intro.php'; ?>

    <!-- Start . Section : Footer -->
    <?php include HOME . '/block/footer.php'; ?>
    <!-- End . Section : Footer -->

    <?php include HOME . '/block/scripts.php'; ?>

</body>

</html>