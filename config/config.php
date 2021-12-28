<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// define secret key
define('ACCESS', 'QG(m_,Lo1vR=CL[cRy,1NvBuPQS2G~:)#T%#7,),xuzX~C-(q~ODoAGXLu2');

// define path
define('HOME', dirname(__DIR__, 1));

//define main link
//define('LINK', 'http://9238-112-134-148-27.ngrok.io/promised');
define('LINK', 'http://localhost/promised');

//define('LINK', 'https://promised-land.de');

//define('LINK', 'https://promised.alcaline.lk');
//define('LINK', 'http://digitale-zwangsversteigerungen.de');

// define admin link
define('ADMIN', LINK . '/admin');
define('USER', LINK . '/user');

// include functions
require_once HOME . '/config/class.php';
require_once HOME . '/config/db.php';
require_once HOME . '/config/func.php';
require_once HOME . '/config/block.php';
require_once HOME . '/config/mail.php';

?>