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
    // $(document).ready(function() {
    //     var fileIndex = 0;
    //     var fileCaptions = {};
    //     var fileInputId = 'file_pendukung';
    //     var captionContainerId = 'captions-container';
    //     var captionPrefix = 'keterangan';
    //     var docFileCaptions = {};
    //     var mediaFileCaptions = {};

    //     $("#file_pendukung").fileinput({
    //         theme: "fa5",
    //         showUpload: false,
    //         showBrowse: false,
    //         browseOnZoneClick: true,
    //         showRemove: false,
    //         allowedFileExtensions: ['jpg', 'png', 'jpeg', 'docx', 'doc', 'ppt', 'pptx', 'xls', 'xlsx',
    //             'csv', 'gif', 'pdf',
    //         ],
    //         maxFileSize: 50096,
    //         maxFileCount: 50,
    //         msgFilesTooMany: 'You can upload a maximum of {m} files. You have selected {n} files.',
    //         maxFilePreviewSize: 2048,
    //         overwriteInitial: false,
    //         append: true,
    //         initialPreviewAsData: true,
    //         initialPreview: {!! json_encode($initialPreview) !!},
    //         initialPreviewConfig: {!! json_encode($initialPreviewConfig) !!},
    //         removeFromPreviewOnError: true,
    //         previewFileIconSettings: {
    //             'doc': '<i class="fas fa-file-word text-primary"></i>',
    //             'docx': '<i class="fas fa-file-word text-primary"></i>',
    //             'xls': '<i class="fas fa-file-excel text-success"></i>',
    //             'xlsx': '<i class="fas fa-file-excel text-success"></i>',
    //             'ppt': '<i class="fas fa-file-powerpoint text-danger"></i>',
    //             'pptx': '<i class="fas fa-file-powerpoint text-danger"></i>',
    //             'pdf': '<i class="fas fa-file-pdf text-danger"></i>',
    //             'zip': '<i class="fas fa-file-archive text-muted"></i>',
    //             'htm': '<i class="fas fa-file-code text-info"></i>',
    //             'txt': '<i class="fas fa-file-alt text-info"></i>',
    //         },
    //         previewFileExtSettings: {
    //             'doc': function(ext) {
    //                 return ext.match(/(doc|docx)$/i);
    //             },
    //             'xls': function(ext) {
    //                 return ext.match(/(xls|xlsx)$/i);
    //             },
    //             'ppt': function(ext) {
    //                 return ext.match(/(ppt|pptx)$/i);
    //             },
    //             'zip': function(ext) {
    //                 return ext.match(/(zip|rar|tar|gzip|gz|7z)$/i);
    //             },
    //             'htm': function(ext) {
    //                 return ext.match(/(htm|html)$/i);
    //             },
    //             'txt': function(ext) {
    //                 return ext.match(/(txt|ini|csv|java|php|js|css)$/i);
    //             },
    //             'mov': function(ext) {
    //                 return ext.match(/(avi|mpg|mkv|mov|mp4|3gp|webm|wmv)$/i);
    //             },
    //             'mp3': function(ext) {
    //                 return ext.match(/(mp3|wav)$/i);
    //             }
    //         },

    //     }).on('filepreloaded', function(event, data, previewId, index) {
    //         // Handle preloaded (existing) files
    //         var uniqueId = data.key; // Media ID from initialPreviewConfig
    //         var caption = data.extra.keterangan || data.caption.replace(/<[^>]+>/g, '');
    //         fileCaptions[uniqueId] = caption;
    //         $(`#${$.escapeSelector(previewId)}`).attr('data-unique-id', uniqueId);
    //         $('#' + captionContainerId).append(
    //             `<div class="form-group" id="caption-group-${uniqueId}">
    //                 <label class="control-label mb-0 small mt-2" for="keterangan-${uniqueId}">{{ __('cruds.program.ket_file') }} : <span class="text-red">${data.caption}</span></label>
    //                 <input type="text" class="form-control" name="${captionPrefix}" id="keterangan-${uniqueId}" value="${caption}">
    //             </div>`
    //         );
    //     }).on('fileloaded', function(event, file, previewId, index, reader) {
    //         var uniqueId = fileInputId + '-' + (new Date().getTime() + index);
    //         fileCaptions[uniqueId] = file.name;
    //         $(`#${$.escapeSelector(previewId)}`).attr('data-unique-id', uniqueId);
    //         $('#' + captionContainerId).append(
    //             `<div class="form-group" id="caption-group-${uniqueId}">
    //                 <label class="control-label mb-0 small mt-2" for="keterangan-${uniqueId}">{{ __('cruds.program.ket_file') }} : <span class="text-red">${file.name}</span></label>
    //                 <input type="text" class="form-control" name="${captionPrefix}[]" id="keterangan-${uniqueId}" value="">
    //             </div>`
    //         );
    //     }).on('fileremoved', function(event, id) {
    //         var uniqueId = $(`#${id}`).attr('data-unique-id');
    //         delete fileCaptions[uniqueId];
    //         $(`#caption-group-${uniqueId}`).remove();
    //     }).on('fileclear', function(event) {
    //         fileCaptions = {};
    //         $('#captions-container').empty();
    //     }).on('filebatchselected', function(event, files) {
    //         // Kosongkan semua data dan caption ketika user pilih ulang file
    //         fileCaptions = {};
    //         $('#' + captionContainerId).empty();
    //     });
    //     // .on('filebatchselected', function(event, files, previewId, index, reader) {
    //     //     fileCaptions = {}; // Reset the tracking object
    //     //     $('#captions-container').empty();
    //     //     for (var i = 0; i < files.length; i++) {
    //     //         console.log('File:', files[i]);
    //     //         console.log('Selected files count:', files.length);
    //     //         if (files.length === 1){
    //     //             var uniqueId = fileInputId + '-' + (new Date().getTime() + index);
    //     //             fileCaptions[uniqueId] = file.name;
    //     //             $(`#${$.escapeSelector(previewId)}`).attr('data-unique-id', uniqueId);
    //     //             $('#' + captionContainerId).append(
    //     //                 `<div class="form-group" id="caption-group-${uniqueId}">
    //     //                     <label class="control-label mb-0 small mt-2" for="keterangan-${uniqueId}">{{ __('cruds.program.ket_file') }} : <span class="text-red">${file.name}</span></label>
    //     //                     <input type="text" class="form-control" name="${captionPrefix}[]" id="keterangan-${uniqueId}" value="">
    //     //                 </div>`
    //     //             );
    //     //         }else {
    //     //             //append caption for multiple files
    //     //             var uniqueId = fileInputId + '-' + (new Date().getTime() + i);
    //     //             fileCaptions[uniqueId] = files[i].name;
    //     //             $('#' + captionContainerId).append(
    //     //                 `<div class="form-group" id="caption-group-${uniqueId}">
    //     //                     <label class="control-label mb-0 small mt-2" for="keterangan-${uniqueId}">{{ __('cruds.program.ket_file') }} : <span class="text-red">${files[i].name}</span></label>
    //     //                     <input type="text" class="form-control" name="${captionPrefix}[]" id="keterangan-${uniqueId}" value="">
    //     //                     </div>`
    //     //                 );
    //     //         }
    //     //     }
    //     // });
    // });

    $(document).ready(function () {
        const fileInputId = 'file_pendukung';
        const captionContainerId = 'captions-container';
        const captionPrefix = 'keterangan';
        let fileCaptions = {};

        $("#" + fileInputId).fileinput({
                theme: "fa5",
                showUpload: false,
                showBrowse: false,
                browseOnZoneClick: true,
                showRemove: false,
                allowedFileExtensions: ['jpg', 'png', 'jpeg', 'docx', 'doc', 'ppt', 'pptx', 'xls', 'xlsx', 'csv', 'gif', 'pdf'],
                maxFileSize: 50096,
                maxFileCount: 50,
                msgFilesTooMany: 'You can upload a maximum of {m} files. You have selected {n} files.',
                maxFilePreviewSize: 2048,
                overwriteInitial: false,
                append: true,
                initialPreviewAsData: true,
                initialPreview: {!! json_encode($initialPreview) !!},
                initialPreviewConfig: {!! json_encode($initialPreviewConfig)!!},
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
                    'doc': ext => /(doc|docx)$/i.test(ext),
                    'xls': ext => /(xls|xlsx)$/i.test(ext),
                    'ppt': ext => /(ppt|pptx)$/i.test(ext),
                    'zip': ext => /(zip|rar|tar|gzip|gz|7z)$/i.test(ext),
                    'htm': ext => /(htm|html)$/i.test(ext),
                    'txt': ext => /(txt|ini|csv|java|php|js|css)$/i.test(ext),
                    'mov': ext => /(avi|mpg|mkv|mov|mp4|3gp|webm|wmv)$/i.test(ext),
                    'mp3': ext => /(mp3|wav)$/i.test(ext),
                },
        })

        // 1. Tambah input untuk file yang sudah ada (initialPreview)
        .on('filepreloaded', function (event, data, previewId) {
            const uid = data.key;
            const label = data.extra.keterangan || data.caption.replace(/<[^>]+>/g, '');
            addCaptionInput(uid, label, false);
            $('#' + previewId).attr('data-unique-id', uid);
        })

        // 2. Tambah input caption ketika user pilih file baru (batch)
        .on('filebatchselected', function (event, files) {
            $('#' + captionContainerId + ' .new-file').remove();

            Array.from(files).forEach((file, i) => {
                const uid = 'new-' + Date.now() + '-' + i;
                addCaptionInput(uid, file.name, true);
                fileCaptions[uid] = file.name;
            });
        })


        // 3. Pasang data-unique-id ke DOM preview untuk dipakai saat remove
        .on('fileloaded', function (event, file, previewId, index) {
            const uidKeys = Object.keys(fileCaptions);
            const uid = uidKeys[index];
            $('#' + previewId).attr('data-unique-id', uid);
        })

        // 4. Hapus input caption jika preview file dihapus
        .on('fileremoved', function (event, previewId) {
            const uid = $('#' + previewId).data('unique-id');
            $('#caption-group-' + uid).remove();
        })

        // 5. Hapus semua input caption jika file di-clear semua
        .on('fileclear', function () {
            $('#' + captionContainerId).empty();
            fileCaptions = {};
        });

        // Fungsi bantu untuk tambah input caption
        function addCaptionInput(uid, label, isNew) {
            $('#' + captionContainerId).append(`
                <div class="form-group ${isNew ? 'new-file' : ''}" id="caption-group-${uid}">
                    <label class="control-label mb-0 small mt-2" for="keterangan-${uid}">
                        {{ __('cruds.program.ket_file') }}: <span class="text-red">${label}</span>
                    </label>
                    <input type="text" class="form-control" name="${captionPrefix}[]" id="keterangan-${uid}">
                </div>
            `);
        }
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

            $(this).find('button[type="submit"]').attr('disabled', 'disabled');
            var formData = new FormData(this);

            // Log the values of staff[] and peran[] to verify they are included
            var staffValues = $('select[name="staff[]"]').map(function() {
                return $(this).val();
            }).get();
            var peranValues = $('select[name="peran[]"]').map(function() {
                return $(this).val();
            }).get();

            // console.log('Staff Values:', staffValues);
            // console.log('Peran Values:', peranValues);

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

                // Collect and unmask totalnilaiprogram
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
            // Detailed logging
            for (var pair of formData.entries()) {
                console.log(`${pair[0]}: ${pair[1]}`);
            }

            // Check if we have large file upload (50+ files)
            const fileCounts = formData.getAll('file_pendukung').length;
            const isBulkUpload = fileCounts >= 10; // Adjusted threshold for bulk upload

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
                url: $(this).attr('action'),
                type: 'post',
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
                timeout: 300000, // 5 minutes timeout
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
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
                beforeSend: function() {
                    // Log the values of staff[] and peran[] to verify they are included
                    var staffValues = $('select[name="staff[]"]').map(function() {
                        return $(this).val();
                    }).get();
                    var peranValues = $('select[name="peran[]"]').map(function() {
                        return $(this).val();
                    }).get();
                },
                success: function(response) {
                    setTimeout(() => {
                        if (response.success === true) {
                            if (isBulkUpload) {
                                completeBulkUpload();
                            }

                            // Check if files are being processed in background
                            if (response.message && response.message.includes('Files are being processed')) {
                                Swal.fire({
                                    title: "Success",
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
                                            <p class="text-muted small">Page will reload shortly...</p>
                                        </div>
                                    `,
                                    icon: "success",
                                    timer: 5000,
                                    timerProgressBar: true,
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    allowEscapeKey: false
                                }).then(() => {
                                    $('#editProgram').trigger('reset');
                                    $('#kelompokmarjinal, #targetreinstra, #kaitansdg').val('').trigger('change');
                                    $('#editProgram').find('button[type="submit"]').removeAttr('disabled');
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: "Success",
                                    text: response.message,
                                    icon: "success",
                                    timer: 1500,
                                    timerProgressBar: true,
                                }).then(() => {
                                    $('#editProgram').trigger('reset');
                                    $('#kelompokmarjinal, #targetreinstra, #kaitansdg').val('').trigger('change');
                                    $('#editProgram').find('button[type="submit"]').removeAttr('disabled');
                                    window.location.reload();
                                });
                            }
                        }
                    }, 500);
                },
                // error: function (xhr, textStatus, errorThrown) {
                //     $('#editProgram').find('button[type="submit"]').removeAttr('disabled');
                //     if (isBulkUpload) {
                //         failBulkUpload();
                //     }
                //     const errorMessage = getErrorMessage(xhr);
                //     const response = JSON.parse(xhr.responseText);

                //     // Check for file quantity validation errors
                //     if (errorMessage.includes('Too many files') ||
                //         errorMessage.includes('Maximum') ||
                //         (response.errors && response.errors['file_pendukung'])) {
                //         Swal.fire({
                //             title: "File Upload Limit Exceeded!",
                //             html: `
                //                 <div class="text-center">
                //                     <div class="mb-3">
                //                         <i class="fas fa-exclamation-triangle fa-3x text-warning"></i>
                //                     </div>
                //                     <p><strong>File upload validation failed.</strong></p>
                //                     <p class="text-muted">${errorMessage}</p>
                //                     <p class="text-info">Maximum allowed: 50 files per upload</p>
                //                     <p class="text-danger">Please reduce the number of files and try again.</p>
                //                 </div>
                //             `,
                //             icon: "warning",
                //             confirmButtonText: "OK",
                //             confirmButtonColor: "#3085d6"
                //         });
                //     } else if (response.errors) {
                //         addInvalidClassToFields(response.errors);
                //         Swal.fire({
                //             icon: 'error',
                //             title: 'Error!',
                //             html: errorMessage,
                //             confirmButtonText: 'Okay'
                //         });
                //     } else {
                //         Swal.fire({
                //             icon: 'error',
                //             title: 'Error!',
                //             html: errorMessage,
                //             confirmButtonText: 'Okay'
                //         });
                //     }
                // },
                error: function (xhr) {
                    $form.find('button[type="submit"]').removeAttr('disabled');
                    if (isBulkUpload) {
                        failBulkUpload();
                    }
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

            });
        });

        // Bulk upload progress functions
        function showBulkUploadProgress(fileCounts) {
            Swal.fire({
                title: 'Uploading Files',
                html: `
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="fas fa-cloud-upload-alt fa-3x text-primary"></i>
                        </div>
                        <p><strong>Uploading ${fileCounts} files...</strong></p>
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
