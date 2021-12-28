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
    'answer' => '',
);

$faq_id = 0;
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $faq_id = $_GET['edit'];
    $data = $db->query('SELECT * FROM `faq` WHERE `id` = ?', $faq_id)->fetchArray();
    if (!$data) removeEdit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;
    $error = array();

    $update_faq = false;
    if (empty($error)) {
        if ($faq_id !== 0) {
            $update_faq = $db->query('UPDATE `faq` SET `question` = ?, `answer` = ?, `updated_at` = CURRENT_TIMESTAMP WHERE id = ?;', $p['question'], $p['answer'], $faq_id);
        } else {
            $update_faq = $db->query('INSERT INTO `faq` (`id`, `question`, `answer`) VALUES (NULL, ?, ?);', $p['question'], $p['answer']);
        }
    }

    if ($update_faq) {
        redirect('F.A.Q Updated Successfully!', ADMIN . '/faq.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title><?= $faq_id !== 0 ? 'Update F.A.Q' : 'Create F.A.Q'; ?> - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4"><?= $faq_id !== 0 ? 'Update F.A.Q' : 'Create F.A.Q'; ?></h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Users</li>
                            <li class="breadcrumb-item active"><?= $faq_id !== 0 ? 'Update F.A.Q' : 'New F.A.Q'; ?></li>
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
                                    <label>Answer</label>
                                    <textarea id="foreclosure_desc" class="form-control" name="answer" placeholder="Please enter a answer here"><?= isset($p['answer']) ? $p['answer'] : $data['answer']; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus-square"></i> <?= $faq_id !== 0 ? 'Update F.A.Q' : 'Create F.A.Q'; ?></button>
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