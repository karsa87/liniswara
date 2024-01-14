"use strict";

// Class definition
var KTDetailTransaction = function () {
    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    var handleSearchRegency = () => {
        $('#filter-month').change(function () {
            window.location.href = window.location.origin + window.location.pathname + `?regency_month_id=${$(this).val()}`;
        });
    }

    return {
        // Public functions
        init: function () {
            handleSearchRegency();
        },
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTDetailTransaction.init();
});
