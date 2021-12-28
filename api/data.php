<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$headers = apache_request_headers();

require_once '../config/config.php';

// Create connection
/* $conn = mysqli_connect("localhost", "root", "", "pl_api");
if (!$conn) {
    die("Connection failed to the db");
}

define('ACCESS', 'QG(m_,Lo1vR=CL[cRy,1NvBuPQS2G~:)#T%#7,),xuzX~C-(q~ODoAGXLu2'); */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // check if access key is right
    if ($headers['accesskey'] == ACCESS || $headers['Accesskey'] == ACCESS) {

        /* Display All rows */
        $listings = $api->query('SELECT * FROM `tbl_data`')->fetchAll();
        echo json_encode($listings, true);

        /* $listings = array();
        $result = mysqli_query($conn, "SELECT * FROM `tbl_data`;");
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $listings[] = $row;
            }
            echo json_encode($listings, true);
        } else {
            echo "No results available";
        } */
    } else {
        echo 'Authorization failed: You don\'t have access to this page';
    }
} else {
    echo 'Authorization failed: You don\'t have access to this page';
}
