<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once  '../inc/account/logged.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $error = '';
    $p = $_POST;
    if (isset($p['feedback']) && !empty($p['feedback'])) {
        if (strlen($p['feedback']) < 3) {
            $error = 'Please enter some better feedback';
        }
    } else {
        $error = 'Bitte gebe ein Feedback an';
    }

    if ($error == '') {
        $submit = $db->query("INSERT INTO `feedback`(`id`, `user_id`, `feedback`) VALUES (NULL, ?, ?);", $user, $p['feedback']);
        if ($submit) {
            user_redirect('Danke für dein Feedback!', 'success', LINK . '/user/feedback/');
        } else {
            user_redirect('Ewas lief falsch! Bitte erneut probieren.!', 'error', LINK . '/user/feedback/');
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title>Bewertungen - Promised Land</title>
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
                            <section id="feedback" class="no-gap">

                                <div class="account_body__title">
                                    <h4>Bewertungen</h4>
                                    <p>Sag uns was dir gefällt und gerne auch was wir verbessern können.</p>
                                </div>

                                <div class="account_body__content">
                                    <div class="account_feedback">

                                        <div class="account_feedback__alert">
                                            <?php if (isset($error) && !empty($error)) {
                                                echo '<div class="alert alert-danger">' . $error . '</div>';
                                            } ?>
                                        </div>

                                        <form action="<?= fullUrl() ?>" method="POST" autocomplete="off">
                                            <div class="account_feedback__input">
                                                <textarea name="feedback" class="form-control" value="<?= isset($p['feedback']) ? $p['feedback'] : ''; ?>"></textarea>
                                            </div>
                                            <div class="account_feedback__button">
                                                <button class="btn btn-blue">
                                                    <i class="fa fa-comment-alt-lines"></i>
                                                    <span>Absenden</span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </section>
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