<?php

use PHPMailer\PHPMailer\PHPMailer;

require dirname(__DIR__) . '/library/phpmailer/vendor/autoload.php';

$mail = new PHPMailer;
$mail->isSMTP(true);
$mail->Host = 'mx2e99.netcup.net';
$mail->SMTPAuth = true;
$mail->Username = 'info@promised-land.de';
$mail->Password = '202lWlb&';
//$mail->Port = 465;
$mail->Port = 25;
$mail->SMTPDebug = 0;
//$mail->SMTPSecure = 'ssl';
$mail->CharSet = 'UTF-8';
//$mail->Encoding = 'base64';
$mail->setFrom('info@promised-land.de', 'PromisedLand');
$mail->addReplyTo('info@promised-land.de', 'PromisedLand');
$mail->IsHTML(true);

function mail_send($subject, $msg, $email, $name)
{
    global $mail;
    $mail->addAddress($email, $name);
    $mail->Subject = $subject . ' | PromisedLand';
    $mail->Body = emailBody($subject, $msg);
    if ($mail->send()) {
        return true;
    } else {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
        return false;
    }
    //return false;
}

function emailContent($all)
{
    return '<tr><td>' . $all . '</td></tr>';
}

function emailText($text)
{
    return '<p style="margin:0 0 15px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;color:#586b83;">' . $text . '</p>';
}

function emailBtn($link, $text)
{
    return '<p style="margin:0 0 15px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;"><a href="' . $link . '" style="color:#ffffff;text-decoration:none;background:#507ebf;padding:6px 20px;border-radius:4px;display:inline-block;">' . $text . '</a></p>';
}

function emailBody($subject, $content)
{
    $element = '';
    $element .= '<!DOCTYPE html>
    <html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width,initial-scale=1">
            <meta name="x-apple-disable-message-reformatting">
            <title></title>
            <!--[if mso]>
            <noscript>
                <xml>
                <o:OfficeDocumentSettings>
                    <o:PixelsPerInch>96</o:PixelsPerInch>
                </o:OfficeDocumentSettings>
                </xml>
            </noscript>
            <![endif]-->
            <style>
                table, td, div, h1, p {font-family: Arial, sans-serif;}
            </style>
        </head>
        <body style="margin:0;padding:30px 0;background:#F8F8FF;">
            <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#F8F8FF;">
                <tr>
                    <td align="center" style="padding:0;">
                        <table role="presentation" style="width:600px;border-collapse:collapse;border:1px solid #d9e8fd;border-spacing:0;text-align:left;">
                            <tr>
                                <td align="center" style="padding:25px 0;background:#fff;">
                                    <img src="https://promised-land.de/assets/img/logo.png" alt="" width="200" style="height:auto;display:block;" />
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:20px 30px;background:#17304e;">
                                    <h1 style="font-size:18px;margin:0;font-family:Arial,sans-serif;color:#ffffff;">' . $subject . '</h1>
                                </td>
                            </tr>
                            <tr style="background:#fff;">
                                <td style="padding:35px 30px 25px 30px;">
                                    <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                        ' . $content . '
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:20px 30px;background:#17304e;">
                                    <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;font-family:Arial,sans-serif;">
                                        <tr>
                                            <td>
                                                <p style="margin:0;font-size:14px;line-height:16px;font-family:Arial,sans-serif;color:#ffffff;">
                                                &copy; Copyright 2021 <a href="' . LINK . '" style="color:#ffffff;text-decoration:underline;">PromisedLand</a>, Alle Rechte vorbehalten
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
    </html>';
    return $element;
}

function confirm_email($email)
{
    $subject = 'E-Mail bestätigen';
    $name = get_col_data($email, 'email', 'name', 'users');
    $confirmKey = get_col_data($email, 'email', 'email_key', 'users');

    $content = '';
    $content .= emailText("Hallo {$name},");
    $content .= emailText("Wir müssen deine E-Mail-Adresse verifizieren, bevor du auf promised-Land.de zugreifen kannst.");
    $content .= emailText("Überprüfe und bestätige deine E-Mail-Adresse und erhalte Zugriff auf unseren Service:");
    $content .= emailBtn(USER . '/confirm?key=' . $confirmKey, 'E-Mail verifizieren');
    $content .= emailText("Vielen Dank!");
    $content .= emailText("Dein <br>Promised Land Team");

    $msg = emailContent($content);

    return mail_send($subject, $msg, $email, $name);
}

