@push('js')
    <script>
        // var data_donor = "{{ route('api.prov') }}";
        var data_donor = "{{ route('api.program.donor') }}"; //for multiple select2
        var placeholder = "{{ __('cruds.program.donor.select') }}";
        // $(document).ready(function() {
        //     $('#donor').select2({
        //         placeholder: placeholder,
        //         width: '100%',
        //         allowClear: true,
        //         closeOnSelect: true,
        //         dropdownPosition: 'below',
        //         ajax: {
        //             url: data_donor,
        //             method: 'GET',
        //             delay: 1000,
        //             processResults: function(data) {
        //                 return {
        //                     results: data.map(function(item) {
        //                         return {
        //                             id: item.id,
        //                             text: item.nama // Mapping 'nama' to 'text'
        //                         };
        //                     })
        //                 };
        //             },
        //             data: function(params) {
        //                 var query = {
        //                     search: params.term,
        //                     page: params.page || 1
        //                 };
        //                 return query;
        //             }
        //         }
        //     });
        //     $('#donor').change(function() {
        //         var selected = $(this).val();
        //         $('#pendonor-container').empty();
        //         selected.forEach(function(pendonor_id) {
        //             if ($('#pendonor-container').find(`#pendonor-${pendonor_id}`).length === 0) {
        //                 var data_pendonor = '{{ route('api.search.pendonor', ':id') }}'.replace(
        //                     ':id', pendonor_id);

        //                 $.ajax({
        //                     type: 'GET',
        //                     url: data_pendonor,
        //                     dataType: 'json',
        //                     delay: 500,
        //                     success: function(data) {
        //                         setTimeout(() => {
        //                             if (data && Array.isArray(data)) {
        //                                 data.forEach(function(pendonor) {

        //                                     let containerId =
        //                                         `pendonor-container-${pendonor.id}`;
        //                                         $('#pendonor-container')
        //                                         .append(`
        //                                             <div class="row" id="${containerId}">
        //                                                 <div class="col-lg-3 form-group">
        //                                                     <div class="input-group">
        //                                                         <label for="nama_pendonor-${pendonor.id}" class="input-group small mb-0">{{ __('cruds.program.donor.nama') }}</label>
        //                                                         <input type="hidden" name="pendonor_id[]" value="${pendonor.id}" id="pendonor-${pendonor.id}">
        //                                                         <input type="hidden" name="program_id[]" value="${pendonor.id}" id="program-${pendonor.id}">
        //                                                         <input type="text" id="nama_pendonor-${pendonor.id}" name="nama_pendonor" class="form-control" value="${pendonor.nama || ''}" readonly>
        //                                                     </div>
        //                                                 </div>
        //                                                 <div class="col-lg-3 form-group">
        //                                                     <div class="input-group">
        //                                                         <label for="email-${pendonor.id}" class="input-group small mb-0">{{ __('cruds.program.donor.email') }}</label>
        //                                                         <input type="text" id="email-${pendonor.id}" name="email" class="form-control" value="${pendonor.email || ''}" readonly>
        //                                                     </div>
        //                                                 </div>
        //                                                 <div class="col-lg-2 form-group">
        //                                                     <div class="input-group">
        //                                                         <label for="phone-${pendonor.id}" class="input-group small mb-0">{{ __('cruds.program.donor.ph') }}</label>
        //                                                         <input type="text" id="phone-${pendonor.id}" name="phone" class="form-control" value="${pendonor.phone || ''}" readonly>
        //                                                     </div>
        //                                                 </div>
        //                                                 <div class="col-lg-3 form-group">
        //                                                     <div class="input-group">
        //                                                         <label for="nilaidonasi-${pendonor.id}" class="input-group small mb-0">{{ __('cruds.program.donor.val') }}</label>
        //                                                         <input type="text" id="nilaidonasi-${pendonor.id}" name="nilaidonasi[]" class="form-control currency nilaidonasi-value" value="${pendonor.nilaidonasi || 0}">
        //                                                         <span class="input-group-append">
        //                                                             <button type="button" class="btn btn-danger form-control remove-pendonor nilaidonasi btn-flat" data-target="${containerId}"><i class="bi bi-trash"></i></button>
        //                                                         </span>
        //                                                     </div>
        //                                                 </div>
        //                                             </div>
        //                                         `);
        //                                         new AutoNumeric(`#nilaidonasi-${pendonor.id}`, {
        //                                             digitGroupSeparator: '.',
        //                                             decimalCharacter: ',',
        //                                             currencySymbol: 'Rp ',
        //                                             modifyValueOnWheel: false
        //                                         });
        //                                 });
        //                             } else {
        //                                 console.error('Invalid data format',
        //                                     data);
        //                             }
        //                         }, 500);

        //                     },
        //                     error: function(xhr) {
        //                         console.error('AJAX error:', xhr);
        //                     }
        //                 });
        //             }
        //         });
        //     });
        //     // Add click event for remove button
        //     $(document).on('click', '.remove-pendonor', function() {
        //         let targetId = $(this).data('target');
        //         $('#' + targetId).remove();
        //     });
        // });

        $(document).ready(function() {
            let rowCounter = 1; // Start with 1 or the current number of rows
            // Function to initialize select2 for a specific row
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
                                <input type="text" id="nilaidonasi-${rowCounter}" name="nilaidonasi[]" class="form-control currency">
                                <span class="input-group-append">
                                    <button type="button" class="btn btn-danger form-control remove-pendonor nilaidonasi btn-flat" data-target="pendonor-row-${rowCounter}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                `);

                // Append the new row
                $('#pendonor-container').append(newRow);

                // Initialize select2 for the new row
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
