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
    <title>Finanzierung - Promised Land</title>
</head>

<body>

    <!-- Start . Section : Header -->
    <?php include HOME . '/block/header.php'; ?>
    <!-- End . Section : Header -->

    <section id="contact" class="no-bot">
        <div class="container">
            <div class="finance">
                <div class="finance_title">
                    <h4>Um mehr über die Finanzierung zu erfahren, schaue dir bitte das folgende Video an.</h4>
                    <p>Wir erklären dir, wie Promised Land bei deiner Finanzierung helfen kann.</p>
                </div>
                <div class="finance_body">
                    <video width="100%" controls>
                        <source src="<?= LINK ?>/assets/vids/advisor.mp4" type="video/mp4">
                        Your browser does not support HTML video.
                    </video>
                </div>
            </div>
        </div>
    </section>

    <section id="contact">
        <div class="container">
            <div class="finance">
                <div class="finance_title">
                    <h4>Anfrage</h4>
                    <p>Erkundige dich über Möglichkeiten zur persönlichen Finanzbereatung</p>
                </div>
                <div class="finance_body">
                    <iframe src="https://promised-land-ug.drklein-plattform.de/apps/lead-form/c50fd221-96d7-4154-8c38-a02e22615e51" title="Kontaktformular" frameborder="0" style="width: 100%; min-height: 900px;" onload="window.addEventListener('message', function (event) {if(event?.data?.type === 'drk-rechner.iframe.resized' && event.data.data.id === 'lead-form') {this.style.height = event.data.data.height + 'px'; this.style.minHeight = 0;}}.bind(this))"></iframe>
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