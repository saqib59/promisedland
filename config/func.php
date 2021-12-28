<?php

// format result of var_dump
function dump($result)
{
    echo '<pre>';
    var_dump($result);
    echo '</pre>';
}

// get current visitor IP
function getIP_old()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function getIP()
{
    if (isset($_SERVER)) {
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
            return $_SERVER["HTTP_X_FORWARDED_FOR"];
        if (isset($_SERVER["HTTP_CLIENT_IP"]))
            return $_SERVER["HTTP_CLIENT_IP"];
        return $_SERVER["REMOTE_ADDR"];
    }

    if (getenv('HTTP_X_FORWARDED_FOR'))
        return getenv('HTTP_X_FORWARDED_FOR');
    if (getenv('HTTP_CLIENT_IP'))
        return getenv('HTTP_CLIENT_IP');
    return getenv('REMOTE_ADDR');
}

// get full url of current page
function fullUrl()
{
    return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}

// redirect to another page with an alert
function redirect($msg, $url)
{
    echo "<script>alert('{$msg}'); window.location.href = '{$url}';</script>";
}

function user_redirect($msg, $status, $path)
{
    echo "<link rel=\"stylesheet\" src=\"https://fonts.googleapis.com/css2?family=Poppins:wght@100;400;500;700;800;900&display=swap\">";
    echo "<style>
    .swal-modal {border-radius: 4px;padding: 20px 15px;margin: 0;}
    .swal-icon {transition: 0.5s ease;margin-top: 0px !important;margin-bottom: 10px;transform: scale(0.8);}
    .swal-title {font-family: 'Poppins', sans-serif;color: #17304e;font-size: 18px;font-weight: 500;margin-bottom: 15px !important;padding: 0;}
    .swal-footer {margin-top: 0;text-align: center;padding: 0;margin-bottom: 5px;}
    .swal-button-container {margin: 0 !important;}
    .swal-button {transition: 0.3s ease;background: #17304e;padding: 10px 30px;}
    .swal-button:not([disabled]):hover {background: #507ebf;}
    .swal-button:focus {box-shadow: unset;}</style>";
    echo "<script src=\"https://unpkg.com/sweetalert/dist/sweetalert.min.js\"></script>";
    echo "<script src=\"" . LINK . "/assets/js/jquery.min.js\"></script>";
    echo "<script>$(function() { swal('{$msg}', '', '$status').then((value) => { window.location.href = '{$path}'; }); });</script>";
    exit();
}

// clear login session
function clear_session()
{
    session_unset();
    session_destroy();
}

// check if user is an admin
function admin()
{
    if (isset($_SESSION['admin']) && isset($_SESSION['role']) && isset($_SESSION['ip'])) {
        if ($_SESSION['admin'] == '' || $_SESSION['role'] == '' || $_SESSION['ip'] == '') {
            return false;
        } else {
            if ($_SESSION['ip'] == getIP()) {
                return true;
            } else {
                clear_session();
                redirect("Access Denied!", ADMIN . '/login.php');
            }
        }
    }
    return false;
}

function role($role)
{
    if (isset($_SESSION['role']) && $_SESSION['role'] == $role) {
        return true;
    }
    return false;
}

function roleLink($role)
{
    $link = '/';
    switch ($role) {
        case 'admin':
            $link = '/';
            break;
        case 'manager':
            $link = '/complete_listings.php';
            break;
        case 'writer':
            $link = '/manage_blog.php';
            break;
        case 'tutor':
            $link = '/manage_courses.php';
            break;
        default:
            $link = '/';
    }
    return $link;
}

function user()
{
    if (isset($_SESSION['user']) && isset($_SESSION['ip'])) {
        if ($_SESSION['user'] == '' || $_SESSION['ip'] == '') {
            return false;
        } else {
            if ($_SESSION['ip'] == getIP()) {
                return true;
            } else {
                clear_session();
                redirect("Access Denied!", USER . '/login/');
            }
        }
    }
    return false;
}

// remove edit query var if don't have any data
function removeEdit()
{
    $new_url = preg_replace('/&edit=[^&]*/', '', fullUrl());
    header("Location: $new_url");
}

// add edit query var if already have data
function addEdit()
{
    if (!isset($_GET['edit'])) {
        $new_url = fullUrl() . '&edit=1';
        header("Location: $new_url");
    }
}

// clear unnecessary characters from label to create url slug
function utf8_uri_encode($utf8_string, $length = 0)
{
    $unicode = '';
    $values = array();
    $num_octets = 1;
    $unicode_length = 0;

    $string_length = strlen($utf8_string);
    for ($i = 0; $i < $string_length; $i++) {

        $value = ord($utf8_string[$i]);

        if ($value < 128) {
            if ($length && ($unicode_length >= $length))
                break;
            $unicode .= chr($value);
            $unicode_length++;
        } else {
            if (count($values) == 0) $num_octets = ($value < 224) ? 2 : 3;

            $values[] = $value;

            if ($length && ($unicode_length + ($num_octets * 3)) > $length)
                break;
            if (count($values) == $num_octets) {
                if ($num_octets == 3) {
                    $unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]) . '%' . dechex($values[2]);
                    $unicode_length += 9;
                } else {
                    $unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]);
                    $unicode_length += 6;
                }

                $values = array();
                $num_octets = 1;
            }
        }
    }

    return $unicode;
}

