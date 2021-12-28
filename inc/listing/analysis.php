<div class="analy-tab">

    <div class="analyse-steps">
        <div class="analyse-steps-inner">
            <div class="analyse-col">
                <div class="analyse-inner active" data-linking="1">
                    <div class="analyse-number">
                        <span>1</span>
                    </div>
                    <div class="analyse-title">
                        <span>Schritt 1</span>
                    </div>
                </div>
            </div>
            <div class="analyse-col">
                <div class="analyse-inner" data-linking="2">
                    <div class="analyse-number">
                        <span>2</span>
                    </div>
                    <div class="analyse-title">
                        <span>Schritt 2</span>
                    </div>
                </div>
            </div>
            <div class="analyse-col">
                <div class="analyse-inner" data-linking="3">
                    <div class="analyse-number">
                        <span>3</span>
                    </div>
                    <div class="analyse-title">
                        <span>Schritt 3</span>
                    </div>
                </div>
            </div>
            <div class="analyse-col">
                <div class="analyse-inner" data-linking="4">
                    <div class="analyse-number">
                        <span>4</span>
                    </div>
                    <div class="analyse-title">
                        <span>Schritt 4</span>
                    </div>
                </div>
            </div>
            <div class="analyse-col">
                <div class="analyse-inner" data-linking="5">
                    <div class="analyse-number">
                        <span>5</span>
                    </div>
                    <div class="analyse-title">
                        <span>Schritt 5</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="analyse_step active" data-step="1">
        <div class="tab_content">
            <div class="about_more_title">
                <h4>1. Schritt: Schau dir die Lage an</h4>
            </div>
            <div class="about_more_body">
                <div id="map_tab" class="surr_map">
                    <?php if (!isset($list['object_address']) || empty($list['object_address'])) { ?>
                        <div class="alert alert-info">Sorry! Map not available</div>
                    <?php } else { ?>
                        <div id="map_canvas" data-address="<?= $list['object_address'] ?>"></div>
                    <?php } ?>
                </div>
            </div>
            <div class="analyse_btn">
                <button class="btn btn-blue" data-goto="2">
                    <span>Weiter</span>
                    <i class="fa fa-angle-double-right"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="analyse_step" data-step="2">
        <div class="tab_content">
            <div class="about_more_title">
                <h4>2.Schritt: Schaue dich in der direkten Umgebung um</h4>
            </div>
            <div class="about_more_body">
                <div id="street_tab" class="surr_map">
                    <?php if (!isset($list['object_address']) || empty($list['object_address'])) { ?>
                        <div class="alert alert-info">Street View Not Available</div>
                    <?php } else { ?>
                        <div id="streetview" data-address="<?= $list['object_address'] ?>"></div>
                    <?php } ?>
                </div>
            </div>
            <div class="analyse_btn">
                <button class="btn btn-nope" data-goto="1">
                    <span>Back</span>
                </button>
                <button class="btn btn-blue" data-goto="3">
                    <span>Weiter</span>
                    <i class="fa fa-angle-double-right"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="analyse_step" data-step="3">
        <div class="tab_content">

            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">

                    <div class="suited">
                        <div class="about_more_title">
                            <h4>3. Schritt: Welche Zielgruppe kommt in Frage?</h4>
                        </div>

                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i>
                            <span>Coming Soon!</span>
                        </div>

                        <!-- <div class="suited_body">
                            <div class="row">
                                <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-12">
                                    <div class="suited_chart">
                                        <svg viewBox="0 0 36 36" class="circular-chart">
                                            <path class="circle" stroke-dasharray="75, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                        </svg>
                                        <div class="suited_chart__info">
                                            <h4>75%</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-12">
                                    <div class="suited_info">
                                        <p>Am besten geeignet für</p>
                                        <h2>Families</h2>
                                        <h4>Score: 75%</h4>
                                        <button class="btn btn-blue btn-steps" data-for="5">Erklärung</button>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                    </div>

                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">

                    <div class="value">
                        <div class="about_more_title">
                            <h4>4. Schritt: Wie weit sind die täglichen Bedarfsgeschäfte entfernt?</h4>
                        </div>

                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i>
                            <span>Coming Soon!</span>
                        </div>

                        <!-- <div class="value_body">

                            <div class="value_body-item">
                                <i class="fa fa-shopping-cart"></i>
                                <strong>Supermarket</strong>
                                <span>323m</span>
                            </div>

                            <div class="value_body-item">
                                <i class="fa fa-graduation-cap"></i>
                                <strong>School</strong>
                                <span>126m</span>
                            </div>
                            <div class="value_body-item">
                                <i class="fa fa-car-bus"></i>
                                <strong>Public Transport</strong>
                                <span>271m</span>
                            </div>
                            <div class="value_body-item">
                                <i class="fa fa-trees"></i>
                                <strong>Parks</strong>
                                <span>311m</span>
                            </div>

                        </div> -->

                    </div>

                </div>

            </div>

            <div class="analyse_btn">
                <button class="btn btn-nope" data-goto="2">
                    <span>Back</span>
                </button>
                <button class="btn btn-blue" data-goto="4">
                    <span>Weiter</span>
                    <i class="fa fa-angle-double-right"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="analyse_step" data-step="4">
        <div class="tab_content">
            <div class="about_more_title">
                <h4>5. Schritt: Für eine rentable Vermietung ist eine Makroökonomische positive Entwicklung sehr wichtig</h4>
            </div>
            <div class="about_more_body">

                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i>
                    <span>Coming Soon!</span>
                </div>

                <!-- <div class="charts">
                    <div class="charts_item">
                        <div id="chart_jobless"></div>
                    </div>
                    <div class="charts_item">
                        <div id="chart_inhabitants"></div>
                    </div>
                </div> -->

            </div>
            <div class="analyse_btn">
                <button class="btn btn-nope" data-goto="3">
                    <span>Back</span>
                </button>
                <button class="btn btn-blue" data-goto="5">
                    <span>Weiter</span>
                    <i class="fa fa-angle-double-right"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="analyse_step" data-step="5">
        <div class="tab_content">
            <div class="about_more_title">
                <h4>6. Schritt: Unsere Standortanalyse</h4>
            </div>
            <div class="about_more_body">

                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i>
                    <span>Coming Soon!</span>
                </div>

                <!-- <div class="suite">
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-families-tab" data-bs-toggle="tab" data-bs-target="#nav-families">Familien</button>
                        <button class="nav-link" id="nav-students-tab" data-bs-toggle="tab" data-bs-target="#nav-students">Studenten</button>
                        <button class="nav-link" id="nav-retired-tab" data-bs-toggle="tab" data-bs-target="#nav-retired">Rentner</button>
                        <button class="nav-link" id="nav-singles-tab" data-bs-toggle="tab" data-bs-target="#nav-singles">Alleinstehende</button>
                        <button class="nav-link" id="nav-airbnb-tab" data-bs-toggle="tab" data-bs-target="#nav-airbnb">Ferienwohnungen<br>(z.B. AirBNB)</button>
                    </div>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-families" role="tabpanel">
                            <?php include HOME . '/inc/listing/suite/suite1.php'; ?>
                        </div>
                        <div class="tab-pane fade" id="nav-students" role="tabpanel">
                            <?php include HOME . '/inc/listing/suite/suite2.php'; ?>
                        </div>
                        <div class="tab-pane fade" id="nav-retired" role="tabpanel">
                            <?php include HOME . '/inc/listing/suite/suite3.php'; ?>
                        </div>
                        <div class="tab-pane fade" id="nav-singles" role="tabpanel">
                            <?php include HOME . '/inc/listing/suite/suite4.php'; ?>
                        </div>
                        <div class="tab-pane fade" id="nav-airbnb" role="tabpanel">
                            <?php include HOME . '/inc/listing/suite/suite5.php'; ?>
                        </div>
                    </div>
                </div> -->

                <div class="score">
                    <div class="score_info">
                        <p>Die wichtigsten Fragen rund um unsere Hauseigene Standortanalyse.</p>
                    </div>
                    <div class="score_list">
                        <div class="score_list__item">
                            <div class="score_list__item-title">
                                <i class="fa fa-plus"></i>
                                <strong>Wie kann mir die Standortanalyse helfen?</strong>
                            </div>
                            <div class="score_list__item-body">
                                <p>Unsere Standortanalyse wurde auf Grund von Untersuchungen und Forschungsergebnissen entwickelt und soll dem Vermieter helfen, die geeignete Zielgruppe zu bestimmen. Das hilft die Bedürfnisse der Mieter zu verstehen. Man soll diesen Wert nur als Anhaltspunkt für weitergehende Recherchen nehmen und sich nicht ausschließlich darauf verlassen.</p>
                            </div>
                        </div>
                        <div class="score_list__item">
                            <div class="score_list__item-title">
                                <i class="fa fa-plus"></i>
                                <strong>Wie funktioniert die Standortanalyse?</strong>
                            </div>
                            <div class="score_list__item-body">
                                <p>Verschiedene Zielgruppen haben unterschiedliche Preferenzen. Bei Studenten werden zum Beispiel Universitäten höher gewichtet als bei anderen Gruppen. Bei Familien wird hingegen auf Kindergärten in der Nähe geachtet und Universitäten werden nicht gewertet.</p>
                            </div>
                        </div>
                        <div class="score_list__item">
                            <div class="score_list__item-title">
                                <i class="fa fa-plus"></i>
                                <strong>Meine Immobilie ist derzeit an Studenten vermietet, der Score zeigt aber an, dass es nicht dafür geeignet ist?</strong>
                            </div>
                            <div class="score_list__item-body">
                                <p>Nur weil eine Lage einen geringen Score hat, heißt es nicht, dass die Immobilie nicht auch für diese Gruppe geeignet ist. Es heißt lediglich, dass an diesem Standort keine unmittelbaren Standortfaktoren (wie eine Universität) vorliegen. Einige Studenten sind aber durchaus auch bereit 20 Minuten zur Universität zu fahren und ziehen daher bei dir ein.</p>
                            </div>
                        </div>
                        <div class="score_list__item">
                            <div class="score_list__item-title">
                                <i class="fa fa-plus"></i>
                                <strong>Welche Grenzen hat die Standortanalyse?</strong>
                            </div>
                            <div class="score_list__item-body">
                                <p>Im jetzigen Stand bezieht sie Faktoren wie Wirtschaftswachstum noch nicht mit ein. Auch kann es vorkommen, dass eine Wohnung im 5. Stock liegt und einen hohen Score für Rentner aufweist. Wir arbeiten daran, diese Faktoren zu verbessern.</p>
                            </div>
                        </div>
                    </div>

                </div>


            </div>

            <div class="analyse_btn">
                <button class="btn btn-nope" data-goto="4">
                    <span>Back</span>
                </button>
            </div>
        </div>
    </div>

</div>