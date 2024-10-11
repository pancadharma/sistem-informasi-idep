<script>
    $('#totalnilai').maskMoney({
        prefix: 'Rp. ',
        allowNegative: false,
        thousands: '.',
        decimal: ',',
        affixesStay: false
    });
    //SCRIPT FOR CREATE PROGRAM FORM

    $(document).ready(function() {
        $('#status').select2();
        var url_update = $('#editProgram').attr('action');

        $(document).ready(function() {
            $("#file_pendukung").fileinput({
                theme: 'fa',
                showRemove: true,
                previewZoomButtonTitles: true,
                dropZoneEnabled: false,
                removeIcon: '<i class="bi bi-trash"></i>',
                showDrag: true,
                dragIcon: '<i class="bi-arrows-move"></i>',
                showZoom: true,
                showUpload: false,
                showRotate: true,
                showCaption: true,
                uploadUrl: url_update,
                uploadAsync: false,
                maxFileSize: 4096,
                allowedFileExtensions: ["jpg", "png", "gif", "pdf", "jpeg", "bmp", "doc",
                    "docx"
                ],
                append: true,
                initialPreview: @json($initialPreview),
                initialPreviewAsData: true,
                initialPreviewConfig: @json($initialPreviewConfig),

                overwriteInitial: false,
            });

            $('#file_pendukung').on('change', function(event) {
                const files = event.target.files;
                const dataTransfer = new DataTransfer();
                for (let i = 0; i < this.files.length; i++) {
                    dataTransfer.items.add(this.files[i]);
                }
                for (let file of files) {
                    dataTransfer.items.add(file);
                }
                this.files = dataTransfer.files;
            });
        });

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

        $('#editProgram').on('submit', function(e) {
            e.preventDefault();
            $(this).find('button[type="submit"]').attr('disabled', 'disabled');
            var formData = new FormData(this);
            // let url = $(this).attr('action');
            var unmaskedValue = $('#totalnilai').maskMoney('unmasked')[0];
            formData.set('totalnilai', unmaskedValue);

            $.ajax({
                url: url_update,
                method: 'PUT',
                data: formData,
                processData: false,
                contentType: false,
                // dataType: 'json',
                success: function(response) {
                    setTimeout(() => {
                        if (response.success === true) {
                            Swal.fire({
                                title: "{{ __('global.success') }}",
                                text: response.message,
                                icon: "success",
                                timer: 1500,
                                timerProgressBar: true,
                            });
                            $(this).trigger('reset');
                            $('#editProgram')[0].reset();
                            $('#editProgram').trigger('reset');
                            $('#kelompokmarjinal, #targetreinstra, #kaitansdg').val(
                                '').trigger('change');
                            $(".btn-tool").trigger('click');
                            $('#editProgram').find('button[type="submit"]')
                                .removeAttr('disabled');
                        }
                    }, 500);
                },
                error: function(xhr, status, error) {
                    $('#editProgram').find('button[type="submit"]').removeAttr(
                        'disabled');
                    let errorMessage = `Error: ${xhr.status} - ${xhr.statusText}`;
                    try {
                        const response = xhr.responseJSON;
                        if (response.errors) {
                            errorMessage +=
                                '<br><br><ul style="text-align:left!important">';
                            $.each(response.errors, function(field, messages) {
                                messages.forEach(message => {
                                    errorMessage +=
                                        `<li>${field}: ${message}</li>`;
                                    $(`#${field}-error`).removeClass(
                                        'is-valid').addClass(
                                        'is-invalid');
                                    $(`#${field}-error`).text(message);
                                    $(`#${field}`).removeClass('invalid')
                                        .addClass('is-invalid');
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    html: errorMessage,
                                });
                            });
                            errorMessage += '</ul>';
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        html: errorMessage,
                    });
                },
                complete: function() {
                    setTimeout(() => {
                        $(this).find('button[type="submit"]').removeAttr(
                            'disabled');
                    }, 500);
                }
            });

        });

    });


    $('.select-all').click(function() {
        let $select2 = $(this).parent().siblings('.select2')
        $select2.find('option').prop('selected', 'selected')
        $select2.trigger('change')
    })
    $('.deselect-all').click(function() {
        let $select2 = $(this).parent().siblings('.select2')
        $select2.find('option').prop('selected', '')
        $select2.trigger('change')
    })
</script>
