// Function to save form data to localStorage
function saveFormDataToStorage() {
    let formData = {};

    // Select all relevant inputs within #createKegiatan and #pesertaForm
    $('#createKegiatan, #pesertaForm').find('input, select, textarea').each(function() {
        let $field = $(this);
        let id = $field.attr('id') || $field.attr('name'); // Use 'id' or fallback to 'name'

        if (id) {
            // Handle different input types
            if ($field.is(':checkbox')) {
                formData[id] = $field.is(':checked');
            } else if ($field.is(':radio')) {
                if ($field.is(':checked')) {
                    formData[id] = $field.val();
                }
            } else if ($field.hasClass('select2')) {
                formData[id] = $field.select2('val'); // For Select2, retrieve selected values
            } else if ($field.hasClass('summernote')) {
                formData[id] = $field.summernote('code'); // For Summernote, retrieve HTML content
            } else {
                formData[id] = $field.val();
            }
        }
    });

    // Save formData to localStorage
    localStorage.setItem('kegiatanFormData', JSON.stringify(formData));
}



$(document).ready(function () {
    // Attach change event listeners to #createKegiatan and #pesertaForm
    $('#createKegiatan, #pesertaForm').on('change', 'input, select, textarea', function () {
        saveFormDataToStorage();
    });

    // Specifically handle Select2 events
    $(document).on('select2:select select2:unselect', '.select2', function () {
        saveFormDataToStorage();
    });

    // Specifically handle Summernote changes
    $(document).on('summernote.change', '.summernote', function () {
        saveFormDataToStorage();
    });

    // Restore form data on page load
    loadFormDataFromStorage();
});

// Function to load form data from localStorage
function loadFormDataFromStorage() {
    let savedData = localStorage.getItem('kegiatanFormData');
    if (savedData) {
        let formData = JSON.parse(savedData);

        $.each(formData, function (key, value) {
            let $field = $('#' + key);

            if ($field.length === 0) {
                // If no element with 'id', try with 'name'
                $field = $('[name="' + key + '"]');
            }

            if ($field.length > 0) {
                if ($field.is(':checkbox')) {
                    $field.prop('checked', value);
                } else if ($field.is(':radio')) {
                    $field.each(function () {
                        if ($(this).val() === value) {
                            $(this).prop('checked', true);
                        }
                    });
                } else if ($field.hasClass('select2')) {
                    $field.val(value).trigger('change'); // For Select2, set value and trigger change
                } else if ($field.hasClass('summernote')) {
                    $field.summernote('code', value); // For Summernote, set HTML content
                } else {
                    $field.val(value);
                }
            }
        });
    }
}

// Handling Form Submission
// Before submitting the form via AJAX, ensure that all Summernote fields are synchronized with their underlying <textarea> elements. 
// Although Summernote updates the <textarea> automatically, it's good practice to ensure this manually:

$('#submitButton').click(function (e) {
    e.preventDefault();

    // Ensure all Summernote fields are updated
    $('.summernote').each(function () {
        let $textarea = $(this);
        $textarea.val($textarea.summernote('code'));
    });

    // Collect the form data
    let formData = $('#createKegiatan, #pesertaForm').serialize();

    // Optionally, if you prefer JSON:
    /*
    let formDataObj = {};
    $('#createKegiatan, #pesertaForm').find('input, select, textarea').each(function() {
        let $field = $(this);
        let id = $field.attr('id') || $field.attr('name');
        if (id) {
            if ($field.is(':checkbox')) {
                formDataObj[id] = $field.is(':checked');
            } else if ($field.is(':radio')) {
                if ($field.is(':checked')) {
                    formDataObj[id] = $field.val();
                }
            } else if ($field.hasClass('select2')) {
                formDataObj[id] = $field.select2('val');
            } else if ($field.hasClass('summernote')) {
                formDataObj[id] = $field.summernote('code');
            } else {
                formDataObj[id] = $field.val();
            }
        }
    });
    */

    // Send data through AJAX
    $.ajax({
        url: '/submit-kegiatan', // Replace with your endpoint
        type: 'POST',
        data: formData, // or JSON.stringify(formDataObj) with appropriate headers
        success: function (response) {
            console.log('Data submitted successfully', response);

            // Clear localStorage after successful submission
            localStorage.removeItem('kegiatanFormData');

            // Optionally, reset the form
            $('#createKegiatan, #pesertaForm').trigger('reset');

            // Reset Select2 and Summernote
            $('.select2').val(null).trigger('change');
            $('.summernote').summernote('code', '');
        },
        error: function (error) {
            console.error('Error submitting data', error);
        },
    });
});


