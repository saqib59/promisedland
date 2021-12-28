<?php

function taxRate($zip)
{
    $city = getState($zip);
    switch ($city) {
        case 'Baden-Württemberg':
            return '5.0';
        case 'Bayern':
            return '3.5';
        case 'Berlin':
            return '6.0';
        case 'Brandenburg':
            return '6.5';
        case 'Bremen':
            return '5.0';
        case 'Hamburg':
            return '4.5';
        case 'Hessen':
            return '6.0';
        case 'Mecklenburg-Vorpommern':
            return '6.0';
        case 'Niedersachsen':
            return '5.0';
        case 'Nordrhein-Westfalen':
            return '6.5';
        case 'Rheinland-Pfalz':
            return '5.0';
        case 'Saarland':
            return '6.5';
        case 'Sachsen':
            return '3.5';
        case 'Sachsen-Anhalt':
            return '5.0';
        case 'Schleswig-Holstein':
            return '6.5';
        case 'Thüringen':
            return '6.5';
        default:
            return '0';
    }
}

/* function plans()
{
    return array(
        'premium' => array(
            '1' => array(
                'product' => 'PROD-1EW9685503324825M',
                'plan' => 'P-6GP749667F770033GMFZJ5XQ',
                'title' => '1 Monat Premium',
                'description' => 'Premium Membership Renew Monthly',
                'count' => '1',
                'discounted' => '49.99',
                'amount' => '34.99',
            ),
            '3' => array(
                'product' => 'PROD-5VM67942JG987392J',
                'plan' => 'P-2V352939L5424881CMGDEVFI',
                'title' => '3 Monate Premium',
                'description' => 'Premium Membership Renew Quarter-Yearly',
                'count' => '3',
                'discounted' => '44.99',
                'amount' => '29.99',
            ),
            '6' => array(
                'product' => 'PROD-32548850FC5467642',
                'plan' => 'P-9L321055M1756035RMFZJVCI',
                'title' => '6 Monate Premium',
                'description' => 'Premium Membership Renew Half-Yearly',
                'count' => '6',
                'discounted' => '34.99',
                'amount' => '24.99',
            ),
            '12' => array(
                'product' => 'PROD-5X11686694702372Y',
                'plan' => 'P-0V9988821U768354AMFZJXZQ',
                'title' => '12 Monate Premium',
                'description' => 'Premium Membership Renew Yearly',
                'count' => '12',
                'discounted' => '29.99',
                'amount' => '19.99',
            ),
        ),
        'plus' => array(
            '1' => array(
                'product' => 'PROD-6LD39504J9649480G',
                'plan' => 'P-05A45058R84048033MFZJVQA',
                'title' => '1 Monat Premium+',
                'description' => 'Premium Membership Renew Monthly',
                'count' => '1',
                'discounted' => '79.99',
                'amount' => '49.99',
            ),
            '3' => array(
                'product' => 'PROD-7H3268938E506641L',
                'plan' => 'P-4NB06227DR413415LMGDEVRA',
                'title' => '3 Monate Premium+',
                'description' => 'Premium Membership Renew Quarter-Yearly+',
                'count' => '3',
                'discounted' => '69.99',
                'amount' => '44.99',
            ),
            '6' => array(
                'product' => 'PROD-9C158946MY495115H',
                'plan' => 'P-2UR36244M9407704XMFZJVJI',
                'title' => '6 Monate Premium+',
                'description' => 'Premium+ Membership Renew Half-Yearly',
                'count' => '6',
                'discounted' => '59.99',
                'amount' => '39.99',
            ),
            '12' => array(
                'product' => 'PROD-4DB37313WC9714234',
                'plan' => 'P-0YP57104G9783181KMFZJYHA',
                'title' => '12 Monate Premium+',
                'description' => 'Premium+ Membership Renew Yearly',
                'count' => '12',
                'discounted' => '49.99',
                'amount' => '34.99',
            ),
        ),
    );
} */

