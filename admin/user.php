<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

if (admin() == false) {
    redirect("Please login first!", ADMIN . '/login.php');
    exit();
}

if ( !role('admin') ) {
    redirect("You don't have permission to view this page!", ADMIN . '/login.php');
    exit();
}

if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $user_id = $_GET['edit'];
    $data = $db->query('SELECT * FROM `users` WHERE `id` = ?', $user_id)->fetchArray();
} else {
    redirect('User ID is Missing!', ADMIN . '/pending_listings.php');
}

$bdate = explode('.', $data['bday']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $p = $_POST;
    $error = array();

    if (!isset($p['name']) || empty($p['name'])) {
        $error[] = 'Please enter user name';
    }

    if (!isset($p['surname']) || empty($p['surname'])) {
        $error[] = 'Please enter user surname';
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
        }
    } else {
        $error[] = 'Please select user birthday';
    }

    if (isset($p['email']) && !empty($p['email'])) {
        if (!filter_var($p['email'], FILTER_VALIDATE_EMAIL)) {
            $error[] = 'Bitte gebe eine gültige E-Mail Adresse ein';
        }
    } else {
        $error[] = 'Please enter user email';
    }

    if (isset($p['pwd']) && !empty($p['pwd'])) {
        $pwd = md5($p['pwd']);
    } else {
        $pwd = $data['pwd'];
    }

    $update_user = false;
    if (empty($error)) {
        $update_user = $db->query('UPDATE `users` SET `name` = ?, `surname` = ?, `bday` = ?, `email` = ?, `pwd` = ?, `updated_at` = CURRENT_TIMESTAMP WHERE id = ?;', $p['name'], $p['surname'], $bday, $p['email'], $pwd, $user_id);
    }

    if ($update_user) {
        redirect('User Updated Successfully!', ADMIN . '/manage_users.php');
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title>Update User - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4">Update User: <?= $data['name'] ?></h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Users</li>
                            <li class="breadcrumb-item active">Update User</li>
                        </ol>
                    </div>

                    <div class="card mb-5">
                        <div class="card-body">

                            <?php if (isset($error) && !empty($error)) {
                                echo '<div class="alert alert-danger"><ul class="mb-0">';
                                foreach ($error as $e) {
                                    echo '<li>' . $e . '</li>';
                                }
                                echo '</ul></div>';
                            } ?>

                            <form action="<?= fullUrl() ?>" method="POST">

                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="name" value="<?= isset($p['name']) ? $p['name'] : $data['name']; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Surname</label>
                                    <input type="text" class="form-control" name="surname" value="<?= isset($p['surname']) ? $p['surname'] : $data['surname']; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Birthday</label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <select name="day" class="form-select" data-select="<?= $bdate[0]; ?>">
                                                <option value="">Day</option>
                                                <?php for ($i = 1; $i <= 31; $i++) {
                                                    $i = str_pad($i, 2, '0', STR_PAD_LEFT);
                                                    echo "<option value=\"{$i}\">{$i}</option>";
                                                } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select name="month" class="form-select" data-select="<?= $bdate[1]; ?>">
                                                <option value="">Month</option>
                                                <?php for ($i = 1; $i <= 12; $i++) {
                                                    $i = str_pad($i, 2, '0', STR_PAD_LEFT);
                                                    echo "<option value=\"{$i}\">{$i}</option>";
                                                } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select name="year" class="form-select" data-select="<?= $bdate[2]; ?>">
                                                <option value="">Year</option>
                                                <?php for ($i = 1900; $i <= date("Y"); $i++) {
                                                    echo "<option value=\"{$i}\">{$i}</option>";
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6 col-12">
                                        <label>Email</label>
                                        <input type="email" class="form-control" name="email" value="<?= isset($p['email']) ? $p['email'] : $data['email']; ?>">
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label>Password</label>
                                        <input type="text" class="form-control" name="pwd" value="" placeholder="Leave it empty not to change the password">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus-square"></i> Update User</button>
                                </div>

                            </form>

                        </div>
                    </div>

                </div>
            </main>

            <?php include HOME . '/admin/inc/footer.php'; ?>

        </div>

    </div>

    <?php include HOME . '/admin/inc/scripts.php'; ?>

</body>

</html>