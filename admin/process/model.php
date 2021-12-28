<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../../config/config.php';

if (admin() == false) {
    redirect("Please login first!", ADMIN . '/login.php');
    exit();
}

if (isset($_FILES['file']['name'])) {
    $filename = $_FILES['file']['name'];
    $imageFileType = pathinfo($filename, PATHINFO_EXTENSION);
    $imageFileType = strtolower($imageFileType);
    $path = "../..";
    $location = "/assets/3d/" . rand(10000, 99999) . time() . '.' . $imageFileType;
    $valid_extensions = array("glb");
    $response = 0;
    if (in_array(strtolower($imageFileType), $valid_extensions)) {
        if (move_uploaded_file($_FILES['file']['tmp_name'], $path . $location)) {
            $response = $location;
        }
    }
    echo $response;
    exit;
}
echo 0;