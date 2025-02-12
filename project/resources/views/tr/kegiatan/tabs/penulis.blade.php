<!-- Penulis Laporan Kegiatan-->
<div class="form-group row tambah_penulis col" id="PenulisContainer">
    <button type="button" class="btn btn-success float-right" title="{{ __('global.add') .' '. __('cruds.kegiatan.penulis.laporan') }}" id="addPenulis">
        <i class="bi bi-folder-plus"></i> {{ __('global.add') .' '. __('cruds.kegiatan.penulis.laporan') }}
    </button>
</div>

<div class="form-group row" id="list_penulis">
    <div class="col-12">
    </div>
</div>
{{-- <div class="form-group row" id="list_penulis_2">

</div> --}}

<div class="form-group row col-md-12" id="list_penulis_edit">
    @foreach ($kegiatan->penulis as $penulis)

    <div class="row penulis-row col-12">
        <div class="col-lg-5 form-group mb-0">
            <label for="penulis" class="input-group small mb-0">{{ __('cruds.kegiatan.penulis.nama') }}</label>
            <div class="select2-orange">
                <select class="form-control select2 penulis-select" name="penulis[]" data-selected="{{ $penulis->id }}">
                    <option value="{{ $penulis->id }}" selected>{{ $penulis->nama }}</option>
                </select>
            </div>

        </div>
        <div class="col-lg-5 form-group d-flex align-items-end">
            <div class="flex-grow-1">
                <label for="jabatan" class="input-group small mb-0">{{ __('cruds.kegiatan.penulis.jabatan') }}</label>
                <div class="select2-orange">
                    <select class="form-control select2 jabatan-select" name="jabatan[]" data-selected="{{ $penulis->pivot->peran_id }}">
                        <option value="{{ $penulis->pivot->peran_id }}" selected>
                            {{ $penulis->peran->find($penulis->pivot->peran_id)->nama ?? 'Unknown Role' }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="ml-2">
                <button type="button" class="btn btn-danger remove-penulis-row btn-flat">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    </div>
    @endforeach
</div>
<!-- End Penulis Laporan Kegiatan-->

@push('basic_tab_js')
<script>
    function initPenulisSelect2(selectElement) {
        var data_penulis = "{{ route('api.kegiatan.penulis') }}";
        var placeholder = "{{  __('cruds.kegiatan.penulis.sel_penulis') }}";

        selectElement.select2({
            placeholder: placeholder,
            width: '100%',
            allowClear: true,
            ajax: {
                url: data_penulis,
                method: 'GET',
                delay: 250,
                processResults: function (data) {
                    // Exclude already selected penulis
                    var selectedpenulisIds = $('.penulis-select')
                        .map(function () {
                            return $(this).val();
                        })
                        .get()
                        .filter(Boolean);

                    return {
                        results: data
                            .filter(function (item) {
                                return !selectedpenulisIds.includes(item.id.toString());
                            })
                            .map(function (item) {
                                return {
                                    id: item.id,
                                    text: item.nama
                                };
                            })
                    };
                },
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                }
            }
        });
    }

    function initJabatanSelect2(selectElement) {
        var data_jabatan = "{{ route('api.kegiatan.jabatan') }}";
        var placeholder = "{{ __('cruds.kegiatan.penulis.sel_jabatan') }}";

        selectElement.select2({
            placeholder: placeholder,
            width: '100%',
            allowClear: true,
            ajax: {
                url: data_jabatan,
                method: 'GET',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data.map(function (item) {
                            return { id: item.id, text: item.nama };
                        })
                    };
                },
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                }
            }
        });
    }

    // Add New penulis and jabatan Input Row
    $('#addPenulis').on('click', function () {

            var newRow = $(`
            <div class="row penulis-row col-12">
                <div class="col-lg-5 form-group mb-0">
                    <label for="penulis" class="input-group small mb-0">{{ __('cruds.kegiatan.penulis.nama') }}</label>
                    <div class="select2-orange">
                        <select class="form-control select2 penulis-select" name="penulis[]"></select>
                    </div>
                </div>
                <div class="col-lg-5 form-group d-flex align-items-end">
                    <div class="flex-grow-1">
                        <label for="jabatan" class="input-group small mb-0">{{ __('cruds.kegiatan.penulis.jabatan') }}</label>
                        <div class="select2-orange">
                            <select class="form-control select2 jabatan-select" name="jabatan[]"></select>
                        </div>
                    </div>
                    <div class="ml-2">
                        <button type="button" class="btn btn-danger remove-penulis-row btn-flat">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `);
        // Append new row to container
        $('#list_penulis').append(newRow);

        // Initialize Select2 for the new row
        initPenulisSelect2(newRow.find('.penulis-select'));
        initJabatanSelect2(newRow.find('.jabatan-select'));
    });

    // Remove penulis Row
    $('#list_penulis').on('click', '.remove-penulis-row', function () {
        $(this).closest('.penulis-row').remove();
    });

</script>

@endpush
