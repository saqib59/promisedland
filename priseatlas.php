<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once './config/config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title>Priseatlas - Promised Land</title>
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
                    <h2>Priseatlas</h2>
                    <p>Der Preisatlas ermöglicht dir einen Überblick über <br>Deutschlands Miet- und Kaufpreise basierend auf den Postleitzahlen </p>
                </div>
            </div>
        </div>
    </section>
    <!-- End . Section : Title -->

    <section id="calculator">
        <div class="container">

            <div class="coming_soon mb-4">
                <div class="coming_soon__txt">
                    <h4>Coming Soon!</h4>
                </div>
            </div>

            <div class="finance">
                <div class="finance_body">
                    <div class="overlay just_hide"></div>
                    <?php include HOME . '/inc/atlas.php'; ?>
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