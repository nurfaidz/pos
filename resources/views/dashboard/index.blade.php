@extends('layouts.master')
@section('main')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="home-tab">
                    <div class="tab-content tab-content-basic">
                        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                            <div class="row">
                                <div class="col-lg-8 d-flex flex-column">
                                    <div class="row flex-grow">
                                        <div class="col-12 grid-margin stretch-card">
                                            <div class="card card-rounded">
                                                <div class="card-body">
                                                    <div class="d-sm-flex justify-content-between align-items-start">
                                                        <div>
                                                            <h4 class="card-title card-title-dash">Penjualan</h4>
                                                        </div>
                                                    </div>
                                                    <div class="d-sm-flex align-items-center mt-1 justify-content-between">
                                                        <div class="me-3">
                                                            <div id="transaction-overview-legend"></div>
                                                        </div>
                                                    </div>
                                                    <div class="chartjs-bar-wrapper mt-3">
                                                        <canvas id="transactionOverview"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 d-flex flex-column">
                                    <div class="row flex-grow">
                                        <div class="col-12 grid-margin stretch-card">
                                            <div class="card card-rounded">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="d-flex justify-content-between align-items-center mb-3">
                                                                <h4 class="card-title card-title-dash">Type
                                                                    By Amount</h4>
                                                            </div>
                                                            <canvas class="my-auto" id="doughnutMemberChart"
                                                                height="200"></canvas>
                                                            <div id="doughnut-chart-member" class="mt-5 text-center">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page_js')
    <script>
        let labels = {!! json_encode($labels) !!};
        let users = {!! json_encode($data) !!};
        let unmember = {{ $unMember }};
        let member = {{ $member }};

        // Chart bar sale
        if ($("#transactionOverview").length) {
            let transactionOverviewChart = document
                .getElementById("transactionOverview")
                .getContext("2d");
            let transactionOverviewData = {
                labels: labels,
                datasets: [{
                    label: "Penjualan akhir ini",
                    data: users,
                    backgroundColor: "#1F3BB3",
                    borderColor: ["#1F3BB3"],
                    borderWidth: 0,
                    fill: true, // 3: no fill
                }, ],
            };

            let transactionOverviewOptions = {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        gridLines: {
                            display: true,
                            drawBorder: false,
                            color: "#F0F0F0",
                            zeroLineColor: "#F0F0F0",
                        },
                        ticks: {
                            beginAtZero: true,
                            autoSkip: true,
                            maxTicksLimit: 5,
                            fontSize: 10,
                            color: "#6B778C",
                        },
                    }, ],
                    xAxes: [{
                        stacked: true,
                        barPercentage: 0.35,
                        gridLines: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            beginAtZero: false,
                            autoSkip: true,
                            maxTicksLimit: 12,
                            fontSize: 10,
                            color: "#6B778C",
                        },
                    }, ],
                },
                legend: false,
                legendCallback: function(chart) {
                    let text = [];
                    text.push('<div class="chartjs-legend"><ul>');
                    for (let i = 0; i < chart.data.datasets.length; i++) {
                        console.log(chart.data.datasets[i]); // see what's inside the obj.
                        text.push('<li class="text-muted text-small">');
                        text.push(
                            '<span style="background-color:' +
                            chart.data.datasets[i].borderColor +
                            '">' +
                            "</span>"
                        );
                        text.push(chart.data.datasets[i].label);
                        text.push("</li>");
                    }
                    text.push("</ul></div>");
                    return text.join("");
                },

                elements: {
                    line: {
                        tension: 0.4,
                    },
                },
                tooltips: {
                    backgroundColor: "rgba(31, 59, 179, 1)",
                },
            };
            let transactionOverview = new Chart(transactionOverviewChart, {
                type: "bar",
                data: transactionOverviewData,
                options: transactionOverviewOptions,
            });
            document.getElementById("transaction-overview-legend").innerHTML =
                transactionOverview.generateLegend();
        }
        // End chart bar sale

        // Chart doughnut member
        if ($("#doughnutMemberChart").length) {
            let doughnutMemberChartCanvas = $("#doughnutMemberChart").get(0).getContext("2d");
            let doughnutPieData = {
                datasets: [{
                    data: [unmember, member],
                    backgroundColor: [
                        "#FDD0C7",
                        "#1F3BB3",
                    ],
                    borderColor: [
                        "#FDD0C7",
                        "#1F3BB3",
                    ],
                }],

                // These labels appear in the legend and in the tooltips when hovering different arcs
                labels: ['Unmember', 'Member'],
            };
            let doughnutPieOptions = {
                cutoutPercentage: 50,
                animationEasing: "easeOutBounce",
                animateRotate: true,
                animateScale: false,
                responsive: true,
                maintainAspectRatio: true,
                showScale: true,
                legend: false,
                legendCallback: function(chart) {
                    let text = [];
                    text.push('<div class="chartjs-legend"><ul class="justify-content-center">');
                    for (let i = 0; i < chart.data.datasets[0].data.length; i++) {
                        text.push('<li><span style="background-color:' + chart.data.datasets[0].backgroundColor[i] +
                            '">');
                        text.push('</span>');
                        if (chart.data.labels[i]) {
                            text.push(chart.data.labels[i]);
                        }
                        text.push('</li>');
                    }
                    text.push('</div></ul>');
                    return text.join("");
                },

                layout: {
                    padding: {
                        left: 0,
                        right: 0,
                        top: 0,
                        bottom: 0
                    }
                },
                tooltips: {
                    callbacks: {
                        title: function(tooltipItem, data) {
                            return data['labels'][tooltipItem[0]['index']];
                        },
                        label: function(tooltipItem, data) {
                            return data['datasets'][0]['data'][tooltipItem['index']];
                        }
                    },

                    backgroundColor: '#fff',
                    titleFontSize: 14,
                    titleFontColor: '#0B0F32',
                    bodyFontColor: '#737F8B',
                    bodyFontSize: 11,
                    displayColors: false
                }
            };
            let doughnutMemberChart = new Chart(doughnutMemberChartCanvas, {
                type: 'doughnut',
                data: doughnutPieData,
                options: doughnutPieOptions
            });
            document.getElementById('doughnut-chart-member').innerHTML = doughnutMemberChart.generateLegend();
        }
        // End chart doughnut member
    </script>
@endsection
