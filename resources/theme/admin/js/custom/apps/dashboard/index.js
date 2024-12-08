"use strict";

// Class definition
var KTWidgets = function () {
    var initChartsWidgetZone = function() {
        var element = document.getElementById("kt_charts_sales_zone");

        if ( !element ) {
            return;
        }

        var chart = {
            self: null,
            rendered: false
        };

        var initChart = function(zone_1, zone_2, zone_3, zone_4, zone_5, zone_6) {
            var height = parseInt(KTUtil.css(element, 'height'));
            var labelColor = KTUtil.getCssVariableValue('--bs-gray-500');
            var borderColor = KTUtil.getCssVariableValue('--bs-gray-200');
            var baseColor = KTUtil.getCssVariableValue('--bs-primary');
            var secondaryColor = KTUtil.getCssVariableValue('--bs-gray-300');
            var thirdColor = KTUtil.getCssVariableValue('--bs-success');
            var fourColor = KTUtil.getCssVariableValue('--bs-info');
            var fiveColor = KTUtil.getCssVariableValue('--bs-warning');
            var sixColor = KTUtil.getCssVariableValue('--bs-danger');

            var options = {
                series: [{
                    name: 'Zona 1',
                    data: zone_1
                }, {
                    name: 'Zona 2',
                    data: zone_2
                }, {
                    name: 'Zona 3',
                    data: zone_3
                }, {
                    name: 'Zona 4',
                    data: zone_4
                }, {
                    name: 'Zona 5',
                    data: zone_5
                }, {
                    name: 'Zona 6',
                    data: zone_6
                }],
                chart: {
                    fontFamily: 'inherit',
                    type: 'bar',
                    height: height,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: ['80%'],
                        borderRadius: [6]
                    },
                },
                legend: {
                    show: true
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 0.1,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Des'],
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function(value) {
                            var val = Math.abs(value);

                            if (val >= 1000 && val < 1000000) {
                                val = (val / 1000).toFixed(0) + 'Rb'
                            } else if (val >= 1000000 && val < 1000000000) {
                                val = (val / 1000000).toFixed(0) + 'Jt'
                            } else if (val >= 1000000000) {
                                val = (val / 1000000000).toFixed(0) + 'M'
                            } else {
                                val = val;
                            }

                            return val;
                        },
                        style: {
                            colors: labelColor,
                            fontSize: '12px'
                        }
                    }
                },
                fill: {
                    opacity: 5
                },
                states: {
                    normal: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    }
                },
                tooltip: {
                    style: {
                        fontSize: '12px'
                    },
                    y: {
                        formatter: function (val) {
                            return (val).toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                        }
                    }
                },
                colors: [baseColor, secondaryColor, thirdColor, fourColor, fiveColor, sixColor],
                grid: {
                    borderColor: borderColor,
                    strokeDashArray: 4,
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                }
            };

            chart.self = new ApexCharts(element, options);
            chart.self.render();
            chart.rendered = true;
        }

        // Init chart
        axios.get(element.getAttribute('data-url')).then(function (response) {
            if (response && response.data) {
                let data = response.data;
                initChart(data.zone_1, data.zone_2, data.zone_3, data.zone_4, data.zone_5, data.zone_6);
            } else {
                // Show error popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                Swal.fire({
                    text: "Sorry, looks like there are some errors detected, please try again.",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
            }
        }).catch(function (error) {
            let msg = "Gagal load data zona.";

            Swal.fire({
                title: "Failed load data",
                text: msg,
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
        }).then(() => {
            // Hide loading indication
            document.getElementById('widget-zone-loader').classList.add('d-none');
        });

        // Update chart on theme mode change
        KTThemeMode.on("kt.thememode.change", function() {
            if (chart.rendered) {
                chart.self.destroy();
            }

            initChart();
        });
    }

    var initChartsWidgetSales = function() {
        var element = document.getElementById("kt_charts_sales");

        if ( !element ) {
            return;
        }

        var chart = {
            self: null,
            rendered: false
        };

        var initChart = function(team_a, team_b, retail, writing) {
            var height = parseInt(KTUtil.css(element, 'height'));
            var labelColor = KTUtil.getCssVariableValue('--bs-gray-500');
            var borderColor = KTUtil.getCssVariableValue('--bs-gray-200');
            var baseColor = KTUtil.getCssVariableValue('--bs-primary');
            var secondaryColor = KTUtil.getCssVariableValue('--bs-gray-300');
            var thirdColor = KTUtil.getCssVariableValue('--bs-success');
            var fourColor = KTUtil.getCssVariableValue('--bs-warning');

            var options = {
                series: [{
                    name: 'Tim A',
                    data: team_a
                }, {
                    name: 'Tim B',
                    data: team_b
                }, {
                    name: 'Retail',
                    data: retail
                }, {
                    name: 'Penulis',
                    data: writing
                }],
                chart: {
                    fontFamily: 'inherit',
                    type: 'bar',
                    height: height,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: ['80%'],
                        borderRadius: [6]
                    },
                },
                legend: {
                    show: true
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 0.1,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Des'],
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function(value) {
                            var val = Math.abs(value);

                            if (val >= 1000 && val < 1000000) {
                                val = (val / 1000).toFixed(0) + 'Rb'
                            } else if (val >= 1000000 && val < 1000000000) {
                                val = (val / 1000000).toFixed(0) + 'Jt'
                            } else if (val >= 1000000000) {
                                val = (val / 1000000000).toFixed(0) + 'M'
                            } else {
                                val = val;
                            }

                            return val;
                        },
                        style: {
                            colors: labelColor,
                            fontSize: '12px'
                        }
                    }
                },
                fill: {
                    opacity: 5
                },
                states: {
                    normal: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    }
                },
                tooltip: {
                    style: {
                        fontSize: '12px'
                    },
                    y: {
                        formatter: function (val) {
                            return (val).toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                        }
                    }
                },
                colors: [baseColor, secondaryColor, thirdColor, fourColor],
                grid: {
                    borderColor: borderColor,
                    strokeDashArray: 4,
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                }
            };

            chart.self = new ApexCharts(element, options);
            chart.self.render();
            chart.rendered = true;
        }

        // Init chart
        axios.get(element.getAttribute('data-url')).then(function (response) {
            if (response && response.data) {
                let data = response.data;
                initChart(data.team_a, data.team_b, data.retail, data.writing);
            } else {
                // Show error popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                Swal.fire({
                    text: "Sorry, looks like there are some errors detected, please try again.",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
            }
        }).catch(function (error) {
            let msg = "Gagal load data tim marketing.";

            Swal.fire({
                title: "Failed load data",
                text: msg,
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
        }).then(() => {
            // Hide loading indication
            document.getElementById('widget-sales-loader').classList.add('d-none');
        });

        // Update chart on theme mode change
        KTThemeMode.on("kt.thememode.change", function() {
            if (chart.rendered) {
                chart.self.destroy();
            }

            initChart();
        });
    }

    // Private methods
    var loadChartPreorder = function() {
        var initChart = function(
            dp,
            paid,
            not_paid
        ) {
            var chart = {
                self: null,
                rendered: false
            };

            var element = document.getElementById('kt_card_widget_4_chart');

            if (!element) {
                return;
            }

            if ( chart.rendered === true && element.classList.contains("initialized") ) {
                return;
            }

            var height = parseInt(KTUtil.css(element, 'height'));
            var successColor = KTUtil.getCssVariableValue('--bs-' + 'success');
            var dangerColor = KTUtil.getCssVariableValue('--bs-' + 'danger');

            var options = {
                series: [paid, not_paid, dp],
                chart: {
                    fontFamily: 'inherit',
                    height: height,
                    type: 'pie',
                    sparkline: {
                        enabled: true,
                    }
                },
                colors: [successColor, dangerColor, '#E4E6EF'],
                stroke: {
                    lineCap: "round",
                },
                labels: ["Lunas", "Belum Lunas", "Sebagian Terbayar"],
                tooltip: {
                    custom: function({series, seriesIndex, dataPointIndex, w}) {
                        return w.config.labels[seriesIndex] + ' : '+ (series[seriesIndex]).toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                    }
                }
            };

            chart.self = new ApexCharts(element, options);
            chart.self.render();
            chart.rendered = true;

            document.getElementById('widget-preorder-header').classList.remove('d-none');
            document.getElementById('widget-preorder-body').classList.remove('d-none');
            document.getElementById('widget-preorder-loader').classList.add('d-none');
        }

        var el = document.getElementById('kt_card_widget_4_chart');
        // Check axios library docs: https://axios-http.com/docs/intro
        axios.get(el.getAttribute('data-url')).then(function (response) {
            if (response && response.data) {
                let data = response.data;

                // Count
                document.getElementById('widget-preorder-count-paid').innerHTML = data.count.paid.toLocaleString('in-ID');
                document.getElementById('widget-preorder-count-dp').innerHTML = data.count.dp.toLocaleString('in-ID');
                document.getElementById('widget-preorder-count-not_paid').innerHTML = data.count.not_paid.toLocaleString('in-ID');

                document.getElementById('widget-preorder-total-count').innerHTML = (data.count.paid + data.count.dp + data.count.not_paid).toLocaleString('in-ID');

                document.getElementById('widget-preorder-detail-header').classList.remove('d-none');
                document.getElementById('widget-preorder-detail-body').classList.remove('d-none');
                document.getElementById('widget-preorder-detail-loader').classList.add('d-none');

                // Total
                document.getElementById('widget-preorder-total-paid').innerHTML = data.total.paid.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                document.getElementById('widget-preorder-total-dp').innerHTML = data.total.dp.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                document.getElementById('widget-preorder-total-not_paid').innerHTML = data.total.not_paid.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });

                document.getElementById('widget-preorder-total').innerHTML = (data.total.paid + data.total.dp + data.total.not_paid).toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });

                initChart(data.total.paid, data.total.paid, data.total.not_paid);
            } else {
                // Show error popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                Swal.fire({
                    text: "Sorry, looks like there are some errors detected, please try again.",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
            }
        }).catch(function (error) {
            let msg = "Gagal load data preorder.";

            Swal.fire({
                title: "Failed load data",
                text: msg,
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
        }).then(() => {
            // Hide loading indication
        });
    }

    // Private methods
    var loadChartProduct = function() {
        var el = document.getElementById('widget-product');
        // Check axios library docs: https://axios-http.com/docs/intro
        axios.get(el.getAttribute('data-url')).then(function (response) {
            if (response && response.data) {
                let data = response.data;
                // Count
                document.getElementById('widget-product-count-ready').innerHTML = data.count.ready.toLocaleString('in-ID');
                document.getElementById('widget-product-count-empty').innerHTML = data.count.empty.toLocaleString('in-ID');
                document.getElementById('widget-product-count-limited').innerHTML = data.count.limited.toLocaleString('in-ID');
                document.getElementById('widget-product-count-printed').innerHTML = data.count.printed.toLocaleString('in-ID');

                document.getElementById('widget-product-total-count').innerHTML = data.total_count.toLocaleString('in-ID');

                document.getElementById('widget-product-header').classList.remove('d-none');
                document.getElementById('widget-product-body').classList.remove('d-none');
                document.getElementById('widget-product-loader').classList.add('d-none');
            } else {
                // Show error popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                Swal.fire({
                    text: "Sorry, looks like there are some errors detected, please try again.",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
            }
        }).catch(function (error) {
            let msg = "Gagal load data preorder.";

            Swal.fire({
                title: "Failed load data",
                text: msg,
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
        }).then(() => {
            // Hide loading indication
        });
    }

    // Private methods
    var loadChartOrder = function() {
        var initChart = function(
            dp,
            paid,
            not_paid
        ) {
            var chart = {
                self: null,
                rendered: false
            };

            var element = document.getElementById('kt_card_widget_order');

            if (!element) {
                return;
            }

            if ( chart.rendered === true && element.classList.contains("initialized") ) {
                return;
            }

            var height = parseInt(KTUtil.css(element, 'height'));
            var successColor = KTUtil.getCssVariableValue('--bs-' + 'success');
            var dangerColor = KTUtil.getCssVariableValue('--bs-' + 'danger');

            var options = {
                series: [paid, not_paid, dp],
                chart: {
                    fontFamily: 'inherit',
                    height: height,
                    type: 'pie',
                    sparkline: {
                        enabled: true,
                    }
                },
                colors: [successColor, dangerColor, '#E4E6EF'],
                stroke: {
                    lineCap: "round",
                },
                labels: ["Lunas", "Belum Lunas", "Sebagian Terbayar"],
                tooltip: {
                    custom: function({series, seriesIndex, dataPointIndex, w}) {
                        return w.config.labels[seriesIndex] + ' : '+ (series[seriesIndex]).toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                    }
                }
            };

            chart.self = new ApexCharts(element, options);
            chart.self.render();
            chart.rendered = true;

            document.getElementById('widget-order-header').classList.remove('d-none');
            document.getElementById('widget-order-body').classList.remove('d-none');
            document.getElementById('widget-order-loader').classList.add('d-none');
        }

        var el = document.getElementById('kt_card_widget_order');
        // Check axios library docs: https://axios-http.com/docs/intro
        axios.get(el.getAttribute('data-url')).then(function (response) {
            if (response && response.data) {
                let data = response.data;

                // Count
                document.getElementById('widget-order-count-paid').innerHTML = data.count.paid.toLocaleString('in-ID');
                document.getElementById('widget-order-count-dp').innerHTML = data.count.dp.toLocaleString('in-ID');
                document.getElementById('widget-order-count-not_paid').innerHTML = data.count.not_paid.toLocaleString('in-ID');

                document.getElementById('widget-order-total-count').innerHTML = (data.count.paid + data.count.dp + data.count.not_paid).toLocaleString('in-ID');

                document.getElementById('widget-order-detail-header').classList.remove('d-none');
                document.getElementById('widget-order-detail-body').classList.remove('d-none');
                document.getElementById('widget-order-detail-loader').classList.add('d-none');

                document.getElementById('widget-order-total-paid').innerHTML = data.total.paid.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                document.getElementById('widget-order-total-dp').innerHTML = data.total.dp.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                document.getElementById('widget-order-total-not_paid').innerHTML = data.total.not_paid.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });

                document.getElementById('widget-order-total').innerHTML = (data.total.paid + data.total.dp + data.total.not_paid).toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });

                initChart(data.total.dp, data.total.paid, data.total.not_paid);
            } else {
                // Show error popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                Swal.fire({
                    text: "Sorry, looks like there are some errors detected, please try again.",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
            }
        }).catch(function (error) {
            let msg = "Gagal load data pesanan.";

            Swal.fire({
                title: "Failed load data",
                text: msg,
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
        }).then(() => {
            // Hide loading indication
        });
    }

    // Public methods
    return {
        init: function () {
            // Charts widgets
            initChartsWidgetZone();
            initChartsWidgetSales();
            loadChartPreorder();
            loadChartOrder();
            loadChartProduct();
        }
    }
}();

// Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = KTWidgets;
}

// On document ready
KTUtil.onDOMContentLoaded(function() {
    KTWidgets.init();
});
