// Coordinate validation function
function validateCoordinate(coordinate, type) {
    // Remove any whitespace
    coordinate = coordinate.trim();
    if (!coordinate) {
        return { valid: false, message: 'Coordinate cannot be empty' };
    }
    const coord = parseFloat(coordinate);
    if (isNaN(coord)) {
        return { valid: false, message: 'Invalid coordinate format' };
    }
    if (type === 'latitude') {
        if (coord < -90 || coord > 90) {
            return { valid: false, message: 'Latitude must be between -90 and 90' };
        }
    } else if (type === 'longitude') {
        if (coord < -180 || coord > 180) {
            return { valid: false, message: 'Longitude must be between -180 and 180' };
        }
    }

    return {
        valid: true,
        value: coord.toFixed(6)
    };
}

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

    $(document).on('blur', '.lat-input, .lang-input', function () {
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
        placeholder: 'Pilih Kecamatan',
        allowClear: true,
        ajax: {
            url: "{{ route('api.kegiatan.kecamatan') }}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term,
                    kabupaten_id: $(`#kabupaten_id`).val(),
                    page: params.page || 1
                };
            },
            processResults: function (data, params) {
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

    // Initialize kelurahan select2
    $(`#kelurahan-${uniqueId}`).select2({
        placeholder: 'Pilih Desa',
        allowClear: true,
        ajax: {
            url: "{{ route('api.kegiatan.kelurahan') }}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term,
                    kecamatan_id: $(`#kecamatan-${uniqueId}`).val(),
                    page: params.page || 1
                };
            },
            processResults: function (data, params) {
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

    // Handle dependencies
    $(`#provinsi_id`).on('change', function () {
        $(`#kabupaten_id`).val(null).trigger('change');
        $(`#kecamatan-${uniqueId}`).val(null).trigger('change');
        $(`#kelurahan-${uniqueId}`).val(null).trigger('change');
    });

    $(`#kabupaten_id`).on('change', function () {
        $(`#kecamatan-${uniqueId}`).val(null).trigger('change');
        $(`#kelurahan-${uniqueId}`).val(null).trigger('change');
    });

    $(`#kecamatan-${uniqueId}`).on('change', function () {
        $(`#kelurahan-${uniqueId}`).val(null).trigger('change');
    });

    $(`#provinsi_id, #kabupaten_id, #kecamatan-${uniqueId}, #kelurahan-${uniqueId}, #lokasi-${uniqueId}, #lat-${uniqueId}, #long-${uniqueId}`).on('change', function () {
        // saveLocationToLocalStorage(uniqueId);
    });

    $(`.list-lokasi-kegiatan .lokasi-kegiatan[data-unique-id="${uniqueId}"]`).on('click', '.remove-staff-row', function () {
        // removeLocationFromLocalStorage(uniqueId);
        $(this).closest('.lokasi-kegiatan').remove();
    });
    return uniqueId
}

// function saveLocationToLocalStorage(uniqueId) {
//     let kegiatanLokasi = JSON.parse(localStorage.getItem('KegiatanLokasi')) || [];

//     const locationData = {
//         uniqueId: uniqueId,
//         provinsi_id: $(`#provinsi_id`).val(),
//         kabupaten_id: $(`#kabupaten_id`).val(),
//         kecamatan_id: $(`#kecamatan-${uniqueId}`).val(),
//         kelurahan_id: $(`#kelurahan-${uniqueId}`).val(),
//         lokasi: $(`#lokasi-${uniqueId}`).val(),
//         lat: $(`#lat-${uniqueId}`).val(),
//         long: $(`#long-${uniqueId}`).val(),

//         provinsi: $(`#provinsi-${uniqueId} option:selected`).text(),
//         kabupaten: $(`#kabupaten-${uniqueId} option:selected`).text(),
//         kecamatan: $(`#kecamatan-${uniqueId} option:selected`).text(),
//     };

//     kegiatanLokasi = kegiatanLokasi.filter(item => item.uniqueId !== uniqueId);
//     kegiatanLokasi.push(locationData);
//     localStorage.setItem('KegiatanLokasi', JSON.stringify(kegiatanLokasi));
// }

// function loadLocationFromLocalStorage(uniqueId) {
//     let kegiatanLokasi = JSON.parse(localStorage.getItem('KegiatanLokasi')) || [];
//     let locationData = kegiatanLokasi.find(item => item.uniqueId === uniqueId);
//     let storedData = JSON.parse(localStorage.getItem('kegiatanFormData')) || {};

//     if (locationData) {
//         preselectSelect2($(`#provinsi_id`), storedData.provinsi_id, "{{ route('api.kegiatan.provinsi') }}")
//             .then(() => {
//                 return preselectSelect2($(`#kabupaten_id`), storedData.kabupaten_id, "{{ route('api.kegiatan.kabupaten') }}", {
//                     provinsi_id: storedData.provinsi_id
//                 });
//             })
//             .then(() => {
//                 return preselectSelect2($(`#kecamatan-${uniqueId}`), locationData.kecamatan_id, "{{ route('api.kegiatan.kecamatan') }}", {
//                     kabupaten_id: storedData.kabupaten_id
//                 });
//             })
//             .then(() => {
//                 return preselectSelect2($(`#kelurahan-${uniqueId}`), locationData.kelurahan_id, "{{ route('api.kegiatan.kelurahan') }}", {
//                     kecamatan_id: locationData.kecamatan_id
//                 });
//             })
//             .then(() => {
//                 // After all select2s are populated, load the inputs
//                 $(`#lokasi-${uniqueId}`).val(locationData.lokasi);
//                 $(`#lat-${uniqueId}`).val(locationData.lat);
//                 $(`#long-${uniqueId}`).val(locationData.long);
//             });
//     }
//     return uniqueId;
// }

// function preselectSelect2(selectElement, id, url, additionalData = {}) {
//     return new Promise(resolve => {
//         if (!id) {
//             resolve();
//             return;
//         }

//         $.ajax({
//             url: url,
//             dataType: 'json',
//             data: { ...additionalData, id: [ id ] },
//             success: function (data) {
//                 if (data.results && data.results.length > 0) {
//                     const item = data.results[ 0 ];
//                     const option = new Option(item.text, item.id, true, true);
//                     selectElement.append(option).trigger('change');
//                     selectElement.trigger({
//                         type: 'select2:select',
//                         params: {
//                             data: item
//                         }
//                     });
//                 }
//                 resolve();
//             },
//             error: function () {
//                 resolve();
//             }
//         });
//     });
// }

// function removeLocationFromLocalStorage(uniqueId) {
//     let kegiatanLokasi = JSON.parse(localStorage.getItem('KegiatanLokasi')) || [];
//     kegiatanLokasi = kegiatanLokasi.filter(item => item.uniqueId !== uniqueId);
//     localStorage.setItem('KegiatanLokasi', JSON.stringify(kegiatanLokasi));
// }

// function clearKegiatanLokasiLocalStorage() {
//     localStorage.removeItem('KegiatanLokasi');
// }

$(document).ready(function () {
    let kegiatanLokasi = JSON.parse(localStorage.getItem('KegiatanLokasi')) || [];

    kegiatanLokasi.forEach(data => {
        let uniqueId = addNewLocationInputs(data.uniqueId);
        loadLocationFromLocalStorage(uniqueId);
    });

    // Initialize provinsi select2
    $(`#provinsi_id`).select2({
        placeholder: 'Pilih Provinsi',
        allowClear: true,
        ajax: {
            url: "{{ route('api.kegiatan.provinsi') }}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term,
                    page: params.page || 1
                };
            },
            processResults: function (data, params) {
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
        placeholder: 'Pilih Kabupaten',
        allowClear: true,
        ajax: {
            url: "{{ route('api.kegiatan.kabupaten') }}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                const provinsiId = $(`#provinsi_id`).val();
                return {
                    search: params.term,
                    provinsi_id: provinsiId,
                    page: params.page || 1
                };
            },
            processResults: function (data, params) {
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
});
