"use strict";

// Class definition
var KTUsersAddBranch = function () {
    // Shared variables
    const element = document.getElementById('kt_modal_add_area');
    const form = element.querySelector('#kt_modal_add_area_form');
    const modal = new bootstrap.Modal(element);

    // Init add schedule modal
    var initAddBranch = () => {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'area_name': {
                        validators: {
                            notEmpty: {
                                message: 'Branch name is required'
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

        // Close button handler
        const closeButton = element.querySelector('[data-kt-areas-modal-action="close"]');
        closeButton.addEventListener('click', e => {
            e.preventDefault();

            Swal.fire({
                text: "Are you sure you would like to close?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, close it!",
                cancelButtonText: "No, return",
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-active-light"
                }
            }).then(function (result) {
                if (result.value) {
                    modal.hide(); // Hide modal
                }
            });
        });

        // Cancel button handler
        const cancelButton = element.querySelector('[data-kt-areas-modal-action="cancel"]');
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
                    modal.hide(); // Hide modal
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

        // Submit button handler
        const submitButton = element.querySelector('[data-kt-areas-modal-action="submit"]');
        submitButton.addEventListener('click', function (e) {
            // Prevent default button action
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
                        let param = new FormData(form);
                        let formData = {};
                        for (const pair of param.entries()) {
                            if (pair[1] instanceof FileList) {
                                formData[pair[0]] = [];
                                for (const file of pair[1]) {
                                    formData[pair[0]].push(file);
                                }
                            } else if (pair[0].includes('[')) {
                                const key = pair[0].split('[')[0];
                                const index = pair[0].split('[')[1].replace(']', '');
                                if (!formData[key]) {
                                    formData[key] = {};
                                }
                                formData[key][index] = pair[1];
                            } else {
                                formData[pair[0]] = pair[1];
                            }
                        }
                        axios.post(submitButton.closest('form').getAttribute('action'), formData).then(function (response) {
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
                            KTUsersBranchsList.refresh();
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
    }

    // Init condition select2
    const initConditionsSelect2 = () => {
        $('#area_add_province_id').select2({
            dropdownParent: $('#kt_modal_add_area'),
            minimumInputLength: -1,
            ajax: {
                url: $('#area_add_province_id').data('url'),
                dataType: 'json',
                data: function (params) {
                    return {
                        q: $.trim(params.term),
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
        $('#area_add_regency_id').select2({
            dropdownParent: $('#kt_modal_add_area'),
            minimumInputLength: -1,
            ajax: {
                url: $('#area_add_regency_id').data('url'),
                dataType: 'json',
                data: function (params) {
                    return {
                        q: $.trim(params.term),
                        province_id: $('#area_add_province_id').val(),
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
        $('#area_add_district_id').select2({
            dropdownParent: $('#kt_modal_add_area'),
            minimumInputLength: -1,
            ajax: {
                url: $('#area_add_district_id').data('url'),
                dataType: 'json',
                data: function (params) {
                    return {
                        q: $.trim(params.term),
                        regency_id: $('#area_add_regency_id').val(),
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
        $('#area_add_village_id').select2({
            dropdownParent: $('#kt_modal_add_area'),
            minimumInputLength: -1,
            ajax: {
                url: $('#area_add_village_id').data('url'),
                dataType: 'json',
                data: function (params) {
                    return {
                        q: $.trim(params.term),
                        district_id: $('#area_add_district_id').val(),
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
    }

    return {
        // Public functions
        init: function () {
            initAddBranch();
            initConditionsSelect2();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTUsersAddBranch.init();
});
