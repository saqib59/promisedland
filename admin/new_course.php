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

$admin_id = $_SESSION['admin'];

$course_authors = $db->query("SELECT * FROM `course_author`;")->fetchAll();

$data = array(
    'image' => '',
    'intro' => '',
    'title' => '',
    'content' => '',
    'price' => '',
    'author' => '',
);

$course_id = 0;
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $course_id = $_GET['course_id'];
    $data = $db->query('SELECT * FROM `course` WHERE `id` = ?', $course_id)->fetchAll();
    if (!$data) removeEdit();
    $data = $data[0];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;
    $error = array();

    if (!isset($p['title']) || $p['title'] == '') {
        $error[] = 'Please enter a title';
    }

    $post_slug = createSlug($p['title']);

    $update_course = false;
    if (empty($error)) {
        if ($course_id !== 0) {
            $update_course = $db->query('UPDATE `course` SET `title` = ?, `slug` = ?, `content` = ?, `price` = ?, `image` = ?, `intro` = ?, `author` = ?, `updated_at` = CURRENT_TIMESTAMP WHERE id = ?;', $p['title'], $post_slug, $p['content'], $p['price'], $p['image'], $p['intro'], $p['author'], $course_id);
        } else {
            $update_course = $db->query('INSERT INTO `course`(`id`, `title`, `slug`, `content`, `price`, `image`, `intro`, `author`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?);', $p['title'], $post_slug, $p['content'], $p['price'], $p['image'], $p['intro'], $p['author']);
            //$course_id = $db->lastInsertID();
        }
    }

    if ($update_course) {
        redirect('Course Updated Successfully!', ADMIN . '/course_learn.php?course_id=' . $course_id);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title><?= $course_id !== 0 ? 'Update Course' : 'Create Course'; ?> - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4"><?= $course_id !== 0 ? 'Update Course' : 'Create Course'; ?></h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Course</li>
                            <li class="breadcrumb-item active"><?= $course_id !== 0 ? 'Update Course' : 'New Course'; ?></li>
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

                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                        <div class="shd-gallery-area">
                                            <div class="shd-gallery-info">
                                                <i class="far fa-images"></i>
                                                <p>Upload Course Image</p>
                                            </div>
                                            <div class="shd-gallery-btn">
                                                <span>Select Image</span>
                                            </div>
                                            <div id="img_path" data-path="course"></div>
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
                                            <div id="video_path" data-path="intro"></div>
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
                                    <input type="text" class="form-control" name="title" value="<?= isset($p['title']) ? $p['title'] : $data['title']; ?>">
                                </div>

                                <div class="form-group mb-4">
                                    <label>Content</label>
                                    <textarea id="foreclosure_desc" class="form-control" name="content"><?= isset($p['content']) ? $p['content'] : $data['content']; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Price</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="price" value="<?= isset($p['price']) ? $p['price'] : $data['price']; ?>">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">&euro;</div>
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label>Author</label>
                                    <select name="author" class="form-select" data-select="<?= isset($p['author']) ? $p['author'] : $data['author']; ?>">
                                        <option value="">Select Author</option>
                                        <?php
                                        foreach ($course_authors as $auth) {
                                            echo "<option value=\"{$auth['id']}\">{$auth['name']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus-square"></i> <?= $course_id !== 0 ? 'Update Course' : 'Create Course'; ?></button>
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