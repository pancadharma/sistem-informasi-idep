<script>
function initializeLocationSelects(provinsiSelector, kabupatenSelector, kecamatanSelector, desaSelector, dusunSelector, dropdownParent) {
    // Initialize Provinsi dropdown
    $(provinsiSelector).select2({
        ajax: {
            url: "{{ route('api.prov') }}",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return { search: params.term, page: params.page || 1 };
            },
            processResults: function(data) {
                return { results: data.results, pagination: data.pagination };
            }
        },
        dropdownParent: dropdownParent
    });

    // Initialize Kabupaten (depends on Provinsi)
    $(kabupatenSelector).select2({
        ajax: {
            url: function() {
                return "{{ route('api.kab', ['id' => ':id']) }}".replace(':id', $(provinsiSelector).val());
            },
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return { search: params.term, page: params.page || 1 };
            },
            processResults: function(data) {
                return { results: data.results, pagination: data.pagination };
            }
        },
        dropdownParent: dropdownParent
    });

    // Initialize Kecamatan (depends on Kabupaten)
    $(kecamatanSelector).select2({
        ajax: {
            url: function() {
                return "{{ route('api.kec', ['id' => ':id']) }}".replace(':id', $(kabupatenSelector).val());
            },
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return { search: params.term, page: params.page || 1 };
            },
            processResults: function(data) {
                return { results: data.results, pagination: data.pagination };
            }
        },
        dropdownParent: dropdownParent
    });

    // Initialize Desa (depends on Kecamatan)
    $(desaSelector).select2({
        ajax: {
            url: function() {
                return "{{ route('api.desa', ['id' => ':id']) }}".replace(':id', $(kecamatanSelector).val());
            },
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return { search: params.term, page: params.page || 1 };
            },
            processResults: function(data) {
                return { results: data.results, pagination: data.pagination };
            }
        },
        dropdownParent: dropdownParent
    });
    // Initialize Desa (depends on Kecamatan)
    $(dusunSelector).select2({
        ajax: {
            url: function() {
                return "{{ route('api.dusun', ['id' => ':id']) }}".replace(':id', $(desaSelector).val());
            },
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return { search: params.term, page: params.page || 1 };
            },
            processResults: function(data) {
                return { results: data.results, pagination: data.pagination };
            }
        },
        dropdownParent: dropdownParent
    });

    // Clear dependent dropdowns when parent changes
    $(provinsiSelector).on('change', function() {
        $(kabupatenSelector).val(null).trigger('change');
        $(kecamatanSelector).val(null).trigger('change');
        $(desaSelector).val(null).trigger('change');
        $(dusunSelector).val(null).trigger('change');
    });

    $(kabupatenSelector).on('change', function() {
        $(kecamatanSelector).val(null).trigger('change');
        $(desaSelector).val(null).trigger('change');
        $(dusunSelector).val(null).trigger('change');
    });

    $(kecamatanSelector).on('change', function() {
        $(desaSelector).val(null).trigger('change');
        $(dusunSelector).val(null).trigger('change');
    });

    $(desaSelector).on('change', function() {
        $(dusunSelector).val(null).trigger('change');
    });
}
</script>