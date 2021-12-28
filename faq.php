<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once './config/config.php';

$faq = $db->query('SELECT * FROM `faq` WHERE `status` = 1;')->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<style>

</style>

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title>F.A.Q - Promised Land</title>
</head>

<body>

    <!-- Start . Section : Header -->
    <?php include HOME . '/block/header.php'; ?>
    <!-- End . Section : Header -->

    <!-- Page Title -->
    <section id="title" class="no-gap">
        <div class="page_header">
            <div class="container">
                <div class="page_header__title">
                    <h2>F.A.Q</h2>
                    <p>Häufige Fragen und Antworten</p>
                </div>
            </div>
        </div>
    </section>

    <section id="faq">
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
                    <div class="faq_widgets">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="faq_widgets__item">
                                    <!-- <div class="faq_widgets__item-title">
                                        <h4>Widget Title #1</h4>
                                    </div> -->
                                    <div class="faq_widgets__item-body">
                                        <p>Für tiefergehendes Wissen steht dir unsere Wissensbibliothek zur Verfügung. Hier erhältst du in Form von <a href="<?= LINK ?>/blog/">Blog-Beiträgen</a> Fachliteratur oder die beliebte Videodatenbank alles wichtige vermittelt.</p>
                                        <p>Jetzt Premium abschließen und Zugriff auf Alexandria bekommen.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-6 col-sm-12 col-12">
                                <div class="faq_widgets__item">
                                    <!-- <div class="faq_widgets__item-title">
                                        <h4>Widget Title #2</h4>
                                    </div> -->
                                    <div class="faq_widgets__item-body">
                                        <p>Bald erhältlich: Buche ein persönliches Beratungsgespräch um dein Fallbeispiel durchzugehen.</p>
                                        <a href="#" class="btn btn-blue">Hier klicken</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-6 col-sm-12 col-12">
                                <div class="faq_widgets__item">
                                    <!-- <div class="faq_widgets__item-title">
                                        <h4>Widget Title #3</h4>
                                    </div> -->
                                    <div class="faq_widgets__item-body">
                                        <p>Du hast Verbesserungsvorschläge oder Feedback, dass du loswerden willst. </p>
                                        <p>Schreib uns jetzt deine Meinung zu Promised Land unter Feedback </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">

                    <?php if ($faq && !empty($faq)) { ?>
                        <div class="faq_list">
                            <div class="accordion" id="faq_list">

                                <?php foreach ($faq as $k => $item) { ?>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button <?= $k == 0 ? '' : 'collapsed' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $k ?>" aria-expanded="<?= $k == 0 ? 'true' : 'false' ?>">
                                                <span><?= $item['question'] ?></span>
                                            </button>
                                        </h2>
                                        <div id="collapse<?= $k ?>" class="accordion-collapse collapse  <?= $k == 0 ? 'show' : '' ?>" data-bs-parent="#faq_list">
                                            <div class="accordion-body"><?= $item['answer'] ?></div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } else { ?>

                    <?php } ?>

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