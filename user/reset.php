<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

if (user() !== false) {
    header("Location: " . USER);
    exit();
}

$key = 0;
if (isset($_GET['key']) && !empty($_GET['key'])) {
    $key = 1;
    if (check_row($_GET['key'], 'pwd_key', 'users') == false) {
        user_redirect('Ungültiger Link', 'error', USER . '/reset/');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $p = $_POST;
    $error = '';

    if ($key == 1) {
        // if reset password page
        if (isset($p['pwd']) && !empty($p['pwd'])) {
            if (checkPassword($p['pwd']) == false) {
                $error = 'Dein Passwort ist nicht sicher';
            } else {
                if (isset($p['cpwd']) && !empty($p['cpwd'])) {
                    if ($p['pwd'] == $p['cpwd']) {
                        $pass = $p['pwd'];
                    } else {
                        $error = 'Die Passwörter sind nicht identisch';
                    }
                } else {
                    $error = 'Bitte Passwort Bestätigen';
                }
            }
        } else {
            $error = 'Bitte Passwort eingeben';
        }

        if ($error == '') {

            $accounts = $db->query('SELECT * FROM `users` WHERE pwd_key = ?', $_GET['key']);
            if ($accounts->numRows() == 1) {
                $accounts = $accounts->fetchArray();

                if (updateDatabyId(md5($pass), 'pwd', $accounts['id'], 'users')) {
                    updateDatabyId('', 'pwd_key', $accounts['id'], 'users');

                    user_redirect('Das Passwort wurde zurück gesetzt!', 'success', USER . '/login/');
                } else {
                    $error = 'Etwas lief falsch!';
                }
            } else {
                $error = 'Diese Email ist nicht registriert!';
            }
        }
    } else {
        // if mail page
        if (isset($p['user_email']) && isset($p['user_email'])) {
            if (empty($p['user_email'])) {
                $error = 'Bitte E-Mail eingeben';
            } else {
                $user_email = $p['user_email'];
            }
        } else {
            $error = 'Bitte E-Mail eingeben & password';
        }

        if ($error == '') {

            $accounts = $db->query('SELECT * FROM `users` WHERE email = ?', $p['user_email']);
            if ($accounts->numRows() == 1) {
                $accounts = $accounts->fetchArray();

                $reset_key = randomKey(15);
                if (updateDatabyId($reset_key, 'pwd_key', $accounts['id'], 'users')) {
                    $reset_link = USER . '/reset/?key=' . $reset_key;

                    // @@mail : send confirmation email
                    reset_password($p['user_email'], $reset_link);

                    header("Location: " . USER . '/reset/?status=success');
                    //user_redirect('Password reset link have sent to your email', 'info', USER . '/reset/?status=success');
                } else {
                    $error = 'Etwas lief falsch!';
                }
            } else {
                $error = 'Diese Email ist nicht registriert!';
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title>Reset Password - Promised Land</title>
</head>

<body>

    <!-- Start . Section : Header -->
    <?php include HOME . '/block/header.php'; ?>
    <!-- End . Section : Header -->

    <!--Start . Section : Login -->

    <section id="login">
        <div class="container">


            <div class="row ">
                <div class="col-xl-4 col-lg-5 col-md-7 col-sm-12 col-12 mx-auto">
                    <div class="login">

                        <div class="login_title">
                            <h2>Passwort zurücksetzen</h2>
                        </div>

                        <?php if (isset($_GET['status']) && $_GET['status'] == 'success') { ?>
                            <div class="alert alert-success">Wir haben dir eine Mail zum Passwort zurücksetzen geschickt.</div>
                        <?php } else { ?>

                            <?php if (isset($error) && !empty($error)) {
                                echo '<div class="alert alert-danger">' . $error . '</div>';
                            } ?>

                            <div class="login_form">
                                <form id="user_reset" action="<?= fullUrl() ?>" method="POST">

                                    <?php if ($key == 1) { ?>
                                        <div class="login_form__input" data-bs-toggle="tooltip" data-bs-placement="right" title="Add 9 charachters or more, lowercase letters, uppercase letters, numbers and symbols to make the password really strong!">
                                            <div class="login_form__input-line password-strength">
                                                <i class="far fa-lock"></i>
                                                <input name="pwd" class="form-control password-strength__input" type="password" placeholder="Password" id="password" />
                                                <small class="password-strength__error text-danger js-hidden"></small>
                                            </div>
                                        </div>
                                        <div class="login_form__input">
                                            <div class="login_form__input-line">
                                                <i class="far fa-lock"></i>
                                                <input name="cpwd" class="form-control" type="password" placeholder="Confirm Password" id="password-confirm" />
                                            </div>
                                        </div>
                                        <div class="login_form__input">
                                            <div class="login_form__input-line">
                                                <div class="password-strength__bar-block progress">
                                                    <div class="password-strength__bar progress-bar bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="login_form__input">
                                            <div class="login_form__input-line">
                                                <i class="far fa-envelope"></i>
                                                <input class="form-control" type="email" name="user_email" placeholder="E-Mail" value="<?= isset($p['user_email']) ? $p['user_email'] : ''; ?>">
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <div class="login_form__btn register_form__btn">
                                        <a href="<?= LINK ?>/user/login/">Zurück zur Anmeldung</a>
                                        <button class="btn btn-dark" name="submit" <?= $key == 1 ? 'disabled' : ''; ?>>Absenden</button>
                                    </div>

                                </form>
                            </div>

                            <div class="login_other">
                                <span>oder</span>
                            </div>

                            <div class="login_account">
                                <span>Du hast kein Konto?</span>
                                <a href="<?= LINK ?>/user/register/">Registrieren</a>
                            </div>

                        <?php } ?>

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