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

$admin_id = $_SESSION['admin'];

$data = array(
    'title' => '',
    'content' => '',
    'intro' => '',
    'price' => '',
    'time' => '',
);

$consultant_id = 0;
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $consultant_id = $_GET['edit'];
    $data = $db->query('SELECT * FROM `consulting` WHERE `id` = ?', $consultant_id)->fetchArray();
    if (!$data) removeEdit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;
    $error = array();

    if (!isset($p['title']) || $p['title'] == '') {
        $error[] = 'Please enter a title';
    }
    if (!isset($p['price']) || $p['price'] == '') {
        $error[] = 'Please enter a price';
    }
    if (!isset($p['time']) || $p['time'] == '') {
        $error[] = 'Please enter a time';
    }

    $update_consultant = false;
    if (empty($error)) {
        if ($consultant_id !== 0) {
            $update_consultant = $db->query('UPDATE `consulting` SET `title` = ?, `content` = ?, `intro` = ?, `price` = ?, `time` = ?, `updated_at` = CURRENT_TIMESTAMP WHERE id = ?;', $p['title'], $p['content'], $p['intro'], $p['price'], $p['time'], $consultant_id);
        } else {
            $update_consultant = $db->query('INSERT INTO `consulting` (`id`, `title`, `content`, `intro`, `price`, `time`) VALUES (NULL, ?, ?, ?, ?, ?);', $p['title'], $p['content'], $p['intro'], $p['price'], $p['time']);
        }
    }

    if ($update_consultant) {
        redirect('Consultant Updated Successfully!', ADMIN . '/manage_consultants.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title><?= $consultant_id !== 0 ? 'Update Consultant' : 'Create Consultant'; ?> - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4"><?= $consultant_id !== 0 ? 'Update Consultant' : 'Create Consultant'; ?></h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Consulting</li>
                            <li class="breadcrumb-item active"><?= $consultant_id !== 0 ? 'Update Consultant' : 'New Consultant'; ?></li>
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

                                <div class="row">
                                    <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
                                        <div class="shd-gallery-area">
                                            <div class="shd-gallery-info">
                                                <i class="far fa-photo-video"></i>
                                                <p>Upload Intro Video</p>
                                            </div>
                                            <div class="shd-gallery-btn">
                                                <span>Select Video</span>
                                            </div>
                                            <div id="video_path" data-path="consulting"></div>
                                            <input type="file" name="course_video" class="course_video" accept="video/*" />
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
                                        <div class="video_preview">
                                            <?php if (!empty($p['intro']) || !empty($data['intro'])) { ?>
                                                <video width="100%" controls>
                                                    <source src="<?= isset($p['intro']) ? LINK . $p['intro'] : LINK . $data['intro']; ?>" type="video/mp4">
                                                    Your browser does not support HTML video.
                                                </video>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group d-none">
                                    <label>Intro</label>
                                    <input type="text" class="form-control" name="intro" value="<?= isset($p['intro']) ? $p['intro'] : $data['intro']; ?>">
                                </div>

                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" class="form-control" name="title" value="<?= isset($p['title']) ? $p['title'] : $data['title']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Desciption</label>
                                    <textarea id="foreclosure_desc" class="form-control" name="content"><?= isset($p['content']) ? $p['content'] : $data['content']; ?></textarea>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6 col-12">
                                        <label>Price</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="price" placeholder="Ex: 60" value="<?= isset($p['price']) ? $p['price'] : $data['price']; ?>">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">&euro;</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label>Time</label>
                                        <input type="text" class="form-control" name="time" placeholder="1 Hour" value="<?= isset($p['time']) ? $p['time'] : $data['time']; ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus-square"></i> <?= $consultant_id !== 0 ? 'Update Seminar' : 'Create Seminar'; ?></button>
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