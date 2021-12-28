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

$search_process = 0;
$loop = array();

$seminar = $db->query('SELECT * FROM `seminar` WHERE `status` = 1;')->fetchAll();

$query = '';
$query .= 'SELECT * FROM `seminar` WHERE ';
//$query .= 'SELECT * FROM `seminar` WHERE id = 10 AND ';

//if ($_SERVER['REQUEST_METHOD'] === 'GET') {
if (!empty($_GET)) {
    $search_process = 1;
    $p = $_GET;

    if (isset($p['search']) && !empty($p['search'])) {
        $search = strip_tags($p['search']);
        $query .= "`title` LIKE '%{$search}%' AND ";
    }
    if (isset($p['speaker']) && !empty($p['speaker'])) {
        $speaker = strip_tags($p['speaker']);
        $query .= "`speaker` LIKE '%{$speaker}%' AND ";
    }
    if (isset($p['date']) && !empty($p['date'])) {
        $date = strip_tags($p['date']);
        $query .= "`event_date` = '{$date}' AND ";
    }
    if (isset($p['method']) && !empty($p['method'])) {
        $method = strip_tags($p['method']);
        $query .= "`method` = '{$method}' AND ";
    }

    /* if (isset($p['ratings']) && !empty($p['ratings'])) {
        $ratings = strip_tags($p['ratings']);
        $query .= "`event_date` = '{$date}' AND ";
    } */

    $query .= '`status` = 1;';
    $seminar = $db->query($query)->fetchAll();
}

