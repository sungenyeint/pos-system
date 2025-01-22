/*
----------------------------------------------
    : Custom - Dashboard CRM js :
----------------------------------------------
*/
"use strict";
$(document).ready(function() {

    /* -----  Apex Line1 Chart ----- */
    const chartData = {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        series: [
            {
                name: 'Total Purchase',
                data: Object.values(purchases)
            },
            {
                name: 'Total Sale',
                data: Object.values(sales)
            },
            {
                name: 'Total Profit',
                data: Object.values(profits)
            }
        ]
    };

    var options = {
        chart: {
            type: 'area',
            height: 350,
        },
        series: chartData.series,
        xaxis: {
            categories: chartData.categories,
            axisBorder: {
                show: false,
                color: 'transparent'
            },
            axisTicks: {
                show: false,
                color: 'transparent'
            },
        },
        tooltip: {
            y: {
                formatter: function (value) {
                    // Format number with commas
                    return value.toLocaleString() + 'ကျပ်';
                }
            }
        },
        yaxis: {
            labels: {
                formatter: function (value) {
                    // Format y-axis labels with commas
                    return value.toLocaleString();
                }
            }
        },
        dataLabels: {
            enabled: false
        },
        colors: ['#008FFB', '#00E396', '#FEB019'], // Sale, Purchase, Profit colors
        stroke: {
            curve: 'smooth', // Smooth line style
            width: 4
        },
        legend: {
            position: 'top'
        },
        grid: {
            borderColor: '#e7e7e7',
        },
    };

    var chart = new ApexCharts(
        document.querySelector("#apex-line-chart1"),
        options
    );
    chart.render();

    /* -----  Apex Line3 Chart ----- */
    /*
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
    */
});
