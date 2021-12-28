<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

?>

<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

<!-- <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" /> -->
<link href="https://cdn.datatables.net/1.11.0/css/jquery.dataTables.min.css" rel="stylesheet" />
<link href="<?= LINK ?>/assets/css/select2.min.css" rel="stylesheet" />
<link href="<?= LINK ?>/assets/css/jquery-ui.min.css" rel="stylesheet" />
<link href="<?= ADMIN ?>/css/styles.css" rel="stylesheet" />
<link href="<?= ADMIN ?>/css/custom.css" rel="stylesheet" />