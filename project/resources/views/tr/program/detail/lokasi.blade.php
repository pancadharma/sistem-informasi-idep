{{-- delete the form tag --}}
<div class="col-md-12">
    <div class="form-group">
        <label for="lokasi" class="small control-label">
            <strong>
                {{ __('cruds.dusun.form.prov') }}
            </strong>
        </label>
        <div class="select2-orange">
            <select class="form-control select2" name="lokasi[]" id="lokasi" multiple="multiple">
            </select>
        </div>
    </div>
</div>
@push('js')
    <script>
        // var data_lokasi = "{{ route('api.prov') }}";
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
    </script>
@endpush
