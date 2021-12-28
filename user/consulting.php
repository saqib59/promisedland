<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once  '../inc/account/logged.php';

$consulting = $db->query('SELECT * FROM `consulting_booking` WHERE `user_id` = ? ORDER BY `id` DESC;', $user)->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title>Beratung - Promised Land</title>
</head>

<body>

    <!-- Start . Section : Header -->
    <?php include HOME . '/block/header.php'; ?>
    <!-- End . Section : Header -->

    <section id="account">
        <div class="account">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12">
                        <?php include HOME . '/inc/account/sidebar.php'; ?>
                    </div>
                    <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12 col-12">
                        <div class="account_body">
                            <section id="manage" class="no-gap">

                                <div class="account_body__title">
                                    <h4>Beratung</h4>
                                    <p>Hier findest du die gebuchten Beratungstermine und kannst Bewertungen für deinen Berater vergeben.</p>
                                </div>

                                <div class="account_body__content">

                                    <div class="account_tables account_consulting table-responsive">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Berater</th>
                                                    <th>Datei</th>
                                                    <th>Preis</th>
                                                    <th>Zeit</th>
                                                    <th>gebucht am</th>
                                                    <th>Status</th>
                                                    <th>Bewertung</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($consulting as $item) { ?>
                                                    <tr>
                                                        <td><?= $subject = get_data($item['consultant_id'], 'title', 'consulting') ?></td>
                                                        <td><a href="<?= $item['files'] ?>" class="btn btn-dark btn-sm" target="_blank">File</a></td>
                                                        <td><?= priceClean(get_data($item['consultant_id'], 'price', 'consulting')) ?>&euro;</td>
                                                        <td><?= get_data($item['consultant_id'], 'time', 'consulting') ?></td>
                                                        <td><?= dayOnly($item['insert_at']) ?></td>
                                                        <td><?php
                                                            if ($item['status'] == 'pending') {
                                                                echo '<span class="text-primary">Bestätigung ausstehend</span>';
                                                            } elseif ($item['status'] == 'approved') {
                                                                echo '<span class="text-success">Bestätigt</span>';
                                                            } elseif ($item['status'] == 'cancelled') {
                                                                echo '<span class="text-danger">Cancelled</span>';
                                                            } elseif ($item['status'] == 'attended') {
                                                                echo '<span class="text-success">Teilgenommen</span>';
                                                            } elseif ($item['status'] == 'notattended') {
                                                                echo '<span class="text-info">Not Attended</span>';
                                                            }
                                                            ?></td>
                                                        <td class="text-center">
                                                            <?php if ($item['status'] == 'attended') { ?>
                                                                <?php if (checkFeedback('consulting_feedback', 'consultant_id', $item['consultant_id'], $user)) { ?>
                                                                    <button class="btn btn-sm btn-success consulting_feedback_view" data-row="<?= $item['consultant_id'] ?>" data-type="consulting">Ansehen</button>
                                                                <?php } else { ?>
                                                                    <button class="btn btn-sm btn-blue consulting_feedback_btn" data-subject="<?= $subject ?>" data-row="<?= $item['consultant_id'] ?>" data-type="consulting">Beitrag</button>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <button class="btn btn-sm btn-blue" disabled>Beitrag</button>
                                                            <?php } ?>
                                                        </td>
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