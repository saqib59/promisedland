<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once  '../inc/account/logged.php';

$seminar = $db->query('SELECT * FROM `seminar_booking` WHERE `user_id` = ? ORDER BY `id` DESC;', $user)->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title>Seminare - Promised Land</title>
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
                                    <h4>Seminare</h4>
                                    <p>Hier kannst du ganz einfach deine gebuchten Seminare verwalten.</p>
                                </div>

                                <div class="account_body__content">

                                    <div class="account_tables account_seminar table-responsive">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Thema</th>
                                                    <th>Datum</th>
                                                    <th>gebucht am</th>
                                                    <th>Status</th>
                                                    <th>Bewertung</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($seminar as $item) { ?>
                                                    <tr>
                                                        <td><?= $subject = get_data($item['seminar_id'], 'title', 'seminar') ?></td>
                                                        <td><?= get_data($item['seminar_id'], 'event_date', 'seminar') ?></td>
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
                                                                <?php if (checkFeedback('seminar_feedback', 'seminar_id', $item['seminar_id'], $user)) { ?>
                                                                    <button class="btn btn-sm btn-success seminar_feedback_view" data-row="<?= $item['seminar_id'] ?>" data-type="seminar">Ansehen</button>
                                                                <?php } else { ?>
                                                                    <button class="btn btn-sm btn-blue seminar_feedback_btn" data-subject="<?= $subject ?>" data-row="<?= $item['seminar_id'] ?>" data-type="seminar">Beitrag</button>
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