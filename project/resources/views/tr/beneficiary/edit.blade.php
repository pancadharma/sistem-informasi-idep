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
                <th colspan="1" class="align-middle text-center">{{ __("cruds.beneficiary.penerima.dusun") }}  <sup><i class="fas fa-question-circle"  title="{{ __("cruds.beneficiary.penerima.banjar") }}" data-placement="top"></i></sup></th>
                <th colspan="1" class="align-middle text-center">{{ __("cruds.beneficiary.penerima.desa") }}</th>
                <th colspan="1" class="align-middle text-center bg-cyan" title="{{ __('cruds.kegiatan.peserta.anak') }}">0-17</th>
                <th colspan="1" class="align-middle text-center bg-teal" title="{{ __('cruds.kegiatan.peserta.remaja') }}">18-24</th>
                <th colspan="1" class="align-middle text-center bg-yellow" title="{{ __('cruds.kegiatan.peserta.dewasa') }}">25-59</th>
                <th colspan="1" class="align-middle text-center bg-pink" title="{{ __('cruds.kegiatan.peserta.lansia') }}"> > 60 </th>
                ${activityHeaders}
            `);

            $('#headerActivityProgram').attr('rowspan', 1);
            $('#headerActivityProgram').attr('colspan', activities.length);

            // $('#dataTable').DataTable().destroy(); // Destroy existing instance
            // $('#dataTable').DataTable({
            //     "paging": true,
            //     "lengthChange": true,
            //     "searching": true,
            //     "ordering": true,
            //     "info": true,
            //     "autoWidth": false,
            //     "responsive": true,
            // });

        } else {
            $('#activityHeaders').html(`
                <th colspan="1" class="align-middle text-center">{{ __("cruds.beneficiary.penerima.rt") }}</th>
                <th colspan="1" class="align-middle text-center">{{ __("cruds.beneficiary.penerima.rw") }}</th>
                <th colspan="1" class="align-middle text-center">{{ __("cruds.beneficiary.penerima.dusun") }} <sup><i class="fas fa-question-circle"  title="{{ __("cruds.beneficiary.penerima.banjar") }}" data-placement="top"></i></sup></th>
                <th colspan="1" class="align-middle text-center">{{ __("cruds.beneficiary.penerima.desa") }}</th>
                <th colspan="1" class="align-middle text-center bg-cyan" title="{{ __('cruds.kegiatan.peserta.anak') }}">0-17</th>
                <th colspan="1" class="align-middle text-center bg-teal" title="{{ __('cruds.kegiatan.peserta.remaja') }}">18-24</th>
                <th colspan="1" class="align-middle text-center bg-yellow" title="{{ __('cruds.kegiatan.peserta.dewasa') }}">25-59</th>
                <th colspan="1" class="align-middle text-center bg-pink" title="{{ __('cruds.kegiatan.peserta.lansia') }}"> > 60 </th>
            `);

            $('#headerActivityProgram').attr('rowspan', 2);

            $('#dataTable').DataTable().destroy(); // Destroy existing instance
            $('#dataTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });

        }
    }
    function populateActivitySelect(activities, selectElement) {
        selectElement.empty().append('Pilih Activity');
        activities.forEach(activity => {
            const option = new Option(activity.kode, activity.id, false, false);
            option.setAttribute('title', activity.nama);
            selectElement.append(option);
        });
        selectElement.select2();
    }
    function loadJenisKelompok(){
        let placeholder = '{{ __('global.pleaseSelect') . ' ' . __('cruds.beneficiary.penerima.jenis_kelompok') }}';
        $("#jenis_kelompok").select2({
            placeholder: placeholder,
            ajax: {
                url: '{{ route('api.jenis.kelompok') }}',
                dataType: "json",
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                        page: params.page || 1,
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.results,
                        pagination: {
                            more: data.pagination.more,
                        },
                    };
                },
                cache: true,
            },
            dropdownParent: $("#ModalTambahPeserta"),
            width: "100%",
        });
        $("#editJenisKelompok").select2({
            placeholder: placeholder,
            ajax: {
                url: '{{ route('api.jenis.kelompok') }}',
                dataType: "json",
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                        page: params.page || 1,
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.results,
                        pagination: {
                            more: data.pagination.more,
                        },
                    };
                },
                cache: true,
            },
            dropdownParent: $("#editDataModal"),
            width: "100%",
        });


    }
    function loadKelompokMarjinal() {
        $("#kelompok_rentan").select2({
            placeholder: "{{ __('cruds.beneficiary.penerima.sel_rentan') }} ...",
            dropdownParent: $("#ModalTambahPeserta"),
            width: "100%",
            allowClear: true,
            ajax: {
                url: '{{ route('api.beneficiary.kelompok.rentan') }}',
                dataType: "json",
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                        page: params.page || 1,
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.results,
                        pagination: {
                            more: data.pagination.more,
                        },
                    };
                },
                cache: true,
            },
        });

        $("#editKelompokRentan").select2({
            placeholder: "{{ __('cruds.beneficiary.penerima.sel_rentan') }} ...",
            dropdownParent: $("#editDataModal"),
            width: "100%",
            allowClear: true,
            ajax: {
                url: '{{ route('api.beneficiary.kelompok.rentan') }}',
                dataType: "json",
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                        page: params.page || 1,
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.results,
                        pagination: {
                            more: data.pagination.more,
                        },
                    };
                },
                cache: true,
            },
        });
    }

    function setLocationForm(provinsiSelector, kabupatenSelector, kecamatanSelector, desaSelector, dusunSelector, dropdownParent) {
        // Initialize Provinsi dropdown
        if($(provinsiSelector).val() == null || $(provinsiSelector).val() == ''){
            $(kabupatenSelector).prop('disabled', true);
            $(kecamatanSelector).prop('disabled', true);
            $(desaSelector).prop('disabled', true);
            $(dusunSelector).prop('disabled', true);
        }
        $(provinsiSelector).select2({
            ajax: {
                placeholder: "{{ __('global.selectProv') }}",
                url: "{{ route('api.beneficiary.provinsi') }}",
                dataType: 'json',
                delay: 250,
                beforeSend: function() {
                   Toast.fire({
                        icon: "info",
                        position: "top-right",
                        title: "{{ __('global.loading') }} " + "{{ __('cruds.data.data') }} " + "{{ __('cruds.provinsi.title') }}",
                        timer: 500,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });
                },
                complete: function() {
                    // $(kabupatenSelector).prop('disabled', false);
                    Toast.close();
                },
                data: function(params) {
                    return { search: params.term, page: params.page || 1 };
                },
                processResults: function(data) {
                    return { results: data.results, pagination: data.pagination };
                }
            },
            cache: true,
            dropdownParent: dropdownParent
        }).on('change', function() {
            if ($(this).val()) {
                // If a province is selected (value exists), enable kabupatenSelector
                $(kabupatenSelector).prop('disabled', false);
                $(kecamatanSelector).prop('disabled', true).val(null).trigger('change');
                $(desaSelector).prop('disabled', true).val(null).trigger('change');
                $(dusunSelector).prop('disabled', true).val(null).trigger('change');
            } else {
                // If no province is selected (value is null/undefined/empty), disable kabupatenSelector
                $(kabupatenSelector).prop('disabled', true).val(null).trigger('change');
            }
        });

        $(kabupatenSelector).select2({
            ajax: {
                url: function() {
                    return "{{ route('api.beneficiary.kab', ['id' => ':id']) }}".replace(':id', $(provinsiSelector).val());
                },
                dataType: 'json',
                delay: 250,
                beforeSend: function() {
                    Toast.fire({
                        icon: "info",
                        position: "top-right",
                        title: "{{ __('global.loading') }} " + "{{ __('cruds.data.data') }} " + "{{ __('cruds.kabupaten.title') }}",
                        timer: 500,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });
                },
                complete: function() {
                    Toast.close();
                },
                data: function(params) {
                    return {
                        provinsi_id: $(provinsiSelector).val(),
                        search: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function(data) {
                    return { results: data.results, pagination: data.pagination };
                }
            },
            dropdownParent: dropdownParent
        }).on('change', function() {
            if ($(this).val()) {
                $(kecamatanSelector).prop('disabled', false);
                $(desaSelector).prop('disabled', true).val(null).trigger('change');
                $(dusunSelector).prop('disabled', true).val(null).trigger('change');
            } else {
                $(kecamatanSelector).prop('disabled', true).val(null).trigger('change');
            }
        });

        $(kecamatanSelector).select2({
            ajax: {
                url: function() {
                    return "{{ route('api.beneficiary.kec', ['id' => ':id']) }}".replace(':id', $(kabupatenSelector).val());
                },
                dataType: 'json',
                delay: 250,
                beforeSend: function() {
                    Toast.fire({
                        icon: "info",
                        position: "top-right",
                        title: "{{ __('global.loading') }} " + "{{ __('cruds.data.data') }} " + "{{ __('cruds.kecamatan.title') }}",
                        timer: 500,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });
                },
                complete: function() {
                    Toast.close();
                },
                data: function(params) {
                    return {
                        kabupaten_id: $(kabupatenSelector).val(),
                        search: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function(data) {
                    return { results: data.results, pagination: data.pagination };
                }
            },
            dropdownParent: dropdownParent
        }).on('change', function() {
            if ($(this).val()) {
                $(desaSelector).prop('disabled', false).val(null).trigger('change');
                $(dusunSelector).prop('disabled', true).val(null).trigger('change');
            } else {
                $(desaSelector).prop('disabled', true).val(null).trigger('change');
            }
        });

        $(desaSelector).select2({
            ajax: {
                url: function() {
                    return "{{ route('api.beneficiary.desa', ['id' => ':id']) }}".replace(':id', $(kecamatanSelector).val());
                },
                dataType: 'json',
                delay: 250,
                beforeSend: function() {
                    Toast.fire({
                        icon: "info",
                        position: "top-right",
                        title: "{{ __('global.loading') }} " + "{{ __('cruds.data.data') }} " + "{{ __('cruds.desa.title') }}",
                        timer: 500,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });
                },
                complete: function() {
                    Toast.close();
                },
                data: function(params) {
                    return {
                        search: params.term,
                        kecamatan_id: $(kecamatanSelector).val(),
                        page: params.page || 1
                    };
                },
                processResults: function(data) {
                    return { results: data.results, pagination: data.pagination };
                }
            },
            dropdownParent: dropdownParent
        }).on('change', function() {
            if ($(this).val()) {
                $(dusunSelector).prop('disabled', false);
            } else {
                $(dusunSelector).prop('disabled', true).val(null).trigger('change');
            }
        });

        $(dusunSelector).select2({
            ajax: {
                url: function() {
                    return "{{ route('api.beneficiary.dusun', ['id' => ':id']) }}".replace(':id', $(desaSelector).val());
                },
                dataType: 'json',
                delay: 250,
                beforeSend: function() {
                    Toast.fire({
                        icon: "info",
                        position: "top-right",
                        title: "{{ __('global.loading') }} " + "{{ __('cruds.data.data') }} " + "{{ __('cruds.desa.title') }}",
                        timer: 500,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });
                },
                complete: function() {
                    Toast.close();
                },
                data: function(params) {
                    return {
                        search: params.term,
                        desa_id: $(desaSelector).val(),
                        // desa_id: $("#desa_id").val() || $("#editDesa").val(),
                        page: params.page || 1
                    };
                },
                processResults: function(data) {
                    return { results: data.results, pagination: data.pagination };
                }
            },
            dropdownParent: dropdownParent
        }).on('change', function() {
            if ($(this).val()) {
                $(dusunSelector).prop('disabled', false);
            }
        });

        // Clear dependent dropdowns when parent changes
        $(provinsiSelector).on('change', function() {
            $(kabupatenSelector).val(null).trigger('change');
            $(kecamatanSelector).val(null).trigger('change');
            $(desaSelector).val(null).trigger('change');
            $(dusunSelector).val(null).trigger('change');
        });

        $(kabupatenSelector).on('change', function() {
            $(kecamatanSelector).val(null).trigger('change');
            $(desaSelector).val(null).trigger('change');
            $(dusunSelector).val(null).trigger('change');
        });

        $(kecamatanSelector).on('change', function() {
            $(desaSelector).val(null).trigger('change');
            $(dusunSelector).val(null).trigger('change');
        });

        $(desaSelector).on('change', function() {
            $(dusunSelector).val(null).trigger('change');
        });
    }

    // load function on document ready
    $(document).ready(function() {
        const csrfToken = $('meta[name="csrf-token"]').attr("content");
        if (!csrfToken) {
            console.error("CSRF token not found.");
            return;
        }
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
        });

        loadJenisKelompok();
        loadKelompokMarjinal();
        setLocationForm();

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

        // Fetch activities for the selected program on edit pages
        const id = {{ $program->id }};
        const url = "{{ route('api.program.activity', ':id') }}".replace(':id', id);

        fetch(url).then(response => response.json()).then(activities => {
            populateActivitySelect(activities, $("#activitySelect"));
            populateActivitySelect(activities, $("#activitySelectEdit"));
            updateActivityHeaders(activities);

        }).catch(error => console.error('Error fetching activities:', error));


        $("#activitySelect").select2({
            width: "100%",
            dropdownParent: $("#ModalTambahPeserta"),
        });

        $("#activitySelectEdit").select2({
            width: "100%",
            dropdownParent: $("#editDataModal"),
        });
    });

    $(document).ready(function() {
        $(document).on('click', '.edit-btn', function(e) {
            e.preventDefault();
            editRow(this);
            // const beneficiaryId = $(this).data('id');
            // const row = $(`#dataTable tbody tr[data-id="${beneficiaryId}"]`);

            // $('#editRowId').val(beneficiaryId);
            // $('#editNama').val(row.find('td[data-nama]').attr('data-nama'));
            // $('#editNoTelp').val(row.find('td[data-no_telp]').attr('data-no_telp'));
            // $('#editUsia').val(row.find('td[data-usia]').attr('data-usia'));
            // $('#editGender').val(row.find('td[data-gender]').attr('data-gender'));
            // $('#editKelompokRentan').val(row.find('td[data-kelompok_rentan]').attr('data-kelompok_rentan') ? row.find('td[data-kelompok_rentan]').attr('data-kelompok_rentan').split(',') : []);
            // $('#editJenisKelompok').val(row.find('td[data-jenis_kelompok]').attr('data-jenis_kelompok') ? row.find('td[data-jenis_kelompok]').attr('data-jenis_kelompok').split(',') : []);
            // $('#editRt').val(row.find('td[data-rt]').attr('data-rt'));
            // $('#editRwBanjar').val(row.find('td[data-rw]').attr('data-rw'));
            // $('#dusun_id_edit').val(row.find('td[data-dusun-id]').attr('data-dusun-id'));
            // $('#edit_is_non_activity').prop('checked', row.find('td[data-is_non_activity]').attr('data-is_non_activity') === 'true');
            // const activityIds = [];
            // row.find('td[data-program-activity-id]').each(function() {
            //     if ($(this).text().trim() === '√') {
            //         activityIds.push($(this).attr('data-program-activity-id'));
            //     }
            // });
            // $('#activitySelectEdit').val(activityIds).trigger('change');
            // $('#keterangan_edit').val(row.find('td[data-keterangan]').attr('data-keterangan'));

            // $('#editDataModal').modal('show');
        });

        const activities = @json($activities);
        const beneficiaries = @json($beneficiaries);

        function editRow(row) {
            const beneficiaryId = $(row).data('id');
            const beneficiary = beneficiaries.find(b => b.id === beneficiaryId);
            if (!beneficiary) {
                console.error("Beneficiary not found:", beneficiaryId);
                return;
            }

            // Set basic fields
            $("#editRowId").val(beneficiaryId);
            $("#editNama").val(beneficiary.nama);
            $("#editNoTelp").val(beneficiary.no_telp);
            $("#editGender").val(beneficiary.jenis_kelamin).trigger("change");
            $("#editUsia").val(beneficiary.umur);
            $("#editRt").val(beneficiary.rt);
            $("#editRwBanjar").val(beneficiary.rw);
            $("#edit_is_non_activity").prop("checked", beneficiary.is_non_activity);
            $("#keterangan_edit").val(beneficiary.keterangan);

            // Pre-populate location fields (assuming these are stored in the database)
            const addOptionAndTriggerChange = (selector, text, value) => {
                return new Promise(resolve => {
                    const option = new Option(text || '-', value || '', true, true);
                    $(selector).append(option).trigger('change');
                    setTimeout(resolve, 100);
                });
            };
            addOptionAndTriggerChange("#provinsi_id_edit", beneficiary.provinsi_nama || '-', beneficiary.provinsi_id || '')
                .then(() => addOptionAndTriggerChange("#kabupaten_id_edit", beneficiary.kabupaten_nama || '-', beneficiary.kabupaten_id || ''))
                .then(() => addOptionAndTriggerChange("#kecamatan_id_edit", beneficiary.kecamatan_nama || '-', beneficiary.kecamatan_id || ''))
                .then(() => addOptionAndTriggerChange("#desa_id_edit", beneficiary.desa_id || '-', beneficiary.desa_id || ''))
                .then(() => addOptionAndTriggerChange("#dusun_id_edit", beneficiary.dusun_id || '-', beneficiary.dusun_id || ''));

            // Pre-populate kelompokMarjinal
            $("#editKelompokRentan").empty();
            const kelompokMarjinalData = beneficiary.kelompok_marjinal.map(k => ({ id: k.id, text: k.nama }));
            kelompokMarjinalData.forEach(item => {
                const option = new Option(item.text, item.id, true, true);
                $("#editKelompokRentan").append(option);
            });
            $("#editKelompokRentan").val(kelompokMarjinalData.map(k => k.id)).trigger("change");

            // Pre-populate jenisKelompok
            $("#editJenisKelompok").empty();
            const jenisKelompokData = beneficiary.jenis_kelompok.map(j => ({ id: j.id, text: j.nama }));
            jenisKelompokData.forEach(item => {
                const option = new Option(item.text, item.id, true, true);
                $("#editJenisKelompok").append(option);
            });
            $("#editJenisKelompok").val(jenisKelompokData.map(j => j.id)).trigger("change");

            // Pre-populate activities
            const selectedActivityIds = beneficiary.penerima_activity.map(a => a.id.toString());
            $("#activitySelectEdit").val(selectedActivityIds).trigger("change");

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

            $.ajax({
                url: `{{ route('beneficiary.update', '') }}/${beneficiaryId}`,
                method: "PUT",
                data: JSON.stringify(formData),
                contentType: "application/json",
                success: function(response) {
                    Swal.fire({
                        title: "Success",
                        text: "Beneficiary updated successfully!",
                        icon: "success",
                        timer: 1500,
                    }).then(() => {
                        location.reload(); // Reload to reflect changes
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        title: "Error",
                        text: xhr.responseJSON?.message || "Failed to update beneficiary.",
                        icon: "error",
                        timer: 3000,
                    });
                },
            });
        }

    });

</script>



{{-- @include('tr.beneficiary.js.create') --}}
@include('tr.beneficiary.js.search')

@stack('basic_tab_js')

{{-- @include('tr.beneficiary.js.beneficiaries') --}}
{{-- @include('tr.beneficiary.js.program') --}}

@include('tr.beneficiary.tabs.program')
@include('tr.beneficiary.tabs.bene-modal')

@include('api.master.dusun')
@include('api.master.jenis-kelompok-instansi')

@endpush
