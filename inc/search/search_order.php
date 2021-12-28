<div class="modal fade shd_modal" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Suchauftrag</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="course_thread">
                    <div class="course_thread-alert"></div>

                    <div class="seminar_details search_order__details">
                        <table>
                            <tr>
                                <td>Address</td>
                                <td><span class="pl_so-address"></span></td>
                            </tr>
                            <tr>
                                <td>Radius</td>
                                <td><span class="pl_so-radius"></span></td>
                            </tr>
                            <tr>
                                <td>Objektart</td>
                                <td><span class="pl_so-category"></span></td>
                            </tr>
                            <tr>
                                <td>Wohnfläche (m<sup>2</sup>)</td>
                                <td>
                                    <span class="pl_so-space_from"></span>
                                    <span> - </span>
                                    <span class="pl_so-space_to"></span>
                                </td>
                            </tr>
                            <tr>
                                <td>Zimmer</td>
                                <td>
                                    <span class="pl_so-rooms_from"></span>
                                    <span> - </span>
                                    <span class="pl_so-rooms_to"></span>
                                </td>
                            </tr>
                            <tr>
                                <td>Wertgrenze</td>
                                <td><span class="pl_so-value"></span></td>
                            </tr>
                            <tr>
                                <td>Verkehrswert (&euro;)</td>
                                <td>
                                    <span class="pl_so-price_from"></span>
                                    <span> - </span>
                                    <span class="pl_so-price_to"></span>
                                </td>
                            </tr>

                            <?php if (contentStatus(array('premium', 'plus'))) { ?>
                                <tr>
                                    <td>Ist-Miete (&euro;)</td>
                                    <td>
                                        <span class="pl_so-miete_from"></span>
                                        <span> - </span>
                                        <span class="pl_so-miete_to"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Potenzielle Miete (&euro;)</td>
                                    <td>
                                        <span class="pl_so-potential_from"></span>
                                        <span> - </span>
                                        <span class="pl_so-potential_to"></span>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Kaufpreis (&euro;)</td>
                                    <td>
                                        <span class="pl_so-kauf_from"></span>
                                        <span> - </span>
                                        <span class="pl_so-kauf_to"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Durchschnittlicher Kaufpreis (&euro;)</td>
                                    <td>
                                        <span class="pl_so-preis_from"></span>
                                        <span> - </span>
                                        <span class="pl_so-preis_to"></span>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Potentielle Rendite (%)</td>
                                    <td>
                                        <span class="pl_so-rendite_from"></span>
                                        <span> - </span>
                                        <span class="pl_so-rendite_to"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Mietmultiplikator</td>
                                    <td>
                                        <span class="pl_so-multiplier_gross_from"></span>
                                        <span> - </span>
                                        <span class="pl_so-multiplier_gross_to"></span>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Geschätzte monatliche Rate (&euro;)</td>
                                    <td>
                                        <span class="pl_so-month_payment_from"></span>
                                        <span> - </span>
                                        <span class="pl_so-month_payment_to"></span>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Reports</td>
                                    <td><span class="pl_so-reports"></span></td>
                                </tr>

                                <tr>
                                    <td>Baujahr</td>
                                    <td>
                                        <span class="pl_so-construction_year_from"></span>
                                        <span> - </span>
                                        <span class="pl_so-construction_year_to"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Besondere Ausstattung</td>
                                    <td><span class="pl_so-listing_equipment"></span></td>
                                </tr>
                            <?php } ?>

                            <?php if (contentStatus(array('plus'))) { ?>
                                <tr>
                                    <td>3D Model</td>
                                    <td><span class="pl_so-model"></span></td>
                                </tr>
                                <tr>
                                    <td>Denkmalschutz</td>
                                    <td><span class="pl_so-denkmalschutz"></span></td>
                                </tr>
                                <tr>
                                    <td>Altlastenverdacht</td>
                                    <td><span class="pl_so-contaminated"></span></td>
                                </tr>
                                <tr>
                                    <td>Vermietungsverpflichtungen</td>
                                    <td><span class="pl_so-commitments"></span></td>
                                </tr>

                                <tr>
                                    <td>Vermietungstatus</td>
                                    <td><span class="pl_so-current_usage"></span></td>
                                </tr>
                                <tr>
                                    <td>Besichtigungsart</td>
                                    <td><span class="pl_so-inspection_type"></span></td>
                                </tr>
                                <tr>
                                    <td>Wertermittlungsstichtag früher als</td>
                                    <td><span class="pl_so-report_time"></span></td>
                                </tr>
                            <?php } ?>

                        </table>
                    </div>

                    <form id="search_order__submit" action="<?= $_SERVER['PHP_SELF']; ?>" method="POST" autocomplete="off">

                        <div class="overlay"></div>

                        <input class="pl_so-address" name="address" type="hidden" value="">
                        <input class="pl_so-radius" name="radius" type="hidden" value="">
                        <input class="pl_so-category" name="category" type="hidden" value="">

                        <input class="pl_so-space_from" name="space_from" type="hidden" value="">
                        <input class="pl_so-space_to" name="space_to" type="hidden" value="">

                        <input class="pl_so-rooms_from" name="rooms_from" type="hidden" value="">
                        <input class="pl_so-rooms_to" name="rooms_to" type="hidden" value="">

                        <input class="pl_so-value" name="value" type="hidden" value="">

                        <input class="pl_so-price_from" name="price_from" type="hidden" value="">
                        <input class="pl_so-price_to" name="price_to" type="hidden" value="">

                        <input class="pl_so-model" name="model" type="hidden" value="">
                        <input class="pl_so-denkmalschutz" name="denkmalschutz" type="hidden" value="">
                        <input class="pl_so-reports" name="reports" type="hidden" value="">

                        <input class="pl_so-miete_from" name="miete_from" type="hidden" value="">
                        <input class="pl_so-miete_to" name="miete_to" type="hidden" value="">

                        <input class="pl_so-potential_from" name="potential_from" type="hidden" value="">
                        <input class="pl_so-potential_to" name="potential_to" type="hidden" value="">

                        <input class="pl_so-kauf_from" name="kauf_from" type="hidden" value="">
                        <input class="pl_so-kauf_to" name="kauf_to" type="hidden" value="">

                        <input class="pl_so-preis_from" name="preis_from" type="hidden" value="">
                        <input class="pl_so-preis_to" name="preis_to" type="hidden" value="">

                        <input class="pl_so-month_payment_from" name="month_payment_from" type="hidden" value="">
                        <input class="pl_so-month_payment_to" name="month_payment_to" type="hidden" value="">

                        <input class="pl_so-rendite_from" name="rendite_from" type="hidden" value="">
                        <input class="pl_so-rendite_to" name="rendite_to" type="hidden" value="">

                        <input class="pl_so-multiplier_gross_from" name="multiplier_gross_from" type="hidden" value="">
                        <input class="pl_so-multiplier_gross_to" name="multiplier_gross_to" type="hidden" value="">

                        <input class="pl_so-current_usage" name="current_usage" type="hidden" value="">
                        <input class="pl_so-inspection_type" name="inspection_type" type="hidden" value="">

                        <input class="pl_so-contaminated" name="contaminated" type="hidden" value="">
                        <input class="pl_so-commitments" name="commitments" type="hidden" value="">

                        <input class="pl_so-listing_equipment" name="listing_equipment" type="hidden" value="">

                        <input class="pl_so-construction_year_from" name="construction_year_from" type="hidden" value="">
                        <input class="pl_so-construction_year_to" name="construction_year_to" type="hidden" value="">

                        <input class="pl_so-report_time" name="report_time" type="hidden" value="">

                        <div class="form-group">
                            <button id="search_order__submit-btn" type="submit" class="btn btn-blue btn-sm">
                                <i class="fa fa-search-plus"></i>
                                <span>Suchauftrag anlegen</span>
                            </button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>
</div>