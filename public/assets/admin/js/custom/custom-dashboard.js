/*
----------------------------------------------
    : Custom - Dashboard CRM js :
----------------------------------------------
*/
"use strict";
$(document).ready(function() {

    /* -----  Apex Line1 Chart ----- */
    var options = {
        chart: {
            height: 200,
            type: 'bar',
            toolbar: {
                show: false
            },
            zoom: {
                enabled: false
            }
        },
        colors: ['#1ba4fd'],
        series: [{
            data: Object.values(purchases)
        }],
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 4
        },
        grid: {
            row: {
                colors: ['transparent', 'transparent'], opacity: .2
            },
            borderColor: 'transparent'
        },
        yaxis: {
            labels: {
                show: false
            },
            min: 0
        },
        xaxis: {
            labels: {
                show: true
            },
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            axisBorder: {
                show: true,
                color: 'transparent'
            },
            axisTicks: {
                show: true,
                color: 'transparent'
            }
        }
    }
    var chart = new ApexCharts(
        document.querySelector("#apex-line-chart1"),
        options
    );
    chart.render();

    /* -----  Apex Line2 Chart ----- */
    var options = {
        chart: {
            height: 200,
            type: 'bar',
            toolbar: {
                show: false
            },
            zoom: {
                enabled: false
            }
        },
        colors: ['#3dcd8b'],
        series: [{
            data: Object.values(sales)
        }],
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 4
        },
        grid: {
            row: {
                colors: ['transparent', 'transparent'], opacity: .2
            },
            borderColor: 'transparent'
        },
        yaxis: {
            labels: {
                show: false
            },
            min: 0
        },
        xaxis: {
            labels: {
                show: true
            },
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            axisBorder: {
                show: false,
                color: 'transparent'
            },
            axisTicks: {
                show: false,
                color: 'transparent'
            }
        }
    }
    var chart = new ApexCharts(
        document.querySelector("#apex-line-chart2"),
        options
    );
    chart.render();

    /* -----  Apex Line3 Chart ----- */
    var options = {
        chart: {
            height: 200,
            type: 'area',
            toolbar: {
                show: false
            },
            zoom: {
                enabled: false
            }
        },
        colors: ['#b8d1e1'],
        series: [{
            data: Object.values(profits)
        }],
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 4
        },
        grid: {
            row: {
                colors: ['transparent', 'transparent'], opacity: .2
            },
            borderColor: 'transparent'
        },
        yaxis: {
            labels: {
                show: false
            },
            min: 0
        },
        xaxis: {
            labels: {
                show: true
            },
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            axisBorder: {
                show: false,
                color: 'transparent'
            },
            axisTicks: {
                show: false,
                color: 'transparent'
            }
        }
    }
    var chart = new ApexCharts(
        document.querySelector("#apex-line-chart3"),
        options
    );
    chart.render();

    /* -- User Slider -- */
    $('.user-slider').slick({
        arrows: true,
        dots: false,
        infinite: true,
        adaptiveHeight: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        prevArrow: '<i class="ri-arrow-left-s-line"></i>',
        nextArrow: '<i class="ri-arrow-right-s-line"></i>'
    });

});
