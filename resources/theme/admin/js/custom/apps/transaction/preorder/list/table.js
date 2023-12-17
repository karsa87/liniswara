"use strict";

var KTPreordersList = function () {
    // Define shared variables
    var table = document.getElementById('kt_table_preorders');
    var datatable;

    // Private functions
    var initPreorderTable = function () {
        $('.select-filter-preorder').each(function () {
            $(this).select2({
                dropdownParent: $('#data-kt-menu-filter-preorder'),
                ajax: {
                    url: $(this).data('url'),
                    dataType: 'json',
                    data: function (params) {
                        return {
                            q: $.trim(params.term)
                        };
                    },
                    processResults: function(data) {
                        // Transforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: data.items
                        };
                    },
                    cache: true
                }
            });
        });

        // Init datatable --- more info on datatables: https://datatables.net/manual/
        datatable = $(table).DataTable({
            searchDelay: 1000,
            processing: true,
            serverSide: true,
            order: [[0, 'asc']],
            stateSave: true,
            ajax: {
                url: table.dataset.url,
                "data": function ( d ) {
                    d.search.branch_id = $('#filter-search_branch_id').val();
                    d.search.collector_id = $('#filter-search_collector_id').val();
                    d.search.customer_id = $('#filter-search_customer_id').val();
                    d.search.marketing_id = $('#filter-search_marketing_id').val();
                }
            },
            columns: [
                { data: 'date' },
                { data: 'invoice_number' },
                { data: null },
                { data: 'zone' },
                { data: 'total_amount' },
                { data: null },
                { data: 'marketing' },
                { data: 'status_order' },
                { data: 'status_payment' },
                { data: 'method_payment' },
                { data: null },
            ],
            columnDefs: [
                {
                    targets: 2,
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
                                result += `<br><span class="fs-7 text-muted">${row.customer_address.full_address}</span>`;
                            }
                        }

                        return result;
                    }
                },
                {
                    targets: 3,
                    render: function (data, type, row) {
                        if (row.zone == 2) {
                            return 'Zona 2';
                        }

                        return 'Zona 1';
                    }
                },
                {
                    targets: 4,
                    render: function (data, type, row) {
                        if (typeof row.total_amount == 'number') {
                            return row.total_amount.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                        }

                        return 0;
                    }
                },
                {
                    targets: 5,
                    orderable: false,
                    render: function (data, type, row) {
                        if (
                            row.shipping != null
                            && row.shipping.expedition.id != undefined
                            && row.shipping.expedition != null
                            && row.shipping.expedition.id != null
                        ) {
                            return row.shipping.expedition.name;
                        }

                        return '';
                    }
                },
                {
                    targets: 6,
                    orderable: false,
                    render: function (data, type, row) {
                        let result = '';
                        if (
                            row.created_by != null
                            && row.created_by.id != undefined
                            && row.created_by.id != null
                        ) {
                            result +=`<span class="badge badge-primary">${row.created_by.name}</span> / `;
                        }

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
                    targets: 7,
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
                        }

                        return result;
                    }
                },
                {
                    targets: 8,
                    render: function (data, type, row) {
                        let result = '';
                        if (row.status_payment == 1 || row.status_payment == "1") {
                            result +=`<span class="badge badge-danger">Belum Terbayar</span>`;
                        } else if (row.status_payment == 2 || row.status_payment == "2") {
                            result +=`<span class="badge badge-success">Lunas</span>`;
                        } else if (row.status_payment == 3 || row.status_payment == "3") {
                            result +=`<span class="badge badge-primary">Sebagian Terbayar</span>`;
                        }

                        return result;
                    }
                },
                {
                    targets: 9,
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
                    targets: -1,
                    data: null,
                    orderable: false,
                    className: 'text-end',
                    render: function (data, type, row) {
                        return `
                            <a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                <i class="ki-duotone ki-down fs-5 ms-1"></i>
                            </a>

                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <a href="preorder/detail/${row.id}/" class="menu-link px-3">Detail</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="preorder/edit/${row.id}/" class="menu-link px-3">Edit</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-preorders-table-filter="update_discount" data-bs-toggle="modal" data-bs-target="#kt_modal_update_discount_preorder" data-id='${row.id}'>Diskon</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-preorders-table-filter="update_status" data-bs-toggle="modal" data-bs-target="#kt_modal_update_status_preorder" data-id='${row.id}'>Status</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-preorders-table-filter="delete_row" data-id='${row.id}'>Delete</a>
                                </div>
                            </div>
                        `;
                    },
                },
            ],
        });

        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
        datatable.on('draw', function () {
            handleDeleteRows();
            handleEditDiscountRows();
            handleEditStatusRows();
            KTMenu.createInstances();
        });
    }

    // Filter Datatable
    var handleFilterDatatable = () => {
        // Select filter options
        const filterForm = document.querySelector('[data-kt-preorder-table-filter="form"]');
        const filterButton = filterForm.querySelector('[data-kt-preorder-table-filter="filter"]');
        const selectOptions = filterForm.querySelectorAll('select');

        // Filter datatable on submit
        filterButton.addEventListener('click', function () {
            // Filter datatable --- official docs reference: https://datatables.net/reference/api/search()
            datatable.draw();
        });
    }

    // Reset Filter
    var handleResetForm = () => {
        // Select reset button
        const resetButton = document.querySelector('[data-kt-preorder-table-filter="reset"]');

        // Reset datatable
        resetButton.addEventListener('click', function () {
            // Select filter options
            const filterForm = document.querySelector('[data-kt-preorder-table-filter="form"]');
            const selectOptions = filterForm.querySelectorAll('select');

            // Reset select2 values -- more info: https://select2.org/programmatic-control/add-select-clear-items
            selectOptions.forEach(select => {
                $(select).val('').trigger('change');
            });

            // Reset datatable --- official docs reference: https://datatables.net/reference/api/search()
            datatable.search('').draw();
        });
    }


    // Delete subscirption
    var handleDeleteRows = () => {
        // Select all delete buttons
        const deleteButtons = table.querySelectorAll('[data-kt-preorders-table-filter="delete_row"]');

        deleteButtons.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                // Select parent row
                const parent = e.target.closest('tr');

                // Select parent row
                const button = e.target.closest('a');

                // Get preorder name
                const preorderName = parent.querySelectorAll('td')[1].innerText;

                // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                Swal.fire({
                    text: "Are you sure you want to delete " + preorderName + "?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, delete!",
                    cancelButtonText: "No, cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    if (result.isConfirmed) {
                        axios.delete(table.dataset.urlDelete + '/' + button.getAttribute('data-id'), {})
                        .then(response => {
                            if (response) {
                                Swal.fire({
                                    text: "You have deleted " + preorderName + "!.",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn fw-bold btn-primary",
                                    }
                                }).then(function () {
                                    // Remove current row
                                    datatable.row($(parent)).remove().draw();
                                });
                            } else {
                                Swal.fire({
                                    text: "Sorry, looks like failed deleted, please try again.",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                });
                            }
                        })
                        .catch(error => {
                            let msg = "Sorry, failed delete, please try again.";
                            if (
                                error.response && error.response.data
                                && error.response.data != undefined
                                && error.response.data != null
                                && error.response.data != ''
                                && error.response.data.message
                            ) {
                                msg = error.response.data.message;
                            }

                            Swal.fire({
                                title: "Failed Delete",
                                text: msg,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                        });
                    } else if (result.dismiss === 'cancel') {
                        Swal.fire({
                            text: preorderName + " was not deleted.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        });
                    }
                });
            })
        });
    }

    // Update preorder
    var handleEditDiscountRows = () => {
        // Select all update buttons
        const updateButtons = table.querySelectorAll('[data-kt-preorders-table-filter="update_discount"]');

        updateButtons.forEach(d => {
            // Update button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                // // Select parent row
                const button = e.target.closest('a');

                const element = document.getElementById('kt_modal_update_discount_preorder');
                const form = element.querySelector('#kt_modal_update_discount_preorder_form');

                KTApp.showPageLoading();

                // Check axios library docs: https://axios-http.com/docs/intro
                axios.get(table.dataset.urlEdit + '/' + button.getAttribute('data-id')).then(function (response) {
                    if (response && response.data) {
                        let preorder = response.data.data;
                        form.querySelector("input[name='preorder_id']").value = preorder.id;
                        form.querySelector("input[name='preorder_discount_price']").value = preorder.discount_price;
                        form.querySelector("input[name='preorder_discount_percentage']").value = preorder.discount_percentage;
                        form.querySelector("input[name='preorder_shipping_price']").value = preorder.shipping_price;
                        $('#form-select-discount-type').val(preorder.discount_type).trigger('change');
                        $('#form-select-discount-type').change();
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
                    let msg = "Sorry, looks like there are some errors detected, please try again.";
                    if (
                        error.response && error.response.data
                        && error.response.data != undefined
                        && error.response.data != null
                        && error.response.data != ''
                        && error.response.data.message
                    ) {
                        msg = error.response.data.message;
                    }

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
            })
        });
    }

    // Update preorder
    var handleEditStatusRows = () => {
        // Select all update buttons
        const updateButtons = table.querySelectorAll('[data-kt-preorders-table-filter="update_status"]');

        updateButtons.forEach(d => {
            // Update button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                // // Select parent row
                const button = e.target.closest('a');

                const element = document.getElementById('kt_modal_update_status_preorder');
                const form = element.querySelector('#kt_modal_update_status_preorder_form');

                KTApp.showPageLoading();

                // Check axios library docs: https://axios-http.com/docs/intro
                axios.get(table.dataset.urlEdit + '/' + button.getAttribute('data-id')).then(function (response) {
                    if (response && response.data) {
                        let preorder = response.data.data;
                        form.querySelector("input[name='preorder_id']").value = preorder.id;
                        $('#form-select-status-marketing').val(preorder.marketing).trigger('change');
                        $('#form-select-status-marketing').change();
                        $('#form-select-status-status').val(preorder.status).trigger('change');
                        $('#form-select-status-status').change();
                        $('#form-select-status-status_payment').val(preorder.status_payment).trigger('change');
                        $('#form-select-status-status_payment').change();
                        $('#form-select-status-method_payment').val(preorder.method_payment).trigger('change');
                        $('#form-select-status-method_payment').change();
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
                    let msg = "Sorry, looks like there are some errors detected, please try again.";
                    if (
                        error.response && error.response.data
                        && error.response.data != undefined
                        && error.response.data != null
                        && error.response.data != ''
                        && error.response.data.message
                    ) {
                        msg = error.response.data.message;
                    }

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
            })
        });
    }

    return {
        // Public functions
        init: function () {
            if (!table) {
                return;
            }

            initPreorderTable();
            handleResetForm();
            handleDeleteRows();
            handleFilterDatatable();
            handleEditDiscountRows();
            handleEditStatusRows();
        },
        refresh: function() {
            datatable.ajax.reload(null, false); // preorder paging is not reset on reload
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTPreordersList.init();
});
