<?php
$lst_values = $db->query('SELECT DISTINCT `foreclosure_court`, `platform` FROM `listing`;')->fetchAll();
?>
<div class="listing_filtering">
    <form id="listing_filter" action="<?= fullUrl() ?>" method="GET">
        <div class="row row-cols-md-4 row-cols-1">

            <div class="col">
                <label>Bundesland</label>
                <select name="select_state" class="form-select">
                    <option value="">- Bundesland auswählen -</option>
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

            <div class="col">
                <label>Amtsgericht</label>
                <select name="select_court" class="form-select">
                    <option value="">- Amtsgericht wählen -</option>
                    <?php
                    foreach ($lst_values as $item) {
                        echo '<option value="' . $item['foreclosure_court'] . '">' . $item['foreclosure_court'] . '</option>';
                    } ?>
                </select>
            </div>

            <div class="col">
                <label>Portal</label>
                <select name="select_portal" class="form-select">
                    <option value="">- Portal auswählen -</option>
                    <option value="zvg-portal.de">zvg-portal.de</option>
                    <option value="zvg.com">zvg.com</option>
                    <option value="versteigerungspool.de">versteigerungspool.de</option>
                    <option value="hanmark.de">hanmark.de</option>
                    <option value="thueringen.de">thueringen.de</option>
                </select>
            </div>

            <div class="col">
                <label>PDF</label>
                <select name="listing_pdf" class="form-select">
                    <option value="">- PDF Status auswählen -</option>
                    <option value="(No Gutachten and No Exposé)">No Gutachten and No Exposé</option>
                    <option value="(Exposé)">Exposé</option>
                    <option value="(Gutachten)">Gutachten</option>
                </select>
            </div>

        </div>
    </form>
</div>