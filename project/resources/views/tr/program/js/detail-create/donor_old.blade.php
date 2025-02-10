@push('js')
    <script>
        // var data_donor = "{{ route('api.prov') }}";
        var data_donor = "{{ route('api.program.donor') }}"; //for multiple select2
        var placeholder = "{{ __('cruds.program.donor.select') }}";
        $(document).ready(function() {
            $('#donor').select2({
                placeholder: placeholder,
                width: '100%',
                allowClear: true,
                closeOnSelect: true,
                dropdownPosition: 'below',
                ajax: {
                    url: data_donor,
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
            $('#donor').change(function() {
                var selected = $(this).val();
                $('#pendonor-container').empty();
                selected.forEach(function(pendonor_id) {
                    if ($('#pendonor-container').find(`#pendonor-${pendonor_id}`).length === 0) {
                        var data_pendonor = '{{ route('api.search.pendonor', ':id') }}'.replace(
                            ':id', pendonor_id);

                        $.ajax({
                            type: 'GET',
                            url: data_pendonor,
                            dataType: 'json',
                            delay: 500,
                            success: function(data) {
                                setTimeout(() => {
                                    if (data && Array.isArray(data)) {
                                        data.forEach(function(pendonor) {

                                            let containerId =
                                                `pendonor-container-${pendonor.id}`;
                                                $('#pendonor-container')
                                                .append(`
                                                    <div class="row" id="${containerId}">
                                                        <div class="col-lg-3 form-group">
                                                            <div class="input-group">
                                                                <label for="nama_pendonor-${pendonor.id}" class="input-group small mb-0">{{ __('cruds.program.donor.nama') }}</label>
                                                                <input type="hidden" name="pendonor_id[]" value="${pendonor.id}" id="pendonor-${pendonor.id}">
                                                                <input type="hidden" name="program_id[]" value="${pendonor.id}" id="program-${pendonor.id}">
                                                                <input type="text" id="nama_pendonor-${pendonor.id}" name="nama_pendonor" class="form-control" value="${pendonor.nama || ''}" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 form-group">
                                                            <div class="input-group">
                                                                <label for="email-${pendonor.id}" class="input-group small mb-0">{{ __('cruds.program.donor.email') }}</label>
                                                                <input type="text" id="email-${pendonor.id}" name="email" class="form-control" value="${pendonor.email || ''}" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 form-group">
                                                            <div class="input-group">
                                                                <label for="phone-${pendonor.id}" class="input-group small mb-0">{{ __('cruds.program.donor.ph') }}</label>
                                                                <input type="text" id="phone-${pendonor.id}" name="phone" class="form-control" value="${pendonor.phone || ''}" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 form-group">
                                                            <div class="input-group">
                                                                <label for="nilaidonasi-${pendonor.id}" class="input-group small mb-0">{{ __('cruds.program.donor.val') }}</label>
                                                                <input type="text" id="nilaidonasi-${pendonor.id}" name="nilaidonasi[]" class="form-control currency nilaidonasi-value" value="${pendonor.nilaidonasi || 0}">
                                                                <span class="input-group-append">
                                                                    <button type="button" class="btn btn-danger form-control remove-pendonor nilaidonasi btn-flat" data-target="${containerId}"><i class="bi bi-trash"></i></button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                `);
                                                new AutoNumeric(`#nilaidonasi-${pendonor.id}`, {
                                                    digitGroupSeparator: '.',
                                                    decimalCharacter: ',',
                                                    currencySymbol: 'Rp ',
                                                    modifyValueOnWheel: false
                                                });
                                        });
                                    } else {
                                        console.error('Invalid data format',
                                            data);
                                    }
                                }, 500);

                            },
                            error: function(xhr) {
                                console.error('AJAX error:', xhr);
                            }
                        });
                    }
                });
            });
            // Add click event for remove button
            $(document).on('click', '.remove-pendonor', function() {
                let targetId = $(this).data('target');
                $('#' + targetId).remove();
            });
        });

        function capitalizeWords(str) {
            return str.replace(/\b\w/g, function(char) {
                return char.toUpperCase();
            });
        }
        $('#nama, #editnama').on('input', function() {
            let input = $(this).val();
            if (/^\d{1,3}/.test(input)) {
                input = input.replace(/^\d{1,3}/, '');
            }
            input = capitalizeWords(input);
            $(this).val(input);
        });
    </script>
@endpush