function reset_password($email, $reset_link)
{
    $subject = 'Passwort zurücksetzen';
    $name = get_col_data($email, 'email', 'name', 'users');

    $content = '';
    $content .= emailText("Hallo {$name},");
    $content .= emailText("Es wurde eine Anfrage zum zurücksetzen deines Passwort gestellt. Bestätige diese unter folgendem Button:");
    $content .= emailBtn($reset_link, 'Passwort zurücksetzen');
    $content .= emailText("Wenn du deine Zurücksetzung deines Passworts nicht beantragt hast, dann ignoriere die E-Mail oder kontaktiere den Support.");
    $content .= emailText("Dein <br>Promised Land Team");

    $msg = emailContent($content);

    return mail_send($subject, $msg, $email, $name);
}

function membership_invoice($email, $number, $invoice, $package, $period, $gateway, $start_dt, $end_dt)
{
    $subject = "Rechnung #{$number}";
    $name = get_col_data($email, 'email', 'name', 'users');

    $content = '';
    $content .= emailText("Hallo {$name},");
    $content .= emailText("Für die von uns bereitgestellten Dienstleistungen stellen wir folgende Position in Rechnung:");
    $msg = emailContent($content);

    if (!empty($invoice)) {
        $gateway_content = $gateway . ' (' . $invoice . ')';
    } else {
        $gateway_content = $gateway;
    }

    $table = '<table style="border:2px solid #E5E5E5;color:#919191;font-size:14px;border-collapse:collapse;" 
    border="2" bordercolor="#E5E5E5" width="100%" cellpadding="7" cellspacing="0">
        <tr>
            <td>Mitgliedschaftsplan</td>
            <td>' . $package . '</td>
        </tr>
        <tr>
            <td>Dauer der Mitgliedschaft</td>
            <td>' . $period . '</td>
        </tr>
        <tr>
            <td>Zahlungsabwickler</td>
            <td>' . $gateway_content . '</td>
        </tr>
        <tr>
            <td>Mitgliedschaftsstatus</td>
            <td>Genehmigt</td>
        </tr>
        <tr>
            <td>Beginn deiner Mitgliedschafts</td>
            <td>' . $start_dt . '</td>
        </tr>
        <tr>
            <td>Ablauf deiner Mitgliedschafts</td>
            <td>' . $end_dt . '</td>
        </tr>
    </table>';
    $bill = emailContent($table);

    $fullmsg = $msg . $bill;

    return mail_send($subject, $fullmsg, $email, $name);
}

