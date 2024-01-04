"use strict";

var KTSuppliersList = function () {
    // Define shared variables
    var table = document.getElementById('kt_table_customers');
    var datatable;

    // Private functions
    var initSupplierTable = function () {
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
                    // // Select filter options
                    // const filterForm = document.querySelector('[data-kt-customer-table-filter="form"]');
                    // const selectOptions = filterForm.querySelectorAll('select');

                    // // Filter datatable on submit
                    // var filterString = '';

                    // // Get filter values
                    // selectOptions.forEach((item, index) => {
                    //     if (item.value && item.value !== '') {
                    //         if (index !== 0) {
                    //             filterString += ' ';
                    //         }

                    //         // Build filter value options
                    //         filterString += item.value;
                    //     }
                    // });
                    // d.search.role_id = filterString;
                }
            },
            columns: [
                { data: 'company' },
                { data: 'name' },
                { data: 'email' },
                { data: 'phone_number', orderable: false },
                { data: 'type_label', orderable: false },
                { data: null },
            ],
            columnDefs: [
                {
                    targets: 3,
                    render: function (data) {
                        if (data) {
                            return `<a href="https://wa.me/${data}" class="badge badge-light-success fs-7 m-1">${data}</a>`;
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
                        return `
                            <a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                <i class="ki-duotone ki-down fs-5 ms-1"></i>
                            </a>

                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-175px py-4" data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <a href="customer/${row.id}/customer-address" class="menu-link px-3" data-id='${row.id}'>Alamat</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-customers-table-filter="update_row" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer" data-id='${row.id}'>Edit</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-customers-table-filter="delete_row" data-id='${row.id}'>Delete</a>
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
            handleEditRows();
            KTMenu.createInstances();
        });
    }

    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    var handleSearchDatatable = () => {
        const filterSearch = document.querySelector('[data-kt-customer-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
        filterSearch.dispatchEvent(new KeyboardEvent('keyup',  {'key':''}));
    }

    // Delete subscirption
    var handleDeleteRows = () => {
        // Select all delete buttons
        const deleteButtons = table.querySelectorAll('[data-kt-customers-table-filter="delete_row"]');

        deleteButtons.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                // Select parent row
                const parent = e.target.closest('tr');

                // Select parent row
                const button = e.target.closest('a');

                // Get customer name
                const customerName = parent.querySelectorAll('td')[0].innerText;

                // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                Swal.fire({
                    text: "Are you sure you want to delete " + customerName + "?",
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
                                    text: "You have deleted " + customerName + "!.",
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
                            text: customerName + " was not deleted.",
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

    // Update customer
    var handleEditRows = () => {
        // Select all update buttons
        const updateButtons = table.querySelectorAll('[data-kt-customers-table-filter="update_row"]');

        updateButtons.forEach(d => {
            // Update button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                // // Select parent row
                const button = e.target.closest('a');

                const element = document.getElementById('kt_modal_add_customer');
                const form = element.querySelector('#kt_modal_add_customer_form');

                KTApp.showPageLoading();

                // Check axios library docs: https://axios-http.com/docs/intro
                axios.get(table.dataset.urlEdit + '/' + button.getAttribute('data-id')).then(function (response) {
                    if (response && response.data) {
                        let customer = response.data.data;
                        form.querySelector("input[name='customer_id']").value = customer.id;
                        form.querySelector("input[name='customer_name']").value = customer.name;
                        form.querySelector("input[name='customer_email']").value = customer.email;
                        form.querySelector("input[name='customer_company']").value = customer.company;
                        form.querySelector("input[name='customer_phone_number']").value = customer.phone_number;
                        // form.querySelector("select[name='customer_type']").value = customer.type;
                        form.querySelector("input[name='customer_province_id']").value = customer.province.id;
                        form.querySelector("input[name='customer_regency_id']").value = customer.regency.id;
                        form.querySelector("input[name='customer_district_id']").value = customer.district.id;
                        form.querySelector("input[name='customer_village_id']").value = customer.village.id;
                        form.querySelector("textarea[name='customer_address']").value = customer.address;

                        form.querySelector(`[data-kt-region="customer_region_description"]`).value = `${customer.village.name}, Kec. ${customer.district.name} <br/> ${customer.regency.name} - ${customer.province.name}`;

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

    return {
        // Public functions
        init: function () {
            if (!table) {
                return;
            }

            initSupplierTable();
            handleSearchDatatable();
            handleDeleteRows();
            handleEditRows();
        },
        refresh: function() {
            datatable.ajax.reload(null, false); // customer paging is not reset on reload
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTSuppliersList.init();
});
