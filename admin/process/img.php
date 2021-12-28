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

if (isset($_FILES['file']['name'])) {
    $filename = $_FILES['file']['name'];
    $imageFileType = pathinfo($filename, PATHINFO_EXTENSION);
    $imageFileType = strtolower($imageFileType);
    $path = "../..";
    $location = "/assets/img/" . $folder . "/" . rand(10000, 99999) . time() . '.' . $imageFileType;
    $valid_extensions = array("jpg", "jpeg", "png");
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

/* if(isset($_POST["image"]))
{
 $data = $_POST["image"];
 $url = 'http://localhost/promised/';

 $image_array_1 = explode(";", $data);
 $image_array_2 = explode(",", $image_array_1[1]);
 $data = base64_decode($image_array_2[1]);

 $imageName = rand(10000, 99999) . time() . '.png';

 if(file_put_contents("./gallery/" . $imageName, $data)) {
     echo $url . 'gallery/' . $imageName;
 } else {
     echo "failed";
 }

} */
