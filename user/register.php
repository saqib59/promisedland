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

$name = '';
$surname = '';
$bday = '';
$email = '';
$password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $p = $_POST;
    $error = array();

    if (isset($p['name']) && !empty($p['name'])) {
        if (strlen($p['name']) < 2) {
            $error[] = 'Der Name sollte mehr als 2 Charaktere haben';
        } elseif (strlen($p['name']) > 100) {
            $error[] = 'Der Name sollte weniger als 100 Charaktere haben';
        } else {
            $name = htmlentities($p['name']);
        }
    } else {
        $error[] = 'Vorname eingeben';
    }

    if (isset($p['surname']) && !empty($p['surname'])) {
        if (strlen($p['surname']) < 2) {
            $error[] = 'Der Nachname sollte mehr als 2 Charaktere haben';
        } elseif (strlen($p['surname']) > 100) {
            $error[] = 'Der Nachname sollte weniger als 100 Charaktere haben';
        } elseif ($p['name'] == $p['surname']) {
            $error[] = 'Nachname und Vorname dürfen nicht identisch sein ';
        } else {
            $surname = htmlentities($p['surname']);
        }
    } else {
        $error[] = 'Nachname eingeben';
    }

    if (
        isset($p['day']) && !empty($p['day']) &&
        isset($p['month']) && !empty($p['month']) &&
        isset($p['year']) && !empty($p['year'])
    ) {
        $date = $p['year'] . '-' . $p['month'] . '-' . $p['day'];
        $bday = $p['day'] . '.' . $p['month'] . '.' . $p['year'];
        if (validateDate($date) == false) {
            $error[] = 'Bitte wähle ein gültiges Datum für das Geburtsjahr';
        } elseif (validateAge($date) == false) {
            $error[] = 'Du musst 18 Jahre oder älter, um dich anzumdelden';
        }
    } else {
        $error[] = 'Geburtstag wählen';
    }

    if (isset($p['email']) && !empty($p['email'])) {
        if (!filter_var($p['email'], FILTER_VALIDATE_EMAIL)) {
            $error[] = 'Bitte gebe eine gültige E-Mail Adresse ein';
        } else {
            if (check_row($p['email'], 'email', 'users')) {
                $error[] = 'Diese E-Mail ist bereits registriert';
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
                    if ($p['pwd'] !== $p['cpwd']) {
                        $error[] = 'Die Passwörter sind nicht identisch';
                    } else {
                        $password = htmlentities($p['pwd']);
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
        $error[] = 'Bitte Passwort eingeben';
    }

    if (empty($error)) {
        $register = userRegister($name, $surname, $bday, $email, $password);
        if ($register) {
            $_SESSION['email'] = $email;

            // @@mail : send confirmation email
            confirm_email($email);

            header("Location: " . USER . '/register/?status=success');
            //user_redirect('Erfolgreich registriert! PÜberprüfe deine Posteingang, um die E-Mail zu bestätigen!', 'success', USER . '/login/');
        } else {
            user_redirect('Ewas lief falsch! Bitte erneut probieren.!', 'error', USER . '/register/');
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title>Register - Promised Land</title>

    <!-- Global site tag (gtag.js) - Google Ads: 347311738 -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-347311738"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'AW-347311738');
    </script>
</head>

<body>

    <!-- Start . Section : Header -->
    <?php include HOME . '/block/header.php'; ?>
    <!-- End . Section : Header -->


    <!-- Start . Section : Register -->
    <section id="login">

        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-lg-5 col-md-7 col-sm-12 col-12 mx-auto">
                    <div class="login">
                        <div class="login_title">
                            <h2>Registrieren</h2>
                        </div>

                        <?php if (isset($_GET['status']) && $_GET['status'] == 'success') { ?>
                            <div class="alert alert-success">Erfolgreich registriert! Überprüfe deine Posteingang, um die E-Mail zu bestätigen!</div>
                        <?php } else { ?>

                            <div class="login_alerts">
                                <?php if (!empty($error)) { ?>
                                    <div class="login_alerts__list">
                                        <?php foreach ($error as $er) { ?>
                                            <p><?= $er ?></p>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="login_form">
                                <form id="user_register" action="<?= fullUrl() ?>" method="POST">
                                    <div class="login_form__input">
                                        <div class="login_form__input-line">
                                            <i class="fas fa-user"></i>
                                            <input name="name" type="text" class="form-control" placeholder="Name" value="<?= isset($p['name']) ? $p['name'] : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="login_form__input">
                                        <div class="login_form__input-line">
                                            <i class="far fa-user"></i>
                                            <input name="surname" type="text" class="form-control" placeholder="Vorname" value="<?= isset($p['surname']) ? $p['surname'] : ''; ?>">
                                        </div>
                                    </div>

                                    <div class="login_form__input">
                                        <div class="row row-sm">
                                            <div class="col-xs-4 col-lg-4 col-md-4 col-sm-6 col-6">
                                                <select name="day" class="form-select" data-select="<?= isset($p['day']) ? $p['day'] : ''; ?>">
                                                    <option value="">Tag</option>
                                                    <?php for ($i = 1; $i <= 31; $i++) {
                                                        $i = str_pad($i, 2, '0', STR_PAD_LEFT);
                                                        echo "<option value=\"{$i}\">{$i}</option>";
                                                    } ?>
                                                </select>
                                            </div>
                                            <div class="col-xs-4 col-lg-4 col-md-4 col-sm-6 col-6">
                                                <select name="month" class="form-select" data-select="<?= isset($p['month']) ? $p['month'] : ''; ?>">
                                                    <option value="">Monat</option>
                                                    <?php for ($i = 1; $i <= 12; $i++) {
                                                        $i = str_pad($i, 2, '0', STR_PAD_LEFT);
                                                        echo "<option value=\"{$i}\">{$i}</option>";
                                                    } ?>
                                                </select>
                                            </div>
                                            <div class="col-xs-4 col-lg-4 col-md-4 col-sm-12 col-12">
                                                <select name="year" class="form-select" data-select="<?= isset($p['year']) ? $p['year'] : ''; ?>">
                                                    <option value="">Jahr</option>
                                                    <?php for ($i = 1900; $i <= date("Y"); $i++) {
                                                        echo "<option value=\"{$i}\">{$i}</option>";
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="login_form__input">
                                        <div class="login_form__input-line">
                                            <i class="far fa-envelope"></i>
                                            <input name="email" type="email" class="form-control" placeholder="E-Mail" value="<?= isset($p['email']) ? $p['email'] : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="login_form__input" data-bs-toggle="tooltip" data-bs-placement="right" title="Add 9 charachters or more, lowercase letters, uppercase letters, numbers and symbols to make the password really strong!">
                                        <div class="login_form__input-line password-strength">
                                            <i class="far fa-lock"></i>
                                            <input name="pwd" class="form-control password-strength__input" type="password" placeholder="Passwort" id="password" />
                                            <small class="password-strength__error text-danger js-hidden"></small>
                                        </div>
                                    </div>
                                    <div class="login_form__input">
                                        <div class="login_form__input-line">
                                            <i class="far fa-lock"></i>
                                            <input name="cpwd" class="form-control" type="password" placeholder="Passwort bestätigen" id="password-confirm" />
                                        </div>
                                    </div>
                                    <div class="login_form__input">
                                        <div class="login_form__input-line">
                                            <div class="password-strength__bar-block progress">
                                                <div class="password-strength__bar progress-bar bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="register_form__btn">
                                        <button class="btn btn-dark" name="submit" disabled>Registrieren</button>
                                    </div>

                                </form>
                            </div>

                            <div class="login_other mt-4">
                                <span>oder</span>
                            </div>

                            <div class="login_account">
                                <span>Du hast bereits ein Konto?</span>
                                <a href="<?= LINK ?>/user/login/">Anmelden</a>
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