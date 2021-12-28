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


/*
  ** Get top nanonets_ids database and call api to get record then store into database
  */

//$sql = "SELECT * FROM nanonets_ids";
$sql = "SELECT * FROM nanonets_ids WHERE `data_set` IS NULL";
$result = $db->query($sql);

if ($result->numRows() > 0) {
    foreach($result->fetchAll() as $data){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://app.nanonets.com/api/v2/Inferences/Model/637fb174-3478-447f-8e5e-03bebfd8e662/ImageLevelInferences/'.$data['nanonets_id'],
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

        $response = json_decode($response,true);

        if ($response['message']== 'Success'){
            $dataSet = [];
            foreach($response['result'][0]['prediction'] as $index => $prediction){
                if(!isset($dataSet[$prediction['label']]))
                {
                    $dataSet[$prediction['label']] = array();
                }
                $dataSet[$prediction['label']][] = isset($prediction['ocr_text']) ? $prediction['ocr_text'] : '';
            }
            $dataSet = json_encode($dataSet);

            //dump($response['result'][0]['input']);

            //$sql = "UPDATE nanonets_ids SET pdf_name='".$response['result'][0]['input']."', data_set='".$dataSet."' WHERE nanonets_id='".$data['nanonets_id']."' limit 1";
            //$db->query($sql);

            //$sql = "UPDATE nanonets_ids SET pdf_name='".$response['result'][0]['input']."', data_set='".$dataSet."' WHERE nanonets_id='".$data['nanonets_id']."' limit 1";
            $db->query("UPDATE nanonets_ids SET pdf_name = ?, data_set = ? WHERE nanonets_id = ? limit 1", $response['result'][0]['input'], $dataSet, $data['nanonets_id']);
        }
    }
}

echo 'Pdf name and fields are saved successfully!';

?>