{{-- Basic Information --}}
<div class="form-group row">
    <div class="col-sm-12 col-md-12 col-lg-3 self-center order-1 order-md-1">
        <label for="kode_program" class="input-group col-form-label">{{ __('cruds.kegiatan.basic.program_kode') }}</label>
    <!-- id program -->
        <input type="hidden" name="program_id" id="program_id">
    <!-- kode program -->
        <input type="text" class="form-control" id="kode_program" placeholder="{{ __('cruds.kegiatan.basic.program_select_kode') }}" name="kode_program"
        data-toggle="modal" data-target="#ModalDaftarProgram">
    </div>
    <!-- nama program-->
    <div class="col-sm-12 col-md-12 col-lg-9 self-center order-2 order-md-2">
        <label for="nama_program" class="input-group col-form-label">
            {{ __('cruds.kegiatan.basic.program_nama') }}
        </label>
        <input type="text" class="form-control" id="nama_program" placeholder="{{ __('cruds.kegiatan.basic.program_nama') }}" name="nama_program">
    </div>
</div>
<div class="form-group row">
    <!-- kode kegiatan-->
    <div class="col-sm-12 col-md-12 col-lg-3 self-center order-1 order-md-1">
        <label for="kode_kegiatan" class="input-group col-form-label">
            {{ __('cruds.kegiatan.basic.kode') }}
        </label>
        <input type="hidden" class="form-control" id="programoutcomeoutputactivity_id" placeholder="{{ __('cruds.kegiatan.basic.kode') }}" name="programoutcomeoutputactivity_id">
        <input type="text" class="form-control" id="kode_kegiatan" placeholder="{{ __('cruds.kegiatan.basic.kode') }}" name="kode_kegiatan"
        data-toggle="modal" data-target="#ModalDaftarProgramActivity">
    </div>
    <!-- nama kegiatan-->
    <div class="col-sm-12 col-md-12 col-lg-9 self-center order-2 order-md-2">
        <label for="nama_kegiatan" class="input-group col-form-label">
            {{ __('cruds.kegiatan.basic.nama') }}
        </label>
        <input type="text" class="form-control" id="nama_kegiatan" placeholder="{{ __('cruds.kegiatan.basic.nama') }}" name="nama_kegiatan">
    </div>
</div>

