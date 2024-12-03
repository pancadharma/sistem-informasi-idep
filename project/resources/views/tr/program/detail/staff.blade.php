<div class="col-md-12" id="staffContainer">
    <!-- Labels for the fields -->
    <div class="row">
        <div class="col-lg-5 form-group">
            <label for="staff" class="input-group small mb-0">{{ __('cruds.program.staff.label') }}</label>
        </div>
        <div class="col-lg-5 form-group">
            <label for="peran" class="input-group small mb-0">{{ __('cruds.program.staff.peran') }}</label>
        </div>
        <div class="col-lg-2 form-group">
            <label for="tambah" class="input-group small mb-0"> </label>
        </div>
    </div>

    @if($program->staff->isEmpty())
        <!-- Show a single set of fields if there are no existing staff -->
        <div class="row staff-row">
            <div class="col-lg-5 form-group">
                <div class="form-group">
                    <div class="select2-orange">
                        <select class="form-control select2 staff-select" name="staff[]" id="staff_0">
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 form-group">
                <div class="form-group">
                    <div class="select2-orange">
                        <select class="form-control select2 peran-select" name="peran[]" id="peran_0">
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 form-group">
                <div class="form-group">
                    <span class="input-group-append">
                        <button type="button" class="ml-2 btn btn-success add-staff-row btn-flat">
                            <i class="bi bi-plus"></i>
                        </button>
                    </span>
                </div>
            </div>
        </div>
    @else
        @foreach ($program->staff as $index => $staff)
            <div class="row staff-row">
                <div class="col-lg-5 form-group">
                    <div class="form-group">
                        <div class="select2-orange">
                            <select class="form-control select2 staff-select" name="staff[]" id="staff_{{ $index }}">
                                <option value="{{ $staff->id }}" selected>{{ $staff->nama }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 form-group">
                    <div class="form-group">
                        <div class="select2-orange">
                            <select class="form-control select2 peran-select" name="peran[]" id="peran_{{ $index }}">
                                <option value="{{ $staff->pivot->peran_id }}" selected>{{ $staff->pivot->peran->nama }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 form-group">
                    <div class="form-group">
                        <input type="hidden" name="staff_pivot_id[]" value="{{ $staff->pivot->id }}">
                        <span class="input-group-append">
                            @if ($index === 0)
                                <button type="button" class="ml-2 btn btn-success add-staff-row btn-flat">
                                    <i class="bi bi-plus"></i>
                                </button>
                            @else
                                <button type="button" class="ml-2 btn btn-danger remove-staff-row btn-flat">
                                    <i class="bi bi-trash"></i>
                                </button>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

@push('css')
    <style>
    .select2-container .select2-selection {
        width: 100%;
    }
    .select2-container {
        width: 100% !important;
    }
    </style>
@endpush

@push('js')
<script>
    $(document).ready(function() {
        // $('.select2').select2();
        // Staff Select2 Configuration
        function initStaffSelect2(selectElement) {
            // Destroy existing Select2 if it exists
            if (selectElement.data('select2')) {
                selectElement.select2('destroy');
            }

            var data_staff = "{{ route('api.program.staff') }}";
            var placeholder = "{{ __('global.pleaseSelect') . ' ' . __('cruds.program.staff.label') }}";

            selectElement.select2({
                placeholder: placeholder,
                width: '100%',
                allowClear: true,
                ajax: {
                    url: data_staff,
                    method: 'GET',
                    delay: 250,
                    processResults: function(data) {
                        // Get currently selected staff IDs
                        var selectedStaffIds = $('.staff-select')
                            .map(function() {
                                return $(this).val();
                            })
                            .get()
                            .filter(Boolean);

                        return {
                            results: data
                                .filter(function(item) {
                                    // Exclude already selected staff
                                    return !selectedStaffIds.includes(item.id.toString());
                                })
                                .map(function(item) {
                                    return {
                                        id: item.id,
                                        text: item.nama
                                    };
                                })
                        };
                    },
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    }
                }
            });
        }

        // Peran Select2 Configuration
        function initPeranSelect2(selectElement) {
            // Destroy existing Select2 if it exists
            if (selectElement.data('select2')) {
                selectElement.select2('destroy');
            }

            var data_peran = "{{ route('api.program.peran') }}";
            var placeholder = "{{ __('global.pleaseSelect') . ' ' . __('cruds.program.staff.peran') }}";

            selectElement.select2({
                placeholder: placeholder,
                width: '100%',
                allowClear: true,
                ajax: {
                    url: data_peran,
                    method: 'GET',
                    delay: 250,
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
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    }
                }
            });
        }

        // Initialize existing Select2 dropdowns
        function initializeExistingSelect2() {
            $('.staff-select').each(function() {
                initStaffSelect2($(this));
            });
            $('.peran-select').each(function() {
                initPeranSelect2($(this));
            });
        }
        // Initial initialization
        initializeExistingSelect2();

        // Add Staff Row
        $('#staffContainer').on('click', '.add-staff-row', function() {
            // Clone the first row
            var newRow = $('.staff-row:first').clone();

            // Clear any existing select2 containers
            newRow.find('.select2-container').remove();

            // Generate unique IDs
            var timestamp = Date.now();

            // Reset staff select
            newRow.find('.staff-select')
                .attr('id', 'staff_' + timestamp)
                .val(null)
                .removeClass('select2-hidden-accessible');

            // Preserve Peran select option
            var currentPeranOption = newRow.find('.peran-select option:selected');
            // newRow.find('.peran-select')
            //     .attr('id', 'peran_' + timestamp)
            //     .empty() // Clear existing options
            //     .append(currentPeranOption.clone()) // Add back the current selected option
            //     .val(currentPeranOption.val())
            //     .val(null)
            //     .removeClass('select2-hidden-accessible');

            newRow.find('.peran-select')
            .attr('id', 'peran_' + timestamp)
            .removeClass('select2-hidden-accessible');


            // Remove any hidden input for pivot ID
            newRow.find('input[name="staff_pivot_id[]"]').remove();

            // Replace add button with remove button for new rows
            var addButton = newRow.find('.add-staff-row');
            addButton.removeClass('btn-success add-staff-row')
                    .addClass('btn-danger remove-staff-row')
                    .html('<i class="bi bi-trash"></i>');

            // Append the new row
            $('#staffContainer').append(newRow);

            // Reinitialize Select2 for the new row
            initPeranSelect2(newRow.find('.peran-select'));
            initStaffSelect2(newRow.find('.staff-select'));

            // Refresh all staff selects to update available options
            $('.staff-select').each(function() {
                initStaffSelect2($(this));
            });
        });

        // Remove Staff Row
        $('#staffContainer').on('click', '.remove-staff-row', function() {
            // Ensure at least one row remains
            if ($('.staff-row').length > 1) {
                $(this).closest('.staff-row').remove();

                // Refresh all staff selects to update available options
                $('.staff-select').each(function() {
                    initStaffSelect2($(this));
                    initPeranSelect2($(this));
                });
            }
        });
    });
</script>
@endpush
