<script>
    function handleErrors(response) {
        let errorMessage = response.message;
        if (response.status === 400) {
            try {
                const errors = response.errors;
                errorMessage = formatErrorMessages(errors);
            } catch (error) {
                errorMessage = "<p>An unexpected error occurred. Please try again later.</p>";
            }
        }
        Swal.fire({
            title: "Error!",
            html: errorMessage,
            icon: "error"
        });
    }

    function formatErrorMessages(errors) {
        let message = '<br><ul style="text-align:left!important">';
        for (const field in errors) {
            errors[ field ].forEach(function (error) {
                message += `<li>${error}</li>`;
            });
        }
        message += '</ul>';
        return message;
    }

    function getErrorMessage(xhr) {
        let message;
        try {
            const response = JSON.parse(xhr.responseText);
            message = response.message || 'An unexpected error occurred. Please try again later.';
        } catch (e) {
            message = 'An unexpected error occurred. Please try again later.';
        }
        return message;
    }

    function addInvalidClassToFields(errors) {
        for (const field in errors) {
            if (errors.hasOwnProperty(field)) {
                errors[ field ].forEach(function (error) {
                    const inputField = $(`[name="${field}"]`);
                    if (inputField.length) {
                        inputField.addClass('is-invalid');
                        // Optionally, you can add error messages below the input fields
                        if (inputField.next('.invalid-feedback').length === 0) {
                            inputField.after(`<div class="invalid-feedback">${error}</div>`);
                        }
                    }
                });
            }
        }

        // Attach an event listener to remove the invalid class and message on input change
        $('input, textarea, select').on('input change', function () {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        });
    }



    //SCRIPT FOR CREATE PROGRAM FORM
    // $('#totalnilai').maskMoney({
    //     prefix: 'Rp. ',
    //     allowNegative: false,
    //     thousands: '.',
    //     decimal: ',',
    //     affixesStay: false
    // });
    $(document).ready(function () {
        new AutoNumeric('#totalnilai', {
            digitGroupSeparator: '.',
            decimalCharacter: ',',
            currencySymbol: 'Rp ',
            modifyValueOnWheel: false
        });

        var fileIndex = 0;
        var fileCaptions = {}; // Object to track files and captions

        $("#file_pendukung").fileinput({
            theme: "fa5",
            showBrowse: false,
            showUpload: false,
            showRemove: false,
            showCaption: true,
            showDrag: true,
            uploadAsync: false,
            browseOnZoneClick: true,
            maxFileSize: 25096,
            maxFileCount: 50,
            allowedFileExtensions: [ 'jpg', 'png', 'jpeg', 'docx', 'doc', 'ppt', 'pptx', 'xls',
                'xlsx',
                'csv', 'gif', 'pdf',
            ],
            msgFilesTooMany: 'You can upload a maximum of {m} files. You have selected {n} files.',
            previewFileIconSettings: {
                'doc': '<i class="fas fa-file-word text-primary"></i>',
                'docx': '<i class="fas fa-file-word text-primary"></i>',
                'xls': '<i class="fas fa-file-excel text-success"></i>',
                'xlsx': '<i class="fas fa-file-excel text-success"></i>',
                'ppt': '<i class="fas fa-file-powerpoint text-danger"></i>',
                'pptx': '<i class="fas fa-file-powerpoint text-danger"></i>',
                'pdf': '<i class="fas fa-file-pdf text-danger"></i>',
                'zip': '<i class="fas fa-file-archive text-muted"></i>',
                'htm': '<i class="fas fa-file-code text-info"></i>',
                'txt': '<i class="fas fa-file-alt text-info"></i>',
            },
            previewFileExtSettings: {
                'doc': function (ext) {
                    return ext.match(/(doc|docx)$/i);
                },
                'xls': function (ext) {
                    return ext.match(/(xls|xlsx)$/i);
                },
                'ppt': function (ext) {
                    return ext.match(/(ppt|pptx)$/i);
                },
                'zip': function (ext) {
                    return ext.match(/(zip|rar|tar|gzip|gz|7z)$/i);
                },
                'htm': function (ext) {
                    return ext.match(/(htm|html)$/i);
                },
                'txt': function (ext) {
                    return ext.match(/(txt|ini|csv|java|php|js|css)$/i);
                },
                'mov': function (ext) {
                    return ext.match(/(avi|mpg|mkv|mov|mp4|3gp|webm|wmv)$/i);
                },
                'mp3': function (ext) {
                    return ext.match(/(mp3|wav)$/i);
                }
            },
            overwriteInitial: false,
        }).on('fileloaded', function (event, file, previewId, index, reader) {
            fileIndex++;
            var uniqueId = 'file-' + fileIndex;
            fileCaptions[ uniqueId ] = file.name; // Track the file with its unique ID
            $('#captions-container').append(
                `<div class="form-group" id="caption-group-${uniqueId}">
                    <label for="caption-${uniqueId}"> {{ __('cruds.program.ket_file') }} ${file.name}</label>
                    <input type="text" class="form-control" name="keterangan[]" id="keterangan-${uniqueId}">
                    </div>`
            );
            // Store the unique identifier in the file preview element
            $(`#${$.escapeSelector(previewId)}`).attr('data-unique-id', uniqueId);
            // $(`#${previewId}`).attr('data-unique-id', uniqueId);
        }).on('fileremoved', function (event, id) {
            // Remove the corresponding caption input
            var uniqueId = $(`#${id}`).attr('data-unique-id');
            delete fileCaptions[ uniqueId ]; // Remove the file from the tracking object
            $(`#caption-group-${uniqueId}`).remove();
        }).on('fileclear', function (event) {
            // Clear all caption inputs when files are cleared
            fileCaptions = {}; // Reset the tracking object
            $('#captions-container').empty();
        }).on('filebatchselected', function (event, files) {
            // Clear all caption inputs when new files are selected
            fileCaptions = {}; // Reset the tracking object
            $('#captions-container').empty();
            // Iterate over each selected file and trigger the fileloaded event manually
            for (var i = 0; i < files.length; i++) {
                var file = files[ i ];
                fileIndex++;
                var uniqueId = 'file-' + fileIndex;
                fileCaptions[ uniqueId ] = file.name; // Track the file with its unique ID
                $('#captions-container').append(
                    `<div class="form-group" id="caption-group-${uniqueId}">
                        <label class="control-label mb-0 small mt-2" for="caption-${uniqueId}">{{ __('cruds.program.ket_file') }} : <span class="text-red">${file.name}</span></label>
                        <input type="text" class="form-control" name="keterangan[]" id="caption-${uniqueId}">
                        </div>`
                );
            }
        });

        $('#status').select2();

        var data_reinstra = "{{ route('program.api.reinstra') }}";
        var data_kelompokmarjinal = "{{ route('program.api.marjinal') }}";
        var data_sdg = "{{ route('program.api.sdg') }}";

        $('#kelompokmarjinal').select2({
            placeholder: "{{ __('cruds.program.marjinal.select') }}",
            width: '100%',
            allowClear: true,
            closeOnSelect: false,
            dropdownPosition: 'below',
            ajax: {
                url: data_kelompokmarjinal,
                method: 'GET',
                delay: 1000,
                processResults: function (data) {
                    return {
                        results: data.map(function (item) {
                            return {
                                id: item.id,
                                text: item.nama // Mapping 'nama' to 'text'
                            };
                        })
                    };
                },
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    };
                    return query;
                }
            }
        });
        // SELECT2 For Target Reinstra
        $('#targetreinstra').select2({
            placeholder: "{{ __('cruds.program.select_reinstra') }}",
            width: '100%',
            allowClear: true,
            closeOnSelect: false,
            dropdownPosition: 'below',
            ajax: {
                url: data_reinstra,
                method: 'GET',
                delay: 1000,
                processResults: function (data) {
                    return {
                        results: data.map(function (item) {
                            return {
                                id: item.id,
                                text: item.nama // Mapping 'nama' to 'text'
                            };
                        })
                    };
                },
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    };
                    return query;
                }
            }
        });

        //KAITAN SDG SELECT2

        $('#kaitansdg').select2({
            placeholder: "{{ __('cruds.program.select_sdg') }}",
            width: '100%',
            allowClear: true,
            closeOnSelect: false,
            dropdownPosition: 'below',
            ajax: {
                url: data_sdg,
                method: 'GET',
                delay: 1000,
                processResults: function (data) {
                    return {
                        results: data.map(function (item) {
                            return {
                                id: item.id,
                                text: item.nama // Mapping 'nama' to 'text'
                            };
                        })
                    };
                },
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    };
                    return query;
                }
            }
        });


        $('#createProgram').on('submit', function (e) {
            e.preventDefault();

            // Validate file quantity before submission
            const fileInput = document.getElementById('file_pendukung');
            const maxFiles = 50;
            const fileCount = fileInput.files ? fileInput.files.length : 0;

            if (fileCount > maxFiles) {
                Swal.fire({
                    title: "File Upload Limit Exceeded!",
                    html: `
                        <div class="text-center">
                            <div class="mb-3">
                                <i class="fas fa-exclamation-triangle fa-3x text-warning"></i>
                            </div>
                            <p><strong>You have uploaded ${fileCount} files.</strong></p>
                            <p class="text-muted">Maximum allowed files: ${maxFiles}</p>
                            <p class="text-danger">Please remove ${fileCount - maxFiles} file(s) and try again.</p>
                        </div>
                    `,
                    icon: "warning",
                    confirmButtonText: "OK",
                    confirmButtonColor: "#3085d6"
                });
                return;
            }

            $('#outcomeTemplate').find('textarea, input').attr('disabled', true);
            const $form = $(this);
            $form.find('button[type="submit"]').attr('disabled', true);

            let formData = new FormData(this);

            // Collect selected staff and peran data from the dynamically added rows
            $('.staff-select').each(function () {
                formData.append('staff[]', $(this).val());
            });
            $('.peran-select').each(function () {
                formData.append('peran[]', $(this).val());
            });

            $('#outcomeTemplate').find('textarea, input').removeAttr('disabled');

            var fieldsToRemove = [];
            $('input.currency').each(function () {
                fieldsToRemove.push($(this).attr('name'));
            });

            // Remove original masked `nilaidonasi` values from FormData
            fieldsToRemove.forEach(function (field) {
                formData.delete(field);
            });

            // Unmask all AutoNumeric fields before submitting, excluding #totalnilai
            var nilaidonasiValues = [];
            $('input.currency').each(function () {
                if ($(this).attr('id') !== 'totalnilai') {
                    var autoNumericElement = AutoNumeric.getAutoNumericElement(this);
                    if (autoNumericElement !== null) {
                        var unmaskedValue = autoNumericElement.getNumericString();
                        formData.append($(this).attr('name'), unmaskedValue);
                        nilaidonasiValues.push(unmaskedValue);
                    } else {
                        console.error('AutoNumeric not initialized for:', this);
                    }
                }
            });

            const totalNilaiInput = document.querySelector('#totalnilai');
            if (totalNilaiInput) {
                const totalNilaiAutoNumeric = AutoNumeric.getAutoNumericElement(totalNilaiInput);
                if (totalNilaiAutoNumeric) {
                    const totalNilaiUnmasked = totalNilaiAutoNumeric.getNumericString(); // Get unmasked value
                    formData.append('totalnilai', totalNilaiUnmasked); // Append to FormData
                } else {
                    console.error('AutoNumeric not initialized for #totalnilai');
                }
            }

            // Log arrays for debugging
            var pendonorIds = formData.getAll('pendonor_id[]');
            console.log('pendonor_id[]:', pendonorIds);
            console.log('nilaidonasi[]:', nilaidonasiValues);

            // Check for length mismatch
            if (pendonorIds.length !== nilaidonasiValues.length) {
                console.error('Mismatch between pendonor_id and nilaidonasi arrays');
                $('#editProgram').find('button[type="submit"]').removeAttr('disabled');
                Swal.fire({
                    icon: 'error',
                    title: 'Mismatch Error',
                    text: 'Mismatch between pendonor_id and nilaidonasi arrays.'
                });
                return; // Prevent form submission
            }

            // Check if we have large file upload (50+ files)
            const fileCounts = formData.getAll('file_pendukung').length;
            const isBulkUpload = fileCounts >= 10;

            if (isBulkUpload) {
                // Show bulk upload progress modal
                showBulkUploadProgress(fileCounts);
            } else {
                // Show regular processing toast
                Swal.fire({
                    title: "Processing...",
                    icon: "info",
                    html: "Please wait while we save your data. This may take a few minutes for large files...",
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    showConfirmButton: false,
                    allowOutsideClick: false,
                });
            }

            $.ajax({
                url: "{{ route('program.store') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                timeout: 300000, // 5 minutes timeout
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    if (isBulkUpload) {
                        xhr.upload.addEventListener('progress', function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = (evt.loaded / evt.total) * 100;
                                updateBulkUploadProgress(percentComplete, evt.loaded, evt.total);
                            }
                        }, false);
                    }
                    return xhr;
                },
                beforeSend: function () {
                    // Prepare JSON for preview
                    const jsonPreview = {};
                    // Collect data from FormData
                    formData.forEach((value, key) => {
                        if (!jsonPreview[ key ]) {
                            jsonPreview[ key ] = [];
                        }
                        jsonPreview[ key ].push(value);
                    });
                    jsonPreview[ 'nilaidonasi[]' ] = nilaidonasiValues;

                    console.log("log before send", jsonPreview);
                },
                success: function (response) {
                    if (response.success) {
                        if (isBulkUpload) {
                            completeBulkUpload();
                        }

                        // Check if files are being processed in background
                        if (response.message && response.message.includes('Files are being processed')) {
                            Swal.fire({
                                title: "{{ __('global.success') }}",
                                html: `
                                    <div class="text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-cloud-upload-alt fa-3x text-primary"></i>
                                        </div>
                                        <p><strong>${response.message}</strong></p>
                                        <div class="progress mb-3" style="height: 20px;">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                                                 role="progressbar" style="width: 100%">Processing...</div>
                                        </div>
                                        <p class="text-muted small">You will be redirected shortly...</p>
                                    </div>
                                `,
                                icon: "success",
                                timer: 5000,
                                timerProgressBar: true,
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            }).then(() => {
                                window.location.href = "{{ route('program.index') }}";
                            });
                        } else {
                            Swal.fire({
                                title: "{{ __('global.success') }}",
                                text: response.message,
                                icon: "success",
                                timer: 500,
                                timerProgressBar: true,
                            }).then(() => {
                                $form[ 0 ].reset();
                                $('#outcomeContainer .row').not("#outcomeTemplate").remove(); // Clear dynamically added outcomes
                                $('#kelompokmarjinal, #targetreinstra, #kaitansdg').val('').trigger('change');
                                $('#pendonor-container').empty(); // Clear dynamically added rows
                                $('#donor').val(null).trigger('change'); // Reset Select2 dropdown
                                window.location.href = "{{ route('program.index') }}";
                            });
                        }
                    }
                },
                error: function (xhr) {
                    $form.find('button[type="submit"]').removeAttr('disabled');
                    if (isBulkUpload) {
                        failBulkUpload();
                    }
                    // const response = JSON.parse(xhr.responseText);
                    // const errorMessage = getErrorMessage(xhr) || 'An error occurred.';

                    let response;
                    let errorMessage = 'An error occurred.';

                    try {
                        // Try to parse as JSON
                        response = JSON.parse(xhr.responseText);
                        errorMessage = getErrorMessage(xhr) || 'An error occurred.';
                    } catch (e) {
                        // If parsing fails, it's HTML or some other format
                        console.error('Server returned non-JSON response:', xhr.responseText);

                        // Try to extract error message from HTML if possible
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = xhr.responseText;
                        const errorElements = tempDiv.querySelectorAll('h1, h2, h3, h4, p, .error, .message');
                        if (errorElements.length > 0) {
                            errorMessage = errorElements[0].textContent;
                        }
                    }

                    // Check for file quantity validation errors
                    if (errorMessage.includes('Too many files') ||
                        errorMessage.includes('Maximum') ||
                        (response && response.errors && response.errors['file_pendukung'])) {
                        Swal.fire({
                            title: "File Upload Limit Exceeded!",
                            html: `
                                <div class="text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-exclamation-triangle fa-3x text-warning"></i>
                                    </div>
                                    <p><strong>File upload validation failed.</strong></p>
                                    <p class="text-muted">${errorMessage}</p>
                                    <p class="text-info">Maximum allowed: 50 files per upload</p>
                                    <p class="text-danger">Please reduce the number of files and try again.</p>
                                </div>
                            `,
                            icon: "warning",
                            confirmButtonText: "OK",
                            confirmButtonColor: "#3085d6"
                        });
                    } else if (response && response.errors) {
                        addInvalidClassToFields(response.errors);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            html: errorMessage,
                            confirmButtonText: 'Okay'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            html: errorMessage,
                            confirmButtonText: 'Okay'
                        });
                    }
                },

                complete: function () {
                    setTimeout(() => {
                        $form.find('button[type="submit"]').removeAttr('disabled');
                    }, 500);
                }
            });
        });

        // Bulk upload progress functions
        function showBulkUploadProgress(fileCount) {
            Swal.fire({
                title: 'Uploading Files',
                html: `
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="fas fa-cloud-upload-alt fa-3x text-primary"></i>
                        </div>
                        <p><strong>Uploading ${fileCount} files...</strong></p>
                        <div class="progress mb-2" style="height: 20px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                                 role="progressbar" style="width: 0%" id="bulkUploadProgress">0%</div>
                        </div>
                        <div class="small text-muted">
                            <span id="uploadStatus">Preparing upload...</span>
                        </div>
                        <div class="mt-2">
                            <button class="btn btn-sm btn-outline-secondary" onclick="cancelBulkUpload()" id="cancelUploadBtn">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                        </div>
                    </div>
                `,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showCloseButton: false
            });
        }

        function updateBulkUploadProgress(percentComplete, loaded, total) {
            const progressBar = $('#bulkUploadProgress');
            const statusText = $('#uploadStatus');

            progressBar.css('width', percentComplete + '%');
            progressBar.text(Math.round(percentComplete) + '%');

            // Format file sizes
            const loadedMB = (loaded / (1024 * 1024)).toFixed(1);
            const totalMB = (total / (1024 * 1024)).toFixed(1);

            if (percentComplete < 100) {
                statusText.html(`Uploaded: ${loadedMB} MB / ${totalMB} MB`);
            } else {
                statusText.html('Processing files on server...');
            }
        }

        function completeBulkUpload() {
            $('#bulkUploadProgress').removeClass('progress-bar-animated').addClass('bg-success');
            $('#uploadStatus').html('<i class="fas fa-check-circle text-success"></i> Upload complete! Processing data...');
            $('#cancelUploadBtn').hide();
        }

        function failBulkUpload() {
            $('#bulkUploadProgress').removeClass('progress-bar-animated').addClass('bg-danger');
            $('#uploadStatus').html('<i class="fas fa-exclamation-triangle text-danger"></i> Upload failed!');
            $('#cancelUploadBtn').hide();
        }

        function cancelBulkUpload() {
            // This would need to be implemented with proper XHR abort
            Swal.fire({
                title: 'Upload Cancelled',
                text: 'File upload has been cancelled.',
                icon: 'info',
                timer: 2000,
                timerProgressBar: true
            });
        }


    });
</script>
