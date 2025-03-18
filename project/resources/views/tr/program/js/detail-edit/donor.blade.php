@push('js')
    <script>
        var programId = {{ $program->id }};
        var data_donor = "{{ route('api.program.donor') }}";
        var placeholder = "{{ __('cruds.program.donor.select') }}";
        var url_program_pendonor = "{{ route('api.pendonor.data', ':id') }}".replace(':id', programId);
        $(document).ready(function() {

            $.ajax({
                url: url_program_pendonor,
                method: 'get',
                dataType: 'json',
                delay: 500,
                success: function(data) {
                    setTimeout(() => {
                        var edit_program_pendonor = data.map(function(item) {
                            return {
                                id: item.id,
                                program_id: item.program_id,
                                nama: item.nama,
                                text: item.text,
                                email: item.email,
                                phone: item.phone,
                                nilaidonasi: item.nilaidonasi
                            };
                        });
                        var selected_pendonor = data.map(function(item) {
                            return item.id;
                        });
                        $('#donor').select2({
                            placeholder: placeholder,
                            width: '100%',
                            allowClear: true,
                            closeOnSelect: true,
                            dropdownPosition: 'below',
                            data: edit_program_pendonor,
                            ajax: {
                                url: data_donor,
                                method: 'GET',
                                delay: 500,
                                processResults: function(data) {
                                    return {
                                        results: data.map(function(item) {
                                            return {
                                                id: item.id,
                                                text: item.nama
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
                    }, 1000);
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching program pendonors:", status, error);
                    // Optionally show user-friendly error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Failed to load donor information'
                    });
                }
            });

            $('#donor').change(function() {
                var selected = $(this).val();
                var existingDonors = new Set();

                $('#pendonor-container .row').each(function() {
                    var existingDonorId = $(this).find('input[name="pendonor_id[]"]').val();
                    existingDonors.add(existingDonorId);
                });

                $('#pendonor-container').empty();


                selected.forEach(function(pendonor_id) {
                    if (existingDonors.has(pendonor_id)) return;

                    if ($('#pendonor-container').find(`#pendonor-${pendonor_id}`).length === 0) {
                        var data_pendonor = '{{ route('api.search.pendonor', ':id') }}'.replace(
                                ':id', pendonor_id) + '?program_id=' +
                            programId;

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
                                                                <input type="hidden" name="program_id[]" value="${pendonor.id}" id="pendonor-${pendonor.id}">
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
                                                                <input type="text" id="nilaidonasi-${pendonor.id}" name="nilaidonasi[]" class="form-control currency nilaidonasi" value="${pendonor.nilaidonasi || 0}">
                                                                <span class="input-group-append">
                                                                    <button type="button" class="btn btn-danger form-control remove-pendonor nilaidonasi btn-flat" data-target="${containerId}"><i class="bi bi-trash"></i></button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                `);
                                            // Initialize AutoNumeric for donation value
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
                                }, 1000);

                            },
                            error: function(xhr) {
                                console.error('AJAX error:', xhr);
                            }
                        });
                    }
                });
            });

            let rowCounter = 1;
            function initializeSelect2(rowElement) {
                rowElement.find('select[name="pendonor[]"]').select2({

                    placeholder:  "Pilih Pendonor",
                    width: '100%',
                    allowClear: true,
                    ajax: {
                        url: "{{ route('api.program.donor') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                search: params.term,
                                page: params.page || 1
                            };
                        },
                        processResults: function(data, params) {
                            params.page = params.page || 1;

                            return {
                                results: data.data.map(function(item) {
                                    return {
                                        id: item.id,
                                        text: item.nama
                                    };
                                }),
                                pagination: {
                                    more: data.current_page < data.last_page
                                }
                            };
                        },
                        cache: true
                    },
                });

                // Add change event to populate additional fields
                rowElement.find('select[name="pendonor[]"]').on('change', function() {
                    const selectedId = $(this).val();
                    const rowContainer = $(this).closest('.row');
                    let url_search = "{{ route('api.search.pendonor', ':id') }}".replace(':id', selectedId);
                    // Fetch pendonor details
                    $.ajax({

                        url: url_search,
                        method: 'GET',
                        success: function(data) {
                            if (data && data.length > 0) {
                                const pendonor = data[0];

                                // Populate hidden inputs
                                rowContainer.find('input[name="pendonor_id[]"]').val(pendonor.id);
                                rowContainer.find('input[name="program_id[]"]').val(pendonor.id);

                                // Populate other fields
                                rowContainer.find('input[name="email"]').val(pendonor.email || '');
                                rowContainer.find('input[name="phone"]').val(pendonor.phone || '');

                                // Initialize AutoNumeric for donation value
                                new AutoNumeric(rowContainer.find('input[name="nilaidonasi[]"]')[0], {
                                    digitGroupSeparator: '.',
                                    decimalCharacter: ',',
                                    currencySymbol: 'Rp ',
                                    modifyValueOnWheel: false
                                });
                            }
                        },
                        error: function(xhr) {
                            console.error('Error fetching pendonor details', xhr);
                        }
                    });
                });
            }
             // Add new row button click handler
            $('#addDonasiButton').on('click', function() {
                rowCounter++; // Increment counter
                // Clone the template row
                const newRow = $(`
                    <div class="row container-pendonor" id="pendonor-row-${rowCounter}">
                        <div class="col-lg-3 form-group">
                            <div class="input-group">
                                <label for="nama_pendonor-${rowCounter}" class="input-group small mb-0">{{ __('cruds.program.donor.nama') }}</label>
                                <input type="hidden" name="pendonor_id[]">
                                <input type="hidden" name="program_id[]">
                                <select name="pendonor[]" class="form-control select2"></select>
                            </div>
                        </div>
                        <div class="col-lg-3 form-group">
                            <div class="input-group">
                                <label for="email-${rowCounter}" class="input-group small mb-0">{{ __('cruds.program.donor.email') }}</label>
                                <input type="text" id="email-${rowCounter}" name="email" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-lg-2 form-group">
                            <div class="input-group">
                                <label for="phone-${rowCounter}" class="input-group small mb-0">{{ __('cruds.program.donor.ph') }}</label>
                                <input type="text" id="phone-${rowCounter}" name="phone" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-lg-3 form-group">
                            <div class="input-group">
                                <label for="nilaidonasi-${rowCounter}" class="input-group small mb-0">{{ __('cruds.program.donor.val') }}</label>
                                <input type="text" id="nilaidonasi-${rowCounter}" name="nilaidonasi[]" class="form-control currency nilaidonasi-value">
                                <span class="input-group-append">
                                    <button type="button" class="btn btn-danger form-control remove-pendonor nilaidonasi btn-flat" data-target="pendonor-row-${rowCounter}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                `);

                $('#pendonor-container').append(newRow);


                initializeSelect2(newRow);
            });

            // Remove row button handler (using event delegation)
            $(document).on('click', '.remove-pendonor', function() {
                const targetId = $(this).data('target');
                $('#' + targetId).remove();
            });

            // Initialize the first row's select2 if it exists
            initializeSelect2($('.container-pendonor').first());


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
