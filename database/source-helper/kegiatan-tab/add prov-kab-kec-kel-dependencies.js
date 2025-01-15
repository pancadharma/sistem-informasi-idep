$('#btn-lokasi-kegiatan').on('click', function() {
    addNewLocationInputs();
});

function addNewLocationInputs() {
    var uniqueId = Date.now(); // Generate unique ID for each set of fields
    var newLocationField = `
        <div class="form-group row lokasi-kegiatan">
            <div class="col-sm-12 col-md-2 col-lg-2 self-center order-1">
                <select name="provinsi_id[]" class="form-control select2-api provinsi-select" 
                    data-api-url="${$('#provinsi_id').data('api-url')}"
                    data-placeholder="${$('#provinsi_id').data('placeholder')}"
                    data-target="kabupaten-${uniqueId}">
                </select>
            </div>
            <div class="col-sm-12 col-md-2 col-lg-2 self-center order-2">
                <select name="kabupaten_id[]" class="form-control select2-api kabupaten-select" 
                    id="kabupaten-${uniqueId}"
                    data-api-url="${$('#kabupaten_id').data('api-url')}"
                    data-placeholder="${$('#kabupaten_id').data('placeholder')}"
                    data-target="kecamatan-${uniqueId}">
                </select>
            </div>
            <div class="col-sm-12 col-md-2 col-lg-2 self-center order-3">
                <select name="kecamatan_id[]" class="form-control select2-api kecamatan-select"
                    id="kecamatan-${uniqueId}"
                    data-api-url="${$('#kecamatan_id').data('api-url')}"
                    data-placeholder="${$('#kecamatan_id').data('placeholder')}"
                    data-target="kelurahan-${uniqueId}">
                </select>
            </div>
            <div class="col-sm-12 col-md-2 col-lg-2 self-center order-4">
                <select name="kelurahan_id[]" class="form-control select2-api kelurahan-select"
                    id="kelurahan-${uniqueId}"
                    data-api-url="${$('#kelurahan_id').data('api-url')}"
                    data-placeholder="${$('#kelurahan_id').data('placeholder')}">
                </select>
            </div>
            <div class="col-sm-12 col-md-2 col-lg-2 self-center order-5">
                <input type="text" class="form-control" name="lat[]" placeholder="{{ __('cruds.kegiatan.basic.lat') }}">
            </div>
            <div class="col-sm-12 col-md-2 col-lg-2 self-center order-6 d-flex align-items-center">
                <input type="text" class="form-control flex-grow-1" name="long[]" placeholder="{{ __('cruds.kegiatan.basic.long') }}">
                <button type="button" class="btn btn-danger remove-staff-row btn-sm ml-1">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>`;
    
    $('.list-lokasi-kegiatan').append(newLocationField);
    
    // Initialize select2 for the new fields
    $('.list-lokasi-kegiatan .select2-api').select2({
        placeholder: function() {
            return $(this).data('placeholder');
        },
        allowClear: true
    });

    // Handle dependent dropdowns
    $('.list-lokasi-kegiatan').on('change', '.provinsi-select', function() {
        var targetId = $(this).data('target');
        var selectedValue = $(this).val();
        var $kabupatenSelect = $('#' + targetId);
        
        if (selectedValue) {
            $.get($kabupatenSelect.data('api-url'), { provinsi_id: selectedValue })
                .done(function(data) {
                    $kabupatenSelect.empty().trigger('change');
                    $kabupatenSelect.append(new Option('', '', false, false));
                    data.forEach(function(item) {
                        $kabupatenSelect.append(new Option(item.text, item.id, false, false));
                    });
                });
        } else {
            $kabupatenSelect.empty().trigger('change');
        }
    });

    // Similar handlers for kabupaten to kecamatan and kecamatan to kelurahan
    $('.list-lokasi-kegiatan').on('change', '.kabupaten-select', function() {
        var targetId = $(this).data('target');
        var selectedValue = $(this).val();
        var $kecamatanSelect = $('#' + targetId);
        
        if (selectedValue) {
            $.get($kecamatanSelect.data('api-url'), { kabupaten_id: selectedValue })
                .done(function(data) {
                    $kecamatanSelect.empty().trigger('change');
                    $kecamatanSelect.append(new Option('', '', false, false));
                    data.forEach(function(item) {
                        $kecamatanSelect.append(new Option(item.text, item.id, false, false));
                    });
                });
        } else {
            $kecamatanSelect.empty().trigger('change');
        }
    });

    $('.list-lokasi-kegiatan').on('change', '.kecamatan-select', function() {
        var targetId = $(this).data('target');
        var selectedValue = $(this).val();
        var $kelurahanSelect = $('#' + targetId);
        
        if (selectedValue) {
            $.get($kelurahanSelect.data('api-url'), { kecamatan_id: selectedValue })
                .done(function(data) {
                    $kelurahanSelect.empty().trigger('change');
                    $kelurahanSelect.append(new Option('', '', false, false));
                    data.forEach(function(item) {
                        $kelurahanSelect.append(new Option(item.text, item.id, false, false));
                    });
                });
        } else {
            $kelurahanSelect.empty().trigger('change');
        }
    });
}

