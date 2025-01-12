"use strict";

var KTRegionsList = function () {
    // Define shared variables
    var table = document.getElementById('kt_table_customers');
    var datatable;

    // Private functions
    var initCustomerTable = function () {
        // Init datatable --- more info on datatables: https://datatables.net/manual/
        datatable = $(table).DataTable({
            searchDelay: 1000,
            processing: true,
            serverSide: true,
            order: [[4, 'desc']],
            // stateSave: true,
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
                {
                    className: 'dt-control',
                    orderable: false,
                    data: null,
                    defaultContent: ''
                },
                { data: 'name', orderable: false },
                { data: 'total_transaction', orderable: false },
                { data: 'target', orderable: false },
                { data: 'total_achieved', orderable: false },
                { data: null, orderable: false },
            ],
            columnDefs: [
                {
                    targets: 1,
                    render: function (data, type, row) {
                        return row.name;
                    }
                },
                {
                    targets: 2,
                    render: function (data, type, row) {
                        let total_transaction = 0;
                        if (typeof row.total_transaction == 'number') {
                            total_transaction = row.total_transaction;
                        }

                        return total_transaction;
                    }
                },
                {
                    targets: 3,
                    render: function (data, type, row) {
                        let target = 0;
                        if (typeof row.target == 'number') {
                            target = row.target.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                        }

                        return target;
                    }
                },
                {
                    targets: 4,
                    render: function (data, type, row) {
                        let total_achieved = 0;
                        if (typeof row.total_achieved == 'number') {
                            total_achieved = row.total_achieved.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                        }

                        return total_achieved;
                    }
                },
                {
                    targets: -1,
                    data: null,
                    orderable: false,
                    className: 'text-end',
                    render: function (data, type, row) {
                        var total_achieved = row.total_achieved.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });

                        var percent = row.total_achieved >= row.target ? 100 : Math.round((row.total_achieved / row.target) * 100);
                        var percent_show = Math.round((row.total_achieved / row.target) * 100);
                        return `
                        <div class="d-flex align-items-center flex-column mt-3 w-100">
                            <div class="d-flex justify-content-between fw-bold fs-6 opacity-50 w-100 mt-auto mb-2">
                                <span>${percent_show}%</span>
                            </div>
                            <div class="h-8px mx-3 w-100 bg-light-success rounded">
                                <div class="bg-success rounded h-8px" role="progressbar" style="width: ${percent}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        `;
                    },
                },
            ],
        });

        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
        datatable.on('draw', function () {
            KTMenu.createInstances();
        });

        // Add event listener for opening and closing details
        datatable.on('click', 'td.dt-control', function (e) {
            let table = e.target.closest('table');
            if (table.id != 'kt_table_customers') {
                return;
            }

            let tr = e.target.closest('tr');
            let row = datatable.row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
            }
            else {
                // Open this row
                row.child(format(e, row.data())).show();
            }
        });
    }

    // Formatting function for row details - modify as you need
    var format = (element, data) => {
        var urlRankSchool = element.target.closest('table').dataset.rankSchool.replace('REPLACE', data.id);
        var urlRankAgent = element.target.closest('table').dataset.rankAgent.replace('REPLACE', `${data.id}?school_id=REPLACE`);

        var result = '<h6 class="text-center">Target dan Pencapaian per Jenjang Sekolah</h6>';
        result += `<table class="table align-middle table-row-dashed fs-6 gy-5" id="table-transaction-school-${data.id}" data-rank-agent="${urlRankAgent}">
            <thead>
                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-25px text-center"></th>
                    <th class="min-w-325px text-center">Sekolah</th>
                    <th class="min-w-125px">Target</th>
                    <th class="min-w-125px">Pencapaian</th>
                    <th class="min-w-125px">Persentase</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>`;

        KTApp.showPageLoading();
        // Init chart
        axios.get(urlRankSchool).then(function (response) {
            if (response && response.data) {
                response.data.forEach(dataSchool => {
                    var preordersTotal = dataSchool.preorders_total ?? 0;
                    var preordersTotalFormatted = '-';
                    if (preordersTotal != null && preordersTotal != undefined) {
                        preordersTotalFormatted = preordersTotal.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                    }

                    var percent_show = 0;
                    var percent = 0;
                    var nameSchool = dataSchool.name ? dataSchool.name : '-';
                    var targetAgent = dataSchool.pivot.target.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                    percent_show = Math.round((preordersTotal / dataSchool.pivot.target) * 100);
                    percent = preordersTotal >= dataSchool.pivot.target ? 100 : Math.round((preordersTotal / dataSchool.pivot.target) * 100);

                    var resultTr = `<tr data-school-id="${dataSchool.id}">
                        <td class="dt-control"></td>
                        <td>${nameSchool}</td>
                        <td>${targetAgent}</td>
                        <td>${preordersTotalFormatted}</td>
                        <td>
                        <div class="d-flex align-items-center flex-column mt-3 w-100">
                            <div class="d-flex justify-content-between fw-bold fs-6 opacity-50 w-100 mt-auto mb-2">
                                <span>${percent_show}%</span>
                            </div>
                            <div class="h-8px mx-3 w-100 bg-light-success rounded">
                                <div class="bg-success rounded h-8px" role="progressbar" style="width: ${percent}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        </td>
                    </tr>`;

                    $(`#table-transaction-school-${data.id} tbody`).append(resultTr);
                });

                $(`#table-transaction-school-${data.id} tbody td.dt-control`).on('click', function (e) {
                    e.preventDefault();

                    let tr = e.target.closest('tr');
                    let childRow = $(`#table-transaction-school-${data.id}`).find(`.child-row-${data.id}-${tr.dataset.schoolId}`);

                    if (childRow.length > 0) {
                        // This row is already open - remove child
                        childRow.remove();
                        tr.classList.remove('dt-hasChild');
                    } else {
                        // Add child row
                        tr.classList.add('dt-hasChild');
                        const newRow = document.createElement('tr');
                        newRow.classList.add(`child-row-${data.id}-${tr.dataset.schoolId}`);
                        let tableArea = formatArea(e, tr.dataset.schoolId);
                        newRow.innerHTML = `<td colspan="${tr.children.length}">
                            ${tableArea}
                        </td>`;
                        tr.after(newRow);
                    }
                });
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
            let msg = "Gagal load data.";

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

        return result;
    }

    // Formatting function for row details - modify as you need
    var formatArea = (element, school_id) => {
        var urlRankAgent = element.target.closest('table').dataset.rankAgent.replace('REPLACE', school_id);

        var result = '<h6 class="text-center">Target dan Pencapaian per Agent</h6>';
        result += `<table class="table align-middle table-row-dashed fs-6 gy-5" id="table-transaction-agent-${school_id}">
            <thead>
                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-325px text-center">Agent</th>
                    <th class="min-w-125px">Target</th>
                    <th class="min-w-125px">Pencapaian</th>
                    <th class="min-w-125px">Persentase</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>`;

        KTApp.showPageLoading();
        // Init chart
        axios.get(urlRankAgent).then(function (response) {
            if (response && response.data) {
                response.data.forEach(dataAgent => {
                    var preordersTotal = dataAgent.preorders_total ?? 0;
                    var preordersTotalFormatted = '-';
                    if (preordersTotal != null && preordersTotal != undefined) {
                        preordersTotalFormatted = preordersTotal.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                    }

                    var percent_show = 0;
                    var percent = 0;
                    var nameAgent = dataAgent.user ? dataAgent.user.name : '-';
                    var targetAgent = dataAgent.target.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                    percent_show = Math.round((preordersTotal / dataAgent.target) * 100);
                    percent = preordersTotal >= dataAgent.target ? 100 : Math.round((preordersTotal / dataAgent.target) * 100);

                    var resultTr = `<tr>
                        <td>${nameAgent}</td>
                        <td>${targetAgent}</td>
                        <td>${preordersTotalFormatted}</td>
                        <td>
                        <div class="d-flex align-items-center flex-column mt-3 w-100">
                            <div class="d-flex justify-content-between fw-bold fs-6 opacity-50 w-100 mt-auto mb-2">
                                <span>${percent_show}%</span>
                            </div>
                            <div class="h-8px mx-3 w-100 bg-light-success rounded">
                                <div class="bg-success rounded h-8px" role="progressbar" style="width: ${percent}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        </td>
                    </tr>`;

                    $(`#table-transaction-agent-${school_id} tbody`).append(resultTr);
                });
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
            let msg = "Gagal load data.";

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

        return result;
    }

    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    var handleSearchDatatable = () => {
        const filterSearch = document.querySelector('[data-kt-customer-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
        filterSearch.dispatchEvent(new KeyboardEvent('keyup',  {'key':''}));
    }

    return {
        // Public functions
        init: function () {
            if (!table) {
                return;
            }

            initCustomerTable();
            handleSearchDatatable();
        },
        refresh: function() {
            datatable.ajax.reload(null, false); // customer paging is not reset on reload
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTRegionsList.init();
});