function plans()
{
    return array(
        'premium' => array(
            '1' => array(
                'plan' => 'P-2T679938MY238812FMGDGI7Y',
                'title' => '1 Monat Premium',
                'description' => 'Premium Membership Renew Monthly',
                'count' => '1',
                'discounted' => '49.99',
                'amount' => '34.99',
            ),
            '3' => array(
                'plan' => 'P-7P110698XF804351EMGDGJUQ',
                'title' => '3 Monate Premium',
                'description' => 'Premium Membership Renew Quarter-Yearly',
                'count' => '3',
                'discounted' => '44.99',
                'amount' => '29.99',
            ),
            '6' => array(
                'plan' => 'P-5SL43523BL3219443MGDGKAY',
                'title' => '6 Monate Premium',
                'description' => 'Premium Membership Renew Half-Yearly',
                'count' => '6',
                'discounted' => '34.99',
                'amount' => '24.99',
            ),
            '12' => array(
                'plan' => 'P-6FE80593H67094603MGDGKTI',
                'title' => '12 Monate Premium',
                'description' => 'Premium Membership Renew Yearly',
                'count' => '12',
                'discounted' => '29.99',
                'amount' => '19.99',
            ),
        ),
        'plus' => array(
            '1' => array(
                'plan' => 'P-9AH756481H2465610MGDGLFY',
                'title' => '1 Monat Premium+',
                'description' => 'Premium Membership Renew Monthly',
                'count' => '1',
                'discounted' => '79.99',
                'amount' => '49.99',
            ),
            '3' => array(
                'plan' => 'P-3HW50581UP1150735MGDGLPA',
                'title' => '3 Monate Premium+',
                'description' => 'Premium Membership Renew Quarter-Yearly+',
                'count' => '3',
                'discounted' => '69.99',
                'amount' => '44.99',
            ),
            '6' => array(
                'plan' => 'P-5KW95516LC412331GMGDGLWQ',
                'title' => '6 Monate Premium+',
                'description' => 'Premium+ Membership Renew Half-Yearly',
                'count' => '6',
                'discounted' => '59.99',
                'amount' => '39.99',
            ),
            '12' => array(
                'plan' => 'P-49D577331B2803346MGDGL7A',
                'title' => '12 Monate Premium+',
                'description' => 'Premium+ Membership Renew Yearly',
                'count' => '12',
                'discounted' => '49.99',
                'amount' => '34.99',
            ),
        ),
    );
}

function paypalPlans()
{
    return array(
        'P-6GP749667F770033GMFZJ5XQ' => array(
            'type' => 'premium',
            'months' => '1'
        ),
        'P-9L321055M1756035RMFZJVCI' => array(
            'type' => 'premium',
            'months' => '6'
        ),
        'P-0V9988821U768354AMFZJXZQ' => array(
            'type' => 'premium',
            'months' => '12'
        ),
        'P-05A45058R84048033MFZJVQA' => array(
            'type' => 'plus',
            'months' => '1'
        ),
        'P-2UR36244M9407704XMFZJVJI' => array(
            'type' => 'plus',
            'months' => '6'
        ),
        'P-0YP57104G9783181KMFZJYHA' => array(
            'type' => 'plus',
            'months' => '12'
        ),
    );
}

function paymentStatus($status)
{
    $result = [];
    switch ($status) {
        case 'complete':
            $result = array(
                'title' => 'Zahlung erfolgreich!',
                'content' => 'Wir haben deine Zahlung erhalten! Genieße deine Premium Mitgliedschaft.',
            );
            break;
        case 'error':
            $result = array(
                'title' => 'Etwas lief falsch!',
                'content' => 'Wir haben Probleme deine Zahlung zu erhalten. Bitte versuche es erneut oder kontaktiere den Support.',
            );
            break;
        case 'cancel':
            $result = array(
                'title' => 'Die Zahlung wurde stoniert!',
                'content' => 'Du hast deine Zahlung stoniert.',
            );
            break;
    }
    return $result;
}

function paymentCourse($status)
{
    $result = [];
    switch ($status) {
        case 'complete':
            $result = array(
                'title' => 'Zahlung erfolgreich!',
                'content' => 'We haben deine Zahlung erhalten! Viel Spaß an deinem neuen Kurs.',
            );
            break;
        case 'error':
            $result = array(
                'title' => 'Etwas lief falsch!',
                'content' => 'Wir haben Probleme deine Zahlung zu erhalten. Bitte versuche es erneut oder kontaktiere den Support.',
            );
            break;
        case 'cancel':
            $result = array(
                'title' => 'Die Zahlung wurde stoniert!',
                'content' => 'Du hast deine Zahlung stoniert.',
            );
            break;
    }
    return $result;
}

function energy_width($val)
{
    $result = 0;
    switch ($val) {
        case 'A+':
            $result = 0;
            break;
        case 'A':
            $result = 1;
            break;
        case 'B':
            $result = 2;
            break;
        case 'C':
            $result = 3;
            break;
        case 'D':
            $result = 4;
            break;
        case 'E':
            $result = 5;
            break;
        case 'F':
            $result = 6;
            break;
        case 'G':
            $result = 7;
            break;
        case 'H':
            $result = 8;
            break;
    }
    return $result * 11.1111111;
}

