(function (w, d) {
    var loader = function () {
        var s = d.createElement("script"),
            tag = d.getElementsByTagName("script")[0];
        s.src = "https://cdn.iubenda.com/iubenda.js";
        tag.parentNode.insertBefore(s, tag);
    };
    if (w.addEventListener) {
        w.addEventListener("load", loader, false);
    } else if (w.attachEvent) {
        w.attachEvent("onload", loader);
    } else {
        w.onload = loader;
    }
})(window, document);

function gtag_report_conversion(url) {
    var callback = function () {
        if (typeof (url) != 'undefined') {
            window.location = url;
        }
    };
    gtag('event', 'conversion', {
        'send_to': 'AW-347311738/r-WoCJDMpIIDEPqczqUB',
        'event_callback': callback
    }); return false;
}

function gtag_report_conversion_Consultant(url) {
    var callback = function () {
        if (typeof (url) != 'undefined') {
            window.location = url;
        }
    };
    gtag('event', 'conversion', {
        'send_to': 'AW-347311738/3llsCIjDjYMDEPqczqUB',
        'event_callback': callback
    }); return false;
}

$(document).ready(function () {

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    tinymce.init({
        selector: '.mce_editor'
    });

    // Select2
    $('.select2').select2();

    /* Radius Range */
    function resetAll() {
        $(".factor-slider").slider("option", "values", [3]);
        $(".factor-slider").slider("value", 3);
        $(".factor-slider").slider("option", "range", false);
    }
    var radiusRange = $(".radius_range");
    radiusRange.slider({
        min: 1,
        max: 250,
        value: 35,
        slide: function (event, ui) {
            $(".show_radius_range").html(ui.value + " Kilometer");
            $("input[name=radius]").val(ui.value);
        },
        stop: function (event, ui) {
            resetAll();
        }
    });
    radiusRange.slider("disable");

    var radiusInput = radiusRange.slider("values", 0);
    $(".show_radius_range").html(radiusInput + " Kilometer");
    $("input[name=radius]").val(radiusInput);

    /* Price Range */
    var priceRange = $(".price_range");
    priceRange.slider({
        range: true,
        min: 0,
        max: 1000000,
        values: [0, 1000000],
        slide: function (event, ui) {
            $(".show_price_range").html("&euro;" + ui.values[0] + " bis &euro;" + ui.values[1]);
            $("input[name=price_from]").val(ui.values[0]);
            $("input[name=price_to]").val(ui.values[1]);
        }
    });

    var priceFrom = priceRange.slider("values", 0);
    var priceTo = priceRange.slider("values", 1);
    $(".show_price_range").html("&euro;" + priceFrom + " bis &euro;" + priceTo);
    $("input[name=price_from]").val(priceFrom);
    $("input[name=price_to]").val(priceTo);

    /* Monthly Range */
    var monthly_range = $(".monthly_range");
    monthly_range.slider({
        range: true,
        min: 0,
        max: 10000,
        values: [0, 10000],
        slide: function (event, ui) {
            $(".show_monthly_range").html("&euro;" + ui.values[0] + " bis &euro;" + ui.values[1]);
            $("input[name=month_payment_from]").val(ui.values[0]);
            $("input[name=month_payment_to]").val(ui.values[1]);
        }
    });

    var priceFrom = monthly_range.slider("values", 0);
    var priceTo = monthly_range.slider("values", 1);
    $(".show_monthly_range").html("&euro;" + priceFrom + " bis &euro;" + priceTo);
    $("input[name=month_payment_from]").val(priceFrom);
    $("input[name=month_payment_to]").val(priceTo);

    $('.land_page__slider').owlCarousel({
        margin: 20,
        responsiveClass: true,
        loop: true,
        autoplay: true,
        URLhashListener: true,
        autoplayTimeout: 3000,
        autoplayHoverPause: true,
        responsive: {
            0: {
                items: 1,

            },
            600: {
                items: 2,

            },
            1000: {
                items: 3,
            }

        }
    })

    $('.portal_slider').owlCarousel({
        center: true,
        margin: 0,
        responsiveClass: true,
        loop: true,
        autoplay: true,
        autoplayTimeout: 3000,
        autoplayHoverPause: true,
        responsive: {
            0: {
                items: 1,

            },
            900: {
                items: 2,

            },

        }
    })



})