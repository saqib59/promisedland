function searchMap() {
    var fullH = $(window).height();
    var headerH = $("#header").outerHeight();
    var restH = fullH - headerH;
    if($(window).width() > 991) {
        $("#map").css("height", restH);
        $(".search_info").css("height", restH);
    }
}

function sameH(main, sub) {
    var maxH = 0;
    var elm = $(main).find(sub);
    elm.each(function () {
        if (maxH < $(this).outerHeight()) {
            maxH = $(this).outerHeight();
        }

    })
    elm.css("height", maxH);
}

$(window).on("load resize", function () {


    sameH(".pb_features", ".pb_feature");
    sameH(".course_landing", ".video_item-title");
    sameH(".course_landing", ".video_item-info");
    sameH(".pricing_boxes", ".land_price_top");
    sameH(".top-listing", ".favourite_list__item");
    sameH(".land_features", ".land_feature__panel");
    sameH(".favourite_list", ".favourite_list__item");
    sameH(".user_courses", ".user_courses__item-info");
    sameH(".listing_gallery__panel-charts", ".listing_chart");

    /* Search Page height */
    searchMap();

})