<div class="form-group row">
    <!-- jenis kegiatan-->
    <!-- bentuk kegiatan-->
    <div class="col-sm-12 col-md-12 col-lg-3 self-center order-1 order-md-1">
        <label for="jenis_kegiatan" class="input-group col-form-label">
            <strong>{{ __('cruds.kegiatan.basic.jenis_kegiatan') }}</strong>
        </label>
        <div class="select2-purple">
            <select class="form-control select2" name="jenis_kegiatan" id="jenis_kegiatan" data-api-url="{{ route('api.kegiatan.jenis_kegiatan') }}">
                <!-- Options will be populated by select2 -->
            </select>
        </div>
    </div>
    <!-- sektor kegiatan-->
    <div class="col-sm-12 col-md-12 col-lg-3 self-center order-1 order-md-1">
        <label for="sektor_kegiatan" class="input-group col-form-label">
            <strong>{{ __('cruds.kegiatan.basic.sektor_kegiatan') }}</strong>
        </label>
        <div class="select2-purple">
            <select class="form-control select2" name="sektor_kegiatan" id="sektor_kegiatan" multiple data-api-url="{{ route('api.kegiatan.sektor_kegiatan') }}">
                <!-- Options will be populated by select2 -->
            </select>
        </div>
    </div>
    <!-- fase pelaporan-->
    <div class="col-sm-12 col-md-12 col-lg-2 self-center order-1 order-md-1">
        <label for="fasepelaporan" class="input-group col-form-label">
            <strong>{{ __('cruds.kegiatan.basic.fase_pelaporan') }} </strong>
            <i class="bi bi-question-circle" data-toggle="tooltip" title="{{ __('cruds.kegiatan.basic.tooltip.fase_pelaporan') }}"></i>
        </label>
        <div class="select2-purple">
            <select class="form-control select2-readonly" name="fasepelaporan" id="fasepelaporan">
                <option value="">{{ __('global.pleaseSelect') }}</option>
                @for ($i = 1; $i <= 99; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>
    </div>
    <!-- durasi kegiatan-->
    <!-- tgl mulai-->
    <div class="col-sm-6 col-md-6 col-lg-2 self-center order-1 order-md-1">
        <label for="tanggalmulai" class="input-group col-form-label">
            {{ __('cruds.kegiatan.basic.tanggalmulai') }}
        </label>
        <input type="date" class="form-control" id="tanggalmulai" name="tanggalmulai">
    </div>
    <!-- tgl selesai-->
    <div class="col-sm-6 col-md-6 col-lg-2 self-center order-2 order-md-2">
        <label for="tanggalselesai" class="input-group col-form-label">
            {{ __('cruds.kegiatan.basic.tanggalselesai') }}
        </label>
        <input type="date" class="form-control" id="tanggalselesai" name="tanggalselesai">
    </div>
</div>

<!-- nama mitra-->
<div class="form-group row">
    <div class="col-sm-12 col-md-9 col-lg-9 self-center order-2 order-md-2">
        <label for="mitra" class="input-group col-form-label">{{ __('cruds.kegiatan.basic.nama_mitra') }}</label>
        <div class="select2-purple">
            <select class="form-control select2" data-api-url="{{ route('api.kegiatan.mitra') }}" id="mitra" placeholder=" {{ __('global.pleaseSelect') .' '.__('cruds.kegiatan.basic.nama_mitra') }}" name="mitra" multiple>
            </select>
        </div>
    </div>
<!-- status kegiatan-->
    <div class="col-sm-12 col-md-3 col-lg-3 self-center order-1 order-md-1">
        <label for="status" class="input-group col-form-label">
            <strong>{{ __('cruds.status.title') }}</strong>
        </label>
        <div class="select2-purple">
            <select class="form-control" name="status" id="status" required placeholder=" {{ __('global.pleaseSelect') .' '.__('cruds.kegiatan.basic.status_kegiatan') }}">
                <optgroup label="{{ __('cruds.kegiatan.status') }}">
                    @foreach($statusOptions as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </optgroup>
            </select>
        </div>
    </div>
</div>


<div class="form-group row">
    <div class="col-sm-12 col-md-12 col-lg-12 self-center order-1 order-md-1">
        <label for="provinsi_id" class="input-group col-form-label">{{ __('cruds.provinsi.title') }}</label>
        <select name="provinsi_id" id="provinsi_id" class="form-control select2" data-api-url="{{ route('api.kegiatan.provinsi') }}" data-placeholder="{{ __('global.pleaseSelect') .' '.__('cruds.provinsi.title') }}">
        </select>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-12 self-center order-1 order-md-1">
        <label for="kabupaten_id" class="input-group col-form-label">{{ __('cruds.kabupaten.title') }}</label>
        <select name="kabupaten_id" id="kabupaten_id" class="form-control select2" data-api-url="{{ route('api.kegiatan.kabupaten') }}" data-placeholder="{{ __('global.pleaseSelect') .' '.__('cruds.kabupaten.title') }}">
        </select>
    </div>
</div>

<div class="card-info pt-2">
    <div class="card-header pl-1">
        <div class="col-sm-12 col-md-12 col-lg-4 self-center order-1 order-md-1">
            <button type="button" id="btn-lokasi-kegiatan" class="btn btn-warning">{{ __('cruds.kegiatan.basic.tambah_lokasi') }}</button>
        </div>
    </div>
    <div class="card-body pl-0 pt-1 pb-0 pr-1 mb-0">
        <div class="form-group row lokasi-kegiatan mb-0">
            <div class="col-sm-12 col-md-12 col-lg-2 self-center order-1 order-md-1">
                <label class="input-group col-form-label">
                    {{ __('cruds.kecamatan.title') }}
                    <i class="bi bi-geo-alt-fill" data-toggle="tooltip" title="{{ __('cruds.kegiatan.basic.tooltip.lokasi') }}"></i>
                </label>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-2 self-center order-1 order-md-1">
                <label class="input-group col-form-label">
                    {{ __('cruds.desa.title') }}
                    <i class="bi bi-geo-alt-fill" data-toggle="tooltip" title="{{ __('cruds.desa.title') }}"></i>
                </label>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-2 self-center order-1 order-md-1">
                <label class="input-group col-form-label">
                    {{ __('cruds.kegiatan.basic.lokasi') }}
                    <i class="bi bi-geo-alt-fill" data-toggle="tooltip" title="{{ __('cruds.kegiatan.basic.tooltip.lokasi') }}"></i>
                </label>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-2 self-center order-2 order-md-2">
                <label class="input-group col-form-label">
                    {{ __('cruds.kegiatan.basic.lat') }}
                    <i class="bi bi-pin-map-fill" data-toggle="tooltip" title="{{ __('cruds.kegiatan.basic.tooltip.long_lat') }}"></i>
                </label>
            </div>
            <div class="col-sm-10 col-md-10 col-lg-2 self-center order-3 order-md-3">
                <label class="input-group col-form-label">
                    {{ __('cruds.kegiatan.basic.long') }}
                    <i class="bi bi-geo" data-toggle="tooltip" title="{{ __('cruds.kegiatan.basic.tooltip.long_lat') }}"></i>
                </label>
            </div>
        </div>
    </div>
    <div class="list-lokasi-kegiatan"></div>
</div>

<div class="form-group row">
    <div class="col-sm-12 col-md-12 col-lg-12 self-center order-1 order-md-1">
        <label class="input-group col-form-label">
            {{ __('Get Coordinate') }}
            <i class="bi bi-map-fill"></i>
        </label>
        <div id="map" class="form-control col-form-label"></div>
    </div>
</div>


@include('tr.kegiatan.tabs.program')
@include('tr.kegiatan.tabs.program-act')

@push('next-button')
<div class="button" id="task_flyout">
    <button type="button" id="clearStorageButton" class="btn btn-warning float-left">Reset</button>
    <button type="button" id="next-button" class="btn btn-primary float-right">Next</button>
</div>
@endpush

@push('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map {
        height: 500px;
        width: 100%;
        /* margin-top: 20px; */
    }

    .select-container {
        /* margin-bottom: 15px; */
    }

    .select2-container {
        width: 100% !important;
        /* margin-bottom: 10px; */
    }
</style>
@endpush
@push('basic_tab_js')

<script>
    const ErrorHandler = {
        handleMapError: function(error) {
            console.error('Map Initialization Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Map Error',
                text: 'Unable to load map. Please try again later.',
                footer: `Error Details: ${error.message}`
            });
        },

        handleGeojsonError: function(error) {
            console.error('GeoJSON Loading Error:', error);
            Swal.fire({
                icon: 'warning',
                title: 'Geographic Data Error',
                text: 'Could not load geographic boundaries. Some features may be limited.',
                confirmButtonText: 'Retry',
                showCancelButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        },

        handleCoordinateError: function(message) {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Coordinates',
                text: message || 'The provided coordinates are invalid.'
            });
        }
    };
    // Place the validateCoordinate function in a global scope
    function validateCoordinate(value, type) {
        let isValid = false;
        let validatedValue = null;

        if (value === null || value === undefined || value === '') {
            return {
                valid: false,
                value: null
            };
        }

        // Validate Latitude
        if (type === 'latitude') {
            const lat = parseFloat(value);
            if (!isNaN(lat) && lat >= -90 && lat <= 90) {
                isValid = true;
                validatedValue = lat.toFixed(6); // Formatting to 6 decimal places
            }
        }
        // Validate Longitude
         else if (type === 'longitude') {
            const long = parseFloat(value);
            if (!isNaN(long) && long >= -180 && long <= 180) {
                isValid = true;
                validatedValue = long.toFixed(6); // Formatting to 6 decimal places
            }
        }

        return {
             valid: isValid,
             value: validatedValue
        };

    }

    $(document).ready(function() {

        $(`#provinsi_id`).select2({
            placeholder: '{{ __("cruds.kegiatan.basic.select_provinsi") }}',
            allowClear: true,
            ajax: {
                url: "{{ route('api.kegiatan.provinsi') }}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.results,
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                },
                cache: true
            }
        });

        // Initialize kabupaten select2
        $(`#kabupaten_id`).select2({
            placeholder: '{{ __("cruds.kegiatan.basic.select_kabupaten") }}',
            allowClear: true,
            ajax: {
                url: "{{ route('api.kegiatan.kabupaten') }}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    const provinsiId = $(`#provinsi_id`).val();
                    console.log("data : ", provinsiId);

                    return {
                        search: params.term,
                        provinsi_id: provinsiId,
                        page: params.page || 1
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.results,
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                },
                cache: true
            }
        });

        // Add event listener to provinsi_id to trigger kabupaten update
        $(`#provinsi_id`).on('change', function() {
            $(`#kabupaten_id`).val(null).trigger('change');
            // Clear existing location fields
            $('.list-lokasi-kegiatan').empty();
        });

        function addNewLocationInputs(uniqueId) {
            if (!uniqueId) {
                uniqueId = Date.now();
            }
            var newLocationField = `
            <div class="form-group row lokasi-kegiatan" data-unique-id="${uniqueId}">
                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-3">
                    <select name="kecamatan_id[]" class="form-control dynamic-select2 kecamatan-select" id="kecamatan-${uniqueId}" data-placeholder="Pilih Kecamatan">
                    </select>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-4">
                    <select name="kelurahan_id[]" class="form-control dynamic-select2 kelurahan-select" id="kelurahan-${uniqueId}" data-placeholder="Pilih Desa">
                    </select>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-5">
                    <input type="text" class="form-control lokasi-input" id="lokasi-${uniqueId}" name="lokasi[]" placeholder="{{ __('cruds.kegiatan.basic.lokasi') }}">
                </div>
                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-6">
                    <input type="text" class="form-control lat-input" id="lat-${uniqueId}" name="lat[]" placeholder="{{ __('cruds.kegiatan.basic.lat') }}">
                </div>
                <div class="col-sm-12 col-md-12 col-lg-2 self-center order-7 d-flex align-items-center">
                    <input type="text" class="form-control lang-input flex-grow-1" id="long-${uniqueId}" name="long[]" placeholder="{{ __('cruds.kegiatan.basic.long') }}">
                    <button type="button" class="btn btn-danger remove-staff-row btn-sm ml-1">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>`;
            $('.list-lokasi-kegiatan').append(newLocationField);

            $(document).on('blur', '.lat-input, .lang-input', function() {
                const $input = $(this);
                const value = $input.val();
                const type = $input.hasClass('lat-input') ? 'latitude' : 'longitude';

                const validationResult = validateCoordinate(value, type);

                if (validationResult.valid) {
                    $input.val(validationResult.value);
                    $input.removeClass('is-invalid').addClass('is-valid');
                } else {
                    $input.val('');
                    $input.removeClass('is-valid').addClass('is-invalid');
                }
            });

            // Initialize kecamatan select2
            $(`#kecamatan-${uniqueId}`).select2({
                placeholder: '{{ __("cruds.kegiatan.basic.select_kecamatan") }}',
                allowClear: true,
                ajax: {
                    url: "{{ route('api.kegiatan.kecamatan') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            kabupaten_id: $(`#kabupaten_id`).val(),
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: true,
                    error: function(jqXHR, textStatus, errorThrown) {
                        Toast.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'There was an error fetching Kecamatan data. Please try again later.',
                            position: 'center',
                            timer: 2000,
                            timerProgressBar: true
                        });
                        $('#kecamatan-' + uniqueId).focus();
                    }
                }
            });

            $(`#kelurahan-${uniqueId}`).select2({
                placeholder: '{{ __("cruds.kegiatan.basic.select_desa") }}',
                allowClear: true,
                ajax: {
                    url: "{{ route('api.kegiatan.kelurahan') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                    return {
                        search: params.term,
                        kecamatan_id: $(`#kecamatan-${uniqueId}`).val(),
                        page: params.page || 1
                    };
                    },
                    processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.results,
                        pagination: {
                        more: data.pagination.more
                        }
                    };
                    },
                    cache: true,
                    error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Error fetching Kelurahan data:", textStatus, errorThrown);
                        Toast.fire({
                            icon: 'error', // Use 'error' icon for errors
                            title: 'Please Select Kecamatan First!',
                            text: 'Kelurahan data depends on Kecamatan data. Please select a Kecamatan first.',
                            position: 'center',
                            timer: 2000, // Increased timer to 3 seconds for better visibility
                            timerProgressBar: true
                        });
                        $('#kecamatan-' + uniqueId).focus();
                    }
                }
            });

            // Handle dependencies
            $(`#provinsi_id`).on('change', function() {
                $(`#kabupaten_id`).val(null).trigger('change');
                $(`#kecamatan-${uniqueId}`).val(null).trigger('change');
                $(`#kelurahan-${uniqueId}`).val(null).trigger('change');
            });

            $(`#kabupaten_id`).on('change', function() {
                $(`#kecamatan-${uniqueId}`).val(null).trigger('change');
                $(`#kelurahan-${uniqueId}`).val(null).trigger('change');
            });

            $(`#kecamatan-${uniqueId}`).on('change', function() {
                $(`#kelurahan-${uniqueId}`).val(null).trigger('change');
            });

            $(`#provinsi_id, #kabupaten_id, #kecamatan-${uniqueId}, #kelurahan-${uniqueId}, #lokasi-${uniqueId}, #lat-${uniqueId}, #long-${uniqueId}`).on('change', function() {
                // saveLocationToLocalStorage(uniqueId);
            });

            $(`.list-lokasi-kegiatan .lokasi-kegiatan[data-unique-id="${uniqueId}"]`).on('click', '.remove-staff-row', function() {
                // removeLocationFromLocalStorage(uniqueId);
                $(this).closest('.lokasi-kegiatan').remove();
            });
            return uniqueId
        }

        $('#btn-lokasi-kegiatan').on('click', function() {
            let idProvinsi = $('#provinsi_id').val();
            let idKabupaten = $('#kabupaten_id').val();
            if (!idProvinsi) {
                Swal.fire({
                    icon: 'warning',
                    title: '',
                    text: 'Please select a province first.',
                    position: 'center',
                    timer: 1000,
                    timerProgressBar: true
                });
                $('#provinsi_id').focus();
                return false;
            }
            if (!idKabupaten) {
                Swal.fire({
                    icon: 'success',
                    title: '',
                    text: 'Please select a kabupaten after selecting a province.',
                    position: 'center',
                    timer: 1000,
                    timerProgressBar: true
                });
                $('#kabupaten_id').focus();
                return false;
            }

            addNewLocationInputs();
        });

        $(document).on('input', 'input[name="long[]"], input[name="lat[]"]', function() {
            const uniqueId = $(this).closest('.lokasi-kegiatan').data('unique-id');
            const container = $(this).closest('.lokasi-kegiatan');
            const long = parseFloat(container.find('input[name="long[]"]').val());
            const lat = parseFloat(container.find('input[name="lat[]"]').val());
            const index = $('.lokasi-kegiatan').index(container);

            if (!isNaN(lat) && !isNaN(long)) {
                saveLocationToLocalStorage(uniqueId);
            } else {
                ErrorHandler.handleCoordinateError('Invalid coordinate format');
            }
        });


        $('#list_program_out_activity tbody').on('click', '.select-activity', function(e) {
            e.preventDefault();
            var activity_Id = $(this).closest('tr').data('id');
            var activityKode = $(this).closest('tr').data('kode');
            var activityNama = $(this).closest('tr').data('nama');

            $('#programoutcomeoutputactivity_id').val(activity_Id).trigger('change');
            $('#kode_kegiatan').val(activityKode);
            $('#nama_kegiatan').val(activityNama).prop('disabled', true);
            $('#kode_program').prop('disabled', true);
            $('#nama_kegiatan').focus();
            setTimeout(function() {
                $('#ModalDaftarProgramActivity').modal('hide');
            }, 200);

        });

        // Add the remove location handler
        $(document).on('click', '.remove-staff-row', function() {
            var row = $(this).closest('.lokasi-kegiatan');
            var index = $('.lokasi-kegiatan').index(row);
            row.remove();
        });
    });
