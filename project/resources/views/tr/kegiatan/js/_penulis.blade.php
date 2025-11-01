<script>
    function initPenulisSelect2(selectElement) {
        var data_penulis = "{{ route('api.kegiatan.penulis') }}";

        selectElement.select2({
            width: '100%',
            // allowClear: true,
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
            // allowClear: true,
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
