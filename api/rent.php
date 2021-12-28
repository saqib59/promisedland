<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';

$query = '';
$query .= 'INSERT INTO `rent_house`(`zip`, `avarage_rent`, `median_rent`) VALUES ';

if (($handle = fopen("rent_house.csv", "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    $num = count($data);

    $avg = str_replace(' €', '', $data[1]);
    $avg = str_replace(' ', '', $avg);
    //$avg = object_price($avg);
    $avg = $avg;

    $med = str_replace(' €', '', $data[2]);
    $med = str_replace(' ', '', $med);
    //$avg = object_price($avg);
    $med = $med;

    $zip = str_pad($data[0], 5, '0', STR_PAD_LEFT);
    
    $check = $db->query("SELECT * FROM `rent_house` WHERE zip = ?;", $zip);
    if($check->numRows() > 0) {

    } else {

      //echo $data[0];
    $query .=  '(';
    $query .=  "'" . $zip . "', ";
    $query .=  "'" . $avg . "', ";
    $query .=  "'" . $med . "'";
    $query .=  '), ';
    //echo "<br />\n";
    
    }

    
    
  }
  fclose($handle);
}

echo $query;