function booking_confirm($email, $type, $data)
{
    $subject = 'Deine Buchung ist bestätigt';
    $name = get_col_data($email, 'email', 'name', 'users');

    $content = '';
    $content .= emailText("Hallo {$name},");
    $content .= emailText("WIr freuen uns dir mitteilen zu können, dass deine Buchungsanfrage eingegangen und bestätigt wurde.");
    /* if ($type == 'course') {
        $content .= emailText("Die Rechnung hierfür wird in einer separaten E-Mail zugestellt.");
    } */
    $content .= emailText("Buchungsdetails");
    $msg = emailContent($content);

    $table = '';

    $table .= '<table style="margin:0 0 15px 0;border:2px solid #E5E5E5;color:#919191;font-size:14px;border-collapse:collapse;" 
    border="2" bordercolor="#E5E5E5" width="100%" cellpadding="7" cellspacing="0">';

    if ($type == 'course') {
        $table .= '<tr><td>Kurs</td><td>' . $data['title'] . '</td></tr>
            <tr><td>Verfasser</td><td>' . $data['author'] . '</td></tr>
            <tr><td>Preis</td><td>' . $data['amount'] . '</td></tr>
            <tr><td>Zahlungsmethode</td><td>' . $data['gateway'] . '</td></tr>
            <tr><td>Abonnement-Status</td><td>Genehmigt</td></tr>';
    } elseif ($type == 'consultant') {
        $table .= '<tr><td>Subject</td><td>' . $data['subject'] . '</td></tr>
            <tr><td>Price</td><td>' . $data['price'] . '</td></tr>
            <tr><td>Duration</td><td>' . $data['time'] . '</td></tr>';
    } else {
        $table .= '<tr><td>Subject</td><td>' . $data['subject'] . '</td></tr>
            <tr><td>Date</td><td>' . $data['date'] . '</td></tr>
            <tr><td>Location</td><td>' . $data['location'] . '</td></tr>
            <tr><td>Method</td><td>' . $data['method'] . '</td></tr>';
    }

    $table .= '</table>';
    $info = emailContent($table);

    $footer = '';
    $footer .= emailText("Deine Buchung ist bestätigt. Vielen Dank!");
    $footer .= emailText("Hast du Fragen zu deiner Buchung oder Veranstaltung?");
    $footer .= emailBtn(LINK . '/contact/', 'Kontaktiere uns');
    $end = emailContent($footer);

    $fullmsg = $msg . $info . $end;

    return mail_send($subject, $fullmsg, $email, $name);
}

function cancel_membership($membership_id)
{
    global $db;

    $subject = 'Schade, dass du uns verlässt';

    $memInfo = $db->query("SELECT * FROM `membership` WHERE `id` = ?", $membership_id)->fetchArray();

    $user_id = $memInfo['user_id'];
    $end_dt = $memInfo['end_dt'];

    $name = get_col_data($user_id, 'id', 'name', 'users');
    $email = get_col_data($user_id, 'id', 'email', 'users');

    $content = '';
    $content .= emailText("Hallo {$name},");

    $content .= emailText("Deine Mitgliedschaft wird zum {$end_dt} - End of Membership - gekündigt.");
    $content .= emailText("Bis dahin hast du noch vollen Zugriff auf alle Features.");

    $content .= emailText("Dein <br>Promised Land Team");

    $msg = emailContent($content);

    return mail_send($subject, $msg, $email, $name);
}

function listingTable($listing_id)
{
    global $db;

    $listing_title = '';
    $listing_equip = '';

    $listing = $db->query("SELECT * FROM `listing` WHERE `id` = ?", $listing_id)->fetchArray();
    $detailsData = $db->query('SELECT * FROM `details` WHERE `listing_id` = ?', $listing_id)->fetchArray();

    if ($detailsData && !empty($detailsData)) {
        $listing_title = $detailsData['about_type'];
        $listing_equip = $detailsData['listing_equipment'];
    }

    $table = '';
    $table .= '<table style="margin:0 0 15px 0;border:2px solid #E5E5E5;color:#919191;font-size:14px;border-collapse:collapse;" 
    border="2" bordercolor="#E5E5E5" width="100%" cellpadding="7" cellspacing="0">';

    if (!empty($listing_title)) $table .= '<tr><td colspan="2">' . $listing_title . '</td></tr>';
    if (!empty($listing['object_address'])) $table .= '<tr><td colspan="2">' . $listing['object_address'] . '</td></tr>';
    if (!empty($listing['listing_label'])) $table .= '<tr><td>Aktenzeichen</td><td>' . $listing['listing_label'] . '</td></tr>';
    if (!empty($listing['foreclosure_date'])) $table .= '<tr><td>Versteigerungsdatum</td><td>' . $listing['foreclosure_date'] . '</td></tr>';
    if (!empty($listing['object_val'])) $table .= '<tr><td>Verkehrswert</td><td>' . $listing['object_val'] . '</td></tr>';
    if (!empty($listing_equip)) $table .= '<tr><td>Besondere Ausstattung</td><td>' . $listing_equip . '</td></tr>';

    $table .= '</table>';

    return emailContent($table);
}

