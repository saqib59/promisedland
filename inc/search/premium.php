<div class="modal fade shd_modal" id="additionalModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Additional Filters</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="premium_search__inner">

                    <?php if (contentStatus(array('premium', 'plus'))) { ?>

                        <div class="search_info__form-filter">
                            <div class="row row-sm">
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
                                    <div class="search_custom__choose">
                                        <div class="search_custom__choose-block">
                                            <div class="search_custom__choose-title">Ist-Miete</div>
                                            <div class="form-group row row-sm">
                                                <div class="col-6">
                                                    <input type="number" name="miete_from" class="form-control" placeholder="Von">
                                                </div>
                                                <div class="col-6">
                                                    <input type="number" name="miete_to" class="form-control" placeholder="Bis">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="search_custom__choose-block">
                                            <div class="search_custom__choose-title">Potenzielle Miete</div>
                                            <div class="form-group row row-sm">
                                                <div class="col-6">
                                                    <input type="number" name="potential_from" class="form-control" placeholder="Von">
                                                </div>
                                                <div class="col-6">
                                                    <input type="number" name="potential_to" class="form-control" placeholder="Bis">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
                                    <div class="search_custom__choose dark">
                                        <div class="search_custom__choose-block">
                                            <div class="search_custom__choose-title">Kaufpreis</div>
                                            <div class="form-group row row-sm">
                                                <div class="col-6">
                                                    <input type="number" name="kauf_from" class="form-control" placeholder="Von">
                                                </div>
                                                <div class="col-6">
                                                    <input type="number" name="kauf_to" class="form-control" placeholder="Bis">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="search_custom__choose-block">
                                            <div class="search_custom__choose-title">Durchschnittlicher Kaufpreis</div>
                                            <div class="form-group row row-sm">
                                                <div class="col-6">
                                                    <input type="number" name="preis_from" class="form-control" placeholder="Von">
                                                </div>
                                                <div class="col-6">
                                                    <input type="number" name="preis_to" class="form-control" placeholder="Bis">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
                                    <div class="search_custom__choose">
                                        <div class="search_custom__choose-block">
                                            <div class="search_custom__choose-title">Potentielle Rendite (%)</div>
                                            <div class="form-group row row-sm">
                                                <div class="col-6">
                                                    <input type="number" name="rendite_from" class="form-control" placeholder="Von">
                                                </div>
                                                <div class="col-6">
                                                    <input type="number" name="rendite_to" class="form-control" placeholder="Bis">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="search_custom__choose-block">
                                            <div class="search_custom__choose-title">Mietmultiplikator</div>
                                            <div class="form-group row row-sm">
                                                <div class="col-6">
                                                    <input type="number" name="multiplier_gross_from" class="form-control" placeholder="Von">
                                                </div>
                                                <div class="col-6">
                                                    <input type="number" name="multiplier_gross_to" class="form-control" placeholder="Bis">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="search_info__form-price">
                            <div class="search_info__form-price--slider">
                                <label for="amount">Geschätzte monatliche Rate:</label>
                                <span class="show_monthly_range"></span>
                                <div class="monthly_range"></div>
                            </div>
                        </div>

                        <div class="search_info__form-filter">
                            <div class="search_custom__choose dark search_custom__checklist">
                                <div class="search_custom__choose-title">Reports</div>
                                <div class="form-check-inline">
                                    <label class="checker">
                                        <strong>Gutachten</strong>
                                        <input class="form-check-input" type="checkbox" name="reports[]" value="long">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-check-inline">
                                    <label class="checker">
                                        <strong>Exposé</strong>
                                        <input class="form-check-input" type="checkbox" name="reports[]" value="short">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-check-inline">
                                    <label class="checker">
                                        <strong>Kein Gutachten</strong>
                                        <input class="form-check-input" type="checkbox" name="reports[]" value="none">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="search_info__form-labels">
                            <label>Baujahr</label>
                            <div class="row row-sm">
                                <div class="col-6">
                                    <select name="construction_year_from" class="form-select">
                                        <option value="">- Von -</option>
                                        <?php foreach ($construction_year as $item) {
                                            echo "<option value=\"{$item}\">{$item}</option>";
                                        } ?>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <select name="construction_year_to" class="form-select">
                                        <option value="">- Bis -</option>
                                        <?php foreach ($construction_year as $item) {
                                            echo "<option value=\"{$item}\">{$item}</option>";
                                        } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="search_info__form-labels">
                            <label>Besondere Ausstattung (sowas wie Aufzug, Pool)</label>
                            <select class="form-select select2" name="listing_equipment[]" multiple="multiple">
                                <?php
                                foreach ($equipments as $equip) {
                                    echo "<option value=\"{$equip['id']}\">{$equip['label']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                    <?php } ?>

                    <?php if (contentStatus(array('plus'))) { ?>
                        <div class="search_info__form-check">
                            <div class="row row-sm">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="search_custom__choose">
                                        <div class="form-check-inline">
                                            <label class="checker">
                                                <strong>3D Model</strong>
                                                <input class="form-check-input" type="checkbox" name="model3d" value="yes">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="search_custom__choose">
                                        <div class="form-check-inline">
                                            <label class="checker">
                                                <strong>Denkmalschutz</strong>
                                                <input class="form-check-input" type="checkbox" name="denkmalschutz" value="yes">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="search_custom__choose">
                                        <div class="form-check-inline">
                                            <label class="checker">
                                                <strong>Keine Altlastenverdacht</strong>
                                                <input class="form-check-input" type="checkbox" name="altlastenverdacht" value="yes" checked>
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="search_custom__choose">
                                        <div class="form-check-inline">
                                            <label class="checker">
                                                <strong>Keine Vermietungsverpflichtungen</strong>
                                                <input class="form-check-input" type="checkbox" name="mietbindungen" value="yes">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="search_info__form-labels">
                            <div class="form-group row row-sm">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <label>Vermietungstatus</label>
                                    <select name="current_usage" class="form-select">
                                        <option value="">- Derzeitigen Vermietungsstatus auswählen -</option>
                                        <option value="Vermietet">Vermietet</option>
                                        <option value="Eigennutzung">Eigennutzung</option>
                                        <option value="Leerstehend">Leerstehend</option>
                                        <option value="Verpachtet">Verpachtet</option>
                                        <option value="Nicht bewohnbar">Nicht bewohnbar</option>
                                        <option value="Nicht vermietet">Nicht vermietet</option>
                                        <option value="Unbekannt">Unbekannt</option>
                                    </select>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                    <label>Besichtigungsart</label>
                                    <select name="inspection_type" class="form-select">
                                        <option value="">- Besichtigungsart auswählen -</option>
                                        <option value="Innenbesichtigung">Innenbesichtigung</option>
                                        <option value="Außenbesichtigung">Außenbesichtigung</option>
                                        <option value="Teilweise besichtigt">Teilweise besichtigt</option>
                                    </select>
                                </div>
                                <!-- <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <label>Altlastenverdacht</label>
                                <select name="contaminated" class="form-select">
                                    <option value="">- Auswählen -</option>
                                    <option value="1">Bei diesem Grundstück liegen Altlasten vor</option>
                                    <option value="2">Bei diesem Grundstück liegen wahrscheinlich keine Altlasten vor</option>
                                </select>
                            </div> -->
                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                    <label>Wertermittlungsstichtag früher als</label>
                                    <input type="date" name="report_time" class="form-control">
                                    <!-- <div class="search_info__form-calender">
                                    <div class="row row-sm">
                                        <div class="col-6">
                                            <input type="date" name="report_time_from" class="form-control">
                                        </div>
                                        <div class="col-6">
                                            <input type="date" name="report_time_to" class="form-control">
                                        </div>
                                    </div>
                                </div> -->

                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <input type="hidden" id="month_payment_from" name="month_payment_from" value="">
                    <input type="hidden" id="month_payment_to" name="month_payment_to" value="">

                    <div class="search_info__form-search">
                        <button type="submit" class="btn btn-dark">
                            <i class="fa fa-search"></i>
                            <span>Suchen</span>
                        </button>
                    </div>

                </div> <!-- Form End -->

            </div>
        </div>
    </div>
</div>