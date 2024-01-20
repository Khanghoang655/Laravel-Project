
var options = {
    series: [
        {
            name: "series1",
            data: [],
        },
    ],
    chart: {
        height: 350,
        type: "area",
        toolbar: {
            show: false,
        },
    },
    dataLabels: {
        enabled: false,
    },
    stroke: {
        curve: "smooth",
    },
    colors: ["#fcb800", "#f9f9f9", "#9C27B0"],
    xaxis: {
        type: "datetime",
        categories: [
           
        ],
    },
    tooltip: {
        x: {
            format: "dd/MM/yy",
        },
    },
};
for (const row of orderNumberData) {
    if (row[0] !== "Month Year") {
        const MonthYear = row[0];
        const parsedDate = moment(MonthYear, "YYYYMMDD");

        const formattedDate = parsedDate.format("YYYY-MM-DDTHH:mm:ss.SSSZ");

        options.xaxis.categories.push(formattedDate);
    }
    if (row[1] !== "Number") {
        const number = row[1];
        options.series[0].data.push(number);
    }
}
var donutChart = {
    series: [],
    chart: {
        height: "250",
        type: "donut",
    },
    chartOptions: {
        labels: ["Apple", "Mango", "Orange"],
    },

    plotOptions: {
        pie: {
            donut: {
                size: "71%",
                polygons: {
                    strokeWidth: 0,
                },
            },
            expandOnClick: false,
        },
    },
    states: {
        hover: {
            filter: {
                type: "darken",
                value: 0.9,
            },
        },
    },

    dataLabels: {
        enabled: false,
    },

    legend: {
        show: false,
    },
    tooltip: {
        enabled: false,
    },
};
for (const row of chartData) {
    if (row[1] !== "Number") {
        const number = row[1];
        donutChart.series.push(number);
    }
}

var donut = new ApexCharts(document.querySelector("#donut-chart"), donutChart);
donut.render();

var chart = new ApexCharts(document.querySelector("#chart"), options);
chart.render();