// replace unnecessary symbols for creating slug
function seems_utf8($str)
{
    $length = strlen($str);
    for ($i = 0; $i < $length; $i++) {
        $c = ord($str[$i]);
        if ($c < 0x80) $n = 0;
        elseif (($c & 0xE0) == 0xC0) $n = 1;
        elseif (($c & 0xF0) == 0xE0) $n = 2;
        elseif (($c & 0xF8) == 0xF0) $n = 3;
        elseif (($c & 0xFC) == 0xF8) $n = 4;
        elseif (($c & 0xFE) == 0xFC) $n = 5;
        else return false;
        for ($j = 0; $j < $n; $j++) {
            if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
                return false;
        }
    }
    return true;
}

// create url slug for listing ( will use for blogs, videos later )
function createSlug($title)
{
    $title = strip_tags($title);
    $title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
    $title = str_replace('%', '', $title);
    $title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);
    if (seems_utf8($title)) {
        if (function_exists('mb_strtolower')) {
            $title = mb_strtolower($title, 'UTF-8');
        }
        $title = utf8_uri_encode($title, 200);
    }
    $title = strtolower($title);
    $title = preg_replace('/&.+?;/', '', $title);
    $title = str_replace('.', '-', $title);
    $title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
    $title = preg_replace('/\s+/', '-', $title);
    $title = preg_replace('|-+|', '-', $title);
    $title = trim($title, '-');
    return $title;
}

/* function getAPI()
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, LINK . '/api/data.php');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('accesskey: ' . ACCESS));
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
} */

function curl($type, $url, $headers = null, $post_fields = null)
{
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_POST, $type);
    if (!empty($post_fields)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    }

    if (!empty($headers)) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSLVERSION, 6);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

    $data = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);

    return $data;
}

function numbersOnly($str)
{
    return abs((int) filter_var($str, FILTER_SANITIZE_NUMBER_INT));
}

function paypalToken()
{
    $access_token = '';
    $headers = [
        "Accept: application/json",
        "Accept-Language: en_US",
        "Content-Type: application/x-www-form-urlencoded",
        "Authorization: Basic " . base64_encode('AQCbv4Oi2KWkZNUkl-_Yk4ZAa4lod3XW_s1qWWGYHVzN7vR1irEICn2vgkbouQxxxsiZFQUh3O6nWkAh:EIMBVeC6l7x8PCyPU9JHAVlSHs2JzHBTRUwUazmA8Dg09LVb-lAP0L6Hazc7oZXAyu9SgESX3fu_cZWy'),
    ];
    $url = "https://api.paypal.com/v1/oauth2/token";
    //$url = "https://api-m.sandbox.paypal.com/v1/oauth2/token";
    $data = "grant_type=client_credentials";
    $response = curl(1, $url, $headers, $data);
    if ($response && !empty($response)) {
        $response = json_decode($response, true);
        if (isset($response['access_token']) && !empty($response['access_token'])) {
            $access_token = $response['access_token'];
        }
    }
    return $access_token;
}

