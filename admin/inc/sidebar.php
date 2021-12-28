<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

if (admin() == false) {
    redirect("Please login first!", ADMIN . '/login.php');
    exit();
}

define('NAVPAGE', basename($_SERVER["SCRIPT_NAME"], '.php'));
?>

<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">

                <?php if ($_SESSION['role'] == 'admin') { ?>
                <?php } ?>

                <?php if (!in_array($_SESSION['role'], array('writer', 'tutor'))) { ?>
                    <div class="sb-sidenav-menu-heading">Listings</div>
                    <a class="nav-link <?= NAVPAGE == 'update_listing' ? 'current' : ''; ?>" href="<?= ADMIN ?>/update_listing.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-edit"></i></div>
                        <span>Add New Listing</span>
                    </a>
                    <a class="nav-link <?= NAVPAGE == 'complete_listings' ? 'current' : ''; ?>" href="<?= ADMIN ?>/complete_listings.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-comment-check"></i></div>
                        <span>Fertige Einträge</span>
                    </a>
                    <a class="nav-link <?= NAVPAGE == 'pending_listings' ? 'current' : ''; ?>" href="<?= ADMIN ?>/pending_listings.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-comment-slash"></i></div>
                        <span>Ausstehende Einträge</span>
                    </a>
                    <a class="nav-link <?= NAVPAGE == 'archived_listings' ? 'current' : ''; ?>" href="<?= ADMIN ?>/archived_listings.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-comment-dots"></i></div>
                        <span>Archivierte Einträge</span>
                    </a>
                    <a class="nav-link <?= NAVPAGE == 'equipments' ? 'current' : ''; ?>" href="<?= ADMIN ?>/equipments.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-link"></i></div>
                        <span>Ausstattung</span>
                    </a>
                    <a class="nav-link <?= NAVPAGE == 'report_requests' ? 'current' : ''; ?>" href="<?= ADMIN ?>/report_requests.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-flag"></i></div>
                        <span>Report Requests</span>
                    </a>
                    <a class="nav-link <?= NAVPAGE == 'bugs_report' ? 'current' : ''; ?>" href="<?= ADMIN ?>/bugs_report.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-bug"></i></div>
                        <span>Listing Bugs</span>
                    </a>
                <?php } ?>

                <?php if (!in_array($_SESSION['role'], array('manager', 'tutor'))) { ?>
                    <div class="sb-sidenav-menu-heading">Blog</div>
                    <a class="nav-link <?= NAVPAGE == 'new_post' ? 'current' : ''; ?>" href="<?= ADMIN ?>/new_post.php">
                        <div class="sb-nav-link-icon"><i class="fa fa-copy"></i></div>
                        <span>Add New Post</span>
                    </a>
                    <a class="nav-link <?= NAVPAGE == 'manage_blog' ? 'current' : ''; ?>" href="<?= ADMIN ?>/manage_blog.php">
                        <div class="sb-nav-link-icon"><i class="fa fa-th-list"></i></div>
                        <span>Manage Blogs</span>
                    </a>
                    <a class="nav-link <?= NAVPAGE == 'blog_cat' ? 'current' : ''; ?>" href="<?= ADMIN ?>/blog_cat.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-folder-open"></i></div>
                        <span>Blog Categories</span>
                    </a>
                <?php } ?>

                <?php if (!in_array($_SESSION['role'], array('manager', 'writer'))) { ?>
                    <div class="sb-sidenav-menu-heading">Courses</div>
                    <a class="nav-link <?= NAVPAGE == 'new_course' ? 'current' : ''; ?>" href="<?= ADMIN ?>/new_course.php">
                        <div class="sb-nav-link-icon"><i class="fa fa-play-circle"></i></div>
                        <span>Add New Course</span>
                    </a>
                    <a class="nav-link <?= NAVPAGE == 'manage_courses' ? 'current' : ''; ?>" href="<?= ADMIN ?>/manage_courses.php">
                        <div class="sb-nav-link-icon"><i class="fa fa-photo-video"></i></div>
                        <span>Manage Courses</span>
                    </a>
                    <?php if (!in_array($_SESSION['role'], array('tutor'))) { ?>
                        <a class="nav-link <?= NAVPAGE == 'course_authors' ? 'current' : ''; ?>" href="<?= ADMIN ?>/course_authors.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-podcast"></i></div>
                            <span>Courses Authors</span>
                        </a>
                    <?php } else { ?>
                        <a class="nav-link <?= NAVPAGE == 'new_author' ? 'current' : ''; ?>" href="<?= ADMIN ?>/new_author.php?edit=<?= get_col_data($_SESSION['admin'], 'tutor', 'id', 'course_author') ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-podcast"></i></div>
                            <span>Edit Profile</span>
                        </a>
                    <?php } ?>
                <?php } ?>

                <?php if (!in_array($_SESSION['role'], array('manager', 'writer', 'tutor'))) { ?>
                    <div class="sb-sidenav-menu-heading">Seminars</div>
                    <a class="nav-link <?= NAVPAGE == 'new_seminar' ? 'current' : ''; ?>" href="<?= ADMIN ?>/new_seminar.php">
                        <div class="sb-nav-link-icon"><i class="fa fa-chalkboard-teacher"></i></div>
                        <span>Add New Seminar</span>
                    </a>
                    <a class="nav-link <?= NAVPAGE == 'manage_seminar' ? 'current' : ''; ?>" href="<?= ADMIN ?>/manage_seminar.php">
                        <div class="sb-nav-link-icon"><i class="fa fa-users-class"></i></div>
                        <span>Manage Seminars</span>
                    </a>
                <?php } ?>

                <?php if (!in_array($_SESSION['role'], array('manager', 'writer', 'tutor'))) { ?>
                    <div class="sb-sidenav-menu-heading">Consulting</div>
                    <a class="nav-link <?= NAVPAGE == 'new_consultant' ? 'current' : ''; ?>" href="<?= ADMIN ?>/new_consultant.php">
                        <div class="sb-nav-link-icon"><i class="fa fa-hands-helping"></i></div>
                        <span>Add New Consultant</span>
                    </a>
                    <a class="nav-link <?= NAVPAGE == 'manage_consultants' ? 'current' : ''; ?>" href="<?= ADMIN ?>/manage_consultants.php">
                        <div class="sb-nav-link-icon"><i class="fa fa-user-headset"></i></div>
                        <span>Manage Consultants</span>
                    </a>
                <?php } ?>

                <?php if (!in_array($_SESSION['role'], array('manager', 'writer', 'tutor'))) { ?>
                    <div class="sb-sidenav-menu-heading">Users</div>
                    <a class="nav-link <?= NAVPAGE == 'manage_users' ? 'current' : ''; ?>" href="<?= ADMIN ?>/manage_users.php">
                        <div class="sb-nav-link-icon"><i class="fa fa-users"></i></div>
                        <span>Manage Users</span>
                    </a>
                    <a class="nav-link <?= NAVPAGE == 'subscriptions' ? 'current' : ''; ?>" href="<?= ADMIN ?>/subscriptions.php">
                        <div class="sb-nav-link-icon"><i class="fa fa-crown"></i></div>
                        <span>Subscriptions</span>
                    </a>
                    <a class="nav-link <?= NAVPAGE == 'delete_requests' ? 'current' : ''; ?>" href="<?= ADMIN ?>/delete_requests.php">
                        <div class="sb-nav-link-icon"><i class="fa fa-user-slash"></i></div>
                        <span>Delete Requests</span>
                    </a>
                    <a class="nav-link <?= NAVPAGE == 'webinar' ? 'current' : ''; ?>" href="<?= ADMIN ?>/webinar.php">
                        <div class="sb-nav-link-icon"><i class="fa fa-user-clock"></i></div>
                        <span>Webinar Questions</span>
                    </a>
                <?php } ?>

                <?php if (!in_array($_SESSION['role'], array('manager', 'writer', 'tutor'))) { ?>
                    <div class="sb-sidenav-menu-heading">Coupons</div>
                    <a class="nav-link <?= NAVPAGE == 'new_coupon' ? 'current' : ''; ?>" href="<?= ADMIN ?>/new_coupon.php">
                        <div class="sb-nav-link-icon"><i class="fa fa-tags"></i></div>
                        <span>Create Coupon</span>
                    </a>
                    <a class="nav-link <?= NAVPAGE == 'manage_coupons' ? 'current' : ''; ?>" href="<?= ADMIN ?>/manage_coupons.php">
                        <div class="sb-nav-link-icon"><i class="fa fa-ticket-alt"></i></div>
                        <span>Manage Coupons</span>
                    </a>
                <?php } ?>

                <?php if (!in_array($_SESSION['role'], array('manager', 'writer', 'tutor'))) { ?>
                    <div class="sb-sidenav-menu-heading">Logs</div>
                    <a class="nav-link <?= NAVPAGE == 'paypal_logs' ? 'current' : ''; ?>" href="<?= ADMIN ?>/paypal_logs.php">
                        <div class="sb-nav-link-icon"><i class="fab fa-cc-paypal"></i></div>
                        <span>Paypal</span>
                    </a>
                    <a class="nav-link <?= NAVPAGE == 'paypal_logs_onetime' ? 'current' : ''; ?>" href="<?= ADMIN ?>/paypal_logs_onetime.php">
                        <div class="sb-nav-link-icon"><i class="fab fa-cc-paypal"></i></div>
                        <span>Paypal Onetime</span>
                    </a>
                    <a class="nav-link <?= NAVPAGE == 'stripe_logs' ? 'current' : ''; ?>" href="<?= ADMIN ?>/stripe_logs.php">
                        <div class="sb-nav-link-icon"><i class="fab fa-cc-stripe"></i></div>
                        <span>Stripe</span>
                    </a>
                    <a class="nav-link <?= NAVPAGE == 'stripe_logs_onetime' ? 'current' : ''; ?>" href="<?= ADMIN ?>/stripe_logs_onetime.php">
                        <div class="sb-nav-link-icon"><i class="fab fa-cc-stripe"></i></div>
                        <span>Stripe Onetime</span>
                    </a>
                <?php } ?>

                <?php if (!in_array($_SESSION['role'], array('manager', 'writer', 'tutor'))) { ?>
                    <div class="sb-sidenav-menu-heading">Site</div>
                    <a class="nav-link <?= NAVPAGE == 'admins' ? 'current' : ''; ?>" href="<?= ADMIN ?>/admins.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-users-cog"></i></div>
                        <span>Admins</span>
                    </a>
                    <a class="nav-link <?= NAVPAGE == 'attemps' ? 'current' : ''; ?>" href="<?= ADMIN ?>/attemps.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-hat-cowboy"></i></div>
                        <span>Attemps</span>
                    </a>
                    <a class="nav-link <?= NAVPAGE == 'inquiries' ? 'current' : ''; ?>" href="<?= ADMIN ?>/inquiries.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-phone-volume"></i></div>
                        <span>Inquiries</span>
                    </a>
                    <a class="nav-link <?= NAVPAGE == 'feedbacks' ? 'current' : ''; ?>" href="<?= ADMIN ?>/feedbacks.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-comment-alt-medical"></i></div>
                        <span>Feedbacks</span>
                    </a>
                    <a class="nav-link <?= NAVPAGE == 'faq' ? 'current' : ''; ?>" href="<?= ADMIN ?>/faq.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-life-ring"></i></div>
                        <span>F.A.Q</span>
                    </a>
                    <a class="nav-link <?= NAVPAGE == 'questions' ? 'current' : ''; ?>" href="<?= ADMIN ?>/questions.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-question-circle"></i></div>
                        <span>Questions</span>
                    </a>
                <?php } ?>

            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            <span><?= get_data($_SESSION['admin'], 'user', 'admin'); ?></span>
        </div>
    </nav>
</div>