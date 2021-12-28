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
?>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="<?= ADMIN ?>/assets/demo/chart-area-demo.js"></script>
<script src="<?= ADMIN ?>/assets/demo/chart-bar-demo.js"></script> -->

<script src="<?= LINK ?>/assets/js/jquery.min.js"></script>
<script src="<?= LINK ?>/assets/js/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
<script src="<?= LINK ?>/assets/js/bootstrap.bundle.min.js"></script>

<!-- <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script> -->
<script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
<script src="<?= LINK ?>/assets/js/select2.full.min.js"></script>
<script src="https://cdn.tiny.cloud/1/joe7sdxx20r1tasd62xih3d4sd9jec4fq082emy0vv0engmb/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
<script src="<?= ADMIN ?>/js/scripts.js"></script>