function today()
{
    return date("Y-m-d H:i:s");
}

function fixDate($date)
{
    $date = str_replace('.', ' ', $date);
    $date = str_replace('  ', ' ', $date);
    $german = array('Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember');
    $english = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');

    return str_ireplace($german, $english, $date);
}

function fixTime($time)
{
    $time = str_replace('Uhr', '', $time);
    $time = str_replace('-', '', $time);

    return trim($time) . ':00';
}

function cleanForcDate($forc_date)
{
    if (!empty($forc_date)) {
        $normal_time = '00:00:00';
        if (strpos($forc_date, ',') !== false) {
            $date = explode(', ', $forc_date);
            if (count($date) > 1) {
                $date[1] = str_replace('den ', '', $date[1]);
                $normal_date = fixDate($date[1]);
            } else {
                $normal_date = $forc_date;
            }
            if (count($date) > 2) {
                $normal_time = fixTime($date[2]);
            }

            $normal_date = str_replace(' ', '-', $normal_date);
            $full_date = $normal_date . ' ' . $normal_time;
        } elseif (strpos($forc_date, 'um') !== false) {
            $forc_date = str_replace('Uhr', '', $forc_date);
            $forc_date = str_replace(' um ', ' ', $forc_date);
            $forc_date = str_replace('um', '', $forc_date);
            $forc_date = str_replace('  ', ' ', $forc_date);
            $forc_date = trim($forc_date);

            $full_date = $forc_date;
        } elseif (strpos($forc_date, 'Uhr') !== false) {
            $forc_date = str_replace('Uhr', '', $forc_date);
            $forc_date = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $forc_date);
            $forc_date = str_replace('     ', '   ', $forc_date);
            $forc_date = str_replace('   ', '/', $forc_date);
            $forc_date = fixDate($forc_date);
            $forc_date = fixTime($forc_date);
            $forc_date = str_replace(' ', '-', $forc_date);
            $forc_date = str_replace('/', ' ', $forc_date);

            $full_date = $forc_date;
        } else {
            $full_date = $forc_date;
        }

        if (isset($full_date) && !empty($full_date)) {
            $date = date_create($full_date);
            $format_date = date_format($date, "Y-m-d H:i:s");
            /* if ($date instanceof DateTime) {
                $format_date = date_format($date, "Y-m-d H:i:s");
            } else {
                $format_date = '';
            } */
        }
    } else {
        $format_date = '';
    }

    return $format_date;
    //return $normal_date;
}

function price($val)
{
    $val = str_replace(' ', '', $val);
    $val = str_replace(' €', '', $val);
    $val = str_replace('€', '', $val);
    $val = str_replace(' EUR', '', $val);
    $val = str_replace('EUR', '', $val);

    $val = str_replace('.', '', $val);
    return str_replace(',', '.', $val);
    //return str_replace(',', '.', number_format($val));
}

function object_price($val)
{
    $val = str_replace(' ', '', $val);
    $val = str_replace(' €', '', $val);
    $val = str_replace('€', '', $val);
    $val = str_replace(' EUR', '', $val);
    $val = str_replace('EUR', '', $val);

    if (preg_match("/[a-z]/i", $val)) {
        return false;
    } else {
        $val = str_replace('-', '', $val);
        $val = str_replace('.', '', $val);
        $val = str_replace(',', '.', $val);
    }
    return $val;
}