function listing_cancelled($listing_id, $user_id)
{
    $subject = 'Versteigerung abgesagt';

    $listing_label = get_data($listing_id, 'listing_label', 'listings');
    $name = get_data($user_id, 'name', 'users');
    $email = get_data($user_id, 'email', 'users');

    $content = '';
    $content .= emailText("Hallo {$name},");
    $content .= emailText("Die Versteigerung zu dem Objekt {$listing_label} wurde abgesagt.");
    $content .= emailText("Objekt:");
    $msg = emailContent($content);

    // object description
    $info = listingTable($listing_id);

    $footer = emailBtn(USER . '/login/?redirect=/user/request/', 'Jetzt Merkliste anschauen');
    $ft = emailContent($footer);

    $full_mail = $msg . $info . $ft;

    return mail_send($subject, $full_mail, $email, $name);
}

function listing_auction($listing_id, $user_id)
{
    $subject = 'Versteigerung Erinnerung';

    $listing_label = get_data($listing_id, 'listing_label', 'listings');
    $listing_slug = get_data($listing_id, 'listing_slug', 'listings');

    $listing_days = '7';

    $name = get_data($user_id, 'name', 'users');
    $email = get_data($user_id, 'email', 'users');

    $content = '';
    $content .= emailText("Hallo {$name},");
    $content .= emailText("Die Versteigerung zu dem Objekt {$listing_label} steht in {$listing_days} Tagen an.");
    $content .= emailText("Objekt:");
    $main = emailContent($content);

    // object description
    $info = listingTable($listing_id);

    $footer = '';
    $footer .= emailBtn(LINK . '/listing/' . $listing_slug . '/', 'Zum Objekt');
    $footer .= emailText("Kontaktiere nochmal das Amtsgericht, um sicher zu stellen, dass der Termin Ort kurzfristig nicht verlegt wurde.");

    $ft = emailContent($footer);

    $full_mail = $main . $info . $ft;

    return mail_send($subject, $full_mail, $email, $name);
}

function listing_favs($listing_id, $user_id)
{
    $subject = 'Versteigerung abgesagt';

    $listing_label = get_data($listing_id, 'listing_label', 'listings');
    $name = get_data($user_id, 'name', 'users');
    $email = get_data($user_id, 'email', 'users');

    $content = '';
    $content .= emailText("Hallo {$name},");
    $content .= emailText("Zu deiner Merkliste liegen Aktualisierungen vor:");
    // fav listing changes
    $content .= emailText("Schaue dir die Updates jetzt an und verpasse keine Änderung.");
    $content .= emailBtn(USER . '/login/?redirect=/user/favourite/', 'Zur Merkliste');

    $msg = emailContent($content);

    return mail_send($subject, $msg, $email, $name);
}

