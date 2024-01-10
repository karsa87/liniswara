"use strict";

var KTPreordersList = function () {
    // Define shared variables
    var table = document.getElementById('kt_table_orders');
    var datatable;
    var datePaidAt;

    // Private functions
    var initPreorderTable = function () {
        // Init flatpickr
        datePaidAt = $('#kt_ecommerce_edit_order_paid_at').flatpickr({
            altInput: true,
            altFormat: "d F, Y",
            dateFormat: "Y-m-d",
        });

        $('.select-filter-order').each(function () {
            $(this).select2({
                dropdownParent: $('#data-kt-menu-filter-order'),
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

        $('.select-export-order').each(function () {
            $(this).select2({
                dropdownParent: $('#data-kt-menu-export-order'),
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
                    d.search.customer_id = $('#filter-search_customer_id').val();
                    d.search.marketing_id = $('#filter-search_marketing_id').val();
                    d.search.status = $('#filter-search_status').val();
                    d.search.status_payment = $('#filter-search_status_payment').val();
                    d.search.method_payment = $('#filter-search_method_payment').val();
                }
            },
            columns: [
                { data: 'invoice_number' },
                { data: null },
                { data: null },
                { data: 'marketing' },
                { data: 'status' },
                { data: 'method_payment' },
                { data: 'total_amount' },
                { data: null },
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
                {
                    targets: -1,
                    data: null,
                    orderable: false,
                    className: 'text-end',
                    render: function (data, type, row) {
                        let result = `
                            <a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                <i class="ki-duotone ki-down fs-5 ms-1"></i>
                            </a>

                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-175px py-4" data-kt-menu="true">
                        `;

                        if ('order-print_address' in userPermissions) {
                            result += `
                                <div class="menu-item px-3">
                                    <a href="order/print/address/${row.id}" class="menu-link px-3">
                                        <i class="ki-duotone ki-printer fs-2 me-2 text-info">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                        </i>
                                        Cetak Alamat
                                    </a>
                                </div>
                            `;
                        }

                        if ('order-print_po' in userPermissions) {
                            result += `
                                <div class="menu-item px-3">
                                    <a href="order/print/purchase-order/${row.id}" class="menu-link px-3">
                                        <i class="ki-duotone ki-message-text fs-2 me-2 text-info">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                        </i>
                                        Purchase Order
                                    </a>
                                </div>
                            `;
                        }

                        if ('order-print_faktur' in userPermissions) {
                            result += `
                                <div class="menu-item px-3">
                                    <a href="order/print/faktur/${row.id}" class="menu-link px-3">
                                        <i class="ki-duotone ki-directbox-default fs-2 me-2 text-info">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                        Faktur
                                    </a>
                                </div>
                            `;
                        }

                        if ('order-print_sent_letter' in userPermissions) {
                            result += `
                                <div class="menu-item px-3">
                                    <a href="order/print/sent-document/${row.id}" class="menu-link px-3">
                                        <i class="ki-duotone ki-delivery fs-2 me-2 text-info">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                        </i>
                                        Surat Jalan
                                    </a>
                                </div>
                            `;
                        }

                        if (
                            'order-update_status' in userPermissions
                            || 'order-update_discount' in userPermissions
                        ) {
                            result += `<div class="separator mb-3 opacity-75"></div>`;
                            if ('order-update_discount' in userPermissions) {
                                result += `
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3" data-kt-orders-table-filter="update_discount" data-bs-toggle="modal" data-bs-target="#kt_modal_update_discount_order" data-id='${row.id}'>
                                            <i class="ki-duotone ki-discount fs-2 me-2 text-primary">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            Diskon
                                        </a>
                                    </div>
                                `;
                            }

                            if ('order-update_status' in userPermissions) {
                                result += `
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3" data-kt-orders-table-filter="update_status" data-bs-toggle="modal" data-bs-target="#kt_modal_update_status_order" data-id='${row.id}'>
                                            <i class="ki-duotone ki-message-edit fs-2 me-2 text-primary">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            Status
                                        </a>
                                    </div>
                                `;
                            }
                        }

                        if (
                            'order-track' in userPermissions
                            && row.shipping != undefined
                            && row.shipping != null
                            && row.shipping.resi != null
                        ) {
                            result += `
                                <div class="menu-item px-3">
                                    <a href="order/track/${row.id}/" class="menu-link px-3">
                                        <i class="ki-duotone ki-delivery-time fs-2 me-2 text-primary">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                        </i>
                                        Track
                                    </a>
                                </div>
                            `;
                        }

                        if ('order-delete' in userPermissions) {
                            result += `<div class="separator mb-3 opacity-75"></div>
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3" data-kt-orders-table-filter="delete_row" data-id='${row.id}'>
                                            <i class="ki-duotone ki-trash-square fs-2 me-2 text-danger">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                            </i>
                                            Delete
                                        </a>
                                    </div>
                            `;
                        }

                        result += `</div>`;
                        return result;
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

    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    var handleSearchDatatable = () => {
        const filterSearch = document.querySelector('[data-kt-order-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
        filterSearch.dispatchEvent(new KeyboardEvent('keyup',  {'key':''}));
    }

    // Filter Datatable
    var handleFilterDatatable = () => {
        // Select filter options
        const filterForm = document.querySelector('[data-kt-order-table-filter="form"]');
        const filterButton = filterForm.querySelector('[data-kt-order-table-filter="filter"]');
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
        const resetButton = document.querySelector('[data-kt-order-table-filter="reset"]');

        // Reset datatable
        resetButton.addEventListener('click', function () {
            // Select filter options
            const filterForm = document.querySelector('[data-kt-order-table-filter="form"]');
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
        const deleteButtons = table.querySelectorAll('[data-kt-orders-table-filter="delete_row"]');

        deleteButtons.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                // Select parent row
                const parent = e.target.closest('tr');

                // Select parent row
                const button = e.target.closest('a');

                // Get order name
                const orderName = parent.querySelectorAll('td')[1].innerText;

                // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                Swal.fire({
                    text: "Are you sure you want to delete " + orderName + "?",
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
                                    text: "You have deleted " + orderName + "!.",
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
                            text: orderName + " was not deleted.",
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

    // Update order
    var handleEditDiscountRows = () => {
        // Select all update buttons
        const updateButtons = table.querySelectorAll('[data-kt-orders-table-filter="update_discount"]');

        updateButtons.forEach(d => {
            // Update button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                // // Select parent row
                const button = e.target.closest('a');

                const element = document.getElementById('kt_modal_update_discount_order');
                const form = element.querySelector('#kt_modal_update_discount_order_form');

                KTApp.showPageLoading();

                // Check axios library docs: https://axios-http.com/docs/intro
                axios.get(table.dataset.urlEdit + '/' + button.getAttribute('data-id')).then(function (response) {
                    if (response && response.data) {
                        let order = response.data.data;
                        form.querySelector("input[name='order_id']").value = order.id;
                        form.querySelector("input[name='order_discount_price']").value = order.discount_price;
                        form.querySelector("input[name='order_discount_percentage']").value = order.discount_percentage;
                        form.querySelector("input[name='order_shipping_price']").value = order.shipping_price;
                        $('#form-select-discount-type').val(order.discount_type).trigger('change');
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

    // Update order
    var handleEditStatusRows = () => {
        // Select all update buttons
        const updateButtons = table.querySelectorAll('[data-kt-orders-table-filter="update_status"]');

        updateButtons.forEach(d => {
            // Update button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                // // Select parent row
                const button = e.target.closest('a');

                const element = document.getElementById('kt_modal_update_status_order');
                const form = element.querySelector('#kt_modal_update_status_order_form');

                KTApp.showPageLoading();

                // Check axios library docs: https://axios-http.com/docs/intro
                axios.get(table.dataset.urlEdit + '/' + button.getAttribute('data-id')).then(function (response) {
                    if (response && response.data) {
                        let order = response.data.data;
                        form.querySelector("input[name='order_id']").value = order.id;
                        $('#form-select-status-marketing').val(order.marketing).trigger('change');
                        $('#form-select-status-marketing').change();
                        $('#form-select-status-status').val(order.status).trigger('change');
                        $('#form-select-status-status').change();
                        $('#form-select-status-status_payment').val(order.status_payment).trigger('change');
                        $('#form-select-status-status_payment').change();
                        if (order.status_payment == 2) {
                            datePaidAt.setDate(new Date(order.paid_at));
                        }
                        $('#form-select-status-method_payment').val(order.method_payment).trigger('change');
                        $('#form-select-status-method_payment').change();

                        if (order.shipping) {
                            form.querySelector("input[name='order_resi']").value = order.shipping.resi;
                            // Set the value, creating a new option if necessary
                            if ($("#form-select-status-expedition").find("option[value=" + order.shipping.expedition.id + "]").length) {
                                $("#form-select-status-expedition").val(order.shipping.expedition.id).trigger("change");
                            } else {
                                // Create the DOM option that is pre-selected by default
                                var newState = new Option(order.shipping.expedition.name, order.shipping.expedition.id, true, true);
                                // Append it to the select
                                $("#form-select-status-expedition").append(newState).trigger('change');
                            }
                        }
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

    // Export Datatable
    var handleExportDatatable = () => {
        // Select filter options
        const filterForm = document.querySelector('[data-kt-order-table-export="form"]');
        if (filterForm == null) {
            return;
        }

        const filterButton = filterForm.querySelector('[data-kt-order-table-export="export"]');

        // Export datatable on submit
        filterButton.addEventListener('click', function (e) {
            // Prevent default button action
            e.preventDefault();
            const form = document.getElementById('kt_ecommerce_export_order_form');
            let param = new FormData(form);
            param.append('q', document.querySelector('[data-kt-order-table-filter="search"]').value);
            const objString = '?' + new URLSearchParams(Object.fromEntries(param)).toString();

            window.open(form.action + objString);
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
            handleSearchDatatable();
            handleExportDatatable();
        },
        refresh: function() {
            datatable.ajax.reload(null, false); // order paging is not reset on reload
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTPreordersList.init();
});
