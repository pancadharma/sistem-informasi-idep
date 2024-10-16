<script>
    $(document).ready(function() {
        var data_lokasi = "{{ route('api.program.lokasi') }}";


        $('#lokasi').select2({
            placeholder: '{{ __('cruds.program.lokasi.select') }}',
            width: '100%',
            allowClear: true,
            closeOnSelect: false,
            // dropdownPosition: 'below',
            ajax: {
                url: data_lokasi,
                method: 'GET',
                delay: 1000,
                processResults: function(data) {
                    return {
                        results: data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.nama // Mapping 'nama' to 'text'
                            };
                        })
                    };
                },
                data: function(params) {
                    var query = {
                        search: params.term,
                        page: params.page || 1
                    };
                    return query;
                }
            }
        });
    });
</script>
