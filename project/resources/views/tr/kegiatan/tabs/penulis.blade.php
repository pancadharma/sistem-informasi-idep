<!-- Penulis Laporan Kegiatan -->
<div class="form-group row tambah_penulis col" id="PenulisContainer">
    <button type="button" class="btn btn-success float-right" id="addPenulis">
        <i class="bi bi-folder-plus"></i> {{ __('global.add') .' '. __('cruds.kegiatan.penulis.laporan') }}
    </button>
</div>

<div class="form-group row col-md-12" id="list_penulis_edit">
    @if (!empty($kegiatan->penulis) && $kegiatan->penulis->isNotEmpty())
        @foreach ($kegiatan->penulis as $penulis)
            <div class="row penulis-row col-12">
                <div class="col-lg-5 form-group mb-0">
                    <label for="penulis">{{ __('cruds.kegiatan.penulis.nama') }}</label>
                    <div class="select2-orange">
                        <select class="form-control select2 penulis-select" name="penulis[]" data-selected="{{ $penulis->id }}">
                            <option value="{{ $penulis->id }}" selected>{{ $penulis->nama }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-5 form-group d-flex align-items-end">
                    <div class="flex-grow-1">
                        <label for="jabatan">{{ __('cruds.kegiatan.penulis.jabatan') }}</label>
                        <div class="select2-orange">
                            <select class="form-control select2 jabatan-select" name="jabatan[]" data-selected="{{ $penulis->pivot->peran_id }}">
                                <option value="{{ $penulis->pivot->peran_id }}" selected>
                                    {{ $penulis->peran->find($penulis->pivot->peran_id)->nama ?? 'Unknown Role' }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="ml-2">
                        <button type="button" class="btn btn-danger remove-penulis-row">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        {{-- <p class="text-muted"></p> --}}
    @endif
</div>


@push('basic_tab_js')
<script>
    function initPenulisSelect2(selectElement) {
        var data_penulis = "{{ route('api.kegiatan.penulis') }}";

        selectElement.select2({
            width: '100%',
            allowClear: true,
            ajax: {
                url: data_penulis,
                method: 'GET',
                delay: 250,
                processResults: function(data) {
                    var selectedIds = $('.penulis-select').map(function() {
                        return $(this).val();
                    }).get().filter(Boolean);

                    return {
                        results: data.filter(function(item) {
                            return !selectedIds.includes(item.id.toString());
                        }).map(function(item) {
                            return {
                                id: item.id,
                                text: item.nama
                            };
                        })
                    };
                }
            }
        });
    }

    function initJabatanSelect2(selectElement) {
        var data_jabatan = "{{ route('api.kegiatan.jabatan') }}";

        selectElement.select2({
            width: '100%',
            allowClear: true,
            ajax: {
                url: data_jabatan,
                method: 'GET',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.nama
                            };
                        })
                    };
                }
            }
        });
    }

    $(document).ready(function() {
        // Initialize Select2 for existing selects when page loads
        $('.penulis-select').each(function() {
            initPenulisSelect2($(this));
        });
        $('.jabatan-select').each(function() {
            initJabatanSelect2($(this));
        });

        $('#addPenulis').on('click', function() {
            var newRow = $(`
            <div class="row penulis-row col-12">
                <div class="col-lg-5 form-group mb-0">
                    <label>{{ __('cruds.kegiatan.penulis.nama') }}</label>
                    <div class="select2-orange">
                        <select class="form-control select2 penulis-select" name="penulis[]"></select>
                    </div>
                </div>
                <div class="col-lg-5 form-group d-flex align-items-end">
                    <div class="flex-grow-1">
                        <label>{{ __('cruds.kegiatan.penulis.jabatan') }}</label>
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
            </div>`);

            $('#list_penulis_edit').append(newRow);

            // ✅ Initialize Select2 for dynamically added selects
            initPenulisSelect2(newRow.find('.penulis-select'));
            initJabatanSelect2(newRow.find('.jabatan-select'));
        });

        $('#list_penulis_edit').on('click', '.remove-penulis-row', function() {
            $(this).closest('.penulis-row').remove();
        });
    });
</script>
@endpush
