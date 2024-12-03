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
            errors[field].forEach(function (error) {
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
                errors[field].forEach(function (error) {
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


    function addInvalidClassToFields(errors) {
        for (const field in errors) {
            if (errors.hasOwnProperty(field)) {
                errors[field].forEach(function (error) {
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

    new AutoNumeric('#totalnilai', {
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        currencySymbol: 'Rp ',
        modifyValueOnWheel: false
    });

    $(document).on('click', '.kv-file-remove', function() {
        let mediaID = $(this).data('key');
        let url = '{{ route('program.media.destroy', ':id') }}'.replace(':id', mediaID);
        $.ajax({
            url: url,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log('AJAX success:', response);
                if (response.success) {
                    Toast.fire({
                        icon: "success",
                        title: "Files Deleted",
                        timer: 500,
                        timerProgressBar: true,
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        icon: 'error',
                        timer: 500,
                    });
                }
                let filePreview = $(`.kv-file-remove[data-key="${mediaID}"]`).closest(
                    '.file-preview-frame');
                filePreview.remove();
            },
            error: function(xhr) {
                console.error('AJAX error:', xhr);
            }
        });
    });
    // INIT LOAD MEDIA
    $(document).ready(function() {
        var fileIndex = 0;
        var fileCaptions = {};
        $("#file_pendukung").fileinput({
            theme: "fa5",
            showUpload: false,
            showBrowse: false,
            browseOnZoneClick: true,
            showRemove: false,
            allowedFileExtensions: ['jpg', 'png', 'jpeg', 'docx', 'doc', 'ppt', 'pptx', 'xls', 'xlsx',
                'csv', 'gif', 'pdf',
            ],
            maxFileSize: 4096,
            maxFilePreviewSize: 2048,
            overwriteInitial: true,
            append: false,
            initialPreviewAsData: true,
            initialPreview: {!! json_encode($initialPreview) !!},
            initialPreviewConfig: {!! json_encode($initialPreviewConfig) !!},
            removeFromPreviewOnError: true,
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

        }).on('fileloaded', function(event, file, previewId, index, reader) {
            fileIndex++;
            var uniqueId = 'file-' + fileIndex;
            fileCaptions[uniqueId] = file.name;
            // $('#captions-container').append(
            //     `<div class="form-group" id="caption-group-${uniqueId}">
            //         <label class="control-label mb-0 small mt-2" for="caption-${uniqueId}">{{ __('cruds.program.ket_file') }} : <span class="text-red">${file.name}</span></label>
            //         <input type="text" class="form-control" name="keterangan[]" id="keterangan-${uniqueId}">
            //     </div>`
            // );
            // $(`#${previewId}`).attr('data-unique-id', uniqueId);
        }).on('fileremoved', function(event, id) {
            var uniqueId = $(`#${id}`).attr('data-unique-id');
            delete fileCaptions[uniqueId];
            $(`#caption-group-${uniqueId}`).remove();
        }).on('fileclear', function(event) {
            fileCaptions = {};
            $('#captions-container').empty();
        }).on('filebatchselected', function(event, files) {
            fileCaptions = {};
            $('#captions-container').empty();
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                fileIndex++;
                var uniqueId = 'file-' + fileIndex;
                fileCaptions[uniqueId] = file.name; // Track the file with its unique ID
                $('#captions-container').append(
                    `<div class="form-group" id="caption-group-${uniqueId}">
                        <label class="control-label mb-0 small mt-2" for="caption-${uniqueId}">{{ __('cruds.program.ket_file') }} : <span class="text-red">${file.name}</span></label>
                            <input type="text" class="form-control" name="keterangan[]" id="keterangan-${uniqueId}">
                        </div>`
                );
            }
        });
    });

    // POPULATE EDIT FORM
    $(document).ready(function() {
        $('#status').select2();
        var url_update = $('#editProgram').attr('action');
        // SELECT2 GET DATA
        var data_reinstra = "{{ route('program.api.reinstra') }}";
        var data_kelompokmarjinal = "{{ route('program.api.marjinal') }}";
        var data_sdg = "{{ route('program.api.sdg') }}";

        var edit_reinstra_data = @json(
            $program->targetReinstra->map(function ($targetReinstra) {
                return ['id' => $targetReinstra->id, 'text' => $targetReinstra->nama];
            }))

        var target_reinstra = @json(
            $program->targetReinstra->map(function ($targetReinstra) {
                return $targetReinstra->id;
            }));
        var edit_kelompokmarjinal = @json(
            $program->kelompokMarjinal->map(function ($kelompokMarjinal) {
                return ['id' => $kelompokMarjinal->id, 'text' => $kelompokMarjinal->nama];
            }))

        var kelompokmarjinals = @json(
            $program->kelompokMarjinal->map(function ($kelompokMarjinal) {
                return $kelompokMarjinal->id;
            }));
        var edit_kaitansdg = @json(
            $program->kaitanSDG->map(function ($data) {
                return ['id' => $data->id, 'text' => $data->nama];
            }))

        var kaitansdgs = @json(
            $program->kaitanSDG->map(function ($data) {
                return $data->id;
            }));

        // KELOMPOK MARJINAL LOADS DATA
        $('#kelompokmarjinal').select2({
            placeholder: '{{ __('cruds.program.marjinal.select') }}',
            width: '100%',
            allowClear: true,
            data: edit_kelompokmarjinal,
            // closeOnSelect: false,
            dropdownPosition: 'below',
            ajax: {
                url: data_kelompokmarjinal,
                method: 'GET',
                delay: 1000,
                processResults: function(data) {
                    return {
                        results: data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.nama // Mapping 'nama' to 'text'
                            };
                        })
                    };
                },
                data: function(params) {
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
            placeholder: '{{ __('cruds.program.select_reinstra') }}',
            width: '100%',
            data: edit_reinstra_data,
            allowClear: true,
            // closeOnSelect: false,
            width: '100%',
            dropdownPosition: 'below',
            ajax: {
                url: data_reinstra,
                method: 'GET',
                delay: 1000,
                processResults: function(data) {
                    return {
                        results: data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.nama // Mapping 'nama' to 'text'
                            };
                        })
                    };
                },
                data: function(params) {
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
            placeholder: '{{ __('cruds.program.select_sdg') }}',
            width: '100%',
            allowClear: true,
            closeOnSelect: false,
            data: edit_kaitansdg,
            dropdownPosition: 'below',
            ajax: {
                url: data_sdg,
                method: 'GET',
                delay: 1000,
                processResults: function(data) {
                    return {
                        results: data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.nama // Mapping 'nama' to 'text'
                            };
                        })
                    };
                },
                data: function(params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    };
                    return query;
                }
            }
        });

        if (target_reinstra.length > 0) {
            $('#targetreinstra').val(target_reinstra).trigger('change');
        }
        if (kelompokmarjinals.length > 0) {
            $('#kelompokmarjinal').val(kelompokmarjinals).trigger('change');
        }
        if (kaitansdgs.length > 0) {
            $('#kaitansdg').val(kaitansdgs).trigger('change');
        }

    });

    // BUTTON SUBMIT UPDATE PROGRAM
    $(document).ready(function() {
        $('#editProgram').on('submit', function(e) {
            e.preventDefault();
            $(this).find('button[type="submit"]').attr('disabled', 'disabled');
            var formData = new FormData(this);

            // Log the values of staff[] and peran[] to verify they are included
            var staffValues = $('select[name="staff[]"]').map(function() {
                return $(this).val();
            }).get();
            var peranValues = $('select[name="peran[]"]').map(function() {
                return $(this).val();
            }).get();

            console.log('Staff Values:', staffValues);
            console.log('Peran Values:', peranValues);

            $.each(staffValues, function(index, value) {
                formData.append('staff[]', value);
            });

            $.each(peranValues, function(index, value) {
                formData.append('peran[]', value);
            });

            formData.append('_method', 'PUT');

            // Collect fields to remove masked values
            var fieldsToRemove = [];
            $('input.currency').each(function() {
                fieldsToRemove.push($(this).attr('name'));
            });

            // Remove original masked `nilaidonasi` values from FormData
            fieldsToRemove.forEach(function(field) {
                formData.delete(field);
            });

            // Unmask all AutoNumeric fields before submitting, excluding #totalnilai
            var nilaidonasiValues = [];
            $('input.currency').each(function() {
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
            // Detailed logging
            for (var pair of formData.entries()) {
                console.log(`${pair[0]}: ${pair[1]}`);
            }

            // Collect staff and peran data
            // $('.staff-row').each(function () {
            //     const staffId = $(this).find('.staff-select').val(); // Get selected staff ID
            //     const peranId = $(this).find('.peran-select').val(); // Get selected peran ID
            //     if (staffId && peranId) {
            //         formData.append('staff_peran[' + staffId + ']', peranId); // Append as key-value pairs
            //     }
            // });

            // let staffPeran = [];
            // $('input[name="staff[]"]').each(function(index) {
            //     let staffId = $(this).val();
            //     let peranId = $('input[name="peran[]"]').eq(index).val();
            //     staffPeran.push({ staff_id: staffId, peran_id: peranId });
            // });

            // console.log('hasil log staff dan peran', staffPeran);

            // formData.append('staff_peran', JSON.stringify(staffPeran));


            $.ajax({
                url: $(this).attr('action'),
                type: 'post',
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    Toast.fire({
                        icon: "info",
                        title: "Uploading...",
                        timer: 2000,
                        timerProgressBar: true,
                    });
                },
                success: function(response) {
                    setTimeout(() => {
                        if (response.success === true) {
                            Swal.fire({
                                title: "Success",
                                text: response.message,
                                icon: "success",
                                timer: 1500,
                                timerProgressBar: true,
                            });
                            $('#editProgram').trigger('reset');
                            $('#kelompokmarjinal, #targetreinstra, #kaitansdg').val('').trigger('change');
                            $('#editProgram').find('button[type="submit"]').removeAttr('disabled');
                            window.location.reload();
                        }
                    }, 500);
                },
                error: function (xhr, textStatus, errorThrown) {
                    $('#editProgram').find('button[type="submit"]').removeAttr('disabled');
                    const errorMessage = getErrorMessage(xhr);
                    const response = JSON.parse(xhr.responseText);
                    if (response.errors) {
                        addInvalidClassToFields(response.errors);
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        html: errorMessage,
                        confirmButtonText: 'Okay'
                    });
                },

            });
        });
    });
</script>
