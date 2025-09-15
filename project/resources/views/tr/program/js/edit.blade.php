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
    // This logic has been moved to the main edit.blade.php file to handle validation correctly
</script>
