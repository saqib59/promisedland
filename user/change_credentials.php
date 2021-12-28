<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once  '../inc/account/logged.php';

$data = $db->query('SELECT * FROM `users` WHERE `id` = ?', $user)->fetchArray();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $p = $_POST;
    $error = array();

    if (isset($p['email']) && !empty($p['email'])) {
        if (!filter_var($p['email'], FILTER_VALIDATE_EMAIL)) {
            $error[] = 'Bitte gebe eine gültige E-Mail Adresse ein';
        } else {
            if (check_row($p['email'], 'email', 'users')) {
                $error[] = 'Diese Email ist nicht registriert!';
            } else {
                $email = htmlentities($p['email']);
            }
        }
    } else {
        $error[] = 'Bitte E-Mail eingeben';
    }

    if (isset($p['pwd']) && !empty($p['pwd'])) {
        $clean_pw = htmlentities($p['pwd']);
        if ($clean_pw == $p['pwd']) {
            if (checkPassword($p['pwd']) == false) {
                $error[] = 'Dein Passwort ist nicht sicher';
            } else {
                if (isset($p['cpwd']) && !empty($p['cpwd'])) {
                    if ($p['pwd'] == $p['cpwd']) {
                        $pwd = md5($p['pwd']);
                    } else {
                        $error[] = 'Die Passwörter sind nicht identisch';
                    }
                } else {
                    $error[] = 'Bitte Passwort Bestätigen';
                }
            }
        } else {
            $p['pwd'] = '';
            $p['cpwd'] = '';
            $error[] = 'Bitte Passwort eingeben';
        }
    } else {
        $pwd = $data['pwd'];
    }

    if (empty($error)) {
        $update_credentials = $db->query('UPDATE `users` SET `email` = ?, `pwd` = ?, `updated_at` = CURRENT_TIMESTAMP WHERE id = ?;', $email, $pwd, $user);
        if ($update_credentials) {
            user_redirect('Kontoinformationen erfolgreich geupdated!', 'success', LINK . '/user/change_credentials/');
        } else {
            user_redirect('Ewas lief falsch! Bitte erneut probieren.!', 'error', LINK . '/user/change_credentials/');
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title>Anmeldeinformationen ändern - Promised Land</title>
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
                                    <h4>Logindaten ändern</h4>
                                    <p>Hier kannst du deine Logindaten ändern.</p>
                                </div>

                                <div class="account_body__content">

                                    <div class="account_credentials">

                                        <?php if (isset($error) && !empty($error)) {
                                            echo '<div class="alert alert-danger"><ul class="mb-0">';
                                            foreach ($error as $e) {
                                                echo '<li>' . $e . '</li>';
                                            }
                                            echo '</ul></div>';
                                        } ?>

                                        <form class="account_form" action="<?= fullUrl() ?>" method="POST">

                                            <div class="form-group">
                                                <label>E-Mail</label>
                                                <input type="email" class="form-control" name="email" value="<?= isset($p['email']) ? $p['email'] : $data['email']; ?>">
                                            </div>
                                            <div class="form-group row row-sm">
                                                <div class="col-md-6 col-12">
                                                    <label>Passwort</label>
                                                    <input type="text" class="form-control" name="pwd" value="" placeholder="">
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <label>Passwort bestätigen</label>
                                                    <input type="text" class="form-control" name="cpwd" value="" placeholder="">
                                                </div>
                                            </div>

                                            <div class="form-button">
                                                <button type="submit" class="btn btn-dark">
                                                    <i class="fa fa-key"></i>
                                                    <span>Aktualisieren</span>
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