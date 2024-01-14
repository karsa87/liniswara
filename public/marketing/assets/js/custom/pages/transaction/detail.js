"use strict";

// Class definition
var KTDetailTransaction = function () {
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
                            result +=`<span class="badge badge-light-danger">Belum Terbayar</span>`;
                        } else if (row.status_payment == 2 || row.status_payment == "2") {
                            result +=`<span class="badge badge-light-primary">Lunas</span>`;
                        } else if (row.status_payment == 3 || row.status_payment == "3") {
                            result +=`<span class="badge badge-light-secondary">Terbayar Sebagian</span>`;
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

    return {
        // Public functions
        init: function () {
            table = document.querySelector('#kt_transaction_table');

            if (!table) {
                return;
            }

            initTransactionAgentsList();
            handleSearchDatatable();
        },
        refresh: function() {
            datatable.ajax.reload(null, false); // user paging is not reset on reload
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTDetailTransaction.init();
});
