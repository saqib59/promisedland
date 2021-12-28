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

$data = array(
    'code' => '',
    'discount' => '',
    'package' => '',
    'plan' => '',
    'user_limit' => '',
);

$coupon_id = 0;
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $coupon_id = $_GET['edit'];
    $data = $db->query('SELECT * FROM `coupon` WHERE `id` = ?', $coupon_id)->fetchArray();
    if (!$data) removeEdit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = $_POST;
    $error = array();

    $update_coupon = false;
    if (empty($error)) {
        if ($coupon_id !== 0) {
            $update_coupon = $db->query('UPDATE `coupon` SET `code` = ?, `discount` = ?, `package` = ?, `plan` = ?, `user_limit` = ?, `updated_at` = CURRENT_TIMESTAMP WHERE id = ?;', $p['code'], $p['discount'], $p['package'], $p['plan'], $p['user_limit'], $coupon_id);
        } else {
            $update_coupon = $db->query('INSERT INTO `coupon` (`id`, `code`, `discount`, `package`, `plan`, `user_limit`) VALUES (NULL, ?, ?, ?, ?, ?);', $p['code'], $p['discount'], $p['package'], $p['plan'], $p['user_limit']);
        }
    }

    if ($update_coupon) {
        redirect('Coupon Updated Successfully!', ADMIN . '/manage_coupons.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/admin/inc/header.php'; ?>
    <title><?= $coupon_id !== 0 ? 'Update Coupon' : 'Create Coupon'; ?> - PromisedLand</title>
</head>

<body class="sb-nav-fixed">

    <?php include HOME . '/admin/inc/nav.php'; ?>

    <div id="layoutSidenav">

        <?php include HOME . '/admin/inc/sidebar.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    <div class="page_title">
                        <h1 class="mt-4"><?= $coupon_id !== 0 ? 'Update Coupon' : 'Create Coupon'; ?></h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="<?= LINK ?>/admin/">Dashboard</a></li>
                            <li class="breadcrumb-item">Coupons</li>
                            <li class="breadcrumb-item active"><?= $coupon_id !== 0 ? 'Update Coupon' : 'New Coupon'; ?></li>
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
                                    <label>Coupon Code <span class="genarate_coupon">Genarate</span></label>
                                    <input type="text" class="form-control" name="code" value="<?= isset($p['code']) ? $p['code'] : $data['code']; ?>" placeholder="Click Genarate to Genarate a random coupon code" required>

                                </div>

                                <div class="form-group row">
                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                        <label>Membership Package</label>
                                        <select name="package" class="form-select" data-select="<?= isset($p['package']) ? $p['package'] : $data['package']; ?>">
                                            <option value="any">Any</option>
                                            <option value="premium">Premium</option>
                                            <option value="plus">Premium+</option>
                                        </select>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                        <label>Membership Plan</label>
                                        <select name="plan" class="form-select" data-select="<?= isset($p['plan']) ? $p['plan'] : $data['plan']; ?>">
                                            <option value="0">Any</option>
                                            <option value="1">1 Month</option>
                                            <option value="3">3 Months</option>
                                            <option value="6">6 Months</option>
                                            <option value="12">12 Months</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                        <label>Discount</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="discount" min="1" max="100" value="<?= isset($p['discount']) ? $p['discount'] : $data['discount']; ?>" placeholder="Discount Percentage (Ex: 1 - 100)" required>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">%</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                        <label>Usage Limit for User</label>
                                        <input type="number" class="form-control" name="user_limit" min="1" value="<?= isset($p['user_limit']) ? $p['user_limit'] : $data['user_limit']; ?>" placeholder="How many times coupon can use for same user" required>
                                    </div>
                                </div>



                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus-square"></i> <?= $coupon_id !== 0 ? 'Update Coupon' : 'Create Coupon'; ?></button>
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