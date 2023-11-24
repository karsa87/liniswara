"use strict";

// Class definition
var KTRegionSearchModal = function () {
    // Define shared variables
    var table = document.getElementById('kt_table_regions');
    var datatable;
    const modal = new bootstrap.Modal(document.getElementById('kt_modal_search_region'));

    // Init add schedule modal
    var initModal = () => {
        // Init datatable --- more info on datatables: https://datatables.net/manual/
        datatable = $(table).DataTable({
            searchDelay: 1000,
            processing: true,
            serverSide: true,
            stateSave: true,
            order: [],
            ajax: {
                url: table.dataset.url
            },
            columns: [
                { data: 'province' },
                { data: 'regency' },
                { data: 'district' },
                { data: 'village' },
                { data: null },
            ],
            columnDefs: [
                {
                    targets: 0,
                    orderable: false,
                    render: function (data, type, row) {
                        return `<span data-id="${row.province.id}">${row.province.name}</span>`;
                    },
                },
                {
                    targets: 1,
                    orderable: false,
                    render: function (data, type, row) {
                        return `<span data-id="${row.regency.id}">${row.regency.name}</span>`;
                    },
                },
                {
                    targets: 2,
                    orderable: false,
                    render: function (data, type, row) {
                        return `<span data-id="${row.district.id}">${row.district.name}</span>`;
                    },
                },
                {
                    targets: 3,
                    orderable: false,
                    render: function (data, type, row) {
                        return `<span data-id="${row.village.id}">${row.village.name}</span>`;
                    },
                },
                {
                    targets: -1,
                    data: null,
                    orderable: false,
                    className: 'text-center',
                    render: function (data, type, row) {
                        return `
                            <button class="btn btn-icon btn-light-primary w-100px" data-kt-region-table-filter="choose_row" data-id="${data.id}">
                                Pilih
                            </button>
                        `;
                    },
                },
            ],
        });

        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
        datatable.on('draw', function () {
            handleChooseRegion();
        });
    }

    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    var handleSearchDatatable = () => {
        const filterSearch = document.querySelector('[data-kt-region-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Update region
    var handleChooseRegion = () => {
        // Select all update buttons
        const chooseButtons = table.querySelectorAll('[data-kt-region-table-filter="choose_row"]');

        chooseButtons.forEach(d => {
            // Update button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                // Select parent row
                const parent = e.target.closest('tr');

                let province = parent.querySelectorAll('td')[0].innerText;
                let regency = parent.querySelectorAll('td')[1].innerText;
                let district = parent.querySelectorAll('td')[2].innerText;
                let village = parent.querySelectorAll('td')[3].innerText;

                var provinceId = parent.querySelectorAll('td')[0].getElementsByTagName('span')[0].dataset.id;
                var regencyId = parent.querySelectorAll('td')[1].getElementsByTagName('span')[0].dataset.id;
                var districtId = parent.querySelectorAll('td')[2].getElementsByTagName('span')[0].dataset.id;
                var villageId = parent.querySelectorAll('td')[3].getElementsByTagName('span')[0].dataset.id;

                let target = table.dataset.targetElement ?? '';
                document.querySelector(`[data-kt-region="${target}region_description"]`).innerHTML = `${village}, Kec. ${district} <br/> ${regency} - ${province}`;

                document.querySelector(`[data-kt-region="${target}province_id"]`).value = provinceId;
                document.querySelector(`[data-kt-region="${target}regency_id"]`).value = regencyId;
                document.querySelector(`[data-kt-region="${target}district_id"]`).value = districtId;
                document.querySelector(`[data-kt-region="${target}village_id"]`).value = villageId;

                document.getElementById('kt_modal_search_region_btn_close').click();
            })
        });
    }

    return {
        // Public functions
        init: function () {
            initModal();
            handleSearchDatatable();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTRegionSearchModal.init();
});
