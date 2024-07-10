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
            order: [[1, 'desc']],
            stateSave: true,
            ajax: {
                url: table.dataset.url,
                "data": function ( d ) {
                    d.search.month = $('#filter-month').val();
                    d.search.area_id = $('#filter-area-id').val();
                }
            },
            columns: [
                {
                    className: 'dt-control',
                    orderable: false,
                    data: null,
                    defaultContent: ''
                },
                { data: 'date' },
                { data: 'invoice_number' },
                { data: 'total_amount' },
            ],
            columnDefs: [
                {
                    targets: 3,
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

        // Add event listener for opening and closing details
        datatable.on('click', 'td.dt-control', function (e) {
            let tr = e.target.closest('tr');
            let row = datatable.row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
            }
            else {
                // Open this row
                row.child(format(e, row.data())).show();
            }
        });
    }

    // Formatting function for row details - modify as you need
    var format = (element, data) => {
        var urlDetailOrder = element.target.closest('table').dataset.detailOrderUrl.replace('REPLACE', data.id);

        var result = '<h6 class="text-center">Target dan Pencapaian per Wilayah</h6>';
        result += `<table class="table align-middle table-row-dashed fs-6 gy-5" id="table-transaction-order-agent-${data.id}">
            <thead>
                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-75px text-center">Tanggal</th>
                    <th class="min-w-125px">No Faktur</th>
                    <th class="min-w-100px">Status Order</th>
                    <th class="min-w-100px">Status Pembayaran</th>
                    <th class="min-w-125px">Nominal</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>`;

        KTApp.showPageLoading();
        // Init chart
        axios.get(urlDetailOrder).then(function (response) {
            if (response && response.data) {
                response.data.orders.forEach(dataOrder => {
                    let statusOrder = '';
                    if (dataOrder.status == 1 || dataOrder.status == "1") {
                        statusOrder =`<span class="badge badge-light-warning fs-base">Validasi Admin</span>`;
                    } else if (dataOrder.status == 2 || dataOrder.status == "2") {
                        statusOrder =`<span class="badge badge-light-primary fs-base">Proses</span>`;
                    } else if (dataOrder.status == 3 || dataOrder.status == "3") {
                        statusOrder =`<span class="badge badge-light-info fs-base">Kirim</span>`;
                    } else if (dataOrder.status == 4 || dataOrder.status == "4") {
                        statusOrder =`<span class="badge badge-light-success fs-base">Selesai</span>`;
                    }

                    let statusPayment = '';
                    if (dataOrder.status_payment == 1 || dataOrder.status_payment == "1") {
                        statusPayment +=`<span class="badge badge-light-danger fs-base">Belum Terbayar</span>`;
                    } else if (dataOrder.status_payment == 2 || dataOrder.status_payment == "2") {
                        statusPayment +=`<span class="badge badge-light-primary fs-base">Lunas</span>`;
                    } else if (dataOrder.status_payment == 3 || dataOrder.status_payment == "3") {
                        statusPayment +=`<span class="badge badge-light-secondary fs-base">Terbayar Sebagian</span>`;
                    }

                    var total_amount = dataOrder.total_amount.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });

                    var resultTr = `<tr>
                        <td>${dataOrder.date}</td>
                        <td>${dataOrder.invoice_number}</td>
                        <td>${statusOrder}</td>
                        <td>${statusPayment}</td>
                        <td>${total_amount}</td>
                    </tr>`;

                    $(`#table-transaction-order-agent-${data.id} tbody`).append(resultTr);
                });
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
            console.log(error);
            let msg = "Gagal load data.";

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
            KTApp.hidePageLoading();
        });

        return result;
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
