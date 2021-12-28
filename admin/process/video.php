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

$folder = $_POST['path'];

$allowedExts = array("mp4", "mov", "3gp", "ogg");
$extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

if (
    (
        ($_FILES["file"]["type"] == "video/mov") ||
        ($_FILES["file"]["type"] == "video/mp4") ||
        ($_FILES["file"]["type"] == "video/3gp") ||
        ($_FILES["file"]["type"] == "video/ogg"))
    && in_array($extension, $allowedExts)
) {
    if ($_FILES["file"]["error"] > 0) {
        echo 1;
    } else {

        $path = "../..";
        $location = "/assets/course/" . $folder . "/" . rand(10000, 99999) . time() . '.' . $extension;
        $new_location = $path . $location;

        if (file_exists($new_location)) {
            echo 2;
        } else {
            move_uploaded_file($_FILES["file"]["tmp_name"], $new_location);
            echo $location;
            exit;
        }
    }
} else {
    echo $_FILES["file"]["type"];
}