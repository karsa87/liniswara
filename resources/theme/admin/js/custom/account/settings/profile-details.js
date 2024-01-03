"use strict";

// Class definition
var KTAccountSettingsProfileDetails = function () {
    // Private variables
    var form;
    var submitButton;
    var validation;
    var dropzone;

    // Init DropzoneJS --- more info:
    const initDropzone = () => {
        // For more info about Dropzone plugin visit:  https://www.dropzonejs.com/#usage
		dropzone = new Dropzone("#kt_modal_add_update_user_profile_photo", {
			url: $('meta[name=file_upload_url]').attr("content"), // Set the url for your upload script location
            paramName: "file", // The name that will be used to transfer the file
            maxFiles: 1,
            maxFilesize: 2, // MB
            addRemoveLinks: true,
            acceptedFiles: ".png,.jpg,.jpeg",
            headers:{
                'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr("content")
            },
            accept: function(file, done) {
                if (file.name == "justinbieber.jpg") {
                    done("Naha, you don't.");
                } else {
                    done();
                }
            },
            init: function() {
                this.on('success',function(file, result){
                    document.querySelector('input[name="profile_photo_id"]').value = result.id;

                    Swal.fire({
                        title: "Upload",
                        text: "Complete upload file",
                        icon: 'success',
                    });

                    return true;
                });

                this.on("error", function(file, message){
                    Swal.fire({
                        title: "Upload",
                        text: message,
                        icon: 'error',
                    });
                });

                this.on("removedfile", function (file) {
                    if (document.querySelector("input[name='profile_photo_id']").value == '') {
                        return false;
                    }

                    Swal.fire({
                        text: "Apakah anda yakin menghapus file ini ?",
                        icon: "warning",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonText: "Ya",
                        cancelButtonText: "Tidak",
                        customClass: {
                            confirmButton: "btn fw-bold btn-danger",
                            cancelButton: "btn fw-bold btn-active-light-primary"
                        }
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            axios.delete($('meta[name=file_delete_url]').attr("content") + '/' + document.querySelector('input[name="profile_photo_id"]').value, {})
                            .then(response => {
                                if (response) {
                                    Swal.fire({
                                        text: "You have deleted!.",
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn fw-bold btn-primary",
                                        }
                                    }).then(function () {
                                        // Remove current row
                                        return true;
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
                                text: "File was not deleted.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                }
                            });
                        }
                    });
                });
            }
		});

        if (document.querySelector('input[name="profile_photo_id"]').value) {
            dropzone.files.forEach(function(file) {
                file.previewElement.remove();
            });
            dropzone.removeAllFiles();
            $('div.dz-success').remove();

            let name = document.getElementById('profile_photo_name').value;
            let full_url = document.getElementById('profile_photo_full_url').value;
            var mockFile = { name: name, size: 100, src: full_url };
            dropzone.options.addedfile.call(dropzone, mockFile);
            dropzone.options.thumbnail.call(dropzone, mockFile, mockFile.src);
            mockFile.previewElement.classList.add('dz-success');
            mockFile.previewElement.classList.add('dz-complete');
        }
    }

    // Private functions
    var initValidation = function () {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validation = FormValidation.formValidation(
            form,
            {
                fields: {
                    name: {
                        validators: {
                            notEmpty: {
                                message: 'Nama harus diisi'
                            }
                        }
                    },
                    phone: {
                        validators: {
                            notEmpty: {
                                message: 'Phone harus diisi'
                            }
                        }
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    submitButton: new FormValidation.plugins.SubmitButton(),
                    //defaultSubmit: new FormValidation.plugins.DefaultSubmit(), // Uncomment this line to enable normal button submit after form validation
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }
        );
    }

    var handleForm = function () {
        submitButton.addEventListener('click', function (e) {
            e.preventDefault();

            validation.validate().then(function (status) {
                if (status == 'Valid') {
                    // Show loading indication
                    submitButton.setAttribute('data-kt-indicator', 'on');

                    // Disable button to avoid multiple click
                    submitButton.disabled = true;

                    // Check axios library docs: https://axios-http.com/docs/intro
                    let param = new FormData(form);
                    param.append('_method', 'PUT');
                    axios.post(form.getAttribute('action'), param).then(function (response) {
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
                            confirmButton: "btn fw-bold btn-light-primary"
                        }
                    });
                }
            });
        });
    }

    // Public methods
    return {
        init: function () {
            form = document.getElementById('kt_account_profile_details_form');

            if (!form) {
                return;
            }

            submitButton = form.querySelector('#kt_account_profile_details_submit');

            initValidation();
            initDropzone();
            handleForm();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function() {
    KTAccountSettingsProfileDetails.init();
});
