<script>
    $(document).ready(function() {
        var data_lokasi = "{{ route('api.program.lokasi') }}";

        var edit_data_lokasi = @json(
            $program->lokasi->map(function ($lokasi) {
                return ['id' => $lokasi->id, 'text' => $lokasi->nama];
            }));
        var selected_lokasi = @json(
            $program->lokasi->map(function ($lokasi) {
                return $lokasi->id;
            }));
        $('#lokasi').select2({
            placeholder: '{{ __('cruds.program.lokasi.select') }}',
            width: '100%',
            data: edit_data_lokasi, //pre populate selected location
            allowClear: true,
            width: '100%',
            dropdownPosition: 'below',
            ajax: {
                url: data_lokasi, //call data_lokasi
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

        // Trigger Select2 #lokasi to change into selected data from database
        if (selected_lokasi.length > 0) {
            $('#lokasi').val(selected_lokasi).trigger('change');
        }

    });
</script>
`
