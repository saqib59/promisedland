<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';
require_once  HOME . '/inc/account/logged.php';

$alerts = $db->query("SELECT * FROM `user_alerts` WHERE `user` = ? ORDER BY updated_at DESC LIMIT 0, 4;", $user);
$all = $alerts->fetchAll();

?>

<?php if (isset($all) && !empty($all)) { ?>
    <?php foreach ($all as $item) { ?>
        <?php

        switch ($item['type']) {
            case 'course_subscribe':
                $icon = 'graduation-cap';
                break;
            default:
                $icon = 'check';
        }
        ?>

        <a href="<?= $item['link'] ?>" class="account_alerts__list-item">
            <div class="account_alerts__list-item--icon">
                <i class="fa fa-<?= $icon ?>"></i>
            </div>
            <div class="account_alerts__list-item--info">
                <p><?= dayOnly($item['insert_at']) ?></p>
                <h4><?= $item['alert'] ?></h4>
            </div>
        </a>

    <?php } ?>
<?php } else { ?>
    <div class="listing_item__alert">
        <div class="alert alert-info">
            <span>Keine neuen Benachrichtigungen</span>
        </div>
    </div>
<?php } ?>