function priceGerman($val)
{
    /* if (strpos($val, ',') !== false) {
        return $val;
    } */
    $val = str_replace(' ', '', $val);
    return number_format($val, 2, ',', '.');
}

function priceClean($val)
{
    $val = price($val);
    return number_format($val, 2, ',', '.');
}

function priceNoCents($val)
{
    $val = price($val);
    return number_format($val, 0, ',', '.');
}

function rateGerman($val)
{
    //return number_format($val, 1, ',', '.');
    return str_replace('.', ',', $val);
}

function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

function validateAge($year)
{
    $year = strtotime($year);
    $min = strtotime('+18 years', $year);
    if (time() < $min) {
        return false;
    }
    return true;
}

function checkPassword($pwd)
{
    if (
        (strlen($pwd) < 5) &&
        (!preg_match("#[0-9]+#", $pwd)) &&
        (!preg_match("#[a-z]+#", $pwd))
    ) {
        return false;
    }
    return true;
}

function randomKey($length)
{
    return substr(md5(time() . rand(100000, 999999)), 0, $length);
}

function dayOnly($date)
{
    $timestamp = strtotime($date);
    return date("Y-m-d", $timestamp);
}

function dayComma($date)
{
    $timestamp = strtotime($date);
    $timestamp = strtotime("last month", $timestamp);
    return date("Y, m, d", $timestamp);
}

function dayCalender($date)
{
    $timestamp = strtotime($date);
    return date("D M j Y G:i:s", $timestamp) . ' GMT+0200';
}

function upload_files($path, $img_name)
{
    $file_path = '/assets/' . $path . '/';
    $target_dir = HOME . $file_path;

    $filename = $target_dir . basename($img_name['name']);
    $imageFileType = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $random = substr(md5(rand(111111, 999999)), 0, 12);
    $target_file = $target_dir . $random . '.' . $imageFileType;

    if (file_exists($target_file)) {
        return false;
    } elseif ($img_name['size'] > 5000000) {
        return false;
    } elseif (
        $imageFileType != 'jpg'
        && $imageFileType != 'png'
        && $imageFileType != 'jpeg'
        && $imageFileType != 'pdf'
        && $imageFileType != 'doc'
    ) {
        return false;
    } else {
        if (move_uploaded_file($img_name['tmp_name'], $target_file)) {
            return $file_path . $random . '.' . $imageFileType;
        } else {
            return false;
        }
    }
}

function upload_avatar($img)
{

    $rmname = $img['tmp_name'];
    $upname = basename($img['name']);
    $imageFileType = strtolower(pathinfo($upname, PATHINFO_EXTENSION));
    if ($imageFileType == 'jpg' || $imageFileType == 'jpeg') {
        $image = imagecreatefromjpeg($rmname);
    } elseif ($imageFileType == 'png') {
        $image = imagecreatefrompng($rmname);
    }
    $random = substr(md5(rand(111111, 999999)), 0, 10);
    $filename = HOME . '/assets/img/users/' . $random . "." . $imageFileType;
    $thumb_width = 200;
    $thumb_height = 200;
    $width = imagesx($image);
    $height = imagesy($image);

    $original_aspect = $width / $height;
    $thumb_aspect = $thumb_width / $thumb_height;
    if ($original_aspect >= $thumb_aspect) {
        $new_height = $thumb_height;
        $new_width = $width / ($height / $thumb_height);
    } else {
        $new_width = $thumb_width;
        $new_height = $height / ($width / $thumb_width);
    }

    $thumb = imagecreatetruecolor($thumb_width, $thumb_height);
    imagecopyresampled($thumb, $image, 0 - ($new_width - $thumb_width) / 2, 0 - ($new_height - $thumb_height) / 2, 0, 0, $new_width, $new_height, $width, $height);
    if (imagejpeg($thumb, $filename, 80)) {
        imagedestroy($thumb);
        return '/assets/img/users/' . $random . "." . $imageFileType;
    }
    return false;
}

function isJson($string)
{
    json_decode($string);
    return json_last_error() === JSON_ERROR_NONE;
}

