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
    // Variables to store current GeoJSON layers
    var provinsiLayer = null;
    var kabupatenLayer = null;
    var map;


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

     // Declare updateMap function outside of $(document).ready()
    function fetchAndDisplayGeoJSON(id, type, layerVar, color, parentLayer) {
        if (id) {
            fetch(`/api/geojson/${type}/${id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const geojson = convertPathToGeoJSON([data.path]);
                    if (geojson) {
                        layerVar = L.geoJSON(geojson, {
                            style: { color: color, weight: 2, fillOpacity: 0.3 }
                        }).addTo(map);

                        if (parentLayer) {
                            map.fitBounds(parentLayer.getBounds());
                        } else {
                            map.fitBounds(layerVar.getBounds());
                        }
                    } else {
                        console.error(`geoJson ${type} is null or invalid`);
                    }
                })
                .catch(error => {
                    if (error instanceof SyntaxError) {
                        console.error("JSON Parsing Error:", error);
                    } else if (error.message.startsWith("HTTP error!")) {
                        console.error("Network Error:", error);
                    } else {
                        console.error("GeoJSON Loading Error:", error);
                    }
                    ErrorHandler.handleGeojsonError(error);
                });
        }
    }


    function updateMap() {
        let provinsiId = $('#provinsi_id').val();
        let kabupatenId = $('#kabupaten_id').val();

        // Clear layers if selections are cleared
        if (!provinsiId && provinsiLayer) { map.removeLayer(provinsiLayer); provinsiLayer = null; }
        if (!kabupatenId && kabupatenLayer) { map.removeLayer(kabupatenLayer); kabupatenLayer = null; }


        fetchAndDisplayGeoJSON(provinsiId, 'provinsi', provinsiLayer, '#2563eb');
        fetchAndDisplayGeoJSON(kabupatenId, 'kabupaten', kabupatenLayer, '#dc2626', provinsiLayer);

    }

    $(document).ready(function() {
          // Initialize the map
        map = L.map('map').setView([ -2.5489, 118.0149 ], 5);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'}).addTo(map);

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
        }).on('select2:open', function (e) {
             // this make the select2 dropdown appear above other element that might overlap it
             $('.select2-container').css('z-index', 1051);
        }).on('select2:close', function (e) {
           $('.select2-container').css('z-index', 999);
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
            }).on('select2:open', function (e) {
                // this make the select2 dropdown appear above other element that might overlap it
                $('.select2-container').css('z-index', 1051);
            }).on('select2:close', function (e) {
            $('.select2-container').css('z-index', 999);
            });

    // Add event listener to provinsi_id to trigger kabupaten update
        $(`#provinsi_id`).on('change', function() {
            $(`#kabupaten_id`).val(null).trigger('change');
            // Clear existing location fields
            $('.list-lokasi-kegiatan').empty();
            updateMap();
        });

        $(`#kabupaten_id`).on('change', function() {
            updateMap();
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
            }).on('select2:open', function (e) {
             // this make the select2 dropdown appear above other element that might overlap it
             $('.select2-container').css('z-index', 1051);
            }).on('select2:close', function (e) {
                $('.select2-container').css('z-index', 999);
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
            }).on('select2:open', function (e) {
                 // this make the select2 dropdown appear above other element that might overlap it
                 $('.select2-container').css('z-index', 1051);
            }).on('select2:close', function (e) {
               $('.select2-container').css('z-index', 999);
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
        // Call updateMap() after select2 is initialized
        updateMap();
    });
    function convertPathToGeoJSON(pathData) {
        try {
            if (!Array.isArray(pathData)) {
                console.error("pathData is not an array:", pathData);
                return null;
            }

            const convertedCoordinates = pathData.map(polygon => {
                if (!Array.isArray(polygon)) {
                    console.error("Polygon is not an array:", polygon);
                    return null;
                }
                return polygon.map(ring => {
                    if (!Array.isArray(ring)) {
                        console.error("Ring is not an array:", ring);
                        return null;
                    }
                    const convertedRing = ring.map(coord => {
                        if (!Array.isArray(coord)) {
                             console.error("Coordinate is not an array:", coord);
                             return null;
                        }

                        if (coord.length < 2) {
                           console.error("Coordinate is missing values:", coord);
                           return null;
                        }

                        let lng = parseFloat(coord[1]);
                        let lat = parseFloat(coord[0]);

                        if (isNaN(lng)) {
                            console.error("Invalid lng", coord[1]);
                            lng = 0;
                        }
                        if (isNaN(lat)) {
                            console.error("Invalid lat", coord[0]);
                            lat = 0;
                        }

                        return [lng, lat];
                    }).filter(coord => coord !== null); // Remove invalid coords

                    // ensure first and last point is same
                    if (convertedRing.length > 0) {
                        const firstCoord = convertedRing[0];
                        const lastCoord = convertedRing[convertedRing.length - 1];
                        if (firstCoord[0] !== lastCoord[0] || firstCoord[1] !== lastCoord[1]) {
                            convertedRing.push(firstCoord);
                        }
                    }
                    return convertedRing;
                }).filter(ring => ring !== null && ring.length > 0); // Remove invalid rings
            }).filter(polygon => polygon !== null && polygon.length > 0);  // Remove invalid polygons

            if(convertedCoordinates.length === 0){
                 console.error("No valid convertedCoordinates:", convertedCoordinates)
                 return null;
            }
            return {
                type: "Feature",
                geometry: {
                    type: "MultiPolygon",
                    coordinates: convertedCoordinates,
                }
            };
        } catch (e) {
            console.error("Error parsing or converting path to geojson:", e);
            return null;
        }
    }
</script>
@endpush