// Remove button handler
$(document).on('click', '.remove-staff-row', function() {
    $(this).closest('.lokasi-kegiatan').remove();
});






// by gooogel
// by gooogel
// by gooogel
// by gooogel
// by gooogel

$('#btn-lokasi-kegiatan').on('click', function() {
    addNewLocationInputs();
});


function addNewLocationInputs() {
    var newLocationField = `
        <div class="form-group row lokasi-kegiatan">
            <div class="col-sm-12 col-md-2 col-lg-2 self-center order-1 order-md-1">
                <input type="text" class="form-control" name="lokasi[]" placeholder="{{ __('cruds.kegiatan.basic.lokasi_kegiatan') }}">
            </div>
            <div class="col-sm-12 col-md-2 col-lg-2 self-center order-2 order-md-2">
                <input type="text" class="form-control" name="lat[]" placeholder="{{ __('cruds.kegiatan.basic.lat') }}">
            </div>
            <div class="col-sm-12 col-md-2 col-lg-2 self-center order-3 order-md-3 d-flex align-items-center">
                <input type="text" class="form-control flex-grow-1" name="long[]" placeholder="{{ __('cruds.kegiatan.basic.long') }}">
                <button type="button" class="btn btn-danger remove-staff-row btn-sm ml-1">
                    <i class="bi bi-trash"></i>
                </button>
            </div>

             <!-- Dropdown Container -->
            <div class="col-sm-12 col-md-6 col-lg-6 mt-2 mt-md-0 order-4 order-md-4">
              <div class="form-group row">
                    <div class="col-md-6">
                        <select name="provinsi_id[]" class="form-control select2-api provinsi-select" data-api-url="{{ route('api.kegiatan.provinsi') }}" data-placeholder="{{ __('global.pleaseSelect') .' '.__('cruds.kegiatan.basic.provinsi') }}"></select>
                    </div>
                     <div class="col-md-6">
                        <select name="kabupaten_id[]" class="form-control select2-api kabupaten-select" data-api-url="{{ route('api.kegiatan.kabupaten') }}" data-placeholder="{{ __('global.pleaseSelect') .' '.__('cruds.kegiatan.basic.kabupaten') }}"></select>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6">
                        <select name="kecamatan_id[]" class="form-control select2-api kecamatan-select" data-api-url="{{ route('api.kegiatan.kecamatan') }}" data-placeholder="{{ __('global.pleaseSelect') .' '.__('cruds.kegiatan.basic.kecamatan') }}"></select>
                     </div>
                     <div class="col-md-6">
                        <select name="kelurahan_id[]" class="form-control select2-api kelurahan-select" data-api-url="{{ route('api.kegiatan.kelurahan') }}" data-placeholder="{{ __('global.pleaseSelect') .' '.__('cruds.kegiatan.basic.kelurahan') }}"></select>
                    </div>
                </div>
              </div>

        </div>`;

    $('.list-lokasi-kegiatan').append(newLocationField);


    // Initialize select2 for newly added dropdowns after they are added to the DOM
    $('.list-lokasi-kegiatan').find('.lokasi-kegiatan:last .provinsi-select').select2({
        placeholder: '{{ __('global.pleaseSelect') .' '.__('cruds.kegiatan.basic.provinsi') }}',
        ajax: {
        url: "{{ route('api.kegiatan.provinsi') }}",
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
            return {
            results: $.map(data, function(item) {
                return {
                text: item.name,
                id: item.id
                }
            })
        };
        },
        cache: true
        }
    });

    $('.list-lokasi-kegiatan').find('.lokasi-kegiatan:last .kabupaten-select').select2({
        placeholder: '{{ __('global.pleaseSelect') .' '.__('cruds.kegiatan.basic.kabupaten') }}',
        ajax: {
        url: "{{ route('api.kegiatan.kabupaten') }}",
        dataType: 'json',
        delay: 250,
         data: function (params) {
          var query = {
            provinsi_id: $(this).closest('.lokasi-kegiatan').find('.provinsi-select').val(),
            term: params.term,
          }
          return query;
        },
        processResults: function (data) {
            return {
            results: $.map(data, function(item) {
                return {
                text: item.name,
                id: item.id
                }
            })
        };
        },
        cache: true
        }
    });
     $('.list-lokasi-kegiatan').find('.lokasi-kegiatan:last .kecamatan-select').select2({
         placeholder: '{{ __('global.pleaseSelect') .' '.__('cruds.kegiatan.basic.kecamatan') }}',
         ajax: {
         url: "{{ route('api.kegiatan.kecamatan') }}",
         dataType: 'json',
         delay: 250,
          data: function (params) {
          var query = {
              kabupaten_id: $(this).closest('.lokasi-kegiatan').find('.kabupaten-select').val(),
              term: params.term,
          }
          return query;
        },
         processResults: function (data) {
            return {
            results: $.map(data, function(item) {
                return {
                text: item.name,
                id: item.id
                }
            })
        };
        },
         cache: true
         }
    });
      $('.list-lokasi-kegiatan').find('.lokasi-kegiatan:last .kelurahan-select').select2({
         placeholder: '{{ __('global.pleaseSelect') .' '.__('cruds.kegiatan.basic.kelurahan') }}',
         ajax: {
         url: "{{ route('api.kegiatan.kelurahan') }}",
         dataType: 'json',
         delay: 250,
         data: function (params) {
          var query = {
              kecamatan_id: $(this).closest('.lokasi-kegiatan').find('.kecamatan-select').val(),
              term: params.term,
          }
          return query;
        },
         processResults: function (data) {
            return {
            results: $.map(data, function(item) {
                return {
                text: item.name,
                id: item.id
                }
            })
        };
        },
         cache: true
         }
    });
    //  Event to trigger kabupaten load
    $('.list-lokasi-kegiatan').find('.lokasi-kegiatan:last .provinsi-select').on('change',function(){
        $(this).closest('.lokasi-kegiatan').find('.kabupaten-select').val(null).trigger('change');
         $(this).closest('.lokasi-kegiatan').find('.kecamatan-select').val(null).trigger('change');
          $(this).closest('.lokasi-kegiatan').find('.kelurahan-select').val(null).trigger('change');
    })
     $('.list-lokasi-kegiatan').find('.lokasi-kegiatan:last .kabupaten-select').on('change',function(){
        $(this).closest('.lokasi-kegiatan').find('.kecamatan-select').val(null).trigger('change');
        $(this).closest('.lokasi-kegiatan').find('.kelurahan-select').val(null).trigger('change');
    })
    $('.list-lokasi-kegiatan').find('.lokasi-kegiatan:last .kecamatan-select').on('change',function(){
         $(this).closest('.lokasi-kegiatan').find('.kelurahan-select').val(null).trigger('change');
    })
}


// Remove location row
$(document).on('click', '.remove-staff-row', function() {
    $(this).closest('.lokasi-kegiatan').remove();
});
