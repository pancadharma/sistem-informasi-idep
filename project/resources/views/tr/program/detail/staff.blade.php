<div class="col-md-12" id="staffContainer">
    <button type="button" id="addStaffRowButton" class="btn btn-primary btn-flat">
        <i class="bi bi-plus"></i>
    </button>
    <div class="row mt-2">
        <div class="col-lg-5">
            <label for="staff" class="input-group small mb-0">{{ __('cruds.program.staff.label') }}</label>
        </div>
        <div class="col-lg-5 form-group">
            <label for="peran" class="input-group small mb-0">{{ __('cruds.program.staff.peran') }}</label>
        </div>
        <div class="col-lg-2 form-group">
            <label for="tambah" class="input-group small mb-0"></label>
        </div>
    </div>
</div>
<!-- Render existing staff and peran rows -->
<div class="col-md-12" id="staffContainerEdit">
    @foreach ($program->staff as $staff)
    <div class="row staff-row mt-3">
        <div class="col-lg-5 form-group">
            <div class="select2-orange">
                <select class="form-control select2 staff-select" name="staff[]" data-selected="{{ $staff->id }}">
                    <option value="{{ $staff->id }}" selected>{{ $staff->nama }}</option>
                </select>
            </div>
        </div>
        <div class="col-lg-5 form-group">
            <div class="select2-orange">
                <select class="form-control select2 peran-select" name="peran[]" data-selected="{{ $staff->pivot->peran_id }}">
                    <option value="{{ $staff->pivot->peran_id }}" selected>{{ $staff->pivot->peran->nama }}</option>
                </select>
            </div>
        </div>
        <div class="col-lg-2 form-group">
            <button type="button" class="btn btn-danger remove-staff-row btn-flat">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </div>
    @endforeach
</div>

{{-- @push('js')
<script>
$(document).ready(function () {
    // Staff and Peran Select2 Initialization
    function initStaffSelect2(selectElement) {
        var data_staff = "{{ route('api.program.staff') }}";
        var placeholder = "{{  __('cruds.program.staff.select') }}";

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
    }

    function initPeranSelect2(selectElement) {
        var data_peran = "{{ route('api.program.peran') }}";
        var placeholder = "{{ __('cruds.program.staff.sel_peran') }}";

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
    }

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
@endpush --}}
