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

define('CURRENT', basename($_SERVER["SCRIPT_NAME"], '.php'));

if (isset($_GET['listing_id']) && !empty($_GET['listing_id'])) {
    $listing_id = $_GET['listing_id'];
?>

    <div class="listing_steps">
        <div class="listing_steps__inner">
            <a class="btn <?= CURRENT == 'update_listing' ? 'current' : ''; ?>" href="<?= ADMIN ?>/update_listing.php?listing_id=<?= $listing_id ?>">Step 1</a>
            <a class="btn <?= CURRENT == 'update_first' ? 'current' : ''; ?>" href="<?= ADMIN ?>/update_first.php?listing_id=<?= $listing_id ?>">Step 2</a>
            <a class="btn <?= CURRENT == 'update_second' ? 'current' : ''; ?>" href="<?= ADMIN ?>/update_second.php?listing_id=<?= $listing_id ?>">Step 3</a>
            <a class="btn <?= CURRENT == 'update_third' ? 'current' : ''; ?>" href="<?= ADMIN ?>/update_third.php?listing_id=<?= $listing_id ?>">Step 4</a>
        </div>
    </div>

<?php } ?>