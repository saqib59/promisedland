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
    'image' => '',
    'intro' => '',
    'method' => '',
    'event_date' => '',
    'speaker' => '',
    'location' => '',
);

$seminar_id = 0;
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $seminar_id = $_GET['edit'];
    $data = $db->query('SELECT * FROM `seminar` WHERE `id` = ?', $seminar_id)->fetchArray();
    if (!$data) removeEdit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;
    $error = array();

    if (!isset($p['title']) || $p['title'] == '') {
        $error[] = 'Please enter a title';
    }
    if (!isset($p['event_date']) || $p['event_date'] == '') {
        $error[] = 'Please select an event date';
    }
    if (!isset($p['method']) || $p['method'] == '') {
        $error[] = 'Please select a method';
    }

    $update_seminar = false;
    if (empty($error)) {
        if ($seminar_id !== 0) {
            $update_seminar = $db->query('UPDATE `seminar` SET `title` = ?, `content` = ?, `image` = ?, `intro` = ?, `method` = ?, `event_date` = ?, `speaker` = ?, `location` = ?, `updated_at` = CURRENT_TIMESTAMP WHERE id = ?;', $p['title'], $p['content'], $p['image'], $p['intro'], $p['method'], $p['event_date'], $p['speaker'], $p['location'], $seminar_id);
        } else {
            $update_seminar = $db->query('INSERT INTO `seminar` (`id`, `title`, `content`, `image`, `intro`, `method`, `event_date`, `speaker`, `location`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?);', $p['title'], $p['content'], $p['image'], $p['intro'], $p['method'], $p['event_date'], $p['speaker'], $p['location']);
        }
    }

    if ($update_seminar) {
        redirect('Seminar Updated Successfully!', ADMIN . '/manage_seminar.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title><?= $seminar_id !== 0 ? 'Update Seminar' : 'Create Seminar'; ?> - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4"><?= $seminar_id !== 0 ? 'Update Seminar' : 'Create Seminar'; ?></h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Seminar</li>
                            <li class="breadcrumb-item active"><?= $seminar_id !== 0 ? 'Update Seminar' : 'New Seminar'; ?></li>
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
                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                        <div class="shd-gallery-area">
                                            <div class="shd-gallery-info">
                                                <i class="far fa-images"></i>
                                                <p>Upload Seminar Image</p>
                                            </div>
                                            <div class="shd-gallery-btn">
                                                <span>Select Image</span>
                                            </div>
                                            <div id="img_path" data-path="seminar"></div>
                                            <input type="file" name="upload_image" id="upload_image" accept="image/png, image/jpeg" />
                                        </div>
                                        <div class="course_img">
                                            <?php
                                            if (isset($p['image'])) {
                                                echo '<img src="' . LINK . $p['image'] . '">';
                                            } else {
                                                if (!empty($data['image'])) {
                                                    echo '<img src="' . LINK . $data['image'] . '">';
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                        <div class="shd-gallery-area">
                                            <div class="shd-gallery-info">
                                                <i class="far fa-photo-video"></i>
                                                <p>Upload Intro Video</p>
                                            </div>
                                            <div class="shd-gallery-btn">
                                                <span>Select Video</span>
                                            </div>
                                            <div id="video_path" data-path="seminar"></div>
                                            <input type="file" name="course_video" class="course_video" accept="video/*" />
                                        </div>


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
                                    <label>Image</label>
                                    <input type="text" class="form-control" name="image" value="<?= isset($p['image']) ? $p['image'] : $data['image']; ?>">
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
                                <div class="form-group">
                                    <label>Method</label>
                                    <select name="method" class="form-select" data-select="<?= isset($p['method']) ? $p['method'] : $data['method']; ?>" required>
                                        <option value="">Select Method</option>
                                        <option value="online">Online</option>
                                        <option value="person">In-person</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Date</label>
                                    <input type="date" class="form-control" name="event_date" value="<?= isset($p['event_date']) ? $p['event_date'] : $data['event_date']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Location</label>
                                    <input type="text" class="form-control" name="location" value="<?= isset($p['location']) ? $p['location'] : $data['location']; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Speaker</label>
                                    <input type="text" class="form-control" name="speaker" value="<?= isset($p['speaker']) ? $p['speaker'] : $data['speaker']; ?>">
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus-square"></i> <?= $seminar_id !== 0 ? 'Update Seminar' : 'Create Seminar'; ?></button>
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