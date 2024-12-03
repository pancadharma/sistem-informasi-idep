@push('js')
<script>
$(document).ready(function () {
    // Initialize Staff and Peran Select2
    function initStaffSelect2(selectElement) {
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
                processResults: function (data) {
                    // Exclude already selected staff
                    var selectedStaffIds = $('.staff-select')
                        .map(function () {
                            return $(this).val();
                        })
                        .get()
                        .filter(Boolean);

                    return {
                        results: data
                            .filter(function (item) {
                                return !selectedStaffIds.includes(item.id.toString());
                            })
                            .map(function (item) {
                                return {
                                    id: item.id,
                                    text: item.nama
                                };
                            })
                    };
                },
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                }
            }
        });

        // Preselect existing value
        var selectedValue = selectElement.data('selected');
        if (selectedValue) {
            selectElement.val(selectedValue).trigger('change');
        }
    }

    function initPeranSelect2(selectElement) {
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
                processResults: function (data) {
                    return {
                        results: data.map(function (item) {
                            return { id: item.id, text: item.nama };
                        })
                    };
                },
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                }
            }
        });

        // Preselect existing value
        var selectedValue = selectElement.data('selected');
        if (selectedValue) {
            selectElement.val(selectedValue).trigger('change');
        }
    }

    // Initialize Select2 for all preloaded rows
    $('.staff-select').each(function () {
        initStaffSelect2($(this));
    });
    $('.peran-select').each(function () {
        initPeranSelect2($(this));
    });

    // Add New Staff and Peran Input Row
    $('#addStaffRowButton').on('click', function () {
        var newRow = $(`
            <div class="row staff-row">
                <div class="col-lg-5 form-group">
                    <div class="select2-orange">
                        <select class="form-control select2 staff-select" name="staff[]"></select>
                    </div>
                </div>
                <div class="col-lg-5 form-group">
                    <div class="select2-orange">
                        <select class="form-control select2 peran-select" name="peran[]"></select>
                    </div>
                </div>
                <div class="col-lg-2 form-group">
                    <button type="button" class="btn btn-danger remove-staff-row btn-flat">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `);

        // Append new row to container
        $('#staffContainer').append(newRow);

        // Initialize Select2 for the new row
        initStaffSelect2(newRow.find('.staff-select'));
        initPeranSelect2(newRow.find('.peran-select'));
    });

    // Remove Staff Row
    $('#staffContainer').on('click', '.remove-staff-row', function () {
        $(this).closest('.staff-row').remove();
    });
});
</script>
@endpush


{{-- @push('js')
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
@endpush --}}
