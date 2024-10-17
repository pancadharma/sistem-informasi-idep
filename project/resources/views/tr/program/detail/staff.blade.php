{{-- delete the form tag --}}
<div class="col-md-12">
    <div class="form-group">
        <label for="lokasi" class="small control-label">
            <strong>
                {{ __('cruds.program.staff.label') }}
            </strong>
        </label>
        <div class="select2-orange">
            <select class="form-control select2" name="staff[]" id="staff" multiple="multiple">
            </select>
        </div>
    </div>
</div>
@push('js')
    <script>
        // var data_staff = "{{ route('api.prov') }}";
        var data_staff = "{{ route('api.program.staff') }}";
        var placeholder = "{{ __('global.pleaseSelect') . ' ' . __('cruds.program.staff.label') }}";

        $('#staff').select2({
            placeholder: placeholder,
            width: '100%',
            allowClear: true,
            closeOnSelect: false,
            dropdownPosition: 'below',
            ajax: {
                url: data_staff,
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
