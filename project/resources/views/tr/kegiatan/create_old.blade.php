@extends('layouts.app')

@section('subtitle', __('cruds.kegiatan.add'))
@section('content_header_title')<strong>{{ __('cruds.kegiatan.add') }}</strong> @endsection
@section('sub_breadcumb')<a href="{{ route('kegiatan.index') }}" title="{{ __('cruds.kegiatan.list') }}">
    {{ __('cruds.kegiatan.list') }} </a> @endsection
@section('sub_sub_breadcumb') / <span
    title="Current Page {{ __('cruds.kegiatan.add') }}">{{ __('cruds.kegiatan.add') }}</span> @endsection

@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">{{ __('global.loading') }}...</h4>
@endsection

@section('content_body')
    <form id="createKegiatan" method="POST" class="needs-validation" data-toggle="validator" autocomplete="off"
        enctype="multipart/form-data">
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
            border-color: #dc3545 !important; /* Bootstrap's error color */
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important; /* Optional: Add a shadow */
        }

        /* Optional styling for the dropdown (if needed) */
        .select2-container--default.select2-container--open .select2-selection--single.is-invalid-select2 {
            border-color: #dc3545 !important;
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
<script>
    $(document).ready(function() {

        function validasiLongLat() {
            let kecamatanInputs = $('.kecamatan-select').map(function() {
                return $(this).val();
            }).get();
            let kelurahanInputs = $('.kelurahan-select').map(function() {
                return $(this).val();
            }).get();

            let lokasiInputs = document.querySelectorAll('input[name="lokasi[]"]');
            let latInputs = document.querySelectorAll('input[name="lat[]"]');
            let longInputs = document.querySelectorAll('input[name="long[]"]');

            let isValid = true;
            let kecamatanKelurahanError = false;
            let lokasiError = false;
            let longLatError = false;

            for (let i = 0; i < latInputs.length; i++) {
                let latValue = latInputs[i].value.trim();
                let longValue = longInputs[i].value.trim();
                let lokasiValue = lokasiInputs[i].value.trim();

                let kecamatanValue = kecamatanInputs[i];
                let kelurahanValue = kelurahanInputs[i];

                console.log("Data Select2 of array Kec & Kel :", kecamatanValue, kelurahanValue, lokasiValue);

                // Select2 elements validation
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
                }

                // Check if lokasi is  empty
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
                    longLatError = true; // Set the flag
                    latInputs[i].classList.add('is-invalid');
                } else {
                    latInputs[i].classList.remove('is-invalid');
                }

                // Check if longitude is empty or not a valid number between -180 and 180
                if (!longValue || isNaN(parseFloat(longValue)) || parseFloat(longValue) < -180 || parseFloat(
                        longValue) > 180) {
                    isValid = false;
                    longLatError = true; // Set the flag
                    longInputs[i].classList.add('is-invalid');
                } else {
                    longInputs[i].classList.remove('is-invalid');
                }
            }

            return {
                isValid: isValid,
                kecamatanKelurahanError: kecamatanKelurahanError,
                lokasiError: lokasiError,
                longLatError: longLatError
            };
        }

        function validasiSelectInput(){
            let jenisKegiatan = $('#jeniskegiatan_id').val();
        }

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

            return true; // Return true if both values are valid
        }

        $('#simpan_kegiatan').on('click', function(e) {
            e.preventDefault();
            const validationResult = validasiLongLat(); // Get the validation result

            if (!validationResult.isValid) {
                let errorMessage = '<ul class="list-unstyled">';

                if (validationResult.kecamatanKelurahanError) {
                    errorMessage += '<li>Please select Kecamatan or Kelurahan first.</li>';
                }
                if (validationResult.lokasiError) {
                    errorMessage += '<li>Please fill the activity location.</li>';
                }
                if (validationResult.longLatError) {
                    errorMessage += '<li>Please fill in all latitude and longitude fields with valid values.</li></ul>';
                }

                Swal.fire({
                    html: errorMessage,
                    icon: 'warning'
                });
                return;
            }


            if (!validasiProgramIDActivityID()) {
                Swal.fire({
                    text: 'Please select a Program or Activity first',
                    icon: 'warning'
                });
                return;
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
                confirmButtonText: '{{ __('global.yes') }}'+ ', '+ '{{ __('global.save') }}' +' ! '
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

                                title: 'Berhasil!',
                                text: 'Data berhasil disimpan.',
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

                                title: 'Gagal!',
                                text: 'Data gagal disimpan.',
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
