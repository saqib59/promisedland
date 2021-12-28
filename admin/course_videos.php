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
$data = $db->query('SELECT * FROM `course_video` WHERE `course_id` = ?', $course_id)->fetchAll();
if ($data) addEdit();

if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $edit = 1;
    if (!$data) removeEdit();
    $data = $data[0];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;
    $error = array();

    $videos = json_encode($p, true);

    if ($edit == 1) {
        $update_videos = $db->query('UPDATE `course_video` SET `videos` = ?, `updated_at` = CURRENT_TIMESTAMP WHERE `course_id` = ?', $videos, $course_id);
    } else {
        $update_videos = $db->query('INSERT INTO `course_video`(`id`, `course_id`, `videos`) VALUES (NULL, ?, ?);', $course_id, $videos);
    }

    if ($update_videos) {
        redirect('Course Video Successfully!', ADMIN . '/manage_courses.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title>Update Videos - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4">Update Videos</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Courses</li>
                            <li class="breadcrumb-item active">Videos</li>
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

                                <div class="course_videos">

                                    <?php
                                    if (!empty($data) && $data !== '{}') {
                                        $vids = json_decode($data["videos"], true);
                                        foreach ($vids["course"] as $k => $vid) { ?>
                                            <div class="course_videos__item" data-current="<?= $k ?>">
                                                <div class="course_videos__item-title">
                                                    <h4>#<span><?= $k + 1 ?></span> - Video Segment</h4>
                                                </div>
                                                <hr>

                                                <div class="row">
                                                    <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-12">
                                                        <div class="course_videos__item-inner">
                                                            <div class="form-group">
                                                                <label>Title</label>
                                                                <input type="text" class="form-control" name="course[<?= $k ?>][title]" value="<?= $vid['title'] ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Content</label>
                                                                <textarea class="form-control" name="course[<?= $k ?>][content]"><?= $vid['content'] ?></textarea>
                                                            </div>
                                                            <div class="course_videos__item-btn">
                                                                <div class="btn btn-danger">Delete</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-12">
                                                        <div class="shd-gallery-area">
                                                            <div class="shd-gallery-info">
                                                                <i class="far fa-photo-video"></i>
                                                                <p>Upload video for this segment</p>
                                                            </div>
                                                            <div class="shd-gallery-btn">
                                                                <span>Select Video</span>
                                                            </div>
                                                            <div id="video_path" data-path="videos"></div>
                                                            <input type="file" name="course_video" class="course_video" accept="video/*" />
                                                        </div>
                                                        <div class="video_preview">
                                                            <video width="100%" controls>
                                                                <source src="<?= LINK . $vid['video'] ?>" type="video/mp4">
                                                                Your browser does not support HTML video.
                                                            </video>
                                                        </div>
                                                    </div>
                                                </div>

                                                <input type="hidden" class="form-control" name="course[<?= $k ?>][video]" value="<?= $vid['video'] ?>">
                                                <input type="hidden" class="form-control" name="course[<?= $k ?>][length]" value="<?= $vid['length'] ?>">
                                                <input type="hidden" class="form-control" name="course[<?= $k ?>][watch]" value="<?= $vid['watch'] ?>">

                                            </div>
                                        <?php }
                                    } else { ?>
                                        <div class="course_videos__item" data-current="0">
                                            <div class="course_videos__item-title">
                                                <h4>#<span>1</span> - Video Segment</h4>
                                            </div>
                                            <hr>

                                            <div class="row">
                                                <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-12">
                                                    <div class="course_videos__item-inner">
                                                        <div class="form-group">
                                                            <label>Title</label>
                                                            <input type="text" class="form-control" name="course[0][title]" value="">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Content</label>
                                                            <textarea class="form-control" name="course[0][content]"></textarea>
                                                        </div>
                                                        <div class="course_videos__item-btn">
                                                            <div class="btn btn-danger">Delete</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-12">
                                                    <div class="shd-gallery-area">
                                                        <div class="shd-gallery-info">
                                                            <i class="far fa-photo-video"></i>
                                                            <p>Upload video for this segment</p>
                                                        </div>
                                                        <div class="shd-gallery-btn">
                                                            <span>Select Video</span>
                                                        </div>
                                                        <div id="video_path" data-path="videos"></div>
                                                        <input type="file" name="course_video" class="course_video" accept="video/*" />
                                                    </div>
                                                    <div class="video_preview">
                                                    </div>
                                                </div>
                                            </div>

                                            <input type="hidden" class="form-control" name="course[0][video]" value="">
                                            <input type="hidden" class="form-control" name="course[0][length]" value="">
                                            <input type="hidden" class="form-control" name="course[0][watch]" value="">

                                        </div>
                                    <?php } ?>


                                </div>

                                <div class="form-group">
                                    <div class="btn btn-outline-dark video-btn">Add New Video</div>
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus-square"></i> Update Course Videos</button>
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