"use strict";

var KTFollowTransactionsList = function () {
    // Define shared variables
    var table = document.getElementById('kt_table_follow_transactions');
    var datatable;

    // Private functions
    var initFollowTransactionTable = function () {
        // Init datatable --- more info on datatables: https://datatables.net/manual/
        datatable = $(table).DataTable({
            searchDelay: 1000,
            processing: true,
            serverSide: true,
            order: [[0, 'desc']],
            ajax: {
                url: table.dataset.url,
                "data": function ( d ) {
                    d.search.customer_id = $('#filter-search_customer_id').val();
                    d.search.marketing_id = $('#filter-search_marketing_id').val();
                    d.search.status_payment = $('#filter-search_status_payment').val();
                }
            },
            columns: [
                { data: 'id' },
                { data: null },
                { data: null },
                { data: 'marketing' },
                { data: 'status' },
                { data: 'method_payment' },
                { data: 'total_amount' },
            ],
            columnDefs: [
                {
                    targets: 0,
                    render: function (data, type, row) {
                        let result = `<a href="order/detail/${row.id}/" class="fw-bold text-gray-600 text-hover-primary">${row.invoice_number}</a><br><span class="fs-7 text-muted">${row.date}</span>`;

                        if (
                            row.created_by != null
                            && row.created_by.id != undefined
                            && row.created_by.id != null
                        ) {
                            result +=`<br><span class="badge badge-success">Dibuat ${row.created_by.name}`;
                            if (
                                row.updated_by != null
                                && row.updated_by.id != undefined
                                && row.updated_by.id != null
                            ) {
                                result +=` & Diedit ${row.updated_by.name}</span>`;
                            }
                        }

                        return result;
                    }
                },
                {
                    targets: 1,
                    orderable: false,
                    render: function (data, type, row) {
                        var result = '';
                        if (
                            row.customer != null
                            && row.customer.id != undefined
                            && row.customer.id != null
                        ) {
                            result = `<span class="fw-bold text-gray-600 text-hover-primary">${row.customer.name}</span>`;

                            if (
                                row.customer_address != null
                                && row.customer_address.id != undefined
                                && row.customer_address.id != null
                            ) {
                                result += `<br><span class="fs-7 text-muted">${row.customer_address.summary_address}</span>`;
                            }
                        }

                        result += `<br><span class="fs-7 text-muted">${row.notes}</span>`;

                        return result;
                    }
                },
                {
                    targets: 2,
                    orderable: false,
                    render: function (data, type, row) {
                        if (
                            row.shipping != null
                            && row.shipping.expedition.id != undefined
                            && row.shipping.expedition != null
                            && row.shipping.expedition.id != null
                        ) {
                            return `<span class="fw-bold text-gray-600 text-hover-primary">${row.shipping.expedition.name}</span><br><span class="badge badge-success">${row.shipping.resi}</span>`;
                        }

                        return '';
                    }
                },
                {
                    targets: 3,
                    orderable: false,
                    render: function (data, type, row) {
                        let result = '';
                        if (row.marketing == 1 || row.marketing == "1") {
                            result +=`<span class="badge badge-info">Tim A</span>`;
                        } else if (row.marketing == 2 || row.marketing == "2") {
                            result +=`<span class="badge badge-info">Tim B</span>`;
                        } else if (row.marketing == 3 || row.marketing == "3") {
                            result +=`<span class="badge badge-info">Retail</span>`;
                        } else if (row.marketing == 4 || row.marketing == "4") {
                            result +=`<span class="badge badge-info">Penulis</span>`;
                        }

                        return result;
                    }
                },
                {
                    targets: 4,
                    render: function (data, type, row) {
                        let result = '';
                        if (row.status == 1 || row.status == "1") {
                            result +=`<span class="badge badge-warning">Validasi Admin</span>`;
                        } else if (row.status == 2 || row.status == "2") {
                            result +=`<span class="badge badge-primary">Proses</span>`;
                        } else if (row.status == 3 || row.status == "3") {
                            result +=`<span class="badge badge-info">Kirim</span>`;
                        } else if (row.status == 4 || row.status == "4") {
                            result +=`<span class="badge badge-success">Selesai</span>`;
                        } else if (row.status == 5 || row.status == "5") {
                            result +=`<span class="badge badge-info">Packing</span>`;
                        }

                        let zone = 'Zona 1';
                        if (row.zone == 2) {
                            zone = 'Zona 2';
                        } else if (row.zone == 3) {
                            zone = 'Zona 3';
                        } else if (row.zone == 4) {
                            zone = 'Zona 4';
                        } else if (row.zone == 5) {
                            zone = 'Zona 5';
                        } else if (row.zone == 6) {
                            zone = 'Zona 6';
                        }

                        return `${result}<br><span class="badge badge-dark">${zone}</span>`;
                    }
                },
                {
                    targets: 5,
                    render: function (data, type, row) {
                        let result = '';
                        if (row.method_payment == 1 || row.method_payment == "1") {
                            result +=`<span class="badge badge-success">Cash</span>`;
                        } else if (row.method_payment == 2 || row.method_payment == "2") {
                            result +=`<span class="badge badge-danger">Hutang</span>`;
                        } else if (row.method_payment == 3 || row.method_payment == "3") {
                            result +=`<span class="badge badge-info">Freelance</span>`;
                        }

                        return result;
                    }
                },
                {
                    targets: 6,
                    render: function (data, type, row) {
                        let total_amount = 0;
                        if (typeof row.total_amount == 'number') {
                            total_amount = row.total_amount.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                        }

                        let result = '';
                        if (row.status_payment == 1 || row.status_payment == "1") {
                            result +=`<span class="badge badge-danger">Belum Terbayar</span>`;
                        } else if (row.status_payment == 2 || row.status_payment == "2") {
                            result +=`<span class="badge badge-success">Lunas : ${row.paid_at}</span>`;
                        } else if (row.status_payment == 3 || row.status_payment == "3") {
                            result +=`<span class="badge badge-primary">Sebagian Terbayar</span>`;
                        }

                        return `<span class="fw-bold text-gray-600 text-hover-primary">${total_amount}</span><br>${result}`;
                    }
                },
            ],
        });

        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
        datatable.on('draw', function () {
            KTMenu.createInstances();
        });
    }

    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    var handleSearchDatatable = () => {
        const filterSearch = document.querySelector('[data-kt-follow-transaction-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Filter Datatable
    var handleFilterDatatable = () => {
        // Select filter options
        const filterForm = document.querySelector('[data-kt-follow-transaction-table-filter="form"]');
        const filterButton = filterForm.querySelector('[data-kt-follow-transaction-table-filter="filter"]');


        // Filter datatable on submit
        filterButton.addEventListener('click', function () {
            // Filter datatable --- official docs reference: https://datatables.net/reference/api/search()
            datatable.draw();
        });
    }

    // Reset Filter
    var handleResetForm = () => {
        // Select reset button
        const resetButton = document.querySelector('[data-kt-follow-transaction-table-filter="reset"]');

        // Reset datatable
        resetButton.addEventListener('click', function () {
            // Select filter options
            const filterForm = document.querySelector('[data-kt-follow-transaction-table-filter="form"]');
            const selectOptions = filterForm.querySelectorAll('select');

            // Reset select2 values -- more info: https://select2.org/programmatic-control/add-select-clear-items
            selectOptions.forEach(select => {
                $(select).val('').trigger('change');
            });

            // Reset datatable --- official docs reference: https://datatables.net/reference/api/search()
            datatable.search('').draw();
        });
    }

    return {
        // Public functions
        init: function () {
            if (!table) {
                return;
            }

            initFollowTransactionTable();
            handleSearchDatatable();
            handleFilterDatatable();
            handleResetForm();
        },
        refresh: function() {
            datatable.ajax.reload(null, false); // customer paging is not reset on reload
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTFollowTransactionsList.init();
});
