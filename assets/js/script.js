$(document).ready(function () {

    const siteURL = $("#siteurl").data("url");

    if ($("#stripe_key").length > 0) {
        var key = $("#stripe_key").attr("data-key");
        var stripe = Stripe(key);

        var elements = stripe.elements();

        var cardElement = elements.create('cardNumber');
        var exp = elements.create('cardExpiry');
        var cvc = elements.create('cardCvc');
    }


    function priceNormal(price) {
        price = price.replace(/\./g, '');
        price = price.replace(/,/g, '.');
        return price;
    }

    function priceGerman(float) {
        return float.toLocaleString('de-DE', { minimumFractionDigits: 2 });
    }

    function validateDate(dateString) {
        // First check for the pattern
        if (!/^\d{1,2}\/\d{1,2}\/\d{4}$/.test(dateString))
            return false;

        // Parse the date parts to integers
        var parts = dateString.split("/");
        var day = parseInt(parts[1], 10);
        var month = parseInt(parts[0], 10);
        var year = parseInt(parts[2], 10);

        // Check the ranges of month and year
        if (year < 1000 || year > 3000 || month == 0 || month > 12)
            return false;

        var monthLength = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

        // Adjust for leap years
        if (year % 400 == 0 || (year % 100 != 0 && year % 4 == 0))
            monthLength[1] = 29;

        // Check the range of the day
        return day > 0 && day <= monthLength[month - 1];
    }

    function validateAge(day, month, year) {
        var cutOffDate = new Date(parseInt(year) + 18, month, day);
        var currdate = new Date();
        if (cutOffDate > currdate) {
            return false;
        }
        return true;
    }

    function validateEmail(email) {
        const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

    function checkPassword(pswd) {
        if (
            pswd.length > 4 &&
            pswd.match(/[a-z]/) &&
            pswd.match(/\d/)
        ) {
            return true;
        }
        return false;
    }

    function validateURL(str) {
        /* var pattern = new RegExp('^(https?:\\/\\/)?' + // protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
            '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
            '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
            '(\\#[-a-z\\d_]*)?$', 'i'); // fragment locator */
        var pattern = new RegExp('^http[s]?:\/\/(www\.)?(.*)?\/?(.)*');
        return !!pattern.test(str);
    }

    function redTimeout(path) {
        setTimeout(function () {
            window.location.href = siteURL + path;
        }, 1500)
    }

    // Select the database value
    $("select").each(function () {
        if ($(this).attr("data-select") && $(this).attr("data-select") !== '') {
            var selected = $(this).data("select");
            if ($(this).hasClass("select2")) {
                $(this).val(selected);
            } else {
                if ($(this).find(`option[value='${selected}']`)) {
                    $(this).find(`option[value='${selected}']`).prop('selected', true);
                }
            }

        }
    })

    // main menu
    var menu = $(".header_bot__nav");
    var menu_icon = $(".header_bot__toggle-icon");
    menu_icon.on("click", function () {
        menu.slideToggle();
        menu_icon.find("i").toggleClass("fa-times");
    })

    // user menu
    var user_menu = $(".account_menu__list");
    var user_menu_icon = $(".account_menu__mobile-open");
    user_menu_icon.on("click", function () {
        user_menu.slideToggle();
        user_menu_icon.find("i").toggleClass("fa-times");
    })

    $(".user_favorite__menu").hover(
        function () {
            $("#user_favorite__menu").fadeIn();
        }, function () {
            $("#user_favorite__menu").fadeOut();
        }
    );

    $(".account_details__avatar-image").on("click", function () {
        $('.account_details__avatar input[type="file"]').click();
    })

    $('.account_details__avatar input[type="file"]').on("change", function (event) {
        var output = document.getElementById('account_details__avatar-image--link');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function () {
            URL.revokeObjectURL(output.src);
        }
    })

    $("[data-sectitle]").each(function () {
        var element = $(this).attr("data-secelem");
        if ($(this).find("." + element).length == 0) {
            $(this).hide();
        }
    })

    $(".listing_details__item").hover(
        function () {
            var target = $(this).attr("data-find");
            if ($(this).find(".custom_tooltip[data-tool=" + target + "]").length) {
                $(this).find(".custom_tooltip[data-tool=" + target + "]").fadeIn();
            }
        }, function () {
            $(".custom_tooltip").fadeOut();
        }
    )

    $(".about_report__question").hover(
        function () {
            if ($(".about_report__question-tooltip").length) {
                $(".about_report__question-tooltip").fadeIn();
            }
        }, function () {
            $(".about_report__question-tooltip").fadeOut();
        }
    )

    $("input[name=object_price]").on("keyup", function () {

        var total = 0;
        var total_add = 0;

        var total_extra = 0;
        var total_add_extra = 0;

        var objPrice = priceNormal($(this).val());

        $(".html_object_price").html($(this).val());

        var taxed = (objPrice * $("input[name=object_tax]").val()) / 100;
        $("input[name=land_transfer_tax]").val(priceGerman(taxed));
        $(".html_land_transfer_tax").html(priceGerman(taxed));

        var court = (objPrice * 0.5) / 100;
        $("input[name=court_costs]").val(priceGerman(court));
        $("#court_costs").html(priceGerman(court));

        var maklerprovision = (objPrice * 2.74) / 100;
        $("#maklerprovision").html(priceGerman(maklerprovision));

        var court_extra = (objPrice * 1.5) / 100;
        $("#court_costs_extra").html(priceGerman(court_extra));

        var land = (objPrice * 0.5) / 100;
        $("input[name=land_register]").val(priceGerman(land));
        $(".html_land_register").html(priceGerman(land));

        total = parseInt(objPrice) + parseInt(taxed) + parseInt(court) + parseInt(land);
        $("input[name=aquistion_total]").val(priceGerman(total));

        total_add = parseInt(taxed) + parseInt(court) + parseInt(land);
        $("#aquistion_total").html(priceGerman(total_add));

        total_extra = parseInt(objPrice) + parseInt(maklerprovision) + parseInt(taxed) + parseInt(court_extra) + parseInt(land);
        $("#aquistion_total_extra").html(priceGerman(total_extra));

        total_add_extra = parseInt(maklerprovision) + parseInt(taxed) + parseInt(court_extra) + parseInt(land);
        $("#aquistion_total_extra").html(priceGerman(total_add_extra));
    })

    /* function update_width(elm, width) {
        //console.log(width);
        $(elm).css({
            'flex': '0 0 ' + width + '%',
            'max-width': width + '%',
        })
    } */

    /* function show_pricing_charts() {

        var tots = priceNormal($("input[name=aquistion_total]").val());

        var land_transfer_tax = priceNormal($("input[name=land_transfer_tax]").val());
        var transfer_tax = (land_transfer_tax / (tots / 100)).toFixed(2);
        //update_width(".pricing-ruler--item ul li", transfer_tax);
        $(".pricing-ruler--info_item").find(".pricing-ruler--info_item__value").text(transfer_tax + "%");

        var object_price = priceNormal($("input[name=object_price]").val());
        var price_rating = (object_price / (tots / 100)).toFixed(2);
        //update_width(".pricing-ruler--item ul li:first-child", price_rating);
        $(".pricing-ruler--info_item:first-child").find(".pricing-ruler--info_item__value").text(price_rating + "%");

        var object_court_costs = priceNormal($("input[name=court_costs]").val());
        var court_costs = (object_court_costs / (tots / 100)).toFixed(2);
        //update_width(".pricing-ruler--item ul li:nth-child(3)", court_costs);
        $(".pricing-ruler--info_item:nth-child(3)").find(".pricing-ruler--info_item__value").text(court_costs + "%");

        var land_register = priceNormal($("input[name=land_register]").val());
        var register_tax = (land_register / (tots / 100)).toFixed(2);
        //update_width(".pricing-ruler--item ul li:last-child", register_tax);
        $(".pricing-ruler--info_item:last-child").find(".pricing-ruler--info_item__value").text(register_tax + "%");

        var what_left = 100 - transfer_tax - court_costs - register_tax;
        //update_width(".pricing-ruler--item ul li:first-child", what_left);
        $(".pricing-ruler--info_item:first-child").find(".pricing-ruler--info_item__value").text(what_left + "%");

    }

    if ($(".aquision_calc").length) {
        show_pricing_charts();
    }

    $(".aquision_calc").find("input").on("keyup", function () {
        show_pricing_charts();
    }) */

    /* Request Report */

    $("#request_report").on("submit", function (event) {
        event.preventDefault();

        var form_over = $(this).find(".overlay");
        var report_status = $("#report_status");

        var listing_id = $("#listing_id").val();

        if (listing_id == "") {
            report_status.html('<div class="text-danger">Ewas lief falsch! Bitte erneut probieren.</div>');
        } else {
            $.ajax({
                type: "POST",
                url: siteURL + "/ajax/request_report.php",
                data: {
                    listing_id: listing_id,
                },
                beforeSend: function () {
                    report_status.html("");
                    form_over.fadeIn();
                },
                success: function (data) {
                    console.log(data);
                    form_over.fadeOut();
                    if (data == "success") {
                        $(".about_report__info-body--request").find("form").remove();
                        report_status.html('<div class="text-success">Wir haben deine Anfrage erhalten. Wir werden uns zeitnah bei dir melden.</div>');
                    } else if (data == "logged") {
                        report_status.html('<div class="text-danger">Melde dich an, um das Feature zu nutzen.</div>');
                    } else {
                        report_status.html('<div class="text-danger">Ewas lief falsch! Bitte erneut probieren.</div>');
                    }
                },
                error: function (data) {
                    console.log(data);
                    form_over.fadeOut();
                    report_status.html('<div class="alert alert-danger">Ewas lief falsch! Bitte erneut probieren.</div>');
                },
            });
        }
    });

    /* Contact */

    $("#contact_form").on("submit", function (event) {
        event.preventDefault();

        var error = 0;

        var form_over = $(this).find(".overlay");
        var alerts = $(".contact_alerts");

        var name = $(this).find("input[name=name]").val();
        if (name == '' || name.length == 0) {
            error += 1;
            alerts.html('<div class="alert alert-danger">Vorname eingeben</div>');
        }

        var email = $(this).find("input[name=email]").val();
        if (email == '' || email.length == 0) {
            error += 1;
            alerts.html('<div class="alert alert-danger">Bitte E-Mail eingeben</div>');
        } else if (!validateEmail(email)) {
            error += 1;
            alerts.html('<div class="alert alert-danger">Bitte gebe eine gültige E-Mail Adresse ein</div>');
        }

        var reason = $(this).find("select[name=reason]").val();
        if (reason == '' || reason.length == 0) {
            error += 1;
            alerts.html('<div class="alert alert-danger">Bitte wählen einen Grund aus</div>');
        }

        var message = $(this).find("textarea[name=message]").val();
        if (message == '' || message.length == 0) {
            error += 1;
            alerts.html('<div class="alert alert-danger">Dieses Feld bitte ausfüllen</div>');
        }

        if (error > 0) {
            alerts.html('<div class="alert alert-danger">Ewas lief falsch! Bitte erneut probieren.</div>');
        } else {
            $.ajax({
                type: "POST",
                url: siteURL + "/ajax/contact_form.php",
                data: $(this).serialize(),
                beforeSend: function () {
                    alerts.html("");
                    form_over.fadeIn();
                },
                success: function (data) {
                    console.log(data);
                    form_over.fadeOut();
                    if (data == "success") {
                        $(".contact_area").find("input, select, textarea").val("");
                        alerts.html('<div class="alert alert-success">Deine Anfrage ist eingegangen! Unsere Mitarbeiter werden sich in Kürze bei dir melden!</div>');
                    } else if (data == "empty") {
                        alerts.html('<div class="alert alert-success">Bitte füll alle Felder aus, um die Anfrage abzuschicken</div>');
                    } else {
                        alerts.html('<div class="alert alert-danger">Ewas lief falsch! Bitte erneut probieren.</div>');
                    }
                },
                error: function (data) {
                    console.log(data);
                    form_over.fadeOut();
                    alerts.html('<div class="alert alert-danger">Ewas lief falsch! Bitte erneut probieren.</div>');
                },
            });
        }
    });

    /* Analysis Tabs */
    $(document).on("click", ".analyse-inner", function () {
        var linking = $(this).attr("data-linking");
        $(".analyse-inner").removeClass("active");
        $(this).addClass("active");
        $(".analyse_step").slideUp();
        if ($(".analy-tab").find(".analyse_step[data-step=" + linking + "]").length) {
            $(".analy-tab").find(".analyse_step[data-step=" + linking + "]").slideDown();
        }
    })

    $(document).on("click", ".analyse_btn .btn", function () {
        var next = $(this).attr("data-goto");
        $(".analyse-inner").removeClass("active");
        $(".analyse-inner[data-linking=" + next + "]").addClass("active");
        $(".analyse_step").slideUp();
        if ($(".analy-tab").find(".analyse_step[data-step=" + next + "]").length) {
            $(".analy-tab").find(".analyse_step[data-step=" + next + "]").slideDown();
        }
    })

    // like to faq

    $(document).on("click", ".listing_fav button", function (e) {

        var favBtn = $(this).parent();
        var listing_id = favBtn.data('listing');
        var method = favBtn.data('method');

        if (listing_id == '' || method == '') {
            alert("Ewas lief falsch! Bitte erneut probieren.");
        } else {
            $.ajax({
                type: "POST",
                url: siteURL + "/ajax/fav.php",
                data: {
                    listing_id: listing_id,
                    method: method,
                },
                beforeSend: function () {
                    $(this).prop("disabled", true);
                },
                success: function (data) {
                    //console.log(data);
                    $(this).prop("disabled", false);
                    if (data !== "0") {
                        if (method == 'add') {
                            favBtn.addClass("active");
                            favBtn.data("method", "remove");
                        } else if (method == 'remove') {
                            favBtn.removeClass("active");
                            favBtn.data("method", "add");
                        }
                    } else {
                        alert("Ewas lief falsch! Bitte erneut probieren.");
                    }
                },
                error: function (data) {
                    alert("Ewas lief falsch! Bitte erneut probieren.");
                    console.log(data);
                },
            });
        }
    })

    // user register
    $(document).on("submit", "#user_register", function (e) {
        //e.preventDefault();
        var error = 0;


        $(".login_alerts").html('<div class="login_alerts__list"></div>');
        var alerts = $(".login_alerts .login_alerts__list");

        var name = $(this).find("input[name=name]").val();
        if (name == '' || name.length == 0) {
            error += 1;
            alerts.append('<p>Vorname eingeben</p>');
        } else if (name.length < 2) {
            error += 1;
            alerts.append('<p>Der Name sollte mehr als 2 Charaktere haben</p>');
        } else if (name.length > 100) {
            error += 1;
            alerts.append('<p>Der Name sollte weniger als 100 Charaktere haben</p>');
        }

        var surname = $(this).find("input[name=surname]").val();
        if (surname == '' || surname.length == 0) {
            error += 1;
            alerts.append('<p>Nachname eingeben</p>');
        } else if (surname.length < 2) {
            error += 1;
            alerts.append('<p>Der Nachname sollte mehr als 2 Charaktere haben</p>');
        } else if (surname.length > 100) {
            error += 1;
            alerts.append('<p>Der Nachname sollte weniger als 100 Charaktere haben</p>');
        } else if (name == surname) {
            error += 1;
            alerts.append('<p>Nachname und Vorname dürfen nicht identisch sein </p>');
        }

        var day = $(this).find("select[name=day]").val();
        var month = $(this).find("select[name=month]").val();
        var year = $(this).find("select[name=year]").val();
        if (
            (day == '' || day.length == 0) &&
            (month == '' || month.length == 0) &&
            (year == '' || year.length) == 0
        ) {
            error += 1;
            alerts.append('<p>Geburtstag wählen</p>');
        } else {
            var bday = month + '/' + day + '/' + year;
            if (!validateDate(bday)) {
                error += 1;
                alerts.append('<p>Bitte wähle ein gültiges Datum für das Geburtsjahr</p>');
            } else if (!validateAge(day, month, year)) {
                error += 1;
                alerts.append('<p>Du musst 18 Jahre oder älter, um dich anzumdelden</p>');
            }
        }

        var email = $(this).find("input[name=email]").val();
        if (email == '' || email.length == 0) {
            error += 1;
            alerts.append('<p>Bitte E-Mail eingeben</p>');
        } else if (!validateEmail(email)) {
            error += 1;
            alerts.append('<p>Bitte gebe eine gültige E-Mail Adresse ein</p>');
        }

        var pwd = $(this).find("input[name=pwd]").val();
        var cpwd = $(this).find("input[name=cpwd]").val();
        if (pwd == '' || pwd.length == 0) {
            error += 1;
            alerts.append('<p>Bitte Passwort eingeben</p>');
        } else {
            if (!checkPassword(pwd)) {
                error += 1;
                alerts.append('<p>Dein Passwort ist nicht sicher</p>');
            } else {
                if (cpwd == '' || cpwd.length == 0) {
                    error += 1;
                    alerts.append('<p>Bitte Passwort Bestätigen</p>');
                } else {
                    if (pwd !== cpwd) {
                        error += 1;
                        alerts.append('<p>Die Passwörter sind nicht identisch</p>');
                    }
                }
            }
        }

        if (error == 0) {
            $(".login_alerts").html('');
            gtag_report_conversion();
            //$(this).submit();
        } else {
            e.preventDefault();
        }
    })

    // choose package
    $(".payment_plan__box").on("click", function () {
        if ($(this).find("input:checked").length) {
            $(this).find("input").prop("checked", false).change();
            $(this).removeClass("active");
        } else {
            $(this).find("input").prop("checked", true).change();

            $(".payment_plan__box").removeClass("active");
            $(this).addClass("active");
        }
    })

    // choose gateway
    $(".payment_icon").on("click", function () {
        if ($(this).find("input:checked").length) {
            $(this).find("input").prop("checked", false).change();
            $(this).removeClass("active");
        } else {
            $(this).find("input").prop("checked", true).change();

            $(".payment_icon").removeClass("active");
            $(this).addClass("active");
        }
    })

    $("input[name=plan], input[name=method]").on("change", function () {
        if ($("input[name=plan]").is(":checked") && $("input[name=method]").is(":checked")) {
            $(".buy_now__button button").prop("disabled", false);
        } else {
            $(".buy_now__button button").prop("disabled", true);
        }
    })

    // Coupon apply
    var couponApply = $(".coupon_apply");
    couponApply.find(".btn").on("click", function () {

        var checkOver = $(".checkout_form .overlay");
        var checkBtn = $(".checkout_details__checkout button");
        var alerts = $(".coupon_apply__alert");

        var amount = $("#plan_amount").val();
        var package = $("#package").val();
        var plan_id = $("#plan_id").val();
        var coupon = $(".coupon_apply input").val();

        if (coupon == '' || coupon.length == 0) {
            alerts.html('<div class="alert alert-danger">Please Enter a Coupon to Activate</div>');
        } else {
            $.ajax({
                type: "POST",
                url: siteURL + "/ajax/coupon_apply.php",
                data: {
                    'coupon': coupon,
                    'package': package,
                    'plan_id': plan_id
                },
                beforeSend: function () {
                    alerts.html('');
                    checkBtn.prop("disabled", true);
                    checkOver.fadeIn();
                },
                success: function (res) {
                    checkOver.fadeOut();
                    console.log(res);

                    if (res.length !== 0) {
                        if (IsJsonString(res)) {
                            var data = JSON.parse(res);

                            if (data['status'] == 'success') {
                                var discount = data['discount'];
                                var newPrice = amount * ( (100 - discount) / 100);
                                $(".checkout_details__info table").append('<tr class="blue"><td>Coupon Discount (' + discount + '%)</td><td>&euro; ' + newPrice.toFixed(2) + '</td></tr>');
                                couponApply.html('<div class="alert alert-success mb-0">Coupon Applied Successfully! <strong>(' + coupon + ')</strong></div>');
                                couponApply.append('<input name="coupon_code" value="' + coupon + '" type="hidden">');

                                // if 100% discount
                                if(discount == '100') {
                                    $(".checkout_details__card").remove();
                                    $(".checkout_details__checkout button").prop("disabled", false);
                                    $(".checkout_form__body input[name=method]").val('free');
                                }
                            } else if (data['status'] == 'used') {
                                alerts.html('<div class="alert alert-info">You have already redeemed this coupon!</div>');
                            } else if (data['status'] == 'invalid') {
                                alerts.html('<div class="alert alert-danger">Invalid Coupon!</div>');
                            } else if (data['status'] == 'unmatch_package') {
                                alerts.html('<div class="alert alert-danger">Sorry! This coupon cannot redeem for this membership package.</div>');
                            } else if (data['status'] == 'unmatch_plan') {
                                alerts.html('<div class="alert alert-danger">Sorry! This coupon cannot redeem for this membership plan.</div>');
                            } else {
                                alerts.html('<div class="alert alert-danger">Ewas lief falsch! Bitte erneut probieren.</div>');
                            }
                        }
                    }


                },
                error: function (data) {
                    checkOver.fadeOut();
                    alerts.html('<div class="login_alerts__list"><span class="text-danger">Ewas lief falsch! Bitte erneut probieren.</span></div>');
                    console.log(data);
                },
            });
        }
    })

    // stripe on checkout
    var checkBTN = $(".checkout_details__checkout button");
    var checkMethod = $(".checkout_form__body input[name=method]").val();
    if (checkMethod == 'stripe') {
        cardElement.mount('#card_number');
        exp.mount('#card_expiry');
        cvc.mount('#card_cvc');

        cardElement.on("change", function (event) {
            if (event.error) {
                checkBTN.prop("disabled", true);
                $(".login_alerts").html('<div class="login_alerts__list"><p>' + event.error.message + '</p></div>');
            } else {
                checkBTN.prop("disabled", false);
                $(".login_alerts").html('');
            }
        })
    }

    function validateCheckout(form, alerts) {
        var error = 0;
        var name = $(form).find("input[name=name]").val();
        if (name == '' || name.length == 0) {
            error += 1;
            alerts.append('<p>Vorname eingeben</p>');
        } else if (name.length < 2) {
            error += 1;
            alerts.append('<p>Der Name sollte mehr als 2 Charaktere haben</p>');
        } else if (name.length > 100) {
            error += 1;
            alerts.append('<p>Der Name sollte weniger als 100 Charaktere haben</p>');
        }

        var address = $(form).find("input[name=address]").val();
        if (address == '' || address.length == 0) {
            error += 1;
            alerts.append('<p>Please enter your address</p>');
        } else if (address.length < 3) {
            error += 1;
            alerts.append('<p>Deine Adresse sollte mindestens 3 Zeichen beinhalten</p>');
        }

        var state = $(form).find("select[name=state]").val();
        if (state == '' || state.length == 0) {
            error += 1;
            alerts.append('<p>Bitte wähle dein Bundesland aus</p>');
        }

        var city = $(form).find("input[name=city]").val();
        if (city == '' || city.length == 0) {
            error += 1;
            alerts.append('<p>Bitte gebe deine Stadt ein</p>');
        }

        var zip = $(form).find("input[name=zip]").val();
        if (zip == '' || zip.length == 0) {
            error += 1;
            alerts.append('<p>Bitte gebe deine Postleitzahl ein</p>');
        }
        return error;
    }

    // user checkout
    $(document).on("submit", "#user_checkout", function (e) {
        e.preventDefault();

        $(".login_alerts").html('<div class="login_alerts__list"></div>');
        var alerts = $(".login_alerts .login_alerts__list");

        if (
            $(this).find("input[name=method]").val() == '' &&
            $(this).find("input[name=package]").val() == '' &&
            $(this).find("input[name=plan_id]").val() == ''
        ) {
            window.location.href = siteURL + "/packages.php";
        }

        var payMethod = $(this).find("input[name=method]").val();

        var checkoutValid = validateCheckout("#user_checkout", alerts);

        if (checkoutValid == 0 || checkoutValid == '0') {
            $(".login_alerts").html('');
            var stripe_token = '';
            if (payMethod == 'stripe') {
                stripe.createToken(cardElement).then(function (result) {
                    if (result.error) {
                        $(".login_alerts").html('<div class="login_alerts__list"><p>' + result.error.message + '</p></div>');
                    } else {
                        stripe_token = result.token.id;
                        //console.log(stripe_token);
                        doPayment('stripe', stripe_token);
                    }
                });
            } else if (payMethod == 'paypal') {
                doPayment('paypal');
            } else if (payMethod == 'free') {
                doPayment('free_membership');
            }
        }
    })

    function doPayment(payMethod, stripe_token = '') {

        $(".login_alerts").html('<div class="login_alerts__list"></div>');
        var alerts = $(".login_alerts .login_alerts__list");

        var checkOver = $(".checkout_form .overlay");
        var checkBtn = $(".checkout_details__checkout button");

        if (payMethod == 'stripe') {
            var dataURL = $('#user_checkout').serialize() + "&stripeToken=" + stripe_token;
        } else {
            var dataURL = $('#user_checkout').serialize();
        }

        $.ajax({
            type: "POST",
            url: siteURL + "/ajax/" + payMethod + ".php",
            data: dataURL,
            beforeSend: function () {
                alerts.parent().html('');
                checkBtn.prop("disabled", true);
                checkOver.fadeIn();
            },
            success: function (data) {
                checkOver.fadeOut();
                console.log(data);
                if (validateURL(data)) {
                    window.location.href = data;
                } else {
                    $(".login_alerts").html('<div class="login_alerts__list"><span class="text-danger">Ewas lief falsch! Bitte erneut probieren.</span></div>');
                    checkBtn.prop("disabled", false);
                }
            },
            error: function (data) {
                checkOver.fadeOut();
                $(".login_alerts").html('<div class="login_alerts__list"><span class="text-danger">Ewas lief falsch! Bitte erneut probieren.</span></div>');
                checkBtn.prop("disabled", false);
                console.log(data);
            },
        });
    }

    /*************************************/
    /*************************************/
    /*************************************/

    $(".consulting_info-content--overlay").on("click", function () {
        let main = $(this).parent();
        if (main.hasClass("half")) {
            main.removeClass("half");
        } else {
            main.addClass("half");
        }

    })

    // seminar popup
    $(".seminar_booking").on("click", function () {
        var seminar_id = $(this).data("seminar");

        $(".seminar_details").find("#subject").text($(this).data("subject"));
        $(".seminar_details").find("#date").text($(this).data("date"));
        $(".seminar_details").find("#location").text($(this).data("location"));
        $(".seminar_details").find("#method").text($(this).data("method"));

        $("#seminar_booking").find("input[name=seminar_id]").val(seminar_id);

        $("#submit_booking").prop("disabled", false);
        $("#seminarModal").modal("show");
    })

    // seminar booking
    $(document).on("submit", "#seminar_booking", function (e) {
        e.preventDefault();

        var alert = $(".course_thread-alert");
        var seminar_id = $(this).find("input[name=seminar_id]").val();

        if (seminar_id == '' || seminar_id.length == 0) {
            alert.html('<div class="alert alert-danger">Etwas lief falsch! Bitte lade die Seite erneut</div>');
        } else {
            $.ajax({
                type: "POST",
                url: siteURL + "/ajax/seminar_booking.php",
                data: {
                    seminar_id: seminar_id,
                },
                beforeSend: function () {
                    $("#submit_booking").prop("disabled", true);
                    alert.html('<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>');
                },
                success: function (data) {
                    alert.html('');
                    console.log(data);

                    $(".course_thread").find("input[name=seminar_id]").val("");

                    if (data == "success") {
                        alert.html('<div class="alert alert-success">Deine Buchung wurde erfolgreich übermittelt</div>');
                        redTimeout('/seminar/');
                    } else if (data == "booked") {
                        alert.html('<div class="alert alert-info">Du hast das bereits gebucht.</div>');
                    } else if (data == "logged") {
                        alert.html('<div class="alert alert-info">Du musst eingeloggt sein, um eine Buchung vorzunehmen.</div>');
                    } else {
                        alert.html('<div class="alert alert-danger">Ewas lief falsch! Bitte erneut probieren.</div>');
                    }
                },
                error: function (data) {
                    console.log(data);
                },
            });
        }
    })

    // seminar feedback submit
    $(document).on("submit", "#seminar_feedback", function (e) {
        e.preventDefault();
        var alert = $(".course_thread-alert");

        var row_id = $(this).find("input[name=row_id]").val();
        var row_type = $(this).find("input[name=row_type]").val();
        var rating = $(this).find("input[name=rating]:checked").val();
        var feedback = $(this).find("textarea[name=feedback]").val();

        //console.log(row_id + " " + user + " " + row_type);
        if (row_id == '' || row_type == '') {
            alert.html('<div class="alert alert-danger">Ewas lief falsch! Bitte erneut probieren.</div>');
        } else if (rating == '' || feedback == '') {
            alert.html('<div class="alert alert-info">Wir freuen uns auf dein Feedback und deine Bewertung</div>');
        } else {
            $.ajax({
                type: "POST",
                url: siteURL + "/ajax/rating.php",
                data: {
                    row_id: row_id,
                    row_type: row_type,
                    rating: rating,
                    feedback: feedback,
                },
                beforeSend: function () {
                    alert.html('<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>');
                },
                success: function (data) {
                    console.log(data);
                    alert.html('');
                    //$(".seminar_feedback").find("input[name=rating], textarea[name=reply]").val("");
                    if (data == "success") {
                        alert.html('<div class="alert alert-success">Danke, wir haben dein Feedback erhalten!</div>');
                        redTimeout('/user/' + row_type + '.php');
                    } else {
                        alert.html('<div class="alert alert-danger">Ewas lief falsch! Bitte erneut probieren.</div>');
                    }
                },
                error: function (data) {
                    console.log(data);
                },
            });
        }
    })

    // seminar view feedback
    $(".seminar_feedback_view, .consulting_feedback_view").on("click", function () {
        var section = $("#seminarViewFeedback");
        var row_id = $(this).data("row");
        var row_type = $(this).data("type");

        $.ajax({
            type: "POST",
            url: siteURL + "/ajax/view_rate.php",
            data: {
                row_id: row_id,
                row_type: row_type,
            },
            beforeSend: function () {
                $("#seminarViewFeedbackModal").modal("show");
                section.html('<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>');
            },
            success: function (data) {
                if (data !== "0") {
                    section.html(data)
                } else {
                    section.html('<div class="alert alert-danger">Ewas lief falsch! Bitte erneut probieren.</div>');
                }
            },
            error: function (data) {
                console.log(data);
            },
        });
    })

    // seminar submit feedback
    $(".seminar_feedback_btn, .consulting_feedback_btn").on("click", function () {
        var subject = $(this).data("subject");
        var row_id = $(this).data("row");
        var row_type = $(this).data("type");
        $("#seminarFeedbackTitle").text(subject);
        $(".seminar_feedback").find("input[name=row_type]").val(row_type);
        $(".seminar_feedback").find("input[name=row_id]").val(row_id);
        $("#seminarFeedbackModal").modal("show");
    })

    // seminar view all feedbacks
    $(".seminar_show_feedbacks").on("click", function () {
        var section = $("#seminarViewAll");

        var subject = $(this).data("subject");
        var seminar_id = $(this).data("seminar");

        $("#seminarAllTitle").text(subject);

        $.ajax({
            type: "POST",
            url: siteURL + "/ajax/seminar_feedbacks.php",
            data: {
                seminar_id: seminar_id,
            },
            beforeSend: function () {
                $("#seminarAllFeedbackModal").modal("show");
                section.html('<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>');
            },
            success: function (data) {
                console.log(data);
                if (data !== "0") {
                    section.html(data)
                } else {
                    section.html('<div class="alert alert-danger">Ewas lief falsch! Bitte erneut probieren.</div>');
                }
            },
            error: function (data) {
                console.log(data);
            },
        });
    })

    /*************************************/
    /*************************************/
    /*************************************/

    // course method
    var payBTN = $(".course_payment button");
    $(".course_pay_method input[name=gateway]").on("change", function () {
        if ($(this).is(":checked")) {
            if ($(this).val() == 'stripe') {

                $(".course_checkout__stripe").html('<div class="checkout_details__card">' +
                    '<div class="form-group">' +
                    '<label>Kartennummer</label>' +
                    '<div id="card_number" class="field form-control"></div>' +
                    '</div>' +
                    '<div class="form-group row">' +
                    '<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">' +
                    '<label>Ablaufdatum</label>' +
                    '<div id="card_expiry" class="field form-control"></div>' +
                    '</div>' +
                    '<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">' +
                    '<label>CVC</label>' +
                    '<div id="card_cvc" class="field form-control"></div>' +
                    '</div>' +
                    '</div>' +
                    '</div>');

                cardElement.mount('#card_number');
                exp.mount('#card_expiry');
                cvc.mount('#card_cvc');

                cardElement.on("change", function (event) {
                    if (event.error) {
                        payBTN.prop("disabled", true);
                        $(".login_alerts").html('<div class="login_alerts__list"><p>' + event.error.message + '</p></div>');
                    } else {
                        payBTN.prop("disabled", false);
                        $(".login_alerts").html('');
                    }
                })
            } else {
                $(".course_checkout__stripe").html("");
                payBTN.prop("disabled", false);
            }

        } else {
            payBTN.prop("disabled", true);
        }
    })

    // course checkout
    $(document).on("submit", "#course_checkout", function (e) {
        e.preventDefault();
        var error = 0;

        $(".login_alerts").html('<div class="login_alerts__list"></div>');
        var alerts = $(".login_alerts .login_alerts__list");

        if (
            $(this).find("input[name=gateway]").val() == '' ||
            $(this).find("input[name=course_id]").val() == ''
        ) {
            alerts.append('<p>Etwas lief falsch! <storng>Laden ...</strong></p>');
            redTimeout('/courses/');
        }

        var payMethod = $(this).find("input[name=gateway]:checked").val();

        var checkoutValid = validateCheckout("#course_checkout", alerts);

        if (checkoutValid == 0 || checkoutValid == '0') {
            var stripe_token = '';
            if (payMethod == 'stripe') {
                //console.log('stripe');
                stripe.createToken(cardElement).then(function (result) {
                    if (result.error) {
                        alerts.append('<p>' + result.error.message + '</p>');
                    } else {
                        $(".login_alerts").html("");
                        stripe_token = result.token.id;
                        oneTimePayment('stripe', stripe_token);
                    }
                });
            } else if (payMethod == 'paypal') {
                $(".login_alerts").html("");
                //console.log('paypal');
                oneTimePayment('paypal');
            }

        }
    })

    function oneTimePayment(payMethod, stripe_token = '') {

        $(".login_alerts").html('<div class="login_alerts__list"></div>');
        var alerts = $(".login_alerts .login_alerts__list");

        var checkOver = $(".course_checking .overlay, .checkout_form .overlay");
        var checkBtn = $(".course_payment button");

        if (payMethod == 'paypal') {
            var dataURL = $('#course_checkout').serialize();
        } else {
            var dataURL = $('#course_checkout').serialize() + "&stripeToken=" + stripe_token;
        }

        $.ajax({
            type: "POST",
            url: siteURL + "/ajax/" + payMethod + "_onetime.php",
            data: dataURL,
            beforeSend: function () {
                alerts.parent().html('');
                checkBtn.prop("disabled", true);
                checkOver.fadeIn();
            },
            success: function (data) {
                checkOver.fadeOut();
                console.log(data);

                if (validateURL(data)) {
                    window.location.href = data;
                } else {
                    $(".login_alerts").html('<div class="login_alerts__list"><span class="text-danger">Ewas lief falsch! Bitte erneut probieren.</span></div>');
                    checkBtn.prop("disabled", false);
                }
            },
            error: function (data) {
                checkOver.fadeOut();
                $(".login_alerts").html('<div class="login_alerts__list"><span class="text-danger">Ewas lief falsch! Bitte erneut probieren.</span></div>');
                checkBtn.prop("disabled", false);
                console.log(data);
            },
        });
    }

    /*************************************/
    /*************************************/
    /*************************************/

    // course checkout
    $(document).on("submit", "#course_free__checkout", function (e) {
        e.preventDefault();
        var error = 0;

        $(".login_alerts").html('<div class="login_alerts__list"></div>');
        var alerts = $(".login_alerts .login_alerts__list");

        if ($(this).find("input[name=course_id]").val() == '') {
            alerts.append('<p>Etwas lief falsch! <storng>Laden ...</strong></p>');
            redTimeout('/courses/');
        }

        var checkoutValid = validateCheckout("#course_free__checkout", alerts);

        if (checkoutValid == 0 || checkoutValid == '0') {

            var checkOver = $(".course_checking .overlay");
            var checkBtn = $(".course_free__claim button");

            $.ajax({
                type: "POST",
                url: siteURL + "/ajax/course_free.php",
                data: $('#course_free__checkout').serialize(),
                beforeSend: function () {
                    alerts.parent().html('');
                    checkBtn.prop("disabled", true);
                    checkOver.fadeIn();
                },
                success: function (data) {
                    checkOver.fadeOut();
                    console.log(data);

                    if (validateURL(data)) {
                        // course claimed successfully!
                        window.location.href = data;
                    } else if (data == 'already') {
                        $(".login_alerts").html('<div class="login_alerts__list"><span class="text-danger">You have already claimed this course.</span></div>');
                        redTimeout('/user/courses/');
                    } else {
                        $(".login_alerts").html('<div class="login_alerts__list"><span class="text-danger">Ewas lief falsch! Bitte erneut probieren.</span></div>');
                        checkBtn.prop("disabled", false);
                    }
                },
                error: function (data) {
                    checkOver.fadeOut();
                    $(".login_alerts").html('<div class="login_alerts__list"><span class="text-danger">Ewas lief falsch! Bitte erneut probieren.</span></div>');
                    checkBtn.prop("disabled", false);
                },
            });
        }
    })

    /*************************************/
    /*************************************/
    /*************************************/

    // consultant booking popup
    $(".consulting_booking").on("click", function () {
        var consulting_id = $(this).data("consulting");
        $("#consultant_name").text($(this).data("subject"));
        $("#consulting_booking").find("input[name=consulting_id]").val(consulting_id);
        $("#consulting_booking_btn").prop("disabled", false);
        $("#consultingModal").modal("show");
    })

    $("input[name=attachment]").change(function () {
        var file = this.files[0];
        var fileType = file.type;
        var match = ['application/pdf', 'application/msword', 'application/vnd.ms-office', 'image/jpeg', 'image/png', 'image/jpg'];
        if (!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]) || (fileType == match[3]) || (fileType == match[4]) || (fileType == match[5]))) {
            alert('Sorry, only PDF, DOC, JPG, JPEG, & PNG files are allowed to upload.');
            $("input[name=attachment]").val('');
            return false;
        }
    });

    // consultant booking
    $(document).on("submit", "#consulting_booking", function (e) {
        e.preventDefault();

        var alert = $(".course_thread-alert");

        var form_data = new FormData(this);
        //form_data.append('file', $('input[name=attachment]')[0].files[0]);

        var consulting_id = $(this).find("input[name=consulting_id]").val();

        if (consulting_id == '' || consulting_id.length == 0) {
            alert.html('<div class="alert alert-danger">Etwas lief falsch! Bitte lade die Seite erneut</div>');
        } else {
            $.ajax({
                type: "POST",
                url: siteURL + "/ajax/consulting_booking.php",
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    $("#submit_booking").prop("disabled", true);
                    alert.html('<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>');
                },
                success: function (data) {
                    alert.html('');
                    //console.log(data);

                    $("#consulting_booking").find("input").each(function () {
                        $(this).val("");
                    })

                    if (data == "success") {
                        alert.html('<div class="alert alert-success">Deine Buchung wurde erfolgreich übermittelt <strong>Laden ...</strong></div>');
                    } else if (data == "logged") {
                        alert.html('<div class="alert alert-info">Du musst eingeloggt sein, um eine Buchung vorzunehmen. <strong>Laden ...</strong></div>');
                    } else {
                        alert.html('<div class="alert alert-danger">Ewas lief falsch! Bitte erneut probieren. <strong>Laden ...</strong></div>');
                    }

                    redTimeout('/consulting/');
                },
                error: function (data) {
                    console.log(data);
                },
            });
        }
    })

    // consultant view all feedbacks
    $(".consulting_show_feedbacks").on("click", function () {
        var section = $("#seminarViewAll");

        var subject = $(this).data("subject");
        var consultant_id = $(this).data("consultant");

        $("#seminarAllTitle").text(subject);

        $.ajax({
            type: "POST",
            url: siteURL + "/ajax/consulting_feedbacks.php",
            data: {
                consultant_id: consultant_id,
            },
            beforeSend: function () {
                $("#seminarAllFeedbackModal").modal("show");
                section.html('<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>');
            },
            success: function (data) {
                console.log(data);
                if (data !== "0") {
                    section.html(data)
                } else {
                    section.html('<div class="alert alert-danger">Ewas lief falsch! Bitte erneut probieren.</div>');
                }
            },
            error: function (data) {
                console.log(data);
            },
        });
    })

    /*************************************/
    /*************************************/
    /*************************************/

    // show intro on a popup
    $(".course_title__img-play").on("click", function () {
        var title = $(this).data("title");
        var video = $(this).data("intro");
        $(".course_intro__popup .modal-title").html(title);
        $(".course_intro__popup-video video source").prop("src", video);
        $(".course_intro__popup-video video")[0].load();

        $("#introModal").modal("show");
    })

    $("#introModal").on("hide.bs.modal", function () {
        var video = $(".course_intro__popup-video video");
        video.get(0).pause();
        video.get(0).currentTime = 0;
    })

    // Show each video of course
    var course_embed = $(".course_video__embed");
    $(".couse_video__list-item").on("click", function () {
        course_embed.find(".overlay").fadeIn();
        var viewing = $(this).data("video");
        $(".couse_video__list-item").removeClass("active");
        $(this).addClass("active");
        setTimeout(function () {
            course_embed.find("video source").prop("src", viewing);
            course_embed.find("video")[0].load();
        }, 1000);
        course_embed.find(".overlay").delay(1500).fadeOut();
    })

    // FAQ Popup
    $(".course_faq__item").on("click", function () {
        var section = $(".course_question");
        var question = $(this).data("question");
        $.ajax({
            type: "POST",
            url: siteURL + "/ajax/question.php",
            data: {
                question_id: question,
            },
            beforeSend: function () {
                $("#questionModal").modal("show");
                section.html('<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>');
            },
            success: function (data) {
                if (data !== "0") {
                    section.html(data)
                } else {
                    section.html('<div class="alert alert-danger">Ewas lief falsch! Bitte erneut probieren.</div>');
                }
            },
            error: function (data) {
                console.log(data);
            },
        });
    })

    // star new question
    $(document).on("submit", "#faq_question", function (e) {
        e.preventDefault();

        var alert = $(".course_thread-alert");

        var course_id = $(this).find("input[name=course_id]").val();
        var course_slug = $(this).find("input[name=course_slug]").val();
        var title = $(this).find("input[name=title]").val();
        var question = tinymce.get("mce_editor").getContent();

        if (course_id == '' || course_slug == '' || title == '' || question == '') {
            alert.html('<div class="alert alert-danger">Bitte fülle das Feld aus, um eine neue Frage zu beantworten. </div>');
        } else {
            $.ajax({
                type: "POST",
                url: siteURL + "/ajax/thread.php",
                data: {
                    question: question,
                    title: title,
                    course_id: course_id,
                },
                beforeSend: function () {
                    alert.html('<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>');
                },
                success: function (data) {
                    alert.html('');

                    $(".course_thread").find("input[name=title], textarea[name=reply]").val("");
                    tinymce.get("mce_editor").setContent("");

                    if (data == "success") {
                        alert.html('<div class="alert alert-success">Wir haben deine Frage erhalten.</div>');
                        redTimeout('/views/video.php?slug=' + course_slug);
                    } else {
                        alert.html('<div class="alert alert-danger">Ewas lief falsch! Bitte erneut probieren.</div>');
                    }
                },
                error: function (data) {
                    console.log(data);
                },
            });
        }
    })

    // faq reply
    $(document).on("submit", "#faq_reply", function (e) {
        e.preventDefault();
        var alert = $(".course_question__comment-alert");

        var question = $(this).find("input[name=question]").val();
        var reply = $(this).find("textarea[name=reply]").val();

        var comment_counter = $(".course_faq__item[data-question=" + question + "]").find(".show_comment_count");

        if (question == '' || reply == '') {
            alert.html('<div class="alert alert-danger">Bitte erwähne alles wichtige zu deiner Frage.</div>');
        } else {
            $.ajax({
                type: "POST",
                url: siteURL + "/ajax/reply.php",
                data: {
                    question: question,
                    reply: reply,
                },
                beforeSend: function () {
                    alert.html('<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>');
                },
                success: function (data) {
                    alert.html('');
                    $(".course_question").find("textarea[name=reply]").val("");
                    if (data !== "0") {

                        var comment_count = comment_counter.html();
                        comment_counter.html(parseInt(comment_count) + 1);

                        $(".course_question__reply").append(data);
                    } else {
                        alert.html('<div class="alert alert-danger">Ewas lief falsch! Bitte erneut probieren.</div>');
                    }
                },
                error: function (data) {
                    console.log(data);
                },
            });
        }
    })

    // like to faq
    $(document).on("click", "#faq_like", function (e) {
        var alert = $(".course_question__main-alert");
        var faqBTN = $(".course_question").find("#faq_like");

        var faq_id = $(this).data('faq');
        var like_status = $(this).data('like');

        var like_counter = $(".course_faq__item[data-question=" + faq_id + "]").find(".show_like_count");
        var like_counter_pop = $(".course_question").find(".show_like_count");

        if (faq_id == '') {
            alert.html('<span class="text-danger">Ewas lief falsch! Bitte erneut probieren.</span>');
        } else {
            $.ajax({
                type: "POST",
                url: siteURL + "/ajax/like.php",
                data: {
                    faq_id: faq_id,
                    like_status: like_status,
                },
                beforeSend: function () {
                    if (like_status == 'unlike') {
                        faqBTN.removeClass("btn-dark").addClass("btn-dark-outline");
                        faqBTN.find("span").text("Leave a Like");
                        faqBTN.data("like", "like");
                    } else {
                        faqBTN.removeClass("btn-dark-outline").addClass("btn-dark");
                        faqBTN.find("span").text("Liked!");
                        faqBTN.data("like", "unlike");
                    }
                },
                success: function (data) {
                    //console.log(data);
                    alert.html('');
                    if (data !== "0") {

                        var like_count = like_counter.html();
                        var like_count_pop = like_counter_pop.html();

                        if (like_status == 'unlike') {
                            like_counter.html(parseInt(like_count) - 1);
                            like_counter_pop.html(parseInt(like_count_pop) - 1);
                        } else {
                            like_counter.html(parseInt(like_count) + 1);
                            like_counter_pop.html(parseInt(like_count_pop) + 1);
                        }

                        //alert.html('<span class="text-success">Your vote has been casted</span>');
                    } else {
                        alert.html('<span class="text-danger">Ewas lief falsch! Bitte erneut probieren.</span>');
                    }
                },
                error: function (data) {
                    alert.html('<span class="text-danger">Ewas lief falsch! Bitte erneut probieren.</span>');
                    console.log(data);
                },
            });
        }
    })

    /*************************************/
    /*************************************/
    /*************************************/

    // listing bug feedback form
    $("#listing_bugs__feedback textarea").on("change keyup", function () {
        if ($(this).val() !== '') {
            $("#listing_bugs__feedback button").prop("disabled", false);
        } else {
            $("#listing_bugs__feedback button").prop("disabled", true);
        }
    })

    // listing bug feedback
    $(document).on("submit", "#listing_bugs__feedback", function (e) {
        e.preventDefault();
        var alert = $(".listing_bugs__alert");
        var info = $(this).find("textarea[name=info]").val();
        var listing_id = $(this).find("input[name=listing_id]").val();

        if (info == '') {
            alert.html('<div class="alert alert-danger">Bitte gebe einige Wörter ein, um den Inhalt zu senden.</div>');
        } else {
            $.ajax({
                type: "POST",
                url: siteURL + "/ajax/bug_submit.php",
                data: {
                    info: info,
                    listing_id: listing_id,
                },
                beforeSend: function () {
                    alert.html('<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>');
                },
                success: function (data) {
                    alert.html('');
                    $("#listing_bugs__feedback").find("textarea[name=info]").val("");

                    if (data == "success") {
                        alert.html('<div class="alert alert-success">Danke für deine Feedback!</div>');
                    } else {
                        alert.html('<div class="alert alert-danger">Ewas lief falsch! Bitte erneut probieren.</div>');
                    }
                },
                error: function (data) {
                    console.log(data);
                },
            });
        }
    })

    /*************************************/

    // listing bug feedback form
    $("#webinar_register textarea").on("change keyup", function () {
        if ($(this).val() !== '') {
            $("#webinar_register button").prop("disabled", false);
        } else {
            $("#webinar_register button").prop("disabled", true);
        }
    })

    // listing bug feedback
    $(document).on("submit", "#webinar_register", function (e) {
        e.preventDefault();
        var alert = $(".webinar_register__alert");
        var question = $(this).find("textarea[name=question]").val();

        if (question == '') {
            alert.html('<div class="alert alert-danger">Bitte gebe einige Wörter ein, um den Inhalt zu senden.</div>');
        } else {
            $.ajax({
                type: "POST",
                url: siteURL + "/ajax/webinar_register.php",
                data: {
                    question: question,
                },
                beforeSend: function () {
                    alert.html('<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>');
                },
                success: function (data) {
                    alert.html('');
                    //console.log(data);
                    if (data == "success") {
                        $("#webinar_register").slideUp().remove();
                        alert.html('<div class="alert alert-success">Danke für deine Anmeldung für unser Webinar!</div>');
                    } else if (data == "logged") {
                        alert.html('<div class="alert alert-danger">Bittte <a href="' + siteURL + '/user/login/?redirect=/webinar/"><strong>Einloggen</strong></a>/ <a href="' + siteURL + '/user/register/"><strong>Registrieren</strong></a> um fortzufahren</div>');
                    } else {
                        alert.html('<div class="alert alert-danger">Ewas lief falsch! Bitte erneut probieren.</div>');
                    }
                },
                error: function (data) {
                    console.log(data);
                },
            });
        }
    })

    /*************************************/

    function contentElm(element, value) {
        var main = $(element);
        main.each(function () {
            var elm = $(this);
            if (elm.prop("tagName") == 'SPAN') {
                /* if (value == '') {
                    elm.parent().parent().hide();
                } else {
                    elm.parent().parent().show();
                } */
                if (value == '') {
                    //value = 'Any';
                    value = '';
                }
                elm.text(value);
            } else {
                if (value == 'Any' || value == '') {
                    value = '';
                }
                elm.val(value);
            }
        })

    }

    var searchForm = $(".search_info__form");
    $("#search_order").on("click", function () {

        var searchInput = searchForm.find("#searchInput").val();
        contentElm(".pl_so-address", searchInput);

        if (searchInput == '') {
            $("span.pl_so-radius").parent().parent().hide();
        } else {
            var radius_cal = searchForm.find("#radius_cal").val();
            contentElm(".pl_so-radius", radius_cal);
        }

        var category = searchForm.find("select[name=category]").val();
        contentElm(".pl_so-category", category);

        var living_space_from = searchForm.find("input[name=living_space_from]").val();
        contentElm(".pl_so-space_from", living_space_from);
        var living_space_to = searchForm.find("input[name=living_space_to]").val();
        contentElm(".pl_so-space_to", living_space_to);

        var room_count_from = searchForm.find("input[name=room_count_from]").val();
        contentElm(".pl_so-rooms_from", room_count_from);
        var room_count_to = searchForm.find("input[name=room_count_to]").val();
        contentElm(".pl_so-rooms_to", room_count_to);

        var value_count = searchForm.find("select[name=value_count]").val();
        contentElm(".pl_so-value", value_count);

        var price_from = searchForm.find("input[name=price_from]").val();
        contentElm(".pl_so-price_from", price_from);

        var price_to = searchForm.find("input[name=price_to]").val();
        contentElm(".pl_so-price_to", price_to);

        // reports
        var reports = searchForm.find("input[name='reports[]']:checked");
        var reports_text = "Any";
        var reports_values = "";

        var report_list = reports.map(function (elem) {
            var report_name = '';
            if ($(this).val() == 'none') report_name = 'Kein Gutachten';
            if ($(this).val() == 'short') report_name = 'Exposé';
            if ($(this).val() == 'long') report_name = 'Gutachten';
            return report_name;
        }).get().join(", ");
        if (report_list.length !== 0) {
            reports_text = report_list;
        }

        var report_vals = reports.map(function (elem) {
            return $(this).val();
        }).get().join(", ");
        if (report_vals.length !== 0) {
            reports_values = report_vals;
        }

        /* if (reports.val().length !== 0) {
            reports_text = reports.find("option:selected").text();
        } */

        $("span.pl_so-reports").text(reports_text);
        $("input.pl_so-reports").val(reports_values);

        // checkbox
        /* var reports = searchForm.find("input[name='reports[]']").val();
        contentElm(".pl_so-reports", reports); */

        var model = '';
        if (searchForm.find("input[name=model3d]:checked").length > 0) {
            var model = searchForm.find("input[name=model3d]:checked").val();
        }
        contentElm(".pl_so-model", model);

        var denkmalschutz = '';
        if (searchForm.find("input[name=denkmalschutz]:checked").length > 0) {
            var denkmalschutz = searchForm.find("input[name=denkmalschutz]:checked").val();
        }
        contentElm(".pl_so-denkmalschutz", denkmalschutz);

        var contaminated = '';
        if (searchForm.find("input[name=altlastenverdacht]:checked").length > 0) {
            var contaminated = searchForm.find("input[name=altlastenverdacht]:checked").val();
        }
        contentElm(".pl_so-contaminated", contaminated);

        var commitments = '';
        if (searchForm.find("input[name=mietbindungen]:checked").length > 0) {
            var commitments = searchForm.find("input[name=mietbindungen]:checked").val();
        }
        contentElm(".pl_so-commitments", commitments);


        var miete_from = searchForm.find("input[name=miete_from]").val();
        contentElm(".pl_so-miete_from", miete_from);

        var miete_to = searchForm.find("input[name=miete_to]").val();
        contentElm(".pl_so-miete_to", miete_to);

        var potential_from = searchForm.find("input[name=potential_from]").val();
        contentElm(".pl_so-potential_from", potential_from);

        var potential_to = searchForm.find("input[name=potential_to]").val();
        contentElm(".pl_so-potential_to", potential_to);

        var kauf_from = searchForm.find("input[name=kauf_from]").val();
        contentElm(".pl_so-kauf_from", kauf_from);

        var kauf_to = searchForm.find("input[name=kauf_to]").val();
        contentElm(".pl_so-kauf_to", kauf_to);

        var preis_from = searchForm.find("input[name=preis_from]").val();
        contentElm(".pl_so-preis_from", preis_from);

        var preis_to = searchForm.find("input[name=preis_to]").val();
        contentElm(".pl_so-preis_to", preis_to);

        var month_payment_from = searchForm.find("input[name=month_payment_from]").val();
        contentElm(".pl_so-month_payment_from", month_payment_from);

        var month_payment_to = searchForm.find("input[name=month_payment_to]").val();
        contentElm(".pl_so-month_payment_to", month_payment_to);

        var rendite_from = searchForm.find("input[name=rendite_from]").val();
        contentElm(".pl_so-rendite_from", rendite_from);

        var rendite_to = searchForm.find("input[name=rendite_to]").val();
        contentElm(".pl_so-rendite_to", rendite_to);

        var multiplier_gross_from = searchForm.find("input[name=multiplier_gross_from]").val();
        contentElm(".pl_so-multiplier_gross_from", multiplier_gross_from);

        var multiplier_gross_to = searchForm.find("input[name=multiplier_gross_to]").val();
        contentElm(".pl_so-multiplier_gross_to", multiplier_gross_to);

        var current_usage = searchForm.find("select[name=current_usage]").val();
        contentElm(".pl_so-current_usage", current_usage);

        var inspection_type = searchForm.find("select[name=inspection_type]").val();
        contentElm(".pl_so-inspection_type", inspection_type);

        /* var contaminated = searchForm.find("select[name=contaminated]");
        var contaminated_text = "Any";
        if (contaminated.val() !== '') {
            contaminated_text = contaminated.find("option:selected").text();
        }
        $("span.pl_so-contaminated").text(contaminated_text);
        $("input.pl_so-contaminated").val(contaminated.val()); */
        //contentElm(".pl_so-contaminated", contaminated);

        var listing_equipment = searchForm.find("select[name='listing_equipment[]']");
        var listing_equipment_text = "Any";

        if (listing_equipment === null) {
            if (listing_equipment.hasClass("select2")) {
                var equip_list = listing_equipment.select2('data');
                var equip_show = equip_list.map(function (elem) {
                    return elem.text;
                }).join(", ");
                if (equip_list.length !== 0) {
                    listing_equipment_text = equip_show;
                }
            } else {
                if (listing_equipment.val().length !== 0) {
                    listing_equipment_text = listing_equipment.find("option:selected").text();
                }
            }
        }

        $("span.pl_so-listing_equipment").text(listing_equipment_text);
        $("input.pl_so-listing_equipment").val(listing_equipment.val());
        //contentElm(".pl_so-listing_equipment", listing_equipment);

        var construction_year_from = searchForm.find("select[name=construction_year_from]").val();
        contentElm(".pl_so-construction_year_from", construction_year_from);

        var construction_year_to = searchForm.find("select[name=construction_year_to]").val();
        contentElm(".pl_so-construction_year_to", construction_year_to);

        var report_time = searchForm.find("input[name=report_time]").val();
        contentElm(".pl_so-report_time", report_time);




        if (searchInput == '' && category == '' && living_space_from == '' && living_space_to == '' && room_count_from == '' && room_count_to == '' && value_count == '' && price_from == '' && price_to == '') {
            $(".search_info__form-alert").html('<div class="alert alert-danger">Du musst mindestens einen Suchparametern wählen, damit der Suchauftrag erstellt werden kann</div>');
        } else {
            $("#searchModal").modal("show");
        }

    })

    // seminar booking
    $(document).on("submit", "#search_order__submit", function (e) {
        e.preventDefault();

        var alert = $(".course_thread-alert");
        var overlay = $(this).find(".overlay");

        var emptyCount = 0;
        $(this).find("input, select").each(function () {
            if ($(this).val() == "") {
                emptyCount += 1;
            }
        });

        if (emptyCount == 7) {
            alert.html('<div class="alert alert-danger">Please fill one fact atleast.</div>');
            //redTimeout('/search/');
        } else {

            $.ajax({
                type: "POST",
                url: siteURL + "/ajax/search_order.php",
                data: $(this).serialize(),
                beforeSend: function () {
                    $("#search_order__submit-btn").prop("disabled", true);
                    overlay.fadeIn();
                    alert.html('<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>');
                },
                success: function (data) {
                    alert.html('');
                    console.log(data);

                    overlay.fadeOut();

                    $("#search_order__submit").find("input").each(function () {
                        $(this).val("");
                    })

                    if (data == "success") {
                        alert.html('<div class="alert alert-success">Dein Suchauftrag wurde erfolgreich angelegt!</div>');
                        //redTimeout('/user/request/');
                    } else if (data == "empty") {
                        alert.html('<div class="alert alert-danger">Please fill one fact atleast.</div>');
                        //redTimeout('/search/');
                    } else if (data == "already") {
                        alert.html('<div class="alert alert-info">Du hast bereits einen Suchauftrag zu diesen Parametern.</div>');
                    } else if (data == "logged") {
                        alert.html('<div class="alert alert-info">Du musst eingeloggt sein, um einen Suchauftrag zu erstellen.</div>');
                        //redTimeout('/user/login/');
                    } else if (data == "limited") {
                        alert.html('<div class="alert alert-info">Du hast die Maximale Anzahl an Suchaufträgen erstellt.</div>');
                    } else {
                        alert.html('<div class="alert alert-danger">Ewas lief falsch! Bitte erneut probieren.</div>');
                        //redTimeout('/search/');
                    }
                },
                error: function (data) {
                    console.log(data);
                },
            });
        }

    })

    // user search order actions
    $(".request_actions button").on("click", function () {
        var order_id = $(this).data("order");
        var action = $(this).data("action");

        var overlay = $(this).parent().parent().parent().parent().find(".overlay");
        overlay.fadeIn();

        if (action == '' || order_id == '') {
            window.alert("Ewas lief falsch! Bitte erneut probieren.");
        } else {
            var confirmTxt = '';
            var successTxt = '';
            if (action == 'delete') {
                confirmTxt = 'Willst du diesen Suchauftrag wirklich löschen?';
                successTxt = 'Dein Suchauftrag wurde erfolgreich gelöscht.';
            } else if (action == 'pause') {
                confirmTxt = 'Willst du deinen Suchauftrag pausieren?';
                successTxt = 'Dein Suchauftrag wurde pausiert.';
            } else if (action == 'resume') {
                confirmTxt = 'Willst du den Suchauftrag wieder aktivieren?';
                successTxt = 'Dein Suchauftrag is wieder aktiv.';
            }

            if (confirmTxt == '') {
                window.alert("Ewas lief falsch! Bitte erneut probieren.");
            } else {
                if (confirm(confirmTxt)) {
                    $.ajax({
                        type: "POST",
                        url: siteURL + "/ajax/search_order_action.php",
                        data: {
                            order_id: order_id,
                            action: action,
                        },
                        beforeSend: function () {
                            overlay.fadeIn();
                        },
                        success: function (data) {
                            if (data == "success") {
                                window.alert(successTxt);
                                window.location.href = siteURL + '/user/request/';
                            } else {
                                window.alert("Ewas lief falsch! Bitte erneut probieren.");
                                overlay.fadeOut();
                            }
                        },
                        error: function (data) {
                            console.log(data);
                        },
                    });
                } else {
                    overlay.fadeOut();
                }
            }
        }
    })

    // user search order results
    $(".request_details__title-button button").on("click", function () {
        var section = $("#orderResults");
        var order_id = $(this).data("order");

        $.ajax({
            type: "POST",
            url: siteURL + "/ajax/search_order_results.php",
            data: {
                order_id: order_id,
            },
            beforeSend: function () {
                $("#soResultsModal").modal("show");
                section.html('<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>');
            },
            success: function (data) {
                console.log(data);
                if (data !== "0") {
                    section.html(data)
                } else {
                    section.html('<div class="alert alert-danger">Ewas lief falsch! Bitte erneut probieren.</div>');
                }
            },
            error: function (data) {
                console.log(data);
            },
        });

    })


});