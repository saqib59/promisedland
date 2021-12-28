<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once './config/config.php';

$user_id = '';
if (user() !== false) {
    $user_id = $_SESSION['user'];
}

$consulting = $db->query('SELECT * FROM `consulting` WHERE `status` = 1;')->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title>Persönliche Beratung - Promised Land</title>
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
                    <h2>Persönliche Beratung</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut <br>labore et dolore magna aliqua. Ut enim ad minim veniam</p>
                </div>
            </div>
        </div>
    </section>
    <!-- End . Section : Title -->

    <!-- Start . Section : Seminar List -->
    <?php if (!$consulting) { ?>

        <section id="seminar">
            <div class="container">

                <div class="coming_soon">
                    <div class="coming_soon__txt">
                        <h4>Coming Soon!</h4>
                    </div>
                </div>

            </div>
        </section>

    <?php } else { ?>
        <?php foreach ($consulting as $k => $item) { ?>

            <section id="consulting" class="<?= ($k % 2 == 0) ? 'white' : ''; ?> ">
                <div class="container">

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="consulting_info">

                                <div class="consulting_info-title">
                                    <?php if (isset($item['title']) && !empty($item['title'])) { ?>
                                        <h4><?= $item['title'] ?></h4>
                                    <?php } ?>
                                </div>
                                <div class="consulting_info-content">
                                    <?php if (isset($item['content']) && !empty($item['content'])) { ?>
                                        <p><?= $item['content'] ?></p>
                                    <?php } ?>
                                </div>

                                <?php
                                $checkFeeds = getRowCount('consulting_feedback', 'consultant_id', $item['id']);
                                if ($checkFeeds !== 0) { ?>
                                    <div class="consulting_info-rating consulting_show_feedbacks" data-subject="<?= $item['title'] ?>" data-consultant="<?= $item['id'] ?>">
                                        <div class="consulting_info-rating--stars">
                                            <?php
                                            $avgRate = avgRating('consulting_feedback', 'consultant_id', $item['id']);
                                            for ($i = 0; $i < $avgRate; $i++) {
                                                echo '<i class="fa fa-star gold"></i>';
                                            }
                                            for ($i = 5; $i > $avgRate; $i--) {
                                                echo '<i class="fa fa-star"></i>';
                                            }
                                            ?>
                                        </div>
                                        <div class="consulting_info-rating--feedback">
                                            <span>(<?= $checkFeeds ?> Bewertungen)</span>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="consulting_info-rating">
                                        <div class="consulting_info-rating--feedback">
                                            <span>(No Feedbacks Yet)</span>
                                        </div>
                                    </div>
                                <?php } ?>

                                <?php if (user() !== false) { ?>
                                    <div class="consulting_info-btn">
                                        <button class="btn btn-blue consulting_booking" data-consulting="<?= $item['id'] ?>" data-subject="<?= $item['title'] ?>">
                                            <i class="fa fa-paper-plane"></i>
                                            <span>Buchen</span>
                                        </button>
                                    </div>
                                <?php } else { ?>
                                    <a href="<?= USER ?>/login/" class="btn btn-blue">
                                        <i class="fa fa-sign-out-alt"></i>
                                        <span>Login to Booking</span>
                                    </a>
                                <?php } ?>

                            </div>
                        </div>
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-12 offset-xl-1 offset-lg-1 offset-md-1">
                            <div class="consulting_video">
                                <?php if (isset($item['intro']) && !empty($item['intro'])) { ?>
                                    <video width="100%" controls>
                                        <source src="<?= LINK . $item['intro']; ?>" type="video/mp4">
                                        Your browser does not support HTML video.
                                    </video>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                </div>
            </section>

    <?php
        }
    }
    ?>
    <!-- End . Section : Seminar List -->

    <?php
    if (user() !== false) {
        include HOME . '/inc/consulting_booking.php';
    }
    ?>

    <?php include HOME . '/inc/feedback/all.php'; ?>

    <!-- Start . Section : Footer -->
    <?php include HOME . '/block/footer.php'; ?>
    <!-- End . Section : Footer -->

    <!-- Start . Scripts -->
    <?php include HOME . '/block/scripts.php'; ?>
    <!-- Start . Scripts -->

</body>

</html>