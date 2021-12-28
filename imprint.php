<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once './config/config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title>Impressum - Promised Land</title>
</head>

<body>

    <!-- Start . Section : Header -->
    <?php include HOME . '/block/header.php'; ?>
    <!-- End . Section : Header -->

    <!-- Start . Section : Title -->
    <section id="title" class="no-gap">
        <div class="page_header">
            <div class="container">
                <div class="page_header__title">
                    <h2>Impressum</h2>
                </div>
            </div>
        </div>
    </section>
    <!-- End . Section : Title -->

    <section id="imprint">
        <div class="container">

            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-5 col-sm-12 col-12">
                    <div class="imprint_box">
                        <div class="imprint_box__title">
                            <h4>Angaben gemäß § 5 TMG</h4>
                        </div>
                        <div class="imprint_box__info">
                            <ul>
                                <li>Promised Land UG (haftungsbeschränkt)</li>
                                <li>Metzlerstr. 26</li>
                                <li>60594 Frankfurt am Main</li>
                                <li>Deutschland</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-8 col-md-7 col-sm-12 col-12">
                    <div class="imprint_more">
                        <table>
                            <tr>
                                <td>E-Mail</td>
                                <td><a href="mailto:info@promised-land.de">info@promised-land.de</a></td>
                            </tr>
                            <tr>
                                <td>Registergericht</td>
                                <td>Amtsgericht Frankfurt am Main</td>
                            </tr>
                            <tr>
                                <td>Registernummer</td>
                                <td>HRB 121 998</td>
                            </tr>
                            <tr>
                                <td>Geschäftsführer</td>
                                <td>Philipp Stein</td>
                            </tr>
                            <tr>
                                <td>Umsatzsteuer-Identifikationsnummer</td>
                                <td>DE342675423</td>
                            </tr>
                            <tr>
                                <td>Plattform der EU-Kommission zur Online-Streitbeilegung</td>
                                <td>
                                    <a href="https://ec.europa.eu/consumers/odr">https://ec.europa.eu/consumers/odr</a>
                                    <div>Wir sind zur Teilnahme an einem Streitbeilegungsverfahren vor einer Verbraucherschlichtungsstelle weder verpflichtet noch bereit.</div>
                                </td>
                            </tr>
                            <tr>
                                <td>Inhaltlich Verantwortliche/r i.S.d. § 18 Abs. 2 MStV</td>
                                <td>Philipp Stein</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Start . Section : Footer -->
    <?php include HOME . '/block/footer.php'; ?>
    <!-- End . Section : Footer -->

    <?php include HOME . '/block/scripts.php'; ?>

</body>

</html>