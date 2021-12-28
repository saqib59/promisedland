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

$tutors = $db->query("SELECT * FROM `admin` WHERE `role` = 'tutor';")->fetchAll();

$data = array(
    'name' => '',
    'position' => '',
    'image' => '',
    'content' => '',
    'focus_work' => '',
    'experience' => '',
    'final_thoughts' => '',
    'tutor' => '',
);

$author_id = 0;
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $author_id = $_GET['edit'];
    $data = $db->query('SELECT * FROM `course_author` WHERE `id` = ?', $author_id)->fetchAll();
    if (!$data) removeEdit();
    $data = $data[0];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;
    $error = array();

    $update_author = false;
    if (empty($error)) {
        if ($author_id !== 0) {
            $update_author = $db->query('UPDATE `course_author` SET `name` = ?, `position` = ?, `image` = ?, `content` = ?, `focus_work` = ?, `experience` = ?, `final_thoughts` = ?, `tutor` = ?, `updated_at` = CURRENT_TIMESTAMP WHERE id = ?;', $p['name'], $p['position'], $p['image'], $p['content'], $p['focus_work'], $p['experience'], $p['final_thoughts'], $p['tutor'], $author_id);
        } else {
            $update_author = $db->query('INSERT INTO `course_author`(`id`, `name`, `position`, `image`, `content`, `focus_work`, `experience`, `final_thoughts`, `tutor`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?);', $p['name'], $p['position'], $p['image'], $p['content'], $p['focus_work'], $p['experience'], $p['final_thoughts'], $p['tutor']);
        }
    }

    if ($update_author) {
        redirect('Author Updated Successfully!', ADMIN . '/course_authors.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title><?= $author_id !== 0 ? 'Update Author' : 'Create Author'; ?> - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4"><?= $author_id !== 0 ? 'Update Author' : 'Create Author'; ?></h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Course</li>
                            <li class="breadcrumb-item active"><?= $author_id !== 0 ? 'Update Author' : 'New Author'; ?></li>
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
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="name" value="<?= isset($p['name']) ? $p['name'] : $data['name']; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Position</label>
                                    <input type="text" class="form-control" name="position" value="<?= isset($p['position']) ? $p['position'] : $data['position']; ?>">
                                </div>

                                <div class="row">
                                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12">
                                        <div class="shd-gallery-area">
                                            <div class="shd-gallery-info">
                                                <i class="far fa-images"></i>
                                                <p>Upload Author Image</p>
                                            </div>
                                            <div class="shd-gallery-btn">
                                                <span>Select Image</span>
                                            </div>
                                            <div id="img_path" data-path="author"></div>
                                            <input type="file" name="upload_image" id="upload_image" accept="image/png, image/jpeg" />
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
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
                                </div>

                                <div class="form-group d-none">
                                    <label>Image</label>
                                    <input type="text" class="form-control" name="image" value="<?= isset($p['image']) ? $p['image'] : $data['image']; ?>">
                                </div>

                                <div class="form-group">
                                    <label>About</label>
                                    <textarea id="foreclosure_desc" class="form-control" name="content"><?= isset($p['content']) ? $p['content'] : $data['content']; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Focus Work</label>
                                    <textarea class="form-control" name="focus_work"><?= isset($p['focus_work']) ? $p['focus_work'] : $data['focus_work']; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Experience</label>
                                    <textarea class="form-control" name="experience"><?= isset($p['experience']) ? $p['experience'] : $data['experience']; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Final Thoughts</label>
                                    <textarea class="form-control" name="final_thoughts"><?= isset($p['final_thoughts']) ? $p['final_thoughts'] : $data['final_thoughts']; ?></textarea>
                                </div>

                                <?php if ($_SESSION['role'] !== 'writer') { ?>
                                    <div class="form-group">
                                        <label>Tutor Account</label>
                                        <select name="tutor" class="form-select" data-select="<?= isset($p['tutor']) ? $p['tutor'] : $data['tutor']; ?>" required>
                                            <option value="">Select Tutor</option>
                                            <?php
                                            foreach ($tutors as $tut) {
                                                echo "<option value=\"{$tut['id']}\">{$tut['user']} ({$tut['email']})</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                <?php } else { ?>
                                    <input name="tutor" type="hidden" value="<?= isset($p['tutor']) ? $p['tutor'] : $data['tutor']; ?>" required>
                                <?php } ?>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus-square"></i> <?= $author_id !== 0 ? 'Update Author' : 'Create Author'; ?></button>
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