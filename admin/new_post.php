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

if ( !role('admin') && !role('writer') ) {
    redirect("You don't have permission to view this page!", ADMIN . '/login.php');
    exit();
}

$admin_id = $_SESSION['admin'];

$blog_cat = $db->query("SELECT * FROM `blog_cat`;")->fetchAll();

$data = array(
    'title' => '',
    'gallery' => '',
    'content' => '',
    'category' => '',
    'tags' => '',
);

$post_id = 0;
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $post_id = $_GET['edit'];
    $data = $db->query('SELECT * FROM `blog` WHERE `id` = ?', $post_id)->fetchAll();
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

    if (!isset($p['listing_gallery']) || $p['listing_gallery'] == '') {
        //$error[] = 'Please select 1 image atleast';
        $gallery = '';
    } else {
        $gallery = json_encode($p['listing_gallery'], true);
    }

    $update_post = false;
    if (empty($error)) {
        if ($post_id !== 0) {
            $update_post = $db->query('UPDATE `blog` SET `title` = ?, `slug` = ?, `gallery` = ?, `content` = ?, `category` = ?, `tags` = ?, `author` = ?, `updated_at` = CURRENT_TIMESTAMP WHERE id = ?;', $p['title'], $post_slug, $gallery, $p['content'], $p['category'], $p['tags'], $p['admin_id'], $post_id);
        } else {
            $update_post = $db->query('INSERT INTO `blog`(`id`, `title`, `slug`, `gallery`, `content`, `category`, `tags`, `author`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?);', $p['title'], $post_slug, $gallery, $p['content'], $p['category'], $p['tags'], $p['admin_id']);
        }
    }

    if ($update_post) {
        redirect('Blog Updated Successfully!', ADMIN . '/manage_blog.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title><?= $post_id !== 0 ? 'Update Post' : 'Create Post'; ?> - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4"><?= $post_id !== 0 ? 'Update Post' : 'Create Post'; ?></h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Blog</li>
                            <li class="breadcrumb-item active"><?= $post_id !== 0 ? 'Update Post' : 'New Post'; ?></li>
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

                                <input name="admin_id" type="hidden" value="<?= $admin_id; ?>">

                                <div class="shd-gallery-area">
                                    <div class="shd-gallery-info">
                                        <i class="far fa-images"></i>
                                        <p>Upload images of the blog article</p>
                                    </div>
                                    <div class="shd-gallery-btn">
                                        <span>Select Images</span>
                                    </div>
                                    <div id="img_path" data-path="blog"></div>
                                    <input type="file" name="upload_image" id="upload_image" accept="image/png, image/jpeg" />
                                </div>

                                <div id="uploaded_image" class="upg-gallery-list gallery_blogs">
                                    <?php
                                    if (isset($p['listing_gallery']) && !empty($p['listing_gallery'])) {
                                        //$gallery = json_decode($p['listing_gallery'], true);
                                        $gallery = $p['listing_gallery'];
                                    } elseif (!isset($data['gallery']) && empty($data['gallery'])) {
                                        $gallery = '';
                                    } else {
                                        $gallery = json_decode($data['gallery'], true);
                                    }
                                    if (isset($gallery) && !empty($gallery)) {
                                        foreach ($gallery as $k) { ?>
                                            <div class="upg-inner">
                                                <input name="listing_gallery[]" type="hidden" value="<?= $k ?>">
                                                <img src="<?= LINK . $k ?>">
                                                <div class="upg-delete" data-imgr="<?= $k ?>">
                                                    <i class="fa fa-trash"></i>
                                                </div>
                                            </div>
                                    <?php }
                                    } ?>
                                </div>

                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" class="form-control" name="title" value="<?= isset($p['title']) ? $p['title'] : $data['title']; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Content</label>
                                    <textarea id="foreclosure_desc" class="form-control" name="content"><?= isset($p['content']) ? $p['content'] : $data['content']; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Category</label>
                                    <select name="category" class="form-select" data-select="<?= isset($p['category']) ? $p['category'] : $data['category']; ?>">
                                        <option value="">Select Category</option>
                                        <?php
                                        foreach ($blog_cat as $cat) {
                                            echo "<option value=\"{$cat['id']}\">{$cat['cat_name']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Tags</label>
                                    <textarea class="form-control" name="tags"><?= isset($p['tags']) ? $p['tags'] : $data['tags']; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus-square"></i> <?= $post_id !== 0 ? 'Update Post' : 'Create Post'; ?></button>
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