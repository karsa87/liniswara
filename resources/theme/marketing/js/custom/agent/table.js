"use strict";

var KTCustomersList = function () {
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
            order: [[3, 'desc']],
            // stateSave: true,
            ajax: {
                url: table.dataset.url,
            },
            columns: [
                {
                    className: 'dt-control',
                    orderable: false,
                    data: null,
                    defaultContent: ''
                },
                { data: 'name' },
                { data: 'target' },
                { data: 'total_achieved', orderable: false },
                { data: null, orderable: false },
            ],
            columnDefs: [
                {
                    targets: 1,
                    render: function (data, type, row) {
                        var result = `<a href="${window.location.origin}/marketing/payment/agent/${row.id}" class="text-dark text-hover-primary fs-6 fw-bold">${data}</a>
                            <br>
                            <span class="text-muted">${row.email}</span>
                        `;
                        // var result = `<span class="text-dark text-hover-primary fs-6 fw-bold">${data}</span>
                        //     <br>
                        //     <span class="text-muted">${row.email}</span>
                        // `;

                        if (row.phone_number) {
                            result += `<br><a href="https://wa.me/${row.phone_number}" class="badge badge-light-success fs-7 m-1">${row.phone_number}</a>`;
                        }

                        return result;
                    }
                },
                {
                    targets: 2,
                    render: function (data, type, row) {
                        let target = 0;
                        if (typeof row.target == 'number') {
                            target = row.target.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                        }

                        return target;
                    }
                },
                {
                    targets: 3,
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

                        var percent_show = Math.round((row.total_achieved / row.target) * 100);
                        var percent = row.total_achieved >= row.target ? 100 : Math.round((row.total_achieved / row.target) * 100);
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
        var urlRankRegency = element.target.closest('table').dataset.rankRegency.replace('REPLACE', data.id);

        var result = '<h6>Target dan Pencapaian per Wilayah</h6>';
        result += `<table class="table align-middle table-row-dashed fs-6 gy-5" id="table-transaction-agent-${data.id}">
            <thead>
                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-325px text-center">Kota</th>
                    <th class="min-w-125px">Target</th>
                    <th class="min-w-125px">Pencapaian</th>
                    <th class="min-w-125px">Persentase</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>`;

        KTApp.showPageLoading();
        // Init chart
        axios.get(urlRankRegency).then(function (response) {
            if (response && response.data) {
                response.data.forEach(dataArea => {
                    var preordersTotal = dataArea.preorders_total ?? 0;
                    var preordersTotalFormatted = '-';
                    if (preordersTotal != null && preordersTotal != undefined) {
                        preordersTotalFormatted = preordersTotal.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                    }

                    var percent_show = 0;
                    var percent = 0;
                    var areaName = dataArea.name;
                    var targetArea = dataArea.target.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                    percent_show = Math.round((preordersTotal / dataArea.target) * 100);
                    percent = preordersTotal >= dataArea.target ? 100 : Math.round((preordersTotal / dataArea.target) * 100);

                    var resultTr = `<tr>
                        <td>${areaName}</td>
                        <td>${targetArea}</td>
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

                    $(`#table-transaction-agent-${data.id} tbody`).append(resultTr);
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
            console.log(error);
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
    KTCustomersList.init();
});
