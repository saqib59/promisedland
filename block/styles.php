<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once HOME . '/config/config.php';
?>

<div id="siteurl" data-url="<?= LINK ?>"></div>

<link rel="stylesheet" href="<?= LINK ?>/assets/css/bootstrap.min.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
<link rel="stylesheet" href="<?= LINK ?>/assets/css/password.css">
<link rel="stylesheet" href="<?= LINK ?>/assets/css/caleandar.min.css">
<link rel="stylesheet" href="<?= LINK ?>/assets/css/select2.min.css">
<link rel="stylesheet" href="<?= LINK ?>/assets/css/style.css">
<link rel="stylesheet" href="<?= LINK ?>/assets/css/responsive.css">

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-197869132-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-197869132-1');
  gtag('config', 'AW-347311738');
</script>

<!-- Global site tag (gtag.js) - Google Ads: 347311738 -->
<!-- 
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-347311738"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', 'AW-347311738');
</script>
 -->