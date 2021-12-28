<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>

<script src="<?= LINK ?>/assets/js/jquery.min.js"></script>
<script src="<?= LINK ?>/assets/js/jquery.lazy.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<script src="<?= LINK ?>/assets/js/bootstrap.min.js"></script>
<script src="<?= LINK ?>/assets/js/owl.carousel.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="<?= LINK ?>/assets/js/charts.js"></script>
<script src="<?= LINK ?>/assets/js/password.min.js"></script>
<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyCiAkUANLWC9y4U4Ngy_o8pNN28jsXf9NY&libraries=places"></script>
<!-- <script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyCiAkUANLWC9y4U4Ngy_o8pNN28jsXf9NY?libraries=places"></script> -->
<script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>

<script src="https://cdn.tiny.cloud/1/joe7sdxx20r1tasd62xih3d4sd9jec4fq082emy0vv0engmb/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script src="https://js.stripe.com/v3/"></script>
<script src="<?= LINK ?>/assets/js/caleandar.min.js"></script>
<script src="<?= LINK ?>/assets/js/select2.full.min.js"></script>
<script src="<?= LINK ?>/assets/js/calculator.js"></script>
<script src="<?= LINK ?>/assets/js/listing_map.js"></script>
<script src="<?= LINK ?>/assets/js/search.js"></script>
<script src="<?= LINK ?>/assets/js/libraries.js"></script>
<script src="<?= LINK ?>/assets/js/script.js"></script>
<script src="<?= LINK ?>/assets/js/responsive.js"></script>

<script type="text/javascript">
    var _iub = _iub || [];
    _iub.csConfiguration = {
        "consentOnContinuedBrowsing": false,
        "lang": "de",
        "siteId": 2352503,
        "countryDetection": true,
        "perPurposeConsent": true,
        "cookiePolicyId": 17675138,
        "banner": {
            "acceptButtonDisplay": true,
            "customizeButtonDisplay": true,
            "position": "float-top-center",
            "acceptButtonColor": "#0073CE",
            "acceptButtonCaptionColor": "white",
            "customizeButtonColor": "#DADADA",
            "customizeButtonCaptionColor": "#4D4D4D",
            "rejectButtonColor": "#0073CE",
            "rejectButtonCaptionColor": "white",
            "textColor": "black",
            "backgroundColor": "white",
            "rejectButtonDisplay": true
        }
    };
</script>
<script type="text/javascript" src="//cdn.iubenda.com/cs/iubenda_cs.js" charset="UTF-8" async></script>