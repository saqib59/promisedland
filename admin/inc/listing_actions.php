<div class="listing_options">
    <div class="row">
        <div class="col-md-5 col-12">
            <form id="listing_assign" action="<?= ADMIN ?>/process/assign.php" method="POST">
                <div class="row">
                    <div class="col-md-8 col-12">
                        <label>Mitarbeiter auswählen</label>
                        <select name="listing_role" class="form-select">
                            <option value="">- Mitarbeiter zuweisen -</option>
                            <?php
                            $managers = $db->query('SELECT * FROM `admin` WHERE `role` = "manager";')->fetchAll();
                            foreach ($managers as $user) {
                                echo '<option value="' . $user['id'] . '">' . $user['user'] . '</option>';
                            } ?>
                        </select>
                    </div>
                    <div class="col-md-4 col-12">
                        <label></label>
                        <button id="listing_role_btn" class="btn btn-primary btn-block" disabled>Zuweisen</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-md-7 col-12">
            <form id="listing_date_filter" action="<?= fullUrl() ?>" method="POST">
                <div class="row">
                    <div class="col">
                        <label>Startdatum</label>
                        <input name="start_date" type="date" class="form-control" placeholder="- Startdatum -" value="<?= isset($_POST['start_date']) ? $_POST['start_date'] : '' ?>">
                    </div>
                    <div class="col">
                        <label>Enddatum</label>
                        <input name="end_date" type="date" class="form-control" placeholder="- Enddatum -" value="<?= isset($_POST['end_date']) ? $_POST['end_date'] : '' ?>">
                    </div>
                    <div class="col">
                        <label></label>
                        <button id="listing_filter_btn" class="btn btn-primary btn-block">Filter</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- <div class="col-md-6 col-12">
            <form id="listing_pdf_search" action="<?= fullUrl() ?>" method="POST">
                <div class="row">
                    <div class="col-md-8 col-12">
                        <select name="listing_pdf" class="form-select">
                            <option value="">Check PDF</option>
                            <option value="(No Gutachten and No Exposé)">No Gutachten and No Exposé</option>
                            <option value="(Exposé)">Exposé</option>
                            <option value="(Gutachten)">Gutachten</option>
                        </select>
                    </div>
                    <div class="col-md-4 col-12">
                        <button id="listing_pdf_btn" class="btn btn-primary btn-block">Filter</button>
                    </div>
                </div>
            </form>
        </div> -->
    </div>
</div>