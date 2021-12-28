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

$users = $db->query('SELECT * FROM `users`;')->fetchAll();


if (isset($_GET['user_id']) && $_GET['user_id'] !== '') {
    $user_id = $_GET['user_id'];
    if (isset($_GET['action'])) {

        if ($_GET['action'] == 'delete') {
            $delete_user = $db->query("DELETE FROM `users` WHERE `id` = ?;", $user_id);
            if ($delete_user) {
                redirect('User Deleted Successfully!', ADMIN . '/manage_users.php');
            }
        }

        if ($_GET['action'] == 'unverify') {
            $unverify_user = $db->query("UPDATE `users` SET `verify` = '0' WHERE `id` = ?;", $user_id);
            if ($unverify_user) {
                redirect('User Email Not-Verified Successfully!', ADMIN . '/manage_users.php');
            }
        }

        if ($_GET['action'] == 'verify') {
            $verify_user = $db->query("UPDATE `users` SET `verify` = '1' WHERE `id` = ?;", $user_id);
            if ($verify_user) {
                redirect('User Email Verified Successfully!', ADMIN . '/manage_users.php');
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title>Manage Users - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4">Manage Users</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Users</li>
                            <li class="breadcrumb-item active">Manage Users</li>
                        </ol>
                    </div>

                    <div class="card mb-5">
                        <div class="card-body">

                            <div class="table-responsive">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Birth Date</th>
                                            <th>Email</th>
                                            <th>Registered at</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        foreach ($users as $item) {


                                            $actions = '';
                                            $actions .= '<div class="listing_action">';
                                            $actions .= '<a href="' . ADMIN . '/user.php?edit=' . $item['id'] . '" class="btn btn-primary" title="Edit USer"><i class="fa fa-edit"></i></a>';

                                            if ($item['verify'] == '0') {
                                                $actions .= '<a href="' . ADMIN . '/manage_users.php?user_id=' . $item['id'] . '&action=verify" class="btn btn-success" title="Mark as Email Verified"><i class="fa fa-check"></i></a>';
                                            } else {
                                                $actions .= '<a href="' . ADMIN . '/manage_users.php?user_id=' . $item['id'] . '&action=unverify" class="btn btn-warning" title="Mark as Email Not-Verified"><i class="fa fa-times"></i></a>';
                                            }

                                            $actions .= '<a href="' . ADMIN . '/manage_users.php?user_id=' . $item['id'] . '&action=delete" class="btn btn-danger" title="Delete User" onclick="return confirm(\'Are you sure to delete this user?\');"><i class="fa fa-trash-alt"></i></a>';
                                            $actions .= '</div>';

                                            echo '<tr>';
                                            echo '<td>' . $item['id'] . '</td>';
                                            echo '<td><img width="80" src="' . LINK . $item['image'] . '"></td>';
                                            echo '<td>' . $item['name'] . '</td>';
                                            echo '<td>' . $item['bday'] . '</td>';
                                            if ($item['verify'] == '1') {
                                            echo '<td><span class="text-success">(Verified)</span> ' . $item['email'] . '</td>';
                                            } else {
                                                echo '<td>' . $item['email'] . '</td>';
                                            }
                                            echo '<td>' . $item['insert_at'] . '</td>';
                                            echo '<td>' . $actions . '</td>';
                                            echo '</tr>';
                                        }
                                        ?>

                                    </tbody>
                                </table>
                            </div>

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