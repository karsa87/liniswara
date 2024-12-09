"use strict";

var KTSuppliersList = function () {
    // Define shared variables
    var table = document.getElementById('kt_table_prerestocks');
    var datatable;

    // Private functions
    var initSupplierTable = function () {
        $('[name="search_branch_id"]').select2({
            dropdownParent: $('#data-kt-menu-filter-prerestock'),
            ajax: {
                url: $('[name="search_branch_id"]').data('url'),
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

        // Init datatable --- more info on datatables: https://datatables.net/manual/
        datatable = $(table).DataTable({
            searchDelay: 1000,
            processing: true,
            serverSide: true,
            order: [[2, 'desc']],
            stateSave: true,
            ajax: {
                url: table.dataset.url,
                "data": function ( d ) {
                    // Select filter options
                    const filterForm = document.querySelector('[data-kt-prerestock-table-filter="form"]');
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
                    d.search.branch_id = filterString;
                }
            },
            columns: [
                { data: 'user', orderable: false },
                { data: 'label' },
                { data: 'created_at' },
                { data: null, orderable: false },
                { data: null },
            ],
            columnDefs: [
                {
                    targets: 0,
                    render: function (data, type, row) {
                        if (
                            row.user != null
                            && row.user.id != undefined
                            && row.user.id != null
                        ) {
                            return row.user.name;
                        }
                        return '';
                    }
                },
                {
                    targets: 3,
                    render: function (data, type, row) {
                        var result = '';
                        if (row.is_migrate) {
                            result =`<span class="badge badge-info">Belum Lengkap</span>`;
                        }

                        if (row.is_migrate_all) {
                            result =`<span class="badge badge-success">Lengkap</span>`;
                        }

                        if (!row.is_migrate && !row.is_migrate_all) {
                            result =`<span class="badge badge-secondary">Menunggu</span>`;
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
                        var result = `
                            <a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                <i class="ki-duotone ki-down fs-5 ms-1"></i>
                            </a>

                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-175px py-4" data-kt-menu="true">
                        `;

                        if (!row.is_migrate && 'prerestock-edit' in userPermissions) {
                            result += `
                                <div class="menu-item px-3">
                                    <a href="prerestock/edit/${row.id}/" class="menu-link px-3">Edit</a>
                                </div>
                            `;
                        }

                        if ('prerestock-view_detail' in userPermissions) {
                            result += `
                                <div class="menu-item px-3">
                                    <a href="prerestock/detail/${row.id}/" class="menu-link px-3">Detail</a>
                                </div>
                            `;
                        }

                        if (!row.is_migrate_all && 'prerestock-migrate' in userPermissions) {
                            // result += `
                            //     <div class="menu-item px-3">
                            //         <a href="#" class="menu-link px-3" data-kt-prerestocks-table-filter="migrate_row" data-id='${row.id}'>Migrate</a>
                            //     </div>
                            // `;

                            result += `
                                <div class="menu-item px-3">
                                    <a href="prerestock/migrate/${row.id}/" class="menu-link px-3">Migrate</a>
                                </div>
                            `;
                        }

                        if (!row.is_migrate && 'prerestock-delete' in userPermissions) {
                            result += `
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-prerestocks-table-filter="delete_row" data-id='${row.id}'>Delete</a>
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
            // handleMigrateRows();
            KTMenu.createInstances();
        });
    }

    // Filter Datatable
    var handleFilterDatatable = () => {
        // Select filter options
        const filterForm = document.querySelector('[data-kt-prerestock-table-filter="form"]');
        const filterButton = filterForm.querySelector('[data-kt-prerestock-table-filter="filter"]');
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
        const resetButton = document.querySelector('[data-kt-prerestock-table-filter="reset"]');

        // Reset datatable
        resetButton.addEventListener('click', function () {
            // Select filter options
            const filterForm = document.querySelector('[data-kt-prerestock-table-filter="form"]');
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
        const deleteButtons = table.querySelectorAll('[data-kt-prerestocks-table-filter="delete_row"]');

        deleteButtons.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                // Select parent row
                const parent = e.target.closest('tr');

                // Select parent row
                const button = e.target.closest('a');

                // Get prerestock name
                const prerestockName = parent.querySelectorAll('td')[0].innerText;

                // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                Swal.fire({
                    text: "Are you sure you want to delete " + prerestockName + "?",
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
                                    text: "You have deleted " + prerestockName + "!.",
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
                            text: prerestockName + " was not deleted.",
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

    // Migrate
    var handleMigrateRows = () => {
        // Select all migrate buttons
        const migrateButtons = table.querySelectorAll('[data-kt-prerestocks-table-filter="migrate_row"]');

        migrateButtons.forEach(d => {
            // Migrate button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                // Select parent row
                const parent = e.target.closest('tr');

                // Select parent row
                const button = e.target.closest('a');

                // Get prerestock name
                const prerestockName = parent.querySelectorAll('td')[0].innerText;

                // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                Swal.fire({
                    text: "Are you sure you want to migrate " + prerestockName + "?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, migrate!",
                    cancelButtonText: "No, cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    if (result.isConfirmed) {
                        axios.post('prerestock/migrate/' + button.getAttribute('data-id'), {})
                        .then(response => {
                            if (response) {
                                Swal.fire({
                                    text: "You have migrated " + prerestockName + "!.",
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
                                    text: "Sorry, looks like failed migrated, please try again.",
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
                            let msg = "Sorry, failed migrate, please try again.";
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
                                title: "Failed Migrate",
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
                            text: prerestockName + " was not migrated.",
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

    return {
        // Public functions
        init: function () {
            if (!table) {
                return;
            }

            initSupplierTable();
            handleResetForm();
            handleDeleteRows();
            // handleMigrateRows();
            handleFilterDatatable();
        },
        refresh: function() {
            datatable.ajax.reload(null, false); // prerestock paging is not reset on reload
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTSuppliersList.init();
});
