"use strict";

// Class definition
var KTAppEcommerceSaveProduct = function () {
    let quill = null;
    var dropzone = '';

    // Private functions
    // Init quill editor
    const initQuill = () => {
        // Define all elements for quill editor
        const elements = [
            '#kt_ecommerce_add_product_description',
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

            let productDescription = document.querySelector('textarea[name="product_description"]').value;
            quill.pasteHTML(productDescription);
        });
    }

    // Init condition select2
    const initConditionsSelect2 = () => {
        // Tnit new repeating condition types
        const allConditionTypes = document.querySelectorAll('[data-kt-ecommerce-catalog-add-product="product_option"]');
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

    // Init noUIslider
    const initSlider = () => {
        var slider = document.querySelector("#kt_ecommerce_add_product_discount_slider");
        var value = document.querySelector("#kt_ecommerce_add_product_discount_label");

        let start = document.querySelector("input[name='product_discount_percentage']").value;
        if (start == null || start == undefined || start == '') {
            start = 10;
        }
        noUiSlider.create(slider, {
            start: [start],
            connect: true,
            range: {
                "min": 1,
                "max": 100
            }
        });

        slider.noUiSlider.on("update", function (values, handle) {
            value.innerHTML = Math.round(values[handle]);
            if (handle) {
                value.innerHTML = Math.round(values[handle]);
                document.querySelector("input[name='product_discount_percentage']").value = values[handle];
            }
        });
    }

    // Init DropzoneJS --- more info:
    const initDropzone = () => {
        // For more info about Dropzone plugin visit:  https://www.dropzonejs.com/#usage
		dropzone = new Dropzone("#kt_modal_add_update_product_thumbnail", {
			url: "../file/upload", // Set the url for your upload script location
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
                    document.querySelector('input[name="product_thumbnail_id"]').value = result.id;

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
                    if (form.querySelector("input[name='product_thumbnail_id']").value == '') {
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
                            axios.delete('../file/delete/' + document.querySelector('input[name="product_thumbnail_id"]').value, {})
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

        if (document.querySelector('input[name="product_thumbnail_id"]').value) {
            dropzone.files.forEach(function(file) {
                file.previewElement.remove();
            });
            dropzone.removeAllFiles();
            $('div.dz-success').remove();

            let name = document.getElementById('thumbnail_name').value;
            let full_url = document.getElementById('thumbnail_full_url').value;
            var mockFile = { name: name, size: 100, src: full_url };
            dropzone.options.addedfile.call(dropzone, mockFile);
            dropzone.options.thumbnail.call(dropzone, mockFile, mockFile.src);
            mockFile.previewElement.classList.add('dz-success');
            mockFile.previewElement.classList.add('dz-complete');
        }
    }

    // Handle discount options
    const handleDiscount = () => {
        const discountOptions = document.querySelectorAll('input[name="product_discount_type"]');
        const percentageEl = document.getElementById('kt_ecommerce_add_product_discount_percentage');
        const fixedEl = document.getElementById('kt_ecommerce_add_product_discount_fixed');

        discountOptions.forEach(option => {
            option.addEventListener('change', e => {
                const value = e.target.value;

                switch (value) {
                    case '2': {
                        percentageEl.classList.remove('d-none');
                        fixedEl.classList.add('d-none');
                        break;
                    }
                    case '3': {
                        percentageEl.classList.add('d-none');
                        fixedEl.classList.remove('d-none');
                        break;
                    }
                    default: {
                        percentageEl.classList.add('d-none');
                        fixedEl.classList.add('d-none');
                        break;
                    }
                }
            });
        });
    }

    // Submit form handler
    const handleSubmit = () => {
        // Define variables
        let validator;

        // Get elements
        const form = document.getElementById('kt_ecommerce_add_product_form');
        const submitButton = document.getElementById('kt_ecommerce_add_product_submit');

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'product_name': {
                        validators: {
                            notEmpty: {
                                message: 'Nama harus diisi'
                            }
                        }
                    },
                    'product_code': {
                        validators: {
                            notEmpty: {
                                message: 'Kode produk harus diisi'
                            }
                        }
                    },
                    'product_price': {
                        validators: {
                            notEmpty: {
                                message: 'Harga harus diisi'
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

                        form.querySelector('textarea[name="product_description"]').value = quill.root.innerHTML;
                        form.querySelector('textarea[name="product_description"]').text = quill.root.innerHTML;

                        form.querySelector('input[name="product_discount_percentage"]').value = document.getElementById("kt_ecommerce_add_product_discount_label").innerHTML;

                        let param = new FormData(form);
                        let formSubmit = null;
                        if (param.get('product_id') != null && param.get('product_id') != undefined  && param.get('product_id') != '') {
                            param.append('_method', 'PUT');

                            formSubmit = axios.post(
                                submitButton.closest('form').getAttribute('action-update') + '/' + param.get('product_id'),
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

    // Public methods
    return {
        init: function () {
            // Init forms
            initQuill();
            initSlider();
            initDropzone();
            initConditionsSelect2();

            // Handle forms
            handleDiscount();
            handleSubmit();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTAppEcommerceSaveProduct.init();
});
