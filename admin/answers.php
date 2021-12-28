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

if (!role('admin')) {
    redirect("You don't have permission to view this page!", ADMIN . '/login.php');
    exit();
}

if (isset($_GET['question_id']) && $_GET['question_id'] !== '') {
    $question_id = $_GET['question_id'];
} else {
    header("Location: " . ADMIN . "/questions.php");
    exit();
}

$answers = $db->query('SELECT * FROM `answers` WHERE `question_id` = ?;', $question_id)->fetchAll();

if (isset($_GET['answer_id']) && $_GET['answer_id'] !== '') {
    $answer_id = $_GET['answer_id'];

    if (isset($_GET['action']) && !empty($_GET['action'])) {

        if ($_GET['action'] == 'delete') {
            $delete_answer = $db->query("DELETE FROM `answers` WHERE `id` = ?;", $answer_id);
            if ($delete_answer) {
                redirect('Answer Deleted Successfully!', ADMIN . '/answers.php?question_id=' . $question_id);
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title>Manage Answers - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4">Answers</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Site</li>
                            <li class="breadcrumb-item">Questions</li>
                            <li class="breadcrumb-item active">Answers</li>
                        </ol>
                    </div>

                    <?php
                    $question = get_data($question_id, 'question', 'questions');
                    if (!empty($question)) { ?>
                        <div class="alert alert-primary">
                            <strong>Question:</strong>
                            <p class="mb-0"><?= $question ?></p>
                        </div>
                    <?php } ?>

                    <div class="card mb-5">
                        <div class="card-body">

                            <div class="table-responsive">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>User</th>
                                            <th>Answer</th>
                                            <th style="min-width: 180px;">Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        foreach ($answers  as $item) {

                                            $actions = '';
                                            $actions .= '<div class="listing_action">';
                                            $actions .= '<a href="' . ADMIN . '/answers.php?question_id=' . $question_id . '&answer_id=' . $item['id'] . '&action=delete" class="btn btn-danger" title="Delete Answer" onclick="return confirm(\'Are you sure to delete this answer?\');"><i class="fa fa-trash-alt"></i></a>';
                                            $actions .= '</div>';

                                            echo '<tr>';
                                            echo '<td>' . $item['id'] . '</td>';
                                            echo '<td>' . get_data($item['user_id'], 'name', 'users') . '</td>';
                                            echo '<td>' . $item['answer'] . '</td>';
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