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

if ( !role('admin') && !role('writer') ) {
    redirect("You don't have permission to view this page!", ADMIN . '/login.php');
    exit();
}

if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $equipment_id = $_GET['edit'];
}

$data = array(
    'label' => '',
);

$equipments = $db->query('SELECT * FROM `equipments`;')->fetchAll();

$edit = 0;
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $edit = 1;
    $data = $db->query('SELECT * FROM `equipments` WHERE `id` = ?', $equipment_id)->fetchAll();
    if (!$data) removeEdit();
    $data = $data[0];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;
    $error = array();

    if (!isset($p['label']) || $p['label'] == '') {
        $error[] = 'Please enter label';
    }

    $update_equipments = false;
    if (empty($error)) {
        if ($edit == 1) {
            $update_equipments = $db->query('UPDATE `equipments` SET `label` = ?, `updated_at` = CURRENT_TIMESTAMP WHERE `id` = ?', $p['label'], $equipment_id);
            $msg = 'Equipment Updated Successfully!';
        } else {
            $update_equipments = $db->query('INSERT INTO `equipments`(`id`, `label`) VALUES (NULL, ?)', $p['label']);
            $msg = 'Equipment Added Successfully!';
        }
    }

    if ($update_equipments) {
        redirect($msg, ADMIN . '/equipments.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title>Update Inspection - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4">Equipments</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Listings</li>
                            <li class="breadcrumb-item active">Equipments</li>
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
                                    <label>Equipments Label</label>
                                    <input type="text" class="form-control" name="label" value="<?= isset($p['label']) ? $p['label'] : $data['label']; ?>" required>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-plus-square"></i>
                                        <?php if ($edit == 1) { ?>
                                            <span>Update Equipment</span>
                                        <?php } else { ?>
                                            <span>Add Equipment</span>
                                        <?php } ?>
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>

                    <?php if ($edit !== 1) { ?>
                    <div class="card mb-5">
                        <div class="card-body">

                            <div class="table-responsive">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Equipment</th>
                                            <th>Created at</th>
                                            <th>Last Changed</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        foreach ($equipments as $item) {
                                            $actions = '';
                                            $actions .= '<div class="listing_action">';
                                            $actions .= '<a href="' . ADMIN . '/equipments.php?edit=' . $item['id'] . '" class="btn btn-primary" title="Edit Equipments"><i class="fa fa-edit"></i></a>';
                                            $actions .= '</div>';
                                            echo '<tr>';
                                            echo '<td>' . $item['id'] . '</td>';
                                            echo '<td>' . $item['label'] . '</td>';
                                            echo '<td>' . $item['insert_at'] . '</td>';
                                            echo '<td>' . $item['updated_at'] . '</td>';
                                            echo '<td>' . $actions . '</td>';
                                            echo '</tr>';
                                        }
                                        ?>

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <?php } ?>

                </div>
            </main>

            <?php include HOME . '/admin/inc/footer.php'; ?>

        </div>

    </div>

    <?php include HOME . '/admin/inc/scripts.php'; ?>

</body>

</html>