</script>


{{-- MAPS --}}

<script defer>
    $(document).ready(function () {
        // Initialize the map
        var map = L.map('map').setView([ -2.5489, 118.0149 ], 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Variables to store current GeoJSON layers
        var provinsiLayer = null;
        var kabupatenLayer = null;
        function convertPathToGeoJSON(pathData) {
            try {
                const convertedCoordinates = pathData.map(polygon => {
                    return polygon.map(ring => {
                        const convertedRing = ring.map(coord => {
                            let lng = parseFloat(coord[ 1 ]);
                            let lat = parseFloat(coord[ 0 ]);

                            if (isNaN(lng)) {
                                console.error("Invalid lng", coord[ 1 ]);
                                lng = 0;
                            }
                            if (isNaN(lat)) {
                                console.error("Invalid lat", coord[ 0 ]);
                                lat = 0;
                            }
                            return [ lng, lat ];
                        });
                        // ensure first and last point is same
                        if (convertedRing.length > 0) {
                            const firstCoord = convertedRing[ 0 ]
                            const lastCoord = convertedRing[ convertedRing.length - 1 ]
                            if (firstCoord[ 0 ] !== lastCoord[ 0 ] || firstCoord[ 1 ] !== lastCoord[ 1 ]) {
                                convertedRing.push(firstCoord)
                            }
                        }
                        return convertedRing;
                    })
                })
                return {
                    type: "Feature",
                    geometry: {
                        type: "MultiPolygon",
                        coordinates: convertedCoordinates,
                    }
                };
            } catch (e) {
                console.error("Error parsing or converting path to geojson:", e)
                return null;
            }
        }
        const provinsiPath = [ [
            [ [ -8.100002, 114.519701 ], [ -8.095996, 114.503341 ], [ -8.092949, 114.524537 ], [ -8.100002, 114.519701 ] ],
            [ [ -8.746994, 115.234823 ], [ -8.750259, 115.224802 ], [ -8.743982, 115.218828 ], [ -8.729553, 115.224463 ], [ -8.721596, 115.237886 ], [ -8.729054, 115.244832 ], [ -8.735112, 115.238011 ], [ -8.730306, 115.250894 ], [ -8.742084, 115.240437 ], [ -8.741479, 115.226853 ], [ -8.746994, 115.234823 ] ],
            [ [ -8.762691, 115.486108 ], [ -8.745983, 115.465669 ], [ -8.745084, 115.451275 ], [ -8.720702, 115.449327 ], [ -8.673648, 115.490757 ], [ -8.671937, 115.559462 ], [ -8.686277, 115.579262 ], [ -8.705585, 115.584703 ], [ -8.746245, 115.619169 ], [ -8.769948, 115.628524 ], [ -8.790472, 115.606795 ], [ -8.805681, 115.605966 ], [ -8.818415, 115.58463 ], [ -8.795751, 115.528274 ], [ -8.762691, 115.486108 ] ],
            [ [ -8.698163, 115.448016 ], [ -8.687595, 115.462891 ], [ -8.71207, 115.443046 ], [ -8.703833, 115.439776 ], [ -8.698163, 115.448016 ] ],
            [ [ -8.694394, 115.443789 ], [ -8.686238, 115.428416 ], [ -8.676185, 115.446912 ], [ -8.664763, 115.449104 ], [ -8.66811, 115.47115 ], [ -8.67866, 115.46959 ], [ -8.694394, 115.443789 ] ],
            [ [ -8.4374, 114.84339 ], [ -8.432518, 114.821486 ], [ -8.410978, 114.794165 ], [ -8.396411, 114.734743 ], [ -8.407292, 114.634207 ], [ -8.398087, 114.581884 ], [ -8.345168, 114.541866 ], [ -8.333988, 114.522272 ], [ -8.307139, 114.517942 ], [ -8.277295, 114.485064 ], [ -8.216348, 114.447341 ], [ -8.164278, 114.434385 ], [ -8.165519, 114.441367 ], [ -8.184574, 114.442029 ], [ -8.17115, 114.457049 ], [ -8.176159, 114.456827 ], [ -8.166795, 114.476608 ], [ -8.154677, 114.444781 ], [ -8.122099, 114.434948 ], [ -8.096681, 114.436513 ], [ -8.094115, 114.490689 ], [ -8.134108, 114.523137 ], [ -8.151317, 114.512537 ], [ -8.154872, 114.523551 ], [ -8.132246, 114.553798 ], [ -8.141847, 114.566749 ], [ -8.131447, 114.562063 ], [ -8.120277, 114.584266 ], [ -8.123687, 114.594585 ], [ -8.129896, 114.587177 ], [ -8.138359, 114.592946 ], [ -8.127895, 114.625922 ], [ -8.143891, 114.656939 ], [ -8.144508, 114.694024 ], [ -8.169044, 114.748952 ], [ -8.196381, 114.862509 ], [ -8.183942, 114.909479 ], [ -8.176741, 114.997748 ], [ -8.143521, 115.048766 ], [ -8.108531, 115.079064 ], [ -8.084338, 115.114443 ], [ -8.06475, 115.156895 ], [ -8.062932, 115.188292 ], [ -8.104605, 115.29419 ], [ -8.111225, 115.336079 ], [ -8.128468, 115.357843 ], [ -8.161353, 115.450883 ], [ -8.231051, 115.557343 ], [ -8.325249, 115.631101 ], [ -8.357971, 115.696296 ], [ -8.387833, 115.711127 ], [ -8.424704, 115.694045 ], [ -8.462182, 115.634183 ], [ -8.506045, 115.610893 ], [ -8.516406, 115.582244 ], [ -8.500749, 115.53836 ], [ -8.503693, 115.522299 ], [ -8.515505, 115.507608 ], [ -8.529241, 115.514782 ], [ -8.541294, 115.507888 ], [ -8.555626, 115.462549 ], [ -8.570559, 115.445048 ], [ -8.576564, 115.363778 ], [ -8.62168, 115.305636 ], [ -8.663952, 115.261574 ], [ -8.692128, 115.266987 ], [ -8.709461, 115.25958 ], [ -8.714212, 115.227979 ], [ -8.726266, 115.213876 ], [ -8.746504, 115.211341 ], [ -8.746052, 115.206238 ], [ -8.718674, 115.209689 ], [ -8.734547, 115.187108 ], [ -8.723789, 115.186571 ], [ -8.727001, 115.182349 ], [ -8.777463, 115.177926 ], [ -8.771853, 115.184711 ], [ -8.790192, 115.221144 ], [ -8.766461, 115.215688 ], [ -8.75306, 115.220699 ], [ -8.801818, 115.236928 ], [ -8.834445, 115.213667 ], [ -8.848983, 115.167963 ], [ -8.845527, 115.112635 ], [ -8.838145, 115.088811 ], [ -8.828206, 115.084838 ], [ -8.8139, 115.090478 ], [ -8.804782, 115.114122 ], [ -8.791705, 115.12346 ], [ -8.777464, 115.165854 ], [ -8.760548, 115.1689 ], [ -8.750457, 115.152459 ], [ -8.71444, 115.167508 ], [ -8.674591, 115.145853 ], [ -8.643644, 115.104573 ], [ -8.581383, 115.059414 ], [ -8.477357, 114.934927 ], [ -8.4374, 114.84339 ] ]
        ] ];
        const kabupatenPath = [ [ [ [ -8.735726739697185, 115.21395685772295 ], [ -8.7467070382991, 115.21176702832575 ], [ -8.738045968046908, 115.20786987469502 ], [ -8.741422978987217, 115.20053993417069 ], [ -8.72902617711631, 115.21723099701295 ], [ -8.735726739697185, 115.21395685772295 ] ] ], [ [ [ -8.72337403068693, 115.23389600199889 ], [ -8.724296026440452, 115.23259296799117 ], [ -8.72376096309793, 115.23159703537497 ], [ -8.72312803912763, 115.23371402778025 ], [ -8.72337403068693, 115.23389600199889 ] ] ], [ [ [ -8.721402429878182, 115.23951372507841 ], [ -8.729052396850605, 115.24480802586977 ], [ -8.7369997966033, 115.23642042596191 ], [ -8.728305246389905, 115.24755630278116 ], [ -8.731082076776335, 115.25065436110997 ], [ -8.743866920821723, 115.23699863517895 ], [ -8.734930012310187, 115.22940397741175 ], [ -8.746779858203872, 115.23473009621702 ], [ -8.752062260964504, 115.2298522013181 ], [ -8.748183740387265, 115.21837130229382 ], [ -8.731689910560885, 115.22191015522958 ], [ -8.721402429878182, 115.23951372507841 ] ] ], [ [ [ -8.59153955840382, 115.2350164448532 ], [ -8.63217007678763, 115.25557466803666 ], [ -8.650443024625858, 115.27488099266374 ], [ -8.665629045044358, 115.26145498955624 ], [ -8.704308967185064, 115.26459402279598 ], [ -8.739038056061403, 115.18876381289968 ], [ -8.70696608341797, 115.18756198859498 ], [ -8.682325727750122, 115.17250843741455 ], [ -8.674896548836646, 115.17975179930616 ], [ -8.661854480699631, 115.1734298683938 ], [ -8.660080290073306, 115.1805350359632 ], [ -8.61619746074414, 115.18019297612497 ], [ -8.611896874840834, 115.19739969673864 ], [ -8.60502057124231, 115.19675435582896 ], [ -8.596174214568268, 115.21172426736112 ], [ -8.59153955840382, 115.2350164448532 ] ] ] ];

        // Province selection change handler
        $('#provinsi_id').on('change', function () {
            var provinsiId = $(this).val();

            // Clear previous layers
            if (provinsiLayer) {
                map.removeLayer(provinsiLayer);
            }
            if (kabupatenLayer) {
                map.removeLayer(kabupatenLayer);
            }

            // Clear kabupaten select
            $('#kabupaten_id').val(null).trigger('change');
            $('#kabupaten_id').prop('disabled', !provinsiId);

            if (provinsiId) {
                const geojson = convertPathToGeoJSON(provinsiPath);
                if (geojson) {
                    // Add province layer
                    provinsiLayer = L.geoJSON(geojson, {
                        style: {
                            color: '#2563eb',
                            weight: 2,
                            fillOpacity: 0.2
                        }
                    }).addTo(map);

                    // Fit map to province bounds
                    map.fitBounds(provinsiLayer.getBounds());
                }
            }
        });

        // Kabupaten selection change handler
        $('#kabupaten_id').on('change', function () {
            var kabupatenId = $(this).val();

            if (kabupatenLayer) {
                map.removeLayer(kabupatenLayer);
            }

            if (kabupatenId) {
                const geojson = convertPathToGeoJSON(kabupatenPath);
                if (geojson) {
                    // Add kabupaten layer
                    kabupatenLayer = L.geoJSON(geojson, {
                        style: {
                            color: '#dc2626',
                            weight: 2,
                            fillOpacity: 0.4
                        }
                    }).addTo(map);

                    // Fit map to kabupaten bounds
                    map.fitBounds(kabupatenLayer.getBounds());
                }
            }
        });
    });
</script>
@endpush
