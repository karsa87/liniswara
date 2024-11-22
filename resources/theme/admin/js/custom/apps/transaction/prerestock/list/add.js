"use strict";

// Class definition
var KTAppEcommerceSaveProduct = function () {
    let quill = null;

    // Private functions
    // Init quill editor
    const initQuill = () => {
        // Define all elements for quill editor
        const elements = [
            '#kt_ecommerce_add_prerestock_description',
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

            let prerestockDescription = document.querySelector('textarea[name="prerestock_notes"]').value;
            quill.pasteHTML(prerestockDescription);
        });
    }

    // Init condition select2
    const initConditionsSelect2 = () => {
        // Tnit new repeating condition types
        const allConditionTypes = document.querySelectorAll('[data-kt-ecommerce-catalog-add-prerestock="prerestock_option"]');
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
    }

    // Init form repeater --- more info: https://github.com/DubFriend/jquery.repeater
    const initFormRepeater = () => {
        $('#prerestock_details').repeater({
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
                initConditionsChangeType();
            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            },
        });
    }

    // Init condition select2
    const initConditionsProductSelect2 = () => {
        // Tnit new repeating condition types
        const allConditionTypes = document.querySelectorAll('[data-kt-ecommerce-catalog-add-prerestock="product_option"]');
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
                                var excludeIds = $('.prerestock_details_select_product').map((_,el) => el.value).get();

                                return {
                                    q: $.trim(params.term),
                                    exid: $.trim(JSON.stringify(excludeIds)),
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

                    $(type).on("change", function (e) {
                        let data = $(this).select2('data');
                        if (data.length > 0) {
                            data = data[0];

                            $(this).closest("tr").children()[1].innerHTML = data.code;
                            $(this).closest("tr").children()[2].innerHTML = data.stock;
                            $(this).closest("tr").children()[3].innerHTML = data.total_stock_need;
                        }

                        $(this).closest("tr").find('select.prerestock_details_select_type').removeAttr('disabled');
                        $(this).closest("tr").find('input.prerestock_detail_qty').removeAttr('disabled');
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
        // Tnit new repeating condition types
        $(".prerestock_detail_qty").on('change', function (e) {
            let dataSelected = $(this).closest("tr").find('select.prerestock_details_select_product').select2('data');
            let stock = dataSelected[0].stock;
            let type = $(this).closest("tr").find('select.prerestock_details_select_type').val();
            let qty = $(this).closest("tr").find('input.prerestock_detail_qty').val();
            var stockNow = 0;
            if (type == 1) { // stock add
                stockNow = parseInt(stock) + parseInt(qty);
            } else if (type == 2) { // stock minus
                stockNow = parseInt(stock) - parseInt(qty);
            }

            if (stockNow < 0) {
                Swal.fire({
                    title: "Stok tidak mencukupi",
                    text: "Stok maksimal yang dapat dikurangi hanya: " + stock,
                    icon: "warning",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
                $(this).closest("tr").find('input.prerestock_detail_qty').val(stock);
            }
        });
    }

    // Init condition select2
    const initConditionsChangeType = () => {
        // Tnit new repeating condition types
        $(".prerestock_details_select_type").on('change', function (e) {
            $(this).closest("tr").find('input.prerestock_detail_qty').val(1);
        });
    }

    // Submit form handler
    const handleSubmit = () => {
        KTApp.showPageLoading();
        // Define variables
        let validator;

        // Get elements
        const form = document.getElementById('kt_ecommerce_add_prerestock_form');
        const submitButton = document.getElementById('kt_ecommerce_add_prerestock_submit');

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'prerestock_date': {
                        validators: {
                            notEmpty: {
                                message: 'Tanggal harus diisi'
                            }
                        }
                    },
                    'prerestock_branch_id': {
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

                        form.querySelector('textarea[name="prerestock_notes"]').value = quill.root.innerHTML;
                        form.querySelector('textarea[name="prerestock_notes"]').text = quill.root.innerHTML;

                        let param = new FormData(form);
                        // console.log(JSON.stringify(Object.fromEntries(param)));
                        let formSubmit = null;
                        if (param.get('prerestock_id') != null && param.get('prerestock_id') != undefined  && param.get('prerestock_id') != '') {
                            param.append('_method', 'PUT');

                            formSubmit = axios.post(
                                submitButton.closest('form').getAttribute('action-update') + '/' + param.get('prerestock_id'),
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
            initConditionsChangeType();

            // Handle forms
            handleSubmit();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTAppEcommerceSaveProduct.init();
});
