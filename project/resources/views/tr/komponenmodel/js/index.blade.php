<script>
        $(document).ready(function () {
        $('#komponenmodelTable').DataTable({
            serverSide: true,
            processing: true,
            // responsive: true,
            ajax: '{{ route('api.komodel.datatable') }}',
            columns: [
    {
        data: 'DT_RowIndex',
        width: "5%",
        name: 'DT_RowIndex',
        className: "text-center",
        title: '{{ __('No.') }}',
        orderable: false,
        searchable: false,
    },
    { data: 'program_name', name: 'program_name', title: '{{ __('cruds.program.nama') }}' },
    { data: 'sektor', name: 'sektor', title: '{{ __('cruds.komponenmodel.sektor') }}' },
    { data: 'komponen_model', name: 'komponen_model', title: '{{ __('cruds.komponenmodel.nama') }}' },
    { data: 'totaljumlah', name: 'totaljumlah', title: '{{ __('cruds.komponenmodel.jumlah') }}' },
    // { data: 'satuan', name: 'satuan', title: '{{ __('cruds.komponenmodel.satuan') }}' },
    { data: 'provinsi', name: 'provinsi', title: '{{ __('cruds.komponenmodel.provinsi') }}' },
    { data: 'kabupaten', name: 'kabupaten', title: '{{ __('cruds.komponenmodel.kabupaten') }}' },
    { data: 'kecamatan', name: 'kecamatan', title: '{{ __('cruds.komponenmodel.kecamatan') }}' },
    { data: 'desa', name: 'desa', title: '{{ __('cruds.komponenmodel.desa') }}' },
    { data: 'dusun', name: 'dusun', title: '{{ __('cruds.komponenmodel.dusun') }}' },
    { data: 'action', name: 'action', title: '{{ __('global.action') }}', orderable: false, searchable: false, className: 'text-center' },
],
            layout: {
                topStart: {
                    buttons: [{
                            text: '<i class="fas fa-print"></i> <span class="d-none d-md-inline"></span>',
                            className: 'btn btn-secondary',
                            extend: 'print',
                            exportOptions: {
                                columns: [0, 1, 2, 3] // Ensure these indices match your visible columns
                            }
                        },
                        {
                            text: '<i class="fas fa-file-excel"></i> <span class="d-none d-md-inline"></span>',
                            className: 'btn btn-success',
                            extend: 'excel',
                            exportOptions: {
                                columns: [0, 1, 2, 3]
                            }
                        },
                        {
                            text: '<i class="fas fa-file-pdf"></i> <span class="d-none d-md-inline"></span>',
                            className: 'btn btn-danger',
                            extend: 'pdf',
                            exportOptions: {
                                columns: [0, 1, 2, 3]
                            }
                        },
                        {
                            extend: 'copy',
                            text: '<i class="fas fa-copy"></i> <span class="d-none d-md-inline"></span>',
                            className: 'btn btn-info',
                            exportOptions: {
                                columns: [0, 1, 2, 3]
                            }
                        },
                        {
                            extend: 'colvis',
                            text: '<i class="fas fa-eye"></i> <span class="d-none d-md-inline"></span>',
                            className: 'btn btn-warning',
                            exportOptions: {
                                columns: [0, 1, 2, 3]
                            }
                        },
                    ],
                },
                bottomStart: {
                    pageLength: 10,
                }
            },
            order: [1, 'asc'],
            lengthMenu: [10,25,50,100],

        });
    });

    $(document).ready(function () {
        $('#komponenmodelTable tbody').on('click', '.edit-komponen-model-btn', function () {
            var id = $(this).data('komponen-model-id');
            window.location.href = '{{ route('komodel.edit', ['id' => ':id']) }}'
                    .replace(':id', id);
        });
    });
</script>
