@extends('layouts.app')

@section('subtitle', __('cruds.beneficiary.edit'))
@section('content_header_title') <strong>{{ __('cruds.beneficiary.edit') }}</strong>  @endsection
@section('sub_breadcumb')<a href="{{ route('beneficiary.index') }}" title="{{ __('cruds.beneficiary.list') }}"> {{ __('cruds.beneficiary.list') }} </a> @endsection
@section('sub_sub_breadcumb') / <span title="Current Page {{ __('cruds.beneficiary.edit') }}">{{ __('cruds.beneficiary.edit') }}</span> @endsection

@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">{{ __('global.loading') }}...</h4>
@endsection

@section('content_body')
    <form id="editBeneficiary" method="POST" class="needs-validation" data-toggle="validator" autocomplete="off" enctype="multipart/form-data">
        @csrf
        @method('POST')
        <div class="row">
            <div class="col-12 col-sm-12">
                <div class="card card-primary card-tabs">
                    <div class="card-header border-bottom-0 card-header p-0 pt-1 navigasi">
                        {{-- <button type="button" class="btn btn-danger float-right" id="SimpanFormMeals">{{ __('global.save') }}</button> --}}
                        <ul class="nav nav-tabs" id="details-kegiatan-tab" role="tablist">
                            <button type="button" class="btn btn-tool btn-small" data-card-widget="collapse" title="Minimize">
                                <i class="bi bi-arrows-collapse"></i>
                            </button>
                            <li class="nav-item">
                                <a class="nav-link active" id="beneficiaries-tab" data-toggle="pill" href="#tab-beneficiaries" role="tab" aria-controls="tab-beneficiaries" aria-selected="true">
                                    {{ __('cruds.beneficiary.penerima.label') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="details-kegiatan-tabContent">
                            <div class="tab-pane fade show active" id="tab-beneficiaries" role="tabpanel" aria-labelledby="beneficiaries-tab">
                                @include('tr.beneficiary.tabs.beneficiaries-edit')
                            </div>
                            <div class="tab-pane fade" id="description-tab" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                                {{-- @include('tr.kegiatan.tabs.description') --}}
                            </div>
                            <div class="tab-pane fade" id="tab-hasil" role="tabpanel" aria-labelledby="tab-hasil">
                                {{-- @include('tr.kegiatan.tabs.hasil') --}}
                            </div>

                            <div class="tab-pane fade" id="tab-file" role="tabpanel" aria-labelledby="tab-file">
                                {{-- @include('tr.kegiatan.tabs.file-uploads') --}}
                            </div>
                            <div class="tab-pane fade" id="tab-penulis" role="tabpanel" aria-labelledby="tab-penulis">
                                {{-- @include('tr.kegiatan.tabs.penulis') --}}
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        @stack('next-button')
                    </div>

                </div>
            </div>
        </div>
    </form>
@stop

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/krajee-fileinput/css/fileinput.min.css') }}">
    <style>
        .card-header.border-bottom-0.card-header.p-0.pt-1.navigasi {
            position: sticky;
            z-index: 1045;
            top: 0;
        }
        .wah {
            display: grid;
            align-content: space-around;
            justify-content: center;
            align-items: center;
            justify-items: stretch;
        }

        .select2-container--open .select2-dropdown {
            top: 100% !important; /* Force dropdown to appear below */
            bottom: auto !important;
        }

        .modal {
            overflow: visible !important; /* Ensure modal doesn’t clip content */
        }

        .modal-dialog {
            overflow: visible !important; /* Allow dropdown to extend outside dialog */
        }

        .modal-content {
            overflow: visible !important; /* Prevent content from hiding dropdown */
        }

        .select2-container--open .select2-dropdown {
            z-index: 1056; /* Match or exceed modal z-index (Bootstrap default is 1050) */
        }

                /* Sorting indicators */
        th.asc::after {
            content: ' ↑';
            color: #333;
        }

        th.desc::after {
            content: ' ↓';
            color: #333;
        }

        .responsive-table {
            overflow-x: visible;
            overflow-y: visible;
        }

        .ellipsis-cell {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px; /* Adjust as needed */
            /* display: block; Or display: block */
        }
    </style>
@endpush

@push('js')
@section('plugins.Sweetalert2', true)
@section('plugins.DatatablesNew', true)
@section('plugins.Select2', true)
@section('plugins.Toastr', true)
@section('plugins.Validation', true)

<script>
    const activities = @json($activities);
    $(window).on('resize', function() {
        $('.select2-container--open').each(function() {
            const $select = $(this).prev('select');
            $select.select2('close');
            $select.select2('open');
        });
    });

    function escapeHtml(str) {
        if (!str) return ""; // Handle null/undefined cases
        return str
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function updateActivityHeaders(activities) {
        if (activities.length > 0) {
            const activityHeaders = activities.map(activity => `
                <th class="align-middle text-center activity-header" data-activity-id="${activity.id}">${activity.kode}</th>
            `).join('');
            $('#activityHeaders').html(`
                <th colspan="1" class="align-middle text-center">{{ __("cruds.beneficiary.penerima.rt") }}</th>
                <th colspan="1" class="align-middle text-center">{{ __("cruds.beneficiary.penerima.rw") }}</th>
                <th colspan="1" class="align-middle text-center">{{ __("cruds.beneficiary.penerima.dusun") }}  <sup><i class="fas fa-question-circle" title="{{ __("cruds.beneficiary.penerima.banjar") }}" data-placement="top"></i></sup></th>
                <th colspan="1" class="align-middle text-center">{{ __("cruds.beneficiary.penerima.desa") }}</th>
                <th colspan="1" class="align-middle text-center bg-cyan" title="{{ __('cruds.kegiatan.peserta.anak') }}">0-17</th>
                <th colspan="1" class="align-middle text-center bg-teal" title="{{ __('cruds.kegiatan.peserta.remaja') }}">18-24</th>
                <th colspan="1" class="align-middle text-center bg-yellow" title="{{ __('cruds.kegiatan.peserta.dewasa') }}">25-59</th>
                <th colspan="1" class="align-middle text-center bg-pink" title="{{ __('cruds.kegiatan.peserta.lansia') }}"> > 60 </th>
                ${activityHeaders}
            `);
            $('#headerActivityProgram').attr('rowspan', 1).attr('colspan', activities.length);
        } else {
            $('#activityHeaders').html(`
                <th colspan="1" class="align-middle text-center">{{ __("cruds.beneficiary.penerima.rt") }}</th>
                <th colspan="1" class="align-middle text-center">{{ __("cruds.beneficiary.penerima.rw") }}</th>
                <th colspan="1" class="align-middle text-center">{{ __("cruds.beneficiary.penerima.dusun") }} <sup><i class="fas fa-question-circle" title="{{ __("cruds.beneficiary.penerima.banjar") }}" data-placement="top"></i></sup></th>
                <th colspan="1" class="align-middle text-center">{{ __("cruds.beneficiary.penerima.desa") }}</th>
                <th colspan="1" class="align-middle text-center bg-cyan" title="{{ __('cruds.kegiatan.peserta.anak') }}">0-17</th>
                <th colspan="1" class="align-middle text-center bg-teal" title="{{ __('cruds.kegiatan.peserta.remaja') }}">18-24</th>
                <th colspan="1" class="align-middle text-center bg-yellow" title="{{ __('cruds.kegiatan.peserta.dewasa') }}">25-59</th>
                <th colspan="1" class="align-middle text-center bg-pink" title="{{ __('cruds.kegiatan.peserta.lansia') }}"> > 60 </th>
            `);
            $('#headerActivityProgram').attr('rowspan', 2);
        }
    }

    function populateActivitySelect(activities, selectElement) {
        activities.forEach(activity => {
            const option = new Option(activity.kode, activity.id, false, false);
            option.setAttribute('title', activity.nama);
            option.setAttribute('data-nama', activity.nama);
            selectElement.append(option);
        });
        selectElement.select2({
            placeholder: "{{ __('cruds.beneficiary.select_activity') }}",
            width: "100%",
            allowClear: true
        });
    }

    function loadActivity() {
        populateActivitySelect(activities, $("#activitySelect"));
        populateActivitySelect(activities, $("#activitySelectEdit"));
        updateActivityHeaders(activities);
    }
    function buatSelect2(elementId, dropdownParentId, placeholder, url) {
        $(elementId).select2({
            placeholder: placeholder,
            dropdownParent: $(dropdownParentId),
            width: "100%",
            allowClear: true,
            multiple: true,
            ajax: {
                url: url, // Use the dynamic URL passed as a parameter
                dataType: 'json',
                delay: 250,
                data: (params) => ({
                    search: params.term,
                    page: params.page || 1
                }),
                processResults: (data) => ({
                    results: data.results,
                    pagination: { more: data.pagination.more }
                }),
                cache: true,
                error: (xhr, status, error) => {
                    console.error(`Error fetching data for ${elementId}:`, error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to fetch data. Please try again later.'
                    });
                    return [];
                }
            }
        });
    }

    function loadKelompokMarjinal() {
        const placeholder = "{{ __('cruds.beneficiary.penerima.sel_rentan') }}";
        const apiRoute = '{{ route('api.beneficiary.kelompok.rentan') }}';
        buatSelect2('#kelompok_rentan', '#ModalTambahPeserta', placeholder, apiRoute);
        buatSelect2('#editKelompokRentan', '#editDataModal', placeholder, apiRoute);
    }

    function loadJenisKelompok() {
        let placeholder = '{{ __('global.pleaseSelect') . ' ' . __('cruds.beneficiary.penerima.jenis_kelompok') }}';
        const apiRoute = '{{ route('api.jenis.kelompok') }}';
        buatSelect2('#jenis_kelompok', '#ModalTambahPeserta', placeholder, apiRoute);
        buatSelect2('#editJenisKelompok', '#editDataModal', placeholder, apiRoute);
    }


    function setLocationForm(provinsiSelector, kabupatenSelector, kecamatanSelector, desaSelector, dusunSelector, dropdownParent) {
        if (!$(provinsiSelector).val()) {
            $(kabupatenSelector).prop('disabled', true);
            $(kecamatanSelector).prop('disabled', true);
            $(desaSelector).prop('disabled', true);
            $(dusunSelector).prop('disabled', true);
        }
        $(provinsiSelector).select2({
            ajax: {
                url: "{{ route('api.beneficiary.provinsi') }}",
                dataType: 'json',
                delay: 250,
                data: params => ({
                    search: params.term,
                    page: params.page || 1
                }),
                processResults: data => ({
                    results: data.results,
                    pagination: data.pagination
                }),
                cache: true
            },
            dropdownParent: dropdownParent,
            placeholder: "{{ __('global.selectProv') }}"
        }).on('change', function() {
            $(kabupatenSelector).val(null).trigger('change').prop('disabled', !$(this).val());
            $(kecamatanSelector).val(null).trigger('change').prop('disabled', true);
            $(desaSelector).val(null).trigger('change').prop('disabled', true);
            $(dusunSelector).val(null).trigger('change').prop('disabled', true);
        });

        $(kabupatenSelector).select2({
            ajax: {
                url: () => "{{ route('api.beneficiary.kab', ['id' => ':id']) }}".replace(':id', $(provinsiSelector).val()),
                dataType: 'json',
                delay: 250,
                data: params => ({
                    provinsi_id: $(provinsiSelector).val(),
                    search: params.term,
                    page: params.page || 1
                }),
                processResults: data => ({
                    results: data.results,
                    pagination: data.pagination
                })
            },
            dropdownParent: dropdownParent
        }).on('change', function() {
            $(kecamatanSelector).val(null).trigger('change').prop('disabled', !$(this).val());
            $(desaSelector).val(null).trigger('change').prop('disabled', true);
            $(dusunSelector).val(null).trigger('change').prop('disabled', true);
        });

        $(kecamatanSelector).select2({
            ajax: {
                url: () => "{{ route('api.beneficiary.kec', ['id' => ':id']) }}".replace(':id', $(kabupatenSelector).val()),
                dataType: 'json',
                delay: 250,
                data: params => ({
                    kabupaten_id: $(kabupatenSelector).val(),
                    search: params.term,
                    page: params.page || 1
                }),
                processResults: data => ({
                    results: data.results,
                    pagination: data.pagination
                })
            },
            dropdownParent: dropdownParent
        }).on('change', function() {
            $(desaSelector).val(null).trigger('change').prop('disabled', !$(this).val());
            $(dusunSelector).val(null).trigger('change').prop('disabled', true);
        });

        $(desaSelector).select2({
            ajax: {
                url: () => "{{ route('api.beneficiary.desa', ['id' => ':id']) }}".replace(':id', $(kecamatanSelector).val()),
                dataType: 'json',
                delay: 250,
                data: params => ({
                    kecamatan_id: $(kecamatanSelector).val(),
                    search: params.term,
                    page: params.page || 1
                }),
                processResults: data => ({
                    results: data.results,
                    pagination: data.pagination
                })
            },
            dropdownParent: dropdownParent
        }).on('change', function() {
            $(dusunSelector).val(null).trigger('change').prop('disabled', !$(this).val());
        });

        $(dusunSelector).select2({
            ajax: {
                url: () => "{{ route('api.beneficiary.dusun', ['id' => ':id']) }}".replace(':id', $(desaSelector).val()),
                dataType: 'json',
                delay: 250,
                data: params => ({
                    desa_id: $(desaSelector).val(),
                    search: params.term,
                    page: params.page || 1
                }),
                processResults: data => ({
                    results: data.results,
                    pagination: data.pagination
                })
            },
            dropdownParent: dropdownParent
        });
    }

    $(document).ready(function() {
        // $('#dataTable').DataTable({
        //     "paging": true,
        //     "lengthChange": false,
        //     "searching": true,
        //     "ordering": true,
        //     "info": true,
        //     "autoWidth": false,
        // });
        const csrfToken = $('meta[name="csrf-token"]').attr("content");
        $.ajaxSetup({ headers: { "X-CSRF-TOKEN": csrfToken } });
        const beneficiaries = @json($beneficiaries);
        let rowCount = beneficiaries.length;

        loadJenisKelompok();
        loadKelompokMarjinal();
        setLocationForm();
        // loadActivity();

        setLocationForm(
            '#provinsi_id_tambah',
            '#kabupaten_id_tambah',
            '#kecamatan_id_tambah',
            '#desa_id_tambah',
            '#dusun_id_tambah',
            $('#ModalTambahPeserta')
        );
        setLocationForm(
            '#provinsi_id_edit',
            '#kabupaten_id_edit',
            '#kecamatan_id_edit',
            '#desa_id_edit',
            '#dusun_id_edit',
            $('#editDataModal')
        );

        function addRow() {
            const form = document.getElementById("dataForm");
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const formData = $("#dataForm").serializeArray().reduce((obj, item) => {
                obj[item.name] = item.value;
                return obj;
            }, {});
            formData.program_id = '{{ $program->id }}';
            formData.user_id = '{{ Auth::user()->id }}';
            formData.dusun_id = $("#dusun_id_tambah").val();
            formData.desa_id = $("#desa_id_tambah").val();

            formData.nama = $("#nama_beneficiary").val();
            formData.umur = $("#umur").val();
            formData.no_telp = $("#no_telp").val();
            formData.jenis_kelamin = $("#jenis_kelamin").val();
            formData.rt = $("#rt").val();
            formData.rw = $("#rw").val();
            formData.kelompok_rentan = $("#kelompok_rentan").val() || [];
            formData.jenis_kelompok = $("#jenis_kelompok").val() || [];
            formData.keterangan = $("#keterangan").val();

            formData.activity_ids = $("#activitySelect").val() || [];
            formData.is_non_activity = $("#is_non_activity").is(":checked");

            $.ajax({
                url: '{{ route('beneficiary.store.individual') }}',
                method: 'POST',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                success: function(response) {
                    redrawTable();
                    $("dataForm")[0].reset();
                    $("#ModalTambahPeserta").modal("hide");
                    Swal.fire({
                        title: "Success",
                        text: "Beneficiary added!",
                        icon: "success",
                        timer: 1500,
                        showConfirmButton: false,
                        timerProgressBar: true,
                    });
                },
                error: function(xhr) {
                    Swal.fire("Error", xhr.responseJSON?.message || "Failed to add beneficiary.", "error");
                }
            });
        }

        function redrawTable() {
            $.ajax({
                url: '{{ route('beneficiary.edit', $program->id) }}', // Assumes this returns the edit view with updated table
                method: 'GET',
                success: function(response) {
                    const newTableBody = $(response).find('#tableBody').html(); // Extract the updated table body
                    $('#tableBody').html(newTableBody); // Replace the existing table body
                    rowCount = $('#tableBody tr').length; // Update row count
                },
                error: function(xhr) {
                    Swal.fire("Error", "Failed to reload table data.", "error");
                }
            });
        }
        function editRow(row) {
            const beneficiaryId = $(row).data('id');
            const beneficiary = beneficiaries.find(b => b.id === beneficiaryId);
            if (!beneficiary) {
                console.error("Beneficiary not found:", beneficiaryId);
                return;
            }

            $("#editRowId").val(beneficiaryId);
            $("#editNama").val(beneficiary.nama);
            $("#editNoTelp").val(beneficiary.no_telp);
            $("#editGender").val(beneficiary.jenis_kelamin).trigger("change");
            $("#editUsia").val(beneficiary.umur);
            $("#editRt").val(beneficiary.rt);
            $("#editRwBanjar").val(beneficiary.rw);
            $("#edit_is_non_activity").prop("checked", beneficiary.is_non_activity);
            $("#keterangan_edit").val(beneficiary.keterangan);

            const addOptionAndTriggerChange = (selector, text, value) => {
                const option = new Option(text || '-', value || '', true, true);
                $(selector).empty().append(option).trigger('change');
            };
            addOptionAndTriggerChange("#provinsi_id_edit", beneficiary.provinsi_nama || '-', beneficiary.provinsi_id || '');
            addOptionAndTriggerChange("#kabupaten_id_edit", beneficiary.kabupaten_nama || '-', beneficiary.kabupaten_id || '');
            addOptionAndTriggerChange("#kecamatan_id_edit", beneficiary.kecamatan_nama || '-', beneficiary.kecamatan_id || '');
            addOptionAndTriggerChange("#desa_id_edit", beneficiary.dusun?.desa?.nama || '-', beneficiary.dusun?.desa_id || '');
            addOptionAndTriggerChange("#dusun_id_edit", beneficiary.dusun?.nama || '-', beneficiary.dusun_id || '');

            $("#editKelompokRentan").empty();
            beneficiary.kelompok_marjinal.forEach(k => {
                $("#editKelompokRentan").append(new Option(k.nama, k.id, true, true));
            });
            $("#editKelompokRentan").val(beneficiary.kelompok_marjinal.map(k => k.id)).trigger("change");

            $("#editJenisKelompok").empty();
            beneficiary.jenis_kelompok.forEach(j => {
                $("#editJenisKelompok").append(new Option(j.nama, j.id, true, true));
            });
            $("#editJenisKelompok").val(beneficiary.jenis_kelompok.map(j => j.id)).trigger("change");

            $("#activitySelectEdit").val(beneficiary.penerima_activity.map(a => a.id.toString())).trigger("change");

            $("#editDataModal").modal("show");
        }

        function updateRow() {
            const form = document.getElementById("editDataForm");
            const beneficiaryId = $("#editRowId").val();
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const formData = $("#editDataForm").serializeArray().reduce((obj, item) => {
                if (obj[item.name]) {
                    if (!Array.isArray(obj[item.name])) obj[item.name] = [obj[item.name]];
                    obj[item.name].push(item.value);
                } else {
                    obj[item.name] = item.value;
                }
                return obj;
            }, {});
            formData.is_non_activity = $("#edit_is_non_activity").is(":checked");
            formData.kelompok_rentan = $("#editKelompokRentan").val() || [];
            formData.jenis_kelompok = $("#editJenisKelompok").val() || [];
            formData.activity_ids = $("#activitySelectEdit").val() || [];
            formData.program_id = '{{ $program->id }}';

            const id = beneficiaryId;
            const url = "{{ route('beneficiary.edit.individual', ':id') }}".replace(':id', id);

            $.ajax({
                url: url,
                method: "PUT",
                data: JSON.stringify(formData),
                contentType: "application/json",
                success: function(response) {
                    $("#editDataModal").modal("hide");
                    Swal.fire("Success", "Beneficiary updated!", "success").then(() => {
                        redrawTable();
                    });
                },
                error: function(xhr) {
                    Swal.fire("Error", xhr.responseJSON?.message || "Failed to update beneficiary.", "error");
                }
            });
        }

        function deleteRow(row) {
            const beneficiaryId = $(row).data('id');
            Swal.fire({
                title: "Are you sure?",
                text: "This action cannot be undone!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ route('beneficiary.delete.individual', '') }}/${beneficiaryId}`,
                        method: "DELETE",
                        success: function() {
                            Swal.fire("Deleted!", "Beneficiary removed.", "success").then(() => {
                                redrawTable();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire("Error", xhr.responseJSON?.message || "Failed to delete.", "error");
                        }
                    });
                }
            });
        }

        $("#saveDataBtn").on("click", function(e) {
            e.preventDefault();
            // alert("saveDataBtn clicked");
            addRow();
        });

        $("#dataTable tbody").on("click", ".edit-btn", function(e) {
            e.preventDefault();
            // editRow(this);
        });

        $("#updateDataBtn").on("click", function(e) {
            e.preventDefault();
            updateRow();
        });

        $("#dataTable tbody").on("click", ".delete-btn", function(e) {
            e.preventDefault();
            deleteRow(this);
        });
    });


</script>


{{-- @include('tr.beneficiary.js.create') --}}
@include('tr.beneficiary.js.search')

@stack('basic_tab_js')

{{-- @include('tr.beneficiary.js.beneficiaries') --}}
{{-- @include('tr.beneficiary.js.program') --}}

{{-- @include('tr.beneficiary.tabs.program') --}}
@include('tr.beneficiary.tabs.bene-modal')

@include('api.master.dusun')
@include('api.master.jenis-kelompok-instansi')

@endpush
