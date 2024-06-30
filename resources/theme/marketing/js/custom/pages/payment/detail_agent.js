"use strict";

// Class definition
var KTDetailAgent = function () {
    // Shared variables
    var datatable;
    var table;

    // Init add schedule modal
    var initTransactionAgentsList = () => {
        // Init datatable --- more info on datatables: https://datatables.net/manual/
        datatable = $(table).DataTable({
            searchDelay: 1000,
            processing: true,
            serverSide: true,
            order: [[0, 'desc']],
            stateSave: true,
            ajax: {
                url: table.dataset.url,
                "data": function ( d ) {
                    d.search.month = $('#filter-month').val();
                    d.search.area_id = $('#filter-area-id').val();
                }
            },
            columns: [
                { data: 'date' },
                { data: 'invoice_number' },
                { data: 'status' },
                { data: 'status_payment' },
                { data: 'total_amount' },
            ],
            columnDefs: [
                {
                    targets: 2,
                    render: function (data, type, row) {
                        let result = '';
                        if (row.status == 1 || row.status == "1") {
                            result +=`<span class="badge badge-light-warning fs-base">Validasi Admin</span>`;
                        } else if (row.status == 2 || row.status == "2") {
                            result +=`<span class="badge badge-light-primary fs-base">Proses</span>`;
                        } else if (row.status == 3 || row.status == "3") {
                            result +=`<span class="badge badge-light-info fs-base">Kirim</span>`;
                        } else if (row.status == 4 || row.status == "4") {
                            result +=`<span class="badge badge-light-success fs-base">Selesai</span>`;
                        }

                        return result;
                    }
                },
                {
                    targets: 3,
                    render: function (data, type, row) {
                        let result = '';
                        if (row.status_payment == 1 || row.status_payment == "1") {
                            result +=`<span class="badge badge-light-danger fs-base">Belum Terbayar</span>`;
                        } else if (row.status_payment == 2 || row.status_payment == "2") {
                            result +=`<span class="badge badge-light-primary fs-base">Lunas</span>`;
                        } else if (row.status_payment == 3 || row.status_payment == "3") {
                            result +=`<span class="badge badge-light-secondary fs-base">Terbayar Sebagian</span>`;
                        }

                        return result;
                    }
                },
                {
                    targets: 4,
                    render: function (data, type, row) {
                        let total_amount = 0;
                        if (typeof row.total_amount == 'number') {
                            total_amount = row.total_amount.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                        }

                        return total_amount;
                    }
                },
            ],
        });

        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
        datatable.on('draw', function () {
            handleSearchDatatable();
            KTMenu.createInstances();
        });
    }

    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    var handleSearchDatatable = () => {
        $('#filter-month').change(function () {
            datatable.ajax.reload(null, false); // user paging is not reset on reload
        });
    }

    var initChartsWidgetTransactionAll = function() {
        var element = document.getElementById("kt_charts_transaction_all");

        if ( !element ) {
            return;
        }

        var chart = {
            self: null,
            rendered: false
        };

        var  initChart = function(transaction) {
            var height = parseInt(KTUtil.css(element, 'height'));
            var labelColor = KTUtil.getCssVariableValue('--bs-gray-500');
            var borderColor = KTUtil.getCssVariableValue('--bs-gray-200');
            var baseColor = KTUtil.getCssVariableValue('--bs-primary');
            var secondaryColor = KTUtil.getCssVariableValue('--bs-gray-300');

            var options = {
                series: [{
                    name: 'Transaction',
                    data: transaction
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
                colors: [baseColor, secondaryColor],
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
                initChart(data.transactions);
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
            document.getElementById('widget-transaction-all-loader').classList.add('d-none');
        });

        // Update chart on theme mode change
        KTThemeMode.on("kt.thememode.change", function() {
            if (chart.rendered) {
                chart.self.destroy();
            }

            initChart();
        });
    }

    var handleFilterArea = function() {
        var elements = document.querySelectorAll('[data-filter="area_id"]');
        elements.forEach(d => {
            // Update button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                $('#filter-area-id').val(d.dataset.areaId);
                datatable.ajax.reload(null, false); // user paging is not reset on reload
            });
        });
    }

    return {
        // Public functions
        init: function () {
            table = document.querySelector('#kt_transaction_agent_table');

            if (!table) {
                return;
            }

            initTransactionAgentsList();
            handleSearchDatatable();
            initChartsWidgetTransactionAll();
            handleFilterArea();
        },
        refresh: function() {
            datatable.ajax.reload(null, false); // user paging is not reset on reload
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTDetailAgent.init();
});
