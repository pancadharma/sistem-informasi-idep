@push('js')
    <script>
        var programId = {{ $program->id }};
        var data_donor = "{{ route('api.program.donor') }}"; // for multiple select2

        var placeholder = "{{ __('global.pleaseSelect') . ' ' . __('cruds.program.donor.label') }}";
        var url_program_pendonor = "{{ route('api.pendonor.data', ':id') }}".replace(':id', programId);

        $(document).ready(function() {
            $.getJSON(url_program_pendonor, function(data) {
                var edit_program_pendonor = data.map(function(item) {
                    return {
                        id: item.id,
                        text: item.text,
                        email: item.email,
                        phone: item.phone,
                        nilaidonasi: item.nilaidonasi
                    };
                });

                var selected_pendonor = data.map(function(item) {
                    return item.id;
                });

                // Initialize select2 with existing data
                $('#donor').select2({
                    placeholder: placeholder,
                    width: '100%',
                    allowClear: true,
                    closeOnSelect: false,
                    dropdownPosition: 'below',
                    data: edit_program_pendonor,
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
                $('#donor').val(selected_pendonor).trigger('change');
            });

            $('#donor').change(function() {
                var selected = $(this).val();
                console.log('Selected values:', selected);

                selected.forEach(function(pendonor_id) {
                    if ($('#pendonor-container').find(`#pendonor-${pendonor_id}`).length === 0) {
                        var data_pendonor = '{{ route('api.search.pendonor', ':id') }}'.replace(
                            ':id', pendonor_id);

                        $.ajax({
                            type: 'GET',
                            url: data_pendonor,
                            dataType: 'json',
                            success: function(data) {
                                console.log('Fetched data:', data);

                                if (data && data.id) {
                                    let containerId = `pendonor-container-${data.id}`;
                                    $('#pendonor-container').append(
                                        `<div class="row" id="${containerId}">
                                <div class="col-lg-3 form-group">
                                    <div class="input-group">
                                        <label for="pendonor_id" class="input-group small mb-0">{{ __('cruds.program.donor.nama') }}</label>
                                        <input type="hidden" name="pendonor_id[]" value="${data.id}" id="pendonor-${data.id}">
                                        <input type="text" id="nama-${data.id}" name="nama" class="form-control" value="${data.nama || ''}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-3 form-group">
                                    <div class="input-group">
                                        <label for="email-${data.id}" class="input-group small mb-0">{{ __('cruds.program.donor.email') }}</label>
                                        <input type="text" id="email-${data.id}" name="email" class="form-control" value="${data.email || ''}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-2 form-group">
                                    <div class="input-group">
                                        <label for="phone-${data.id}" class="input-group small mb-0">{{ __('cruds.program.donor.ph') }}</label>
                                        <input type="text" id="phone-${data.id}" name="phone" class="form-control" value="${data.phone || ''}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-3 form-group">
                                    <div class="input-group">
                                        <label for="nilaidonasi" class="input-group small mb-0">{{ __('cruds.program.donor.val') }}</label>
                                        <input type="text" id="nilaidonasi-${data.id}" name="nilaidonasi[]" class="form-control currency" value="${data.nilaidonasi || 0}">
                                        <span class="input-group-append">
                                            <button type="button" class="btn btn-danger form-control remove-pendonor nilaidonasi btn-flat" data-target="${containerId}"><i class="bi bi-trash"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>`
                                    );

                                    var nilaidonasiElement = `#nilaidonasi-${data.id}`;
                                    if (!AutoNumeric.getAutoNumericElement(
                                            nilaidonasiElement)) {
                                        console.log(
                                            `Initializing AutoNumeric for ${nilaidonasiElement} with value: ${data.nilaidonasi}`
                                        );
                                        new AutoNumeric(nilaidonasiElement, {
                                            digitGroupSeparator: '.',
                                            decimalCharacter: ',',
                                            currencySymbol: 'Rp ',
                                            modifyValueOnWheel: false
                                        });

                                        AutoNumeric.getAutoNumericElement(
                                            nilaidonasiElement).set(data
                                            .nilaidonasi);
                                    }
                                } else {
                                    console.error('Invalid data format', data);
                                }
                            },
                            error: function(xhr) {
                                console.error('AJAX error:', xhr);
                            }
                        });
                    }
                });
            });



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