// Combining all the above recommendations, here's a comprehensive code example:

$(document).ready(function () {
    // Function to save form data to localStorage
    function saveFormDataToStorage() {
        let formData = {};

        // Select all relevant inputs within #createKegiatan and #pesertaForm
        $('#createKegiatan, #pesertaForm').find('input, select, textarea').each(function () {
            let $field = $(this);
            let id = $field.attr('id') || $field.attr('name'); // Use 'id' or fallback to 'name'

            if (id) {
                // Handle different input types
                if ($field.is(':checkbox')) {
                    formData[id] = $field.is(':checked');
                } else if ($field.is(':radio')) {
                    if ($field.is(':checked')) {
                        formData[id] = $field.val();
                    }
                } else if ($field.hasClass('select2')) {
                    formData[id] = $field.select2('val'); // For Select2, retrieve selected values
                } else if ($field.hasClass('summernote')) {
                    formData[id] = $field.summernote('code'); // For Summernote, retrieve HTML content
                } else {
                    formData[id] = $field.val();
                }
            }
        });

        // Save formData to localStorage
        localStorage.setItem('kegiatanFormData', JSON.stringify(formData));
    }

    // Function to load form data from localStorage
    function loadFormDataFromStorage() {
        let savedData = localStorage.getItem('kegiatanFormData');
        if (savedData) {
            let formData = JSON.parse(savedData);

            $.each(formData, function (key, value) {
                let $field = $('#' + key);

                if ($field.length === 0) {
                    // If no element with 'id', try with 'name'
                    $field = $('[name="' + key + '"]');
                }

                if ($field.length > 0) {
                    if ($field.is(':checkbox')) {
                        $field.prop('checked', value);
                    } else if ($field.is(':radio')) {
                        $field.each(function () {
                            if ($(this).val() === value) {
                                $(this).prop('checked', true);
                            }
                        });
                    } else if ($field.hasClass('select2')) {
                        $field.val(value).trigger('change'); // For Select2, set value and trigger change
                    } else if ($field.hasClass('summernote')) {
                        $field.summernote('code', value); // For Summernote, set HTML content
                    } else {
                        $field.val(value);
                    }
                }
            });
        }
    }

    // Attach change event listeners to #createKegiatan and #pesertaForm
    $('#createKegiatan, #pesertaForm').on('change', 'input, select, textarea', function () {
        saveFormDataToStorage();
    });

    // Specifically handle Select2 events
    $(document).on('select2:select select2:unselect', '.select2', function () {
        saveFormDataToStorage();
    });

    // Specifically handle Summernote changes
    $(document).on('summernote.change', '.summernote', function () {
        saveFormDataToStorage();
    });

    // Restore form data on page load
    loadFormDataFromStorage();

    // Handle AJAX form submission
    $('#submitButton').click(function (e) {
        e.preventDefault();

        // Ensure all Summernote fields are updated
        $('.summernote').each(function () {
            let $textarea = $(this);
            $textarea.val($textarea.summernote('code'));
        });

        // Collect the form data
        let formData = $('#createKegiatan, #pesertaForm').serialize();

        // Send data through AJAX
        $.ajax({
            url: '/submit-kegiatan', // Replace with your endpoint
            type: 'POST',
            data: formData, // or JSON.stringify(formDataObj) with appropriate headers
            success: function (response) {
                console.log('Data submitted successfully', response);

                // Clear localStorage after successful submission
                localStorage.removeItem('kegiatanFormData');

                // Optionally, reset the form
                $('#createKegiatan, #pesertaForm').trigger('reset');

                // Reset Select2 and Summernote
                $('.select2').val(null).trigger('change');
                $('.summernote').summernote('code', '');
            },
            error: function (error) {
                console.error('Error submitting data', error);
            },
        });
    });
});




// Function to save form data to localStorage
function saveFormDataToStorage() {
    let formData = {};
    $('#createKegiatan input, #createKegiatan select, #createKegiatan textarea, #pesertaForm input, #pesertaForm select, #pesertaForm textarea').each(function() {
        let id = $(this).attr('id');
        if (id) {
            formData[id] = $(this).val();
        }
    });

    // Save formData to localStorage
    localStorage.setItem('formData', JSON.stringify(formData));
}

