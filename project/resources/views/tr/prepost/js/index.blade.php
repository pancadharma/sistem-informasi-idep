<script>
        $(document).ready(function () {
        $('#prepostTable').DataTable({
            serverSide: true,
            processing: true,
            // responsive: true,
            ajax: '{{ route('api.komodel.datatable') }}',
            columns: [
                {
                    data: 'DT_RowIndex',
                    width: "5%",
                    name: 'No.',
                    className: "text-center",
                    title: '{{ __('No.') }}',
                    orderable: false,
                    searchable: true,
                },
                // {data: 'kode', name: 'kode', title: '{{ __('cruds.program.kode') }}'},
                {data: 'nama', name: 'nama', title: '{{ __('cruds.program.nama') }}'},
                {data: 'dusun.nama', name: 'dusun.nama', title: '{{ __('cruds.prepost.nama') }}'},
                {data: 'tanggalmulai', name: 'tanggalmulai', title: '{{ __('cruds.prepost.sektor') }}'},
                {data: 'tanggalselesai', name: 'tanggalselesai', title: '{{ __('cruds.prepost.dusun') }}'},
                {data: 'duration_in_days', name: 'duration_in_days', title: '{{ __('cruds.prepost.desa') }}'},
                {data: 'tempat', name: 'tempat', title: '{{ __('cruds.prepost.kecamatan') }}'},
                {data: 'fase', name: 'tempat', title: '{{ __('cruds.prepost.kabupaten') }}'},
                {data: 'status', name: 'status', title: '{{ __('cruds.prepost.provinsi') }}'},
                {data: 'status', name: 'status', title: '{{ __('cruds.prepost.jumlah') }}'},
                {data: 'status', name: 'status', title: '{{ __('cruds.prepost.satuan') }}'},
                {data: 'action', name: 'action', title: '{{ __('global.action') }}', orderable: false, searchable: false, className: 'text-center'},
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
</script>
