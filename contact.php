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
    <title>Kontakt - Promised Land</title>
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
                    <h2>Kontakt</h2>
                    <p>Nutze das folgende Kontaktformular für deine Anfrage</p>
                </div>
            </div>
        </div>
    </section>
    <!-- End . Section : Title -->

    <section id="contact">
        <div class="container">

            <div class="contact">
                <div class="row justify-content-center">
                    <div class="col-xl-6 col-lg-8 col-md-10 col-sm-12 col-12">
                        <div class="contact_area">

                            <div class="contact_alerts"></div>

                            <form id="contact_form" action="<?= fullUrl() ?>" method="POST" autocomplete="off">
                                <div class="form-group">
                                    <label>Name:</label>
                                    <input name="name" type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>E-Mail:</label>
                                    <input name="email" type="email" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Ich wünsche persönliche Beratung für:</label>
                                    <select name="reason" class="form-select">
                                        <option value="">-- Auswählen --</option>
                                        <option value="ein Objekt/ Gutachten">ein Objekt/ Gutachten</option>
                                        <option value="Vorverhandlungen">Vorverhandlungen</option>
                                        <option value="Versteigerungsbegleitung">Versteigerungsbegleitung</option>
                                        <option value="Anwaltliche Erstberatung">Anwaltliche Erstberatung</option>
                                        <option value="Allgemeine Anfrage">Allgemeine Anfrage</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Nachricht:</label>
                                    <textarea name="message" class="form-control"></textarea>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-dark">
                                        <i class="fa fa-paper-plane"></i>
                                        <span>Abschicken</span>
                                    </button>
                                </div>
                                <div class="overlay"></div>
                            </form>
                        </div>
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