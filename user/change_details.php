<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once  '../inc/account/logged.php';

$data = $db->query('SELECT * FROM `users` WHERE `id` = ?', $user)->fetchArray();

$name = '';
$surname = '';
$bday = '';

$avatar = $data['image'];
$bdate = explode('.', $data['bday']);

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

    if ($_FILES['file']['name'] != '') {
        $upload_avatar = upload_avatar($_FILES['file']);
        if ($upload_avatar) {
            $avatar = $upload_avatar;
        }
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

    if (empty($error)) {
        $update_details = $db->query('UPDATE `users` SET `image` = ?, `name` = ?, `surname` = ?, `bday` = ?, `updated_at` = CURRENT_TIMESTAMP WHERE id = ?;', $avatar, $p['name'], $p['surname'], $bday, $user);
        if ($update_details) {
            user_redirect('Kontoinformationen wurden erfolgreich geupdated!', 'success', LINK . '/user/change_details/');
        } else {
            user_redirect('Ewas lief falsch! Bitte erneut probieren.!', 'error', LINK . '/user/change_details/');
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title>Details ändern - Promised Land</title>
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
                                    <h4>Daten aktualisieren</h4>
                                    <p>Hier kannst du deine Daten einsehen und ändern.</p>
                                </div>

                                <div class="account_body__content">

                                    <div class="account_details">
                                        <form class="account_form" action="<?= fullUrl() ?>" method="POST" enctype="multipart/form-data">
                                            <div class="row">

                                                <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-12">
                                                    <div class="account_details__avatar">
                                                        <div class="account_details__avatar-image">
                                                            <img id="account_details__avatar-image--link" src="<?= LINK . $data['image'] ?>">
                                                            <div class="account_details__avatar-overlay">
                                                                <i class="fa fa-camera"></i>
                                                                <span>Foto hochladen</span>
                                                            </div>
                                                        </div>
                                                        <input name="file" type="file" class="form-control" accept="image/png, image/gif, image/jpeg">
                                                    </div>
                                                </div>

                                                <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-12">
                                                    <div class="account_details__form">
                                                        <?php if (isset($error) && !empty($error)) {
                                                            echo '<div class="alert alert-danger"><ul class="mb-0">';
                                                            foreach ($error as $e) {
                                                                echo '<li>' . $e . '</li>';
                                                            }
                                                            echo '</ul></div>';
                                                        } ?>

                                                        <div class="form-group">
                                                            <label>Name</label>
                                                            <input type="text" class="form-control" name="name" value="<?= isset($p['name']) ? $p['name'] : $data['name']; ?>">
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Vorname</label>
                                                            <input type="text" class="form-control" name="surname" value="<?= isset($p['surname']) ? $p['surname'] : $data['surname']; ?>">
                                                        </div>

                                                        <div class="account_details__form-day">
                                                            <div class="form-group">
                                                                <label>Geburtsdatum</label>
                                                                <div class="form-group row row-sm">
                                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-6">
                                                                        <select name="day" class="form-select" data-select="<?= $bdate[0]; ?>">
                                                                            <option value="">Tag</option>
                                                                            <?php for ($i = 1; $i <= 31; $i++) {
                                                                                $i = str_pad($i, 2, '0', STR_PAD_LEFT);
                                                                                echo "<option value=\"{$i}\">{$i}</option>";
                                                                            } ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-6">
                                                                        <select name="month" class="form-select" data-select="<?= $bdate[1]; ?>">
                                                                            <option value="">Monat</option>
                                                                            <?php for ($i = 1; $i <= 12; $i++) {
                                                                                $i = str_pad($i, 2, '0', STR_PAD_LEFT);
                                                                                echo "<option value=\"{$i}\">{$i}</option>";
                                                                            } ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                                                        <select name="year" class="form-select" data-select="<?= $bdate[2]; ?>">
                                                                            <option value="">Jahr</option>
                                                                            <?php for ($i = 1900; $i <= date("Y"); $i++) {
                                                                                echo "<option value=\"{$i}\">{$i}</option>";
                                                                            } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-button">
                                                            <button type="submit" class="btn btn-dark">
                                                                <i class="fa fa-key"></i>
                                                                <span>Aktualisieren</span>
                                                            </button>
                                                        </div>

                                                    </div>
                                                </div>

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