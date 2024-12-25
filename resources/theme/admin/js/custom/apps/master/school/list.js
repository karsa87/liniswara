"use strict";

// Class definition
var KTUsersSchoolsList = function () {
    // Shared variables
    var datatable;
    var table;

    // Init add schedule modal
    var initSchoolsList = () => {
        // Set date data order
        const tableRows = table.querySelectorAll('tbody tr');

        tableRows.forEach(row => {
            const dateRow = row.querySelectorAll('td');
            const realDate = moment(dateRow[2].innerHTML, "DD MMM YYYY, LT").format(); // select date from 2nd column in table
            dateRow[2].setAttribute('data-order', realDate);
        });

        // Init datatable --- more info on datatables: https://datatables.net/manual/
        datatable = $(table).DataTable({
            searchDelay: 1000,
            processing: true,
            serverSide: true,
            order: [[0, 'desc']],
            stateSave: true,
            ajax: {
                url: table.dataset.url,
            },
            columns: [
                { data: 'name' },
                { data: null },
            ],
            columnDefs: [
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

                        if ('school-edit' in userPermissions) {
                            result += `
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-schools-table-filter="update_row" data-bs-toggle="modal" data-bs-target="#kt_modal_update_school" data-id="${row.id}">Edit</a>
                                </div>
                            `;
                        }

                        if ('school-delete' in userPermissions) {
                            result += `
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-schools-table-filter="delete_row" data-id="${row.id}">Delete</a>
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
        const filterSearch = document.querySelector('[data-kt-schools-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
        filterSearch.dispatchEvent(new KeyboardEvent('keyup',  {'key':''}));
    }

    // Delete user
    var handleDeleteRows = () => {
        // Select all delete buttons
        const deleteButtons = table.querySelectorAll('[data-kt-schools-table-filter="delete_row"]');

        deleteButtons.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                // Select parent row
                const parent = e.target.closest('tr');

                // Select parent row
                const button = e.target.closest('a');

                // Get school name
                const schoolName = parent.querySelectorAll('td')[0].innerText;

                // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                Swal.fire({
                    text: "Are you sure you want to delete " + schoolName + "?",
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
                                    text: "You have deleted " + schoolName + "!.",
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
                            text: schoolName + " was not deleted.",
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

    // Update user
    var handleEditRows = () => {
        // Select all update buttons
        const updateButtons = table.querySelectorAll('[data-kt-schools-table-filter="update_row"]');

        updateButtons.forEach(d => {
            // Update button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                // Select parent row
                const parent = e.target.closest('tr');

                // Select parent row
                const button = e.target.closest('a');

                // Get school name
                const schoolName = parent.querySelectorAll('td')[0].innerText;

                const element = document.getElementById('kt_modal_update_school');
                const form = element.querySelector('#kt_modal_update_school_form');
                form.querySelector("input[name='school_name']").value = schoolName;
                form.querySelector("input[name='school_id']").value = button.getAttribute('data-id');
            })
        });
    }

    return {
        // Public functions
        init: function () {
            table = document.querySelector('#kt_schools_table');

            if (!table) {
                return;
            }

            initSchoolsList();
            handleSearchDatatable();
            handleDeleteRows();
            handleEditRows();
        },
        refresh: function() {
            datatable.ajax.reload(null, false); // user paging is not reset on reload
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTUsersSchoolsList.init();
});
