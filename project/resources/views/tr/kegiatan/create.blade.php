@extends('layouts.app')

@section('subtitle', __('cruds.kegiatan.add'))
@section('content_header_title') <strong>{{ __('cruds.kegiatan.add') }}</strong>@endsection
@section('sub_breadcumb')<a href="{{ route('kegiatan.index') }}" title="{{ __('cruds.kegiatan.list') }}"> {{ __('cruds.kegiatan.list') }}</a>@endsection
@section('sub_sub_breadcumb') / <span title="Current Page {{ __('cruds.kegiatan.add') }}">{{ __('cruds.kegiatan.add') }}</span>@endsection

@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">{{ __('global.loading') }}...</h4>
@endsection

@section('content_body')
    <form id="createKegiatan" method="POST" class="needs-validation" data-toggle="validator" autocomplete="off" enctype="multipart/form-data">
        @csrf
        @method('POST')
        <div class="row">
            @include('tr.kegiatan.tabs')
        </div>
    </form>
@stop

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/krajee-fileinput/css/fileinput.min.css') }}">
    <style>
        .card-header.border-bottom-0.card-header.p-0.pt-1.navigasi {
            position: sticky;
            z-index: 1045;
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
    </style>
@endpush

@push('js')
    @section('plugins.Sweetalert2', true)
    @section('plugins.DatatablesNew', true)
    @section('plugins.Select2', true)
    @section('plugins.Toastr', true)
    @section('plugins.Validation', true)

    <script src="{{ asset('/vendor/inputmask/jquery.maskMoney.js') }}"></script>
    <script src="{{ asset('/vendor/inputmask/AutoNumeric.js') }}"></script>
    <script src="{{ asset('vendor/krajee-fileinput/js/plugins/buffer.min.js') }}"></script>
    <script src="{{ asset('vendor/krajee-fileinput/js/plugins/sortable.min.js') }}"></script>
    <script src="{{ asset('vendor/krajee-fileinput/js/plugins/piexif.min.js') }}"></script>
    <script src="{{ asset('vendor/krajee-fileinput/js/fileinput.min.js') }}"></script>
    <script src="{{ asset('vendor/krajee-fileinput/js/locales/id.js') }}"></script>




    @include('tr.kegiatan.js.create')
    @stack('basic_tab_js')

    {{-- script to validate forms and store kegiatan data via ajax --}}
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
                
                const jenisKegiatanValidation = validasiSingleSelect2('#jeniskegiatan_id', 'Please select a Jenis Kegiatan.');
                
                if (!jenisKegiatanValidation.isValid) {
                    isValid = false;
                    errorMessage += jenisKegiatanValidation.message + '<br>';
                }

                const sektorValidation = validasiMultipleSelect2('#sektor_id', 'Please select at least one sector.');
                if (!sektorValidation.isValid) {
                    isValid = false;
                    errorMessage += sektorValidation.message + '<br>';
                }

                const mitraValidation = validasiMultipleSelect2('#mitra_id', 'Please select at least one Mitra.');
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
                        title: 'Validation Error',
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
                    if (!latValue || isNaN(parseFloat(latValue)) || parseFloat(latValue) < -90 || parseFloat(
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
                    longLatErrorMessage += 'Please select Kecamatan and Kelurahan for all locations.<br>';
                }
                if (longLatError) {
                    longLatErrorMessage += 'Please fill in all latitude and longitude fields with valid values.<br>';
                }
                if (lokasiError) {
                    longLatErrorMessage += 'Please fill in the required Location field.<br>';
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
                        message = 'Please select a Penulis for each entry.';
                        applySelect2ErrorStyling($(this).find('.penulis-select'));
                    } else {
                        removeSelect2ErrorStyling($(this).find('.penulis-select'));
                    }

                    if (!jabatanValue) {
                        isValid = false;
                        message = 'Please select a Jabatan for each entry.';
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
                    message = 'Please select a Program and Activity.';
                } else if (programError) {
                    message = 'Please select a Program.';
                } else if (activityError) {
                    message = 'Please select an Activity.';
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
                let displayData = serializedData.map(item => `${item.name}: ${item.value}`).join('\n');
                console.log(`pre ${displayData}`);

                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah anda yakin ingin menyimpan data ini?',
                    // html: `<pre>${displayData}</pre>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{ __('global.yes') }}' + ', ' + '{{ __('global.save') }}' + ' ! '
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('api.kegiatan.store') }}", // Ensure this is the correct route
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            cache: false,
                            success: function(data) {
                                Swal.fire({
                                    title: '{{ __('global.success') }}',
                                    text: '{{ __('global.save_success') }}',
                                    icon: 'success'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Optionally redirect or reset form here
                                        // location.reload(); // Example to reload page
                                        // $('#createKegiatan')[0].reset(); // Reset form
                                    }
                                });
                            },
                            error: function(data) {
                                Swal.fire({
                                    title: '{{ __('global.error') }}',
                                    text: '{{ __('global.response.save_failed') }}',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });

        });
    </script>

@endpush