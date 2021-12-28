<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $post_id = $_GET['id'];
    $data = $db->query("SELECT * FROM `blog` WHERE `status` = '1' AND `id` = ?", $post_id)->fetchArray();

    if (!$data || empty($data)) {
        redirect('Ungültiger Link', LINK);
    }

    if ($data['status'] == '0') {
        if (isset($_SESSION) && !empty($_SESSION['admin'])) {
            //
        } else {
            redirect('Ungültiger Link', LINK);
        }
    }
} else {
    redirect('Ungültiger Link', LINK);
}

$gallery = '';
if (!empty($data['gallery'])) {
    $gallery = json_decode($data['gallery'], true);
}

// blog nav
$previous_post = $db->query("SELECT * FROM `blog` WHERE `status` = '1' AND `id` = (SELECT min(id) FROM `blog` WHERE `id` < ?)", $post_id)->fetchArray();
$next_post = $db->query("SELECT * FROM `blog` WHERE `status` = '1' AND `id` = (SELECT min(id) FROM `blog` WHERE `id` > ?)", $post_id)->fetchArray();

//dump($previous_post);
//dump($next_post);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title><?= $data['title'] ?> - Promised Land</title>
</head>

<body>

    <!-- Start . Section : Header -->
    <?php include HOME . '/block/header.php'; ?>
    <!-- End . Section : Header -->

    <!-- Start . Section : Post -->
    <section id="post">
        <div class="container">
            <div class="post">

                <!-- Start Slider -->
                <div class="post_slider">

                    <?php
                    if (!empty($gallery)) {
                        if (count($gallery) == 1) { ?>
                            <div class="post_slider__image">
                                <img src="<?= LINK . $gallery[0] ?>">
                            </div>
                        <?php
                        } else { ?>


                            <div id="carouselIndicators" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-indicators">
                                    <button type="button" data-bs-target="#carouselIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                    <button type="button" data-bs-target="#carouselIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                    <button type="button" data-bs-target="#carouselIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                </div>
                                <div class="carousel-inner">
                                    <?php foreach ($gallery as $k => $value) {
                                        if ($k == '0') {
                                            echo '<div class="carousel-item active">';
                                        } else {
                                            echo '<div class="carousel-item">';
                                        }

                                        echo '<div class="slider_item">
                                                <img src="' . LINK . $value . '" class="d-block w-100" alt="' . $data['title'] . '">
                                            </div>
                                        </div>';
                                    }
                                    ?>

                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselIndicators" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselIndicators" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>

                <div class="step_post">

                    <div class="step_post__title">
                        <h4><?= $data['title'] ?></h4>
                    </div>

                    <div class="step_post__meta">
                        <div class="post_meta__date">
                            <i class="fal fa-calendar-alt"></i>
                            <p>Zuletzt aktualisiert: <?= date_format(date_create($data['updated_at']), "d.m.Y"); ?> by <span><?= get_data($data['author'], 'user', 'admin') ?></span></p>
                        </div>
                        <div class="post_meta__cat">
                            <i class="fas fa-paperclip"></i>
                            <p><?= get_data($data['category'], 'cat_name', 'blog_cat') ?></p>
                        </div>
                        <div class="post_meta__comment">
                            <i class="far fa-comment"></i>
                            <p>1 Kommentar</p>
                        </div>
                    </div>

                    <div class="step_post__separator"></div>

                    <div class="step_post__content">
                        <?= $data['content'] ?>
                    </div>

                    <div class="step_post__tags">
                        <div class="post_tags__title">
                            <strong>Schlagwörter</strong>
                        </div>
                        <div class="post_tags__name">
                            <?php
                            $tags = explode(',', $data['tags']);
                            foreach ($tags as $tag) {
                                echo '<span>#' . $tag . '</span>';
                            }
                            ?>
                        </div>
                    </div>

                    <div class="step_post__nav">
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                <?php if ($previous_post && !empty($previous_post)) {
                                    echo blogNav($previous_post, 'prev');
                                } ?>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                <?php if ($next_post && !empty($next_post)) {
                                    echo blogNav($next_post, 'next');
                                } ?>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- Start . Section : Footer -->
    <?php include HOME . '/block/footer.php'; ?>
    <!-- End . Section : Footer -->

    <?php include HOME . '/block/scripts.php'; ?>
</body>

</html>