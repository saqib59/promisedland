<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

define('NAVPAGE', basename($_SERVER["SCRIPT_NAME"], '.php'));
?>

<div class="account_menu">

    <div class="account_menu__mobile">
        <div class="account_menu__mobile-open">
            <i class="fa fa-bars"></i>
            <span>Open Menu</span>
        </div>
    </div>

    <div class="account_menu__list">
        <div class="account_menu__list-inner">
            <a href="<?= USER ?>/" class="account_menu__list--item <?= NAVPAGE == 'index' ? 'active' : ''; ?>">
                <i class="fa fa-user"></i>
                <span>Mein Profil</span>
            </a>
            <a href="<?= USER ?>/favourite/" class="account_menu__list--item <?= NAVPAGE == 'favourite' ? 'active' : ''; ?>">
                <i class="fa fa-heart"></i>
                <span>Favoriten</span>
            </a>
            <a href="<?= USER ?>/request/" class="account_menu__list--item <?= NAVPAGE == 'request' ? 'active' : ''; ?>">
                <i class="fa fa-search"></i>
                <span>Suchaufträge</span>
            </a>
            <a href="<?= USER ?>/subscription/" class="account_menu__list--item <?= NAVPAGE == 'subscription' ? 'active' : ''; ?>">
                <i class="fa fa-users"></i>
                <span>Mitgliedschaft</span>
            </a>
            <a href="<?= USER ?>/feedback/" class="account_menu__list--item <?= NAVPAGE == 'feedback' ? 'active' : ''; ?>">
                <i class="fa fa-comment-alt-lines"></i>
                <span>Bewertungen</span>
            </a>
            <a href="<?= USER ?>/questions/" class="account_menu__list--item <?= NAVPAGE == 'questions' ? 'active' : ''; ?>">
                <i class="fa fa-question-circle"></i>
                <span>Fragen</span>
            </a>
            <a href="<?= USER ?>/manage/" class="account_menu__list--item <?= (in_array(NAVPAGE, array('manage', 'change_details', 'change_credentials', 'delete_account'))) ? 'active' : ''; ?>">
                <i class="fa fa-cogs"></i>
                <span>Einstellungen</span>
            </a>
            <a href="<?= USER ?>/courses/" class="account_menu__list--item <?= NAVPAGE == 'courses' ? 'active' : ''; ?>">
                <i class="fa fa-graduation-cap"></i>
                <span>Kurse</span>
            </a>
            <a href="<?= USER ?>/seminar/" class="account_menu__list--item <?= NAVPAGE == 'seminar' ? 'active' : ''; ?>">
                <i class="fa fa-users-class"></i>
                <span>Seminare</span>
            </a>
            <a href="<?= USER ?>/consulting/" class="account_menu__list--item <?= NAVPAGE == 'consulting' ? 'active' : ''; ?>">
                <i class="fa fa-envelope-open-text"></i>
                <span>Beratung</span>
            </a>
            <a href="<?= USER ?>/logout/" class="account_menu__list--item">
                <i class="fa fa-sign-out-alt"></i>
                <span>Abmelden</span>
            </a>
        </div>
    </div>

</div>