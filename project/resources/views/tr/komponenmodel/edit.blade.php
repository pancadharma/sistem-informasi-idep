@extends('layouts.app')

@section('subtitle', __('cruds.komponenmodel.edit'))
@section('content_header_title') <strong>{{ __('cruds.komponenmodel.edit') }}</strong>  @endsection
@section('sub_breadcumb')<a href="{{ route('komodel.index') }}" title="{{ __('cruds.komponenmodel.edit') }}"> {{ __('cruds.komponenmodel.edit') }} </a> @endsection
@section('sub_sub_breadcumb') / <span title="Current Page {{ __('cruds.komponenmodel.edit') }}">{{ __('cruds.komponenmodel.edit') }}</span> @endsection

@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">{{ __('global.loading') }}...</h4>
@endsection

@section('content_body')
    <form id="editKOMODEL" method="POST" class="needs-validation" data-toggle="validator" autocomplete="off" enctype="multipart/form-data">
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
                                    {{ __('cruds.komponenmodel.label') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="details-kegiatan-tabContent">
                            <div class="tab-pane fade show active" id="tab-beneficiaries" role="tabpanel" aria-labelledby="beneficiaries-tab">
                                @include('tr.komponenmodel.tabs.komodel-edit')

                                nanti lanjut buat tabedit js ajax dll
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
            z-index: 1030;
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
    $(document).ready(function() {
        $('#sektor_id').select2({
            allowClear: true,
            placeholder: "Pilih Sektor",
            ajax: {
                url: "{{ route('api.komodel.sektor') }}",
                dataType: "json",
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term, // kirim parameter pencarian
                        page: params.page || 1 // pagination
                    };
                },
                processResults: function(response) {
                    // console.log("Data dari API:", response); // Debugging

                    return {
                        results: response.results.map(function(item) {
                            return {
                                id: item.id,
                                text: item.nama
                            };
                        }),
                        pagination: {
                            more: response.pagination.more
                        }
                    };
                },
                cache: false
            }
        });
    });

    $(document).ready(function() {
        $('#model_id').select2({
            allowClear: true,
            placeholder: "Pilih Model",
            ajax: {
                url: "{{ route('api.komodel.model') }}", // Ganti dengan route API yang benar
                dataType: "json",
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term, // kirim parameter pencarian
                        page: params.page || 1 // pagination
                    };
                },
                processResults: function(response) {
                    // console.log("Data Model dari API:", response); // Debugging

                    return {
                        results: response.results.map(function(item) {
                            return {
                                id: item.id,
                                text: item.nama // Sesuaikan dengan field yang benar
                            };
                        }),
                        pagination: {
                            more: response.pagination.more
                        }
                    };
                },
                cache: false
            }
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

    function buatSelect2(elementId, dropdownParentId, placeholder, url) {
        $(elementId).select2({
            placeholder: placeholder,
            dropdownParent: $(dropdownParentId),
            width: "100%",
            allowClear: true,
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
                cache: false,
                // error: (xhr, status, error) => {
                //     console.error(`Error fetching data for ${elementId}:`, error);
                //     Swal.fire({
                //         icon: 'error',
                //         title: 'Error',
                //         text: 'Failed to fetch data. Please try again later.'
                //     });
                //     return [];
                // }
            }
        });
    }

    function loadSatuan() {
        const placeholder = "{{ __('global.pleaseSelect') . ' ' . __('cruds.satuan.title') }}";
        const apiRoute = '{{ route('api.komodel.satuan') }}';
        buatSelect2('#satuan_id', '#ModalTambah', placeholder, apiRoute);
        buatSelect2('#editsatuan_id', '#editDataModal', placeholder, apiRoute);
    }
    
    function initializeLocationSelects(provinsiSelector, kabupatenSelector, kecamatanSelector, desaSelector, dusunSelector, dropdownParent) {
            // Fungsi bantu reset + disable
            const resetSelect = selector => {
                $(selector).val(null).empty().trigger('change').prop('disabled', true);
            };

            // Disable semua jika provinsi belum dipilih
            if (!$(provinsiSelector).val()) {
                resetSelect(kabupatenSelector);
                resetSelect(kecamatanSelector);
                resetSelect(desaSelector);
                resetSelect(dusunSelector);
            }

            $(provinsiSelector).select2({
                ajax: {
                    url: "{{ route('api.komodel.prov') }}",
                    dataType: 'json',
                    delay: 250,
                    cache: true,
                    data: params => ({
                        search: params.term,
                        page: params.page || 1
                    }),
                    processResults: data => ({
                        results: data.results,
                        pagination: data.pagination
                    }),
                },
                dropdownParent: dropdownParent,
                placeholder: "{{ __('global.selectProv') }}"
            }).on('change', function () {
                resetSelect(kabupatenSelector);
                resetSelect(kecamatanSelector);
                resetSelect(desaSelector);
                resetSelect(dusunSelector);

                if ($(this).val()) {
                    $(kabupatenSelector).prop('disabled', false);
                }
            });

            $(kabupatenSelector).select2({
                ajax: {
                    url: () => "{{ route('api.komodel.kab', ['id' => ':id']) }}".replace(':id', $(provinsiSelector).val()),
                    dataType: 'json',
                    delay: 250,
                    cache: true,
                    data: params => ({
                        provinsi_id: $(provinsiSelector).val(),
                        search: params.term,
                        page: params.page || 1
                    }),
                    processResults: data => ({
                        results: data.results,
                        pagination: data.pagination
                    }),
                },
                dropdownParent: dropdownParent,
                placeholder: "{{ __('global.selectKab') }}"
            }).on('change', function () {
                resetSelect(kecamatanSelector);
                resetSelect(desaSelector);
                resetSelect(dusunSelector);

                if ($(this).val()) {
                    $(kecamatanSelector).prop('disabled', false);
                }
            });

            $(kecamatanSelector).select2({
                ajax: {
                    url: () => "{{ route('api.komodel.kec', ['id' => ':id']) }}".replace(':id', $(kabupatenSelector).val()),
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
                    }),
                },
                dropdownParent: dropdownParent,
                placeholder: "{{ __('global.selectKec') }}"
            }).on('change', function () {
                resetSelect(desaSelector);
                resetSelect(dusunSelector);

                if ($(this).val()) {
                    $(desaSelector).prop('disabled', false);
                }
            });

            $(desaSelector).select2({
                ajax: {
                    url: () => "{{ route('api.komodel.desa', ['id' => ':id']) }}".replace(':id', $(kecamatanSelector).val()),
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
                    }),
                },
                dropdownParent: dropdownParent,
                placeholder: '{{ __('global.pleaseSelect') . ' ' . __('cruds.desa.title') }}',
            }).on('change', function () {
                resetSelect(dusunSelector);

                if ($(this).val()) {
                    $(dusunSelector).prop('disabled', false);
                }
            });

            $(dusunSelector).select2({
                ajax: {
                    url: () => "{{ route('api.komodel.dusun', ['id' => ':id']) }}".replace(':id', $(desaSelector).val()),
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
                    }),
                },
                dropdownParent: dropdownParent,
                placeholder: '{{ __('global.pleaseSelect') . ' ' . __('cruds.dusun.title') }}',
            });
        }


        function resetFormAdd() {
            // Reset input biasa
            $("#dataForm")[0].reset();

            // Reset semua Select2 (harus setelah .reset())
            const selectsToReset = [
                "#pilihprovinsi_id",
                "#pilihkabupaten_id",
                "#pilihkecamatan_id",
                "#pilihdesa_id",
                "#pilihdusun_id",
                "#satuan_id"
            ];

            selectsToReset.forEach(id => {
                $(id).val(null).empty().trigger("change");
            });

            // Reset input text dan angka (optional karena sudah pakai .reset())
            $("input[name='long']").val("");
            $("input[name='lat']").val("");
            $("input[name='jumlah']").val("");

            // Tutup modal
            $("#ModalTambah").modal("hide");
        }

        function resetFormEdit() {
            $("#editDataForm")[0].reset();

            // Reset Select2 dropdowns
            $("#editprovinsi_id").val(null).empty().trigger("change");
            $("#editkabupaten_id").val(null).empty().trigger("change");
            $("#editkecamatan_id").val(null).empty().trigger("change");
            $("#editdesa_id").val(null).empty().trigger("change");
            $("#editdusun_id").val(null).empty().trigger("change");
            $("#editsatuan_id").val(null).empty().trigger("change");

            // Reset input text dan angka
            $("input[name='long']").val("");
            $("input[name='lat']").val("");
            $("input[name='jumlah']").val("");

            // Sembunyikan modal setelah reset
            $("#editDataModal").modal("hide");
        }
    
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                layout: {
                    topStart: {
                        buttons: [
                            // {
                            //     text: '<i class="fas fa-print" title="Print Table Data"></i> <span class="d-none d-md-inline"></span>',
                            //     className: 'btn btn-secondary',
                            //     extend: 'print',
                            //     exportOptions: {
                            //         columns: ':not(:last-child)' // Exclude the last column
                            //     }
                            // },
                            {
                                text: '<i class="fas fa-file-excel" title="Export to EXCEL"></i> <span class="d-none d-md-inline"></span>',
                                className: 'btn btn-success',
                                extend: 'excel',
                                exportOptions: {
                                    columns: ':not(:last-child)' // Exclude the last column
                                }
                            },
                            // {
                            //     text: '<i class="fas fa-file-pdf" title="Export to PDF"></i> <span class="d-none d-md-inline"></span>',
                            //     className: 'btn btn-danger',
                            //     extend: 'pdfHtml5',
                            //     exportOptions: {
                            //         columns: ':not(:last-child)', // Exclude the last column
                            //         rows: function (idx, data, node) {
                            //             return true; // Include all rows
                            //         },
                            //         stripHtml: false,
                            //         format: {
                            //             body: function (data, row, column, node) {
                            //                 // Handle checkboxes safely without using jQuery find
                            //                 if (data.indexOf('type="checkbox"') > -1) {
                            //                     // Simple string-based check for checked attribute
                            //                     return data.indexOf('checked') > -1 ? '✓' : '☐';
                            //                 }
                            //                 // Clean HTML but preserve important formatting
                            //                 return data.replace(/<(?!\s*br\s*\/?)[^>]+>/gi, '');
                            //             },
                            //             header: function (data, columnIdx) {
                            //                 // Clean HTML from headers but preserve text
                            //                 return data.replace(/<(?!\s*br\s*\/?)[^>]+>/gi, '');
                            //             }
                            //         }
                            //     },
                            //     orientation: 'landscape',
                            //     pageSize: 'A3',
                            //     title: 'Table Export',
                            //     customize: function (doc) {
                            //         // Define available fonts - using standard pdf fonts for better compatibility
                            //         // Available built-in fonts in pdfmake: helvetica/times/courier
                            //         pdfMake.fonts = {
                            //             Roboto: {
                            //                 normal: 'Roboto-Regular.ttf',
                            //                 bold: 'Roboto-Medium.ttf',
                            //                 italics: 'Roboto-Italic.ttf',
                            //                 bolditalics: 'Roboto-MediumItalic.ttf'
                            //             },
                            //             // Fallback to standard PDF fonts if custom ones not available
                            //             times: {
                            //                 normal: 'Times-Roman',
                            //                 bold: 'Times-Bold',
                            //                 italics: 'Times-Italic',
                            //                 bolditalics: 'Times-BoldItalic'
                            //             },
                            //             courier: {
                            //                 normal: 'Courier',
                            //                 bold: 'Courier-Bold',
                            //                 italics: 'Courier-Oblique',
                            //                 bolditalics: 'Courier-BoldOblique'
                            //             }
                            //         };
                            //         // Style headers like Bootstrap tables
                            //         doc.styles.tableHeader = {
                            //             fontSize: 11,
                            //             bold: true,
                            //             color: '#212529',
                            //             fillColor: '#f8f9fa',
                            //             alignment: 'left'
                            //         };
                            //         doc.styles.tableBodyEven = {

                            //             fontSize: 10,
                            //             color: '#212529',
                            //             alignment: 'left'
                            //         };

                            //         // Style odd rows for zebra striping
                            //         doc.styles.tableBodyOdd = {
                            //             fontSize: 10,
                            //             color: '#212529',
                            //             fillColor: '#f2f2f2',
                            //             alignment: 'left'
                            //         };

                            //         // Set auto column widths
                            //         let tableColumnCount = doc.content[1].table.body[0].length;
                            //         let columnWidths = Array(tableColumnCount).fill('auto');
                            //         doc.content[1].table.widths = columnWidths;

                            //         // Add table borders and padding using layout
                            //         doc.content[1].layout = {
                            //             hLineWidth: function(i, node) { return 1; },
                            //             vLineWidth: function(i, node) { return 1; },
                            //             hLineColor: function(i, node) { return '#dee2e6'; },
                            //             vLineColor: function(i, node) { return '#dee2e6'; },
                            //             paddingLeft: function(i, node) { return 8; },
                            //             paddingRight: function(i, node) { return 8; },
                            //             paddingTop: function(i, node) { return 6; },
                            //             paddingBottom: function(i, node) { return 6; }
                            //         };

                            //         // Add page footer with page numbers
                            //         doc.footer = function(currentPage, pageCount) {
                            //             return {
                            //                 text: 'Page ' + currentPage.toString() + ' of ' + pageCount,
                            //                 alignment: 'right',
                            //                 margin: [0, 10, 20, 0],
                            //                 fontSize: 8,
                            //             };
                            //         };
                            //     }
                            // },
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
                            // {
                            //     extend: 'copy',
                            //     text: '<i class="fas fa-copy" title="Copy Table Data"></i> <span class="d-none d-md-inline"></span>',
                            //     className: 'btn btn-primary',
                            //     exportOptions: {
                            //         columns: ':not(:last-child)' // Exclude the last column
                            //     }
                            // },
                            {
                                extend: 'colvis',
                                text: '<i class="fas fa-eye"></i> <span class="d-none d-md-inline"></span>',
                                className: 'btn btn-warning',
                            }
                        ],
                    },
                    bottomStart: {
                        pageLength: 25,
                    }
                },
                order: [2, 'asc'],
                lengthMenu: [10, 25, 50, 100],
            });

            const csrfToken = $('meta[name="csrf-token"]').attr("content");
            $.ajaxSetup({ headers: { "X-CSRF-TOKEN": csrfToken } });
            const lokasi = @json($lokasiData);
            let rowCount = lokasi.length;

            initializeLocationSelects();
            loadSatuan();
            
            // Initialize for #tambahData
            initializeLocationSelects(
                '#pilihprovinsi_id',
                '#pilihkabupaten_id',
                '#pilihkecamatan_id',
                '#pilihdesa_id',
                '#pilihdusun_id',
                $('#ModalTambah')
            );

            // Initialize for #editDataModal
            initializeLocationSelects(
                '#editprovinsi_id',
                '#editkabupaten_id',
                '#editkecamatan_id',
                '#editdesa_id',
                '#editdusun_id',
                $('#editDataModal')
            );

            function addRow() {
                const form = document.getElementById("dataForm");
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                const formData = {
                    mealskomponenmodel_id: '{{ $komodel->id }}', // ID dari data yang sedang diedit
                    provinsi_id: $("#pilihprovinsi_id").val(),
                    kabupaten_id: $("#pilihkabupaten_id").val(),
                    kecamatan_id: $("#pilihkecamatan_id").val(),
                    desa_id: $("#pilihdesa_id").val(),
                    dusun_id: $("#pilihdusun_id").val(),
                    long: $("#long").val(),
                    lat: $("#lat").val(),
                    jumlah: $("#jumlah").val(),
                    satuan_id: $("#satuan_id").val(),
                };

                $.ajax({
                    url: '{{ route('komodel.store.lokindi') }}',
                    method: 'POST',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    beforeSend: function () {
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
                    success: function (response) {
                        redrawTable(); // pastikan fungsi ini reload ulang data lokasi dari backend
                        $("#dataForm")[0].reset();
                        $("#ModalTambah").modal("hide");
                        Swal.fire({
                            title: "Success",
                            text: "Data lokasi berhasil ditambahkan.",
                            icon: "success",
                            timer: 1500,
                            showConfirmButton: false,
                            timerProgressBar: true,
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                        });
                        resetFormAdd(); // kalau ada fungsi reset tambahan
                    },
                    error: function (xhr) {
                        Swal.fire("Error", xhr.responseJSON?.message || "Gagal menyimpan data lokasi.", "error");
                    }
                });
            }

            function redrawTable() {
                $.ajax({
                    url: '{{ route('komodel.edit', $komodel->id) }}', // Assumes this returns the edit view with updated table
                    method: 'GET',
                    success: function(response) {
                        const newTableBody = $(response).find('#tableBody').html(); // Extract the updated table body
                        $('#tableBody').html(newTableBody); // Replace the existing table body
                        rowCount = $('#tableBody tr').length; // Update row count
                        // location.reload();
                    },
                    
                    error: function(xhr) {
                        Swal.fire("Error", "Failed to reload table data.", "error");
                    }
                });
            }

            // edit baris lokasi
            $('#tableBody').on('click', '.edit-btn', function (e) {
                e.preventDefault();
                const lokasiId = $(this).data('id');
                const url = "{{ route('komodel.get.lokindi', ':id') }}".replace(':id', lokasiId);

                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function (lokasi) {
                        $('#editRowId').val(lokasi.id);

                        // Populate dropdown dengan text/value yang sesuai
                        $('#editprovinsi_id').empty().append(new Option(lokasi.provinsi?.nama, lokasi.provinsi_id, true, true)).trigger('change');
                        $('#editkabupaten_id').empty().append(new Option(lokasi.kabupaten?.nama, lokasi.kabupaten_id, true, true)).trigger('change');
                        $('#editkecamatan_id').empty().append(new Option(lokasi.kecamatan?.nama, lokasi.kecamatan_id, true, true)).trigger('change');
                        $('#editdesa_id').empty().append(new Option(lokasi.desa?.nama, lokasi.desa_id, true, true)).trigger('change');
                        $('#editdusun_id').empty().append(new Option(lokasi.dusun?.nama, lokasi.dusun_id, true, true)).trigger('change');
                        $('#editsatuan_id').empty().append(new Option(lokasi.satuan?.nama, lokasi.satuan_id, true, true)).trigger('change');

                        // Field input lainnya
                        $('#editlat').val(lokasi.lat);
                        $('#editlong').val(lokasi.long);
                        $('#editjumlah').val(lokasi.jumlah);

                        // Tampilkan modal
                        $('#editDataModal').modal('show');
                    },
                    error: function () {
                        Swal.fire("Gagal", "Tidak bisa memuat data lokasi.", "error");
                    }
                });
            });

            // simpan hasil edit baris lokasi
            $('#updateDataBtn').on('click', function (e) {
                e.preventDefault();
                const id = $('#editRowId').val();
                const formData = $('#editDataForm').serialize();
                const url = "{{ route('komodel.update.lokindi', ':id') }}".replace(':id', id);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        $('#editDataModal').modal('hide');
                        Swal.fire("Sukses", response.message, "success").then(() => location.reload());
                    },
                    error: function (xhr) {
                        const errorMsg = xhr.responseJSON?.message ?? "Terjadi kesalahan.";
                        Swal.fire("Gagal", errorMsg, "error");
                    }
                });
            });

            function calculateTotalJumlah() {
                let total = 0;
                $('.jumlah-cell').each(function () {
                    const val = parseFloat($(this).text().trim());
                    if (!isNaN(val)) {
                        total += val;
                    }
                });
                return total;
            }

            $('#submitDataBtn').on('click', function (e) {
                e.preventDefault();

                const modelId = $('#model_id').val(); // select2, jadi bisa ambil langsung
                const sektorIds = $('#sektor_id').val(); // array karena multiple
                const totaljumlah = calculateTotalJumlah(); // update total jumlah

                if (!modelId || sektorIds.length === 0) {
                    Swal.fire("Peringatan", "Mohon pilih komponen dan minimal satu sektor.", "warning");
                    return;
                }

                $.ajax({
                    url: "{{ route('komodel.update.modelsektor', $komodel->id) }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        model_id: modelId,
                        sektor_ids: sektorIds,
                        totaljumlah: totaljumlah
                    },
                    beforeSend: function () {
                        Swal.fire({
                            title: "Menyimpan...",
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function (response) {
                        Swal.fire("Sukses", response.message, "success").then(() => location.reload());
                    },
                    error: function (xhr) {
                        const msg = xhr.responseJSON?.message || "Terjadi kesalahan.";
                        Swal.fire("Gagal", msg, "error");
                    }
                });
            });

            $('#tableBody').on('click', '.delete-row', function (e) {
                e.preventDefault();
                
                const lokasiId = $(this).data('id');

                Swal.fire({
                    title: "Yakin ingin hapus lokasi ini?",
                    text: "Data yang sudah dihapus tidak bisa dikembalikan.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Ya, hapus!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/komodel/lokasi/${lokasiId}`,
                            method: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            beforeSend: function () {
                                Toast.fire({
                                    icon: "info",
                                    title: "Menghapus...",
                                    position: "bottom-end",
                                    timer: 3000,
                                    timerProgressBar: true,
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    },
                                });
                            },
                            success: function () {
                                Swal.fire({
                                    title: "Berhasil!",
                                    text: "Data lokasi berhasil dihapus.",
                                    icon: "success",
                                    timer: 1500,
                                    timerProgressBar: true,
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    },
                                }).then(() => {
                                    location.reload(); // atau redrawTable()
                                });
                            },
                            error: function (xhr) {
                                Swal.fire("Gagal", xhr.responseJSON?.message || "Tidak dapat menghapus data.", "error");
                            }
                        });
                    }
                });
            });

            














            $('#ModalTambah, #editDataModal').on('shown.bs.modal', function () {
            // kode sync checkbox...
            });

            $("#saveDataBtn").on("click", function(e) {
                e.preventDefault();
                addRow();
            });

            // $("#dataTable tbody").on("click", ".delete-btn", function(e) {
            //     e.preventDefault();
            //     deleteRow(this);
            // });


            
        });




</script>

@stack('basic_tab_js')
@include('tr.komponenmodel.tabs.tambahkomponen-modal')
@include('tr.komponenmodel.tabs.komodel-modal')
@include('api.master.dusun')

@endpush
