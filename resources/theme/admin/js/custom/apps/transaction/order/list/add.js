"use strict";

// Class definition
var KTAppEcommerceSaveProduct = function () {
    let quill = null;

    // Private functions
    // Init quill editor
    const initQuill = () => {
        // Define all elements for quill editor
        const elements = [
            '#kt_ecommerce_add_order_notes',
            '#kt_ecommerce_add_order_notes_staff',
        ];

        // Loop all elements
        elements.forEach(element => {
            // Get quill element
            quill = document.querySelector(element);

            // Break if element not found
            if (!quill) {
                return;
            }

            // Init quill --- more info: https://quilljs.com/docs/quickstart/
            quill = new Quill(element, {
                modules: {
                    toolbar: [
                        [{
                            header: [1, 2, false]
                        }],
                        ['size', 'bold', 'italic', 'underline'],
                    ]
                },
                placeholder: 'Type your text here...',
                theme: 'snow' // or 'bubble'
            });

            let orderDescription = document.querySelector('textarea[name="order_notes"]').value;
            quill.pasteHTML(orderDescription);
        });
    }

    // Init condition select2
    const initConditionsSelect2 = () => {
        // Tnit new repeating condition types
        const allConditionTypes = document.querySelectorAll('[data-kt-ecommerce-catalog-add-order="order_option"]');
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
                } else {
                    $(type).select2({
                        minimumResultsForSearch: -1
                    });
                }
            }
        });

        $('#form-select-customer').on("change", function (e) {
            if ($(this).select2('data').length > 0) {
                let selectedData = $(this).select2('data')[0];

                if (
                    selectedData.addresses != null
                    && selectedData.addresses.length > 0
                ) {
                    $('#form-select-customer-address').html('').select2({data: selectedData.addresses});
                }
            }
        });

        $('#form-select-discount-type').on('change', function () {
            let selected = $(this).val();
            if (selected == 1) {
                $('input[name="order_discount_price"]').val('');
                $('#div-discount-percentage').show();
                $('#div-discount-price').hide();
            } else if (selected == 2) {
                $('input[name="order_discount_percentage]').val('');
                $('#div-discount-percentage').hide();
                $('#div-discount-price').show();
            } else {
                $('#div-discount-percentage').hide();
                $('#div-discount-price').hide();
                $('input[name="order_discount_percentage]').val('');
                $('input[name="order_discount_price"]').val('');
            }
        });
        $('#form-select-discount-type').change();

        $('#form-select-zone').on("change", function (e) {
            const allConditionTypes = document.querySelectorAll('.order_details_select_product');
            allConditionTypes.forEach(type => {
                KTApp.showPageLoading();
                $(type).val($(type).val()).trigger('change');
                let data = $(type).data();

                if (data != undefined) {
                    $(type).closest("tr").children()[1].innerHTML = data.code;
                    $(type).closest("tr").children()[2].innerHTML = data.stock;

                    let price = data.price;
                    let discount = data.discount;
                    if ($('#form-select-zone').val() == 2) { //zone 2
                        price = data.price_zone_2;
                        discount = data.discount_zone_2;
                    }

                    $(type).closest("tr").children()[3].querySelector('span').innerHTML = price.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                    $(type).closest("tr").children()[3].querySelector('input.order_details_price').value = price;

                    $(type).closest("tr").children()[4].querySelector('span').innerHTML = discount.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                    $(type).closest("tr").children()[4].querySelector('span.text-muted').innerHTML = data.discount_description;
                    $(type).closest("tr").children()[4].querySelector('input.order_details_discount').value = discount;
                    $(type).closest("tr").children()[4].querySelector('input.order_details_discount_description').value = data.discount_description;

                    let total = (price * 1) - (discount * 1);
                    $(type).closest("tr").children()[6].innerHTML = total.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                }

                KTApp.hidePageLoading();
            });
        });
    }

    // Init form repeater --- more info: https://github.com/DubFriend/jquery.repeater
    const initFormRepeater = () => {
        $('#order_details').repeater({
            initEmpty: false,

            defaultValues: {
                'text-input': 'foo'
            },

            show: function () {
                $(this).slideDown();
                // Init select2 on new repeated items
                initConditionsProductSelect2();
                initConditionsQuantity();
            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            },
        });
    }

    // Init condition select2
    const initConditionsProductSelect2 = () => {
        // Tnit new repeating condition types
        const allConditionTypes = document.querySelectorAll('[data-kt-ecommerce-catalog-add-order="product_option"]');
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
                                var excludeIds = $('.order_details_select_product').map((_,el) => el.value).get();
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

                            // I set `staff_constant` field here
                            templateSelection: function (data, container) {
                                $(data.element).attr('data-price', data.price);
                                $(data.element).attr('data-discount_description', data.discount_description);
                                $(data.element).attr('data-discount', data.discount);
                                $(data.element).attr('data-price_zone_2', data.price_zone_2);
                                $(data.element).attr('data-discount_zone_2', data.discount_zone_2);
                                $(data.element).attr('data-code', data.code);
                                $(data.element).attr('data-stock', data.stock);
                                return data.text;
                            },
                            cache: true
                        }
                    });

                    $(type).on("change", function (e) {
                        let data = $(this).select2('data');

                        if (data.length > 0 && $(this).val() != undefined && $(this).val() != null && $(this).val() != '') {
                            if ($(data[0].element).data().code != undefined) {
                                data = $(data[0].element).data();
                            } else {
                                data = data[0];
                            }

                            $(type).closest("tr").children()[1].innerHTML = data.code;
                            $(type).closest("tr").children()[2].innerHTML = data.stock;

                            let price = data.price;
                            let discount = data.discount;
                            if ($('#form-select-zone').val() == 2) { //zone 2
                                price = data.price_zone_2;
                                discount = data.discount_zone_2;
                            }

                            $(type).closest("tr").children()[3].querySelector('span').innerHTML = price.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                            $(type).closest("tr").children()[3].querySelector('input.order_details_price').value = price;


                            $(type).closest("tr").children()[4].querySelector('span').innerHTML = discount.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                            $(type).closest("tr").children()[4].querySelector('span.text-muted').innerHTML = data.discount_description;
                            $(type).closest("tr").children()[4].querySelector('input.order_details_discount').value = discount;
                            $(type).closest("tr").children()[4].querySelector('input.order_details_discount_description').value = data.discount_description;

                            let total = (price * 1) - (discount * 1);
                            $(type).closest("tr").children()[6].innerHTML = total.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });

                            $(this).closest("tr").find('input.order_detail_qty').removeAttr('disabled');
                        } else {
                            $(type).closest("tr").children()[1].innerHTML = '-';
                            $(type).closest("tr").children()[2].innerHTML = '-';

                            $(type).closest("tr").children()[3].querySelector('span').innerHTML = (0).toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                            $(type).closest("tr").children()[3].querySelector('input.order_details_price').value = 0;


                            $(type).closest("tr").children()[4].querySelector('span').innerHTML = (0).toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
                            $(type).closest("tr").children()[4].querySelector('span.text-muted').innerHTML = '';
                            $(type).closest("tr").children()[4].querySelector('input.order_details_discount').value = 0;
                            $(type).closest("tr").children()[4].querySelector('input.order_details_discount_description').value = '';

                            $(type).closest("tr").children()[6].innerHTML = (0).toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });

                            $(this).closest("tr").find('input.order_detail_qty').attr('disabled');
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
        $(".order_detail_qty").on('change', function (e) {
            let productEl = $(this).closest("tr").find('span.order_details_select_product');
            let dataSelected = $(productEl).data();

            let qty = $(this).closest("tr").find('input.order_detail_qty').val();

            let price = dataSelected.price;
            let discount = dataSelected.discount;
            if ($('#form-select-zone').val() == 2) { //zone 2
                price = dataSelected.price_zone_2;
                discount = dataSelected.discount_zone_2;
            }

            let total = (price * qty) - (discount * qty);
            $(this).closest("tr").children()[6].innerHTML = total.toLocaleString('in-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
        });
    }

    // Submit form handler
    const handleSubmit = () => {
        KTApp.showPageLoading();
        // Define variables
        let validator;

        // Get elements
        const form = document.getElementById('kt_ecommerce_add_order_form');
        const submitButton = document.getElementById('kt_ecommerce_add_order_submit');

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'order_date': {
                        validators: {
                            notEmpty: {
                                message: 'Tanggal harus diisi'
                            }
                        }
                    },
                    'order_branch_id': {
                        validators: {
                            notEmpty: {
                                message: 'Gudang harus diisi'
                            }
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }
        );

        // Handle submit button
        submitButton.addEventListener('click', e => {
            e.preventDefault();

            // Validate form before submit
            if (validator) {
                validator.validate().then(function (status) {
                    if (status == 'Valid') {
                        submitButton.setAttribute('data-kt-indicator', 'on');

                        // Disable submit button whilst loading
                        submitButton.disabled = true;

                        form.querySelector('textarea[name="order_notes"]').value = quill.root.innerHTML;
                        form.querySelector('textarea[name="order_notes"]').text = quill.root.innerHTML;

                        let param = new FormData(form);
                        // console.log(JSON.stringify(Object.fromEntries(param)));
                        let formSubmit = null;
                        if (param.get('order_id') != null && param.get('order_id') != undefined  && param.get('order_id') != '') {
                            param.append('_method', 'PUT');

                            formSubmit = axios.post(
                                submitButton.closest('form').getAttribute('action-update') + '/' + param.get('order_id'),
                                param,
                                {
                                    headers: {
                                        "Content-Type": "multipart/form-data",
                                    }
                                }
                            )
                        } else {
                            formSubmit = axios.post(
                                submitButton.closest('form').getAttribute('action'),
                                param,
                                {
                                    headers: {
                                        "Content-Type": "multipart/form-data",
                                    }
                                }
                            );
                        }

                        formSubmit.then(function (response) {
                            if (response) {
                                form.reset();

                                // Show message popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                                Swal.fire({
                                    text: "Form has been successfully submitted!",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then(function (result) {
                                    if (result.isConfirmed) {
                                        window.location = form.getAttribute("data-kt-redirect");
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
                    } else {
                        Swal.fire({
                            html: "Sorry, looks like there are some errors detected, please try again. <br/><br/>Please note that there may be errors in the <strong>General</strong> or <strong>Advanced</strong> tabs",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    }
                });
            }
        })
    }

    const initForm = () => {
        // Init flatpickr
        $('#kt_ecommerce_edit_order_date').flatpickr({
            altInput: true,
            altFormat: "d F, Y",
            dateFormat: "Y-m-d",
        });
    }

    // Public methods
    return {
        init: function () {
            // Init forms
            initForm();
            initQuill();
            initConditionsSelect2();
            initFormRepeater();
            initConditionsProductSelect2();
            initConditionsQuantity();

            // Handle forms
            handleSubmit();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTAppEcommerceSaveProduct.init();
});
