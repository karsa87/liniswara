"use strict";

// Class definition
var KTExpeditionsAddExpedition = function () {
    // Shared variables
    const element = document.getElementById('kt_modal_add_category');
    const form = element.querySelector('#kt_modal_add_category_form');
    const modal = new bootstrap.Modal(element);
    var dropzone = '';

    // Init add schedule modal
    var initAddExpedition = () => {
        $('[name="category_parent_id"]').select2({
            dropdownParent: $('#kt_modal_add_category')
        });

        // For more info about Dropzone plugin visit:  https://www.dropzonejs.com/#usage
		dropzone = new Dropzone("#kt_modal_add_update_category_image", {
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
                    document.querySelector('input[name="category_image_id"]').value = result.id;

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
                    if (document.querySelector("input[name='category_name']").value == '') {
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
                            axios.delete($('meta[name=file_delete_url]').attr("content") + '/' + document.querySelector('input[name="category_image_id"]').value, {})
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

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'category_name': {
                        validators: {
                            notEmpty: {
                                message: 'Full name is required'
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
        const submitButton = element.querySelector('[data-kt-categorys-modal-action="submit"]');
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
                        if (param.get('category_id') != null && param.get('category_id') != undefined  && param.get('category_id') != '') {
                            param.append('_method', 'PUT');

                            formSubmit = axios.post(
                                submitButton.closest('form').getAttribute('action-update') + '/' + param.get('category_id'),
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
                            KTExpeditionsList.refresh();
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
        const cancelButton = element.querySelector('[data-kt-categorys-modal-action="cancel"]');
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
        const closeButton = element.querySelector('[data-kt-categorys-modal-action="close"]');
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
            initAddExpedition();
        },
        addImage(name, full_url)
        {
            dropzone.files.forEach(function(file) {
                file.previewElement.remove();
            });
            dropzone.removeAllFiles();
            $('div.dz-success').remove();

            var mockFile = { name: name, size: 32000, src: full_url };
            dropzone.options.addedfile.call(dropzone, mockFile);
            dropzone.options.thumbnail.call(dropzone, mockFile, mockFile.src);
            mockFile.previewElement.classList.add('dz-success');
            mockFile.previewElement.classList.add('dz-complete');
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTExpeditionsAddExpedition.init();
});
