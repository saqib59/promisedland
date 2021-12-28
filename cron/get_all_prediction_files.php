<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

/* if (admin() == false) {
    redirect("Please login first!", ADMIN . '/login.php');
    exit();
}

if (!role('admin')) {
    redirect("You don't have permission to view this page!", ADMIN . '/login.php');
    exit();
} */

$curl = curl_init();
//18852
//18878
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://app.nanonets.com/api/v2/Inferences/Model/637fb174-3478-447f-8e5e-03bebfd8e662/ImageLevelInferences?start_day_interval=18809&current_batch_day=18889',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Authorization: Basic NTU0OWtOMEkyNHk3dTV4dDlMNkViUVNpaVV3ODh5MHo6'
    ),
));

$response = curl_exec($curl);

curl_close($curl);

$moderateImages = json_decode($response, true)['moderated_images'];
foreach ($moderateImages as $moderateImage) {
    //$sql = "SELECT * FROM nanonets_ids WHERE nanonets_id = '". $moderateImage['id'] ."' limit 1";
    $result = $db->query("SELECT * FROM nanonets_ids WHERE nanonets_id = ? limit 1", $moderateImage['id']);
    if ($result->numRows() > 0) {
    } else{
        $db->query("INSERT INTO nanonets_ids (id, nanonets_id) VALUES (NULL, ?);", $moderateImage['id']);
    }

}

$unmoderateImages = json_decode($response, true)['unmoderated_images'];
foreach ($unmoderateImages as $unmoderateImage) {
    //$sql = "SELECT * FROM nanonets_ids WHERE nanonets_id='".$unmoderateImage['id']."' limit 1";
    $result = $db->query("SELECT * FROM nanonets_ids WHERE nanonets_id = ? limit 1", $unmoderateImage['id']);
    if ($result->numRows() > 0) {
    } else{
        $db->query("INSERT INTO nanonets_ids (id, nanonets_id) VALUES (NULL, ?);", $unmoderateImage['id']);
    }
}

echo 'Nanonets ids are saved successfully!';
