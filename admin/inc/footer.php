<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

?>

<div id="siteURL" data-url="<?= LINK ?>"></div>
<footer class="py-4 bg-sec mt-auto">
    <div class="container-fluid px-4">
        <div class="small">
            <div class="text-white">Copyright &copy; Promised Land <?= date("Y"); ?></div>
        </div>
    </div>
</footer>