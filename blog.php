<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once './config/config.php';

$all_posts = $db->query("SELECT * FROM `blog` WHERE category != 1 AND status = 1;");
$total_posts = $all_posts->numRows();
$posts = $all_posts->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title>Blog - Promised Land</title>
</head>

<body>

    <!-- Start . Section : Header -->
    <?php include HOME . '/block/header.php'; ?>
    <!-- End . Section : Header -->

    <!-- Page Title -->
    <section id="title" class="no-gap">
        <div class="page_header">
            <div class="container">
                <div class="page_header__title">
                    <h2>Blog</h2>
                    <p>All Recent Articles</p>
                </div>
            </div>
        </div>
    </section>

    <?php if ($posts && !empty($posts)) { ?>
        <?php foreach ($posts as $i => $item) { ?>
            <?php
            $number = (int)$i + 1;

            if (isset($item['gallery']) && !empty($item['gallery'])) {
                $gallery_list = json_decode($item['gallery'], true);
                $image = $gallery_list[0];
            }

            $tags = array();
            if (isset($item['tags']) && !empty($item['tags'])) {
                $tags = explode(',', $item['tags']);
            }

            $content = '';
            if (isset($item['content']) && !empty($item['tags'])) {
                $content = strip_tags($item['content']);
                $content = substr($content, 0, 300);
            }

            ?>
            <section class="<?= $total_posts !== $number ? 'no-bot' : '' ?>">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
                            <div class="step_post__box">

                                <?php if (isset($image) && !empty($image)) { ?>
                                    <div class="step_post__box-image">
                                        <img src="<?= LINK . $image ?>" alt="">
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                        <div class="col-xl-8 col-lg-8 col-md-6 col-sm-12 col-12">

                            <?php if (isset($tags) && !empty($tags)) { ?>
                                <div class="step_post__tags">
                                    <?php foreach ($tags as $tag) { ?>
                                        <span>#<?= ltrim($tag) ?></span>
                                    <?php } ?>
                                </div>
                            <?php } ?>

                            <div class="step_post__title">
                                <h4><?= $item['title'] ?></h4>
                            </div>
                            <div class="step_post__content">
                                <p><?= $content ?>...</p>
                            </div>
                            <div class="step_post_button">
                                <a href="<?= LINK ?>/article/<?= $item['id'] ?>/" class="btn btn-dark">Mehr erfahren</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        <?php } ?>
    <?php } else { ?>
        <section id="soon">
            <div class="container">
                <div class="coming_soon">
                    <div class="coming_soon__txt">
                        <h4>No Articles Found!</h4>
                    </div>
                </div>
            </div>
        </section>
    <?php } ?>

    <!-- Start . Section : Footer -->
    <?php include HOME . '/block/footer.php'; ?>
    <!-- End . Section : Footer -->

    <?php include HOME . '/block/scripts.php'; ?>
</body>

</html>