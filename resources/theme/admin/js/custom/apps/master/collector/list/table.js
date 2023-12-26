"use strict";

var KTCollectorsList = function () {
    // Define shared variables
    var table = document.getElementById('kt_table_collectors');
    var datatable;

    // Private functions
    var initCollectorTable = function () {
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
                    // const filterForm = document.querySelector('[data-kt-collector-table-filter="form"]');
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
                { data: 'phone_number' },
                { data: 'address' },
                { data: null },
            ],
            columnDefs: [
                {
                    targets: 4,
                    className: 'd-flex align-items-center',
                    render: function (data, type, row) {
                        return `
                            <div class="d-flex flex-column">
                                <span class="text-gray-800 text-hover-primary mb-1">${row.address}</span>
                                <span>${row.village.name} - ${row.district.name}</span>
                                <span>${row.regency.name} - ${row.province.name}</span>
                            </div>
                        `;
                    },
                },
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
                                    <a href="#" class="menu-link px-3" data-kt-collectors-table-filter="update_row" data-bs-toggle="modal" data-bs-target="#kt_modal_add_collector" data-id='${row.id}'>Edit</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-collectors-table-filter="delete_row" data-id='${row.id}'>Delete</a>
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
        const filterSearch = document.querySelector('[data-kt-collector-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Delete subscirption
    var handleDeleteRows = () => {
        // Select all delete buttons
        const deleteButtons = table.querySelectorAll('[data-kt-collectors-table-filter="delete_row"]');

        deleteButtons.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                // Select parent row
                const parent = e.target.closest('tr');

                // Select parent row
                const button = e.target.closest('a');

                // Get collector name
                const collectorName = parent.querySelectorAll('td')[0].innerText;

                // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                Swal.fire({
                    text: "Are you sure you want to delete " + collectorName + "?",
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
                                    text: "You have deleted " + collectorName + "!.",
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
                            text: collectorName + " was not deleted.",
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

    // Update collector
    var handleEditRows = () => {
        // Select all update buttons
        const updateButtons = table.querySelectorAll('[data-kt-collectors-table-filter="update_row"]');

        updateButtons.forEach(d => {
            // Update button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                // // Select parent row
                const button = e.target.closest('a');

                const element = document.getElementById('kt_modal_add_collector');
                const form = element.querySelector('#kt_modal_add_collector_form');

                KTApp.showPageLoading();

                // Check axios library docs: https://axios-http.com/docs/intro
                axios.get(table.dataset.urlEdit + '/' + button.getAttribute('data-id')).then(function (response) {
                    if (response && response.data) {
                        let collector = response.data.data;
                        if (collector.signin_file != undefined && collector.signin_file.name != undefined != null && collector.signin_file.name) {
                            form.querySelector("input[name='collector_signin_file_id']").value = collector.signin_file.id;
                            KTCollectorsAddCollector.addImage(collector.signin_file.name, collector.signin_file.full_url);
                        }
                        form.querySelector("input[name='collector_id']").value = collector.id;
                        form.querySelector("input[name='collector_name']").value = collector.name;
                        form.querySelector("input[name='collector_email']").value = collector.email;
                        form.querySelector("input[name='collector_company']").value = collector.company;
                        form.querySelector("input[name='collector_phone_number']").value = collector.phone_number;
                        form.querySelector("input[name='collector_npwp']").value = collector.npwp;
                        form.querySelector("input[name='collector_gst']").value = collector.gst;
                        form.querySelector("input[name='collector_province_id']").value = collector.province.id;
                        form.querySelector("input[name='collector_regency_id']").value = collector.regency.id;
                        form.querySelector("input[name='collector_district_id']").value = collector.district.id;
                        form.querySelector("input[name='collector_village_id']").value = collector.village.id;
                        form.querySelector("textarea[name='collector_address']").value = collector.address;
                        form.querySelector("textarea[name='collector_footer']").value = collector.footer;
                        form.querySelector("textarea[name='collector_billing_notes']").value = collector.billing_notes;

                        form.querySelector(`[data-kt-region="collector_region_description"]`).innerHTML = `${collector.village.name}, Kec. ${collector.district.name} <br/> ${collector.regency.name} - ${collector.province.name}`;

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

            initCollectorTable();
            handleSearchDatatable();
            handleDeleteRows();
            handleEditRows();
        },
        refresh: function() {
            datatable.ajax.reload(null, false); // collector paging is not reset on reload
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTCollectorsList.init();
});
