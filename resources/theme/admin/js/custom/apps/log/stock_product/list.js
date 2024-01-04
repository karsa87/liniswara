"use strict";

// Class definition
var KTStockProductsList = function () {
    // Shared variables
    var datatable;
    var table;

    // Init add schedule modal
    var initStockProductsList = () => {
        $('[name="search_product_id"]').select2({
            dropdownParent: $('#data-kt-menu-filter-stock-product'),
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
            order: [[0, 'desc']],
            stateSave: true,
            ajax: {
                url: table.dataset.url,
                "data": function ( d ) {
                    d.search.product_id = $('[name="search_product_id"]').val();
                }
            },
            columns: [
                { data: 'product.code', orderable: false },
                { data: 'product.name', orderable: false },
                { data: 'stock_old' },
                { data: 'stock_in' },
                { data: 'stock_out' },
                { data: 'stock_new' },
                { data: null },
            ],
            columnDefs: [
                {
                    targets: 6,
                    orderable: false,
                    render: function (data, type, row) {
                        if (row.user != undefined && row.user != null && row.user.name != undefined && row.user.name != null) {
                            return row.user.name;
                        }
                        return '';
                    },
                },
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

    // Filter Datatable
    var handleFilterDatatable = () => {
        // Select filter options
        const filterForm = document.querySelector('[data-kt-stock-product-table-filter="form"]');
        const filterButton = filterForm.querySelector('[data-kt-stock-product-table-filter="filter"]');
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
        const resetButton = document.querySelector('[data-kt-stock-product-table-filter="reset"]');

        // Reset datatable
        resetButton.addEventListener('click', function () {
            // Select filter options
            const filterForm = document.querySelector('[data-kt-stock-product-table-filter="form"]');
            const selectOptions = filterForm.querySelectorAll('select');

            // Reset select2 values -- more info: https://select2.org/programmatic-control/add-select-clear-items
            selectOptions.forEach(select => {
                $(select).val('').trigger('change');
            });

            // Reset datatable --- official docs reference: https://datatables.net/reference/api/search()
            datatable.search('').draw();
        });
    }

    return {
        // Public functions
        init: function () {
            table = document.querySelector('#kt_stock_product_table');

            if (!table) {
                return;
            }

            initStockProductsList();
            handleSearchDatatable();
            handleFilterDatatable();
            handleResetForm();
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
