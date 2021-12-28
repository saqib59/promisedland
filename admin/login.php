<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

if (admin() !== false) {
    header("Location: " . ADMIN . roleLink($_SESSION['role']));
    exit();
}

$login_ip = getIP();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;

    if (isset($p['inputEmail']) && isset($p['inputEmail'])) {
        if (empty($p['inputEmail']) && empty($p['inputPassword'])) {
            $error = 'Please enter your email & password';
        } else {
            if (empty($p['inputEmail'])) {
                $error = 'Please enter your email';
            } else {
                $inputEmail = $p['inputEmail'];
            }
            if (empty($p['inputPassword'])) {
                $error = 'Please enter your password';
            } else {
                $inputPassword = md5($p['inputPassword']);
            }
        }
    } else {
        $error = 'Please enter your email & password';
    }


    if (isset($inputEmail) && isset($inputPassword)) {
        if (attempChecker($login_ip)) {

            $accounts = $db->query('SELECT * FROM `admin` WHERE email = ? AND pwd = ?', $inputEmail, $inputPassword);
            if ($accounts->numRows() == 1) {
                $accounts = $accounts->fetchArray();

                $_SESSION['admin'] = $accounts['id'];
                $_SESSION['role'] = $accounts['role'];
                $_SESSION['ip'] = $login_ip;

                redirect('Login Success! Redirecting to dashboard...', ADMIN . roleLink($_SESSION['role']));
            } else {
                adminAttemp($p['inputEmail'], $p['inputPassword'], $login_ip);
                $error = 'Your login credentials are incorrect';
            }
        } else {
            $error = 'Sorry! You have reached your maximum login attemps';
        }
    } else {
        $error = 'Please enter your email & password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title>Login - SB Admin</title>
</head>

<body class="bg-dark">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h4 class="text-center font-weight-light my-2"><i class="fa fa-users"></i> Admin Login</h4>
                                </div>

                                <?php if (attempChecker($login_ip)) { ?>
                                    <form action="<?= fullUrl() ?>" method="POST">

                                        <?php if (isset($error) && !empty($error)) {
                                            echo '<div class="alert alert-danger">' . $error . '</div>';
                                        } ?>

                                        <div class="card-body pt-5 pb-4 px-4">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" name="inputEmail" type="email" placeholder="name@example.com" value="<?= isset($p['inputEmail']) ? $p['inputEmail'] : 'administrator@promised-land.de'; ?>" />
                                                <label for="inputEmail">Email address</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" name="inputPassword" type="password" placeholder="Password" value="<?= isset($p['inputPassword']) ? $p['inputPassword'] : '@promised-land.de0'; ?>" />
                                                <label for="inputPassword">Password</label>
                                            </div>
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" id="inputRememberPassword" type="checkbox" value="" />
                                                <label class="form-check-label" for="inputRememberPassword">Remember Password</label>
                                            </div>
                                        </div>
                                        <div class="card-footer text-center py-3">
                                            <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-sign-in"></i> Login</button>
                                        </div>
                                    </form>
                                <?php } else { ?>
                                    <div class="alert alert-danger mb-0">Sorry! You have reached your maximum login attemps</div>
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            <?php include HOME . '/admin/inc/footer.php'; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>

</html>