function listingResults($listing)
{
    ob_start();
    $listing_id = $listing['listing_id'];

    $loop_label = $listing['listing_label'];
    $loop_date = $listing['foreclosure_date'];

    $loop_slug = $listing['listing_slug'];

    $loop_featured = $listing['featured'];
    $loop_report = $listing['report_available'];

    $loop_price = $listing['object_val'];
    $loop_desc = $listing['object_desc'];
    $loop_address = $listing['object_address'];

    $loop_catergory = $listing['new_cat'];

    $loop_title = $listing['about_type'];
    $loop_rooms = $listing['listing_rooms'];

    $loop_space = $listing['living_space'];

    $loop_units = $listing['listing_flats'];
    $loop_use = $listing['use_space'];
    $loop_plot = $listing['plot_area'];
    $loop_owner = $listing['listing_ownership'];
    $loop_limit = $listing['value_limit'];

    $loop_earn_month = $listing['earn_month'];

    $loop_demolished = $listing['demolished'];

    $loop_equip = $listing['listing_equipment'];
    include HOME . '/inc/layout/list.php';
    return ob_get_clean();
}

function categoryArray($new_cat, $main_cat)
{
    $all = array();

    $new_cats = array();
    if ($new_cat && !empty($new_cat)) {
        if (isJson($new_cat)) {
            $new_cats = json_decode($new_cat, true);
            //dump($new_cats);
        } else {
            $new_cats = array(html_entity_decode($new_cat));
        }
    }
    $all = array_merge($all, $new_cats);

    $main_cats = array();
    if ($main_cat && !empty($main_cat)) {
        $main_cats = array(html_entity_decode($main_cat));
    }

    $all = array_merge($all, $main_cats);
    return $all;
}

function getCatArray($listing_id)
{
    $listing_cat = get_data($listing_id, 'new_cat', 'listing');
    $main_cat = get_data($listing_id, 'main_cat', 'listing');

    return categoryArray($listing_cat, $main_cat);
}

function distance($lat1, $lon1, $lat2, $lon2, $unit)
{
    if (($lat1 == $lat2) && ($lon1 == $lon2)) {
        return 0;
    } else {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }
}

function extendStatus($result)
{
    $extended = false;
    if (isset($result) && !empty($result)) {
        foreach ($result as $item) {
            if (isset($item["table"]) && !empty($item["table"])) {
                foreach ($item["table"] as $tb) {
                    if (isset($tb["rooms"]) && !empty($tb["rooms"])) {
                        foreach ($tb["rooms"] as $ta) {
                            if (isset($ta['room'])) {
                                $extended = true;
                            }
                        }
                    }
                }
            }
        }
    }
    return $extended;
}

function create_fields_set($results)
{
    $dataSet = [];
    if ($results->numRows() > 0) {
        foreach ($results->fetchAll() as $index => $nanonet) {
            $dataSetJSON = json_decode($nanonet['data_set'], true);
            if (!empty($dataSetJSON)) {
                foreach ($dataSetJSON as $key => $value) {
                    foreach ($value as $k => $val) {
                        if (!isset($dataSet[$key])) {
                            $dataSet[$key] = array();
                        }
                        if (!in_array($val, $dataSet[$key])) {
                            $dataSet[$key][] = $val;
                        }
                    }
                }
            }
        }
    }

    return $dataSet;
}

function energy_transform($val)
{
    $values = array('0', '30', '50', '75', '100', '130', '160', '250');
    if (in_array($val, $values)) {
        return true;
    }
    return false;
}

