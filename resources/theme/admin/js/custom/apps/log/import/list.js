"use strict";

// Class definition
var KTStockProductsList = function () {
    // Shared variables
    var datatable;
    const element = document.getElementById('kt_modal_show_changes');
    const modal = new bootstrap.Modal(element);
    var table;

    // Init add schedule modal
    var initStockProductsList = () => {
        $('[name="search_user_id"]').select2({
            dropdownParent: $('#data-kt-menu-filter-import'),
            ajax: {
                url: $('[name="search_user_id"]').data('url'),
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
            order: [[0, 'desc']],
            stateSave: true,
            ajax: {
                url: table.dataset.url,
                "data": function ( d ) {
                    d.search.user_id = $('[name="search_user_id"]').val();
                    d.search.status = $('#filter-search_status').val();
                }
            },
            columns: [
                { data: 'name' },
                { data: null },
                { data: 'date' },
                { data: 'status' },
                { data: null },
            ],
            columnDefs: [
                {
                    targets: 1,
                    orderable: false,
                    render: function (data, type, row) {
                        if (row.user != undefined && row.user != null && row.user.name != undefined && row.user.name != null) {
                            return row.user.name;
                        }
                        return '';
                    },
                },
                {
                    targets: 3,
                    orderable: false,
                    render: function (data, type, row) {
                        let result = '';
                        if (row.status == 0 || row.status == "0") {
                            result +=`<span class="badge badge-warning">Menunggu</span>`;
                        } else if (row.status == 1 || row.status == "1") {
                            result +=`<span class="badge badge-primary">Proses</span>`;
                        } else if (row.status == 2 || row.status == "2") {
                            result +=`<span class="badge badge-success">Selesai</span>`;
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
                            <button class="btn btn-icon btn-active-light-primary w-30px h-30px me-3" data-kt-import-table-filter="show" data-bs-toggle="modal" data-bs-target="#kt_modal_show_changes" data-id="${data.id}">
                                <i class="ki-duotone ki-eye fs-3">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                </i>
                            </button>
                        `;
                    },
                },
            ],
        });

        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
        datatable.on('draw', function () {
            handleShowRows();
            KTMenu.createInstances();
        });
    }

    // Filter Datatable
    var handleFilterDatatable = () => {
        // Select filter options
        const filterForm = document.querySelector('[data-kt-import-table-filter="form"]');
        const filterButton = filterForm.querySelector('[data-kt-import-table-filter="filter"]');
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
        const resetButton = document.querySelector('[data-kt-import-table-filter="reset"]');

        // Reset datatable
        resetButton.addEventListener('click', function () {
            // Select filter options
            const filterForm = document.querySelector('[data-kt-import-table-filter="form"]');
            const selectOptions = filterForm.querySelectorAll('select');

            // Reset select2 values -- more info: https://select2.org/programmatic-control/add-select-clear-items
            selectOptions.forEach(select => {
                $(select).val('').trigger('change');
            });

            // Reset datatable --- official docs reference: https://datatables.net/reference/api/search()
            datatable.search('').draw();
        });
    }

    // Update user
    var handleShowRows = () => {
        // Select all update buttons
        const showButtons = table.querySelectorAll('[data-kt-import-table-filter="show"]');

        showButtons.forEach(d => {
            // Update button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();
                let table = document.getElementById("table-show-changes");
                table.querySelector("tbody").innerHTML = '';

                // // Select parent row
                const button = e.target.closest('button');

                KTApp.showPageLoading();

                // Check axios library docs: https://axios-http.com/docs/intro
                axios.get('import/detail/' + button.getAttribute('data-id')).then(function (response) {
                    if (response && response.data) {
                        let log = response.data.data;
                        if (
                            log.logs != undefined
                            && log.logs != null
                        ) {
                            for (const [key, value] of Object.entries(log.logs)) {
                                // Create a row using the inserRow() method and
                                // specify the index where you want to add the row
                                let row = table.querySelector("tbody").insertRow(-1); // We are adding at the end

                                // Create table cells
                                row.insertCell(0).innerText = value.description;

                                let reasons = '<ul>';
                                if (
                                    value.reason != undefined
                                    && value.reason != null
                                ) {
                                    value.reason.forEach(function (reason) {
                                        reasons += `<li>${reason}</li>`;
                                    })
                                }
                                reasons += '</ul>';

                                row.insertCell(1).innerHTML = reasons;
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

        // Close button handler
        const closeButton = element.querySelector('[data-kt-import-modal-action="close"]');
        closeButton.addEventListener('click', e => {
            e.preventDefault();
            modal.hide(); // Hide modal
        });
    }

    return {
        // Public functions
        init: function () {
            table = document.querySelector('#kt_import_table');

            if (!table) {
                return;
            }

            initStockProductsList();
            handleFilterDatatable();
            handleResetForm();
            handleShowRows();
        },
        refresh: function() {
            datatable.ajax.reload(null, false); // user paging is not reset on reload
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTStockProductsList.init();
});
