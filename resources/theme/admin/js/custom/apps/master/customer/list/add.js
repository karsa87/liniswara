"use strict";

// Class definition
var KTSuppliersAddSupplier = function () {
    // Shared variables
    const element = document.getElementById('kt_modal_add_customer');
    const form = element.querySelector('#kt_modal_add_customer_form');
    const modal = new bootstrap.Modal(element);

    // Init add schedule modal
    var initAddSupplier = () => {
        $('[data-kt-modal-select-region="province"]').select2({
            dropdownParent: $('#kt_modal_add_customer')
        });

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'customer_name': {
                        validators: {
                            notEmpty: {
                                message: 'Full name is required'
                            }
                        }
                    },
                    'customer_password': {
                        validators: {
                            callback: {
                                message: 'Use 5 or more characters with a mix of letters, numbers & symbols.',
                                callback: function (input) {
                                    let validatePassword = false;
                                    let password = input.value;
                                    if (
                                        form.querySelector("input[name='customer_id']").value != null
                                        && form.querySelector("input[name='customer_id']").value != undefined
                                        && form.querySelector("input[name='customer_id']").value != ''
                                        && password.length <= 0
                                    ) {
                                        return true;
                                    }

                                    validatePassword = password.length >= 5;

                                    // Validate lowercase letters
                                    var lowerCaseLetters = /[a-z]/g;
                                    if(password.match(lowerCaseLetters) && validatePassword) {
                                        validatePassword = true;
                                    } else {
                                        validatePassword = false;
                                    }

                                    // Validate capital letters
                                    var upperCaseLetters = /[A-Z]/g;
                                    if(password.match(upperCaseLetters) && validatePassword) {
                                        validatePassword = true;
                                    } else {
                                        validatePassword = false;
                                    }

                                    // Validate numbers
                                    var numbers = /[0-9]/g;
                                    if(password.match(numbers) && validatePassword) {
                                        validatePassword = true;
                                    } else {
                                        validatePassword = false;
                                    }

                                    return validatePassword;
                                }
                            }
                        }
                    },
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

        // Submit button handler
        const submitButton = element.querySelector('[data-kt-customers-modal-action="submit"]');
        submitButton.addEventListener('click', e => {
            e.preventDefault();

            // Validate form before submit
            if (validator) {
                validator.validate().then(function (status) {


                    if (status == 'Valid') {
                        // Show loading indication
                        submitButton.setAttribute('data-kt-indicator', 'on');

                        // Disable button to avoid multiple click
                        submitButton.disabled = true;

                        // Check axios library docs: https://axios-http.com/docs/intro
                        // Check axios library docs: https://axios-http.com/docs/intro
                        let param = new FormData(form);
                        let formSubmit = null;
                        if (param.get('customer_id') != null && param.get('customer_id') != undefined  && param.get('customer_id') != '') {
                            param.append('_method', 'PUT');

                            formSubmit = axios.post(
                                submitButton.closest('form').getAttribute('action-update') + '/' + param.get('customer_id'),
                                param
                            )
                        } else {
                            formSubmit = axios.post(submitButton.closest('form').getAttribute('action'), param);
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
                                        modal.hide();
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
                            KTSuppliersList.refresh();
                        });
                    } else {
                        // Show popup warning. For more info check the plugin's official documentation: https://sweetalert2.github.io/
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
                });
            }
        });

        // Cancel button handler
        const cancelButton = element.querySelector('[data-kt-customers-modal-action="cancel"]');
        cancelButton.addEventListener('click', e => {
            e.preventDefault();

            Swal.fire({
                text: "Are you sure you would like to cancel?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, cancel it!",
                cancelButtonText: "No, return",
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-active-light"
                }
            }).then(function (result) {
                if (result.value) {
                    form.reset(); // Reset form
                    modal.hide();
                } else if (result.dismiss === 'cancel') {
                    Swal.fire({
                        text: "Your form has not been cancelled!.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary",
                        }
                    });
                }
            });
        });

        // Close button handler
        const closeButton = element.querySelector('[data-kt-customers-modal-action="close"]');
        closeButton.addEventListener('click', e => {
            e.preventDefault();

            Swal.fire({
                text: "Are you sure you would like to cancel?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, cancel it!",
                cancelButtonText: "No, return",
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-active-light"
                }
            }).then(function (result) {
                if (result.value) {
                    form.reset(); // Reset form
                    modal.hide();
                } else if (result.dismiss === 'cancel') {
                    Swal.fire({
                        text: "Your form has not been cancelled!.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary",
                        }
                    });
                }
            });
        });
    }

    return {
        // Public functions
        init: function () {
            initAddSupplier();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTSuppliersAddSupplier.init();
});
