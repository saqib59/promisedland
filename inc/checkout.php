<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;

$name = '';
$email = '';
$address = '';
$state = '';
$city = '';
$zipcode = '';

$basic = $db->query("SELECT * FROM `users` WHERE `id` = ?;", $user)->fetchArray();
if (!empty($basic)) {
    $name = $basic['name'];
    $email = $basic['email'];
}
$details = $db->query("SELECT * FROM `user_details` WHERE `user_id` = ?;", $user)->fetchArray();
if (!empty($details)) {
    $address = $details['address'];
    $state = $details['state'];
    $city = $details['city'];
    $zipcode = $details['zipcode'];
}
?>
<div class="checkout_form">
    <div class="checkout_form__title">
        <h2>Bezahlvorgang</h2>
    </div>
    <div class="login_alerts"></div>
    <div class="checkout_form__body">

        <input name="method" type="hidden" value="<?= isset($method) ? $method : '' ?>">
        <input name="package" type="hidden" value="<?= isset($package) ? $package : '' ?>">
        <input name="plan" type="hidden" value="<?= isset($plan) ? $plan : '' ?>">

        <div class="form-group row row-sm">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                <label>Name</label>
                <input name="name" type="text" class="form-control" placeholder="Vor- und Nachname" value="<?= $name ?>">
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                <label>Email</label>
                <input name="email" type="email" class="form-control" placeholder="E-Mail-Adresse" value="<?= $email ?>">
            </div>
        </div>
        <div class="form-group">
            <label>Adresse</label>
            <input name="address" type="text" class="form-control" placeholder="Straße und Hausnummer" value="<?= $address ?>">
        </div>
        <div class="form-group">
            <label>Staat</label>
            <select name="state" class="form-select" data-select="<?= $state ?>">
                <option value="">- Bundesland -</option>
                <option value="Thüringen">Thüringen</option>
                <option value="Schleswig-Holstein">Schleswig-Holstein</option>
                <option value="Sachsen-Anhalt">Sachsen-Anhalt</option>
                <option value="Sachsen">Sachsen</option>
                <option value="Saarland">Saarland</option>
                <option value="Rheinland-Pfalz">Rheinland-Pfalz</option>
                <option value="Nordrhein-Westfalen">Nordrhein-Westfalen</option>
                <option value="Niedersachsen">Niedersachsen</option>
                <option value="Mecklenburg-Vorpommern">Mecklenburg-Vorpommern</option>
                <option value="Bremen">Bremen</option>
                <option value="Brandenburg">Brandenburg</option>
                <option value="Hamburg">Hamburg</option>
                <option value="Hessen">Hessen</option>
                <option value="Bayern">Bayern</option>
                <option value="Berlin">Berlin</option>
                <option value="Baden-Württemberg">Baden-Württemberg</option>
            </select>
        </div>
        <div class="form-group row row-sm">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                <label>Stadt</label>
                <input name="city" type="text" class="form-control" placeholder="Stadt" value="<?= $city ?>">
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                <label>PLZ</label>
                <input name="zip" type="text" class="form-control" placeholder="Postleitzahl" value="<?= $zipcode ?>">
            </div>
        </div>
    </div>
    <div class="overlay"></div>
</div>