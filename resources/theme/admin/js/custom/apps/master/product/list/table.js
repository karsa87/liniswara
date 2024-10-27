"use strict";

var KTSuppliersList = function () {
    // Define shared variables
    var table = document.getElementById('kt_table_products');
    var datatable;

    // Private functions
    var initSupplierTable = function () {
        $('[name="search_category_id"]').select2({
            dropdownParent: $('#data-kt-menu-filter-product'),
            minimumInputLength: 2,
            ajax: {
                url: $('[name="search_category_id"]').data('url'),
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

        $('.select-export-product').each(function () {
            $(this).select2({
                dropdownParent: $('#data-kt-menu-export-product'),
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
            order: [[1, 'asc']],
            stateSave: true,
            ajax: {
                url: table.dataset.url,
                "data": function ( d ) {
                    // Select filter options
                    const filterForm = document.querySelector('[data-kt-product-table-filter="form"]');
                    const selectOptions = filterForm.querySelectorAll('select');

                    // Filter datatable on submit
                    var filterString = '';

                    // Get filter values
                    selectOptions.forEach((item, index) => {
                        if (item.value && item.value !== '') {
                            if (index !== 0) {
                                filterString += ' ';
                            }

                            // Build filter value options
                            filterString += item.value;
                        }
                    });
                    d.search.category_id = filterString;
                }
            },
            columns: [
                { data: null },
                { data: 'name' },
                { data: 'code' },
                { data: 'stock' },
                { data: 'total_sale', orderable: false },
                { data: 'category', orderable: false },
                { data: null },
            ],
            columnDefs: [
                {
                    targets: 0,
                    orderable: false,
                    render: function (data, type, row) {
                        if (row.thumbnail != null && row.thumbnail.full_url != null && row.thumbnail.full_url != '' && row.thumbnail.full_url != undefined) {
                            return `<a href="${row.url_detail}" class="symbol symbol-50px">
                                <span class="symbol-label" style="background-image:url(${row.thumbnail.full_url});"></span>
                            </a>`;
                        }

                        return '';
                    }
                },
                {
                    targets: 5,
                    render: function (data, type, row) {
                        if (row.category != null && row.category.name != undefined && row.category.name != null) {
                            return row.category.name;
                        }

                        return '';
                    }
                },
                {
                    targets: -1,
                    data: null,
                    orderable: false,
                    className: 'text-end',
                    render: function (data, type, row) {
                        var result = `
                            <a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                <i class="ki-duotone ki-down fs-5 ms-1"></i>
                            </a>

                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-175px py-4" data-kt-menu="true">
                        `;

                        if ('product-view_detail' in userPermissions) {
                            result += `
                                <div class="menu-item px-3">
                                    <a href="product/detail/${row.id}/" class="menu-link px-3">Detail</a>
                                </div>
                            `;
                        }

                        if ('product-edit' in userPermissions) {
                            result += `
                                <div class="menu-item px-3">
                                    <a href="product/edit/${row.id}/" class="menu-link px-3">Edit</a>
                                </div>
                            `;
                        }

                        if ('product-delete' in userPermissions) {
                            result += `
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-products-table-filter="delete_row" data-id='${row.id}'>Delete</a>
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
            handleEditRows();
            KTMenu.createInstances();
        });
    }

    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    var handleSearchDatatable = () => {
        const filterSearch = document.querySelector('[data-kt-product-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
        filterSearch.dispatchEvent(new KeyboardEvent('keyup',  {'key':''}));
    }

    // Filter Datatable
    var handleFilterDatatable = () => {
        // Select filter options
        const filterForm = document.querySelector('[data-kt-product-table-filter="form"]');
        const filterButton = filterForm.querySelector('[data-kt-product-table-filter="filter"]');
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
        const resetButton = document.querySelector('[data-kt-product-table-filter="reset"]');

        // Reset datatable
        resetButton.addEventListener('click', function () {
            // Select filter options
            const filterForm = document.querySelector('[data-kt-product-table-filter="form"]');
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
        const deleteButtons = table.querySelectorAll('[data-kt-products-table-filter="delete_row"]');

        deleteButtons.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                // Select parent row
                const parent = e.target.closest('tr');

                // Select parent row
                const button = e.target.closest('a');

                // Get product name
                const productName = parent.querySelectorAll('td')[0].innerText;

                // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                Swal.fire({
                    text: "Are you sure you want to delete " + productName + "?",
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
                                    text: "You have deleted " + productName + "!.",
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
                            text: productName + " was not deleted.",
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

    // Update product
    var handleEditRows = () => {
        // Select all update buttons
        const updateButtons = table.querySelectorAll('[data-kt-products-table-filter="update_row"]');

        updateButtons.forEach(d => {
            // Update button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                // // Select parent row
                const button = e.target.closest('a');

                const element = document.getElementById('kt_modal_add_product');
                const form = element.querySelector('#kt_modal_add_product_form');

                KTApp.showPageLoading();

                // Check axios library docs: https://axios-http.com/docs/intro
                axios.get(table.dataset.urlEdit + '/' + button.getAttribute('data-id')).then(function (response) {
                    if (response && response.data) {
                        let product = response.data.data;
                        form.querySelector("input[name='product_id']").value = product.id;
                        form.querySelector("input[name='product_name']").value = product.name;
                        form.querySelector("input[name='product_email']").value = product.email;
                        form.querySelector("input[name='product_company']").value = product.company;
                        form.querySelector("input[name='product_phone_number']").value = product.phone_number;
                        form.querySelector("select[name='product_type']").value = product.type;

                        let detailAddress = '';
                        form.querySelector("input[name='product_village_id']").value = '';
                        if (product.village) {
                            form.querySelector("input[name='product_village_id']").value = product.village.id;
                            detailAddress += `${product.village.name}`;
                        }

                        form.querySelector("input[name='product_district_id']").value = '';
                        if (product.district) {
                            form.querySelector("input[name='product_district_id']").value = product.district.id;
                            detailAddress += `, Kec. ${product.district.name}`;
                        }

                        form.querySelector("input[name='product_regency_id']").value = '';
                        if (product.regency) {
                            form.querySelector("input[name='product_regency_id']").value = product.regency.id;
                            detailAddress += `<br/> ${product.regency.name}`;
                        }

                        form.querySelector("input[name='product_province_id']").value = '';
                        if (product.province) {
                            form.querySelector("input[name='product_province_id']").value = product.province.id;
                            detailAddress += ` - ${product.province.name}`;
                        }

                        form.querySelector("textarea[name='product_address']").value = product.address;

                        form.querySelector(`[data-kt-region="product_region_description"]`).value = detailAddress;
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

    // Export Datatable
    var handleExportDatatable = () => {
        // Select filter options
        const filterForm = document.querySelector('[data-kt-product-table-export="form"]');
        const filterButton = filterForm.querySelector('[data-kt-product-table-export="export"]');

        // Export datatable on submit
        filterButton.addEventListener('click', function (e) {
            // Prevent default button action
            e.preventDefault();
            const form = document.getElementById('kt_ecommerce_export_product_form');
            let param = new FormData(form);
            param.append('q', document.querySelector('[data-kt-product-table-filter="search"]').value);
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

            initSupplierTable();
            handleSearchDatatable();
            handleResetForm();
            handleDeleteRows();
            handleEditRows();
            handleFilterDatatable();
            handleExportDatatable();
        },
        refresh: function() {
            datatable.ajax.reload(null, false); // product paging is not reset on reload
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTSuppliersList.init();
});
