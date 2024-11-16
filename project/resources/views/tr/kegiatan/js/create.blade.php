{{-- javascript to create kegiatan --}}
<script>
    // error handle function
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
            errors[field].forEach(function(error) {
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
                errors[field].forEach(function(error) {
                    const inputField = $(`[name="${field}"]`);
                    if (inputField.length) {
                        inputField.addClass('is-invalid');
                        if (inputField.next('.invalid-feedback').length === 0) {
                            inputField.after(`<div class="invalid-feedback">${error}</div>`);
                        }
                    }
                });
            }
        }

        // Attach an event listener to remove the invalid class and message on input change
        $('input, textarea, select').on('input change', function() {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        });
    }

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
            maxFileSize: 4096,
            allowedFileExtensions: ['jpg', 'png', 'jpeg', 'docx', 'doc', 'ppt', 'pptx', 'xls',
                'xlsx',
                'csv', 'gif', 'pdf',
            ],
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
                'doc': function(ext) {
                    return ext.match(/(doc|docx)$/i);
                },
                'xls': function(ext) {
                    return ext.match(/(xls|xlsx)$/i);
                },
                'ppt': function(ext) {
                    return ext.match(/(ppt|pptx)$/i);
                },
                'zip': function(ext) {
                    return ext.match(/(zip|rar|tar|gzip|gz|7z)$/i);
                },
                'htm': function(ext) {
                    return ext.match(/(htm|html)$/i);
                },
                'txt': function(ext) {
                    return ext.match(/(txt|ini|csv|java|php|js|css)$/i);
                },
                'mov': function(ext) {
                    return ext.match(/(avi|mpg|mkv|mov|mp4|3gp|webm|wmv)$/i);
                },
                'mp3': function(ext) {
                    return ext.match(/(mp3|wav)$/i);
                }
            },
            overwriteInitial: false,
        }).on('fileloaded', function(event, file, previewId, index, reader) {
            fileIndex++;
            var uniqueId = 'file-' + fileIndex;
            fileCaptions[uniqueId] = file.name; // Track the file with its unique ID
            $('#captions-container').append(
                `<div class="form-group" id="caption-group-${uniqueId}">
                    <label for="caption-${uniqueId}"> {{ __('cruds.program.ket_file') }} ${file.name}</label>
                    <input type="text" class="form-control" name="keterangan[]" id="keterangan-${uniqueId}">
                    </div>`
            );
            // Store the unique identifier in the file preview element
            $(`#${previewId}`).attr('data-unique-id', uniqueId);
        }).on('fileremoved', function(event, id) {
            // Remove the corresponding caption input
            var uniqueId = $(`#${id}`).attr('data-unique-id');
            delete fileCaptions[uniqueId]; // Remove the file from the tracking object
            $(`#caption-group-${uniqueId}`).remove();
        }).on('fileclear', function(event) {
            // Clear all caption inputs when files are cleared
            fileCaptions = {}; // Reset the tracking object
            $('#captions-container').empty();
        }).on('filebatchselected', function(event, files) {
            // Clear all caption inputs when new files are selected
            fileCaptions = {}; // Reset the tracking object
            $('#captions-container').empty();
            // Iterate over each selected file and trigger the fileloaded event manually
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                fileIndex++;
                var uniqueId = 'file-' + fileIndex;
                fileCaptions[uniqueId] = file.name; // Track the file with its unique ID
                $('#captions-container').append(
                    `<div class="form-group" id="caption-group-${uniqueId}">
                        <label class="control-label mb-0 small mt-2" for="caption-${uniqueId}">{{ __('cruds.program.ket_file') }} : <span class="text-red">${file.name}</span></label>
                        <input type="text" class="form-control" name="keterangan[]" id="caption-${uniqueId}">
                        </div>`
                );
            }
    });

</script>
