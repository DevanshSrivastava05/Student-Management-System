<?php include 'header.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<div id="tabs-container">
    <div id="tabs">
        <ul>
            <li><a href="#tab-1"><span>Total Male/Female</span></a></li>
            <li><a href="#tab-2"><span>Student Registrations</span></a></li>
            <li><a href="#tab-3"><span>Daywise Registration</span></a></li>
            <li><a href="#tab-4"><span>Daily Gender Registration</span></a></li>
        </ul>
        <div id="tab-1" class="tab-content">

            <canvas id="pie-chart-container-1"></canvas>
            <script>
                $(function() {
                    $.ajax({
                        url: "ajax_report.php?chart=1", // Adjust the URL to the correct one
                        type: "GET",
                        success: function(data) {
                            var ctx = document.getElementById("pie-chart-container-1").getContext("2d");
                            ctx.canvas.width = 800 * window.devicePixelRatio;
                            ctx.canvas.height = 600 * window.devicePixelRatio;
                            ctx.canvas.style.width = "800px";
                            ctx.canvas.style.height = "600px";
                            var pieChart = new Chart(ctx, {
                                type: "pie",
                                data: {
                                    labels: data.labels,
                                    datasets: [{
                                        data: data.data,
                                        backgroundColor: ["#3498db", "#e74c3c"],
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    title: {
                                        display: true,
                                        text: "Total Male/Female Students",
                                    },
                                }
                            });
                        }
                    });
                });
            </script>
        </div>
        <div id="tab-2" class="tab-content">
            <!-- Content loaded dynamically via AJAX -->
            <canvas id="bar-graph-container-2"></canvas>
            <script type="text/javascript">
                $(function() {
                    $.ajax({
                        url: "ajax_report.php?chart=2", // Adjust the URL to the correct one
                        type: "GET",
                        success: function(data) {
                            var ctx = document.getElementById("bar-graph-container-2").getContext("2d");
                            var barGraph = new Chart(ctx, {
                                type: "bar",
                                data: {
                                    labels: data.labels,
                                    datasets: [{
                                        label: "Student Registrations",
                                        data: data.data,
                                        backgroundColor: "#3498db",
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    title: {
                                        display: true,
                                        text: "Student Registrations (Last 15000 Days)",
                                    },
                                    scales: {
                                        xAxes: [{
                                            type: "time",
                                            time: {
                                                unit: "day",
                                                displayFormats: {
                                                    day: "MMM D"
                                                }
                                            },
                                            scaleLabel: {
                                                display: true,
                                                labelString: "Date"
                                            }
                                        }],
                                        yAxes: [{
                                            scaleLabel: {
                                                display: true,
                                                labelString: "Number of Registrations"
                                            }
                                        }]
                                    },
                                }
                            });
                        }
                    });
                });
            </script>
        </div>
        <div id="tab-3" class="tab-content">
            <canvas id="line-bar-graph-container-3"></canvas>
            <script type="text/javascript">
                $(function() {
                    $.ajax({
                        url: "ajax_report.php?chart=3",
                        type: "GET",
                        success: function(data) {
                            var ctx = document.getElementById("line-bar-graph-container-3").getContext("2d");
                            console.log("Data received for tab-3:", data);


                            var combinedGraph = new Chart(ctx, {
                                type: "bar",
                                data: {
                                    labels: data.labels,
                                    datasets: [{
                                        label: "Bar Graph Label",
                                        data: data.barData,
                                        backgroundColor: "rgba(52, 152, 219, 0.7)",
                                        borderColor: "rgba(52, 152, 219, 1)",
                                        borderWidth: 1,
                                    }, {
                                        label: "Line Graph Label",
                                        data: data.lineData,
                                        borderColor: "rgba(231, 76, 60, 1)",
                                        borderWidth: 2,
                                        fill: false,
                                        type: "line",
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    title: {
                                        display: true,
                                        text: "Daywise Student Registration",
                                        fontSize: 16,
                                    },
                                    scales: {
                                        xAxes: [{
                                            type: "time",
                                            time: {
                                                unit: "day",
                                                displayFormats: {
                                                    day: "MMM D"
                                                }
                                            },
                                            scaleLabel: {
                                                display: true,
                                                labelString: "Date",
                                                fontSize: 14,
                                            }
                                        }],
                                        yAxes: [{
                                            scaleLabel: {
                                                display: true,
                                                labelString: "Number of Registrations",
                                                fontSize: 14,
                                            }
                                        }]
                                    },
                                }
                            });

                            console.log("Tab-3 graph created successfully!");
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching data for tab-3:", error);
                        }
                    });
                });
            </script>
        </div>
        <div id="tab-4" class="tab-content">
            <!-- Content loaded dynamically via AJAX -->
            <canvas id="three-bar-graph-container-4"></canvas>
            <script type="text/javascript">
                $(function() {
                    $.ajax({
                        url: "ajax_report.php?chart=4", // Adjust the URL to the correct one
                        type: "GET",
                        success: function(data) {
                            var ctx = document.getElementById("three-bar-graph-container-4").getContext("2d");
                            var barGraph = new Chart(ctx, {
                                type: "bar",
                                data: {
                                    labels: data.labels,
                                    datasets: [{
                                        label: "Male",
                                        data: data.maleData,
                                        backgroundColor: "#3498db",
                                    }, {
                                        label: "Female",
                                        data: data.femaleData,
                                        backgroundColor: "#e74c3c",
                                    }, {
                                        label: "Total",
                                        data: data.totalData,
                                        backgroundColor: "#2ecc71",
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    title: {
                                        display: true,
                                        text: "Daily Gender Registration (Last 15000 Days)",
                                    },
                                    scales: {
                                        xAxes: [{
                                            type: "time",
                                            time: {
                                                unit: "day",
                                                displayFormats: {
                                                    day: "MMM D"
                                                }
                                            },
                                            scaleLabel: {
                                                display: true,
                                                labelString: "Date"
                                            }
                                        }],
                                        yAxes: [{
                                            scaleLabel: {
                                                display: true,
                                                labelString: "Number of Registrations"
                                            }
                                        }]
                                    },
                                }
                            });
                        }
                    });
                });
            </script>
        </div>
    </div>

    <script>
        $(function() {
            $("#tabs").tabs({
                beforeLoad: function(event, ui) {
                    ui.panel.html('<div class="loading">Loading...</div>');
                },
                load: function(event, ui) {

                }
            });
        });
    </script>
    <?php include 'footer.php'; ?>