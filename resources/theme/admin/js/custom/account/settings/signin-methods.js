"use strict";

// Class definition
var KTAccountSettingsSigninMethods = function () {
    var signInForm;
    var signInMainEl;
    var signInEditEl;
    var passwordMainEl;
    var passwordEditEl;
    var signInChangeEmail;
    var signInCancelEmail;
    var passwordChange;
    var passwordCancel;

    var toggleChangeEmail = function () {
        signInMainEl.classList.toggle('d-none');
        signInChangeEmail.classList.toggle('d-none');
        signInEditEl.classList.toggle('d-none');
    }

    var toggleChangePassword = function () {
        passwordMainEl.classList.toggle('d-none');
        passwordChange.classList.toggle('d-none');
        passwordEditEl.classList.toggle('d-none');
    }

    // Private functions
    var initSettings = function () {
        if (!signInMainEl) {
            return;
        }

        // toggle UI
        signInChangeEmail.querySelector('button').addEventListener('click', function () {
            toggleChangeEmail();
        });

        signInCancelEmail.addEventListener('click', function () {
            toggleChangeEmail();
        });

        passwordChange.querySelector('button').addEventListener('click', function () {
            toggleChangePassword();
        });

        passwordCancel.addEventListener('click', function () {
            toggleChangePassword();
        });
    }

    var handleChangeEmail = function (e) {
        var validation;

        if (!signInForm) {
            return;
        }

        validation = FormValidation.formValidation(
            signInForm,
            {
                fields: {
                    emailaddress: {
                        validators: {
                            notEmpty: {
                                message: 'Email harus diisi'
                            },
                            emailAddress: {
                                message: 'Alamat email harus valid'
                            }
                        }
                    },

                    confirmemailpassword: {
                        validators: {
                            notEmpty: {
                                message: 'Password harus diisi'
                            }
                        }
                    }
                },

                plugins: { //Learn more: https://formvalidation.io/guide/plugins
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row'
                    })
                }
            }
        );

        signInForm.querySelector('#kt_signin_submit').addEventListener('click', function (e) {
            e.preventDefault();
            var submitButton = e.target;

            validation.validate().then(function (status) {
                if (status == 'Valid') {
                    // Show loading indication
                    submitButton.setAttribute('data-kt-indicator', 'on');

                    // Disable button to avoid multiple click
                    submitButton.disabled = true;

                    // Check axios library docs: https://axios-http.com/docs/intro
                    let param = new FormData(signInForm);
                    param.append('_method', 'PUT');
                    axios.post(signInForm.getAttribute('action'), param).then(function (response) {
                        if (response) {
                            signInForm.reset();

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
                                    window.location.reload();
                                    signInForm.reset();
                                    validation.resetForm(); // Reset formvalidation --- more info: https://formvalidation.io/guide/api/reset-form/
                                    toggleChangeEmail();
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
                    });
                } else {
                    swal.fire({
                        text: "Sorry, looks like there are some errors detected, please try again.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    });
                }
            });
        });
    }

    var handleChangePassword = function (e) {
        var validation;

        // form elements
        var passwordForm = document.getElementById('kt_signin_change_password');

        if (!passwordForm) {
            return;
        }

        validation = FormValidation.formValidation(
            passwordForm,
            {
                fields: {
                    currentpassword: {
                        validators: {
                            notEmpty: {
                                message: 'Password saat ini harus diisi'
                            }
                        }
                    },

                    password: {
                        validators: {
                            notEmpty: {
                                message: 'Password baru harus diisi'
                            },
                            callback: {
                                message: 'Gunakan 8 karakter atau lebih dengan campuran huruf, angka & simbol.',
                                callback: function (input) {
                                    let validatePassword = false;
                                    let password = input.value;
                                    validatePassword = password.length >= 5;

                                    // Validate lowercase letters
                                    var lowerCaseLetters = /[a-z]/g;
                                    if(password.match(lowerCaseLetters)) {
                                        validatePassword = true;
                                    } else {
                                        validatePassword = false;
                                    }

                                    // Validate capital letters
                                    var upperCaseLetters = /[A-Z]/g;
                                    if(password.match(upperCaseLetters)) {
                                        validatePassword = true;
                                    } else {
                                        validatePassword = false;
                                    }

                                    // Validate numbers
                                    var numbers = /[0-9]/g;
                                    if(password.match(numbers)) {
                                        validatePassword = true;
                                    } else {
                                        validatePassword = false;
                                    }

                                    return validatePassword;
                                }
                            }
                        }
                    },

                    password_confirmation: {
                        validators: {
                            notEmpty: {
                                message: 'Konfirmasi Password harus diisi'
                            },
                            identical: {
                                compare: function() {
                                    return passwordForm.querySelector('[name="password"]').value;
                                },
                                message: 'Kata sandi dan konfirmasinya tidak sama'
                            }
                        }
                    },
                },

                plugins: { //Learn more: https://formvalidation.io/guide/plugins
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row'
                    })
                }
            }
        );

        passwordForm.querySelector('#kt_password_submit').addEventListener('click', function (e) {
            e.preventDefault();
            let submitButton = e.target;

            validation.validate().then(function (status) {
                if (status == 'Valid') {
                    // Show loading indication
                    submitButton.setAttribute('data-kt-indicator', 'on');

                    // Disable button to avoid multiple click
                    submitButton.disabled = true;

                    // Check axios library docs: https://axios-http.com/docs/intro
                    let param = new FormData(passwordForm);
                    param.append('_method', 'PUT');
                    axios.post(passwordForm.getAttribute('action'), param).then(function (response) {
                        if (response) {
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
                                    window.location.reload();
                                    passwordForm.reset();
                                    validation.resetForm(); // Reset formvalidation --- more info: https://formvalidation.io/guide/api/reset-form/
                                    toggleChangePassword();
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
                    });
                } else {
                    swal.fire({
                        text: "Sorry, looks like there are some errors detected, please try again.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    });
                }
            });
        });
    }

    // Public methods
    return {
        init: function () {
            signInForm = document.getElementById('kt_signin_change_email');
            signInMainEl = document.getElementById('kt_signin_email');
            signInEditEl = document.getElementById('kt_signin_email_edit');
            passwordMainEl = document.getElementById('kt_signin_password');
            passwordEditEl = document.getElementById('kt_signin_password_edit');
            signInChangeEmail = document.getElementById('kt_signin_email_button');
            signInCancelEmail = document.getElementById('kt_signin_cancel');
            passwordChange = document.getElementById('kt_signin_password_button');
            passwordCancel = document.getElementById('kt_password_cancel');

            initSettings();
            handleChangeEmail();
            handleChangePassword();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function() {
    KTAccountSettingsSigninMethods.init();
});