foreach ($seminar as $item) {
    $avgRate = avgRating('seminar_feedback', 'seminar_id', $item['id']);
    if (isset($p['ratings']) && !empty($p['ratings'])) {
        if (floatval($p['ratings']) == $avgRate) {
            $loop[] = $item;
        }
    } else {
        $loop[] = $item;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title>Unsere Seminare - Promised Land</title>
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
                    <?php if ($search_process == 0) { ?>
                        <h2>Unsere Seminare</h2>
                        <p>Egal ob du Fragen zu Zwangsversteigerungen, Teilungsversteigerungen oder Spezialgebieten hast, <br>bei unseren Seminaren ist für jeden das richtige dabei.</p>
                    <?php } else { ?>
                        <h2>Suchergebnisse:</h2>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
    <!-- End . Section : Title -->


    <!-- Start . Section : Search -->
    <section id="seminar" class="dark">
        <div class="container">
            <div class="seminar_search">

                <form action="<?= fullUrl() ?>" method="GET" autocomplete="off">
                    <div class="row row-cols-xl-6 row-cols-lg-6 row-cols-md-3 row-cols-sm-2 row-cols-1">
                        <div class="col">
                            <div class="seminar_search__field">
                                <input name="search" type="text" placeholder="Thema" class="form-control" value="<?= isset($p['search']) ? $p['search'] : ''; ?>">
                            </div>
                        </div>
                        <div class="col">
                            <div class="seminar_search__field">
                                <input name="speaker" type="text" placeholder="Sprecher" class="form-control" value="<?= isset($p['speaker']) ? $p['speaker'] : ''; ?>">
                            </div>
                        </div>
                        <div class="col">
                            <div class="seminar_search__field">
                                <input name="date" type="date" placeholder="Date" class="form-control" value="<?= isset($p['date']) ? $p['date'] : ''; ?>">
                            </div>
                        </div>
                        <div class="col">
                            <div class="seminar_search__field">
                                <select name="ratings" class="form-select" data-select="<?= isset($p['ratings']) ? $p['ratings'] : ''; ?>">
                                    <option value="">Bewertung</option>
                                    <option value="5">5 Stars</option>
                                    <option value="4">4 Stars</option>
                                    <option value="3">3 Stars</option>
                                    <option value="2">2 Stars</option>
                                    <option value="1">1 Star</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="seminar_search__field">
                                <select name="method" class="form-select" data-select="<?= isset($p['method']) ? $p['method'] : ''; ?>">
                                    <option value="">Methode</option>
                                    <option value="online">Online</option>
                                    <option value="person">In Person</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="seminar_search__field">
                                <button type="submit" class="btn btn-blue white">
                                    <i class="fa fa-file-search"></i>
                                    <span>Suchen</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </section>
    <!-- End . Section : Search -->

    <!-- Start . Section : Seminar List -->
    <?php if (empty($loop)) { ?>
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
        <?php foreach ($loop as $k => $item) { ?>

            <section id="consulting" class="<?= ($k % 2 == 0) ? 'white' : ''; ?> ">
                <div class="container">

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="consulting_info">

                                <div class="consulting_info-date">
                                    <?php if (isset($item['event_date']) && !empty($item['event_date'])) { ?>
                                        <div class="consulting_info-date--item">
                                            <i class="fa fa-calendar-minus"></i>
                                            <span><?= $item['event_date'] ?></span>
                                        </div>
                                    <?php } ?>
                                    <?php if (isset($item['location']) && !empty($item['location'])) { ?>
                                        <div class="consulting_info-date--item">
                                            <i class="fa fa-map-marker-alt"></i>
                                            <span><?= $item['location'] ?></span>
                                        </div>
                                    <?php } ?>
                                </div>

                                <div class="consulting_info-title">
                                    <?php if (isset($item['title']) && !empty($item['title'])) { ?>
                                        <h4><?= $item['title'] ?></h4>
                                    <?php } ?>
                                </div>

                                <?php if (strlen($item['content']) > 265) { ?>
                                    <div class="consulting_info-content half">
                                        <?php if (isset($item['content']) && !empty($item['content'])) { ?>
                                            <p><?= $item['content'] ?></p>
                                        <?php } ?>
                                        <div class="consulting_info-content--overlay">
                                            <span>Expand</span>
                                            <strong>Collapse</strong>
                                        </div>
                                        <div class="consulting_info-content--space"></div>
                                    </div>
                                <?php } else { ?>
                                    <div class="consulting_info-content">
                                        <?php if (isset($item['content']) && !empty($item['content'])) { ?>
                                            <p><?= $item['content'] ?></p>
                                        <?php } ?>
                                    </div>
                                <?php } ?>

                                <?php
                                $checkFeeds = getRowCount('seminar_feedback', 'seminar_id', $item['id']);
                                if ($checkFeeds !== 0) { ?>
                                    <div class="consulting_info-rating seminar_show_feedbacks" data-subject="<?= $item['title'] ?>" data-seminar="<?= $item['id'] ?>">
                                        <div class="consulting_info-rating--stars">
                                            <?php
                                            $avgRate = avgRating('seminar_feedback', 'seminar_id', $item['id']);
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
                                            <span>(Noch keine Bewertungen)</span>
                                        </div>
                                    </div>
                                <?php } ?>

                                <?php if (user() !== false) { ?>
                                    <?php if (!empty($user_id) && checkBooking('seminar_booking', 'seminar_id', $item['id'], $user_id)) { ?>
                                        <button class="btn btn-success" disabled>
                                            <i class="fa fa-check"></i>
                                            <span>Bereits gebucht</span>
                                        </button>
                                    <?php } else { ?>
                                        <div class="consulting_info-btn">
                                            <button class="btn btn-blue seminar_booking" data-seminar="<?= $item['id'] ?>" data-subject="<?= $item['title'] ?>" data-date="<?= $item['event_date'] ?>" data-method="<?= ucfirst($item['method']) ?>" data-location="<?= $item['location'] ?>">
                                                <i class="fa fa-paper-plane"></i>
                                                <span>Buchen</span>
                                            </button>
                                        </div>
                                    <?php } ?>
                                <?php } else { ?>
                                    <button class="btn btn-blue" disabled>
                                        <i class="fa fa-sign-out-alt"></i>
                                        <span>Login to Booking</span>
                                    </button>
                                <?php } ?>

                            </div>
                        </div>
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-12 offset-xl-1 offset-lg-1 offset-md-1">

                            <?php if (!empty($item['image'])) { ?>
                                <div class="course_title__img">
                                    <img src="<?= LINK . $item['image'] ?>">
                                    <?php if (!empty($item['intro'])) { ?>
                                        <div class="course_title__img-play" data-title="Intro: <?= $item['title'] ?>" data-intro="<?= LINK . $item['intro'] ?>">
                                            <i class="fa fa-play"></i>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } elseif (!empty($item['intro'])) { ?>
                                <div class="consulting_video">
                                    <?php if (isset($item['intro']) && !empty($item['intro'])) { ?>
                                        <video width="100%" controls>
                                            <source src="<?= LINK . $item['intro']; ?>" type="video/mp4">
                                            Your browser does not support HTML video.
                                        </video>
                                    <?php } ?>
                                </div>
                            <?php } ?>


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
        include HOME . '/inc/seminar_booking.php';
    }
    ?>

    <!-- Intro Modal -->
    <?php include HOME . '/inc/intro.php'; ?>

    <?php include HOME . '/inc/feedback/all.php'; ?>

    <!-- Start . Section : Footer -->
    <?php include HOME . '/block/footer.php'; ?>
    <!-- End . Section : Footer -->

    <!-- Start . Scripts -->
    <?php include HOME . '/block/scripts.php'; ?>
    <!-- Start . Scripts -->

</body>

</html>