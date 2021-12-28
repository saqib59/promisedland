<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once HOME . '/config/config.php';
require_once  HOME . '/inc/account/logged.php';

$alert_count = 0;
$alerts = $db->query("SELECT * FROM `user_alerts` WHERE `user` = ? ORDER BY updated_at DESC LIMIT 5;", $user);
$alert_count = $alerts->numRows();
$all = $alerts->fetchAll();

?>

<div class="user_action__item-menu--header">
    <h4>
        <span>Benachrichtigungen</span>
        <strong>(<?= $alert_count ?>)</strong>
    </h4>
</div>
<div class="user_action__item-menu--body">
    <div class="listing">

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
                <a href="<?= $item['link'] ?>" class="listing_item">
                    <div class="listing_item__inner">
                        <div class="listing_item__inner-img">
                            <i class="fa fa-<?= $icon ?>"></i>
                        </div>
                        <div class="listing_item__inner-info">
                            <span><?= dayOnly($item['insert_at']) ?></span>
                            <h4><?= $item['alert'] ?></h4>
                        </div>
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

    </div>
</div>
<div class="user_action__item-menu--footer">
    <a href="<?= USER ?>">
        <span>Jetzt ansehen</span>
        <i class="fa fa-arrow-right"></i>
    </a>
</div>