@push('js')
    <script>
        var data_partner = "{{ route('api.program.partner') }}";
        var placeholder = "{{ __('global.pleaseSelect') . ' ' . __('cruds.program.partner.label') }}";

        $('#partner').select2({
            placeholder: placeholder,
            width: '100%',
            allowClear: true,
            closeOnSelect: false,
            dropdownPosition: 'below',
            ajax: {
                url: data_partner,
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

    </script>
@endpush
