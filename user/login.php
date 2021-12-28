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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $p = $_POST;
    $error = '';

    if (isset($p['user_email']) && isset($p['user_email'])) {
        if (empty($p['user_email']) && empty($p['user_pass'])) {
            $error = 'Bitte E-Mail eingeben & password';
        } else {
            if (empty($p['user_email'])) {
                $error = 'Bitte E-Mail eingeben';
            } else {
                $user_email = $p['user_email'];
            }
            if (empty($p['user_pass'])) {
                $error = 'Passwort eingeben';
            } else {
                $user_pass = $p['user_pass'];
            }
        }
    } else {
        $error = 'Bitte E-Mail eingeben & password';
    }

    if ($error == '') {

        $accounts = $db->query('SELECT * FROM `users` WHERE email = ? AND pwd = ?', $_POST['user_email'], md5($_POST['user_pass']));
        if ($accounts->numRows() == 1) {
            $accounts = $accounts->fetchArray();

            if($accounts['verify'] == '0') {
                user_redirect("Bestätige deine E-Mail, um dich einzuloggen.", 'error', USER . '/login/');
            } elseif (check_row($accounts['id'], 'user', 'user_delete')) {
                user_redirect("Die Löschung deines Accounts ist in Bearbeitung", 'error', USER . '/login/');
            } else {
                $redirect_link = USER;
                if (isset($_SESSION['email'])) {
                    unset($_SESSION['email']);
                    $redirect_link = LINK . '/packages/';
                }

                if(isset($_GET['redirect'])) {
                    $redirect_link = LINK . htmlentities($_GET['redirect']);
                }

                if (isset($_POST['remember'])) {
                    $params = session_get_cookie_params();
                    setcookie(session_name(), $_COOKIE[session_name()], time() + 60 * 60 * 24 * 30, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
                }

                $_SESSION['user'] = $accounts['id'];
                $_SESSION['ip'] = getIP();
                //redirect('You have logged in successfully!', $redirect_link);
                header("Location:" . $redirect_link);
            }
        } else {
            $error = 'Die angegeben Kontoinformationen sind inkorrekt!';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title>Login - Promised Land</title>
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
                            <h2>Login</h2>
                        </div>

                        <?php if (isset($error) && !empty($error)) {
                            echo '<div class="alert alert-danger">' . $error . '</div>';
                        } ?>

                        <div class="login_form">
                            <form id="user_login" action="<?php echo fullUrl(); ?>" method="POST">

                                <div class="login_form__input">
                                    <div class="login_form__input-line">
                                        <i class="far fa-envelope"></i>
                                        <input class="form-control" type="email" name="user_email" placeholder="E-Mail" value="<?= isset($p['user_email']) ? $p['user_email'] : 'kaizer@mail.com'; ?>">
                                    </div>
                                </div>
                                <div class="login_form__input">
                                    <div class="login_form__input-line">
                                        <i class="far fa-lock"></i>
                                        <input class="form-control" type="password" name="user_pass" placeholder="Passwort" value="Kaiz12345#" required>
                                    </div>
                                </div>
                                <div class="login_form_check">
                                    <div>
                                        <input class="form-check-input" type="checkbox" value="" id="rememberme" name="remember">
                                        <label for="rememberme">Benutzer merken</label>
                                    </div>
                                </div>

                                <!-- Login & Forgot  -->
                                <div class="login_form__btn">
                                    <a href="<?= LINK ?>/user/reset/">Passwort vergessen?</a>
                                    <button class="btn btn-dark" name="submit">Anmelden</button>
                                </div>


                            </form>
                        </div>

                        <div class="login_other">
                            <span>oder</span>
                        </div>

                        <!-- <div class="login_social">
                            <div class="login_social__item facebook">
                                <a href="#">
                                    <span>Sign in with Facebook</span>
                                </a>
                            </div>
                            <div class="login_social__item google">
                                <a href="#">
                                    <span>Sign in with Google</span>
                                </a>
                            </div>
                            <div class="login_social__item apple">
                                <a href="#">
                                    <span>Sign in with Apple</span>
                                </a>
                            </div>
                        </div> -->

                        <div class="login_account">
                            <span>Du hast kein Konto?</span>
                            <a href="<?= LINK ?>/user/register/">Registrieren</a>
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