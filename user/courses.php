<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once  '../inc/account/logged.php';

$courses = $db->query('SELECT * FROM `course_subscribe` WHERE `user_id` = ? ORDER BY `id` DESC;', $user)->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title>Kurse - Promised Land</title>
</head>

<body>

    <!-- Start . Section : Header -->
    <?php include HOME . '/block/header.php'; ?>
    <!-- End . Section : Header -->

    <section id="account">
        <div class="account">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                        <?php include HOME . '/inc/account/sidebar.php'; ?>
                    </div>
                    <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
                        <div class="account_body">
                            <section id="manage" class="no-gap">

                                <div class="account_body__title">
                                    <h4>Kurse</h4>
                                    <p>Du nimmst an einem Webinar teil? Den Link stellen wir dir hier zur Verfügung.</p>
                                </div>

                                <div class="account_body__content">

                                    <div class="user_courses">
                                        <div class="row row-cols-xl-3 row-cols-lg-2 row-cols-md-2 row-cols-sm-1 row-cols-1">

                                            <?php 
                                            foreach ($courses as $item) { 
                                                $info = $db->query('SELECT * FROM `course` WHERE `id` = ?;', $item['course_id'])->fetchArray();
                                            ?>
                                                <div class="col">
                                                    <div class="user_courses__item">
                                                        <div class="user_courses__item-image">
                                                            <img src="<?= LINK . $info['image'] ?>">
                                                        </div>
                                                        <div class="user_courses__item-info">
                                                            <h4><?= $info['title'] ?></h4>
                                                            <span>by <?= get_data($info['author'], 'name', 'course_author') ?></span>
                                                        </div>
                                                        <div class="user_courses__item-data">
                                                            <ul>
                                                                <li>
                                                                    <strong>Datum der Einschreibung :</strong>
                                                                    <span><?= dayOnly($item['insert_at']) ?></span>
                                                                </li>
                                                                <li>
                                                                    <strong>Zahlungsmethode :</strong>
                                                                    <span><?= ucfirst($item['gateway']) ?></span>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="user_courses__item-btn">
                                                            <a href="<?= LINK . '/courses/view/' . $info['slug'] . '/' ?>" class="btn btn-blue btn-sm btn-block">
                                                                <i class="fa fa-play-circle"></i>
                                                                <span>Videos ansehen</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                        </div>
                                    </div>

                                </div>

                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include HOME . '/inc/feedback/view.php'; ?>
    <?php include HOME . '/inc/feedback/submit.php'; ?>

    <!-- Start . Section : Footer -->
    <?php include HOME . '/block/footer.php'; ?>
    <!-- End . Section : Footer -->

    <?php include HOME . '/block/scripts.php'; ?>

</body>

</html>