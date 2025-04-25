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

        @media screen and (max-width: 768px) {
            .ellipsis-cell {
                max-width: 100px; /* Adjust for smaller screens */
            }

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
                <th colspan="1" class="align-middle text-center" title="{{ __("cruds.beneficiary.penerima.banjar") }}">{{ __("cruds.beneficiary.penerima.dusun") }}</th>
                <th colspan="1" class="align-middle text-center">{{ __("cruds.beneficiary.penerima.desa") }}</th>
                <th colspan="1" class="align-middle text-center bg-cyan" title="{{ __('cruds.kegiatan.peserta.anak') }}">0 - 17</th>
                <th colspan="1" class="align-middle text-center bg-teal" title="{{ __('cruds.kegiatan.peserta.remaja') }}">18 - 24</th>
                <th colspan="1" class="align-middle text-center bg-yellow" title="{{ __('cruds.kegiatan.peserta.dewasa') }}">25 - 59</th>
                <th colspan="1" class="align-middle text-center bg-pink" title="{{ __('cruds.kegiatan.peserta.lansia') }}"> >60 </th>
                ${activityHeaders}
            `);
            $('#headerActivityProgram').attr('rowspan', 1).attr('colspan', activities.length);
        } else {
            $('#activityHeaders').html(`
                <th colspan="1" class="align-middle text-center">{{ __("cruds.beneficiary.penerima.rt") }}</th>
                <th colspan="1" class="align-middle text-center">{{ __("cruds.beneficiary.penerima.rw") }}</th>
                <th colspan="1" class="align-middle text-center" title="{{ __("cruds.beneficiary.penerima.banjar") }}">{{ __("cruds.beneficiary.penerima.dusun") }}</th>
                <th colspan="1" class="align-middle text-center">{{ __("cruds.beneficiary.penerima.desa") }}</th>
                <th colspan="1" class="align-middle text-center bg-cyan" title="{{ __('cruds.kegiatan.peserta.anak') }}">0 - 17</th>
                <th colspan="1" class="align-middle text-center bg-teal" title="{{ __('cruds.kegiatan.peserta.remaja') }}">18 - 24</th>
                <th colspan="1" class="align-middle text-center bg-yellow" title="{{ __('cruds.kegiatan.peserta.dewasa') }}">25 - 59</th>
                <th colspan="1" class="align-middle text-center bg-pink" title="{{ __('cruds.kegiatan.peserta.lansia') }}"> >60 </th>
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
        // updateActivityHeaders(activities);
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
    function resetFormAdd() {
        $("#dataForm")[0].reset();
        $("#kelompok_rentan").val(null).trigger("change");
        $("#jenis_kelompok").val(null).trigger("change");
        $("#activitySelect").val(null).trigger("change");

        $("#provinsi_id_tambah").val(null).trigger("change");
        $("#kabupaten_id_tambah").val(null).trigger("change");
        $("#kecamatan_id_tambah").val(null).trigger("change");
        $("#desa_id_tambah").val(null).trigger("change");
        $("#dusun_id_tambah").val(null).trigger("change");

        $("#ModalTambahPeserta").modal("hide");
    }

    function resetFormEdit() {
        $("#editDataForm")[0].reset();
        $("#editKelompokRentan").val(null).trigger("change");
        $("#editJenisKelompok").val(null).trigger("change");
        $("#activitySelectEdit").val(null).trigger("change");

        $("#provinsi_id_edit").val(null).trigger("change");
        $("#kabupaten_id_edit").val(null).trigger("change");
        $("#kecamatan_id_edit").val(null).trigger("change");
        $("#desa_id_edit").val(null).trigger("change");
        $("#dusun_id_edit").val(null).trigger("change");

        $("#editDataModal").modal("hide");
    }

    $(document).ready(function() {
        $('#dataTable').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            layout: {
                topStart: {
                    buttons: [
                        {
                            text: '<i class="fas fa-print" title="Print Table Data"></i> <span class="d-none d-md-inline"></span>',
                            className: 'btn btn-secondary',
                            extend: 'print',
                            exportOptions: {
                                columns: ':not(:last-child)' // Exclude the last column
                            }
                        },
                        {
                            text: '<i class="fas fa-file-excel" title="Export to EXCEL"></i> <span class="d-none d-md-inline"></span>',
                            className: 'btn btn-success',
                            extend: 'excel',
                            exportOptions: {
                                columns: ':not(:last-child)' // Exclude the last column
                            }
                        },
                        {
                            text: '<i class="fas fa-file-pdf" title="Export to PDF"></i> <span class="d-none d-md-inline"></span>',
                            className: 'btn btn-danger',
                            extend: 'pdfHtml5',
                            exportOptions: {
                                columns: ':not(:last-child)', // Exclude the last column
                                rows: function (idx, data, node) {
                                    return true; // Include all rows
                                },
                                stripHtml: false,
                                format: {
                                    body: function (data, row, column, node) {
                                        // Handle checkboxes safely without using jQuery find
                                        if (data.indexOf('type="checkbox"') > -1) {
                                            // Simple string-based check for checked attribute
                                            return data.indexOf('checked') > -1 ? '✓' : '☐';
                                        }
                                        // Clean HTML but preserve important formatting
                                        return data.replace(/<(?!\s*br\s*\/?)[^>]+>/gi, '');
                                    },
                                    header: function (data, columnIdx) {
                                        // Clean HTML from headers but preserve text
                                        return data.replace(/<(?!\s*br\s*\/?)[^>]+>/gi, '');
                                    }
                                }
                            },
                            orientation: 'landscape',
                            pageSize: 'A3',
                            title: 'Table Export',
                            customize: function (doc) {
                                // Define available fonts - using standard pdf fonts for better compatibility
                                // Available built-in fonts in pdfmake: helvetica/times/courier
                                pdfMake.fonts = {
                                    Roboto: {
                                        normal: 'Roboto-Regular.ttf',
                                        bold: 'Roboto-Medium.ttf',
                                        italics: 'Roboto-Italic.ttf',
                                        bolditalics: 'Roboto-MediumItalic.ttf'
                                    },
                                    // Fallback to standard PDF fonts if custom ones not available
                                    times: {
                                        normal: 'Times-Roman',
                                        bold: 'Times-Bold',
                                        italics: 'Times-Italic',
                                        bolditalics: 'Times-BoldItalic'
                                    },
                                    courier: {
                                        normal: 'Courier',
                                        bold: 'Courier-Bold',
                                        italics: 'Courier-Oblique',
                                        bolditalics: 'Courier-BoldOblique'
                                    }
                                };
                                // Style headers like Bootstrap tables
                                doc.styles.tableHeader = {
                                    fontSize: 11,
                                    bold: true,
                                    color: '#212529',
                                    fillColor: '#f8f9fa',
                                    alignment: 'left'
                                };
                                doc.styles.tableBodyEven = {

                                    fontSize: 10,
                                    color: '#212529',
                                    alignment: 'left'
                                };

                                // Style odd rows for zebra striping
                                doc.styles.tableBodyOdd = {
                                    fontSize: 10,
                                    color: '#212529',
                                    fillColor: '#f2f2f2',
                                    alignment: 'left'
                                };

                                // Set auto column widths
                                let tableColumnCount = doc.content[1].table.body[0].length;
                                let columnWidths = Array(tableColumnCount).fill('auto');
                                doc.content[1].table.widths = columnWidths;

                                // Add table borders and padding using layout
                                doc.content[1].layout = {
                                    hLineWidth: function(i, node) { return 1; },
                                    vLineWidth: function(i, node) { return 1; },
                                    hLineColor: function(i, node) { return '#dee2e6'; },
                                    vLineColor: function(i, node) { return '#dee2e6'; },
                                    paddingLeft: function(i, node) { return 8; },
                                    paddingRight: function(i, node) { return 8; },
                                    paddingTop: function(i, node) { return 6; },
                                    paddingBottom: function(i, node) { return 6; }
                                };

                                // Add page footer with page numbers
                                doc.footer = function(currentPage, pageCount) {
                                    return {
                                        text: 'Page ' + currentPage.toString() + ' of ' + pageCount,
                                        alignment: 'right',
                                        margin: [0, 10, 20, 0],
                                        fontSize: 8,
                                    };
                                };
                            }
                        },
                        {
                            text: '<i class="fas fa-file-csv" title="Export to CSV"></i> <span class="d-none d-md-inline"></span>',
                            className: 'btn btn-info',
                            extend: 'csv',
                            exportOptions: {
                                columns: ':not(:last-child)' // Exclude the last column
                            },
                            bom: true, // Add UTF-8 BOM
                            customize: function (csv) {
                                // Replace unsupported characters
                                return csv.replace(/√/g, '✔️'); // Replace '√' with 'Checked'
                            }
                        },
                        {
                            extend: 'copy',
                            text: '<i class="fas fa-copy" title="Copy Table Data"></i> <span class="d-none d-md-inline"></span>',
                            className: 'btn btn-primary',
                            exportOptions: {
                                columns: ':not(:last-child)' // Exclude the last column
                            }
                        },
                        {
                            extend: 'colvis',
                            text: '<i class="fas fa-eye"></i> <span class="d-none d-md-inline"></span>',
                            className: 'btn btn-warning',
                        }
                    ],
                },
                bottomStart: {
                    pageLength: 10,
                }
            },
            order: [2, 'asc'],
            lengthMenu: [10, 25, 50, 100],
        });

        const csrfToken = $('meta[name="csrf-token"]').attr("content");
        $.ajaxSetup({ headers: { "X-CSRF-TOKEN": csrfToken } });
        const beneficiaries = @json($beneficiaries);
        let rowCount = beneficiaries.length;

        loadJenisKelompok();
        loadKelompokMarjinal();
        setLocationForm();
        loadActivity();

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
            formData.keterangan = escapeHtml($("#keterangan").val());

            formData.activity_ids = $("#activitySelect").val() || [];
            formData.is_non_activity = $("#is_non_activity").is(":checked");

            $.ajax({
                url: '{{ route('beneficiary.store.individual') }}',
                method: 'POST',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                beforeSend: function() {
                    Toast.fire({
                        icon: "info",
                        title: "Saving...",
                        position: "bottom-end",
                        timer: 3000,
                        timerProgressBar: true,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    });
                },
                success: function(response) {
                    redrawTable();
                    $("#dataForm")[0].reset();
                    $("#ModalTambahPeserta").modal("hide");
                    Swal.fire({
                        title: "Success",
                        text: "Beneficiary added!",
                        icon: "success",
                        timer: 1500,
                        showConfirmButton: false,
                        timerProgressBar: true,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    });
                    resetFormAdd();
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
            const url = "{{ route('beneficiary.get.individual', ':id') }}".replace(':id', beneficiaryId);
            console.log("beneficiaryId", beneficiaryId, url);
            // Fetch the latest data for the selected beneficiary
            $.ajax({
                url: url, // Replace with your endpoint to fetch a single beneficiary
                method: "GET",
                success: function(response) {
                    const beneficiary = response[0]; // Assuming the server returns the beneficiary in response.data

                    // console.log("isi data dari", beneficiary);

                    // Populate the modal with the latest data
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

                    addOptionAndTriggerChange("#provinsi_id_edit", beneficiary.dusun?.desa?.kecamatan?.kabupaten?.provinsi?.nama || '-', beneficiary.dusun.desa.kecamatan.kabupaten.provinsi.id || '');
                    addOptionAndTriggerChange("#kabupaten_id_edit", beneficiary.dusun?.desa?.kecamatan?.kabupaten?.nama || '-', beneficiary.dusun.desa.kecamatan.kabupaten.id || '');
                    addOptionAndTriggerChange("#kecamatan_id_edit", beneficiary.dusun?.desa?.kecamatan?.nama || '-', beneficiary.dusun.desa.kecamatan.id || '');
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
                },
                error: function(xhr) {
                    console.error("Failed to fetch beneficiary data:", xhr.responseText);
                }
            });
        }

        function updateRow() {
            const form = document.getElementById("editDataForm");
            const beneficiaryId = $("#editRowId").val();
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Start with data from serializeArray (gets standard inputs like nama, usia, rt, rw, etc.)
            const formData = $("#editDataForm").serializeArray().reduce((obj, item) => {
                // Handle potential duplicate names (though less likely in an edit form)
                if (obj[item.name]) {
                    if (!Array.isArray(obj[item.name])) {
                        obj[item.name] = [obj[item.name]];
                    }
                    obj[item.name].push(item.value);
                } else {
                    obj[item.name] = item.value;
                }
                return obj;
            }, {});

            // --- Manually Add Fields Not Reliably Caught by serializeArray ---

            // 1. Checkbox
            formData.is_non_activity = $("#edit_is_non_activity").is(":checked");

            // 2. Select2 Multi-Select Values
            formData.id = $("#editRowId").val() || [];
            formData.nama = $("#editNama").val() || [];
            formData.umur = $("#editUsia").val() || [];
            formData.kelompok_rentan = $("#editKelompokRentan").val() || [];
            formData.jenis_kelompok = $("#editJenisKelompok").val() || [];

            formData.no_telp = $("#editNoTelp").val() || '';
            formData.jenis_kelamin = $("#editGender").val() || 'lainnya';
            formData.keterangan = escapeHtml($("#keterangan_edit").val()) || '';
            formData.rt = $("#editRt").val() || '';
            formData.rw = $("#editRwBanjar").val() || '';

            formData.activity_ids = $("#activitySelectEdit").val() || [];

            // 3. Location Select2 Values (Crucial Addition!)
            formData.provinsi_id = $("#provinsi_id_edit").val() || null; // Send null if empty
            formData.kabupaten_id = $("#kabupaten_id_edit").val() || null;
            formData.kecamatan_id = $("#kecamatan_id_edit").val() || null;
            formData.desa_id = $("#desa_id_edit").val() || null;
            formData.dusun_id = $("#dusun_id_edit").val() || null;
            formData.is_non_activity = $("#edit_is_non_activity").is(":checked");

            // 4. Other Required IDs (like program_id, user_id if needed)
            formData.program_id = '{{ $program->id }}';
            formData.user_id = '{{ Auth::id() }}'; // Uncomment if needed

            // --- AJAX Call ---
            const id = beneficiaryId; // Use the beneficiaryId directly
            if (!id) {
                 Swal.fire("Error", "Beneficiary ID is missing.", "error");
                 return; // Prevent AJAX call if ID is missing
            }
            const url = "{{ route('beneficiary.edit.individual', ':id') }}".replace(':id', id);

            $.ajax({
                url: url,
                method: "PUT", // Use PUT for updates
                data: JSON.stringify(formData), // Send the manually constructed object as JSON
                contentType: "application/json", // Tell the server we're sending JSON
                beforeSend: function() {
                    Toast.fire({
                        icon: "info",
                        title: "Updating...",
                        position: "bottom-end",
                        timer: 3000,
                        timerProgressBar: true,
                    });
                },
                success: function(response) {
                    const index = beneficiaries.findIndex(b => b.id === beneficiaryId);
                    if (index !== -1) {
                        beneficiaries[index] = response.data; // Assuming the server returns the updated beneficiary in response.data
                    }
                    Swal.fire({
                        title: "Success",
                        text: response.message || "Beneficiary updated!",
                        icon: "success",
                        timer: 1500,
                        timerProgressBar: true,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    })
                    redrawTable(); // Function to refresh table data
                    resetFormEdit();
                    $("#editDataModal").modal("hide");
                },
                error: function(xhr) {
                    // Improved error handling
                    let errorMsg = "Failed to update beneficiary.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                        // Optional: Append validation errors
                        if (xhr.responseJSON.errors) {
                            errorMsg += "<br><br>Details:<br>" + Object.values(xhr.responseJSON.errors).flat().join("<br>");
                        }
                    }
                    Swal.fire("Error", errorMsg, "error");
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
                        beforeSend: function() {
                            Toast.fire({
                                icon: "info",
                                title: "Deleting...",
                                position: "bottom-end",
                                timer: 3000,
                                timerProgressBar: true,
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                },
                            });
                        },
                        success: function() {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Beneficiary removed.",
                                icon: "success",
                                timer: 1500,
                                timerProgressBar: true,
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                },
                            }).then(() => {
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
            // console.info("form reset, now opening edit modal")
            setTimeout(() => {
                editRow(this);
            }, 250);
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


@include('tr.beneficiary.js.search')

@stack('basic_tab_js')

@include('tr.beneficiary.tabs.bene-modal')

@include('api.master.dusun')
@include('api.master.jenis-kelompok-instansi')

@endpush
