@extends('layouts.app')
@section('subtitle', __('cruds.kegiatan.edit') . ' ' . $kegiatan->activity->nama)
@section('content_header_title')
    <strong>
        {{ __('cruds.kegiatan.edit') . ' ' . $kegiatan->activity->nama ?? '-' }}</strong>
@endsection

@section('sub_breadcumb')
    <a href="{{ route('kegiatan.index') }}" title="{{ __('cruds.kegiatan.list') }}"> {{ __('cruds.kegiatan.list') }}</a>
@endsection

@section('sub_sub_breadcumb')
    / <span title="Current Page {{ __('cruds.kegiatan.edit') }}">{{ __('cruds.kegiatan.edit') }}</span>
@endsection

@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">{{ __('global.loading') }}...</h4>
@endsection

@section('content_body')
    <form id="createKegiatan" method="POST" class="needs-validation" data-toggle="validator" autocomplete="off"
        action="{{ route('kegiatan.update', [$kegiatan->id]) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            @include('tr.kegiatan.tabs')
        </div>
    </form>
@stop

@include('tr.kegiatan.modal._preview')
@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/krajee-fileinput/css/fileinput.min.css') }}">
    <style>
        .card-header.border-bottom-0.card-header.p-0.pt-1.navigasi {
            position: sticky;
            z-index: 1030;
            top: 0;
        }

        .select2-selection.is-invalid-select2 {
            border-color: #dc3545 !important;
        }

        .select2-selection.is-valid-select2 {
            border-color: #28a745 !important;
        }

        .select2-container--default.select2-container--open .select2-selection--single.is-invalid-select2 {
            border-color: #a7dc35 !important;
        }

        .fixed {
            position: fixed;
            bottom: 0;
            left: 0;
            z-index: 2;
            width: 100% !important;
        }

        .content-header h1 {
            font-size: 1.1rem !important;
            margin: 0;
        }

        .note-toolbar {
            background: #00000000 !important;
        }

        .note-editor.note-frame .note-statusbar,
        .note-editor.note-airframe .note-statusbar {
            background-color: #007bff17 !important;
            border-bottom-left-radius: 4px;
            border-bottom-right-radius: 4px;
            border-top: 1px solid #00000000;
        }

        .table-custom th:nth-child(2),
        .table-custom td:nth-child(2),
        .table-custom th:nth-child(3),
        .table-custom td:nth-child(3),
        .table-custom th:nth-child(4),
        .table-custom td:nth-child(4) {
            width: 20%;
        }

        .table-custom th:first-child,
        .table-custom td:first-child {
            width: 40%;
        }
    </style>
@endpush