// Attach change event listeners to input, select, date, select2, and summernote fields
$('#createKegiatan').on('change', 'input, select, textarea, .select2, .summernote', function () {
    saveFormDataToStorage();
});
$('#pesertaForm').on('change', 'input, select, textarea, .select2, .summernote', function () {
    saveFormDataToStorage();
});

// Additionally, capture changes from select2 and summernote
$('.select2').on('select2:select select2:unselect', function() {
    saveFormDataToStorage();
});
$('.summernote').on('summernote.change', function() {
    saveFormDataToStorage();
});











































$(document).ready(function () {
    // Function to save form data to localStorage
    function saveFormDataToLocalStorage() {
        const formData = {
            deskripsi_kegiatan: $('#deskripsi_kegiatan').val(),
            tujuan_kegiatan: $('#tujuan_kegiatan').val(),
            yang_terlibat: $('#yang_terlibat').val(),
            pelatih_asal: $('#pelatih_asal').val(),
            kegiatan: $('#kegiatan').val(),
            informasi_lain: $('#informasi_lain').val(),
            luas_lahan: $('#luas_lahan').val(),
            barang: $('#barang').val(),
            satuan: $('#satuan').val(),
            others: $('#others').val(),
        };

        // Include Summernote content
        $('.form-group textarea').each(function () {
            const id = $(this).attr('id');
            formData[id] = $(this).summernote('isEmpty') ? '' : $(this).summernote('code');
        });

        localStorage.setItem('kegiatanFormData', JSON.stringify(formData));
    }

    // Function to load form data from localStorage
    function loadFormDataFromLocalStorage() {
        const savedData = localStorage.getItem('kegiatanFormData');
        if (savedData) {
            const formData = JSON.parse(savedData);

            // Populate form fields
            $('#deskripsi_kegiatan').val(formData.deskripsi_kegiatan);
            $('#tujuan_kegiatan').val(formData.tujuan_kegiatan);
            $('#yang_terlibat').val(formData.yang_terlibat);
            $('#pelatih_asal').val(formData.pelatih_asal);
            $('#kegiatan').val(formData.kegiatan);
            $('#informasi_lain').val(formData.informasi_lain);
            $('#luas_lahan').val(formData.luas_lahan);
            $('#barang').val(formData.barang);
            $('#satuan').val(formData.satuan);
            $('#others').val(formData.others);

            // Restore Summernote content
            $('.form-group textarea').each(function () {
                const id = $(this).attr('id');
                if (formData[id]) {
                    $(this).summernote('code', formData[id]);
                }
            });
        }
    }

    // Save form data to localStorage whenever an input changes
    $('#createKegiatan').on('change', 'input, select, textarea', function () {
        saveFormDataToLocalStorage();
    });

    // Restore form data on page load
    loadFormDataFromLocalStorage();

    // AJAX submit
    $('#submitButton').click(function (e) {
        e.preventDefault();

        // Collect the form data
        const formData = {
            deskripsi_kegiatan: $('#deskripsi_kegiatan').summernote('code'),
            tujuan_kegiatan: $('#tujuan_kegiatan').summernote('code'),
            yang_terlibat: $('#yang_terlibat').summernote('code'),
            pelatih_asal: $('#pelatih_asal').summernote('code'),
            kegiatan: $('#kegiatan').summernote('code'),
            informasi_lain: $('#informasi_lain').summernote('code'),
            luas_lahan: $('#luas_lahan').val(),
            barang: $('#barang').val(),
            satuan: $('#satuan').val(),
            others: $('#others').summernote('code'),
        };

        // Send data through AJAX
        $.ajax({
            url: '/submit-kegiatan',
            type: 'POST',
            data: formData,
            success: function (response) {
                console.log('Data submitted successfully', response);

                // Clear localStorage after successful submission
                localStorage.removeItem('kegiatanFormData');
            },
            error: function (error) {
                console.log('Error submitting data', error);
            },
        });
    });
});




// Function to save form data to localStorage
function saveFormDataToStorage() {
    let formData = {};
    $('#createKegiatan input, #createKegiatan select, #createKegiatan textarea, #pesertaForm input, #pesertaForm select, #pesertaForm textarea').each(function() {
        let id = $(this).attr('id');
        if (id) {
            formData[id] = $(this).val();
        }
    });

    // Save formData to localStorage
    localStorage.setItem('formData', JSON.stringify(formData));
}

