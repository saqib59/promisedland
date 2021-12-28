/* Jobless */

var options1 = {
    series: [{
        name: "STOCK ABC",
        data: [30, 40, 35, 50, 49, 60, 70, 91, 125]
    }],
    chart: {
        type: 'area',
        height: 350,
        zoom: {
            enabled: false
        }
    },
    title: {
        text: 'Arbeitslosenquote in %',
        align: 'left'
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        curve: 'straight'
    },
    labels: [1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998, 1999],
    xaxis: {
        type: 'datetime',
    }
};

var options2 = {
    series: [{
        name: "STOCK ABC",
        data: [30, 40, 35, 50, 49, 60, 70, 91, 125]
    }],
    chart: {
        type: 'area',
        height: 350,
        zoom: {
            enabled: false
        }
    },
    title: {
        text: 'Einwohner',
        align: 'left'
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        curve: 'straight'
    },
    labels: [1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998, 1999],
    xaxis: {
        type: 'datetime',
    }
};

$(document).ready(function () {

    if ($("#chart_jobless").lenght > 0) {
        var chart1 = new ApexCharts(document.querySelector("#chart_jobless"), options1);
        chart1.render();
    }

    if ($("#chart_inhabitants").lenght > 0) {
        var chart2 = new ApexCharts(document.querySelector("#chart_inhabitants"), options2);
        chart2.render();
    }

})


