function addNewLocationInputs(uniqueId) {
    if (!uniqueId) {
         uniqueId = Date.now();
    }

    var newLocationField = `
        <div class="form-group row lokasi-kegiatan" data-unique-id="${uniqueId}">
            <div class="col-sm-12 col-md-12 col-lg-1 self-center order-1">
                <select name="provinsi_id[]" class="form-control dynamic-select2 provinsi-select"
                    id="provinsi-${uniqueId}"
                    data-placeholder="Pilih Provinsi">
                </select>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-1 self-center order-2">
                <select name="kabupaten_id[]" class="form-control dynamic-select2 kabupaten-select"
                    id="kabupaten-${uniqueId}"
                    data-placeholder="Pilih Kabupaten">
                </select>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-2 self-center order-3">
                <select name="kecamatan_id[]" class="form-control dynamic-select2 kecamatan-select"
                    id="kecamatan-${uniqueId}"
                    data-placeholder="Pilih Kecamatan">
                </select>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-2 self-center order-4">
                <select name="kelurahan_id[]" class="form-control dynamic-select2 kelurahan-select"
                    id="kelurahan-${uniqueId}"
                    data-placeholder="Pilih Desa">
                </select>
            </div>
             <div class="col-sm-12 col-md-12 col-lg-2 self-center order-5">
                <input type="text" class="form-control lokasi-input" id="lokasi-${uniqueId}" name="lokasi[]" placeholder="{{ __('cruds.kegiatan.basic.lokasi') }}">
             </div>
            <div class="col-sm-12 col-md-12 col-lg-2 self-center order-6">
                <input type="text" class="form-control lat-input" id="lat-${uniqueId}" name="lat[]" placeholder="{{ __('cruds.kegiatan.basic.lat') }}">
            </div>
            <div class="col-sm-12 col-md-12 col-lg-2 self-center order-7 d-flex align-items-center">
                <input type="text" class="form-control lang-input flex-grow-1"  id="long-${uniqueId}" name="long[]" placeholder="{{ __('cruds.kegiatan.basic.long') }}">
                <button type="button" class="btn btn-danger remove-staff-row btn-sm ml-1">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>`;

    $('.list-lokasi-kegiatan').append(newLocationField);


    // Initialize provinsi select2
    $(`#provinsi-${uniqueId}`).select2({
        placeholder: 'Pilih Provinsi',
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
    $(`#kabupaten-${uniqueId}`).select2({
        placeholder: 'Pilih Kabupaten',
        allowClear: true,
        ajax: {
            url: "{{ route('api.kegiatan.kabupaten') }}",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    search: params.term,
                    provinsi_id: $(`#provinsi-${uniqueId}`).val(), // Get the province ID
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


    // Initialize kecamatan select2
    $(`#kecamatan-${uniqueId}`).select2({
        placeholder: 'Pilih Kecamatan',
        allowClear: true,
        ajax: {
            url:  "{{ route('api.kegiatan.kecamatan') }}",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    search: params.term,
                    kabupaten_id: $(`#kabupaten-${uniqueId}`).val(),
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


        // Initialize kelurahan select2
    $(`#kelurahan-${uniqueId}`).select2({
        placeholder: 'Pilih Desa',
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
            cache: true
        }
    });


        // Handle dependencies
    $(`#provinsi-${uniqueId}`).on('change', function() {
        $(`#kabupaten-${uniqueId}`).val(null).trigger('change');
        $(`#kecamatan-${uniqueId}`).val(null).trigger('change');
        $(`#kelurahan-${uniqueId}`).val(null).trigger('change');
    });

    $(`#kabupaten-${uniqueId}`).on('change', function() {
        $(`#kecamatan-${uniqueId}`).val(null).trigger('change');
        $(`#kelurahan-${uniqueId}`).val(null).trigger('change');
    });


    $(`#kecamatan-${uniqueId}`).on('change', function() {
        $(`#kelurahan-${uniqueId}`).val(null).trigger('change');
    });


    // Add event listeners to save data to localStorage
    $(`#provinsi-${uniqueId}, #kabupaten-${uniqueId}, #kecamatan-${uniqueId}, #kelurahan-${uniqueId}, #lokasi-${uniqueId}, #lat-${uniqueId}, #long-${uniqueId}`)
    .on('change', function() {
        saveLocationToLocalStorage(uniqueId);
    });

    $(`.list-lokasi-kegiatan .lokasi-kegiatan[data-unique-id="${uniqueId}"]`).on('click', '.remove-staff-row', function() {
        // Remove from localStorage when row is removed
        removeLocationFromLocalStorage(uniqueId);
        $(this).closest('.lokasi-kegiatan').remove();
    });
    return uniqueId
}

function saveLocationToLocalStorage(uniqueId) {
    const locationRow = $(`.lokasi-kegiatan[data-unique-id="${uniqueId}"]`);

    // Get all location data
    var locationData = {
        uniqueId: uniqueId,
        provinsi_id: $(`#provinsi-${uniqueId}`).val(),
        kabupaten_id: $(`#kabupaten-${uniqueId}`).val(),
        kecamatan_id: $(`#kecamatan-${uniqueId}`).val(),
        kelurahan_id: $(`#kelurahan-${uniqueId}`).val(),
        lokasi: $(`#lokasi-${uniqueId}`).val(),
        lat: $(`#lat-${uniqueId}`).val(),
        long: $(`#long-${uniqueId}`).val(),
    };
    let kegiatanLokasi = JSON.parse(localStorage.getItem('KegiatanLokasi')) || [];

    let existingIndex = kegiatanLokasi.findIndex(item => item.uniqueId === uniqueId);
    if (existingIndex !== -1) {

    kegiatanLokasi[existingIndex] = locationData;
    } else {
        // Add new entry
        kegiatanLokasi.push(locationData);
    }
    // Save back to localStorage
    localStorage.setItem('KegiatanLokasi', JSON.stringify(kegiatanLokasi));

}

function loadLocationFromLocalStorage(uniqueId) {
    let kegiatanLokasi = JSON.parse(localStorage.getItem('KegiatanLokasi')) || [];
    let locationData = kegiatanLokasi.find(item => item.uniqueId === uniqueId);

  if (locationData) {
    preselectSelect2($(`#provinsi-${uniqueId}`), locationData.provinsi_id, "{{ route('api.kegiatan.provinsi') }}")
      .then(() => {
        return preselectSelect2($(`#kabupaten-${uniqueId}`), locationData.kabupaten_id, "{{ route('api.kegiatan.kabupaten') }}", { provinsi_id: locationData.provinsi_id });
      })
      .then(() => {
          return preselectSelect2($(`#kecamatan-${uniqueId}`), locationData.kecamatan_id, "{{ route('api.kegiatan.kecamatan') }}", { kabupaten_id: locationData.kabupaten_id });
      })
      .then(() => {
        return preselectSelect2($(`#kelurahan-${uniqueId}`), locationData.kelurahan_id, "{{ route('api.kegiatan.kelurahan') }}", { kecamatan_id: locationData.kecamatan_id });
       })
      .then(() => {
        // After all select2s are populated, load the inputs
        $(`#lokasi-${uniqueId}`).val(locationData.lokasi);
        $(`#lat-${uniqueId}`).val(locationData.lat);
        $(`#long-${uniqueId}`).val(locationData.long);
      });
  }
    return uniqueId;
}


function preselectSelect2(selectElement, id, url, additionalData = {}) {
    return new Promise(resolve => {
        if (!id) {
            resolve();
            return;
        }

        $.ajax({
            url: url,
            dataType: 'json',
            data: { ...additionalData, id: [id] },
            success: function(data) {
                if (data.results && data.results.length > 0) {
                    const item = data.results[0];
                    const option = new Option(item.text, item.id, true, true);
                    selectElement.append(option).trigger('change');
                    selectElement.trigger({
                       type: 'select2:select',
                       params: {
                         data: item
                       }
                    });
                }
                resolve();
            },
            error: function() {
                resolve(); // Resolve even on error
            }
        });
    });
}

function removeLocationFromLocalStorage(uniqueId) {
    let kegiatanLokasi = JSON.parse(localStorage.getItem('KegiatanLokasi')) || [];
    kegiatanLokasi = kegiatanLokasi.filter(item => item.uniqueId !== uniqueId);
    localStorage.setItem('KegiatanLokasi', JSON.stringify(kegiatanLokasi));
}

function clearKegiatanLokasiLocalStorage() {
    localStorage.removeItem('KegiatanLokasi');
}

$(document).ready(function() {
    let kegiatanLokasi = JSON.parse(localStorage.getItem('KegiatanLokasi')) || [];

    kegiatanLokasi.forEach(data => {
        let uniqueId = addNewLocationInputs(data.uniqueId);
        loadLocationFromLocalStorage(uniqueId);
    });
});