function getZip($address)
{
    $zip = '';
    if (strpos($address, ',') !== false) {
        $parts = explode(', ', $address);
        foreach ($parts as $part) {
            $part = preg_replace('/[^0-9.]+/', '', $part);
            $code = substr($part, -5);
            if (is_numeric($code) && strlen($code) == '5') {
                $zip = $code;
            }
        }
    } else {
        $parts = explode(' ', $address);
        foreach ($parts as $part) {
            $part = preg_replace('/[^0-9.]+/', '', $part);
            $code = substr($part, -5);
            if (is_numeric($code) && strlen($code) == '5') {
                $zip = $code;
            }
        }
    }
    return $zip;
}
function getStatebyAddress($address)
{
    //$address = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $address);
    $zip = getZip($address);

    if ($zip == '') {
        $city = '';
    } else {
        $city = getState($zip);
    }
    return $city;
}


function geocode($address)
{
    // url encode the address
    $address = str_replace('"', '', $address);
    $address = urlencode($address);
    $context = stream_context_create(
        array(
            "http" => array(
                "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
            )
        )
    );

    $url = "http://nominatim.openstreetmap.org/?format=json&addressdetails=1&q={$address}&format=json&limit=1";
    // get the json response
    $resp_json = file_get_contents($url, false, $context);
    // decode the json
    $resp = json_decode($resp_json, true);
    if ($resp == NULL) {
        $url_1 = "https://positionstack.com/geo_api.php?query={$address}&output=xml";

        // get the json response
        $resp_json = file_get_contents($url_1, false, $context);
        $resp = json_decode($resp_json, true);
        return array($resp['data'][0]['latitude'], $resp['data'][0]['longitude']);
    }

    return array($resp[0]['lat'], $resp[0]['lon']);
}

function minimumBid($listing_value, $value_limit)
{
    $minimum_bid = '';
    if (!empty($listing_value)) {
        if (preg_match('/[A-Za-z]/', $value_limit) == false) {
            $value_limit = explode('/', $value_limit);
            $minimum_bid = price($listing_value) * price($value_limit[0]) / price($value_limit[1]);
        }
    }
    return $minimum_bid;
}

function contentStatus($roles)
{
    if (user()) {
        $user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;
        $info = userStatus($user);
        if ($info['status'] == 'approved') {
            if (!empty($roles)) {
                foreach ($roles as $role) {
                    if ($role == $info['plan']) {
                        return true;
                    }
                }
            }
        }
    }
    return false;
}

function contentRestric($roles)
{
    $msg = '';
    if (contentStatus($roles) == false) {
        if ($roles == array('premium')) {
            $msg = 'Only Premium members are allowed to access this feature.';
        }
        if ($roles == array('plus')) {
            $msg = 'Only Premium+ members are allowed to access this feature.';
        }
        if ($roles == array('premium', 'plus')) {
            $msg = 'Only Premium & Premium+ members are allowed to access this feature.';
        }
    }

    if ($msg !== '') {
        user_redirect($msg, 'error', LINK);
    }
}

function includeStatus($first_val, $second_val, $main)
{
    $include = 1;
    if (
        (isset($first_val) && !empty($first_val)) ||
        (isset($second_val) && !empty($second_val))
    ) {
        if (empty($main)) {
            $include = 0;
        } else {
            if (!empty($first_val) && !empty($second_val)) {
                if (($first_val <= $main) && ($main <= $second_val)) {
                    //
                } else {
                    $include = 0;
                }
            } else if (!empty($first_val)) {
                if (($first_val > $main)) {
                    $include = 0;
                }
            } elseif (!empty($second_val)) {
                if (($main > $second_val)) {
                    $include = 0;
                }
            }
        }
    }
    return $include;
}

function construction_years()
{
    global $db;
    $construction_year = [];
    $data_energy = $db->query('SELECT DISTINCT `construction_year` FROM `energy`;')->fetchAll();
    if ($data_energy && !empty($data_energy)) {
        foreach ($data_energy as $item) {
            if ($item['construction_year'] !== '') {
                $year = numbersOnly($item['construction_year']);
                $year = substr($year, 0, 4);
                if (strlen($year) == 4) {
                    $construction_year[] = $year;
                }
            }
        }
    }
    sort($construction_year, SORT_NUMERIC);
    return $construction_year;
}
