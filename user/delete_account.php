<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once  '../inc/account/logged.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $p = $_POST;
    $error = '';

    if (isset($p['delete']) && !empty($p['delete'])) {
        if ($p['delete'] !== 'DELETE') {
            $error = 'Korrektes Wort zur bestätigung eintragen';
        }
    } else {
        $error = 'Wort eingeben  zur Bestätigung ';
    }

    if (empty($error)) {

        if (check_row($user, 'user', 'user_delete')) {
            redirect("Die Löschung deines Accounts wurde beauftragt. Du wirst nun ausgeloggt ", USER . '/login/');
        } else {
            $request = $db->query('INSERT INTO `user_delete`(`id`, `user`) VALUES (NULL, ?);', $user);
        }
        
        if ($request) {
            session_destroy();
            user_redirect('Die Löschung deines Accounts wurde beauftragtr!', 'success', LINK . '/user/login/');
        } else {
            user_redirect('Ewas lief falsch! Bitte erneut probieren.!', 'error', LINK . '/user/delete_account/');
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title>Konto löschen - Promised Land</title>
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
                                    <h4>Account Löschen</h4>
                                    <p>Wenn du deinen Account löschen willst kannst du dies hier tun. BItte beachte, dies deine Daten unwiederruflich aus der Plattform entfernt und niemand mehr darauf Zugriff haben wird. </p>
                                </div>

                                <div class="account_body__content">

                                    <div class="account_credentials">

                                        <?php if (isset($error) && !empty($error)) {
                                            echo '<div class="alert alert-danger"><span>' . $error . '</span></div>';
                                        } ?>

                                        <form class="account_form" action="<?= fullUrl() ?>" method="POST" autocomplete="off">

                                            <div class="form-group">
                                                <label><strong>DELETE</strong> im Eingabefeld eingeben</label>
                                                <input type="text" class="form-control" name="delete" placeholder="Löschen im Eingabefeld eingeben ">
                                            </div>

                                            <div class="form-button">
                                                <button type="submit" class="btn btn-dark">
                                                    <i class="fa fa-user-slash"></i>
                                                    <span>Löschen</span>
                                                </button>
                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </section>
                        </div>
                    </div>
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