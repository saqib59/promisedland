<div class="step_calculator">

    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">

            <div class="left_calc">

                <div class="calc_step">
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>Adresse des Bewertungsobjektes</label>
                        </div>
                        <div class="col-sm-7">
                            <input placeholder="Address" type="text" class="form-control" id="address" name="address">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>Quadratmeter</label>
                        </div>
                        <div class="col-sm-7">
                            <input placeholder="m²" type="number" class="form-control" id="square_meters" name="square_meters">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>Kaufdatum</label>
                        </div>
                        <div class="col-sm-7">
                            <input value="1900-01-01" type="date" class="form-control" id="purchase_date" name="purchase_date">
                        </div>
                    </div>
                </div>

                <div class="calc_step">
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>Kaufpreis</label>
                        </div>
                        <div class="col-sm-7">
                            <input placeholder="€" type="number" id="price_of_purchase" name="price_of_purchase" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>Kaltmiete pro Monat</label>
                        </div>
                        <div class="col-sm-7">
                            <input placeholder="€" type="number" id="rental" name="rental" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>einfache Bruttomiete</label>
                        </div>
                        <div class="col-sm-7">
                            <input placeholder="%" type="text" id="gross_rental" name="gross_rental" class="form-control" readonly>
                        </div>
                    </div>
                </div>

                <div class="calc_step">
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>Bundesland</label>
                        </div>
                        <div class="col-sm-7">
                            <select name="state" id="state" class="form-select">
                                <option value="" selected></option>
                                <option value="5.0">Baden-Württemberg</option>
                                <option value="3.5">Bayern</option>
                                <option value="6.0">Berlin</option>
                                <option value="6.5">Brandenburg</option>
                                <option value="5.0">Bremen</option>
                                <option value="4.5">Hamburg</option>
                                <option value="6.0">Hessen</option>
                                <option value="5.0">Mecklenburg-Vorpommern</option>
                                <option value="5.0">Niedersachsen</option>
                                <option value="6.5">Nordrhein-Westfalen</option>
                                <option value="5.0">Rheinland-Pfalz</option>
                                <option value="6.5">Saarland</option>
                                <option value="3.5">Sachsen</option>
                                <option value="5.0">Sachsen-Anhalt</option>
                                <option value="6.5">Schleswig-Holstein</option>
                                <option value="6.5">Thüringen</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>Grunderwerbssteuer</label>
                        </div>
                        <div class="col-sm-7">
                            <input placeholder="3.50%" type="number" id="buying_tax" name="buying_tax" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>Immobilienmakler</label>
                        </div>
                        <div class="col-sm-7">
                            <input placeholder="%" type="number" id="estate_agent" name="estate_agent" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>Notar</label>
                        </div>
                        <div class="col-sm-7">
                            <input placeholder="%" type="number" id="notary" name="notary" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>Grundbucheintrag</label>
                        </div>
                        <div class="col-sm-7">
                            <input placeholder="%" type="number" id="register_cost" name="register_cost" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>weitere Nebenkosten</label>
                        </div>
                        <div class="col-sm-7">
                            <input placeholder="%" type="number" id="more_cost" name="more_cost" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>Summe Kaufnebenkosten</label>
                        </div>
                        <div class="col-sm-7">
                            <input placeholder="€" type="text" id="additional_cost" name="additional_cost" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="calc_step">
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>geplante Sanierungskosten</label>
                        </div>
                        <div class="col-sm-7">
                            <input placeholder="€" type="number" id="renovation" name="renovation" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>geplante Sonderumlagen</label>
                        </div>
                        <div class="col-sm-7">
                            <input placeholder="€" type="number" id="contribution" name="contribution" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>Kaufpreis inkl. Nebenkosten</label>
                        </div>
                        <div class="col-sm-7">
                            <input placeholder="€" type="text" id="purchase_cost" name="purchase_cost" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>Kapitalbedarf</label>
                        </div>
                        <div class="col-sm-7">
                            <input placeholder="€" type="text" id="capital_need" name="capital_need" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>Sanierung bei Mieterwechsel</label>
                        </div>
                        <div class="col-sm-7">
                            <input placeholder="€" type="number" id="renovation_ten" name="renovation_ten" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="calc_step">
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>Kreditanteil vom Kaufpreis</label>
                        </div>
                        <div class="col-sm-7">
                            <input placeholder="80%" type="number" max="100" min="0" id="loan_percent" name="loan_percent" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>Kreditbetrag</label>
                        </div>
                        <div class="col-sm-7">
                            <input placeholder="€" type="text" id="loan_amount" name="loan_amount" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>Zinssatz</label>
                        </div>
                        <div class="col-sm-7">
                            <input placeholder="%" type="number" id="interest" name="interest" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>Tilgung in %</label>
                        </div>
                        <div class="col-sm-7">
                            <input placeholder="%" type="number" id="repayment_in" name="repayment_in" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="calc_step">
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>Monatliche Rate</label>
                        </div>
                        <div class="col-sm-7">
                            <input placeholder="€" type="text" id="monthly_payment" name="monthly_payment" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>Schuldenfrei in</label>
                        </div>
                        <div class="col-sm-7">
                            <input placeholder="in years" type="text" value="25" id="debt_free" name="debt_free" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>Eigenkapitalbedarf</label>
                        </div>
                        <div class="col-sm-7">
                            <input placeholder="€" type="text" id="private_capital" name="private_capital" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="calc_step">
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>Nicht umlagerfähiges Hausgeld</label>
                        </div>
                        <div class="col-sm-7">
                            <input placeholder="€" type="number" id="house_money" name="house_money" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>eigene Rücklagen Mietausfall</label>
                        </div>
                        <div class="col-sm-7">
                            <input placeholder="i% of rent" type="number" id="saving" name="saving" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>eigene Rücklagen Renovierung</label>
                        </div>
                        <div class="col-sm-7">
                            <input placeholder="€ p. m²" type="number" id="renovation_saving" name="renovation_saving" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="calc_step">
                    <div class="calc_step__title">
                        <h3>Zwischenergebnis :</h3>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>Monatlicher Cashflow vor Steuern</label>
                        </div>
                        <div class="col-sm-7">
                            <span id="cashFlowWithoutTax"></span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">

            <div class="right_calc">

                <div class="calc_step">
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>Zinsbindung</label>
                        </div>
                        <div class="col-sm-7">
                            <input type="number" id="interest_for_years" name="interest_for_years" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>gleiche Rate danach bei Zinssatz</label>
                        </div>
                        <div class="col-sm-7">
                            <input type="number" id="same_payment" name="same_payment" class="form-control" readonly>
                        </div>
                    </div>
                </div>

                <div class="calc_step">
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>Fläche Haus von Grund in %</label>
                        </div>
                        <div class="col-sm-7">
                            <input type="number" id="house_of_ground" name="house_of_ground" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="calc_step">
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>Baujahr</label>
                        </div>
                        <div class="col-sm-7">
                            <input placeholder="<1925, >1924" type="number" id="year_build" name="year_build" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>Abschreibung in %</label>
                        </div>
                        <div class="col-sm-7">
                            <input placeholder="2,5%, 2%" type="text" id="depreciation" name="depreciation" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>Abschreibung pro Monat</label>
                        </div>
                        <div class="col-sm-7">
                            <input placeholder="€" type="text" id="depreciation_month" name="depreciation_month" class="form-control" readonly>
                        </div>
                    </div>
                </div>

                <div class="calc_step">
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>Persönlicher Steuersatz</label>
                        </div>
                        <div class="col-sm-7">
                            <input type="number" id="tax_rate" name="tax_rate" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>zu Versteuernder Cash Flow</label>
                        </div>
                        <div class="col-sm-7">
                            <input placeholder="€" type="text" id="tax_cashflow" name="tax_cashflow" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>Monthly tax amount</label>
                        </div>
                        <div class="col-sm-7">
                            <input placeholder="€" type="text" id="monthly_tax" name="monthly_tax" class="form-control" readonly>
                        </div>
                    </div>
                </div>

                <div class="calc_step">
                    <div class="calc_step__title">
                        <h3>Final result :</h3>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label>Cashflow after tax</label>
                        </div>
                        <div class="col-sm-7">
                            <span id="cashflowAfterTax"></span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>