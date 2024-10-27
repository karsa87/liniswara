"use strict";

// Class definition
var KTUsersPreorderBooksList = function () {
    // Shared variables
    var datatable;
    var table;

    // Init add schedule modal
    var initPreorderBooksList = () => {
        $('[name="search_product_id"]').select2({
            dropdownParent: $('#data-kt-menu-export-product'),
            ajax: {
                url: $('[name="search_product_id"]').data('url'),
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
            order: [[4, 'desc']],
            stateSave: true,
            ajax: {
                url: table.dataset.url,
            },
            columns: [
                { data: 'product.code', orderable: false },
                { data: 'product.name', orderable: false },
                { data: 'total_sale', orderable: false },
                { data: 'stock' },
                { data: 'stock_need' },
                { data: 'total_stock_need' },
                // { data: 'total_stock_more' },
            ],
            columnDefs: [
                {
                    targets: 2,
                    orderable: false,
                    className: 'text-end',
                    render: function (data, type, row) {
                        return row.total_sale.toLocaleString('in-ID', { style: 'currency', currency: '', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                    },
                },
                {
                    targets: 3,
                    orderable: false,
                    className: 'text-end',
                    render: function (data, type, row) {
                        return row.stock.toLocaleString('in-ID', { style: 'currency', currency: '', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                    },
                },
                {
                    targets: 4,
                    orderable: false,
                    className: 'text-end',
                    render: function (data, type, row) {
                        return row.stock_need.toLocaleString('in-ID', { style: 'currency', currency: '', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                    },
                },
                {
                    targets: 5,
                    orderable: false,
                    className: 'text-end',
                    render: function (data, type, row) {
                        return row.total_stock_need.toLocaleString('in-ID', { style: 'currency', currency: '', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                    },
                },
                // {
                //     targets: 5,
                //     orderable: false,
                //     className: 'text-end',
                //     render: function (data, type, row) {
                //         return row.total_stock_more.toLocaleString('in-ID', { style: 'currency', currency: '', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                //     },
                // },
            ],
        });

        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
        datatable.on('draw', function () {
            KTMenu.createInstances();
        });
    }

    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    var handleSearchDatatable = () => {
        const filterSearch = document.querySelector('[data-kt-preorder-books-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
        filterSearch.dispatchEvent(new KeyboardEvent('keyup',  {'key':''}));
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
            const form = document.getElementById('kt_ecommerce_export_preorder_book_form');
            let param = new FormData(form);
            param.append('q', document.querySelector('[data-kt-preorder-books-table-filter="search"]').value);
            const objString = '?' + new URLSearchParams(Object.fromEntries(param)).toString();

            window.open(form.action + objString);
        });
    }

    return {
        // Public functions
        init: function () {
            table = document.querySelector('#kt_preorder_book_table');

            if (!table) {
                return;
            }

            initPreorderBooksList();
            handleSearchDatatable();
            handleExportDatatable();
        },
        refresh: function() {
            datatable.ajax.reload(null, false); // user paging is not reset on reload
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTUsersPreorderBooksList.init();
});
