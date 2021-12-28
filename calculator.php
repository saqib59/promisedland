<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once './config/config.php';

/* if (user() == false) {
    user_redirect("Please login first!", "warning", USER . '/login/');
    exit();
} */

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title>Zinsrechner - Promised Land</title>
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
                    <h2>Zinsrechner</h2>
                    <p>Zinskonditionen selbst berechnen</p>
                </div>
            </div>
        </div>
    </section>
    <!-- End . Section : Title -->

    <section id="calculator">
        <div class="container">

            <div class="calculators">

                <div class="calculators_buttons">
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#bauzinsrechner" type="button">Bauzinsrechner</button>

                        <?php if (contentStatus(array('premium', 'plus'))) { ?>
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#mietkaufrechner" type="button">Mietkaufrechner</button>
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#budgetrechner" type="button">Budgetrechner</button>
                        <?php } else { ?>
                            <button class="nav-link disabled" data-bs-toggle="tab" data-bs-target="#mietkaufrechner" type="button">Mietkaufrechner<span class="premium_label">Premium</span></button>
                            <button class="nav-link disabled" data-bs-toggle="tab" data-bs-target="#budgetrechner" type="button">Budgetrechner<span class="premium_label">Premium</span></button>
                        <?php } ?>

                        <?php if (contentStatus(array('plus'))) { ?>
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#bauzinsenchart" type="button">Bauzinsenchart</button>
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#hauskreditrechner" type="button">Hauskreditrechner</button>
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#grundbuchrechner" type="button">Grundbuchrechner</button>
                        <?php } else { ?>
                            <button class="nav-link disabled" data-bs-toggle="tab" data-bs-target="#bauzinsenchart" type="button">Bauzinsenchart<span class="premium_label">Premium+</span></button>
                            <button class="nav-link disabled" data-bs-toggle="tab" data-bs-target="#hauskreditrechner" type="button">Hauskreditrechner<span class="premium_label">Premium+</span></button>
                            <button class="nav-link disabled" data-bs-toggle="tab" data-bs-target="#grundbuchrechner" type="button">Grundbuchrechner<span class="premium_label">Premium+</span></button>
                        <?php } ?>
                    </div>
                </div>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="bauzinsrechner" role="tabpanel">
                        <?php include HOME . '/inc/calculator/bauzinsrechner.php'; ?>
                    </div>

                    <?php if (contentStatus(array('premium', 'plus'))) { ?>
                        <div class="tab-pane fade" id="mietkaufrechner" role="tabpanel">
                            <?php include HOME . '/inc/calculator/mietkaufrechner.php'; ?>
                        </div>
                        <div class="tab-pane fade" id="budgetrechner" role="tabpanel">
                            <?php include HOME . '/inc/calculator/budgetrechner.php'; ?>
                        </div>
                    <?php } ?>

                    <?php if (contentStatus(array('plus'))) { ?>
                        <div class="tab-pane fade" id="bauzinsenchart" role="tabpanel">
                            <?php include HOME . '/inc/calculator/bauzinsenchart.php'; ?>
                        </div>
                        <div class="tab-pane fade" id="hauskreditrechner" role="tabpanel">
                            <?php include HOME . '/inc/calculator/hauskreditrechner.php'; ?>
                        </div>
                        <div class="tab-pane fade" id="grundbuchrechner" role="tabpanel">
                            <?php include HOME . '/inc/calculator/grundbuchrechner.php'; ?>
                        </div>
                    <?php } ?>

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