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

if ( !role('admin') && !role('tutor') ) {
    redirect("You don't have permission to view this page!", ADMIN . '/login.php');
    exit();
}

if (isset($_GET['course_id']) && !empty($_GET['course_id'])) {
    $course_id = $_GET['course_id'];
} else {
    redirect('Course ID is Missing!', ADMIN . '/manage_courses.php');
}

$showBlank = false;

$edit = 0;
$data = $db->query('SELECT * FROM `course_learn` WHERE `course_id` = ?', $course_id)->fetchAll();
if ($data) addEdit();

if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $edit = 1;
    if (!$data) removeEdit();
    $data = $data[0];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;
    $error = array();

    $course = json_encode($p, true);

    if ($edit == 1) {
        $update_costs = $db->query('UPDATE `course_learn` SET `course_content` = ?, `updated_at` = CURRENT_TIMESTAMP WHERE `course_id` = ?', $course, $course_id);
    } else {
        $update_costs = $db->query('INSERT INTO `course_learn`(`id`, `course_id`, `course_content`) VALUES (NULL, ?, ?)', $course_id, $course);
    }

    if ($update_costs) {
        redirect('Course Content Successfully!', ADMIN . '/course_videos.php?course_id=' . $course_id);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title>Update Learn Facts - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4">Update Learn Facts</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Courses</li>
                            <li class="breadcrumb-item active">Learn Facts</li>
                        </ol>
                    </div>

                    <?php include HOME . '/admin/inc/course_steps.php'; ?>

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

                                <div class="you_learn">
                                    <?php if (!empty($data) && $data !== '{}') {

                                        $facts = json_decode($data['course_content'], true);
                                        foreach ($facts["learn"] as $k => $fact) { ?>

                                            <div class="you_learn__item" data-current="<?= $k ?>">
                                                <div class="you_learn__item-title">
                                                    <h4>#<span><?= $k + 1 ?></span> - You Will Learn</h4>
                                                </div>
                                                <hr>
                                                <div class="you_learn__item-inner">
                                                    <div class="form-group">
                                                        <label>Title</label>
                                                        <input type="text" class="form-control" name="learn[<?= $k ?>][title]" value="<?= $fact['title'] ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Content</label>
                                                        <textarea class="form-control" name="learn[<?= $k ?>][content]"><?= $fact['content'] ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="you_learn__item-btn">
                                                    <div class="btn btn-danger">Delete</div>
                                                </div>
                                            </div>

                                        <?php }
                                    } else { ?>
                                        <div class="you_learn__item" data-current="0">
                                            <div class="you_learn__item-title">
                                                <h4>#<span>1</span> - You Will Learn</h4>
                                            </div>
                                            <hr>
                                            <div class="you_learn__item-inner">
                                                <div class="form-group">
                                                    <label>Title</label>
                                                    <input type="text" class="form-control" name="learn[0][title]" value="">
                                                </div>
                                                <div class="form-group">
                                                    <label>Content</label>
                                                    <textarea class="form-control" name="learn[0][content]"></textarea>
                                                </div>
                                            </div>
                                            <div class="you_learn__item-btn">
                                                <div class="btn btn-danger">Delete</div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>

                                <div class="form-group">
                                    <div class="btn btn-outline-dark learn-btn">Add New Item</div>
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus-square"></i> Update Learn Facts</button>
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