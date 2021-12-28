<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once  '../inc/account/logged.php';

$questions = $db->query('SELECT * FROM `questions` WHERE `status` = 1;')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $error = '';
    $p = $_POST;

    $Ok = 0;
    foreach ($p as $a) {
        if (!empty($a)) {
            $Ok = 1;
            break;
        }
    }

    $query = '';
    $query = 'INSERT INTO `answers` (`id`, `question_id`, `user_id`, `answer`) VALUES';
    foreach ($p['answer'] as $k => $item) {
        if (!empty($item)) {
            $query .= " (NULL, '{$k}', '{$user}', '{$item}'),";
        }
    }
    $query = rtrim($query, ',');

    if ($Ok == 0) {
        $error = 'Bitte gebe mindestens eine Antwort an';
    }

    if ($error == '') {
        $submit = $db->query($query);
        if ($submit) {
            user_redirect('Danke für deine Antworten!', 'success', LINK . '/user/questions/');
        } else {
            user_redirect('Ewas lief falsch! Bitte erneut probieren.!', 'error', LINK . '/user/questions/');
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include HOME . '/block/meta.php'; ?>
    <?php include HOME . '/block/styles.php'; ?>
    <title>Fragen - Promised Land</title>
</head>

<body>

    <!-- Start . Section : Header -->
    <?php include HOME . '/block/header.php'; ?>
    <!-- End . Section : Header -->

    <section id="account">
        <div class="account">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                        <?php include HOME . '/inc/account/sidebar.php'; ?>
                    </div>
                    <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
                        <div class="account_body">
                            <section id="feedback" class="no-gap">

                                <div class="account_body__title">
                                    <h4>Unsere Fragen an dich</h4>
                                    <p>Um unser Angebot Kundenorientiert zu gestalten, wollen wir hören was du willst. An dieser Stelle werden dir wöchentlich neue Fragen präsentiert. Durch deine Antworten, können wir das System weiterentwickeln.</p>
                                </div>

                                <?php if ($questions) { ?>
                                    <div class="account_body__content">
                                        <div class="improve">

                                            <div class="improve_alert">
                                                <?php if (isset($error) && !empty($error)) {
                                                    echo '<div class="alert alert-danger">' . $error . '</div>';
                                                } ?>
                                            </div>
                                            <form action="<?= fullUrl() ?>" method="POST" autocomplete="off">
                                                <div class="improve_list">
                                                    <?php foreach ($questions as $item) { ?>
                                                        <?php if (!checkAnswered($item['id'], $user)) { ?>
                                                            <div class="improve_list__item">
                                                                <p><?= $item['question'] ?></p>
                                                                <input name="answer[<?= $item['id'] ?>]" type="text" class="form-control" placeholder="Deine Vorschläge">
                                                            </div>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </div>

                                                <div class="improve_button">
                                                    <button class="btn btn-blue">
                                                        <i class="fa fa-question-circle"></i>
                                                        <span>Senden</span>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                <?php } ?>

                            </section>
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