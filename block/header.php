<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once HOME . '/config/config.php';

$user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;
?>

<section id="header" class="no-gap">
    <div class="header">

        <!-- Start . Header Top -->
        <div class="header_top">
            <div class="container">
                <div class="row align-items-center justify-content-between">
                    <div class="col-xl-4 col-lg-4 col-md-5 col-sm-4 col-12">
                        <div class="header_top__logo">
                            <a href="<?= LINK ?>">
                                <img src="<?= LINK ?>/assets/img/logo.png">
                            </a>
                            <div class="header_top__logo-beta">
                                <span>Beta-Version</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8 col-lg-8 col-md-7 col-sm-8 col-12">
                        <div class="header_top__menu">
                            <div class="header_top__menu-inner">

                                <?php if (user()) { ?>
                                    <div class="header_top__menu-inner--col">
                                        <div class="user_action">
                                            <div class="user_action__item user_favorite__menu">

                                                <?php if (check_row($user, 'user', 'user_alerts')) { ?>
                                                    <div class="user_action__item-dot"></div>
                                                <?php } ?>

                                                <div class="user_action__item-icon">
                                                    <i class="far fa-bell"></i>
                                                </div>
                                                <div id="user_favorite__menu" class="user_action__item-menu">
                                                    <?php include HOME . '/inc/dropdown/alerts.php'; ?>
                                                </div>
                                            </div>
                                            <div class="user_action__item">
                                                <a href="<?= USER ?>/favourite/" class="user_action__item-icon">
                                                    <i class="far fa-heart"></i>
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                <?php } ?>

                                <div class="header_top__menu-inner--col">

                                    <div class="user_menu">
                                        <div class="user_menu-inner">
                                            <?php if (user() == false) { ?>
                                                <div class="user_menu__avatar">
                                                    <img src="<?= LINK ?>/assets/img/user.jpg">
                                                </div>
                                                <div class="user_menu__info">
                                                    <h4>Hallo!</h4>
                                                    <div class="user_menu__info-links">
                                                        <a href="<?= USER ?>/login/">Anmelden</a>
                                                        <span></span>
                                                        <a href="<?= USER ?>/register/">Registrieren</a>
                                                    </div>
                                                </div>
                                            <?php } else { ?>
                                                <div class="user_menu__avatar">
                                                    <a href="<?= USER ?>">
                                                        <img src="<?= LINK . get_data($_SESSION['user'], 'image', 'users') ?>">
                                                    </a>
                                                </div>
                                                <div class="user_menu__info">
                                                    <?php $user_name = explode(' ', get_data($_SESSION['user'], 'name', 'users')) ?>
                                                    <h4>Hi, <span><?= substr(array_shift($user_name), 0, 15) ?></span>!</h4>
                                                    <div class="user_menu__info-links">
                                                        <a href="<?= USER ?>">Mein Konto</a>
                                                        <span></span>
                                                        <a href="<?= USER ?>/logout/">Abmelden</a>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End . Header Top -->

        <!-- Start . Header Bottom -->
        <div class="header_bot">
            <div class="container">

                <div class="header_bot__toggle">
                    <div class="header_bot__toggle-title">
                        <span>Menu</span>
                    </div>
                    <div class="header_bot__toggle-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </div>
                </div>

                <div class="header_bot__nav">
                    <div class="header_bot__nav-inner">
                        <a href="<?= LINK ?>/search/" class="header_bot__nav-inner--item">
                            <span>Immobilien</span>
                        </a>
                        <a href="<?= LINK ?>/finance/" class="header_bot__nav-inner--item">
                            <span>Finanzieren</span>
                        </a>
                        <a href="<?= LINK ?>/portal/" class="header_bot__nav-inner--item">
                            <span>Bildungsportal</span>
                        </a>
                        <a href="<?= LINK ?>/immomanager/" class="header_bot__nav-inner--item">
                            <span>4Rent</span>
                        </a>
                        <a href="<?= LINK ?>/priseatlas/" class="header_bot__nav-inner--item">
                            <span>Preisatlas</span>
                        </a>
                        <a href="<?= LINK ?>/blog/" class="header_bot__nav-inner--item">
                            <span>Blog</span>
                        </a>
                        <a href="<?= LINK ?>/consultant/" class="header_bot__nav-inner--item">
                            <span>Persönliche Beratung</span>
                        </a>
                        <a href="<?= LINK ?>/webinar/" class="header_bot__nav-inner--item showcase">
                            <span>Gratis Webinar</span>
                        </a>
                        <a href="<?= LINK ?>/packages/" class="header_bot__nav-inner--item highlight">
                            <!-- <strong>Coming Soon</strong> -->
                            <span>Premium</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
        <!-- Start . Header Bottom -->

    </div>
</section>