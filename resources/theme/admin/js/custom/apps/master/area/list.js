"use strict";

// Class definition
var KTUsersBranchsList = function () {
    // Shared variables
    var datatable;
    var table;

    // Init add schedule modal
    var initBranchsList = () => {
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
            ajax: {
                url: table.dataset.url,
            },
            columns: [
                { data: 'name' },
                { data: null },
                { data: 'target' },
                { data: null },
            ],
            columnDefs: [
                {
                    targets: 1,
                    render: function (data, type, row) {
                        var result = `
                            <div class="d-flex flex-column">
                        `;

                        if (row.village) {
                            result += `<span class="text-gray-800 text-hover-primary mb-1">${row.village.name}</span>`;
                        }

                        if (row.district) {
                            result += `${row.district.name}`;
                        }

                        if (row.regency) {
                            result += `<br> ${row.regency.name}`;
                        }

                        if (row.province) {
                            result += `<br> ${row.province.name}`;
                        }

                        result += `</div>`;
                        return result;
                    }
                },
                {
                    targets: 2,
                    render: function (data, type, row) {
                        let result_schools = "";
                        if (row.schools != undefined && row.schools != null && row.schools.length > 0) {
                            result_schools = "<br><ul class='fs-7 text-muted'>";
                            row.schools.forEach(function (school) {
                                let target_school = school.target.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                                result_schools += `<li>${school.name} : ${target_school}</li>`
                            });
                            result_schools += "</ul>";
                        }

                        let target = 0;
                        if (typeof row.target == 'number') {
                            target = row.target.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                        }

                        return `<span class="fw-bold text-gray-600">${target}</span>` + result_schools;
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

                        if ('area_school-view' in userPermissions) {
                            result += `
                                <div class="menu-item px-3">
                                    <a href="area/${row.id}/area-school" class="menu-link px-3" data-id='${row.id}'>Sekolah</a>
                                </div>
                            `;
                        }

                        if ('area-edit' in userPermissions) {
                            result += `
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-areas-table-filter="update_row" data-bs-toggle="modal" data-bs-target="#kt_modal_update_area" data-id="${row.id}">Edit</a>
                                </div>
                            `;
                        }

                        if ('area-delete' in userPermissions) {
                            result += `
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-areas-table-filter="delete_row" data-id="${row.id}">Delete</a>
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
        const filterSearch = document.querySelector('[data-kt-areas-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
        filterSearch.dispatchEvent(new KeyboardEvent('keyup',  {'key':''}));
    }

    // Delete user
    var handleDeleteRows = () => {
        // Select all delete buttons
        const deleteButtons = table.querySelectorAll('[data-kt-areas-table-filter="delete_row"]');

        deleteButtons.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                // Select parent row
                const parent = e.target.closest('tr');

                // Select parent row
                const button = e.target.closest('a');

                // Get area name
                const areaName = parent.querySelectorAll('td')[0].innerText;

                // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                Swal.fire({
                    text: "Are you sure you want to delete " + areaName + "?",
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
                                    text: "You have deleted " + areaName + "!.",
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
                            text: areaName + " was not deleted.",
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
        const updateButtons = table.querySelectorAll('[data-kt-areas-table-filter="update_row"]');

        updateButtons.forEach(d => {
            // Update button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                // // Select parent row
                const button = e.target.closest('a');

                const element = document.getElementById('kt_modal_update_area');
                const form = element.querySelector('#kt_modal_update_area_form');

                KTApp.showPageLoading();

                // Check axios library docs: https://axios-http.com/docs/intro
                axios.get(table.dataset.urlEdit + '/' + button.getAttribute('data-id')).then(function (response) {
                    if (response && response.data) {
                        let area = response.data.data;
                        form.querySelector("input[name='area_id']").value = area.id;
                        form.querySelector("input[name='area_name']").value = area.name;
                        // form.querySelector("input[name='area_target']").value = area.target;

                        if (area.schools != undefined && area.schools != null) {
                            area.schools.forEach(school => {
                                form.querySelector(`input[name='area_schools[${school.id}]']`).value = school.target;
                            })
                        }

                        let detailAddress = '';
                        $('#area_update_village_id').val('').trigger('change');
                        if (area.village) {
                            var stateEl = new Option(area.village.name, area.village.id, true, true);
                            $('#area_update_village_id').append(stateEl);
                            $('#area_update_village_id').val(area.village.id).trigger('change');

                            detailAddress += `${area.village.name}`;
                        }

                        $('#area_update_district_id').val('').trigger('change');
                        if (area.district) {
                            var stateEl = new Option(area.district.name, area.district.id, true, true);
                            $('#area_update_district_id').append(stateEl);
                            $('#area_update_district_id').val(area.district.id).trigger('change');

                            detailAddress += `, Kec. ${area.district.name}`;
                        }

                        $('#area_update_regency_id').val('').trigger('change');
                        if (area.regency) {
                            var stateEl = new Option(area.regency.name, area.regency.id, true, true);
                            $('#area_update_regency_id').append(stateEl);
                            $('#area_update_regency_id').val(area.regency.id).trigger('change');

                            detailAddress += `<br/> ${area.regency.name}`;
                        }

                        $('#area_update_province_id').val('').trigger('change');
                        if (area.province) {
                            var stateEl = new Option(area.province.name, area.province.id, true, true);
                            $('#area_update_province_id').append(stateEl);
                            $('#area_update_province_id').val(area.province.id).trigger('change');

                            detailAddress += ` - ${area.province.name}`;
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
            table = document.querySelector('#kt_areas_table');

            if (!table) {
                return;
            }

            initBranchsList();
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
    KTUsersBranchsList.init();
});