function search_order_create($user_id, $data)
{
    $subject = 'Dein neuer Suchauftrag';

    $name = get_data($user_id, 'name', 'users');
    $email = get_data($user_id, 'email', 'users');

    $content = '';
    $content .= emailText("Hallo {$name},");
    $content .= emailText("Wir haben deinen Suchauftrag eingerichtet.");
    $content .= emailText("Hier siehst du deine festgelegten Parameter:");
    $msg = emailContent($content);

    // search order parameters
    $table = '';
    $table .= '<table style="margin:0 0 15px 0;border:2px solid #E5E5E5;color:#919191;font-size:14px;border-collapse:collapse;" 
    border="2" bordercolor="#E5E5E5" width="100%" cellpadding="7" cellspacing="0">';

    $table .= '<tr><td>Address</td><td>' . $data['address'] . '</td></tr>
        <tr><td>Radius</td><td>' . $data['radius'] . '</td></tr>
        <tr><td>Objektart</td><td>' . $data['category'] . '</td></tr>
        <tr><td>Wohnfläche (m<sup>2</sup>)</td><td>' . $data['living_space_from'] . ' - ' . $data['living_space_to'] . '</td></tr>
        <tr><td>Zimmer</td><td>' . $data['room_count_from'] . ' - ' . $data['room_count_to'] . '</td></tr>
        <tr><td>Wertgrenze</td><td>' . $data['value_count'] . '</td></tr>
        <tr><td>Verkehrswert (&euro;)</td><td>' . $data['price_from'] . ' - ' . $data['price_to'] . '</td></tr>';

    if (contentStatus(array('premium', 'plus'))) {
        $table .= '<tr><td>Ist-Miete (&euro;)</td><td>' . $data['miete_from'] . ' - ' . $data['miete_to'] . '</td></tr>
        <tr><td>Potenzielle Miete (&euro;)</td><td>' . $data['potential_from'] . ' - ' . $data['potential_to'] . '</td></tr>
        <tr><td>Kaufpreis (&euro;)</td><td>' . $data['kauf_from'] . ' - ' . $data['kauf_to'] . '</td></tr>
        <tr><td>Durchschnittlicher Kaufpreis (&euro;)</td><td>' . $data['preis_from'] . ' - ' . $data['preis_to'] . '</td></tr>
        <tr><td>Potentielle Rendite (%)</td><td>' . $data['rendite_from'] . ' - ' . $data['rendite_to'] . '</td></tr>
        <tr><td>Mietmultiplikator</td><td>' . $data['multiplier_gross_from'] . ' - ' . $data['multiplier_gross_to'] . '</td></tr>
        <tr><td>Geschätzte monatliche Rate (&euro;)</td><td>' . $data['month_payment_from'] . ' - ' . $data['month_payment_to'] . '</td></tr>
        <tr><td>Reports</td><td>' . $data['reports'] . '</td></tr>
        <tr><td>Baujahr</td><td>' . $data['construction_year_from'] . ' - ' . $data['construction_year_to'] . '</td></tr>
        <tr><td>Besondere Ausstattung</td><td>' . $data['listing_equipment'] . '</td></tr>';
    }

    if (contentStatus(array('plus'))) {
        $table .= '<tr><td>3D Model</td><td>' . $data['model'] . '</td></tr>
        <tr><td>Denkmalschutz</td><td>' . $data['denkmalschutz'] . '</td></tr>
        <tr><td>Altlastenverdacht</td><td>' . $data['contaminated'] . '</td></tr>
        <tr><td>Vermietungsverpflichtungen</td><td>' . $data['commitments'] . '</td></tr>
        <tr><td>Vermietungstatus</td><td>' . $data['current_usage'] . '</td></tr>
        <tr><td>Besichtigungsart</td><td>' . $data['inspection_type'] . '</td></tr>
        <tr><td>Wertermittlungsstichtag früher als</td><td>' . $data['report_time'] . '</td></tr>';
    }

    $table .= '</table>';
    $info = emailContent($table);


    $footer = '';
    $footer .= emailText("Du erhältst nun immer die neuesten Objekte zu deinem Suchauftrag per E-Mail und kannst sie jederzeit in deinem Konto ansehen.");
    $footer .= emailBtn(USER . '/login/?redirect=/user/request/', 'Zum Suchauftrag');
    $ft = emailContent($footer);

    $full_msg = $msg . $info . $ft;

    return mail_send($subject, $full_msg, $email, $name);
}

function search_order_result($user_id, $listing_id)
{
    $subject = 'Neue Immobilien für deinen Suchauftrag';

    $name = get_data($user_id, 'name', 'users');
    $email = get_data($user_id, 'email', 'users');

    $content = '';
    $content .= emailText("Hallo {$name},");
    $content .= emailText("Es gibt neue Objekte für deinen Suchauftrag:");
    $msg = emailContent($content);

    // search order result item
    $info = listingTable($listing_id);

    $footer = '';
    $footer .= emailText("Du findest alle Objekte in deinem Konto.");
    $footer .= emailBtn(USER . '/login/?redirect=/user/request/', 'Zum Listing');

    $ft = emailContent($footer);

    $full_mail = $msg . $ft;

    return mail_send($subject, $full_mail, $email, $name);
}