function get_extra_field_html($dataSet, $label)
{
    $html = '';

    if (!empty($dataSet) && isset($dataSet[$label]) && !empty($dataSet[$label])) {
        $html .= '<div class="form-group extra-field">';
        $html .= '<label>' . $label . '</label>';
        $html .= '<ul>';

        foreach ($dataSet[$label] as $key => $value) {
            $html .= '<li style="list-style-type:none;">' . $value . '</li>';
        }
        $html .= '</ul>';
        $html .= '</div>';
    }
    echo $html;
}

function getListingSteps($row_id)
{
    $edit = '';
    $edit .= '<div class="actions">';

    $edit .= '<a class="btn btn-secondary" href="' . ADMIN . '/update_listing.php?listing_id=' . $row_id . '">Step 1</a>';
    $edit .= '<a class="btn btn-secondary" href="' . ADMIN . '/update_first.php?listing_id=' . $row_id . '">Step 2</a>';
    $edit .= '<a class="btn btn-secondary" href="' . ADMIN . '/update_second.php?listing_id=' . $row_id . '">Step 3</a>';
    $edit .= '<a class="btn btn-secondary" href="' . ADMIN . '/update_third.php?listing_id=' . $row_id . '">Step 4</a>';

    $edit .= '</div>';
    return $edit;
}

function userMembBox($user_status, $user_plan, $show_renew = false)
{
    $elm = '';
    if ($show_renew && ($user_status == 'expired' || $user_status == 'rejected')) {

        $elm .=  '<div class="payment_status mb-4">';

        if ($user_status == 'expired') {
            $elm .=  '<h4>Deine Mitgliedschaft ist ausgelaufen</h4>';
        } elseif ($user_status == 'rejected') {
            $elm .=  '<h4>Deine Mitgliedschaft wurde abgelehnt</h4>';
        }

        if ($user_status == 'expired') {
            $elm .=  '<p>Erneure deine Mitgliedschaft <a href="' . LINK . '/packages/">hier</a>.</p>';
        } elseif ($user_status == 'rejected') {
            $elm .=  '<p>Erneuer deine Mitgliedschaft <a href="' . LINK . '/packages/">hier</a>.</p>';
        }

        $elm .=  '</div>';
    } else {
        $elm .=  '<div class="current_membership mb-4">';
        if ($user_status == 'pending') {
            $elm .= '<div class="alert alert-info">';
        } elseif ($user_status == 'expired') {
            $elm .= '<div class="alert alert-danger">';
        } elseif ($user_status == 'rejected') {
            $elm .= '<div class="alert alert-warning">';
        } else {
            $elm .= '<div class="alert alert-success">';
        }
        $elm .= '<i class="fa fa-exclamation-circle"></i> ';
        $elm .= '<strong>Derzeitige Mitgliedschaft:</strong> ';
        if (empty($user_status)) {
            $elm .= '<span>' . $user_plan . '</span>';
        } else {
            if ($user_plan == 'Free') {
                $elm .= '<span>Gratis</span>';
            } else {
                $elm .= '<span>' . $user_plan . ' (' . ucfirst($user_status) . ')</span>';
            }
        }
        $elm .= '</div>';
        $elm .= '</div>';
    }

    return $elm;
}

function blogNav($item, $type)
{
    if (isset($item['gallery']) && !empty($item['gallery'])) {
        $gallery_list = json_decode($item['gallery'], true);
        $image = $gallery_list[0];
    }

    $elm = '';

    if ($type == 'prev') {
        $elm .= '<a href="' . LINK . '/article/' . $item['id'] . '/" class="post_nav__item">';
    } else {
        $elm .= '<a href="' . LINK . '/article/' . $item['id'] . '/" class="post_nav__item align-right">';
    }

    $elm .= '<div class="post_nav__item-inner">
                <div class="post_nav__item-inner--col left">
                    <div class="post_nav__item-img">
                        <img src="' . LINK . $image . '">
                    </div>
                </div>
                <div class="post_nav__item-inner--col right">
                    <div class="post_nav__item-content">
                        <div class="post_nav__item-content--link">';

    if ($type == 'prev') {
        $elm .= '<i class="fa fa-arrow-left"></i>';
        $elm .= '<span>Previous Post</span>';
    } else {
        $elm .= '<span>Next Post</span>';
        $elm .= '<i class="fa fa-arrow-right"></i>';
    }

    $elm .= '            </div>
                        <div class="post_nav__item-content--title">
                            <h4>' . $item['title'] . '</h4>
                        </div>
                    </div>
                </div>
            </div>
        </a>';
    return $elm;
}