// Attach change event listeners to input, select, date, select2, and summernote fields
$('#createKegiatan').on('change', 'input, select, textarea, .select2, .summernote', function () {
    saveFormDataToStorage();
});
$('#pesertaForm').on('change', 'input, select, textarea, .select2, .summernote', function () {
    saveFormDataToStorage();
});

// Additionally, capture changes from select2 and summernote
$('.select2').on('select2:select select2:unselect', function() {
    saveFormDataToStorage();
});
$('.summernote').on('summernote.change', function() {
    saveFormDataToStorage();
});


















//===============================================
//===============================================
// USED 
// THIS CODE IS USED TO SAVE FORM DATA TO LOCAL STORAGE
//===============================================
//===============================================
//===============================================


$(document).ready(function () {
    // Function to save form data to localStorage
    function saveFormDataToLocalStorage() {
        const formData = {
            deskripsi_kegiatan: $('#deskripsi_kegiatan').val(),
            tujuan_kegiatan: $('#tujuan_kegiatan').val(),
            yang_terlibat: $('#yang_terlibat').val(),
            pelatih_asal: $('#pelatih_asal').val(),
            kegiatan: $('#kegiatan').val(),
            informasi_lain: $('#informasi_lain').val(),
            luas_lahan: $('#luas_lahan').val(),
            barang: $('#barang').val(),
            satuan: $('#satuan').val(),
            others: $('#others').val(),
        };

        // Include Summernote content
        $('.form-group textarea').each(function () {
            const id = $(this).attr('id');
            formData[id] = $(this).summernote('isEmpty') ? '' : $(this).summernote('code');
        });

        localStorage.setItem('kegiatanFormData', JSON.stringify(formData));
    }

    // Function to load form data from localStorage
    function loadFormDataFromLocalStorage() {
        const savedData = localStorage.getItem('kegiatanFormData');
        if (savedData) {
            const formData = JSON.parse(savedData);

            // Populate form fields
            $('#deskripsi_kegiatan').val(formData.deskripsi_kegiatan);
            $('#tujuan_kegiatan').val(formData.tujuan_kegiatan);
            $('#yang_terlibat').val(formData.yang_terlibat);
            $('#pelatih_asal').val(formData.pelatih_asal);
            $('#kegiatan').val(formData.kegiatan);
            $('#informasi_lain').val(formData.informasi_lain);
            $('#luas_lahan').val(formData.luas_lahan);
            $('#barang').val(formData.barang);
            $('#satuan').val(formData.satuan);
            $('#others').val(formData.others);

            // Restore Summernote content
            $('.form-group textarea').each(function () {
                const id = $(this).attr('id');
                if (formData[id]) {
                    $(this).summernote('code', formData[id]);
                }
            });
        }
    }

    // Save form data to localStorage whenever an input changes
    $('#createKegiatan').on('change', 'input, select, textarea', function () {
        saveFormDataToLocalStorage();
    });

    // Restore form data on page load
    loadFormDataFromLocalStorage();

    // AJAX submit
    $('#submitButton').click(function (e) {
        e.preventDefault();

        // Collect the form data
        const formData = {
            deskripsi_kegiatan: $('#deskripsi_kegiatan').summernote('code'),
            tujuan_kegiatan: $('#tujuan_kegiatan').summernote('code'),
            yang_terlibat: $('#yang_terlibat').summernote('code'),
            pelatih_asal: $('#pelatih_asal').summernote('code'),
            kegiatan: $('#kegiatan').summernote('code'),
            informasi_lain: $('#informasi_lain').summernote('code'),
            luas_lahan: $('#luas_lahan').val(),
            barang: $('#barang').val(),
            satuan: $('#satuan').val(),
            others: $('#others').summernote('code'),
        };

        // Send data through AJAX
        $.ajax({
            url: '/submit-kegiatan',
            type: 'POST',
            data: formData,
            success: function (response) {
                console.log('Data submitted successfully', response);

                // Clear localStorage after successful submission
                localStorage.removeItem('kegiatanFormData');
            },
            error: function (error) {
                console.log('Error submitting data', error);
            },
        });
    });
});
