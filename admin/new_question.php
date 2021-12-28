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

$data = array(
    'question' => '',
);

$question_id = 0;
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $question_id = $_GET['edit'];
    $data = $db->query('SELECT * FROM `questions` WHERE `id` = ?', $question_id)->fetchArray();
    if (!$data) removeEdit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;
    $error = array();

    $update_question = false;
    if (empty($error)) {
        if ($question_id !== 0) {
            $update_question = $db->query('UPDATE `questions` SET `question` = ?, `updated_at` = CURRENT_TIMESTAMP WHERE id = ?;', $p['question'], $question_id);
        } else {
            $update_question = $db->query('INSERT INTO `questions` (`id`, `question`) VALUES (NULL, ?);', $p['question']);
        }
    }

    if ($update_question) {
        redirect('Question Updated Successfully!', ADMIN . '/questions.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title><?= $question_id !== 0 ? 'Update Question' : 'Create Question'; ?> - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4"><?= $question_id !== 0 ? 'Update Question' : 'Create Question'; ?></h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Site</li>
                            <li class="breadcrumb-item active"><?= $question_id !== 0 ? 'Update Question' : 'New Question'; ?></li>
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
                                    <label>Question</label>
                                    <textarea class="form-control" name="question" placeholder="Please enter a question here"><?= isset($p['question']) ? $p['question'] : $data['question']; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus-square"></i> <?= $question_id !== 0 ? 'Update Question' : 'Create Question'; ?></button>
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