$(document).ready(function () {

    //var siteURL = 'http://localhost/promised';
    var siteURL = $("#siteURL").data("url");

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

    // enable Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    // datatables
    /* const datatablesSimple = document.getElementById('datatablesSimple');
    if (datatablesSimple) {
        var dtables = new simpleDatatables.DataTable(datatablesSimple);
    } */

    var dataTable = $('#datatablesSimple').DataTable({
        language: { search: "Suchen" },
    });

    $('.listing_filtering select').on('change', function () {
        $('.listing_filtering select').not(this).val('');
        dataTable.search($(this).val()).draw();
    });

    // post editor
    tinymce.init({
        selector: '#foreclosure_desc',
        plugins: 'link',
        menubar: 'edit insert format',
        toolbar: 'undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | link | numlist bullist checklist | outdent indent',
    });

    // clear old inputs
    $.fn.inputFilter = function (inputFilter) {
        return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function () {
            if (inputFilter(this.value)) {
                this.oldValue = this.value;
                this.oldSelectionStart = this.selectionStart;
                this.oldSelectionEnd = this.selectionEnd;
            } else if (this.hasOwnProperty("oldValue")) {
                this.value = this.oldValue;
                this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
            } else {
                this.value = "";
            }
        });
    };

    // gallery images sort
    $("#uploaded_image").sortable();
    $("#uploaded_image").disableSelection();

    // gallery image upload
    $("#upload_image").on("change", function () {
        var fd = new FormData();
        var files = $('#upload_image')[0].files;
        var img_path = $("#img_path").attr("data-path");
        if (files.length > 0) {
            //if (files[0].size > 10485760) {
            //    alert('File size is larger than 10MB!');
            //} else {
            fd.append('file', files[0]);
            fd.append('path', img_path);
            $.ajax({
                url: siteURL + "/admin/process/img.php",
                type: "POST",
                data: fd,
                contentType: false,
                processData: false,
                success: function (data) {
                    console.log(data);
                    $("#upload_image").val('');
                    if (data == "0" || data == 0) {
                        alert("Image upload failed!");
                    } else {

                        if (img_path == 'listings' || img_path == 'blog') {
                            $("#uploaded_image").append(
                                '<div class="upg-inner">' +
                                '<input name="listing_gallery[]" type="hidden" value="' + data + '">' +
                                '<img src="' + siteURL + data + '">' +
                                '<div class="upg-delete" data-imgr="' + data + '">' +
                                '<i class="fa fa-trash"></i>' +
                                '</div>' +
                                '</div>'
                            );
                        }

                        if (img_path == 'course' || img_path == 'author' || img_path == 'seminar') {
                            $(".course_img").html('<img src="' + siteURL + data + '">');
                            $("input[name='image']").val(data);
                        }

                    }
                },
            });

            //}
        }
        $("#upload_image").val('');
    });

    // delete image
    $(document).on("click", ".upg-delete", function () {
        $(this).parent().remove();
    })

    // 3d model upload
    $(document).on("change", "#upload_model", function () {
        var fd = new FormData();
        var files = $(this)[0].files;
        if (files.length > 0) {
            fd.append('file', files[0]);
            $.ajax({
                url: siteURL + "/admin/process/model.php",
                type: "POST",
                data: fd,
                contentType: false,
                processData: false,
                cache: false,
                success: function (data) {
                    console.log(data);
                    $("#upload_image").val('');
                    if (data == "0" || data == 0) {
                        alert("3d model upload failed!");
                    } else {
                        $("#model-viewer").html(
                            '<div class="model-preview">' +
                            '<div class="model-delete"><i class="fa fa-trash"></i></div>' +
                            '<input type="hidden" name="model_url" value="' + data + '" />' +
                            '<model-viewer src="' + siteURL + data + '" ar ar-modes="webxr scene-viewer quick-look" environment-image="neutral" auto-rotate camera-controls></model-viewer>' +
                            '</div>'
                        );
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
        $("#upload_image").val('');
    });

    // delete model
    $(document).on("click", ".model-delete", function () {
        $(this).parent().remove();
    })

    // Select the database value
    $("select").each(function () {
        if ($(this).attr("data-select") && $(this).attr("data-select") !== '') {
            var selected = $(this).data("select");

            if ($(this).hasClass("select2")) {
                $(this).val(selected);
            } else {
                if ($(this).find(`option[value='${selected}']`).length) {
                    $(this).find(`option[value='${selected}']`).prop('selected', true);
                } else {
                    var elm = $(this).parent();
                    var name = $(this).attr("name");
                    //console.log(name);
                    elm.find("select").remove();
                    if (!elm.find("input").length) {
                        elm.append('<input type="text" class="form-control" name="' + name + '" value="' + selected + '">');
                    }

                }
            }

        }
    })

    // check radio db value
    if ($(".select-radio").length) {
        var checked = $(".select-radio").attr("data-value");
        $(".select-radio").find(".form-check-input[value=" + checked + "]").prop("checked", true);
    }

    // input limit numbers only
    $("input").each(function () {
        if ($(this).attr("data-numbers") && $(this).attr("data-numbers") == 'true') {
            $(this).inputFilter(function (value) {
                return /^\d*$/.test(value);
            });
        }
    })

    // Select2
    $('.select2').select2();

    function priceNormal(price) {
        price = price.replace(/\./g, '');
        price = price.replace(/,/g, '.');
        return price;
    }

    function priceGerman(float) {
        return float.toLocaleString('de-DE', { minimumFractionDigits: 2 });
    }

    // calculate acquisition tax
    $("#acquisition").find("input[name=object_price], input[name=tax_percentage]").on("change keyup", function () {

        var price = priceNormal($("input[name=object_price]").val());
        var tax_percentage = priceNormal($("input[name=tax_percentage]").val());

        var transfer_tax = (price * tax_percentage) / 100;
        $("input[name=transfer_tax]").val(priceGerman(transfer_tax));

        var court_costs = (price * 0.5) / 100;
        $("input[name=court_costs]").val(priceGerman(court_costs));
        $("input[name=land_register]").val(priceGerman(court_costs));
    })

    // calculate acquisition total cost
    $("#acquisition").find("input").on("change keyup", function () {
        var total = 0;
        $("#acquisition").find("input").each(function () {
            if ($(this).attr("name") !== 'total_cost' && $(this).attr("name") !== 'tax_percentage') {
                if ($(this).val() !== '') {
                    total = total + parseInt(priceNormal($(this).val()));
                }
            }
        })
        $("input[name=total_cost]").val(priceGerman(total));
    })

    // get energy efficiency class
    $("input[name=energy_requirements]").on("keyup", function () {
        var energy = $(this).val();
        var drop = $("[name=efficiency_class]");
        if (energy < 30) {
            drop.val("A+");
        } else if (energy >= 30 && energy < 50) {
            drop.val("A");
        } else if (energy >= 50 && energy < 75) {
            drop.val("B");
        } else if (energy >= 75 && energy < 100) {
            drop.val("C");
        } else if (energy >= 100 && energy < 130) {
            drop.val("D");
        } else if (energy >= 130 && energy < 160) {
            drop.val("E");
        } else if (energy >= 160 && energy < 200) {
            drop.val("F");
        } else if (energy >= 200 && energy < 250) {
            drop.val("G");
        } else if (energy >= 250) {
            drop.val("H");
        }
    })

    // create new cost row
    $(document).on("click", ".add-cost-row", function () {
        var current = $(this).parent().parent().parent().attr("data-current");
        var next = $(this).parent().parent().find(".cost-item:last-child").attr("data-identity");
        var identity = parseInt(next) + 1;
        var elm = ('<div class="cost-item" data-identity="' + identity + '">' +
            '<div class="delete-cost-row">' +
            '<i class="fa fa-times"></i>' +
            '</div>' +
            '<div class="form-group row">' +

            '<div class="col-md-6 col-12">' +
            '<label>Beschreibung</label>' +
            '<input type="text" class="form-control" name="backlog[' + current + '][table][' + next + '][desc]" placeholder="Beschreibung">' +
            '</div>' +

            '<div class="col-md-6 col-12">' +
            '<label>Geschätzter Kostenpunkt</label>' +
            '<input type="text" class="form-control" name="backlog[' + current + '][table][' + next + '][estimated]" placeholder="Geschätzter Kostenpunkt">' +
            '</div>' +


            '</div>' +
            '</div>');
        $(this).parent().parent().find(".cost-list").append(elm);
    })

    // delete cost row
    $(document).on("click", ".delete-cost-row", function () {
        $(this).parent().remove();
    })

    // create new cost block
    $(document).on("click", ".backlog-btn", function () {
        var next = $(this).attr("data-next");

        if ($(".backlog-list").find(".cost-pack").length) {
            var curr = $(".backlog-list").find(".cost-pack:last-child").attr("data-current");
        } else {
            var curr = 0;
        }

        var current = parseInt(curr) + 1;

        var elm = ('<div class="cost-pack" data-current="' + current + '">' +
            '<div class="delete-cost-main">' +
            '<i class="fa fa-trash"></i>' +
            '</div>' +

            '<div class="form-group row">' +
            '<div class="col-md-6 col-12">' +
            '<label>Baumängel</label>' +
            '<input type="text" class="form-control" name="backlog[' + current + '][title]" placeholder="Baumängel">' +
            '</div>' +
            '<div class="col-md-6 col-12">' +
            '<label>Summe gesamt</label>' +
            '<input type="text" class="form-control" name="backlog[' + current + '][total]" placeholder="Summe gesamt">' +
            '</div>' +
            '</div>' +

            /* '<div class="form-group">' +
            '<label>Baumängel</label>' +
            '<input type="text" class="form-control" name="backlog[' + current + '][title]" placeholder="Baumängel">' +
            '</div>' + */

            '<div class="cost-table">' +
            '<div class="cost-list">' +
            '<div class="cost-item" data-identity="1">' +
            '<div class="delete-cost-row">' +
            '<i class="fa fa-times"></i>' +
            '</div>' +
            '<div class="form-group row">' +

            '<div class="col-md-6 col-12">' +
            '<label>Beschreibung</label>' +
            '<input type="text" class="form-control" name="backlog[' + current + '][table][0][desc]" placeholder="Beschreibung">' +
            '</div>' +

            '<div class="col-md-6 col-12">' +
            '<label>Geschätzter Kostenpunkt</label>' +
            '<input type="text" class="form-control" name="backlog[' + current + '][table][0][estimated]" placeholder="Geschätzter Kostenpunkt">' +
            '</div>' +

            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="cost-btn">' +
            '<div class="btn btn-secondary add-cost-row">Neue Zeile hinzufügen</div>' +
            '</div>' +
            '</div>' +
            '</div>');
        $(".backlog-list").append(elm);
        $(this).attr("data-next", parseInt(next) + 1);
    })

    // highlight cost block on delete hover
    $(document).on("mouseenter", ".delete-cost-main", function () {
        $(this).parent().addClass("warning");
    })
    $(document).on("mouseleave", ".delete-cost-main", function () {
        $(this).parent().removeClass("warning");
    })

    // delete cost block
    $(document).on("click", ".delete-cost-main", function () {
        $(this).parent().remove();
    })

    /*************************************************/
    /*************************************************/
    /*************************************************/

    // create new room row
    $(document).on("click", ".add-floor-room-row", function () {
        var current = $(this).parent().parent().parent().attr("data-current");
        var next = $(this).parent().parent().find(".room-item:last-child").attr("data-identity");
        var identity = parseInt(next) + 1;
        var elm = ('<div class="room-item" data-identity="' + identity + '">' +
            '<div class="delete-room-row">' +
            '<i class="fa fa-times"></i>' +
            '</div>' +

            /* '<div class="form-group">' +
            '<label>Zimmername</label>' +
            '<input type="text" class="form-control" name="floor[' + current + '][table][' + next + '][room]" placeholder="Zimmername, z.B. Keller, Schlafzimmer, Abstellraum">' +
            '</div>' + */

            '<div class="form-group row">' +
            '<div class="col-md-6 col-12">' +
            '<label>Zimmername</label>' +
            '<input type="text" class="form-control" name="floor[' + current + '][table][' + next + '][room]" placeholder="Zimmername, z.B. Keller, Schlafzimmer, Abstellraum">' +
            '</div>' +
            '<div class="col-md-6 col-12">' +
            '<label>Anzahl der Räume</label>' +
            '<input type="text" class="form-control" name="floor[' + current + '][table][' + next + '][count]" placeholder="Anzahl der Räume, nummerisch ohne Einheit">' +
            '</div>' +
            '</div>' +


            '</div>');
        $(this).parent().parent().find(".room-list").append(elm);
    })

    // delete room row
    $(document).on("click", ".delete-room-row", function () {
        $(this).parent().remove();
    })

    // create new room block
    $(document).on("click", ".floor-btn", function () {
        //var next = $(this).attr("data-next");

        if ($(".floor-list").find(".room-pack").length) {
            var curr = $(".floor-list").find(".room-pack:last-child").attr("data-current");
        } else {
            var curr = 0;
        }

        var current = parseInt(curr) + 1;

        var elm = ('<div class="room-pack" data-current="' + current + '">' +
            '<div class="delete-room-main">' +
            '<i class="fa fa-trash"></i>' +
            '</div>' +
            '<div class="form-group">' +
            '<label>Stockwerk</label>' +
            '<input type="text" class="form-control" name="floor[' + current + '][title]" placeholder="Stockwerk">' +
            '</div>' +
            '<div class="room-table">' +
            '<div class="room-list">' +
            '<div class="room-item" data-identity="1">' +
            '<div class="delete-room-row">' +
            '<i class="fa fa-times"></i>' +
            '</div>' +

            '<div class="form-group row">' +
            '<div class="col-md-6 col-12">' +
            '<label>Zimmername</label>' +
            '<input type="text" class="form-control" name="floor[' + current + '][table][0][room]" placeholder="Zimmername, z.B. Keller, Schlafzimmer, Abstellraum">' +
            '</div>' +
            '<div class="col-md-6 col-12">' +
            '<label>Anzahl der Räume</label>' +
            '<input type="text" class="form-control" name="floor[' + current + '][table][0][count]" placeholder="Anzahl der Räume, nummerisch ohne Einheit">' +
            '</div>' +
            '</div>' +

            '</div>' +
            '</div>' +
            '<div class="room-btn">' +
            '<div class="btn btn-secondary add-floor-room-row">Add New Row</div>' +
            '</div>' +
            '</div>' +
            '</div>');
        $(".floor-list").append(elm);
        //$(this).attr("data-next", parseInt(next) + 1);
    })

    // highlight room block on delete hover
    $(document).on("mouseenter", ".delete-room-main", function () {
        $(this).parent().addClass("warning");
    })
    $(document).on("mouseleave", ".delete-room-main", function () {
        $(this).parent().removeClass("warning");
    })

    // delete room block
    $(document).on("click", ".delete-room-main", function () {
        $(this).parent().remove();
    })

    /*************************************************/
    /*************************************************/
    /*************************************************/

    // create new room row
    $(document).on("click", ".add-floor-lmsk-row", function () {

        var main = $(this).parent().parent().parent().parent().parent().attr("data-current");
        var current = $(this).parent().parent().attr("data-identity");

        if ($(this).parent().parent().find(".lmsk-list").find(".lmsk-item:last-child").length) {
            var next = $(this).parent().parent().find(".lmsk-list").find(".lmsk-item:last-child").attr("data-identity");
        } else {
            var next = -1;
        }


        var next = parseInt(next) + 1;

        var elm = ('<div class="lmsk-item" data-identity="' + next + '">' +
            '<div class="delete-lmsk-row">' +
            '<i class="fa fa-times"></i>' +
            '</div>' +

            '<div class="form-group row">' +
            '<div class="col-md-6 col-12">' +
            '<label>Zimmername</label>' +
            '<input type="text" class="form-control" name="floor[' + main + '][table][' + current + '][rooms][' + next + '][room]" placeholder="Zimmername, z.B. Keller, Schlafzimmer, Abstellraum">' +
            '</div>' +
            '<div class="col-md-6 col-12">' +
            '<label>Anzahl der Räume</label>' +
            '<input type="text" class="form-control" name="floor[' + main + '][table][' + current + '][rooms][' + next + '][count]" placeholder="Anzahl der Räume, nummerisch ohne Einheit">' +
            '</div>' +
            '</div>' +

            '</div>');
        $(this).parent().parent().find(".lmsk-list").append(elm);
    })

    // delete room row
    $(document).on("click", ".delete-lmsk-row", function () {
        $(this).parent().remove();
    })

    // create new section
    $(document).on("click", ".add-floor-rmmsk-row", function () {

        var main = $(this).parent().parent().parent().attr("data-current");

        if ($(this).parent().parent().find(".rmmsk-list").find(".rmmsk-item").length) {
            var curr = $(this).parent().parent().find(".rmmsk-list").find(".rmmsk-item:last-child").attr("data-identity");
        } else {
            var curr = -1;
        }

        var newc = parseInt(curr) + 1;

        var elm = ('<div class="rmmsk-item" data-identity="' + newc + '">' +
            '<div class="delete-rmmsk-row">' +
            '<i class="fa fa-times"></i>' +
            '</div>' +
            '<div class="form-group">' +
            '<label>Aufteilung im Stockwerk</label>' +
            '<input type="text" class="form-control" name="floor[' + main + '][table][' + newc + '][section]" placeholder="(links, rechts, mitte)">' +
            '</div>' +
            '<div class="form-group row">' +
            '<div class="col-md-4 col-12">' +
            '<label>Vermietungsstatus</label>' +
            '<input type="text" class="form-control" name="floor[' + main + '][table][' + newc + '][status]" placeholder="Vermietungsstatus (z.B. vermietet, persönlich bewohnt, unbekannt, Leerstand)">' +
            '</div>' +
            '<div class="col-md-4 col-12">' +
            '<label>Monatliche Mieteinnahmen</label>' +
            '<input type="text" class="form-control" name="floor[' + main + '][table][' + newc + '][rent]" placeholder="Mieteinnahmen pro Monat">' +
            '</div>' +
            '<div class="col-md-4 col-12">' +
            '<label>Wohnfläche</label>' +
            '<input type="text" class="form-control" name="floor[' + main + '][table][' + newc + '][space]" placeholder="Wohnfläche">' +
            '</div>' +
            '</div>' +
            '<div class="lmsk-list">' +
            '<div class="lmsk-item" data-identity="0">' +
            '<div class="delete-lmsk-row">' +
            '<i class="fa fa-times"></i>' +
            '</div>' +

            '<div class="form-group row">' +
            '<div class="col-md-6 col-12">' +
            '<label>Zimmername</label>' +
            '<input type="text" class="form-control" name="floor[' + main + '][table][' + newc + '][rooms][0][room]" placeholder="Zimmername, z.B. Keller, Schlafzimmer, Abstellraum">' +
            '</div>' +
            '<div class="col-md-6 col-12">' +
            '<label>Anzahl der Räume</label>' +
            '<input type="text" class="form-control" name="floor[' + main + '][table][' + newc + '][rooms][0][count]" placeholder="Anzahl der Räume, nummerisch ohne Einheit">' +
            '</div>' +
            '</div>' +

            '</div>' +
            '</div>' +
            '<div class="rmmsk-btn">' +
            '<div class="btn btn-secondary btn-sm add-floor-lmsk-row">Weiteren Raum hinzufügen</div>' +
            '</div>' +
            '</div>');

        $(this).parent().parent().find(".rmmsk-list").append(elm);
    })

    $(document).on("click", ".delete-rmmsk-row", function () {
        $(this).parent().remove();
    })

    // whole block
    $(document).on("click", ".floor-whole-btn", function () {

        if ($(".floor-list").find(".rmmsk-pack").length) {
            var curr = $(".floor-list").find(".rmmsk-pack:last-child").attr("data-current");
        } else {
            var curr = -1;
        }

        var newc = parseInt(curr) + 1;

        var elm = ('<div class="rmmsk-pack" data-current="' + newc + '">' +
            '<div class="delete-rmmsk-main">' +
            '<i class="fa fa-trash"></i>' +
            '</div>' +

            '<div class="form-group">' +
            '<label>Stockwerk</label>' +
            '<input type="text" class="form-control" name="floor[' + newc + '][title]" placeholder="Stockwerk">' +
            '</div>' +

            '<div class="rmmsk-table">' +
            '<div class="rmmsk-list">' +

            '<div class="rmmsk-item" data-identity="0">' +
            '<div class="delete-rmmsk-row">' +
            '<i class="fa fa-times"></i>' +
            '</div>' +
            '<div class="form-group">' +
            '<label>Aufteilung im Stockwerk</label>' +
            '<input type="text" class="form-control" name="floor[' + newc + '][table][0][section]" placeholder="(links, rechts, mitte)">' +
            '</div>' +
            '<div class="form-group row">' +
            '<div class="col-md-4 col-12">' +
            '<label>Vermietungsstatus</label>' +
            '<input type="text" class="form-control" name="floor[' + newc + '][table][0][status]" placeholder="Vermietungsstatus (z.B. vermietet, persönlich bewohnt, unbekannt, Leerstand)">' +
            '</div>' +
            '<div class="col-md-4 col-12">' +
            '<label>Monatliche Mieteinnahmen</label>' +
            '<input type="text" class="form-control" name="floor[' + newc + '][table][0][rent]" placeholder="Mieteinnahmen pro Monat">' +
            '</div>' +
            '<div class="col-md-4 col-12">' +
            '<label>Wohnfläche</label>' +
            '<input type="text" class="form-control" name="floor[' + newc + '][table][0][space]" placeholder="Wohnfläche">' +
            '</div>' +
            '</div>' +
            '<div class="lmsk-list">' +
            '<div class="lmsk-item" data-identity="0">' +
            '<div class="delete-lmsk-row">' +
            '<i class="fa fa-times"></i>' +
            '</div>' +

            '<div class="form-group row">' +
            '<div class="col-md-6 col-12">' +
            '<label>Zimmername</label>' +
            '<input type="text" class="form-control" name="floor[' + newc + '][table][0][rooms][0][room]" placeholder="Zimmername, z.B. Keller, Schlafzimmer, Abstellraum">' +
            '</div>' +
            '<div class="col-md-6 col-12">' +
            '<label>Anzahl der Räume</label>' +
            '<input type="text" class="form-control" name="floor[' + newc + '][table][0][rooms][0][count]" placeholder="Anzahl der Räume, nummerisch ohne Einheit">' +
            '</div>' +
            '</div>' +

            '</div>' +
            '</div>' +
            '<div class="rmmsk-btn">' +
            '<div class="btn btn-secondary btn-sm add-floor-lmsk-row">Weiteren Raum hinzufügen</div>' +
            '</div>' +
            '</div>' +

            '</div>' +
            '<div class="rmmsk-btn">' +
            '<div class="btn btn-dark add-floor-rmmsk-row">Weitere Wohneinheit hinzufügen</div>' +
            '</div>' +
            '</div>' +
            '</div>');

        $(".floor-list").append(elm);
    })

    // highlight room block on delete hover
    $(document).on("mouseenter", ".delete-rmmsk-main", function () {
        $(this).parent().addClass("warning");
    })
    $(document).on("mouseleave", ".delete-rmmsk-main", function () {
        $(this).parent().removeClass("warning");
    })

    // delete room block
    $(document).on("click", ".delete-rmmsk-main", function () {
        $(this).parent().remove();
    })

    /*************************************************/
    /*************************************************/
    /*************************************************/

    // create new facility row
    $(document).on("click", ".facility-btn", function () {
        if ($(".facility_list").find(".facility-item").length) {
            var current = $(".facility_list").find(".facility-item:last-child").attr("data-next");
        } else {
            var current = 0;
        }
        var next = parseInt(current) + 1;
        var elm = ('<div class="facility-item" data-next="' + next + '">' +
            '<div class="facility-delete">' +
            '<i class="fa fa-times"></i>' +
            '</div>' +
            '<div class="form-group row">' +

            '<div class="col-md-2 col-12">' +
            '<label>Miteigentumsanteil</label>' +
            '<input type="text" class="form-control" name="facilities[' + current + '][share]" placeholder="Miteigentumsanteil">' +
            '</div>' +
            '<div class="col-md-5 col-12">' +
            '<label>Beschreibung</label>' +
            '<input type="text" class="form-control" name="facilities[' + current + '][description]" placeholder="Beschreibung">' +
            '</div>' +
            '<div class="col-md-2 col-12">' +
            '<label>Fläche</label>' +
            '<input type="text" class="form-control" name="facilities[' + current + '][area]" placeholder="Fläche">' +
            '</div>' +
            '<div class="col-md-3 col-12">' +
            '<label>Geschätzter Wert</label>' +
            '<input type="text" class="form-control" name="facilities[' + current + '][estimated]" placeholder="Geschätzter Wert">' +
            '</div>' +

            '</div>' +

            '<div class="facility-check">' +
            '<div class="form-check">' +
            '<input class="form-check-input" type="checkbox" value="1" name="facilities[' + current + '][check]">' +
            '<label class="form-check-label">Miteigentumsanteil nicht vorhanden</label>' +
            '</div>' +
            '</div>' +

            '</div>');
        $(this).parent().parent().parent().find(".facility_list").append(elm);
    })

    // delete facility row
    $(document).on("click", ".facility-delete", function () {
        $(this).parent().remove();
    })

    // create new room row
    $(document).on("click", ".add-room-row", function () {
        if ($(".room-list").find(".room-item").length) {
            var current = $(".room-list").find(".room-item:last-child").attr("data-next");
        } else {
            var current = 0;
        }

        var next = parseInt(current) + 1;
        var elm = ('<div class="room-item" data-next="' + next + '">' +
            '<div class="delete-room">' +
            '<i class="fa fa-times"></i>' +
            '</div>' +
            '<div class="form-group row">' +
            '<div class="col-md-6 col-12">' +
            '<label>Room Type</label>' +
            '<input type="text" class="form-control" name="room[' + next + '][type]" placeholder="Room Type" value="" required>' +
            '</div>' +
            '<div class="col-md-6 col-12">' +
            '<label>Anzahl der Räume</label>' +
            '<input type="text" class="form-control" name="room[' + next + '][count]" placeholder="Anzahl der Räume, nummerisch ohne Einheit" value="" required>' +
            '</div>' +
            '</div>' +
            '</div>');
        $(this).parent().parent().find(".room-list").append(elm);
    })

    // delete room row
    $(document).on("click", ".delete-room", function () {
        $(this).parent().remove();
    })

    /*************************************************/
    /*************************************************/
    /*************************************************/

    // Custom Input
    $(document).on("click", ".custom_input", function () {
        var elm = $(this).parent().parent();
        var name = $(this).attr("data-name");
        var holder = $(this).attr("data-holder");

        var elval = elm.find("select").data("select");
        elm.find("select").remove();
        if (!elm.find("input").length) {
            elm.append('<input type="text" class="form-control" name="' + name + '" placeholder="' + holder + '" value="' + elval + '">');
            $('input[name=' + name + ']').focus();
        }
    })

    /*************************************************/
    /*************************************************/
    /*************************************************/

    // Listing Assign
    $("#listing_assign").on("submit", function (event) {
        event.preventDefault();
        var admin = $("select[name=listing_role]").val();
        var listings = $("input[name='listing_checked[]']:checked").map(function () { return $(this).val(); }).get();

        if (listings === undefined || listings.length == 0) {
            alert("Please select listings to assign");
            $("select[name=listing_role]").val("");
            $("#listing_role_btn").prop("disabled", true);
        } else if (admin === undefined || admin == '') {
            alert("Please select a user to assign");
        } else {
            $.ajax({
                type: 'POST',
                url: $(this).attr("action"),
                data: {
                    user: admin,
                    listings: listings
                },
                beforeSend: function () {
                    $("#listing_assign_overlay").fadeIn();
                },
                success: function (data) {
                    console.log(data);
                    $("#listing_assign_overlay").delay(500).fadeOut();
                    if (data == 'user_missing') {
                        alert('Please select a user to assign!');
                    } else if (data == 'listings_missing') {
                        alert('Please select listings to assign!');
                    } else if (data == 'error') {
                        alert('Etwas lief falsch!');
                        window.location.href = siteURL + '/admin/' + $("#current_page").attr("data-url");
                    } else if (data == 'success') {
                        alert('Listings assigned successfully!');
                        window.location.href = siteURL + '/admin/' + $("#current_page").attr("data-url");
                    }
                }
            });
        }
    })

    // listing pdf search
    $("#listing_pdf_search").on("submit", function (event) {
        event.preventDefault();
        var pdf = $("select[name=listing_pdf]").val();
        dtables.search(pdf);
        //$(".dataTable-input").val(pdf);
        //$(".dataTable-input").keyup();
        //$(".dataTable-input").trigger('keyup');
    })

    $("select[name=listing_role]").on("change", function () {
        var selected = $(this).val();
        if (selected === undefined || selected == '') {
            $("#listing_role_btn").prop("disabled", true);
        } else {
            $("#listing_role_btn").prop("disabled", false);
        }
    })

    $("#inspection_status").on("change", function () {
        if ($("#inspection_status").is(':checked')) {
            $("input[name=inspection_date]").attr('readonly', true);
        } else {
            $("input[name=inspection_date]").removeAttr("readonly");
        }
    })

    // create new course learn block
    $(document).on("click", ".learn-btn", function () {
        if ($(".you_learn").find(".you_learn__item").length) {
            var curr = $(".you_learn").find(".you_learn__item:last-child").attr("data-current");
        } else {
            var curr = 0;
        }

        var next = parseInt(curr) + 1;
        var current = parseInt(next) + 1;

        var elm = ('<div class="you_learn__item" data-current="' + next + '">' +
            '<div class="you_learn__item-title">' +
            '<h4>#<span>' + current + '</span> - You Will Learn</h4>' +
            '</div>' +
            '<hr>' +
            '<div class="you_learn__item-inner">' +
            '<div class="form-group">' +
            '<label>Title</label>' +
            '<input type="text" class="form-control" name="learn[' + next + '][title]" value="">' +
            '</div>' +
            '<div class="form-group">' +
            '<label>Content</label>' +
            '<textarea class="form-control" name="learn[' + next + '][content]"></textarea>' +
            '</div>' +
            '</div>' +
            '<div class="you_learn__item-btn">' +
            '<div class="btn btn-danger">Delete</div>' +
            '</div>' +
            '</div>');
        $(".you_learn").append(elm);
    })

    // delete room row
    $(document).on("click", ".you_learn__item-btn .btn", function () {
        $(this).parent().parent().remove();
    })

    // create new course video block
    $(document).on("click", ".video-btn", function () {
        if ($(".course_videos").find(".course_videos__item").length) {
            var curr = $(".course_videos").find(".course_videos__item:last-child").attr("data-current");
        } else {
            var curr = 0;
        }

        var next = parseInt(curr) + 1;
        var current = parseInt(next) + 1;

        var elm = ('<div class="course_videos__item" data-current="' + next + '">' +
            '<div class="course_videos__item-title">' +
            '<h4>#<span>' + current + '</span> - Video Segment</h4>' +
            '</div>' +
            '<hr>' +
            '<div class="row">' +
            '<div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-12">' +
            '<div class="course_videos__item-inner">' +
            '<div class="form-group">' +
            '<label>Title</label>' +
            '<input type="text" class="form-control" name="course[' + next + '][title]" value="">' +
            '</div>' +
            '<div class="form-group">' +
            '<label>Content</label>' +
            '<textarea class="form-control" name="course[' + next + '][content]"></textarea>' +
            '</div>' +
            '<div class="course_videos__item-btn">' +
            '<div class="btn btn-danger">Delete</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-12">' +
            '<div class="shd-gallery-area">' +
            '<div class="shd-gallery-info">' +
            '<i class="far fa-photo-video"></i>' +
            '<p>Upload video for this segment</p>' +
            '</div>' +
            '<div class="shd-gallery-btn">' +
            '<span>Select Video</span>' +
            '</div>' +
            '<div id="video_path" data-path="videos"></div>' +
            '<input type="file" name="course_video" class="course_video" accept="video/*" />' +
            '</div>' +
            '<div class="video_preview">' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<input type="hidden" class="form-control" name="course[' + next + '][video]" value="">' +
            '<input type="hidden" class="form-control" name="course[' + next + '][length]" value="">' +
            '<input type="hidden" class="form-control" name="course[' + next + '][watch]" value="">' +
            '</div>');
        $(".course_videos").append(elm);
    })

    // delete room row
    $(document).on("click", ".course_videos__item-btn .btn", function () {
        $(this).parent().parent().parent().parent().parent().remove();
    })

    /***************/

    // video upload
    $(document).on("change", ".course_video", function () {
        var fd = new FormData();
        var files = $(this)[0].files;
        var img_path = $("#video_path").attr("data-path");

        if (img_path == 'videos') {
            var main = $(this).parent().parent().parent().parent();
            var current = main.attr("data-current");
        }

        if (files.length > 0) {

            fd.append('file', files[0]);
            fd.append('path', img_path);
            $.ajax({
                url: siteURL + "/admin/process/video.php",
                type: "POST",
                data: fd,
                contentType: false,
                processData: false,
                success: function (data) {
                    console.log(data);
                    $("#course_video").val('');
                    if (data == "0" || data == 0) {
                        alert("Video upload failed!");
                    } else {
                        if (img_path == 'videos') {
                            var root = $(document).find(main);
                            if (root.find(".video_preview video").length) {
                                root.find(".video_preview video source").prop("src", siteURL + data);
                            } else {
                                root.find(".video_preview").html(
                                    '<video width="100%" controls>' +
                                    '<source src="' + siteURL + data + '" type="video/mp4">' +
                                    'Your browser does not support HTML video.' +
                                    '</video>');
                            }

                            //root.find(".video_preview video source").prop("src", siteURL + data);
                            root.find(".video_preview video")[0].load();

                            root.find(".video_preview video").on("durationchange", function () {
                                var duration = root.find(".video_preview video")[0].duration;
                                duration = Math.round(duration);
                                root.find("input[name='course[" + current + "][length]']").val(duration);
                            });

                            root.find("input[name='course[" + current + "][video]']").val(data);
                        }

                        if (img_path == 'intro' || img_path == 'seminar' || img_path == 'consulting') {
                            if ($(".video_preview video").length) {
                                $(".video_preview video source").prop("src", siteURL + data);
                            } else {
                                $(".video_preview").html(
                                    '<video width="100%" controls>' +
                                    '<source src="' + siteURL + data + '" type="video/mp4">' +
                                    'Your browser does not support HTML video.' +
                                    '</video>');
                            }
                            //$(".video_preview video source").prop("src", siteURL + data);
                            $(".video_preview video")[0].load();
                            $("input[name='intro']").val(data);
                        }

                    }
                },
            });
        }
        $(this).val('');
    });

    /***************/

    $(".genarate_coupon").on("click", function () {
        var length = '12';
        var result = '';
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        var charactersLength = characters.length;
        for (var i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        $("input[name=code]").val(result);
    })

});