@push('js')
    @section('plugins.Sweetalert2', true)
    @section('plugins.DatatablesNew', true)
    @section('plugins.Select2', true)
    @section('plugins.Toastr', true)
    @section('plugins.Validation', true)
    @section('plugins.Summernote', true)

    <script src="{{ asset('/vendor/inputmask/jquery.maskMoney.js') }}"></script>
    <script src="{{ asset('/vendor/inputmask/AutoNumeric.js') }}"></script>
    <script src="{{ asset('vendor/krajee-fileinput/js/plugins/buffer.min.js') }}"></script>
    <script src="{{ asset('vendor/krajee-fileinput/js/plugins/sortable.min.js') }}"></script>
    <script src="{{ asset('vendor/krajee-fileinput/js/plugins/piexif.min.js') }}"></script>
    <script src="{{ asset('vendor/krajee-fileinput/js/fileinput.min.js') }}"></script>
    <script src="{{ asset('vendor/krajee-fileinput/js/locales/id.js') }}"></script>

    @include('tr.kegiatan.js.create')
    @stack('basic_tab_js')
    @include('tr.kegiatan.js.tabs.basic')
    <script>
        $(document).ready(function() {
            function validasiProgramIDActivityID() {
                let program_id = $('#program_id').val();
                let kode_program = $('#kode_program').val();
                let activity_id = $('#programoutcomeoutputactivity_id').val();

                // Check if either program_id and activity_id is empty or invalid
                if (!program_id && !activity_id) {
                    return false;
                }
                if (isNaN(program_id) || isNaN(activity_id)) {
                    return false;
                }
                if (program_id !== '' && activity_id !== '') {
                    return false;
                }

                return true;
            }

            let errorMessage = '';

            function validateForm() {
                let isValid = true;
                const programValidation = validasiProgramIDActivityID();

                errorMessage = '';
                if (!programValidation.isValid) {
                    isValid = false;
                    errorMessage += programValidation.message + '<br>';
                }

                const jenisKegiatanValidation = validasiSingleSelect2('#jeniskegiatan_id', '{{ __('cruds.kegiatan.validate.jenis_kegiatan') }}');

                if (!jenisKegiatanValidation.isValid) {
                    isValid = false;
                    errorMessage += jenisKegiatanValidation.message + '<br>';
                }

                const sektorValidation = validasiMultipleSelect2('#sektor_id', '{{ __('cruds.kegiatan.validate.sektor') }}');
                if (!sektorValidation.isValid) {
                    isValid = false;
                    errorMessage += sektorValidation.message + '<br>';
                }

                const mitraValidation = validasiMultipleSelect2('#mitra_id', '{{ __('cruds.kegiatan.validate.mitra') }}');
                // const mitraValidation = validasiMultipleSelect2('#mitra_id', '{!! __('cruds.kegiatan.validate.mitra', ['icon' => '<i class="bi bi-exclamation-lg text-danger"></i>']) !!}');
                if (!mitraValidation.isValid) {
                    isValid = false;
                    errorMessage += mitraValidation.message + '<br>';
                }

                const longLatValidation = validasiLongLat();
                if (!longLatValidation.isValid) {
                    isValid = false;
                    errorMessage += longLatValidation.errorMessage + '<br>';
                }

                const penulisValidation = validasiPenulis();
                if (!penulisValidation.isValid) {
                    isValid = false;
                    errorMessage += penulisValidation.message + '<br>';
                }

                if (!isValid) {
                    Swal.fire({
                        title: '',
                        html: errorMessage,
                        icon: 'warning'
                    });
                    return false;
                }

                return true;
            }

            function validasiLongLat() {
                let kecamatanInputs = $('.kecamatan-select').map(function() {
                    return $(this).val();
                }).get();
                let kelurahanInputs = $('.kelurahan-select').map(function() {
                    return $(this).val();
                }).get();
                let latInputs = document.querySelectorAll('input[name="lat[]"]');
                let longInputs = document.querySelectorAll('input[name="long[]"]');
                let lokasiInputs = document.querySelectorAll('input[name="lokasi[]"]');

                let isValid = true;
                let longLatErrorMessage = '';
                let kecamatanKelurahanError = false;
                let longLatError = false;
                let lokasiError = false;

                for (let i = 0; i < latInputs.length; i++) {
                    let latValue = latInputs[i].value.trim();
                    let longValue = longInputs[i].value.trim();
                    let lokasiValue = lokasiInputs[i].value.trim();

                    let kecamatanValue = kecamatanInputs[i];
                    let kelurahanValue = kelurahanInputs[i];

                    console.log("Data Select2 of array Kec & Kel :", kecamatanValue, kelurahanValue);

                    let $kecamatanSelect2Container = $($('.kecamatan-select')[i]).next('.select2-container');
                    let $kelurahanSelect2Container = $($('.kelurahan-select')[i]).next('.select2-container');

                    if (!kecamatanValue || !kelurahanValue) {
                        isValid = false;
                        kecamatanKelurahanError = true;

                        $kecamatanSelect2Container.find('.select2-selection').addClass('is-invalid-select2');
                        $kelurahanSelect2Container.find('.select2-selection').addClass('is-invalid-select2');

                    } else {
                        $kecamatanSelect2Container.find('.select2-selection').removeClass('is-invalid-select2');
                        $kelurahanSelect2Container.find('.select2-selection').removeClass('is-invalid-select2');
                        $kecamatanSelect2Container.find('.select2-selection').addClass('is-valid-select2');
                        $kelurahanSelect2Container.find('.select2-selection').addClass('is-valid-select2');
                    }

                    if (!lokasiValue) {
                        isValid = false;
                        lokasiError = true;
                        lokasiInputs[i].classList.add('is-invalid');
                    } else {
                        lokasiInputs[i].classList.remove('is-invalid');
                    }

                    // Check if latitude is empty or not a valid number between -90 and 90
                    if (!latValue || isNaN(parseFloat(latValue)) || parseFloat(
                            latValue) < -90 || parseFloat(
                            latValue) > 90) {
                        isValid = false;
                        longLatError = true;
                        latInputs[i].classList.add('is-invalid');
                    } else {
                        latInputs[i].classList.remove('is-invalid');
                    }

                    if (!longValue || isNaN(parseFloat(longValue)) || parseFloat(longValue) < -180 || parseFloat(longValue) > 180) {
                        isValid = false;
                        longLatError = true;
                        longInputs[i].classList.add('is-invalid');
                    } else {
                        longInputs[i].classList.remove('is-invalid');
                    }
                }
                if (kecamatanKelurahanError) {
                    longLatErrorMessage += '{{ __('cruds.kegiatan.validate.kec_kel') }} <br>';
                }
                if (longLatError) {
                    longLatErrorMessage += '{{ __('cruds.kegiatan.validate.longlat') }} <br>';
                }
                if (lokasiError) {
                    longLatErrorMessage += '{{ __('cruds.kegiatan.validate.tempat_kegiatan') }} <br>';
                }

                return {
                    isValid: isValid,
                    errorMessage: longLatErrorMessage
                };
            }

            function validasiPenulis() {
                let isValid = true;
                let message = '';

                $('.penulis-row').each(function(index) {
                    let penulisValue = $(this).find('.penulis-select').val();
                    let jabatanValue = $(this).find('.jabatan-select').val();

                    if (!penulisValue) {
                        isValid = false;
                        message = "{{ __('global.pleaseSelect') .' '. __('cruds.kegiatan.penulis.nama') }}";
                        applySelect2ErrorStyling($(this).find('.penulis-select'));
                    } else {
                        removeSelect2ErrorStyling($(this).find('.penulis-select'));
                    }

                    if (!jabatanValue) {
                        isValid = false;
                        message = "{{ __('global.pleaseSelect') .' '. __('cruds.kegiatan.penulis.jabatan') }}";
                        applySelect2ErrorStyling($(this).find('.jabatan-select'));
                    } else {
                        removeSelect2ErrorStyling($(this).find('.jabatan-select'));
                    }
                });

                return {
                    isValid: isValid,
                    message: message
                };
            }

            function validasiMultipleSelect2(selector, message) {
                let values = $(selector).val();
                if (!values || values.length === 0) {
                    applySelect2ErrorStyling(selector);
                    return {
                        isValid: false,
                        message: message
                    };
                }
                removeSelect2ErrorStyling(selector);
                return {
                    isValid: true,
                    message: ''
                };
            }

            function validasiSingleSelect2(selector, message) {
                let value = $(selector).val();
                if (!value) {
                    applySelect2ErrorStyling(selector);
                    return {
                        isValid: false,
                        message: message
                    };
                }
                removeSelect2ErrorStyling(selector);
                return {
                    isValid: true,
                    message: ''
                };
            }

            function applySelect2ErrorStyling(selector) {
                $(selector).next('.select2-container').find('.select2-selection').addClass('is-invalid-select2');
            }

            function removeSelect2ErrorStyling(selector) {
                $(selector).next('.select2-container').find('.select2-selection').removeClass('is-invalid-select2');
                $(selector).next('.select2-container').find('.select2-selection').addClass('is-valid-select2');
            }

            function validasiProgramIDActivityID() {
                let isValid = true;
                let message = '';

                let program_id = $('#program_id').val();
                let activity_id = $('#programoutcomeoutputactivity_id').val();

                let programError = false;
                let activityError = false;

                if (!program_id) {
                    isValid = false;
                    programError = true;
                    $('#kode_program, #nama_program').addClass('is-invalid');
                } else {
                    $('#kode_program, #nama_program').removeClass('is-invalid');
                    $('#kode_program, #nama_program').addClass('is-valid');
                }

                if (!activity_id) {
                    isValid = false;
                    activityError = true;
                    $('#kode_kegiatan, #nama_kegiatan').addClass('is-invalid');
                } else {
                    $('#kode_kegiatan, #nama_kegiatan').removeClass('is-invalid');
                    $('#kode_kegiatan, #nama_kegiatan').addClass('is-valid');
                }

                if (programError && activityError) {
                    message = '{{ __('cruds.kegiatan.validate.program_activity') }}';
                } else if (programError) {
                    message = '{{ __('cruds.kegiatan.validate.program') }}';
                } else if (activityError) {
                    message = '{{ __('cruds.kegiatan.validate.activity') }}';
                }

                return {
                    isValid: isValid,
                    message: message
                };
            }
            // submision if all validated
            $('#simpan_kegiatan').on('click', function(e) {
                e.preventDefault();
                if (!validateForm()) {
                    return false;
                }
                // Get form data
                let formData = new FormData($('#createKegiatan')[0]);
                let serializedData = $("#createKegiatan").serializeArray();

                // Convert serialized data to a readable format for display
                let displayData = serializedData.map(item => `${item.name}: ${item.value}`).join('
');
                console.log(`pre ${displayData}`);

                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah anda yakin ingin menyimpan data ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{ __('global.yes') }}' + ', ' + '{{ __('global.save') }}' + ' ! '
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        Swal.fire({
                            title: 'Processing...',
                            html: 'Please wait while we save your data. This may take a few minutes for large files.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $.ajax({
                            url: "{{ route('api.kegiatan.update', $kegiatan->id) }}",
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            cache: false,
                            timeout: 300000, // 5 minutes timeout
                            xhr: function() {
                                var xhr = new window.XMLHttpRequest();
                                // Upload progress
                                xhr.upload.addEventListener("progress", function(evt) {
                                    if (evt.lengthComputable) {
                                        var percentComplete = (evt.loaded / evt.total) * 100;
                                        Swal.update({
                                            html: `Uploading... ${Math.round(percentComplete)}%`
                                        });
                                    }
                                }, false);
                                return xhr;
                            },
                            success: function(data) {
                                Swal.fire({
                                    title: '{{ __('global.response.success') }}',
                                    text: '{{ __('global.response.save_success') }}',
                                    icon: 'success'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = "{{ route('kegiatan.index') }}";
                                    }
                                });
                            },
                            error: function(xhr, status, error) {
                                let errorMessage = '{{ __('global.response.save_failed') }}';

                                if (status === 'timeout') {
                                    errorMessage = 'Request timeout. Please try again with smaller files or check your internet connection.';
                                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                } else if (xhr.responseText) {
                                    errorMessage = xhr.responseText;
                                } else if (xhr.statusText) {
                                    errorMessage = xhr.statusText;
                                }

                                Swal.fire({
                                    title: '{{ __('global.error') }}',
                                    text: errorMessage ?? '{{ __('global.response.save_failed') }}',
                                    icon: 'error'
                                });

                                console.table(xhr);
                                console.log('Error:', error);
                                console.log('Status:', status);
                                console.log('Response:', xhr.responseText);
                            }
                        });
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Initialize Summernote
            $('.summernote').summernote({
                height: 120,
                width: '100%',
                inheritPlaceholder: true,
                tabDisable: true,
                codeviewFilter: false,
            });

            // Set initial values for Summernote editors
            $('#deskripsilatarbelakang').summernote('code', `{!! old('deskripsilatarbelakang', $kegiatan->deskripsilatarbelakang) !!}`);
            $('#deskripsitujuan').summernote('code', `{!! old('deskripsitujuan', $kegiatan->deskripsitujuan) !!}`);
            $('#deskripsikeluaran').summernote('code', `{!! old('deskripsikeluaran', $kegiatan->deskripsikeluaran) !!}`);

            // Initialize participant fields
            $('#penerimamanfaatdewasaperempuan').val("{{ old('penerimamanfaatdewasaperempuan', $kegiatan->penerimamanfaatdewasaperempuan) }}");
            $('#penerimamanfaatdewasalakilaki').val("{{ old('penerimamanfaatdewasalakilaki', $kegiatan->penerimamanfaatdewasalakilaki) }}");
            $('#penerimamanfaatlansiaperempuan').val("{{ old('penerimamanfaatlansiaperempuan', $kegiatan->penerimamanfaatlansiaperempuan) }}");
            $('#penerimamanfaatlansialakilaki').val("{{ old('penerimamanfaatlansialakilaki', $kegiatan->penerimamanfaatlansialakilaki) }}");
            $('#penerimamanfaatremajaperempuan').val("{{ old('penerimamanfaatremajaperempuan', $kegiatan->penerimamanfaatremajaperempuan) }}");
            $('#penerimamanfaatremajalakilaki').val("{{ old('penerimamanfaatremajalakilaki', $kegiatan->penerimamanfaatremajalakilaki) }}");
            $('#penerimamanfaatanakperempuan').val("{{ old('penerimamanfaatanakperempuan', $kegiatan->penerimamanfaatanakperempuan) }}");
            $('#penerimamanfaatanaklakilaki').val("{{ old('penerimamanfaatanaklakilaki', $kegiatan->penerimamanfaatanaklakilaki) }}");
            $('#penerimamanfaatdisabilitasperempuan').val("{{ old('penerimamanfaatdisabilitasperempuan', $kegiatan->penerimamanfaatdisabilitasperempuan) }}");
            $('#penerimamanfaatdisabilitaslakilaki').val("{{ old('penerimamanfaatdisabilitaslakilaki', $kegiatan->penerimamanfaatdisabilitaslakilaki) }}");
            $('#penerimamanfaatnondisabilitasperempuan').val("{{ old('penerimamanfaatnondisabilitasperempuan', $kegiatan->penerimamanfaatnondisabilitasperempuan) }}");
            $('#penerimamanfaatnondisabilitaslakilaki').val("{{ old('penerimamanfaatnondisabilitaslakilaki', $kegiatan->penerimamanfaatnondisabilitaslakilaki) }}");
            $('#penerimamanfaatmarjinalperempuan').val("{{ old('penerimamanfaatmarjinalperempuan', $kegiatan->penerimamanfaatmarjinalperempuan) }}");
            $('#penerimamanfaatmarjinallakilaki').val("{{ old('penerimamanfaatmarjinallakilaki', $kegiatan->penerimamanfaatmarjinallakilaki) }}");

            // Function to calculate totals
            function calculateTotals() {
                // Dewasa
                let dewasaP = parseInt($('#penerimamanfaatdewasaperempuan').val()) || 0;
                let dewasaL = parseInt($('#penerimamanfaatdewasalakilaki').val()) || 0;
                $('#penerimamanfaatdewasatotal').val(dewasaP + dewasaL);

                // Lansia
                let lansiaP = parseInt($('#penerimamanfaatlansiaperempuan').val()) || 0;
                let lansiaL = parseInt($('#penerimamanfaatlansialakilaki').val()) || 0;
                $('#penerimamanfaatlansiatotal').val(lansiaP + lansiaL);

                // Remaja
                let remajaP = parseInt($('#penerimamanfaatremajaperempuan').val()) || 0;
                let remajaL = parseInt($('#penerimamanfaatremajalakilaki').val()) || 0;
                $('#penerimamanfaatremajatotal').val(remajaP + remajaL);

                // Anak
                let anakP = parseInt($('#penerimamanfaatanakperempuan').val()) || 0;
                let anakL = parseInt($('#penerimamanfaatanaklakilaki').val()) || 0;
                $('#penerimamanfaatanaktotal').val(anakP + anakL);

                // Total Perempuan & Laki-laki
                $('#penerimamanfaatperempuantotal').val(dewasaP + lansiaP + remajaP + anakP);
                $('#penerimamanfaatlakilakitotal').val(dewasaL + lansiaL + remajaL + anakL);

                // Grand Total
                $('#penerimamanfaattotal').val((dewasaP + dewasaL) + (lansiaP + lansiaL) + (remajaP + remajaL) + (anakP + anakL));

                // Disabilitas
                let disabilitasP = parseInt($('#penerimamanfaatdisabilitasperempuan').val()) || 0;
                let disabilitasL = parseInt($('#penerimamanfaatdisabilitaslakilaki').val()) || 0;
                $('#penerimamanfaatdisabilitastotal').val(disabilitasP + disabilitasL);

                // Non-Disabilitas
                let nonDisabilitasP = parseInt($('#penerimamanfaatnondisabilitasperempuan').val()) || 0;
                let nonDisabilitasL = parseInt($('#penerimamanfaatnondisabilitaslakilaki').val()) || 0;
                $('#penerimamanfaatnondisabilitastotal').val(nonDisabilitasP + nonDisabilitasL);

                // Marjinal
                let marjinalP = parseInt($('#penerimamanfaatmarjinalperempuan').val()) || 0;
                let marjinalL = parseInt($('#penerimamanfaatmarjinallakilaki').val()) || 0;
                $('#penerimamanfaatmarjinaltotal').val(marjinalP + marjinalL);

                // Total Beneficiaries
                $('#total_beneficiaries_perempuan').val(disabilitasP + nonDisabilitasP + marjinalP);
                $('#total_beneficiaries_lakilaki').val(disabilitasL + nonDisabilitasL + marjinalL);
                $('#beneficiaries_difable_total').val((disabilitasP + disabilitasL) + (nonDisabilitasP + nonDisabilitasL) + (marjinalP + marjinalL));
            }

            // Calculate totals on page load
            calculateTotals();

            // Add event listeners to input fields
            $('.calculate, .hitung-difabel').on('input', calculateTotals);
        });
    </script>

    <script>
        $(document).ready(function() {
            function initializeFile(elementId, options) {
                const { initialPreview, initialPreviewConfig, initialPreviewAsData, initialPreviewFileType, initialPreviewShowDelete, deleteUrl, deleteExtraData, overwriteInitial, language, allowedFileExtensions, maxFileSize, maxFilesNum, dropZoneEnabled, showRemove, showUpload, showCancel, showBrowse, browseOnZoneClick, theme, autoReplace, caption, msgError, layoutTemplates, uploadUrl, uploadExtraData } = options;

                $("#" + elementId).fileinput({
                    initialPreview: initialPreview,
                    initialPreviewConfig: initialPreviewConfig,
                    initialPreviewAsData: initialPreviewAsData,
                    initialPreviewFileType: initialPreviewFileType,
                    initialPreviewShowDelete: initialPreviewShowDelete,
                    deleteUrl: deleteUrl,
                    deleteExtraData: deleteExtraData,
                    overwriteInitial: overwriteInitial,
                    language: language,
                    allowedFileExtensions: allowedFileExtensions,
                    maxFileSize: maxFileSize,
                    maxFilesNum: maxFilesNum,
                    dropZoneEnabled: dropZoneEnabled,
                    showRemove: showRemove,
                    showUpload: showUpload,
                    showCancel: showCancel,
                    showBrowse: showBrowse,
                    browseOnZoneClick: browseOnZoneClick,
                    theme: theme,
                    autoReplace: autoReplace,
                    caption: caption,
                    msgError: msgError,
                    layoutTemplates: layoutTemplates,
                    uploadUrl: uploadUrl,
                    uploadExtraData: uploadExtraData
                }).on('filedeleted', function(event, key, jqXHR, data) {
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    );
                }).on('filepredelete', function(event, key, jqXHR, data) {
                    return new Promise(function(resolve, reject) {
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                resolve();
                            } else {
                                reject();
                            }
                        });
                    });
                });
            }

            // Initialize for 'dokumen_pendukung'
            initializeFile('dokumen_pendukung', {
                initialPreview: {!! json_encode($dokumen_initialPreview) !!},
                initialPreviewConfig: {!! json_encode($dokumen_initialPreviewConfig) !!},
                initialPreviewAsData: true,
                initialPreviewFileType: 'pdf',
                initialPreviewShowDelete: true,
                deleteUrl: "{{ route('api.kegiatan.delete_media') }}",
                deleteExtraData: { _token: "{{ csrf_token() }}" },
                overwriteInitial: false,
                language: 'id',
                allowedFileExtensions: ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'pptx'],
                maxFileSize: 50000,
                maxFilesNum: 10,
                dropZoneEnabled: true,
                showRemove: true,
                showUpload: false,
                showCancel: true,
                showBrowse: true,
                browseOnZoneClick: true,
                theme: 'fa5',
                autoReplace: false,
                caption: function(files) {
                    // Your caption logic here
                },
                msgError: 'Error uploading file',
                layoutTemplates: {
                    // Your layout templates here
                },
                uploadUrl: "{{ route('api.kegiatan.upload_media') }}",
                uploadExtraData: {
                    _token: "{{ csrf_token() }}",
                    kegiatan_id: "{{ $kegiatan->id }}",
                    collection_name: 'dokumen_pendukung'
                }
            });

            // Initialize for 'media_pendukung'
            initializeFile('media_pendukung',.blade.php
                initialPreview: {!! json_encode($media_initialPreview) !!},
                initialPreviewConfig: {!! json_encode($media_initialPreviewConfig) !!},
                initialPreviewAsData: true,
                initialPreviewFileType: 'image',
                initialPreviewShowDelete: true,
                deleteUrl: "{{ route('api.kegiatan.delete_media') }}",
                deleteExtraData: { _token: "{{ csrf_token() }}" },
                overwriteInitial: false,
                language: 'id',
                allowedFileExtensions: ['jpg', 'png', 'jpeg'],
                maxFileSize: 50000,
                maxFilesNum: 10,
                dropZoneEnabled: true,
                showRemove: true,
                showUpload: false,
                showCancel: true,
                showBrowse: true,
                browseOnZoneClick: true,
                theme: 'fa5',
                autoReplace: false,
                caption: function(files) {
                    // Your caption logic here
                },
                msgError: 'Error uploading file',
                layoutTemplates: {
                    // Your layout templates here
                },
                uploadUrl: "{{ route('api.kegiatan.upload_media') }}",
                uploadExtraData: {
                    _token: "{{ csrf_token() }}",
                    kegiatan_id: "{{ $kegiatan->id }}",
                    collection_name: 'media_pendukung'
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            function initializePenulisSelect2(selector) {
                $(selector).select2({
                    placeholder: '{{ __('global.pleaseSelect') . ' ' . __('cruds.kegiatan.penulis.nama') }}',
                    minimumInputLength: 2,
                    ajax: {
                        url: '{{ route('api.kegiatan.penulis') }}',
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        text: item.nama,
                                        id: item.id
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });
            }

            function initializeJabatanSelect2(selector) {
                $(selector).select2({
                    placeholder: '{{ __('global.pleaseSelect') . ' ' . __('cruds.kegiatan.penulis.jabatan') }}',
                    minimumInputLength: 0,
                    ajax: {
                        url: '{{ route('api.kegiatan.jabatan') }}',
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        text: item.nama,
                                        id: item.id
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });
            }

            // Initialize existing penulis and jabatan selects
            $('.penulis-select').each(function() {
                initializePenulisSelect2(this);
                var selectedId = $(this).data('selected');
                var selectedText = $(this).find('option').text();
                if (selectedId && selectedText) {
                    var option = new Option(selectedText, selectedId, true, true);
                    $(this).append(option).trigger('change');
                }
            });

            $('.jabatan-select').each(function() {
                initializeJabatanSelect2(this);
                var selectedId = $(this).data('selected');
                var selectedText = $(this).find('option').text();
                if (selectedId && selectedText) {
                    var option = new Option(selectedText, selectedId, true, true);
                    $(this).append(option).trigger('change');
                }
            });

            // Add new penulis row
            $('#addPenulis').click(function() {
                var newPenulisRow = `
                    <div class="row penulis-row col-12">
                        <div class="col-lg-5 form-group mb-0">
                            <label for="penulis">{{ __('cruds.kegiatan.penulis.nama') }}</label>
                            <div class="select2-orange">
                                <select class="form-control select2 penulis-select" name="penulis[]"></select>
                            </div>
                        </div>
                        <div class="col-lg-5 form-group d-flex align-items-end">
                            <div class="flex-grow-1">
                                <label for="jabatan">{{ __('cruds.kegiatan.penulis.jabatan') }}</label>
                                <div class="select2-orange">
                                    <select class="form-control select2 jabatan-select" name="jabatan[]"></select>
                                </div>
                            </div>
                            <div class="ml-2">
                                <button type="button" class="btn btn-danger remove-penulis-row">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>`;
                $('#list_penulis_edit').append(newPenulisRow);
                initializePenulisSelect2($('#list_penulis_edit').find('.penulis-select').last());
                initializeJabatanSelect2($('#list_penulis_edit').find('.jabatan-select').last());
            });

            // Remove penulis row
            $('#list_penulis_edit').on('click', '.remove-penulis-row', function() {
                $(this).closest('.penulis-row').remove();
            });
        });
    </script>
@endpush