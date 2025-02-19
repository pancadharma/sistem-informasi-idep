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
                        // Optionally, you can add error messages below the input fields
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



    //SCRIPT FOR CREATE PROGRAM FORM
    // $('#totalnilai').maskMoney({
    //     prefix: 'Rp. ',
    //     allowNegative: false,
    //     thousands: '.',
    //     decimal: ',',
    //     affixesStay: false
    // });
    $(document).ready(function() {
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
            placeholder: "{{ __('cruds.program.select_reinstra') }}",
            width: '100%',
            allowClear: true,
            closeOnSelect: false,
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
            placeholder: "{{ __('cruds.program.select_sdg') }}",
            width: '100%',
            allowClear: true,
            closeOnSelect: false,
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

        // CREATE PROGRAM AND SAVE SOME DETAILS
        // $('#createProgram').on('submit', function(e) {
        //     e.preventDefault();
        //     $(this).find('button[type="submit"]').attr('disabled', 'disabled');
        //     var formData = new FormData(this);

        //     var fieldsToRemove = [];
        //     $('input.currency').each(function() {
        //         fieldsToRemove.push($(this).attr('name'));
        //     });
        //     // Remove original masked `nilaidonasi` values from FormData
        //     fieldsToRemove.forEach(function(field) {
        //         formData.delete(field);
        //     });
        //     // Unmask all AutoNumeric fields and append unmasked values
        //     $('input.currency').each(function() {
        //         var unmaskedValue = AutoNumeric.getAutoNumericElement(this).getNumericString();
        //         formData.append($(this).attr('name'), unmaskedValue);
        //     });

        //     $.ajax({
        //         url: "{{ route('program.store') }}",
        //         method: 'POST',
        //         data: formData,
        //         processData: false,
        //         contentType: false,
        //         beforeSend: function() {
        //             Toast.fire({
        //                 icon: "info",
        //                 title: "Processing...",
        //                 timer: 3000,
        //                 timerProgressBar: true,
        //             });
        //         },
        //         success: function(response) {
        //             setTimeout(() => {
        //                 if (response.success === true) {
        //                     Swal.fire({
        //                         title: "{{ __('global.success') }}",
        //                         text: response.message,
        //                         icon: "success",
        //                         timer: 500,
        //                         timerProgressBar: true,
        //                     });
        //                     $(this).trigger('reset');
        //                     $('#createProgram')[0].reset();
        //                     $('#createProgram').trigger('reset');
        //                     $('#kelompokmarjinal, #targetreinstra, #kaitansdg').val('').trigger('change');
        //                     // $(".btn-tool").trigger('click');
        //                     $('#createProgram').find('button[type="submit"]').removeAttr('disabled');
        //                     setTimeout(function() {
        //                         window.location.href ="{{ route('program.index') }}"; //redirect into index program
        //                     }, 500);
        //                 }
        //             }, 500);
        //         },
        //         error: function(xhr, textStatus, errorThrown) {
        //             $('#createProgram').find('button[type="submit"]').removeAttr('disabled');
        //             const errorMessage = getErrorMessage(xhr);
        //             const response = JSON.parse(xhr.responseText);
        //             if (response.errors) {
        //                 addInvalidClassToFields(response.errors);
        //             }
        //             Swal.fire({
        //                 icon: 'error',
        //                 title: 'Error!',
        //                 html: errorMessage,
        //                 confirmButtonText: 'Okay'
        //             });
        //         },
        //         complete: function() {
        //             setTimeout(() => {
        //                 $(this).find('button[type="submit"]').removeAttr(
        //                     'disabled');
        //             }, 500);
        //         }
        //     });

        // });

        $('#createProgram').on('submit', function(e) {
            e.preventDefault();
            $('#outcomeTemplate').find('textarea, input').attr('disabled', true);
            const $form = $(this);
            $form.find('button[type="submit"]').attr('disabled', true);
            let formData = new FormData(this);

            // Remove original masked values from FormData
            $('input.currency').each(function() {
                formData.delete($(this).attr('name'));
                const unmaskedValue = AutoNumeric.getAutoNumericElement(this).getNumericString();
                formData.append($(this).attr('name'), unmaskedValue);
            });

            // Collect selected staff and peran data from the dynamically added rows
            $('.staff-select').each(function() {
                formData.append('staff[]', $(this).val());
            });
            $('.peran-select').each(function() {
                formData.append('peran[]', $(this).val());
            });

            $('#outcomeTemplate').find('textarea, input').removeAttr('disabled');
            console.log(formData);

            $.ajax({
                url: "{{ route('program.store') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    Toast.fire({
                        icon: "info",
                        title: "Processing...",
                        timer: 3000,
                        timerProgressBar: true,
                    });
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: "{{ __('global.success') }}",
                            text: response.message,
                            icon: "success",
                            timer: 500,
                            timerProgressBar: true,
                        }).then(() => {
                            $form[0].reset();
                            $('#outcomeContainer .row').not("#outcomeTemplate").remove(); // Clear dynamically added outcomes
                            $('#kelompokmarjinal, #targetreinstra, #kaitansdg').val('').trigger('change');
                            window.location.href = "{{ route('program.index') }}";
                        });
                    }
                },
                error: function(xhr) {
                    $form.find('button[type="submit"]').removeAttr('disabled');
                    const response = JSON.parse(xhr.responseText);
                    if (response.errors) {
                        addInvalidClassToFields(response.errors);
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        html: getErrorMessage(xhr),
                        confirmButtonText: 'Okay'
                    });
                },
                complete: function() {
                    setTimeout(() => {
                        $form.find('button[type="submit"]').removeAttr('disabled');
                    }, 500);
                }
            });
        });


    });
</script>
