<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once  '../inc/account/logged.php';

$subscription = $db->query('SELECT * FROM `membership` WHERE `user_id` = ? ORDER BY `id` DESC;', $user)->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title>Mitgliedschaft - Promised Land</title>
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
                            <section id="manage" class="no-gap">

                                <div class="account_body__title">
                                    <h4>Mitgliedschaft</h4>
                                    <p>Hier kannst du deine Mitgliedschaft verwalten</p>
                                </div>

                                <div class="account_body__content">


                                    <?= userMembInfo($user, true); ?>

                                    <!-- <div class="ud_blocks">
                                        <div class="ud_blocks__item">
                                            <div class="ud_blocks__item-list">
                                                <ul>
                                                    <li>
                                                        <strong>Datum</strong>
                                                        <span>2021-11-03</span>
                                                    </li>
                                                    <li>
                                                        <strong>Paket</strong>
                                                        <span>Premium</span>
                                                    </li>
                                                    <li>
                                                        <strong>Laufzeit</strong>
                                                        <span></span>
                                                    </li>
                                                    <li>
                                                        <strong>Zahlungsmethode</strong>
                                                        <span>Stripe</span>
                                                    </li>
                                                    <li>
                                                        <strong>Ablaufdatum</strong>
                                                        <span>2022-05-03</span>
                                                    </li>
                                                    <li>
                                                        <strong>Status</strong>
                                                        <span>Bestätigung ausstehend</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div> -->

                                    <div class="account_tables account_seminar table-responsive">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th style="min-width: 120px">Datum</th>
                                                    <th>Paket</th>
                                                    <th style="min-width: 120px">Laufzeit</th>
                                                    <th>Zahlungsmethode</th>
                                                    <th>Ablaufdatum</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if ($subscription && !empty($subscription)) { ?>
                                                    <?php foreach ($subscription as $item) { ?>
                                                        <tr>
                                                            <td><?= dayOnly($item['start_dt']) ?></td>
                                                            <td><?= $item['plan'] == 'plus' ? 'Premium+' : 'Premium'; ?></td>
                                                            <td><?= $item['period'] ?> <?= $item['period'] == 1 ? 'Monat' : 'Monate' ?></td>
                                                            <td><?= ucfirst($item['gateway']) ?></td>
                                                            <td><?= dayOnly($item['end_dt']) ?></td>

                                                            <td><?php
                                                                if ($item['status'] == 'pending') {
                                                                    echo '<span class="text-primary">Bestätigung ausstehend</span>';
                                                                } elseif ($item['status'] == 'approved') {
                                                                    echo '<span class="text-success">Bestätigt</span>';
                                                                } elseif ($item['status'] == 'expired') {
                                                                    echo '<span class="text-danger">Abgelaufen</span>';
                                                                } elseif ($item['status'] == 'rejected') {
                                                                    echo '<span class="text-danger">Abgelehnt</span>';
                                                                }
                                                                ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <tr>
                                                        <td colspan="6">No Membership subscription History to show</td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include HOME . '/inc/feedback/view.php'; ?>
    <?php include HOME . '/inc/feedback/submit.php'; ?>

    <!-- Start . Section : Footer -->
    <?php include HOME . '/block/footer.php'; ?>
    <!-- End . Section : Footer -->

    <?php include HOME . '/block/scripts.php'; ?>

</body>

</html>