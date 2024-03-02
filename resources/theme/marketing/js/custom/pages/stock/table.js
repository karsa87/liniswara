"use strict";

// Class definition
var KTAppStock = function () {
    const calculateTotal = () => {
        KTApp.showPageLoading();
        let totalAmountDetail = 0;
        let no = 1;
        $('[data-repeater-item]').each(function () {
            let amount = $(this).find('.amount_detail').text();
            amount = amount.replace(/[^0-9]/g, "");
            amount = parseInt(amount);
            amount = isNaN(amount) ? 0 : amount;
            totalAmountDetail += amount;

            $(this).find('.number_detail').text(no);
            no++;
        });

        totalAmountDetail = typeof totalAmountDetail == 'number' ? totalAmountDetail : 0;
        $('#total-amount-detail').text(totalAmountDetail.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 }))
        KTApp.hidePageLoading();
    }

    // Init form repeater --- more info: https://github.com/DubFriend/jquery.repeater
    const initFormRepeater = () => {
        $('#product_details').repeater({
            initEmpty: false,
            isFirstItemUndeletable: true,

            defaultValues: {
                'text-input': 'foo'
            },

            show: function () {
                $(this).slideDown();
                // Init select2 on new repeated items
                initConditionsProductSelect2();
                initConditionsQuantity();
                calculateTotal();
            },

            hide: function (deleteElement) {
                deleteElement();

                calculateTotal();
            },
        });
    }

    // Init condition select2
    const initConditionsProductSelect2 = () => {
        // Tnit new repeating condition types
        const allConditionTypes = document.querySelectorAll('[data-kt-ecommerce-catalog-add-product="product_stock_option"]');
        allConditionTypes.forEach(type => {
            if ($(type).hasClass("select2-hidden-accessible")) {
                return;
            } else {
                if ($(type).data('url')) {
                    $(type).select2({
                        minimumInputLength: -1,
                        ajax: {
                            url: $(type).data('url'),
                            dataType: 'json',
                            data: function (params) {
                                var excludeIds = $('.stock_details_select_product').map((_,el) => el.value).get();
                                var zone = $('#form-select-zone').val();

                                return {
                                    q: $.trim(params.term),
                                    exid: $.trim(JSON.stringify(excludeIds)),
                                    zone: $.trim(zone),
                                };
                            },
                            processResults: function(data) {
                                // Transforms the top-level key of the response object from 'items' to 'results'
                                return {
                                    results: data.items
                                };
                            },
                            cache: true
                        },
                        // I set `staff_constant` field here
                        templateSelection: function (data, container) {
                            $(data.element).attr('data-price', data.price);
                            $(data.element).attr('data-discount_description', data.discount_description);
                            $(data.element).attr('data-discount', data.discount);
                            $(data.element).attr('data-price_zone_2', data.price_zone_2);
                            $(data.element).attr('data-discount_zone_2', data.discount_zone_2);
                            $(data.element).attr('data-code', data.code);
                            $(data.element).attr('data-stock', data.stock);
                            return data.text.substring(0, 20);
                        },
                    });

                    $(type).on("change", function (e) {
                        let data = $(this).select2('data');

                        if (data.length > 0 && $(this).val() != undefined && $(this).val() != null && $(this).val() != '') {
                            if ($(data[0].element).data().code != undefined) {
                                data = $(data[0].element).data();
                            } else {
                                data = data[0];
                            }

                            $(type).closest("tr").children()[2].innerHTML = data.code;

                            $(type).closest("tr").children()[4].querySelector('span').innerHTML = data.stock;

                            let price = data.price;
                            let discount = data.discount;
                            if ($('#form-select-zone').val() == 2) { //zone 2
                                price = data.price_zone_2;
                                discount = data.discount_zone_2;
                            }

                            $(type).closest("tr").children()[5].querySelector('span').innerHTML = price.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                            $(type).closest("tr").children()[5].querySelector('input.stock_details_price').value = price;

                            let total = (price * 1);
                            $(type).closest("tr").children()[6].innerHTML = total.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });

                            $(this).closest("tr").find('input.stock_detail_qty').removeAttr('disabled');
                            $(this).closest("tr").find('input.kt_ecommerce_stock_estimation_date').removeAttr('disabled');
                            calculateTotal();
                        } else {
                            $(type).closest("tr").children()[2].innerHTML = '-';
                            $(type).closest("tr").children()[3].querySelector('input.stock_detail_qty').value = 0;

                            $(type).closest("tr").children()[4].querySelector('span').innerHTML = (0).toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });

                            $(type).closest("tr").children()[5].querySelector('span').innerHTML = (0).toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                            $(type).closest("tr").children()[5].querySelector('input.stock_details_price').value = 0;

                            $(type).closest("tr").children()[6].innerHTML = (0).toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });

                            $(this).closest("tr").find('input.stock_detail_qty').attr('disabled');
                            $(this).closest("tr").find('input.kt_ecommerce_stock_estimation_date').attr('disabled');
                        }
                    });
                } else {
                    $(type).select2({
                        minimumResultsForSearch: -1
                    });
                }
            }
        });
    }

    // Init condition select2
    const initConditionsQuantity = () => {
        // // Tnit new repeating condition types
        $(".stock_detail_qty").on('change', function (e) {
            let productEl = $(this).closest("tr").find('select.stock_details_select_product');
            let data = $(productEl).select2('data');
            let dataSelected = data[0];
            if (data.length > 0 && $(this).val() != undefined && $(this).val() != null && $(this).val() != '') {
                if ($(data[0].element).data().code != undefined) {
                    dataSelected = productEl.find(':selected').data();
                }
            }

            let qty = $(this).closest("tr").find('input.stock_detail_qty').val();

            let price = dataSelected.price;
            let discount = dataSelected.discount;
            if ($('#form-select-zone').val() == 2) { //zone 2
                price = dataSelected.price_zone_2;
                discount = dataSelected.discount_zone_2;
            }

            let total = (price * qty) - (discount * qty);
            $(this).closest("tr").children()[6].innerHTML = total.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });

            calculateTotal();
        });
    }

    // Submit form handler
    const handleSubmit = () => {
        KTApp.showPageLoading();
        // Get elements
        const form = document.getElementById('kt_ecommerce_stock_form');
        const submitButton = document.getElementById('kt_ecommerce_stock_submit_excel');

        // Handle submit button
        submitButton.addEventListener('click', e => {
            e.preventDefault();

            submitButton.setAttribute('data-kt-indicator', 'on');

            // Disable submit button whilst loading
            submitButton.disabled = true;

            let param = new FormData(form);
            // console.log(JSON.stringify(Object.fromEntries(param)));
            axios.post(
                submitButton.closest('form').getAttribute('action'),
                param,
                {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    }
                }
            ).then(function (response) {
                if (response) {
                    if (response.data.excel) {
                        window.open(response.data.excel);
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
                    title: "Failed submit",
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
                submitButton.removeAttribute('data-kt-indicator');

                // Enable button
                submitButton.disabled = false;
                KTApp.hidePageLoading();
            });
        })
    }

    const initForm = () => {
        $('.kt_ecommerce_stock_estimation_date').flatpickr({
            altInput: true,
            altFormat: "d F, Y",
            dateFormat: "Y-m-d",
        });

        $('#form-select-zone').on("change", function (e) {
            const allConditionTypes = document.querySelectorAll('[data-kt-ecommerce-catalog-add-product="product_stock_option"]');
            allConditionTypes.forEach(type => {
                KTApp.showPageLoading();
                $(type).val($(type).val()).trigger('change');
                let data = $(type).select2('data');
                if (data.length > 0 && $(this).val() != undefined && $(this).val() != null && $(this).val() != '') {
                    if ($(data[0].element).data().code != undefined) {
                        data = $(data[0].element).data();
                    } else {
                        data = data[0];
                    }

                    $(type).closest("tr").children()[2].innerHTML = data.code;
                    $(type).closest("tr").children()[4].querySelector('span').innerHTML = data.stock;

                    let price = data.price;
                    let discount = data.discount;
                    if ($('#form-select-zone').val() == 2) { //zone 2
                        price = data.price_zone_2;
                        discount = data.discount_zone_2;
                    }

                    $(type).closest("tr").children()[5].querySelector('span').innerHTML = price.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                    $(type).closest("tr").children()[5].querySelector('input.stock_details_price').value = price;

                    let qty = $(type).closest("tr").children()[3].querySelector('input.stock_detail_qty').value;

                    let total = (price * 1) * qty;

                    $(type).closest("tr").children()[6].innerHTML = total.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                }

                KTApp.hidePageLoading();
            });
        });
    }

    // Public methods
    return {
        init: function () {
            // Init forms
            initForm();
            initFormRepeater();
            initConditionsProductSelect2();
            initConditionsQuantity();

            // Handle forms
            handleSubmit();
            calculateTotal();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTAppStock.init();
});
