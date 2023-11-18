"use strict";

// Class definition
var KTUsersAddRole = function () {
    // Shared variables
    const element = document.getElementById('kt_modal_add_role');
    const form = element.querySelector('#kt_modal_add_role_form');
    const modal = new bootstrap.Modal(element);

    // Init add schedule modal
    var initAddRole = () => {

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'role_name': {
                        validators: {
                            notEmpty: {
                                message: 'Role name is required'
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
        const closeButton = element.querySelector('[data-kt-roles-modal-action="close"]');
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
        const cancelButton = element.querySelector('[data-kt-roles-modal-action="cancel"]');
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
         const submitButton = element.querySelector('[data-kt-roles-modal-action="submit"]');
         submitButton.addEventListener('click', function (e) {
             // Prevent default button action
             e.preventDefault();

             // Validate form before submit
             if (validator) {
                 validator.validate().then(function (status) {
                     console.log('validated!');

                     if (status == 'Valid') {
                         // Show loading indication
                         submitButton.setAttribute('data-kt-indicator', 'on');

                         // Disable button to avoid multiple click
                         submitButton.disabled = true;

                         // Check axios library docs: https://axios-http.com/docs/intro
                         axios.post(submitButton.closest('form').getAttribute('action'), new FormData(form)).then(function (response) {
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
                                         window.location.reload();
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
                             KTUsersPermissionsList.refresh();
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

    // Select all handler
    const handleSelectAll = () =>{
        // Define variables
        const selectAll = form.querySelector('#kt_roles_select_all');
        const allCheckboxes = form.querySelectorAll('[type="checkbox"]');

        // Handle check state
        selectAll.addEventListener('change', e => {

            // Apply check state to all checkboxes
            allCheckboxes.forEach(c => {
                c.checked = e.target.checked;
            });
        });
    }

    // Delete user
    var handleDeleteRows = () => {
        // Select all delete buttons
        const deleteButtons = document.getElementsByClassName('delete_row');

        deleteButtons.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                const roleName = e.target.dataset.roleName;

                // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                Swal.fire({
                    text: "Are you sure you want to delete " + roleName + "?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, delete!",
                    cancelButtonText: "No, cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    if (result.isConfirmed) {
                        axios.delete(e.target.dataset.urlDelete, {})
                        .then(response => {
                            if (response) {
                                Swal.fire({
                                    text: "You have deleted " + roleName + "!.",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn fw-bold btn-primary",
                                    }
                                }).then(function () {
                                    // Remove current row
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    text: "Sorry, looks like failed deleted, please try again.",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                });
                            }
                        })
                        .catch(error => {
                            let msg = "Sorry, failed delete, please try again.";
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
                                title: "Failed Delete",
                                text: msg,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                        });
                    } else if (result.dismiss === 'cancel') {
                        Swal.fire({
                            text: roleName + " was not deleted.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        });
                    }
                });
            })
        });
    }

    return {
        // Public functions
        init: function () {
            initAddRole();
            handleSelectAll();
            handleDeleteRows();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTUsersAddRole.init();
});
